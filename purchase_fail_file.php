<?php 
session_start();
$sum=0;
$finaltotalprice=0;
include('navbar.php');
 ini_set('display_errors', '0');

date_default_timezone_set("Asia/Yangon");

$connect=mysqli_connect("Localhost","root","","the_bottle_database");
if (isset($_POST['txtquantity'])) {
	sleep(3);
	$product_id= $_POST['txtproductid'];
	$quantity=$_POST['txtquantity'];
	
	
	 $select="SELECT * FROM product WHERE Product_id= $product_id";
	$select_query=mysqli_query($connect,$select);
	$array=mysqli_fetch_array($select_query);
	$finaltotalprice=$_SESSION['finaltotalprice'];
	$price=$array['Price'];
	$totalprice=$price * $quantity;
	$date=date('Y-m-d');
	$time=date("H:i:s");
	$insert="INSERT INTO purchase VALUES('','$product_id','$quantity','$totalprice','$date','$time','Unconfirmed')";
	$insert_query=mysqli_query($connect,$insert);
	$finaltotalprice+=$price * $quantity;
	$_SESSION['finaltotalprice']=$finaltotalprice;
	
}
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<title></title>
 </head>
 <body>
 	<link href="fontawesome/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet">
 	<form action="purchase.php" method="POST">
 		<br><br>	
 		<div class="container-whole">
 		<div class="container-1">	
 			<table>
		 		<tr>
		 			<td>Product Code</td>
		 		</tr>
		 		<tr>
		 			
		 			<td><input type="text" class="inputbox" name="txtproductid"></td>
		 		</tr>
 				<tr>
 					<td class="quantity"><input type="submit" name="txtquantity" value="1">
 					<input type="submit" name="txtquantity" value="2">
 					<input type="submit" name="txtquantity" value="3">
 					<input type="submit" name="txtquantity" value="4">
 					<input type="submit" name="txtquantity" value="5">
					<input type="submit" name="txtquantity" value="6">
</td>
 		</tr>
 		<tr>
 			
 			<td class="quantity"><input type="submit" name="txtquantity" value="7">
 			<input type="submit" name="txtquantity" value="8">
 		<input type="submit" name="txtquantity" value="9">
 	<input type="submit" name="txtquantity" value="10">
 <input type="submit" name="txtquantity" value="11">
<input type="submit" name="txtquantity" value="12"></td>
 			
 		</tr>	
 		
 		
 	</table>
 	</div>
<div class="container-2">
 	<!-- 
 	<a href="destroypurchasesession.php?PID='$purchaseid'">Complete</a> -->
 	<table class="product">
 	<?php 
 $select="SELECT * FROM purchase p,product pr WHERE p.Product_id=pr.Product_id AND p.status='Unconfirmed'";
 $select_query=mysqli_query($connect,$select);
 $count=mysqli_num_rows($select_query);

$result= mysqli_query($connect,"SELECT SUM(totalprice) AS totalsum FROM purchase WHERE status='Unconfirmed'");

$row = mysqli_fetch_assoc($result); 

$sum = $row['totalsum'];
?>

<br>

<?php

 for ($i=0; $i < $count; $i++) { 
 	$array=mysqli_fetch_array($select_query);
 	$purchaseid=$array['purchaseid'];
 	?>
 		<tr>
 			
 			<td><?php echo $array['Product_name'] ?>(<?php echo $array['amount'] ?>)</td>
 			<td><?php echo $array['Buy_Quantity'] ?></td>
 			<td><?php echo $array['totalprice'] ?></td>
 			<td><a class="cancel" href="purchase_cancel.php?PID='<?php echo $purchaseid ?>'">Cancel</a></td>
 			<td></td>
 		</tr>
 		
 	<?php
 }



  ?>
  	<tr>
 			<td  colspan="4"><a class="link" href="purchase_complete.php?PID='<?php echo $purchaseid ?>'">Confirm</a>
 				<a class="link" href="purchase_cancel_all.php?PID='<?php echo $purchaseid ?>'">Cancel</a></td>
 		</tr>
 		<tr>
 			<td colspan="4"><p>Total Price is <?php echo $sum ?></p></td>
 		</tr>
</table>
		
	

	</div>
	</div>
</form>

	</form>
 		
 </body>
 </html>
 <style type="text/css">
 	
 	.container-whole{
 		display:flex;
 		justify-content: center;

 	}
 	.product tr td{
 		padding:20px;
 	}
 	body{
 		margin-top: 100px;
 		font-family: arial;
	background: #0E1818;
	color: white;
 	}
 	.inputbox:focus{
	outline: none;
}
 	.inputbox{
 		padding-left:10px;
 		font-size: 36px;
 		color:green;
 		background:black;
 		border:1px solid white;
 		width:340px;
 		padding:20px;
 	}
 	.inputbox:hover{
 		opacity:0.5;
 	}
 	.quantity input{
 		width:60px;
 		font-size: 20px;
 		background:black;
 		color:white;
 		border:1px solid white;
 		padding:20px;
 		color:green;
 	}
 	.quantity input:hover{
 		background:white;
 	}
 	.link{
 		color: cyan;
 		font-size: 25px;
 		padding-left:10px;
 		text-decoration: none;
 	}
 	.link:hover{
 		color:green;
 	}
 	.cancel{
 		color:red;
 		text-decoration: none;
 		font-size:25px;
 	}
 	.cancel:hover{
 		opacity:0.6;
 	}
 </style>