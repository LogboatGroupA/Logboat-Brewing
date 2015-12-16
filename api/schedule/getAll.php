<?php

require '../init.php';
require '../tools.php';

$dateStart = $_GET['start'] . " 00:00:00";
$dateEnd = $_GET['end'] . " 23:59:59";

$returnJSON = array();;

$events = Database::runQuery(
	"SELECT
		brew.id AS brewId,
		brew.brewStart,
		brew.brewEnd,
		brew.quantity,
		brew.beerid,
		brew.userid,
		beer.name AS beerName,
		u.username as username,
		u.id as userId
	FROM
		brew
	LEFT OUTER JOIN
		beer ON beerid = beer.id
	LEFT OUTER JOIN
		`user` AS u ON userid = u.id
	WHERE :start1 BETWEEN brewStart AND brewEnd
	OR	:end1 BETWEEN brewStart AND brewEnd
	OR brewStart BETWEEN :start2 AND :end2
	OR brewEnd BETWEEN :start3 AND :end3"
, array(    "start1" => $dateStart,
						"start2" => $dateStart,
						"start3" => $dateStart,
						"end1" => $dateEnd,
						"end2" => $dateEnd,
						"end3" => $dateEnd)
);

foreach($events as $event) {
		$returnJSON[] = array(  'title'     => $event['beerName'] . " - " .$event['brewId'] . " - " . $event['username'],
														'id'				=> $event['brewId'],
														'start'     => $event['brewStart'],
														'end'       => $event['brewEnd'],
														'editable'  => ($event['userid'] == $_SESSION['userId'] ? true : false),
														'color'			=> ($event['userid'] == $_SESSION['userId'] ? "#337AB7" : "#7BA9D0"));
}

echo json_encode($returnJSON);