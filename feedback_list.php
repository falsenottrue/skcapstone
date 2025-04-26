<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
include 'connection.php';
include 'session_timeout.php';
session_start();

if (!isset($_SESSION['login_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied! Admins only.'); window.location.href='index.php';</script>";
    exit();
}

$result = $conn->query("
    SELECT feedback.feedback_id, users.first_name, users.last_name, users.gender, users.occupation, feedback.feedback_type, feedback.message, feedback.date_submitted
    FROM feedback
    JOIN users ON feedback.user_id = users.user_id
    ORDER BY feedback.date_submitted DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/form-dark.css">
    <link rel="icon" type="image/png" href="img/sklogo.png">
</head>
<body class="container mt-4">
    <h2 class="text-center">Submitted Feedback</h2>
    
    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Gender</th>
                <th>Occupation</th>
                <th>Feedback Type</th>
                <th>Message</th>
                <th>Date Submitted</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                    <td><?= htmlspecialchars($row['gender']) ?></td>
                    <td><?= htmlspecialchars($row['occupation']) ?></td>
                    <td><?= htmlspecialchars($row['feedback_type']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                    <td><?= $row['date_submitted'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <hr>
    <a href="admin_dashboard.php"> <button class="btn btn-danger w-100"> Back </button> </a>
</body>
</html>

<?php $conn->close(); ?>
