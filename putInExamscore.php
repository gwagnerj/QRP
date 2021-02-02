<?php
require_once "pdo.php";
session_start();
	
    
    if (isset($_POST['eexamtime_id']) && isset($_POST['student_id'] )  && isset($_POST['qr_tot'] ) && isset($_POST['other_pblm'] ) && isset($_POST['exam_ec'] ) && isset($_POST['exam_tot'] )){

       		
        
            $sql = 'INSERT INTO Eexamscore (student_id, eexamtime_id, qr_tot, other_pblm, exam_ec, exam_tot ) 
                                     VALUES(:student_id, :eexamtime_id, :qr_tot, :other_pblm, :exam_ec, :exam_tot) ON DUPLICATE KEY UPDATE 
                                     student_id = :student_id, eexamtime_id= :eexamtime_id, qr_tot = :qr_tot, other_pblm = :other_pblm, exam_ec = :exam_ec, exam_tot = :exam_tot ';

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
             ":student_id"   =>   $_POST['student_id'], 
             ":eexamtime_id"   =>   $_POST['eexamtime_id'], 
             ":qr_tot"   =>   $_POST['qr_tot'], 
             ":other_pblm"   =>   $_POST['other_pblm'], 
             ":exam_ec"   =>   $_POST['exam_ec'], 
             ":exam_tot"   =>   $_POST['exam_tot']
            ));
	}
 ?>





