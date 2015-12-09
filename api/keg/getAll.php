<?php

require '../init.php';
require '../tools.php';

$query = 
    "SELECT 
        keg.id as kegId,
        keg.serialNum,
        kegOrder.id as kegOrderId,
        kegOrder.customerId as customerId,
        kegOrder.brewId as brewId,
        beer.name as beerName,
        customer.firstName as customerFirstName,
        customer.lastName as customerLastName
    FROM keg
    LEFT OUTER JOIN 
        (SELECT * FROM kegOrder WHERE returned = 0) AS kegOrder
        ON kegOrder.kegId = keg.id
    LEFT OUTER JOIN customer ON kegOrder.customerId = customer.id
    LEFT OUTER JOIN brew ON kegOrder.brewId = brew.id
    LEFT OUTER JOIN beer ON brew.beerId = beer.id
    GROUP BY keg.id";

if(($data = Database::runQuery($query))) {
    success($data);
}

fail("Error in keg/getAll.php");