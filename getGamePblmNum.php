<?php
session_start();
if(isset($_SESSION['problem_id'])){
 unset($_SESSION['problem_id']);
}

 unset($_SESSION['oldPoints']);
 $_SESSION['oldPoints']=0;

 
// first do some error checking on the input.  If it is not OK set the session failure and send them back to QRGameIndex.
if ( isset($_SESSION['numPlayers'])){
	//echo 'numplayers set';
} else {
		if ( ! isset($_GET['numPlayers']) ) {
		  $_SESSION['error'] = "Missing number of players";
		  header('Location: index.php');
		  return;
		}
		if ($_GET['numPlayers']<1 or $_GET['numPlayers']>6)  {
		  $_SESSION['error'] = "number of players must be between 1 and 6";
		  header('Location: index.php');
		  return;
		}
		$_SESSION['numPlayers'] = $_GET['numPlayers'];
}
$_SESSION['alt_dex'] = rand(2,200);
$alt_dex = rand(2,200);
?>

<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRPGames</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
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

$g_num = "";
$index = "";
$gs_num = "";
?>

<form action = "QRGameGetIn.php" method = "POST" autocomplete="off">
	<p><font color=#003399>Game Number: </font><input type="text" name="game_id" size=3 value="<?php echo (htmlentities($g_num))?>"  ></p>
	<p><font color=#003399> </font><input type="hidden" name="alt_dex" size=3 value="<?php echo (htmlentities($alt_dex))?>"  ></p>

	<p><input type = "submit" value="Submit" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>
	</form>

</body>
</html>



