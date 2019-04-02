<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['discipline'])){
		
/* 		 	$stmt = "SELECT Course.course_id 
			FROM Discipline JOIN DisciplineCourseConnect JOIN Course
			ON DisciplineCourseConnect.discipline_id = Discipline.discipline_id AND DisciplineCourseConnect.course_id = Course.course_id
			WHERE Discipline.discipline_name ="."'". $_POST['discipline']."' ORDER BY Course.course_name"; 
			$stmt = $pdo->prepare($stmt);	
			$stmt->execute();
			$course2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$_SESSION['course_id'] = $course2['course_id'];  */
	
	
			$stmt = "SELECT Course.course_name 
			FROM Discipline JOIN DisciplineCourseConnect JOIN Course
			ON DisciplineCourseConnect.discipline_id = Discipline.discipline_id AND DisciplineCourseConnect.course_id = Course.course_id

			WHERE Discipline.discipline_name ="."'". $_POST['discipline']."' ORDER BY Course.course_name"; 
			$stmt = $pdo->prepare($stmt);	
			$stmt->execute();
			$course = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		 echo json_encode($course);
	}
 ?>





