<?php

include 'connection.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "You need to be logged in first.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch existing data for the specified ID
$sql = "SELECT * FROM demographics WHERE dm_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


// Check if the user exists
if ($result->num_rows > 0) {
    // Fetch the user's data
    $row = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

$stmt->close();

// Update record if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gather form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $user_bday = $_POST['user_bday'];
    $contact_number = $_POST['contact_number'];
    $status = $_POST['status'];
    $occupation = $_POST['occupation'];
    $sports = $_POST['sports'];
    $precinct_number = $_POST['precinct_number'];

    $sql = "UPDATE demographics SET 
    first_name = ?, last_name = ?, address = ?, gender = ?, user_bday = ?, 
    contact_number = ?, status = ?, occupation = ?, sports = ?, precinct_number = ? 
    WHERE dm_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssssssi", 
    $first_name, $last_name, $address, $gender, $user_bday, $contact_number, 
    $status, $occupation, $sports, $precinct_number, $user_id 
);

if ($stmt->execute()) {
    $conn->commit();
    echo "<script>alert('Record updated successfully.'); window.location.href = 'dashboard.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}


$stmt->close();
$conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Demographics Form</title>
</head>
<body>
<h2>Update Demographics Form</h2>
<form method="POST" action="">

<!-- Personal Info Section -->
<label>First Name:</label>
<input type="text" name="first_name" value="<?php echo htmlspecialchars($row['first_name']); ?>" required><br>

<label>Last Name:</label>
<input type="text" name="last_name" value="<?php echo htmlspecialchars($row['last_name']); ?>" required><br>

<label>Address:</label>
<textarea name="address" required><?php echo htmlspecialchars($row['address']); ?></textarea><br>

<label>Gender:</label>
<select name="gender" required>
<option value="">Select Gender</option>
<option value="Male" <?php if ($row['gender'] == 'Male') echo 'selected'; ?>>Male</option>
<option value="Female" <?php if ($row['gender'] == 'Female') echo 'selected'; ?>>Female</option>
<option value="Other" <?php if ($row['gender'] == 'Other') echo 'selected'; ?>>Other</option>
</select><br>

<label>Birthdate:</label>
<input type="date" name="user_bday" value="<?php echo $row['user_bday']; ?>" required><br>

<label>Contact Number:</label>
<input type="text" name="contact_number" value="<?php echo htmlspecialchars($row['contact_number']); ?>" required><br>

<label>Status:</label>
<select name="status" required>
<option value="">Select Status</option>
<option value="Single" <?php if ($row['status'] == 'Single') echo 'selected'; ?>>Single</option>
<option value="Married" <?php if ($row['status'] == 'Married') echo 'selected'; ?>>Married</option>
<option value="Widowed" <?php if ($row['status'] == 'Widowed') echo 'selected'; ?>>Widowed</option>
<option value="Separated" <?php if ($row['status'] == 'Separated') echo 'selected'; ?>>Separated</option>
<option value="Annulled" <?php if ($row['status'] == 'Annulled') echo 'selected'; ?>>Annulled</option>
</select><br>

<label>Occupation:</label>
<select name="occupation" required>
<option value="">Select Occupation</option>
<option value="Student" <?php if ($row['occupation'] == 'Student') echo 'selected'; ?>>Student</option>
<option value="Working_Student" <?php if ($row['occupation'] == 'Working_Student') echo 'selected'; ?>>Working Student</option>
<option value="Working_Employed" <?php if ($row['occupation'] == 'Working_Employed') echo 'selected'; ?>>Working/Employed</option>
<option value="Out_of_School_Youth" <?php if ($row['occupation'] == 'Out_of_School_Youth') echo 'selected'; ?>>Out of School Youth</option>
<option value="Unemployed" <?php if ($row['occupation'] == 'Unemployed') echo 'selected'; ?>>Unemployed</option>
<option value="Self_Employed" <?php if ($row['occupation'] == 'Self_Employed') echo 'selected'; ?>>Self-Employed</option>
</select><br>

<label>Sports:</label>
<input type="text" name="sports" value="<?php echo htmlspecialchars($row['sports']); ?>" required><br>

<label>Precinct Number:</label>
<input type="text" name="precinct_number" value="<?php echo htmlspecialchars($row['precinct_number']); ?>"><br>

<button type="submit">Update</button>
</form>
</body>
</html>
