<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['login_id'])) {
    echo "Access denied.";
    exit;
}

$login_id = $_SESSION['login_id'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

if ($new_password !== $confirm_password) {
    echo "New passwords do not match.";
    exit;
}

$stmt = $conn->prepare("SELECT passwrd FROM login WHERE login_id = ?");
$stmt->bind_param("i", $login_id);
$stmt->execute();
$stmt->bind_result($hashed_password);
$stmt->fetch();
$stmt->close();

if (!password_verify($current_password, $hashed_password)) {
    echo "Current password is incorrect.";
    exit;
}

$new_hashed = password_hash($new_password, PASSWORD_DEFAULT);

$update = $conn->prepare("UPDATE login SET passwrd = ? WHERE login_id = ?");
$update->bind_param("si", $new_hashed, $login_id);
if ($update->execute()) {
    echo "Password updated successfully.";
} else {
    echo "Error updating password.";
}
$update->close();
?>
