<?php
require_once "pdo.php";
// need to make the others in the same exam 0 later

$score_auth = -10;  // author penalty

    if (
        isset($_POST['iid'])
        && isset($_POST['questionwomb_id'])  && isset($_POST['reject_justification'])
     ){

        // take off poionts from author and reviewers that wanted to promote  maybe no additional point
        // need to add a kill justification field to the questionwombactivity table

        $sql = 'SELECT * FROM QuestionWomb WHERE questionwomb_id = :questionwomb_id';
        $stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ":questionwomb_id"   =>   $_POST['questionwomb_id'], 
            ));
            $qw_data = $stmt -> fetch();

            $status = $qw_data['status'];
            $question_use = $qw_data['question_use'];
            $id_checker = array();

            for ($i = 0; $i <5 ; $i++){
                $j = $i + 1;
                $sel_str = 'id_checker'.$j;
                $id_checker[$i] = $qw_data[$sel_str];
            }


            $num_reject = $qw_data['num_reject'];
            $sel_reject_fn = 'reject_justification'.$num_reject+1;

            if ($question_use == 1){ $score = -2;}
            if ($question_use == 2){ $score = -3;}
            if ($question_use == 3){ $score = -4;}
            if ($question_use == 4){ $score = -4;}

        $sql = 'SELECT * FROM Student WHERE student_id = :student_id';
        $stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ":student_id"   =>   $qw_data['student_id'], 
            ));
            $student_data = $stmt -> fetch();

            $first_name = $student_data['first_name'];
            $last_name = $student_data['last_name'];
            $checker_name = $first_name . ' ' . $last_name;

           
//? take care of the student author

                $sql = "INSERT INTO QuestionWombActivity (
                    `student_id`,
                    `questionwomb_id`,
                    `activity`,
                    `kill_justification`,
                    `score`
                    )
                VALUES (
                    :student_id,
                    :questionwomb_id,
                    :activity,
                    :kill_justification,
                    :score
                    ) ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                ':student_id' => $qw_data['student_id'],
                ':questionwomb_id' => $_POST['questionwomb_id'],
                ':activity' => 'killed',
                ':kill_justification' => $_POST['reject_justification'],
                ':score' => $score_auth,
                ));	

//? take care of the students that approved it again in the acitivity table

for ($i = 0; $i <5 ; $i++){
    
    // $id_checker[$i] = $qw_data[$sel_str];

    if ($id_checker[$i] != 0){
        //? see if they rejected or approved the problem by looking in the questionwombactivity table
        $sql = '
        SELECT * FROM QuestionWombActivity
        WHERE 
        questionwomb_id = :questionwomb_id AND 
        student_id = :student_id
        ';
        $stmt = $pdo->prepare($sql);	
        $stmt->execute(array(
            ":questionwomb_id"   =>   $_POST['questionwomb_id'], 
            ":student_id"   =>   $id_checker[$i], 
        ));
        $qwa_data = $stmt -> fetch();
        $qwa_activity = $qwa_data['activity'];
        $qwa_activity_ar = explode('_',$qwa_activity);
        if (count($qwa_activity_ar)<2){  //? they approved the question
           
           $sql = "INSERT INTO QuestionWombActivity (
                `student_id`,
                `questionwomb_id`,
                `activity`,
                `kill_justification`,
                `score`
                )
            VALUES (
                :student_id,
                :questionwomb_id,
                :activity,
                :kill_justification,
                :score
                ) ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
            ':student_id' => $id_checker[$i],
            ':questionwomb_id' => $_POST['questionwomb_id'],
            ':activity' => 'Q approved sent back',
            ':kill_justification' => $_POST['reject_justification'],
            ':score' => $score,
            ));	

        }

    }


}


//? change status of question to sent_back
 
        $sql = 'UPDATE QuestionWomb
        SET  
       `status` = :status, `message` = :message
        WHERE  questionwomb_id = :questionwomb_id';

        $stmt = $pdo->prepare($sql);	
        $stmt->execute(array(
        ":questionwomb_id"   =>   $_POST['questionwomb_id'], 
        ":status"   =>     'sent_back' ,
        ":message"   =>     $_POST['reject_justification'] ,

        ));


            echo '1';  //? indicates success
            die();
	} else {
        echo '0';  //? indicates success
            die();
    }
 ?>





