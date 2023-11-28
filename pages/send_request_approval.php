<?php
session_start();
include_once('../mod/database_connection.php');

$id_pp = $_POST['id_pp'];
$id_product = $_POST['id_product'];
$price = $_POST['price'];
$amount = $_POST['amount'];
$quantity_PP = $_POST['quantity_PP'];
$customer_id = $_SESSION['id_account'];

if ($quantity_PP == $amount) {
    $sql_update_pp = "UPDATE `purchased_products` SET `price`= $price,`status`= 'review' WHERE id = $id_pp";
    $conn->query($sql_update_pp);
}

if ($quantity_PP > $amount) {

    $quantity_update = $quantity_PP - $amount;
    $sql_update_pp = "UPDATE `purchased_products` SET `quantity`= $quantity_update WHERE id = $id_pp";
    $conn->query($sql_update_pp);

    $sql_insert_review = "INSERT INTO `purchased_products`(`customer_id`, `product_id`, `quantity`, `price`, `status`) 
    VALUES ('$customer_id','$id_product','$amount','$price','review')";
    $conn->query($sql_insert_review);

}

?>
<script>
    notification_dialog("Success", "Send Request Successful!!!");
    setTimeout(() => {
        location.href = "bag";
    }, 2000);

</script>