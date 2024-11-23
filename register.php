<?php

include 'connection.php';
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $usernm = $_POST['usernm'];
    $email = $_POST['email'];
    $passwrd = password_hash($_POST['passwrd'], PASSWORD_DEFAULT);


    // $sql = "SELECT * FROM users WHERE usernm = '$usernm'";
    // $result = $conn->query($sql);
    $sql = "SELECT * FROM users WHERE usernm = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usernm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username already taken. Please choose a different username.');</script>";
    } else {
        $sql = "INSERT INTO users (usernm, email, passwrd) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $usernm, $email, $passwrd);

        // if ($conn->query($sql) === TRUE) {
        //     echo "New record created successfully. <a href='demographics.php'>Fill out your Personal Information</a>";
        // } else {
        //     echo "Error: " . $sql . "<br>" . $conn->error;
        // }
        if ($stmt->execute()) {
            // Get the user_id of the newly registered user
            $user_id = $conn->insert_id;

            // Set session variables to log the user in immediately after registration
            $_SESSION['user_id'] = $user_id; // Store user_id from the database
            $_SESSION['usernm'] = $usernm;

            header("Location: demographics.php");
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // Close the statement and the database connection
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
    <link rel="icon" type="image/png" href="./sklogo.png">
    <title>SK | Registration</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <header><img src="sklogo.png" alt="Logo" class="logo"><br>Registration</br></header>
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
                    Already a member? <a href="index.php">Sign In</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>