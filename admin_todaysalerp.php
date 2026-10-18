<?php
include('admin_navbar.php');
include('connect.php');
$date = date('Y-m-d');
$total_sum = 0;
$Quantity = 0;
$searchTerm = trim((string)($_GET['txtitem'] ?? ''));
$isSearch = isset($_GET['btnitem']);

// Search today's confirmed sales by product name, barcode, or product ID.
if ($isSearch) {
    $searchStatement = $connect->prepare(
        "SELECT p.Product_name, pr.Product_id, pr.purchaseid, pr.totalprice,
                pr.Buy_Quantity, pr.profit, p.sp_price, pr.Time
         FROM purchase pr
         JOIN product p ON p.Product_id = pr.Product_id
         WHERE pr.status = 'Confirmed' AND pr.Date = ?
           AND (p.Product_name LIKE ? OR p.Product_code = ? OR p.Product_id = ?)
         ORDER BY pr.Time DESC"
    );
    $nameMatch = '%' . $searchTerm . '%';
    $searchStatement->bind_param('ssss', $date, $nameMatch, $searchTerm, $searchTerm);
    $searchStatement->execute();
    $select_query = $searchStatement->get_result();
} else {
    $select = "
        SELECT p.Product_name, pr.Product_id, pr.purchaseid, pr.totalprice,
               pr.Buy_Quantity, pr.profit, p.sp_price, pr.Time
        FROM purchase pr
        JOIN product p ON p.Product_id = pr.Product_id
        WHERE pr.status='Confirmed' AND pr.Date='$date'
        ORDER BY pr.Time DESC
    ";
    $select_query = mysqli_query($connect, $select);
}
$count = mysqli_num_rows($select_query);

// Fetch sums
$total = mysqli_fetch_assoc(mysqli_query($connect,"SELECT SUM(totalprice) AS sum FROM purchase WHERE Date='$date' AND status='Confirmed'"));
$items = mysqli_fetch_assoc(mysqli_query($connect,"SELECT SUM(Buy_Quantity) AS total_items FROM purchase WHERE Date='$date' AND status='Confirmed'"));
$profit = mysqli_fetch_assoc(mysqli_query($connect,"SELECT SUM(profit) AS total_profit FROM purchase WHERE Date='$date' AND status='Confirmed'"));

$sum = $total['sum'] ?? 0;
$totalsale = $items['total_items'] ?? 0;
$profitsum = $profit['total_profit'] ?? 0;

// Save daily report
if (isset($_POST['btnsave'])) {
    $amount = mysqli_real_escape_string($connect, $_POST['txtprice']);
    $profit1 = mysqli_real_escape_string($connect, $_POST['txtprofit']);
    $date_post = mysqli_real_escape_string($connect, $_POST['txtdate']);
    $time = date("H:i:s");

    $insert = "INSERT INTO daily_report VALUES ('', '$amount', '$profit1', '$date_post', '$time')";
    if (mysqli_query($connect, $insert)) {
        echo "<script>alert('Daily Report SAVED!');</script>";
    } else {
        echo "<script>alert('Date already exists!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Today's Sales Report</title>
<style>
.report-summary { display:flex; gap:20px 36px; flex-wrap:wrap; margin:24px clamp(16px, 3vw, 40px); }
.report-summary div { font-size:17px; }
.today-report-actions { margin:0 clamp(16px, 3vw, 40px) 18px; }
.report-search-form { display:flex; justify-content:flex-end; margin:18px clamp(16px, 3vw, 40px); }
.report-search-controls { display:flex; align-items:center; gap:8px; width:min(100%, 520px); }
.report-search-controls input { flex:1 1 260px; min-width:0; }
.report-results-summary { margin:14px clamp(16px, 3vw, 40px); color:#d5dfed; }
@media(max-width:560px) { .report-search-controls { flex-wrap:wrap; } .report-search-controls input { flex-basis:100%; } }
</style>
</head>
<body>

<form method="POST">
<div class="report-summary">
    <div>Date: <strong><?php echo date('d M Y', strtotime($date)) ?></strong></div>
    <div>Total Amount: <strong><?php echo number_format($sum) ?> Kyats</strong></div>
    <div>Items Sold: <strong><?php echo $totalsale ?></strong></div>
    <div>Profit: <strong><?php echo number_format($profitsum) ?> Kyats</strong></div>
</div>

<input type="hidden" name="txtprice" value="<?php echo $sum ?>">
<input type="hidden" name="txtprofit" value="<?php echo $profitsum ?>">
<input type="hidden" name="txtdate" value="<?php echo $date ?>">

<div>
    <!-- <input type="submit" class="save" name="btnsave" value="Save Daily Report" onclick="return confirm('Save to daily report?')"> -->
    <button type="button" class="print" onclick="window.print()">Print Report</button>
</div>
</form>
<form method="GET" class="report-search-form" role="search">
    <div class="report-search-controls">
        <label class="visually-hidden" for="today-item-search">Search today's sales by product name, barcode, or ID</label>
        <input id="today-item-search" type="search" name="txtitem" placeholder="Name, barcode, or product ID" value="<?php echo htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit" class="btn search" name="btnitem" value="1">Search</button>
        <?php if ($isSearch): ?><a class="search-clear" href="admin_todaysalerp.php">Clear</a><?php endif; ?>
    </div>
</form>
<table class="table">
<thead>
<tr>
    <th>Purchase ID</th>
    <th>Product Name</th>
    <th>Quantity</th>
    <th>Total Price</th>
    <th>Spent Price</th>
    <th>Profit</th>
    <th>Time</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php if ($count > 0): ?>
<?php while ($array = mysqli_fetch_assoc($select_query)):
  $purchaseid = (int)$array['purchaseid'];
  $product_id = $array['Product_id'];
  $productname = $array['Product_name'];
  $time = $array['Time'];
  $sp_qty = (float)$array['sp_price'] * (int)$array['Buy_Quantity'];
  $totalprice = (float)$array['totalprice'];
  $profit = (float)$array['profit'];
  $total_sum += $totalprice;
  $Quantity += (int)$array['Buy_Quantity'];
?>
    <tr>
      <td><?php echo $purchaseid ?></td>
      <td><?php echo htmlspecialchars((string)$product_id, ENT_QUOTES, 'UTF-8') ?> - <?php echo htmlspecialchars($productname, ENT_QUOTES, 'UTF-8') ?></td>
      <td><?php echo (int)$array['Buy_Quantity'] ?></td>
      <td><?php echo number_format($totalprice) ?></td>
      <td><?php echo number_format($sp_qty) ?></td>
      <td><?php echo number_format($profit) ?></td>
      <td><?php echo $time ?></td>
      <td><a class="btn remove-btn" href="itemremovetdysale.php?PID=<?= $purchaseid ?>">Remove</a></td>
      
    </tr>
<?php endwhile; ?>
<?php else: ?>
    <tr><td colspan="8"><?php echo $isSearch ? 'No sales matched your search today.' : 'No confirmed sales today.'; ?></td></tr>
<?php endif; ?>
</tbody>
</table>
<?php if ($isSearch && $count > 0): ?>
<p class="report-results-summary">Matching sales: <?php echo $count; ?> · Items: <?php echo $Quantity; ?> · Total: <?php echo number_format($total_sum); ?> Kyats</p>
<?php endif; ?>
</body>
</html>
