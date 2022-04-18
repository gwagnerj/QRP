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


            // $id_checker1 = $qw_data['id_checker1'];
            // $id_checker2 = $qw_data['id_checker2'];
            // $id_checker3 = $qw_data['id_checker3'];
            // $id_checker4 = $qw_data['id_checker4'];
            // $id_checker5 = $qw_data['id_checker5'];
            $num_reject = $qw_data['num_reject'];
            $sel_reject_fn = 'reject_justification'.$num_reject+1;

            if ($question_use == 1){ $score = -1;}
            if ($question_use == 2){ $score = -2;}
            if ($question_use == 3){ $score = -3;}
            if ($question_use == 4){ $score = -3;}

        $sql = 'SELECT * FROM Student WHERE student_id = :student_id';
        $stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ":student_id"   =>   $qw_data['student_id'], 
            ));
            $student_data = $stmt -> fetch();

            $first_name = $student_data['first_name'];
            $last_name = $student_data['last_name'];
            $checker_name = $first_name . ' ' . $last_name;

           
            // $sel_nm = '';
            // if ($id_checker5 ==0){ $sel_id = 'id_checker5'; $sel_nm = 'nm_checker5'; $status = 'reviewed5'; }  //? select the first nonzero entry
            // if ($id_checker4 ==0){ $sel_id = 'id_checker4'; $sel_nm = 'nm_checker4'; $status = 'reviewed4'; }  //? select the first nonzero entry
            // if ($id_checker3 ==0){ $sel_id = 'id_checker3'; $sel_nm = 'nm_checker3'; $status = 'reviewed3'; }  //? select the first nonzero entry
            // if ($id_checker2 ==0){ $sel_id = 'id_checker2'; $sel_nm = 'nm_checker2'; $status = 'reviewed2';}
            // if ($id_checker1 ==0){ $sel_id = 'id_checker1'; $sel_nm = 'nm_checker1'; $status = 'reviewed1';}

            // if ($sel_nm == ''){ 
            //     echo '0';    // indicates a failure
            //     die();
            //     }
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
            ':activity' => 'Q approved killed',
            ':kill_justification' => $_POST['reject_justification'],
            ':score' => $score,
            ));	

        }

    }


}


//? Move the question from the questionwomb tot he questiontomb table
 
$sql = 'INSERT INTO QuestionTomb (
    primary_concept,
    secondary_concept,
    tertiary_concept,
    title,
    `subject`,
    grade,
    specif_ref,
    unpubl_auth,
    course,
    email,
    `status`,
    question_type,
    question_use,
    nm_author,
    user_id,
    id_checker1,
    id_checker2,
    id_checker3,
    id_checker4,
    id_checker5,
    nm_checker1,
    nm_checker2,
    nm_checker3,
    nm_checker4,
    nm_checker5,
    htmlfilenm,
    key_a,
    key_b,
    key_c,
    key_d,
    key_e,
    key_f,
    key_g,
    key_h,
    key_i,
    key_j,
    explanation_filenm
    )
    VALUES (
        :primary_concept,
        :secondary_concept,
        :tertiary_concept,
        :title,
        :subject,
        :grade,
        :specif_ref,
        :unpubl_auth,
        :course,
        :email,
        :status,
        :question_type,
        :question_use,
        :nm_author,
        :user_id,
        :id_checker1,
        :id_checker2,
        :id_checker3,
        :id_checker4,
        :id_checker5,
        :nm_checker1,
        :nm_checker2,
        :nm_checker3,
        :nm_checker4,
        :nm_checker5,
        :htmlfilenm,
        :key_a,
        :key_b,
        :key_c,
        :key_d,
        :key_e,
        :key_f,
        :key_g,
        :key_h,
        :key_i,
        :key_j,
        :explanation_filenm
      )';
$stmt = $pdo->prepare($sql);	
$stmt->execute(array(
   ':primary_concept'=> $qw_data['primary_concept'],
   ':secondary_concept' => $qw_data['secondary_concept'],
   ':tertiary_concept' => $qw_data['tertiary_concept'],
   ':title' => $qw_data['title'],
   ':subject' => $qw_data['subject'],
   ':grade'=>$qw_data['grade'],
   ':specif_ref' => $qw_data['specif_ref'],
   ':unpubl_auth' => $qw_data['unpubl_auth'],
   ':course' => $qw_data['course'],
   ':email' => $qw_data['email'],
   ':status' => $qw_data['status'],
   ':question_type' => $qw_data['question_type'],
   ':question_use' => $qw_data['question_use'],
   ':nm_author' => $qw_data['nm_author'],
   ':user_id' => $qw_data['user_id'],
   ':id_checker1' => $qw_data['id_checker1'],
   ':id_checker2' => $qw_data['id_checker2'],
   ':id_checker3' => $qw_data['id_checker3'],
   ':id_checker4' => $qw_data['id_checker4'],
   ':id_checker5' => $qw_data['id_checker5'],
   ':nm_checker1' => $qw_data['nm_checker1'],
   ':nm_checker2' => $qw_data['nm_checker2'],
   ':nm_checker3' => $qw_data['nm_checker3'],
   ':nm_checker4' => $qw_data['nm_checker4'],
   ':nm_checker5' => $qw_data['nm_checker5'],
   ':htmlfilenm' => $qw_data['htmlfilenm'],
   ':key_a' => $qw_data['key_a'],
   ':key_b' => $qw_data['key_b'],
   ':key_c' => $qw_data['key_c'],
   ':key_d' => $qw_data['key_d'],
   ':key_e' => $qw_data['key_e'],
   ':key_f' => $qw_data['key_f'],
   ':key_g' => $qw_data['key_g'],
   ':key_h' => $qw_data['key_h'],
   ':key_i' => $qw_data['key_i'],
   ':key_j' => $qw_data['key_j'],
   ':explanation_filenm' => $qw_data['explanation_filenm']
));

// delete the entry from the questionwomb

$sql = 'DELETE FROM QuestionWomb WHERE questionwomb_id = :questionwomb_id';
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':questionwomb_id' => $_POST['questionwomb_id']));




//? Delete the problem from the questionwomb table


            echo '1';  //? indicates success
            die();
	}
 ?>





