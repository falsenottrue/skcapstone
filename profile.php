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
    JOIN users u ON l.login_id = u.login_id
    WHERE l.login_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $login_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    // Handle no user found, maybe logout and redirect
    session_destroy();
    echo "<script>alert('User not found. Please login again.'); window.location.href='login.php';</script>";
    exit();
}

// Get guardian info (if under 18)
$guardian = null;
$age = date_diff(date_create($user['birth_date']), date_create('today'))->y;

if ($age < 18) {
    $g_stmt = $conn->prepare("SELECT * FROM guardian_info WHERE user_id = ?");
    $g_stmt->bind_param("i", $user['user_id']);
    $g_stmt->execute();
    $g_result = $g_stmt->get_result();
    $guardian = $g_result->fetch_assoc();
    $g_stmt->close();
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

$stmt->close();
$prog_stmt->close();
$conn->close();
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
              <?php if (isset($_SESSION['login_id'])): ?>
              <a class="dropdown-log-out" href="logout.php">
                <i class="fe fe-log-out"></i>&nbsp;&nbsp;&nbsp;Log Out
              </a>
            <?php else: ?>
              <a class="dropdown-item" href="login.php">
                <i class="fe fe-log-in"></i>&nbsp;&nbsp;&nbsp;Log In
              </a>
            <?php endif; ?>
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
              <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="M513.33-580v-260H840v260H513.33ZM120-446.67V-840h326.67v393.33H120ZM513.33-120v-393.33H840V-120H513.33ZM120-120v-260h326.67v260H120Zm66.67-393.33H380v-260H186.67v260ZM580-186.67h193.33v-260H580v260Zm0-460h193.33v-126.66H580v126.66Zm-393.33 460H380v-126.66H186.67v126.66ZM380-513.33Zm200-133.34Zm0 200ZM380-313.33Z"/></svg>
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
              <a class="nav-link" href="Community_Events.php">
              <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M0-240v-63q0-43 44-70t116-27q13 0 25 .5t23 2.5q-14 21-21 44t-7 48v65H0Zm240 0v-65q0-32 17.5-58.5T307-410q32-20 76.5-30t96.5-10q53 0 97.5 10t76.5 30q32 20 49 46.5t17 58.5v65H240Zm540 0v-65q0-26-6.5-49T754-397q11-2 22.5-2.5t23.5-.5q72 0 116 26.5t44 70.5v63H780Zm-455-80h311q-10-20-55.5-35T480-370q-55 0-100.5 15T325-320ZM160-440q-33 0-56.5-23.5T80-520q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T160-440Zm640 0q-33 0-56.5-23.5T720-520q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T800-440Zm-320-40q-50 0-85-35t-35-85q0-51 35-85.5t85-34.5q51 0 85.5 34.5T600-600q0 50-34.5 85T480-480Zm0-80q17 0 28.5-11.5T520-600q0-17-11.5-28.5T480-640q-17 0-28.5 11.5T440-600q0 17 11.5 28.5T480-560Zm1 240Zm-1-280Z"/></svg>
              <span class="ml-3 item-text">Community Events</span>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="Event_Announcement.php">
              <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M440-280h80v-240h-80v240Zm40-320q17 0 28.5-11.5T520-640q0-17-11.5-28.5T480-680q-17 0-28.5 11.5T440-640q0 17 11.5 28.5T480-600Zm0 520q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>
                <span class="ml-3 item-text">Event Announcement</span>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="update_form.php">
              <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg>
              <span class="ml-3 item-text">Update Information</span>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="register_program.php">
              <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M80-160v-112q0-33 17-62t47-44q51-26 115-44t141-18q30 0 58.5 3t55.5 9l-70 70q-11-2-21.5-2H400q-71 0-127.5 17T180-306q-9 5-14.5 14t-5.5 20v32h250l80 80H80Zm542 16L484-282l56-56 82 82 202-202 56 56-258 258ZM400-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm10 240Zm-10-320q33 0 56.5-23.5T480-640q0-33-23.5-56.5T400-720q-33 0-56.5 23.5T320-640q0 33 23.5 56.5T400-560Zm0-80Z"/></svg>
                <span class="ml-3 item-text">Program Registration</span>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="feedback.php">
              <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M240-400h320v-80H240v80Zm0-120h480v-80H240v80Zm0-120h480v-80H240v80ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/></svg>
              <span class="ml-3 item-text">Submit Feedback</span>
              </a>
            </li>
          </ul>
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
            <!-- Request Deletion Button -->
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletionModal">
                Request Account Deletion
            </button>
            <!-- Add this where appropriate (e.g., navbar) -->
            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                Change Password
            </button>

          <!-- Deletion Request Modal -->
          <div class="modal fade" id="deletionModal" tabindex="-1" aria-labelledby="deletionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <form method="POST" action="request_deletion.php" class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="deletionModalLabel">Confirm Account Deletion</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p>Are you sure you want to request account deletion? This action must be approved by an admin and cannot be undone once approved.</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-danger">Submit Request</button>
                </div>
              </form>
            </div>
          </div>
          <!-- Change Password Modal -->
          <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
              <div class="modal-dialog">
                  <form method="post" action="process_change_password.php" id="changePasswordForm">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title" id="changePasswordLabel">Change Password</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                              <div class="mb-3">
                                  <label for="current_password" class="form-label">Current Password</label>
                                  <input type="password" name="current_password" class="form-control" required>
                              </div>
                              <div class="mb-3">
                                  <label for="new_password" class="form-label">New Password</label>
                                  <input type="password" name="new_password" class="form-control" required>
                              </div>
                              <div class="mb-3">
                                  <label for="confirm_password" class="form-label">Confirm New Password</label>
                                  <input type="password" name="confirm_password" class="form-control" required>
                              </div>
                              <div id="passwordMsg" class="text-danger small"></div>
                          </div>
                          <div class="modal-footer">
                              <button type="submit" class="btn btn-primary">Update Password</button>
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
          <!-- Password Success Modal -->
          <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header bg-success text-white">
                  <h5 class="modal-title" id="successLabel">Success</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                  ✅ Your password has been updated successfully.
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                </div>
              </div>
            </div>
          </div>

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
                    <p>No program registrations yet.</p>
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
    <script src="js/jquery.dataTables`.min.js"></script>
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
    <script>
    document.getElementById("changePasswordForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        fetch("process_change_password.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(response => {
            const msgBox = document.getElementById("passwordMsg");
            if (response.includes("successfully")) {
                // Hide change password modal
                const changeModal = bootstrap.Modal.getInstance(document.getElementById("changePasswordModal"));
                changeModal.hide();

                // Show success modal
                const successModal = new bootstrap.Modal(document.getElementById("successModal"));
                successModal.show();

                msgBox.textContent = "";
                form.reset();
            } else {
                msgBox.textContent = response; // Show any error from PHP
            }
        })
        .catch(() => {
            document.getElementById("passwordMsg").textContent = "Something went wrong. Please try again.";
        });
    });
    </script>


</body>
</html>
