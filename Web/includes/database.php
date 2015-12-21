<?php
    $servername = "SERVER_NAME";
    $username = "USER_NAME";
    $password = "PASSWORD";
    $database = "DB_NAME";

    // Create connection
    $db = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
?>
