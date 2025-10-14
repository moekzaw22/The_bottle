<?php 
include('connect.php');
include('admin_navbar.php');

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<link rel="stylesheet" type="text/css" href="admin_product_list.css">
	<link href="fontawesome/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet">
</head>
<body>

<style type="text/css">
</style>
	<form action="admin_product_list.php" method="POST">
		<!-- <input type="text" id="productname" name="txtproductname">
		<input type="submit" name="btnsubmit">
		 -->
  <div style="float:right;padding-top: 10px;padding-bottom: 10px"><input type="text" name="txtproductname" class="input" placeholder="find something" autofocus="On"><button class="search" name ="btnsubmit" type="submit"><i class="fas fa-search fa-1x"></i></button></div>

	
	<br>


	 <table class="table">
		<tr>
			
			<td>Product ID</td>	
			<td>Product Code</td>
			<td>Product Name(milliliter)</td>
			<td>Quantity</td>
			<td>Sp Price</td>
			<td>Price</td>
			<td>Profit</td>
			<td>Action</td>
		</tr>

		<?php
		$itemperpage = 60;

if (isset($_GET["page"])) {    
    $page = $_GET["page"];    
}
else { 
    $page = 1;    
}  
 
		if(isset($_POST['btnsubmit'])) {
	$pname=$_POST['txtproductname'];
	$query=mysqli_query($connect,"SELECT Product_id,Product_code,Product_name,sp_price, amount, Product_type, Quantity ,Price  FROM product WHERE Product_name LIKE '%$pname%' OR Product_id = '$pname' ORDER BY Product_name");
	$count1=mysqli_num_rows($query);
	 $lastpage = ceil($count1 / $itemperpage);
	 if (empty($_POST['txtproductname'])) {
	 	echo "KeyWord Empty !";
	 	echo "Showing All result";
	 }
	 else{


		if ($count1 > 0) {
				for ($i=0; $i < $count1 ; $i++) { 
				$row=mysqli_fetch_array($query);
				$product_id=$row['Product_id'];
				$quantity=$row['Quantity'];
				$productcode = $row['Product_code'];
				$productname = $row['Product_name'];
				$price=$row['Price'];
				$amount = $row['amount'];
				$spprice = $row['sp_price'];
				$profit=$price - $spprice;  
				?>
				<tr>
					<td><?php echo $product_id ?></td>
					<td><?php echo $productcode ?></td>
					<td><?php echo $productname ?>(<?php echo $amount ?>)</td>	
					<td><?php echo $quantity ?>Stock</td>
					<td><?php echo $spprice ?></td>
					<td><?php echo number_format($price) ?>&nbsp;&nbsp;</td>
					<td><?php echo $profit ?></td>
					<td><?php echo "<a href='product_del.php?PID='.$product_id.'>Del</a>" ?>||<a href="restockwithlink.php?PID=<?php echo $product_id ?>" class="linkrestock">Restock  </a>
						|| 
						<a href="productedit.php?PID=<?php echo $product_id ?>" class="linkrestock">Edit Product</a>
					</td>
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
 }
else{
			$select="SELECT  Product_id,Product_code,Product_name, amount, Product_type, Quantity ,Price FROM product ORDER BY Product_name";
			$select_query=mysqli_query($connect,$select);
			$count=mysqli_num_rows($select_query);
			$lastpage = ceil($count / $itemperpage);
			$firstpage = ($page-1) * $itemperpage;  
			$select1= "SELECT * FROM product LIMIT $firstpage, $itemperpage";
			$select_query1 = mysqli_query($connect,$select1);
			$count1 = mysqli_num_rows($select_query1);
			$addpage= $page + 1;
			$subpage = $page - 1;
			echo "<div class='pagination'>";
			if ($page == 1 && $lastpage != 1) {
				echo "<a class='button'><i class='fas fa-angle-left'></i></a>";
				echo "<label class='page-info'>Page ". $page ." of ". $lastpage . "</label>";
?>
					<a href="admin_product_list.php?page=<?php echo $addpage ?>"><i class="fas fa-angle-right"></i></a>
				<?php
			}
			elseif ($lastpage <= 1 ) {
				echo "<a class='button'><i class='fas fa-angle-left'></i></a>";
			

				
				echo "<label class='page-info'>Page ". $page ." of ". $lastpage . "</label>";

				echo "<a class='button'><i class='fas fa-angle-right'></i></a>";
				
			}
			elseif($page > 1 && $page < $lastpage){
				?>

				<a href="admin_product_list.php?page=<?php echo $subpage ?>"><i class='fas fa-angle-left'></i></a>
				<?php
				echo "<label class='page-info'>Page ". $page ." of ". $lastpage . "</label>";
				?>
				<a href="admin_product_list.php?page=<?php echo $addpage ?>"><i class="fas fa-angle-right"></i></a>
				<?php
			}
			elseif($page == $lastpage){
				?>
				<a href="admin_product_list.php?page=<?php echo $subpage ?>"><i class='fas fa-angle-left'></i></a>

				<?php
				echo "<label class='page-info'>Page ". $page ." of ". $lastpage . "</label>";
				?>
				<?php
				echo "<a class='button'><i class='fas fa-angle-right'></i></a>";
			}
			echo "</div>";
			
			for ($i=0; $i < $count1 ; $i++) { 
				$select_array=mysqli_fetch_array($select_query1);
				$roomid=$select_array['Product_id'];
				$amount = $select_array['amount'];	
				$productcode = $select_array['Product_code'];
				$quantity=$select_array['Quantity'];
				$productname = $select_array['Product_name'];
				$price=$select_array['Price'];
				$spprice = $select_array['sp_price'];
				$totalprice=$price - $spprice;  
				?>
				<tr>
					<td><?php echo $roomid ?></td>
					<td><?php echo $productcode ?></td>
					<td><?php echo $productname ?> (<?php echo $amount ?>)</td>
					<td><?php echo $quantity ?>&nbsp;&nbsp;</td>
					<td><?php echo number_format($spprice) ?></td>
					<td><?php echo number_format($price) ?> </td>
					<td><?php echo number_format($totalprice) ?></td>
					<td><?php echo "<a href='product_del.php?PID='.$roomid.'>Del</a>" ?>||
					<a href="restockwithlink.php?PID=<?php echo $roomid ?>" class="linkrestock">Restock</a>
						|| 
						<a href="productedit.php?PID=<?php echo $roomid ?>" class="linkrestock">Edit Product</a>
					</td>
				</tr>
				<?php
			}
			}
		 ?>
	</table>
</form>

</body>
</html>