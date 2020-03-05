<?php
session_start();
	require_once "pdo.php";

	   if (isset($_POST['game_id'])){
        $game_id = $_POST['game_id'];
    }  elseif(isset($_SESSION['game_id'])){
         $game_id = $_SESSION['game_id'];
    } else  {
       $_SESSION['error'] = "Missing game_id from StopGame";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['game_id'] = $game_id;
   

   
     if (isset($_POST['pin'])){
        $pin = $_POST['pin'];
    }  elseif(isset($_SESSION['pin'])){
         $pin = $_SESSION['pin'];
    } else  {
       $_SESSION['error'] = "Missing pin from StopGame";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['pin'] = $pin;
    
    
      if (isset($_POST['team_id'])){
        $team_id = $_POST['team_id'];
    }  elseif(isset($_SESSION['team_id'])){
         $team_id = $_SESSION['team_id'];
    } else  {
       $_SESSION['error'] = "Missing team_id from StopGame";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['team_id'] = $team_id;
    
      if (isset($_POST['gmact_id'])){
        $gmact_id = $_POST['gmact_id'];
    }  elseif(isset($_SESSION['gmact_id'])){
         $gmact_id = $_SESSION['gmact_id'];
    } else  {
       $_SESSION['error'] = "Missing gmact_id from StopGame";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['gmact_id'] = $gmact_id;
    
      if (isset($_POST['gameactivity_id'])){
        $gameactivity_id = $_POST['gameactivity_id'];
    }  elseif(isset($_SESSION['gameactivity_id'])){
         $gameactivity_id = $_SESSION['gameactivity_id'];
    } else  {
       $_SESSION['error'] = "Missing gameactivity_id from StopGame";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['gameactivity_id'] = $gameactivity_id;
    
      if (isset($_POST['name'])){
        $name = $_POST['name'];
    }  elseif(isset($_SESSION['name'])){
         $name = $_SESSION['name'];
    } else  {
       $_SESSION['error'] = "Missing name from StopGame";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['name'] = $name;
    
      if (isset($_POST['problem_id'])){
        $problem_id = $_POST['problem_id'];
    }  elseif(isset($_SESSION['problem_id'])){
         $problem_id = $_SESSION['problem_id'];
    } else  {
       $_SESSION['error'] = "Missing problem_id from StopGame";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['problem_id'] = $problem_id;
    
   
      if (isset($_POST['dex'])){
        $dex = $_POST['dex'];
    }  elseif(isset($_SESSION['dex'])){
         $dex = $_SESSION['dex'];
    } else  {
       $_SESSION['error'] = "Missing dex from StopGame";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['dex'] = $dex;
	
     if (isset($_POST['iid'])){
        $iid = $_POST['iid'];
    }  elseif(isset($_SESSION['iid'])){
         $iid = $_SESSION['iid'];
    } else  {
       $_SESSION['error'] = "Missing iid from StopGame";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['iid'] = $iid;
    
    
    if ( isset($_POST['game_score']) ) {
			$GamePts = $_POST['game_score'];
		}  else {
            $GamePts=$_SESSION['points'];
		}		
   
   if ( isset($_POST['gameactivity_id']) ) {
			$gameactivity_id = $_POST['gameactivity_id'];
		}  
    
    // update the gameactivity table with the score
    
            $stmt = $pdo->prepare("UPDATE `Gameactivity` SET `score` = :GamePts WHERE gameactivity_id = :gameactivity_id ");
			$stmt->execute(array(":gameactivity_id" => $gameactivity_id, ":GamePts" => $GamePts));
    
    
	 
	
	
	?>

	<!DOCTYPE html>
	<html lang = "en">
	<head>
	<link rel="icon" type="image/png" href="McKetta.png" />
	<meta Charset = "utf-8">
	<title>QRProblems</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" /> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
	</head>

	<body>
	<header>
	<!--<h1>this is an application that gets the return code from the score</h1>-->
	</header>
	<main>
    <h3> Name: <?php echo($name);?> </h3>
    <h3> Game number: <?php echo($game_id);?> </h3>
    <h3> PIN: <?php echo($pin);?> </h3>
    <h3> Team Number: <?php echo($team_id);?> </h3>
	<p><b><font size=6><p>You Earned:<font color = "blue"> <?php echo (round( $GamePts))?> Points</font></font></b></p> 
	<p><b><font size=5><p>Your QRGame Pts<font color = "blue"> <?php echo (round( $_SESSION['score']))?> </font></font></b></p>
	<b><font size=4><p>Number of Tries:<font color = "blue"> <?php echo ($_SESSION['count'])?></font></font></b>
	<p><br></p>
	<!--<span class = 'push_luck'> You can keep your points by Selecting a New Problem</span></br> -->
	
    	<form action="QRGameRouter.php" method="POST" id = "the_form">
            <input type="hidden" name="name"  value="<?php echo ($name)?>" >
            <input type="hidden" name="pin" value="<?php echo ($pin)?>" >
            <input type="hidden" name="team_id"  value="<?php echo ($team_id)?>" >
            <input type="hidden" name="problem_id"  value="<?php echo ($problem_id)?>" >
            <input type="hidden" name="dex" value="<?php echo ($dex)?>" >
            <input type="hidden" name="game_id" value="<?php echo ($game_id)?>" >
            <input type="hidden" id = "gmact_id" name="gmact_id" value="<?php echo ($gmact_id)?>" >
            <input type="hidden" name="gameactivity_id" value="<?php echo ($gameactivity_id)?>" >
            <input type="hidden" name="phase" id = "phase" >
            <input type="hidden" name="iid" value="<?php echo ($iid)?>" >
            
    <hr>
	<p><b>Record Score then <font Color="red">Wait</font> for the instructor/game master to advance to reflection topic then preceed to reflection</b></p>
	  <!--<input type="hidden" name="score" value=<?php echo ($score) ?> /> -->
	  <!-- <?php //$_SESSION['score'] = round($PScore);  $_SESSION['count'] = $count; ?> -->
	 <b><input type="submit" value="Go To Reflection" name="submit_name" id = "submit_id" style = "width: 30%; background-color:yellow "></b>
	 <p><br> </p>
	 <hr>
	</form>

    
    
   <!-- <a href="QRGamePblmPost.php"><b><font size = 5> Go To Reflection </font></b></a>
	   <a href="index.php"><b><font size = 5> New Problem </font></b></a>  -->

	</main>
	<script>
		$(document).ready( function () {
                
				  $('#submit_id').hide();
                
                // get the current phase
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
                            
                            if(phase > 5){  // Question time is over or they have pressed finished button
                                $("#phase").attr('value', phase);
                               SubmitAway(); 
                            }
                        });
                      
                    }, 1000);  // calling the function every 1 second
                
                 
                
                     function SubmitAway() { 
                  
                         document.getElementById('the_form').submit();
                    }
                });
                
	</script>


	<footer>
	<!--<p>This is the footer</p> -->
	</footer>
	</body>
	</html>