<?php
include 'connection.php';
session_start();

// Ensure only logged-in users can access this page
if (!isset($_SESSION['login_id']) || $_SESSION['role'] !== 'user') {
    echo "<script>alert('You must be logged in as a user to submit feedback.'); window.location.href='index.php';</script>";
    exit();
}

// Get user info from database
$login_id = $_SESSION['login_id'];
$query = $conn->prepare("SELECT user_id, first_name, last_name, email FROM users JOIN login ON users.user_id = login.login_id WHERE login_id = ?");
$query->bind_param("i", $login_id);
$query->execute();
$query->bind_result($user_id, $first_name, $last_name, $email);
$query->fetch();
$query->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback_type = $_POST['feedback_type'];
    $message = $_POST['message'];
    $date_submitted = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO feedback (user_id, feedback_type, message, date_submitted) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $feedback_type, $message, $date_submitted);

    if ($stmt->execute()) {
        echo "<script>alert('Feedback submitted successfully!'); window.location.href='feedback.php';</script>";
    } else {
        echo "<script>alert('Error submitting feedback. Please try again.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Feedback</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/form-dark.css">
</head>
<body class="container mt-4">

    <h2 class="text-center">Submit Your Feedback</h2>
    
    <form action="feedback.php" method="POST" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Your Name:</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($first_name . ' ' . $last_name); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Your Email:</label>
            <input type="email" class="form-control" value="<?= htmlspecialchars($email); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Feedback Type:</label>
            <select name="feedback_type" class="form-control" required>
                <option value="Website Feedback">Website Feedback</option>
                <option value="SK Feedback">SK Feedback</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Your Feedback:</label>
            <textarea name="message" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Submit Feedback</button>
        <hr>
    </form>
        <a href="index.php"> <button class="btn btn-danger w-100"> Back </button> </a>
</body>
</html>
