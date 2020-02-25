<?php
	session_start();
	require_once "pdo.php";
	
// Called by QRGamePblemPlan.php and then if all normal then goes to QRGameGetIn.php The purpose of this program is to make sure the team_size reported from the students is the same as the number of enrties in the gameactivity table that have input values and 
// if so to sum those values for b and the last item.  if not it should send an error code to the gmact table so that and the phase fixed at 3 so that the problem can be 
// corrected by the game master.  
    
   if (isset($_POST['game_id'])){
        $game_id = $_POST['game_id'];
    }  elseif(isset($_SESSION['game_id'])){
         $game_id = $_SESSION['game_id'];
    } else  {
       $_SESSION['error'] = "Missing game_id from QRGameGetSum";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['game_id'] = $game_id;
   

   
     if (isset($_POST['pin'])){
        $pin = $_POST['pin'];
    }  elseif(isset($_SESSION['pin'])){
         $pin = $_SESSION['pin'];
    } else  {
       $_SESSION['error'] = "Missing pin from QRGameGetSum";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['pin'] = $pin;
    
    
      if (isset($_POST['team_id'])){
        $team_id = $_POST['team_id'];
    }  elseif(isset($_SESSION['team_id'])){
         $team_id = $_SESSION['team_id'];
    } else  {
       $_SESSION['error'] = "Missing team_id from QRGameGetSum";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['team_id'] = $team_id;
    
      if (isset($_POST['gmact_id'])){
        $gmact_id = $_POST['gmact_id'];
    }  elseif(isset($_SESSION['gmact_id'])){
         $gmact_id = $_SESSION['gmact_id'];
    } else  {
       $_SESSION['error'] = "Missing gmact_id from QRGameGetSum";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['gmact_id'] = $gmact_id;
    
      if (isset($_POST['gameactivity_id'])){
        $gameactivity_id = $_POST['gameactivity_id'];
    }  elseif(isset($_SESSION['gameactivity_id'])){
         $gameactivity_id = $_SESSION['gameactivity_id'];
    } else  {
       $_SESSION['error'] = "Missing gameactivity_id from QRGameGetSum";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['gameactivity_id'] = $gameactivity_id;
    
      if (isset($_POST['name'])){
        $name = $_POST['name'];
    }  elseif(isset($_SESSION['name'])){
         $name = $_SESSION['name'];
    } else  {
       $_SESSION['error'] = "Missing name from QRGameGetSum";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['name'] = $name;
    
      if (isset($_POST['problem_id'])){
        $problem_id = $_POST['problem_id'];
    }  elseif(isset($_SESSION['problem_id'])){
         $problem_id = $_SESSION['problem_id'];
    } else  {
       $_SESSION['error'] = "Missing problem_id from QRGameGetSum";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['problem_id'] = $problem_id;
    
   
      if (isset($_POST['dex'])){
        $dex = $_POST['dex'];
    }  elseif(isset($_SESSION['dex'])){
         $dex = $_SESSION['dex'];
    } else  {
       $_SESSION['error'] = "Missing dex from QRGameGetSum";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['dex'] = $dex;
   
   
    
	if ($game_id<1 || $game_id>1000000) {
	  $_SESSION['error'] = "game number out of range";
	  header('Location: index.php');
	  return;
	}
	
    // check the number of individuals on the team
        
        $sql_stmt = "SELECT COUNT(*) FROM Gameactivity WHERE team_id = :team_id";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt->execute(array(':team_id' => $team_id));
           $number_activated = $stmt->fetchColumn(); 
    
    // See what the team size is that is reported by the users
    
	 $sql_stmt = "SELECT * FROM Gameactivity WHERE `team_id`= :team_id AND `game_id`= :game_id AND created_at >= DATE_SUB(NOW(),INTERVAL 2 HOUR)";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt->execute(array(':team_id' => $team_id,':game_id' => $game_id));
            $row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row2 as $row3){
                $team_size = $row3['team_size'];
                if ($number_activated != $team_size){
                   // update the gameactivity table to set the team_size_error to 1 
                   $sql = "UPDATE `Gameactivity` 
                        SET team_size_error = 1
                        WHERE gameactivity_id = :gameactivity_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt -> execute(array(
                        ':gameactivity_id' => $gameactivity_id,
                        )); 
                        $_SESSION['error'] = "the number of active team members do not match someone on the teams input for team size";
                          header('Location: index.php');
                          return;   
      
                    }

                 else {
            
                   // Sum up the answers for b and the last one and update the gameactivity table with those values - this is done in QRGetGamein but could be done here instead
                      $stmt = $pdo->prepare("SELECT SUM(`ans_b`) AS ans_sumb FROM `Gameactivity` WHERE game_id = :game_id AND team_id = :team_id AND created_at >= DATE_SUB(NOW(),INTERVAL 2 HOUR)");
                        $stmt->execute(array(":game_id" => $game_id, ":team_id" => $team_id));
                        $row = $stmt -> fetch();
                        $ans_sumb = $row['ans_sumb'];
                        
                    $stmt = $pdo->prepare("UPDATE `Gameactivity` SET `ans_sumb` = :ans_sumb WHERE gameactivity_id = :gameactivity_id");
                        $stmt->execute(array(":gameactivity_id" => $gameactivity_id, ":ans_sumb" => $ans_sumb ));
                        
                     $stmt = $pdo->prepare("SELECT SUM(`ans_last`) AS ans_sumlast FROM `Gameactivity` WHERE game_id = :game_id AND team_id = :team_id AND created_at >= DATE_SUB(NOW(),INTERVAL 2 HOUR)");
                        $stmt->execute(array(":game_id" => $game_id, ":team_id" => $team_id));
                        $row = $stmt -> fetch();
                        $ans_sumlast = $row['ans_sumlast'];   
                        
                    $stmt = $pdo->prepare("UPDATE `Gameactivity` SET `ans_sumlast` = :ans_sumlast WHERE gameactivity_id = :gameactivity_id");
                    $stmt->execute(array(":gameactivity_id" => $gameactivity_id,  ":ans_sumlast" => $ans_sumlast )); 
                        
                        
            
            // move on to the QRGetGamein again can rely on session vars or if that gives problems use a POst and JS to submit
            
                            header('Location: QRGameGetIn.php');
                            return;   
                 }
        }
        
   
   ?>

