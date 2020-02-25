<?php
	session_start();
	require_once "pdo.php";
	
// This is the program that gives them the values for the input parameters and is called by QRGameRouter.php  script determines if Phase has advanced  fetchworktime.php to start the timer
	// first do some error checking on the input.  If it is not OK set the session failure and send them back to QRGameIndex.
	// Comming from index the game number will be a POST where if we are coming from a QRcode of the game it will be a GET
    
    if (isset($_SESSION['phase'])){
        $phase = $_SESSION['phase'];
    }  else {
       $_SESSION['error'] = "Missing phase in QRGamePblmPlan";
	  header('Location: index.php');
	  return;   
    }
    
    if (isset($_SESSION['game_id'])){
        $game_id = $_SESSION['game_id'];
    }  else {
       $_SESSION['error'] = "Missing game_id in QRGamePblmPlan";
	  header('Location: index.php');
	  return;   
    }
   
    if (isset($_SESSION['gmact_id'])){
        $gmact_id = $_SESSION['gmact_id'];
    }  else {
       $_SESSION['error'] = "Missing gmact_id in QRGamePblmPlan";
	  header('Location: index.php');
	  return;   
    }
   
    if (isset($_SESSION['gameactivity_id'])){
        $gameactivity_id = $_SESSION['gameactivity_id'];
    }  else {
       $_SESSION['error'] = "Missing gameactivity_id in QRGamePblmPlan";
	  header('Location: index.php');
	  return;   
    }
    
     if (isset($_SESSION['name'])){
        $name = $_SESSION['name'];
    }  else {
       $_SESSION['error'] = "Missing name in QRGamePblmPlan";
	  header('Location: index.php');
	  return;   
    }
    
     if (isset($_SESSION['pin'])){
        $pin = $_SESSION['pin'];
    }  else {
       $_SESSION['error'] = "Missing pin in QRGamePblmPlan";
	  header('Location: index.php');
	  return;   
    }
    
     if (isset($_SESSION['team_id'])){
        $team_id = $_SESSION['team_id'];
    }  else {
       $_SESSION['error'] = "Missing team_id in QRGamePblmPlan";
	  header('Location: index.php');
	  return;   
    }
    
     if (isset($_SESSION['dex'])){
        $dex = $_SESSION['dex'];
    }  else {
       $_SESSION['error'] = "Missing dex in QRGamePblmPlan";
	  header('Location: index.php');
	  return;   
    }
    
     if (isset($_SESSION['problem_id'])){
        $problem_id = $_SESSION['problem_id'];
    }  else {
       $_SESSION['error'] = "Missing problem_id in QRGamePblmPlan";
	  header('Location: index.php');
	  return;   
    }
    
    
    
   
   
		
		
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
	<h2> <div id = "message"> Planning Stage - <font color = "red">Silent Phase </font> </p>
    
    
    <font class = "show" color = "red">Write </font> down your plan. Prompts:</h2>	
    <ol class = "show"> <li> Principles and equations?</li>
   <li><b>Diagrams</b> / Tables? </li>
   <li>Additional information?</li>
    <li>Assumptions/basis?</li>
    <li>Procedure / Algorithm?</li>
     <li>Hardest part?</li>
     <li>Where to start?</li>
    </ol> 		
	</div>

	<form action = "QRGameGetSum.php" method = "POST" id="the_form" >
	<!--	<p><font color=#003399>Problem Number: </font><input type="text" name="problem_id" size=3 value="<?php echo (htmlentities($p_num))?>"  ></p> -->
		<p><input type="hidden" name="game_id" id = "game_id" size=3 value="<?php echo (htmlentities($game_id))?>"  ></p>
        <p><input type="hidden" name="name" id = "name" size=3 value="<?php echo (htmlentities($name))?>"  ></p>
        <p><input type="hidden" name="problem_id" id = "problem_id" size=3 value="<?php echo (htmlentities($problem_id))?>"  ></p>
        <p><input type="hidden" name="gmact_id" id = "gmact_id" size=3 value="<?php echo (htmlentities($gmact_id))?>"  ></p>
        <p><input type="hidden" name="gameactivity_id" id = "gameactivity_id" size=3 value="<?php echo (htmlentities($gameactivity_id))?>"  ></p>
     
        <p><input type="hidden" name="team_id" id = "team_id" size=3 value="<?php echo (htmlentities($team_id))?>"  ></p>
		<p><input type="hidden" id = "dex" name="dex" size=3 value="<?php echo (htmlentities($dex))?>"  ></p>
        <p><input type="hidden" id = "pin" name="pin" size=3 value="<?php echo (htmlentities($pin))?>"  ></p>
		
        
        
		<p><b><input type = "submit" id = "submit_id" value="Get Problem Parameters" size="30" style = "width: 50%; background-color: #003399; color: white"/> &nbsp &nbsp </b></p>
		</form>
        <h2><font color = "black"> <span id = "message2" class = "show">  </span> </font>  </h2>	
		<script>
			
			$(document).ready( function () {
                
                  $('#submit_id').hide();
                    
				// get the how long the students have to solve the problem
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
                            if(phase <= 0 || phase == null){  // preptime waiting to start
                                	
                                  
                                    $('.show').hide();
                                   // $('#message').hide();
                                    Discuss("Waiting to Start ");
                              } else if(phase == 1){ //preptime silent
                            
                                    Discuss("Writing ");
                                      $('.show').show();
                            } else if(phase == 2){ //preptime talk
                            
                                    Discuss("Group Discussion ");
                                     document.body.style.background = "SkyBlue";
                                     
                            } else if (phase == 3){ // wait
                                 Discuss("Questions for Instructor? ");
                                    document.body.style.background = "LightGoldenRodYellow";
                            } else {  // go to QRGameGetIn.php
                                 $('#submit_id').show();
                               SubmitAway(); 
                                
                            }
                        }
                    });
                }
                setInterval(function() {
                    if (request) request.abort();
                    fetchPhase();
                }, 1000);
                  function Discuss(x) { 
                     
                         $('#message').html('Planning Stage - <font color = "red">'+ x +'</font>Phase');
                       
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

