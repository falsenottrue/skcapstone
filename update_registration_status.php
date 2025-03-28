<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access Denied!'); window.location.href='index.php';</script>";
    exit();
}

$sql = "SELECT pr.registration_id, pr.login_id, pr.status, l.usernm, p.program_name, pr.created_at 
        FROM program_registrations pr
        JOIN login l ON pr.login_id = l.login_id
        JOIN programs p ON pr.program_id = p.program_id
        ORDER BY pr.registration_id DESC";  // Sort newest to oldest

$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_all'])) {
    $conn->begin_transaction();
    $error = false;

    foreach ($_POST['registration_status'] as $registration_id => $new_status) {
        $update_sql = "UPDATE program_registrations SET status = ? WHERE registration_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $new_status, $registration_id);

        if (!$stmt->execute()) {
            $error = true;
            break;
        }
    }

    if ($error) {
        $conn->rollback();
        echo "<script>alert('Error updating status. Please try again.');</script>";
    } else {
        $conn->commit();
        echo "<script>alert('Statuses updated successfully!'); window.location.href='update_registration_status.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Registrant Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/form-dark.css">
</head>
<body class="container mt-4">
    <h2 class="text-center">Update Registrant Status</h2>
    <form action="update_registration_status.php" method="POST">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Program</th>
                    <th>Registration Date</th>
                    <th>Current Status</th>
                    <th>New Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['usernm']) ?></td>
                    <td><?= htmlspecialchars($row['program_name']) ?></td>
                    <td><?= date("M d, Y H:i", strtotime($row['created_at'])) ?></td> <!-- Format the date -->
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <select name="registration_status[<?= $row['registration_id'] ?>]" class="form-select">
                            <option value="Registered" <?= $row['status'] == 'Registered' ? 'selected' : '' ?>>Registered</option>
                            <option value="Completed" <?= $row['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="Cancelled" <?= $row['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <button type="submit" name="update_all" class="btn btn-primary mt-3">Update All</button>
        <hr>
    </form>
    <a href="update_program.php"> <button class="btn btn-danger mt-3"> Back </button> </a>
</body>
</html>
