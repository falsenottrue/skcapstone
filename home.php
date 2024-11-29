<?php
include 'connection.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
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
    <title> Sport Event </title>
</head>
<body>
    <div class="nav">
        <div class="logo">
             <p><a href="home.php"><img src="img/sk.NBA.png" alt="Logo" class="logo"></a></p>
             <p><strong>Sportevent</strong></p>   
           </a>
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
                <p><strong>BASKETBALL</strong>-(Mon 8:30am to 11:30am) </p>
            </div>
            <div class="box">
                <h1><p><img src="img/SK.Volleyball.png" class="Logo" width="70px" alt="Logo" class="logo"></p></h1>
                <p><strong>VOLLEYBALL</strong>-(Tue 3:00pm to 5:00pm) </p>      
            </div>
            <div class="box">
                <h1><p><img src="img/sk.Badminton.jpg" class="Logo" width="70px" alt="Logo" class="logo"></p></h1>
                <p><strong>BADMINTON</strong>-(Fri 6:00am to 10:00am) </p>      
            </div>
            <div class="box">
                <h1><p><img src="img/sk.Swimming.jpg" class="Logo" width="70px" alt="Logo" class="logo"></p></h1>
                <p><strong>SWIMMING</strong>-(Sat 4:00pm to 8:00pm) </p>
            </div>
            <div class="box">
                <h1><p><img src="img/sk.Dancing.png" class="Logo" width="70px" alt="Logo" class="logo"></p></h1>
                <p><strong>DANCING</strong>-(Sun 6:30am to 11:00am) </p>
            </div>
           </div> 
        </div>
    </main>
</body>