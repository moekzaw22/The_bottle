<?php 
$productname1=$_REQUEST['productname'];
$con=mysqli_connect("localhost","root","","the_bottle_database");
if ($productname1 !== "") {
	$query=mysqli_query($con,"SELECT * FROM product WHERE Product_name='$productname1'");
	$row=mysqli_fetch_array($query);

	$productid=$row['Product_id'];

}
$result = array($productid);
$myJSON = json_decode($result);
echo $myJSON;
 ?>