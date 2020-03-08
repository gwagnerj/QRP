<?php
	session_start();
	require_once "pdo.php";
	
// Called by QRGameBackStage.php and then if all normal then goes back to  QRGameBackStage.php  The purpose of this program is to edit a single row in the Gameactivity table.  
    
    
    
      if (isset($_POST['gameactivity_id'])){
        $gameactivity_id = $_POST['gameactivity_id'];
    }  else  {
       $_SESSION['error'] = "Missing gameactivity_id from QRGameEditPlayer";
	  
      echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
    }
 
  if (isset($_POST['submit_name']))  {  // submitted this form
         $sql = "UPDATE `Gameactivity` 
				SET game_id = :game_id,
                    team_id = :team_id,
                    team_size = :team_size,
                    pin = :pin,
                    gmact_id = :gmact_id,
                    team_size_error = :team_size_error,
                    name = :name,
                    ans_b = :ans_b,
                    ans_sumb = :ans_sumb,
                    ans_last = :ans_last,
                    ans_sumlast = :ans_sumlast,
                    score = :score,
                    team_cohesivity = :team_cohesivity,
                    kahoot_score = :kahoot_score,
                    team_score = :team_score
				WHERE gameactivity_id = :gameactivity_id";
                $stmt = $pdo->prepare($sql);
                $stmt -> execute(array(
                    ':game_id' => htmlentities($_POST['game_id']),
                    ':team_id' => htmlentities($_POST['team_id']),
                    ':team_size' => htmlentities($_POST['team_size']),
                    ':pin' => htmlentities($_POST['pin']),
                    ':gmact_id' => htmlentities($_POST['gmact_id']),
                    ':team_size_error' => htmlentities($_POST['team_size_error']),
                    ':name' => htmlentities($_POST['name']),
                    ':ans_b' => htmlentities($_POST['ans_b']),
                    ':ans_sumb' => htmlentities($_POST['ans_sumb']),
                    ':ans_last' => htmlentities($_POST['ans_last']),
                    ':ans_sumlast' => htmlentities($_POST['ans_sumlast']),
                    ':score' => htmlentities($_POST['score']),
                    ':team_cohesivity' => htmlentities($_POST['team_cohesivity']),
                    ':kahoot_score' => htmlentities($_POST['kahoot_score']),
                    ':team_score' => htmlentities($_POST['team_score']),
                    ':gameactivity_id' => $gameactivity_id
                ));


  }      
 
 
    
    // See what the team size is that is reported by the users
    
	 $sql_stmt = "SELECT * FROM Gameactivity WHERE `gameactivity_id`= :gameactivity_id";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt->execute(array(':gameactivity_id' => $gameactivity_id));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
          //  print_r ($row);
          //  echo ("game_id = ".$row['game_id']);
            
   if(isset($_POST['close'])){
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
     }
        
   
   ?>
 <!DOCTYPE html>
	<html lang = "en">
	<head>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta Charset = "utf-8">
	<title>QRGame Edit Player</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 
		
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                    
                      <meta name="viewport" content="width=device-width, initial-scale=1" /> 
				<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.js"></script>
		
	</head>

	<body>
	<header>
	<h2>QR Edit Player</h2>
	 
	</header>
<form action = "" method = "POST" >

    <p><font color=#003399>&nbsp; game_id </font><input type="number" name="game_id" id = "game_id" value = <?php echo $row['game_id'];?> size=3 width = "40px"></p>
    <p><font color=#003399>&nbsp; team_id </font><input type="number" name="team_id" id = "team_id" value = <?php echo $row['team_id'];?> size=3 width = "40px"></p>
    <p><font color=#003399>&nbsp; team_size </font><input type="number" name="team_size" id = "team_size" value = <?php echo $row['team_size'];?> size=3 width = "40px"></p>
    <p><font color=#003399>&nbsp; pin </font><input type="number" name="pin" id = "pin" value = <?php echo $row['pin'];?> size=3 width = "40px"></p> 
    <p><font color=#003399>&nbsp; gmact_id </font><input type="number" name="gmact_id" id = "gmact_id" value = <?php echo $row['gmact_id'];?> size=3 width = "40px"></p>
    <p><font color=#003399>&nbsp; team_size_error </font><input type="number" name="team_size_error" id = "team_size_error" value = <?php echo $row['team_size_error'];?> size=3 width = "40px"></p>
    <p><font color=#003399>&nbsp; name </font><input type="text" name="name" id = "name" value = "<?php echo $row['name'];?>" size=3 width = "40px"></p>
    <p><font color=#003399>&nbsp; ans_b </font><input type="text" name="ans_b" id = "ans_b" value = "<?php echo $row['ans_b'];?>" size=3 width = "40px"></p>
    <p><font color=#003399>&nbsp; ans_sumb </font><input type="text" name="ans_sumb" id = "ans_sumb" value = "<?php echo $row['ans_sumb'];?>" size=3 width = "40px"></p>
    <p><font color=#003399>&nbsp; ans_last </font><input type="text" name="ans_last" id = "ans_last" value = "<?php echo $row['ans_last'];?>" size=3 width = "40px"></p>
    <p><font color=#003399>&nbsp; ans_sumlast </font><input type="text" name="ans_sumlast" id = "ans_sumlast" value = "<?php echo $row['ans_sumlast'];?>" size=3 width = "40px"></p>
    <p><font color=#003399>&nbsp; score </font><input type="text" name="score" id = "score" value = "<?php echo $row['score'];?>" size=3 width = "40px"></p>
    <p><font color=#003399>&nbsp; team_cohesivity </font><input type="text" name="team_cohesivity" id = "team_cohesivity" value = "<?php echo $row['team_cohesivity'];?>" size=3 width = "40px"></p>
    <p><font color=#003399>&nbsp; kahoot_score </font><input type="text" name="kahoot_score" id = "kahoot_score" value = "<?php echo $row['kahoot_score'];?>" size=3 width = "40px"></p>
    <p><font color=#003399>&nbsp; team_score </font><input type="text" name="team_score" id = "team_score" value = "<?php echo $row['team_score'];?>" size=3 width = "40px"></p>
      
    <p> When all the info is updated select "Submit" </p>
	<input type="hidden" name="gameactivity_id" value="<?php echo ($gameactivity_id)?>"  >
    
	<p><input type = "submit" value="Submit" name = "submit_name" size="14" style = "width: 30%; background-color: blue; color: white"/> &nbsp &nbsp </p>
	</form>
    
     <p style="font-size:150px;"></p>   
      <form method="POST" >
           <p><input type="hidden" name="gameactivity_id" id="gameactivity_id" value=<?php echo($gameactivity_id);?> ></p>
         <p><input type = "submit" name = "close" value="Exit - Close Window" id="close_id" size="2" style = "width: 40%; background-color: black; color: white"/> &nbsp &nbsp </p>
      </form>
      
      
      
      
      
      </body>
      </html>
      
      