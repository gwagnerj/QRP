<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['currentclass_id']) && isset($_POST['exam_num']) ){
		$currentclass_id = $_POST['currentclass_id'];
		$exam_num = $_POST['exam_num'];
		
			$stmt = "SELECT alias_num
			FROM Exam
			WHERE currentclass_id ='".$currentclass_id."' AND exam_num ='".$exam_num."' ORDER BY alias_num" ; 
			
			$stmt = $pdo->prepare($stmt);	
			$stmt->execute();
			$activealiasexam = $stmt->fetchAll(PDO::FETCH_NUM);
		//$activealias = 5;
	//	echo $activealias;
		 echo json_encode($activealiasexam);
	}
	
 ?>





