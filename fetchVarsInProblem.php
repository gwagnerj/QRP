<?php
require_once "pdo.php";
session_start();

// THis is called by NumerictoMC.php to find out how many parts are in the problem using the qa table
 
	$resp_arr = [];
	
 //$_POST['problem_id']= 238; //temp
 
	$stmt = $pdo->prepare("SELECT *	FROM `Problem` where problem_id = :problem_id");
	 $stmt->execute(array(":problem_id" => $_POST['problem_id'] ));
	 //$stmt->execute(array(":problem_id" => 256 ));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
		if ( $row === false ) {
			$_SESSION['error'] = 'could not read row of table qa values';
	
		}	
	// replace this with foreach loop later
	if (!empty($row['nv_1'])){$resp_arr['nv_1'] = $row['nv_1']; }
	if (!empty($row['nv_2'])){$resp_arr['nv_2'] = $row['nv_2']; }
	if (!empty($row['nv_3'])){$resp_arr['nv_3'] = $row['nv_3']; }
	if (!empty($row['nv_4'])){$resp_arr['nv_4'] = $row['nv_4']; }
	if (!empty($row['nv_5'])){$resp_arr['nv_5'] = $row['nv_5']; }
	if (!empty($row['nv_6'])){$resp_arr['nv_6'] = $row['nv_6']; }
	if (!empty($row['nv_7'])){$resp_arr['nv_7'] = $row['nv_7']; }
	if (!empty($row['nv_8'])){$resp_arr['nv_8'] = $row['nv_8']; }
	if (!empty($row['nv_9'])){$resp_arr['nv_9'] = $row['nv_9']; }
	if (!empty($row['nv_10'])){$resp_arr['nv_10'] = $row['nv_10']; }
	if (!empty($row['nv_11'])){$resp_arr['nv_11'] = $row['nv_11']; }
	if (!empty($row['nv_12'])){$resp_arr['nv_12'] = $row['nv_12']; }
	if (!empty($row['nv_13'])){$resp_arr['nv_13'] = $row['nv_13']; }
	if (!empty($row['nv_14'])){$resp_arr['nv_14'] = $row['nv_14']; }
	
	
	 echo json_encode($resp_arr);
	
	
?>	

