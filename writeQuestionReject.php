<?php
require_once "pdo.php";
// need to make the others in the same exam 0 later


    if (
        isset($_POST['student_id'])
        && isset($_POST['questionwomb_id'])  && isset($_POST['reject_justification'])
     ){

        $sql = 'SELECT * FROM QuestionWomb WHERE questionwomb_id = :questionwomb_id';
        $stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ":questionwomb_id"   =>   $_POST['questionwomb_id'], 
            ));
            $questionwomb_data = $stmt -> fetch();

            $status = $questionwomb_data['status'];
            $question_use = $questionwomb_data['question_use'];
            $id_checker1 = $questionwomb_data['id_checker1'];
            $id_checker2 = $questionwomb_data['id_checker2'];
            $id_checker3 = $questionwomb_data['id_checker3'];
            $id_checker4 = $questionwomb_data['id_checker4'];
            $id_checker5 = $questionwomb_data['id_checker5'];
            $num_reject = $questionwomb_data['num_reject'];
            $sel_reject_fn = 'reject_justification'.$num_reject+1;

            if ($question_use == 1){ $score = 2;}
            if ($question_use == 2){ $score = 4;}
            if ($question_use == 3){ $score = 5;}
            if ($question_use == 4){ $score = 5;}

        $sql = 'SELECT * FROM Student WHERE student_id = :student_id';
        $stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ":student_id"   =>   $_POST['student_id'], 
            ));
            $student_data = $stmt -> fetch();

            $first_name = $student_data['first_name'];
            $last_name = $student_data['last_name'];
            $checker_name = $first_name . ' ' . $last_name;

           
            $sel_nm = '';
            if ($id_checker5 ==0){ $sel_id = 'id_checker5'; $sel_nm = 'nm_checker5'; $status = 'reviewed5';$status_act = 'reviewed5_rej'; }  //? select the first nonzero entry
            if ($id_checker4 ==0){ $sel_id = 'id_checker4'; $sel_nm = 'nm_checker4'; $status = 'reviewed4';$status_act = 'reviewed4_rej'; }  //? select the first nonzero entry
            if ($id_checker3 ==0){ $sel_id = 'id_checker3'; $sel_nm = 'nm_checker3'; $status = 'reviewed3';$status_act = 'reviewed3_rej'; }  //? select the first nonzero entry
            if ($id_checker2 ==0){ $sel_id = 'id_checker2'; $sel_nm = 'nm_checker2'; $status = 'reviewed2';$status_act = 'reviewed2_rej';}
            if ($id_checker1 ==0){ $sel_id = 'id_checker1'; $sel_nm = 'nm_checker1'; $status = 'reviewed1';$status_act = 'reviewed1_rej';}

            if ($sel_nm == ''){ 
                echo '0';    // indicates a failure
                die();
                }

                $sql = "INSERT INTO QuestionWombActivity (
                    `student_id`,
                    `questionwomb_id`,
                    `activity`,
                    `score`
                    )
                VALUES (
                    :student_id,
                    :questionwomb_id,
                    :activity,
                    :score
                    ) ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                ':student_id' => $_POST['student_id'],
                ':questionwomb_id' => $_POST['questionwomb_id'],
                ':activity' => $status_act,
                ':score' => $score,
                ));	

      
            $sql = 'UPDATE QuestionWomb
                    SET  
                    '.$sel_nm.' = :sel_nm, '.$sel_id.' = :sel_id, `status` = :status, `num_reject` = `num_reject`+1,'.$sel_reject_fn.'=:reject_justification
                    WHERE  questionwomb_id = :questionwomb_id';

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ":questionwomb_id"   =>   $_POST['questionwomb_id'], 
                ":sel_nm"   =>    $checker_name ,
                ":sel_id"   =>     $_POST['student_id'] ,
                ":status"   =>     $status ,
                ":reject_justification"   =>     $_POST['reject_justification'] ,

            ));

            echo '1';  //? indicates success
            die();
	}
 ?>





