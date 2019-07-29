<?php
 session_start();
  Require_once "pdo.php";
  
 if (isset($_POST['score'])){ // this is now the pscore (percentage)
	$score = $_POST['score'];
}elseif(isset($_GET['score'])){
	$score = $_GET['score'];
} elseif(isset($_SESSION['score'])){
	$score = $_SESSION['score'];
} else {
	
	$_SESSION['error'] = 'score not set';
}
 
 if (isset($_POST['problem_id'])){
	$problem_id = $_POST['problem_id'];
}elseif(isset($_GET['problem_id'])){
	$problem_id = $_GET['problem_id'];
} elseif(isset($_SESSION['problem_id'])){
	$problem_id = $_SESSION['problem_id'];
} else {
	
	$_SESSION['error'] = 'problem_id not set';
}
 if (isset($_SESSION['assign_num'])){
	 $assign_num = $_SESSION['assign_num'];
	 
 } else {
	 $assign_num = '';
 }

 
 if (isset($_POST['pin'])){
	$pin = $_POST['pin'];
}elseif(isset($_GET['pin'])){
	$pin = $_GET['pin'];
} elseif(isset($_SESSION['pin'])){
	$pin = $_SESSION['pin'];
} else {
	
	$_SESSION['error'] = 'pin not set';
}
 
 if (isset($_POST['iid'])){
	$iid = $_POST['iid'];
}elseif(isset($_GET['iid'])){
	$iid = $_GET['iid'];
} elseif(isset($_SESSION['iid'])){
	$iid = $_SESSION['iid'];
} else {
	
	$_SESSION['error'] = 'iid not set';
}
 
 if (isset($_POST['count'])){
	$count = $_POST['count'];
}elseif(isset($_GET['count'])){
	$count = $_GET['count'];
} elseif(isset($_SESSION['count'])){
	$count = $_SESSION['count'];
} else {
	
	$_SESSION['error'] = 'count not set';
}
 
// get rand and rand2 from the checker Table

 $stmt = $pdo->prepare("SELECT * FROM `Checker` WHERE problem_id = :problem_id AND pin = :pin AND iid = :iid");
		$stmt->execute(array(":problem_id" => $problem_id, ":pin" => $pin, ":iid" => $iid));
		$row = $stmt -> fetch();
		if ( $row === false ) {
			
			// try to get them from the session varaibles
			 $rand=$_SESSION['rand'];
			 $rand2=$_SESSION['rand2'];
			
			$_SESSION['error'] = 'could not read values to get returnd code ';
			// should point the headers somewhere
			//return();
			// later put in where to go to display these
		} else {
			
			$rand = $row['rand1'];
			$rand2 = $row['rand2'];
			
		}
 
 
  
//$rand= rand(100000,999999);

$first=substr($rand,0,1);
//echo $first;
if ($first ==1 ){$key1=1; $key2=6;}
elseif ($first==2){$key1=2; $key2 = 5;}
elseif ($first==3){$key1=3; $key2 = 4;}
elseif ($first==4){$key1=4; $key2 = 3;}
elseif ($first==5){$key1=5; $key2 = 2;}
elseif ($first==6){$key1=6; $key2 = 1;}
elseif ($first==7){$key1=1; $key2 = 3;}
elseif ($first==8){$key1=2; $key2 = 4;}
else {$key1=3; $key2=5;}

$map1=substr($rand,$key1-1,1);
$map2=substr($rand,$key2-1,1);


$rslt=$map1.$map2;


$rslt2=$rslt+$score;
if ($pin != 0 ) {
	$rtn_Code = $rand.'-'.$rslt2.$rand2;
} else {
	$rtn_Code = 0;
}

$_SESSION['rtn_Code']=$rtn_Code;


		 //$time_complete = date('Y-m-d H:i:s');   // this give the current time using the SQL time stamp now() instead
		$sql = "UPDATE Activity SET rtn_code = :rtn_code, time_complete = now() WHERE problem_id = :problem_id AND iid = :iid AND pin = :pin";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':rtn_code' => $rtn_Code,
			':problem_id' => $problem_id,
		//	':time_complete' => $time_complete,
			':iid' => $iid,
			':pin' => $pin
			));
			
	// get the students name if available for the 		
	 $stmt = $pdo->prepare("SELECT * FROM `Activity` WHERE problem_id = :problem_id AND pin = :pin AND iid = :iid");
			$stmt->execute(array(":problem_id" => $problem_id, ":pin" => $pin, ":iid" => $iid));
			$row = $stmt -> fetch();
			if ( $row === false ) {
				$stu_name = '';
			} else {
			$stu_name = $row['stu_name'];
			}

session_destroy();

?>

<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />
<meta Charset = "utf-8">
<title>QRProblems</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
</head>

<body>
<header>
<!--<h1>this is an application that gets the return code from the score</h1>-->
</header>
<main>
<p><b><font size=5><p>Your Computer Score:<font color = "blue"> <?php echo( $score)?>%</font></font></b></p> 
<p><b><font size=5>Your rtn Code: <font color = "blue"><?php echo ($rtn_Code)?></font></font></b></p>
<p><br></p>
<?php
$problem_id = (isset($problem_id) ? $problem_id : '');
$iid = (isset($iid) ? $iid : '');
$pin = (isset($pin) ? $pin : '');
$stu_name = (isset($stu_name) ? $stu_name : '');


	echo('<a href="QRhomework.php?problem_id='.$problem_id.'&pin='.$pin.'&assign_num='.$assign_num.'&iid='.$iid.'&stu_name='.$stu_name.'"><b> Return to Main Screen</b></a>');
	//QRhomework.php'+'?problem_id='+problem_id+'&pin='+pin+'&iid='+iid+'&stu_name='+stu_name_back
?>
</main>



<footer>
<!--<p>This is the footer</p> -->
</footer>
</body>
</html>