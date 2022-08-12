<?php
require_once "pdo.php";

//! This file will get all the active questions
session_start();
// $iid = 1;
// $currentclass_id = 44;

     $json = file_get_contents("php://input"); // json string

    $object = json_decode($json); // php object
    if ($object){
            $iid = $object->iid;  // pulls the iid value out of the key value 
            $course = $object->course;  
             $discipline_name = $object->discipline_name;  
    }
        // if (!isset($iid)| !is_numeric($iid)){
        //      $_SESSION['error'] = ' iid not sent to get data file getProblems...'.$iid;
        //     die();
        // }

        $sql = "SELECT * FROM Question WHERE  course = :course AND `subject`=:discipline_name  ORDER BY question_id";

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ':course'	=> $course,
                ':discipline_name'	=> $discipline_name
               
            ));
			$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
		 echo json_encode($questions);
	
 ?>





