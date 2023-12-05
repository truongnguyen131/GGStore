<?php
session_start();
include_once('../mod/database_connection.php');

$fullname = $_POST['fullname'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$username = trim($_POST['username']);
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$check_query = "SELECT username FROM users WHERE username = ?";
$check_stmt = $conn->prepare($check_query);
if ($check_stmt === false) {
    die("Error preparing statement");
}

$check_stmt->bind_param("s", $username);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo "<script>$('#username').addClass('is-invalid');
    $('#errorUsername').html('Username already exists')</script>";
    $check_stmt->close();
    $conn->close();
} else {
    $sql = "INSERT INTO `users`(`username`, `password`, `full_name`, `phone_number`, `email`) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement");
    }

    $stmt->bind_param("sssss", $username, $hashed_password, $fullname, $phone, $email);

    if ($stmt->execute()) {
        echo "<script>
        alert('Register successful');
        Cancel();</script>";
    } else {
        echo "Error inserting record: " . $stmt->error;
    }

    $check_stmt->close();
    $stmt->close();
    $conn->close();
}

?>