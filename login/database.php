<?php

$serverName = "http://127.0.0.1:8000/register";
$dbUsername = "root";
$dbPassword = "";
$dbName = "login";

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}