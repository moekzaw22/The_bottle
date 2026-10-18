<?php include('connect.php');
include('navbar.php');
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Today's Sales</title>
    <style>
        .today-report-toolbar { display:flex; justify-content:flex-end; align-items:center; flex-wrap:wrap; gap:8px; margin:20px clamp(16px, 3vw, 40px) 10px; }
        .today-report-toolbar input { width:min(100%, 420px); }
        .today-report-toolbar .clear-search { min-height:40px; padding:8px 12px; color:#d5dfed; background:#26364d; border:1px solid #3a4b64; border-radius:9px; font:600 14px/1.2 Inter, "Segoe UI", Arial, sans-serif; cursor:pointer; }
        .today-report-toolbar .clear-search:hover { background:#344660; }
        .report-summary { align-items:center; flex-wrap:wrap; gap:12px 32px; margin:18px clamp(16px, 3vw, 40px); }
        .report-summary #message { color:#d5dfed; }
        @media(max-width:560px) { .today-report-toolbar { justify-content:stretch; } .today-report-toolbar input { flex:1 1 100%; width:100%; } }
        @media print { .navbar, .today-report-toolbar { display:none !important; } body { color:#111 !important; background:#fff !important; } }
    </style>
</head>
<body>
<div class="today-report-toolbar" role="search">
    <label class="visually-hidden" for="txtitem">Filter today's sales</label>
    <input type="search" id="txtitem" placeholder="Filter by product name, barcode, or ID" autocomplete="off" aria-label="Filter today's sales">
    <button type="button" class="clear-search" id="clearSearch">Clear</button>
    <button type="button" class="btn print" onclick="window.print()">Print</button>
</div>
<div class="report-summary">
    <div>Date: <strong id="reportDate"><?= date('d M Y') ?></strong></div>
    <div id="message"></div>
</div>

<table class="table">
<thead>
<tr>
    <th>Purchase ID</th>
    <th>Product Name</th>
    <th>Quantity</th>
    <th>Total Price</th>
    <th>Time</th>
</tr>
</thead>
<tbody id="reportBody">
    <!-- Filled dynamically -->
</tbody>
</table>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("txtitem");
    const tbody = document.getElementById("reportBody");
    const message = document.getElementById("message");
    const clearButton = document.getElementById("clearSearch");
    let timeout = null;

    function appendCell(row, value) {
        const cell = document.createElement("td");
        cell.textContent = value;
        row.appendChild(cell);
    }

    function fetchReport() {
        const value = input.value.trim();
        fetch("todaysalerp_ajax.php?txtitem=" + encodeURIComponent(value))
            .then(response => {
                if (!response.ok) throw new Error("Report request failed");
                return response.json();
            })
            .then(data => {
                tbody.replaceChildren();
                if (data.rows.length > 0) {
                    data.rows.forEach(item => {
                        const row = document.createElement("tr");
                        appendCell(row, item.purchaseid);
                        appendCell(row, item.product_id + " - " + item.product_name);
                        appendCell(row, item.quantity);
                        appendCell(row, item.total_price);
                        appendCell(row, item.time);
                        tbody.appendChild(row);
                    });
                    message.textContent = `Total for ${value ? `matching “${value}”` : "all products"}: ${data.total_sum} Kyats · Items sold: ${data.total_qty}`;
                } else {
                    const row = document.createElement("tr");
                    const cell = document.createElement("td");
                    cell.colSpan = 5;
                    cell.textContent = value ? `No sales found matching “${value}”.` : "No sales found today.";
                    row.appendChild(cell);
                    tbody.appendChild(row);
                    message.textContent = "";
                }
            })
            .catch(() => {
                tbody.replaceChildren();
                const row = document.createElement("tr");
                const cell = document.createElement("td");
                cell.colSpan = 5;
                cell.textContent = "Unable to load today's sales. Please try again.";
                row.appendChild(cell);
                tbody.appendChild(row);
                message.textContent = "";
            });
    }

    input.addEventListener("input", function () {
        clearTimeout(timeout);
        timeout = setTimeout(fetchReport, 250);
    });
    input.addEventListener("keydown", event => {
        if (event.key === "Enter") {
            event.preventDefault();
            clearTimeout(timeout);
            fetchReport();
        }
    });
    clearButton.addEventListener("click", function () {
        input.value = "";
        fetchReport();
        input.focus();
    });
    fetchReport();
});
</script>
</body>
</html>
