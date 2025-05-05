<?php
include 'connection.php';

$sql = "SELECT center, amount FROM budget_allocation";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
