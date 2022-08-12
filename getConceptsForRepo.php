<?php
require_once "pdo.php";

//! This file will get all the concepts for a course
session_start();
// $iid = 1;
// $currentclass_id = 44;

     $json = file_get_contents("php://input"); // json string

    $object = json_decode($json); // php object
    if ($object){
            $iid = $object->iid;  // pulls the iid value out of the key value 
            $course = $object->course;  
    }

        $sql = "SELECT Concept.concept_name as concept_name FROM Concept 
        LEFT JOIN CourseConceptConnect ON Concept.concept_id = CourseConceptConnect.concept_id
        LEFT JOIN Course ON CourseConceptConnect.course_id = Course.course_id
        WHERE  Course.course_name = :course_name";

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ':course_name'	=> $course,
            ));
			$concepts = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
		 echo json_encode($concepts);
	
 ?>





