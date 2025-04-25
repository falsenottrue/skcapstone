<?php
include 'connection.php';
include 'session_timeout.php';
session_start(); //access control

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT login_id, first_name FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $_SESSION['login_id'] = $user['user_id'];
        $_SESSION['usernm'] = $user['first_name'];

        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Invalid credentials. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style/dashboard.css">
    <link rel="icon" type="image/png" href="img/sklogo.png">
</head>

<body>
    <div class="sidebar">
    <div class="logo">
        <h1>
            <p><img src="img/sklogo.png" class="Logo" width="80px" alt="Logo"></p>
        </h1>
    </div>
    <ul class="menu">
        <?php if (isset($_SESSION['login_id'])): ?>
            <li style="font-family: 'Arial', sans-serif; font-size: 18px; color:rgb(241, 241, 241);">
                <strong>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</strong>
            </li>
            <a href='logout.php'>
                <li><strong>Logout</strong></li>
            </a>
        <?php else: ?>
            <a href='login.php'>
                <li><strong>Login/Sign Up</strong></li>
            </a>
        <?php endif; ?>
        <a href='Community_Events.php'>
            <li><strong>Community Events</strong></li>
        </a>
        <a href='Event_Announcement.php'>
            <li><strong>Event Announcement</strong></li>
        </a>
        <a href='register_program.php'>
            <li><strong>Program Registration</strong></li>
        </a>
        <a href='update_form.php'>
            <li><strong>Update Information</strong></li>
        </a>
        <a href="feedback.php">
            <li><strong>Submit Feedback</strong></li>
        </a>
    </ul>
</div>


    <div class="content">
        <div class="header">
            <div class="user-profile">
                <img src="img/sk.users.png" alt="User" class="user-icon">
                <div class="notifications">
                    <img src="img/sk.notification.png" alt="Notifications">
                </div>
            </div>
        </div>


        <div class="main-box">
           <div class="box">
            <p><a href="accomplishment_report.php"><img src="img/bebeko.jpg" width="500x" alt="Logo" class="logo"> <img src="img/bebeko2.jpg" width="500x" alt="Logo" class="logo"></a></p>
            <p><a><img src="img/librengprint.jpg" width="500x" alt="Logo" class="logo"> <img src="img/librelg.jpg" width="500x" alt="Logo" class="logo"></a></p>
            <p><a><img src="img/lib1.jpg" width="500x" alt="Logo" class="logo">          <img src="img/lib2.jpg" width="500x" alt="Logo" class="logo"></a></p>
            </div>
        </div>

       
</body>
</html>               