<?php
include('connect.php');
header('Content-Type: application/json; charset=utf-8');

$id = filter_var($_POST['product_id'] ?? null, FILTER_VALIDATE_INT);
$add = filter_var($_POST['add_quantity'] ?? null, FILTER_VALIDATE_INT);
if (!$id || $id < 1 || $add === false || $add < 1) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Enter a whole number greater than zero.']);
    exit;
}

try {
    $connect->begin_transaction();
    $update = $connect->prepare(
        'UPDATE product SET Quantity = GREATEST(COALESCE(Quantity, 0), 0) + ? WHERE Product_id = ?'
    );
    $update->bind_param('ii', $add, $id);
    $update->execute();
    if ($update->affected_rows !== 1) {
        throw new RuntimeException('Product not found.');
    }

    $history = $connect->prepare(
        "INSERT INTO history (Type, Amount, added_to, date_time) VALUES ('Restock', ?, ?, NOW())"
    );
    $history->bind_param('ii', $add, $id);
    $history->execute();

    $select = $connect->prepare('SELECT Quantity FROM product WHERE Product_id = ?');
    $select->bind_param('i', $id);
    $select->execute();
    $row = $select->get_result()->fetch_assoc();
    $connect->commit();

    echo json_encode([
        'success' => true,
        'product_id' => $id,
        'new_quantity' => max(0, (int)$row['Quantity']),
    ]);
} catch (Throwable $error) {
    $connect->rollback();
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Restock could not be completed.']);
}
