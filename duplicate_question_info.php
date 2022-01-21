<?php
require_once 'pdo.php';

if (isset($_POST['question_id'])) {

    $question_id = $_POST['question_id'];
  
    // $sql = "SELECT * FROM Question WHERE question_id = :question_id";
    // $stmt = $pdo->prepare($sql);
    //         $stmt->execute(array(
    //         ':question_id' => $question_id
    //         ));
    //         $question_data = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = 'CREATE TEMPORARY TABLE tmptable_1 SELECT * FROM Question WHERE question_id = :question_id;
    UPDATE tmptable_1 SET question_id = NULL; 
    INSERT INTO Question SELECT * FROM tmptable_1; 
    DROP TEMPORARY TABLE IF EXISTS tmptable_1;
  '; 
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':question_id' => $question_id
			));
	
	// get the last inserted row
		$sql = 'SELECT LAST_INSERT_ID()';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
				
		));

		$question_data = $stmt->fetch(PDO::FETCH_ASSOC);

		$new_question_id=$question_data['LAST_INSERT_ID()'];

// look in QuestionProblem Connect for problem_id if any
$sql = 'SELECT * FROM QuestionProblemConnect WHERE question_id = :question_id';
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
  ':question_id' => $question_id
  ));

         $questionproblem_data = $stmt->fetch(PDO::FETCH_ASSOC);
if($questionproblem_data){
    $problem_id = $questionproblem_data['problem_id'];

    $sql = 'INSERT INTO QuestionProblemConnect (question_id,problem_id) VALUES (:question_id, :problem_id)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':question_id' => $new_question_id,
      ':problem_id' => $problem_id,
      ));

}


    
  

    



//     INSERT INTO menuship3
//     (headhash, menucardhash, menucathash, producthash)
// SELECT 
//     headhash, 'qqq', menucathash, producthash
// FROM 
//     menuship3
// WHERE 
//     menucardhash = 'aaa' ;








            // $sql = 'CREATE TEMPORARY TABLE Tmp SELECT * FROM Question WHERE question_id = :question_id';
            // $stmt = $pdo->prepare($sql);
            // $stmt->execute(array(
            // ':question_id' => $question_id
            // ));

            // $sql = 'ALTER TABLE Tmp DROP COLUMN `question_id`';
            // $stmt = $pdo->prepare($sql);
            // $stmt->execute();

            // $sql = 'INSERT INTO Question SELECT * FROM Tmp';
            // // $sql = 'INSERT INTO Question SELECT * FROM Tmp WHERE question_id = :question_id';
            // $stmt = $pdo->prepare($sql);
            // $stmt->execute();
            // // $stmt->execute(array(
            // // ':question_id' => $question_id
            // // ));

            // $new_question_id = $pdo->lastInsertId();


            // $sql = 'DROP TABLE `Tmp`';
            // $stmt = $pdo->prepare($sql);
            // $stmt->execute();


// return the newest question_id number

            // CREATE TEMPORARY TABLE tmp SELECT * FROM invoices WHERE id = 99;

            // UPDATE tmp SET id=100 WHERE id = 99;
            
            // INSERT INTO invoices SELECT * FROM tmp WHERE id = 100;
  
  
  
  echo $new_question_id;
  
}
?>





