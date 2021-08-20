<?php
require_once "pdo.php";
	  if (isset($_POST['assigntime_id'])&& $_POST['student_id']){

			$assigntime_id = $_POST['assigntime_id'];
			$student_id = $_POST['student_id'];

            // $assigntime_id = 89;
            // $student_id = 1;
          
            $sql = "SELECT * FROM `Assignscore` WHERE assigntime_id = :assigntime_id AND student_id = :student_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':assigntime_id' => $assigntime_id,
                ':student_id' => $student_id,
                ));        
             $assignscore_data = $stmt -> fetch();
		  echo json_encode( $assignscore_data);
	}
 ?>





