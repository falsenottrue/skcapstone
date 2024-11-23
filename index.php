<?php

include 'connection.php';
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    $usernm = $_POST['usernm'];
    $passwrd = $_POST['passwrd'];

    // $sql = "SELECT * FROM users WHERE usernm = '$usernm'";
    // $result = $conn->query($sql);

    $sql = "SELECT * FROM users WHERE usernm = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usernm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();

        if (password_verify($passwrd, $row['passwrd']))
        {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['usernm'] = $usernm;
            header("Location: dashboard.php");
        }
        else
        {
            echo "<script>alert('Invalid Username or Password.');</script>";
        }
    }
    else 
    {
        echo "<script>alert('Invalid Username or Password.');</script>";
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./sklogo.png">
    <title>SK | Login</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <header><img src="sklogo.png" alt="Logo" class="logo"><br>Login</br></header>
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