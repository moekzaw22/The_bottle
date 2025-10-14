<?php
$connect=mysqli_connect("localhost","root","","the_bottle_database");
  


if(isset($_GET['PID'])) 
{
	$PID=$_GET['PID'];

 $purchase_Query="UPDATE purchase p,product pr
			 SET 
			 p.status='Confirmed'
			 WHERE
			 p.status='Unconfirmed' AND pr.Product_id=p.Product_id";

	$purchase_ret=mysqli_query($connect,$purchase_Query);
	if ($purchase_ret) {

				$select_product="SELECT * FROM product p, purchase pr WHERE p.Product_id=pr.Product_id AND pr.purchaseid=$PID";
				$select_query=mysqli_query($connect,$select_product);
				$product_array=mysqli_fetch_array($select_query);
				$quantity=$product_array['Quantity'];
				$product_id=$product_array['Product_id'];
				$buyquantity=$product_array['Buy_Quantity'];
				$update="UPDATE product SET Quantity = Quantity - $buyquantity WHERE Product_id='$product_id'";
			

			$update_query=mysqli_query($connect,$update);
			echo "<script>window.location='purchase.php';</script>";
	
exit();
}
}

?>