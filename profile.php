<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();
include 'connection.php';
include 'session_timeout.php';

if (!isset($_SESSION['login_id'])) {
    echo "<script>alert('Please login to access your profile.'); window.location.href='login.php';</script>";
    exit();
}

$login_id = $_SESSION['login_id'];

// Get login & user info
$query = "
    SELECT l.usernm, l.email, u.*
    FROM login l
    JOIN users u ON l.login_id = u.user_id
    WHERE l.login_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $login_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get guardian info (if under 18)
$guardian = null;
$age = date_diff(date_create($user['birth_date']), date_create('today'))->y;

if ($age < 18) {
    $g_stmt = $conn->prepare("SELECT * FROM guardian_info WHERE user_id = ?");
    $g_stmt->bind_param("i", $login_id);
    $g_stmt->execute();
    $g_result = $g_stmt->get_result();
    $guardian = $g_result->fetch_assoc();
}

// Get registered programs
$prog_stmt = $conn->prepare("
    SELECT p.program_name, p.description, r.created_at
    FROM program_registrations r
    JOIN programs p ON r.program_id = p.program_id
    WHERE r.login_id = ?
");
$prog_stmt->bind_param("i", $login_id);
$prog_stmt->execute();
$programs = $prog_stmt->get_result();
?>

<head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link rel="icon" type="image/png" href="img/sklogo.png"/>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/fontawesome.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    />
    <title>Profile</title>
    <link rel="stylesheet" href="css/simplebar.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Overpass:wght@100;400;600;900&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@100;400;600;900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/feather.css" />
    <link rel="stylesheet" href="css/main.css" /> <!-- wag mo'to tanggalin or palitan  add ka nalang ng css file -->

    <style>
     
      .sidebar-left {
        background-color: #191d67; /* Set the sidebar color in light mode */
      }
       body .circle-icon{
       background-color: #191d67;
       color: white;
       box-shadow: 2px 0 10px rgba(0, 0, 0, 0.5);
       }
      .avatar-img:hover .upload-icon {
        opacity: 1;
      }

      .avatar-img {
        position: relative;
        transition: background-color 0.3s ease-in-out;
      }

      .avatar-img:hover {
        background-color: #a0f0e6;
      }

      /* Dark Mode Styles */
      body.dark {
        background-color: #0e0e0e; /* Dark background */
        color: #ffffff; /* Light text color */
      }

      body.dark .card {
        background-color: #1e1e1e;
        color: #f1f1f1;
        border-color: #333;
      }

      /* Card headers in dark mode */
      body.dark .card-header {
        background-color: #2c2c2c !important;
        color: #ffffff !important;
      }

      /* List group items (like the registered programs list) */
      body.dark .list-group-item {
        background-color: #2a2a2a;
        color: #dddddd;
        border-color: #444;
      }
      .navbar-light.dark {
        background-color: #1f1f1f; /* Dark navbar background */
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.5);
      }

      body.dark .circle-icon {
        background-color: #1f1f1f; /* Dark background for circle icon */
        color: white; /* White text for better visibility */
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.5);
      }

      .sidebar-left.dark {
        background-color: #1f1f1f; /* Dark sidebar background */
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.5);
      }

      /* Ensure nav links are white in dark mode */
      body.dark .nav-link {
        color: #ffffff !important; /* White text for nav links */
      }

      /* Additional styles for dropdown items */
      body.dark .dropdown-item {
        color: black !important; /* White text for dropdown items */
      }

      /* Change background on hover for dropdown items */
      body.dark .dropdown-item:hover {
        color: #ffffff; /* Keep text white on hover */
      }

      /* Light mode styles for dropdown items */
      .dropdown-item {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem; /* Padding for dropdown items */
        color: #333; /* Default text color in light mode */
        text-decoration: none; /* Remove underline */
      }

      /* Change color on hover */
      .dropdown-item:hover {
        background-color: #f8f9fa; /* Light background on hover */
        color: #007bff; /* Change text color on hover */
      }

      /* Style for the log out link */
      .dropdown-log-out {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem; /* Padding for log out item */
        color: #dc3545; /* Red color for log out */
        text-decoration: none; /* Remove underline */
      }
      .text-muted {
        color:rgb(255, 255, 255)
      }
      /* Change color on hover for log out */
      .dropdown-log-out:hover {
        background-color: #f8d7da; /* Light red background on hover */
        color: #c82333; /* Darker red text on hover */
      }

      /* Optional: Add a transition effect */
      .dropdown-item,
      .dropdown-log-out {
        transition: background-color 0.2s ease, color 0.2s ease;
      }
    </style>
  </head>
<body class="vertical light">
<div class="wrapper">
      <nav class="topnav navbar navbar-light">
        <button
          type="button"
          class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar"
        >
          <i class="fe fe-menu navbar-toggler-icon"></i>
        </button>

        <ul class="nav">
          <!-- <li class="nav-item">
            <section
              class="nav-link text-muted my-2 circle-icon"
              href="#"
              data-toggle="modal"
              data-target=".modal-shortcut"
            >
              <span class="fe fe-message-circle fe-16"></span>
            </section>
          </li> -->
          <li class="nav-item nav-notif">
            <section
              class="nav-link text-muted my-2 circle-icon"
              href="#"
              data-toggle="modal"
              data-target=".modal-notif"
            >
              <span class="fe fe-bell fe-16"></span>
              <span
                id="notification-count"
                style="
                  position: absolute;
                  top: 12px;
                  right: 5px;
                  font-size: 13px;
                  color: white;
                  background-color: red;
                  width: 8px;
                  height: 8px;
                  display: flex;
                  justify-content: center;
                  align-items: center;
                  border-radius: 50%;
                "
              >
              </span>
            </section>
          </li>
          <li class="nav-item nav-darkmode">
            <section
              class="nav-link text-muted my-2 circle-icon"
              id="darkModeToggle"
            >
              <span class="fe fe-moon fe-16"></span>
            </section>
          </li>
          <li class="nav-item dropdown">
            <span
              class="nav-link text-muted pr-0 profile-icon"
              href="#"
              id="navbarDropdownMenuLink"
              role="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="fe fe-user circle-icon"></i>
              <!-- Profile icon -->
            </span>
            <div
              class="dropdown-menu dropdown-menu-right"
              aria-labelledby="navbarDropdownMenuLink"
            >
              <a class="dropdown-item" href="profile.php"
                ><i class="fe fe-user"></i>&nbsp;&nbsp;&nbsp;Profile</a
              >
              <a class="dropdown-log-out" href="logout.php"
                ><i class="fe fe-log-out"></i>&nbsp;&nbsp;&nbsp;Log Out</a
              >
            </div>
          </li>
        </ul>
      </nav>

      <aside
        class="sidebar-left border-right bg-white"
        id="leftSidebar"
        data-simplebar
      >
        <a
          href="#"
          class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3"
          data-toggle="toggle"
        >
          <i class="fe fe-x"><span class="sr-only"></span></i>
        </a>
        <nav class="vertnav navbar-side navbar-light">
          <div class="w-100 mb-4 d-flex">
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="#">
              <i class="fas fa-landmark fa-3x"></i>
              <div class="brand-title">
                <br />
                <span>SK NAGKAISANG NAYON</span>
              </div>
            </a>
          </div>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item dropdown">
              <a class="nav-link" href="dashboard.php">
                <i class="fas fa-chart-line"></i>
                <span class="ml-3 item-text"> Dashboard</span>
              </a>
            </li>
          </ul>
          <p class="text-muted-nav nav-heading mt-4 mb-1">
            <span
              style="
                font-size: 10.5px;
                font-weight: bold;
                font-family: 'Inter', sans-serif;
              "
              >MAIN COMPONENTS</span
            >
          </p>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="community_events.php">
                <i class="fas fa-landmark fa"></i>
                <span class="ml-3 item-text">Community Events</span>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="event_announcement.php">
                <i class="fas fa-landmark fa"></i>
                <span class="ml-3 item-text">Event Announcement</span>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="update_form.php">
                <i class="fas fa-landmark fa"></i>
                <span class="ml-3 item-text">Update Information</span>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="register_program.php">
                <i class="fas fa-landmark fa"></i>
                <span class="ml-3 item-text">Program Registration</span>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="feedback.php">
                <i class="fas fa-landmark fa"></i>
                <span class="ml-3 item-text">Submit Feedback</span>
              </a>
            </li>
          </ul>
          <p class="text-muted-nav nav-heading mt-4 mb-1">
            <span
              style="
                font-size: 10.5px;
                font-weight: bold;
                font-family: 'Inter', sans-serif;
              "
              >OTHER COMPONENTS</span
            >
          </p>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="#">
                <i class="fas fa-landmark fa"></i>
                <span class="ml-3 item-text">Module 6</span>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="#">
                <i class="fas fa-landmark fa"></i>
                <span class="ml-3 item-text">Module 7</span>
              </a>
            </li>
          </ul>
        </nav>
      </aside>

      <main role="main" class="main-content">
        <!-- For Notification header -->
        <div
          class="modal fade modal-notif modal-slide"
          tabindex="-1"
          role="dialog"
          aria-labelledby="defaultModalLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="defaultModalLabel">
                  Notifications
                </h5>
                <button
                  type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-label="Close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="list-group list-group-flush my-n3">
                  <div class="col-12 mb-4">
                    <div
                      class="alert alert-success alert-dismissible fade show"
                      role="alert"
                      id="notification"
                    >
                    <i class="fas fa-landmark fa" style="font-size: 35px;"></i>
                    <strong
                      style="
                        font-size: 12px;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                      "
                    ></strong>
                      <button
                        type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close"
                        onclick="removeNotification()"
                      >
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                  </div>
                  <div
                    id="no-notifications"
                    style="display: none; text-align: center; margin-top: 10px"
                  >
                    No notifications
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary btn-block"
                  onclick="clearAllNotifications()"
                >
                  Clear All
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="container-fluid my-4">

  <div class="row">
    <!-- Profile Card -->
    <div class="col-lg-4 mb-4">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">User Profile</h5>
        </div>
        <div class="card-body">
        <p><strong>Username:</strong> <?= htmlspecialchars($user['usernm']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        </div>
      </div>
    </div>

    <!-- User Info -->
    <div class="col-lg-4 mb-4">
      <div class="card shadow-sm">  
        <div class="card-header bg-info text-white">
          <h5 class="mb-0">User Information</h5>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
            <p><strong>Gender:</strong> <?= $user['gender'] ?></p>
            <p><strong>Birth Date:</strong> <?= $user['birth_date'] ?> (Age: <?= $age ?>)</p>
            <p><strong>Contact:</strong> <?= $user['contact_number'] ?></p>
            <p><strong>Address:</strong> <?= $user['address'] ?></p>
            <p><strong>Status:</strong> <?= $user['status'] ?></p>
            <p><strong>Occupation:</strong> <?= $user['occupation'] ?></p>
        </div>
      </div>
    </div>

        <?php if ($age < 18 && isset($guardian)): ?>
      <!-- Guardian Info -->
      <div class="col-lg-4 mb-4">
        <div class="card shadow-sm">
          <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Guardian Information</h5>
          </div>
          <div class="card-body">
            <p><strong>Name:</strong> <?= htmlspecialchars($guardian['guardian_name']) ?></p>
            <p><strong>Contact:</strong> <?= htmlspecialchars($guardian['guardian_contact']) ?></p>
            <p><strong>Relationship:</strong> <?= htmlspecialchars($guardian['relationship']) ?></p>
          </div>
        </div>
      </div>
      <?php else: ?>
        <div class="col-lg-4 mb-4">
          <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
              <h5 class="mb-0">Guardian Information</h5>
            </div>
            <div class="card-body">
              <p>This user is 18 or older. No guardian information is required.</p>
            </div>
          </div>
        </div>
      <?php endif; ?>


  <!-- Registered Programs -->
    <div class="col-lg-4 mb-4">
      <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
          <h5 class="mb-0">Registered Programs</h5>
        </div>
        <div class="card-body">
        <?php if ($programs->num_rows > 0): ?>
                <ul class="list-group">
                    <?php while ($program = $programs->fetch_assoc()): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($program['program_name']) ?></strong><br>
                            <?= htmlspecialchars($program['description']) ?><br>
                            <small class="text-muted">Registered on: <?= $program['created_at'] ?></small>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">No program registrations yet.</p>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>


    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/simplebar.min.js"></script>
    <script src="js/daterangepicker.js"></script>
    <script src="js/jquery.stickOnScroll.js"></script>
    <script src="js/tinycolor-min.js"></script>
    <script src="js/d3.min.js"></script>
    <script src="js/topojson.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/gauge.min.js"></script>
    <script src="js/jquery.sparkline.min.js"></script>
    <script src="js/apexcharts.min.js"></script>
    <script src="js/apexcharts.custom.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/jquery.steps.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/jquery.timepicker.js"></script>
    <script src="js/dropzone.min.js"></script>
    <script src="js/uppy.min.js"></script>
    <script src="js/quill.min.js"></script>
    <script src="js/apps.js"></script>
    <script src="js/preloader.js"></script>
    <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>

    <script>
  // Apply saved dark mode on page load
  window.addEventListener("DOMContentLoaded", function () {
    if (localStorage.getItem("darkMode") === "enabled") {
      document.body.classList.add("dark");
      document.querySelector(".navbar-light").classList.add("dark");
      document.querySelector(".sidebar-left").classList.add("dark");
    }
  });

  // Toggle dark mode and save preference
  document
    .getElementById("darkModeToggle")
    .addEventListener("click", function () {
      document.body.classList.toggle("dark");
      document.querySelector(".navbar-light").classList.toggle("dark");
      document.querySelector(".sidebar-left").classList.toggle("dark");

      const isDarkMode = document.body.classList.contains("dark");
      localStorage.setItem("darkMode", isDarkMode ? "enabled" : "disabled");
    });
    let idleTime = 0;
    const maxIdleTime = 12 * 60; // 12 minutes idle before showing warning
    const logoutTime = 15 * 60; // 15 minutes total timeout

    // Reset idle timer on activity
    function resetTimer() {
      idleTime = 0;
    }
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.onscroll = resetTimer;
    document.onclick = resetTimer;

    setInterval(() => {
      idleTime++;

      // Show modal at 12 minutes idle
      if (idleTime === maxIdleTime) {
        document.getElementById("sessionModal").style.display = "block";
      }

      // Auto-logout at 15 minutes
      if (idleTime >= logoutTime) {
        logout();
      }
    }, 1000); // check every second

    function extendSession() {
      fetch("keep_alive.php"); // Pings the server
      idleTime = 0;
      document.getElementById("sessionModal").style.display = "none";
    }

    function logout() {
      window.location.href = "logout.php";
    }
</script>
</body>
</html>
