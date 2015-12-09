<?php

require '../init.php';
require '../tools.php';

$id = htmlspecialchars($_POST['id']);
$name = htmlspecialchars($_POST['name']);
$supplier = htmlspecialchars($_POST['supplier']);
$quantity = $_POST['quantity'];
$unitId = $_POST['unitId'];
$lowValue = $_POST['lowValue'];

$query = 'UPDATE ingredient SET name=?, supplier=?, quantity=?, unitId=?, lowValue=? WHERE id=?';

$stmt = $link->prepare($query);

$stmt->bind_param("ssdidi", $name, $supplier, $quantity, $unitId, $lowValue, $id);

if($stmt->execute()) {
    success();
} else {
    fail($stmt->error);
}
