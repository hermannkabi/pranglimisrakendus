<?php

if(isset($_POST["registration"])){
    $nimi = $_POST("name");
    $perenimi = $_POST("famname");
    $parool = $_POST("pwd");


    require_once "database.php";
    require_once "functions.php";

    if (emptyLogin($nimi, $perenimi, $parool) !== false){
        //add inf
        exit();
    }

    loginUser($conn, $nimi, $perenimi, $parool);
} else {
    //add inf
    exit();
}