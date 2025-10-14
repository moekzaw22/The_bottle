<?php 
include('navbar.php');
$error="";
$error2="";
$connect=mysqli_connect("Localhost","root","","the_bottle_database");
if (isset($_POST['btnadd'])) {

	$product_id=$_POST['txtproductid'];
	$addquantity=$_POST['txtquantity'];
	
	
	
	$update="UPDATE product SET Quantity =Quantity + $addquantity WHERE Product_id= $product_id";
	$update_query=mysqli_query($connect,$update);
	if ($update_query) {
		echo "<script>alert('Product Restock Successfully')</script>";
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
 	<form action="restock.php" method="POST">
 		

<div class="container-restock">
   
 		<h1>Restock Form</h1>
<table>
	
	 
	 <tr>
	 	<td>Product Name</td>
	 	<td><select name="txtproductid" class="inputbox" required/>
	 			<option>--Please Select Product--</option>
 		 	<?php 
 		 	$product="SELECT * FROM product";
 		 	$produt_query=mysqli_query($connect,$product);

 				$product_count=mysqli_num_rows($produt_query);

 				for ($i=0; $i < $product_count ; $i++) { 
 					$speciality_array=mysqli_fetch_array($produt_query);
 					$Product_name=$speciality_array['Product_name'];
 					$productid=$speciality_array['Product_id'];
 					$quantity=$speciality_array['Quantity'];
 					$rolename=$speciality_array['name'];
 					
 						echo "<option value='$productid'>$Product_name / $quantity InStock</option>";
 						

 				}
 		 			
 		 	
 		 	 ?>
 		 

 		 	 ?>
	 	</select >&nbsp;&nbsp;<span class="error"><?php echo $error ?></span></td>
	 </tr>
	 
	 <tr>
	 	<td>Quantity</td>
	 	<td><input type="number" class="inputbox" name="txtquantity" required/>&nbsp;&nbsp;<span class="error"><?php echo $error2 ?></span></td>
	 </tr>
	 
	 <tr>
	 	<td></td>	
	 	<td colspan="2"><input type="submit" name="btnadd" class="add" value="Add">
	 		<input type="reset" value="Refresh" class="refresh" name=""></td>

	 </tr>
	 <tr>
		
		<td colspan="2"><a href="product_list.php" class="list_link">Go to Product List</a></td>
	 </tr>
</table>
<button onclick="go()">Go Back</button>
</div>
<script type="text/javascript">
	function go(){
		history.go(-1);
	}


</script>
</form>
<script type="text/javascript">
	


</script>
<style type="text/css">
.container-restock{
	padding-left: 20px;
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
	width: 350px;
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
	margin-top: 100px;
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