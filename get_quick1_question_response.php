<?php
require_once "pdo.php";

//! This file will get all the initial responses from students for a particular course and question
session_start();
// $iid = 1;
 // $currentclass_id = 44;
 // $question_id = 80;

$course = "Testing Problems";

     $json = file_get_contents("php://input"); // json string

    $object = json_decode($json); // php object
    if ($object){
            $iid = $object->iid;  // pulls the iid value out of the key value 
            $course = $object->course;  
            $question_id = $object->question_id;  
          
    }

        $sql = "SELECT currentclass_id  FROM CurrentClass         
        WHERE  `name` = :course";
			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ':course'	=> $course,
            ));
			$currentclass_id = $stmt->fetchColumn();


        $sql = "SELECT try_number,response_st, COUNT(*) AS count  FROM QuickQuestionActivity         
        WHERE  currentclass_id = :currentclass_id AND question_id = :question_id AND  created_at  >= NOW() - INTERVAL 48 HOUR
        GROUP BY try_number, response_st ORDER BY try_number, response_st
        ";
			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ':currentclass_id'	=> $currentclass_id,
                ':question_id'	=> $question_id,
                
            ));
			$responses = $stmt->fetchAll(PDO::FETCH_ASSOC);


            

		 echo json_encode($responses);
	
 ?>





