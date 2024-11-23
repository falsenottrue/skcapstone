<?php

include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "You need to log in first.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user information from the database
// $sql = "SELECT usernm, email FROM users WHERE id = $user_id";
// $result = $conn->query($sql);
$sql = "SELECT usernm, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
    // Fetch the user's data
    $row = $result->fetch_assoc();
    $usernm = $row['usernm'];
    $email = $row['email'];
} else {
    echo "User not found.";
    exit;
}

// Close the database connection
$stmt->close();
$conn->close();
?>
     
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Information</title>
</head>
<body>
     
    <h2>Update Information</h2>
    <form method="POST" action="process_update.php">
        <label for="name">Name:</label><br>
        <input type="text" name="usernm" value="<?php echo htmlspecialchars($usernm); ?>"><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"><br><br>

        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

        <button type="submit">Update</button>
    </form>
</body>
</html>