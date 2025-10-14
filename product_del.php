<?php 
include('connect.php');
if(isset($_GET['PID'])) 
{
	$PID=$_GET['PID'];
	$purchase_Query="DELETE FROM product WHERE 
			 Product_id=$PID";
	$purchase_ret=mysqli_query($connect,$purchase_Query);
	
			
	echo "<script>window.location='admin_product_list.php';</script>";
exit();

}
 ?>