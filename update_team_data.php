<?php
require_once "pdo.php";
// need to make the others in the same exam 0 later


    if (
        isset($_POST['team_id'])
        && isset($_POST['fin_score'])
        && isset($_POST['env_score'])
        && isset($_POST['soc_score'])
        && isset($_POST['fin_block'])
        && isset($_POST['env_block'])
        && isset($_POST['soc_block'])
        && isset($_POST['fin_hit'])
        && isset($_POST['env_hit'])
        && isset($_POST['soc_hit'])
        && isset($_POST['available_funds'])
     ){

      
            $sql = 'UPDATE Team
                    SET  
                        fin_score = :fin_score,
                        env_score = :env_score,
                        soc_score = :soc_score,
                        fin_block = :fin_block,
                        env_block = :env_block,
                        soc_block = :soc_block,
                        fin_hit = :fin_hit,
                        env_hit = :env_hit,
                        soc_hit = :soc_hit,
                        pol_points = :pol_points
                    WHERE  team_id = :team_id';

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ":team_id"   =>   $_POST['team_id'], 
                ":fin_score"   =>   $_POST['fin_score'], 
                ":env_score"   =>   $_POST['env_score'], 
                ":soc_score"   =>   $_POST['soc_score'], 
                ":fin_block"   =>   $_POST['fin_block'], 
                ":env_block"   =>   $_POST['env_block'], 
                ":soc_block"   =>   $_POST['soc_block'], 
                ":fin_hit"   =>   $_POST['fin_hit'], 
                ":env_hit"   =>   $_POST['env_hit'], 
                ":soc_hit"   =>   $_POST['soc_hit'], 
                ":pol_points"   =>   $_POST['available_funds'] 
            ));
	}
 ?>





