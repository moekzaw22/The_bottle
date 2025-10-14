<?php 
include('navbar.php');
$connect=mysqli_connect("Localhost","root","","the_bottle_database");
if (isset($_POST['btnadd'])) 
{
	$name=$_POST['txtsuppliername'];
	$phone=$_POST['txtphonenumber'];
	$product=$_POST['txtimportproduct'];
	$address=$_POST['txtaddress'];
	
	
	$insert="INSERT INTO supplier VALUES ('','$name','$phone','$product','$address')";
	$insert_query=mysqli_query($connect,$insert);

	if ($insert_query) {

		echo "<script>alert('Supplier Added Successfully')</script>";
	}
	else{
		echo "Error: adding Supplier";
	}

}
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title></title>
 </head>
 <body>
 	<form action="Supplier_entry.php" method="POST">
 		<h1 style="margin-left:20px">Supplier Entry Form</h1>
 	<table class="container">
 		<tr class="spaceunder">
 			<td>Supplier Name</td>
 			<td><input class="inputbox" type="text" name="txtsuppliername" required/></td>
 		</tr>
 		<tr class="spaceunder">
 			<td>Phone Number</td>
 			<td><input class="inputbox" type="text" name="txtphonenumber"></td>
 		</tr>
 		<tr class="spaceunder">
 			<td>Most Import Product</td>
 			<td><input class="inputbox" type="text" name="txtimportproduct"></td>
 		</tr>
 		<tr class="spaceunder">
 			<td>Address</td>
 			<td><input class="inputbox" type="text" name="txtaddress"></td>
 		</tr>
 		<tr class="spaceunder">
 			<td></td>
 			<td><input type="submit" name="btnadd" class="add" value="Add"></td>
 		</tr>
 	</table>


 		<h3>Supplier List</h3>

 		<table class="list">
 			<tr>
 				<td>Id</td>
 				<td>Name</td>
 				<td>Phone</td>
 				<td>Address</td>
 				<td>Most Product</td>
 				<td>Action</td>
 			</tr>
 		<?php 
 				$select="SELECT * FROM supplier ORDER BY name";
			$select_query=mysqli_query($connect,$select);
			$count=mysqli_num_rows($select_query);
			for ($i=0; $i < $count ; $i++) { 
				$select_array=mysqli_fetch_array($select_query);
				$sid=$select_array['id'];
			
				?>
				<tr>
					
					<td><?php echo $select_array['id'] ?></td>
					<td><?php echo $select_array['name'] ?></td>
					<td><?php echo $select_array['phone'] ?></td>
						<td><?php echo $select_array['address'] ?></td>
					<td><?php echo $select_array['product'] ?></td>
					
					<td><a href="supplier_edit.php?SID=<?php echo $sid ?>" class="linkrestock">Edit  </a>
						|| 
						<a href="supplier_delete.php?SID=<?php echo $sid ?>" class="linkrestock">Delete</a>

					</td>
				</tr>
				<?php
			}
			
 		 ?>
 		 	
 		 	
 		 
 		
 		  </table>
 
 	</form>
 </body>


 <style type="text/css">
 	.list{
 		width:100%;
 		border-collapse: collapse;
 		border:1px solid grey;
 	}
 	.list tr td
 	{
 		
 		border:1px solid grey;
 	}
 	.container{
 		padding-left:20px;
 		font-size:30px;
 		font-family: arial;
 	}
 	.add{
	background: darkcyan;
	border: none;
	width: 100px;
	color: white;
	padding: 7px;
	font-size: 22px;
	border-radius: 10px;
}
.inputbox{
	border:none;
	background:black;
	cursor:white;
	color:white;
	width: 250px;
	box-sizing: content-box;
	padding: 6px;
	font-size: 25px;
	border-radius: 10px;
}
.inputbox:focus{
	outline: none;
}
.add:hover{
		background:green;
		cursor: pointer;
	}
	tr.spaceunder>td{
		padding-bottom:10px;
	}
 		body{
 		margin-top: 110px;
 		font-family: arial;
	background: #0E1818;
	color: white;
 </style>
 </html>