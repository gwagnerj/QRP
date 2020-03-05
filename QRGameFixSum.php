<?php
	session_start();
	require_once "pdo.php";
	
// Called by QRGameBackStage.php and then if all normal then goes back to  QRGameBackStage.php  The purpose of this program is to fix the sums for a team and the team size
// corrected by the game master.  
    
  
    
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
    
   
   
   
	
    // check the number of individuals on the team
        
        $sql_stmt = "SELECT COUNT(*) FROM Gameactivity WHERE team_id = :team_id AND `gmact_id`= :gmact_id ";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt->execute(array(':team_id' => $team_id,':gmact_id' => $gmact_id));
           $number_activated = $stmt->fetchColumn(); 
    
    // See what the team size is that is reported by the users
    
	 $sql_stmt = "SELECT * FROM Gameactivity WHERE `team_id`= :team_id AND `gmact_id`= :gmact_id ";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt->execute(array(':team_id' => $team_id,':gmact_id' => $gmact_id));
            $row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row2 as $row){
                    $gameactivity_id = $row['gameactivity_id'];
              
                   // update the gameactivity table to set the team_size_error to 1 
                   $sql = "UPDATE `Gameactivity` 
                        SET
                        team_size_error = 0,
                        team_size = :team_size
                        WHERE gameactivity_id = :gameactivity_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt -> execute(array(
                        ':gameactivity_id' => $gameactivity_id,
                         ':team_size' => $number_activated,
                        )); 
                       
                
            
                   // Sum up the answers for b and the last one and update the gameactivity table with those values - this is done in QRGetGamein but could be done here instead
                      $stmt = $pdo->prepare("SELECT SUM(`ans_b`) AS ans_sumb FROM `Gameactivity` WHERE gmact_id = :gmact_id AND team_id = :team_id ");
                        $stmt->execute(array(":gmact_id" => $gmact_id, ":team_id" => $team_id));
                        $row = $stmt -> fetch();
                        $ans_sumb = $row['ans_sumb'];
                        
                    $stmt = $pdo->prepare("UPDATE `Gameactivity` SET `ans_sumb` = :ans_sumb WHERE gameactivity_id = :gameactivity_id");
                        $stmt->execute(array(":gameactivity_id" => $gameactivity_id, ":ans_sumb" => $ans_sumb ));
                        
                     $stmt = $pdo->prepare("SELECT SUM(`ans_last`) AS ans_sumlast FROM `Gameactivity` WHERE gmact_id = :gmact_id AND team_id = :team_id ");
                        $stmt->execute(array(":gmact_id" => $gmact_id, ":team_id" => $team_id));
                        $row = $stmt -> fetch();
                        $ans_sumlast = $row['ans_sumlast'];   
                        
                    $stmt = $pdo->prepare("UPDATE `Gameactivity` SET `ans_sumlast` = :ans_sumlast WHERE gameactivity_id = :gameactivity_id");
                    $stmt->execute(array(":gameactivity_id" => $gameactivity_id,  ":ans_sumlast" => $ans_sumlast )); 
                        
            }       
            
            // move on to the QRGetGamein again can rely on session vars or if that gives problems use a POst and JS to submit
            
                          //  header('Location: QRGameGetIn.php');
                          //  return;   
                 
        
                    echo  "<script type='text/javascript'>";
                    echo "window.close();";
                    echo "</script>";
                    
        
   
   ?>

