<?php
require_once "pdo.php";
// need to make the others in the same exam 0 later


    // if (
    //     isset($_POST['iid']) && isset($_POST['question_id']) 
    //  ){

        $json = file_get_contents("php://input"); // json string

            $object = json_decode($json); // php object
            if ($object){
                    $iid = $object->iid;  // pulls the iid value out of the key value 
                    $question_id = $object->question_id;  
            }



        // $iid = 1;                     //! 
        // $question_id = 188;



       $sql = ' CREATE TEMPORARY TABLE temp_table 

        SELECT * FROM Question WHERE question_id=:question_id;
        UPDATE temp_table SET question_id=0, created_at=NOW();
        
        INSERT INTO Question SELECT * FROM temp_table;
        DROP TABLE temp_table;
     ';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array('question_id'=>$question_id));

            $sql = "SELECT  MAX(question_id) FROM Question";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $new_question_id = $stmt->fetch(PDO::FETCH_COLUMN);



            $success['flag'] = true;  
            $success['new_question_id'] = $new_question_id;

            echo json_encode($success);

            die();
	// } else {
    //     echo '-3';
        
    // }
 ?>





