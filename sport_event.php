<?php
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();
include 'session_timeout.php';
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style2.css">
    <title> Sports Event </title>
</head>
<body>
    <div class="nav">
        <div class="logo">
             <p><a href="Sport event.php"><img src="img/sklogo.png" alt="Logo" class="logo"></a></p>
             <p><strong>Sports Events</strong></p>   
           </a>
        </div>

        <div class="right-links">
            <a href="Community_Events.php"> Back </a>
          </div>
         </div>   

            <div class="main-box">
           <div class="center-box">
            <div class="box">
                <h1><p><img src="img/Sk.Basketball.png" class="Logo" width="60px" alt="Logo" class="logo"></p></h1>
                <p><strong>BASKETBALL</strong>- (Mon 8:30am to 11:30am)</p>
            </div>
            <div class="box">
                <h1><p><img src="img/SK.Volleyball.png" class="Logo" width="60px" alt="Logo" class="logo"></p></h1>
                <p><strong>VOLLEYBALL</strong>- (Tue 3:00pm to 5:00pm)</p>      
            </div>
            <div class="box">
                <h1><p><img src="img/sk.Badminton.jpg" class="Logo" width="60px" alt="Logo" class="logo"></p></h1>
                <p><strong>BADMINTON</strong>- (Fri 6:00am to 10:00am)</p>      
            </div>
            <div class="box">
                <h1><p><img src="img/sk.Swimming.jpg" class="Logo" width="60px" alt="Logo" class="logo"></p></h1>
                <p><strong>SWIMMING</strong>- (Sat 4:00pm to 8:00pm)</p>
            </div>
           </div> 
          </div>
         </div>
        </div>
</body>
</html>