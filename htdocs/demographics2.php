<?php

include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $dob = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $education_level = $_POST['education_level'];
    $religion = $_POST['religion'];
    $hobbies = $_POST['hobbies'];

    $sql = "INSERT INTO demographics (first_name, last_name, date_of_birth, gender, phone_number, address, education_level, religion, hobbies) 
            VALUES ('$first_name', '$last_name', '$dob', '$gender', '$phone_number', '$address', '$education_level', '$religion', '$hobbies')";

    if ($conn->query($sql) === TRUE) {
        // echo "New record created successfully! <a href='dashboard.php'>You may now proceed to the dashboard!</a>";
        
        echo "<script>
            alert('Operation completed successfully!');
            window.location.href = 'dashboard.php';
          </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Demographic Information Form</title>
    <link rel="stylesheet" href="./Style/style2.css">
    <link rel="icon" type="image/png" href="./sklogo.png">
</head>
<body>
    <h2>Enter Demographic Information</h2>
    <form method="POST" action="">
        <div class="container">
            <div class="box form-box">
                <div class="field input">
                    <label>First Name:</label><br>
                    <input type="text" name="first_name" required><br><br>
                </div>
                
                <div class="field input">
                    <label>Last Name:</label><br>
                    <input type="text" name="last_name" required><br><br>
                </div>    

                <div class="field input">
                    <label>Date of Birth:</label><br>
                    <input type="date" name="date_of_birth" required><br><br>
                </div>

                <div class="field input">
                    <label for="gender">Gender:</label><br>
                        <select name="gender" id="gender">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select><br><br>
                </div>    
                
                <div class="field input">
                    <label>Phone Number:</label><br>
                    <input type="text" name="phone_number" required><br><br>
                </div>

                <div class="field input">
                    <label>Address:</label><br>
                    <input type="text" name="address" required><br><br>
                </div>

                <div class="field input">
                    <label for="education_level">Education Level:</label><br>
                        <select name='education_level' id='education_level'>
                            <option value="Elementary (Grade 1-6)">Elementary (Grade 1-6)</option>
                            <option value="High School (Grade 7-10)">High School (Grade 7-10)</option>
                            <option value="Senior High School (Grade 11-12)">Senior High School (Grade 11-12)</option>
                        </select><br><br>
                </div>    

                <div class="field input">
                    <label>Religion:</label><br>
                    <input type="text" name="religion" required><br><br>
                </div>

                <div class="field input">
                    <label>Hobbies</label><br>
                    <input type="text" name="hobbies" required><br><br>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Submit" required>
                </div>
    </form>
</body>
</html>
