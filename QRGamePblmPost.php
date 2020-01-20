<?php
	session_start();
	require_once "pdo.php";
	
	// this is called by stop game and is for the disscussion of the 
	// Comming from index the game number will be a POST where if we are coming from a QRcode of the game it will be a GET
   
     if (isset($_POST['game_id'])){
        $game_id = $_POST['game_id'];
    }  else {
       $_SESSION['error'] = "Missing game number- this one";
	  header('Location: index.php');
	  return;   
    }
     /*
    if($_SESSION['game_progress']==5){
        
     echo ('reloaded page');
	  return;
    }
    
	if ($game_id<1 || $game_id>1000000) {
	  $_SESSION['error'] = "game number out of range";
	  header('Location: index.php');
	  return;
	} */
	
	
	$_SESSION['game_id'] = $game_id;

	/* if ( isset($_POST['alt_dex']) ) {
		$alt_dex = $_POST['alt_dex'];
	} elseif (isset($_SESSION['alt_dex'])){
		$alt_dex = $_SESSION['alt_dex'];
	} else {
	  $_SESSION['error'] = "Missing alt dex";
	  header('Location: index.php');
	  return;
	}
    $_SESSION['game_progress']=; //put this in to stop a page reload from restarting the clock
    
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
         */
        
		// $gameData=$row;	
		
       // echo ($gameData['post_time']);
		

		
		
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

	<h2> <span id = "message"> Post Problem Reflection - <font color = "red">Silent Phase </font> </p>
    Write  down your response </h2>	</span>		
	

	<form action = "index.php" method = "POST" id="the_form" >
	<!--	<p><font color=#003399>Problem Number: </font><input type="text" name="problem_id" size=3 value="<?php echo (htmlentities($p_num))?>"  ></p> -->
		<p><font color=#003399> </font><input type="hidden" name="game_id" id = "game_id" size=3 value="<?php echo (htmlentities($game_id))?>"  ></p>
		<p><font color=#003399> </font><input type="hidden" id = "problem_id" name="problem_id" size=3 value="<?php echo (htmlentities($problem_id))?>"  ></p>
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
				
                var post_time = arrn.post_time;
               
                var silent_post = post_time/2
				var silent_time = AddMinutesToDate (now,silent_post);
				var stop_time = AddMinutesToDate (now,post_time);
				$('#submit_id').hide();
                $('#defaultCountdown').hide();
                  $('#message2').hide();
				console.log ('now = ',now);
				console.log ('post_time = ',post_time);
                console.log ('silent_post_time = ',silent_post);
				console.log ('stop_time = ',stop_time);
               
					
                // http://keith-wood.name/countdownRef.html
                
                    $('#silentCountdown').countdown({until: silent_time, format: 'ms',  layout: '{d<}{dn} {dl} {d>}{h<}{hn} {hl} {h>}{m<}{mn} {ml} {m>}{s<}{sn} {sl}{s>}',onExpiry: Discuss}); 

                    $('#defaultCountdown').countdown({until: stop_time, format: 'ms',  layout: '{d<}{dn} {dl} {d>}{h<}{hn} {hl} {h>}{m<}{mn} {ml} {m>}{s<}{sn} {sl}{s>}',onExpiry: SubmitAway}); 

                    function Discuss() { 
                      $('#defaultCountdown').show();
                         $('#message').text('Reflection Stage - Group Discussion Phase');
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

