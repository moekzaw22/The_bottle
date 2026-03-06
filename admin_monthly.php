<?php

include('admin_navbar.php');
include('connect.php');
if (!$connect) die("Database connection failed: ".mysqli_connect_error());

// Get selected year from GET
$selected_year = isset($_GET['year']) ? $_GET['year'] : date("Y");

// Fetch available years for dropdown
$year_query = mysqli_query($connect, "SELECT DISTINCT YEAR(Date) AS year FROM purchase WHERE status='Confirmed' ORDER BY year DESC");
$years = [];
while ($y = mysqli_fetch_assoc($year_query)) {
    $years[] = $y['year'];
}

// Build main query
$where = "WHERE status='Confirmed'";
if ($selected_year) {
    $where .= " AND YEAR(Date) = '$selected_year'";
}

$select = "
    SELECT YEAR(Date) AS sltyear, MONTH(Date) AS sltmonth, 
           SUM(totalprice) AS total_price, SUM(profit) AS total_profit, SUM(Buy_Quantity) AS total_items
    FROM purchase
    $where
    GROUP BY YEAR(Date), MONTH(Date)
    ORDER BY YEAR(Date) DESC, MONTH(Date) DESC
";

$select_query = mysqli_query($connect, $select);
$count = mysqli_num_rows($select_query);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Monthly Sales Report</title>
<style>
body {
    font-family: Arial, sans-serif;
    
    margin: 0;
}
h2 {
    text-align: center;
    margin-bottom: 20px;
}
.filter {
    text-align: center;
    
}
.filter select {
    padding: 7px 12px;
    font-size: 16px;
    margin:10px;
}
</style>
</head>
<body>
<div class="filter">
<form method="GET">
    <label for="year">Select Year:</label>
    <select name="year" id="year" onchange="this.form.submit()">
        <option value="">All Years</option>
        <?php foreach ($years as $y): ?>
            <option value="<?= $y ?>" <?= ($selected_year == $y) ? 'selected' : '' ?>><?= $y ?></option>
        <?php endforeach; ?>
    </select>
</form>
</div>

<table class="table">
    <tr>
        <th>Year</th>
        <th>Month</th>
        <th>Total Sale (Price)</th>
        <th>Profit</th>
        <th>Total Items Sold</th>
    </tr>
<?php
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
if($count > 0) {
    while($row = mysqli_fetch_assoc($select_query)) {
        $year = $row['sltyear'];
        $month = $months[$row['sltmonth']-1];
        $total_price = number_format($row['total_price']);
        $total_profit = number_format($row['total_profit']);
        $total_items = number_format($row['total_items']);
        echo "<tr>
                <td>$year</td>
                <td>$month</td>
                <td>$total_price Ks</td>
                <td>$total_profit Ks</td>
                <td>$total_items</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No records found.</td></tr>";
}
?>
</table>

</body>
</html>