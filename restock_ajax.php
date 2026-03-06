<?php
include('connect.php');
if(isset($_POST['product_id'], $_POST['add_quantity'])){
    $id = intval($_POST['product_id']);
    $add = intval($_POST['add_quantity']);

    mysqli_query($connect, "UPDATE product SET Quantity = Quantity + $add WHERE Product_id=$id");
    mysqli_query($connect, "INSERT INTO history(Type,Amount,added_to,date_time) VALUES('Restock',$add,$id,NOW())");

    $res = mysqli_query($connect, "SELECT Quantity FROM product WHERE Product_id=$id");
    $row = mysqli_fetch_assoc($res);

    echo json_encode(['success'=>true,'product_id'=>$id,'new_quantity'=>$row['Quantity']]);
    exit();
}
?>