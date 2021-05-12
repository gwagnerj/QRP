<?php
require_once "pdo.php";

    if (isset($_POST['eregistration_id']) &&  isset($_POST['change_kahoot_pts']) ){
       
            $sql = 'UPDATE Eregistration
                    SET kahoot_points = :kahoot_points
                    WHERE  eregistration_id =:eregistration_id';

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
             ":eregistration_id"   =>   htmlentities($_POST['eregistration_id']), 
             ":kahoot_points"   =>  htmlentities( $_POST['change_kahoot_pts']), 
            ));
	}
 ?>





