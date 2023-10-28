<?php
include("../add_template.php");
function main()
{
include("../../../mod/database_connection.php");
$customer_id = $_POST["customer_id"];
$order_date = date("Y-m-d H:i:s", strtotime($_POST["order_date"]));
$total_amount = $_POST["total_amount"];
$status = $_POST["status"];
$address = $_POST["address"];
$products = $_POST["products"];
$quantities = $_POST["quantities"];

$sql = "INSERT INTO orders (customer_id, order_date, total_amount, address, status)
        VALUES ('$customer_id', '$order_date', '$total_amount', '$address', '$status')";
$conn->query($sql);

$id_order = mysqli_insert_id($conn);

for ($i = 0; $i < count($products); $i++) {
    $product_id = $products[$i];
    $quantity = $quantities[$i];
    $sql = "INSERT INTO `order_details`(`order_id`, `product_id`, `quantity`) 
    VALUES ('$id_order','$product_id','$quantity')";
    $conn->query($sql);
}
$conn->close();
createNotification("Add Order Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
echo "<script>location.href='add_order.php';</script>";
}
?>