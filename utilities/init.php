<?php

if ($_SERVER['SERVER_PORT'] != 443) {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

session_start();

function isLoggedIn() {
    return isset($_SESSION['userId']);
}

function isUserAdmin() {
    return isLoggedIn() && $_SESSION['isAdmin'];
}

function getBaseUrl() {
    //return "https://cs3380-jam9rd.cloudapp.net/LogboatBrewing/";    //Jacob
    //return "https://logboat-brewing-percyodi.c9.io/Logboat-Brewing/"; //Pearse
    //Devun
    //Seth
    //Peter
    return "https://logboat.cloudapp.net/";                       //Master VM
}