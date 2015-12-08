<?php

require '../init.php';
require '../tools.php';

//Should really just make a Database non-static object option....
$conn = Database::getConn();

$data = Database::runQuery(
  "SELECT 
    value, 
    dateTime, 
    typeId
  FROM fermentation
  WHERE brewId = :brewId
  ORDER BY typeId, dateTime"
  , array('brewId' => (int) $_GET['brewId']), $conn);
  
if(count($data) == 0){
  fail("No Data To Display");
}
  
$brewDates = Database::runQuery("SELECT brewEnd, brewStart FROM brew WHERE id = :brewId", array("brewId" => (int) $_GET['brewId']), $conn);
$brewDates = $brewDates[0]; // Select the only one that should show

//Set min x-axis to first day of brew
// $return["ph"][] = array("x" => $brewDates['brewStart']);
// $return['gravity'][] = array("x" => $brewDates['brewStart'], "y" => null);

//Set datapoints for graph
foreach($data as $datapoint) {
  if($datapoint["typeId"] == 1) {
    $return["ph"][] = array("x" => $datapoint['dateTime'], "y" => $datapoint['value']);
  } elseif($datapoint['typeId'] == 11) {
    $return['gravity'][] = array("x" => $datapoint['dateTime'], "y" => $datapoint['value']);
  }
}

//Set max x-axis to last day of brew
$return["ph"][] = array("x" => $brewDates['brewEnd']);
$return['gravity'][] = array("x" => $brewDates['brewEnd']);

success($return);