<?php
require_once 'db_connect.php';

$sql = "SELECT MAX(created_at) as latest FROM announ";
$result = $conn->query($sql);
if ($row = $result->fetch_assoc()) {
    echo json_encode(['latest' => $row['latest']]);
} else {
    echo json_encode(['latest' => null]);
}
?>
