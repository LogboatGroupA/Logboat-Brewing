<?php

require '../init.php';
require '../tools.php';

if(!isLoggedIn()) {
    fail("Only logged in users can create beer recipes");
}

//Beer attributes
$name = htmlspecialchars($_POST['name']);
$beerTypeId = htmlspecialchars($_POST['beerTypeId']);
$createdBy = htmlspecialchars($_SESSION['userId']);

$ingredients = getArrayForMultiInputKey("ingredient");
$quantities = getArrayForMultiInputKey("quantity");

$query = 'INSERT INTO beer VALUES (DEFAULT, ?, ?, ?)';
$stmt = $link->prepare($query);

$stmt->bind_param("sdd", $name, $createdBy, $beerTypeId);

if($stmt->execute()) {
   
    $beerId = $link->insert_id;
    
    $query = 'INSERT INTO beerUsesIngredient VALUES (DEFAULT, ?, ?, ?)';
    $stmt = $link->prepare($query);
    
    $stmt->bind_param("ddd", $beerId, $ingredient, $quantity);
    
    for($i = 0; $i < count($ingredients); $i++) {
        $ingredient = $ingredients[$i];
        $quantity = $quantities[$i];
        
        if(!$stmt->execute()) {
            fail("Error adding ingredients in beer/create.php");
        }
    }
    
    success();
}

fail($stmt->error);