<?php
include 'connection.php';
session_start(); //access control
if (!isset($_SESSION['login_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='index.php';</script>";
    exit();
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
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
             <p><a href="admin_index.php"><img src="img/sklogo.png" alt="Logo" class="logo"></a></p>  
        </div>

        <div class="right-links">
            <a href="Community_Events.php"> Back </a>
            <a href="logout.php"> <button class="btn"> Logout </button> </a>

        </div>
    </div>
    <main>

        <div class="main-box">
           <div class="top">
                <div class="box">
                    <h1><p><img src="img/Sk.Basketball.png" class="Logo" width="70px" alt="Logo" class="logo"></p></h1>
                    <a href='update_program.php'>
                    <h2><strong>Update Program</strong></h2>
                    </a>
                    <h1><p><img src="img/Sk.Basketball.png" class="Logo" width="70px" alt="Logo" class="logo"></p></h1>
                    <a href='feedback_list.php'>
                    <h2><strong>Feedback List</strong></h2>
                    </a>
                </div>
           </div> 
        </div>
    </main>
</body>