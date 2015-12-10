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

function redirect($relativePathFromBaseUrl = '') {
    header("Location: " . getBaseUrl() . $relativePathFromBaseUrl);
    exit();
}

function getBaseUrl() {
    //return "https://cs3380-jam9rd.cloudapp.net/LogboatBrewing/";    //Jacob
    return "https://logboat-brewing-percyodi.c9.io/Logboat-Brewing/"; //Pearse
    //return "https://cs3380-dpshd7.cloudapp.net/LogboatBrewing/"; //Devun
    //Seth
    //Peter
    //return "https://cs3380-dpshd7.cloudapp.net/Logboat-Brewing/";
    //return "https://logboat.cloudapp.net/";                       //Master VM
}