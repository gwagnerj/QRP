<?php
require_once "pdo.php";
session_start();

// THis is called by QRGameGetIn.php to find out how log the students have to solve the problem
 
 
	$resp_arr = [];
 
	$stmt = $pdo->prepare("SELECT * FROM `Game` WHERE game_id = :game_id");
	 $stmt->execute(array(":game_id" => $_POST['game_id'] ));
	 //$stmt->execute(array(":problem_id" => 256, ":dex" => 8 ));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
		if ( $row === false ) {
			$_SESSION['error'] = 'could not read row of table game values';
	
		}	
	if (!empty($row['work_time'])){$resp_arr['work_time'] = $row['work_time']; }
	if (!empty($row['prep_time'])){$resp_arr['prep_time'] = $row['prep_time']; }
	if (!empty($row['nv_3'])){$resp_arr['post_time'] = $row['post_time']; }
	

	
	 echo json_encode($resp_arr);
	
	// echo json_encode($row);
	
?>	

