<?php

require '../init.php';
require '../tools.php';

try {
    $data = Database::runQuery("INSERT INTO keg (serialNum) VALUES (:serialNum)", array("serialNum" => $_POST['serialNum']));
    $kegId = Database::runQuery("SELECT id FROM keg WHERE serialNum = :serialNum", array("serialNum" => $_POST['serialNum']));
    $name = Database::runQuery("INSERT INTO kegorder (customerId,userId,kegId) VALUES(31,:userId,:kegId)",array("userId" => $_SESSION['userId'], "kegId" => $_POST['kegId']));
    success();
} catch (PDOException $e) {
    fail("Error in api/update.php: " . $e->getMessage());
}
