<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
include 'connection.php';
session_start();

if (!isset($_SESSION['login_id'])) {
    echo "<script>alert('You must be logged in to register for a program.'); window.location.href='dashboard.php';</script>";
    exit();
}

$sql = "SELECT program_id, program_name FROM programs";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register for a Program</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/form-dark.css">
    <link rel="icon" type="image/png" href="img/sklogo.png">
</head>
<body class="container mt-4">
    <form action="dashboard.php" method="POST" class="mt-3">
    <h2 class="text-center">Program Registration</h2>

    <?php if ($result->num_rows > 0) { ?>
        <form action="register_program_action.php" method="POST">
            <div class="mb-3">
                <label for="program_id" class="form-label">Select Program:</label>
                <select name="program_id" id="program_id" class="form-control" required>
                    <option value="">Choose a program</option>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['program_id']}'>{$row['program_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
    <?php } else { ?>
        <p class="text-danger text-center">No programs available.</p>
    <?php } ?>
    <hr>
    <a href="dashboard.php"> <button class="btn btn-danger w-100"> Back </button> </a>

</body>
</html>
