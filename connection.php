<?php

$servername = "localhost";
$username = "sksyroot";
$password = "72-Jz-H!C2KI3xNt";
$dbname = "sksy_skcapstone";
    
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error)
    {
        die("Connection Failed: ". $conn->connect_error);
    }

