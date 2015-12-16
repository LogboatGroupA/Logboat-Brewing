<?php

require '../init.php';
require '../tools.php';

if(!isLoggedIn()) {
    fail("Only logged in users can get users");
}

$userId = htmlspecialchars($_GET['userId']);

$query = 
    "SELECT brew.id,
            brewStart,
            brewEnd,
            beerId,
            beer.name AS beerName
    FROM
        (SELECT * FROM brew
        WHERE userId = :userId)
        AS brew
    INNER JOIN beer
        ON brew.beerId = beer.id";

$bind_params = array("userId" => $userId);

$data = Database::runQuery($query, $bind_params);
$data = enforceEmptyArray($data);

success($data);