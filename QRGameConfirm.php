<?php
session_start();



// this is a confirming input file.  called by getGamePblmNum.php after the users have input information and passes it on to QRGameRouter.php 
 
// first do some error checking on the input.  If it is not OK set the session failure and send them back to Index or getGamePblmNum.

   if (isset($_POST['game_id'])){
        $game_id = $_POST['game_id'];
    } 
   else {
       $_SESSION['error'] = "Missing game number from getGame";
	  header('Location: index.php');
	  return;   
   }
  
	if ($game_id<1 || $game_id>1000000) {
	  $_SESSION['error'] = "game number out of range";
	  header('Location: index.php');
	  return;
	}
    
       if (isset($_POST['pin'])){
            $pin = $_POST['pin'];
          } 
       else {
           $_SESSION['error'] = "Missing pin";
          
          header('Location: getGamePblmNum.php');
          return;   
        }
        
         if (isset($_POST['gmact_id'])){
            $gmact_id = $_POST['gmact_id'];
          } 
       else {
           $_SESSION['error'] = "Missing Gmact_id in QRGameConfirm";
          
          header('Location: getGamePblmNum.php');
          return;   
        }
        
              if (isset($_POST['iid'])){
            $iid = $_POST['iid'];
            $_SESSION['iid'] = $iid;
              } 
           else {
               $_SESSION['error'] = "Missing iid from QRGameConfirm";
              
              header('Location: getGamePblmNum.php');
              return;   
            }
        
        
        if (isset($_POST['team_id'])){
            $team_id = $_POST['team_id'];
          } 
       else {
           $_SESSION['error'] = "Missing team number";
          header('Location: getGamePblmNum.php');
          return;   
        }
        
         if (isset($_POST['team_size'])){
            $team_size = $_POST['team_size'];
          } 
       else {
           $_SESSION['error'] = "Missing team size";
          header('Location: getGamePblmNum.php');
          return;   
        }
        
        if (isset($_POST['phase'])){
            $phase = $_POST['phase'];
          } 
       else {
           $_SESSION['error'] = "Missing Problem phase or game not active";
          header('Location: index.php');
          return;   
        }
        
         if (isset($_POST['name'])){
            $name = $_POST['name'];
          } 
        else {
           $name = 'Left Blank';
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


?>



<h3> Name: <?php echo($name);?> </h3>
<h3> Game number: <?php echo($game_id);?> </h3>
<h3> PIN: <?php echo($pin);?> </h3>
<h3> Team Number: <?php echo($team_id);?> </h3>
<h3> Total People on Team: <?php echo($team_size);?> </h3>


<form action = "QRGameSetUp.php" method = "POST" autocomplete="off">

    
     
      <p><input type="hidden" name="name" size=3 value="<?php echo (htmlentities($name))?>"  ></p>
      <p><input type="hidden" name="pin" size=3 value="<?php echo (htmlentities($pin))?>"  ></p>
      <p><input type="hidden" name="team_id" size=3 value="<?php echo (htmlentities($team_id))?>"  ></p>
      <p><input type="hidden" name="team_size" size=3 value="<?php echo (htmlentities($team_size))?>"  ></p>
     
     
    <p><input type="hidden" name="name" size=3 value="<?php echo (htmlentities($name))?>"  ></p>
    <p><input type="hidden" name="game_id" size=3 value="<?php echo (htmlentities($game_id))?>"  ></p>
     <p><input type="hidden" name="gmact_id" size=3 value="<?php echo (htmlentities($gmact_id))?>"  ></p>
    <p><input type="hidden" name="phase" size=3 value="<?php echo (htmlentities($phase))?>"  ></p>
     <p><input type="hidden" name="iid" size=3 value="<?php echo (htmlentities($iid))?>"  ></p>
    
    <p> <font color=#003399> If the information above is correct select confirm</font> </p>
    
	<p><input type = "submit" value="Confirm" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>
	</form>
     <p> <font color=#003399> Otherwise "Re_Input Data"</font> </p>
    <form action = "getGamePblmNum.php" method = "POST">
    <p><input type="hidden" name="game_id" size=3 value="<?php echo (htmlentities($game_id))?>"  ></p>
    	<p><input type = "submit" value="Re-Input Data" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>
    </form>

</body>
</html>



