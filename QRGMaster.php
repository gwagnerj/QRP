<?php
	require_once "pdo.php";
	session_start();
	

 /*     
 this will be called form the QRGameMasterStart.php with the game_id as a POST 
 Validity will be checked here and sent back to QRGameMasterStart.php  if it is not valid
 This will give control of the game on the fly allowing the GM to change to timers and phase of the game 
 it will also have a link way to monitor the game in a separate tab say QRGameMonitor.php
 */

//Check the input

		if ( isset($_POST['game_id']) && is_numeric($_POST['game_id']) ) {
			$game_id = $_POST['game_id'];
		
		} else {
		  $_SESSION['error'] = "Missing game_id";
		  header('Location: QRGameMasterStart.php');
		 die();
		}






	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRGame Master</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>

<body>
<header>
<h1>Quick Response Game Master Screen</h1>
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

<!--<h3>Print the problem statement with "Ctrl P"</h3>
 <p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p>  -->
<form  method="POST" action = "">
		
    
     
     
   <h2>Game Master: </h2>
      
   
		

      <p><input type = "submit" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	
	</form>
    
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	


</body>
</html>



