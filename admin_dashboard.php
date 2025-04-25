<?php
include 'connection.php';
session_start(); //access control
if (!isset($_SESSION['login_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='dashboard.php';</script>";
    exit();
}

$sql = "SELECT 
    user_id,
    first_name,
    last_name,
    gender,
    birth_date,
    TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) AS age,
    contact_number,
    address,
    status,
    occupation,
    created_at
FROM users";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style2.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="img/sklogo.png">
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
             <p><a href="admin_dashboard.php"><img src="img/sklogo.png" alt="Logo" class="logo"></a></p>  
        </div>

        <div class="right-links">
            <a href="Community_Events.php"> Back </a>
            <a href="logout.php"> <button class="btn btn-primary w-100"> Logout </button> </a>

        </div>
    </div>
    <main>

        <div class="main-box">
           <div class="top">
                <div class="box">
                    <h1><p><img src="img/Sk.Basketball.png" class="Logo" width="70px" alt="Logo" class="logo"></p></h1>
                    <a href='update_program.php'>
                    <h2><strong>Update Program</strong></h2>
                    </a>
                    <h1><p><img src="img/Sk.Basketball.png" class="Logo" width="70px" alt="Logo" class="logo"></p></h1>
                    <a href='feedback_list.php'>
                    <h2><strong>Feedback List</strong></h2>
                    </a>
                </div>
           </div> 
        </div>

        <div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Registered Users</h4>
        </div>
        <div class="card-body">
            <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Birth Date</th>
                                <th>Age</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Occupation</th>
                                <th>Registered On</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                <td><?= htmlspecialchars($row['gender']) ?></td>
                                <td><?= htmlspecialchars($row['birth_date']) ?></td>
                                <td><?= htmlspecialchars($row['age']) ?></td>
                                <td><?= htmlspecialchars($row['contact_number']) ?></td>
                                <td><?= htmlspecialchars($row['address']) ?></td>
                                <td><?= htmlspecialchars($row['status']) ?></td>
                                <td><?= htmlspecialchars($row['occupation']) ?></td>
                                <td><?= htmlspecialchars(date('F j, Y', strtotime($row['created_at']))) ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No users found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
    </main>
</body>
<?php $conn->close(); ?>
</html>