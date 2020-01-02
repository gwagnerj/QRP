<?php
session_start();
	
	 
	$elapTime = $_SESSION['time']-$_SESSION['startTime'];
	 $minutes = floor(($elapTime / 60) % 60);
	$seconds = $elapTime % 60;
	
	$GamePts=$_SESSION['points'];
	
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
	<a href="index.php"><b><font size = 5> New Problem </font></b></a>
	
	</main>
	


	<footer>
	<!--<p>This is the footer</p> -->
	</footer>
	</body>
	</html>