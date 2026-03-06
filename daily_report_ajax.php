<?php
include('connect.php');

$date = date('Y-m-d');
$item = isset($_GET['txtitem']) ? mysqli_real_escape_string($connect, $_GET['txtitem']) : '';

// Build query
$where = "1";
if ($item) $where .= " AND p.Product_name LIKE '$item%'";

$select = "SELECT * FROM purchase pr
           JOIN product p ON p.Product_id = pr.Product_id
           WHERE pr.status='Confirmed' AND pr.Date='$date' AND $where
           ORDER BY pr.Time DESC";

$select_query = mysqli_query($connect, $select);

// Totals
$result = mysqli_query($connect,"SELECT SUM(totalprice) AS totalsumpri, SUM(Buy_Quantity) AS totalqty 
                                 FROM purchase 
                                 WHERE Date='$date' AND status='Confirmed'"
                                 .($item ? " AND Product_id IN (SELECT Product_id FROM product WHERE Product_name LIKE '$item%')" : "")
);
$row = mysqli_fetch_assoc($result); 
$sum = $row['totalsumpri'] ?? 0;
$totalsale = $row['totalqty'] ?? 0;

if(mysqli_num_rows($select_query) > 0){
    $rows = [];
    while($array = mysqli_fetch_assoc($select_query)){
        $rows[] = [
            'purchaseid'=>$array['purchaseid'],
            'product_id'=>$array['Product_id'],
            'product_name'=>$array['Product_name'],
            'quantity'=>$array['Buy_Quantity'],
            'total_price'=>number_format($array['totalprice']),
            'time'=>$array['Time']
        ];
    }
} else {
    $rows = [];
}

echo json_encode([
    'rows'=>$rows,
    'total_sum'=>number_format($sum),
    'total_qty'=>$totalsale
]);
?>