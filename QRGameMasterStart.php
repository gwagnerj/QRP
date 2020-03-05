<?php
	require_once "pdo.php";
	session_start();
	
	
            $sql_stmt = "SELECT * FROM Game WHERE DATE(NOW())<= exp_date order by game_id";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
     
// this will be called form the main repo when the game master wants to run a game
// this is just to get the game number and go on to QRGMaster.php with a post of the game number.
// Validity will be checked in that file and sent back here if it is not valid

$_SESSION['counter']=0;  // this is for the score board


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
<h1>Quick Response Game - Get Game Number</h1>
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
<form  method="POST" action = "QRGMaster.php">
		
    
     
     
     <label> <h2>Select Active Game Number: </label>
      
     
			 <select name="game_id" id = "game_id">	
                 <option>  </option>
                <?php foreach ($rows as $row): ?>
                    <option><?=$row["game_id"]?> 
                     </option>
                <?php endforeach ?>

</h2>


            </select>

      <p><input type = "submit" value="Submit" id="submit_id" size="2" style = "width: 40%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	
	</form>
  <p style="font-size:150px;"></p>   
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	


</body>
</html>



