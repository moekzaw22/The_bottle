    <?php
    include('navbar.php');
     $connect=mysqli_connect("localhost","root","","the_bottle_database");
    $product_whiskey="SELECT * FROM product WHERE product_type='Whiskey'";
    $product_whiskey_query=mysqli_query($connect,$product_whiskey);
    $product_whiskey_count=mysqli_num_rows($product_whiskey_query);

    $product_beer="SELECT * FROM product WHERE product_type='Beer'";
    $product_beer_query=mysqli_query($connect,$product_beer);
    $product_beer_count=mysqli_num_rows($product_beer_query);
         
    $product_wine="SELECT * FROM product WHERE product_type='Wine'";
    $product_wine_query=mysqli_query($connect,$product_wine);
    $product_wine_count=mysqli_num_rows($product_wine_query);

    $product_gin="SELECT * FROM product WHERE product_type='Gin'";
    $product_gin_query=mysqli_query($connect,$product_gin);
    $product_gin_count=mysqli_num_rows($product_gin_query);

    $product_brandy="SELECT * FROM product WHERE product_type='Brandy'";
    $product_brandy_query=mysqli_query($connect,$product_brandy);
    $product_brandy_count=mysqli_num_rows($product_brandy_query);

    $product_rum="SELECT * FROM product WHERE product_type='Rum'";
    $product_rum_query=mysqli_query($connect,$product_rum);
    $product_rum_count=mysqli_num_rows($product_rum_query);

    $product_Tequila="SELECT * FROM product WHERE product_type='Tequila'";
    $product_Tequila_query=mysqli_query($connect,$product_Tequila);
    $product_Tequila_count=mysqli_num_rows($product_Tequila_query);

    $product_vodka="SELECT * FROM product WHERE product_type='Vodka'";
    $product_vodka_query=mysqli_query($connect,$product_vodka);
    $product_vodka_count=mysqli_num_rows($product_vodka_query);

        
  
     
    ?>
    
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width">
</head>
<body>
	<h4>Whiskey</h4>
	<div class="container">
		
	<?php 
	
	for ($i=0; $i < $product_whiskey_count; $i++) { 
		$product_fetch=mysqli_fetch_array($product_whiskey_query);
		if ($product_whiskey_count = 8) {
		
		
		
		?>

		<table class="table" style="padding-right:15px;padding-left:15px">
		<tr>
			<td><?php echo $product_fetch['Product_name']; ?></td>
		</tr>
		<tr>
			<td style="text-align: center;"><?php echo $product_fetch['Quantity'] ?></td>
		</tr>
		
			</table>&nbsp;&nbsp;&nbsp;
			
		<?php
		}
	}
	 ?>
	
	</div>

		<h4>Beer</h4>
	<div class="container">
		
	<?php 
	for ($i=0; $i < $product_beer_count; $i++) { 
		$product_fetch=mysqli_fetch_array($product_beer_query);
		?>
		<table class="table" style="padding-right:15px;padding-left:15px">
		<tr>
			<td><?php echo $product_fetch['Product_name']; ?></td>
		</tr>
		<tr>
			<td style="text-align: center;"><?php echo $product_fetch['Quantity'] ?></td>
		</tr>
		
			</table>&nbsp;&nbsp;&nbsp;
			
		<?php
	}
echo "<br>";
	 ?>
	
	</div>

		<h4>Wine</h4>
		
	<div class="container">
		
	<?php 
	for ($i=0; $i < $product_wine_count; $i++) { 
		$product_fetch=mysqli_fetch_array($product_wine_query);
		?>
		<table class="table" style="padding-right:15px;padding-left:15px">
		<tr>
			<td><?php echo $product_fetch['Product_name']; ?></td>
		</tr>
		<tr>
			<td style="text-align: center;"><?php echo $product_fetch['Quantity'] ?></td>
		</tr>
		
			</table>&nbsp;&nbsp;&nbsp;
			
		<?php
	}
	 ?>
	
	</div>

		<h4>Gin</h4>
	<div class="container">
		
	<?php 
	for ($i=0; $i < $product_gin_count; $i++) { 
		$product_fetch=mysqli_fetch_array($product_gin_query);
		?>
		<table class="table" style="padding-right:15px;padding-left:15px">
		<tr>
			<td><?php echo $product_fetch['Product_name']; ?></td>
		</tr>
		<tr>
			<td style="text-align: center;"><?php echo $product_fetch['Quantity'] ?></td>
		</tr>
		
			</table>&nbsp;&nbsp;&nbsp;
			
		<?php
	}
	 ?>
	
	</div>
	<h4>Tequila</h4>
	<div class="container">
		
	<?php 
	for ($i=0; $i < $product_Tequila_count; $i++) { 
		$product_fetch=mysqli_fetch_array($product_Tequila_query);
		?>
		<table class="table" style="padding-right:15px;padding-left:15px">
		<tr>
			<td><?php echo $product_fetch['Product_name']; ?></td>
		</tr>
		<tr>
			<td style="text-align: center;"><?php echo $product_fetch['Quantity'] ?></td>
		</tr>
		
			</table>&nbsp;&nbsp;&nbsp;
			
		<?php
	}
	 ?>
	
	</div>

	<h4>Brandy</h4>	
	<div class="container">
		
	<?php 
	for ($i=0; $i < $product_brandy_count; $i++) { 
		$product_fetch=mysqli_fetch_array($product_brandy_query);
		?>
		 
		<table class="table" style="padding-right:15px;padding-left:15px">
		<tr>
			<td><?php echo $product_fetch['Product_name']; ?></td>
		</tr>
		<tr>
			<td style="text-align: center;"><?php echo $product_fetch['Quantity'] ?></td>
		</tr>
		
			</table>&nbsp;&nbsp;&nbsp;
			
		<?php
	}
	 ?>
	
	</div>

		<h4>Rum</h4>
	<div class="container">
		
	<?php 
	for ($i=0; $i < $product_rum_count; $i++) { 
		$product_fetch=mysqli_fetch_array($product_rum_query);
		?>
		
		<table class="table" style="padding-right:15px;padding-left:15px">
		<tr>
			<td><?php echo $product_fetch['Product_name']; ?></td>
		</tr>
		<tr>
			<td style="text-align: center;"><?php echo $product_fetch['Quantity'] ?></td>
		</tr>
		
			</table>&nbsp;&nbsp;&nbsp;
			
		<?php
	}
	 ?>
	
	</div>

	<h4>Vodka</h4>	
	<div class="container">
		
	<?php 
	for ($i=0; $i < $product_vodka_count; $i++) { 
		$product_fetch=mysqli_fetch_array($product_vodka_query);
		?>
		
		<table class="table" style="padding-right:15px;padding-left:15px">
		<tr>
			<td><?php echo $product_fetch['Product_name']; ?></td>
		</tr>
		<tr>
			<td style="text-align: center;"><?php echo $product_fetch['Quantity'] ?></td>
		</tr>
		
			</table>&nbsp;&nbsp;&nbsp;
			
		<?php
	}
	 ?>
	
	</div>

		

</body>
<style type="text/css">
.value{

}
.table{

	border: 1px solid white;
	border-radius: 100px;

	background: royalblue;

}
body{
		font-family: arial;
	background: #0E1818;
	color: white;
}
	.showdata{
		border-radius: 100%;
		padding: 10px;
		font-size: 50px;
		
		width: 200px;
		border: 1px solid black;
	}
	.showdata p{
		background: cyan;

	}
	.container{
		display: flex;
		
	}
</style>
</html>
