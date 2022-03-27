<?php
require_once "pdo.php";
// need to make the others in the same exam 0 later


    if (
        isset($_POST['student_id'])
        && isset($_POST['questionwomb_id']) )
     {

        $questionwomb_id = $_POST['questionwomb_id'];
        // $sql = 'SELECT updated_at FROM QuestionWomb WHERE questionwomb_id = :questionwomb_id ';          
         $sql = 'SELECT updated_at FROM QuestionWomb WHERE questionwomb_id = :questionwomb_id AND updated_at >= NOW() - INTERVAL 5 MINUTE';          
        $stmt = $pdo->prepare($sql);	
        $stmt->execute(array(
            ":questionwomb_id"   =>  $questionwomb_id, 
        ));
        $qw_data = $stmt->fetch();

        if (isset($qw_data['updated_at'])){
            echo '-1';
            die();
        }


            $sql = 'UPDATE QuestionWomb
                    SET  
                     `num_looks` = `num_looks`+1
                    WHERE  questionwomb_id = :questionwomb_id
                    ';

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ":questionwomb_id"   =>  $questionwomb_id, 
            ));

            $sql = 'SELECT num_looks FROM QuestionWomb WHERE questionwomb_id = :questionwomb_id';          
            $stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ":questionwomb_id"   =>  $questionwomb_id, 
            ));
            $qw_data = $stmt->fetch();
            echo $qw_data['num_looks'];  //? indicates success
            die();
	} else {

        echo '0';
        die();
    }

 ?>





