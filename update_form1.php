<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['login_id'])) {
    echo "<script>alert('You must be logged in to update your information.'); window.location.href='dashboard.php';</script>";
    exit();
}

$login_id = $_SESSION['login_id'];

$sql1 = "SELECT * FROM users WHERE user_id = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $login_id);
$stmt1->execute();
$user_result = $stmt1->get_result();
$user_data = $user_result->fetch_assoc();
$stmt1->close();

$sql2 = "SELECT * FROM guardian_info WHERE user_id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $login_id);
$stmt2->execute();
$guardian_result = $stmt2->get_result();
$guardian_data = $guardian_result->fetch_assoc();
$stmt2->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birth_date = $_POST['birth_date'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $occupation = $_POST['occupation'];

    $birth_date = date('Y-m-d', strtotime($_POST['birth_date']));


    $guardian_name = $_POST['guardian_name'];
    $guardian_contact = $_POST['guardian_contact'];
    $relationship = $_POST['relationship'];

    $conn->begin_transaction();
    try {
        $sql1 = "UPDATE users SET first_name = ?, last_name = ?, birth_date = ?, contact_number = ?, address = ?, status = ?, occupation = ? WHERE user_id = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("sssssssi", $first_name, $last_name, $birth_date, $contact_number, $address, $status, $occupation, $login_id);
        $stmt1->execute();
        $stmt1->close();

        if ($guardian_data) {
            $sql2 = "UPDATE guardian_info SET guardian_name = ?, guardian_contact = ?, relationship = ? WHERE user_id = ?";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("sssi", $guardian_name, $guardian_contact, $relationship, $login_id);
            $stmt2->execute();
            $stmt2->close();
        }
        

        $conn->commit();
        echo "<script>alert('Information updated successfully.'); window.location.href = 'dashboard.php';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error updating records: " . $e->getMessage();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/form-dark.css">
    <link rel="icon" type="image/png" href="img/sklogo.png">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Update Information</h2>
        <form method="POST" action="">
            <h4>User Information</h4>
            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($user_data['first_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($user_data['last_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Birthday</label>
                <input type="date" class="form-control" name="birth_date" value="<?= $user_data['birth_date']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" class="form-control" name="contact_number" value="<?= htmlspecialchars($user_data['contact_number']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($user_data['address']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status" required>
                    <option value="Single" <?= (isset($user_data['status']) && $user_data['status'] == "Single") ? "selected" : ""; ?>>Single</option>
                    <option value="Married" <?= (isset($user_data['status']) && $user_data['status'] == "Married") ? "selected" : ""; ?>>Married</option>
                    <option value="Widowed" <?= (isset($user_data['status']) && $user_data['status'] == "Widowed") ? "selected" : ""; ?>>Widowed</option>
                    <option value="Separated" <?= (isset($user_data['status']) && $user_data['status'] == "Separated") ? "selected" : ""; ?>>Separated</option>
                    <option value="Annulled" <?= (isset($user_data['status']) && $user_data['status'] == "Annulled") ? "selected" : ""; ?>>Annulled</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Occupation</label>
                <select class="form-select" name="occupation" required>
                    <option value="Student" <?= (isset($user_data['occupation']) && $user_data['occupation'] == "Student") ? "selected" : ""; ?>>Student</option>
                    <option value="Working_Student" <?= (isset($user_data['occupation']) && $user_data['occupation'] == "Working_Student") ? "selected" : ""; ?>>Working Student</option>
                    <option value="Unemployed" <?= (isset($user_data['occupation']) && $user_data['occupation'] == "Unemployed") ? "selected" : ""; ?>>Unemployed</option>
                </select>
            </div>
            <?php if ($guardian_data): ?>
            <h4>Guardian Information</h4>
            <div class="mb-3">
                <label class="form-label">Guardian's Name</label>
                <input type="text" class="form-control" name="guardian_name" value="<?= htmlspecialchars($guardian_data['guardian_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Guardian's Contact Number</label>
                <input type="text" class="form-control" name="guardian_contact" value="<?= htmlspecialchars($guardian_data['guardian_contact']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Guardian Type</label>
                <select class="form-select" name="relationship" required>
                    <option value="Parent" <?= ($guardian_data['relationship'] == "Parent") ? "selected" : ""; ?>>Parent</option>
                    <option value="Relative" <?= ($guardian_data['relationship'] == "Relative") ? "selected" : ""; ?>>Relative</option>
                    <option value="Legal Guardian" <?= ($guardian_data['relationship'] == "Legal Guardian") ? "selected" : ""; ?>>Legal Guardian</option>
                </select>
            </div>
            <?php else: ?>
                <p class="text-muted"></p>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary w-100">Update</button>
        </form>
        <hr>
        <a href="dashboard.php"> <button class="btn btn-danger w-100"> Back </button> </a>
    </div>
</body>
</html>
