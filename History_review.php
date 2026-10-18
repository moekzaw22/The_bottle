<?php
include('connect.php');
include('admin_navbar.php');

function valid_history_date($value)
{
    if (!is_string($value) || $value === '') {
        return '';
    }
    $date = DateTime::createFromFormat('!Y-m-d', $value);
    return $date && $date->format('Y-m-d') === $value ? $value : '';
}

$dateFrom = valid_history_date($_GET['date_from'] ?? '');
$dateTo = valid_history_date($_GET['date_to'] ?? '');
$allowedActions = ['Add', 'Canceled', 'Restock'];
$action = (string)($_GET['action'] ?? '');
if (!in_array($action, $allowedActions, true)) {
    $action = '';
}

$pageSize = 25;
$requestedPage = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT);
$requestedPage = $requestedPage && $requestedPage > 0 ? $requestedPage : 1;
$filterSql = "WHERE (? = '' OR h.date_time >= CONCAT(?, ' 00:00:00'))
                AND (? = '' OR h.date_time < DATE_ADD(?, INTERVAL 1 DAY))
                AND (? = '' OR h.Type = ?)";
$countStatement = $connect->prepare("SELECT COUNT(*) AS total FROM history h $filterSql");
$countStatement->bind_param('ssssss', $dateFrom, $dateFrom, $dateTo, $dateTo, $action, $action);
$countStatement->execute();
$totalRecords = (int)$countStatement->get_result()->fetch_assoc()['total'];
$totalPages = max(1, (int)ceil($totalRecords / $pageSize));
$page = min($requestedPage, $totalPages);
$offset = ($page - 1) * $pageSize;

$statement = $connect->prepare(
    "SELECT h.History_ID, h.Type, h.Amount, h.added_to, h.date_time, p.Product_name
     FROM history h
     LEFT JOIN product p ON p.Product_id = h.added_to
     $filterSql
     ORDER BY h.History_ID DESC LIMIT ? OFFSET ?"
);
$statement->bind_param('ssssssii', $dateFrom, $dateFrom, $dateTo, $dateTo, $action, $action, $pageSize, $offset);
$statement->execute();
$history = $statement->get_result();
$resultCount = $history->num_rows;
$filterParams = ['date_from' => $dateFrom, 'date_to' => $dateTo, 'action' => $action];
$previousUrl = '?' . htmlspecialchars(http_build_query($filterParams + ['page' => max(1, $page - 1)]), ENT_QUOTES, 'UTF-8');
$nextUrl = '?' . htmlspecialchars(http_build_query($filterParams + ['page' => min($totalPages, $page + 1)]), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product History</title>
    <style>
        .history-page { width:min(calc(100% - 32px), 1320px); margin:30px auto; }
        .history-heading { display:flex; justify-content:space-between; align-items:end; gap:16px; flex-wrap:wrap; margin-bottom:20px; }
        .history-heading h1 { margin:0; }
        .history-count { color:#a9b8cc; }
        .history-pagination { display:flex; align-items:center; justify-content:center; gap:14px; margin:18px 0 32px; }
        .history-pagination .page-link { min-width:94px; }
        .history-pagination .page-link[aria-disabled="true"] { opacity:.45; pointer-events:none; }
        .history-pagination .page-status { min-width:130px; color:#a9b8cc; text-align:center; }
        .history-filters { display:flex; align-items:end; flex-wrap:wrap; gap:12px; margin-bottom:20px; padding:18px; background:#131e30; border:1px solid #2a3a52; border-radius:14px; }
        .history-filters label { display:grid; gap:5px; min-width:170px; color:#d5dfed; }
        .history-filters input, .history-filters select { width:100%; }
        .history-filter-actions { display:flex; gap:8px; }
        .history-filter-actions .apply-filter { color:#17202b !important; background:#f0b74c !important; border-color:#d8992e !important; }
        .history-filter-actions .reset-filter { color:#edf3fb !important; background:#344660 !important; }
        .history-table-wrap { overflow-x:auto; }
        .history-table { width:100%; min-width:650px; }
        .history-table td:first-child, .history-table th:first-child { width:100px; }
        .history-table td:nth-child(3), .history-table th:nth-child(3) { width:140px; }
        .history-table td:last-child, .history-table th:last-child { width:210px; white-space:nowrap; }
        .history-badge { display:inline-flex; align-items:center; min-height:28px; padding:4px 10px; border-radius:999px; font-size:13px; font-weight:700; }
        .history-badge.add { color:#9ce7c2; background:#14553e; }
        .history-badge.canceled { color:#ffc1c5; background:#612c36; }
        .history-badge.restock { color:#f8d88d; background:#59431c; }
        .history-badge.other { color:#d5dfed; background:#344660; }
        .history-empty { color:#a9b8cc !important; text-align:center; padding:30px !important; }
        @media(max-width:600px) { .history-page { width:calc(100% - 24px); margin:20px auto; } .history-filters label { flex:1 1 100%; } .history-filter-actions { width:100%; } .history-filter-actions button { flex:1; } }
    </style>
</head>
<body>
<main class="history-page">
    <div class="history-heading">
        <h1>Product History</h1>
        <div class="history-count"><?= number_format($totalRecords) ?> records</div>
    </div>

    <form method="get" class="history-filters" role="search">
        <label>
            From date
            <input type="date" name="date_from" value="<?= htmlspecialchars($dateFrom, ENT_QUOTES, 'UTF-8') ?>">
        </label>
        <label>
            To date
            <input type="date" name="date_to" value="<?= htmlspecialchars($dateTo, ENT_QUOTES, 'UTF-8') ?>">
        </label>
        <label>
            Action
            <select name="action">
                <option value="" <?= $action === '' ? 'selected' : '' ?>>All actions</option>
                <?php foreach ($allowedActions as $historyAction): ?>
                    <option value="<?= htmlspecialchars($historyAction, ENT_QUOTES, 'UTF-8') ?>" <?= $action === $historyAction ? 'selected' : '' ?>><?= htmlspecialchars($historyAction, ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <div class="history-filter-actions">
            <button class="apply-filter" type="submit">Apply filters</button>
            <a class="reset-filter btn" href="History_review.php">Reset</a>
        </div>
    </form>

    <div class="history-table-wrap">
        <table class="table history-table">
            <thead>
                <tr><th>ID</th><th>Action</th><th>Product</th><th>Amount</th><th>Date and time</th></tr>
            </thead>
            <tbody>
                <?php if ($resultCount === 0): ?>
                    <tr><td class="history-empty" colspan="5">No history records match these filters.</td></tr>
                <?php else: ?>
                    <?php while ($row = $history->fetch_assoc()):
                        $type = (string)$row['Type'];
                        $badgeClass = match (strtolower($type)) {
                            'add' => 'add',
                            'canceled' => 'canceled',
                            'restock' => 'restock',
                            default => 'other',
                        };
                    ?>
                        <tr>
                            <td><?= (int)$row['History_ID'] ?></td>
                            <td><span class="history-badge <?= $badgeClass ?>"><?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?></span></td>
                            <td><?= htmlspecialchars($row['Product_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars((string)($row['Amount'] ?? 'N/A'), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars(date('d M Y, h:i A', strtotime($row['date_time'])), ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <nav class="history-pagination" aria-label="History pages">
        <a class="btn page-link" href="<?= $previousUrl ?>" aria-disabled="<?= $page <= 1 ? 'true' : 'false' ?>" tabindex="<?= $page <= 1 ? '-1' : '0' ?>">Previous</a>
        <span class="page-status" aria-live="polite">Page <?= $page ?> of <?= $totalPages ?></span>
        <a class="btn page-link" href="<?= $nextUrl ?>" aria-disabled="<?= $page >= $totalPages ? 'true' : 'false' ?>" tabindex="<?= $page >= $totalPages ? '-1' : '0' ?>">Next</a>
    </nav>
</main>
</body>
</html>
