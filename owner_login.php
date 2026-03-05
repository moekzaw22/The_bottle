<?php 
include('connect.php');
$attemp = 0;
session_start();
if (isset($_POST['btnlogin'])) {
	$user_name = $_POST['txtusername'];
	$password = $_POST['txtpassword'];
    $check = "SELECT * FROM admin WHERE Username = '$user_name' AND Password = '$password'";
    $check_query = mysqli_query($connect, $check);
    $check_exist = mysqli_num_rows($check_query);
    $arra=mysqli_fetch_array($check_query);
	if ($check_exist == 1 ) {
        $_SESSION['username']=$arra['Username'];
        $password = $_SESSION['Password'];
	
		echo "<script>window.location = 'admindash.php' </script>";
	}
	else{
		echo "<script>alert('Login Fail');</script>";
	}
}
include('navbar.php');
 ?>

 <!DOCTYPE html>
 <html>
 <head>

 	<link href="fontawesome/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet">
 	<meta charset="utf-8">
 	<title></title>
 </head>
 <body>
 	<form action="owner_login.php?action=Login" method="POST">
 	<div class="container-f">
 	<div>
 		<label>Username</label>
 		<input type="text" name="txtusername" autocomplete="off">
 	</div>
 	<div>
 		<label>Password</label>
 		<input id="psw" type="password" name="txtpassword"> <i onclick="psw()" class="fas fa-eye"></i>
 	</div>
 	<div>
 		<input class="login" type="submit" value="login" name="btnlogin">
 	</div>
 	</div>
 	</form>

 </body>
 <script type="text/javascript">
 	function psw(){
 		var x=document.getElementById("psw");
 			if (x.type === "password") {
 				x.type="text";
 			}
 			else{
 				x.type="password";
 			}
 	}
 </script>
 <style type="text/css">
 .container-f{
 	margin-top: 100px;
 	color: white;
 	padding: 28px;
 }
 .fa-user{
 	color: black;
 }
 body{
 	margin: 0;
 	color: white;
 	background: #0E1818;
 }
 	.container-m{
 		margin-left: 260px;
 		margin-top: 100px;
 		width: 799px;
 		display: flex;
 		
 	}
 	.login{
 		padding: 3px 9px 3px 9px;
 	}
 	label{
 		display: inline-block;
 		width: 140px;
 		font-size: 28px;
 		font-family: arial;
 		line-height: 50px;
 		font-weight: bold;
 	}
 	input{
padding: 2px;
 		font-size: 28px;
 	}
 	i{
 		font-size: 28px;
 		color: white;
 	}
 	button{
 		background: transparent;
 		border: none;
 		

 	}
 </style>
 </html>