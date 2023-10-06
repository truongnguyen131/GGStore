<?php
function success($conn, $id_account, $username, $role)
{
    $_SESSION["id_account"] = $id_account;
    $_SESSION["userName"] = $username;
    $_SESSION["role"] = $role;
    $sql = "UPDATE users SET login_attempts = 0 WHERE username = '$username'";
    $conn->query($sql);
    echo '<script>window.location="http://localhost:82/Galaxy_Game_Store/home"</script>';
}

function error()
{
    echo '<script>
                $("#errorUsername").html("Username invalid");
                $("#errorPassword").html("Password invalid");
                $("#alert").html("Wrong more than 5 times auto-locked account");
            </script>';
}

function fail($conn, $username, $login_attempts)
{

    $sql = "UPDATE users SET login_attempts = login_attempts + 1 WHERE username = '$username'";
    $conn->query($sql);
    if ($login_attempts == 4) {
        $currentDateTime = new DateTime();
        $lockedUntil = $currentDateTime->modify('+1 day')->format('Y-m-d');
        $sql = "UPDATE users SET locked_until = '$lockedUntil' WHERE username = '$username'";
        $conn->query($sql);
        $sql = "UPDATE users SET login_attempts = 0 WHERE username = '$username'";
        $conn->query($sql);
        echo '<script>
                $("#alert").html("Your account is locked for 1 day");
            </script>';
    } else {
        error();
    }
}
?>



<?php
session_start();
include_once('../mod/database_connection.php');

$username = trim($_POST['username']);
$post_password = $_POST['password'];
$rememberMe = $_POST['rememberMe'];
$login_attempts = true;

setcookie("rememberMe", "unchecked", time() + (86400 * 30));
if ($rememberMe == "checked") {
    setcookie("rememberMe", "checked", time() + (86400 * 30));
    setcookie("username", $username, time() + (86400 * 30));
    setcookie("password", $post_password, time() + (86400 * 30));
}

$sql = "SELECT id, password, role, login_attempts, locked_until FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing statement");
}

$stmt->bind_param("s", $username);

if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($result->num_rows == 0) {
    error();
} else {
    $id_account = $row['id'];
    $hashed_password = $row['password'];
    $role = $row['role'];
    $login_attempts = $row['login_attempts'];
    $locked_until = $row['locked_until'];

    if ($locked_until != null && $locked_until > date('Y-m-d')) {
        echo '<script>
                    $("#alert").html("Your account is being locked");
                </script>';
    } else {
        if ($role == "developer") {
            $a = mb_split("_", $post_password);
            $password = $a[0];
            if (isset($a[1]) && $a[1] == "lvtn" && password_verify($password, $hashed_password)) {
                success($conn, $id_account, $username, $role);
            } else {
                fail($conn, $username, $login_attempts);
            }
        } else {
            if (password_verify($post_password, $hashed_password)) {
                success($conn, $id_account, $username, $role);
            } else {
                fail($conn, $username, $login_attempts);
            }
        }
    }
}

$stmt->close();
$conn->close();
?>