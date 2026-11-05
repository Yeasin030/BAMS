<?php
    // Connection Wit mysql

    $host ="localhost";
    $user ="root";
    $pass = "";
    $db = "business_account_management";
    $conn = new mysqli($host, $user, $pass, $db);
    if (!$conn) {
        die("Database connection failed :" . mysqli_connect_error());
        
    }

  
?>
