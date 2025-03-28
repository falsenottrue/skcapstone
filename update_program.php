<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['login_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_program'])) {
    $program_name = $_POST['program_name'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $sql = "INSERT INTO programs (program_name, description, status, start_date, end_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $program_name, $description, $status, $start_date, $end_date);

    if ($stmt->execute()) {
        echo "<script>alert('Program added successfully!'); window.location.href='update_program.php';</script>";
    } else {
        echo "<script>alert('Error adding program.');</script>";
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_program'])) {
    $program_id = $_POST['program_id'];
    $program_name = $_POST['program_name'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $sql = "UPDATE programs SET program_name=?, description=?, status=?, start_date=?, end_date=? WHERE program_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $program_name, $description, $status, $start_date, $end_date, $program_id);

    if ($stmt->execute()) {
        echo "<script>alert('Program updated successfully!'); window.location.href='update_program.php';</script>";
    } else {
        echo "<script>alert('Error updating program.');</script>";
    }
    $stmt->close();
}

if (isset($_GET['delete'])) {
    $program_id = $_GET['delete'];

    $sql = "DELETE FROM programs WHERE program_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $program_id);

    if ($stmt->execute()) {
        echo "<script>alert('Program deleted successfully!'); window.location.href='update_program.php';</script>";
    } else {
        echo "<script>alert('Error deleting program.');</script>";
    }
    $stmt->close();
}

$result = $conn->query("SELECT * FROM programs");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Programs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/form-dark.css">
    <link rel="icon" type="image/png" href="img/sklogo.png">
</head>
<body class="container mt-4">

    <h2 class="text-center">Manage Programs</h2>

    <form method="POST" class="mb-4">
        <h4>Add a New Program</h4>
        <div class="mb-3">
            <label class="form-label">Program Name:</label>
            <input type="text" name="program_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description:</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Status:</label>
            <select name="status" class="form-control">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Start Date:</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">End Date:</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>

        <button type="submit" name="add_program" class="btn btn-success w-100">Add Program</button>
    </form>

    <hr>

    <h4>Existing Programs</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Program Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <input type="hidden" name="program_id" value="<?= $row['program_id'] ?>">
                    <td><input type="text" name="program_name" class="form-control" value="<?= $row['program_name'] ?>" required></td>
                    <td><textarea name="description" class="form-control" required><?= $row['description'] ?></textarea></td>
                    <td>
                        <select name="status" class="form-control">
                            <option value="Active" <?= $row['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                            <option value="Inactive" <?= $row['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </td>
                    <td><input type="date" name="start_date" class="form-control" value="<?= $row['start_date'] ?>" required></td>
                    <td><input type="date" name="end_date" class="form-control" value="<?= $row['end_date'] ?>" required></td>
                    <td>
                        <button type="submit" name="update_program" class="btn btn-primary btn-sm">Update</button>
                        <a href="update_program.php?delete=<?= $row['program_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <hr>

    <a href="update_registration_status.php"><button type="submit" name="update_registration" class="btn btn-success w-100">Update Registrations</button></a>
    <hr>
    <a href="admin_index.php"> <button class="btn btn-danger w-100"> Back </button> </a>
</body>
</html>

<?php $conn->close(); ?>
