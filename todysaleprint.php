<?php 
date_default_timezone_set("Asia/Yangon");
include('connect.php');
$date=date('Y-m-d');
 $select="SELECT * FROM purchase pr, product p WHERE p.Product_id=pr.Product_id AND status='Confirmed' AND Date='$date' ORDER BY Time ASC";
 $select_query=mysqli_query($connect,$select);
 $count=mysqli_num_rows($select_query);
 ?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<button onclick="print()">Print</button>
	<button onclick="back()">Back</button>
	<script type="text/javascript">
		function back(){
			window.location="todaysalerp.php";
		}
	</script>
	<table class="table">
  	<tr>
    <td>Product Name</td>
    <td>Buy Quantity</td>
    <td>Total price</td>
    <td>Time</td>
  </tr>		
<?php
 for ($i=0; $i < $count; $i++) { 
  $array=mysqli_fetch_array($select_query);
  ?>
    <tr>
      <td><?php echo $array['Product_name'] ?></td>
      <td><?php echo $array['Buy_Quantity'] ?></td>
      <td><?php echo $array['totalprice'] ?></td>  
      <td><?php echo $array['Time'] ?></td>  
    </tr>
  <?php
 }
 ?>
 </table>
</body>
<style type="text/css">
	table{
		width:100%;
		border-collapse: collapse;
	}
	table tr td{
    padding:5px;
		border:1px solid grey;
	}
</style>
</html>