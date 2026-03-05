<?php 
$connect=mysqli_connect("Localhost","root","","the_bottle_database");
include('navbar.php');
date_default_timezone_set("Asia/Yangon");
if (isset($_POST['btnsave'])) {
	$barcode=$_POST['txtbarcode'];
	$quantity=$_POST['txtquantity'];

	$select="SELECT Product_id, Product_code, sp_price, Price FROM product WHERE Product_code='$barcode' OR Product_id='$barcode'";
	$query=mysqli_query($connect,$select);
	if (mysqli_num_rows($query) > 0) {
		$array=mysqli_fetch_array($query);
	$price=$array['Price'];
	$date=date('Y-m-d');
	$time=date("H:i:s");
	$f_profit = $array['sp_price'];
	$s_profit = $f_profit * $quantity;
	$product_id = $array['Product_id'];
	$total_price=$price * $quantity;
	$f_profit = $total_price - $s_profit;
	$insert="INSERT INTO purchase VALUES('','$product_id','$quantity','$total_price','$f_profit','$date','$time','Unconfirmed')";
	$insert_query=mysqli_query($connect,$insert);
	header("Location: " . $_SERVER['PHP_SELF']);
    exit;
	}else{
		echo "No Product LIKE ".$barcode. " Found";
	}
	
}if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'confirm') {
        // Confirm all unconfirmed purchases
        mysqli_query($connect, "UPDATE purchase SET status='Confirmed' WHERE status='Unconfirmed'");
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if ($action == 'cancel') {
        // Delete all unconfirmed purchases
        mysqli_query($connect, "DELETE FROM purchase WHERE status='Unconfirmed'");
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if ($action == 'cancel_single' && isset($_GET['PID'])) {
        $pid = intval($_GET['PID']);
        $PID=$_GET['PID'];
	$select = mysqli_query($connect,"SELECT pr.Product_id,pr.status,pr.purchaseid,pr.Buy_Quantity FROM purchase pr LEFT JOIN product p ON p.Product_id=pr.Product_id WHERE pr.purchaseid=$PID");
	if ($select && mysqli_num_rows($select) > 0) {
		$row = mysqli_fetch_assoc($select);
		$product_id = $row['Product_id'];
		$buyquantity = $row['Buy_Quantity'];
		mysqli_query($connect,"INSERT INTO history (type,Amount,added_to,Date_time) VALUES ('Canceled',$buyquantity,$product_id,Now())");
	$purchase_Query="UPDATE purchase SET status = 'Canceled' WHERE purchaseid=$PID";
	$purchase_ret=mysqli_query($connect,$purchase_Query);
	
	}	
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Sale</title>
	<link rel="stylesheet" type="text/css" href="purchase.css">
</head>
<body>
	<form action="purchase.php" method="POST">

	<table style="position:absolute;left:5%;top:20%;">
		<tr>
			<td>Barcode</td>
		</tr>
		<tr>
			<td><input class="inputbox" id="barcode" type="number" name="txtbarcode" onkeypress="return enterkeyPressed(event)" placeholder="Enter Barcode" autofocus="on" autocomplete="off" required/></td>
		</tr>
		<tr>
			<td>Quantity</td>
		</tr>
		<tr>
			<td><input class="inputbox"
       type="number"
       name="txtquantity"
       id="qty"
       value="1"
       min="1"></td>
		</tr>
		<tr>
			<td><input type="submit" name="btnsave" hidden/></td>
		</tr>
		 		<tr>
 			<td><input class="inputbox" type="number" id="pay" name="" placeholder="Changes" oninput="CheckTheChanges()"></td>
 		</tr>
 		
 		<tr>
 			<td><div style="font-size:30px" id="Changes">
	
</div></td>
 		</tr>

	</table>
	<?php 

		
 $select="SELECT * FROM purchase p,product pr WHERE p.Product_id=pr.Product_id AND p.status='Unconfirmed'";
 $select_query=mysqli_query($connect,$select);
 $count=mysqli_num_rows($select_query);

$select_price="SELECT SUM(totalprice) AS totalsum FROM purchase WHERE status='Unconfirmed'";
$result=mysqli_query($connect,$select_price);
$row1=mysqli_fetch_array($result);
$row = mysqli_fetch_assoc($result); 

$sum = $row1['totalsum'];
?>
</form>
<div class="detail-sale">
	<div class="action-btn">
		<form method="GET">
	<a class="link con" accesskey="C" href="?action=confirm">Confirm</a>
 				<a class="link can" href="?action=cancel">Cancel</a>
</div>


<table class="table-1">
	<tr>
 			<td colspan="4"><p style="font-size:30px;">Total Price is <span id="sum" style="color:green;font-weight: bolder;font-size:34px;">
 				<?php echo number_format($sum) ?></span> KS</p></td>
 		</tr>
		<tr>
			<th>Product Name</th>
			<th>Price</th>
			<th>Total price</th>
			<th></th>
		</tr>
<?php

 for ($i=0; $i < $count; $i++) { 
 	$array=mysqli_fetch_array($select_query);
 	$purchaseid=$array['purchaseid'];
 	$product_id = $array['Product_id'];
 	?>
		<tr class="item">
			<td><?php echo $product_id ?> - <?php echo $array['Product_name'] ?></td>
			<td><?php echo $array['Price']  ?> &nbsp;Ks * <?php echo $array['Buy_Quantity'] ?></td>
		
			<td><?php echo $array['totalprice'] ?> &nbsp;Ks</td>
			<td>  <a class="link can" href="?action=cancel_single&PID=<?= $purchaseid ?>"> Cancel</a></td>
		</tr>
		<?php 
}
		 ?>
 		
	</table>
		</div>
	
	</form>
</body>
</html>
<script type="text/javascript">
	function checkchanges(event){
		if (event.keyCode == 13) {
		pay= document.getElementById('pay').value;
		sum= document.getElementById('sum').value;
		changes = pay - <?php echo $sum ?>;
		document.getElementById('Changes').innerHTML="Changes = " + changes;
	}
	} function CheckTheChanges() {
            var pay = document.getElementById('pay').value;
            var sum = <?php echo $sum ?>; // Fetch PHP variable $sum for total amount

            if (!isNaN(pay)) { // Check if pay is a number
                var changes = pay - sum;
                var formattedChanges = changes.toLocaleString('en-US'); // Format changes with commas
                document.getElementById('Changes').innerHTML = formattedChanges;
            }
        }
	
document.getElementById("qty").addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
        this.form.submit();
    }
});
function enterkeyPressed(e) {
    if (e.key === "Enter") {
        e.preventDefault(); // stop form submit
        document.getElementById("qty").focus();
        document.getElementById("qty").select();
        return false;
    }
}
</script>