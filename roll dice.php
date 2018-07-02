<?php
session_start();
$numDice=$_SESSION['numDice'];
$numSides=$_SESSION['numSides']-1;
//$numDice=5;
//$numSides=6;
if($_SESSION['numDice']==0){

echo 'Please do not refresh the page';
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRPGames</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
</head>
<style>
div.dice{
	float:left;
	width:20px;
	background:#F5F5F5;
	border:#999 1px solid;
	padding:10px;
	font-size:20px;
	text-align:center;
	margin:5px;
}
div.dice2{
	float:left;
	width:40px;
	background:#fafc8d;
	border:#999 1px solid;
	padding:10px;
	font-size:40px;
	color:red;
	text-align:center;
	margin:5px;
}
</style>
<body>
<header>
<h1>Quick Response Game </h1>
<h2>Values for Dice:</h2>
</header>
</head>
<body>
<?php
$tot=0;
for ($i=1;$i<=$numDice;$i++){
$d[$i-1]=rand(0,$numSides);

echo '<div class="dice">'.$d[$i-1].' </div>';
$tot=$tot+$d[$i-1];
$_SESSION['oldPoints']=$tot;
}
if($_SESSION['numDice']==0){ // they refreshed the page

$tot = $_SESSION['oldPoints'];
}
?>

<br/>
<br/>
<br/>


<h1>Your Points Are Now:</h1>
<p></p>
<h1><?php echo '<div class="dice2">'.$tot.' </div>' ?></h1>
<p><br/><br/></p>
<br/>
<hr>
<a href="getGamePblmNum.php"><b><font size = 6> New Problem </font></b></a>

 <?php 
 
 $_SESSION['numDice']=0;
 $_SESSION['numSides']=0;
 
 ?>


</body>
</html>