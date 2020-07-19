<?php
	require_once "pdo.php";
	session_start();
	
	
    if (isset($_POST['iid'])) {
	$iid = $_POST['iid'];
} else {
	 $_SESSION['error'] = 'invalid User_id in remove_assignment.php ';
      			header( 'Location: QRPRepo.php' ) ;
				die();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
    
    if ($_POST['currentclass_id']==0 || $_POST['currentclass_id']=='' || $_POST['active_assign']==0 || $_POST['active_assign']=='' || $_POST['iid']==0 || $_POST['iid']=='' ){
        $_SESSION['error'] = 'class and assignment number must be set';
        header( 'Location: QRAssignmentStart0.php' ) ;
	   die();
 
 
    }
   // see if the We already have a entry in the Assigntime table for this one or its new
   
   $new_flag = 0;
   
   
  	 $sql = 'DELETE FROM Assigntime WHERE currentclass_id = :currentclass_id AND iid = :iid AND assign_num = :assign_num';

 // $sql = 'DELETE assigntime_id FROM Assigntime WHERE currentclass_id = :currentclass_id AND iid = :iid AND assign_num = :assign_num';     


 // $sql = 'SELECT assigntime_id FROM Assigntime WHERE currentclass_id = :currentclass_id AND iid = :iid AND assign_num = :assign_num';     
          $stmt = $pdo->prepare($sql);
           $stmt -> execute(array (
           ':currentclass_id' => $_POST['currentclass_id'],
           ':assign_num' => $_POST['active_assign'],
           ':iid' => $_POST['iid'],
           )); 
           $_SESSION['success']='Assignment removed';
        
          // echo('assigntime_id: '.$assigntime_id);
          // now go to the QRAssignmentStart2 with the assigntime_id 
                header( 'Location: QRPRepo.php' ) ;
				die();
         
        
}
?>



