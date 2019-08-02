<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['iid'])){
		

			$stmt = "SELECT name
			FROM CurrentClass
			WHERE iid ='". $_POST['iid']."'"; 
			$stmt = $pdo->prepare($stmt);	
			$stmt->execute();
			$cclass = $stmt->fetchAll(PDO::FETCH_NUM);
		
		 echo json_encode($cclass);
	}
 ?>





