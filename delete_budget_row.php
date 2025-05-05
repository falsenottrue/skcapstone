<?php
require 'connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $stmt = $conn->prepare("DELETE FROM budget_allocation WHERE id = ?");
    $stmt->bind_param("i", $data['id']);

    echo json_encode(["success" => $stmt->execute(), "error" => $stmt->error]);

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Missing ID"]);
}

$conn->close();
?>
