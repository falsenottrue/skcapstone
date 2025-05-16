<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
include 'connection.php';

session_start();
if (!isset($_SESSION['login_id'])) {
    echo "<script>alert('You must be logged in to submit this form.'); window.location.href='login.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['login_id'];

    $checkUserQuery = "SELECT user_id FROM users WHERE user_id = ?";
    $checkStmt = $conn->prepare($checkUserQuery);
    $checkStmt->bind_param("i", $user_id);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows == 0) {
        echo "<script>alert('Error: User does not exist.'); window.location.href='dashboard.php';</script>";
        exit();
    }
    $checkStmt->close();

    $youth_classification = $_POST['youth_classification'];
    $specific_needs = $_POST['specific_needs'] ?? 'None';
    $educational_background = $_POST['educational_background'];
    $register_sk_voter = isset($_POST['register_sk_voter']) ? 1 : 0;
    $vote_last_sk_election = isset($_POST['vote_last_sk_election']) ? 1 : 0;
    $registered_national_voter = isset($_POST['registered_national_voter']) ? 1 : 0;
    $attended_sk_assembly = isset($_POST['attended_sk_assembly']) ? 1 : 0;

    $sql = "INSERT INTO demographics (user_id, youth_classification, specific_needs, educational_background, register_sk_voter, vote_last_sk_election, registered_national_voter, attended_sk_assembly) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssiiii", $user_id, $youth_classification, $specific_needs, $educational_background, $register_sk_voter, $vote_last_sk_election, $registered_national_voter, $attended_sk_assembly);

    if ($stmt->execute()) {
        echo "<script>alert('Demographics data submitted successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        // Delete user if demographics failed
        $conn->query("DELETE FROM demographics WHERE user_id = $user_id");
        $conn->query("DELETE FROM guardian_info WHERE user_id = $user_id");
        $conn->query("DELETE FROM users WHERE user_id = $user_id");
        $conn->query("DELETE FROM login WHERE login_id = $user_id");
        session_destroy();
        echo "<script>alert('Error submitting demographics. Your account was deleted. Please register again.'); window.location.href='register.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demographics Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/form-dark.css">
    <link rel="icon" type="image/png" href="img/sklogo.png">
</head>
<body class="container mt-4">
    <h2 class="text-center">Demographics Form</h2>
    <form action="demographics.php" method="POST">
        <div class="mb-3">
            <label>Youth Classification:</label>
            <select name="youth_classification" class="form-control" required>
                <option value="">Select</option>
                <option value="In_School_Youth">In-School Youth</option>
                <option value="Out_of_School_Youth">Out-of-School Youth</option>
                <option value="Working_Youth">Working Youth</option>
                <option value="Youth_with_Specific_Needs">Youth with Specific Needs</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label>Specific Needs (If Applicable):</label>
            <select name="specific_needs" class="form-control">
                <option value="None">None</option>
                <option value="PWD">Person with Disability</option>
                <option value="Indigenous">Indigenous</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label>Educational Background:</label>
            <select name="educational_background" class="form-control" required>
                <option value="">Select</option>
                <option value="Elementary Level">Elementary Level</option>
                <option value="High School Level">High School Level</option>
                <option value="Senior High School Level">Senior High School Level</option>
                <option value="College Level">College Level</option>
            </select>
        </div>
        
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="register_sk_voter" value="1">
            <label class="form-check-label">Registered SK Voter</label>
        </div>
        
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="vote_last_sk_election" value="1">
            <label class="form-check-label">Voted in Last SK Election</label>
        </div>
        
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="registered_national_voter" value="1">
            <label class="form-check-label">Registered National Voter</label>
        </div>
        
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="attended_sk_assembly" value="1">
            <label class="form-check-label">Attended SK Assembly</label>
        </div>
        
        <button type="submit" class="btn btn-primary w-100 mt-3">Submit</button>
    </form>
</body>
</html>
