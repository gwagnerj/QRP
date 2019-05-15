<?php
require_once "pdo.php";
session_start();

// first do some error checking on the input.  If it is not OK set the session failure and send them back to QRGameIndex.
if ( ! isset($_GET['problem_id']) ) {
  $_SESSION['error'] = "Missing problem number";
  header('Location: getGamePblmNum.php');
  return;
}
if ($_GET['problem_id']<1 or $_GET['problem_id']>1000000)  {
  $_SESSION['error'] = "problem number out of range";
  header('Location: getGamePblmNum.php');
  return;
}

$_SESSION['problem_id'] = $_GET['problem_id'];
$_SESSION['count']=0;
$_SESSION['startTime'] = time();

	$stmt = $pdo->prepare("SELECT * FROM Problem where problem_id = :problem_id");
	$stmt->execute(array(":problem_id" => $_SESSION['problem_id']));
	//$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$row = $stmt -> fetch();
	if ( $row === false ) {
		$_SESSION['error'] = 'Bad value for problem_id';
		header( 'Location: getGamePblmNum.php' ) ;
		return;
	}
	$probData=$row;	
	//echo $probData['tol_a'];
	
	$gameOnFlag=$probData['game_prob_flag'];	
	if($gameOnFlag==0){
			$_SESSION['error']="Not a game problem";
			header('Location: getGamePblmNum.php');
			return;
	}
//echo $_SESSION['problem_id'];
//echo '<br>';
//echo $_SESSION['index'];
//echo '<br>';
//die();

	$stmt = $pdo->prepare("SELECT * FROM Qa where problem_id = :problem_id AND dex = :dex");
	$stmt->execute(array(":problem_id" => $_SESSION['problem_id'], ":dex" => $_SESSION['index']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	//$row = $stmt -> fetch();
		if ( $row === false ) {
			$_SESSION['error'] = 'could not read row of table Qa for game variables';
			header('Location: getGamePblmNum.php');
			return;
		}	
	$_SESSION['g1']=$row['g1'];
	$_SESSION['g2']=$row['g2'];
	$_SESSION['g3']=$row['g3'];

	if ($_SESSION['g1']=="" or $_SESSION['g1']=="NULL"){
			$_SESSION['error']="Game variable 1 is empty for this problem";
			header('Location: getGamePblmNum.php');
			return;
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


<svg  width="500" height="100" >
  <rect  fill="white" stroke="blue" stroke-width="5" width="400" height = "75" x="15"/>
  <text x="200" y="50" text-anchor="middle" fill="black" font-size="25"> <?php echo ($_SESSION['g1']);?></text>
</svg>


<svg height="140" width="500">
  <ellipse cx="200" cy="70" rx="200" ry="40"
  style="fill:white ;stroke:red;stroke-width:4" />
   <text x="200" y="80" text-anchor="middle" fill="black" font-size="25"> <?php echo ($_SESSION['g2']);?></text>
</svg>



<svg  width="500" height="100" >
  <polygon  fill="white" stroke="green" stroke-width="4" points="60,10 450,10 480,60 30,60"/>
  <text x="250" y="50" text-anchor="middle" fill="black" font-size="25"> <?php echo ($_SESSION['g3']);?></text>
</svg>




<form action = "QRGameCheck.php" method = "GET" >
<!--	<p><font color=#003399>Problem Number: </font><input type="text" name="problem_id" size=3 value="<?php echo (htmlentities($p_num))?>"  ></p> -->
	
	<p><b><input type = "submit" value="Go to Checker" size="30" style = "width: 50%; background-color: #003399; color: white"/> &nbsp &nbsp </b></p>
	</form>

</body>
</html>

