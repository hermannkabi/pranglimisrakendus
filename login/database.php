<?php
use Inertia\Inertia;

$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "login";

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

return Inertia::render('Welcome/WelcomePage', ["message"=>"Parool on vale!"]);


if (!$conn) {
    return Inertia::render('Login/LoginPage', ["message"=>"Parool on vale!"]);
}