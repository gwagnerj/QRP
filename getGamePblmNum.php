<?php
session_start();
if(isset($_SESSION['problem_id'])){
 unset($_SESSION['problem_id']);
}

// this is a passthrough file.  If they are comming from the index then the game number is input and is passed to this file as
// a POST if it directly from a QRcode then the game_id should be a GET.  The purpose of this file is to assign a random number
// for the $alt_dex and pass that $game_id and $alt_dex as a post to "QRGameGetIn.php" 
 
// first do some error checking on the input.  If it is not OK set the session failure and send them back to QRGameIndex.

  if (isset($_POST['game_id'])){
        $game_id = $_POST['game_id'];
    } elseif(isset($_GET['game_id'])){
          $game_id = $_POST['game_id'];
    } else {
       $_SESSION['error'] = "Missing game number";
	  header('Location: index.php');
	  return;   
    }
  
	if ($game_id<1 || $game_id>1000000) {
	  $_SESSION['error'] = "game number out of range";
	  header('Location: index.php');
	  return;
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
    <p> Wait until the Game Master/Instructor tells you to Start
	<p><font color=#003399> </font><input type="hidden" name="game_id" size=3 value="<?php echo (htmlentities($game_id))?>"  ></p>
	<p><font color=#003399> </font><input type="hidden" name="alt_dex" size=3 value="<?php echo (htmlentities($alt_dex))?>"  ></p>

	<p><input type = "submit" value="Start" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>
	</form>

</body>
</html>



