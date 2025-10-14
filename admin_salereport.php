<?php 
$connect=mysqli_connect("Localhost","root","","the_bottle_database");
date_default_timezone_set("Asia/Yangon");
$sum='';
$itemperpage = 100;
$profit =0;
if (isset($_GET["page"])) {    
    $page = $_GET["page"];    
}
else { 
    $page = 1;    
}  ini_set('display_errors', 0);
include('admin_navbar.php');
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<title></title>
  <link href="fontawesome/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="admin_salereport.css">
 </head>
 <body>

 <style type="text/css">
 </style>
<form action="admin_salereport.php" method="POST">
 <div style="float:right;padding-top: 12px;padding-bottom: 10px">
            <input type="text" name="txtitem" class="input" placeholder="Find Item" value="<?php echo isset($_POST['txtitem']) ? $_POST['txtitem'] : ''; ?>">
            <input type="date" name="txtdate1" class="input" value="<?php echo isset($_POST['txtdate1']) ? $_POST['txtdate1'] : ''; ?>">
            <input type="date" name="txtdate2" class="input" value="<?php echo isset($_POST['txtdate2']) ? $_POST['txtdate2'] : ''; ?>">
            <button class="" name ="btnsearch" type="submit"><i class="fas fa-search fa-1x"></i></button><button class="" type="button" onclick="clearForm()">Clear</button>
        </div>
  <br>
 	<table class="table">
 		<tr>
 			<td>Id</td>
      <td>Date</td>
      <td>Time</td>
 			<td>Product Name</td>
 			<td>Buy Quantity</td>
 			<td>Total Price</td>
      <td>Profit</td>
 			
 			
      <td>Action</td>
 		</tr>
  <?php
if (isset($_POST['btnsearch'])) {
  
 
                $searchitem = $_POST['txtitem'];
                $searchdate1 = $_POST['txtdate1'];
                $searchdate2 = $_POST['txtdate2'];

                // Sanitize inputs
                $searchitem = mysqli_real_escape_string($connect, $searchitem);
                $searchdate1 = mysqli_real_escape_string($connect, $searchdate1);
                $searchdate2 = mysqli_real_escape_string($connect, $searchdate2);
// date 1 and 2 not null                
  if ($searchitem == null && $searchdate1 != null && $searchdate2 != null) {
    $select_item = "SELECT * FROM product p, purchase pr WHERE p.Product_id=pr.Product_id AND 
                    pr.Date BETWEEN '$searchdate1' AND '$searchdate2' AND status='Confirmed'";
                    $item_query = mysqli_query($connect, $select_item);
                    $item_count = mysqli_num_rows($item_query);
        $result = mysqli_query($connect, "SELECT SUM(totalprice) AS totalsum FROM purchase 
                  WHERE Date BETWEEN '$searchdate1' AND '$searchdate2' AND status='Confirmed'");
                    $row = mysqli_fetch_assoc($result);
                    $sum = $row['totalsum'];
                    echo "Total Amount = " . number_format($sum) . " ks<br>";
       $result2 = mysqli_query($connect, "SELECT SUM(profit) AS totalsum FROM purchase WHERE Date BETWEEN '$searchdate1' AND '$searchdate2' AND status='Confirmed'");
if ($result2) {
    $row2 = mysqli_fetch_array($result2);
    if ($row2) {
        $profit = $row2['totalsum'];
        echo "Profit: " . number_format($profit). " Ks";
    } else {
        echo "No profit data available.";
    }
} else {
    echo "Error: " . mysqli_error($connect);
}
 }
  elseif ($searchitem == null && $searchdate1 != null && $searchdate2 == null) {
                   $select_item = "SELECT * FROM product p, purchase pr WHERE p.Product_id=pr.Product_id AND pr.Date='$searchdate1' AND status='Confirmed'";
                     $item_query = mysqli_query($connect, $select_item);
                     if ($item_query) {
                         echo "Executing searchdate 1 not null";
                     }
                      $result = mysqli_query($connect, "SELECT SUM(totalprice) AS totalsum FROM purchase WHERE Date = '$searchdate1' AND status='Confirmed'");

                    $row = mysqli_fetch_assoc($result);
                    $sum = $row['totalsum'];

                    echo "Total Amount = " . number_format($sum) . " ks<br>";
                  }
elseif ($searchitem != null && $searchdate1 != null && $searchdate2 != null) {
    $select_item = "SELECT * FROM product p, purchase pr 
                    WHERE p.Product_id = pr.Product_id 
                    AND pr.Product_id = 'searchitem'
                    AND pr.Date BETWEEN '$searchdate1' AND '$searchdate2' 
                    AND status='Confirmed'";

    $item_query = mysqli_query($connect, $select_item);
     if ($item_query) {
                         echo "Executing searchdate 1 ,2 and item not null";
                     }
     $item_count = mysqli_num_rows($item_query);

    $result = "SELECT SUM(totalprice) AS totalsum 
           FROM product p, purchase pr 
           WHERE pr.Date = '$searchdate1' 
           AND pr.Product_id = p.Product_id 
           AND p.Product_name LIKE '%$searchitem%' 
           AND status='Confirmed'";

$result_query = mysqli_query($connect, $result);

if (!$result_query) {
    // Query failed
    echo "Error: " . mysqli_error($connect);
} else {
    // Query successful
    $row = mysqli_fetch_assoc($result_query);
    if (!$row) {
        // No rows returned
        echo "No result found.";
    } else {
        // Data fetched successfully
        $sum = $row['totalsum'];
        echo "</br>Total Amount = " . number_format($sum) . " ks<br>";
    }
  } 
    }
     elseif ($searchitem != null && $searchdate1 != null && $searchdate2 == null){
   //item and date 1
    $select_item = "SELECT * FROM product p, purchase pr WHERE p.Product_id=pr.Product_id AND 
                    pr.Product_id = 'searchitem' OR p.Product_id = '$searchitem' AND pr.Date='$searchdate1' 
                    AND status='Confirmed'";
    $item_query = mysqli_query($connect, $select_item);
     $item_count = mysqli_num_rows($item_query);

    $result = "SELECT SUM(totalprice) AS totalsum 
           FROM product p, purchase pr 
           WHERE pr.Date = '$searchdate1' 
           AND pr.Product_id = p.Product_id 
           AND p.Product_name LIKE '%$searchitem%' 
           AND status='Confirmed'";

$result_query = mysqli_query($connect, $result);

if (!$result_query) {
    // Query failed
    echo "Error: " . mysqli_error($connect);
} else {
    // Query successful
    $row = mysqli_fetch_assoc($result_query);
    if (!$row) {
        // No rows returned
        echo "No result found.";
    } else {
        // Data fetched successfully
        $sum = $row['totalsum'];
        echo "</br>Total Amount = " . number_format($sum) . " ks<br>";
    }
  } 
    }
    if ($item_query) {       
        $item_count = mysqli_num_rows($item_query);
        echo "</br>Number of rows returned: $item_count<br>";
        if ($item_count > 0) {
     for ($i=0; $i < $item_count ; $i++) { 
        $row1=mysqli_fetch_array($item_query);
        $purchase_id = $row1['purchaseid'];
         $quantity=$row1['Buy_Quantity'];
        $price=$row1['Price'];
        $profit = $row1['profit'];
       $date = $row1['Date'];
        $time = $row1['Time'];
       $productname= $row1['Product_name'];
       $buyquantity = $row1['Buy_Quantity'];
          $totalprice=$price * $quantity;



        ?>
          <tr>
            <td><?php echo $purchase_id ?></td>
              <td><?php echo $date ?></td>
          <td><?php echo  $time ?></td>
           <td><?php echo  $productname ?></td>
          <td><?php echo number_format($buyquantity) ?></td>
          <td><?php echo number_format($totalprice) ?> Ks</td>
          <td><?php echo number_format($profit) ?> Ks</td>
        <td><a href="itemremovetdysale.php?PID='<?php echo $purchase_id ?>'">Remove</a></td>
        
          </tr>

        <?php
      }
    }
  }
  
} //if stop here


  ?>
  
  </form>
 </body>
 </html>