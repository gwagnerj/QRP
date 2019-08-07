<?php
require_once "pdo.php";
session_start();
// this is to add a concept that already exists into a course concept table.  This is called by inputConcept.php

if(isset($_GET['author_id'])){
// no need to confirm just delete it
	$author_id = $_GET['author_id'];
					

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
		$sql = "INSERT INTO CourseAuthorConnect (course_id, author_id)
							VALUES (:course_id, :author_id)";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
					':course_id' => $course_id,
					':author_id' => $author_id
					));
					
					 $_SESSION['sucess'] = 'the reference was added to database';
					 
					 
					 header( 'Location: requestPblmNum.php' ) ;
					 return; 
	
	


		} else {
	
	$_SESSION['error'] = 'Something Went Wrong - author_id was not accepted - author was not added to course';
	
	header( 'Location: requestPblmNum.php' ) ;
    return;
	
}


