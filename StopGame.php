<?php
session_start();
	require_once "pdo.php";

	if ( isset($_POST['problem_id']) ) {
			$problem_id = $_POST['problem_id'];
		} else {
		  $_SESSION['error'] = "Missing problem_id";
		  header('Location: getGamePblmNum.php');
		  return;
		}
		
	if ( isset($_POST['game_id']) ) {
			$game_id = $_POST['game_id'];
		}  else {
		  $_SESSION['error'] = "Missing game_id";
		  header('Location: getGamePblmNum.php');
		  return;
		}	
	if ( isset($_POST['game_score']) ) {
			$GamePts = $_POST['game_score'];
		}  else {
            $GamePts=$_SESSION['points'];
		}		
   
   if ( isset($_POST['gameactivity_id']) ) {
			$gameactivity_id = $_POST['gameactivity_id'];
		}  
    
    // update the gameactivity table with the score
    
            $stmt = $pdo->prepare("UPDATE `Gameactivity` SET `score` = $GamePts WHERE gameactivity_id = :gameactivity_id ");
			$stmt->execute(array(":gameactivity_id" => $gameactivity_id));
    
    
	 
	$elapTime = $_SESSION['time']-$_SESSION['startTime'];
	 $minutes = floor(($elapTime / 60) % 60);
	$seconds = $elapTime % 60;
	
	//$GamePts=$_SESSION['points'];
	
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

	<!--<p><b><font size=6><p>You Earned:<font color = "blue"> <?php echo (round( $GamePts))?> Points</font></font></b></p>  -->
	<p><b><font size=5><p>Your QRGame Pts<font color = "blue"> <?php echo (round( $_SESSION['score']))?> </font></font></b></p>
	<b><font size=4><p>Your Time (min:sec):<font color = "blue"> <?php echo "$minutes:$seconds"?></font></font></b>
	<b><font size=4><p>Number of Tries:<font color = "blue"> <?php echo ($_SESSION['count'])?></font></font></b>
	<p><br></p>
	<!--<span class = 'push_luck'> You can keep your points by Selecting a New Problem</span></br> -->
	
    	<form action="QRGamePblmPost.php" method="POST">
			<p><font color=#003399> </font><input type="hidden" id = "problem_id" name="problem_id" size=3 value="<?php echo (htmlentities($problem_id))?>"  ></p>
			<p><font color=#003399> </font><input type="hidden" id = "game_id" name="game_id" size=3 value="<?php echo (htmlentities($game_id))?>"  ></p>
            <p><font color=#003399> </font><input type="hidden" id = "gameactivity_id" name="gameactivity_id" size=3 value="<?php echo (htmlentities($gameactivity_id))?>"  ></p>
    <hr>
	<p><b>Record Score then <font Color="red">Wait</font> for the instructor/game master to give you a reflection topic then preceed to reflection</b></p>
	  <!--<input type="hidden" name="score" value=<?php echo ($score) ?> /> -->
	  <!-- <?php //$_SESSION['score'] = round($PScore);  $_SESSION['count'] = $count; ?> -->
	 <b><input type="submit" value="Go To Reflection" name="score" style = "width: 30%; background-color:yellow "></b>
	 <p><br> </p>
	 <hr>
	</form>

    
    
   <!-- <a href="QRGamePblmPost.php"><b><font size = 5> Go To Reflection </font></b></a>
	   <a href="index.php"><b><font size = 5> New Problem </font></b></a>  -->

	</main>
	


	<footer>
	<!--<p>This is the footer</p> -->
	</footer>
	</body>
	</html>