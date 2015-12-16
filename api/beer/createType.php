<?php

require '../init.php';
require '../tools.php';

if(!isLoggedIn()) {
    fail("Only logged in users can create new beer types");
}

$name = htmlspecialchars($_POST['name']);

$query = 'INSERT INTO beerType VALUES (DEFAULT, ?)';
$stmt = $link->prepare($query);

$stmt->bind_param("s", $name);

if($stmt->execute()) {
    $newBeerType = array("id" => $link->insert_id, "name" => $name);
    success($newBeerType);
}

fail($stmt->error);