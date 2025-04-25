<?php
session_start();

if (isset($_POST['otp'])) {
    $userOtp = $_POST['otp'];

    // Check if OTP and its expiry exist in session
    if (isset($_SESSION['otp']) && time() < $_SESSION['otp_expiry']) {
        if ($userOtp == $_SESSION['otp']) {
            // ✅ Mark OTP as verified
            $_SESSION['otp_verified'] = true;

            // Optionally clear OTP from session
            unset($_SESSION['otp']);
            unset($_SESSION['otp_expiry']);

            echo "success";
        } else {
            echo "invalid";
        }
    } else {
        echo "expired";
    }
}
?>
