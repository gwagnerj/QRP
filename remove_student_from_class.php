<?php
require_once "pdo.php";

    if (isset($_POST['student_id'])&&isset($_POST['currentclass_id'])  ){
        $student_id = $_POST['student_id'];
        $currentclass_id = $_POST['currentclass_id'];
               $sql = 'DELETE FROM StudentCurrentClassConnect
                     WHERE  student_id =:student_id AND currentclass_id =:currentclass_id';

		 	$stmt = $pdo->prepare($sql);	
		 	$stmt->execute(array(
              ":student_id"   =>  $student_id, 
              ":currentclass_id"   =>  $currentclass_id, 
            ));
            echo ("student removed from class");
        
      


	} else {
        echo ("student_id or currnentclass_id lost from stu_login_info.php to remove_student_from_class.php");
    }
    die();
 ?>





