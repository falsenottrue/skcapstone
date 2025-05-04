<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
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
$sql2 = "SELECT * FROM budget_allocation";
$result = $conn->query($sql);
$result2 = $conn->query($sql2);
// Initialize variables
$youthCount = $eventCount = $activeProgramCount = 0;

// SQL: Count users under 18, total events, and active programs
$sql3 = "
    SELECT 
        (SELECT COUNT(*) FROM users WHERE TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 18) AS minor_count,
        (SELECT COUNT(*) FROM announ) AS event_count,
        (SELECT COUNT(*) FROM programs WHERE status = 'active') AS active_program_count
";

$result3 = $conn->query($sql3);

if ($result3 && $row = $result3->fetch_assoc()) {
    $minorCount = $row['minor_count'];
    $eventCount = $row['event_count'];
    $activeProgramCount = $row['active_program_count'];
}


$programs = [];
$sql4 = "SELECT program_name, description FROM programs WHERE status = 'Active'";
$resultPrograms = $conn->query($sql4);

if ($resultPrograms && $resultPrograms->num_rows > 0) {
    while ($row = $resultPrograms->fetch_assoc()) {
        $programs[] = $row;
    }
}
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
    <title>Admin Dashboard</title>
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
      
      /* Remove number input arrows (Chrome, Safari, Edge, Opera) */
      input[type="number"]::-webkit-inner-spin-button,
      input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }

      /* Remove number input arrows (Firefox) */
      input[type="number"] {
        -moz-appearance: textfield;
      }

      
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
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="admin_event.php">
              <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="M448.67-280h66.66v-240h-66.66v240Zm31.32-316q15.01 0 25.18-9.97 10.16-9.96 10.16-24.7 0-15.3-10.15-25.65-10.16-10.35-25.17-10.35-15.01 0-25.18 10.35-10.16 10.35-10.16 25.65 0 14.74 10.15 24.7 10.16 9.97 25.17 9.97Zm.19 516q-82.83 0-155.67-31.5-72.84-31.5-127.18-85.83Q143-251.67 111.5-324.56T80-480.33q0-82.88 31.5-155.78Q143-709 197.33-763q54.34-54 127.23-85.5T480.33-880q82.88 0 155.78 31.5Q709-817 763-763t85.5 127Q880-563 880-480.18q0 82.83-31.5 155.67Q817-251.67 763-197.46q-54 54.21-127 85.84Q563-80 480.18-80Zm.15-66.67q139 0 236-97.33t97-236.33q0-139-96.87-236-96.88-97-236.46-97-138.67 0-236 96.87-97.33 96.88-97.33 236.46 0 138.67 97.33 236 97.33 97.33 236.33 97.33ZM480-480Z"/></svg>
                <span class="ml-3 item-text">Announcement</span>
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

        <!-- SUMMARY CARDS -->
        <div class="container mt-4">
          <div class="row mb-3">
            <!-- Registered Youth Card -->
            <div class="col-md-4">
              <div class="card shadow" data-bs-toggle="modal" data-bs-target="#youthModal" style="cursor: pointer;">
                <div class="card-body">
                  <h5 class="card-title">Registered Youth</h5>
                  <h3>Youths under 18: <?php echo $minorCount; ?></h3>
                </div>
              </div>
            </div>
            
            <!-- Upcoming Events -->
            <div class="col-md-4">
              <div class="card shadow" data-bs-toggle="modal" data-bs-target="#eventsModal" style="cursor:pointer;">
                <div class="card-body">
                  <h5 class="card-title">Upcoming Events</h5>
                  <h3>Total Events: <?php echo $eventCount; ?></h3>
                </div>
              </div>
            </div>

            <!-- Active Programs -->
            <div class="col-md-4">
              <div class="card shadow" data-bs-toggle="modal" data-bs-target="#programsModal" style="cursor:pointer;">
                <div class="card-body">
                  <h5 class="card-title">Active Programs</h5>
                  <h3>Active Programs: <?php echo $activeProgramCount; ?></h3>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Modal for Youth List -->
        <div class="modal fade" id="youthModal" tabindex="-1" aria-labelledby="youthModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="youthModalLabel">Registered Youth (Under 18)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <?php
                // Query to get users under 18
                $today = date('Y-m-d');
                $minorQuery = "SELECT * FROM users WHERE TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 18";
                $minorResult = $conn->query($minorQuery);
                ?>

                <?php if ($minorResult && $minorResult->num_rows > 0): ?>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
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
                      <?php while ($minor = $minorResult->fetch_assoc()): ?>
                        <tr>
                          <td><?= htmlspecialchars($minor['first_name'] . ' ' . $minor['last_name']) ?></td>
                          <td><?= htmlspecialchars($minor['gender']) ?></td>
                          <td><?= htmlspecialchars($minor['birth_date']) ?></td>
                          <td><?= date_diff(date_create($minor['birth_date']), date_create())->y ?></td>
                          <td><?= htmlspecialchars($minor['contact_number']) ?></td>
                          <td><?= htmlspecialchars($minor['address']) ?></td>
                          <td><?= htmlspecialchars($minor['status']) ?></td>
                          <td><?= htmlspecialchars($minor['occupation']) ?></td>
                          <td><?= htmlspecialchars(date('F j, Y', strtotime($minor['created_at']))) ?></td>
                        </tr>
                      <?php endwhile; ?>
                      </tbody>
                    </table>
                  </div>
                <?php else: ?>
                  <p class="text-muted">No youth records found.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal for Upcoming Events -->
        <div class="modal fade" id="eventsModal" tabindex="-1" aria-labelledby="eventsModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="eventsModalLabel">Upcoming Events</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <?php
                // Query to get upcoming events (assuming announcements are future events)
                $eventQuery = "SELECT * FROM announ ORDER BY created_at DESC";
                $eventResult = $conn->query($eventQuery);
                ?>

                <?php if ($eventResult && $eventResult->num_rows > 0): ?>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead class="table-dark">
                        <tr>
                          <th>Image</th>
                          <th>Message</th>
                          <th>Link</th>
                          <th>Document</th>
                          <th>Created At</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($event = $eventResult->fetch_assoc()): ?>
                          <tr>
                            <td>
                              <?php if (!empty($event['image_path'])): ?>
                                <img src="<?= htmlspecialchars($event['image_path']) ?>" alt="Event Image" style="max-width: 100px;">
                              <?php else: ?>
                                <span class="text-muted">No Image</span>
                              <?php endif; ?>
                            </td>
                            <td><?= nl2br(htmlspecialchars($event['message'])) ?></td>
                            <td>
                              <?php if (!empty($event['link'])): ?>
                                <a href="<?= htmlspecialchars($event['link']) ?>" target="_blank">View Link</a>
                              <?php else: ?>
                                <span class="text-muted">No Link</span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php if (!empty($event['doc_path'])): ?>
                                <a href="<?= htmlspecialchars($event['doc_path']) ?>" target="_blank">Download Document</a>
                              <?php else: ?>
                                <span class="text-muted">No Document</span>
                              <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars(date('F j, Y, g:i a', strtotime($event['created_at']))) ?></td>
                          </tr>
                        <?php endwhile; ?>
                      </tbody>
                    </table>
                  </div>
                <?php else: ?>
                  <p class="text-muted">No upcoming events found.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal for Active Programs -->
        <div class="modal fade" id="programsModal" tabindex="-1" aria-labelledby="programsModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="programsModalLabel">Active Programs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <?php
                // Query to get active programs
                $programQuery = "SELECT * FROM programs WHERE status = 'Active'";
                $programResult = $conn->query($programQuery);
                ?>

                <?php if ($programResult && $programResult->num_rows > 0): ?>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead class="table-dark">
                        <tr>
                          <th>Program Name</th>
                          <th>Description</th>
                          <th>Start Date</th>
                          <th>End Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($program = $programResult->fetch_assoc()): ?>
                          <tr>
                            <td><?= htmlspecialchars($program['program_name']) ?></td>
                            <td><?= nl2br(htmlspecialchars($program['description'])) ?></td>
                            <td><?= htmlspecialchars(date('F j, Y', strtotime($program['start_date']))) ?></td>
                            <td><?= htmlspecialchars(date('F j, Y', strtotime($program['end_date']))) ?></td>
                          </tr>
                        <?php endwhile; ?>
                      </tbody>
                    </table>
                  </div>
                <?php else: ?>
                  <p class="text-muted">No active programs found.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

              
        <!-- YOUR CONTENT HERE --> 
        <div class="container mt-1">
          <div class="card shadow">
            <div class="card-header">Manage Budget Allocation</div>
            <div class="card-body">
              <table class="table table-bordered text-center" id="budgetTable">
                <thead>
                  <tr>
                    <th>Center</th>
                    <th>Amount</th>
                    <th>Details</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($row = $result2->fetch_assoc()): ?>
                    <tr data-id="<?= $row['id'] ?>">
                      <td><input class="form-control center-input" value="<?= htmlspecialchars($row['center']) ?>"></td>
                      <td><input type="number" class="form-control amount-input" value="<?= $row['amount'] ?>"></td>
                      <td><textarea class="form-control details-input"><?= htmlspecialchars($row['details']) ?></textarea></td>
                      <td>
                        <button class="btn btn-success btn-sm save-btn">Save</button>
                        <button class="btn btn-danger btn-sm delete-btn">Delete</button>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                  <div class="mb-2 text-end">
                    <button id="save-all-btn" class="btn btn-success">Save All Changes</button>
                  </div>
                </tbody>
                <tfoot>
                  <tr>
                    <td><input id="new-center" class="form-control" placeholder="New Center"></td>
                    <td><input id="new-amount" type="number" class="form-control" placeholder="Amount"></td>
                    <td><textarea id="new-details" class="form-control" placeholder="Details"></textarea></td>
                    <td><button id="add-btn" class="btn btn-primary btn-sm">Add</button></td>
                  </tr>
                </tfoot>
              </table>
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
    // Save All
    document.getElementById('save-all-btn').addEventListener('click', function () {
      const rows = document.querySelectorAll('tbody tr[data-id]');
      const updates = [];

      rows.forEach(row => {
        const id = row.dataset.id;
        const center = row.querySelector('.center-input').value;
        const amount = row.querySelector('.amount-input').value;
        const details = row.querySelector('.details-input').value;

        updates.push({ id, center, amount, details });
      });

      fetch('update_multiple_budget_rows.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ updates })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert("All changes saved!");
        } else {
          alert("Error saving: " + data.error);
        }
      });
    });

      // Add new row
      document.getElementById('add-btn').addEventListener('click', function () {
        const center = document.getElementById('new-center').value;
        const amount = document.getElementById('new-amount').value;
        const details = document.getElementById('new-details').value;

        if (!center || !amount) {
          alert('Center and amount are required!');
          return;
        }

        fetch('add_budget_row.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ center, amount, details })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            location.reload(); // Refresh to see new row
          } else {
            alert('Add failed: ' + data.error);
          }
        });
      });

      // Delete with confirmation modal
      document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
          const row = this.closest('tr');
          const id = row.dataset.id;

          if (!confirm('Are you sure you want to delete this budget entry?')) return;

          fetch('delete_budget_row.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              row.remove();
            } else {
              alert('Delete failed: ' + data.error);
            }
          });
        });
      });
      </script>


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
