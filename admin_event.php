<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
include 'connection.php';
include 'session_timeout.php';
session_start();

if (!isset($_SESSION['login_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied! Admins only.'); window.location.href='dashboard.php';</script>";
    exit();
}

date_default_timezone_set('Asia/Manila');
$entry_saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    // Handle required image upload
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        die('Error: Image upload is required.');
    }
    $imgDir = _DIR_ . '/uploads/images/';
    if (!is_dir($imgDir)) mkdir($imgDir, 0755, true);
    $imgFile = uniqid() . '_' . basename($_FILES['image']['name']);
    $imgPath = $imgDir . $imgFile;
    move_uploaded_file($_FILES['image']['tmp_name'], $imgPath);
    $imgRelPath = 'uploads/images/' . $imgFile;

    // Optional link
    $link = !empty($_POST['link']) ? $conn->real_escape_string($_POST['link']) : null;

    // Optional document/PDF/text
    $docRelPath = null;
    if (!empty($_FILES['document']['name'])) {
        $docDir = _DIR_ . '/uploads/docs/';
        if (!is_dir($docDir)) mkdir($docDir, 0755, true);
        $docFile = uniqid() . '_' . basename($_FILES['document']['name']);
        $docPath = $docDir . $docFile;
        move_uploaded_file($_FILES['document']['tmp_name'], $docPath);
        $docRelPath = 'uploads/docs/' . $docFile;
    }

    // Optional message
    $message = trim($_POST['message'] ?? '');
    $message = !empty($message) ? $conn->real_escape_string($message) : null;

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO announ (image_path, link, doc_path, message, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $imgRelPath, $link, $docRelPath, $message, $created_at, $updated_at);

    if ($stmt->execute()) {
        $entry_saved = true;
    } else {
        echo "<p>❌ Error: " . htmlspecialchars($stmt->error) . "</p>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Event Data Form</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="icon" type="image/png" href="img/sklogo.png"/>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
    }
    .container {
      max-width: 600px;
      margin: 40px auto;
      padding: 20px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    h1 {
      text-align: center;
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: 600;
    }
    input[type="file"],
    input[type="url"],
    textarea {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      display: block;
      width: 100%;
      padding: 10px;
      margin-top: 20px;
      background: #007bff;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
    }
    button:hover {
      background: #0056b3;
    }
    #successModal {
      display: none;
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.5);
      z-index: 9999;
      justify-content: center;
      align-items: center;
    }
    #successModal .modal-content {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      text-align: center;
      max-width: 300px;
      margin: auto;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Submit Your Data</h1>
    <form id="entryForm" method="post" enctype="multipart/form-data">
      <label>Image Upload (required)</label>
      <input type="file" name="image" accept="image/*" required>

      <label>Link (optional)</label>
      <input type="url" name="link" placeholder="https://example.com">

      <label>Document/PDF/Text (optional)</label>
      <input type="file" name="document" accept=".pdf,.doc,.docx,.txt">

      <label>Long Message (optional)</label>
      <textarea name="message" rows="6"></textarea>

      <button type="submit">Submit</button>
    </form>
    <a href="admin_dashboard.php"><button class="btn btn-danger w-100">Back</button></a>
  </div>

  <!-- Success Modal -->
  <div id="successModal">
    <div class="modal-content">
      <h3>✅ Success!</h3>
      <p>Your entry has been saved.</p>
      <button onclick="document.getElementById('successModal').style.display='none'">Close</button>
    </div>
  </div>

  <script>
    <?php if ($entry_saved): ?>
      window.addEventListener('DOMContentLoaded', () => {
        document.getElementById('successModal').style.display = 'flex';
      });
    <?php endif; ?>
  </script>
</body>
</html>