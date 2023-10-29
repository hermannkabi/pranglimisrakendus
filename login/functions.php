<?php

function emptyInputSignup($name, $email, $class, $pwd, $pwdRepeat, $result) {
    
    if (empty($name) || empty($email) || empty($class) || empty($pwd) || empty($pwdRepeat)) {
        $result = true;
    } else {
        $result = false;
    };
    return $result;
};

function invalidname($name, $result) {
    if (!preg_match("/^[a-zA-ZõäöüÕÄÖÜ]*$/", $name)) {
        $result = true;
    } else {
        $result = false;
    };
    return $result;
};

function invalidemail($email, $result) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    } else {
        $result = false;
    };
    return $result;
};

function  pwdMatch($pwd,  $pwdRepeat, $result) {
    if ($pwd !== $pwdRepeat) {
        $result = true;
    } else {
        $result = false;
    };
    return $result;
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

function  createUser($conn, $name, $email, $class, $pwd) {
    $sql = "INSERT INTO users (usersName, usersClass, usersEmail, usersPwd) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'Error';
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ssss",  $name, $class, $email, $hashedPwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    echo 'Success';
    exit();
};