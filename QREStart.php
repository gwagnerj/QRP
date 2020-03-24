<?php
require_once "pdo.php";
	session_start();
	
    
    if ( isset($_SESSION['error']) ) {
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) ) {
			echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
			unset($_SESSION['success']);
		}
        
        
	
    if (isset($_POST['exam_num'])) {
        $exam_num = $_POST['exam_num'];
      } else {
           $_SESSION['error'] = 'invalid examination number in  QREStart.php ';
            header( 'Location: QRPRepo.php' ) ;
            die();
    }
    
    if (isset($_POST['currentclass_id'])) {
        $currentclass_id = $_POST['currentclass_id'];
      } else {
           $_SESSION['error'] = 'invalid examination number in  QREStart.php ';
            header( 'Location: QRPRepo.php' ) ;
            die();
    }
    
    echo 'here we go';
    die();
?>
    