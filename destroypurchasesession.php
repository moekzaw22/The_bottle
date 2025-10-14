<?php   
session_start(); 
session_destroy();
header("location:/the_bottle/purchase.php"); 
exit();
?>
