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
</head>

<body>
    <form name="frmForgotPassword" action="forgot_password?send_info" method="post">
        <h3>Confirm information</h3>
        
        <label for="txtFullname">Full name</label><br>
        <input type="text" name="txtFullname" id="txtFullname">
        <label id="errorFullname"></label>

        <br><label for="txtTelephone">Telephone</label><br>
        <input type="tel" name="txtTelephone" id="txtTelephone">
        <label id="errorTelephone"></label>

        <br><label for="txtEmail">Email Address</label><br>
        <input type="email" name="txtEmail" id="txtEmail">
        <label id="errorEmail"></label>

        <br><label for="txtUsername">Username</label><br>
        <input type="text" name="txtUsername" id="txtUsername">
        <label id="errorUsername"></label>

        <br><button onclick="confirm_info()" type="button">Confirm</button>
        <button type="button" onclick="back()">Back</button><br>
    </form>
</body>
<script src="/Galaxy_Game_Store/includes/js/jquery.min.js"></script>

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
            $('#fullName').addClass('is-invalid');
            $('#errorFullname').html("Fullname has more than 3 characters")
            document.getElementById("txtFullname").focus()
            return;
        } else {
            $('#fullName').removeClass('is-invalid')
            $('#errorFullname').html("")
        }

        if (phoneNumberRegex.test(phone) != true) {
            $('#phone').addClass('is-invalid');
            $('#errorTelephone').html("The numberphone has 10 numbers and starts with 0")
            document.getElementById("txtTelephone").focus()
            return;
        } else {
            $('#phone').removeClass('is-invalid')
            $('#errorPhoneNumber').html("")
        }

        if (emailRegex.test(email) != true) {
            $('#email').addClass('is-invalid');
            $('#errorEmail').html("Email invalid")
            document.getElementById("txtEmail").focus()
            return;
        } else {
            $('#email').removeClass('is-invalid')
            $('#errorEmail').html("")
        }

        if (username.length < 5 || !isNaN(username)) {
            $('#username').addClass('is-invalid');
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