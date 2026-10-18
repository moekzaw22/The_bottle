<?php
include('connect.php');
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['Username'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Admin login required.']);
    exit;
}

$productId = filter_var($_POST['product_id'] ?? null, FILTER_VALIDATE_INT);
$spPriceValue = $_POST['sp_price'] ?? null;
$priceValue = $_POST['price'] ?? null;
$spPrice = is_numeric($spPriceValue) ? (float)$spPriceValue : NAN;
$price = is_numeric($priceValue) ? (float)$priceValue : NAN;

if (!$productId || $productId < 1 || !is_finite($spPrice) || !is_finite($price) || $spPrice < 0 || $price < 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Enter valid prices of zero or more.']);
    exit;
}

$statement = $connect->prepare('UPDATE product SET sp_price = ?, Price = ? WHERE Product_id = ?');
$statement->bind_param('ddi', $spPrice, $price, $productId);
$statement->execute();

if ($statement->affected_rows < 0) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Prices could not be updated.']);
    exit;
}

$check = $connect->prepare('SELECT Product_id FROM product WHERE Product_id = ?');
$check->bind_param('i', $productId);
$check->execute();
if (!$check->get_result()->fetch_assoc()) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Product not found.']);
    exit;
}

echo json_encode([
    'success' => true,
    'product_id' => $productId,
    'sp_price' => $spPrice,
    'price' => $price,
]);
