<?php

require '../init.php';
require '../tools.php';

if(!isLoggedIn()) {
    fail("Only logged in users can update kegs");
}

$id = htmlspecialchars($_POST['brewId']);
$customerId = htmlspecialchars($_POST['customerId']);

// $query = 'UPDATE kegorder SET beerId=? customerId= ? WHERE id=?';

// $stmt = $link->prepare($query);
    
// $stmt->bind_param("", $serialNum, $id);

try {
    $data = Database::runQuery("UPDATE kegorder SET brewId=:brewid, customerId=:customerId WHERE id=:id", array("brewid" => $id, "customerId" => $customerId, "id" => $_POST['kegOrderId']));
    
    if($data) {
        success();
    } else {
        fail("Error!");
    }
} catch (Exception $e) {
    fail("Database Error: " . $e->getMessage());
}
