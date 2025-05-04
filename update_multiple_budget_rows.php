<?php
require 'connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['updates'])) {
    echo json_encode(["success" => false, "error" => "No data sent"]);
    exit;
}

$success = true;
$error = '';

foreach ($data['updates'] as $row) {
    if (!isset($row['id'], $row['center'], $row['amount'])) {
        continue;
    }

    $stmt = $conn->prepare("UPDATE budget_allocation SET center = ?, amount = ?, details = ? WHERE id = ?");
    $stmt->bind_param("sdsi", $row['center'], $row['amount'], $row['details'], $row['id']);

    if (!$stmt->execute()) {
        $success = false;
        $error = $stmt->error;
        break;
    }

    $stmt->close();
}

$conn->close();
echo json_encode(["success" => $success, "error" => $error]);
?>
