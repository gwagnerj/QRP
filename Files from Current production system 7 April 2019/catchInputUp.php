<?php
require_once "pdo.php";

	// reserve the values in Input table for the problem so all subsequent edits will be updates the other values will initialize to null in sql
		for ($j=1; $j<=50;$j++){	
			
			for ($i = 1; $i <= 200; $i++) {
					$sql = "INSERT INTO Input (problem_id, dex)	
						VALUES (:problem_id, :dex)";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
						':problem_id'=> $j,
						':dex' => $i));
			}		
		}				
?>