<?php
date_default_timezone_set("Asia/Yangon");
include('navbar.php');
 $connect=mysqli_connect("Localhost","root","","the_bottle_database");
 $date=date('Y-m-d');
 $select="SELECT * FROM purchase pr, product p WHERE p.Product_id=pr.Product_id AND status='Confirmed' AND Date='$date' ORDER BY Time DESC";
 $select_query=mysqli_query($connect,$select);
 $count=mysqli_num_rows($select_query);
 $result= mysqli_query($connect,"SELECT SUM(totalprice) AS totalsumpri FROM purchase WHERE Date='$date' And status='Confirmed'");
 $result1=mysqli_query($connect,"SELECT SUM(Buy_Quantity) As totalqty FROM purchase WHERE Date='$date' And status='Confirmed'");
 $result2= mysqli_query($connect,"SELECT SUM(profit) AS totalsum FROM purchase WHERE Date='$date' And status='Confirmed'");
 $row = mysqli_fetch_assoc($result); 
 $row1=mysqli_fetch_assoc($result1);
 $row2 = mysqli_fetch_assoc($result2);
 $sum = $row['totalsumpri'];
 $totalsale = $row1['totalqty'];
 $profit = $row2['totalsum'];
 $total_sum = 0;
 $Quantity = 0;
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
    <style type="text/css">
  .table tr td {
    padding-left: 10px;
    padding-right: 10px;
    border:1px solid grey;
  }
  .table{
      border-collapse: collapse;
      width:100%;
  }
  .inputbox{
    background: transparent;
    color:white;
    border:none;
    font-size: 20px;
  }
  body{
      font-size:23px;
    margin-right:10px;
    margin-top: 110px;
    font-family: arial;
  background: #0E1818;
  color: white;
  }
  div .input{
    width:200px;
    background: black;
    border:none;
    color:white;
    border-radius: 20px;
    padding:10px;
  }
  button{
    background:transparent;
    padding:6px;
  }
  div .fa-search
  {
    color:white;
    
    
  }
 </style>
</head>
<body>
  <form action="todaysalerp.php" method="POST">
  <br>
  <div class="tdyrpt">
   <table style="margin-bottom:20px;margin-left:10px">
    <tr>
    <td colspan="0">Date / </td>
    <td><input class="inputbox" type="text" value="<?php echo $date ?>" name="txtdate"><td> 
      <td>Total Amout</td>
      <td>=</td>
      <td><span><?php echo number_format($sum) ?> Kyats</span>
      <input type="text" name="txtprice" value="<?php echo $sum ?>" class="inputbox" hidden/></td>
      <input type="text" name="txtprofit" value="<?php echo $profit ?>" hidden/>
</tr>
  <tr>    
     <td colspan=3><?php echo $totalsale ?> Items Sold today</td>
  </tr>
<tr>
  <td><input type="button" onclick="printthis()" value="Print" name=""></td>
</tr>
<div style="position: absolute;right:10px;top:150px;"><input type="text" name="txtitem" placeholder="Item" style="padding:5px" autofocus><input type="submit" name="btnitem">
</div>
  </table>
  </div>
  <script type="text/javascript">
  function printthis(){
    window.location="todysaleprint.php";
  }
</script>
<div id="ptthis">
	<table class="table">
  <tr>
    <td>Purchase ID</td>
    <td>Product Name</td>
    <td>Quantity</td>
    <td>Total price</td>
    <td>Time</td>
  </tr>		
<?php

if (isset($_POST['btnitem'])) {
  $item = $_POST['txtitem'];
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
echo "Total Sum For (",$productname,") x ",$Quantity," is ",$total_sum,",   ";
}
elseif($count < 1) {
  echo "cannot find product with product name = ",$productname;
}
 
}


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
 </table>
 </div>
 </form>

</body>
</html>





