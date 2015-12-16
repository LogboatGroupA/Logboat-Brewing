<?php

require '../init.php';
require '../tools.php';

$name = htmlspecialchars($_POST['name']);
$supplier = htmlspecialchars($_POST['supplier']);
$quantity = $_POST['quantity'];
$unitId = $_POST['unitId'];
$lowValue= $_POST['lowValue'];

//TODO: handle cases for empty supplier / quantity

/* This is the old query. Current schema doesn't support $unitId */
$query = 'INSERT INTO ingredient VALUES (DEFAULT, ?, ?, ?, ?, ?)';


$stmt = $link->prepare($query);

$stmt->bind_param("ssdid", $name, $supplier, $quantity, $lowValue, $unitId);

if($stmt->execute()) {
    success();
} else {
    fail($stmt->error);
}
