<?php 
$connect=mysqli_connect("Localhost","root","","the_bottle_database");
$select = "SELECT year(Date) AS sltyear,month(Date) AS sltdate,sum(totalprice) AS totalprisum,
			sum(Buy_Quantity) AS totalitem
     from purchase WHERE status = 'Confirmed'
     group by year(Date),month(Date)
     order by year(Date),month(Date)";
$select_query = mysqli_query($connect,$select);
$count = mysqli_num_rows($select_query);

 ?>



<!DOCTYPE html>
<html>
<head>
	<?php include('navbar.php'); ?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<style type="text/css">
	body{
			background: #0E1818;
	color: white;
			font-family: arial;
	}
	.mthdiv-1{
		position:absolute;
		top:140px;

	}
	.mthdiv-1,.Mthrpt{
		width:99%;

	}
	.Mthrpt,tr,td{
		font-size: 23px;
		border:1px solid grey;
		border-collapse: collapse;
	}
</style>
<body>
	<div class="mthdiv-1">
	<table class="Mthrpt">
		<tr>
			<td>Year</td>
			<td>Month</td>
			<td>Total Sale (Price)</td>
			<td>Sale (Items)</td>
		</tr>

		<?php 
			 for ($i=0; $i < $count; $i++) { 
			  $row = mysqli_fetch_array($select_query);
 		  	  $year = $row['sltyear'];
 		  	  $mth = $row['sltdate'];
 		  	  $total_price = $row['totalprisum'];
 		  	  $total_item = $row['totalitem'];
 		  	  if ($mth == 1) {
 		  	  	$mth = "Jan";
 		  	  }elseif ($mth ==2) {
 		  	  	$mth = "Feb";
 		  	  }elseif ($mth ==3) {
 		  	  	$mth = "Mar";
 		  	  }elseif ($mth ==4) {
 		  	  	$mth = "Apr";
 		  	  }elseif ($mth ==5) {
 		  	  	$mth = "May";
 		  	  }elseif ($mth ==6) {
 		  	  	$mth = "Jun";
 		  	  }elseif ($mth ==7) {
 		  	  	$mth = "Jul";
 		  	  }elseif ($mth ==8) {
 		  	  	$mth = "Aug";
 		  	  }elseif ($mth ==9) {
 		  	  	$mth = "Sep";
 		  	  }elseif ($mth ==10) {
 		  	  	$mth = "Oct";
 		  	  }elseif ($mth ==11) {
 		  	  	$mth = "Nov";
 		  	  }elseif ($mth ==12) {
 		  	  	$mth = "Dec";
 		  	  }

 		  	  echo "<tr>";
 		  	  echo "<td>".$year."</td>";
 		  	  echo "<td>".$mth."</td>";
 		  	  echo "<td>".number_format($total_price)." Ks</td>";
 		  	  echo "<td>".number_format($total_item)."</td>";
 		  	   		  	  echo "</tr>";
 		  	}
		 ?>
	</table>
	</div>
</body>
</html>
