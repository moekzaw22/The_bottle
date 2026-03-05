<?php 
date_default_timezone_set("Asia/Yangon");
session_start();
if (empty($_SESSION['Username'])) {
    header("location: admin_login.php");
}
function is_active($page) {
    return basename($_SERVER['PHP_SELF']) === $page ? 'active' : '';
}
?>

<div class="navbar">

    <div class="nav-left">
        <a href="purchase.php">
            <img class="logo" src="logo.jpg">
        </a>

        <a class="nav-link <?= is_active('admin_product_list.php'); ?>" href="admin_product_list.php">Products</a>

        <a class="nav-link <?= is_active('admin_todaysalerp.php'); ?>" href="admin_todaysalerp.php">Today</a>

        <a class="nav-link <?= is_active('admin_salereport.php'); ?>" href="admin_salereport.php">All Reports</a>

        <a class="nav-link <?= is_active('admin_monthly.php'); ?>" href="admin_monthly.php">Monthly</a>

        <a class="nav-link <?= is_active('admin_daily_report.php'); ?>" href="admin_daily_report.php">Daily</a>

        <a class="nav-link <?= is_active('History_review.php'); ?>" href="History_review.php">History</a>
    </div>

    <div class="nav-right">
        <?php if(empty($_SESSION['Username'])){
         echo "<a class='btn login' href='admin_login.php'>Login</a>";
        } 
        else{ 
         echo "<a class='btn logout' href='admin_logout.php'>Logout</a>";
         }
        ?>
        
    </div>
<style>
     *{
    margin:0;
    padding:0;
    
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}body{
  background-color:<?php echo isset($_SESSION['Username']) ? '#f4f6f9' : 'white'; ?>;
}

.navbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:10px 30px;
    background:#5a2ca0;
}

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
    text-decoration: none;
}

.nav-link.active{
    background:#f1c40f;
    color:black;
    font-weight:bold;
}
.btn{
     color:white;
    text-decoration:none;
    padding:8px 15px;
    border-radius:6px;
    transition:0.3s;
}
.nav-right .btn.logout{
    background:#e74c3c;
   
}
.nav-right .btn.login{
    background:#005F02;
}

.nav-right .logout:hover{
    background:#c0392b;
}
</style>
</div>