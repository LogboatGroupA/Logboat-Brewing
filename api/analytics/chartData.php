<?php 

$chartHeaders = Database::runQuery("SELECT dateTime FROM fermentation WHERE brewId = :brewId", array('brewId',$_POST['brewId']));
$data = Database::runQuery("SELECT value FROM fermentation WHERE brewId = :brewId", array('brewId',$_POST['brewId']));
$return['success'] = true;
$return['chartData'] = array(
  "labels" => $chartHeaders,
  "datasets" => array( array(
     'label' => "Fermentation Data", //add beer name - brewid here
     'fillColor' => "rgba(220,220,220,0.2)",
    'strokeColor' => "rgba(220,220,220,1)",
    'pointColor' => "rgba(220,220,220,1)",
    'pointStrokeColor' =>"#fff",
    'pointHighlightFill' => "#fff",
    'pointHighlightStroke' =>"rgba(220,220,220,1)",
    'data' => $data
  ))
);

echo json_encode($return);