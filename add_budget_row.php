<?php
require 'connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['center'], $data['amount'])) {
    $details = $data['details'] ?? '';
    $stmt = $conn->prepare("INSERT INTO budget_allocation (center, amount, details) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $data['center'], $data['amount'], $details);

    echo json_encode(["success" => $stmt->execute(), "error" => $stmt->error]);

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Missing data"]);
}

$conn->close();
?>
