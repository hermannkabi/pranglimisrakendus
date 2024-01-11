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

    if (emptyInputSignup($name, $famname, $email, $class, $pwd, $pwdRepeat) != false) {
        echo 'Error'; // Add more inf
        exit();
    };
}