<?php
require_once "pdo.php";
session_start();

// THis is called by several files includding QRgamePblmPlan, QRGameGetIn.php, and QRGamePblemPost to find out the current phase fromn the master timer
 
 
	$resp_arr = [];
 
	$stmt = $pdo->prepare("SELECT * FROM `Gmact` WHERE gmact_id = :gmact_id");
	 $stmt->execute(array(":gmact_id" => $_POST['gmact_id'] ));
	 //$stmt->execute(array(":problem_id" => 256, ":dex" => 8 ));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
		if ( $row === false ) {
			$_SESSION['error'] = 'could not read row of table game values';
	
		}	
	if (!empty($row['phase'])){$resp_arr['phase'] = $row['phase']; }
	if (!empty($row['end_of_phase'])){$resp_arr['end_of_phase'] = $row['end_of_phase']; }
	
	 echo json_encode($resp_arr);
	
	// echo json_encode($row);
	
?>	

