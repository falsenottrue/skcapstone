<?php
include 'connection.php';

// Fetch feedback summary
$result = $conn->query("SELECT topic, 
    COUNT(*) AS total, 
    SUM(feedback = 'Yes') AS helpful, 
    SUM(feedback = 'No') AS not_helpful 
    FROM ai_feedback GROUP BY topic");

echo "<h1>Feedback Analysis</h1>";
while ($row = $result->fetch_assoc()) {
    echo "<p><strong>Topic:</strong> " . htmlspecialchars($row['topic']) . "</p>";
    echo "<p><strong>Total Feedback:</strong> " . $row['total'] . "</p>";
    echo "<p><strong>Helpful:</strong> " . $row['helpful'] . "</p>";
    echo "<p><strong>Not Helpful:</strong> " . $row['not_helpful'] . "</p>";
    echo "<hr>";
}
?>