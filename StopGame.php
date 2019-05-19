<?php
 session_start();
 if($_SESSION['oldPoints']!==0){ //trying to reload with back button
	 echo '<br>';
	 $_SESSION['error']='using backboutton for retry';
	header('Location: getGamePblmNum.php');
  return; 
 }
 
 
 
 
$elapTime = $_SESSION['time']-$_SESSION['startTime'];
 $minutes = floor(($elapTime / 60) % 60);
$seconds = $elapTime % 60;
$numPlayers=$_SESSION['numPlayers'];
if ($numPlayers==1){
$multiP=6;
$numDice=$_SESSION['points']+1;		
$numSides=9;	
}	elseif ($numPlayers==2){
$multiP=3;
$numDice=$_SESSION['points']+1;		
$numSides=5;	
} elseif ($numPlayers==3){
$multiP=2;
$numDice=$_SESSION['points']+2;		
$numSides=3;		
} elseif ($numPlayers==4)	{
$multiP=1.67;
$numDice=$_SESSION['points']+1;		
$numSides=3;			
} elseif($numPlayers==5){
$multiP=1.33;
$numDice=$_SESSION['points']+1;		
$numSides=3;			
} else {
	$multiP=1;	
	$numDice=$_SESSION['points']+2;		
	$numSides=2;	
}
$GamePts=$_SESSION['points']*$multiP;
$_SESSION['numDice']=$numDice;
$_SESSION['numSides']=$numSides;
$highNum = $numSides-1;
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

<p><b><font size=6><p>You Earned:<font color = "blue"> <?php echo (round( $GamePts))?> Points</font></font></b></p> 
<!--<p><b><font size=5><p>Your Score:<font color = "blue"> <?php echo (round( $_SESSION['score']))?>%</font></font></b></p> -->
<b><font size=4><p>Your Time (min:sec):<font color = "blue"> <?php echo "$minutes:$seconds"?></font></font></b>
<b><font size=4><p>Number of Tries:<font color = "blue"> <?php echo ($_SESSION['count'])?></font></font></b>
<p><br></p>
<span class = 'push_luck'> You can keep your points by Selecting a New Problem</span></br>
<a href="getGamePblmNum.php"><b><font size = 5> New Problem </font></b></a>
</br></br><hr></br><span class = 'push_luck'> or you can </span></br>

<p><font color=#003399> </font><input type="hidden" id = "points" name="points" size=3 value="<?php echo (htmlentities($_SESSION['points']))?>"  ></p>

<?php 
if($_SESSION['points']>=1){
	
	echo '<a href="roll dice.php"><b><font size = 5> Push Your Luck </br> and </br>Roll '.$numDice. ' Dice Having values 0 to '.$highNum.' </font></b></a>'; 
}
?>

</main>
<script>
var points = $("#points").val();
if (points >= 1){$(".push_luck").show();} else {$(".push_luck").hide();}

</script>


<footer>
<!--<p>This is the footer</p> -->
</footer>
</body>
</html>