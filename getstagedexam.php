<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['currentclass_id'])){
			$currentclass_id = $_POST['currentclass_id'];

			$stmt = "SELECT DISTINCT exam_num
			FROM Eexam 
			WHERE currentclass_id ='".$currentclass_id."' ORDER BY exam_num DESC"; 
			$stmt = $pdo->prepare($stmt);	
			$stmt->execute();
			$activeexam = $stmt->fetchAll(PDO::FETCH_NUM);
			
		 echo json_encode($activeexam);
	}
 ?>





