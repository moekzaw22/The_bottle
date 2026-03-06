<?php include('connect.php');
include('navbar.php');
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
</head>
<body>
<style>
    .report-summary { display:flex; gap:100px; margin:20px }
.report-summary div { font-size:20px; }
.print { padding:5px;font-size:16px;border:none;border-radius:5px;margin:5px;background:#007bff; color:white; cursor:pointer; }
a { color:#f0f0f0; text-decoration:none; }
</style>
<form method="GET">
    <div style="position: absolute;right:10px;margin:10px;top:14%">
<input type="text" id="txtitem" placeholder="Item" style="padding:10px" autocomplete="off" autofocus>
</div>
</form>
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
</body>
</html>
<script>
   
document.addEventListener("DOMContentLoaded", function(){

    const input = document.getElementById("txtitem");
    const tbody = document.getElementById("reportBody");
    const message = document.getElementById("message");

    function fetchReport(){
        let val = input.value;

        fetch("todaysalerp_ajax.php?txtitem="+encodeURIComponent(val))
        .then(res => res.json())
        .then(data => {
            tbody.innerHTML = '';

            if(data.rows.length > 0){
                data.rows.forEach(row => {
                    let tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${row.purchaseid}</td>
                        <td>${row.product_id} - ${row.product_name}</td>
                        <td>${row.quantity}</td>
                        <td>${row.total_price}</td>
                        <td>${row.time}</td>
                    `;
                    tbody.appendChild(tr);
                });

                message.innerText = `Total for ${val ? "product matching '"+val+"'" : "all products"} = ${data.total_sum} Kyats, Items Sold: ${data.total_qty}`;
            } else {
                let tr = document.createElement('tr');
                tr.innerHTML = `<td colspan="5">No sales found for ${val ? "product matching '"+val+"'" : "today"}</td>`;
                tbody.appendChild(tr);
                message.innerText = '';
            }
        });
    }

    // Initial load
    fetchReport();

    // Fetch on typing (with small delay to avoid too many requests)
    let timeout = null;
    input.addEventListener("input", function(){
        clearTimeout(timeout);
        timeout = setTimeout(fetchReport, 300); // 300ms delay
    });

    // Focus and select input
    input.focus();
    input.select();
});
</script>
