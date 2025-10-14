<?php 

$error="";
$error2="";
include('connect.php');
if (isset($_GET['PID'])) {
	$PID=$_GET['PID'];
	$product_query="SELECT * from product Where Product_id='$PID'";

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
	$updateprice=$_POST['txtupdateprice'];
	
	
	
	echo $update="UPDATE product SET Price =$updateprice WHERE Product_id= $product_id";
	$update_query=mysqli_query($connect,$update);
	if ($update_query) {
		echo "<script>alert('Product Price Updated')</script>";
			echo "<script>window.location='product_list.php'</script>";
	}
	else{
		echo "<script>alert('Price update Fail')</script>";
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
 	<form action="productupdate.php" method="POST">
 		


   
 		<h1>Product Update Form</h1>
<table>
	
	 
	 <tr>
	 	<td>Product ID</td>
	 	
	 	<td><input class="label" type="text" name="txtproductid" value="<?php echo $PID ?>"></td>	 
	 </tr>
	 <tr>
	 	<td>Barcode</td>
	 	<td><label class="label"><?php echo $product_row['Product_code'] ?></label></td>
	 </tr>
	 <tr>
	 	<td>Product Name</td>
	 	
	 	<td><label class="label"><?php echo $productname ?> (<?php echo $amount ?> ml)</label></td>	 
	 </tr>
	 <tr>
	 	<td>Current Price</td>
	 	<td><label class="label"><?php echo $product_row['Price'] ?> Ks</label></td>
	 </tr>
	 <tr>
	 	<td>Update Price</td>
	 	<td><input type="number" class="inputbox" name="txtupdateprice" autofocus="on" required/>&nbsp;&nbsp;<span class="error"><?php echo $error2 ?></span></td>
	 </tr>
	
	 <tr>
	 	<td></td>	
	 	<td colspan="2"><input type="submit" name="btnadd" class="add" value="Update">
	 		<input type="reset" value="Refresh" class="refresh" name=""></td>

	 </tr>
	 <tr>
		
		<td><button onclick="go()">Go Back</button></td>
	 </tr>
</table>

</form>
<script type="text/javascript">
	function go(){
		history.go(-1);
	}


</script>
<style type="text/css">
.label{
	background: #0E1818;
	color: white;
	border: none;
	font-size: 30px;
}
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.inputbox:focus{
	outline: none;
}
input[type=number] {
  -moz-appearance: textfield;
}
	.inputbox{
	width: 250px;
	padding:7px;
	background:black;
	border:none;
	color:white;
	box-sizing: content-box;
	font-size: 22px;
	border-radius: 10px;
	border: none;
}
.add{
	background: darkcyan;
	border: none;
	width: 100px;
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
	font-family: arial;
	background: #0E1818;
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