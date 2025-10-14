<?php 
include('connect.php');
include('navbar.php');
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title></title>
 </head>
 <body>
 	
 <style type="text/css">
 .container-1{
 	margin-bottom: 20px;
 	box-sizing: content-box;
 	display: flex;
 	float: right;
 }
 	body{
 		margin-right:10px;
 		font-family: arial;
	background: #0E1818;
	color: white;
 		margin-top: 130px;
 	}
 	#sltdate {
 		padding: 6px;
 		font-size: 20px;
 	}
 	#txtdate{
 		font-size: 20px;
 	}
 	#btnfilter{
 		font-size: 20px;
 	}
 	.table tr td{
 			padding-left: 10px;
 		padding-right: 10px;
 		border:1px solid grey;
 		padding-bottom: 5px;
 		padding-top:5px;
 	}
 	.table{
	font-size:24px;
	width: 100%;
	border-collapse: collapse;
	padding-right:5	0px;
}
 </style>
 	<form action="daily_report.php" method="POST">
 		
 	
 	<div class="container-1">
 		<select id="sltdate" name="sltdate">
 			<option value="ASC">Date ASC</option>
 			<option value="DESC">Date DESC</option>
 		</select>
 		<input id="txtdate" type="date" name="txtdate">
 		<input type="submit" name="btnsearch" value="Filter" id="btnfilter">
 	</div>
 <table class="table">
 	<tr>
 		
 		<td>Date</td>
 		<td>Total</td>
 		<td>Total QTY</td>
 	</tr>
 	<?php 
 	if (isset($_POST['btnsearch'])) {
 		$sltdate = $_POST['sltdate'];
 		$txtdate = $_POST['txtdate'];
 		if (empty($txtdate)) {
 		$select="SELECT Date,SUM(totalprice) AS totalp, SUM(Buy_Quantity) AS qtytotal FROM purchase GROUP BY Date ORDER BY Date = '$sltdate'";
			$select_query=mysqli_query($connect,$select);
			$count=mysqli_num_rows($select_query);
			for ($i=0; $i < $count ; $i++) { 
				$select_array=mysqli_fetch_array($select_query);
				
				$date=$select_array['Date'];
				
				$price=$select_array['totalp'];
				$Buy_Quantity = $select_array['qtytotal'];
				 ?>
				 <tr>
				 	
				 	<td><?php echo $date ?></td>
				 	
				 	<td><?php echo number_format($price) ?> Ks</td>
				 	
					<td><?php echo $Buy_Quantity ?></td>
				 
				 </tr>
				 <?php 
 		}
 		}
 		elseif (!empty($txtdate)) {
 		$select="SELECT Date,SUM(totalprice) AS totalp, SUM(Buy_Quantity) AS qtytotal FROM purchase WHERE Date = '$txtdate' GROUP BY Date";
			$select_query=mysqli_query($connect,$select);
			$count=mysqli_num_rows($select_query);
			for ($i=0; $i < $count ; $i++) { 
				$select_array=mysqli_fetch_array($select_query);
				
				$date=$select_array['Date'];
				
				$price=$select_array['totalp'];
				$Buy_Quantity = $select_array['qtytotal'];
				 ?>
				 <tr>
				 	
				 	<td><?php echo $date ?></td>
				 	
				 	<td><?php echo number_format($price) ?> Ks</td>
					
					<td><?php echo $Buy_Quantity ?></td>
				 
				 </tr>
				 <?php 
 		}
 		}
 		
 	}
 	else{
 	$select="SELECT Date,SUM(totalprice) AS totalp, SUM(Buy_Quantity) AS qtytotal FROM purchase WHERE status = 'Confirmed' GROUP BY Date ORDER BY Date DESC";
			$select_query=mysqli_query($connect,$select);
			$count=mysqli_num_rows($select_query);
			for ($i=0; $i < $count ; $i++) { 
				$select_array=mysqli_fetch_array($select_query);
				
				$date=$select_array['Date'];
				
				$price=$select_array['totalp'];
				$Buy_Quantity = $select_array['qtytotal'];
				 ?>
				 <tr>
				 	
				 	<td><?php echo $date ?></td>
				 	
				 	<td><?php echo number_format($price) ?> Ks</td>
					
					<td><?php echo $Buy_Quantity ?></td>
				 
				 </tr>
				 <?php 
				}
			}
				  ?>
 </table>
 <script type="text/javascript">
  document.getElementById('sltdate').value= "<?php echo $_GET['sltdate'];?>";

</script>
 </form>
 </body>
 </html>