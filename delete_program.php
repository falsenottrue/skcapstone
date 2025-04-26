<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
include 'connection.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='login.php';</script>";
    exit();
}

if (isset($_GET['program_id'])) {
    $program_id = $_GET['program_id'];

    $sql = "DELETE FROM programs WHERE program_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $program_id);

    if ($stmt->execute()) {
        echo "<script>alert('Program deleted successfully!'); window.location.href='update_program.php';</script>";
    } else {
        echo "<script>alert('Error deleting program. Ensure there are no active registrations.'); window.location.href='update_program.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
