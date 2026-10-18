<?php
date_default_timezone_set('Asia/Yangon');
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (empty($_SESSION['Username'])) {
    header('Location: admin_login.php');
    exit;
}

if (!function_exists('is_active')) {
    function is_active($page)
    {
        return basename($_SERVER['PHP_SELF']) === $page ? 'active' : '';
    }
}
?>
<!DOCTYPE html>
<link rel="stylesheet" href="bottle-ui.css">
<header class="navbar admin-navbar" data-mode="admin">
    <a class="brand" href="purchase.php" aria-label="Return to user sale screen">
        <img class="logo" src="logo.jpg" alt="The Bottle">
    </a>
    <nav class="nav-left" aria-label="Admin navigation">
        <a class="nav-link <?= is_active('admin_product_list.php') ?>" href="admin_product_list.php">Products</a>
        <a class="nav-link <?= is_active('admin_todaysalerp.php') ?>" href="admin_todaysalerp.php">Report (Today)</a>
        <a class="nav-link <?= is_active('admin_salereport.php') ?>" href="admin_salereport.php">Reports (All)</a>
        <a class="nav-link <?= is_active('admin_daily_report.php') ?>" href="admin_daily_report.php">Reports (Daily)</a>
        <a class="nav-link <?= is_active('admin_monthly.php') ?>" href="admin_monthly.php">Report (Monthly)</a>
        <a class="nav-link <?= is_active('History_review.php') ?>" href="History_review.php">History</a>
    </nav>
    <div class="nav-right">
        <a class="btn-log logout" href="admin_logout.php">Logout</a>
    </div>
</header>
