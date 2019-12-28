<?php
if(isset($_SESSION['numPlayers'])){
	session_destroy();

//session_destroy();	
 unset($_SESSION['numPlayers']);
}
session_start();

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

?>

<form action = "getGamePblmNum.php" method = "GET" autocomplete="off">
		<!-- <p><font color=#003399>Total Number of Players on Your Team (including yourself): </font><input type="text" name="numPlayers" size=3 value="<?php echo (htmlentities($player_num))?>"  ></p> -->
	<p><font color=#003399>Total Number of Players on Your Team (including yourself): </font><input type="text" name="numPlayers" size=3   ></p>

	<p><input type = "submit" value="Submit" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>
	</form>

</body>
</html>
