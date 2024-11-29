<?php

include 'connection.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "You need to be logged in first.";
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM demographics WHERE dm_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

$stmt->close();

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
    $specific_needs = $_POST['specific_needs'] ?? null;
    $youth_age_group = $_POST['youth_age_group'];
    $educational_background = $_POST['educational_background'];
    $register_sk_voter = isset($_POST['register_sk_voter']) ? 1 : 0;
    $vote_last_sk_election = isset($_POST['vote_last_sk_election']) ? 1 : 0;
    $registered_national_voter = isset($_POST['registered_national_voter']) ? 1 : 0;
    $attended_sk_assembly = isset($_POST['attended_sk_assembly']) ? 1 : 0;
    $sk_assembly_reason = $_POST['sk_assembly_reason'] ?? null;

    $sql = "UPDATE demographics SET 
                first_name = ?, last_name = ?, address = ?, gender = ?, user_bday = ?, 
                contact_number = ?, status = ?, occupation = ?, sports = ?, precinct_number = ?, 
                mothers_maiden_name = ?, mother_bday = ?, mother_contact_number = ?, mother_occupation = ?, 
                fathers_name = ?, father_bday = ?, father_contact_number = ?, father_occupation = ?, 
                pwd = ?, solo_parent_household = ?, youth_classification = ?, specific_needs = ?, 
                youth_age_group = ?, educational_background = ?, register_sk_voter = ?, 
                vote_last_sk_election = ?, registered_national_voter = ?, attended_sk_assembly = ?, 
                sk_assembly_reason = ? WHERE dm_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssssssssissssssssssi", 
        $first_name, $last_name, $address, $gender, $user_bday, $contact_number, 
        $status, $occupation, $sports, $precinct_number, $mothers_maiden_name, 
        $mother_bday, $mother_contact_number, $mother_occupation, $fathers_name, 
        $father_bday, $father_contact_number, $father_occupation, $pwd, 
        $solo_parent_household, $youth_classification, $specific_needs, 
        $youth_age_group, $educational_background, $register_sk_voter, 
        $vote_last_sk_election, $registered_national_voter, $attended_sk_assembly, 
        $sk_assembly_reason, $user_id
    );

    if ($stmt->execute()) {
        $conn->commit();
        echo "<script>alert('Record updated successfully.'); window.location.href = 'dashboard.php';</script>";
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
    <title>Update Demographics Form</title>
    <link rel="stylesheet" href="style/style2.css">
    <link rel="icon" type="image/png" href="img/sklogo.png">
    <style>
        body { font-family: Arial, sans-serif; }
        form { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; }
        .checkbox-label { display: inline; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="nav">
        <div class="logo">
             <p><strong>UpdateForm</strong></p>   
           </a>
        </div>

        <div class="right-links">
            <a href="dashboard.php"> Back </a>
        </div>
    </div>
    <form method="POST" action="">
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($row['first_name']); ?>" required><br>

        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($row['last_name']); ?>" required><br>

        <label>Address:</label>
        <textarea name="address" required><?php echo htmlspecialchars($row['address']); ?></textarea><br>

        <label>Gender:</label>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male" <?php if ($row['gender'] == 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if ($row['gender'] == 'Female') echo 'selected'; ?>>Female</option>
            <option value="Other" <?php if ($row['gender'] == 'Other') echo 'selected'; ?>>Other</option>
        </select><br>

        <label>Birthdate:</label>
        <input type="date" name="user_bday" value="<?php echo $row['user_bday']; ?>" required><br>

        <label>Contact Number:</label>
        <input type="text" name="contact_number" value="<?php echo htmlspecialchars($row['contact_number']); ?>" required><br>

        <label>Status:</label>
        <select name="status" required>
            <option value="">Select Status</option>
            <option value="Single" <?php if ($row['status'] == 'Single') echo 'selected'; ?>>Single</option>
            <option value="Married" <?php if ($row['status'] == 'Married') echo 'selected'; ?>>Married</option>
            <option value="Widowed" <?php if ($row['status'] == 'Widowed') echo 'selected'; ?>>Widowed</option>
            <option value="Separated" <?php if ($row['status'] == 'Separated') echo 'selected'; ?>>Separated</option>
            <option value="Annulled" <?php if ($row['status'] == 'Annulled') echo 'selected'; ?>>Annulled</option>
        </select><br>

        <label>Occupation:</label>
        <select name="occupation" required>
            <option value="">Select Occupation</option>
            <option value="Student" <?php if ($row['occupation'] == 'Student') echo 'selected'; ?>>Student</option>
            <option value="Working_Student" <?php if ($row['occupation'] == 'Working_Student') echo 'selected'; ?>>Working Student</option>
            <option value="Working_Employed" <?php if ($row['occupation'] == 'Working_Employed') echo 'selected'; ?>>Working/Employed</option>
            <option value="Out_of_School_Youth" <?php if ($row['occupation'] == 'Out_of_School_Youth') echo 'selected'; ?>>Out of School Youth</option>
            <option value="Unemployed" <?php if ($row['occupation'] == 'Unemployed') echo 'selected'; ?>>Unemployed</option>
            <option value="Self_Employed" <?php if ($row['occupation'] == 'Self_Employed') echo 'selected'; ?>>Self-Employed</option>
        </select><br>

        <label>Sports:</label>
        <input type="text" name="sports" value="<?php echo htmlspecialchars($row['sports']); ?>" required><br>

        <label>Precinct Number:</label>
        <input type="text" name="precinct_number" value="<?php echo htmlspecialchars($row['precinct_number']); ?>"><br>

        <label>Mother's Maiden Name:</label>
        <input type="text" name="mothers_maiden_name" value="<?php echo htmlspecialchars($row['mothers_maiden_name']); ?>" required><br>

        <label>Mother's Birthdate:</label>
        <input type="date" name="mother_bday" value="<?php echo $row['mother_bday']; ?>" required><br>

        <label>Mother's Contact Number:</label>
        <input type="text" name="mother_contact_number" value="<?php echo htmlspecialchars($row['mother_contact_number']); ?>" required><br>

        <label>Mother's Occupation:</label>
        <textarea name="mother_occupation" required><?php echo htmlspecialchars($row['mother_occupation']); ?></textarea><br>

        <label>Father's Name:</label>
        <input type="text" name="fathers_name" value="<?php echo htmlspecialchars($row['fathers_name']); ?>" required><br>

        <label>Father's Birthdate:</label>
        <input type="date" name="father_bday" value="<?php echo $row['father_bday']; ?>" required><br>

        <label>Father's Contact Number:</label>
        <input type="text" name="father_contact_number" value="<?php echo htmlspecialchars($row['father_contact_number']); ?>" required><br>

        <label>Father's Occupation:</label>
        <textarea name="father_occupation" required><?php echo htmlspecialchars($row['father_occupation']); ?></textarea><br>

        <label>PWD:</label>
        <input type="checkbox" name="pwd" <?php if ($row['pwd']) echo 'checked'; ?>><br>

        <label>Solo Parent Household:</label>
        <input type="checkbox" name="solo_parent_household" <?php if ($row['solo_parent_household']) echo 'checked'; ?>><br>

        <label>Youth Classification:</label>
        <select name="youth_classification" required>
            <option value="In_School_Youth" <?php if ($row['youth_classification'] == 'In_School_Youth') echo 'selected'; ?>>In School Youth</option>
            <option value="Out_of_School_Youth" <?php if ($row['youth_classification'] == 'Out_of_School_Youth') echo 'selected'; ?>>Out of School Youth</option>
            <option value="Working_Youth" <?php if ($row['youth_classification'] == 'Working_Youth') echo 'selected'; ?>>Working Youth</option>
            <option value="Youth_with_Specific_Needs" <?php if ($row['youth_classification'] == 'Youth_with_Specific_Needs') echo 'selected'; ?>>Youth with Specific Needs</option>
        </select><br>

        <label>Specific Needs (if applicable):</label>
        <select name="specific_needs">
            <option value="">Select if applicable</option>
            <option value="PWD" <?php if ($row['specific_needs'] == 'PWD') echo 'selected'; ?>>PWD</option>
            <option value="Children_Conflict_Law" <?php if ($row['specific_needs'] == 'Children_Conflict_Law') echo 'selected'; ?>>Children in Conflict with Law</option>
            <option value="Indigenous" <?php if ($row['specific_needs'] == 'Indigenous') echo 'selected'; ?>>Indigenous</option>
            <option value="Teen_Parent" <?php if ($row['specific_needs'] == 'Teen_Parent') echo 'selected'; ?>>Teen Parent</option>
            <option value="Solo_Parent" <?php if ($row['specific_needs'] == 'Solo_Parent') echo 'selected'; ?>>Solo Parent</option>
        </select><br>

        <label>Youth Age Group:</label>
        <select name="youth_age_group" required>
            <option value="Child_Youth" <?php if ($row['youth_age_group'] == 'Child_Youth') echo 'selected'; ?>>Child Youth (15-17)</option>
            <option value="Core_Youth" <?php if ($row['youth_age_group'] == 'Core_Youth') echo 'selected'; ?>>Core Youth (18-24)</option>
            <option value="Young_Adult" <?php if ($row['youth_age_group'] == 'Young_Adult') echo 'selected'; ?>>Young Adult (25-30)</option>
        </select><br>

        <label>Educational Background:</label>
        <select name="educational_background">
            <option value="">Select Education Level</option>
            <option value="Elem_Level" <?php if ($row['educational_background'] == 'Elem_Level') echo 'selected'; ?>>Elementary Level</option>
            <option value="Elem_Graduate" <?php if ($row['educational_background'] == 'Elem_Graduate') echo 'selected'; ?>>Elementary Graduate</option>
            <option value="HS_Level" <?php if ($row['educational_background'] == 'HS_Level') echo 'selected'; ?>>High School Level</option>
            <option value="HS_Graduate" <?php if ($row['educational_background'] == 'HS_Graduate') echo 'selected'; ?>>High School Graduate</option>
            <option value="Voc_Graduate" <?php if ($row['educational_background'] == 'Voc_Graduate') echo 'selected'; ?>>Vocational Graduate</option>
            <option value="College_Level" <?php if ($row['educational_background'] == 'College_Level') echo 'selected'; ?>>College Level</option>
            <option value="College_Graduate" <?php if ($row['educational_background'] == 'College_Graduate') echo 'selected'; ?>>College Graduate</option>
            <option value="Masters_Level" <?php if ($row['educational_background'] == 'Masters_Level') echo 'selected'; ?>>Masters Level</option>
            <option value="Masters_Graduate" <?php if ($row['educational_background'] == 'Masters_Graduate') echo 'selected'; ?>>Masters Graduate</option>
            <option value="Doctorate_Level" <?php if ($row['educational_background'] == 'Doctorate_Level') echo 'selected'; ?>>Doctorate Level</option>
            <option value="Doctorate_Graduate" <?php if ($row['educational_background'] == 'Doctorate_Graduate') echo 'selected'; ?>>Doctorate Graduate</option>
        </select><br>

        <label>Registered as SK Voter:</label>
        <input type="checkbox" name="register_sk_voter" <?php if ($row['register_sk_voter']) echo 'checked'; ?>><br>

        <label>Voted in Last SK Election:</label>
        <input type="checkbox" name="vote_last_sk_election" <?php if ($row['vote_last_sk_election']) echo 'checked'; ?>><br>

        <label>Registered as National Voter:</label>
        <input type="checkbox" name="registered_national_voter" <?php if ($row['registered_national_voter']) echo 'checked'; ?>><br>

        <label>Attended SK Assembly:</label>
        <input type="checkbox" name="attended_sk_assembly" <?php if ($row['attended_sk_assembly']) echo 'checked'; ?>><br>

        <label>SK Assembly Reason (if not attended):</label>
        <select name="sk_assembly_reason">
            <option value="">Select Reason</option>
            <option value="No_KK" <?php if ($row['sk_assembly_reason'] == 'No_KK') echo 'selected'; ?>>No KK Assembly Meeting</option>
            <option value="Not_Interested_Attend" <?php if ($row['sk_assembly_reason'] == 'Not_Interested_Attend') echo 'selected'; ?>>Not Interested to Attend</option>
        </select><br>

        <button type="submit">Update</button>
    </form>
</body>
</html>
