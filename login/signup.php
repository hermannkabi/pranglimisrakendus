<?php

if (isset($_POST['...'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $class = $_POST['class'];
    $pwd = $_POST['pwd'];
    $pwdRepeat = $_POST['pwdrepeat'];

    require_once 'database.php';
    require_once 'functions.php';

    if (emptyInputSignup($name, $email, $class, $pwd, $pwdRepeat) != false) {
        echo 'Error'; // Add more inf
        exit();
    };
    if (invalidname($name) != false) {
        echo 'Error';// Add more inf
        exit();
    };
    if (invalidemail($email) != false) {
        echo 'Error';// Add more inf
        exit();
    };
    if (pwdMatch($pwd,  $pwdRepeat) !== false) {
        echo 'Error';// Add more inf
        exit();
    };
    if (nameExists($conn, $name, $email) != false) {
        echo 'Error';// Add more inf
        exit();
    };
    createUser($conn, $name, $email, $class, $pwd);
} else {
    echo 'Error';// Add more inf
    exit();
}