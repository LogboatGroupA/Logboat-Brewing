<?php

require '../init.php';
require '../tools.php';

if(!isLoggedIn()) {
    fail("Only logged in users can get recipes");
}

$beerId = htmlspecialchars($_GET['beerId']);

$query =
    "SELECT 
        beer.id,
        beer.name,
        beer.beerTypeId,
        beer.beerType,
        beer.createdBy,
        user.username
    FROM
        (SELECT
            beer.id,
            beer.name,
            beer.beerTypeId,
            beerType.name as beerType,
            beer.createdBy
        FROM beer INNER JOIN beerType
            ON beer.beerTypeId = beerType.id)
        AS beer
    INNER JOIN user
        ON beer.createdBy = user.id
    WHERE beer.id= :id
    LIMIT 1";

$result = array();

$bind_params = array("id" => $beerId);

if(($data = Database::runQuery($query, $bind_params))) {
    $result = $data[0];
    
    $ingQuery =
        "SELECT
            bui.quantity,
            ing.name,
            unit.name AS units
        FROM
            (SELECT * FROM beerUsesIngredient
                WHERE beerId= :id)
            AS bui
        INNER JOIN ingredient AS ing
            ON bui.ingredientId = ing.id
        INNER JOIN unit
            ON ing.unitId = unit.id
        ORDER BY ing.name";

    $data = Database::runQuery($ingQuery, $bind_params);
            
    $result['ingredients'] = enforceEmptyArray($data);
    success($result);
}
      
fail("Error in beer/getDetail.php");