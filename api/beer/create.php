<?php

require '../init.php';
require '../tools.php';

if(!isLoggedIn()) {
    fail("Only logged in users can create beer recipes");
}

$name = htmlspecialchars($_POST['name']);
$createdBy = $_SESSION['userId'];
$beerTypeId = $_POST['beerTypeId'];

$query = 'INSERT INTO beer VALUES (DEFAULT, ?, ?, ?)';

$stmt = $link->prepare($query);

$stmt->bind_param("sdd", $name, $createdBy, $beerTypeId);

if($stmt->execute()) {
    success();
}

fail($stmt->error);