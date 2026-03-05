<?php 
include('navbar.php');
include('connect.php');
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
  <div class="msg-content">
    <div class="msg-header">Success!</div>
    <div class="msg-body">Product successfully added.</div>
    <button class="msg-btn" onclick="redirect()">Ok</button>
  </div>
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

 	</style>
<link rel="stylesheet" type="text/css" href="product_entry.css">
<script>	
const msgBox = document.getElementById('msgbox');
// Function to redirect
function redirect() {
  window.location = 'product_entry.php';
}
// Close when clicking outside the content box
msgBox.addEventListener('click', function(e) {
  if(e.target === msgBox) { // only if clicked outside inner box
    redirect();
  }
});
</script>
 </head>
 <body>
 	<form action="product_entry.php" method="POST">
 		<div class="container-productentry">
 		<h1>Product Entry Form</h1>
 			<div>
 				<label>Product Name</label>
 				<input type="text" class="inputbox" name="txtproductname" autocomplete="off" autofocus required/>
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
		 		<input type="text" class="inputbox" name="txtspprice" autocomplete="off" required>
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