<?php
	require_once "pdo.php";
	session_start();
	
if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {	
    if (isset($_POST['iid'])) {
	$iid = $_POST['iid'];
   
} else {
	 $_SESSION['error'] = 'invalid User_id in stu_assignment_results.php ';
     echo('no iid');
      			header( 'Location: QRPRepo.php' ) ;
				die();
}



    
    if ($_POST['currentclass_id']==0 || $_POST['currentclass_id']=='' || $_POST['active_assign']==0 || $_POST['active_assign']=='' || $_POST['iid']==0 || $_POST['iid']=='' ){
        $_SESSION['error'] = 'class and assignment number must be set';
        header( 'Location: QRAssignmentStart0.php' ) ;
	   die();
    }
    
    $assign_num = $_POST['active_assign'];
    $currentclass_id = $_POST['currentclass_id'];
    
  
   //   echo(' currentclass_id:  '.$currentclass_id);
     
  
    $sql = 'SELECT * FROM Assign WHERE currentclass_id = :currentclass_id AND iid = :iid AND assign_num = :assign_num';     
          $stmt = $pdo->prepare($sql);
           $stmt -> execute(array (
           ':currentclass_id' => $currentclass_id,
           ':assign_num' => $assign_num,
           ':iid' => $iid,
           )); 
          $assign_data = $stmt -> fetch();
          $assign_id = $assign_data['assign_id'];
    
    
        $sql = "SELECT *  FROM `Assigntime` WHERE assign_num = :assign_num AND currentclass_id = :currentclass_id";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
			 ':currentclass_id' => $currentclass_id,
              ':assign_num' => $assign_num,
				)); 
          
          $assigntime_data = $stmt->fetch();
           
            $assigntime_id = $assigntime_data['assigntime_id'];
            
            
            
        $sql = "SELECT *  FROM `Activity` WHERE assign_id = :assign_id AND currentclass_id = :currentclass_id";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
			 ':currentclass_id' => $currentclass_id,
              ':assign_id' => $assign_id,
				)); 
          
          $activity_data = $stmt->fetchALL();
           
        // $activity_ids = $activity_data[activity_id];
         foreach( $activity_data as  $activity_datum)
                echo(' name: '.$activity_datum['stu_name']); 
         
       
}
?>



