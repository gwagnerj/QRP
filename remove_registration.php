<?php
require_once "pdo.php";

    if (isset($_POST['eregistration_id'])  ){
        $eregistration_id = $_POST['eregistration_id'];

        $sql = 'SELECT * FROM Eregistration WHERE eregistration_id =:eregistration_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':eregistration_id' => $eregistration_id]);
        $eregistration_data = $stmt->fetch();
        $eexamnow_id = $eregistration_data['eexamnow_id'];
        $student_id = $eregistration_data['student_id'];

            $sql = 'DELETE FROM Eregistration
                    WHERE  eregistration_id =:eregistration_id';

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
             ":eregistration_id"   =>  $eregistration_id, 
            ));

            $sql = 'DELETE FROM TeamStudentConnect
                    WHERE  student_id =:student_id AND eexamnow_id = :eexamnow_id';
			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
             ":student_id"   =>  $student_id, 
             ":eexamnow_id"   =>  $eexamnow_id, 

            ));

	}
 ?>





