<?php
require_once "pdo.php";
// the file is called by QuestionRepo.php via a fetch with the Problem Question Set button.  It fills out the ProblemQuestionConnect table for each problem
// these then can be used in games to ask the questions before the oroblem or in the homework system
// the problem_question_ids is a json object that contains the problem_id and the question_ids



//initializing variables - these change in the program
  $problem_id = 42;

  $problem_question_ids = '{"problem_id":"844","question_1":"262","question_2":"249"}';
  $problem_question_ids = json_decode($problem_question_ids,true);


$json = file_get_contents("php://input"); // json string

    $object = json_decode($json); // php object
    // get the problem_question_ids element out of the $object
     $problem_question_ids_obj = $object->problem_question_ids;
  // extract the key value pairs from the problem_question_ids object
    $problem_question_ids = get_object_vars($problem_question_ids_obj);

if($_SERVER['REQUEST_METHOD'] == 'POST') {

foreach ($problem_question_ids as $param_name => $param_val) { 
  
        if($param_name == 'problem_id'){
          $problem_id = $param_val;

          // make sure the problem exists in the problem table and its status is not num issued if it does not exist send an error message
              $sql = "SELECT `problem_id` FROM Problem WHERE problem_id = :problem_id AND status != 'num issued'";
              $stmt = $pdo->prepare($sql);

              $stmt->execute(array(
                ':problem_id' => $problem_id
              ));
              $row = $stmt->fetch(PDO::FETCH_ASSOC);
              if($row === false){
                echo json_encode('problem_not_found');
                return;
              }

        } else{

              $key = $param_name;
              $question_id = $param_val;
              // insert if it does not already exist
              $sql = "SELECT * FROM ProblemQuestionConnect WHERE problem_id = :problem_id AND question_id = :question_id";
              $stmt = $pdo->prepare($sql);

              $stmt->execute(array(
                ':problem_id' => $problem_id,
                ':question_id' => $question_id
              ));
              $row = $stmt->fetch(PDO::FETCH_ASSOC);
          if($row === false){
              $sql = "INSERT INTO ProblemQuestionConnect (problem_id, question_id) VALUES (:problem_id, :question_id)";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array(
                  ':problem_id' => $problem_id,
                  ':question_id' => $question_id
            ));

        }
      
      }

    }
   
   echo json_encode('success');
} else {

    echo json_encode('fail');
}    



?>