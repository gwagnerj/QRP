<?php
require_once "pdo.php";

//! This file will get all the active questions
session_start();
$iid = 1;
 $currentclass_id = 44;
 $course = 'Material Balances';
 $discipline_name = "Chemical Engineering";
$other_questions = $active_questions = $users_questions = array();

     $json = file_get_contents("php://input"); // json string

    $object = json_decode($json); // php object
    if ($object){
            $iid = $object->iid;  // pulls the iid value out of the key value 
            $course = $object->course;  
             $discipline_name = $object->discipline_name;  
             $currentclass_id = $object->currentclass_id;  
    }

$sql = "SELECT `first`,`last` FROM Users WHERE `users_id` = :iid";
        $stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ':iid'	=> $iid,
            ));
			$users_names = $stmt->fetch(PDO::FETCH_ASSOC);
            $last = $users_names['last'];
            $first = $users_names['first'];
            $full_name = $first.' '.$last;



//? get the active questions first so they will appear first in the card list in the questionrepo

        // $sql = "SELECT DISTINCT Question.question_id AS question_id,
        //                         Question.title AS title,
        //                         Question.primary_concept AS primary_concept,
        //                         Question.grade AS grade,
        //                         Question.htmlfilenm AS htmlfilenm,
        //                         Question.nm_author AS nm_author,
        //                         Question.unpubl_auth AS unpubl_auth
        //  FROM Question
        // INNER JOIN QuickQuestionActivity
        //  ON Question.question_id = QuickQuestionActivity.question_id
        //  WHERE  Question.course = :course AND Question.subject =:discipline_name 
        //  AND QuickQuestionActivity.currentclass_id = :currentclass_id AND QuickQuestionActivity.expires_at > NOW() 
        //  ORDER BY Question.question_id DESC";
		// 	$stmt = $pdo->prepare($sql);	
		// 	$stmt->execute(array(
        //         ':course'	=> $course,
        //         ':discipline_name'	=> $discipline_name,
        //          ':currentclass_id'	=> $currentclass_id,
        //     ));
		// 	$users_questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = "SELECT DISTINCT Question.question_id AS question_id,
                                Question.title AS title,
                                Question.primary_concept AS primary_concept,
                                Question.grade AS grade,
                                Question.htmlfilenm AS htmlfilenm,
                                Question.nm_author AS nm_author,
                                Question.unpubl_auth AS unpubl_auth
         FROM Question
         WHERE   Question.nm_author = :nm_author AND Question.course = :course
         ORDER BY Question.question_id DESC";
        // $sql = "SELECT DISTINCT Question.question_id AS question_id,
        //                         Question.title AS title,
        //                         Question.primary_concept AS primary_concept,
        //                         Question.grade AS grade,
        //                         Question.htmlfilenm AS htmlfilenm,
        //                         Question.nm_author AS nm_author,
        //                         Question.unpubl_auth AS unpubl_auth
        //  FROM Question
        //  WHERE  Question.course = :course AND Question.subject =:discipline_name AND Question.nm_author = :nm_author
        //  ORDER BY Question.question_id DESC";
			$stmt = $pdo->prepare($sql);	
			// $stmt->execute(array(
            //     ':course'	=> $course,
            //     ':discipline_name'	=> $discipline_name,
            //     ':nm_author'	=> $full_name,
            // ));
			$stmt->execute(array(

                ':nm_author'	=> $full_name,
                ':course'	=> $course,
            ));

			$users_questions = $stmt->fetchAll(PDO::FETCH_ASSOC);



        // $sql = "SELECT DISTINCT Question.question_id AS question_id,
        //                         Question.title AS title,
        //                         Question.primary_concept AS primary_concept,
        //                         Question.grade AS grade,
        //                         Question.htmlfilenm AS htmlfilenm,
        //                         Question.nm_author AS nm_author,
        //                         Question.unpubl_auth AS unpubl_auth
        //  FROM Question
        //  WHERE  Question.course = :course  AND Question.nm_author != :nm_author
        //  ORDER BY Question.question_id DESC";
        $sql = "SELECT DISTINCT Question.question_id AS question_id,
                                Question.title AS title,
                                Question.primary_concept AS primary_concept,
                                Question.grade AS grade,
                                Question.htmlfilenm AS htmlfilenm,
                                Question.nm_author AS nm_author,
                                Question.unpubl_auth AS unpubl_auth
         FROM Question
         WHERE  Question.course = :course  AND Question.nm_author != :nm_author
         ORDER BY Question.question_id DESC";
			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ':course'	=> $course,
                ':nm_author'	=> $full_name,

            ));

			$other_questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // $sql = "SELECT * FROM Question WHERE  course = :course AND `subject`=:discipline_name  ORDER BY question_id DESC";
		// 	$stmt = $pdo->prepare($sql);	
		// 	$stmt->execute(array(
        //         ':course'	=> $course,
        //         ':discipline_name'	=> $discipline_name
        //     ));
		// 	$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //? look for the questions that are active

            // $sql = "SELECT DISTINCT question_id AS QQA_question_id FROM QuickQuestionActivity 
            // WHERE currentclass_id = :currentclass_id AND expires_at > NOW() AND discuss_stage > 0";
            //     $stmt = $pdo->prepare($sql);	
            //     $stmt->execute(array(
            //         ':currentclass_id'	=> $currentclass_id,
            //     ));
            //     $active_questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $result = array_merge($users_questions,$other_questions);
			
		 echo json_encode($result);
		 
	
 ?>





