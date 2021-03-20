<?php
require_once "pdo.php";
session_start();

// THis is called by several files includding QRExam, to find out the current globalphase fromn the master timer
 
 if(isset($_POST['eexamnow_id'])){
     
     $eexamnow_id = $_POST['eexamnow_id'];
 } else{
     
    $_SESSION['error'] = 'not getting post in fetchGPhase.php';
 }
 
 
	$resp_arr = [];
 
    $stmt = $pdo->prepare("SELECT `globephase` FROM `Eexamnow` WHERE eexamnow_id = :eexamnow_id");
	 $stmt->execute(array(":eexamnow_id" => $eexamnow_id ));
 
 
	 //$stmt->execute(array(":problem_id" => 256, ":dex" => 8 ));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
		if ( $row === false ) {
		//	$_SESSION['error'] = 'could not read row of examtime values';
	
		}
// echo ('globephase'.$row['globephase']); 
     
	if (!empty($row['globephase'])){$resp_arr['globephase'] = $row['globephase']; }
//	if (!empty($row['end_of_phase'])){$resp_arr['end_of_phase'] = $row['end_of_phase']; }
	
	 echo json_encode($resp_arr);
	
	// echo json_encode($row);
	
?>	

