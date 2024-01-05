<?php

if (isset($_POST['registration'])) {

    $name = $_POST['name'];
    $famname = $_POST['famname'];
    $email = $_POST['email'];
    $class = $_POST['class'];
    $adminpsw = $_POST['adminpsw'];
    $pwd = $_POST['pwd'];
    $pwdRepeat = $_POST['pwdrepeat'];

    require_once 'database.php';
    require_once 'functions.php';

    if (emptyInputSignup($name, $email, $class, $pwd, $pwdRepeat, $result, $famname, $adminpsw) != false) {
        echo 'Error'; // Add more inf
        exit();
    };
    if (invalidname($name, $result) != false) {
        echo 'Error';// Add more inf
        exit();
    };
    if (invalidemail($email, $result) != false) {
        echo 'Error';// Add more inf
        exit();
    };
    if (pwdMatch($pwd,  $pwdRepeat, $result) !== false) {
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