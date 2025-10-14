<?php 
$connect = mysqli_connect("localhost", "root", "", "the_bottle_database");
date_default_timezone_set("Asia/Yangon");
$sum = '';
$itemperpage = 100;

// Check if page is set
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

// Disable error display for production
ini_set('display_errors', 0);

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
    <form action="salereport.php" method="POST">
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
                <td>Product Name</td>
                <td>Buy Quantity</td>
                <td>Total Price</td>
                <td>Date</td>
                <td>Time</td>
                <td>Command</td>
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

                 if ($searchitem == null && $searchdate1 != null && $searchdate2 != null) {
                    // Debugging: Output message to confirm the block is reached
                    echo "Executing searchdate 1 and 2 not null";
                    echo "<br>";
                    $select_item = "SELECT * FROM product p, purchase pr WHERE p.Product_id=pr.Product_id AND pr.Date BETWEEN '$searchdate1' AND '$searchdate2' AND status='Confirmed'";
                    $item_query = mysqli_query($connect, $select_item);
                    $item_count = mysqli_num_rows($item_query);

                    // Debugging: Check if there are any errors in the query execution
//                     if (!$item_query) {
//     die("Error executing query: " . mysqli_error($connect));
// }

                     $result = mysqli_query($connect, "SELECT SUM(totalprice) AS totalsum FROM purchase WHERE Date BETWEEN '$searchdate1' AND '$searchdate2' AND status='Confirmed'");
                    $row = mysqli_fetch_assoc($result);
                    $sum = $row['totalsum'];

                    echo "Total Amount = " . number_format($sum) . " ks<br>";
      

                   
                }
                //searchdate 1 not null
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
// everything not null
 elseif ($searchitem != null && $searchdate1 != null && $searchdate2 != null) {
  echo  $select_item = "SELECT * FROM product p, purchase pr 
                    WHERE p.Product_id = pr.Product_id 
                    AND pr.Product_id = '$searchitem' 
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
   echo $select_item = "SELECT * FROM product p, purchase pr WHERE p.Product_id=pr.Product_id AND 
                    pr.Product_id ='$searchitem' AND pr.Date='$searchdate1' 
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
        echo "Number of rows returned: $item_count<br>";
        if ($item_count > 0) {
            ?>
            <a href="salereportprint.php?sdate=<?php echo $searchdate1 ?>">Print</a>
            <?php
            for ($i = 0; $i < $item_count; $i++) {
                $row = mysqli_fetch_array($item_query);
                $roomid = $row['purchaseid'];
                $quantity = $row['Buy_Quantity'];
                $price = $row['Price'];
                $productname = $row['Product_name'];
                $buyquantity = $row['Buy_Quantity'];
                $date = $row['Date'];
                $time = $row['Time'];
                $totalprice = $price * $quantity;
                $quantity_sum += $buyquantity;
                $totalsum += $totalprice;
                ?>
                <tr>
                    <td><?php echo $roomid ?></td>
                    <td><?php echo $productname ?></td>
                    <td><?php echo $buyquantity ?></td>
                    <td><?php echo $totalprice ?> Ks</td>
                    <td><?php echo $date ?></td>
                    <td><?php echo $time ?></td>
                    <td><a href="itemremovetdysale.php?PID='<?php echo $roomid ?>'">Remove</a></td>
                </tr>
                <?php
              }
            }
          }
            else {
        echo "Error executing query: " . mysqli_error($connect);
    }
         }     
            
            ?>
        </table>
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
