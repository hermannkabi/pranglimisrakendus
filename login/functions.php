<?php

use Inertia\Inertia;


//Sigup Functions
function emptyInputSignup($name, $famname, $email, $class, $pwd, $pwdRepeat) {
    return empty($name) || empty($famname) || empty($email) || empty($class) || empty($pwd) || empty($pwdRepeat);
};

function invalidname($name) {
    return !preg_match('/^[A-Za-zÕÜÖÄõüöäžŽšŠ\s\-]+$/', $name);
};
function invalidfamname($famname){
    return !preg_match('/^[A-Za-zÕÜÖÄõüöäžŽšŠ\s\-]+$/', $famname);
}

function invalidemail($email) {
    return !filter_var($email, FILTER_VALIDATE_EMAIL);
};

function  pwdNoMatch($pwd,  $pwdRepeat) {
    return $pwd !== $pwdRepeat;
};

function  nameExists($conn, $email) {
    $sql = "SELECT * FROM users WHERE usersEmail = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'Error';
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }
    mysqli_stmt_close($stmt);
};

function  createUser($conn, $name, $famname, $email, $class, $pwd) {
    $sql = "INSERT INTO users (usersName, usersFamName, usersClass, usersEmail, usersPwd) VALUES (?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'Error';
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssss",  $name, $famname, $class, $email, $hashedPwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    route("login");
};

//Login functions
function emptyLogin($email, $pwd) {
    return empty($email) || empty($pwd);
};

function loginUser($conn, $email, $pwd){
    $nameExists = nameExists($conn, $email);

    if ($nameExists === false){
        return Inertia::render('Login/LoginPage', ["message"=>"Sellist kasutajat pole"]);
        exit();
    }

    $pwdHashed = $nameExists["usersPwd"];
    $checkPwd = password_verify($pwd, $pwdHashed);

    if (!$checkPwd) {
        # code...
        return Inertia::render('Login/LoginPage', ["message"=>"Parool on vale!"]);
    } else{
        session_start();
        $_SESSION['userid'] = $nameExists["usersId"];
        $_SESSION['username'] = $nameExists["usersName"];
        $_SESSION['userfamname'] = $nameExists["usersFamName"];
        $_SESSION['userclass'] = $nameExists["usersClass"];

        return Inertia::render('Dashboard/DashboardPage', ["user"=>"Parool on vale!"]);

    }
}