<?php 
include('connect.php');
date_default_timezone_set("Asia/Yangon");
$sum = '';
$item_query='';
$result="";

// Disable error display for production
//ini_set('display_errors', 0);

// Include navbar
include('navbar.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <link rel="stylesheet" type="text/css" href="salereport.css">
    <link href="fontawesome/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet">
</head>
<body>
<div >
    <form action="salereport.php" method="GET">
        <div class="container-salereport">
            <input type="text" name="txtitem" class="input" placeholder="Find Item" value="<?php echo isset($_GET['txtitem']) ? $_GET['txtitem'] : ''; ?>">
            <input type="date" name="txtdate1" class="input" value="<?php echo isset($_GET['txtdate1']) ? $_GET['txtdate1'] : ''; ?>">
            <input type="date" name="txtdate2" class="input" value="<?php echo isset($_GET['txtdate2']) ? $_GET['txtdate2'] : ''; ?>">
            <button class="btn search" name ="btnsearch" type="submit" >Search</button>
            <button class="btn clear" onclick="clearForm()">Clear</button>
        </div>
        <br>
       

            <?php
            if (isset($_GET['btnsearch'])) {
                $searchitem = $_GET['txtitem'];
                $searchdate1 = $_GET['txtdate1'];
                $searchdate2 = $_GET['txtdate2'];

                // Sanitize inputs
                $searchitem = mysqli_real_escape_string($connect, $searchitem);
                $searchdate1 = mysqli_real_escape_string($connect, $searchdate1);
                $searchdate2 = mysqli_real_escape_string($connect, $searchdate2);

                 if ($searchitem == null && $searchdate1 != null && $searchdate2 != null) {
                    // Debugging: Output message to confirm the block is reached
                    echo "<h2  class='search-result-info'>Searching Between".Date('d M Y',strtotime($searchdate1)). " AND ".Date('d M Y',strtotime($searchdate2)). "</h2>";
                    echo "<br>";
                    $select_item = "SELECT * FROM product p, purchase pr WHERE p.Product_id=pr.Product_id AND pr.Date BETWEEN '$searchdate1' AND '$searchdate2' AND status='Confirmed'";
                    $item_query = mysqli_query($connect, $select_item);
                    $item_count = mysqli_num_rows($item_query);
                     $result = mysqli_query($connect, "SELECT SUM(totalprice) AS totalsum,SUM(Buy_Quantity) AS totalsale FROM purchase WHERE Date BETWEEN '$searchdate1' AND '$searchdate2' AND status='Confirmed'");
                    $row = mysqli_fetch_assoc($result);
                    

                   
                }
                //searchdate 1 not null
                 elseif ($searchitem == null && $searchdate1 != null && $searchdate2 == null) {
                   $select_item = "SELECT * FROM purchase pr JOIN  product p ON p.Product_id=pr.Product_id WHERE pr.Date='$searchdate1' AND status='Confirmed'";
                     $item_query = mysqli_query($connect, $select_item);
                      $item_count = mysqli_num_rows($item_query);
                     if ($item_query) {
                          echo "<h2 class='search-result-info'>Searching Date = ".DATE('d M Y',strtotime($searchdate1))."</h2>";
                     }
                      $result = mysqli_query($connect, "SELECT SUM(totalprice) AS totalsum, SUM(Buy_Quantity) AS totalsale FROM purchase WHERE Date = '$searchdate1' AND status='Confirmed'");


                   
          
                  }
// everything not null
 elseif ($searchitem != null && $searchdate1 != null && $searchdate2 != null) {
    $select_item = "SELECT * FROM product p LEFT JOIN purchase pr ON p.Product_id = pr.Product_id
        WHERE 
        (p.Product_name LIKE '%$searchitem%' 
         OR p.Product_code = '$searchitem'
         OR p.Product_id = '$searchitem')
        AND pr.Date BETWEEN '$searchdate1' AND '$searchdate2'
        AND pr.status = 'Confirmed'";

    $item_query = mysqli_query($connect, $select_item);
     if ($item_query) {
                         echo "<h2 class='search-result-info'>Searching Product -- ".$searchitem." Between ".Date('d M Y',strtotime($searchdate1)). " AND ".Date('d M Y',strtotime($searchdate2))."</h2>";
                     }
                     else{
                        echo "No result";
                     }
     $item_count = mysqli_num_rows($item_query);

    $result = mysqli_query($connect,"SELECT SUM(totalprice) AS totalsum, SUM(Buy_Quantity) AS totalsale
           FROM product p, purchase pr 
           WHERE pr.Date BETWEEN '$searchdate1' AND '$searchdate2' 
           AND pr.Product_id = p.Product_id 
           AND (p.Product_name LIKE '%$searchitem%' 
           OR p.Product_code = '$searchitem'
           OR p.Product_id = '$searchitem')
           AND status='Confirmed'");
            
  

        }
               
               
    elseif ($searchitem != null && ($searchdate1 != null || $searchdate2 != null)){
   //item and 1 date 
    $select_item = "SELECT * FROM product p, purchase pr WHERE p.Product_id=pr.Product_id AND 
                    (p.Product_name LIKE '%$searchitem%' OR p.Product_code = '$searchitem' OR p.Product_id = '$searchitem')  AND (pr.Date='$searchdate1' OR pr.Date='$searchdate2') 
                    AND status='Confirmed'";
    $item_query = mysqli_query($connect, $select_item);
    $date = !empty($searchdate1) ? $searchdate1 : $searchdate2;
     if ($item_query) {
                         echo "<h2 class='search-result-info'>Searching Product -- ". $searchitem ." At Date -- " . date('d M Y',strtotime($date)) ."</h2>";
                     }
                     else{
                        echo "No result";
                     }
     $item_count = mysqli_num_rows($item_query);

    $result = mysqli_query($connect,"SELECT SUM(totalprice) AS totalsum, SUM(Buy_Quantity) AS totalsale 
           FROM product p, purchase pr 
           WHERE (pr.Date = '$searchdate1' OR pr.Date ='$searchdate2') 
           AND pr.Product_id = p.Product_id 
           AND (p.Product_name LIKE '%$searchitem%' 
           OR p.Product_code = '$searchitem'
           OR p.Product_id = '$searchitem')
           AND status='Confirmed'");


 
    }
    if (!$result) {
    echo "Error: Cannot Search Only Item" . mysqli_error($connect);
} else {
    // Query successful

    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        // No rows returned
        echo "No result found.";
    } else {
        // Data fetched successfully
        $sum = $row['totalsum'];
         $total_sale = $row['totalsale'];

    }
  } 
 if ($item_query) {  

     echo "<div class='report-summary'>
            Total Amount: " . number_format($sum) . " Ks /
            Total Sale: " . number_format($total_sale) . "
          </div>";

            ?>
            <!-- <a href="salereportprint.php?sdate=<?php echo $searchdate1 ?>">Print</a> -->
             </div>
              <table class="table">
            <tr>
                <th>Id</th>
                <th>Product Name</th>
                <th>Buy Quantity</th>
                <th>Total Price</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
            <?php
            for ($i = 0; $i < $item_count; $i++) {
                $row = mysqli_fetch_assoc($item_query);
                $roomid = $row['purchaseid'];
                $quantity = $row['Buy_Quantity'];
                $price = $row['Price'];
                $productname = $row['Product_name'];
                $buyquantity = $row['Buy_Quantity'];
                $date = $row['Date'];
                $time = $row['Time'];
                $totalprice = $price * $quantity;
                ?>
                <tr>
                    <td><?php echo $roomid ?></td>
                    <td><?php echo $productname ?></td>
                    <td><?php echo $buyquantity ?></td>
                    <td><?php echo number_format($totalprice) ?> Ks</td>
                    <td><?php echo date('d M Y', strtotime($date))  ?></td>
                    <td><?php echo $time ?></td>
                 
                </tr>
           
                <?php
              }
            }
          }
            
            ?>
        </table>
        </div>
    </form>
</body>
<script type="text/javascript">
     function clearForm() {
        document.getElementsByName("txtitem")[0].value = "";
        document.getElementsByName("txtdate1")[0].value = "";
        document.getElementsByName("txtdate2")[0].value = "";
    }
</script>
</html>
