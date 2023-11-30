<?php
session_start();
include_once('../mod/database_connection.php');

$total_price = $_POST['total_price'];
$user_id = $_SESSION['id_account'];
$address = "";
$status = "Paid";


$sql_insert_order = "INSERT INTO `orders`(`customer_id`, `order_date`, `total_amount`, `address`, `status`) VALUES (?,NOW(),?,?,?)";
$stmt_insert_order = $conn->prepare($sql_insert_order);
if ($stmt_insert_order === false) {
    die("Error preparing statement");
}
$stmt_insert_order->bind_param("iiss", $user_id, $total_price, $address, $status);
if ($stmt_insert_order->execute()) {

    $id_order = mysqli_insert_id($conn);

    $sql_update_gcoin = "UPDATE `users` SET `Gcoin`= Gcoin-? WHERE `id`= ?";
    $stmt_update_gcoin = $conn->prepare($sql_update_gcoin);
    if ($stmt_update_gcoin === false) {
        die("Error preparing statement");
    }
    $stmt_update_gcoin->bind_param("ii", $total_price, $user_id);
    if ($stmt_update_gcoin->execute()) {

        foreach ($_SESSION["shopping_cart"] as $value) {

            $product_id = $value['product_id'];
            $quantity = $value['quantity'];

            if ($value['type'] == "product") {
                $sql_insert_order_details = "INSERT INTO `order_details`(`order_id`, `product_id`, `quantity`) 
                VALUES ('$id_order','$product_id','$quantity')";
                $conn->query($sql_insert_order_details);
            }
            if ($value['type'] == "exchange") {
                $price = $value['price'];

                $sql_insert_tradings_history = "INSERT INTO `tradings_history`(`buyer_id`, `product_id`, `price`, `transaction_date`) 
                VALUES ('$user_id','$product_id','$price',NOW())";
                $conn->query($sql_insert_tradings_history);

                $sql_insert_pp = "UPDATE `purchased_products` SET `status`='traded' WHERE `id`='$product_id'";
                $conn->query($sql_insert_pp);
            }

            $check_query = "SELECT * FROM `purchased_products` WHERE product_id = ? AND customer_id = ? AND status = 'not trading'";
            $check_stmt = $conn->prepare($check_query);
            if ($check_stmt === false) {
                die("Error preparing statement");
            }

            $check_stmt->bind_param("ii", $product_id, $user_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $row_check = $check_result->fetch_assoc();
            $check_stmt->close();
            if ($check_result->num_rows > 0) {
                $id_update = $row_check['id'];
                $sql_update_pp = "UPDATE `purchased_products` SET `quantity`= quantity + $quantity WHERE `id`= $id_update";
                $conn->query($sql_update_pp);
            } else {
                $sql_insert_purchased_products = "INSERT INTO `purchased_products`(`customer_id`, `product_id`, `quantity`, `price`, `status`) 
                                                    VALUES ('$user_id','$product_id','$quantity','0','not trading')";
                $conn->query($sql_insert_purchased_products);
            }

        }

        unset($_SESSION['shopping_cart']);
    }
}

?>

<script>
    notification_dialog("Success", "Abate Successful!!!");
    setTimeout(() => {
        location.href = "./bag?game";
    }, 1500);
</script>