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
            $question_id = $object->question_id; 
    }
        // if (!isset($iid)| !is_numeric($iid)){
        //      $_SESSION['error'] = ' iid not sent to get data file getProblems...'.$iid;
        //     die();
        // }

        // $sql = "SELECT currentclass_id  FROM CurrentClass         
        // WHERE  `name` = :course";
		// 	$stmt = $pdo->prepare($sql);	
		// 	$stmt->execute(array(
        //         ':course'	=> $course,
        //     ));
		// 	$currentclass_id = $stmt->fetchColumn();



        $sql = "SELECT * FROM Question WHERE  course = :course AND `question_id`=:question_id ";

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ':course'	=> $course,
                ':question_id'	=> $question_id
               
            ));
			$question = $stmt->fetch(PDO::FETCH_ASSOC);




			
		 echo json_encode($question);
	
 ?>





