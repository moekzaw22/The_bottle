<?php
$connect=mysqli_connect("localhost","root","","the_bottle_database");
  
$select="SELECT pr.status,pr.Buy_Quantity,p.Quantity, p.Product_id,pr.Product_id FROM purchase pr JOIN product p ON pr.Product_id=p.Product_id WHERE
			 	pr.status='Unconfirmed'";

	$select_query=mysqli_query($connect,$select);
	if ($select_query && mysqli_num_rows($select_query) > 0) {
		while ($row = mysqli_fetch_assoc($select_query)) {
			$product_id = $row['Product_id'];
			$buyquantity = $row['Buy_Quantity'];
			 $update="UPDATE product SET Quantity = Quantity - $buyquantity WHERE Product_id='$product_id'";
			$update_query=mysqli_query($connect,$update);
		}
		mysqli_query($connect, "UPDATE purchase SET status ='Confirmed' WHERE status='Unconfirmed'");	
		
			 echo "<script>window.location='purchase.php';</script>";
    exit();
} else {
    echo "<script>alert('No pending sale found.'); window.location='purchase.php';</script>";
}
?>