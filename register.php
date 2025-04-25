<?php

include 'connection.php';
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $usernm = $_POST['usernm'];
    $email = $_POST['email'];
    $passwrd = password_hash($_POST['passwrd'], PASSWORD_DEFAULT);


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
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
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
                    <input type="password" name="passwrd" id="password" autocomplete="off" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Sign Up" required>
                </div>
                <div class="links">
                    Already a member? <a href="login.php">Sign In</a>
                </div>
            </form> 
        </div>
    </div>
</body>
</html>