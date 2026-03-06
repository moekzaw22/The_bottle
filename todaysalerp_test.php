<?php
date_default_timezone_set("Asia/Yangon");
include('connect.php');
include('navbar.php');

$date = date('Y-m-d');

// Get item filter safely
$item = isset($_GET['txtitem']) ? mysqli_real_escape_string($connect, $_GET['txtitem']) : '';

// Initialize $where
$where = "1";
if ($item) {
    $where .= " AND p.Product_name LIKE '$item%'";
}

// Fetch purchases
$select = "SELECT * FROM purchase pr
           JOIN product p ON p.Product_id = pr.Product_id
           WHERE pr.status='Confirmed' AND pr.Date='$date' AND $where
           ORDER BY pr.Time DESC";
$select_query = mysqli_query($connect, $select);
$count = mysqli_num_rows($select_query);

// Totals
$result = mysqli_query($connect,"SELECT SUM(totalprice) AS totalsumpri, SUM(Buy_Quantity) AS totalqty FROM purchase 
                                 WHERE Date='$date' AND status='Confirmed'".($item ? " AND Product_id IN (SELECT Product_id FROM product WHERE Product_name LIKE '$item%')" : ""));
$row = mysqli_fetch_assoc($result); 
$sum = $row['totalsumpri'] ?? 0;
$totalsale = $row['totalqty'] ?? 0;

// Prepare $message
if ($count > 0) {
    $message = "Total for ";
    if ($item) $message .= "product matching '$item'";
    else $message .= "all products";
    $message .= " = " . number_format($sum) . " Kyats, Items Sold: $totalsale";
} else {
    $message = '';
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Daily Report</title>
<style>
body { font-family: Arial;margin:0; }
.report-summary { display:flex; gap:100px; margin:20px }
.report-summary div { font-size:20px; }
.print { padding:5px;font-size:16px;border:none;border-radius:5px;margin:5px;background:#007bff; color:white; cursor:pointer; }
a { color:#f0f0f0; text-decoration:none; }

</style>
</head>
<body>

<div class="report-summary">
    <div>Date: <strong><?php echo date('d M Y', strtotime($date)) ?></strong></div>
    <div><?php echo $message ?></div>
</div>

<div style="position: absolute;right:10px;margin:10px;top:14%">
<form method="GET">
    <input type="text" id="txtitem" name="txtitem" placeholder="Item" style="padding:10px" autocomplete="off" autofocus value="<?php echo htmlspecialchars($item); ?>">
    <input type="submit" class="btn submit" name="btnitem"> 
</form>
</div>

<button class="print" onclick="window.print()">Print Report</button>

<table class="table">
<thead>
<tr>
    <th>Purchase ID</th>
    <th>Product Name</th>
    <th>Quantity</th>
    <th>Total Price</th>
    <th>Time</th>
</tr>
</thead>
<tbody>
<?php
if ($count > 0) {
    while($array = mysqli_fetch_assoc($select_query)) {
        $purchaseid = $array['purchaseid'];
        $product_id = $array['Product_id'];
        $productname = $array['Product_name'];
        $time = $array['Time'];
        $totalprice = $array['totalprice'];
        $buyquantity = $array['Buy_Quantity'];
        echo "<tr>
                <td>$purchaseid</td>
                <td>$product_id - $productname</td>
                <td>$buyquantity</td>
                <td>".number_format($totalprice)."</td>
                <td>$time</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No sales found for ".($item ? "product matching '$item'" : "today")."</td></tr>";
}
?>
</tbody>
</table>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    var input = document.getElementById("txtitem");
    input.focus();
    input.select(); // selects the whole value
});
</script>
</body>
</html>