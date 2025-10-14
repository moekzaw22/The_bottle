<?php
date_default_timezone_set("Asia/Yangon");
include('admin_navbar.php');
$connect=mysqli_connect("Localhost","root","","the_bottle_database");
   $date=date('Y-m-d');
 $select="SELECT * FROM purchase pr, product p WHERE p.Product_id=pr.Product_id AND status='Confirmed' AND Date='$date' ORDER BY Time DESC";
 $select_query=mysqli_query($connect,$select);
 $count=mysqli_num_rows($select_query);

$result= mysqli_query($connect,"SELECT SUM(totalprice) AS totalsum FROM purchase WHERE Date='$date' And status='Confirmed'");
$result1=mysqli_query($connect,"SELECT SUM(Buy_Quantity) As totalsum FROM purchase WHERE Date='$date' And status='Confirmed'");
$result2= mysqli_query($connect,"SELECT SUM(profit) AS totalsum FROM purchase WHERE Date='$date' And status='Confirmed'");
$row = mysqli_fetch_assoc($result); 
$row1=mysqli_fetch_assoc($result1);
$row2 = mysqli_fetch_assoc($result2);
$sum = $row['totalsum'];
$totalsale = $row1['totalsum'];
$profit = $row2['totalsum'];
if (isset($_POST['btnsave'])) {
  $amount=$_POST['txtprice'];
  $profit1 =$_POST['txtprofit'];
  $date=$_POST['txtdate'];
  $time=date("H:i:s");
 $insert="INSERT INTO daily_report value('','$amount','$profit1','$date','$time')";
  $insert_query=mysqli_query($connect,$insert);
  if ($insert_query) {
    echo "<script>alert('Daily Report SAVED!')</script>";
  }
  else{
     echo "<script>alert('Date already Exist!')</script>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<title></title>
</head>
<body>
  <style type="text/css"> 
  .table tr td {
    padding-left: 10px;
    padding-right: 10px;
    border:1px solid grey;
  }
  .table{
      border-collapse: collapse;
      width:100%;
      position: absolute;
      top: 230px;
  }
  .inputbox{
    background: transparent;
    color:white;
    border:none;
    font-size: 20px;
  
  }
  .save{
    
   }
  body{
      font-size:23px;
  
    margin-right:10px;
    margin-top: 110px;
    font-family: arial;
  background: #050D23;
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
  .report{
    position: absolute;
    top: 120px;
  }
  .report-1{
    position: absolute;
    top: 150px;
  }
  .report-action{
    position: absolute;
    top: 180px;
  }
  .report-2{
    top: 120px;
    position: absolute;
    left: 320px;
  }
  .report-2-1{
    position: absolute;
    top: 150px;
    left: 320px;
  }
  div .fa-search{color:white;}</style>
  <form action="admin_todaysalerp.php" method="POST">
  <br>
  <div class="report"> 
    Date / <input class="inputbox" type="text" value="<?php echo $date ?>"?>
    </div> 
   <div class="report-1">
     Total Amount = <span><?php echo number_format($sum) ?> Kyats</span>
   </div>
   <div class="report-2">
     <?php echo number_format($totalsale) ?> Items Sold today
   </div>
   <div class="report-2-1">
     Profit - <?php echo number_format($profit) ?> ks
   </div>
    <input type="text" name="txtprice" value="<?php echo $sum ?>" class="inputbox" hidden/></td>
      <input type="text" name="txtprofit" value="<?php echo $profit ?>" hidden/>
    
 <div class="report-action"> <td colspan=2><input type="submit" class="save" name="btnsave" onclick="return confirm('Save to daily report')" value="Save to Daily Report">
    <input type="button" onclick="printthis()" value="Print" name=""></div>

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
    <td>Spent Price</td>
    <td>Profit</td>
    <td>Time</td>
    <td>Action</td>
  </tr>		
<?php
 for ($i=0; $i < $count; $i++) { 
  $array=mysqli_fetch_array($select_query);
  $sp_price = $array['sp_price'];
  $totalprice = $array['totalprice'];
  $buyquantity = $array['Buy_Quantity'];
  $sp_qty = $sp_price * $buyquantity;
  $profit = $totalprice - $sp_qty;
  $time = $array['Time'];
  ?>
    <tr>
      <td><?php echo $array['purchaseid'] ?></td>
      <td><?php echo $array['Product_id'] ?> - <?php echo $array['Product_name'] ?></td>
      <td><?php echo $buyquantity ?></td>
      <td><?php echo number_format($totalprice) ?></td>
      <td><?php echo number_format($sp_qty) ?></td>
      <td><?php echo number_format($profit) ?></td>
      <td><?php echo $time ?></td>
      <td><a href="itemremovetdysale.php?PID=<?php echo $array['purchaseid'] ?>">Remove</a></td>
      
    </tr>
  <?php
 }
 ?>
 </table>
 </div>
 </form>

 
</body>
</html>





