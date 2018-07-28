<?php
 session_start();
   $_SESSION['score'] = "0";
	if($_SESSION['oldPoints']!==0){ //trying to reload with back button
	 echo '<br>';
	 $_SESSION['error']='using backboutton for retry';
	header('Location: getGamePblmNum.php');
  return; 
 }
	//$_SESSION['count'] = 0;
	
Require_once "pdo.php";

	
		
		//$_SESSION['wrongC']=$wrongCount; 
	
	// initialize some variables
	
	$probParts=0;
	//$partsFlag = array('a'=>false,'b'=>false,'c'=>false,'d'=>false,'e'=>false,'f'=>false,'g'=>false,'h'=>false,'i'=>false,'j'=>false);
	$resp = array('a'=>"r",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
	$corr = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
	$unit = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
	$tol=array('a'=>0.02,'b'=>0.02,'c'=>0.02,'d'=>0.02,'e'=>0.02,'f'=>0.02,'g'=>0.02,'h'=>0.02,'i'=>0.02,'j'=>0.02);
	$ansFormat=array('ans_a' =>"",'ans_b' =>"",	'ans_c' =>"",'ans_d' =>"",'ans_e' =>"",'ans_f' =>"",	'ans_g' =>"",'ans_h' =>"",'ans_i' =>"",'ans_j' );
	
	for ($j=0;$j<9;$j++){
		$wrongCount[$j]=0;
		
	}	
	//$_SESSION['wrongC']=$wrongCount; 
	
	$hintLimit = 3;
	$dispBase = 0;
	
	
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
    $_SESSION['error'] = ('Bad value for problem_id' . $_SESSION["index"] .'and' .$_SESSION["problem_id"]);
    header( 'Location: index.php' ) ;
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
		$_SESSION['error'] = 'Bad value for problem_id in tol get';
		header( 'Location: index.php' ) ;
		return;
	}	
	$probData=$row;	
	//echo $probData['tol_a'];
	
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
		$hintaPath ="uploads/default_hints_game.html";	
	}
	if (strlen($probData['hint_b'])>1){
		$hintb = $probData['hint_b'];
		$hintbPath="uploads/".$hintb;
	}
	else {
		$hintbPath ="uploads/default_hints_game.html";	
	}
if (strlen($probData['hint_c'])>1){
		$hintc = $probData['hint_c'];
		$hintcPath="uploads/".$hintc;
	}
	else {
		$hintcPath ="uploads/default_hints_game.html";	
	}
if (strlen($probData['hint_d'])>1){
		$hintd = $probData['hint_d'];
		$hintdPath="uploads/".$hintd;
	}
	else {
		$hintdPath ="uploads/default_hints_game.html";	
	}
	if (strlen($probData['hint_e'])>1){
		$hinte = $probData['hint_e'];
		$hintePath="uploads/".$hinte;
	}
	else {
		$hintePath ="uploads/default_hints_game.html";	
	}
	if (strlen($probData['hint_f'])>1){
		$hintf = $probData['hint_f'];
		$hintfPath="uploads/".$hintf;
	}
	else {
		$hintfPath ="uploads/default_hints_game.html";	
	}
	if (strlen($probData['hint_g'])>1){
		$hintg = $probData['hint_g'];
		$hintgPath="uploads/".$hintg;
	}
	else {
		$hintgPath ="uploads/default_hints_game.html";	
	}
	if (strlen($probData['hint_h'])>1){
		$hinth = $probData['hint_h'];
		$hinthPath="uploads/".$hinth;
	}
	else {
		$hinthPath ="uploads/default_hints_game.html";	
	}

	if (strlen($probData['hint_i'])>1){
		$hinti = $probData['hint_i'];
		$hintiPath="uploads/".$hinti;
	}
	else {
		$hintiPath ="uploads/default_hints_game.html";	
	}
	if (strlen($probData['hint_j'])>1){
		$hintj = $probData['hint_j'];
		$hintjPath="uploads/".$hintj;
	}
	else {
		$hintjPath ="uploads/default_hints_game.html";	
	}
	

	
	$unit = array_slice($row,22,20);  // dows the same thing but easier so long as the table always has the same structure
	//print_r($unit);


	

//echo "the number of parts for this problem is ". $probParts;	

// test to see if the instructor put in the code to get the answers					
					
	$dispAns=substr($_POST['dex_num'],0,7);

	if($dispAns=="McKetta" ){
		
		
		$index=substr($_POST['dex_num'],7)+0;
		
		$dispAnsflag=True;
	
	}
	else
	{

		$dispAnsflag=False;
		$index = $_POST['dex_num']+0;
	}	

	// keep track of the number of tries the student makes
if(!($_SESSION['count'])){
	$_SESSION['count'] = 1;

	for ($j=0;$j<=9;$j++){
				$wrongCount[$j]=0;
				$_SESSION['wrongC'[$j]]=$wrongCount[$j]; 
			}
	
	$count=1;
}else{
	$count = $_SESSION['count'] + 1;
	$_SESSION['count'] = $count;

}
// read the student responses into an array
	$resp['a']=$_POST['a']+0;
	$resp['b']=$_POST['b']+0;
	$resp['c']=$_POST['c']+0;
	$resp['d']=$_POST['d']+0;
	$resp['e']=$_POST['e']+0;
	$resp['f']=$_POST['f']+0;
	$resp['g']=$_POST['g']+0;
	$resp['h']=$_POST['h']+0;
	$resp['i']=$_POST['i']+0;
	$resp['j']=$_POST['j']+0;
	
	
//print_r($partsFlag);
//echo '<br>';
//print_r( $soln);
//echo '<br>';
//print_r( $tol);
//echo '<br>';
//print_r( $resp);
//echo '<br>';
//for ($k=0;$k<=9;$k++){
//	echo $resp[$resp_key[$k]];
//	echo '<br>';
//}	 
	For ($j=0; $j<=9; $j++) {
		if($partsFlag[$j]) {
				//If ($soln[$j]>((1-$tol[$tol_key[$j]])*$resp[$resp_key[$j]]) and ($soln[$j]<((1+$tol[$tol_key[$j]]))*($resp[$resp_key[$j]]))) //if the correct value is within the response plus or minus the tolerance
							
				if($soln[$j]==0){  // take care of the zero solution case
					$sol=1;
				} else {
					$sol=$soln[$j];
				}	
				
				if(	abs(($soln[$j]-$resp[$resp_key[$j]])/$sol)<= $tol[$tol_key[$j]]) {
							
							
									
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
							elseif ($resp[$resp_key[$j]]==0)  // did not attempt it
							{
								
								$wrongCount[$j] = ($_SESSION['wrongC'[$j]]);
								//$_SESSION['wrongC'[$j]] = $wrongCount[$j];
								$corr[$corr_key[$j]]='';
							//	echo ($wrongCount[$j]);
							}
							else  // response is equal to zero so probably did not answer (better to use POST value I suppose - fix later
							{
								$wrongCount[$j] = ($_SESSION['wrongC'[$j]])+1;
								$_SESSION['wrongC'[$j]] = $wrongCount[$j];
									$corr[$corr_key[$j]]='Not Correct';
								//	echo ($wrongCount[$j]);	
							}
				}		
		}
	}

	
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
	
	$PScore=$score/$probParts*100;  
	$rand= rand(100000,999999);  // sets up the rtn code on other page
	$rand2=rand(0,9);				// sets up the rtn code on the other page
	$_SESSION['rand']=$rand;
	$_SESSION['rand2']=$rand2;
	$_SESSION['points']=$score;
	
if(isset($_POST['dex_num']) && $index<=200 && $index>0 && $dispAnsflag)
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
?>
</table>



<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRGameCheck</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
</head>

<body>
<header>
<h1>QRGame Checker</h1>
</header>
<main>


<p> Problem Number: <?php echo ($_SESSION['problem_id']) ?> </p>
<p> Bonus Value: <?php echo ($_SESSION['bonus']) ?> </p>


<form autocomplete="off" method="POST" >
<!-- <p>Problem Number: <input type="text" name="problem_number" ></p> -->
<!-- <p> Please put in your index number </p> -->
<!--<p><font color=#003399>Index: </font><input type="text" name="dex_num" size=3 value="<?php echo (htmlentities($_SESSION['index']))?>"  ></p> -->
<!--<p> <strong> Fill in - then select "Check" </strong></p> -->

<?php

if ($partsFlag[0]){ ?> 
<p> a): <input [ type=number]{width: 5%;} name="a" size = 10% value="<?php echo (htmlentities($resp['a']))?>" > <?php echo($unit[0]) ?> &nbsp - <b><?php echo ($corr['a']) ?> </b><?php if (@$wrongCount[0]>$hintLimit and $corr['a']=="Not Correct"){echo '<a href="'.$hintaPath.'"target = "_blank"> hints for this part </a>';} ?>  </p>

<?php } 
if ($partsFlag[1]){ ?> 
<p> b): <input [ type=number]{width: 5%;} name="b" size = 10% value="<?php echo (htmlentities($resp['b']))?>" > <?php echo($unit[1]) ?> &nbsp - <b><?php echo ($corr['b']) ?> </b><?php if (@$wrongCount[1]>$hintLimit and $corr['b']=="Not Correct"){echo '<a href="'.$hintbPath.'"target = "_blank"> hints for this part </a>';} ?>  </p>
<?php } 
if ($partsFlag[2]){ ?> 
<p> c): <input [ type=number]{width: 5%;} name="c" size = 10% value="<?php echo (htmlentities($resp['c']))?>" > <?php echo($unit[2]) ?> &nbsp - <b><?php echo ($corr['c']) ?> </b><?php if (@$wrongCount[2]>$hintLimit and $corr['c']=="Not Correct"){echo '<a href="'.$hintcPath.'"target = "_blank"> hints for this part </a>';} ?>  </p>
<?php } 
if ($partsFlag[3]){ ?> 
<p> d): <input [ type=number]{width: 5%;} name="d" size = 10% value="<?php echo (htmlentities($resp['d']))?>" > <?php echo($unit[3]) ?> &nbsp - <b><?php echo ($corr['d']) ?> </b><?php if (@$wrongCount[3]>$hintLimit and $corr['d']=="Not Correct"){echo '<a href="'.$hintdPath.'"target = "_blank"> hints for this part </a>';} ?>  </p>
<?php } 
if ($partsFlag[4]){ ?> 
<p> e): <input [ type=number]{width: 5%;} name="e" size = 10% value="<?php echo (htmlentities($resp['e']))?>" > <?php echo($unit[4]) ?> &nbsp - <b><?php echo ($corr['e']) ?> </b><?php if (@$wrongCount[4]>$hintLimit and $corr['e']=="Not Correct"){echo '<a href="'.$hintePath.'"target = "_blank"> hints for this part </a>';} ?>  </p>
<?php } 
if ($partsFlag[5]){ ?> 
<p> f): <input [ type=number]{width: 5%;} name="f" size = 10% value="<?php echo (htmlentities($resp['f']))?>" > <?php echo($unit[5]) ?> &nbsp - <b><?php echo ($corr['f']) ?> </b><?php if (@$wrongCount[5]>$hintLimit and $corr['f']=="Not Correct"){echo '<a href="'.$hintfPath.'"target = "_blank"> hints for this part </a>';} ?>  </p>
<?php } 
if ($partsFlag[6]){ ?> 
<p> g): <input [ type=number]{width: 5%;} name="g" size = 10% value="<?php echo (htmlentities($resp['g']))?>" > <?php echo($unit[6]) ?> &nbsp - <b><?php echo ($corr['g']) ?> </b><?php if (@$wrongCount[6]>$hintLimit and $corr['g']=="Not Correct"){echo '<a href="'.$hintgPath.'"target = "_blank"> hints for this part </a>';} ?>  </p>
<?php } 
if ($partsFlag[7]){ ?> 
<p> h): <input [ type=number]{width: 5%;} name="h" size = 10% value="<?php echo (htmlentities($resp['h']))?>" > <?php echo($unit[7]) ?> &nbsp - <b><?php echo ($corr['h']) ?> </b><?php if (@$wrongCount[7]>$hintLimit and $corr['h']=="Not Correct"){echo '<a href="'.$hinthPath.'"target = "_blank"> hints for this part </a>';} ?>  </p>
<?php } 
if ($partsFlag[8]){ ?> 
<p> i): <input [ type=number]{width: 5%;} name="i" size = 10% value="<?php echo (htmlentities($resp['i']))?>" > <?php echo($unit[8]) ?> &nbsp - <b><?php echo ($corr['i']) ?> </b><?php if (@$wrongCount[8]>$hintLimit and $corr['i']=="Not Correct"){echo '<a href="'.$hintiPath.'"target = "_blank"> hints for this part </a>';} ?>  </p>
<?php } 
if ($partsFlag[9]){ ?> 
<p> j): <input [ type=number]{width: 5%;} name="j" size = 10% value="<?php echo (htmlentities($resp['j']))?>" > <?php echo($unit[9]) ?> &nbsp - <b><?php echo ($corr['j']) ?> </b><?php if (@$wrongCount[9]>$hintLimit and $corr['j']=="Not Correct"){echo '<a href="'.$hintjPath.'"target = "_blank"> hints for this part </a>';} ?>  </p>
<?php } 

$_SESSION['time']=time();
?>

<!--<p>Grading Scheme: <input type="text" name="grade_scheme" ></p> -->
<p><input type = "submit" value="Check" size="10" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp <b> <font size="4" color="Navy">Score:  <?php echo (round($PScore)) ?>%</font></b></p>


</form>




<p> Count: <?php echo ($count) ?> </p>

<!-- <form method="get" >
<p><input type = "submit" value="Finished"/> </p>
</form> -->

<form action="StopGame.php" method="POST">
 <hr>
<p><b><font Color="red">Finished:</font></b></p>
  <!--<input type="hidden" name="score" value=<?php echo ($score) ?> /> -->
   <?php $_SESSION['score'] = $PScore;  $_SESSION['count'] = $count; ?>
 <b><input type="submit" value="Finished" name="score" style = "width: 30%; background-color:yellow "></b>
 <p><br> </p>
 <hr>
</form>

<!--<form method = "POST">
<p><input type = "submit" value="Get Base-Case Answers" name = "show_base" size="10" style = "width: 30%; background-color: green; color: white"/> &nbsp &nbsp <b> <font size="4" color="Green"></font></b></p>
</form> -->


<?php
/* 

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




 */
?>




</main>
</body>
</html>