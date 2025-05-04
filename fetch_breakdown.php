<?php
require 'connection.php';

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT center, amount, details FROM budget_allocation";
$result = $conn->query($sql);

$budget_data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $budget_data[] = $row;
    }
}

$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($budget_data);
