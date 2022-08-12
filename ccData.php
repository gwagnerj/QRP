<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['course'])){
	
	$_SESSION['course'] = $_POST['course'];
	//  $stmt = "SELECT Concept.concept_name 
	  $stmt = "SELECT Concept.concept_name, Concept.concept_id
	FROM Course JOIN CourseConceptConnect JOIN Concept

	ON CourseConceptConnect.course_id = Course.course_id AND CourseConceptConnect.concept_id = Concept.concept_id
	
	WHERE Course.course_name ="."'".$_POST['course']."'ORDER BY CourseConceptConnect.relative_order, Concept.concept_name";
		$stmt = $pdo->prepare($stmt);	
		$stmt->execute();
		$course = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		 echo json_encode($course);
	}
	//   $stmt = "SELECT Concept.concept_name, Concept.concept_id
	// FROM Course JOIN CourseConceptConnect JOIN Concept

	// ON CourseConceptConnect.course_id = Course.course_id AND CourseConceptConnect.concept_id = Concept.concept_id
	
	// WHERE Course.course_name ="."'".$_POST['course']."'ORDER BY Concept.concept_name";
	// 	$stmt = $pdo->prepare($stmt);	
	// 	$stmt->execute();
	// 	$course = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
	// 	 echo json_encode($course);
	// }
 ?>





