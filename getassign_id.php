<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['currentclass_id']) && isset($_POST['assign_num']) ){
		$currentclass_id = $_POST['currentclass_id'];
		$assign_num = $_POST['assign_num'];
		
			$stmt = "SELECT assign_id
			FROM Assign
			WHERE currentclass_id ='".$currentclass_id."' AND assign_num ='".$assign_num."' ORDER BY alias_num" ; 
			
			$stmt = $pdo->prepare($stmt);	
			$stmt->execute();
			$assignids = $stmt->fetchAll(PDO::FETCH_NUM);
		//$activealias = 5;
	//	echo $activealias;
		 echo json_encode($assignids);
	}
	
 ?>





