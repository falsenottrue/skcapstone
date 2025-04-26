<?php
session_start();
include 'session_timeout.php';
include 'connection.php';  // gives you $conn (MySQLi)

if (!isset($_SESSION['login_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Fetch all entries
$result = $conn->query("SELECT * FROM announ ORDER BY created_at ASC");
$entries = [];
while ($row = $result->fetch_assoc()) {
    $entries[] = $row;
}
$total = count($entries);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style/style2.css">
  <link rel="icon" type="image/png" href="img/sklogo.png">
  <title>Event Announcement</title>
  <style>
    .slider { position: relative; max-width: 600px; margin: 40px auto; }
    .slide { display: none; text-align: center; }
    .slide img { max-width: 100%; height: auto; border-radius: 8px; }
    .controls { margin-top: 10px; text-align: center; }
    .controls button { padding: 8px 16px; margin: 0 5px; }
    .message { margin-top: 10px; font-size: 1rem; }
    .doc-frame { margin-top: 10px; width: 100%; height: 400px; border: 1px solid #ccc; }
  </style>
</head>
<body>
  <div class="nav">
    <div class="logo">
      <a href="Community_Events.php">
        <img src="img/sklogo.png" alt="Logo" class="logo">
      </a>
      <p><strong>Event Announcement</strong></p>
    </div>
    <div class="right-links">
      <a href="dashboard.php">Back</a>
      <a href="logout.php"><button class="btn">Logout</button></a>
    </div>
  </div>

  <div class="main-box">
    <div class="slider" id="slider">
      <?php if ($total === 0): ?>
        <p>No entries found.</p>
      <?php else: ?>
        <?php foreach ($entries as $i => $e): ?>
          <div class="slide" data-index="<?= $i ?>">
            <?php if (!empty($e['link'])): ?>
              <a href="<?= htmlspecialchars($e['link']) ?>" target="_blank">
                <img src="<?= htmlspecialchars($e['image_path']) ?>" alt="Entry #<?= $i + 1 ?>">
              </a>
            <?php else: ?>
              <img src="<?= htmlspecialchars($e['image_path']) ?>" alt="Entry #<?= $i + 1 ?>">
            <?php endif; ?>

            <?php if (!empty($e['message'])): ?>
              <div class="message"><?= nl2br(htmlspecialchars($e['message'])) ?></div>
            <?php endif; ?>

            <?php if (!empty($e['doc_path'])): ?>
              <?php
                $ext = pathinfo($e['doc_path'], PATHINFO_EXTENSION);
                if (strtolower($ext) === 'pdf'): 
              ?>
                <iframe src="<?= htmlspecialchars($e['doc_path']) ?>" class="doc-frame"></iframe>
              <?php else: ?>
                <p><a href="<?= htmlspecialchars($e['doc_path']) ?>" target="_blank">
                  Download document (<?= strtoupper($ext) ?>)
                </a></p>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>

        <div class="controls">
          <button id="prevBtn">Prev</button>
          <button id="nextBtn">Next</button>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Session Timeout Modal (unchanged) -->
  <div id="sessionModal" class="modal">
    <div class="modal-content">
      <h3>You're inactive</h3>
      <p>Your session is about to expire. Do you want to stay logged in?</p>
      <div class="modal-buttons">
        <button onclick="extendSession()">Yes, Stay Logged In</button>
        <button onclick="logout()">No, Log Me Out</button>
      </div>
    </div>
  </div>

  <script>
    // Slider logic
    (function() {
      const slides = document.querySelectorAll('.slide');
      let current = 0;
      function show(idx) {
        slides.forEach(s => s.style.display = 'none');
        slides[idx].style.display = 'block';
      }
      if (slides.length > 0) {
        show(0);
        document.getElementById('nextBtn').onclick = () => {
          current = (current + 1) % slides.length;
          show(current);
        };
        document.getElementById('prevBtn').onclick = () => {
          current = (current - 1 + slides.length) % slides.length;
          show(current);
        };
      }
    })();

    // Session timeout logic (unchanged)
    let idleTime = 0;
    const maxIdleTime = 12 * 60, logoutTime = 15 * 60;
    function resetTimer() { idleTime = 0; }
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.onscroll  = resetTimer;
    document.onclick   = resetTimer;
    setInterval(() => {
      idleTime++;
      if (idleTime === maxIdleTime) {
        document.getElementById("sessionModal").style.display = "block";
      }
      if (idleTime >= logoutTime) logout();
    }, 1000);
    function extendSession() { fetch("keep_alive.php"); idleTime = 0; document.getElementById("sessionModal").style.display = "none"; }
    function logout() { window.location.href = "logout.php"; }
  </script>
</body>
</html>