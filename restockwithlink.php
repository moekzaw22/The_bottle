<?php 
include('admin_navbar.php');
$error="";
$error2="";
$date_time = date("Y-m-d H:i:s");

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
		$insert_history = mysqli_query($connect, 
    "INSERT INTO history VALUES ('','Restock','$addquantity','$product_id','$date_time')"
);if (isset($_GET['return']) && !empty($_GET['return'])) {

    $returnUrl = urldecode($_GET['return']); // 🔥 decode it properly

    header("Location: " . $returnUrl);
    exit();

} else {

    header("Location: admin_product_list.php");
    exit();
}
		?>
	<div class="overlay">
    <div class="messagebox">
        <div class="message-header">
            <i class="fas fa-check-circle"></i> Success
        </div>

        <div class="message-body">
            Product successfully updated.
        </div>

        <div class="message-footer">
            <button onclick="window.location.href='<?= $returnUrl ?>'">
                OK
            </button>
        </div>
    </div>
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
}.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.4);
    display: flex;
    justify-content: center;
    align-items: center;
}

.messagebox {
    width: 400px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.3);
    animation: pop 0.3s ease;
}

.message-header {
    background: #28a745;
    color: white;
    padding: 15px;
    font-size: 20px;
    border-radius: 8px 8px 0 0;
}

.message-body {
    padding: 20px;
    font-size: 16px;
}

.message-footer {
    padding: 15px;
    text-align: right;
}

.message-footer button {
    padding: 8px 15px;
    border: none;
    background: #28a745;
    color: white;
    border-radius: 4px;
    cursor: pointer;
}

.message-footer button:hover {
    background: #218838;
}

@keyframes pop {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>
 </body>
 </html>