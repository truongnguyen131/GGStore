<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");
    $updateValue = $_GET['update'];
    $id = $_GET['id'];

    $check_query = "SELECT genre_name FROM genres WHERE genre_name = ? AND id != $id";
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
        createNotification("Genre already exists! Update Genre Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
        echo "<script>location.href='genres_management.php';</script>";
    } else {
        if (strlen($updateValue) < 3) {
            createNotification("Genres with lengths greater than 3! Update Genre Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='genres_management.php';</script>";
        } else {
            $query = "UPDATE `genres` SET `genre_name`= ? WHERE id = ?";
            $stmt = $conn->prepare($query);

            if (!$stmt) {
                die("Prepare failed");
            }

            $stmt->bind_param("si", $updateValue,$id);

            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }

            $check_stmt->close();
            $stmt->close();
            $conn->close();
            createNotification("Update Genre Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='genres_management.php';</script>";
        }
    }
}
?>