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
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
    <link rel="stylesheet" href="../assets/css/log_ris_for.css">
</head>
<style>
    body {
        position: relative;
        width: 100%;height: 100vh;
        background-image: url(../assets/images/slider_bg02.jpg);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        overflow: hidden;
    }
    body::after{
        content: '';
        position: absolute;
        top: 50%;left: 50%;
        translate: -50% -50%;
        background-image: url(../assets/images/slider_circle.png);
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
        width: 600px;
        height: 600px;
        opacity: 0.1;
        animation: rotate 20s linear infinite;
    }
    @keyframes rotate{
        0%{
            transform: rotate(0deg);
        }
        100%{
            transform: rotate(360deg);
        }
    }
</style>

<body>
    <!-- <canvas></canvas> -->
    <form action="" method="post" class="form_login">
        <a class="link_icon home" href="../home" title="Trang chủ">
            <ion-icon class="icon" name="home-outline"></ion-icon>
        </a>
        <div class="from_title">
            <h3>Login</h3>
        </div>

        <div class="form_item">
            <label for="txtUsername">Username</label>
            <div class="form_item__input" id="inputUsername">
                <input type="text" name="txtUsername" id="txtUsername">
                <ion-icon name="person-outline"></ion-icon>
            </div>
            <label class="erro" id="errorUsername"></label>
        </div>

        <div class="form_item">
            <label for="txtPassword">Password</label>
            <div class="form_item__input" id="inputPassword">
                <input type="password" name="txtPassword" id="txtPassword">
                <ion-icon name="eye-outline" style="display: none;" id="hidden_pass"></ion-icon>
                <ion-icon name="eye-off-outline" id="show_pass"></ion-icon>
            </div>
            <label class="erro" id="errorPassword"></label>
        </div>

        <label id="alert"></label>

        <div class="form_item remember_and_forgorPass">
            <div class="remeber">
                <input type="checkbox" id="cbRememberMe" name="cbRememberMe">
                <label for="cbRememberMe">Remember me</label>
            </div>
            <a href="/Galaxy_Game_Store/forgot_password">Forgot password?</a>
        </div>



        <div class="form_item form__btn">
            <button onclick="Login()" type="button">Login</button>
            <button type="button" onclick="Cancel()">Cancel</button>
        </div>

        <div class="form_item form_login__register">
            <span>You don't have an account yet?</span>
            <a href="/Galaxy_Game_Store/register">Register</a>
        </div>
    </form>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        $('#inputUsername').removeClass('is-invalid')
        $('#inputPassword').removeClass('is-invalid')
    }

    function Login() {
        var check = 0
        var username = $('#txtUsername').val()
        var password = $('#txtPassword').val()
        var rememberMe = "unchecked"
        if (password.length == 0) {
            $('#inputPassword').addClass('is-invalid');
            $('#errorPassword').html("Password must not be empty")
        } else {
            $('#inputPassword').removeClass('is-invalid')
            $('#errorPassword').html("")
            check += 1
        }
        if (username.length == 0) {
            $('#inputUsername').addClass('is-invalid');
            $('#errorUsername').html("Username must not be empty")
        } else {
            $('#inputUsername').removeClass('is-invalid')
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
            }, function(data) {
                $('#getProcessLogin').html(data);
            })

        }
    }

    document.addEventListener("keydown", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            Login();
        }
    });
</script>
<script>
    const show = document.getElementById('show_pass');
    const hidden = document.getElementById('hidden_pass');
    const input_pass = document.getElementById('txtPassword');
    show.onclick = () => {
        input_pass.setAttribute('type', 'text');
        show.style.display = 'none';
        hidden.style.display = 'block';
    }
    hidden.onclick = () => {
        input_pass.setAttribute('type', 'password');
        show.style.display = 'block';
        hidden.style.display = 'none';
    }
</script>
<!-- <script src="../assets/js/background.js"></script> -->
</html>