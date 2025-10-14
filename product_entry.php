<?php 
include('navbar.php');
$connect=mysqli_connect("Localhost","root","","the_bottle_database");
if (isset($_POST['btnadd'])) {
	$code=$_POST['txtproductcode'];
	$name=$_POST['txtproductname'];
	$type=$_POST['txtproducttype'];
	$quantity=$_POST['txtquantity'];
	$price=$_POST['txtprice'];
	$spprice = $_POST['txtspprice'];
	$amount=$_POST['txtamount'];
	$bottletype=$_POST['txtbottletype'];
	$insert="INSERT INTO product VALUES ('','$code','$name','$type','$quantity','$spprice','$price','$amount','$bottletype')";
	$insert_query=mysqli_query($connect,$insert);
	if ($insert_query) {
?>
		<div class="messagebox" id="msgbox">
 		<label style="background:green;padding: 7px 0px 7px 0px;color: white;font-size: 30px;width: 300px;display:inline-block;">&nbsp;&nbsp;Success!</label>
 			<label style="padding:17px;display:inline-block;width:100%;font-size:20px;">Product Successful Added .</label>
 			<button style="margin-left:240px;font-size:20px;margin-bottom: 10px;" onclick="window.location='product_entry.php'">Ok</button>
 	</div>	 	
	<?php
	}
	else{
		echo "Error: adding product";
	}
}
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<title></title>
 	<style type="text/css">
.container-productentry{padding-left:20px;position: fixed;}
label{font-size:25px;display: inline-block;width:170px;height:40px;}
body{font-family: arial;background: #0E1818;color: white;margin-top: 120px;}
.product_list_link{color:blue;font-size:25px;}
.messagebox{position:absolute;margin-top:150px;margin-left: 600px;color: black;border: 1px solid black;width: 300px;background: white;}
.inputbox{border:none;background:black;cursor:white;color:white;width: 250px;box-sizing: content-box;padding: 6px;font-size: 25px;border-radius: 10px;}
table{font-size: 25px;}
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {-webkit-appearance: none;margin: 0;}
/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
tr.spaceUnder>td {
  padding-bottom: 0.3em;
}
.inputbox:focus{
	outline: none;
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
		opacity: 0.8;
		cursor: pointer;
	}
 	</style>
<link rel="stylesheet" type="text/css" href="product_entry.css">
 </head>
 <body>
 	<form action="product_entry.php" method="POST">
 		<div class="container-productentry">
 		<h1>Product Entry Form</h1>
 			<div>
 				<label>Product Name</label>
 				<input type="text" class="inputbox" name="txtproductname" autocomplete="off" required/>
 			</div>
 			<div>
 				<label>Product Code</label>
 				<input type="text" class="inputbox" name="txtproductcode" autocomplete="off" required/>
 			</div>
 			<div>
 				<label>Product Type</label>
		 			<select class="inputbox" name="txtproducttype">
			 		<option>Beer</option>
			 		<option>Wine</option>
			 		<option>Gin</option>
			 		<option>Brandy</option>
			 		<option>Cieggrette</option>
			 		<option>Ciger</option>
			 		<option>Whiskey</option>
			 		<option>Rum</option>
			 		<option>Tequila</option>
			 		<option>Vodka</option>
			 		<option>Others</option>
			 		</select>
		 	</div>
		 	<div>
		 		<label>Quantity</label>
		 		<input type="number" class="inputbox" name="txtquantity" autocomplete="off" required/>
		 	</div>
		 	 	<div>
		 		<label>Spent Price</label>
		 		<input type="text" class="inputbox" name="txtspprice" autocomplete="off">
		 	</div>
		 	<div>
		 		<label>Price</label>
		 		<input type="number" class="inputbox" name="txtprice" autocomplete="off" required/>
		 	</div>
		 	<div>
		 		<label>Amount (ml)</label>
		 		<input type="text" class="inputbox" name="txtamount" autocomplete="off">
		 	</div>
		
		 	<div>
		 		<label>Bottle Type</label>
		 		<select name="txtbottletype" class="inputbox">	
	 			<option>Plastic</option>
	 			<option>Can</option>
	 			<option>glass</option>
	 			<option>Metal</option>
	 			<option>Other</option>
	 			</select>
		 	</div>
		 	<div>
		 		<label></label>
		 		<input type="submit" name="btnadd" class="add" value="Add">
	 		<input type="reset" class="refresh" value="Refresh" name="">

		 	</div>
	

	 	<td colspan="2"><a href="product_list.php" class="product_list_link">Go to Product List</a></td>


</div>
</form>
 </body>
 </html>