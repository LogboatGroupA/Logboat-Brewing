<?php

require '../init.php';
require '../tools.php';

if(!isLoggedIn()) {
    fail("Only logged in users can get beers");
}

$userId = htmlspecialchars($_GET['userId']);

$query = 
    "SELECT beer.id,
            beer.name, 
            beerType.name AS beerType
    FROM
        (SELECT * FROM beer
        WHERE createdBy = :userId)
        AS beer
    INNER JOIN beerType
        ON beer.beerTypeId = beerType.id";

$bind_params = array("userId" => $userId);

if(($data = Database::runQuery($query, $bind_params))) {
    success($data);
}

fail("Error in brew/getBeersForUser.php");