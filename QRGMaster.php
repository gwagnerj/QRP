<?php
	require_once "pdo.php";
	session_start();
	

 /*     
 this will be called form the QRGameMasterStart.php with the game_id as a POST 
 Validity will be checked here and sent back to QRGameMasterStart.php  if it is not valid
 This will give control of the game on the fly allowing the GM to change to timers and phase of the game 
 it will also have a link way to monitor the game in a separate tab say QRGameMonitor.php
 */

//Check the input - coming from the QRGameMasterStart.php

		if ( isset($_POST['game_id']) && is_numeric($_POST['game_id']) ) {
			$game_id = $_POST['game_id'];
             
               
            // fill in the initial values for the Gmact table using the values from the Game table
            // get values from Game table
  
            $sql = "SELECT * FROM `Game` WHERE game_id = :game_id";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
				':game_id' => $game_id,
				)); 
            $row1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($row1 as $row){
         //   print_r ($row);
            }
            $iid = $row['iid'];
            $prep_time = round($row['prep_time']/2);
            $prep_time_talk = $prep_time;
            $post_time = round($row['post_time']/2);
            $post_time_talk = $post_time;
            $work_time = $row['work_time'];
            
            // Create the table entry into the Gmact table from the values that were put in the Game table
            
            $sql = 'INSERT INTO `Gmact` (game_id, iid, phase, on_the_fly, prep_time, prep_time_talk,work_time, post_time, post_time_talk,class_time_talk)	
						VALUES (:game_id, :iid, 0,1 , :prep_time, :prep_time_talk,:work_time, :post_time, :post_time_talk, 5)';
				$stmt = $pdo->prepare($sql);
				$stmt -> execute(array(
				':game_id' => $game_id,
				':iid' => $iid,
				':prep_time' => $prep_time,
                ':prep_time_talk' => $prep_time_talk,
				':work_time' => $work_time,
                ':post_time' => $post_time,
                 ':post_time_talk' => $post_time_talk,
				));
                
                // get the gmact_id
           
               $sql = "SELECT `gmact_id` FROM `Gmact` ORDER BY gmact_id DESC LIMIT 1";
               $stmt = $pdo->prepare($sql);
               $stmt -> execute(); 
                $row3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach($row3 as $row){
             //   print_r ($row);
                $gmact_id = $row['gmact_id'];
                }
                echo 'gmact_id = '.$gmact_id;

           } 
            
            
            elseif(isset($_POST['game_num']) && is_numeric($_POST['game_num'])){  // coming in from this sheet
                $game_id = $_POST['game_num'];
                // Update the Gmact with any new values of the input parmaters in the html
              
                $phase = $_POST['phase'];
               if(isset($_POST['phase_inc'])){
                    $phase_inc=$_POST['phase_inc'];
               } else {
                   $phase_inc='not set';
               }
               // test to see if we should increment the current time on submit
               $no_current = "notSet";
                 if(isset($_POST['no_current'])){
                    $no_current=$_POST['no_current'];
               } else {
                   $no_current='notSet';
               }
                $prep_time = $_POST['prep_time'];
                $prep_time_talk = $_POST['prep_time_talk'];
                $work_time = $_POST['work_time'];
                $post_time = $_POST['post_time'];
                $post_time_talk = $_POST['post_time_talk'];
                $class_time_talk = $_POST['class_time_talk'];
                $on_the_fly = $_POST['on_the_fly'];
                $gmact_id = $_POST['gmact_id'];

             if( $phase_inc=="set"){
                   $phase = $phase+1;
                    }
                    
                    // update the end_of_phase time to now plus the time increment in minutes for the current phase
                   
                    if($phase <=0){
                      $t_inc = 0;  
                    } elseif ($phase ==1){
                        $t_inc = $prep_time;
                    } elseif ($phase ==2){  
                        $t_inc = $prep_time_talk;
                    } elseif ($phase ==3){  
                       $t_inc = 0;
                    } elseif ($phase ==4){
                         $t_inc = $work_time;
                    } elseif ($phase ==5){
                         $t_inc = $post_time; 
                    } elseif ($phase ==6){
                         $t_inc = $post_time_talk; 
                    } elseif ($phase ==7){
                          $t_inc = $class_time_talk;  
                    } else {
                        $t_inc = 0;
                      }  
                   
                   if($no_current=="notSet"){ //update the current time
                       $sql = "UPDATE `Gmact`
                              SET `end_of_phase` = (now() + INTERVAL :t_inc MINUTE)
                              WHERE gmact_id = :gmact_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt -> execute(array(
                                ':gmact_id' => $gmact_id,
                                ':t_inc' => $t_inc,
                        ));
                   }
               
                
                
                $sql = "UPDATE `Gmact` 
				SET phase = :phase, prep_time = :prep_time, prep_time_talk = :prep_time_talk, work_time = :work_time, post_time = :post_time, post_time_talk = :post_time_talk, class_time_talk = :class_time_talk, on_the_fly = :on_the_fly
				WHERE gmact_id = :gmact_id";
                $stmt = $pdo->prepare($sql);
                $stmt -> execute(array(
                ':phase' => $phase,
                ':prep_time' => $prep_time,
                ':prep_time_talk' => $prep_time_talk,
                ':work_time' => $work_time,
                ':post_time' => $post_time,
                ':post_time_talk' => $post_time_talk,
                ':class_time_talk' => $class_time_talk,
                ':on_the_fly' => $on_the_fly,
                ':gmact_id' => $gmact_id,
			
			
			));
                
            } else

            {
              $_SESSION['error'] = "Missing game_id";
              header('Location: QRGameMasterStart.php');
             die();
            }
        // do some eror checking on the post data comning in from this page - put the game id in the variable called $game_id



        // get the values from the Gmact table 
        
         $sql_stmt = "SELECT * FROM Gmact WHERE `game_id`= :game_id";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt->execute(array(':game_id' => $game_id));
            $row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row2 as $row3){
                $iid = $row3['iid'];
                $phase = $row3['phase'];
                $prep_time = $row3['prep_time'];
                $prep_time_talk = $row3['prep_time_talk'];
                $work_time = $row3['work_time'];
                $post_time = $row3['post_time'];
                $post_time_talk = $row3['post_time_talk'];
                $class_time_talk = $row3['class_time_talk'];
                $on_the_fly = $row3['on_the_fly'];
                $stop_time = $row3['end_of_phase'];
            }
            if($phase <=0){
              $stage = 'Waiting to Start';  
            } elseif ($phase ==1){
                $stage = 'Planning Stage - Silent';  
                
            } elseif ($phase ==2){  
              $stage = 'Planning Stage - Team Discussion';  
            } elseif ($phase ==3){  
              $stage = 'Planning Stage - Questions?';  
            } elseif ($phase ==4){
                  $stage = 'Working on Problem';  
            } elseif ($phase ==5){
                      $stage = 'Reflection - Silent Phase';  
            } elseif ($phase ==6){
                      $stage = 'Reflection - Team Discussion Phase';  
            } elseif ($phase ==7){
                  $stage = 'Reflection - Class Discussion Phase';  
            } else {
                $stage = 'End - Increment will go back to beginning'; 
                $phase = -1;
            }
            
                
                
	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRGame Master</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="jquery.countdown.css"> 
	
<script type="text/javascript" src="jquery.plugin.js"></script> 
<script type="text/javascript" src="jquery.countdown.js"></script>
</head>

<body>
<header>
<h1>Quick Response Game Time and Phase Screen</h1>
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

<!--<h3>Print the problem statement with "Ctrl P"</h3>
 <p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p>  -->
<form  method="POST" action = "" id = "submit_form">
		
    
     
     
 
      
   <h2> Game Number = <?php echo $game_id; ?></h2>
   <!-- <p> time of the end of this phase = <?php echo $stop_time; ?></p> -->
	<p><input type="hidden" name="game_num" id="game_num" size=3 value=<?php echo($game_id);?> ></p>
    <p style="font-size:100px;">
    <p><input type="hidden" name="gmact_id" id="gmact_id" size=3 value=<?php echo($gmact_id);?> ></p>
    <p><input type="hidden" name="on_the_fly" id="on_the_fly" size=3 value=1 ></p>
    <p><font color=#003399> </font><input type="hidden" id = "stop_time" name="stop_time" size=3 value="<?php echo (htmlentities($stop_time))?>"  ></p>
    <h1><font  style="font-size:300%; color:blue;"> <?php echo $stage;?> </font>
    	<div id="defaultCountdown" style="font-size:300%;color:red;"> </div></h1>
   <p style="font-size:400px;">
    
   <hr> <font color = "blue">manual override </font>
	<p><input type="hidden" name="phase" id="phase" size=3 value=<?php echo($phase);?> ></p>
    <h2><input type="radio" name="phase_inc" value = "set" style="height:15px; width:15px;" id="phase_inc" > Increment Stage</h2>
	<p>Planning Time - Silent Phase: </font><input type="number" name="prep_time" id="prep_time" size=5 value=<?php echo($prep_time);?> >
    <p>Planning Time - Discussion Phase: </font><input type="number" name="prep_time_talk" id="prep_time_talk" size=5 value=<?php echo($prep_time_talk);?> >
    <p>Working Time: <input type="number" name="work_time" id="work_time" size=5 value=<?php echo($work_time);?>></p >
    <p>Reflection Time - Silent Phase: <input type="number" name="post_time" id="post_time" size=5 value=<?php echo($post_time);?> >
     <p>Reflection Time - Team Discussion Phase: <input type="number" name="post_time_talk" id="post_time_talk" size=5 value=<?php echo($post_time_talk);?> >
     <p>Reflection Time - Class - Discussion Phase: <input type="number" name="class_time_talk" id="class_time_talk" size=5 value=<?php echo($class_time_talk);?> >
     <p><input type="radio" name="no_current" value = "set" style="height:10px; width:10px;" id="no_current" > Do Not Change Current Time on Submit</p>
     <p><input type = "submit" value="Submit Changes" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	
	</form>
   
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	
<script>
		$(document).ready( function () {
				var stop_time = $("#stop_time").val();
				var stop_time = new Date(stop_time);
				
                //if you have another AudioContext class use that one, as some browsers have a limit
                var audioCtx = new (window.AudioContext || window.webkitAudioContext || window.audioContext);

                //All arguments are optional:

                //duration of the tone in milliseconds. Default is 500
                //frequency of the tone in hertz. default is 440
                //volume of the tone. Default is 1, off is 0.
                //type of tone. Possible values are sine, square, sawtooth, triangle, and custom. Default is sine.
                //callback to use on end of tone
                function beep(duration, frequency, volume, type, callback) {
                    var oscillator = audioCtx.createOscillator();
                    var gainNode = audioCtx.createGain();

                    oscillator.connect(gainNode);
                    gainNode.connect(audioCtx.destination);

                    if (volume){gainNode.gain.value = volume;}
                    if (frequency){oscillator.frequency.value = frequency;}
                    if (type){oscillator.type = type;}
                    if (callback){oscillator.onended = callback;}

                    oscillator.start(audioCtx.currentTime);
                    oscillator.stop(audioCtx.currentTime + ((duration || 500) / 1000));
                };
                
            
            
            function highlightLast(periods) { 
				if ($.countdown.periodsToSeconds(periods) === 20) { 
					$(this).css('color', 'red','font-weight', 'Bold' ); 
				} 
				if ($.countdown.periodsToSeconds(periods) <= 60) { 
					$(this).css('background-color', 'yellow'); 
				} 
			
			};
			
				$('#defaultCountdown').countdown({until: stop_time, format: 'ms',  layout: '{d<}{dn} {dl} {d>}{h<}{hn} {hl} {h>}{m<}{mn} {ml} {m>}{s<}{sn} {sl}{s>}',onExpiry: SubmitAway}); 
				  
                  function SubmitAway() { 
                       radiobtn = document.getElementById('phase_inc');
                       radiobtn.checked = true;
                        beep();
                       document.getElementById('submit_form').submit();
                    }
				
			});		
	</script>

</body>
</html>



