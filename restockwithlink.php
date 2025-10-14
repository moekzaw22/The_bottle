<?php 
include('admin_navbar.php');
$error="";
$error2="";
$connect=mysqli_connect("Localhost","root","","the_bottle_database");
if (isset($_GET['PID'])) {
	$product_id=$_GET['PID'];
	$product_query="SELECT * from product Where Product_id='$product_id'";

	$product_ret=mysqli_query($connect,$product_query);
	$product_row=mysqli_fetch_array($product_ret);
	$SCount=mysqli_num_rows($product_ret);
	$pid=$product_row['Product_id'];
	$productname=$product_row['Product_name'];
	$quantity=$product_row['Quantity'];
	$amount=$product_row['amount'];
}
if (isset($_POST['btnadd'])) {

	$product_id=$_POST['txtproductid'];
	$addquantity=$_POST['txtquantity'];
	
	
	
	$update="UPDATE product SET Quantity =Quantity + $addquantity WHERE Product_id= $product_id";
	$update_query=mysqli_query($connect,$update);
	if ($update_query) {
		?>
	<div class="messagebox" id="msgbox">
 		<label style="background:green;padding: 7px 0px 7px 0px;color: white;font-size: 30px;width: 300px;display:inline-block;">&nbsp;&nbsp;Success!</label>
 			<label style="padding:17px;display:inline-block;width:100%;font-size:20px;">Product Successful Updated .</label>
 			<button style="margin-left:240px;font-size:20px;" onclick="window.location='admin_product_list.php'">Ok</button>
 	</div>	 	
		<?php
	}
	else{
		echo "<script>alert('Product Restock Fail')</script>";
	}
}
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<title></title>
 	<link href="fontawesome/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet">
 	<link rel="stylesheet" type="text/css" href="restock.css">
 </head>
 <body>
 	<form action="restockwithlink.php?PID=<?php echo $product_id ?>" method="POST">
 		

<div class="con-1">
   
 		<h1>Product restock Form</h1>
<table>
	
	 
	 <tr hidden/>
	 	<td>Product ID</td>
	 	<td><input type="text" value="<?php echo $pid ?>" class="inputbox" name="txtproductid" hidden/></td>
	 </tr>
	 <tr>
	 	<td>Product Name</td>
	 	<td><input type="text" class="inputbox" value="<?php echo $productname ,' ', $amount, ' ml' ?>" readonly/></td>
	 </tr>
	
	 <tr>
	 	<td>Instock</td>
	 	<td><input type="text" class="inputbox" value="<?php echo $quantity ?>" name="" readonly/></td>
	 </tr>
	 <tr>
	 	<td>Quantity</td>
	 	<td><input type="number" class="inputbox" name="txtquantity" required/>&nbsp;&nbsp;<span class="error"><?php echo $error2 ?></span></td>
	 </tr>
	 
	 <tr>
	 	<td></td>	
	 	<td colspan="2"><input type="submit" name="btnadd" class="add" value="Add Stock">
	 		<input type="reset" value="Refresh" class="refresh" name=""></td>

	 </tr>
	 <tr>
		
		<td colspan="2"><a href="admin_product_list.php" class="list_link">Go to Product List</a></td>
	 </tr>
</table>
</div>
</form>
<script type="text/javascript">
	


</script>
<style type="text/css">
	.inputbox{
	width: 350px;
	background: black;
	color:white;
	padding:7px;
	box-sizing: content-box;
	font-size: 22px;
	border-radius: 10px;
	border: none;
}
.inputbox:focus{
	outline:none;
}
.con-1{
	margin-top:-20px;
	margin-left: 20px;
	position: fixed;
}

.messagebox{
		position:absolute;
		margin-top:150px;
		margin-left: 600px;
		color: black;
		border: 1px solid black;
		width: 300px;
		height:150px;
		background: white;
	}
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.add{
	background: darkcyan;
	border: none;
	width: 130px;
	color: white;
	padding: 7px;
	font-size: 22px;
	border-radius: 10px;
}
.add:hover{
	opacity: 0.7;
}
.refresh:hover{
	opacity: 0.7;
}
.refresh{
	background: #940000;
	border: none;
	width: 100px;
	color: white;
	font-size: 22px;
	padding: 7px;
	border-radius: 10px;
}
table{
	font-family: arial;
	font-size: 25px;
}
body{
	margin-top: 140px;
	font-family: arial;
	background: #050D23;
	color: white;
}
.list_link{
	color: white;
	text-decoration: none;
}
.error{
color: red;
font-size: 15px;
}
</style>
 </body>
 </html>