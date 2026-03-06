<?php
include('connect.php');

if(isset($_POST['action'])){

    $action = $_POST['action'];

    if($action == "confirm"){
        mysqli_query($connect,"UPDATE purchase SET status='Confirmed' WHERE status='Unconfirmed'");
        echo "success";
    }

    if($action == "cancel"){
        mysqli_query($connect,"DELETE FROM purchase WHERE status='Unconfirmed'");
        echo "success";
    }

    if($action == "cancel_single"){
        $pid = (int)$_POST['pid'];

        $select = mysqli_query($connect,"SELECT Product_id, Buy_Quantity FROM purchase WHERE purchaseid=$pid");

        if($row = mysqli_fetch_assoc($select)){
            $product_id = $row['Product_id'];
            $buyquantity = $row['Buy_Quantity'];

            mysqli_query($connect,"INSERT INTO history (type, Amount, added_to, Date_time)
            VALUES ('Canceled', $buyquantity, $product_id, NOW())");

            mysqli_query($connect,"UPDATE purchase SET status='Canceled' WHERE purchaseid=$pid");
        }

        echo "success";
    }

}
?>
