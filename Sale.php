<?php
include('connect.php');
include('navbar.php');
// Fetch existing unconfirmed purchases
$select_purchase = "SELECT p.purchaseid, p.Product_id, pr.Product_name, pr.Price, p.Buy_Quantity, p.totalprice 
                    FROM purchase p 
                    JOIN product pr ON p.Product_id = pr.Product_id 
                    WHERE p.status='Unconfirmed'";
$purchase_query = mysqli_query($connect, $select_purchase);
$sum_result = mysqli_query($connect, "SELECT SUM(totalprice) AS totalsum FROM purchase WHERE status='Unconfirmed'");
$sum_row = mysqli_fetch_assoc($sum_result);
$total_sum = (float)$sum_row['totalsum'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" type="text/css" href="purchase.css">
</head>
<body>

<form action="" method="POST" id="purchaseForm">
    <div class="sale-action">
    <table>
        <tr><td>Barcode</td></tr>
        <tr>
            <td>
                <input class="inputbox" type="text" name="txtbarcode" id="barcode" placeholder="Enter Barcode" autofocus autocomplete="off" required>
            </td>
        </tr>
        <tr><td>Quantity</td></tr>
        <tr>
            <td>
                <input class="inputbox" type="number" name="txtquantity" id="qty" value="1" min="1" required>
            </td>
        </tr>
        <!-- Hidden submit input -->
        <tr><td><input type="submit" name="btnsubmit" id="btnsubmit" style="display:none;"></td></tr>
        <tr>
            <td>
                <input class="inputbox" type="number" id="pay" placeholder="Changes" oninput="calculateChanges()">
            </td>
        </tr>
        <tr>
            <td>
                <div style="font-size:30px;color:white" id="Changes"></div>
            </td>
        </tr>
    </table>
    </div>
</form>

<div class="detail-sale">
    <div class="action-btn">
        <a class="btn submit" href="#" onclick="confirmSale()">Confirm</a>
        <a class="btn clear" href="#" onclick="cancelSale()">Cancel</a>
    </div>

    <table class="table">
        <tr>
            <td colspan="4">
                <p style="font-size:30px;">
                    Total Price: <span id="sum" style="color:green;font-weight:bold;font-size:34px;"><?= number_format($total_sum) ?></span> KS
                </p>
            </td>
        </tr>
        <tr>
            <th>Action</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Total Price</th>
        </tr>
        <tbody id="purchaseTableBody">
            <?php while ($row = mysqli_fetch_assoc($purchase_query)) { ?>
                <tr id="row_<?= $row['purchaseid'] ?>">
                    <td>
                        <a class="link can" href="#" onclick="cancelSingle(<?= $row['purchaseid'] ?>)">Cancel</a>
                    </td>
                    <td><?= $row['Product_id'] ?> - <?= $row['Product_name'] ?></td>
                    <td><?= $row['Price'] ?> Ks * <?= $row['Buy_Quantity'] ?></td>
                    <td><?= $row['totalprice'] ?> Ks</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>

<script>
document.addEventListener("DOMContentLoaded", function(){

    let barcode = document.getElementById("barcode");
    let qty = document.getElementById("qty");

    // Auto-focus barcode on load
    barcode.focus();

    // Enter in barcode → move to quantity
    barcode.addEventListener("keydown", function(e){
        if(e.key === "Enter"){
            e.preventDefault();
            qty.focus();
            qty.select();
        }
    });

    // Enter in quantity → submit via AJAX
    qty.addEventListener("keydown", function(e){
        if(e.key === "Enter"){
            e.preventDefault();
            document.getElementById("purchaseForm").dispatchEvent(new Event('submit'));
        }
    });

    // AJAX form submit
    document.getElementById("purchaseForm").addEventListener("submit", function(e){
        e.preventDefault();

        let barcodeVal = barcode.value;
        let qtyVal = qty.value;

        fetch("purchase_ajax.php",{
            method:"POST",
            headers: {"Content-Type":"application/x-www-form-urlencoded"},
            body: "barcode="+encodeURIComponent(barcodeVal)+"&quantity="+encodeURIComponent(qtyVal)
        })
        .then(res=>res.json())
        .then(data=>{
            if(data.success){

                // Add row to table
                let tbody = document.getElementById("purchaseTableBody");
                let row = document.createElement("tr");
                row.id = "row_" + data.purchase_id;
                row.innerHTML = `
                    <td>
                        <a class="link can" href="#" onclick="cancelSingle(${data.purchase_id})">Cancel</a>
                    </td>
                    <td>${data.product_id} - ${data.product_name}</td>
                    <td>${data.price} Ks * ${data.quantity}</td>
                    <td>${data.total_price} Ks</td>
                `;
                tbody.appendChild(row);

                // Update total sum
                let sumElem = document.getElementById("sum");
                let currentSum = parseFloat(sumElem.innerText.replace(/,/g,'')) || 0;
                currentSum += parseFloat(data.total_price);
                sumElem.innerText = currentSum.toLocaleString();

                // Reset input
                barcode.value = "";
                qty.value = 1;
                barcode.focus();

            } else {
                alert(data.message);
            }
        });
    });

});

// Cancel single row
function cancelSingle(purchase_id){
    

    fetch("purchase_action.php",{
        method:"POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body: "action=cancel_single&pid="+purchase_id
    })
    .then(res=>res.text())
    .then(()=>{
        let row = document.getElementById("row_"+purchase_id);
        if(row) row.remove();

        // Recalculate total
        let sumElem = document.getElementById("sum");
        let total = 0;
        document.querySelectorAll("#purchaseTableBody tr").forEach(tr=>{
            let val = parseFloat(tr.cells[3].innerText.replace(/,/g,''));
            total += val;
        });
        sumElem.innerText = total.toLocaleString();
    });
}

// Confirm all via AJAX
function confirmSale(){
    fetch("purchase_action.php",{
        method:"POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body:"action=confirm"
    })
    .then(res=>res.text())
    .then(()=>{
        // Remove all rows
        document.getElementById("purchaseTableBody").innerHTML="";
        document.getElementById("sum").innerText="0";
        barcode.focus();
    });
}

// Cancel all via AJAX
function cancelSale(){
    
    fetch("purchase_action.php",{
        method:"POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body:"action=cancel"
    })
    .then(res=>res.text())
    .then(()=>{
        document.getElementById("purchaseTableBody").innerHTML="";
        document.getElementById("sum").innerText="0";
        barcode.focus();
    });
}

// Calculate changes
function calculateChanges(){
    let pay = parseFloat(document.getElementById("pay").value) || 0;
    let sum = parseFloat(document.getElementById("sum").innerText.replace(/,/g,'')) || 0;
    let changes = pay - sum;
    document.getElementById("Changes").innerText = changes.toLocaleString();
}
</script>