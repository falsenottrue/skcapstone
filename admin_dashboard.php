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
    <title>Dashboard</title>
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
      
      .center-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 80px); /* adjust height if navbar exists */
        padding-top: 0px; /* adjust if navbar height is fixed */
        text-align: center;
      }

      .box img {
        margin: 5px;
        vertical-align: middle;
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
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="admin_dashboard.php">
              <i class="fas fa-landmark fa-3x"></i>
              <div class="brand-title">
                <br />
                <span>SK NAGKAISANG NAYON</span>
              </div>
            </a>
          </div>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item dropdown">
              <a class="nav-link" href="admin_dashboard.php">
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
              <a class="nav-link" href="update_program.php">
              <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="M164.67-160v-66.67H288l-15.33-12.66q-60-49.34-86.34-109Q160-408 160-477.33q0-107.67 63.83-192.84 63.84-85.16 167.5-115.83v69.33q-74 28-119.33 93.84-45.33 65.83-45.33 145.5 0 57 21.33 102.16 21.33 45.17 60 79.84L331.33-278v-115.33H398V-160H164.67Zm404.66-13.33v-70q74.67-28 119.34-93.84 44.66-65.83 44.66-145.5 0-47-21.33-94.16-21.33-47.17-58.67-84.5L630.67-682v115.33H564V-800h233.33v66.67h-124l15.34 14q56.33 53.66 83.83 115.5Q800-542 800-482.67 800-375 736.5-289.5 673-204 569.33-173.33Z"/></svg>
                <span class="ml-3 item-text">Update Programs</span>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="feedback_list.php">
              <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="M146.67-160q-27 0-46.84-19.83Q80-199.67 80-226.67v-152.66h66.67v152.66h666.66v-506.66H146.67v154H80v-154q0-27 19.83-46.84Q119.67-800 146.67-800h666.66q27 0 46.84 19.83Q880-760.33 880-733.33v506.66q0 27-19.83 46.84Q840.33-160 813.33-160H146.67Zm312.66-142L412-350l96.33-96H80v-66.67h428.33l-96.33-96 47.33-48 177.34 177.34L459.33-302Z"/></svg>
                <span class="ml-3 item-text">Feedback List</span>
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

        <!-- YOUR CONTENT HERE -->
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
      const idleLimit = 10 * 60; // 10 minutes in seconds
      const warningLimit = 9 * 60; // Show modal at 9 minutes

      function resetIdleTimer() {
        idleTime = 0;
      }

      function extendSession() {
        $('#sessionModal').modal('hide');
        resetIdleTimer();
        $.get('extend_session.php'); // Optional: touch the session server-side
      }

      function logout() {
        window.location.href = 'logout.php';
      }

      // Reset timer on any user interaction
      ['mousemove', 'keydown', 'click', 'scroll'].forEach(evt => {
        document.addEventListener(evt, resetIdleTimer, false);
      });

      setInterval(() => {
        idleTime++;
        if (idleTime === warningLimit) {
          $('#sessionModal').modal('show');
        } else if (idleTime >= idleLimit) {
          logout();
        }
      }, 1000); // check every second

      // Button click handlers
      $('#extendBtn').on('click', extendSession);
      $('#logoutBtn').on('click', logout);
    </script>
  </body>
</html>
