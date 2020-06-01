<?php
session_start();
//	if(session_status()!=PHP_SESSION_ACTIVE) session_start();  // put this in to try to get rid of a warning of headers already sent - didn't work
	require_once "pdo.php";
/* 
 if (!empty($_GET)) {
        $_SESSION['got'] = $_GET;
    } else {
        if (!empty($_SESSION['got'])) {
            $_GET = $_SESSION['got'];
            unset($_SESSION['got']);
        }
    }
   */
    if(isset($_GET['activity_id'])) {
        $activity_id = $_GET['activity_id'];
       
    } elseif (isset($_POST['activity_id'])){
        $activity_id = $_POST['activity_id'];
       
    }        
       else {
        $_SESSION['error'] = 'activity_id is not being read into the diplay error 30';
        header("Location: QRhomework.php");
        die();
	}


  $attempt_type = 1;  // this will determine how many chances you get
    
   // get the needed info from the activity table
   //  Get all of the required info from the Activity Table
    
    $sql = 'SELECT * FROM Activity WHERE activity_id = :activity_id';
     $stmt = $pdo->prepare($sql);
     $stmt->execute(array(':activity_id' => $activity_id));
     $activity_data = $stmt -> fetch();
        
     $problem_id = $activity_data['problem_id'];   
     $iid = $activity_data['iid'];   
     $pin = $activity_data['pin'];   
     $stu_name = $activity_data['stu_name'];   
     $currentclass_id = $activity_data['currentclass_id'];   
     $instr_last = $activity_data['instr_last'];   
     $university = $activity_data['university'];   
     $dex = $activity_data['dex'];  
     $alias_num = $activity_data['alias_num'];  
     $assign_id = $activity_data['assign_id'];
     $count_tot = $activity_data['count_tot']; 
     $progress = $activity_data['progress']; 
     
     $sql = 'SELECT name FROM CurrentClass WHERE currentclass_id = :currentclass_id';
     $stmt = $pdo->prepare($sql);
     $stmt->execute(array(':currentclass_id' => $currentclass_id));
     $class_data = $stmt -> fetch();
     $class_name = $class_data['name'];
     
      $sql = 'SELECT * FROM Assign WHERE assign_id = :assign_id AND prob_num = :problem_id';
     $stmt = $pdo->prepare($sql);
     $stmt->execute(array(':assign_id' => $assign_id,
     ':problem_id' => $problem_id));
     $assign_data = $stmt -> fetch();
     $assignment_num = $assign_data['assign_num'];
      $alias_num = $assign_data['alias_num'];    
         
         
			if ($progress == 4 ){  // first time through so initialize response and previous response to zero - student never saw this problem before - and is doing the basecase first
             
             $sql ='UPDATE `Activity` SET `progress` = :progress  WHERE activity_id = :activity_id';
                $stmt = $pdo -> prepare($sql);
                $stmt -> execute(array(
                        ':progress' => 5,
                        ':activity_id' => $activity_id
                     ));                  // progress of 5 loaded the checker for the problem
    
    
                for ($j=0;$j<=9;$j++){
					$wrongCount[$j]=0;		// accumulates how many times they missed a part
					//$_SESSION['wrongC'[$j]]=0; // temp  will try to do this from the resp table
					$changed[$j]=false;		// 1 if they changed their response zero otherwise
					$addCount[$j]=0;  // this is zero if they get it right and 1 if they get it wrong
                    $old_resp[$j] = 0;
               //     $_SESSION['oldresp'[$j]]=0;
               }	
                $resp = array('a'=>0,'b'=>0,'c'=>0,'d'=>0,'e'=>0,'f'=>0,'g'=>0,'h'=>0,'i'=>0,'j'=>0);
               $get_flag=1;
            } elseif ($progress == 6 ){  // worked on the specific problem now working BC if they have normal problem correct set BC response to solution for BC           
                 
                $j=0;
                foreach(range('a','j') as $v){
					// see which ones they got correct from the specific problem 
                    $corr_spec_num[$j] = $activity_data['correct_'.$v];
                    $wrongCount[$j]=0;		// accumulates how many times they missed a part
					$changed[$j]=false;		// 1 if they changed their response zero otherwise
					$addCount[$j]=0;  // this is zero if they get it right and 1 if they get it wrong
                    $old_resp[$j] = 0;
                $j++;
               }	
                $resp = array('a'=>0,'b'=>0,'c'=>0,'d'=>0,'e'=>0,'f'=>0,'g'=>0,'h'=>0,'i'=>0,'j'=>0);
               $get_flag=1;
            } elseif (!isset($_POST['activity_id'])){       // revisiting the problem

                // get the values from the Bc_resp table from before
                 
                  foreach(range('a','j') as $v){
                       $sql = 'SELECT `resp_value` FROM Bc_resp WHERE `activity_id` = :activity_id AND `part_name` = :part_name ORDER BY `resp_id` DESC LIMIT 1';
                             $stmt = $pdo->prepare($sql);
                              $stmt ->execute(array(
                            ':activity_id' => $activity_id,
                            ':part_name' => $v
                        ));
                        $resp_data = $stmt -> fetch();
                        $resp[$v] = $resp_data['resp_value'];   
                       // echo('resp'.$v.' = '.$resp[$v]);
                  }
                 $get_flag=1; 
               
            } else { 
                $get_flag=0;  // checking - coming in on a normal post
            }                
			
			$score = 0;
			$PScore = 0;  // percent score
			$partsFlag = array();
		
			//$resp = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
			$corr = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
     		$corr_num = array('a'=>0,'b'=>0,'c'=>0,'d'=>0,'e'=>0,'f'=>0,'g'=>0,'h'=>0,'i'=>0,'j'=>0);

			$unit = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
			$tol=array('a'=>0.02,'b'=>0.02,'c'=>0.02,'d'=>0.02,'e'=>0.02,'f'=>0.02,'g'=>0.02,'h'=>0.02,'i'=>0.02,'j'=>0.02);
			$ansFormat=array('ans_a' =>"",'ans_b' =>"",	'ans_c' =>"",'ans_d' =>"",'ans_e' =>"",'ans_f' =>"",	'ans_g' =>"",'ans_h' =>"",'ans_i' =>"",'ans_j'=>"");
			
			
			$hintLimit = 3;
			$dispBase = 1;
			
			
			$tol_key=array_keys($tol);
			$resp_key=array_keys($tol);
			$corr_key=array_keys($corr);
			$ansFormat_key=array_keys($ansFormat);
			
			$time_sleep1 = 10;  // time delay in seconds
			$time_sleep1_trip = 3;  // number of trials it talkes to trip the give them the answers
			$time_sleep2 = 10;  // additional time if hit the next limit
			$time_sleep2_trip = 30;	
			
			// see if the problem has been suspended	
				
			$stmt = $pdo->prepare("SELECT * FROM Problem where problem_id = :problem_id");
			$stmt->execute(array(":problem_id" => $problem_id));
			$row = $stmt -> fetch();
			if ( $row === false ) {
				$_SESSION['error'] = 'Bad value for problem_id';
				header( 'Location: QRExam.php' ) ;
				return;
			}	
			$probData=$row;	
			
			$probStatus = $probData['status'];
			if ($probStatus =='suspended'){
				$_SESSION['error'] = 'problem has been suspended, check back later';
				header( 'Location: QRExam.php' ) ;
				return;	
			}
	
			// get the tolerances and if the part has any hintfile	
				// initialize some arrays
            foreach(range('a','j') as $v){
                $tol[$v] = $probData['tol_'.$v]*0.001;	
             }
		
			
			
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
			$stmt->execute(array(":problem_id" => $problem_id, ":dex" => 1));
			$row = $stmt -> fetch();
			if ( $row === false ) {
				$_SESSION['error'] = 'Bad value for problem_id';
				header( 'Location: QRExam.php' ) ;
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
          
         //   $response = explode(",",$response);
         //  print_r('response array 1 =  '.$response[0]);
          $oldresp_flag = 0;
          for ($j=0; $j<=9; $j++) {
             if($corr_spec_num[$j]==1){ 
                    $resp[$resp_key[$j]]=$soln[$j];  
             }
                   $oldresp_flag = 1;  
               //    echo ($soln[$j]);
               //       echo ('  resp is: '. $resp[$resp_key[$j]]);
                
           }
        
 
    
     if( $get_flag ==0){ // if we are comming in from this file on a post
    // get the old repsonses from the response table check to see which ones have changed and 
      $i =0;
      $changed_flag = false;
      foreach(range('a','j') as $v){
          if( $partsFlag[$i]){ 
                $sql = 'SELECT `resp_value` FROM Bc_resp WHERE `activity_id` = :activity_id AND `part_name` = :part_name ORDER BY `resp_id` DESC LIMIT 1';
                 $stmt = $pdo->prepare($sql);
                  $stmt ->execute(array(
                ':activity_id' => $activity_id,
                ':part_name' => $v
            ));
            $resp_data = $stmt -> fetch();
            $old_resp[$i] = $resp_data['resp_value'];
            $resp[$v]=(float)$_POST[$v]+0.0;
            // now get the counts for all of the previous tries from the table
           $sql = 'SELECT COUNT(`resp_value`) FROM `Bc_resp` WHERE `activity_id` = :activity_id AND `part_name` = :part_name';
                 $stmt = $pdo->prepare($sql);
                  $stmt ->execute(array(
                ':activity_id' => $activity_id,
                ':part_name' => $v
            ));
             $count_data = $stmt -> fetchColumn();
             $wrongCount[$i] = $count_data;
           
            
        
            
             // $_SESSION['old_resp'[$i]] = $resp[$v];  // reset the old resp so that we have 
            if($resp[$v]==$old_resp[$i]){
                $changed[$i]= false;
            } else { 
                $changed[$i]=true;
                $changed_flag = true;
                $sql = 'INSERT INTO Bc_resp (activity_id, resp_value,part_name) VALUES (:activity_id, :resp_value, :part_name)';
                $stmt = $pdo->prepare($sql);
                $stmt ->execute(array(
                    ':activity_id' => $activity_id,
                    ':resp_value' => $resp[$v],
                    ':part_name' => $v
                 ));
            }
        $i++;  
        }
      }
      
      if ($changed_flag){
            $count_tot++;
      }
    
    
   
		
	//}	 
		for ($j=0; $j<=9; $j++) {
			if($partsFlag[$j] ) {
					//If ($soln[$j]>((1-$tol[$tol_key[$j]])*$resp[$resp_key[$j]]) and ($soln[$j]<((1+$tol[$tol_key[$j]]))*($resp[$resp_key[$j]]))) //if the correct value is within the response plus or minus the tolerance
								
                if($soln[$j]==0){  // take care of the zero solution case
                    $sol=1;
                } else {
                    $sol=$soln[$j];
                }	
                
                if(	abs(($soln[$j]-(float)$resp[$resp_key[$j]])/$sol)<= $tol[$tol_key[$j]]) {
                            
                            
                                    $corr_num[$corr_key[$j]]=1;
                                    $corr[$corr_key[$j]]='Correct';
                                    $score=$score+1;
 //                                   $_SESSION['$wrongC'[$j]] = 0;
 //                                   $wrongCount[$j]=0;
                                            
                            }
                else  // got it wrong or did not attempt
                {
                    
  /*                        
                    if(!(isset($_SESSION['wrongC'[$j]])))  // needs initialized
                    {
                        
                        $_SESSION['$wrongC'[$j]] = 0;
                        $wrongCount[$j]=0;
                        //echo 'im here';
                        //echo $_SESSION['wrongC'[$j]];
                    
                        
                   }
   */
                    if ($resp[$resp_key[$j]]==0)  // did not attempt it
                    {
                        
   //                     $wrongCount[$j] = ($_SESSION['wrongC'[$j]]);
  //                      $_SESSION['wrongC'[$j]] = $wrongCount[$j];
                        $corr_num[$corr_key[$j]]=0;
                        $corr[$corr_key[$j]]='';
                    //	echo ($wrongCount[$j]);
                    }
                    else  // response is equal to zero so probably did not answer (better to use POST value I suppose - fix later
                    {
                        $wrongCount[$j] = $wrongCount[$j]+1;
 //                       $_SESSION['wrongC'[$j]] = $wrongCount[$j];
                            $corr_num[$corr_key[$j]]=0;
                            $corr[$corr_key[$j]]='Not Correct';
                        //	echo ($wrongCount[$j]);	
                    }
                }		
			}
		}
     
     
     
     
    /*  
		
		$PScore=$score/$probParts*100; 
    
     $sql ='UPDATE `Activity` SET `score` = :score, `count_tot` = :count_tot WHERE activity_id = :activity_id';
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute(array(
                ':score' => $score,
                ':count_tot' => $count_tot,
                ':activity_id' => $activity_id
                 ));
    
    
		$_SESSION['points']=$score; // this will cause problems if running multiple browser windows on the same machine - testing on localhost
		$corr_num_st = implode(",",$corr_num);
         */
        
     /*  
           $stmt = $pdo->prepare("UPDATE `Examactivity` SET ".$trynum_pblm." = :trynum_pblm,".$response_key." = :response_key WHERE examactivity_id = :examactivity_id ");
			$stmt->execute(array(
            ":examactivity_id" => $examactivity_id,
            ":trynum_pblm" => $count,
             ":response_key" => $corr_num_st, 
            ));
	
     */
    
    
    // time delay on total tries for the problem - try this in the JS
    
            
    }
  
		
	?>
	



	<!DOCTYPE html>
	<html lang = "en">
	<head>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<meta Charset = "utf-8">
	<title>QR_BC_Check</title>
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
	<h3>Base-Case Checker</h3>
	</header>
	<main>
	<h4> Name: <?php echo($stu_name);?> &nbsp; &nbsp; Assignment Number: <?php echo($assignment_num);?>&nbsp; &nbsp;  Problem: <?php echo($alias_num);?> &nbsp; &nbsp;   Max Attempts: <?php if ($attempt_type==1){echo('infinite');}else{echo($num_attempts);} ?>  </h4>

	<font size = "1"> Problem Number: <?php echo ($problem_id) ?> -  <?php echo ($dex) ?> </font>
    

	<form autocomplete="off" id = "check_form" method="POST" >
	<!--<p><font color=#003399>Index: </font><input type="text" name="dex_num" size=3 value="<?php echo (htmlentities($_SESSION['index']))?>"  ></p> -->

	<?php
    if($attempt_type ==1 || ($attempt_type ==2 && $count_tot <= $num_attempts)){
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
    }


	
	?>
Score:  <?php echo (round($PScore)) ?>% &nbsp;&nbsp;&nbsp; Count: <?php echo ($count_tot) ?>  <span id ="t_delay_message"></span>
	<p><input type = "submit" id = "check_submit" name = "check" value="Check" size="10" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp <b> <font size="4" color="Navy"></font></b></p>
             <input type="hidden" name="activity_id" value="<?php echo ($activity_id)?>" >
              <input type="hidden" id = "prob_parts" value="<?php echo ($probParts)?>" >
               <input type="hidden" id = "count_tot" value="<?php echo ($count_tot)?>" >
          
	</form>

	
    
    
   <p> <span id ="t_delay_limits"> time delay - 5s at count > <?php echo (3*$probParts) ?>, 30s at count > <?php echo (5*$probParts) ?> </span> </p>
    
    
     <!--
    
	<form action="StopExam.php" method="POST" id = "the_form">
		    <input type="hidden" name="name"  value="<?php echo ($name)?>" >
            <input type="hidden" name="pin" value="<?php echo ($pin)?>" >
            <input type="hidden" name="team_id"  value="<?php echo ($team_id)?>" >
            <input type="hidden" name="problem_id"  value="<?php echo ($problem_id)?>" >
            <input type="hidden" name="dex" value="<?php echo ($dex)?>" >
            <input type="hidden" name="exam_num" value="<?php echo ($exam_num)?>" >
            <input type="hidden" id = "examtime_id" name="examtime_id" value="<?php echo ($examtime_id)?>" >
            <input type="hidden" name="examactivity_id" value="<?php echo ($examactivity_id)?>" >
            <input type="hidden" name="globephase" id = "globephase" >
            <input type="hidden" name="iid" value="<?php echo ($iid)?>" >
           
        <p><input type="hidden" id = "pblm_score" name="pblm_score" size=3 value="<?php echo($PScore)?>"  
    <hr>
	<p><b><font Color="red">Finished:</font></b></p>
	 <input type="hidden" name="score" value=<?php echo ($score) ?> />
	   <?php $_SESSION['score'] = round($PScore);  ?>
	 <b><input type="submit" value="Finished" name="score" style = "width: 30%; background-color:yellow "></b>
	 <p><br> </p>
	 <hr>
	</form>
 -->

	<script>
   
    
		$(document).ready( function () {
                
                     var request;
                function fetchPhase() {
                    request = $.ajax({
                        type: "POST",
                        url: "fetchGPhase.php",
                        data: "examtime_id="+examtime_id,
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
                           if(phase != 1){  // submit away work time has eneded this is going to stop game and not back to the router
                               $("#phase").attr('value', phase);
                               SubmitAway(); 
                            }
                        }
                    });
                }
                
              /*   
                setInterval(function() {
                    if (request) request.abort();
                    fetchPhase();
                }, 10000);

 */
                // Delay if they take to many total attempts
                        
                            var count_tot = $("#count_tot").val();
                            var prob_parts = $("#prob_parts").val();
                            console.log ("count_tot = "+count_tot);
                             console.log ("prob_parts = "+prob_parts);
                          
                            

                            var check_form = document.getElementById("check_form"), check_submit = document.getElementById("check_submit");
                            check_form.onsubmit = function() {
                                return false;
                            }

                            check_submit.onclick = function() {
                            
                                 if (count_tot > 5*prob_parts){
                                        $("#t_delay_message").text(" 30s time delay limit exceeded");
                                      setTimeout(function() {
                                              check_form.submit();
                                         }, 30000);
                                           return false;
                                  } else if (count_tot > 3*prob_parts){
                                      $("#t_delay_message").text(" 5s time delay limit exceeded");
                                      setTimeout(function() {
                                              check_form.submit();
                                         }, 5000);
                                           return false; 
                                  } else {
                                      
                                      check_form.submit();
                                      return false; 
                                  }
                            }       
                                 
                                 
                                 
                                 
                                 
                               

/* 
                          if (count_tot > 3*prob_parts){
                                
                               var delayInMilliseconds = 1000; //1 second

                            setTimeout(function() {
                              //your code to be executed after 1 second
                            }, delayInMilliseconds); 
                                
                                
                            }
                         */
                        
                  



                
                     function SubmitAway() { 
                        window.close();
                       // document.getElementById('the_form').submit();
                    }
                });
         

         
	</script>

	</main>
	</body>
	</html>