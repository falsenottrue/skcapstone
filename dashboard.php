<?php
include 'connection.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style/dashboard.css">
    <link rel="icon" type="image/png" href="img/sklogo.png">
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <h1>
                <p><img src="img/sklogo.png" class="Logo" width="80px" alt="Logo" class="logo"></p>
            </h1>
        </div>
        <ul class="menu">
            <a href='Community_Events.php'>
                <li><strong>Community Events</strong></li>
            </a>
            <a href='Program_Posting.php'>
                <li><strong>Program Posting</strong></li>
            </a>
            <a href='Program_Schedule.php'>
                <li><strong>Program Schedule</strong></li>
            </a>
            <a href='Event_Announcement.php'>
                <li><strong>Event Announcement</strong></li>
            </a>
            <a href='List_Residents.php'>
                <li><strong>List of Residents</strong></strong></li>
            </a>
            <a href='update_form.php'>
                <li><strong>Update Information</strong></li>
            </a>
            <a href="logout.php">
                <li><strong>Logout</strong></li>
            </a>
        </ul>
    </div>

    <div class="content">
        <div class="header">
            <div class="user-profile">
                <img src="img/sk.users.png" alt="User" class="user-icon">
                <div class="notifications">
                    <img src="img/sk.notification.png" alt="Notifications">
                </div>
            </div>
        </div>


            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><img src="img/sklogo.png" class="Logo" width="70px" alt="Logo" class="logo"></th>
                            <th>EMERGENCY HOTLINES</th>
                            <th></th>
                            <th><img src="img/sk.qc.jpg" class="Logo" width="70px" alt="Logo" class="logo"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><img src="img/sk.NDRRMC.jpg" class="Logo" width="50px" alt="Logo" class="logo"></td>
                            <td><strong>NDRRMC</strong> National Disaster and Risk Reduction and Management Council Tel:911-1406,912-2665912-5668</td>
                            <td><img src="img/sk.redcrosshotline.png" class="Logo" width="50px" alt="Logo" class="logo"></td>
                            <td><strong>RED CROSS HOTLINE</strong> Tel:143(02)527-0000</td>
                        </tr>
                        <tr>
                            <td><img src="img/sk.PNP.png" class="Logo" width="50px" alt="Logo" class="logo"></td>
                            <td><strong>PNP</strong> Philippine National Police Tel:117,722-0650 09178475757</td>
                            <td><img src="img/sk.nlex.jpg" class="Logo" width="50px" alt="Logo" class="logo"></td>
                            <td><strong>NLEX</strong> North Luzon Expressway Tel:(02)3-500,(02)580-8900</td>
                        </tr>
                        <tr>
                            <td><img src="img/sk.NCR.png" class="Logo" width="50px" alt="Logo" class="logo"></td>
                            <td><strong>(NCR)BUREAU OF FIRE PROTACTION</strong> Tel:(02)426-0219(02)426-3812,(02)426-0242</td>
                            <td><img src="img/SKsctex.png" class="Logo" width="50px" alt="Logo" class="logo"></td>
                            <td><strong>SCTEX</strong> Subic-Clark-Tarlac Expressway Tel:0920-92-SCTEX(72839)</td>
                        </tr>
                        <tr>
                            <td><img src="img/sk.DOTC.png" class="Logo" width="50px" alt="Logo" class="logo"></td>
                            <td><strong>DOTC</strong> Department of Trasportation and Communications-Central T:7890</td>
                            <td><img src="img/sk.skyway.png" class="Logo" width="50px" alt="Logo" class="logo"></td>
                            <td><strong>SKYWAY SYSTEM</strong> Tel:(20)776-7777</td>
                        </tr>
                        <tr>
                            <td><img src="img/sk.MMDA.png" class="Logo" width="50px" alt="Logo" class="logo"></td>
                            <td><strong>MMDA</strong> Metro Manila Developement Authority Tel:136,(20)882-0925</td>
                            <td><img src="img/sk.SLEX.png" class="Logo" width="50px" alt="Logo" class="logo"></td>
                            <td><strong>SLEX</strong>   South Luzon Expressway Tel:(20)824-2282(20)7763909</td>
                        </tr>
                        <tr>
                            <td><img src="img/sk.PCG.png" class="Logo" width="50px" alt="Logo" class="logo"></td>
                            <td><strong>PHILIPINE COAST GUARD</strong> Tel:(20)527-8481</td>
                            <td><img src="img/sk.DPWH.png" class="Logo" width="50px" alt="Logo" class="logo"></td>
                            <td><strong>DPWH</strong> Department of Public Works and Highways Tel:(20)304-3713(20)304-3904</td>
                        </tr>
                        <tr>
                            <td><img src="img/sk.PHIVOLCS.png" class="Logo" width="50px" alt="Logo" class="logo"></td>
                            <td><strong>PHIVOLCS</strong> Tel:(20)426-1468</td>
                            <td><img src="img/sk.DSWD.png" class="Logo" width="50px" alt="Logo" class="logo"></td>
                            <td><strong>DSWD</strong> Department of Social Welfare and Development Tel:(20)931-81-01</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        

       
</body>
</html>               