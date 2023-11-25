<?php
session_start();
include_once('../mod/database_connection.php');

if (isset($_GET['send_info'])) {
    $fullname = $_POST['txtFullname'];
    $phone = $_POST['txtTelephone'];
    $email = $_POST['txtEmail'];
    $username = $_POST['txtUsername'];

    $query = "SELECT * FROM users WHERE full_name = ? AND phone_number = ? AND email = ? AND username = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Error preparing statement");
    }
    $stmt->bind_param("ssss", $fullname, $phone, $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
    } else {
        echo "<p>Incorrect user information</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot password</title>
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
</style>

<body>
    <form name="frmForgotPassword" action="forgot_password?send_info" method="post" class="form_login">
        <div class="from_title">
            <h3>Confirm information</h3>
        </div>


        <div class="form_item">
            <label for="txtFullname">Full name</label>
            <div class="form_item__input" id="divFullName">
            <input type="text" name="txtFullname" id="txtFullname">
            </div>
            <label class="erro" id="errorFullname"></label>
        </div>

        <div class="form_item">
            <label for="txtTelephone">Telephone</label>
            <div class="form_item__input" id="divPhone">
            <input type="tel" name="txtTelephone" id="txtTelephone">
            </div>
            <label class="erro" id="errorTelephone"></label>
        </div>
        <div class="form_item">
            <label for="txtEmail">Email</label>
            <div class="form_item__input" id="divEmail">
            <input type="email" name="txtEmail" id="txtEmail">
            </div>
            <label class="erro" id="errorEmail"></label>
        </div>

        <div class="form_item">
            <label for="txtUsername">Username</label>
            <div class="form_item__input" id="divUserName">
            <input type="text" name="txtUsername" id="txtUsername">
            </div>
            <label class="erro" id="errorUsername"></label>
        </div>

        <div class="form_item form__btn">
            <button onclick="confirm_info()" type="button">Confirm</button>
            <button type="button" onclick="back()">Back</button>
        </div>
    </form>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script src="../assets/js/background.js"></script> -->

<script>
    function confirm_info() {
        document.getElementById("errorFullname").innerHTML = "";
        document.getElementById("errorTelephone").innerHTML = "";
        document.getElementById("errorEmail").innerHTML = "";
        document.getElementById("errorUsername").innerHTML = "";

        const fullNameRegex = /^[\p{L} ]{4,}$/u;
        const phoneNumberRegex = /^0\d{9}$/;
        const emailRegex = /^[a-zA-Z0-9_.+-]+@gmail\.com$/;

        var fullname = document.getElementById("txtFullname").value;
        var phone = document.getElementById("txtTelephone").value;
        var email = document.getElementById("txtEmail").value;
        var username = document.getElementById("txtUsername").value;

        if (fullNameRegex.test(fullname) != true) {
            $('#divFullName').addClass('is-invalid');
            $('#errorFullname').html("Fullname has more than 3 characters")
            document.getElementById("txtFullname").focus()
            return;
        } else {
            $('#divFullName').removeClass('is-invalid')
            $('#errorFullname').html("")
        }

        if (phoneNumberRegex.test(phone) != true) {
            $('#divPhone').addClass('is-invalid');
            $('#errorTelephone').html("The numberphone has 10 numbers and starts with 0")
            document.getElementById("txtTelephone").focus()
            return;
        } else {
            $('#divPhone').removeClass('is-invalid')
            $('#errorPhoneNumber').html("")
        }

        if (emailRegex.test(email) != true) {
            $('#divEmail').addClass('is-invalid');
            $('#errorEmail').html("Email invalid")
            document.getElementById("txtEmail").focus()
            return;
        } else {
            $('#divEmail').removeClass('is-invalid')
            $('#errorEmail').html("")
        }

        if (username.length < 5 || !isNaN(username)) {
            $('#divUserName').addClass('is-invalid');
            $('#errorUsername').html("Username has more than 4 characters and not is a number")
            document.getElementById("txtUsername").focus()
            return;
        } else {
            document.frmForgotPassword.submit();
        }

    }

    function back() {
        window.location = "http://localhost:82/Galaxy_Game_Store/pages/login.php";
    }
</script>

</html>