<?php
session_start();
include_once('../mod/database_connection.php');

$total_price = $_POST['total_price'];
$freeship_id = $_POST['freeship_id'];
$voucher_id = $_POST['voucher_id'];
$vouchers_id = [];
array_push($vouchers_id, $freeship_id);
array_push($vouchers_id, $voucher_id);

$user_id = $_SESSION['id_account'];

$address = $_POST['address'];
if ($address == "") {
    $status = "Paid";
} else {
    $status = "Waiting for confirmation";
}

if (isset($_SESSION['buy_now_temp'])) {
    $check_out = $_SESSION['buy_now_temp'];
    unset($_SESSION['buy_now_temp']);
}else{
    $check_out = $_SESSION['shopping_cart'];
    unset($_SESSION['shopping_cart']);
}


$sql_insert_order = "INSERT INTO `orders`(`customer_id`, `order_date`, `total_amount`, `address`, `status`) VALUES (?,NOW(),?,?,?)";
$stmt_insert_order = $conn->prepare($sql_insert_order);
if ($stmt_insert_order === false) {
    die("Error preparing statement");
}
$stmt_insert_order->bind_param("iiss", $user_id, $total_price, $address, $status);
if ($stmt_insert_order->execute()) {

    $id_order = mysqli_insert_id($conn);

    foreach ($vouchers_id as $id_voucher) {

        $sql_insert_voucher_usage = "INSERT INTO `voucher_usage`(`voucher_id`, `order_id`) VALUES (?,?)";
        $stmt_insert_voucher_usage = $conn->prepare($sql_insert_voucher_usage);
        $stmt_insert_voucher_usage->bind_param("ii", $id_voucher, $id_order);
        if ($stmt_insert_voucher_usage->execute()) {
            $sql_update_user_voucher = "UPDATE `user_voucher` SET `amount`= amount-1 WHERE `user_id`='$user_id' AND `voucher_id`='$id_voucher'";
            $conn->query($sql_update_user_voucher);
        }

    }

    $sql_update_gcoin = "UPDATE `users` SET `Gcoin`= Gcoin-? WHERE `id`= ?";
    $stmt_update_gcoin = $conn->prepare($sql_update_gcoin);
    if ($stmt_update_gcoin === false) {
        die("Error preparing statement");
    }
    $stmt_update_gcoin->bind_param("ii", $total_price, $user_id);
    if ($stmt_update_gcoin->execute()) {

        foreach ($check_out as $value) {

            $product_id = $value['product_id'];
            $quantity = $value['quantity'];

            if ($value['type'] == "product" || $value['type'] == "gear") {
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

                $sql_sl_seller_id = "SELECT customer_id FROM `purchased_products` WHERE `id`='$product_id'";
                $result_sl_seller_id = $conn->query($sql_sl_seller_id);
                $row_sl_seller_id = $result_sl_seller_id->fetch_assoc();
                $seller_id = $row_sl_seller_id['customer_id'];
                $sql_update_gcoin_seller = "UPDATE `users` SET `Gcoin`= Gcoin-$price WHERE `id`= $seller_id";
                $conn->query($sql_update_gcoin_seller);
            }

            if ($value['type'] != "gear") {
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

        }


    }
}

?>

<script>
    notification_dialog("Success", "Abate Successful!!!");
    setTimeout(() => {
        location.href = "./bag?game";
    }, 1500);
</script>