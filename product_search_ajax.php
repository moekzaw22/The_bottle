<?php
include('connect.php');
header('Content-Type: application/json; charset=utf-8');

$search = trim((string)($_GET['q'] ?? ''));
$pageSize = 20;
$requestedPage = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT);
$requestedPage = $requestedPage && $requestedPage > 0 ? $requestedPage : 1;

if ($search === '') {
    $countStatement = $connect->prepare('SELECT COUNT(*) AS total FROM product');
    $countStatement->execute();
    $total = (int)$countStatement->get_result()->fetch_assoc()['total'];
    $totalPages = max(1, (int)ceil($total / $pageSize));
    $page = min($requestedPage, $totalPages);
    $offset = ($page - 1) * $pageSize;
    $statement = $connect->prepare(
        "SELECT Product_id, Product_code, Product_name, amount, Quantity, Price, sp_price
         FROM product ORDER BY Product_id DESC LIMIT ? OFFSET ?"
    );
    $statement->bind_param('ii', $pageSize, $offset);
} else {
    $countStatement = $connect->prepare(
        "SELECT COUNT(*) AS total FROM product
         WHERE Product_name LIKE CONCAT('%', ?, '%') OR Product_id = ? OR Product_code = ?"
    );
    $countStatement->bind_param('sss', $search, $search, $search);
    $countStatement->execute();
    $total = (int)$countStatement->get_result()->fetch_assoc()['total'];
    $totalPages = max(1, (int)ceil($total / $pageSize));
    $page = min($requestedPage, $totalPages);
    $offset = ($page - 1) * $pageSize;
    $statement = $connect->prepare(
        "SELECT Product_id, Product_code, Product_name, amount, Quantity, Price, sp_price
         FROM product
         WHERE Product_name LIKE CONCAT('%', ?, '%') OR Product_id = ? OR Product_code = ?
         ORDER BY Product_name ASC LIMIT ? OFFSET ?"
    );
    $statement->bind_param('sssii', $search, $search, $search, $pageSize, $offset);
}

$statement->execute();
$result = $statement->get_result();
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = [
        'id' => (string)$row['Product_id'],
        'code' => (string)$row['Product_code'],
        'name' => (string)$row['Product_name'],
        'amount' => (string)$row['amount'],
        'quantity' => (string)$row['Quantity'],
        'price' => (float)$row['Price'],
        'sp_price' => (float)$row['sp_price'],
    ];
}

echo json_encode([
    'products' => $products,
    'page' => $page,
    'page_size' => $pageSize,
    'total' => $total,
    'total_pages' => $totalPages,
], JSON_INVALID_UTF8_SUBSTITUTE);
