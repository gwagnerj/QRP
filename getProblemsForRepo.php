<?php
require_once "pdo.php";
session_start();
$iid = 2;
     $json = file_get_contents("php://input"); // json string

    $object = json_decode($json); // php object
    if ($object){
            $iid = $object->iid;  // pulls the iid value out of the key value 
    }
        if (!isset($iid)| !is_numeric($iid)){
             $_SESSION['error'] = ' iid not sent to get data file getProblems...'.$iid;
            die();
        }

			// $stmt = "SELECT * 
			// FROM Problem LEFT JOIN Users ON Problem.users_id=Users.users_id ORDER BY problem_id DESC";
			// $stmt = "SELECT problem_id
			// FROM Problem ORDER BY problem_id DESC";

            $sql="SELECT Problem.problem_id AS problem_id,Users.username AS username, Users.first AS name,Problem.subject as subject,Problem.course as course,Problem.primary_concept as p_concept,Users.users_id as users_id,
            Problem.secondary_concept as s_concept,Problem.title as title,Problem.specif_ref as ref,Problem.status as status, Problem.soln_pblm as soln_pblm,Problem.game_prob_flag as game_prob_flag, 
            Problem.nm_author as nm_author,Problem.docxfilenm as docxfilenm,Problem.infilenm as infilenm,Problem.pdffilenm as pdffilenm,
            Problem.eff_stu_1 as eff_stu_1,Problem.eff_stu_2 as eff_stu_2,Problem.eff_stu_3 as eff_stu_3,Problem.eff_stu_4 as eff_stu_4,Problem.eff_stu_5 as eff_stu_5,
            Problem.diff_stu_1 as diff_stu_1,Problem.diff_stu_2 as diff_stu_2,Problem.diff_stu_3 as diff_stu_3,Problem.diff_stu_4 as diff_stu_4,Problem.diff_stu_5 as diff_stu_5,
            Problem.t_take1_1 as t_take1_1,Problem.t_take1_2 as t_take1_2,Problem.t_take1_3 as t_take1_3,Problem.t_take1_4 as t_take1_4,Problem.t_take1_5 as t_take1_5,Problem.t_take1_6 as t_take1_6,Problem.t_take1_7 as t_take1_7,
            Problem.t_take1_np_1 as t_take1_np_1,Problem.t_take1_np_2 as t_take1_np_2,Problem.t_take1_np_3 as t_take1_np_3,Problem.t_take1_np_4 as t_take1_np_4,Problem.t_take1_np_5 as t_take1_np_5, Problem.t_take1_np_6 as t_take1_np_6,Problem.t_take1_np_7 as t_take1_np_7,
            Problem.t_take2_1 as t_take2_1,Problem.t_take2_2 as t_take2_2,Problem.t_take2_3 as t_take2_3,Problem.t_take2_4 as t_take2_4,Problem.t_take2_5 as t_take2_5,Problem.t_take2_6 as t_take2_6,Problem.t_take2_7 as t_take2_7,
            Problem.t_b4due_1 as t_b4due_1,Problem.t_b4due_2 as t_b4due_2,Problem.t_b4due_3 as t_b4due_3,Problem.t_b4due_4 as t_b4due_4,Problem.t_b4due_5 as t_b4due_5,Problem.t_b4due_6 as t_b4due_6,Problem.t_b4due_7 as t_b4due_7,
            Problem.t_b4due_np_1 as t_b4due_np_1,Problem.t_b4due_np_2 as t_b4due_np_2,Problem.t_b4due_np_3 as t_b4due_np_3,Problem.t_b4due_np_4 as t_b4due_np_4,Problem.t_b4due_np_5 as t_b4due_np_5, Problem.t_b4due_np_6 as t_b4due_np_6, Problem.t_b4due_np_7 as t_b4due_np_7,
            Problem.confidence_1 as confidence_1,Problem.confidence_2 as confidence_2,Problem.confidence_3 as confidence_3,Problem.confidence_4 as confidence_4,Problem.confidence_5 as confidence_5,
            Problem.confidence_np_1 as confidence_np_1,Problem.confidence_np_2 as confidence_np_2,Problem.confidence_np_3 as confidence_np_3,Problem.confidence_np_4 as confidence_np_4,Problem.confidence_np_5 as confidence_np_5,
             Users.university as s_name, Problem.preprob_3 as mc_prelim, Problem.preprob_4 as misc_prelim, Problem.hint_a as hint_a, Problem.hint_b as hint_b, Problem.hint_c as hint_c, Problem.hint_d as hint_d, Problem.hint_e as hint_e,
             Problem.hint_f as hint_f,Problem.hint_g as hint_g,Problem.hint_h as hint_h, Problem.hint_i as hint_i, Problem.hint_j as hint_j, Problem.video_clip as video_clip, Problem.simulation as simulation, Problem.demonstration_directions as demo_directions,
             Problem.activity_directions as activity_directions, Problem.computation_name as computation_name, Problem.allow_clone as allow_clone, Problem.allow_edit as allow_edit, Problem.parent as parent, Problem.children as children, Problem.orig_contr_id as orig_contr_id,
            Problem.edit_id1 as edit_id1, Problem.edit_id2 as edit_id2, Problem.edit_id3 as edit_id3, Assign.assign_num as assign_num, Assign.alias_num AS alias_num, Assign.currentclass_id as currentclass_id ,Assign.assign_id as assign_id,CurrentClass.name AS class_name,
            Assigntime.assigntime_id as assigntime_id ,  Eexam.exam_num AS exam_e_num, Eexam.alias_num AS e_alias_num,Eexam.currentclass_id as e_currentclass_id
            FROM Problem LEFT JOIN Users ON Problem.users_id=Users.users_id 
            LEFT JOIN Assign ON Assign.prob_num = Problem.problem_id
            LEFT JOIN Assigntime ON (Assign.assign_num = Assigntime.assign_num AND Assigntime.currentclass_id = Assign.currentclass_id AND Assigntime.iid = Assign.iid)
            LEFT JOIN CurrentClass ON Assign.currentclass_id = CurrentClass.currentclass_id
            LEFT JOIN Eexam ON (Problem.problem_id = Eexam.problem_id AND Eexam.iid = Users.users_id)
            ORDER BY problem_id DESC";
        // AND iid = :iid AND currentclass_id = :currentclass_id";

        // WHERE prob_num = :prob_num AND iid = :iid";
        // Eexam.exam_num AS exam_e_num, Eexam.alias_num AS e_alias_num,Eexam.currentclass_id as e_currentclass_id FROM Eexam WHERE problem_id = :problem_id AND iid = :iid";

			$stmt = $pdo->prepare($sql);	
			$stmt->execute();
			$problems = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
		 echo json_encode($problems);
	
 ?>





