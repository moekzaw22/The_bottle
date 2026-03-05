<?php
$connect=mysqli_connect("localhost","root","","the_bottle_database");
 
$select = mysqli_query($connect,"SELECT pr.Product_id,pr.status,pr.Buy_Quantity,p.Product_name FROM purchase pr LEFT JOIN product p ON p.Product_id=pr.Product_id WHERE status='Unconfirmed'");
if ($select && mysqli_num_rows($select) > 0 ) {
	while($row = mysqli_fetch_assoc($select)){
		$product_id=$row['Product_id'];
		$buyquantity = $row['Buy_Quantity'];
		$productname = $row['Product_name'];
		mysqli_query($connect,"UPDATE purchase SET status ='Canceled' WHERE status ='Unconfirmed'");
		mysqli_query($connect,"INSERT INTO history (type,Amount,added_to,Date_time) VALUES ('Canceled',$buyquantity,$product_id,Now()) ");
	
}
 echo "<script>window.location='purchase.php';</script>";
    exit();
} else {
    echo "<script>alert('No pending sale found.'); window.location='purchase.php';</script>";
}
?>
