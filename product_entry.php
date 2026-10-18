<?php 
include('navbar.php');
include('connect.php');
$entrySuccess = false;
$entryError = '';
if (isset($_POST['btnadd'])) {
	$code = trim((string)($_POST['txtproductcode'] ?? ''));
	$name = trim((string)($_POST['txtproductname'] ?? ''));
	$type = trim((string)($_POST['txtproducttype'] ?? ''));
	$quantity = filter_var($_POST['txtquantity'] ?? null, FILTER_VALIDATE_INT);
	$priceValue = $_POST['txtprice'] ?? null;
	$spPriceValue = $_POST['txtspprice'] ?? null;
	$amount = trim((string)($_POST['txtamount'] ?? ''));
	$bottleType = trim((string)($_POST['txtbottletype'] ?? ''));
	$price = is_numeric($priceValue) ? (float)$priceValue : NAN;
	$spPrice = is_numeric($spPriceValue) ? (float)$spPriceValue : NAN;
	$amountValid = $amount === '' || (is_numeric($amount) && is_finite((float)$amount) && (float)$amount >= 0);

	if ($code === '' || $name === '' || $type === '' || $bottleType === '' || $quantity === false || $quantity < 0
		|| !is_finite($price) || $price < 0 || !is_finite($spPrice) || $spPrice < 0 || !$amountValid) {
		$entryError = 'Enter valid product details. Quantity and prices must be zero or greater.';
	} else {
		try {
			$insert = $connect->prepare('INSERT INTO product VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)');
			$insert->bind_param('sssiddss', $code, $name, $type, $quantity, $spPrice, $price, $amount, $bottleType);
			$entrySuccess = $insert->execute();
		} catch (Throwable $error) {
			$entrySuccess = false;
		}
		if (!$entrySuccess) {
			$entryError = 'Product could not be added. Check the product code and try again.';
		}
	}
}
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Product Entry</title>
	<link rel="stylesheet" type="text/css" href="product_entry.css">
 </head>
 <body>
	<?php if ($entrySuccess): ?>
	<div class="messagebox" id="msgbox" role="dialog" aria-modal="true" aria-labelledby="msgTitle">
	  <div class="msg-content">
	    <div class="msg-header" id="msgTitle">Success!</div>
	    <div class="msg-body">Product successfully added.</div>
	    <button class="msg-btn" type="button" onclick="redirect()">Ok</button>
	  </div>
	</div>
	<?php elseif ($entryError !== ''): ?>
	<div class="entry-error" role="alert"><?= htmlspecialchars($entryError, ENT_QUOTES, 'UTF-8') ?></div>
	<?php endif; ?>
	<form action="product_entry.php" method="POST" class="product-entry-form">
 		<div class="container-productentry">
		<div class="entry-heading">
			<div class="entry-mark" aria-hidden="true">+</div>
			<div>
				<h1>Product Entry</h1>
				
			</div>
		</div>
		<div class="product-entry-grid">
			<div class="product-entry-field">
				<label for="txtproductname">Product Name</label>
				<input type="text" id="txtproductname" class="inputbox" name="txtproductname" autocomplete="off" required/>
 			</div>
			<div class="product-entry-field">
				<label for="txtproductcode">Product Code</label>
				<input type="text" id="txtproductcode" class="inputbox" name="txtproductcode" autocomplete="off" required/>
 			</div>
			<div class="product-entry-field">
				<label for="txtproducttype">Product Type</label>
					<select id="txtproducttype" class="inputbox" name="txtproducttype">
			 		<option>Beer</option>
			 		<option>Wine</option>
			 		<option>Gin</option>
			 		<option>Brandy</option>
			 		<option>Cieggrette</option>
			 		<option>Ciger</option>
			 		<option>Whiskey</option>
			 		<option>Rum</option>
			 		<option>Tequila</option>
			 		<option>Vodka</option>
			 		<option>Others</option>
			 		</select>
		 	</div>
			<div class="product-entry-field">
				<label for="txtquantity">Quantity</label>
				<input type="number" id="txtquantity" class="inputbox" name="txtquantity" min="0" step="1" autocomplete="off" required/>
		 	</div>
			<div class="product-entry-field">
				<label for="txtspprice">Spent Price</label>
				<input type="number" id="txtspprice" class="inputbox" name="txtspprice" min="0" step="0.01" autocomplete="off" required>
		 	</div>
			<div class="product-entry-field">
				<label for="txtprice">Price</label>
				<input type="number" id="txtprice" class="inputbox" name="txtprice" min="0" step="0.01" autocomplete="off" required/>
		 	</div>
			<div class="product-entry-field">
				<label for="txtamount">Amount (ml)</label>
				<input type="number" id="txtamount" class="inputbox" name="txtamount" min="0" step="0.01" autocomplete="off">
		 	</div>
		
			<div class="product-entry-field">
				<label for="txtbottletype">Bottle Type</label>
				<select id="txtbottletype" name="txtbottletype" class="inputbox">
	 			<option>Plastic</option>
	 			<option>Can</option>
	 			<option>glass</option>
	 			<option>Metal</option>
	 			<option>Other</option>
	 			</select>
		 	</div>
		</div>
		<div class="product-entry-actions">
		 		<input type="submit" name="btnadd" class="add" value="Add">
	 		<input type="reset" class="refresh" value="Refresh" name="">
		<a href="product_list.php" class="product_list_link">View Product List</a>
		</div>


</div>
</form>
<script>
function redirect() { window.location = 'product_entry.php'; }
document.addEventListener('DOMContentLoaded', function () {
    const message = document.getElementById('msgbox');
    if (message) {
        message.addEventListener('click', function (event) { if (event.target === message) redirect(); });
        document.addEventListener('keydown', function (event) { if (event.key === 'Escape') redirect(); });
    }
});
</script>
 </body>
 </html>
