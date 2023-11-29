<?php
session_start();
include_once('../mod/database_connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="./assets/css/log_ris_for.css">
</head>
<style>
    body {
        position: relative;
        width: 100%;height: 100vh;
        background-image: url(./assets/images/slider_bg02.jpg);
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
        background-image: url(./assets/images/slider_circle.png);
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
    .form_register {
    position: fixed;
    z-index: 100;
    top: 50%;
    left: 50%;
    transform:translate(-50%, -50%);
    background: #1e2e42ad;
    border: 3px solid #1e2e42e0;
    width: 25%;
    height: auto;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    row-gap: 5px;
    padding: 20px;
    user-select: none;
}
@media screen and (max-width: 1025px) {
    .form_register{
        width: 35%;
        font-size: 13px;
    }
}
@media screen and (max-width: 426px){
    .form_register{
        width: 70%;
        font-size: 13px;
    }
    body::after{
        display: none;
    }
}
@media screen and (max-width: 321px){
    .form_register{
        font-size: 11px;
    }
}
</style>

<body>
<canvas></canvas>
    <form method="post" class="form_register">
        <a class="link_icon home" href="/Galaxy_Game_Store/home" title="Home"> <ion-icon class="icon" name="home-outline"></ion-icon></a>
        <a class="link_icon login" href="./pages/login.php" title="Login"><ion-icon class="icon" name="log-in-outline"></ion-icon></a>
        <div class="from_title">
            <h3>Register</h3>    
        </div>
        <div class="form_item">
            <label for="fullname">Full name</label>
            <div class="form_item__input" id="divFullName">
                <input type="text" name="fullname" id="fullname">
            </div>
            <label class="erro" id="errorFullname"></label>
        </div>

        <div class="form_item">
            <label for="phone">Phone number</label>
            <div class="form_item__input" id="divPhone">
                <input type="tel" id="phone" name="phone">
            </div>
            <label class="erro" id="errorPhoneNumber"></label>
        </div>

        <div class="form_item">
            <label for="email">Email</label>
            <div class="form_item__input" id="divEmail">
                <input type="email" id="email" name="email">
            </div>
            <label class="erro" id="errorEmail"></label>
        </div>

        <div class="form_item">
            <label for="username">Username</label>
            <div class="form_item__input" id="divUserName">
                <input type="text" name="username" id="username">
            </div>
            <label class="erro" id="errorUsername"></label>
        </div>

        <div class="form_item">
            <label for="password">Password</label>
            <div class="form_item__input" id="divPassword">
                <input type="password" name="password" id="password">
            </div>
            <label class="erro" id="errorPassword"></label>
        </div>

        <div class="form_item">
            <label for="confirm_password">Confirm password</label>
            <div class="form_item__input" id="divConfirmPassword">
                <input type="password" name="confirm_password" id="confirm_password">
            </div>
            <label class="erro" id="errorConfirmPassword"></label>
        </div>

        <div class="form_item form__btn">
            <button onclick="Register()" type="button">Register</button>
            <button type="button" onclick="Cancel()">Cancel</button>
        </div>
    </form>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<p id="getProcessRegister"></p>

<script>
    function Cancel() {
        document.getElementById('fullname').value = "";
        document.getElementById('phone').value = "";
        document.getElementById('email').value = "";
        document.getElementById('username').value = "";
        document.getElementById('password').value = "";
        document.getElementById('confirm_password').value = "";

        $('#errorFullname').html("")
        $('#errorPhoneNumber').html("")
        $('#errorEmail').html("")
        $('#errorUsername').html("")
        $('#errorPassword').html("")
        $('#errorConfirmPassword').html("")

        $('#divFullName').removeClass('is-invalid')
        $('#divPhone').removeClass('is-invalid')
        $('#divEmail').removeClass('is-invalid')
        $('#divUserName').removeClass('is-invalid')
        $('#divPassword').removeClass('is-invalid')
        $('#divConfirmPassword').removeClass('is-invalid')
    }
</script>

<script>
    function Register() {

        var check = 0
        var fullname = $('#fullname').val()
        var phone = $('#phone').val()
        var email = $('#email').val()
        var username = $('#username').val()
        var password = $('#password').val()
        var confirm_password = $('#confirm_password').val()
        const fullNameRegex = /^[\p{L} ]{4,}$/u;
        const phoneNumberRegex = /^0\d{9}$/;
        const emailRegex = /^[a-zA-Z0-9_.+-]+@gmail\.com$/;

        if (fullNameRegex.test(fullname) != true) {
            $('#divFullName').addClass('is-invalid');
            $('#errorFullname').html("Fullname has more than 3 characters")
        } else {
            $('#divFullName').removeClass('is-invalid')
            $('#errorFullname').html("")
            check += 1
        }

        if (phoneNumberRegex.test(phone) != true) {
            $('#divPhone').addClass('is-invalid');
            $('#errorPhoneNumber').html("The numberphone has 10 numbers and starts with 0")
        } else {
            $('#divPhone').removeClass('is-invalid')
            $('#errorPhoneNumber').html("")
            check += 1
        }

        if (emailRegex.test(email) != true) {
            $('#divEmail').addClass('is-invalid');
            $('#errorEmail').html("Email invalid")
        } else {
            $('#divEmail').removeClass('is-invalid')
            $('#errorEmail').html("")
            check += 1
        }

        if (username.length < 5 || !isNaN(username)) {
            $('#divUserName').addClass('is-invalid');
            $('#errorUsername').html("Username has more than 4 characters and not is a number")
        } else {
            $('#divUserName').removeClass('is-invalid')
            $('#errorUsername').html("")
            check += 1
        }

        if (password.length == 0) {
            $('#divPassword').addClass('is-invalid');
            $('#errorPassword').html("Password must not be empty")
        } else {
            $('#divPassword').removeClass('is-invalid')
            $('#errorPassword').html("")
            check += 1
        }

        if (confirm_password != password) {
            $('#divConfirmPassword').addClass('is-invalid');
            $('#errorConfirmPassword').html("Password confirmation must be the same as Password")
        } else {
            $('#divConfirmPassword').removeClass('is-invalid')
            $('#errorConfirmPassword').html("")
            check += 1
        }


        if (check == 6) {
            $.post('/Galaxy_Game_Store/pages/process_register.php', {
                fullname: fullname,
                phone: phone,
                email: email,
                username: username,
                password: password
            }, function(data) {
                $('#getProcessRegister').html(data);
            })

        }
    }
</script>

</html>