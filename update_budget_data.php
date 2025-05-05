<?php
require 'connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'], $data['center'], $data['amount'], $data['details'])) {
    $stmt = $conn->prepare("UPDATE budget_allocation SET center = ?, amount = ?, details = ? WHERE id = ?");
    $stmt->bind_param("sdsi", $data['center'], $data['amount'], $data['details'], $data['id']);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Missing data"]);
}

$conn->close();
?>
