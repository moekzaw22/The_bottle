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
	$productcode=$product_row['Product_code'];
	$productname=$product_row['Product_name'];
	$quantity=$product_row['Quantity'];
	$amount=$product_row['amount'];
}
if (isset($_POST['btnadd'])) {
	$product_name=$_POST['txtproductname'];
	$barcode=$_POST['txtbarcode'];
	$amount=$_POST['txtmililiter'];
	$product_id=$_POST['txtproductid'];
	$updateprice=$_POST['txtupdateprice'];
	$spentprice = $_POST['txtspentprice'];
	
	
 $update="UPDATE product SET Product_id=$product_id, Product_name='$product_name', Product_code='$barcode', amount='$amount', Price='$updateprice', sp_price = '$spentprice' WHERE Product_id= '$product_id'";
	$update_query=mysqli_query($connect,$update);
	if ($update_query) {
	?>
		<div class="messagebox" id="msgbox">
 		<label style="background:green;padding: 7px 0px 7px 0px;color: white;font-size: 30px;width: 300px;display:inline-block;">&nbsp;&nbsp;Success!</label>
 			<label style="padding:17px;display:inline-block;width:100%;font-size:20px;">Product Successful Updated .</label>
 			<button style="margin-left:200px;font-size:20px;width:60px;padding:6px;" onclick="window.location='admin_product_list.php'" >Ok</button>
 	</div>	 	
	<?php
	}
	else{
		?>
		<div class="messagebox" id="msgbox">
 		<label style="background:red;padding: 7px 0px 7px 0px;color: white;font-size: 30px;width: 300px;display:inline-block;">&nbsp;&nbsp;Fail!</label>
 			<label style="padding:17px;display:inline-block;width:100%;font-size:20px;">HaHa.. Product Update Fail!</label>
 			<button style="margin-left:160px;font-size:20px;" onclick="window.location='product_list.php'">Ok</button>
 			<button style="font-size:20px;" onclick="window.location='productedit.php?PID=<?php echo $PID ?>'">Cancel</button>
 	</div>		
		<?php
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
 <body style="position:absolute;" onkeypress="return enterkeyPressed(event)">
 <div class="container">
 	<form action="productedit.php?PID=<?php echo $PID ?>" method="POST">
 		
 		<h1>Product Update Form</h1>	
	 <div>
	 		<div><label class="label1">Product ID</label><input type="text" name="txtproductid" class = "inputbox" value="<?php echo $PID ?>" readonly/></div>
	 </div>
	<div>
			<div><label class="label1">Barcode</label><input type="text" class="inputbox" value="<?php echo $productcode ?>" name="txtbarcode"></div>
	</div>
	<div>
			<div><label class="label1">Product Name</label><input type="text" class="inputbox" name="txtproductname" value="<?php echo $productname ?>"></div>
	</div>
	<div>
			<div><label class="label1">Mililiter</label><input type="text" class="inputbox" name="txtmililiter" value="<?php echo $amount ?>"></div>
	</div>
	
	<div>
		<div><label class="label1">Price(MMK)</label><input type="text" name="txtupdateprice" class="inputbox" value="<?php echo $product_row['Price'] ?>"></div>
	</div>
	<div>
		<div>
			<label class="label1">Spent Price(MMK)</label>
			<input type="text" name="txtspentprice" class="inputbox" value="<?php echo $product_row['sp_price'] ?>">
		</div>
	</div>
	 <input type="submit" name="btnadd" class="add" value="Update">
	 		<input type="reset" value="Refresh" class="refresh" name="">
	 		<br>
	<br>
		<a class="goback" href="admin_product_list.php">Go Back</a>
	
</div>

</form>
<script type="text/javascript">
	function go(){
		history.go(-1);
	}
	function msgbox(){
		 document.getElementById('msgbox').style.display='block';
	}
	function enterkeyPressed(event) {
		if (event.keyCode == 13) {
			
			window.location='admin_product_list.php';
			return true;
		}
		else{
			
		}
	}
</script>
<style type="text/css">
	.container{
		position: fixed;
		margin-left:20px;
	}
	.messagebox{
		
		position:fixed;
		top:200;
		left: 600;
		color: black;
		border: 1px solid black;
		width: 300px;
		height:150px;
		background: white;
	}
.label{
	
	color: white;
	border: none;
	font-size: 30px;
}
.label1{
	height: 50px;
	font-size: 20px;
	display: inline-block;
	width: 170px;
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
body{
	margin:0;
	font-family: arial;
	background: #050D23;
	color: white;
}
.goback{
	background:green;
	color:white;
	text-decoration: none;
	border:none;
	border-radius: 10px;
	padding: 10px 20px 10px 20px;
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