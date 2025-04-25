<?php
session_start();
include 'session_timeout.php';
if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    header("Location: login.php");
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
    <title> Community Events </title>
    <style>
        .modal-content {
        padding: 20px;
        }
        .modal-body p {
        font-size: 16px;
        margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="nav">
        <div class="logo">
            <p><a href="Community_Events.php"><img src="img/sklogo.png" alt="Logo" class="logo"></a></p>
            <p><strong>Community Events</strong></p>
            </a>
        </div>

        <div class="right-links">
            <a href="dashboard.php"> Back </a>
            <a href="logout.php"> <button class="btn"> Logout </button> </a>
        </div>
    </div>


    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <h3>
                            <h1>
                                <p><a href="sport_event.php"><img src="img/sk.NBA.png" class="Logo" width="60px" alt="Logo" class="logo"></a></p>
                            </h1>
                        </h3>
                    </td>
                    <td>
                        <p><strong><a href="sport_event.php">Sport Event</a></strong></p>
                    </td>
                    <td><small>Liga Pambarangay</small></td>
                </tr>
                <tr>
                    <td>
                        <h3>
                            <h1>
                                <p><a href="education_ai.php"><img src="img/sk.educational.jpg" class="Logo" width="60px" alt="Logo" class="logo"></a></p>
                            </h1>
                        </h3>
                    </td>
                    <td>
                        <p><strong><a href="education_ai.php">Educational Programs</a></strong></p>
                    </td>
                    <td><small>AI Assisted Educational Programs</small></td>
                </tr>
                <tr>
                    <td>
                        <h3>
                            <h1>
                                <p><a href="Scholar_Program.php"><img src="img/sk.scholar.jpg" class="Logo" width="60px" alt="Logo" class="logo"></a></p>
                            </h1>
                        </h3>
                    </td>
                    <td>
                        <p><strong><a href="Scholar_Program.php">Scholar Program</a></strong></p>
                    </td>
                    <td><small>Tuition</small></td>
                </tr>
                <tr>
                    <td>
                        <h3>
                            <h1>
                                <p><a href="dental_mission.php"><img src="img/sk.dental.jpg" class="Logo" width="60px" alt="Logo" class="logo"></a></p>
                            </h1>
                        </h3>
                    </td>
                    <td>
                        <p><strong><a href="dental_mission.php">Dental Mission</a></strong></p>
                    </td>
                    <td><small>Health & Hygiene</small></td>
                </tr>
                <tr>
                    <td>
                        <h3>
                            <h1>
                                <p><a href="Feeding_Program.php"><img src="img/sk.feeding.jpg" class="Logo" width="60px" alt="Logo" class="logo"></a></p>
                            </h1>
                        </h3>
                    </td>
                    <td>
                        <p><strong><a href="Feeding_Program.php">Feeding Program</a></strong></p>
                    </td>
                    <td><small>Nourishment</small></td>
                </tr>
            </tbody>
        </table>
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