<?php
require_once "pdo.php";
session_start();
	
    
   if (isset($_POST['eactivity_id']) && isset($_POST['dex'] ) && isset($_POST['part'] ) ){
		
        
       
            $eactivity_id = $_POST['eactivity_id'];
        //     $display_ans_key = $_POST['display_ans_key'];
            $part = $_POST['part'];
            $dex = $_POST['dex'];
             
        //    $eactivity_id = 1;
        //    $display_ans_key = 'display_ans_pblm1';
        //    $part = "a";
            
            if($dex !=1){
                $sql = 'UPDATE Eactivity SET `display_ans_'.$part.'`= 1 WHERE eactivity_id = :eactivity_id';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                ":eactivity_id" => $eactivity_id, 
                ));
                //   $display_activity_data = $stmt->fetch();
            } else {
                $sql = 'UPDATE Eactivity SET `display_bc_ans_'.$part.'`= 1 WHERE eactivity_id = :eactivity_id';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                ":eactivity_id" => $eactivity_id, 
                ));
            }
	 }
 ?>





