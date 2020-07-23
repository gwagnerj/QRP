<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['assigntime_id'])){
			$assigntime_id = $_POST['assigntime_id'];

			$stmt = "SELECT window_opens, due_date, window_closes
			FROM Assigntime 
			WHERE assigntime_id ='".$assigntime_id."'"; 
			$stmt = $pdo->prepare($stmt);	
			$stmt->execute();
			$dates = $stmt->fetch(PDO::FETCH_NUM);
			
		 echo json_encode($dates);
	}
 ?>





