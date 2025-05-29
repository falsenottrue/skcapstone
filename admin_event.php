<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
include 'connection.php';
include 'session_timeout.php';
session_start();

if (isset($_POST['action']) && $_POST['action'] === 'delete_announcement') {
  $delete_id = (int)$_POST['id'];

  $stmt = $conn->prepare("SELECT image_path, doc_path FROM announ WHERE id = ?");
  $stmt->bind_param("i", $delete_id);
  $stmt->execute();
  $stmt->bind_result($imagePath, $docPath);
  $stmt->fetch();
  $stmt->close();

  if (!empty($imagePath) && file_exists(__DIR__ . '/' . $imagePath)) {
      unlink(__DIR__ . '/' . $imagePath);
  }

  if (!empty($docPath) && file_exists(__DIR__ . '/' . $docPath)) {
      unlink(__DIR__ . '/' . $docPath);
  }

  $stmt = $conn->prepare("DELETE FROM announ WHERE id = ?");
  $stmt->bind_param("i", $delete_id);
  if ($stmt->execute()) {
      echo json_encode(['status' => 'success']);
  } else {
      echo json_encode(['status' => 'error', 'message' => 'Database delete failed']);
  }
  exit();
}


if (!isset($_SESSION['login_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied! Admins only.'); window.location.href='dashboard.php';</script>";
    exit();
}

date_default_timezone_set('Asia/Manila');
$entry_saved = false;

if (isset($_GET['delete_id'])) {
  $delete_id = (int)$_GET['delete_id'];

  $stmt = $conn->prepare("SELECT image_path, doc_path FROM announ WHERE id = ?");
  $stmt->bind_param("i", $delete_id);
  $stmt->execute();
  $stmt->bind_result($imagePath, $docPath);
  $stmt->fetch();
  $stmt->close();

  if (!empty($imagePath) && file_exists(__DIR__ . '/' . $imagePath)) {
      unlink(__DIR__ . '/' . $imagePath);
  }

  if (!empty($docPath) && file_exists(__DIR__ . '/' . $docPath)) {
      unlink(__DIR__ . '/' . $docPath);
  }

  $stmt = $conn->prepare("DELETE FROM announ WHERE id = ?");
  $stmt->bind_param("i", $delete_id);

  if ($stmt->execute()) {
      echo "<script>alert('Announcement deleted successfully.'); window.location.href='admin_event.php';</script>";
      exit();
  } else {
      echo "<script>alert('Failed to delete announcement.');</script>";
  }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        die('Error: Image upload is required.');
    }
    $imgDir = __DIR__ . '/uploads/images/';
    if (!is_dir($imgDir)) mkdir($imgDir, 0755, true);
    $imgFile = uniqid() . '_' . basename($_FILES['image']['name']);
    $imgPath = $imgDir . $imgFile;
    move_uploaded_file($_FILES['image']['tmp_name'], $imgPath);
    $imgRelPath = 'uploads/images/' . $imgFile;

    $link = !empty($_POST['link']) ? $conn->real_escape_string($_POST['link']) : null;

    $docRelPath = null;
    if (!empty($_FILES['document']['name'])) {
        $docDir = __DIR__ . '/uploads/docs/';
        if (!is_dir($docDir)) mkdir($docDir, 0755, true);
        $docFile = uniqid() . '_' . basename($_FILES['document']['name']);
        $docPath = $docDir . $docFile;
        move_uploaded_file($_FILES['document']['tmp_name'], $docPath);
        $docRelPath = 'uploads/docs/' . $docFile;
    }

    $message = trim($_POST['message'] ?? '');
    $message = !empty($message) ? $conn->real_escape_string($message) : null;

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
  <link rel="icon" type="image/png" href="img/sklogo.png"/>
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
    /* button {
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
    } */
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
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="manage_budget.php">
              <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="M446.67-200.67h66.66v-40h53.34q14.16 0 23.75-9.58Q600-259.83 600-274v-126.67q0-14.16-9.58-23.75-9.59-9.58-23.75-9.58h-140v-60H600v-66.67h-86.67v-40h-66.66v40h-53.34q-14.16 0-23.75 9.59-9.58 9.58-9.58 23.75v126.66q0 14.17 9.58 23.75 9.59 9.59 23.75 9.59h140v60H360v66.66h86.67v40ZM226.67-80q-27 0-46.84-19.83Q160-119.67 160-146.67v-666.66q0-27 19.83-46.84Q199.67-880 226.67-880H574l226 226v507.33q0 27-19.83 46.84Q760.33-80 733.33-80H226.67Zm300.66-574v-159.33H226.67v666.66h506.66V-654h-206ZM226.67-813.33V-654v-159.33 666.66-666.66Z"/></svg>
                <span class="ml-3 item-text">Manage Budget</span>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100">
              <a class="nav-link" href="admin_deletion_requests.php">
              <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="M480-446.67q-58.33 0-98.17-39.83Q342-526.33 342-584.67q0-58.33 39.83-98.16 39.84-39.84 98.17-39.84t98.17 39.84Q618-643 618-584.67q0 58.34-39.83 98.17-39.84 39.83-98.17 39.83Zm0-66.66q31.33 0 51.33-20t20-51.34q0-31.33-20-51.33T480-656q-31.33 0-51.33 20t-20 51.33q0 31.34 20 51.34 20 20 51.33 20Zm0 432.66q-139.67-35-229.83-161.5Q160-368.67 160-520.67v-240l320-120 320 120v240q0 152-90.17 278.5Q619.67-115.67 480-80.67ZM480-480Zm0-329.67-253.33 95.34v193.66q0 60 16.66 115.34Q260-350 290-302.33q44.67-23.67 91.67-36 47-12.34 98.33-12.34t98.33 12.34q47 12.33 91.67 36 30-47.67 46.67-103 16.66-55.34 16.66-115.34v-193.66L480-809.67ZM480-284q-38 0-75.33 9.33-37.34 9.34-73 27.34Q362.67-214 400-189t80 39q42.67-14 80-39t68.33-58.33q-35.66-18-73-27.34Q518-284 480-284Z"/></svg>  
                <span class="ml-3 item-text">User Requests</span>
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

      <button type="submit" class="btn btn-success w-100">Submit</button>
    </form>
    <hr>
    <a href="admin_dashboard.php"><button class="btn btn-danger w-100">Back</button></a>
  </div>

  <div class="container mt-5">
    <h2>Existing Announcements</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Image</th>
                <th>Link</th>
                <th>Message</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT id, image_path, link, message, created_at FROM announ ORDER BY created_at DESC");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td><img src="' . htmlspecialchars($row['image_path']) . '" alt="Image" style="max-width: 80px; height: auto;"></td>';
                    echo '<td>' . (!empty($row['link']) ? '<a href="' . htmlspecialchars($row['link']) . '" target="_blank">Visit</a>' : 'N/A') . '</td>';
                    echo '<td>' . (empty($row['message']) ? 'No message' : htmlspecialchars(substr($row['message'], 0, 5000))) . '</td>';
                    echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
                    echo '<td><button class="btn btn-danger btn-sm delete-btn" data-id="' . $row['id'] . '">Delete</button></td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="5" class="text-center">No announcements found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
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
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function() {
          if (confirm('Are you sure you want to delete this announcement?')) {
            const announcementId = this.getAttribute('data-id');

            fetch('admin_event.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
              },
              body: 'action=delete_announcement&id=' + announcementId
            })
            .then(response => response.json())
            .then(data => {
              if (data.status === 'success') {
                alert('✅ Announcement deleted!');
                this.closest('tr').remove(); // Remove the row from the table
              } else {
                alert('❌ Failed to delete announcement.');
              }
            })
            .catch(error => {
              console.error('Error:', error);
              alert('❌ An error occurred.');
            });
          }
        });
      });
    });

    <?php if ($entry_saved): ?>
      window.addEventListener('DOMContentLoaded', () => {
        document.getElementById('successModal').style.display = 'flex';
      });
    <?php endif; ?>
  </script>
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
</body>
</html>