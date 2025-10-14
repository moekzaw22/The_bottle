<?php 
include('connect.php');
include('navbar.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<link href="fontawesome/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet">
</head>
<body>
<style type="text/css">
.table{
	width: 100%;
	border-collapse: collapse;
	padding-right:5	0px;
}
.pagination{
	display: flex;
	text-align: center;
	justify-content: center;
	margin: 50px 100px 50px 100px;
}
.pagination a{
	color: blue;
	user-select: none;
	width: 40px;
	border-radius: 10px;
	border: 1px solid grey;
	text-decoration: none;
	font-size: 40px;
}
.pagination .page-info{
	width: 350px;
	color: blue;
	font-size: 35px;
	user-select: none;
}
.pagination .button{
	color: grey;
	width: 40px;
	user-select: none;
	border-radius: 10px;
	border: 1px solid grey;
	text-decoration: none;
	font-size: 40px;
}
.table tr td{
	padding-top:5px;
	padding-bottom:5px;
	font-size: 20px;
	font-family: arial;
	padding: 4px;
	padding-left: 7px;
	padding-right: 7px;
	border: 1px solid grey;
}
.fa-edit{
		color: #00A6E8;
	}
.fa-trash{
		color: red;
	}
.linkrestock{
		color: 	cyan;
		text-decoration: none;
	}
.input:focus{
	outline: none;
}
.refresh:hover{
	opacity: 0.7;
}
body{
		margin-top: 100px;
		font-family: arial;
		background: #0E1818;
		color: white;
}
div .input{
    width:200px;
    background: black;
    border:none;
    color:white;
    border-radius: 20px;
    padding:10px;
}
  button{
    background:transparent;
    padding:6px;
  }

  div .fa-search
  {
    color:white;
  }
</style>
<input type="checkbox" onclick="showspentprice()">EYE</input>
	<form action="product_list_search.php" method="POST">
		<!-- <input type="text" id="productname" name="txtproductname">
		<input type="submit" name="btnsubmit">
		 -->
  <div style="float:right;padding-top: 10px;padding-bottom: 10px"><input type="text" name="product" class="input" placeholder="find something"><button class="search" name ="btnsubmit" type="submit"><i class="fas fa-search fa-1x"></i></button></div>

	
	<br>


	 <table class="table">
		<tr>
			
			<td>Product ID</td>	
			<td>Product Code</td>
			<td>Product Name(milliliter)</td>
			<td>Quantity</td>
			<td>Price</td>
			<td>Spent Price</td>
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
	$pname=$_GET['product'];
	$query=mysqli_query($connect,"SELECT Product_id,Product_code,Product_name,sp_price, amount, Product_type, Quantity ,Price  FROM product WHERE Product_name LIKE '%$pname%' OR Product_id = '$pname' ORDER BY Product_name ASC");
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
				$price=$row['Price'];
				$spprice = $row['sp_price'];
				$profit=$price - $spprice;  
				?>
				<tr>
					<td><?php echo $product_id ?></td>
					<td><?php echo $row['Product_code'] ?></td>
					<td><?php echo $row['Product_name'] ?>(<?php echo $row['amount'] ?>)</td>	
					<td><?php echo $row['Quantity'] ?>Stock</td>
					<td><?php echo $row['Price'] ?>&nbsp;&nbsp;</td>
					<td type="password"><?php echo $row['sp_price'] ?></td>
						<td><a href="restockwithlink.php?PID=<?php echo $product_id ?>" class="linkrestock">Restock  </a>
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
			$select="SELECT  Product_id,Product_code,Product_name, amount, Product_type, Quantity ,Price FROM product ORDER BY Product_name ASC";
			$select_query=mysqli_query($connect,$select);
			$count=mysqli_num_rows($select_query);
			$lastpage = ceil($count / $itemperpage);
			$firstpage = ($page-1) * $itemperpage;  
			$select1= "SELECT * FROM product ORDER BY Product_name ASC LIMIT $firstpage, $itemperpage";
			$select_query1 = mysqli_query($connect,$select1);
			$count1 = mysqli_num_rows($select_query1);
			$addpage= $page + 1;
			$subpage = $page - 1;
			echo "<div class='pagination'>";
			if ($page == 1 && $lastpage != 1) {
				echo "<a class='button'><i class='fas fa-angle-left'></i></a>";
				echo "<label class='page-info'>Page ". $page ." of ". $lastpage . "</label>";
?>
					<a href="product_list.php?page=<?php echo $addpage ?>"><i class="fas fa-angle-right"></i></a>
				<?php
			}
			elseif ($lastpage <= 1 ) {
				echo "<a class='button'><i class='fas fa-angle-left'></i></a>";
			

				
				echo "<label class='page-info'>Page ". $page ." of ". $lastpage . "</label>";

				echo "<a class='button'><i class='fas fa-angle-right'></i></a>";
				
			}
			elseif($page > 1 && $page < $lastpage){
				?>

				<a href="product_list.php?page=<?php echo $subpage ?>"><i class='fas fa-angle-left'></i></a>
				<?php
				echo "<label class='page-info'>Page ". $page ." of ". $lastpage . "</label>";
				?>
				<a href="product_list.php?page=<?php echo $addpage ?>"><i class="fas fa-angle-right"></i></a>
				<?php
			}
			elseif($page == $lastpage){
				?>
				<a href="product_list.php?page=<?php echo $subpage ?>"><i class='fas fa-angle-left'></i></a>

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
				$quantity=$select_array['Quantity'];
				$price=$select_array['Price'];
				$spprice = $select_array['sp_price'];
				$totalprice=$price - $spprice;  
				?>
				<tr>
					<td><?php echo $roomid ?></td>
					<td><?php echo $select_array['Product_code'] ?></td>
					<td><?php echo $select_array['Product_name'] ?> (<?php echo $select_array['amount'] ?>)</td>
					<td><?php echo $select_array['Quantity'] ?>&nbsp;&nbsp;</td>
					<td><?php echo $select_array['Price'] ?> </td>
					<td id="spprice"><?php echo $spprice ?></td>
						<td><a href="restockwithlink.php?PID=<?php echo $product_id ?>" class="linkrestock">Restock  </a>
						|| 
						<a href="productedit.php?PID=<?php echo $product_id ?>" class="linkrestock">Edit Product</a>
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