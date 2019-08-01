<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['currentclass_id'])){
		

			$stmt = "SELECT *
			FROM CurrentClass
			WHERE currentclass_id ="."'". $_POST['currentclass_id']."'"; 
			$stmt = $pdo->prepare($stmt);	
			$stmt->execute();
			$section = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		 echo json_encode($section);
	}
 ?>





