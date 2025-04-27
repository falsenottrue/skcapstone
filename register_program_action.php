<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
include 'connection.php';
session_start();

if (!isset($_SESSION['login_id'])) {
    echo "<script>alert('You must be logged in to register for a program.'); window.location.href='login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_id = $_SESSION['login_id'];
    $program_id = $_POST['program_id'];

    $check_sql = "SELECT * FROM program_registrations WHERE login_id = ? AND program_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $login_id, $program_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('You are already registered for this program.'); window.location.href='index.php';</script>";
    } else {
        $insert_sql = "INSERT INTO program_registrations (login_id, program_id, status, notified) VALUES (?, ?, 'Registered', 0)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ii", $login_id, $program_id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Successfully registered for the program!'); window.location.href='register_program.php';</script>";
        } else {
            echo "<script>alert('Error registering for the program.');</script>";
        }
    }

    $stmt->close();
}
$conn->close();
?>
