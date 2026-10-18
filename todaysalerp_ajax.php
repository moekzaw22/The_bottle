<?php
include('connect.php');
date_default_timezone_set('Asia/Yangon');
header('Content-Type: application/json; charset=utf-8');

$date = date('Y-m-d');
$item = trim((string)($_GET['txtitem'] ?? ''));
$rows = [];

if ($item !== '') {
    $rowStatement = $connect->prepare(
        "SELECT pr.purchaseid, p.Product_id, p.Product_name,
                pr.Buy_Quantity, pr.totalprice, pr.Time
         FROM purchase pr
         JOIN product p ON p.Product_id = pr.Product_id
         WHERE pr.status = 'Confirmed' AND pr.Date = ?
           AND (p.Product_name LIKE ? OR p.Product_code = ? OR p.Product_id = ?)
         ORDER BY pr.Time DESC"
    );
    $nameMatch = '%' . $item . '%';
    $rowStatement->bind_param('ssss', $date, $nameMatch, $item, $item);
} else {
    $rowStatement = $connect->prepare(
        "SELECT pr.purchaseid, p.Product_id, p.Product_name,
                pr.Buy_Quantity, pr.totalprice, pr.Time
         FROM purchase pr
         JOIN product p ON p.Product_id = pr.Product_id
         WHERE pr.status = 'Confirmed' AND pr.Date = ?
         ORDER BY pr.Time DESC"
    );
    $rowStatement->bind_param('s', $date);
}
$rowStatement->execute();
$result = $rowStatement->get_result();
while ($row = $result->fetch_assoc()) {
    $rows[] = [
        'purchaseid' => (int)$row['purchaseid'],
        'product_id' => (string)$row['Product_id'],
        'product_name' => (string)$row['Product_name'],
        'quantity' => (int)$row['Buy_Quantity'],
        'total_price' => number_format((float)$row['totalprice']),
        'time' => (string)$row['Time'],
    ];
}

if ($item !== '') {
    $totalsStatement = $connect->prepare(
        "SELECT SUM(pr.totalprice) AS total_sum, SUM(pr.Buy_Quantity) AS total_qty
         FROM purchase pr
         JOIN product p ON p.Product_id = pr.Product_id
         WHERE pr.status = 'Confirmed' AND pr.Date = ?
           AND (p.Product_name LIKE ? OR p.Product_code = ? OR p.Product_id = ?)"
    );
    $totalsStatement->bind_param('ssss', $date, $nameMatch, $item, $item);
} else {
    $totalsStatement = $connect->prepare(
        "SELECT SUM(totalprice) AS total_sum, SUM(Buy_Quantity) AS total_qty
         FROM purchase
         WHERE Date = ? AND status = 'Confirmed'"
    );
    $totalsStatement->bind_param('s', $date);
}
$totalsStatement->execute();
$totals = $totalsStatement->get_result()->fetch_assoc() ?: [];

echo json_encode([
    'rows' => $rows,
    'total_sum' => number_format((float)($totals['total_sum'] ?? 0)),
    'total_qty' => (int)($totals['total_qty'] ?? 0),
], JSON_INVALID_UTF8_SUBSTITUTE);
