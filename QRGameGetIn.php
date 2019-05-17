<?php
	require_once "pdo.php";
	session_start();

	// first do some error checking on the input.  If it is not OK set the session failure and send them back to QRGameIndex.
	if ( ! isset($_POST['game_id']) ) {
	  $_SESSION['error'] = "Missing game number";
	  header('Location: getGamePblmNum.php');
	  return;
	}
	if ($_POST['game_id']<1 or $_POST['game_id']>1000000)  {
	  $_SESSION['error'] = "game number out of range";
	  header('Location: getGamePblmNum.php');
	  return;
	}
	$game_id = $_POST['game_id'];
	
	$_SESSION['game_id'] = $_POST['game_id'];

	if ( isset($_POST['alt_dex']) ) {
		$alt_dex = $_POST['alt_dex'];
	} elseif (isset($_SESSION['alt_dex'])){
		$alt_dex = $_SESSION['alt_dex'];
	} else {
	  $_SESSION['error'] = "Missing alt dex";
	  header('Location: getGamePblmNum.php');
	  return;
	}


	$alt_dex = $_POST['alt_dex'];





	$_SESSION['count']=0;
	$_SESSION['startTime'] = time();

		$stmt = $pdo->prepare("SELECT * FROM Game WHERE game_id = :game_id");
		$stmt->execute(array(":game_id" => $game_id));
		//$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$row = $stmt -> fetch();
		if ( $row === false ) {
			$_SESSION['error'] = 'Bad value for game_id or game_id not active';
			header( 'Location: getGamePblmNum.php' ) ;
			return;
		}
		$gameData=$row;	
		//echo $probData['tol_a'];
		
		$problem_id = $gameData['problem_id'];
		$dex = $gameData['dex'];
		
		$rect_length = $gameData['rect_length'];
		$oval_length = $gameData['oval_length'];
		$trap_length = $gameData['trap_length'];
		$hexa_length = $gameData['hexa_length'];
		$prep_time = $gameData['prep_time'];
		$work_time = $gameData['work_time'];
		$post_time = $gameData['post_time'];
		
		if ($rect_length == null || strlen($rect_length)<1){$rect_length = 20;}
		
		/* 
		echo ('$rect_length');
		echo ($rect_length);
		die();
		 */
		
		if($dex == -1) {$dex = $alt_dex;} // temp will change to random number 
	//echo $_SESSION['problem_id'];
	//echo '<br>';
	//echo $_SESSION['index'];
	//echo '<br>';
	//die();

		$stmt = $pdo->prepare("SELECT * FROM `Input` where problem_id = :problem_id AND dex = :dex");
		$stmt->execute(array(":problem_id" => $problem_id, ":dex" => $dex));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		//$row = $stmt -> fetch();
			if ( $row === false ) {
				$_SESSION['error'] = 'could not read row of table Qa for game variables';
				header('Location: getGamePblmNum.php');
				return;
			}	
		//$rect_length = 10;		
		//	echo ($rect_length);
		
			$rect_val = $row[$gameData['rect_vnum']];
			$oval_val = $row[$gameData['oval_vnum']];
			$trap_val = $row[$gameData['trap_vnum']];
			$hexa_val = $row[$gameData['hexa_vnum']];
			
			
			
			$char_to_width = 24;
			$rect_width = $rect_length * $char_to_width+5;
			$oval_width = $oval_length * $char_to_width+5;
			$trap_width = $trap_length * $char_to_width +25;
			$hexa_width = $hexa_length * $char_to_width+10;
			
			
			
			
			
			$rect_svg = $rect_width+32;
			$oval_svg = $oval_width+32;
			$trap_svg = $trap_width+32;
			
			$trapx_pt2 = $trap_width-15;
			
			$hexa_svg = $hexa_width+32;
			$hexax_pt2 = $hexa_width-10;
												
			
			if(strtolower($rect_val) == 'null'){$rect_val = ""; $rect_width = 0; $rect_svg = 0; $rect_pt2 = 0;}	
			if(strtolower($oval_val) == 'null'){$oval_val = ""; $oval_width = 0; $oval_svg = 0; $oval_pt2 = 0;}	
			if(strtolower($trap_val) == 'null'){$trap_val = ""; $trap_width = 0; $trap_svg = 0; $trap_pt2 = 0;}		
			if(strtolower($hexa_val) == 'null'){$hexa_val = ""; $hexa_width = 0; $hexa_svg = 0; $hexa_pt2 = 0;}								

		
		
		/* $_SESSION['g1']=$row['g1'];
		$_SESSION['g2']=$row['g2'];
		$_SESSION['g3']=$row['g3'];

		if ($_SESSION['g1']=="" or $_SESSION['g1']=="NULL"){
				$_SESSION['error']="Game variable 1 is empty for this problem";
				header('Location: getGamePblmNum.php');
				return; 
		}*/
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



<h3> Game number: <?php echo($game_id);?> </h3>

	<svg  width=<?php echo($rect_svg); ?> height="100" >
	  <rect  fill="white" stroke="blue" stroke-width="4" width="<?php echo($rect_width);?>" height = "50" x="15" y = "5"/>
	  <text x="<?php echo($rect_width/2+12);?>" y="40" text-anchor="middle" fill="black" font-size="30"> <?php echo ($rect_val);?></text>
	</svg>

	<svg  width=<?php echo($oval_svg) ?> height="100" >
	  <rect  fill="white" stroke="red" stroke-width="4" width="<?php echo($oval_width);?>" rx = "25"  ry = "25" height = "50" x="15" y = "5"/>
	  <text x="<?php echo($oval_width/2+14);?>" y="40" text-anchor="middle" fill="black" font-size="30"> <?php echo ($oval_val);?></text>
	</svg>



	<svg  width=<?php echo($trap_svg) ?> height="100" >
	  <polygon  fill="white" stroke="green" stroke-width="4" points="20,5 <?php echo($trapx_pt2);?>,5 <?php echo($trap_width);?>,50 5,50"/>
	  <text x="<?php echo($trap_width/2+4);?>" y="40" text-anchor="middle" fill="black" font-size="30"> <?php echo ($trap_val);?></text>
	</svg>

	<svg  width=<?php echo($hexa_svg) ?> height="100" >
	  <polygon  fill="white" stroke="#E67E22" stroke-width="4" points="15,5 <?php echo($hexax_pt2);?>,5 <?php echo($hexa_width);?>,30 <?php echo($hexax_pt2);?>,50 15,50 5,30"/>
	  <text x="<?php echo($hexa_width/2+4);?>" y="40" text-anchor="middle" fill="black" font-size="30"> <?php echo ($hexa_val);?></text>
	</svg>


	<form action = "QRGameCheck.php" method = "POST" >
	<!--	<p><font color=#003399>Problem Number: </font><input type="text" name="problem_id" size=3 value="<?php echo (htmlentities($p_num))?>"  ></p> -->
		<p><font color=#003399> </font><input type="hidden" name="game_id" size=3 value="<?php echo (htmlentities($game_id))?>"  ></p>
		<p><font color=#003399> </font><input type="hidden" name="dex" size=3 value="<?php echo (htmlentities($dex))?>"  ></p>
		<p><font color=#003399> </font><input type="hidden" name="problem_id" size=3 value="<?php echo (htmlentities($problem_id))?>"  ></p>
		<p><b><input type = "submit" value="Go to Checker" size="30" style = "width: 50%; background-color: #003399; color: white"/> &nbsp &nbsp </b></p>
		</form>



	</body>
	</html>

