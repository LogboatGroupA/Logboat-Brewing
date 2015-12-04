<?php

require '../init.php';
require '../tools.php';

$dateTime = new DateTime($_POST['dateTime']);
$dateTime = $dateTime->format("Y-m-d H:i:s");
try {
    $data = Database::runQuery("INSERT INTO fermentation(value, dateTime, typeId, unitId, brewId, userId)
                                VALUES (:value, :dateTime, :typeId, :unitId, :brewId, :userId)"
                                , array(
                                    "value" => (double) $_POST['value'],
                                    "dateTime" => $dateTime,
                                    "typeId" => (int) $_POST['quantity'],
                                    "unitId" => (int) $_POST['unitId'],
                                    "brewId" => (int) $_POST['beerId'],
                                    "userId" => $_SESSION['userId'])
                                );
    if($data) {
        success();
    } else {
        fail("Error in api/analytics/addAnalytics.php: $data not valid");
    }
} catch (PDOException $e) {
    fail("Error in api/analytics/addAnalytics.php: " . $e->getMessage());
}
fail("Testing Fail");
?>