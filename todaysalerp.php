<?php
date_default_timezone_set("Asia/Yangon");
include('navbar.php');
 include('connect.php');
 $date=date('Y-m-d');
 $select="SELECT * FROM purchase pr, product p WHERE p.Product_id=pr.Product_id AND status='Confirmed' AND Date='$date' ORDER BY Time DESC";
 $select_query=mysqli_query($connect,$select);
 $count=mysqli_num_rows($select_query);
 $result= mysqli_query($connect,"SELECT SUM(totalprice) AS totalsumpri, SUM(Buy_Quantity) AS totalqty FROM purchase WHERE Date='$date' And status='Confirmed'");
 
 $row = mysqli_fetch_assoc($result); 
 
 $sum = $row['totalsumpri'];
 $totalsale = $row['totalqty'];
 $total_sum = 0;
 $Quantity = 0;
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
    <style type="text/css">
body { font-family: Arial;margin:0; }
.report-summary { display:flex; gap:100px; margin:20px }
.report-summary div { font-size:20px; }
.table { width:100%; border-collapse: collapse; margin-top:20px; }
.table th, .table td { border:0.4px solid grey; padding:8px; text-align:center; }
.table th { background:#343a40;color:white }
.table tr:nth-child(even) { background:#f2f2f2 }
.table tr:hover{background: #e6f2ff}
#btnsubmit { padding:10px; font-size:16px; border:none;background:#005F02;color:white; border-radius:5px; cursor:pointer; }
#btnsubmit:hover {opacity: 0.5}
.save { background:#28a745; color:white; }
.print { padding:5px;font-size:16px;border:none;border-radius:5px;margin:5px;background:#007bff; color:white; }
a { color:#f0f0f0; text-decoration:none; }
</style>
</head>
<body>

<form method="POST">
<div class="report-summary">
    <div>Date: <strong><?php echo date('d M Y', strtotime($date)) ?></strong></div>
    <div>Total Amount: <strong><?php echo number_format($sum) ?> Kyats</strong></div>
    <div>Items Sold: <strong><?php echo $totalsale ?></strong></div>
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
<div style="position: absolute;right:10px;margin:10px;top:14%">
    <input type="text" name="txtitem" placeholder="Item" style="padding:10px" autocomplete="off" autofocus>
    <input type="submit" id="btnsubmit" name="btnitem"> 
</div>
 </form>
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
      <td><?php echo $time ?></td>
      
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
      <td><?php echo $time ?></td>
      
    </tr>
  <?php
 }
}
?>
</tbody>
</table>


</body>
</html>