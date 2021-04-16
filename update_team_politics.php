<?php
require_once "pdo.php";
// need to make the others in the same exam 0 later


    if (
        isset($_POST['team_id'])
        && isset($_POST['gamepolitical_id'])
     ){
      
            $sql = 'UPDATE Team
                    SET  
                    gamepolitical_id = :gamepolitical_id
                    WHERE  team_id = :team_id';

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ":team_id"   =>   $_POST['team_id'], 
                ":gamepolitical_id"   =>   $_POST['gamepolitical_id'] 
            ));
	}
 ?>





