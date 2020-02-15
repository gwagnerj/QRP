<?php
	session_start();
	require_once "pdo.php";
	
// This is the program that gives them the values for the input parameters and is called by index.php  script calles fetchworktime.php to start the timer
	// first do some error checking on the input.  If it is not OK set the session failure and send them back to QRGameIndex.
	// Comming from index the game number will be a POST where if we are coming from a QRcode of the game it will be a GET
    
    if (isset($_POST['game_id'])){
        $game_id = $_POST['game_id'];
    }  else {
       $_SESSION['error'] = "Missing game number- this one";
	  header('Location: index.php');
	  return;   
    }
    
    if($_SESSION['game_progress']==1){
        
      header('Location: index.php');
	  return;
    }
    
	if ($game_id<1 || $game_id>1000000) {
	  $_SESSION['error'] = "game number out of range";
	  header('Location: index.php');
	  return;
	}
	
	
	$_SESSION['game_id'] = $game_id;

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
                
     if ( isset($_POST['team_num']) ) {
		$team_num = $_POST['team_num'];
	} elseif (isset($_SESSION['team_num'])){
		$team_num = $_SESSION['team_num'];
	} else {
        $team_num = 10;
	 // uncomment this when we do something with team number
     /* $_SESSION['error'] = "Missing team_num";
	  header('Location: index.php');
	  return; */
	}   
        $_SESSION['team_num'] = $team_num;
                
    $_SESSION['game_progress']=1; //put this in to stop a page reload from restarting the clock
    
	$_SESSION['count']=0;
	$_SESSION['startTime'] = time();

		$stmt = $pdo->prepare("SELECT * FROM Game WHERE game_id = :game_id");
		$stmt->execute(array(":game_id" => $game_id));
		//$row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $row = $stmt -> fetch();
		if ( $row === false ) {
			$_SESSION['error'] = 'Bad value for game_id or game_id not active';
			header( 'Location: index.php' ) ;
			return;
		}
        
        
		$gameData=$row;	
		
       // echo ($gameData['prep_time']);
		

		
		
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
<h3> PIN: <?php echo($pin);?> </h3>
<h3> alt_dex: <?php echo($alt_dex);?> </h3>
	<h2> <span id = "message"> Planning Stage - <font color = "red">Silent Phase </font> </p>
    <font color = "red">Write </font> down your plan. Prompts:</h2>	
    <ol> <li> Principles and equations?</li>
   <li><b>Diagrams</b> / Tables? </li>
   <li>Additional information?</li>
    <li>Assumptions/basis?</li>
    <li>Procedure / Algorithm?</li>
     <li>Hardest part?</li>
     <li>Where to start?</li>
    </ol> </span>		
	

	<form action = "QRGameGetIn.php" method = "POST" id="the_form" >
	<!--	<p><font color=#003399>Problem Number: </font><input type="text" name="problem_id" size=3 value="<?php echo (htmlentities($p_num))?>"  ></p> -->
		<p><font color=#003399> </font><input type="hidden" name="game_id" id = "game_id" size=3 value="<?php echo (htmlentities($game_id))?>"  ></p>
		<p><font color=#003399> </font><input type="hidden" id = "alt_dex" name="alt_dex" size=3 value="<?php echo (htmlentities($alt_dex))?>"  ></p>
		<p><font color=#003399> </font><input type="hidden" id = "problem_id" name="problem_id" size=3 value="<?php echo (htmlentities($problem_id))?>"  ></p>
		<p><font color=#003399> </font><input type="hidden" id = "stop_time" name="stop_time" size=3 value="3"  ></p>
		<div id="silentCountdown"> </div>
		<div id="defaultCountdown"> </div>
		<p><b><input type = "submit" id = "submit_id" value="Get Problem Parameters" size="30" style = "width: 50%; background-color: #003399; color: white"/> &nbsp &nbsp </b></p>
		</form>
        <h2><font color = "black"> <span id = "message2"> Share and Listen </span> </font>  </h2>	
		<script>
			
			$(document).ready( function () {
				// get the how long the students have to solve the problem
				var game_id = $("#game_id").val();
				console.log ('game_id = ',game_id);
				
				$.post('fetchWorkTime.php', {game_id : game_id, }, function(data){
				
				try{
					var arrn = JSON.parse(data);
				}
				catch(err) {
					alert ('game data unavailable Data not found');
					alert (err);
				}

				function AddMinutesToDate(date, minutes) {
					return new Date(date.getTime() + minutes*60000);
				}
				function DateFormat(date){
				  var days=date.getDate();
				  var year=date.getFullYear();
				  var month=(date.getMonth()+1);
				  var hours = date.getHours();
				  var minutes = date.getMinutes();
				  minutes = minutes < 10 ? '0'+ minutes : minutes;
				  var strTime =days+'/'+month+'/'+year+'/ '+hours + ':' + minutes;
				  return strTime;
				}
				
				var now = new Date();
				
                var prep_time = arrn.prep_time;
                var work_time = arrn.work_time;
                var silent_prep = prep_time/2
				var silent_time = AddMinutesToDate (now,silent_prep);
				var stop_time = AddMinutesToDate (now,prep_time);
				$("#stop_time").val(stop_time);  // This is the value that gets fed to QRGameGetIn
				$('#submit_id').hide();
                $('#defaultCountdown').hide();
                  $('#message2').hide();
				console.log ('now = ',now);
				console.log ('work_time = ',work_time);
				console.log ('prep_time = ',prep_time);
                console.log ('silent_prep_time = ',silent_prep);
				console.log ('stop_time = ',stop_time);
               
					
                // http://keith-wood.name/countdownRef.html
                
                    $('#silentCountdown').countdown({until: silent_time, format: 'ms',  layout: '{d<}{dn} {dl} {d>}{h<}{hn} {hl} {h>}{m<}{mn} {ml} {m>}{s<}{sn} {sl}{s>}',onExpiry: Discuss}); 

                    $('#defaultCountdown').countdown({until: stop_time, format: 'ms',  layout: '{d<}{dn} {dl} {d>}{h<}{hn} {hl} {h>}{m<}{mn} {ml} {m>}{s<}{sn} {sl}{s>}',onExpiry: SubmitAway}); 

                    function Discuss() { 
                      $('#defaultCountdown').show();
                         $('#message').html('Planning Stage - <font color = "red"> Group Discussion </font>Phase');
                        document.body.style.background = "SkyBlue";
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
                    
                   // alert('We can now submit');
                        document.getElementById('the_form').submit();
                    }
           
				});		
			});
                    
		</script>

	</body>
	</html>

