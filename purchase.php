<?php 
include('connect.php');
include('navbar.php');

date_default_timezone_set("Asia/Yangon");

// Handle adding product
if (isset($_POST['btnsubmit'])) {
    $barcode = $_POST['txtbarcode'];
    $quantity = (int)$_POST['txtquantity'];

    $select = "SELECT Product_id, Product_code, sp_price, Price 
               FROM product 
               WHERE Product_code='$barcode' OR Product_id='$barcode'";
    $query = mysqli_query($connect, $select);

    if (mysqli_num_rows($query) > 0) {
        $array = mysqli_fetch_assoc($query);
        $price = (float)$array['Price'];
        $sp_price = (float)$array['sp_price'];
        $product_id = $array['Product_id'];

        $total_price = $price * $quantity;
        $profit = $total_price - ($sp_price * $quantity);

        $date = date('Y-m-d');
        $time = date("H:i:s");

        $insert = "INSERT INTO purchase 
                   VALUES('', '$product_id', '$quantity', '$total_price', '$profit', '$date', '$time', 'Unconfirmed')";
        mysqli_query($connect, $insert);

        // Reload page to avoid form resubmission
        // header("Location: " . $_SERVER['PHP_SELF']);
        // exit;
    } else {
        echo "<script>alert('Wrong Code')</script>";
    }
}

// Handle Confirm / Cancel / Cancel single
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'confirm') {
        mysqli_query($connect, "UPDATE purchase SET status='Confirmed' WHERE status='Unconfirmed'");
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if ($action == 'cancel') {
        mysqli_query($connect, "DELETE FROM purchase WHERE status='Unconfirmed'");
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if ($action == 'cancel_single' && isset($_GET['PID'])) {
        $pid = (int)$_GET['PID'];
        $select = mysqli_query($connect, "SELECT pr.Product_id, pr.Buy_Quantity 
                                         FROM purchase pr 
                                         LEFT JOIN product p ON p.Product_id=pr.Product_id 
                                         WHERE pr.purchaseid=$pid");
        if ($select && mysqli_num_rows($select) > 0) {
            $row = mysqli_fetch_assoc($select);
            $product_id = $row['Product_id'];
            $buyquantity = $row['Buy_Quantity'];

            mysqli_query($connect, "INSERT INTO history (type, Amount, added_to, Date_time) 
                                    VALUES ('Canceled', $buyquantity, $product_id, NOW())");

            mysqli_query($connect, "UPDATE purchase SET status='Canceled' WHERE purchaseid=$pid");
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch purchases for display
$select_purchase = "SELECT p.purchaseid, p.Product_id, pr.Product_name, pr.Price, p.Buy_Quantity, p.totalprice 
                    FROM purchase p 
                    JOIN product pr ON p.Product_id = pr.Product_id 
                    WHERE p.status='Unconfirmed'";
$purchase_query = mysqli_query($connect, $select_purchase);
$purchase_count = mysqli_num_rows($purchase_query);

// Total sum
$sum_result = mysqli_query($connect, "SELECT SUM(totalprice) AS totalsum FROM purchase WHERE status='Unconfirmed'");
$sum_row = mysqli_fetch_assoc($sum_result);
$total_sum = (float)$sum_row['totalsum'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sale</title>
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
            <a class="btn submit" href="?action=confirm">Confirm</a>
            <a class="btn clear" href="?action=cancel">Cancel</a>
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
            <?php while ($row = mysqli_fetch_assoc($purchase_query)) { ?>
                <tr>
                	<td><a class="link can" href="?action=cancel_single&PID=<?= $row['purchaseid'] ?>">Cancel</a></td>
                    <td><?= $row['Product_id'] ?> - <?= $row['Product_name'] ?></td>
                    <td><?= $row['Price'] ?> Ks * <?= $row['Buy_Quantity'] ?></td>
                    <td><?= $row['totalprice'] ?> Ks</td>
                    
                </tr>
            <?php } ?>
        </table>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let barcode = document.getElementById("barcode");
    let qty = document.getElementById("qty");
    let form = document.getElementById("purchaseForm");

    // Auto-focus barcode on load
    barcode.focus();
    barcode.select();

    // Enter in barcode → go to quantity
    barcode.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            qty.focus();
            qty.select();
        }
    });

    // Enter in quantity → submit form
    qty.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            document.getElementById("btnsubmit").click(); // trigger hidden submit
        }
    });
});

// Calculate changes
function calculateChanges() {
    let pay = parseFloat(document.getElementById("pay").value) || 0;
    let sum = <?= $total_sum ?>;
    let changes = pay - sum;
    document.getElementById("Changes").innerText = changes.toLocaleString('en-US');
}
</script>
</body>
</html>