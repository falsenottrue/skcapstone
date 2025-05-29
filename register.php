<?php
include 'connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernm = $_POST['usernm'];
    $email = $_POST['email'];
    $password_raw = $_POST['passwrd'];

    // Validate password strength using regex
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/', $password_raw)) {
        echo "<script>alert('Password must be 8-16 characters long, include at least one uppercase letter, one lowercase letter, and one number.');</script>";
    } else {
        $passwrd = password_hash($password_raw, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM login WHERE usernm = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usernm);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Username already taken. Please choose a different username.');</script>";
        } else {
            $sql = "INSERT INTO login (usernm, email, passwrd) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss",  $usernm, $email, $passwrd);

            if ($stmt->execute()) {
                $login_id = $conn->insert_id;

                $_SESSION['login_id'] = $login_id;
                $_SESSION['usernm'] = $usernm;

                header("Location: user_info.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        $stmt->close();
        $conn->close();
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
    <title>SK | Registration</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <header>
                <a href="dashboard.php"><img src="img/sklogo.png" alt="Logo" class="logo"></a>
                <br>Registration</br>
            </header>
            <form action="" method="post">
                
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="usernm" id="username" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <div style="position: relative;">
                        <input type="password" name="passwrd" id="password" autocomplete="off" required
                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$"
                            title="Password must be 8-12 characters long, include at least one uppercase letter, one lowercase letter, and one number.">
                        <span id="togglePassword" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); cursor:pointer;">👁️</span>
                    </div>
                    <ul id="passwordRequirements" style="font-size: 13px; color: red; list-style: none; padding: 5px 0 0 0; margin: 0;">
                        <li id="length">✔ 8–16 characters</li>
                        <li id="uppercase">✔ At least 1 uppercase letter</li>
                        <li id="lowercase">✔ At least 1 lowercase letter</li>
                        <li id="number">✔ At least 1 number</li>
                    </ul>
                </div>


                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Sign Up" required>
                </div>
                <div class="links">
                    Already a member? <a href="login.php">Sign In</a>
                </div>
            </form> 
            <a href="dashboard.php"> <button class="btn btn-danger"> Back </button> </a>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const password = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const lengthCheck = document.getElementById('length');
        const uppercaseCheck = document.getElementById('uppercase');
        const lowercaseCheck = document.getElementById('lowercase');
        const numberCheck = document.getElementById('number');

        // Show/Hide password
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
        });

        // Live validation
        password.addEventListener('input', function () {
            const value = password.value;

            // Length check
            if (value.length >= 8 && value.length <= 16) {
                lengthCheck.style.color = 'green';
            } else {
                lengthCheck.style.color = 'red';
            }

            // Uppercase
            if (/[A-Z]/.test(value)) {
                uppercaseCheck.style.color = 'green';
            } else {
                uppercaseCheck.style.color = 'red';
            }

            // Lowercase
            if (/[a-z]/.test(value)) {
                lowercaseCheck.style.color = 'green';
            } else {
                lowercaseCheck.style.color = 'red';
            }

            // Number
            if (/\d/.test(value)) {
                numberCheck.style.color = 'green';
            } else {
                numberCheck.style.color = 'red';
            }
        });
    });
    </script>

</body>
</html>