<?php
require_once "pdo.php";
// need to make the others in the same exam 0 later
    if (isset($_POST['chaos_team_number']) && isset($_POST['eexamtime_id'])  && isset($_POST['eexamnow_id']) ){


                $sql = 'UPDATE Team
                SET   chaos_team = "0"
                WHERE  eexamnow_id =:eexamnow_id';

                $stmt = $pdo->prepare($sql);	
                $stmt->execute(array(
                   ":eexamnow_id"   =>   $_POST["eexamnow_id"], 
                ));




            $sql = 'UPDATE Team
                    SET   chaos_team = "1"
                    WHERE  team_id =:chaos_team_number';

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
             ":chaos_team_number"   =>   $_POST['chaos_team_number'], 
            
             
            ));
	}
 ?>





