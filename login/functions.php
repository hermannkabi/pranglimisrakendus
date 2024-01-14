<?php

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

function  nameExists($conn, $name, $email) {
    $sql = "SELECT * FROM users WHERE usersName = ? OR usersEmail = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'Error';
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss",  $name, $email);
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
    echo 'Success';
    exit();
};

//Login functions
function emptyLogin($name, $famname, $pwd) {
    return empty($name) || empty($famname) || empty($pwd);
};

function loginUser($conn, $name, $famname, $pwd){
    $nameExists = nameExists($conn, $name, $email);

    if ($nameExists === false){
        //add more inf
        exit();
    }

    $pwdHashed = $nameExists["usersPwd"];
    $checkPwd = password_verify($pwd, $pwdHashed);

    if ($checkPwd === false) {
        # code...
    }
}