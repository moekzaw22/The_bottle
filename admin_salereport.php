<?php
include('connect.php');
include('admin_navbar.php');
date_default_timezone_set('Asia/Yangon');

$searchItem = trim((string)($_GET['txtitem'] ?? ''));
$date1 = trim((string)($_GET['txtdate1'] ?? ''));
$date2 = trim((string)($_GET['txtdate2'] ?? ''));
$submitted = isset($_GET['btnsearch']);
$matches = null;
$totals = ['totalsum' => 0, 'totalsale' => 0, 'totalprofit' => 0];
$itemCount = 0;
$searchError = '';
$searchHeading = '';

$isValidDate = static function (string $value): bool {
    if ($value === '') {
        return false;
    }
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
    return $date !== false && $date->format('Y-m-d') === $value;
};

if ($submitted) {
    $validDate1 = $date1 !== '' && $isValidDate($date1);
    $validDate2 = $date2 !== '' && $isValidDate($date2);

    if (($date1 !== '' && !$validDate1) || ($date2 !== '' && !$validDate2)) {
        $searchError = 'Enter valid dates to search.';
    } elseif ($searchItem === '' && !$validDate1 && !$validDate2) {
        $searchError = 'Enter a product name, code, or ID, or select a date.';
    } else {
        $conditions = ["pr.status = 'Confirmed'"];
        $params = [];
        $types = '';

        if ($validDate1 && $validDate2) {
            if ($date1 > $date2) {
                [$date1, $date2] = [$date2, $date1];
            }
            $conditions[] = 'pr.Date BETWEEN ? AND ?';
            $params[] = $date1;
            $params[] = $date2;
            $types .= 'ss';
            $searchHeading = 'Searching between ' . date('d M Y', strtotime($date1)) . ' and ' . date('d M Y', strtotime($date2));
        } elseif ($validDate1 || $validDate2) {
            $selectedDate = $validDate1 ? $date1 : $date2;
            $conditions[] = 'pr.Date = ?';
            $params[] = $selectedDate;
            $types .= 's';
            $searchHeading = 'Searching date: ' . date('d M Y', strtotime($selectedDate));
        }

        if ($searchItem !== '') {
            $conditions[] = '(p.Product_name LIKE ? OR p.Product_code = ? OR p.Product_id = ?)';
            $params[] = '%' . $searchItem . '%';
            $params[] = $searchItem;
            $params[] = $searchItem;
            $types .= 'sss';
            $searchHeading = $searchHeading !== ''
                ? 'Searching product “' . $searchItem . '” · ' . $searchHeading
                : 'Searching product “' . $searchItem . '”';
        }

        $where = implode(' AND ', $conditions);
        $bindValues = static function (mysqli_stmt $statement, string $bindTypes, array &$values): void {
            $arguments = [$bindTypes];
            foreach ($values as $index => &$value) {
                $arguments[] = &$value;
            }
            call_user_func_array([$statement, 'bind_param'], $arguments);
        };

        $rowsStatement = $connect->prepare(
            "SELECT pr.purchaseid, pr.Product_id, p.Product_name, pr.Buy_Quantity,
                    pr.totalprice, pr.Date, pr.Time, pr.profit
             FROM purchase pr
             JOIN product p ON p.Product_id = pr.Product_id
             WHERE $where
             ORDER BY pr.Date DESC, pr.Time DESC, pr.purchaseid DESC"
        );
        $bindValues($rowsStatement, $types, $params);
        $rowsStatement->execute();
        $matches = $rowsStatement->get_result();
        $itemCount = $matches->num_rows;

        $totalsStatement = $connect->prepare(
            "SELECT SUM(pr.totalprice) AS totalsum,
                    SUM(pr.Buy_Quantity) AS totalsale,
                    SUM(pr.profit) AS totalprofit
             FROM purchase pr
             JOIN product p ON p.Product_id = pr.Product_id
             WHERE $where"
        );
        $bindValues($totalsStatement, $types, $params);
        $totalsStatement->execute();
        $totals = $totalsStatement->get_result()->fetch_assoc() ?: $totals;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Sales Report</title>
    <link rel="stylesheet" href="salereport.css">
    <link href="fontawesome/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet">
    <style>
        .admin-report-search { display:flex; justify-content:flex-end; margin:22px clamp(16px, 3vw, 40px); }
        .admin-report-search-controls { display:flex; align-items:center; flex-wrap:wrap; gap:8px; width:min(100%, 900px); }
        .admin-report-search-controls .input { flex:1 1 180px; min-width:0; }
        .report-heading, .report-message { margin:18px clamp(16px, 3vw, 40px); }
        .report-heading { color:#f4f7fc; }
        .report-message { color:#f0b74c; }
        .report-summary { margin:14px clamp(16px, 3vw, 40px); }
        .report-table-wrap { max-width:100%; overflow-x:auto; padding:0 16px 24px; }
        .report-table-wrap .table { min-width:760px; }
        @media(max-width:560px) { .admin-report-search-controls .input { flex-basis:100%; } }
    </style>
</head>
<body>
    <form action="admin_salereport.php" method="GET" class="admin-report-search" role="search">
        <div class="admin-report-search-controls">
            <input type="search" name="txtitem" class="input" aria-label="Search sales by product name, code, or ID" placeholder="Product name, barcode, or ID" value="<?= htmlspecialchars($searchItem, ENT_QUOTES, 'UTF-8') ?>">
            <input type="date" name="txtdate1" class="input" aria-label="Start date" value="<?= htmlspecialchars($date1, ENT_QUOTES, 'UTF-8') ?>">
            <input type="date" name="txtdate2" class="input" aria-label="End date" value="<?= htmlspecialchars($date2, ENT_QUOTES, 'UTF-8') ?>">
            <button class="btn search" name="btnsearch" value="1" type="submit">Search</button>
            <?php if ($submitted): ?><a class="search-clear" href="admin_salereport.php">Clear</a><?php endif; ?>
        </div>
    </form>

    <?php if ($submitted && $searchHeading !== ''): ?>
        <h2 class="report-heading"><?= htmlspecialchars($searchHeading, ENT_QUOTES, 'UTF-8') ?></h2>
    <?php endif; ?>
    <?php if ($searchError !== ''): ?>
        <p class="report-message"><?= htmlspecialchars($searchError, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <?php if ($matches !== null && $itemCount > 0): ?>
        <div class="report-summary">
            Total Amount: <?= number_format((float)$totals['totalsum']) ?> /
            Total Profit: <?= number_format((float)$totals['totalprofit']) ?> /
            Items Sold: <?= number_format((int)$totals['totalsale']) ?>
        </div>
    <?php endif; ?>

    <div class="report-table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Buy Quantity</th>
                    <th>Total Price</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Profit</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($matches === null): ?>
                    <tr><td colspan="8">Choose a product or date to search sales.</td></tr>
                <?php elseif ($itemCount === 0): ?>
                    <tr><td colspan="8">No confirmed sales matched your search.</td></tr>
                <?php else: ?>
                    <?php while ($row = $matches->fetch_assoc()): ?>
                        <tr>
                            <td><?= (int)$row['purchaseid'] ?></td>
                            <td><?= htmlspecialchars($row['Product_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= (int)$row['Buy_Quantity'] ?></td>
                            <td><?= number_format((float)$row['totalprice']) ?> Ks</td>
                            <td><?= htmlspecialchars(date('d M Y', strtotime($row['Date'])), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['Time'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= number_format((float)$row['profit']) ?> Ks</td>
                            <td><a class="btn remove-btn" href="itemremoveallsale.php?PID=<?= (int)$row['purchaseid'] ?>" onclick="return confirm('Remove this sale record?')">Remove</a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
