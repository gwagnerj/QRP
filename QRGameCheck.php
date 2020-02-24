<?php
session_start();
//	if(session_status()!=PHP_SESSION_ACTIVE) session_start();  // put this in to try to get rid of a warning of headers already sent - didn't work
	require_once "pdo.php";



	   if (isset($_POST['game_id'])){
        $game_id = $_POST['game_id'];
    }  elseif(isset($_SESSION['game_id'])){
         $game_id = $_SESSION['game_id'];
    } else  {
       $_SESSION['error'] = "Missing game_id from QRGameCheck";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['game_id'] = $game_id;
   

   
     if (isset($_POST['pin'])){
        $pin = $_POST['pin'];
    }  elseif(isset($_SESSION['pin'])){
         $pin = $_SESSION['pin'];
    } else  {
       $_SESSION['error'] = "Missing pin from QRGameCheck";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['pin'] = $pin;
    
    
      if (isset($_POST['team_id'])){
        $team_id = $_POST['team_id'];
    }  elseif(isset($_SESSION['team_id'])){
         $team_id = $_SESSION['team_id'];
    } else  {
       $_SESSION['error'] = "Missing team_id from QRGameCheck";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['team_id'] = $team_id;
    
      if (isset($_POST['gmact_id'])){
        $gmact_id = $_POST['gmact_id'];
    }  elseif(isset($_SESSION['gmact_id'])){
         $gmact_id = $_SESSION['gmact_id'];
    } else  {
       $_SESSION['error'] = "Missing gmact_id from QRGameCheck";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['gmact_id'] = $gmact_id;
    
      if (isset($_POST['gameactivity_id'])){
        $gameactivity_id = $_POST['gameactivity_id'];
    }  elseif(isset($_SESSION['gameactivity_id'])){
         $gameactivity_id = $_SESSION['gameactivity_id'];
    } else  {
       $_SESSION['error'] = "Missing gameactivity_id from QRGameCheck";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['gameactivity_id'] = $gameactivity_id;
    
      if (isset($_POST['name'])){
        $name = $_POST['name'];
    }  elseif(isset($_SESSION['name'])){
         $name = $_SESSION['name'];
    } else  {
       $_SESSION['error'] = "Missing name from QRGameCheck";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['name'] = $name;
    
      if (isset($_POST['problem_id'])){
        $problem_id = $_POST['problem_id'];
    }  elseif(isset($_SESSION['problem_id'])){
         $problem_id = $_SESSION['problem_id'];
    } else  {
       $_SESSION['error'] = "Missing problem_id from QRGameCheck";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['problem_id'] = $problem_id;
    
   
      if (isset($_POST['dex'])){
        $dex = $_POST['dex'];
    }  elseif(isset($_SESSION['dex'])){
         $dex = $_SESSION['dex'];
    } else  {
       $_SESSION['error'] = "Missing dex from QRGameCheck";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['dex'] = $dex;
   
   
    
	if ($game_id<1 || $game_id>1000000) {
	  $_SESSION['error'] = "game number out of range";
	  header('Location: index.php');
	  return;
	}
	
        
        // initialize a few variables
        
         $_SESSION['score'] = "0";
			
			$count = 0;
			for ($j=0;$j<=9;$j++){
					$wrongCount[$j]=0;		// accumulates how many times they missed a part
					$_SESSION['wrongC'[$j]]=0; // temp
					$changed[$j]=0;		// 1 if they changed their response ero otherwise
					$addCount[$j]=0;  // this is zero if they get it right and 1 if they get it wrong
			}	
			
	
			
			
			$score = 0;
			$PScore = 0;
			$partsFlag = array();
			$resp = array('a'=>0,'b'=>0,'c'=>0,'d'=>0,'e'=>0,'f'=>0,'g'=>0,'h'=>0,'i'=>0,'j'=>0,'sumb'=>0,'sumlast'=>0);
			//$resp = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
			$corr = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"",'sumb'=>"",'sumlast'=>"");
			$unit = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
			$tol=array('a'=>0.02,'b'=>0.02,'c'=>0.02,'d'=>0.02,'e'=>0.02,'f'=>0.02,'g'=>0.02,'h'=>0.02,'i'=>0.02,'j'=>0.02,'sumb'=>0.02,'sumlast'=>0.02);
			$ansFormat=array('ans_a' =>"",'ans_b' =>"",	'ans_c' =>"",'ans_d' =>"",'ans_e' =>"",'ans_f' =>"",	'ans_g' =>"",'ans_h' =>"",'ans_i' =>"",'ans_j'=>"" ,'ans_sumb'=>"",'ans_sumlast'=>"");
			
			
			$hintLimit = 3;
			$dispBase = 1;
			
			$_SESSION['wrote_try_flag']=false;

			$tol_key=array_keys($tol);
			$resp_key=array_keys($resp);
			$corr_key=array_keys($corr);
			$ansFormat_key=array_keys($ansFormat);
			
			$time_sleep1 = 2;  // time delay in seconds
			$time_sleep1_trip = 5;  // number of trials it talkes to trip the time delay
			$time_sleep2 = 10;  // additional time if hit the next limit
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
			$probParts=2;
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
			$partsFlag[10]=true;
            $partsFlag[11]=true;


		// keep track of the number of tries the student makes
	if(!($_SESSION['count'])){
		$_SESSION['count'] = 1;

		for ($j=0;$j<=9;$j++){
					$wrongCount[$j]=0;
					$_SESSION['wrongC'[$j]]=$wrongCount[$j]; 
				}
		
		$count=2;
	}else{
		$count = $_SESSION['count'] + 1;
		$_SESSION['count'] = $count;

	}
    
    if($_SESSION['count']==1){
        
      // update the ans_sumb and ans_sumlast for the members of the group
       // $stmt = $pdo->prepare("SELECT SUM(`ans_b`) AS ans_sumb FROM `Gameactivity` WHERE gameactivity_id = :gameactivity_id");

       $stmt = $pdo->prepare("SELECT SUM(`ans_b`) AS ans_sumb FROM `Gameactivity` WHERE game_id = :game_id AND team_id = :team_id AND created_at >= DATE_SUB(NOW(),INTERVAL 2 HOUR)");
			$stmt->execute(array(":game_id" => $game_id, ":team_id" => $team_id));
			$row = $stmt -> fetch();
            $ans_sumb = $row['ans_sumb'];
			// echo ($row['ans_sumb']);
            $soln[10]=$ans_sumb;
    
        $stmt = $pdo->prepare("UPDATE `Gameactivity` SET `ans_sumb` = :ans_sumb WHERE gameactivity_id = :gameactivity_id");
			$stmt->execute(array(":gameactivity_id" => $gameactivity_id, ":ans_sumb" => $ans_sumb ));
			
          $stmt = $pdo->prepare("SELECT SUM(`ans_last`) AS ans_sumlast FROM `Gameactivity` WHERE game_id = :game_id AND team_id = :team_id AND created_at >= DATE_SUB(NOW(),INTERVAL 2 HOUR)");
			$stmt->execute(array(":game_id" => $game_id, ":team_id" => $team_id));
			$row = $stmt -> fetch();
            $ans_sumlast = $row['ans_sumlast'];
			$soln[11]=$ans_sumlast;
    
        $stmt = $pdo->prepare("UPDATE `Gameactivity` SET `ans_sumlast` = :ans_sumlast WHERE gameactivity_id = :gameactivity_id");
			$stmt->execute(array(":gameactivity_id" => $gameactivity_id,  ":ans_sumlast" => $ans_sumlast ));
        
    } else {
        
             $stmt = $pdo->prepare("SELECT *  FROM `Gameactivity` WHERE gameactivity_id = :gameactivity_id");
			$stmt->execute(array(":gameactivity_id" => $gameactivity_id));
			$row = $stmt -> fetch();
           
            $ans_sumb = $row['ans_sumb'];
			//echo ($ans_sumb);
            $soln[10]=$ans_sumb;
             $ans_sumlast = $row['ans_sumlast'];
            //echo ($ans_sumlast);
            $soln[11]=$ans_sumlast;
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
		$resp['sumb']=$_POST['sumb']+0;
        $resp['sumlast']=$_POST['sumlast']+0;
		
	//}	 
		For ($j=0; $j<=11; $j++) {
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
	<h3> Name: <?php echo($name);?> </h3>
    <h3> Game number: <?php echo($game_id);?> </h3>
    <h3> PIN: <?php echo($pin);?> </h3>
    <h3> Team Number: <?php echo($team_id);?> </h3>
	

	<font size = "1"> Problem Number: <?php echo ($problem_id) ?> -  <?php echo ($dex) ?> </font>

	<form autocomplete="off" method="POST" >
	<!--<p><font color=#003399>Index: </font><input type="text" name="dex_num" size=3 value="<?php echo (htmlentities($_SESSION['index']))?>"  ></p> -->

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
    
    if ($partsFlag[10]){ ?> 
	<p> Sum of b): <input [ type=number]{width: 5%;} name="sumb" size = 10% value="<?php echo (htmlentities($resp['sumb']))?>" > <?php echo($unit[1]) ?> &nbsp - <b><?php echo ($corr['sumb']) ?> </b>
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

  if ($partsFlag[11]){ ?> 
	<p> Sum of last part): <input [ type=number]{width: 5%;} name="sumlast" size = 10% value="<?php echo (htmlentities($resp['sumlast']))?>" >  &nbsp - <b><?php echo ($corr['sumlast']) ?> </b>
	</p>
	<?php } 


	$_SESSION['time']=time();
	?>

	<p><input type = "submit" value="Check" size="10" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp <b> <font size="4" color="Navy">Score:  <?php echo (round($PScore)) ?>%</font></b></p>
			<p><font color=#003399> </font><input type="hidden" id = "dex" name="dex" size=3 value="<?php echo (htmlentities($dex))?>"  ></p>
            <input type = "number" hidden name = "pin"  value = <?php echo ($pin); ?> > </input>
			<p><font color=#003399> </font><input type="hidden" id = "problem_id" name="problem_id" size=3 value="<?php echo (htmlentities($problem_id))?>"  ></p>
			<p><font color=#003399> </font><input type="hidden" id = "game_id" name="game_id" size=3 value="<?php echo (htmlentities($game_id))?>"  ></p>
			<p><font color=#003399> </font><input type="hidden" id = "stop_time" name="stop_time" size=3 value="<?php echo (htmlentities($stop_time))?>"  ></p>

	</form>

	<p> Count: <?php echo ($count) ?> </p>
	<form action="StopGame.php" method="POST" id = "finished_form">
		<p><input type="hidden" name="game_id" id = "game_id" size=3 value="<?php echo (htmlentities($game_id))?>"  ></p>
        <p><input type="hidden" name="name" id = "name" size=3 value="<?php echo (htmlentities($name))?>"  ></p>
        <p><input type="hidden" name="problem_id" id = "problem_id" size=3 value="<?php echo (htmlentities($problem_id))?>"  ></p>
        <p><input type="hidden" name="gmact_id" id = "gmact_id" size=3 value="<?php echo (htmlentities($gmact_id))?>"  ></p>
        <p><input type="hidden" name="gameactivity_id" id = "gameactivity_id" size=3 value="<?php echo (htmlentities($gameactivity_id))?>"  ></p>
     
        <p><input type="hidden" name="team_id" id = "team_id" size=3 value="<?php echo (htmlentities($team_id))?>"  ></p>
		<p><input type="hidden" id = "dex" name="dex" size=3 value="<?php echo (htmlentities($dex))?>"  ></p>
        <p><input type="hidden" id = "pin" name="pin" size=3 value="<?php echo (htmlentities($pin))?>"  ></p>
         <p><input type="hidden" id = "game_score" name="game_score" size=3 value="<?php echo (htmlentities($PScore))?>"  
    <hr>
	<p><b><font Color="red">Finished:</font></b></p>
	  <!--<input type="hidden" name="score" value=<?php echo ($score) ?> /> -->
	   <?php $_SESSION['score'] = round($PScore);  $_SESSION['count'] = $count; ?>
	 <b><input type="submit" value="Finished" name="score" style = "width: 30%; background-color:yellow "></b>
	 <p><br> </p>
	 <hr>
	</form>


	<script>
		$(document).ready( function () {
                
				// get the current phase
				var gmact_id = $("#gmact_id").val();
				console.log ('gmact_id = ',gmact_id);
				
                     var request;
                function fetchPhase() {
                    request = $.ajax({
                        type: "POST",
                        url: "fetchPhase.php",
                        data: "gmact_id="+gmact_id,
                        success: function(data){
                           try{
                                var arrn = JSON.parse(data);
                            }
                            catch(err) {
                                alert ('game data unavailable Data not found');
                                alert (err);
                                return;
                            }
                            
                             var phase = arrn.phase;
                            var end_of_phase = arrn.end_of_phase;
                            	console.log ('phase = ',phase);
                           if(phase > 4){  // submit away work time has eneded
                               SubmitAway(); 
                            }
                        }
                    });
                }
                setInterval(function() {
                    if (request) request.abort();
                    fetchPhase();
                }, 1000);

                
                
                
                
                
                
                
                
                
              /*   
                window.setInterval(function(){
                      /// call your function here
                      $.post('fetchPhase.php', {gmact_id : gmact_id, }, function(data){
				
                            try{
                                var arrn = JSON.parse(data);
                            }
                            catch(err) {
                                alert ('game data unavailable Data not found');
                                alert (err);
                            }
                            
                             var phase = arrn.phase;
                            var end_of_phase = arrn.end_of_phase;
                            
                            if(phase > 4){  // submit away work time has eneded
                               SubmitAway(); 
                            }
                        });
                      
                    }, 1000);  // calling the function every 1 second
                 */
                 
                
                     function SubmitAway() { 
                  
                        document.getElementById('finished_form').submit();
                    }
                });
                
	</script>

	</main>
	</body>
	</html>