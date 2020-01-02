<?php
session_start();
//	if(session_status()!=PHP_SESSION_ACTIVE) session_start();  // put this in to try to get rid of a warning of headers already sent - didn't work
	require_once "pdo.php";

	
	
	   $_SESSION['score'] = "0";
		/* if($_SESSION['oldPoints']!==0){ //trying to reload with back button - old way take care of this when we do the new Game table
		 echo '<br>';
		 $_SESSION['error']='using backboutton for retry';
		header('Location: getGamePblmNum.php');
	  return; 
	 } */
		//$_SESSION['count'] = 0;

	if ( isset($_POST['dex']) ) {
			$dex = $_POST['dex'];
	} else {
		  $_SESSION['error'] = "Missing dex";
		  header('Location: getGamePblmNum.php');
		  return;
	}
	if ( isset($_POST['problem_id']) ) {
			$problem_id = $_POST['problem_id'];
		} else {
		  $_SESSION['error'] = "Missing problem_id";
		  header('Location: getGamePblmNum.php');
		  return;
		}
		
	if ( isset($_POST['game_id']) ) {
			$game_id = $_POST['game_id'];
		}  else {
		  $_SESSION['error'] = "Missing game_id";
		  header('Location: getGamePblmNum.php');
		  return;
		}	
		if ( isset($_POST['stop_time']) ) {
			$stop_time = $_POST['stop_time'];
		} else {
			$_SESSION['error'] = "Missing stop_time";
			  header('Location: getGamePblmNum.php');
			  return;
		}	
		
		
		
			// initialize a few variables
			$count = 0;
			for ($j=0;$j<=9;$j++){
					$wrongCount[$j]=0;		// accumulates how many times they missed a part
					$_SESSION['wrongC'[$j]]=0; // temp
					$changed[$j]=0;		// 1 if they changed their response ero otherwise
					$addCount[$j]=0;  // this is zero if they get it right and 1 if they get it wrong
			}	
			
		//	$_SESSION['pin'] = $pin;
		//	$_SESSION['iid'] = $iid;
			$_SESSION['problem_id'] = $problem_id;
			
			
			$score = 0;
			$PScore = 0;
			$partsFlag = array();
			$resp = array('a'=>0,'b'=>0,'c'=>0,'d'=>0,'e'=>0,'f'=>0,'g'=>0,'h'=>0,'i'=>0,'j'=>0);
			//$resp = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
			$corr = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
			$unit = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
			$tol=array('a'=>0.02,'b'=>0.02,'c'=>0.02,'d'=>0.02,'e'=>0.02,'f'=>0.02,'g'=>0.02,'h'=>0.02,'i'=>0.02,'j'=>0.02);
			$ansFormat=array('ans_a' =>"",'ans_b' =>"",	'ans_c' =>"",'ans_d' =>"",'ans_e' =>"",'ans_f' =>"",	'ans_g' =>"",'ans_h' =>"",'ans_i' =>"",'ans_j'=>"" );
			
			
			$hintLimit = 3;
			$dispBase = 1;
			
			$_SESSION['wrote_try_flag']=false;

			$tol_key=array_keys($tol);
			$resp_key=array_keys($resp);
			$corr_key=array_keys($corr);
			$ansFormat_key=array_keys($ansFormat);
			
			$time_sleep1 = 2;  // time delay in seconds
			$time_sleep1_trip = 5;  // number of trials it talkes to trip the time delay
			$time_sleep2 = 5;  // additional time if hit the next limit
			$time_sleep2_trip = 10;	
			
			// see if the problem has been suspended	
				
			$stmt = $pdo->prepare("SELECT * FROM Problem where problem_id = :problem_id");
			$stmt->execute(array(":problem_id" => $problem_id));
			$row = $stmt -> fetch();
			if ( $row === false ) {
				$_SESSION['error'] = 'Bad value for problem_id';
				header( 'Location: getGamePblmNum.php' ) ;
				return;
			}	
			$probData=$row;	
			
			$probStatus = $probData['status'];
			if ($probStatus =='suspended'){
				$_SESSION['error'] = 'problem has been suspended, check back later';
				header( 'Location: getGamePblmNum.php' ) ;
				return;	
			}
				
		

	
			// get the tolerances and if the part has any hintfile	
				
				
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
			
			
			if (strlen($probData['hint_a'])>1){$hinta = $probData['hint_a'];$hintaPath="uploads/".$hinta;} else {$hintaPath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_b'])>1){$hintb = $probData['hint_b'];$hintbPath="uploads/".$hintb;} else {$hintbPath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_c'])>1){$hintc = $probData['hint_c'];$hintcPath="uploads/".$hintc;} else {$hintcPath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_d'])>1){$hintd = $probData['hint_d'];$hintdPath="uploads/".$hintd;} else {$hintdPath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_e'])>1){$hinte = $probData['hint_e'];$hintePath="uploads/".$hinte;} else {$hintePath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_f'])>1){$hintf = $probData['hint_f'];$hintfPath="uploads/".$hintf;} else {$hintfPath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_g'])>1){$hintg = $probData['hint_g'];$hintgPath="uploads/".$hintg;} else {$hintgPath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_h'])>1){$hinth = $probData['hint_h'];$hinthPath="uploads/".$hinth;} else {$hinthPath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_i'])>1){$hinti = $probData['hint_i'];$hintiPath="uploads/".$hinti;} else {$hintiPath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_j'])>1){$hintj = $probData['hint_j'];$hintjPath="uploads/".$hintj;} else {$hintjPath ="uploads/default_hints.html";	}
					
			$unit = array_slice($row,22,20);  // does the same thing but easier so long as the table always has the same structure
			//print_r($unit);
			
			
		
			// Next check the Qa table and see which values have non null values - for those 
			$probParts=0;
			$stmt = $pdo->prepare("SELECT * FROM Qa where problem_id = :problem_id AND dex = :dex");
			$stmt->execute(array(":problem_id" => $problem_id, ":dex" => $dex));
			$row = $stmt -> fetch();
			if ( $row === false ) {
				$_SESSION['error'] = 'Bad value for problem_id';
				header( 'Location: getGamePblmNum.php' ) ;
				return;
			}	
				$soln = array_slice($row,6,20); // this would mean the database table Qa would have the same structure - change the structure of the table and you break the code
			

			for ($i = 0;$i<=9; $i++){  
				if ($soln[$i]>=1.2e43 && $soln[$i] < 1.3e43) {
					$partsFlag[$i]=false;
				} else {
					$probParts = $probParts+1;
					$partsFlag[$i]=true;
				}
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

		
		
		  // we are coming through the first time
		
		$PScore=$score/$probParts*100;  
	
		$_SESSION['points']=$score;
		
	
		
		
		
		
	?>
	</table>



	<!DOCTYPE html>
	<html lang = "en">
	<head>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<meta Charset = "utf-8">
	<title>QRGameCheck</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" /> 
		<link rel="stylesheet" type="text/css" href="jquery.countdown.css"> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript" src="jquery.plugin.js"></script> 
		<script type="text/javascript" src="jquery.countdown.js"></script>
		

		<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
		
	</head>

	<body>
	<header>
	<h1>QRGame Checker</h1>
	</header>
	<main>
	<h3>  Game Number: <?php echo ($game_id) ?> </h3>
	

	<font size = "1"> Problem Number: <?php echo ($problem_id) ?> -  <?php echo ($dex) ?> </font>



	<form autocomplete="off" method="POST" >
	<!-- <p>Problem Number: <input type="text" name="problem_number" ></p> -->
	<!-- <p> Please put in your index number </p> -->
	<!--<p><font color=#003399>Index: </font><input type="text" name="dex_num" size=3 value="<?php echo (htmlentities($_SESSION['index']))?>"  ></p> -->
	<!--<p> <strong> Fill in - then select "Check" </strong></p> -->

	<?php

	if ($partsFlag[0]){ ?> 
	<p> a): <input [ type=number]{width: 5%;} name="a" size = 10% value="<?php echo (htmlentities($resp['a']))?>" > <?php echo($unit[0]) ?> &nbsp - <b><?php echo ($corr['a']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['a']=="Correct" ){echo '- Computed value is: '.$soln[0];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[0]>$hintLimit and $corr['a']=="Not Correct" && $hintaPath != "uploads/default_hints.html" ){echo '<a href="'.$hintaPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[0] and @$wrongCount[0]>$time_sleep1_trip and @$wrongCount[0]< $time_sleep2_trip and $corr['a']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[0] and @$wrongCount[0]>=$time_sleep2_trip and $corr['a']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	  </p>
	<?php } 


	if ($partsFlag[1]){ ?> 
	<p> b): <input [ type=number]{width: 5%;} name="b" size = 10% value="<?php echo (htmlentities($resp['b']))?>" > <?php echo($unit[1]) ?> &nbsp - <b><?php echo ($corr['b']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['b']=="Correct" ){echo '- Computed value is: '.$soln[1];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[1]>$hintLimit and $corr['b']=="Not Correct" && $hintbPath != "uploads/default_hints.html" ){echo '<a href="'.$hintbPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[1] and @$wrongCount[1]>$time_sleep1_trip and @$wrongCount[1]< $time_sleep2_trip and $corr['b']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[1] and @$wrongCount[1]>=$time_sleep2_trip and $corr['b']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[2]){ ?> 
	<p> c): <input [ type=number]{width: 5%;} name="c" size = 10% value="<?php echo (htmlentities($resp['c']))?>" > <?php echo($unit[2]) ?> &nbsp - <b><?php echo ($corr['c']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['c']=="Correct" ){echo '- Computed value is: '.$soln[2];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[2]>$hintLimit and $corr['c']=="Not Correct"&& $hintcPath != "uploads/default_hints.html" ){echo '<a href="'.$hintcPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[2] and @$wrongCount[2]>$time_sleep1_trip and @$wrongCount[2]< $time_sleep2_trip and $corr['c']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[2] and @$wrongCount[2]>=$time_sleep2_trip and $corr['c']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[3]){ ?> 
	<p> d): <input [ type=number]{width: 5%;} name="d" size = 10% value="<?php echo (htmlentities($resp['d']))?>" > <?php echo($unit[3]) ?> &nbsp - <b><?php echo ($corr['d']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['d']=="Correct" ){echo '- Computed value is: '.$soln[3];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[3]>$hintLimit and $corr['d']=="Not Correct"&& $hintdPath != "uploads/default_hints.html" ){echo '<a href="'.$hintdPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[3] and @$wrongCount[3]>$time_sleep1_trip and @$wrongCount[3]< $time_sleep2_trip and $corr['d']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[3] and @$wrongCount[3]>=$time_sleep2_trip and $corr['d']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[4]){ ?> 
	<p> e): <input [ type=number]{width: 5%;} name="e" size = 10% value="<?php echo (htmlentities($resp['e']))?>" > <?php echo($unit[4]) ?> &nbsp - <b><?php echo ($corr['e']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['e']=="Correct" ){echo '- Computed value is: '.$soln[4];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[4]>$hintLimit and $corr['e']=="Not Correct"&& $hintePath != "uploads/default_hints.html" ){echo '<a href="'.$hintePath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[4] and @$wrongCount[4]>$time_sleep1_trip and @$wrongCount[4]< $time_sleep1_trip and $corr['e']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[4] and @$wrongCount[4]>=$time_sleep2_trip and $corr['e']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[5]){ ?> 
	<p> f): <input [ type=number]{width: 5%;} name="f" size = 10% value="<?php echo (htmlentities($resp['f']))?>" > <?php echo($unit[5]) ?> &nbsp - <b><?php echo ($corr['f']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['f']=="Correct" ){echo '- Computed value is: '.$soln[5];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[5]>$hintLimit and $corr['f']=="Not Correct"&& $hintfPath != "uploads/default_hints.html" ){echo '<a href="'.$hintfPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[5] and @$wrongCount[5]>$time_sleep1_trip and @$wrongCount[5]< $time_sleep2_trip and $corr['f']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[5] and @$wrongCount[5]>=$time_sleep2_trip and $corr['f']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[6]){ ?> 
	<p> g): <input [ type=number]{width: 5%;} name="g" size = 10% value="<?php echo (htmlentities($resp['g']))?>" > <?php echo($unit[6]) ?> &nbsp - <b><?php echo ($corr['g']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['g']=="Correct" ){echo '- Computed value is: '.$soln[6];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[6]>$hintLimit and $corr['g']=="Not Correct"&& $hintgPath != "uploads/default_hints.html" ){echo '<a href="'.$hintgPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[6] and @$wrongCount[6]>$time_sleep1_trip and @$wrongCount[6]< $time_sleep2_trip and $corr['g']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[6] and @$wrongCount[6]>=$time_sleep2_trip and $corr['g']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[7]){ ?> 
	<p> h): <input [ type=number]{width: 5%;} name="h" size = 10% value="<?php echo (htmlentities($resp['h']))?>" > <?php echo($unit[7]) ?> &nbsp - <b><?php echo ($corr['h']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['h']=="Correct" ){echo '- Computed value is: '.$soln[7];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[7]>$hintLimit and $corr['h']=="Not Correct"&& $hinthPath != "uploads/default_hints.html" ){echo '<a href="'.$hinthPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[7] and @$wrongCount[7]>$time_sleep1_trip and @$wrongCount[7]< $time_sleep2_trip and $corr['h']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[7] and @$wrongCount[7]>=$time_sleep2_trip and $corr['h']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[8]){ ?> 
	<p> i): <input [ type=number]{width: 5%;} name="i" size = 10% value="<?php echo (htmlentities($resp['i']))?>" > <?php echo($unit[8]) ?> &nbsp - <b><?php echo ($corr['i']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['i']=="Correct" ){echo '- Computed value is: '.$soln[8];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[8]>$hintLimit and $corr['i']=="Not Correct"&& $hintiPath != "uploads/default_hints.html" ){echo '<a href="'.$hintiPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[8] and @$wrongCount[8]>$time_sleep1_trip and @$wrongCount[8]< $time_sleep2_trip and $corr['i']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[8] and @$wrongCount[8]>=$time_sleep2_trip and $corr['i']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[9]){ ?> 
	<p> j): <input [ type=number]{width: 5%;} name="j" size = 10% value="<?php echo (htmlentities($resp['j']))?>" > <?php echo($unit[9]) ?> &nbsp - <b><?php echo ($corr['j']) ?> </b>
	<?php if (isset($_POST['pin']) and @$wrongCount[9]>$hintLimit and $corr['j']=="Not Correct"&& $hintjPath != "uploads/default_hints.html" ){echo '<a href="'.$hintjPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[9] and @$wrongCount[9]>$time_sleep1_trip and @$wrongCount[9]< $time_sleep2_trip and $corr['j']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[9] and @$wrongCount[9]>=$time_sleep2_trip and $corr['j']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 



	$_SESSION['time']=time();
	?>

	<!--<p>Grading Scheme: <input type="text" name="grade_scheme" ></p> -->
	<p><input type = "submit" value="Check" size="10" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp <b> <font size="4" color="Navy">Score:  <?php echo (round($PScore)) ?>%</font></b></p>
			<p><font color=#003399> </font><input type="hidden" id = "dex" name="dex" size=3 value="<?php echo (htmlentities($dex))?>"  ></p>
			<p><font color=#003399> </font><input type="hidden" id = "problem_id" name="problem_id" size=3 value="<?php echo (htmlentities($problem_id))?>"  ></p>
			<p><font color=#003399> </font><input type="hidden" id = "game_id" name="game_id" size=3 value="<?php echo (htmlentities($game_id))?>"  ></p>
			<p><font color=#003399> </font><input type="hidden" id = "stop_time" name="stop_time" size=3 value="<?php echo (htmlentities($stop_time))?>"  ></p>
			

	</form>


		<div id="defaultCountdown"> </div>

	<p> Count: <?php echo ($count) ?> </p>

	<!-- <form method="get" >
	<p><input type = "submit" value="Finished"/> </p>
	</form> -->

	<form action="StopGame.php" method="POST">
	 <hr>
	<p><b><font Color="red">Finished:</font></b></p>
	  <!--<input type="hidden" name="score" value=<?php echo ($score) ?> /> -->
	   <?php $_SESSION['score'] = round($PScore);  $_SESSION['count'] = $count; ?>
	 <b><input type="submit" value="Finished" name="score" style = "width: 30%; background-color:yellow "></b>
	 <p><br> </p>
	 <hr>
	</form>

	<!--<form method = "POST">
	<p><input type = "submit" value="Get Base-Case Answers" name = "show_base" size="10" style = "width: 30%; background-color: green; color: white"/> &nbsp &nbsp <b> <font size="4" color="Green"></font></b></p>
	</form> -->

	<script>
		$(document).ready( function () {
				var stop_time = $("#stop_time").val();
				var stop_time = new Date(stop_time);
				
	/* 			
				$.countdown.resync();
				var now = new Date();
				now_ms = Math.abs(now);
				stop_time_ms = Math.abs(stop_time);
				
				console.log ('now_ms = ',now_ms);
				console.log ('stop_time_ms = ',stop_time_ms);
				var diff_time_ms = Math.floor(stop_time_ms-now_ms); 
				console.log ('diff_time_ms = ',diff_time_ms);
					*/
			
				//	$('#defaultCountdown').countdown({until: "10s", expiryUrl: 'http://jquery.com', 
				//		description: 'To go to jQuery'}); 
						 
					/* $('#newPageStart').click(function() { 
						shortly = new Date(); 
						shortly.setSeconds(shortly.getSeconds() + 5.5); 
						$('#newPage').countdown('option', {until: shortly}); 
					}); */
			
			//$('#highlightCountdown').countdown({until: 0, 
			//	onTick: highlightLast5}); 
			function highlightLast(periods) { 
				if ($.countdown.periodsToSeconds(periods) === 20) { 
					$(this).css('color', 'red','font-weight', 'Bold' ); 
				} 
				if ($.countdown.periodsToSeconds(periods) <= 60) { 
					$(this).css('background-color', 'yellow'); 
				} 
			
			};
			
				$('#defaultCountdown').countdown({until: stop_time, format: 'ms',  layout: '{d<}{dn} {dl} {d>}{h<}{hn} {hl} {h>}{m<}{mn} {ml} {m>}{s<}{sn} {sl}{s>}',expiryUrl: 'StopGame.php',onTick: highlightLast}); 
				
				
			});		
	</script>

	




	</main>
	</body>
	</html>