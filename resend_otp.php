<?php
session_start();
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if email is still in session
if (!isset($_SESSION['email'])) {
    echo "session_expired";
    exit;
}

$email = $_SESSION['email'];
$otp = rand(100000, 999999);
$_SESSION['otp'] = $otp;
$_SESSION['otp_expiry'] = time() + 180;

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'paulwilliamtrinidad@gmail.com';
    $mail->Password = 'tfmn yupn dxtw fpow';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('your@email.com', 'SK OTP');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Your New OTP Code';
    $mail->Body = "Your new OTP code is <b>$otp</b>. It will expire in 3 minutes.";
    $mail->send();

    echo "resent";
} catch (Exception $e) {
    echo "error";
}
?>
