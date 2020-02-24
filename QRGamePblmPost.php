<?php
	session_start();
	require_once "pdo.php";
	
	// this is called by stop game and is for the disscussion of the 
	// Comming from index the game number will be a POST where if we are coming from a QRcode of the game it will be a GET
   
   
      if (isset($_POST['game_id'])){
        $game_id = $_POST['game_id'];
    }  elseif(isset($_SESSION['game_id'])){
         $game_id = $_SESSION['game_id'];
    } else  {
       $_SESSION['error'] = "Missing game_id from QRGamePblmPost";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['game_id'] = $game_id;
   

   
     if (isset($_POST['pin'])){
        $pin = $_POST['pin'];
    }  elseif(isset($_SESSION['pin'])){
         $pin = $_SESSION['pin'];
    } else  {
       $_SESSION['error'] = "Missing pin from QRGamePblmPost";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['pin'] = $pin;
    
    
      if (isset($_POST['team_id'])){
        $team_id = $_POST['team_id'];
    }  elseif(isset($_SESSION['team_id'])){
         $team_id = $_SESSION['team_id'];
    } else  {
       $_SESSION['error'] = "Missing team_id from QRGamePblmPost";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['team_id'] = $team_id;
    
      if (isset($_POST['gmact_id'])){
        $gmact_id = $_POST['gmact_id'];
    }  elseif(isset($_SESSION['gmact_id'])){
         $gmact_id = $_SESSION['gmact_id'];
    } else  {
       $_SESSION['error'] = "Missing gmact_id from QRGamePblmPost";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['gmact_id'] = $gmact_id;
    
      if (isset($_POST['gameactivity_id'])){
        $gameactivity_id = $_POST['gameactivity_id'];
    }  elseif(isset($_SESSION['gameactivity_id'])){
         $gameactivity_id = $_SESSION['gameactivity_id'];
    } else  {
       $_SESSION['error'] = "Missing gameactivity_id from QRGamePblmPost";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['gameactivity_id'] = $gameactivity_id;
    
      if (isset($_POST['name'])){
        $name = $_POST['name'];
    }  elseif(isset($_SESSION['name'])){
         $name = $_SESSION['name'];
    } else  {
       $_SESSION['error'] = "Missing name from QRGamePblmPost";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['name'] = $name;
    
      if (isset($_POST['problem_id'])){
        $problem_id = $_POST['problem_id'];
    }  elseif(isset($_SESSION['problem_id'])){
         $problem_id = $_SESSION['problem_id'];
    } else  {
       $_SESSION['error'] = "Missing problem_id from QRGamePblmPost";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['problem_id'] = $problem_id;
    
   
      if (isset($_POST['dex'])){
        $dex = $_POST['dex'];
    }  elseif(isset($_SESSION['dex'])){
         $dex = $_SESSION['dex'];
    } else  {
       $_SESSION['error'] = "Missing dex from QRGamePblmPost";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['dex'] = $dex;
   
   
  
          
           $stmt = $pdo->prepare("SELECT *  FROM `Gameactivity` WHERE gameactivity_id = :gameactivity_id ");
			$stmt->execute(array(":gameactivity_id" => $gameactivity_id));
			$row = $stmt -> fetch();
            $game_id = $row['game_id'];
            $team_id = $row['team_id'];

         $stmt = $pdo->prepare("SELECT AVG(`score`) AS avg_score FROM `Gameactivity` WHERE game_id = :game_id AND team_id = :team_id AND created_at >= DATE_SUB(NOW(),INTERVAL 2 HOUR)");
			$stmt->execute(array(":game_id" => $game_id, ":team_id" => $team_id));
			$row = $stmt -> fetch();
            $team_score = $row['avg_score'];
			// echo ($row['ans_sumb']);
            
    
            $sql = "UPDATE `Gameactivity` 
                    SET team_score = :team_score
                    WHERE gameactivity_id = :gameactivity_id";
             $stmt = $pdo->prepare($sql);
              $stmt -> execute(array(
                ':gameactivity_id' => $gameactivity_id,
                  ':team_score' => $team_score,
                ));    
                        
                       
    
    
	

		
		
?>

	<!DOCTYPE html>
	<html lang = "en">
	<head>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<meta Charset = "utf-8">
	<title>QRPGames</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 
	<link rel="stylesheet" type="text/css" href="jquery.countdown.css"> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript" src="jquery.plugin.js"></script> 
	<script type="text/javascript" src="jquery.countdown.js"></script>
	

	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
	
	
	
	
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

<h3> Game number: <?php echo($game_id);?> </h3>

	<h2> <span id = "message"> Post Problem Reflection - <font color = "red">Writing Phase </font> </p>
   <font color = "red"> Write </font> down your response</h2>	
   <p> Also, how did your group perform and how could they have done better?</p></span>		
	

	<form action = "index.php" method = "POST" id="the_form" >
	<!--	<p><font color=#003399>Problem Number: </font><input type="text" name="problem_id" size=3 value="<?php echo (htmlentities($p_num))?>"  ></p> -->
            <p><input type="hidden" name="game_id" id = "game_id" size=3 value="<?php echo (htmlentities($game_id))?>"  ></p>
            <p><input type="hidden" name="name" id = "name" size=3 value="<?php echo (htmlentities($name))?>"  ></p>
            <p><input type="hidden" name="problem_id" id = "problem_id" size=3 value="<?php echo (htmlentities($problem_id))?>"  ></p>
            <p><input type="hidden" name="gmact_id" id = "gmact_id" size=3 value="<?php echo (htmlentities($gmact_id))?>"  ></p>
            <p><input type="hidden" name="gameactivity_id" id = "gameactivity_id" size=3 value="<?php echo (htmlentities($gameactivity_id))?>"  ></p>
        
        
		
		<p><b><input type = "submit" id = "submit_id" value="Get Problem Parameters" size="30" style = "width: 50%; background-color: #003399; color: white"/> &nbsp &nbsp </b></p>
		</form>
        <h2><font color = "black"> <span id = "message2"> Share and Listen </span> </font>  </h2>	
		<script>
			
			$(document).ready( function () {
                
                  $('#submit_id').hide();
                
				// get the how long the students have to solve the problem
				var gmact_id = $("#gmact_id").val();
				console.log ('gmact_id = ',gmact_id);
				
                window.setInterval(function(){
                      /// call your function here
                      $.post('fetchPhase.php', {gmact_id : gmact_id, }, function(data){
				
                            try{
                                var arrn = JSON.parse(data);
                            }
                            catch(err) {
                                alert ('game data unavailable Data not found');
                                alert (err);
                            }
                            
                             var phase = arrn.phase;
                            var end_of_phase = arrn.end_of_phase;
                            	console.log ('phase = ',phase);
                            if(phase == 6){  // preptime silent
                                	
                                    Discuss("Writing ");
                                  // $('#message2').hide();
                                
                            } else if(phase == 7){ //preptime talk
                            
                                    Discuss("Group Discussion ");
                                    document.body.style.background = "SkyBlue";
                            } else if (phase == 8){ // wait
                                 Discuss("Class Question and Discussion ");
                                  document.body.style.background = "LightGoldenRodYellow";
                            } else {  // go to QRGameGetin.php
                                 $('#submit_id').show();
                               SubmitAway(); 
                                
                            }
                            
                            
                            
                        });
                      
                    }, 1000);  // calling the function every 1 second
                
                  function Discuss(x) { 
                     
                         $('#message').html('Reflections Stage - <font color = "red">'+ x +'</font>Phase');
                        
                         // flash the message
                          $('#message2').show();
                     /* 
                            var f = document.getElementById('message2');
                            setInterval(function() {
                                f.style.display = (f.style.display == 'none' ? '' : 'none');
                            }, 1000);
                     */
                    
                    }
                
                     function SubmitAway() { 
                  
                        document.getElementById('the_form').submit();
                    }
                });
                
                
                
                    
		</script>

	</body>
	</html>

