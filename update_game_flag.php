<?php
require_once "pdo.php";

    if (isset($_POST['eexamtime_id']) && isset($_POST['game_flag']) && isset($_POST['gameboard_id'])  ){
       
            $sql = 'UPDATE Eexamtime
                    SET   game_flag = :game_flag,gameboard_id = :gameboard_id
                    WHERE  eexamtime_id =:eexamtime_id';

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
             ":eexamtime_id"   =>   $_POST['eexamtime_id'], 
             ":game_flag"   =>   $_POST['game_flag'],
             ":gameboard_id"   =>   $_POST['gameboard_id'],
            ));
	}
 ?>





