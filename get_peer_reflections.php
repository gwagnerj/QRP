<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['assign_num'])&&isset($_POST['currentclass_id'])){
	  
// need to get the time limit that they need to rate the reflections by for the next query
      $sql = 'SELECT `assigntime_id`,`peer_refl_t`,`peer_refl_n` FROM Assigntime WHERE assign_num = :assign_num AND currentclass_id = :currentclass_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
        ':assign_num' => $_POST['assign_num'],
        ':currentclass_id' => $_POST['currentclass_id'],
        ));
        $assigntime_data = $stmt -> fetch();
        
        
         $assigntime_id = $assigntime_data['assigntime_id'];
         
         $now = time();
       
        $peer_refl_t = $assigntime_data['peer_refl_t'];
         $peer_refl_n = $assigntime_data['peer_refl_n'];
        $due_cutoff = $now - $peer_refl_t*24*60*60;
        $sql = 'SELECT * FROM Assigntime WHERE assigntime_id = :assigntime_id AND UNIX_TIMESTAMP(`due_date`) <= :now AND UNIX_TIMESTAMP(`due_date`) >= :due_cutoff '; 
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
        ':assigntime_id' => $assigntime_id,
        ':now' => $now,
        ':due_cutoff' => $due_cutoff,
        ));
        $assigntime_data = $stmt -> fetch();
    /*     
        $current_class_id_peer = $assigntime_data['current_class_id'];
       $assign_num_peer = $assigntime_data['assign_num'];
       $assign_num_peer = $assigntime_data['assign_num'];
       $iid_peer = $assigntime_data['iid'];
        */
       
       $reflections = array(); 
           for ($i=1;$i<=20;$i++){
             if($assigntime_data['perc_ref_'.$i]>0){$reflections['ref'.$i] =$i.') reflect';}
             if($assigntime_data['perc_exp_'.$i]>0){$reflections['exp'.$i] =$i.') explore'; }
             if($assigntime_data['perc_con_'.$i]>0){$reflections['con'.$i] =$i.') connect'; }
             if($assigntime_data['perc_soc_'.$i]>0){$reflections['soc'.$i] =$i.') society'; }
         /*   

           if($assigntime_data['perc_exp_'.$i]>0){$reflections['perc_exp_'.$i] = $assigntime_data['perc_exp_'.$i];}
            if($assigntime_data['perc_con_'.$i]>0){$reflections['perc_con_'.$i] = $assigntime_data['perc_con_'.$i];}
            if($assigntime_data['perc_soc_'.$i]>0){$reflections['perc_soc_'.$i] = $assigntime_data['perc_soc_'.$i];}
            // $ref[$i] = 'ya'.$i; */
           }
/* 
         if($assigntime_data['perc_ref_'.$i]>0){$reflections['perc_ref_'.$i] = $assigntime_data['perc_ref_'.$i];}
            if($assigntime_data['perc_exp_'.$i]>0){$reflections['perc_exp_'.$i] = $assigntime_data['perc_exp_'.$i];}
            if($assigntime_data['perc_con_'.$i]>0){$reflections['perc_con_'.$i] = $assigntime_data['perc_con_'.$i];}
            if($assigntime_data['perc_soc_'.$i]>0){$reflections['perc_soc_'.$i] = $assigntime_data['perc_soc_'.$i];}
             */
       
   
     // print_r( $reflections);  
        
		echo json_encode($reflections);
	}
 
 /* 
     
    if(isset($assigntime_id)){
      


        $sql = 'SELECT * FROM Assigntime WHERE assigntime_id = :assigntime_id AND UNIX_TIMESTAMP(`due_date`) <= :now AND UNIX_TIMESTAMP(`due_date`) >= :due_cutoff '; 
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
        ':assigntime_id' => $assigntime_id,
        ':now' => $now,
        ':due_cutoff' => $due_cutoff,
        ));
        $assigntime_data = $stmt -> fetch();
       $current_class_id_peer = $assigntime_data['current_class_id'];
       $assign_num_peer = $assigntime_data['assign_num'];
       $assign_num_peer = $assigntime_data['assign_num'];
       $iid_peer = $assigntime_data['iid'];
       $reflections = array(); 
       for ($i=0;$i<=20;$i++){
            if($assigntime_data['perc_ref_'.$i]>0){$reflections['perc_ref_'.$i] = $assigntime_data['perc_ref_'.$i];}
            if($assigntime_data['perc_exp_'.$i]>0){$reflections['perc_exp_'.$i] = $assigntime_data['perc_exp_'.$i];}
            if($assigntime_data['perc_con_'.$i]>0){$reflections['perc_con_'.$i] = $assigntime_data['perc_con_'.$i];}
            if($assigntime_data['perc_soc_'.$i]>0){$reflections['perc_soc_'.$i] = $assigntime_data['perc_soc_'.$i];}
            
        }
   
      print_r( $reflections);
     }  

 */
 
 
 
 
 ?>





