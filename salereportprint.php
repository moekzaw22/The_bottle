<?php 
include('connect.php');
if ($_GET['sdate']) {
	$searchdate = $_REQUEST['sdate'];

$select = "SELECT * FROM purchase pc,product p WHERE pc.Date = '$searchdate' AND pc.Product_id = p.Product_id";
$select_query = mysqli_query($connect,$select);
$count = mysqli_num_rows($select_query);
  $result= mysqli_query($connect,"SELECT SUM(totalprice) AS totalsum FROM purchase WHERE Date='$searchdate' And status='Confirmed'");
  $row = mysqli_fetch_assoc($result); 

  $sum = $row['totalsum'];

}
 ?>


 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<title></title>
 </head>
 <body>
 	<style type="text/css">
 	table{
 		border-collapse: collapse;
    font-family: arial;
 	}
 		table tr td{
 			border: 1px solid black;
 			padding: 10px;
 			font-size: 15px;
 		}
    .repinfo{
      font-size: 20px;
    }
 	</style>
 	<script type="text/javascript">
 		function back(){
 			window.location = "salereport.php";
 			
 		}
 	</script>
 <button onclick="print()">Print</button>
 <button onclick="back()">Back</button>
 <div class="repinfo">
 <?php echo $searchdate ?><br>
 Total Sum - <?php echo $sum ?> Ks
 <br>
 	<?php echo $count ?> Items Sold!
 	</div>
 	<div>
 		<table>
 			<tr>
 				<td>Date</td>
 				<td>Item</td>
 				<td>Qty</td>
 				<td>Total</td>
 				<td>Profit</td>
 			</tr>
 		<?php 
 		for ($i=0; $i < $count; $i++) { 
 			$array = mysqli_fetch_array($select_query);
 			?>
 				<tr>
 					<td><?php echo $array['Date'] ?></td>
 					<td><?php echo $array['Product_name'] ?></td>
 					<td><?php echo $array['Buy_Quantity'] ?></td>
 					<td><?php echo $array['totalprice'] ?></td>
 					<td><?php echo $array['profit'] ?></td>

 				</tr>
 			<?php
 		}
 		 ?>
 		 </table>
 	</div>
 </div>
 </body>
 </html>