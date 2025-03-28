<?php
session_start();
include 'connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    $usernm = $_POST['usernm'];
    $passwrd = $_POST['passwrd'];

    $stmt = $conn->prepare("SELECT login_id, role, passwrd FROM login WHERE usernm = ?");
    $stmt->bind_param("s", $usernm);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($login_id, $role, $hashed_password);
        $stmt->fetch();

        if (password_verify($passwrd, $hashed_password)) {
            $_SESSION['login_id'] = $login_id;
            $_SESSION['role'] = $role;

            $stmt = $conn->prepare("SELECT first_name FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $login_id);
            $stmt->execute();
            $stmt->bind_result($first_name);
            $stmt->fetch();
            $_SESSION['user_name'] = $first_name;
            $stmt->close();

            if ($role == 'admin') {
                header("Location: admin_index.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid credentials! Please try again.'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('User not found! Please check your username and try again.'); window.location.href='login.php';</script>";
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
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <header><img src="img/sklogo.png" alt="Logo" class="logo"><br>Login</br></header>
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
        </div>
    </div>
</body>
</html>