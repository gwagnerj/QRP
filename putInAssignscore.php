<?php
require_once "pdo.php";
session_start();
	
    
    if (isset($_POST['assigntime_id']) && isset($_POST['student_id'] )  && isset($_POST['qr_tot'] ) && isset($_POST['other_pblm'] ) && isset($_POST['assign_ec'] ) && isset($_POST['assign_tot'] )){
		
        
            $sql = 'INSERT INTO Assignscore (student_id, assigntime_id, qr_tot, other_pblm, assign_ec, assign_tot ) 
                                     VALUES(:student_id, :assigntime_id, :qr_tot, :other_pblm, :assign_ec, :assign_tot) ON DUPLICATE KEY UPDATE 
                                     student_id = :student_id, assigntime_id=:assigntime_id, qr_tot = :qr_tot, other_pblm =:other_pblm, assign_ec=:assign_ec, assign_tot = :assign_tot ';

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
             ":student_id"   =>   $_POST['student_id'], 
             ":assigntime_id"   =>   $_POST['assigntime_id'], 
             ":qr_tot"   =>   $_POST['qr_tot'], 
             ":other_pblm"   =>   $_POST['other_pblm'], 
             ":assign_ec"   =>   $_POST['assign_ec'], 
             ":assign_tot"   =>   $_POST['assign_tot'], 
            ));
	}
 ?>





