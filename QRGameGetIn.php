<?php
	session_start();
	require_once "pdo.php";
	
// This is the program that gives them the values for the input parameters and is called by QRGameProblemPlan.php  script calles fetchworktime.php to start the timer
	// first do some error checking on the input.  If it is not OK set the session failure and send them back to QRGameIndex.
	// Comming from index the game number will be a POST where if we are coming from a QRcode of the game it will be a GET
    
    if (isset($_POST['game_id'])){
        $game_id = $_POST['game_id'];
    }  elseif(isset($_SESSION['game_id'])){
         $game_id = $_SESSION['game_id'];
    } else  {
       $_SESSION['error'] = "Missing game_id from QRGameGetin";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['game_id'] = $game_id;
   

   
     if (isset($_POST['pin'])){
        $pin = $_POST['pin'];
    }  elseif(isset($_SESSION['pin'])){
         $pin = $_SESSION['pin'];
    } else  {
       $_SESSION['error'] = "Missing pin from QRGameGetin";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['pin'] = $pin;
    
    
      if (isset($_POST['team_id'])){
        $team_id = $_POST['team_id'];
    }  elseif(isset($_SESSION['team_id'])){
         $team_id = $_SESSION['team_id'];
    } else  {
       $_SESSION['error'] = "Missing team_id from QRGameGetin";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['team_id'] = $team_id;
    
      if (isset($_POST['gmact_id'])){
        $gmact_id = $_POST['gmact_id'];
    }  elseif(isset($_SESSION['gmact_id'])){
         $gmact_id = $_SESSION['gmact_id'];
    } else  {
       $_SESSION['error'] = "Missing gmact_id from QRGameGetin";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['gmact_id'] = $gmact_id;
    
      if (isset($_POST['gameactivity_id'])){
        $gameactivity_id = $_POST['gameactivity_id'];
    }  elseif(isset($_SESSION['gameactivity_id'])){
         $gameactivity_id = $_SESSION['gameactivity_id'];
    } else  {
       $_SESSION['error'] = "Missing gameactivity_id from QRGameGetin";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['gameactivity_id'] = $gameactivity_id;
    
      if (isset($_POST['name'])){
        $name = $_POST['name'];
    }  elseif(isset($_SESSION['name'])){
         $name = $_SESSION['name'];
    } else  {
       $_SESSION['error'] = "Missing name from QRGameGetin";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['name'] = $name;
    
      if (isset($_POST['problem_id'])){
        $problem_id = $_POST['problem_id'];
    }  elseif(isset($_SESSION['problem_id'])){
         $problem_id = $_SESSION['problem_id'];
    } else  {
       $_SESSION['error'] = "Missing problem_id from QRGameGetin";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['problem_id'] = $problem_id;
    
   
      if (isset($_POST['dex'])){
        $dex = $_POST['dex'];
    }  elseif(isset($_SESSION['dex'])){
         $dex = $_SESSION['dex'];
    } else  {
       $_SESSION['error'] = "Missing dex from QRGameGetin";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['dex'] = $dex;
   
   
    
	if ($game_id<1 || $game_id>1000000) {
	  $_SESSION['error'] = "game number out of range";
	  header('Location: index.php');
	  return;
	}
	
	  


		$stmt = $pdo->prepare("SELECT * FROM Game WHERE game_id = :game_id");
		$stmt->execute(array(":game_id" => $game_id));
		$row = $stmt -> fetch();
		if ( $row === false ) {
			$_SESSION['error'] = 'Bad value for game_id or game_id not active';
			header( 'Location: index.php' ) ;
			return;
		}
        
        
		$gameData=$row;	

		
		// $problem_id = $gameData['problem_id'];
		// $dex = $gameData['dex'];
		
		$rect_length = $gameData['rect_length'];
		$oval_length = $gameData['oval_length'];
		$trap_length = $gameData['trap_length'];
		$hexa_length = $gameData['hexa_length'];
		$prep_time = $gameData['prep_time'];
		$work_time = $gameData['work_time'];
		$post_time = $gameData['post_time'];
		
		if ($rect_length == null || strlen($rect_length)<1){$rect_length = 20;}
		
		
	
        // write to the Game_activity table the values 
        
     
        
// will sum them up in the next file down QRGameCheck


     
    

		$stmt = $pdo->prepare("SELECT * FROM `Input` where problem_id = :problem_id AND dex = :dex");
		$stmt->execute(array(":problem_id" => $problem_id, ":dex" => $dex));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		//$row = $stmt -> fetch();
			if ( $row === false ) {
				$_SESSION['error'] = 'could not read row of table input for game variables dex ='.$dex.' problem_id ='.$problem_id;
				header('Location: index.php');
				return;
			}	
		//$rect_length = 10;		
		//	echo ($rect_length);
		
			$rect_val = $row[$gameData['rect_vnum']];
			$oval_val = $row[$gameData['oval_vnum']];
			$trap_val = $row[$gameData['trap_vnum']];
			$hexa_val = $row[$gameData['hexa_vnum']];
			
			
			
			$char_to_width = 24;
			$rect_width = $rect_length * $char_to_width+5;
			$oval_width = $oval_length * $char_to_width+5;
			$trap_width = $trap_length * $char_to_width +25;
			$hexa_width = $hexa_length * $char_to_width+10;
			
			
			
			
			
			$rect_svg = $rect_width+32;
			$oval_svg = $oval_width+32;
			$trap_svg = $trap_width+32;
			
			$trapx_pt2 = $trap_width-15;
			
			$hexa_svg = $hexa_width+32;
			$hexax_pt2 = $hexa_width-10;
												
			
			if(strtolower($rect_val) == 'null'){$rect_val = ""; $rect_width = 0; $rect_svg = 0; $rect_pt2 = 0;}	
			if(strtolower($oval_val) == 'null'){$oval_val = ""; $oval_width = 0; $oval_svg = 0; $oval_pt2 = 0;}	
			if(strtolower($trap_val) == 'null'){$trap_val = ""; $trap_width = 0; $trap_svg = 0; $trap_pt2 = 0;}		
			if(strtolower($hexa_val) == 'null'){$hexa_val = ""; $hexa_width = 0; $hexa_svg = 0; $hexa_pt2 = 0;}								

		
		
		
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


<h3> Name: <?php echo($name);?> </h3>
<h3> Game number: <?php echo($game_id);?> </h3>
<h3> PIN: <?php echo($pin);?> </h3>
<h3> Team Number: <?php echo($team_id);?> </h3>

	<h4><font color = "red"> Write</font> the values on your sheet then proceed to checker </h4>			


	<svg  width=<?php echo($rect_svg); ?> height="100" >
	  <rect  fill="white" stroke="blue" stroke-width="4" width="<?php echo($rect_width);?>" height = "50" x="15" y = "5"/>
	  <text x="<?php echo($rect_width/2+12);?>" y="40" text-anchor="middle" fill="black" font-size="30"> <?php echo ($rect_val);?></text>
	</svg>

	<svg  width=<?php echo($oval_svg) ?> height="100" >
	  <rect  fill="white" stroke="red" stroke-width="4" width="<?php echo($oval_width);?>" rx = "25"  ry = "25" height = "50" x="15" y = "5"/>
	  <text x="<?php echo($oval_width/2+14);?>" y="40" text-anchor="middle" fill="black" font-size="30"> <?php echo ($oval_val);?></text>
	</svg>

	<svg  width=<?php echo($trap_svg) ?> height="100" >
	  <polygon  fill="white" stroke="green" stroke-width="4" points="20,5 <?php echo($trapx_pt2);?>,5 <?php echo($trap_width);?>,50 5,50"/>
	  <text x="<?php echo($trap_width/2+4);?>" y="40" text-anchor="middle" fill="black" font-size="30"> <?php echo ($trap_val);?></text>
	</svg>

	<svg  width=<?php echo($hexa_svg) ?> height="100" >
	  <polygon  fill="white" stroke="#E67E22" stroke-width="4" points="15,5 <?php echo($hexax_pt2);?>,5 <?php echo($hexa_width);?>,30 <?php echo($hexax_pt2);?>,50 15,50 5,30"/>
	  <text x="<?php echo($hexa_width/2+4);?>" y="40" text-anchor="middle" fill="black" font-size="30"> <?php echo ($hexa_val);?></text>
	</svg>


	<form action = "QRGameCheck.php" method = "POST" id = "the_form" >
	<!--	<p><font color=#003399>Problem Number: </font><input type="text" name="problem_id" size=3 value="<?php echo (htmlentities($p_num))?>"  ></p> -->
			<p><input type="hidden" name="game_id" id = "game_id" size=3 value="<?php echo (htmlentities($game_id))?>"  ></p>
        <p><input type="hidden" name="name" id = "name" size=3 value="<?php echo (htmlentities($name))?>"  ></p>
        <p><input type="hidden" name="problem_id" id = "problem_id" size=3 value="<?php echo (htmlentities($problem_id))?>"  ></p>
        <p><input type="hidden" name="gmact_id" id = "gmact_id" size=3 value="<?php echo (htmlentities($gmact_id))?>"  ></p>
        <p><input type="hidden" name="gameactivity_id" id = "gameactivity_id" size=3 value="<?php echo (htmlentities($gameactivity_id))?>"  ></p>
     
        <p><input type="hidden" name="team_id" id = "team_id" size=3 value="<?php echo (htmlentities($team_id))?>"  ></p>
		<p><input type="hidden" id = "dex" name="dex" size=3 value="<?php echo (htmlentities($dex))?>"  ></p>
        <p><input type="hidden" id = "pin" name="pin" size=3 value="<?php echo (htmlentities($pin))?>"  ></p>
		
        
		<p><b><input type = "submit" id = "submit_id" value="Go to Checker" size="30" style = "width: 50%; background-color: #003399; color: white"/> &nbsp &nbsp </b></p>
		</form>

		<script>
			$(document).ready( function () {
                
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
                           if(phase > 4){  // submit away work time has eneded
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

