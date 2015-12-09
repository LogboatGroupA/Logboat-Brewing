<?php

require '../init.php';
require '../tools.php';

if(!isLoggedIn()) {
    fail("Only logged in users can get keg orders");
}

$userId = htmlspecialchars($_GET['userId']);

$query = 
    "SELECT kegOrder.id, 
            kegOrder.returned,
            kegOrder.kegId,
            keg.serialNum, 
            customerId,
            customer.firstName,
            customer.lastName,
            kegOrder.brewId
    FROM
        (SELECT * FROM kegOrder
        WHERE userId = :userId)
        AS kegOrder
    INNER JOIN keg
        ON kegOrder.kegId = keg.id
    INNER JOIN customer
        ON kegOrder.customerId = customer.id";

$bind_params = array("userId" => $userId);

if(($data = Database::runQuery($query, $bind_params))) {
    success($data);
}

fail("Error in brew/getBrewsForUser.php");