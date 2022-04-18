<?php
require_once "pdo.php";
// need to make the others in the same exam 0 later


    if (
        isset($_POST['iid'])
        && isset($_POST['questionwomb_id'])
     ){

        $sql = 'SELECT * FROM QuestionWomb WHERE questionwomb_id = :questionwomb_id';
        $stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ":questionwomb_id"   =>   $_POST['questionwomb_id'], 
            ));
            $qw_data = $stmt -> fetch();
            $qw_student_id = $qw_data['student_id'];

            $question_use = $qw_data['question_use'];
            // $id_checker1 = $qw_data['id_checker1'];
            // $id_checker2 = $qw_data['id_checker2'];
            // $id_checker3 = $qw_data['id_checker3'];
            // $id_checker4 = $qw_data['id_checker4'];
            // $id_checker5 = $qw_data['id_checker5'];

            if ($question_use == 1){ $score = 3;}
            if ($question_use == 2){ $score = 5;}
            if ($question_use == 3){ $score = 10;}
            if ($question_use == 4){ $score = 10;}


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
                ':student_id' =>  $qw_student_id,
                ':questionwomb_id' => $_POST['questionwomb_id'],
                ':activity' => 'promoted',
                ':score' => $score,
                ));	
                $questionwombactivity_id = $pdo->lastInsertId();
              

      
            $sql = 'INSERT INTO Question (
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
              
                key_a,
                key_b,
                key_c,
                key_d,
                key_e,
                key_f,
                key_g,
                key_h,
                key_i,
                key_j
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
                    :key_a,
                    :key_b,
                    :key_c,
                    :key_d,
                    :key_e,
                    :key_f,
                    :key_g,
                    :key_h,
                    :key_i,
                    :key_j
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
            ));
            $question_id = $pdo->lastInsertId();

            $qw_htmlfilenm =  $qw_data['htmlfilenm'];
            $qw_htmlfilenm_ar = explode('_',$qw_htmlfilenm);
            $qw_htmlfilenm_ar = array_slice($qw_htmlfilenm_ar,2);
            $q_htmlfilenm = 'q'.$question_id.'_'.implode('_',$qw_htmlfilenm_ar);
            // rename the file in the directory to
            $qw_fullnm = 'uploads/'.$qw_htmlfilenm.'.htm';
            $q_fullnm = 'uploads/'.$q_htmlfilenm.'.htm';
       
            rename ($qw_fullnm,$q_fullnm);

            $sql = 'UPDATE Question SET 
                htmlfilenm=:htmlfilenm
                WHERE question_id = :question_id';
            $stmt = $pdo->prepare($sql);
            $stmt -> execute(array(
            ':question_id' => $question_id,
            ':htmlfilenm' => $q_htmlfilenm,
            ));

            $sql = 'UPDATE QuestionWombActivity SET 
                question_id=:question_id
                WHERE questionwombactivity_id = :questionwombactivity_id';
            $stmt = $pdo->prepare($sql);
            $stmt -> execute(array(
            ':question_id' => $question_id,
            ':questionwombactivity_id' => $questionwombactivity_id,
            ));




            if($qw_data['explanation_filenm'] && strlen($qw_data['explanation_filenm'])>2){

                $qw_expl_htmlfilenm =  $qw_data['explanation_filenm'];
                $qw_expl_htmlfilenm_ar = explode('_',$qw_expl_htmlfilenm);
                $qw_expl_htmlfilenm_ar = array_slice($qw_expl_htmlfilenm_ar,3);
                $q_exp_filenm = 'q'.$question_id.'_expl_'.implode('_',$qw_expl_htmlfilenm_ar);

                $qw_exp_fullnm = 'uploads/'.$qw_expl_htmlfilenm.'.htm';
                $q_exp_fullnm = 'uploads/'.$q_exp_filenm.'.htm';

                rename ($qw_exp_fullnm,$q_exp_fullnm);

                $sql = 'UPDATE Question SET 
               explanation_filenm=:explanation_filenm
                WHERE question_id = :question_id';
                $stmt = $pdo->prepare($sql);
                $stmt -> execute(array(
                ':question_id' => $question_id,
                ':explanation_filenm' =>  $q_exp_filenm,
                ));


    

            }



            

            // get the last question_id so we can remane the file then update the 

            // delete the entry from the questionwomb

            $sql = 'DELETE FROM QuestionWomb WHERE questionwomb_id = :questionwomb_id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(':questionwomb_id' => $_POST['questionwomb_id']));

            echo '1';  //? indicates success
            die();
	}
 ?>





