<?php
session_start();
require_once "pdo.php";
if(isset($_SESSION['problem_id'])){
 unset($_SESSION['problem_id']);
}

// this is a passthrough file.  If they are comming from the index then the game number is input and is passed to this file as
// a POST if it directly from a QRcode then the game_id should be a GET.  The purpose of this file is to assign a random number
// for the $alt_dex and pass that $game_id and $alt_dex as a post to "QRGameGetIn.php" 
 
// first do some error checking on the input.  If it is not OK set the session failure and send them back to QRGameIndex.

  if (isset($_POST['game_id'])){
        $game_id = $_POST['game_id'];
  } 
  elseif(isset($_GET['game_id'])){
          $game_id = $_GET['game_id'];
         
  } else {
       $_SESSION['error'] = "Missing game number from getGame";
       //echo $game_id;
       //die();
	  header('Location: index.php');
	  return;   
    }
  
	if ($game_id<1 || $game_id>1000000) {
	  $_SESSION['error'] = "game number out of range";
	  header('Location: index.php');
	  return;
	}
   
   
   
   //get the current phase of the game
    $sql_stmt = "SELECT * FROM Gmact WHERE `game_id`= :game_id ORDER BY `Gmact_id` DESC LIMIT 1";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt->execute(array(':game_id' => $game_id));
            $row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
           	if ( $row2 == false ) {
                $_SESSION['error'] = 'Game problem has not yet been activated by the Instructor ';
                header( 'Location: index.php' ) ;
                return;
            }
            
            foreach ($row2 as $row3){
                $phase = $row3['phase'];  // if on_the_fly was 0 then the game master has set up the teams and we could use JS to only ask for the pin or select the name from a list
                $on_the_fly = $row3['on_the_fly'];
                $iid = $row3['iid'];
                $gmact_id = $row3['gmact_id'];
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
?>

<form action = "QRGameConfirm.php" method = "POST" autocomplete="off">

     <p><font color=#003399> Name </font><input type="text" name="name" id = "name" size=3 width = "40px"></p>
    <p><font color=#003399> Fill in your PIN </font><input type="number" id = "pin" name="pin" size=3 min="1" max= "9999"  ></p>
     <p><font color=#003399> and Team Number </font><input type="number" name="team_id" id = "team_id" size=3 min="1" max= "100"  ></p>
     <p> <font color=#0000CD size = "1">(PIN and Team Number are supplied from the Instructor)</font> </p>
     <p><font color=#003399> Total Number of People on Your Team </font><input type="number" name="team_size" id = "team_size" size=3 min="1" max= "6"  ></p>
     
    <p> When all the info is complete select "Submit" </p>
	<p><input type="hidden" name="game_id" size=3 value="<?php echo (htmlentities($game_id))?>"  ></p>
    <p><input type="hidden" name="gmact_id" size=3 value="<?php echo (htmlentities($gmact_id))?>"  ></p>
	<p><input type="hidden" name="phase" size=3 value="<?php echo (htmlentities($phase))?>"  ></p>
    <p><input type="hidden" name="on_the_fly" size=3 value="<?php echo (htmlentities($on_the_fly))?>"  ></p>
     <p><input type="hidden" name="iid" size=3 value="<?php echo (htmlentities($iid))?>"  ></p>
    
	<p><input type = "submit" value="Submit" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>
	</form>

</body>
</html>



