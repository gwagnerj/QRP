<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['currentclass_id'])){
			$currentclass_id = $_POST['currentclass_id'];

			$stmt = "SELECT DISTINCT exam_code
			FROM Examactivity 
			WHERE currentclass_id ='".$currentclass_id."' ORDER BY created_at DESC"; 
			$stmt = $pdo->prepare($stmt);	
			$stmt->execute();
			$oldexam = $stmt->fetchAll(PDO::FETCH_NUM);
			
		 echo json_encode($oldexam);
	}
 ?>





