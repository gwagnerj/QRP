<?php
session_start();
require_once "pdo.php";


// After users have confirmed the data the QRGameSetup loads it to the GameActivity table then proceeds onto the QRGameRouter
 
// first do some error checking on the input.  If it is not OK set the session failure and send them back to QRGameIndex or getGamePblmNum.php.

      if (isset($_POST['game_id'])){
            $game_id = $_POST['game_id'];
      } 
       else {
           $_SESSION['error'] = "Missing game number from getGame";
          header('Location: index.php');
          return;   
        }
  
        if ($game_id<1 || $game_id>1000000) {
          $_SESSION['error'] = "game number out of range";
          header('Location: index.php');
          return;
        }
    
    
       if ( isset($_POST['pin']) ) {
            $pin = $_POST['pin'];
        } elseif (isset($_SESSION['pin'])){
            $pin = $_SESSION['pin'];
        } else {
          $_SESSION['error'] = "Missing pin";
          header('Location: index.php');
          return;
        }
                $_SESSION['pin']=$pin;
                    $alt_dex = ($pin-1) % 199 + 2; // % is PHP mudulus function - changing the PIN to an index between 2 and 200
                    $_SESSION['alt_dex'] = $alt_dex;
        
    
    
    if (isset($_POST['team_id'])){
        $team_id = $_POST['team_id'];
      } 
   else {
       $_SESSION['error'] = "Missing team number";
	  header('Location: getGamePblmNum.php');
	  return;   
    }
    
    if (isset($_POST['iid'])){
        $iid = $_POST['iid'];
      } 
   else {
       $_SESSION['error'] = "Missing iid from QRGameRouter";
	  header('Location: getGamePblmNum.php');
	  return;   
    }
    
    
     if (isset($_POST['team_size'])){
        $team_size = $_POST['team_size'];
      } 
   else {
       $_SESSION['error'] = "Missing number on team";
	  header('Location: getGamePblmNum.php');
	  return;   
    }
    
    
        if (isset($_POST['phase'])){
            $phase = $_POST['phase'];
          }  else {        
           $_SESSION['error'] = "Missing Problem phase or game not active";
          header('Location: index.php');
          return;   
        }
        
     if (isset($_POST['name'])){
        $name = $_POST['name'];
        } 
       else {
           $name = 'Not Given';
        }
        
        // seeing if the game has more than four input varaibles by getting dex (if it is -1 then is ok to use alt dex for dex 
        
        $stmt = $pdo->prepare("SELECT * FROM Game WHERE game_id = :game_id");
		$stmt->execute(array(":game_id" => $game_id));
		$row = $stmt -> fetch();
		if ( $row === false ) {
			$_SESSION['error'] = 'Bad value for game_id or game_id not active';
			header( 'Location: index.php' ) ;
			return;
		}
        
		$gameData=$row;	
		$problem_id = $gameData['problem_id'];
		 $dex = $gameData['dex'];
           
        if($dex == -1 ) {$dex = $alt_dex;} // temp will change to Assigned dex from the players pin
   
   
   
        // get the answers for parts b and the last part from the QA table
       	$stmt = $pdo->prepare("SELECT * FROM Qa where problem_id = :problem_id AND dex = :dex");
			$stmt->execute(array(":problem_id" => $problem_id, ":dex" => $dex));
			$row = $stmt -> fetch();
			if ( $row === false ) {
				$_SESSION['error'] = 'Bad value for problem_id';
				header( 'Location: getGamePblmNum.php' ) ;
				return;
			}	
            
            $ans_b = $row['ans_b'];
          

          // gota be a better way but I am going to bruit force it
            
            $ans_last = $row['ans_j'];
            if($row['ans_j']>=1.2e43 && $row['ans_j'] < 1.3e43){
                $ans_last = $row['ans_i'];
            }
             if($row['ans_i']>=1.2e43 && $row['ans_i'] < 1.3e43){
                $ans_last = $row['ans_h'];
            }
              if($row['ans_h']>=1.2e43 && $row['ans_h'] < 1.3e43){
                $ans_last = $row['ans_g'];
            }
            if($row['ans_g']>=1.2e43 && $row['ans_g'] < 1.3e43){
                $ans_last = $row['ans_f'];
            }
             if($row['ans_f']>=1.2e43 && $row['ans_f'] < 1.3e43){
                $ans_last = $row['ans_e'];
            }
             if($row['ans_e']>=1.2e43 && $row['ans_e'] < 1.3e43){
                $ans_last = $row['ans_d'];
            }
              if($row['ans_d']>=1.2e43 && $row['ans_d'] < 1.3e43){
                $ans_last = $row['ans_c'];
            }
              if($row['ans_c']>=1.2e43 && $row['ans_c'] < 1.3e43){
                $ans_last = $row['ans_b'];
            }


   // what if they have already loged into the game once and have an entry then we should update instead of insert
   
        $stmt = $pdo->prepare("SELECT * FROM Gameactivity where game_id = :game_id AND pin = :pin");
        $stmt->execute(array(":game_id" => $game_id, ":pin" => $pin));
        $row = $stmt -> fetch();
        if ( $row === false ) {
            $sql = "INSERT INTO Gameactivity (game_id, team_id, pin, dex, iid, team_size, team_size_error, name, ans_b, ans_last)
				VALUES (:game_id, :team_id, :pin, :dex, :iid, :team_size, 0, :name, :ans_b, :ans_last)";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
					':game_id' => $game_id,
					':team_id' => $team_id,
					':pin' => $pin,
                    ':dex' => $dex,
                    ':iid' => $iid,
					':team_size' => $team_size,
                    ':name' => $name,
                    ':ans_b' => $ans_b,
                    ':ans_last' => $ans_last,
					));
                

                // get the gameactivity_id
                
               $sql = "SELECT `gameactivity_id` FROM `Gameactivity` ORDER BY gameactivity_id DESC LIMIT 1";
               $stmt = $pdo->prepare($sql);
               $stmt -> execute(); 
                $row3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach($row3 as $row){
             //   print_r ($row);
                $gameactivity_id = $row['gameactivity_id'];
                }
        }	else {
            
            // update the gameactivity table or tell them there is a duplicate entry in the table - wont worry about this right now
            $team_size_error = 0;
             $sql = "UPDATE `Gameactivity` 
				SET game_id = :game_id, team_id = :team_id, pin = :pin, dex = :dex, iid = :iid, team_size = :team_size, team_size_error = :team_size_error, name = :name, ans_b = :ans_b, ans_last = :ans_last
				WHERE game_id = :game_id AND pin = :pin ";
                $stmt = $pdo->prepare($sql);
                $stmt -> execute(array(
                    ':game_id' => $game_id,
					':team_id' => $team_id,
					':pin' => $pin,
                    ':dex' => $dex,
                     ':iid' => $iid,
					':team_size' => $team_size,
                    ':team_size_error' => $team_size_error,
                    ':name' => $name,
                    ':ans_b' => $ans_b,
                    ':ans_last' => $ans_last,
                ));
                
                 $sql = "SELECT `gameactivity_id` FROM `Gameactivity` WHERE game_id = :game_id AND pin = :pin LIMIT 1";
               $stmt = $pdo->prepare($sql);
               $stmt -> execute(array(
                    ':game_id' => $game_id,
					':pin' => $pin,
                    )); 
                $row3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach($row3 as $row){
             //   print_r ($row);
                $gameactivity_id = $row['gameactivity_id'];
                }
                
        }
        
        
                
        
         // get what the current phase of the game is so we can tell where to send them
             $sql_stmt = "SELECT * FROM Gmact WHERE `game_id`= :game_id";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt->execute(array(':game_id' => $game_id));
            $row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row2 as $row3){
                $phase = $row3['phase'];
                $gmact_id = $row3['gmact_id'];
            }
           // Put everthing in Session variables and send them on their way?  Could put in html and use JS to submit the correct form if headers gives me a problem set up 
           // html form and make action a php varaibel and submit with JS
           
           $_SESSION['name']=$name;
           $_SESSION['pin']=$pin;
           $_SESSION['team_id']=$team_id;
           $_SESSION['problem_id']=$problem_id;
           $_SESSION['dex']=$dex;
           
            $_SESSION['phase']=$phase;
           $_SESSION['game_id']=$game_id;
            $_SESSION['gmact_id']=$gmact_id;
            $_SESSION['gameactivity_id']=$gameactivity_id;
            $_SESSION['iid']=$iid;
            
             header( 'Location: QRGameRouter.php' ) ;
				return;
            
           


?>









