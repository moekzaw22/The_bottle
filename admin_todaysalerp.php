<?php
include('admin_navbar.php');
include('connect.php');
$date = date('Y-m-d');

// Fetch purchases for today
$select = "
    SELECT p.Product_name, pr.Product_id, pr.purchaseid, pr.totalprice,
           pr.Buy_Quantity, pr.profit, p.sp_price, pr.Time
    FROM purchase pr
    JOIN product p ON p.Product_id = pr.Product_id
    WHERE pr.status='Confirmed' AND pr.Date='$date'
    ORDER BY pr.Time DESC
";
$select_query = mysqli_query($connect, $select);
$count = mysqli_num_rows($select_query);

// Fetch sums
$total = mysqli_fetch_assoc(mysqli_query($connect,"SELECT SUM(totalprice) AS sum FROM purchase WHERE Date='$date' AND status='Confirmed'"));
$items = mysqli_fetch_assoc(mysqli_query($connect,"SELECT SUM(Buy_Quantity) AS total_items FROM purchase WHERE Date='$date' AND status='Confirmed'"));
$profit = mysqli_fetch_assoc(mysqli_query($connect,"SELECT SUM(profit) AS total_profit FROM purchase WHERE Date='$date' AND status='Confirmed'"));

$sum = $total['sum'] ?? 0;
$totalsale = $items['total_items'] ?? 0;
$profitsum = $profit['total_profit'] ?? 0;

// Save daily report
if (isset($_POST['btnsave'])) {
    $amount = mysqli_real_escape_string($connect, $_POST['txtprice']);
    $profit1 = mysqli_real_escape_string($connect, $_POST['txtprofit']);
    $date_post = mysqli_real_escape_string($connect, $_POST['txtdate']);
    $time = date("H:i:s");

    $insert = "INSERT INTO daily_report VALUES ('', '$amount', '$profit1', '$date_post', '$time')";
    if (mysqli_query($connect, $insert)) {
        echo "<script>alert('Daily Report SAVED!');</script>";
    } else {
        echo "<script>alert('Date already exists!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Today's Sales Report</title>
<style>
body { font-family: Arial; margin:0; }
.report-summary { display:flex; gap:50px; margin:20px; }
.report-summary div { font-size:20px; }
.table { width:100%; border-collapse: collapse; margin-top:20px; color:white; }
.table th, .table td { border:1px solid grey;color:black; padding:8px; text-align:center; }
.table th { background:#222;color:white }
.table tr:nth-child(even) { background:lightgrey; }
button { padding:8px 15px; font-size:16px; border:none; border-radius:5px; cursor:pointer; }
.save { background:#28a745; color:white; }
.remove-btn{color:white;background:red;padding:5px;border-radius:5px}
.remove-btn:hover{opacity: 0.4;text-decoration: none;cursor:pointer}
.print { background:#007bff; color:white; }
a { color:#f0f0f0; text-decoration:none; }
a:hover { text-decoration:underline; }
</style>
</head>
<body>

<form method="POST">
<div class="report-summary">
    <div>Date: <strong><?php echo date('d M Y', strtotime($date)) ?></strong></div>
    <div>Total Amount: <strong><?php echo number_format($sum) ?> Kyats</strong></div>
    <div>Items Sold: <strong><?php echo $totalsale ?></strong></div>
    <div>Profit: <strong><?php echo number_format($profitsum) ?> Kyats</strong></div>
</div>

<input type="hidden" name="txtprice" value="<?php echo $sum ?>">
<input type="hidden" name="txtprofit" value="<?php echo $profitsum ?>">
<input type="hidden" name="txtdate" value="<?php echo $date ?>">

<div>
    <!-- <input type="submit" class="save" name="btnsave" value="Save Daily Report" onclick="return confirm('Save to daily report?')"> -->
    <button type="button" class="print" onclick="window.print()">Print Report</button>
</div>
</form>
<form method="GET">
<div style="position: absolute;right:10px;top:150px;"><input type="text" name="txtitem" placeholder="Item" style="padding:5px" autofocus><input type="submit" name="btnitem"> 
</div>
 </form>
<table class="table">
<thead>
<tr>
    <th>Purchase ID</th>
    <th>Product Name</th>
    <th>Quantity</th>
    <th>Total Price</th>
    <th>Spent Price</th>
    <th>Profit</th>
    <th>Time</th>
    <th>Action</th>
</tr>
</thead>
<tbody><?php

if (isset($_GET['btnitem'])) {
  $item = $_GET['txtitem'];
  $select = "SELECT purchaseid, pr.Product_id, Product_name, Time, sp_price, totalprice, Buy_Quantity FROM purchase pr, product p WHERE p.Product_id=pr.Product_id AND status='Confirmed' AND Date='$date' AND p.Product_name LIKE '$item%' ORDER BY Time DESC";
  $select_query1=mysqli_query($connect,$select);
  $count1 = mysqli_num_rows($select_query1);
 
if ($count1 > 0) {
  # code...

  for ($i=0; $i < $count1; $i++) { 
  $array=mysqli_fetch_array($select_query1);
  $purchaseid = $array['purchaseid'];
  $product_id = $array['Product_id'];
  $productname = $array['Product_name'];
  $time = $array['Time'];
  $sp_price = $array['sp_price'];
  $totalprice = $array['totalprice'];
  $buyquantity = $array['Buy_Quantity'];
  $sp_qty = $sp_price * $buyquantity;
  $profit = $totalprice - $sp_qty;
  $total_sum = $total_sum + $totalprice;
  $Quantity = $Quantity + $buyquantity;
  ?>
    <tr>
      <td><?php echo $purchaseid ?></td>
      <td><?php echo $product_id ?> - <?php echo $productname ?></td>
      <td><?php echo $buyquantity ?></td>
      <td><?php echo number_format($totalprice) ?></td>
      <td><?php echo number_format($sp_qty) ?></td>
      <td><?php echo number_format($profit) ?></td>
      <td><?php echo $time ?></td>
      <td><a class="remove-btn" href="itemremovetdysale.php?PID=<?= $row['purchaseid'] ?>">Remove</a></td>
      
      
      
    </tr>
  <?php
}
echo "<br>Total Sum For (",$productname,") x ",$Quantity," is <strong>",number_format($total_sum),"</strong>";
}
elseif($count < 1) {
  echo "cannot find product with product name = ",$productname;
}
 
}//if end


else{
 for ($i=0; $i < $count; $i++) { 
  $array=mysqli_fetch_array($select_query);
  $purchaseid = $array['purchaseid'];
  $product_id = $array['Product_id'];
  $productname = $array['Product_name'];
  $time = $array['Time'];
  $sp_price = $array['sp_price'];
  $totalprice = $array['totalprice'];
  $buyquantity = $array['Buy_Quantity'];
  $sp_qty = $sp_price * $buyquantity;
  $profit = $totalprice - $sp_qty;
  ?>
    <tr>
      <td><?php echo $purchaseid ?></td>
      <td><?php echo $product_id ?> - <?php echo $productname ?></td>
      <td><?php echo $buyquantity ?></td>
      <td><?php echo number_format($totalprice) ?></td>
      <td><?php echo number_format($sp_qty) ?></td>
      <td><?php echo number_format($profit) ?></td>
      <td><?php echo $time ?></td>
      <td><a class="remove-btn" href="itemremovetdysale.php?PID=<?= $row['purchaseid'] ?>">Remove</a></td>
      
    </tr>
  <?php
 }
}
?>
</tbody>
</table>
</body>
</html>