<?php
include 'connection.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $user_bday = $_POST['user_bday'];
    $contact_number = $_POST['contact_number'];
    $status = $_POST['status'];
    $occupation = $_POST['occupation'];
    $sports = $_POST['sports'];
    $precinct_number = $_POST['precinct_number'];

    $mothers_maiden_name = $_POST['mothers_maiden_name'];
    $mother_bday = $_POST['mother_bday'];
    $mother_contact_number = $_POST['mother_contact_number'];
    $mother_occupation = $_POST['mother_occupation'];
    $fathers_name = $_POST['fathers_name'];
    $father_bday = $_POST['father_bday'];
    $father_contact_number = $_POST['father_contact_number'];
    $father_occupation = $_POST['father_occupation'];
    $pwd = isset($_POST['pwd']) ? 1 : 0;
    $solo_parent_household = isset($_POST['solo_parent_household']) ? 1 : 0;

    $youth_classification = $_POST['youth_classification'];
    $specific_needs = $_POST['specific_needs'];
    $youth_age_group = $_POST['youth_age_group'];
    $educational_background = $_POST['educational_background'];
    $register_sk_voter = isset($_POST['register_sk_voter']) ? 1 : 0;
    $vote_last_sk_election = isset($_POST['vote_last_sk_election']) ? 1 : 0;
    $registered_national_voter = isset($_POST['registered_national_voter']) ? 1 : 0;
    $attended_sk_assembly = isset($_POST['attended_sk_assembly']) ? 1 : 0;
    $sk_assembly_reason = $_POST['sk_assembly_reason'] ?? null;

    $sql = "INSERT INTO demographics (
                first_name, last_name, address, gender, user_bday, contact_number, 
                status, occupation, sports, precinct_number, mothers_maiden_name, 
                mother_bday, mother_contact_number, mother_occupation, fathers_name, 
                father_bday, father_contact_number, father_occupation, pwd, 
                solo_parent_household, youth_classification, specific_needs, 
                youth_age_group, educational_background, register_sk_voter, 
                vote_last_sk_election, registered_national_voter, attended_sk_assembly, 
                sk_assembly_reason
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssssssssissssssssss", 
        $first_name, $last_name, $address, $gender, $user_bday, $contact_number, 
        $status, $occupation, $sports, $precinct_number, $mothers_maiden_name, 
        $mother_bday, $mother_contact_number, $mother_occupation, $fathers_name, 
        $father_bday, $father_contact_number, $father_occupation, $pwd, 
        $solo_parent_household, $youth_classification, $specific_needs, 
        $youth_age_group, $educational_background, $register_sk_voter, 
        $vote_last_sk_election, $registered_national_voter, $attended_sk_assembly, 
        $sk_assembly_reason
    );

    if ($stmt->execute()) {
        echo "<script>alert('Data inserted successfully.'); window.location.href = 'dashboard.php';</script>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demographic Information Form</title>
    <link rel="stylesheet" href="style/style2.css">
    <link rel="icon" type="image/png" href="img/sklogo.png">
    <style>
        body { font-family: Arial, sans-serif; }
        form { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; }
        .checkbox-label { display: inline; margin-right: 10px; }
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0, 0, 0, 0.5); 
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        #submit-btn {
            cursor: not-allowed;
            background-color: #ccc;
        }
    </style>
</head>
<body>
    <div id="consentModal" class="modal">
        <div class="modal-content">
            <h3>Data Security Act</h3>
            <p>
                By proceeding with this form, you agree to the collection and use of your personal data
                as governed by the Data Security Act. Please confirm your consent to continue.
            </p>
            <label>
                <input type="checkbox" id="consentCheckbox"> I consent to the use of my personal data.
            </label>
            <button id="proceed-btn" disabled>Proceed</button>
        </div>
    </div>

    <h2>Demographics Form</h2>
    <form action="demographics.php" method="POST">

        <h3>Personal Information</h3>
        <label>First Name:</label>
        <input type="text" name="first_name" required>

        <label>Last Name:</label>
        <input type="text" name="last_name" required>

        <label>Address:</label>
        <input type="text" name="address" required>

        <label>Gender:</label>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>

        <label>Birthday:</label>
        <input type="date" name="user_bday" required>

        <label>Contact Number:</label>
        <input type="text" name="contact_number">

        <label>Status:</label>
        <select name="status" required>
            <option value="">Select Status</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Widowed">Widowed</option>
            <option value="Separated">Separated</option>
            <option value="Annulled">Annulled</option>
        </select>

        <label>Occupation:</label>
        <select name="occupation" required>
            <option value="">Select Occupation</option>
            <option value="Student">Student</option>
            <option value="Working_Student">Working Student</option>
            <option value="Working_Employed">Working/Employed</option>
            <option value="Out_of_School_Youth">Out of School Youth</option>
            <option value="Unemployed">Unemployed</option>
            <option value="Self_Employed">Self-Employed</option>
        </select>

        <label>Sports:</label>
        <input type="text" name="sports">

        <label>Precinct Number:</label>
        <input type="text" name="precinct_number">

        <h3>Additional Information</h3>
        <label>Mother's Maiden Name:</label>
        <input type="text" name="mothers_maiden_name">

        <label>Mother's Birthday:</label>
        <input type="date" name="mother_bday">

        <label>Mother's Contact Number:</label>
        <input type="text" name="mother_contact_number">

        <label>Mother's Occupation:</label>
        <input type="text" name="mother_occupation">

        <label>Father's Name:</label>
        <input type="text" name="fathers_name">

        <label>Father's Birthday:</label>
        <input type="date" name="father_bday">

        <label>Father's Contact Number:</label>
        <input type="text" name="father_contact_number">

        <label>Father's Occupation:</label>
        <input type="text" name="father_occupation">

        <label class="checkbox-label"><input type="checkbox" name="pwd" value="1"> PWD</label>
        <label class="checkbox-label"><input type="checkbox" name="solo_parent_household" value="1"> Solo Parent Household</label>

        <h3>Demographic Characteristics</h3>
        <label>Youth Classification:</label>
        <select name="youth_classification" required>
            <option value="">Select Classification</option>
            <option value="In_School_Youth">In School Youth</option>
            <option value="Out_of_School_Youth">Out of School Youth</option>
            <option value="Working_Youth">Working Youth</option>
            <option value="Youth_with_Specific_Needs">Youth with Specific Needs</option>
        </select>

        <label>If Youth with Specific Needs:</label>
        <select name="specific_needs">
            <option value="">None</option>
            <option value="PWD">PWD</option>
            <option value="Children_Conflict_Law">Children in Conflict with Law</option>
            <option value="Indigenous">Indigenous</option>
            <option value="Teen_Parent">Teen Parent</option>
            <option value="Solo_Parent">Solo Parent</option>
        </select>

        <label>Youth Age Group:</label>
        <select name="youth_age_group" required>
            <option value="Child_Youth">Child Youth (15-17)</option>
            <option value="Core_Youth">Core Youth (18-24)</option>
            <option value="Young_Adult">Young Adult (25-30)</option>
        </select>

        <label>Educational Background:</label>
        <select name="educational_background" required>
            <option value="Elem_Level">Elementary Level</option>
            <option value="Elem_Graduate">Elementary Graduate</option>
            <option value="HS_Level">High School Level</option>
            <option value="HS_Graduate">High School Graduate</option>
            <option value="Voc_Graduate">Vocational Graduate</option>
            <option value="College_Level">College Level</option>
            <option value="College_Graduate">College Graduate</option>
            <option value="Masters_Level">Masters Level</option>
            <option value="Masters_Graduate">Masters Graduate</option>
            <option value="Doctorate_Level">Doctorate Level</option>
            <option value="Doctorate_Graduate">Doctorate Graduate</option>
        </select>

        <label class="checkbox-label"><input type="checkbox" name="register_sk_voter" value="1"> Register SK Voter</label>
        <label class="checkbox-label"><input type="checkbox" name="vote_last_sk_election" value="1"> Voted Last SK Election</label>
        <label class="checkbox-label"><input type="checkbox" name="registered_national_voter" value="1"> Registered National Voter</label>
        <label class="checkbox-label"><input type="checkbox" name="attended_sk_assembly" value="1"> Attended SK Assembly</label>

        <label>If Not Attended SK Assembly, Why?</label>
        <select name="sk_assembly_reason">
            <option value="">N/A</option>
            <option value="No_KK">No KK Assembly Meeting</option>
            <option value="Not_Interested_Attend">Not Interested to Attend</option>
        </select>

        <br><br>
        <button type="submit">Submit</button>
    </form>
    <script>
        const modal = document.getElementById("consentModal");
        const proceedBtn = document.getElementById("proceed-btn");
        const consentCheckbox = document.getElementById("consentCheckbox");
        const submitBtn = document.getElementById("submit-btn");

        window.onload = () => {
            modal.style.display = "block";
        };


        consentCheckbox.onchange = () => {
            proceedBtn.disabled = !consentCheckbox.checked;
        };

        proceedBtn.onclick = () => {
            modal.style.display = "none";
            submitBtn.disabled = false;
            submitBtn.style.cursor = "pointer";
            submitBtn.style.backgroundColor = "#007BFF";
        };
    </script>
</body>
</html>