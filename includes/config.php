<?php
ob_start(); //Turns on output buffering
session_start(); // starts session (so we can use a session).

date_default_timezone_set("America/Los_Angeles");

try {
    $con = new PDO("mysql:dbname=VideoTube;host=localhost", "root", "");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>