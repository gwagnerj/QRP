<?php
require_once "pdo.php";

//! This file will get all the active questions
session_start();
 $currentclass_id = 44;

     $json = file_get_contents("php://input"); // json string

    $object = json_decode($json); // php object
    if ($object){
             $currentclass_id = $object->currentclass_id;  
    }

$sql = "SELECT DISTINCT Question.question_id AS question_id,
Question.title AS title,
Question.course AS course,
Question.primary_concept AS primary_concept,
Question.grade AS grade,
Question.htmlfilenm AS htmlfilenm,
Question.nm_author AS nm_author,
Question.unpubl_auth AS unpubl_auth,
Question.explanation_filenm AS 	explanation_filenm,
Question.key_a AS key_a,
Question.key_b AS key_b,
Question.key_c AS key_c,
Question.key_d AS key_d,
Question.key_e AS key_e,
Question.key_f AS key_f,
Question.key_g AS key_g,
Question.key_h AS key_h,
Question.key_i AS key_i,
Question.key_j AS key_j,
QuickQuestionActivity.email_flag as email_flag
FROM Question
INNER JOIN QuickQuestionActivity
ON Question.question_id = QuickQuestionActivity.question_id
WHERE   QuickQuestionActivity.currentclass_id = :currentclass_id AND QuickQuestionActivity.expires_at > NOW() 
         ORDER BY Question.question_id DESC";
// $sql = "SELECT DISTINCT Question.question_id AS question_id,
// Question.title AS title,
// Question.course AS course,
// Question.subject AS discipline,
// Question.primary_concept AS primary_concept,
// Question.grade AS grade,
// Question.htmlfilenm AS htmlfilenm,
// Question.nm_author AS nm_author,
// Question.unpubl_auth AS unpubl_auth,
// Question.explanation_filenm AS 	explanation_filenm,
// Question.key_a AS key_a,
// Question.key_b AS key_b,
// Question.key_c AS key_c,
// Question.key_d AS key_d,
// Question.key_e AS key_e,
// Question.key_f AS key_f,
// Question.key_g AS key_g,
// Question.key_h AS key_h,
// Question.key_i AS key_i,
// Question.key_j AS key_j,
// QuickQuestionActivity.email_flag as email_flag
// FROM Question
// INNER JOIN QuickQuestionActivity
// ON Question.question_id = QuickQuestionActivity.question_id
// WHERE   QuickQuestionActivity.currentclass_id = :currentclass_id AND QuickQuestionActivity.expires_at > NOW() 
//          ORDER BY Question.question_id DESC";

    $stmt = $pdo->prepare($sql);	
    $stmt->execute(array(
            ':currentclass_id'	=> $currentclass_id,
    ));
    $active_questions = $stmt->fetchAll(PDO::FETCH_ASSOC);


			
		 echo json_encode($active_questions);
		 
	
 ?>





