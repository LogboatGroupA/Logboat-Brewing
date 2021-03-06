<?php

require '../init.php';
require '../tools.php';

try {
    $conn = Database::getConn();
    $data = Database::runQuery("INSERT INTO keg (serialNum) VALUES (:serialNum)", array("serialNum" => $_POST['serialNum']), $conn);
    // attempted to insert keg with no user
    $kegId = Database::runQuery("SELECT id FROM keg WHERE serialNum = :serialNum", array("serialNum" => $_POST['serialNum']), $conn);
    $theKeg = $kegId[0];
    $name = Database::runQuery("INSERT INTO kegorder (customerId,userId,kegId) VALUES(:customerId,:userId,:kegId)",array("customerId" => 31, "userId" => $_SESSION['userId'], "kegId" => $theKeg['id']), $conn);
    success();
} catch (PDOException $e) {
    fail("Error in api/update.php: " . $e->getMessage());
}
