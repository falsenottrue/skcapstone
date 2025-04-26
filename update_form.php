<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
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
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <link rel="icon" type="image/png" href="img/sklogo.png">
    
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/fontawesome.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    />
    <title>Update Form</title>
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
      .card, .theme-card
       {
        background-color: #1e1e1e; /* Dark background */
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
      

    /* Buttons */
        .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        color: #ffffff;
        }

        /* Input hover/focus (optional, if you want better focus indication) */
        .form-control:focus, .form-select:focus {
        border-color: #5cb85c;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
      .card-header {
        background-color: #2c2c2c;
        color: #ffffff;
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
          <li class="nav-item">
            <section
              class="nav-link text-muted my-2 circle-icon"
              href="#"
              data-toggle="modal"
              data-target=".modal-shortcut"
            >
              <span class="fe fe-message-circle fe-16"></span>
            </section>
          </li>
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
              <a class="dropdown-item" href="#"
                ><i class="fe fe-settings"></i>&nbsp;&nbsp;&nbsp;Settings</a
              >
              <a class="dropdown-log-out" href="#"
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

        <!-- YOUR CONTENT HERE -->
        <div class="container mt-4 theme-section">
  <div class="card shadow-sm mb-4 theme-card">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">User Information</h5>
    </div>
    <div class="card-body">
      <form method="POST" action="">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">First Name</label>
            <input type="text" class="form-control bg-body text-body" name="first_name" value="<?= htmlspecialchars($user_data['first_name']); ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" class="form-control bg-body text-body" name="last_name" value="<?= htmlspecialchars($user_data['last_name']); ?>" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label">Birthday</label>
            <input type="date" class="form-control bg-body text-body" name="birth_date" value="<?= $user_data['birth_date']; ?>" required>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" class="form-control bg-body text-body" name="contact_number" value="<?= htmlspecialchars($user_data['contact_number']); ?>" required>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Address</label>
            <input type="text" class="form-control bg-body text-body" name="address" value="<?= htmlspecialchars($user_data['address']); ?>" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Status</label>
            <select class="form-select bg-body text-body" name="status" required>
              <option value="Single" <?= ($user_data['status'] == "Single") ? "selected" : ""; ?>>Single</option>
              <option value="Married" <?= ($user_data['status'] == "Married") ? "selected" : ""; ?>>Married</option>
              <option value="Widowed" <?= ($user_data['status'] == "Widowed") ? "selected" : ""; ?>>Widowed</option>
              <option value="Separated" <?= ($user_data['status'] == "Separated") ? "selected" : ""; ?>>Separated</option>
              <option value="Annulled" <?= ($user_data['status'] == "Annulled") ? "selected" : ""; ?>>Annulled</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Occupation</label>
            <select class="form-select bg-body text-body" name="occupation" required>
              <option value="Student" <?= ($user_data['occupation'] == "Student") ? "selected" : ""; ?>>Student</option>
              <option value="Working_Student" <?= ($user_data['occupation'] == "Working_Student") ? "selected" : ""; ?>>Working Student</option>
              <option value="Unemployed" <?= ($user_data['occupation'] == "Unemployed") ? "selected" : ""; ?>>Unemployed</option>
            </select>
          </div>
        </div>

        <?php if ($guardian_data): ?>
          <div class="card shadow-sm mt-4 theme-card">
            <div class="card-header bg-secondary text-white">
              <h5 class="mb-0">Guardian Information</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Guardian's Name</label>
                  <input type="text" class="form-control bg-body text-body" name="guardian_name" value="<?= htmlspecialchars($guardian_data['guardian_name']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Guardian's Contact Number</label>
                  <input type="text" class="form-control bg-body text-body" name="guardian_contact" value="<?= htmlspecialchars($guardian_data['guardian_contact']); ?>" required>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Guardian Type</label>
                <select class="form-select bg-body text-body" name="relationship" required>
                  <option value="Parent" <?= ($guardian_data['relationship'] == "Parent") ? "selected" : ""; ?>>Parent</option>
                  <option value="Relative" <?= ($guardian_data['relationship'] == "Relative") ? "selected" : ""; ?>>Relative</option>
                  <option value="Legal Guardian" <?= ($guardian_data['relationship'] == "Legal Guardian") ? "selected" : ""; ?>>Legal Guardian</option>
                </select>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <div class="text-end mt-4">
          <button type="submit" class="btn btn-success">Update Information</button>
        </div>
      </form>
      <div class="text-end mt-4">
      <a href="dashboard.php"><button type="submit" class="btn btn-danger">Back</button></a>
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
</script>
  </body>
</html>
