<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");
    $updateValue = $_GET['update'];
    $id = $_GET['id'];

    $check_query = "SELECT discount_percentage FROM vouchers WHERE discount_percentage = ?";
    $check_stmt = $conn->prepare($check_query);

    if ($check_stmt === false) {
        die("Error preparing statement");
    }

    $check_stmt->bind_param("s", $updateValue);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $check_stmt->close();
        $conn->close();
        createNotification("Voucher already exists! Update Voucher Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
        echo "<script>location.href='vouchers_management.php';</script>";
    } else {
        if (!is_numeric($updateValue) || $updateValue < 5 || $updateValue > 90) {
            createNotification("The voucher is numeric and valid from 5 to 90! Update Voucher Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='vouchers_management.php';</script>";
        } else {
            $query = "UPDATE `vouchers` SET `discount_percentage`= ? WHERE id = ?";
            $stmt = $conn->prepare($query);

            if (!$stmt) {
                die("Prepare failed");
            }

            $stmt->bind_param("si", $updateValue, $id);

            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }

            $check_stmt->close();
            $stmt->close();
            $conn->close();
            createNotification("Update Voucher Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='vouchers_management.php';</script>";
        }

    }
}
?>