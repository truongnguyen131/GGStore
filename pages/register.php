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
</head>

<body>
    <form method="post">
        <a href="/Galaxy_Game_Store/login">login</a>
        <a href="/Galaxy_Game_Store/home">home</a><br>

        <label for="fullname">Full name</label><br>
        <input type="text" name="fullname" id="fullname">
        <label id="errorFullname"></label>

        <br><label for="phone">Phone number</label><br>
        <input type="tel" id="phone" name="phone">
        <label id="errorPhoneNumber"></label>

        <br><label for="email">Email</label><br>
        <input type="email" id="email" name="email">
        <label id="errorEmail"></label>

        <br><label for="username">Username</label><br>
        <input type="text" name="username" id="username">
        <label id="errorUsername"></label>

        <br><label for="password">Password</label><br>
        <input type="password" name="password" id="password">
        <label id="errorPassword"></label>

        <br><label for="confirm_password">Confirm password</label><br>
        <input type="password" name="confirm_password" id="confirm_password">
        <label id="errorConfirmPassword"></label>

        <br><button onclick="Register()" type="button">Register</button>
        <button type="button" onclick="Cancel()">Cancel</button>
    </form>
</body>
<script src="/Galaxy_Game_Store/includes/js/jquery.min.js"></script>
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

        $('#fullname').removeClass('is-invalid')
        $('#phone').removeClass('is-invalid')
        $('#email').removeClass('is-invalid')
        $('#username').removeClass('is-invalid')
        $('#password').removeClass('is-invalid')
        $('#confirm_password').removeClass('is-invalid')
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
            $('#fullName').addClass('is-invalid');
            $('#errorFullname').html("Fullname has more than 3 characters")
        } else {
            $('#fullName').removeClass('is-invalid')
            $('#errorFullname').html("")
            check += 1
        }

        if (phoneNumberRegex.test(phone) != true) {
            $('#phone').addClass('is-invalid');
            $('#errorPhoneNumber').html("The numberphone has 10 numbers and starts with 0")
        } else {
            $('#phone').removeClass('is-invalid')
            $('#errorPhoneNumber').html("")
            check += 1
        }

        if (emailRegex.test(email) != true) {
            $('#email').addClass('is-invalid');
            $('#errorEmail').html("Email invalid")
        } else {
            $('#email').removeClass('is-invalid')
            $('#errorEmail').html("")
            check += 1
        }

        if (username.length < 5 || !isNaN(username)) {
            $('#username').addClass('is-invalid');
            $('#errorUsername').html("Username has more than 4 characters and not is a number")
        } else {
            $('#username').removeClass('is-invalid')
            $('#errorUsername').html("")
            check += 1
        }

        if (password.length == 0) {
            $('#password').addClass('is-invalid');
            $('#errorPassword').html("Password must not be empty")
        } else {
            $('#password').removeClass('is-invalid')
            $('#errorPassword').html("")
            check += 1
        }

        if (confirm_password != password) {
            $('#confirm_password').addClass('is-invalid');
            $('#errorConfirmPassword').html("Password confirmation must be the same as Password")
        } else {
            $('#password').removeClass('is-invalid')
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
            }, function (data) {
                $('#getProcessRegister').html(data);
            })

        }
    }

</script>

</html>