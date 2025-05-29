<?php
session_start();
include 'connection.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$showOtpModal = false;

function clearLoginSessionData() {
    unset($_SESSION['otp']);
    unset($_SESSION['otp_expiry']);
    unset($_SESSION['email']);
    unset($_SESSION['login_id']);
    unset($_SESSION['role']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usernm'])) {
    $usernm = $_POST['usernm'];
    $passwrd = $_POST['passwrd'];

    $stmt = $conn->prepare("SELECT login_id, role, passwrd, email FROM login WHERE usernm = ?");
    $stmt->bind_param("s", $usernm);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($login_id, $role, $hashed_password, $email);
        $stmt->fetch();

        if (password_verify($passwrd, $hashed_password)) {
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_expiry'] = time() + 300;
            $_SESSION['email'] = $email;
            $_SESSION['login_id'] = $login_id;
            $_SESSION['role'] = $role;

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'nagkaisangsk@gmail.com';
                $mail->Password = 'mcxz ibwo hqzp pmnw';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('your@email.com', 'SK OTP');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code';
                $mail->Body = "Your OTP code is <b>$otp</b>. It will expire in 5 minutes.";
                $mail->send();

                $showOtpModal = true;

            } catch (Exception $e) {
                clearLoginSessionData(); // <<< Clear residual data if email failed
                echo "<script>alert('Could not send OTP. {$mail->ErrorInfo}');</script>";
            }
        } else {
            clearLoginSessionData(); // <<< Clear residual data if wrong password
            echo "<script>alert('Invalid credentials.');</script>";
        }
    } else {
        clearLoginSessionData(); // <<< Clear residual data if user not found
        echo "<script>alert('User not found.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style2.css">
    <link rel="icon" type="image/png" href="img/sklogo.png">
    <title>SK | Login</title>
    <style>
        .modal {
            display: none; 
            position: fixed;
            z-index: 1001;
            left: 0; top: 0;
            width: 100%; height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .modal-buttons {
            margin-top: 20px;
        }

        .modal-buttons button {
            padding: 10px 15px;
            margin: 5px;
            font-size: 14px;
            border: none;
            border-radius: 8px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .modal-buttons button:hover {
            background-color: #0056b3;
        }

        #otpInput {
            font-size: 18px;
            padding: 10px;
            width: 80%;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-top: 15px;
        }

    </style>
</head>
<body>
    <?php if ($showOtpModal): ?>
    <div id="otpModal" class="modal" style="display:block;">
        <div class="modal-content">
            <h2>Enter OTP</h2>
            <p>Check your email for the code</p>
            <form id="otpForm">
                <input type="text" name="otp" id="otpInput" maxlength="6" required>
                <div class="modal-buttons">
                    <button type="submit">Verify</button>
                    <button type="button" onclick="resendOtp()">Resend OTP</button>
                </div>
                <p id="otpMessage" style="color:red; margin-top:10px;"></p>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="container">
        <div class="box form-box">
            <header>
                <a href="dashboard.php"><img src="img/sklogo.png" alt="Logo" class="logo"></a>
                <br>Login</br>
            </header>
            <form action="" method="post">
                
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="usernm" id="username" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="passwrd" id="password" autocomplete="off" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login" required>
                </div>
                <div class="links">
                    Don't have account? <a href="register.php">Sign Up Now</a>
                </div>
            </form>
            <a href="dashboard.php"> <button class="btn btn-danger"> Back </button> </a>
        </div>
    </div>
    <script>
    document.getElementById("otpForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const otp = document.getElementById("otpInput").value;

        fetch("verify_otp_ajax.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "otp=" + otp
        })
        .then(res => res.text())
        .then(response => {
            if (response.trim() === "success") {
                window.location.href = "<?php echo ($_SESSION['role'] === 'admin') ? 'admin_dashboard.php' : 'dashboard.php'; ?>";
            } else if (response.trim() === "invalid") {
                document.getElementById("otpMessage").innerText = "Incorrect OTP.";
            } else if (response.trim() === "expired") {
                document.getElementById("otpMessage").innerText = "OTP expired. Please log in again.";
            }
        });
    });

    function resendOtp() {
    fetch("resend_otp.php")
        .then(res => res.text())
        .then(response => {
            if (response.trim() === "resent") {
                document.getElementById("otpMessage").innerText = "New OTP sent!";
            } else if (response.trim() === "session_expired") {
                document.getElementById("otpMessage").innerText = "Session expired. Please login again.";
            } else {
                document.getElementById("otpMessage").innerText = "Failed to resend OTP.";
            }
        });
    }
    </script>
</body>
</html>