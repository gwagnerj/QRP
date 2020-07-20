<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['currentclass_id'])){
			$currentclass_id = $_POST['currentclass_id'];

			$stmt = "SELECT DISTINCT assign_num
			FROM Assign 
			WHERE currentclass_id ='".$currentclass_id."' ORDER BY assign_num DESC"; 
			$stmt = $pdo->prepare($stmt);	
			$stmt->execute();
			$activeass = $stmt->fetchAll(PDO::FETCH_NUM);
			
		 echo json_encode($activeass);
	}
 ?>





