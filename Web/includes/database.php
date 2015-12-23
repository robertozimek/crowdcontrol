<?php
    //Better to not hardcode these information and instead use getenv
    $servername = getenv('OPENSHIFT_MYSQL_DB_HOST');
    $username = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
    $password = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
    $database = getenv('OPENSHIFT_APP_NAME');

    // Create connection
    $db = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
?>
