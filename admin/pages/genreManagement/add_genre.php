<?php
include("../add_template.php");
function main()
{
    include("../../../mod/database_connection.php");
    $addValue = $_GET['add'];

    $check_query = "SELECT genre_name FROM genres WHERE genre_name = ?";
    $check_stmt = $conn->prepare($check_query);

    if ($check_stmt === false) {
        die("Error preparing statement");
    }

    $check_stmt->bind_param("s", $addValue);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $check_stmt->close();
        $conn->close();
        createNotification("Genre already exists! Add Genre Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
        echo "<script>location.href='genres_management.php';</script>";
    } else {
        if (strlen($addValue) < 3) {
            createNotification("Genres with lengths greater than 3! Add Genre Failed!", "error", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='genres_management.php';</script>";
        } else {
            $query = "INSERT INTO `genres`(`genre_name`) VALUES (?)";
            $stmt = $conn->prepare($query);

            if (!$stmt) {
                die("Prepare failed");
            }

            $stmt->bind_param("s", $addValue);

            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }

            $check_stmt->close();
            $stmt->close();
            $conn->close();
            createNotification("Add Genre Successful!", "success", date('Y-m-d H:i:s'), "undisplayed");
            echo "<script>location.href='genres_management.php';</script>";
        }

    }
}
?>