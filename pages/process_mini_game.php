<?php
session_start();
include_once('../mod/database_connection.php');
?>
<?php
$user_id = isset($_POST["user_id"]) ? $_POST["user_id"] : "";
$user_money = isset($_POST["user_money"]) ? $_POST["user_money"] : "";
$number_spins = isset($_POST["number_spins"]) ? $_POST["number_spins"] : "";
$product_id = isset($_POST["product_id"]) ? $_POST["product_id"] : "";
$type = isset($_POST["type"]) ? $_POST["type"] : "";
if(!empty($number_spins)) {
    $sql_update_number_spins = "UPDATE `users` SET `number_spins`= ? WHERE id = ?";
    $stmt_users_number_spins = $conn->prepare($sql_update_number_spins);
    $stmt_users_number_spins->bind_param("ii", $number_spins, $user_id);
    $stmt_users_number_spins->execute();
    $stmt_users_number_spins->close();
}
if(!empty($user_money)) {
    $sql_update_gcoin = "UPDATE `users` SET `Gcoin`= ? WHERE id = ?";
    $stmt_users_gcoin = $conn->prepare($sql_update_gcoin);
    $stmt_users_gcoin->bind_param("ii", $user_money, $user_id);
    $stmt_users_gcoin->execute();
    $stmt_users_gcoin->close();
}
if($type == "game") {
// Kiểm tra xem user_id,product_id đã tồn tại hay chưa
    $sql_check_PP = "SELECT COUNT(id) 
    FROM purchased_products 
    WHERE user_id = ? AND product_id  = ? AND status = 'not trading'";
    $stmt_check_PP = $conn->prepare($sql_check_PP);
    $stmt_check_PP->bind_param("ii", $user_id, $product_id);
    $stmt_check_PP->execute();
    $stmt_check_PP->bind_result($PP_count);
    $stmt_check_PP->fetch();
    $stmt_check_PP->close();
    if($PP_count == 1) {
        $sql_update_quantity = "UPDATE purchased_products SET quantity = quantity + 1 WHERE  user_id = ? AND product_id =?";
        $stmt_update_quantity = $conn->prepare($sql_update_quantity);
        $stmt_update_quantity->bind_param("ii", $user_id, $product_id);
        $stmt_update_quantity->execute();
        $stmt_update_quantity->close();
    } else {
        $sql_insert_product = "INSERT INTO `purchased_products`(`customer_id`, `product_id`,`quantity`, `status`) VALUES (?,?,1,'not trading')";
        $stmt_product = $conn->prepare($sql_insert_product);
        $stmt_product->bind_param("ii", $user_id, $product_id);
        $stmt_product->execute();
        $stmt_product->close();
    }

} else {
    // Kiểm tra xem user_id,voucher_id đã tồn tại hay chưa
    $sql_check_voucher = "SELECT COUNT(voucher_id) 
    FROM user_voucher 
    WHERE user_id = ? AND voucher_id = ?";
    $stmt_check_voucher = $conn->prepare($sql_check_voucher);
    $stmt_check_voucher->bind_param("ii", $user_id, $product_id);
    $stmt_check_voucher->execute();
    $stmt_check_voucher->bind_result($voucher_count);
    $stmt_check_voucher->fetch();
    $stmt_check_voucher->close();
    if($voucher_count == 1) {
        // Nếu voucher_id đã tồn tại, cộng giá trị amount lên 1
        $sql_update_amount = "UPDATE user_voucher SET amount = amount + 1 WHERE  user_id = ? AND voucher_id =?";
        $stmt_update_amount = $conn->prepare($sql_update_amount);
        $stmt_update_amount->bind_param("ii", $user_id, $product_id);
        $stmt_update_amount->execute();
        $stmt_update_amount->close();
    } else {
        // Nếu voucher_id chưa tồn tại, thêm mới vào bảng user_voucher
        $sql_insert_voucher = "INSERT INTO user_voucher (user_id, voucher_id, amount) VALUES (?, ?, '1')";
        $stmt_insert_voucher = $conn->prepare($sql_insert_voucher);
        $stmt_insert_voucher->bind_param("ii", $user_id, $product_id);
        $stmt_insert_voucher->execute();
        $stmt_insert_voucher->close();
    }
}
?>