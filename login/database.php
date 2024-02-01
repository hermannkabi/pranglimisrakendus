<?php

$serverName = "127.0.0.1:8000";
$dbUsername = "root";
$dbPassword = "";
$dbName = "login";

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

return Inertia::render('Welcome/WelcomePage', ["message"=>"Parool on vale!"]);


if (!$conn) {
    return Inertia::render('Login/LoginPage', ["message"=>"Parool on vale!"]);
}