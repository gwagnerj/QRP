<?php
require_once "pdo.php";

    if (isset($_POST['eexamtime_id']) && isset($_POST['number_teams'])   ){
            $sql = 'UPDATE Eexamtime
                    SET   number_teams = :number_teams
                    WHERE  eexamtime_id =:eexamtime_id';

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
             ":eexamtime_id"   =>   $_POST['eexamtime_id'], 
             ":number_teams"   =>   $_POST['number_teams'] ,
             
            ));
	}
 ?>





