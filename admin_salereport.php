<?php 
include('connect.php');
date_default_timezone_set("Asia/Yangon");
$sum='';
$profit =0;
$sum=0;
$total_sale=0;
  //ini_set('display_errors', 0);
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
<div class="container-salereport">
    <form action="admin_salereport.php" method="GET">
        <div style="float:right;padding-top: 12px;padding-bottom: 10px">
            <input type="text" name="txtitem" class="input" placeholder="Find Item" value="<?php echo isset($_GET['txtitem']) ? $_GET['txtitem'] : ''; ?>">
            <input type="date" name="txtdate1" class="input" value="<?php echo isset($_GET['txtdate1']) ? $_GET['txtdate1'] : ''; ?>">
            <input type="date" name="txtdate2" class="input" value="<?php echo isset($_GET['txtdate2']) ? $_GET['txtdate2'] : ''; ?>">
            <button class="btn search" name ="btnsearch" type="submit" >Search</button>
            <button class="btn clear" onclick="clearForm()">Clear</button>
        </div>
    </div>
  <br>
 	<table class="table">
 		<tr>
 			<th>Id</th>
            <th>Date</th>
            <th>Time</th>
 			<th>Product Name</th>
 			<th>Buy Quantity</th>
 			<th>Total Price</th>
            <th>Profit</th>
 			<th>Action</th>
 		</tr>
  <?php
if (isset($_GET['btnsearch'])) {
    $searchitem = $_GET['txtitem'];
    $searchdate1 = $_GET['txtdate1'];
    $searchdate2 = $_GET['txtdate2'];

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
                     echo "Searching Sale Between ".DATE('d M Y',strtotime($searchdate1)). " AND " .Date('d M Y',strtotime($searchdate2));
        $result = mysqli_query($connect, "SELECT SUM(totalprice) AS totalsum,SUM(Buy_Quantity) AS 
                totalsale, SUM(profit) AS profit FROM purchase WHERE Date BETWEEN '$searchdate1' AND '$searchdate2' AND status='Confirmed'");
                 
                   
    
if (!$result) {
    // Query failed
    echo "Error: " . mysqli_error($connect);
} else {
    // Query successful

    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        // No rows returned
        echo "No result found.";
    } else {
        // Data fetched successfully
        $sum = $row['totalsum'];
        $profit=$row['profit'];
         $total_sale = $row['totalsale'];

    }
  } 
 }
  elseif ($searchitem == null && $searchdate1 != null && $searchdate2 == null) {
                  $select_item = "SELECT * FROM product p, purchase pr WHERE p.Product_id=pr.Product_id AND pr.Date='$searchdate1' AND status='Confirmed'";
                     $item_query = mysqli_query($connect, $select_item);
                     if ($item_query) {
                         echo "Searching Date = ".DATE('d M Y',strtotime($searchdate1));
                     }
                      $result = mysqli_query($connect, "SELECT SUM(totalprice) AS totalsum,SUM(Buy_Quantity) AS totalsale,SUM(profit) AS profit FROM purchase WHERE Date = '$searchdate1' AND status='Confirmed'");

        if (!$result) {
    // Query failed
    echo "Error: " . mysqli_error($connect);
} else {
    // Query successful
    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        // No rows returned
        echo "No result found.";
    } else {
        // Data fetched successfully
        $sum = $row['totalsum'];
        $profit=$row['profit'];
         $total_sale = $row['totalsale'];

    }
              } 
                  }
elseif ($searchitem != null && $searchdate1 != null && $searchdate2 != null) {
    $select_item = "SELECT * FROM product p, purchase pr 
                    WHERE p.Product_id = pr.Product_id 

                    AND (pr.Product_id = '$searchitem' OR p.Product_name LIKE '%$searchitem%' OR p.Product_Code = '$searchitem')
                    AND pr.Date BETWEEN '$searchdate1' AND '$searchdate2' 
                    AND status='Confirmed'";

    $item_query = mysqli_query($connect, $select_item);
     if ($item_query) {
            echo "Searching Item = ". $searchitem." Between ".DATE('d M y',strtotime($searchdate1)). " AND " .Date('d M Y',strtotime($searchdate2));
        }
     $item_count = mysqli_num_rows($item_query);

    $result = mysqli_query($connect,"SELECT SUM(totalprice) AS totalsum, SUM(Buy_Quantity) AS totalsale, SUM(profit) AS profit
           FROM product p, purchase pr 
           WHERE pr.Date BETWEEN '$searchdate1' AND '$searchdate2' 
           AND pr.Product_id = p.Product_id 
           AND p.Product_name LIKE '%$searchitem%' 
           AND status='Confirmed'");

if (!$result) {
    // Query failed
    echo "Error: " . mysqli_error($connect);
} else {
    // Query successful
    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        // No rows returned
        echo "No result found.";
    } else {
        // Data fetched successfully
        $sum = $row['totalsum'];
        $profit=$row['profit'];
         $total_sale = $row['totalsale'];

    }
  } 
    }
    elseif ($searchitem != null && $searchdate1 != null && $searchdate2 == null){
        echo "Searching Product = ". $searchitem . " at Date = ". Date('d M Y',strtotime($searchdate1));
   //item and date 1
     $select_item = "SELECT * FROM product p, purchase pr WHERE p.Product_id=pr.Product_id AND 
                    (p.Product_id = '$searchitem' OR p.Product_name LIKE '%$searchitem%' OR p.Product_Code ='$searchitem') AND
                    pr.Date='$searchdate1'
                    AND status='Confirmed'";
    $item_query = mysqli_query($connect, $select_item);
     $item_count = mysqli_num_rows($item_query);
   $result = mysqli_query($connect,"SELECT SUM(totalprice) AS totalsum, SUM(Buy_Quantity) AS totalsale, SUM(profit) AS profit
           FROM product p, purchase pr 
           WHERE pr.Date = '$searchdate1' 
           AND pr.Product_id = p.Product_id 
           AND p.Product_name LIKE '%$searchitem%' 
           AND status='Confirmed'");
if (!$result) {
    // Query failed
    echo "Error: " . mysqli_error($connect);
} else {
    // Query successful

    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        // No rows returned
        echo "No result found.";
    } else {
        // Data fetched successfully
        $sum = $row['totalsum'];
        $profit=$row['profit'];
         $total_sale = $row['totalsale'];

    }
  } 
    }
    if (mysqli_num_rows($item_query) >0 ) { 
    if ($sum || $total_sale) {
        $total_amount = $sum ?? 0;
        $total_profit = $profit ?? 0;
        $total_sales = $total_sale ?? 0;
}
    echo "<div class='sale_information'>
            Total Amount: " . number_format($sum) . " Ks /
            Total Profit: " . number_format($profit) . " Ks /
            Total Sale: " . number_format($total_sale) . "
          </div>";

        
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