<?php 
$connect=mysqli_connect("Localhost","root","","the_bottle_database");
include('navbar.php');
date_default_timezone_set("Asia/Yangon");
if (isset($_POST['btnsave'])) {
	$barcode=$_POST['txtbarcode'];
	$quantity=$_POST['txtquantity'];

	$select="SELECT Product_id, Product_code, sp_price, Price FROM product WHERE Product_code='$barcode' OR Product_id='$barcode'";
	$query=mysqli_query($connect,$select);
	$array=mysqli_fetch_array($query);
	$price=$array['Price'];
	$date=date('Y-m-d');
	$time=date("H:i:s");
	$f_profit = $array['sp_price'];
	$s_profit = $f_profit * $quantity;

	$product_id = $array['Product_id'];
	$total_price=$price * $quantity;
	$f_profit = $total_price - $s_profit;
	$insert="INSERT INTO purchase VALUES('','$product_id','$quantity','$total_price','$f_profit','$date','$time','Unconfirmed')";
	$insert_query=mysqli_query($connect,$insert);
}

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Sale</title>
	<link rel="stylesheet" type="text/css" href="purchase.css">
</head>
<body>
	<form action="purchase.php" method="POST">
		<div style="display:flex;">
	<table style="position:absolute;left:100px;top:150px;">
		<tr>
			<td>Barcode</td>
		</tr>
		<tr>
			<td><input class="inputbox" type="text" name="txtbarcode" onkeypress="return enterkeyPressed(event)" autofocus="on" required/></td>
		</tr>
		<tr>
			<td>Quantity</td>
		</tr>
		<tr>
			<td><input class="inputbox" type="number" name="txtquantity" required/></td>
		</tr>
		<tr>
			<td><input type="submit" name="btnsave" hidden/></td>
		</tr>
		 		<tr>
 			<td><input class="inputbox" type="number" id="pay" name="" placeholder="Changes" onkeypress="return checkchanges(event)"></td>
 		</tr>
 		
 		<tr>
 			<td><div style="font-size:30px" id="Changes">
	
</div></td>
 		</tr>

	</table>
	<?php 

		
 $select="SELECT * FROM purchase p,product pr WHERE p.Product_id=pr.Product_id AND p.status='Unconfirmed'";
 $select_query=mysqli_query($connect,$select);
 $count=mysqli_num_rows($select_query);

$select_price="SELECT SUM(totalprice) AS totalsum FROM purchase WHERE status='Unconfirmed'";
$result=mysqli_query($connect,$select_price);
$row1=mysqli_fetch_array($result);
$row = mysqli_fetch_assoc($result); 

$sum = $row1['totalsum'];
?>
	<table style="position:absolute;left:550px;border-collapse: collapse;border:1px dotted black;top:150px">
		<tr class="header">
			<td>Product Name</td>
			<td style="padding-right:20px;padding-left:20px">Price</td>
			<td>Total price</td>
		</tr>
<?php

 for ($i=0; $i < $count; $i++) { 
 	$array=mysqli_fetch_array($select_query);
 	$purchaseid=$array['purchaseid'];
 	$product_id = $array['Product_id'];
 	?>
		<tr class="item">
			<td><?php echo $product_id ?> - <?php echo $array['Product_name'] ?></td>
			<td style="padding-right:20px;padding-left:20px"><?php echo $array['Price']  ?> &nbsp;Ks * <?php echo $array['Buy_Quantity'] ?></td>
		
			<td><?php echo $array['totalprice'] ?> &nbsp;Ks</td>
			<td><a class="cancel" href="purchase_cancel.php?PID='<?php echo $purchaseid ?>'">Cancel</a></td>
		</tr>
		<?php 
}
		 ?>
		 <tr>
		 	<td></td>
		 </tr>
		 <tr>
		 	<td></td>
		 </tr>

		 <tr>
		 	<td></td>
		 </tr>
		 
		 <tr>
		 	<td></td>
		 </tr>

		 		<br>
		 		<br>
		 	<tr>
 			<td  colspan="4"><a class="link" accesskey="C" href="purchase_complete.php?PID='<?php echo $purchaseid ?>'">Confirm</a>
 				<a class="link" href="purchase_cancel_all.php?PID='<?php echo $purchaseid ?>'">Cancel</a></td>
 		</tr>
 		<tr>
 			<td colspan="4"><p style="font-size:30px;">Total Price is <span id="sum" style="color:yellow;font-weight: bolder;font-size:34px;">
 				<?php echo number_format($sum) ?></span> KS</p></td>
 		</tr>
	</table>
	</div>
	</form>
</body>
</html>
<script type="text/javascript">
	function checkchanges(event){
		if (event.keyCode == 13) {
		pay= document.getElementById('pay').value;
		sum= document.getElementById('sum').value;
		changes = pay - <?php echo $sum ?>;
		document.getElementById('Changes').innerHTML="Changes = " + changes;
	}
	}
	
</script>