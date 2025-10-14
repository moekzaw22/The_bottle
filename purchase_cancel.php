<?php
$connect=mysqli_connect("localhost","root","","the_bottle_database");
 


if(isset($_GET['PID'])) 
{
	$PID=$_GET['PID'];

$purchase_Query="DELETE FROM purchase WHERE 
			 purchaseid=$PID";

	$purchase_ret=mysqli_query($connect,$purchase_Query);
	
			
	echo "<script>window.location='purchase.php';</script>";
exit();

}

?>