<?php

session_start();
require_once "pdo.php";
/* 
if(isset($_SESSION['problem_id'])){
 unset($_SESSION['problem_id']);
}

 unset($_SESSION['oldPoints']);
 $_SESSION['oldPoints']=0;
  */
 if ( isset($_POST['gameactivity_id']) ) {
			$gameactivity_id = $_POST['gameactivity_id'];
          
           $stmt = $pdo->prepare("SELECT *  FROM `Gameactivity` WHERE gameactivity_id = :gameactivity_id ");
			$stmt->execute(array(":gameactivity_id" => $gameactivity_id));
			$row = $stmt -> fetch();
            $team_score = $row['team_score'];
            $game_id = $row['game_id'];
           echo ('<h2> Your Average Team Score for Game '.$game_id.' was '.$team_score.'</h2>');
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

 session_destroy();
 session_unset();
 //session_start();
?>

<form action = "getGamePblmNum.php" method = "POST" autocomplete="off">
	<p><font color=#003399>Game Number: </font><input type="text" name="game_id" size=3 value="<?php echo (htmlentities($g_num))?>"  ></p>
	<p><font color=#003399> </font><input type="hidden" name="alt_dex" size=3 value="<?php echo (htmlentities($alt_dex))?>"  ></p>

	<p><input type = "submit" value="Submit" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>
	</form>

</body>
</html>

