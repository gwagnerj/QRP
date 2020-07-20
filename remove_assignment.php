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
  // get the student results for the assignment
   
  	
                header( 'Location: QRPRepo.php' ) ;
				die();
         
        
}
?>



