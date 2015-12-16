<?php

require '../init.php';
require '../tools.php';

if(!isUserAdmin()) {
    fail("Only admins can create user accounts");
}

$username = htmlspecialchars($_POST['username']);

$query = 'INSERT INTO user VALUES (DEFAULT, ?, ?, DEFAULT, DEFAULT)';

if(($stmt = $link->prepare($query))) {
    
    $tempPass = randomString(10);
    $hashedPassword = password_hash($tempPass, PASSWORD_BCRYPT);
    
    $stmt->bind_param("ss", $username, $hashedPassword);
    
    if($stmt->execute()) {
        success($tempPass);
    }
}

fail("Error creating user");