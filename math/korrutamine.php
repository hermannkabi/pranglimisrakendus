<?php
session_start();
$_SESSION['level'] = 1;
// Initial value for score and level
if(!isset($_SESSION['score'])  && !isset($_SESSION['level'])){
    $_SESSION['score'] = 0;
    $_SESSION['level'] = 1;
}
