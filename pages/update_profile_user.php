<?php
session_start();
include_once('../mod/database_connection.php');
?>
<?php
// UPDATE Data
$task = isset($_GET['task']) ? $_GET['task'] : "";
$username = isset($_POST['username']) ? $_POST['username'] : "";
$fullname = isset($_POST['fullname']) ? $_POST['fullname'] : "";
$email = isset($_POST['email']) ? $_POST['email'] : "";
$phone = isset($_POST['phone']) ? $_POST['phone'] : "";
$password = isset($_POST['current_pass']) ? $_POST['current_pass'] : "";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
if ($task == 'change_infor') {
    $sql = "UPDATE users SET full_name= ? ,phone_number= ? ,email= ? WHERE username = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $fullname, $phone, $email, $username);
    if ($stmt->execute()) {
        echo '<script>
        notification_dialog("Success", "Update information successfully!");
        setTimeout(()=>{
            location.href = "profile_user.php";
        },1000)
        </script>';
    } else {
        echo '<script>
        notification_dialog("Failed", "Update information Failed!!");
        setTimeout(()=>{
            location.href = "profile_user.php";
        },1000)
        </script>';
    }
    $stmt->close();
    $conn->close();
}
if ($task == 'change_pass') {
    $sql = "UPDATE users SET password = ? WHERE username = '" . $username . "'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $hashed_password);
    if ($stmt->execute()) {
        echo '<script>
        notification_dialog("Success", "Update password successfully!");
        </script>';
    } else {
        echo '<script>
        notification_dialog("Failed", "Update password unsuccessful!");
        </script>';
    }
    $stmt->close();
    $conn->close();
}
?>
