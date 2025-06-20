<?php
$db_server = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "asset_management";
try {

    $conn = new mysqli($db_server, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection to database failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    //log
    error_log("Database connection error: " . $e->getMessage());
    die("" . $e->getMessage());
}
?>