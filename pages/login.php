<?php 
session_start();
include_once('../mod/database_connection.php');

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <form action="" method="post">
        <a class="home" href="../home" title="Trang chủ">Home</a><br>
        <label for="txtUsername">Username</label><br>
        <input type="text" name="txtUsername" id="txtUsername">
        <label id="errorUsername"></label>

        <br><label for="txtPassword">Password</label><br>
        <input type="password" name="txtPassword" id="txtPassword">
        <label id="errorPassword"></label><br>
        <label id="alert"></label>
        <br><label for="cbRememberMe">Remember me</label>
        <input type="checkbox" id="cbRememberMe" name="cbRememberMe"><br>

        <a href="/Galaxy_Game_Store/register">Register New Account</a><br>
        <a href="/Galaxy_Game_Store/forgot_password">Forgot your password?</a><br>
        <button onclick="Login()" type="button">Login</button>
        <button type="button" onclick="Cancel()">Cancel</button><br>
    </form>
</body>
<script src="/Galaxy_Game_Store/includes/js/jquery.min.js"></script>
<p id="getProcessLogin"></p>

<?php
if (isset($_COOKIE['rememberMe']) && $_COOKIE['rememberMe'] == "unchecked") {
    unset($_COOKIE['username']);
    unset($_COOKIE['password']);
    unset($_COOKIE['rememberMe']);
}

if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
    $us = $_COOKIE['username'];
    $pw = $_COOKIE['password'];
    echo '<script>document.getElementById("txtUsername").value = "' . $us . '";
        document.getElementById("txtPassword").value = "' . $pw . '";
        document.getElementById("cbRememberMe").checked=true;</script>';
}
?>

<script>
    function Cancel() {
        document.getElementById('txtUsername').value = "";
        document.getElementById('txtPassword').value = "";
        document.getElementById("cbRememberMe").checked = false;
        $('#errorUsername').html("")
        $('#errorPassword').html("")
        $('#txtUsername').removeClass('is-invalid')
        $('#txtPassword').removeClass('is-invalid')
    }

    function Login() {
        var check = 0
        var username = $('#txtUsername').val()
        var password = $('#txtPassword').val()
        var rememberMe = "unchecked"
        if (password.length == 0) {
            $('#txtPassword').addClass('is-invalid');
            $('#errorPassword').html("Password must not be empty")
        } else {
            $('#txtPassword').removeClass('is-invalid')
            $('#errorPassword').html("")
            check += 1
        }
        if (username.length == 0) {
            $('#txtUsername').addClass('is-invalid');
            $('#errorUsername').html("Username must not be empty")
        } else {
            $('#txtUsername').removeClass('is-invalid')
            $('#errorUsername').html("")
            check += 1
        }

        if (document.getElementById('cbRememberMe').checked) {
            rememberMe = "checked";
        }

        if (check == 2) {
            $.post('/Galaxy_Game_Store/pages/process_login.php', {
                rememberMe: rememberMe,
                username: username,
                password: password
            }, function (data) {
                $('#getProcessLogin').html(data);
            })

        }
    }

    document.addEventListener("keydown", function (event) {
        if (event.keyCode === 13) { 
            event.preventDefault(); 
            Login(); 
        }
    });
</script>

</html>