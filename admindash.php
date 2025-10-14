<?php 
session_start();
include('connect.php');
date_default_timezone_set("Asia/Yangon");
$date = date('Y-m-d');
$result= mysqli_query($connect,"SELECT SUM(totalprice) AS totalsum FROM purchase WHERE Date='$date' And status='Confirmed'");
$row = mysqli_fetch_assoc($result); 
$sum = $row['totalsum'];

$result2= mysqli_query($connect,"SELECT SUM(profit) AS totalsum FROM purchase WHERE Date='$date' And status='Confirmed'");
$row2 = mysqli_fetch_assoc($result2); 
$sum2 = $row2['totalsum'];
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<title></title>
 </head>
 <style type="text/css">
    
     body{
        background: #0E1818;
        color: white;
     }
     .loginfo{
         font-family: arial;
         font-size: 25px;
     }
     .container-body{
      margin-top: 100px;
      margin-left: 20px;
     }
 </style>
 <body>
   <?php echo include('admin_navbar.php') ?>
 	<div class="container-body">
         <label class="loginfo">Your Are Now Login As <?php echo $_SESSION['username'] ?></label>
    </div>
 </body>

 </html>