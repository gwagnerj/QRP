<?php
require_once "pdo.php";
session_start();

if (isset($_POST['activity_id'])){
	$activity_id = $_POST['activity_id'];
}elseif(isset($_GET['activity_id'])){
	$activity_id = $_GET['activity_id'];
}  else {
	
	$_SESSION['error'] = 'activity_id not set in QRcontroller';
    header("Location: QRhomework.php");
	die();
}

// Get the needed info from the Activity table
    $sql = 'SELECT * FROM `Activity` WHERE `activity_id` = :activity_id';
        $stmt = $pdo->prepare($sql);
     $stmt->execute(array(':activity_id' => $activity_id));
     $activity_data = $stmt -> fetch();
        $progress = $activity_data['progress'];
        $pp1 = $activity_data['pp1'];
        $pp2 = $activity_data['pp2'];
        $pp3 = $activity_data['pp3'];
        $pp4 = $activity_data['pp4'];
        $post_pblm1=$activity_data['post_pblm1'];
        $post_pblm1=$activity_data['post_pblm2'];
        $post_pblm1=$activity_data['post_pblm3'];
        
        // so the logic is read from the assignment table and if say pre-problem 1 was assigned put it in the activity table
        // then read from the activity table if say proproblem 1 is not 1 then move on but if it is one we need to display the pre problem.  once preproblem 1 is done that will write a 2 to the activity table 
        // so if pre problem 1 is ero that should mean it was never assigned.  If preproblem 1 (pp1) is 1 that means it is assined and undone.  If pp1 is 2 that means it 
        // was assigned and completed.  QRguesser.php should set pp1 in the acitvity table to 2 where as QRplanning would set pp2
        
        if (($pp1 != 1 && $pp2 !=1 && $pp3 !=1 && $pp4 != 1) || $problem_id<0 || $pin == 0 ){
            
           //change the progress to 4 - first time to see problem
           if($progress < 4){
            $sql ='UPDATE `Activity` SET `progress` = :progress  WHERE activity_id = :activity_id';
            $stmt = $pdo -> prepare($sql);
            $stmt -> execute(array(
                    ':progress' => 4,
                    ':activity_id' => $activity_id
                     ));
    
           }
           // show them the actual numbered problem
        
// ------------------------------------------send the display problem the activity_id ---------------------------------------------------------------------------------------------------------------						
              
          
              header("Location: QRdisplayPblm.php?activity_id=".$activity_id);
              die();
            }
            
        // this problem has pre-problem assigned
            
            header("Location: QRdisplayPre.php");
            die();

?>