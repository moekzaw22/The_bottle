<?php
session_start();

if(empty($_SESSION['Username'])){
	echo "<script>window.location = 'admin_login.php'</script>";
}
else{
	echo "<script>window.location = 'admin_product_list.php'</script>";
}
?>