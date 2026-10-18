<?php
date_default_timezone_set('Asia/Yangon');
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!function_exists('is_active')) {
    function is_active($page)
    {
        $currentPage = basename($_SERVER['PHP_SELF']);
        if ($currentPage === 'index.php' && $page === 'purchase.php') {
            return 'active';
        }
        return $currentPage === $page ? 'active' : '';
    }
}
$isAdmin = !empty($_SESSION['Username']);
?>
<!DOCTYPE html>
<link rel="stylesheet" href="bottle-ui.css">
<header class="navbar" data-mode="<?= $isAdmin ? 'admin' : 'guest' ?>">
    <?php if ($isAdmin): ?>
        <a class="brand" href="admin_product_list.php" aria-label="Open admin product list">
            <img class="logo" src="logo.jpg" alt="The Bottle">
        </a>
    <?php else: ?>
        <span class="brand brand-static" aria-label="The Bottle">
            <img class="logo" src="logo.jpg" alt="The Bottle">
        </span>
    <?php endif; ?>
    <nav class="nav-left" aria-label="Main navigation">
        <a class="nav-link <?= is_active('product_entry.php') ?>" href="product_entry.php">Product Entry</a>
        <a class="nav-link <?= is_active('product_list.php') ?>" href="product_list.php">Products</a>
        <a class="nav-link <?= is_active('purchase.php') ?>" href="purchase.php">Sale</a>
        <a class="nav-link <?= is_active('salereport.php') ?>" href="salereport.php">Report (All)</a>
        <a class="nav-link <?= is_active('todaysalerp.php') ?>" href="todaysalerp.php">Report (Today)</a>
        <a class="nav-link <?= is_active('daily_report.php') ?>" href="daily_report.php">Report (Daily)</a>
        <a class="nav-link <?= is_active('Monthly.php') ?>" href="Monthly.php">Report (Monthly)</a>
    </nav>
    <div class="nav-right">
        <?php if ($isAdmin): ?>
            <a class="btn-log logout" href="admin_logout.php">Logout</a>
        <?php else: ?>
            <a class="btn-log login" href="admin_login.php">Login</a>
        <?php endif; ?>
    </div>
</header>
