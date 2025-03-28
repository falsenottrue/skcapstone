<?php
include 'connection.php';
session_start();

session_start(); //access control
if (!isset($_SESSION['login_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='login.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $program_id = $_POST['program_id'];
    $program_name = $_POST['program_name'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $sql = "UPDATE programs SET program_name = ?, description = ?, status = ?, start_date = ?, end_date = ? WHERE program_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $program_name, $description, $status, $start_date, $end_date, $program_id);

    if ($stmt->execute()) {
        echo "<script>alert('Program updated successfully!'); window.location.href='update_program.php';</script>";
    } else {
        echo "<script>alert('Error updating program. Please try again.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>