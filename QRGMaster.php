<?php
	require_once "pdo.php";
	session_start();
	

 /*     
 this will be called form the QRGameMasterStart.php with the game_id as a POST 
 Validity will be checked here and sent back to QRGameMasterStart.php  if it is not valid
 This will give control of the game on the fly allowing the GM to change to timers and phase of the game 
 it will also have a link way to monitor the game in a separate tab say QRGameMonitor.php
 */

//Check the input - coming from the QRGameMasterStart.php

		if ( isset($_POST['game_id']) && is_numeric($_POST['game_id']) ) {
			$game_id = $_POST['game_id'];
             
               
            // fill in the initial values for the Gmact table using the values from the Game table
            // get values from Game table
  
            $sql = "SELECT * FROM `Game` WHERE game_id = :game_id";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
				':game_id' => $game_id,
				)); 
            $row1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($row1 as $row){
         //   print_r ($row);
            $iid = $row['iid'];
            }
            
            // Create the table entry into the Gmact table from the values that were put in the Game table
            
            $sql = 'INSERT INTO `Gmact` (game_id, iid, phase, prep_time, work_time, post_time)	
						VALUES (:game_id, :iid, 0, :prep_time, :work_time, :post_time)';
				$stmt = $pdo->prepare($sql);
				$stmt -> execute(array(
				':game_id' => $game_id,
				':iid' => $iid,
				':prep_time' => $row['prep_time'],
				':work_time' => $row['work_time'],
                ':post_time' => $row['post_time'],
				));
                
                // get the gmact_id
           
               $sql = "SELECT `gmact_id` FROM `Gmact` ORDER BY gmact_id DESC LIMIT 1";
               $stmt = $pdo->prepare($sql);
               $stmt -> execute(); 
                $row3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach($row3 as $row){
             //   print_r ($row);
                $gmact_id = $row['gmact_id'];
                }
                echo 'gmact_id = '.$gmact_id;

           } 
            
            
            elseif(isset($_POST['game_num']) && is_numeric($_POST['game_num'])){
                $game_id = $_POST['game_num'];
                // Update the with any new values of the input parmaters in the html
                $phase = $_POST['phase'];
                $prep_time = $_POST['prep_time'];
                $work_time = $_POST['work_time'];
                $post_time = $_POST['post_time'];
                 $gmact_id = $_POST['gmact_id'];
                
                $sql = "UPDATE `Gmact` 
				SET phase = :phase, prep_time = :prep_time, work_time = :work_time, post_time = :post_time
				WHERE gmact_id = :gmact_id";
                $stmt = $pdo->prepare($sql);
                $stmt -> execute(array(
                ':phase' => $phase,
                ':prep_time' => $prep_time,
                ':work_time' => $work_time,
                ':post_time' => $post_time,
                ':gmact_id' => $gmact_id,
			
			
			));
                
            } else

            {
              $_SESSION['error'] = "Missing game_id";
              header('Location: QRGameMasterStart.php');
             die();
            }
        // do some eror checking on the post data comning in from this page - put the game id in the variable called $game_id



        // get the values from the Gmact table 
        
         $sql_stmt = "SELECT * FROM Gmact WHERE `game_id`= :game_id";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt->execute(array(':game_id' => $game_id));
            $row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row2 as $row3){
                $iid = $row3['iid'];
                $phase = $row3['phase'];
                $prep_time = $row3['prep_time'];
                $work_time = $row3['work_time'];
                $post_time = $row3['post_time'];
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
      
   <p> Game Number = <?php echo $game_id; ?></p>
	<p><input type="hidden" name="game_num" id="game_num" size=3 value=<?php echo($game_id);?> ></p>
    <p><input type="hidden" name="gmact_id" id="gmact_id" size=3 value=<?php echo($gmact_id);?> ></p>
	<p><font color=#003399>Phase: </font><input type="number" name="phase" id="phase" size=3 value=<?php echo($phase);?> ></p>
	<p><font color=#003399>Planning Time: </font><input type="number" name="prep_time" id="prep_time" size=5 value=<?php echo($prep_time);?> >
    <p><font color=#003399>Working Time: </font><input type="number" name="work_time" id="work_time" size=5 value=<?php echo($work_time);?> >
    <p><font color=#003399>Reflection Time: </font><input type="number" name="post_time" id="post_time" size=5 value=<?php echo($post_time);?> >


      <p><input type = "submit" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	
	</form>
    
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	


</body>
</html>



