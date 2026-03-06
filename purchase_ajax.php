<?php
include('connect.php');

if(isset($_POST['barcode']) && isset($_POST['quantity'])){

    $barcode = $_POST['barcode'];
    $quantity = (int)$_POST['quantity'];

    $select = "SELECT Product_id, Product_name, Price, sp_price 
               FROM product 
               WHERE Product_code='$barcode' OR Product_id='$barcode'";

    $query = mysqli_query($connect, $select);

    if(mysqli_num_rows($query) > 0){
        $row = mysqli_fetch_assoc($query);
        $product_id = $row['Product_id'];
        $price = (float)$row['Price'];
        $sp_price = (float)$row['sp_price'];
        $product_name = $row['Product_name'];

        $total_price = $price * $quantity;
        $profit = $total_price - ($sp_price * $quantity);

        $date = date('Y-m-d');
        $time = date("H:i:s");

        mysqli_query($connect,"INSERT INTO purchase 
            VALUES('', '$product_id', '$quantity', '$total_price', '$profit', '$date', '$time', 'Unconfirmed')");

        // Get the newly inserted purchase id
        $purchase_id = mysqli_insert_id($connect);

        // Return data as JSON
        echo json_encode([
            'success' => true,
            'purchase_id' => $purchase_id,
            'product_id' => $product_id,
            'product_name' => $product_name,
            'price' => $price,
            'quantity' => $quantity,
            'total_price' => $total_price
        ]);

    } else {
        echo json_encode(['success'=>false, 'message'=>'Wrong Code']);
    }
}
?>