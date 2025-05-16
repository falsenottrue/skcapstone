<?php
include 'connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernm = trim($_POST['usernm']);
    $email = trim($_POST['email']);
    $passwrd = $_POST['passwrd'];
    $passwrd_confirm = $_POST['passwrd_confirm'];

    if ($passwrd !== $passwrd_confirm) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        $hashed_password = password_hash($passwrd, PASSWORD_DEFAULT);

        // Check if username or email already exists for admin
        $sql = "SELECT * FROM login WHERE (usernm = ? OR email = ?) AND role = 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $usernm, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Username or Email already taken.');</script>";
        } else {
            // Insert admin with role='admin'
            $sql = "INSERT INTO login (usernm, email, passwrd, role) VALUES (?, ?, ?, 'admin')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $usernm, $email, $hashed_password);
            if ($stmt->execute()) {
                $_SESSION['login_id'] = $conn->insert_id;
                $_SESSION['usernm'] = $usernm;
                $_SESSION['role'] = 'admin';
                header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
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
    <title>SK | Admin Registration</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <header>
                <a href="admin_dashboard.php"><img src="img/sklogo.png" alt="Logo" class="logo"></a>
                <br>Admin Registration</br>
            </header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="usernm" id="username" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="passwrd" id="password" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password_confirm">Confirm Password</label>
                    <input type="password" name="passwrd_confirm" id="password_confirm" autocomplete="off" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Register" required>
                </div>
                <div class="links">
                    Already registered? <a href="login.php">Login here</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
