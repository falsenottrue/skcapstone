<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['login_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

$login_id = $_SESSION['login_id'];

// Check if a pending request already exists
$stmt = $conn->prepare("SELECT status FROM account_deletion_requests WHERE login_id = ? AND status = 'Pending'");
$stmt->bind_param("i", $login_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>alert('You already have a pending deletion request.'); window.location.href='profile.php';</script>";
    exit();
}
$stmt->close();

// Create new request
$insert = $conn->prepare("INSERT INTO account_deletion_requests (login_id) VALUES (?)");
$insert->bind_param("i", $login_id);
if ($insert->execute()) {
    echo "<script>alert('Deletion request submitted successfully. Admin will review it.'); window.location.href='profile.php';</script>";
} else {
    echo "<script>alert('Error submitting request. Please try again later.'); window.location.href='profile.php';</script>";
}
$insert->close();
$conn->close();
