<?php
require_once "pdo.php";

	
    
    if (isset($_POST['eregistration_id']) && isset($_POST['dex']) && isset($_POST['checker_only'])  ){

           // need to update both the eregistration table and the teamstudentconnect table - need the eexamnow_id for the latter
        
        $sql = 'SELECT eexamnow_id, student_id, dex FROM Eregistration WHERE eregistration_id = :eregistration_id ';   
            $stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
             ":eregistration_id"   =>   $_POST['eregistration_id'], 
            ));
            $eregistration_data = $stmt->fetch();
            $eexamnow_id = $eregistration_data['eexamnow_id'];
            $student_id = $eregistration_data['student_id'];
            $dex_old = $eregistration_data['dex'];

        
            $sql = 'UPDATE Eregistration
                    SET  dex= :dex, checker_only = :checker_only
                    WHERE  eregistration_id =:eregistration_id';

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
             ":eregistration_id"   =>   $_POST['eregistration_id'], 
             ":dex"   =>   $_POST['dex'] ,
             ":checker_only"   =>   $_POST['checker_only'] 
             
            ));

            if ($dex_old != $_POST['dex']){

                $sql = 'UPDATE TeamStudentConnect
                SET  dex= :dex
                WHERE  eexamnow_id =:eexamnow_id AND student_id =:student_id';
                    $stmt = $pdo->prepare($sql);	
                    $stmt->execute(array(
                    ":eexamnow_id"   =>   $eexamnow_id, 
                    ":dex"   =>   $_POST['dex'] ,
                    ":student_id"   =>   $student_id
                ));


                // We are changing the dex for the student so we need to reset there eactivity - give them a new eactivity id = 
                
               // first bruit force with php later I will try sql

               $sql = 'SELECT eactivity_id, problem_id,student_id,alias_num,currentclass_id FROM Eactivity WHERE eregistration_id = :eregistration_id AND eexamnow_id = :eexamnow_id';
               $stmt = $pdo->prepare($sql);	
               $stmt->execute(array(
                ":eregistration_id"   =>   $_POST['eregistration_id'], 
                ":eexamnow_id"   =>   $eexamnow_id, 
                
               ));
               $eactivity_data = $stmt->fetchALL();  // can have more than one problem

               foreach($eactivity_data as $eactivity_datum){

                    $sql = 'INSERT INTO Eactivity (problem_id, eregistration_id, eexamnow_id, currentclass_id, alias_num, student_id)
                                            VALUES (:problem_id, :eregistration_id, :eexamnow_id, :currentclass_id, :alias_num, :student_id)';

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(
                        ":problem_id" => $eactivity_datum['problem_id'],
                        ":eregistration_id" => $_POST['eregistration_id'],
                        ":eexamnow_id" => $eexamnow_id,
                        ":currentclass_id" => $eactivity_datum['currentclass_id'],
                        ":alias_num" => $eactivity_datum['alias_num'],
                        ":student_id" => $eactivity_datum['student_id'],
                    ));

               }


        }


	}
 ?>





