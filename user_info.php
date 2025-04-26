<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
include 'connection.php';

session_start();
if (!isset($_SESSION['login_id'])) {
    echo "<script>alert('You must be logged in to submit this form.'); window.location.href='login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];

    $today = date("Y-m-d");
    $age = date_diff(date_create($birth_date), date_create($today))->y;

    if ($age > 21) {
        echo "<script>alert('Only users aged 21 or below can register.'); window.location.href = 'dashboard.php';</script>";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, birth_date, gender, contact_number, address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $first_name, $last_name, $birth_date, $gender, $contact_number, $address);

    if ($stmt->execute()) {
        $login_id = $stmt->insert_id;

        if ($age < 18) {
            $guardian_name = $_POST['guardian_name'];
            $guardian_contact = $_POST['guardian_contact'];
            $relationship = $_POST['guardian_relationship'];

            $stmt = $conn->prepare("INSERT INTO guardian_info (user_id, guardian_name, guardian_contact, relationship) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $login_id, $guardian_name, $guardian_contact, $relationship);
            $stmt->execute();
        }

        echo "<script>alert('Registration successful!'); window.location.href = 'demographics.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Youth Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/form-dark.css">
    <link rel="icon" type="image/png" href="img/sklogo.png">
    <script>
        function checkAge() {
            let birthDate = new Date(document.getElementById("birth_date").value);
            let today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            if (today < new Date(today.getFullYear(), birthDate.getMonth(), birthDate.getDate())) {
                age--;
            }
            document.getElementById("guardian_section").style.display = (age < 18) ? "block" : "none";
        }

        function enableForm() {
            let consentCheckbox = document.getElementById("consent_checkbox");
            let submitBtn = document.getElementById("submit_button");
            submitBtn.disabled = !consentCheckbox.checked;
        }

        window.onload = function() {
            let consentModal = new bootstrap.Modal(document.getElementById('consentModal'));
            consentModal.show();
        }
    </script>
</head>
<body class="container mt-4">

    <div class="modal fade" id="consentModal" tabindex="-1" aria-labelledby="consentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="consentModalLabel">Data Privacy Notice</h5>
                </div>
                <div class="modal-body">
                    <p>By proceeding, you consent to the collection and processing of your personal information in accordance with the Data Privacy Act of 2012. Your information will be used solely for registration purposes and will not be shared without your consent.</p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="consent_checkbox" onchange="enableForm()">
                        <label class="form-check-label" for="consent_checkbox">
                            I agree and consent to the processing of my personal data.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" disabled id="submit_button">Proceed</button>
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-center">Youth Registration Form</h2>
    <form action="user_info.php" method="POST">
        <div class="mb-3">
            <label>First Name:</label>
            <input type="text" name="first_name" class="form-control" required autocomplete="off">
        </div>
        <div class="mb-3">
            <label>Last Name:</label>
            <input type="text" name="last_name" class="form-control" required autocomplete="off">
        </div>
        <div class="mb-3">
            <label>Birth Date:</label>
            <input type="date" id="birth_date" name="birth_date" class="form-control" required onchange="checkAge()">
        </div>
        <div class="mb-3">
            <label>Gender:</label>
            <select name="gender" class="form-control" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Contact Number:</label>
            <input type="text" name="contact_number" class="form-control" autocomplete="off">
        </div>
        <div class="mb-3">
            <label>Address:</label>
            <input type="text" name="address" class="form-control" required autocomplete="off">
        </div>

        <div id="guardian_section" style="display: none;">
            <h4>Guardian Information (For users under 18)</h4>
            <div class="mb-3">
                <label>Guardian Name:</label>
                <input type="text" name="guardian_name" class="form-control" autocomplete="off">
            </div>
            <div class="mb-3">
                <label>Guardian Contact:</label>
                <input type="text" name="guardian_contact" class="form-control" autocomplete="off">
            </div>
            <div class="mb-3">
                <label>Relationship:</label>
                <select name="guardian_relationship" class="form-control">
                    <option value="">Select Relationship</option>
                    <option value="Parent">Parent</option>
                    <option value="Relative">Relative</option>
                    <option value="Legal Guardian">Legal Guardian</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
