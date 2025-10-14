<?php
$connect=mysqli_connect("localhost","root","","the_bottle_database");

if(isset($_GET['DID'])) 
{
	$DID=$_GET['DID'];

$purchase_Query="DELETE FROM daily_report WHERE 
			 id=$DID";

	$purchase_ret=mysqli_query($connect,$purchase_Query);
	
			
	echo "<script>window.location='admin_daily_report.php';</script>";
exit();

}

?>