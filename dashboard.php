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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./Style/dashboard.css">
    <link rel="icon" type="image/png" href="./sklogo.png">
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <h1>
                <p><img src="sklogo.png" class="Logo" width="80px" alt="Logo" class="logo"></p>
            </h1>
        </div>
        <ul class="menu">
            <a href='Community_Events.php'>
                <li><strong>Community Events</strong></li>
            </a>
            <a href='Program_Posting.php'>
                <li><strong>Program Posting</strong></li>
            </a>
            <a href='Program_Schedule.php'>
                <li><strong>Program Schedule</strong></li>
            </a>
            <a href='Event_Announcement.php'>
                <li><strong>Event Announcement</strong></li>
            </a>
            <a href='List_Residents.php'>
                <li><strong>List of Residents</strong></strong></li>
            </a>
            <a href='update_form.php'>
                <li><strong>Update Information</strong></li>
            </a>
            <a href="logout.php">
                <li><strong>Logout</strong></li>
            </a>
        </ul>
    </div>

    <div class="content">
        <div class="header">
            <div class="user-profile">
                <img src="sk.users.png" alt="User" class="user-icon">
                <div class="notifications">
                    <img src="sk.notification.png" alt="Notifications">
                </div>
            </div>
        </div>


        
</body>
</html>
