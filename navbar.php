<?php 
date_default_timezone_set("Asia/Yangon");
session_start();
$mode = empty($_SESSION['Username']) ? "guest" : "admin";
function is_active($page) {
    return basename($_SERVER['PHP_SELF']) === $page ? 'active' : '';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="Thebottle_css.css">
    <title></title>
</head>
<body class="<?= $mode ?>">
<div class="navbar">

    <div class="nav-left">
        <a class="LOGO" href="check_user.php">
            <img class="logo" src="logo.jpg">
        </a>

        <a class="nav-link <?= is_active('product_entry.php'); ?>" href="product_entry.php">Product Entry</a>

        <a class="nav-link <?= is_active('product_list.php'); ?>" href="product_list.php">Products</a>

        <a class="nav-link <?= is_active('purchase.php'); ?>" href="purchase.php">Sale</a>

        <a class="nav-link <?= is_active('salereport.php'); ?>" href="salereport.php">Report (ALL)</a>
        <a class="nav-link <?= is_active('todaysalerp.php'); ?>" href="todaysalerp.php">Report (Tdy)</a>
        <a class="nav-link <?= is_active('daily_report.php'); ?>" href="daily_report.php">Report (Daily)</a>
        <a class="nav-link <?= is_active('Monthly.php'); ?>" href="Monthly.php">Report (Monthly)</a>
        
    </div>

    <div class="nav-right">
        <?php if(empty($_SESSION['Username'])){
         echo "<a class='btn-log login' href='admin_login.php'>Login</a>";
        } 
        else{ 
         echo "<a class='btn-log logout' href='admin_logout.php'>Logout</a>";
         }
        ?>
        
    </div>

</body>
</html>
<style>
     *{
    margin:0;
    
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}
.navbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:10px 30px;
    background:<?php echo isset($_SESSION['Username']) ? '#2E1A47' : '#2c3e50' ?>;
}.logo{cursor:pointer}

.nav-left{
    display:flex;
    align-items:center;
    gap:15px;
}

.logo{
    width:60px;
    height:60px;
    border-radius:50%;
    margin-right:20px;
}

.nav-link{
    color:white;
    text-decoration:none;
    padding:8px 15px;
    border-radius:6px;
    transition:0.3s;
}

.nav-link:hover{
    background:#34495e;
}

.nav-link.active{
    background:#f1c40f;
    color:black;
    font-weight:bold;
}
.btn-log{
     color:white;
    text-decoration:none;
    padding:8px 15px;
    border-radius:6px;
    transition:0.3s;
}
.nav-right .btn-log.logout{
    background:#e74c3c;
   
}
.nav-right .btn-log.login{
    background:#005F02;
}
.nav-right .logout:hover{
    background:#c0392b;
}
</style>
</div>