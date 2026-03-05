<?php
$connect=mysqli_connect("localhost","root","","the_bottle_database");
 


if(isset($_GET['PID'])) 
{
	$PID=$_GET['PID'];
	$select = mysqli_query($connect,"SELECT pr.Product_id,pr.status,pr.purchaseid,pr.Buy_Quantity FROM purchase pr LEFT JOIN product p ON p.Product_id=pr.Product_id WHERE pr.purchaseid=$PID");
	if ($select && mysqli_num_rows($select) > 0) {
		$row = mysqli_fetch_assoc($select);
		$product_id = $row['Product_id'];
		$buyquantity = $row['Buy_Quantity'];
		mysqli_query($connect,"INSERT INTO history (type,Amount,added_to,Date_time) VALUES ('Canceled',$buyquantity,$product_id,Now())");
	$purchase_Query="UPDATE purchase SET status = 'Canceled' WHERE purchaseid=$PID";
	$purchase_ret=mysqli_query($connect,$purchase_Query);
	
	}	
	echo "<script>window.location='purchase.php';</script>";
	}
exit();


?>