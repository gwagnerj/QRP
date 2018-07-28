<?php
 session_start();
   $_SESSION['score'] = "0";
	if($_SESSION['oldPoints']!==0){ //trying to reload with back button
	 echo '<br>';
	 $_SESSION['error']='using backboutton for retry';
	header('Location: getGamePblmNum.php');
  return; 
 }
Require_once "pdo.php";
	
	// initialize some variables
	
	$probParts=0;
	$resp = array('a'=>"r",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
	$corr = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
	$unit = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
	$tol=array('a'=>0.02,'b'=>0.02,'c'=>0.02,'d'=>0.02,'e'=>0.02,'f'=>0.02,'g'=>0.02,'h'=>0.02,'i'=>0.02,'j'=>0.02);
	$ansFormat=array('ans_a' =>"",'ans_b' =>"",	'ans_c' =>"",'ans_d' =>"",'ans_e' =>"",'ans_f' =>"",	'ans_g' =>"",'ans_h' =>"",'ans_i' =>"",'ans_j' );
	
	for ($j=0;$j<9;$j++){
		$wrongCount[$j]=0;
		
	}	
	
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
		$soln = array_slice($row,6); // this would mean the database table Qa would have the same structure
	

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
	
	$tol['a']=$probData['tol_a']*0.03;	
	$tol['b']=$probData['tol_b']*0.03;
	$tol['c']=$probData['tol_c']*0.03;	
	$tol['d']=$probData['tol_d']*0.03;
	$tol['e']=$probData['tol_e']*0.03;	
	$tol['f']=$probData['tol_f']*0.03;
	$tol['g']=$probData['tol_g']*0.03;	
	$tol['h']=$probData['tol_h']*0.03;
	$tol['i']=$probData['tol_i']*0.03;	
	$tol['j']=$probData['tol_j']*0.03;	
	
	

	
	$unit = array_slice($row,22,20);  // does the same thing but easier so long as the table always has the same structure


//echo "the number of parts for this problem is ". $probParts;	

// test to see if the instructor put in the code to get the answers					
					
	$dispAns=substr($_POST['dex_num'],0,7);


	// keep track of the number of tries the student makes

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
	
	$numParts=0;
	
	
if(!($_SESSION['count'])){
	$_SESSION['count'] = 1;

	for ($j=0;$j<=9;$j++){
				$wrongCount[$j]=0;
				$_SESSION['wrongC'[$j]]=$wrongCount[$j]; 
			}
	
	$count=1;
}else{
	
	// redirect to checker
	$count = $_SESSION['count'] + 1;
	$_SESSION['count'] = $count;

}
if ($_SESSION['count'] ==2){
	$_SESSION['bonus']=1; // assumes that they have answered all of the questions
 
	 For ($j=0; $j<=9; $j++) {
		if($partsFlag[$j]) {
				$numParts=$numParts+1;			
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
							}
							elseif ($resp[$resp_key[$j]]==0)  // did not attempt it
							{
								$_SESSION['bonus']=0;
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
	if ($numParts == $score){
		$_SESSION['bonus']=2;
	}
	header( 'Location: QRGameCheck.php' ) ;
    return;
}

	
	
	
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
<p> Problem Number: <?php echo ($_SESSION['problem_id']) ?> </p>
<p> If responses are: <br> 
correct order of magnitude receive 1 bonus dice. <br>
within 30% of answers receive 2 bonus dice</p>
<h1>Please Guesstimate Answers </h1>

<h2>Maximum Time: 1 minute</h2>
</header>
<main>
<script>
function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10)
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            location.replace("http://localhost/qrp/QRGameCheck.php");
			timer = duration;
        }
    }, 1000);
}

window.onload = function () {
    var oneMinutes = 60 * 1,
        display = document.querySelector('#time');
    startTimer(oneMinutes, display);
	
};

</script>



<h3> Timer: <span id="time">01:00</span> Minutes:Seconds</h3>


<form autocomplete="off" method="POST" >
<!-- <p>Problem Number: <input type="text" name="problem_number" ></p> -->
<!-- <p> Please put in your index number </p> -->
<!--<p><font color=#003399>Index: </font><input type="text" name="dex_num" size=3 value="<?php echo (htmlentities($_SESSION['index']))?>"  ></p> -->
<!--<p> <strong> Fill in - then select "Check" </strong></p> -->

<?php

if ($partsFlag[0]){ ?> 
<p> a): <input [ type=number]{width: 5%;} name="a" size = 10% value=0> <?php echo($unit[0]); ?>   </p>
<?php } 
if ($partsFlag[1]){ ?> 
<p> b): <input [ type=number]{width: 5%;} name="b" size = 10%  value = 0> <?php echo($unit[1]); ?>  </p>
<?php } 
if ($partsFlag[2]){ ?> 
<p> c): <input [ type=number]{width: 5%;} name="c" size = 10%  value = 0> <?php echo($unit[2]); ?>  </p>
<?php } 
if ($partsFlag[3]){ ?> 
<p> d): <input [ type=number]{width: 5%;} name="d" size = 10%  value = 0> <?php echo($unit[3]); ?>   </p>
<?php } 
if ($partsFlag[4]){ ?> 
<p> e): <input [ type=number]{width: 5%;} name="e" size = 10%  value = 0> <?php echo($unit[4]); ?>  </p>
<?php } 
if ($partsFlag[5]){ ?> 
<p> f): <input [ type=number]{width: 5%;} name="f" size = 10%  value = 0> <?php echo($unit[5]); ?>   </p>
<?php } 
if ($partsFlag[6]){ ?> 
<p> g): <input [ type=number]{width: 5%;} name="g" size = 10%  value = 0> <?php echo($unit[6]); ?>  </p>
<?php } 
if ($partsFlag[7]){ ?> 
<p> h): <input [ type=number]{width: 5%;} name="h" size = 10%  value = 0> <?php echo($unit[7]); ?>  </p>
<?php } 
if ($partsFlag[8]){ ?> 
<p> i): <input [ type=number]{width: 5%;} name="i" size = 10%  value = 0> <?php echo($unit[8]); ?>  </p>
<?php } 
if ($partsFlag[9]){ ?> 
<p> j): <input [ type=number]{width: 5%;} name="j" size = 10%  value = 0> <?php echo($unit[9]); ?>  </p>
<?php } 

$_SESSION['time']=time();
?>

<!--<p>Grading Scheme: <input type="text" name="grade_scheme" ></p> -->
<p><input type = "submit" value="Submit Guesses" size="10" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp <b> <font size="4" color="Navy">Score:  <?php echo (round($PScore)) ?>%</font></b></p>


</form>







</main>
</body>
</html>