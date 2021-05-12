<?php
require_once "pdo.php";

    if (isset($_POST['team_id']) &&  isset($_POST['team_nm_value']) ){
       
            $sql = 'UPDATE Team
                    SET team_name = :team_name
                    WHERE  team_id =:team_id';

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
             ":team_id"   =>   htmlentities($_POST['team_id']), 
             ":team_name"   =>  htmlentities( $_POST['team_nm_value']), 
            ));
	}
 ?>





