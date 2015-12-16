<?php

require '../init.php';
require '../tools.php';

if(!isLoggedIn()) {
    fail("Only logged in users can update kegs");
}

$id = htmlspecialchars($_POST['beerId']);
$customerId = htmlspecialchars($_POST['customerId']);

$query = 'UPDATE kegorder SET beerId=? customerId= ? WHERE id=?';

$stmt = $link->prepare($query);
    
$stmt->bind_param("", $serialNum, $id);

if($stmt->execute()) {
    success();
} else {
    fail("Error: " . $stmt->error);
}
