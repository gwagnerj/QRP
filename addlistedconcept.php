<?php
require_once "pdo.php";
session_start();
// this is to add a concept that already exists into a course concept table.  This is called by inputConcept.php

if(isset($_GET['concept_id'])){
// no need to confirm just delete it
	$concept_id = $_GET['concept_id'];
					

			$stmt = "SELECT Course.course_id FROM Course WHERE Course.course_name ="."'".$_SESSION['course']."'";
			$stmt = $pdo->query($stmt);
			$coursess = $stmt->fetchALL(PDO::FETCH_ASSOC);  // this is an array of arrays ugh
			
			if ($coursess != false) {
			$courses = $coursess[0];
			$course_id = $courses['course_id'];
			} else {
			$_SESSION['error'] = 'course Id could not be read in addlistedconcept - Concept was not added to course';
			
			header( 'Location: QRPRepo.php' ) ;
			return;
		}
		$sql = "INSERT INTO CourseConceptConnect (course_id, concept_id)
							VALUES (:course_id, :concept_id)";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
					':course_id' => $course_id,
					':concept_id' => $concept_id
					));
					
					 $_SESSION['sucess'] = 'the concept was added to database';
					 
					 
					 header( 'Location: inputConcept.php' ) ;
					 return; 
	
	


		} else {
	
	$_SESSION['error'] = 'Something Went Wrong - concept_id was not accepted - concept was not added to course';
	/* 
	echo 'wtf2';
	 die();
	 */
	header( 'Location: inputConcept.php' ) ;
    return;
	
}


