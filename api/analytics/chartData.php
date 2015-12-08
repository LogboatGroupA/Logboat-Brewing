<?php

require '../init.php';
require '../tools.php';

$data = Database::runQuery("SELECT value, dateTime, typeId FROM fermentation WHERE brewId = :brewId ORDER BY typeId, dateTime", array('brewId' => $_GET['brewId']));

$return['ph'][0] = array(
  label => "PH",
  strokeColor => "#F16220",
  pointColor => "#F16220", 
  pointStrokeColor => " #fff"
);

$return['gravity'][0] = array (
      label => "Gravity",
      strokeColor => "#F16220",
      pointColor => "#F16220", 
      pointStrokeColor => " #fff"
);

foreach($data as $datapoint) {
  if($datapoint["typeId"] == 1) {
    $return["ph"][0]['data'][] = array("x" => $datapoint['dateTime'], "y" => $datapoint['value']);
  } elseif($datapoint['typeId'] == 11) {
    $return['gravity'][0]['data'][] = array("x" => $datapoint['dateTime'], "y" => $datapoint['value']);
  }
}

echo json_encode($return);