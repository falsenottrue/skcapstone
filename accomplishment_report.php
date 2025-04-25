<?php
session_start();
include 'session_timeout.php';
?>  


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style2.css">
    <title>Accomplishment Report</title>
</head>

<body>
    <div class="nav">
        <div class="logo">
            <a href="Community_Events.php">
                <img src="img/sklogo.png" alt="Logo" class="logo">
            </a>
            <p><strong>Accomplishment Report</strong></p>
        </div>

        <div class="right-links">
            <a href="dashboard.php"> Back </a>
            <a href="logout.php"> <button class="btn"> Logout </button> </a>
        </div>
    </div>

    <div class="main-box">
        <div class="gallery">
            <div class="box"><img src="img/programs1.jpg" alt="Program 1"><img src="img/programs2.jpg" alt="Program 2"></div>
            <div class="box"><img src="img/programs3.jpg" alt="Program 3"><img src="img/programs4.jpg" alt="Program 4"></div>
            <div class="box"><img src="img/programs5.jpg" alt="Program 5"><img src="img/programs6.jpg" alt="Program 6"></div>
            <div class="box"><img src="img/programs7.jpg" alt="Program 7"><img src="img/programs8.jpg" alt="Program 8"></div>
            <div class="box"><img src="img/programs9.jpg" alt="Program 9"><img src="img/programs10.jpg" alt="Program 10"></div>
        </div>
    </div>
        <!-- Session Timeout Modal -->
    <div id="sessionModal" class="modal">
    <div class="modal-content">
        <h3>You're inactive</h3>
        <p>Your session is about to expire. Do you want to stay logged in?</p>
        <div class="modal-buttons">
        <button onclick="extendSession()">Yes, Stay Logged In</button>
        <button onclick="logout()">No, Log Me Out</button>
        </div>
    </div>
    </div>

    <script>
    let idleTime = 0;
    const maxIdleTime = 12 * 60; // 12 minutes idle before showing warning
    const logoutTime = 15 * 60; // 15 minutes total timeout

    // Reset idle timer on activity
    function resetTimer() {
    idleTime = 0;
    }
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.onscroll = resetTimer;
    document.onclick = resetTimer;

    setInterval(() => {
    idleTime++;

    // Show modal at 12 minutes idle
    if (idleTime === maxIdleTime) {
        document.getElementById("sessionModal").style.display = "block";
    }

    // Auto-logout at 15 minutes
    if (idleTime >= logoutTime) {
        logout();
    }
    }, 1000); // check every second

    function extendSession() {
    fetch("keep_alive.php"); // Pings the server
    idleTime = 0;
    document.getElementById("sessionModal").style.display = "none";
    }

    function logout() {
    window.location.href = "logout.php";
    }
    </script>
</body>

</html>
