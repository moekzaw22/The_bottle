<?php 
include('connect.php');
include('navbar.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<link rel="stylesheet" type="text/css" href="product_list.css">
	<link href="fontawesome/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet">
</head>
<body>
<style type="text/css">
</style>
	<form action="product_list.php" method="GET">
  	<div class="mydiv1"><input type="text" name="txtproductname" class="input" placeholder="find something" autofocus="On" value="<?php echo isset($_GET['txtproductname']) ? $_GET['txtproductname'] : '' ?>"><button class="search-btn" name ="btnsubmit" type="submit">Search</button></div>
	<br>
	 <table class="table">
		<tr>
			<th>Product ID</th>	
			<th>Product Code</th>
			<th>Product Name(milliliter)</th>
			<th>Quantity</th>
			<th>Price</th>
		</tr>
		<?php
		$itemperpage = 60;
if (isset($_GET["page"])) {    
    $page = $_GET["page"];    
}
else { 
    $page = 1;    
}  
if(isset($_GET['btnsubmit'])) {
	$pname=$_GET['txtproductname'];
	$query=mysqli_query($connect,"SELECT Product_id,Product_code,Product_name,sp_price, amount, Product_type, Quantity ,Price  FROM product WHERE Product_name LIKE '$pname%' OR Product_id = '$pname' OR Product_code = '$pname' ORDER BY Product_name ASC");
	$count1=mysqli_num_rows($query);

	 
		if ($count1 > 0) {
				for ($i=0; $i < $count1 ; $i++) { 
				$row=mysqli_fetch_array($query);
				$product_id=$row['Product_id'];
				$quantity=$row['Quantity'];
				$price=$row['Price'];
				$spprice = $row['sp_price'];
				$productcode = $row['Product_code'];
				$productname = $row['Product_name'];
				$amount = $row['amount'];
				$quantity = $row['Quantity'];
				
				$profit=$price - $spprice;  
				?>
				<tr>
					<td><?php echo $product_id ?></td>
					<td><?php echo $productcode ?></td>
					<td><?php echo $productname ?>(<?php echo $amount ?>)</td>	
					<td><?php echo $quantity ?>Stock</td>
					<td><?php echo number_format($price) ?>&nbsp;&nbsp;</td>
				
				</tr>
				<?php
			}
		}
		elseif ($count1 < 1) {
			echo "<tr>";
			echo "<td colspan='9'><i class='fas fa-exclamation-triangle'></i>There is no match!</td>";
			echo "</tr>";
		}	
}
 
else{

			$select="SELECT  Product_id,Product_code,Product_name,sp_price, amount, Product_type, Quantity ,Price FROM product ORDER BY Product_id DESC LIMIT 0,20";
			$select_query=mysqli_query($connect,$select);
			$count=mysqli_num_rows($select_query);
			
			
			for ($i=0; $i < $count ; $i++) { 
				$select_array=mysqli_fetch_array($select_query);
				$product_id=$select_array['Product_id'];	
				$quantity=$select_array['Quantity'];
				$price=$select_array['Price'];
				$productcode = $select_array['Product_code'];
				$productname=$select_array['Product_name'];
				$amount = $select_array['amount'];
				$spprice = $select_array['sp_price'];
				$totalprice=$price - $spprice;  
				?>
				<tr>
					<td><?php echo $product_id ?></td>
					<td><?php echo $productcode ?></td>
					<td><?php echo $productname ?> (<?php echo $amount ?>)</td>
					<td><?php echo $quantity ?>&nbsp;&nbsp;</td>
					<td><?php echo number_format($price) ?> </td>
				
				</tr>
				<?php
			}
			}
		 ?>
	</table>
</form>

</body>
</html>