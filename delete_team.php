<?php
require_once "pdo.php";

    if (isset($_POST['teams_id'])  ){
        $team_id = $_POST['teams_id'];
 //   $team_id = 203;
        $sql = 'SELECT * FROM Team WHERE team_id = :team_id';
        $stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
             ":team_id"   =>  $team_id, 
            ));
        $team_data = $stmt->fetch();
        $eexamnow_id = $team_data['eexamnow_id'];


        $sql = 'SELECT * FROM TeamStudentConnect WHERE team_id = :team_id AND eexamnow_id = :eexamnow_id';
        $stmt = $pdo->prepare($sql);	
        $stmt->execute(array(
         ":team_id"   =>  $team_id, 
         ":eexamnow_id"   =>  $eexamnow_id, 
        ));
        $teamstudentconnect_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        foreach ($teamstudentconnect_data as $teamstudentconnect_datum){
            $student_ids[$i] = $teamstudentconnect_datum['student_id'];
            $i++;
        }

        foreach ($student_ids as $student_id){
           
            $sql = 'DELETE FROM Eregistration
                     WHERE  student_id =:student_id
                     AND eexamnow_id = :eexamnow_id
                     ';
			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
             ":student_id"   =>  $student_id, 
             ":eexamnow_id"   =>  $eexamnow_id, 

            ));

            $sql = 'DELETE FROM TeamStudentConnect
                    WHERE  student_id =:student_id AND eexamnow_id = :eexamnow_id';
			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
             ":student_id"   =>  $student_id, 
             ":eexamnow_id"   =>  $eexamnow_id, 

            ));

        }

        $sql = 'DELETE FROM Team WHERE team_id = :team_id';
        $stmt = $pdo->prepare($sql);	
        $stmt->execute(array(
         ":team_id"   =>  $team_id, 
        ));

	}
 ?>





