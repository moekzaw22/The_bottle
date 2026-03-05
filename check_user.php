<?php
session_start();

// If no username in session, go to login
if (empty($_SESSION['Username'])) {
    header("Location: admin_login.php");
    exit;
}

// If user is logged in, go to product list
header("Location: admin_product_list.php");
exit;
?>