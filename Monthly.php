<?php 

include('connect.php');
include('navbar.php'); 
$year = $_GET['txtyear'] ?? date("Y");
$select = "SELECT 
            YEAR(Date) AS sltyear,
            MONTH(Date) AS sltdate,
            SUM(totalprice) AS totalprisum,
            SUM(Buy_Quantity) AS totalitem
           FROM purchase
           WHERE status='Confirmed'";
           
if($year != ''){
    $select .= " AND YEAR(Date) = '$year'";
}

$select .= " GROUP BY YEAR(Date), MONTH(Date)
             ORDER BY YEAR(Date), MONTH(Date)";
             $select_query =mysqli_query($connect,$select);
             $count =mysqli_num_rows($select_query);


 ?>
<!DOCTYPE html>
<html>
<head>
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<style type="text/css">
	body{font-family: arial;}
	
.select{width:100px;color:black;font-size:20px;display:flex;float:right;text-align: center;margin:10px}
.select option{color:black}
.label{margin:10px;font-size: 20px;text-align: center;display: flex;float:left}
</style>
<body>
	<div class="mthdiv-1">
	<form method="GET">
		<label class="label">Showing Year <?php echo $year ?></label>
		<select class="select" name="txtyear" onchange="this.form.submit()">
<?php 
	$year_query = mysqli_query($connect, "SELECT DISTINCT YEAR(Date) AS year FROM purchase WHERE status='Confirmed'");
	$selected_year = $_GET['txtyear'] ?? date('Y');
		while ($row = mysqli_fetch_assoc($year_query)) {
		$year =$row['year'];
		$is_selected = ($year == $selected_year) ? "selected" : "";
    	echo "<option value='$year' $is_selected>$year</option>";
}
?>

</select>
	</form>			
		
	<table class="table">
		<tr>
			<th>Year</th>
			<th>Month</th>
			<th>Total Sale (Price)</th>
			<th>Sale (Items)</th>
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
