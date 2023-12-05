<?php
session_start();
include_once('../mod/database_connection.php');
$user_id = $_SESSION['id_account'];
$order_id = $_POST['order_id'];



$sql_update_order = "UPDATE `orders` SET `order_date`=NOW(),`status`= 'Cancelled' WHERE `id`= ?";
$stmt_update_order = $conn->prepare($sql_update_order);
if ($stmt_update_order === false) {
    die("Error preparing statement");
}
$stmt_update_order->bind_param("i", $order_id);
if ($stmt_update_order->execute()) {
    $sql_order = "SELECT * FROM `orders` WHERE id = $order_id";
    $result_order = $conn->query($sql_order);
    $row_order = $result_order->fetch_assoc();

    $total_amount = $row_order['total_amount'];

    $sql_update_gcoin = "UPDATE `users` SET `Gcoin`= Gcoin+? WHERE `id`= ?";
    $stmt_update_gcoin = $conn->prepare($sql_update_gcoin);
    if ($stmt_update_gcoin === false) {
        die("Error preparing statement");
    }
    $stmt_update_gcoin->bind_param("ii", $total_amount, $user_id);
    if ($stmt_update_gcoin->execute()) {

        $sql_voucher_usage = "SELECT * FROM `voucher_usage` WHERE order_id = $order_id";
        $result_voucher_usage = $conn->query($sql_voucher_usage);
        if ($result_voucher_usage->num_rows > 0) {
            while ($row_voucher_usage = $result_voucher_usage->fetch_assoc()) {
                $voucher_id = $row_voucher_usage['voucher_id'];

                $sql_delete_voucher_usage = "DELETE FROM `voucher_usage` WHERE `voucher_id`= $voucher_id AND `order_id`= $order_id";
                $conn->query($sql_delete_voucher_usage);

                $sql_update_user_voucher = "UPDATE `user_voucher` SET `amount`= amount + 1 
            WHERE `user_id`= $user_id AND `voucher_id`= $voucher_id";
                $conn->query($sql_update_user_voucher);
            }
        }
        ?>
        <script>
            notification_dialog("Success", "Order Canceled Successfully!!!");
            setTimeout(() => {
                location.href = "./orders";
            }, 2000);
        </script>
    <?php }

}
$stmt_update_order->close();

?>