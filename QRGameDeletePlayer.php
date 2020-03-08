<?php
	session_start();
	require_once "pdo.php";
	
// Called by QRGameBackStage.php and then if all normal then goes back to  QRGameBackStage.php  The purpose of this program is to fix the sums for a team and the team size
// corrected by the game master.  
    
  
    
      if (isset($_POST['gameactivity_id'])){
        $gameactivity_id = $_POST['gameactivity_id'];
    }  else  {
       $_SESSION['error'] = "Missing gameactivity_id from QRGameEditPlayer";
	  
      echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
    }
   

   $sql_stmt = "SELECT * FROM Gameactivity WHERE `gameactivity_id`= :gameactivity_id";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt->execute(array(':gameactivity_id' => $gameactivity_id));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
	
    // check the number of individuals on the team
    
	 $sql_stmt = "DELETE FROM Gameactivity WHERE `gameactivity_id`= :gameactivity_id ";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt->execute(array(':gameactivity_id' => $gameactivity_id));
                $gmact_id = $row['gmact_id'];
                 $team_id = $row['team_id'];
                 
              
                   // update the gameactivity table to set the team_size_error to 1 

            
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
                        
            
            
            // move on to the QRGetGamein again can rely on session vars or if that gives problems use a POst and JS to submit
            
                          //  header('Location: QRGameGetIn.php');
                          //  return;   
                 
        
                    echo  "<script type='text/javascript'>";
                    echo "window.close();";
                    echo "</script>";
                    
        
   
   ?>

