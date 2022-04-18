<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['currentclass_id']) && isset($_POST['open_window_d'] ) && isset($_POST['close_window_d'] ) && isset($_POST['role'] ) ){
		$currentclass_id = $_POST['currentclass_id'];
		$open_window_d = $_POST['open_window_d'];
		$close_window_d = $_POST['close_window_d'];
		$role = $_POST['role'] ;

		if ($role == "author"){
		
		$stmt = "SELECT * FROM QuestionWombActivity 
					JOIN QuestionWomb ON QuestionWombActivity.questionwomb_id = QuestionWomb.questionwomb_id
				JOIN StudentCurrentClassConnect ON StudentCurrentClassConnect.student_id = QuestionWombActivity.student_id 
				
				JOIN Student ON Student.student_id = QuestionWombActivity.student_id
				WHERE StudentCurrentClassConnect.currentclass_id = :currentclass_id 
				AND Student.student_id = QuestionWomb.student_id
				AND QuestionWomb.updated_at BETWEEN :open_window_d AND :close_window_d 
				
				ORDER BY QuestionWombActivity.student_id,QuestionWombActivity.activity,QuestionWomb.num_accept DESC,QuestionWomb.questionwomb_id " ; 

		} else if ($role == "reviewer"){
			$stmt = "SELECT * FROM QuestionWombActivity 
					JOIN QuestionWomb ON QuestionWombActivity.questionwomb_id = QuestionWomb.questionwomb_id
				JOIN StudentCurrentClassConnect ON StudentCurrentClassConnect.student_id = QuestionWombActivity.student_id 
				
				JOIN Student ON Student.student_id = QuestionWombActivity.student_id
				WHERE StudentCurrentClassConnect.currentclass_id = :currentclass_id 
				AND Student.student_id != QuestionWomb.student_id
				AND QuestionWomb.updated_at BETWEEN :open_window_d AND :close_window_d 
				
				ORDER BY QuestionWombActivity.student_id,QuestionWombActivity.activity,QuestionWomb.num_accept DESC,QuestionWomb.questionwomb_id " ; 

		} else {
			$stmt = "SELECT * FROM QuestionWombActivity 
					JOIN QuestionWomb ON QuestionWombActivity.questionwomb_id = QuestionWomb.questionwomb_id
				JOIN StudentCurrentClassConnect ON StudentCurrentClassConnect.student_id = QuestionWombActivity.student_id 
				
				JOIN Student ON Student.student_id = QuestionWombActivity.student_id
				WHERE StudentCurrentClassConnect.currentclass_id = :currentclass_id 
				AND QuestionWomb.updated_at BETWEEN :open_window_d AND :close_window_d 
				
				ORDER BY QuestionWombActivity.student_id,QuestionWombActivity.activity,QuestionWomb.num_accept DESC,QuestionWomb.questionwomb_id " ; 

		}
			
			$stmt = $pdo->prepare($stmt);	
			$stmt->execute(array(
				":currentclass_id" => $currentclass_id,
				":open_window_d" => $open_window_d,
				":close_window_d" => $close_window_d,
		
		));
			$student_ids = $stmt->fetchALL(PDO::FETCH_ASSOC);
		 echo json_encode($student_ids);
	} else {
	echo ('error');
	}
 ?>





