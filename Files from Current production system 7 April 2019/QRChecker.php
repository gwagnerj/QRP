<?php
 session_start();
   $_SESSION['score'] = "0";
	
	//$_SESSION['count'] = 0;
	
Require_once "pdo.php";

// first do some error checking on the input.  If it is not OK set the session failure and send them back to QRPIndex.
if ( ! isset($_GET['problem_id']) ) {
  $_SESSION['error'] = "Missing problem number";
  header('Location: QRPindex.php');
  return;
} 
if ( ! isset($_GET['pin']) ) {
  $_SESSION['error'] = "Missing PIN";
  header('Location: QRPindex.php');
  return;
} 

if ( ! isset($_GET['iid']) ) {
  $_SESSION['error'] = "Missing instructor ID";
  header('Location: QRPindex.php');
  return;
} else {
	$_SESSION['iid']=$_GET['iid'];
	
}
 
if ($_GET['problem_id']<1 or $_GET['problem_id']>1000000)  {
  $_SESSION['error'] = "problem number out of range";
  header('Location: QRPindex.php');
  return;
}
if ($_GET['pin']<0 or $_GET['pin']>10000)  {
  $_SESSION['error'] = "Index number out of range";
  header('Location: QRPindex.php');
  return;
}
	$pin = $_GET['pin'];			
	$dex = ($pin-1) % 199 + 2; // % is PHP mudulus function - changing the PIN to an index between 2 and 200
	$_SESSION['index'] = $dex;




if ( isset($_GET['problem_id']) and  isset($_GET['pin'])) {
	$_SESSION['problem_id'] = $_GET['problem_id'];

	$_SESSION['index'] = $dex;
	$_SESSION['pin']=$_GET['pin'];
	
		if (!isset($_POST['pin'])){
			for ($j=0;$j<=9;$j++){
				$wrongCount[$j]=0;
				$_SESSION['wrongC'[$j]]=$wrongCount[$j]; 
			}
		}		
		//$_SESSION['wrongC']=$wrongCount; 
	
}
	
	// initialize some variables
	
	$probParts=0;
	//$partsFlag = array('a'=>false,'b'=>false,'c'=>false,'d'=>false,'e'=>false,'f'=>false,'g'=>false,'h'=>false,'i'=>false,'j'=>false);
	$resp = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
	$corr = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
	$unit = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
	$tol=array('a'=>0.02,'b'=>0.02,'c'=>0.02,'d'=>0.02,'e'=>0.02,'f'=>0.02,'g'=>0.02,'h'=>0.02,'i'=>0.02,'j'=>0.02);
	$ansFormat=array('ans_a' =>"",'ans_b' =>"",	'ans_c' =>"",'ans_d' =>"",'ans_e' =>"",'ans_f' =>"",	'ans_g' =>"",'ans_h' =>"",'ans_i' =>"",'ans_j'=>"" );
	
	for ($j=0;$j<9;$j++){
		$wrongCount[$j]=0;
		
	}	
	$_SESSION['wrongC']=$wrongCount; // not sure what this is doing here
	
	$hintLimit = 3;
	$dispBase = 1;
	
	
	$count='';  // counts the times the check button is placed
	$score=0.0;

	$tol_key=array_keys($tol);
	$resp_key=array_keys($resp);
	$corr_key=array_keys($corr);
	$ansFormat_key=array_keys($ansFormat);
	
		
	// Next check the Qa table and see which values have non null values - for those 

$stmt = $pdo->prepare("SELECT * FROM Qa where problem_id = :problem_id AND dex = :dex");
$stmt->execute(array(":problem_id" => $_SESSION['problem_id'], ":dex" => $_SESSION['index']));
//$row = $stmt->fetch(PDO::FETCH_ASSOC);
$row = $stmt -> fetch();
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for problem_id';
    header( 'Location: QRPindex.php' ) ;
    return;
}	
		$soln = array_slice($row,6); // this would mean the database table Qa would have the dame structure
	

	for ($i = 0;$i<=9; $i++){  
		if ($soln[$i]==1.2345e43) {
			$partsFlag[$i]=false;
		} else {
			$probParts = $probParts+1;
			$partsFlag[$i]=true;
		}
	}
	//get the tolerance for each part - only really need to do this once on the get request - change if it is slow
	$stmt = $pdo->prepare("SELECT * FROM Problem where problem_id = :problem_id");
	$stmt->execute(array(":problem_id" => $_SESSION['problem_id']));
	//$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$row = $stmt -> fetch();
	if ( $row === false ) {
		$_SESSION['error'] = 'Bad value for problem_id';
		header( 'Location: QRPindex.php' ) ;
		return;
	}	
	$probData=$row;	
	
	$probStatus = $probData['status'];
	if ($probStatus =='suspended'){
		$_SESSION['error'] = 'problem has been suspended, check back later';
		header( 'Location: QRPindex.php' ) ;
		return;	
		
		
	}
	
	$tol['a']=$probData['tol_a']*0.001;	
	$tol['b']=$probData['tol_b']*0.001;
	$tol['c']=$probData['tol_c']*0.001;	
	$tol['d']=$probData['tol_d']*0.001;
	$tol['e']=$probData['tol_e']*0.001;	
	$tol['f']=$probData['tol_f']*0.001;
	$tol['g']=$probData['tol_g']*0.001;	
	$tol['h']=$probData['tol_h']*0.001;
	$tol['i']=$probData['tol_i']*0.001;	
	$tol['j']=$probData['tol_j']*0.001;	
	
	
	if (strlen($probData['hint_a'])>1){
		$hinta = $probData['hint_a'];
		$hintaPath="uploads/".$hinta;
	}
	else {
		$hintaPath ="uploads/default_hints.html";	
	}
	if (strlen($probData['hint_b'])>1){
		$hintb = $probData['hint_b'];
		$hintbPath="uploads/".$hintb;
	}
	else {
		$hintbPath ="uploads/default_hints.html";	
	}
if (strlen($probData['hint_c'])>1){
		$hintc = $probData['hint_c'];
		$hintcPath="uploads/".$hintc;
	}
	else {
		$hintcPath ="uploads/default_hints.html";	
	}
if (strlen($probData['hint_d'])>1){
		$hintd = $probData['hint_d'];
		$hintdPath="uploads/".$hintd;
	}
	else {
		$hintdPath ="uploads/default_hints.html";	
	}
	if (strlen($probData['hint_e'])>1){
		$hinte = $probData['hint_e'];
		$hintePath="uploads/".$hinte;
	}
	else {
		$hintePath ="uploads/default_hints.html";	
	}
	if (strlen($probData['hint_f'])>1){
		$hintf = $probData['hint_f'];
		$hintfPath="uploads/".$hintf;
	}
	else {
		$hintfPath ="uploads/default_hints.html";	
	}
	if (strlen($probData['hint_g'])>1){
		$hintg = $probData['hint_g'];
		$hintgPath="uploads/".$hintg;
	}
	else {
		$hintgPath ="uploads/default_hints.html";	
	}
	if (strlen($probData['hint_h'])>1){
		$hinth = $probData['hint_h'];
		$hinthPath="uploads/".$hinth;
	}
	else {
		$hinthPath ="uploads/default_hints.html";	
	}

	if (strlen($probData['hint_i'])>1){
		$hinti = $probData['hint_i'];
		$hintiPath="uploads/".$hinti;
	}
	else {
		$hintiPath ="uploads/default_hints.html";	
	}
	if (strlen($probData['hint_j'])>1){
		$hintj = $probData['hint_j'];
		$hintjPath="uploads/".$hintj;
	}
	else {
		$hintjPath ="uploads/default_hints.html";	
	}
	

	
	$unit = array_slice($row,22,20);  // dows the same thing but easier so long as the table always has the same structure
	//print_r($unit);


	

//echo "the number of parts for this problem is ". $probParts;	

// test to see if the instructor put in the code to get the answers					
					
	$dispAns=substr($_POST['pin'],0,7);

	if($dispAns=="McKetta" ){
		
		
		$pin2=substr($_POST['pin'],7)+0;
		$index = ($pin2-1) % 199 + 2;  // converts the PIN to the index
			
		$dispAnsflag=True;
	
	}
	else
	{

		$dispAnsflag=False;
		$pin2 = $_POST['pin']+0;
		$index = ($pin2-1) % 199 + 2;  // converts the PIN to the index
		
	}	

	
// read the student responses into an array

	$resp['a']=(isset($_POST['a']) ? $_POST['a']+0 : "");
	$resp['b']=(isset($_POST['b']) ? $_POST['b']+0 : "");
	$resp['c']=(isset($_POST['c']) ? $_POST['c']+0 : "");
	$resp['d']=(isset($_POST['d']) ? $_POST['d']+0 : "");
	$resp['e']=(isset($_POST['e']) ? $_POST['e']+0 : "");
	$resp['f']=(isset($_POST['f']) ? $_POST['f']+0 : "");
	$resp['g']=(isset($_POST['g']) ? $_POST['g']+0 : "");
	$resp['h']=(isset($_POST['h']) ? $_POST['h']+0 : "");
	$resp['i']=(isset($_POST['i']) ? $_POST['i']+0 : "");
	$resp['j']=(isset($_POST['j']) ? $_POST['j']+0 : "");
	
	
	
//print_r( $tol);
//echo '<br>';

// keep track of the number of tries the student makes
	if(!($_SESSION['count'])){
		$_SESSION['count'] = 1;
		$changed_a = false;
		$_SESSION['respon_a']= $resp['a'];
			$changed_b = false;
		$_SESSION['respon_b']= $resp['b'];
			$changed_c = false;
		$_SESSION['respon_c']= $resp['c'];
			$changed_d = false;
		$_SESSION['respon_d']= $resp['d'];
			$changed_e = false;
		$_SESSION['respon_e']= $resp['e'];
			$changed_f = false;
		$_SESSION['respon_f']= $resp['f'];
			$changed_g = false;
		$_SESSION['respon_g']= $resp['g'];
			$changed_h = false;
		$_SESSION['respon_h']= $resp['h'];
			$changed_i = false;
		$_SESSION['respon_i']= $resp['i'];
			$changed_j = false;
		$_SESSION['respon_j']= $resp['j'];
		$count=1;
		
		// used to see if the user is updating the value 
		/* for ($j=0;$j<9;$j++){
			$_SESSION['$respon'[$j]]= 0;
			// $_SESSION['$changed'[$j]]= false;
			$_SESSION['$respon'[$j]]=$resp[$j];
			$changed[$j] = false;
		 }  */
		
	}else{
		$count = $_SESSION['count'] + 1;
		$_SESSION['count'] = $count;
		
		if ($_SESSION['respon_a']== $resp['a']){
			$changed_a = false;
		} else  {
		
			$changed_a = true;
			$_SESSION['respon_a']= $resp['a'];
		}
		
		if ($_SESSION['respon_b']== $resp['b']){
			$changed_b = false;
		} else  {
		
			$changed_b = true;
			$_SESSION['respon_b']= $resp['b'];
		}
		
		if ($_SESSION['respon_c']== $resp['c']){
			$changed_c = false;
		} else  {
		
			$changed_c = true;
			$_SESSION[respon_c]= $resp['c'];
		}
		
		if ($_SESSION['respon_d']== $resp['d']){
			$changed_d = false;
		} else  {
		
			$changed_d = true;
			$_SESSION['respon_d']= $resp['d'];
		}
		
		if ($_SESSION['respon_e']== $resp['e']){
			$changed_e = false;
		} else  {
		
			$changed_e = true;
			$_SESSION['respon_e']= $resp['e'];
		}
		
		if ($_SESSION['respon_f']== $resp['f']){
			$changed_f = false;
		} else  {
		
			$changed_f = true;
			$_SESSION['respon_f']= $resp['f'];
		}
		
		if ($_SESSION['respon_g']== $resp['g']){
			$changed_g = false;
		} else  {
		
			$changed_g = true;
			$_SESSION['respon_g']= $resp['g'];
		}
		
		if ($_SESSION['respon_h']== $resp['h']){
			$changed_h = false;
		} else  {
		
			$changed_h = true;
			$_SESSION['respon_h']= $resp['h'];
		}
		
		if ($_SESSION['respon_i']== $resp['i']){
			$changed_i = false;
		} else  {
		
			$changed_i = true;
			$_SESSION['respon_i']= $resp['i'];
		}
		
		if ($_SESSION['respon_j']== $resp['j']){
			$changed_j = false;
		} else  {
		
			$changed_j = true;
			$_SESSION['respon_j']= $resp['j'];
		}
		
		/* for ($j=0;$j<9;$j++){
				if($_SESSION['$respon'[$j]] == $resp[$j]){
					$changed[$j] = false;
				} else {
					$changed[$j] = true;
				}
			$_SESSION['$respon'[$j]] = $resp[$j];	
		} */

	}







//}	 
	For ($j=0; $j<=9; $j++) {
		if($partsFlag[$j]) {
							
				if($soln[$j]==0){  // take care of the zero solution case
					$sol=1;
				} else {
					$sol=$soln[$j];
				}	
				
				if(	abs(($soln[$j]-$resp[$resp_key[$j]])/$sol)<= $tol[$tol_key[$j]]) {
									//echo ($tol[$tol_key[$j]]);
									$corr[$corr_key[$j]]='Correct';
									$score=$score+1;
									$_SESSION['$wrongC'[$j]] = 0;
									$wrongCount[$j]=0;
											
							}
				Else  // got it wrong or did not attempt
				{
						
							if(!(isset($_SESSION['wrongC'[$j]])))  // needs initialized
							{
								
								$_SESSION['$wrongC'[$j]] = 0;
								$wrongCount[$j]=0;
								echo 'im here';
								//echo $_SESSION['wrongC'[$j]];
							
								
							}
							elseif ($resp[$resp_key[$j]]==0)  // got it wrong and did not attempted it
							{
								
								$wrongCount[$j] = ($_SESSION['wrongC'[$j]]);
								//$_SESSION['wrongC'[$j]] = $wrongCount[$j];
								$corr[$corr_key[$j]]='';
							//	echo ($wrongCount[$j]);
							}
							else  // not correct (better to use POST value I suppose - fix later
							{
								$wrongCount[$j] = ($_SESSION['wrongC'[$j]])+1;
								$_SESSION['wrongC'[$j]] = $wrongCount[$j];
									$corr[$corr_key[$j]]='Not Correct';
								//	echo ($wrongCount[$j]);	
							}
				}		
		}
	}

	// time to delay before accepting any more input
	// So after 4 tries on a part the system will delay for 2 seconds before another input is selected (if $time_sleep1_trip is 5 and $time_sleep1 is 2
	$time_sleep1 = 2;  // time delay in seconds
	$time_sleep1_trip = 5;  // number of trieals it talkes to trip the time delay
	$time_sleep2 = 5;  // additional time if hit the next limit
	$time_sleep2_trip = 10;
	
	
	
	/* echo($resp[$resp_key[0]]);
	echo("<br>");
	echo($tol[$tol_key[0]]);
	echo("<br>");
	echo($resp['a']);
	echo("<br>");
	echo($corr['a']);
	echo("<br>");
	echo($soln['ans_a']);
	echo("<br>"); */
	
	  // we are coming through the first time
	
	$PScore=round($score/$probParts*100);  
	$rand= rand(100000,999999);  // sets up the rtn code on other page
	$rand2=rand(0,9);				// sets up the rtn code on the other page
	$_SESSION['rand']=$rand;
	$_SESSION['rand2']=$rand2;

	
if(isset($_POST['pin']) && $index<=200 && $index>0 && $dispAnsflag)
	{
	echo "<table>";
	echo "Answers:";
	echo '<table border="1">';


	echo "<tr><th>index</th>";
	echo	"<th>a)</th>";
	echo	"<th>b)</th>";
	echo	"<th>c)</th>";
	echo	"<th>d)</th>";
	echo	"<th>e)</th>";
	echo	"<th>f)</th>";
	echo	"<th>g)</th>";
	echo	"<th>h)</th>";
	echo	"<th>i)</th>";
	echo	"<th>j)</th></tr>";
	echo	"<tr>";



	
		
		echo "<tr><td>";
		echo ($_SESSION['index']);
		echo ("</td><td>");
		echo ($soln['ans_a']);
		echo ("</td><td>");
		echo ($soln['ans_b']);
		echo ("</td><td>");
		echo ($soln['ans_c']);
		echo ("</td><td>");
		echo ($soln['ans_d']);
		echo ("</td><td>");
		echo ($soln['ans_e']);
		echo ("</td><td>");
		echo ($soln['ans_f']);
		echo ("</td><td>");
		echo ($soln['ans_g']);
		echo ("</td><td>");
		echo ($soln['ans_h']);
		echo ("</td><td>");
		echo ($soln['ans_i']);
		echo ("</td><td>");
		echo ($soln['ans_j']);
		echo ("</td>");
		
	}
	
		$stmt = $pdo->prepare("SELECT * FROM Qa where problem_id = :problem_id AND dex = :dex");
		$stmt->execute(array(":problem_id" => $_SESSION['problem_id'], ":dex" => 1));
		//$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$row = $stmt -> fetch();
		
			for ($j=0;$j<=9;$j++){
				$baseAns[$corr_key[$j]]=$row[$ansFormat_key[$j]];
			}
	
	
	
	
	
	
//	print_r ($_SESSION['wrongC']);
//		print_r ($wrongCount);
	//print_r ($corr);

echo '</table>'	;
	?>



<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRChecker</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
</head>

<body>
<header>
<h1>QRProblem Checker</h1>
</header>
<main>


<p> Problem Number: <?php echo ($_SESSION['problem_id']) ?> </p>


<form autocomplete="off" method="POST" >
<!-- <p>Problem Number: <input type="text" name="problem_number" ></p> -->
<!-- <p> Please put in your index number </p> -->
<p><font color=#003399>PIN: </font><input type="text" name="pin" size=3 value="<?php echo ($pin);?>"  ></p>
<p> <strong> Fill in - then select "Check" </strong></p>


<?php


if ($partsFlag[0]){ ?> 
<p> a): <input [ type=number]{width: 5%;} name="a" size = 10% value="<?php echo (htmlentities($resp['a']))?>" > <?php echo($unit[0]) ?> &nbsp - <b><?php echo ($corr['a']) ?> </b>
<?php if (isset($_POST['pin']) and @$wrongCount[0]>$hintLimit and $corr['a']=="Not Correct"){echo '<a href="'.$hintaPath.'"target = "_blank"> hints for this part </a>';} ?>
<?php if (isset($_POST['pin']) and $changed_a and @$wrongCount[0]>$time_sleep1_trip and @$wrongCount[0]< $time_sleep2_trip and $corr['a']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
<?php if (isset($_POST['pin']) and $changed_a and @$wrongCount[0]>=$time_sleep2_trip and $corr['a']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
  </p>
<?php } 

if ($partsFlag[1]){ ?> 
<p> b): <input [ type=number]{width: 5%;} name="b" size = 10% value="<?php echo (htmlentities($resp['b']))?>" > <?php echo($unit[1]) ?> &nbsp - <b><?php echo ($corr['b']) ?> </b>
<?php if (isset($_POST['pin']) and @$wrongCount[1]>$hintLimit and $corr['b']=="Not Correct"){echo '<a href="'.$hintbPath.'"target = "_blank"> hints for this part </a>';} ?>  
<?php if (isset($_POST['pin']) and $changed_b and @$wrongCount[1]>$time_sleep1_trip and @$wrongCount[1]< $time_sleep2_trip and $corr['b']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
<?php if (isset($_POST['pin']) and $changed_b and @$wrongCount[1]>=$time_sleep2_trip and $corr['b']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
</p>
<?php } 

if ($partsFlag[2]){ ?> 
<p> c): <input [ type=number]{width: 5%;} name="c" size = 10% value="<?php echo (htmlentities($resp['c']))?>" > <?php echo($unit[2]) ?> &nbsp - <b><?php echo ($corr['c']) ?> </b>
<?php if (isset($_POST['pin']) and @$wrongCount[2]>$hintLimit and $corr['c']=="Not Correct"){echo '<a href="'.$hintcPath.'"target = "_blank"> hints for this part </a>';} ?>  
<?php if (isset($_POST['pin']) and $changed_c and @$wrongCount[2]>$time_sleep1_trip and @$wrongCount[2]< $time_sleep2_trip and $corr['c']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
<?php if (isset($_POST['pin']) and $changed_c and @$wrongCount[2]>=$time_sleep2_trip and $corr['c']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
</p>
<?php } 

if ($partsFlag[3]){ ?> 
<p> d): <input [ type=number]{width: 5%;} name="d" size = 10% value="<?php echo (htmlentities($resp['d']))?>" > <?php echo($unit[3]) ?> &nbsp - <b><?php echo ($corr['d']) ?> </b>
<?php if (isset($_POST['pin']) and @$wrongCount[3]>$hintLimit and $corr['d']=="Not Correct"){echo '<a href="'.$hintdPath.'"target = "_blank"> hints for this part </a>';} ?> 
<?php if (isset($_POST['pin']) and $changed_d and @$wrongCount[3]>$time_sleep1_trip and @$wrongCount[3]< $time_sleep2_trip and $corr['d']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
<?php if (isset($_POST['pin']) and $changed_d and @$wrongCount[3]>=$time_sleep2_trip and $corr['d']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
</p>
<?php } 

if ($partsFlag[4]){ ?> 
<p> e): <input [ type=number]{width: 5%;} name="e" size = 10% value="<?php echo (htmlentities($resp['e']))?>" > <?php echo($unit[4]) ?> &nbsp - <b><?php echo ($corr['e']) ?> </b>
<?php if (isset($_POST['pin']) and @$wrongCount[4]>$hintLimit and $corr['e']=="Not Correct"){echo '<a href="'.$hintePath.'"target = "_blank"> hints for this part </a>';} ?>  
<?php if (isset($_POST['pin']) and $changed_e and @$wrongCount[4]>$time_sleep1_trip and @$wrongCount[4]< $time_sleep1_trip and $corr['e']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
<?php if (isset($_POST['pin']) and $changed_e and @$wrongCount[4]>=$time_sleep2_trip and $corr['e']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
</p>
<?php } 

if ($partsFlag[5]){ ?> 
<p> f): <input [ type=number]{width: 5%;} name="f" size = 10% value="<?php echo (htmlentities($resp['f']))?>" > <?php echo($unit[5]) ?> &nbsp - <b><?php echo ($corr['f']) ?> </b>
<?php if (isset($_POST['pin']) and @$wrongCount[5]>$hintLimit and $corr['f']=="Not Correct"){echo '<a href="'.$hintfPath.'"target = "_blank"> hints for this part </a>';} ?>  
<?php if (isset($_POST['pin']) and $changed_f and @$wrongCount[5]>$time_sleep1_trip and @$wrongCount[5]< $time_sleep2_trip and $corr['f']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
<?php if (isset($_POST['pin']) and $changed_f and @$wrongCount[5]>=$time_sleep2_trip and $corr['f']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
</p>
<?php } 

if ($partsFlag[6]){ ?> 
<p> g): <input [ type=number]{width: 5%;} name="g" size = 10% value="<?php echo (htmlentities($resp['g']))?>" > <?php echo($unit[6]) ?> &nbsp - <b><?php echo ($corr['g']) ?> </b>
<?php if (isset($_POST['pin']) and @$wrongCount[6]>$hintLimit and $corr['g']=="Not Correct"){echo '<a href="'.$hintgPath.'"target = "_blank"> hints for this part </a>';} ?>  
<?php if (isset($_POST['pin']) and $changed_g and @$wrongCount[6]>$time_sleep1_trip and @$wrongCount[6]< $time_sleep2_trip and $corr['g']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
<?php if (isset($_POST['pin']) and $changed_g and @$wrongCount[6]>=$time_sleep2_trip and $corr['g']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
</p>
<?php } 

if ($partsFlag[7]){ ?> 
<p> h): <input [ type=number]{width: 5%;} name="h" size = 10% value="<?php echo (htmlentities($resp['h']))?>" > <?php echo($unit[7]) ?> &nbsp - <b><?php echo ($corr['h']) ?> </b>
<?php if (isset($_POST['pin']) and @$wrongCount[7]>$hintLimit and $corr['h']=="Not Correct"){echo '<a href="'.$hinthPath.'"target = "_blank"> hints for this part </a>';} ?>  
<?php if (isset($_POST['pin']) and $changed_h and @$wrongCount[7]>$time_sleep1_trip and @$wrongCount[7]< $time_sleep2_trip and $corr['h']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
<?php if (isset($_POST['pin']) and $changed_h and @$wrongCount[7]>=$time_sleep2_trip and $corr['h']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
</p>
<?php } 

if ($partsFlag[8]){ ?> 
<p> i): <input [ type=number]{width: 5%;} name="i" size = 10% value="<?php echo (htmlentities($resp['i']))?>" > <?php echo($unit[8]) ?> &nbsp - <b><?php echo ($corr['i']) ?> </b>
<?php if (isset($_POST['pin']) and @$wrongCount[8]>$hintLimit and $corr['i']=="Not Correct"){echo '<a href="'.$hintiPath.'"target = "_blank"> hints for this part </a>';} ?>  
<?php if (isset($_POST['pin']) and $changed_i and @$wrongCount[8]>$time_sleep1_trip and @$wrongCount[8]< $time_sleep2_trip and $corr['i']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
<?php if (isset($_POST['pin']) and $changed_i and @$wrongCount[8]>=$time_sleep2_trip and $corr['i']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
</p>
<?php } 

if ($partsFlag[9]){ ?> 
<p> j): <input [ type=number]{width: 5%;} name="j" size = 10% value="<?php echo (htmlentities($resp['j']))?>" > <?php echo($unit[9]) ?> &nbsp - <b><?php echo ($corr['j']) ?> </b>
<?php if (isset($_POST['pin']) and @$wrongCount[9]>$hintLimit and $corr['j']=="Not Correct"){echo '<a href="'.$hintjPath.'"target = "_blank"> hints for this part </a>';} ?>  
<?php if (isset($_POST['pin']) and $changed_j and @$wrongCount[9]>$time_sleep1_trip and @$wrongCount[9]< $time_sleep2_trip and $corr['j']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
<?php if (isset($_POST['pin']) and $changed_j and @$wrongCount[9]>=$time_sleep2_trip and $corr['j']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
</p>
<?php } 


?>

<!--<p>Grading Scheme: <input type="text" name="grade_scheme" ></p> -->
<p><input type = "submit" value="Check" size="10" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp <b> <font size="4" color="Navy">Score:  <?php echo ($PScore) ?>%</font></b></p>


</form>




<p> Count: <?php echo ($count) ?> </p>

<!-- <form method="get" >
<p><input type = "submit" value="Finished"/> </p>
</form> -->

<form action="GetRating.php" method="POST">
 <hr>
<p><b><font Color="red">When Finished:</font></b></p>
  <!--<input type="hidden" name="score" value=<?php echo ($score) ?> /> -->
  <?php $_SESSION['score'] = $PScore; $_SESSION['index'] = $index; $_SESSION['count'] = $count; ?>
 <b><input type="submit" value="Rate & Get rtn Code" style = "width: 30%; background-color:yellow "></b>
 <p><br> </p>
 <hr>
</form>

<form method = "POST">
<p><input type = "submit" value="Get Base-Case Answers" name = "show_base" size="10" style = "width: 30%; background-color: green; color: white"/> &nbsp &nbsp <b> <font size="4" color="Green"></font></b></p>
</form>


<?php


if(isset($_POST['show_base']) and $dispBase){
	
		echo "<table>";
		echo "Base-Case Answers:";
		echo '<table border="1">';
		
			for ($j=0;$j<=9;$j++){
				if($partsFlag[$j]){
					echo	("<th>$corr_key[$j]</th>");
				}
				
				//echo ("</td><td>");
			}
			//echo ("</td>");
			
			echo "<tr>";
			for ($j=0;$j<=9;$j++){
				if($partsFlag[$j]){
					echo ("<td>");
					echo ($baseAns[$corr_key[$j]]);
					echo ("</td>");
				}
		
			}

	}





?>




</main>
</body>
</html>