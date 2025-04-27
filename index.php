

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SK Nagkaisang Nayon</title>
    <link rel="stylesheet" href="index.css">
    <link rel="icon" type="image/png" href="img/sklogo.png"/>
    <script src="https://kit.fontawesome.com/edd0240440.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="script.js"></script>

</head>
<body>
    <div id="header">   
        <div class="container">
                <nav>
                    <img src="img/sklogo.png" class="logo">
                    <ul id="sidemenu">
                        <li><a href="#header">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <a href="dashboard.php" class="logout-button">Dashboard</a>
                        <i class="fas fa-times" onclick="closemenu()"></i>
                    </ul>
                    <i class="fas fa-bars" onclick="openmenu()"></i>
                </nav>
            <!--HEADER-->
            <div class="header-text">
                <p></p>
                
                    <h1><span> Sangguniang<br> Kabataan</span><br> Management System</h1>

                <div class="social-icons">
                    <a href="https://www.facebook.com/SKNagkaisangNayon"><i class="fab fa-facebook"></i></a>
                </div>
            </div>
        </div>
    </div>
   <!--ABOUT-->
   <div id="about">
        <div class="container">
            <div class="row">
                <div class="about-col-1">
                    <img src="img/sklogo.png">
                </div>
                <div class="about-col-2">
                    <h1 class="sub-title">About <span>Us</span></h1>
                    <h3>Welcome to SK Barangay Nagkaisang Nayon<br><br></h3>
                    <p>The Sangguniang Kabataan of Barangay Nagkaisang Nayon is committed to empowering the youth through leadership, community service, and meaningful programs. We strive to create opportunities for growth, participation, and positive change, building a stronger and more united community for all.</p>
                     
                     <div class="tab-title">
                        <p class="tab-links active-link" onclick="opentab('developers')">SK Members</p>
                        <p class="tab-links" onclick="opentab('location')">Location</p>
                        <p class="tab-links" onclick="opentab('contacts')">Contacts</p>
                     </div>

                     <div class="tab-contents active-tab" id="developers">
                        <ul>
                            <li><span>Project Team</span><br><b>Jason P. Pacis</b> - Hon. SK Chairman, <b>Ronneth De Ocampo</b> - SK Secretary,<br> 
                        <b>John Kenneth Junio</b> - SK Treasurer, <b>Florence Ann Juan</b> - Hon. SK Kagawad,<br>
                        <b>Feliciti Mateo</b> - Hon. SK Kagawad, <b>Rizelle Rizza Robles</b> - Hon. SK Kagawad,<br>
                        <b>Earl Joseph Francisco</b> - Hon. SK Kagawad, <b>Kaycee Lopez</b> - Hon. SK Kagawad,<br>
                        <b>Dirk Raisene Abad</b> - Hon. SK Kagawad,  <b>Daniela Marie Magno</b> - Hon. SK Kagawad<br></li>

                        </ul>
                     </div>
                     <div class="tab-contents" id="location">
                        <ul>
                            <li><span>Nagkaisang Nayon</span><br>Barangay Nagkaisang Nayon, Quezon City</li>
        
                        </ul>
                     </div>  <div class="tab-contents" id="contacts">
                        <ul>
                            <li><span>Email</span><br>sknagkaisangnayon@gmail.com</li>
                        
                        </ul>
                     </div>
                </div>
            </div>
        </div>
    </div>
    <!--SERVICES-->
    <div id="services">
        <div class="container">
            <h1 class="sub-title">Our <span>Services</span></h1>
            <div class="services-list">

                <div><a href="dashboard.php">
                <img src="img/sklogo.png" class="logo">
                    <h2>Sport Events</h2>
                    <p>A community-wide event promoting physical fitness, teamwork, and sportsmanship through organized games and competitions.</p>
                    <a href="dashboard.php">Head to Dashboard</a>
                </div></a>
                 <div>
                 <img src="img/sklogo.png" class="logo">
                    <h2>Educational Programs</h2>
                    <p>AI Assisted Educational Programs</p>
                    <a href="dashboard.php">Head to Dashboard</a>
                </div>
                <div>
                <img src="img/sklogo.png" class="logo">
                    <h2>Scholarship Program</h2>
                    <p>Provides financial and academic support to deserving students to help them pursue their educational goals.</p>
                    <a href="dashboard.php">Head to Dashboard</a>
                </div>
                <div>
                <img src="img/sklogo.png" class="logo">
                    <h2>Dental Mission</h2>
                    <p>Offers free dental checkups, cleanings, and treatments to improve oral health in the community.</p>
                    <a href="dashboard.php">Head to Dashboard</a>
                </div>
                <div>
                <img src="img/sklogo.png" class="logo">
                    <h2>Feeding Program</h2>
                    <p>Delivers nutritious meals to children and families in need to support health and well-being.</p>
                    <a href="dashboard.php">Head to Dashboard</a>
                </div>
                <div>
                <img src="img/sklogo.png" class="logo">
                    <a href="election&voter.php" class="box-link">
                    <h2>Election and Voter Management</h2>
                    <p>Outline strategies and improvements in election and voter management.</p>
                    <a href="#">Learn More</a>
                </div>
                
            </div>
        </div>
    </div>
    <!---PROJECTS-->
    <div id="portfolio">
        <div class="container">
            <h1 class="sub-title">Latest <span>Project</span></h1>
            <div class="work-list">
                <div class="work">
                    <img src="img/bebeko2.jpg">
                    <div class="layer">
                        <h3>Accomplishment Report</h3>
                        <p>Programs, Projects, and Activities Implemented!</p>
                        <a href="accomplishment_report.php"><i class="fa-solid fa-link"></i></a>
                    </div>
                </div>
                
                <div class="work">
                    <img src="img/liga.jpg">
                    <div class="layer">
                        <h3>Sport League Kontra Droga</h3>
                        <p>Liga Kontra Droga 2025</p>
                        <a href="Event_Announcement.php"><i class="fa-solid fa-link"></i></a>
                    </div>
                </div>


                <div class="work">
                    <img src="img/librengprint.jpg">
                    <div class="layer">
                        <h3>Libreng Print at Computer Rent</h3>
                        <p>Para sa paggawa ng projects, assignment, modules, research, at iba pa!</p>
                        <a href="https://www.facebook.com/SKNagkaisangNayon/posts/pfbid05jET2Y7rESHWzsnhxLsYfD6JMbBW9iKmRvAjE6d3Bur5zM1rzeYJYxZtnYxM9Xo3l"><i class="fa-solid fa-link"></i></a>
                    </div>
                </div>
            </div>
            <a href="#" class="btn">Back to top</a>
        </div>
    </div>
        </div>
        </div>
        <div class="copyright">
            <p>Copyright</p>
        </div>
    </div>


    <script>
        var tablinks = document.getElementsByClassName("tab-links");
        var tabcontents = document.getElementsByClassName("tab-contents");

        function opentab(tabname){
            for(tablink of tablinks){
                tablink.classList.remove("active-link");
            }
            for(tabcontent of tabcontents){
                tabcontent.classList.remove("active-tab");
            }
            event.currentTarget.classList.add("active-link");
            document.getElementById(tabname).classList.add("active-tab");
        }
    </script>

    <script>
        var sidemeu = document.getElementById('sidemenu');
        function openmenu(){
            sidemeu.style.right ="0";
        }
        function closemenu(){
            sidemeu.style.right ="-200px";
        }
    </script>

</body>
</html>