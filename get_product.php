<?php
include('connect.php');
$id = intval($_GET['id']);
$res = mysqli_query($connect, "SELECT * FROM product WHERE Product_id=$id");
echo json_encode(mysqli_fetch_assoc($res));
?>