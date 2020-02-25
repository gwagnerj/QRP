<?php

session_start();
require_once "pdo.php";

 
      if (isset($_POST['game_id'])){
        $game_id = $_POST['game_id'];
    }  elseif(isset($_SESSION['game_id'])){
         $game_id = $_SESSION['game_id'];
    } else  {
       $_SESSION['error'] = "Missing game_id from QRGameShowResults";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['game_id'] = $game_id;
   

   
     if (isset($_POST['pin'])){
        $pin = $_POST['pin'];
    }  elseif(isset($_SESSION['pin'])){
         $pin = $_SESSION['pin'];
    } else  {
       $_SESSION['error'] = "Missing pin from QRGameShowResults";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['pin'] = $pin;
    
    
      if (isset($_POST['team_id'])){
        $team_id = $_POST['team_id'];
    }  elseif(isset($_SESSION['team_id'])){
         $team_id = $_SESSION['team_id'];
    } else  {
       $_SESSION['error'] = "Missing team_id from QRGameShowResults";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['team_id'] = $team_id;
    
      if (isset($_POST['gmact_id'])){
        $gmact_id = $_POST['gmact_id'];
    }  elseif(isset($_SESSION['gmact_id'])){
         $gmact_id = $_SESSION['gmact_id'];
    } else  {
       $_SESSION['error'] = "Missing gmact_id from QRGameShowResults";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['gmact_id'] = $gmact_id;
    
      if (isset($_POST['gameactivity_id'])){
        $gameactivity_id = $_POST['gameactivity_id'];
    }  elseif(isset($_SESSION['gameactivity_id'])){
         $gameactivity_id = $_SESSION['gameactivity_id'];
    } else  {
       $_SESSION['error'] = "Missing gameactivity_id from QRGameShowResults";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['gameactivity_id'] = $gameactivity_id;
    
      if (isset($_POST['name'])){
        $name = $_POST['name'];
    }  elseif(isset($_SESSION['name'])){
         $name = $_SESSION['name'];
    } else  {
       $_SESSION['error'] = "Missing name from QRGameShowResults";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['name'] = $name;
    
      if (isset($_POST['problem_id'])){
        $problem_id = $_POST['problem_id'];
    }  elseif(isset($_SESSION['problem_id'])){
         $problem_id = $_SESSION['problem_id'];
    } else  {
       $_SESSION['error'] = "Missing problem_id from QRGameShowResults";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['problem_id'] = $problem_id;
    
   
      if (isset($_POST['dex'])){
        $dex = $_POST['dex'];
    }  elseif(isset($_SESSION['dex'])){
         $dex = $_SESSION['dex'];
    } else  {
       $_SESSION['error'] = "Missing dex from QRGameShowResults";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['dex'] = $dex;
    
     if (isset($_POST['iid'])){
        $iid = $_POST['iid'];
    }  elseif(isset($_SESSION['iid'])){
         $iid = $_SESSION['iid'];
    } else  {
       $_SESSION['error'] = "Missing iid from QRGameShowResults";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['iid'] = $iid;
   

 if ( isset($_POST['gameactivity_id']) ) {
			$gameactivity_id = $_POST['gameactivity_id'];
          
           $stmt = $pdo->prepare("SELECT *  FROM `Gameactivity` WHERE gameactivity_id = :gameactivity_id ");
			$stmt->execute(array(":gameactivity_id" => $gameactivity_id));
			$row = $stmt -> fetch();
            $team_score = $row['team_score'];
            $game_id = $row['game_id'];
           echo ('<h2> Your Average Team Score for Game '.$game_id.' was '.$team_score.'</h2>');
	}  
// Cleanup the data 
        $sql = "DELETE from `Gameactivity` WHERE game_id = :game_id AND iid = :iid";
        $count=$pdo->prepare($sql);
        $count->execute(array(
            ":game_id" => $game_id,
             ":iid" => $iid,
        ));

        $no=$count->rowCount();

       $sql = "DELETE from `Gmact` WHERE gmact_id = :gmact_id";
        $count=$pdo->prepare($sql);
        $count->execute(array(
            ":gmact_id" => $gmact_id,
        ));

        $num=$count->rowCount();




?>

<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRPGames</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
</head>

<body>
<header>
<h1>Quick Response Game </h1>
</header>



<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}




?>
<h3> Name: <?php echo($name);?> </h3>
<h3> Game number: <?php echo($game_id);?> </h3>
<h3> PIN: <?php echo($pin);?> </h3>
<h3> Team Number: <?php echo($team_id);?> </h3>
<form action = "index.php" method = "POST" id = "the_form">
	

	<p><input type = "submit" value="Submit" id = "submit_id" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>
		<p><input type="hidden" name="game_id" id = "game_id" size=3 value="<?php echo (htmlentities($game_id))?>"  ></p>
        <p><input type="hidden" name="name" id = "name" size=3 value="<?php echo (htmlentities($name))?>"  ></p>
        <p><input type="hidden" name="problem_id" id = "problem_id" size=3 value="<?php echo (htmlentities($problem_id))?>"  ></p>
        <p><input type="hidden" name="gmact_id" id = "gmact_id" size=3 value="<?php echo (htmlentities($gmact_id))?>"  ></p>
        <p><input type="hidden" name="gameactivity_id" id = "gameactivity_id" size=3 value="<?php echo (htmlentities($gameactivity_id))?>"  ></p>
     
        <p><input type="hidden" name="team_id" id = "team_id" size=3 value="<?php echo (htmlentities($team_id))?>"  ></p>
		<p><input type="hidden" id = "dex" name="dex" size=3 value="<?php echo (htmlentities($dex))?>"  ></p>
        <p><input type="hidden" id = "pin" name="pin" size=3 value="<?php echo (htmlentities($pin))?>"  ></p>
    
    </form>

	<script>
			$(document).ready( function () {
                  $('#submit_id').hide();
				// get the current phase
				var gmact_id = $("#gmact_id").val();
				console.log ('gmact_id = ',gmact_id);
			

                  var request;
                function fetchPhase() {
                    request = $.ajax({
                        type: "POST",
                        url: "fetchPhase.php",
                        data: "gmact_id="+gmact_id,
                        success: function(data){
                           try{
                                var arrn = JSON.parse(data);
                            }
                            catch(err) {
                                alert ('game data unavailable Data not found');
                                alert (err);
                                return;
                            }
                            
                             var phase = arrn.phase;
                            var end_of_phase = arrn.end_of_phase;
                            	console.log ('phase = ',phase);
                           if(phase <= 0){  // submit away work time has eneded
                               SubmitAway(); 
                            }
                        }
                    });
                }
                setInterval(function() {
                    if (request) request.abort();
                    fetchPhase();
                }, 1000);

 
                
                     function SubmitAway() { 
                  
                        document.getElementById('the_form').submit();
                    }
                });
                
                
                
			
		
		</script>



</body>
</html>

