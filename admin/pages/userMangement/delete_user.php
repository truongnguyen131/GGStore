<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");
    $id = $_GET['id'];
    $query = "DELETE FROM `users` WHERE id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Prepare failed");
    }

    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
    createNotification("Delete Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
    echo "<script>location.href='users_management.php';</script>";
}
?>