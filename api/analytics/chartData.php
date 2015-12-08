<?php

require '../init.php';
require '../tools.php';

$data = Database::runQuery(
  "SELECT 
    f.value, 
    f.dateTime, 
    f.typeId,
    brew.brewStart,
    DATEDIFF(brew.brewEnd, brew.brewStart) AS brewLength
  FROM fermentation AS f
  LEFT OUTER JOIN brew AS brew ON f.brewId = brew.id
  WHERE brewId = :brewId
  ORDER BY f.typeId, f.dateTime"
  , array('brewId' => $_GET['brewId']));

foreach($data as $datapoint) {
  if($datapoint["typeId"] == 1) {
    $return["ph"][] = array("x" => $datapoint['dateTime'], "y" => $datapoint['value']);
  } elseif($datapoint['typeId'] == 11) {
    $return['gravity'][] = array("x" => $datapoint['dateTime'], "y" => $datapoint['value']);
  }
}

$return['brewLength'] = $data[0]['brewLength'];
$return['brewStart'] = $data[0]['brewStart'];

echo json_encode($return);