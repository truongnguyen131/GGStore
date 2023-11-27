
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
//Load Composer's autoloader
require '../vendor/autoload.php';

$mail = new PHPMailer();
$fullname = isset($_POST['fullname']) ? $_POST['fullname'] : "";
$email = isset($_POST['email']) ? $_POST['email'] : "";
$new_pass = isset($_POST['password']) ? $_POST['password'] : "";

try {
    //Server settings
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'truongnguyennnt131@gmail.com';
    $mail->Password = 'dcig ejxg gnix vrlm';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    //Recipients
    $mail->setFrom('truongnguyennnt131@gmail.com', 'GGStore');
    $mail->addAddress($email, mb_encode_mimeheader($fullname,'UTF-8'));
    $mail->addAddress('truongnguyennnt131@gmail.com');
    // Content

    $mail->isHTML(true);
    $mail->Subject = mb_encode_mimeheader("Galaxy Game Store: New Password", 'UTF-8');
    $mail->Body = "Mật khẩu mới của ".$fullname."<br><b>Mật khẩu: " . $new_pass . "</b>";


    $mail->send();
    echo '<script>
            window.addEventListener("load", function() {
            notification_dialog("Success", "The password has been changed. Please check your email.!");
            });
            </script>';
} catch (Exception $e) {
    echo '<script>
    window.addEventListener("load", function() {
    notification_dialog("Failed", "The password has been changed. Please check your email.!");
    });
    </script>';
}

?>

