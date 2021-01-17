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
    $switch_to_bc = 0;  
    $diff_time_min = 0;  // default value for the difference in time between

  $attempt_type = 1;  // this will determine how many chances you get
    
   // get the needed info from the activity table
   //  Get all of the required info from the Activity Table
    
    $sql = 'SELECT * FROM Activity WHERE activity_id = :activity_id';
     $stmt = $pdo->prepare($sql);
     $stmt->execute(array(':activity_id' => $activity_id));
     $activity_data = $stmt -> fetch();
     
      $switch_to_bc = $activity_data['switch_to_bc'];  
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
     // $count_tot = $activity_data['count_tot']; 
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
         
    //--------------------------------------------------------------------------------------------------------------------- also in QR_BC_Checker2 and QRChecker2
     $sql = 'SELECT * FROM Assigntime WHERE assign_num = :assign_num AND currentclass_id = :currentclass_id'; // may not want everything here
     $stmt = $pdo->prepare($sql);
     $stmt->execute(array(':assign_num' => $assignment_num,
                          ':currentclass_id' => $currentclass_id));
     $assigntime_data = $stmt -> fetch();
     
     $work_flow =  $assigntime_data['work_flow'];
     $p_bc_n = $assigntime_data['p_bc_n'];  // when it converts from problem to basecase
     $p_bc_t = $assigntime_data['p_bc_t'];
     
     $perc_of_assign = $assigntime_data['perc_'.$alias_num];
     $due_date = new DateTime($assigntime_data['due_date']);
     $due_date = $due_date->format(' D, M d,  g:i A');
     $due_date_int = strtotime($due_date);
     $window_closes = new DateTime($assigntime_data['window_closes']);
     $window_closes = $window_closes->format(' D, M d,  g:i A');
     $window_closes_int = strtotime($window_closes);
     $late_points = $assigntime_data['late_points'];
     $credit = $assigntime_data['credit'];
     $fixed_percent_decline = $assigntime_data['fixed_percent_decline'];
      $now = new DateTime($activity_data['last_updated_at']);
      $now = $now->format(' D, M d,  g:i A');

    $now_int = strtotime($now);
    $perc_late_p_prob = $perc_late_p_part = $perc_late_p_assign = 0; 
    $late_penalty = 0;
    $ec_daysb4due_elgible = $assigntime_data['ec_daysb4due_elgible'];
    $due_date_ec_int = $due_date_int - $ec_daysb4due_elgible*60*60*24;
    $due_date_ec = date(' D, M d,  g:i A', $due_date_ec_int);
     
    if ($now_int > $due_date_int ) {  // figure out the late penalty
         if($late_points == 'linear'){
             $late_penalty = round(100*($now_int - $due_date_int)/($window_closes_int - $due_date_int));
          //   $late_penalty = 100;

         }
          if($late_points == 'fixedpercent'){
             // $late_penalty = 30;
             $days_past_due = ceil(($now_int - $due_date_int)/(60*60*24)); // ceil is php roundup
             $late_penalty = $days_past_due*$fixed_percent_decline;  
         }
         if ($credit =='latetoparts'){
             $perc_late_p_part = $late_penalty;
         } elseif ($credit =='latetoproblems'){
             $perc_late_p_prob = $late_penalty;
         }else {  // late penalty is latetoall and applies to the entire assignment
            $perc_late_p_assign = $late_penalty;
         }
    }
//end section-------------------------------------------------------------------------------------------
          if ($progress == 9){// submitted the work files go back to the frontpage after ressetting the progress variable
              
             
             
             $sql ='UPDATE `Activity` SET `progress` = :progress  WHERE activity_id = :activity_id';
                $stmt = $pdo -> prepare($sql);
                $stmt -> execute(array(
                        ':progress' => 6,
                        ':activity_id' => $activity_id
                     )); 

                header('Location: stu_frontpage.php?activity_id='.$activity_id);
               die();                     
          }
          
        //  echo ('  progress '.$progress);
          
			if ($progress == 4 || $progress == 5 ){  // first time through so initialize response and previous response to zero - student never saw this problem before - 
             
             $sql ='UPDATE `Activity` SET `progress` = :progress  WHERE activity_id = :activity_id';
                $stmt = $pdo -> prepare($sql);
                $stmt -> execute(array(
                        ':progress' => 6,
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
            } elseif (!isset($_POST['activity_id'])){       // revisiting the problem

                // get the values from the Resp table from before
                 
                  foreach(range('a','j') as $v){
                       $sql = 'SELECT `resp_value` FROM Resp WHERE `activity_id` = :activity_id AND `part_name` = :part_name ORDER BY `resp_id` DESC LIMIT 1';
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
			
           // echo('  $get_flag:  '.$get_flag);
            
			$score = 0;
            $num_score_possible = 0;
			$PScore = 0;  // percent score without a post
			$partsFlag = array();
		
			//$resp = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
			$corr = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
     		$corr_num = array('a'=>0,'b'=>0,'c'=>0,'d'=>0,'e'=>0,'f'=>0,'g'=>0,'h'=>0,'i'=>0,'j'=>0);

			$unit = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
			$tol=array('a'=>0.02,'b'=>0.02,'c'=>0.02,'d'=>0.02,'e'=>0.02,'f'=>0.02,'g'=>0.02,'h'=>0.02,'i'=>0.02,'j'=>0.02);
			$tol_type=array('a'=>0,'b'=>0,'c'=>0,'d'=>0,'e'=>0,'f'=>0,'g'=>0,'h'=>0,'i'=>0,'j'=>0);
			$ansFormat=array('ans_a' =>"",'ans_b' =>"",	'ans_c' =>"",'ans_d' =>"",'ans_e' =>"",'ans_f' =>"",	'ans_g' =>"",'ans_h' =>"",'ans_i' =>"",'ans_j'=>"");
			
			
			$hintLimit = 3;
			$dispBase = 1;
			
			
            $tol_key=array_keys($tol);
            $tol_type_key=array_keys($tol_type);
			$resp_key=array_keys($tol);
			$corr_key=array_keys($corr);
			$ansFormat_key=array_keys($ansFormat);
			
           // Will take care of this with JS so the delay is on their machine
			$time_sleep1 = 10;  // time delay in seconds
			$time_sleep1_trip = 5;  // number of trials it talkes to trip the time delay
			$time_sleep2 = 10;  // additional time if hit the next limit
			$time_sleep2_trip = 30;	
			$trip_1_flag = 0;
            $trip_2_flag =0;
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
                $tol_type[$v] = $probData['tol_'.$v.'_type'];	
                 
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
			$stmt->execute(array(":problem_id" => $problem_id, ":dex" => $dex));
			$row = $stmt -> fetch();
			if ( $row === false ) {
				$_SESSION['error'] = 'Bad value for problem_id';
				header( 'Location: QRhomework.php' ) ;
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
              
//--------------------------------  $resp[$resp_key[$j]]=$soln[$j];  // some reason setting resp = solution
                   $oldresp_flag = 1;  
               //    echo ($soln[$j]);
               //       echo ('  resp is: '. $resp[$resp_key[$j]]);
                
           }
        
     // keep track of the number of tries the student makes
	// get the count from the activity table __________________________________________can get this from the resp table eventually _____________________________-
 /*  
   if($count_tot == 0){   // first time no tries initialise count and wrong count
		for ($j=0;$j<=9;$j++){
					$wrongCount[$j]=0;
			
                  
				}
	}
     */
     if( $get_flag ==0){ // if we are comming in from this file on a post
    // get the old repsonses from the response table check to see which ones have changed and 
      $i =0;
      $changed_flag = false;
      $count_tot = 0;
      $switch_to_bc = 0;
    //  var_dump($partsFlag);
    
      foreach(range('a','j') as $v){
          
        
          if( $partsFlag[$i]){ 
         
          
                $sql = 'SELECT `resp_value` FROM Resp WHERE `activity_id` = :activity_id AND `part_name` = :part_name ORDER BY `resp_id` DESC LIMIT 1';
                 $stmt = $pdo->prepare($sql);
                  $stmt ->execute(array(
                ':activity_id' => $activity_id,
                ':part_name' => $v
            ));
            $resp_data = $stmt -> fetch();
            if ($resp_data!=false){
            $old_resp[$i] = $resp_data['resp_value'];
            $resp[$v]=(float)$_POST[$v]+0.0;
            }
 //           echo('  resp[$v]:  '.$resp[$v]);
 //           echo('  partsFlag[$i]:  '.$partsFlag[$i]);
            // now get the counts for all of the previous tries from the table
      

      $sql = 'SELECT COUNT(`resp_value`) FROM `Resp` WHERE `activity_id` = :activity_id AND `part_name` = :part_name';
                 $stmt = $pdo->prepare($sql);
                  $stmt ->execute(array(
                ':activity_id' => $activity_id,
                ':part_name' => $v
            ));
             $count_data = $stmt -> fetchColumn();
             $wrongCount[$i] = $count_data;
            $count_tot = $count_tot + $count_data;
          

          // put the wrong count values in activity table for easy access by other files
            $sql = 'UPDATE `Activity` SET wcount_'.$v.'= :wcount_x WHERE activity_id = :activity_id';
            $stmt = $pdo->prepare($sql);
             $stmt ->execute(array(
                    ':activity_id' => $activity_id,
                    ':wcount_x' => $count_data,
                 ));
            
             // $_SESSION['old_resp'[$i]] = $resp[$v];  // reset the old resp so that we have 
            if($resp[$v]==$old_resp[$i]){
                $changed[$i]= false;
            } else { 
          
                
            


               $changed[$i]=true;
                $changed_flag = true;
                $sql = 'INSERT INTO Resp (activity_id, resp_value,part_name) VALUES (:activity_id, :resp_value, :part_name)';
                $stmt = $pdo->prepare($sql);
                $stmt ->execute(array(
                    ':activity_id' => $activity_id,
                    ':resp_value' => $resp[$v],
                    ':part_name' => $v
                 ));
                
                $sql = 'SELECT UNIX_TIMESTAMP(`created_at`) AS created_at FROM Resp WHERE activity_id = :activity_id AND part_name = :part_name ORDER BY resp_id DESC LIMIT 1';
                $stmt = $pdo->prepare($sql);
                $stmt ->execute(array(
                    ':activity_id' => $activity_id,
                    ':part_name' => $v,
                 ));
                 $original_dates = $stmt -> fetch();                
                 $last_date = $original_dates['created_at'];
               
                // get the time they have been working on this part from the Resp table
                $sql = 'SELECT UNIX_TIMESTAMP(`created_at`) AS created_at FROM Resp WHERE activity_id = :activity_id AND part_name = :part_name ORDER BY resp_id ASC LIMIT 1';
                $stmt = $pdo->prepare($sql);
                $stmt ->execute(array(
                    ':activity_id' => $activity_id,
                    ':part_name' => $v,
                 ));
                 $original_dates = $stmt -> fetch();                
                 $first_date = $original_dates['created_at'];
                 
                if (is_numeric($last_date) && is_numeric($first_date))
                {$diff_time_min = round(($last_date - $first_date)/60);} else {$diff_time_min=0;}
                 
                   if($work_flow == 'bc_if' && $count_data >= $p_bc_n && $diff_time_min > $p_bc_t && $activity_data["bc_correct_".$v] != 1)
                   {$go_to_bc[$i] = 1; $switch_to_bc = 1;} else {$go_to_bc[$i] = 0;}   
            }
         
        }
         $i++;
      }
     
        
    /*  
      if ($changed_flag){
            $count_tot++;
      }
    */
		
    //}	
     
		for ($j=0; $j<=9; $j++) {
			if($partsFlag[$j] ) {
								
                if($soln[$j]==0){  // take care of the zero solution case
                    $sol=1;
                } else {
                    $sol=$soln[$j];
                }	
                
                if($tol_type[$tol_type_key[$j]]==0 &&	(abs(($soln[$j]-(float)$resp[$resp_key[$j]])/$sol)<= $tol[$tol_key[$j]])) {   // first condition makes sure we have a relative error
                    $corr_num[$corr_key[$j]]=1;
                    $corr[$corr_key[$j]]='Correct';
                    $score=$score+1;
                                          
               } elseif($tol_type[$tol_type_key[$j]]==1 && (abs(($soln[$j]-(float)$resp[$resp_key[$j]]))<= $tol[$tol_key[$j]]) ){  // looking for a absolute error
                    $corr_num[$corr_key[$j]]=1;
                    $corr[$corr_key[$j]]='Correct';
                    $score=$score+1;
               }
                else  // got it wrong or did not attempt
                {
 
                    if ($resp[$resp_key[$j]]==0)  // did not attempt it
                    {
                        $corr_num[$corr_key[$j]]=0;
                        $corr[$corr_key[$j]]='';
                   
                    }
                    else  // response is equal to zero so probably did not answer (better to use POST value I suppose - fix later
                    {
                        $wrongCount[$j] = $wrongCount[$j]+1;
                            $corr_num[$corr_key[$j]]=0;
                            $corr[$corr_key[$j]]='Not Correct';
                    }
                }		
			}
		}
     
		
		//$PScore=$score/$probParts*100; 
        $num_score_possible = 0;
        $PScore=0; 
         foreach(range('a','j') as $x){ 
         $PScore = $PScore + ($corr_num[$x]*$assigntime_data['perc_'.$x.'_'.$alias_num]);
          $num_score_possible = $num_score_possible + $assigntime_data['perc_'.$x.'_'.$alias_num];
         }
    
     $sql ='UPDATE `Activity` SET `score` = :score, `count_tot` = :count_tot, correct_a = :correct_a,correct_b = :correct_b,correct_c = :correct_c,correct_d = :correct_d,correct_e = :correct_e,correct_f = :correct_f,correct_g = :correct_g,correct_h = :correct_h,correct_i = :correct_i,correct_j = :correct_j, switch_to_bc = :switch_to_bc
                              WHERE activity_id = :activity_id';
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute(array(
                ':score' => $score,
                ':count_tot' => $count_tot,
                ':activity_id' => $activity_id,
                ':correct_a' => $corr_num['a'],
                ':correct_b' => $corr_num['b'],
                ':correct_c' => $corr_num['c'],
                ':correct_d' => $corr_num['d'],
                ':correct_e' => $corr_num['e'],
                ':correct_f' => $corr_num['f'],
                ':correct_g' => $corr_num['g'],
                ':correct_h' => $corr_num['h'],
                ':correct_i' => $corr_num['i'],
                ':correct_j' => $corr_num['j'],
                ':switch_to_bc' => $switch_to_bc,
                 ));
    
    
		$_SESSION['points']=$score; // this will cause problems if running multiple browser windows on the same machine - testing on localhost
		$corr_num_st = implode(",",$corr_num);
        
        
    
    
    
    // time delay on total tries for the problem - try this in the JS
    
            
    }
  
		
	?>
	



	<!DOCTYPE html>
	<html lang = "en">
	<head>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<meta Charset = "utf-8">
	<title>QRChecker</title>
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
	<h3>Quick Response Checker</h3>
	</header>
	<main>
	<h4> Name: <?php echo($stu_name);?> &nbsp; &nbsp; Assignment Number: <?php echo($assignment_num);?>&nbsp; &nbsp;  Problem: <?php echo($alias_num);?> &nbsp; &nbsp;   Max Attempts: <?php if ($attempt_type==1){echo('infinite');}else{echo($num_attempts);} ?> &nbsp; &nbsp; Time delay starts at count = <?php echo ($time_sleep1_trip); ?> </h4>  

	<font size = "1"> Problem Number: <?php echo ($problem_id) ?> -  <?php echo ($dex) ?> </font>
     <!-- <div id = "test"> test <?php print_r ($wrongCount);?></div>
     
     diff_time_min: <?php echo($diff_time_min);?>
     switch_to_bc: <?php echo($switch_to_bc);?>
 -->
	<form autocomplete="off" id = "check_form" method="POST" >
	<!-- <p><font color=#003399>Index: </font><input type="text" name="dex_num" size=3 value="<?php echo (htmlentities($_SESSION['index']))?>"  ></p> -->

	<?php
    
 /*     
   echo(' resp["a"]: '.$resp["a"]);
    echo(' resp["b"]: '.$resp["b"]);
    echo(' get_flag: '.$get_flag);
    echo(' progress: '.$progress);
        */
    
    
    if($attempt_type ==1 || ($attempt_type ==2 && $count_tot <= $num_attempts)){  // $attempt_type determines how many total chances you get for $attempt_type =  1 you get infinite total chances for $attempt_type =  2 there is a limit on the total number of guesses
	if ($partsFlag[0]){ ?> 
	<p> a)(<?php echo $assigntime_data['perc_a_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="a" size = 10% value="<?php echo (htmlentities($resp['a']))?>" > <?php echo($unit[0]) ?> &nbsp - <b><?php echo ($corr['a']) ?> </b> count <?php echo(@$wrongCount[0].' '); ?> 
	 
    <?php if ( $corr['a']=="Correct" ){echo '- Computed value is: '.$soln[0];} ?>  
	<?php if ( @$wrongCount[0]>$hintLimit && $corr['a']=="Not Correct" && $hintaPath != "uploads/default_hints.html" ){echo '<a href="'.$hintaPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if ( @$changed[0] && @$wrongCount[0]>$time_sleep1_trip && @$wrongCount[0]< $time_sleep2_trip && $corr['a']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if ( @$changed[0] && @$wrongCount[0]>=$time_sleep2_trip && $corr['a']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	  </p>
	<?php } 


	if ($partsFlag[1]){ ?> 
	<p> b)(<?php echo $assigntime_data['perc_b_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="b" size = 10% value="<?php echo (htmlentities($resp['b']))?>" > <?php echo($unit[1]) ?> &nbsp - <b><?php echo ($corr['b']) ?> </b> count <?php echo(@$wrongCount[1].' '); ?> 
	<?php if ( $corr['b']=="Correct" ){echo '- Computed value is: '.$soln[1];} ?>  
	<?php if ( @$wrongCount[1]>$hintLimit && $corr['b']=="Not Correct" && $hintbPath != "uploads/default_hints.html" ){echo '<a href="'.$hintbPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if ( @$changed[1] && @$wrongCount[1]>$time_sleep1_trip && @$wrongCount[1]< $time_sleep2_trip && $corr['b']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if ( @$changed[1] && @$wrongCount[1]>=$time_sleep2_trip && $corr['b']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 
  
	if ($partsFlag[2]){ ?> 
	<p> c)(<?php echo $assigntime_data['perc_c_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="c" size = 10% value="<?php echo (htmlentities($resp['c']))?>" > <?php echo($unit[2]) ?> &nbsp - <b><?php echo ($corr['c']) ?> </b> count <?php echo(@$wrongCount[2].' '); ?>
	<?php if ( $corr['c']=="Correct" ){echo '- Computed value is: '.$soln[2];} ?>  
	<?php if ( @$wrongCount[2]>$hintLimit && $corr['c']=="Not Correct"&& $hintcPath != "uploads/default_hints.html" ){echo '<a href="'.$hintcPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if ( @$changed[2] && @$wrongCount[2]>$time_sleep1_trip && @$wrongCount[2]< $time_sleep2_trip && $corr['c']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if ( @$changed[2] && @$wrongCount[2]>=$time_sleep2_trip && $corr['c']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[3]){ ?> 
	<p> d)(<?php echo $assigntime_data['perc_d_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="d" size = 10% value="<?php echo (htmlentities($resp['d']))?>" > <?php echo($unit[3]) ?> &nbsp - <b><?php echo ($corr['d']) ?> </b> count <?php echo(@$wrongCount[3].' '); ?>
	<?php if ( $corr['d']=="Correct" ){echo '- Computed value is: '.$soln[3];} ?>  
	<?php if ( @$wrongCount[3]>$hintLimit && $corr['d']=="Not Correct"&& $hintdPath != "uploads/default_hints.html" ){echo '<a href="'.$hintdPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if ( @$changed[3] && @$wrongCount[3]>$time_sleep1_trip && @$wrongCount[3]< $time_sleep2_trip && $corr['d']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if ( @$changed[3] && @$wrongCount[3]>=$time_sleep2_trip && $corr['d']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[4]){ ?> 
	<p> e)(<?php echo $assigntime_data['perc_e_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="e" size = 10% value="<?php echo (htmlentities($resp['e']))?>" > <?php echo($unit[4]) ?> &nbsp - <b><?php echo ($corr['e']) ?> </b> count <?php echo(@$wrongCount[4].' '); ?>
	<?php if ( $corr['e']=="Correct" ){echo '- Computed value is: '.$soln[4];} ?>  
	<?php if ( @$wrongCount[4]>$hintLimit && $corr['e']=="Not Correct"&& $hintePath != "uploads/default_hints.html" ){echo '<a href="'.$hintePath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if ( @$changed[4] && @$wrongCount[4]>$time_sleep1_trip && @$wrongCount[4]< $time_sleep1_trip && $corr['e']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if ( @$changed[4] && @$wrongCount[4]>=$time_sleep2_trip && $corr['e']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[5]){ ?> 
	<p> f)(<?php echo $assigntime_data['perc_f_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="f" size = 10% value="<?php echo (htmlentities($resp['f']))?>" > <?php echo($unit[5]) ?> &nbsp - <b><?php echo ($corr['f']) ?> </b> count <?php echo(@$wrongCount[5].' '); ?>
	<?php if ( $corr['f']=="Correct" ){echo '- Computed value is: '.$soln[5];} ?>  
	<?php if ( @$wrongCount[5]>$hintLimit && $corr['f']=="Not Correct"&& $hintfPath != "uploads/default_hints.html" ){echo '<a href="'.$hintfPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if ( @$changed[5] && @$wrongCount[5]>$time_sleep1_trip && @$wrongCount[5]< $time_sleep2_trip && $corr['f']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if ( @$changed[5] && @$wrongCount[5]>=$time_sleep2_trip && $corr['f']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[6]){ ?> 
	<p> g)(<?php echo $assigntime_data['perc_g_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="g" size = 10% value="<?php echo (htmlentities($resp['g']))?>" > <?php echo($unit[6]) ?> &nbsp - <b><?php echo ($corr['g']) ?> </b> count <?php echo(@$wrongCount[6].' '); ?>
	<?php if ( $corr['g']=="Correct" ){echo '- Computed value is: '.$soln[6];} ?>  
	<?php if ( @$wrongCount[6]>$hintLimit && $corr['g']=="Not Correct"&& $hintgPath != "uploads/default_hints.html" ){echo '<a href="'.$hintgPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if ( @$changed[6] && @$wrongCount[6]>$time_sleep1_trip && @$wrongCount[6]< $time_sleep2_trip && $corr['g']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if ( @$changed[6] && @$wrongCount[6]>=$time_sleep2_trip && $corr['g']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[7]){ ?> 
	<p> h)(<?php echo $assigntime_data['perc_h_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="h" size = 10% value="<?php echo (htmlentities($resp['h']))?>" > <?php echo($unit[7]) ?> &nbsp - <b><?php echo ($corr['h']) ?> </b> count <?php echo(@$wrongCount[7].' '); ?>
	<?php if ( $corr['h']=="Correct" ){echo '- Computed value is: '.$soln[7];} ?>  
	<?php if ( @$wrongCount[7]>$hintLimit && $corr['h']=="Not Correct"&& $hinthPath != "uploads/default_hints.html" ){echo '<a href="'.$hinthPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if ( @$changed[7] && @$wrongCount[7]>$time_sleep1_trip && @$wrongCount[7]< $time_sleep2_trip && $corr['h']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if ( @$changed[7] && @$wrongCount[7]>=$time_sleep2_trip && $corr['h']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[8]){ ?> 
	<p> i)(<?php echo $assigntime_data['perc_i_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="i" size = 10% value="<?php echo (htmlentities($resp['i']))?>" > <?php echo($unit[8]) ?> &nbsp - <b><?php echo ($corr['i']) ?> </b> count <?php echo(@$wrongCount[8].' '); ?>
	<?php if ( $corr['i']=="Correct" ){echo '- Computed value is: '.$soln[8];} ?>  
	<?php if ( @$wrongCount[8]>$hintLimit && $corr['i']=="Not Correct"&& $hintiPath != "uploads/default_hints.html" ){echo '<a href="'.$hintiPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if ( @$changed[8] && @$wrongCount[8]>$time_sleep1_trip && @$wrongCount[8]< $time_sleep2_trip && $corr['i']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if ( @$changed[8] && @$wrongCount[8]>=$time_sleep2_trip && $corr['i']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[9]){ ?> 
	<p> j)(<?php echo $assigntime_data['perc_j_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="j" size = 10% value="<?php echo (htmlentities($resp['j']))?>" > <?php echo($unit[9]) ?> &nbsp - <b><?php echo ($corr['j']) ?> </b> count <?php echo(@$wrongCount[9].' '); ?>
	<?php if ( @$wrongCount[9]>$hintLimit && $corr['j']=="Not Correct"&& $hintjPath != "uploads/default_hints.html" ){echo '<a href="'.$hintjPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if ( @$changed[9] && @$wrongCount[9]>$time_sleep1_trip && @$wrongCount[9]< $time_sleep2_trip && $corr['j']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if ( @$changed[9] && @$wrongCount[9]>=$time_sleep2_trip && $corr['j']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 
    }


	
	?>
Provisional Score on Problem:  <?php echo (round($PScore)) ?> %&nbsp; 
<?php if($perc_late_p_prob != 0){if ($PScore >= $perc_late_p_prob){$pscore_less = round($PScore - $perc_late_p_prob); echo (' Less Late Penalty of '.$perc_late_p_prob.'% = '.$pscore_less.'%');} else { echo (' Less Late Penalty of '.$perc_late_p_prob.' % more than points earned'); $pscore_less = 0;}} else {$pscore_less = $PScore; } ?> &nbsp; &nbsp; 
 <br> note - Score only includes quatitative parts of the problem.  These points awarded when work is uploaded. <br>
 
 Total Count:<span id = "total_count" > <?php echo (@$count_tot) ?> </span> <br>
 <!--
 Due Date: <?php echo (@$due_date) ?>  Due_Date_int:  <?php echo (@$due_date_int) ?><br>
 Now: <?php echo (@$now) ?>  Now_int:  <?php echo (@$now_int) ?> Duedate-nowInt: <?php echo ($due_date_int - $now_int) ?> <br>  <br>
 nowInt-window_closes_int <?php echo ($now_int-$window_closes_int) ?> <br>  <br>
 Late Penalty: <?php echo (@$late_penalty) ?>   <br>
 late_points: <?php echo (@$late_points) ?>   <br>
 fixed_percent_decline: <?php echo (@$fixed_percent_decline) ?>   <br>
  days_past_due: <?php echo (@$days_past_due) ?>   <br>
 
Due Date for Extra Credit: <?php echo (@$due_date_ec) ?>   <br>
--> 
<br> numerical score possible  <?php echo (round($num_score_possible)) ?> %&nbsp;  

<?php if ( $pscore_less==$num_score_possible && $pscore_less !=0 && $pscore_less !='' ){$perf_num_score_flag =1;} else {$perf_num_score_flag =0;} ?>

<?php if ( $pscore_less==$num_score_possible && $pscore_less !=0 && $pscore_less !='' && $now_int < $due_date_ec_int){$ec_elgible_flag =1;} else {$ec_elgible_flag =0;} ?>
         <span id ="t_delay_message"></span>
	<p><input type = "submit" id = "check_submit" name = "check" value="Check" size="10" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp <b> <font size="4" color="Navy"></font></b></p><br>
             <input type="hidden" name="activity_id" value="<?php echo ($activity_id)?>" >
             <input type="hidden" id = "prob_parts" value="<?php echo ($probParts)?>" >
             <input type="hidden" id = "count_tot" value="<?php echo ($count_tot)?>" >
             <input type="hidden" id = "switch_to_bc" value="<?php echo ($switch_to_bc)?>" >

     
	</form>
<form id = "finish_form" method="POST" action = "GetRating2.php">
	        

            <input type="hidden" id = "activity_id" name="activity_id" value="<?php echo ($activity_id)?>" >
            <input type="hidden" name="problem_id" value="<?php echo ($problem_id)?>" >
            <input type="hidden" id = "changed_flag" name="changed_flag" value="<?php echo ($changed_flag)?>" >
            <input type="hidden" id = "count_from_check" name="count" value="<?php echo ($count_tot)?>" >
            <input type="hidden" id = "PScore" name="PScore" value="<?php echo ($PScore)?>" >
             <input type="hidden" name="perc_late_p_prob" value="<?php echo ($perc_late_p_prob)?>" >
             <input type="hidden" name="num_score_possible" value="<?php echo ($num_score_possible)?>" >
             <input type="hidden" name="pscore_less" value="<?php echo ($pscore_less)?>" >

             <input type="hidden" id = "ec_elgible_flag" name="ec_elgible_flag" value="<?php echo $ec_elgible_flag?>" >
             <input type="hidden" id = "perf_num_score_flag" name="perf_num_score_flag" value="<?php echo $perf_num_score_flag?>" >



          <p><input type = "submit"  id = "finish_submit" name = "finish" value="Finish and Proceed to Survey" size="10" style = "width: 30%; background-color: red; color: white"/> &nbsp &nbsp <b> <font size="4" color="Navy"></font></b></p>

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
        
        var activity_id = $('#activity_id').val();
          
/*   I played with this to sneek values from the iframe to QRdisplayPblm problem and this worked but may as well use AJAX and get it from the activity table

            var count_from_check = 0;
                var ec_elgible_flag = 0;
                var changed_flag =0;
                 count_from_check = $('#count_from_check').val();
               changed_flag = $('#changed_flag').val();
               ec_elgible_flag = $('#ec_elgible_flag').val();
               localStorage.setItem('count_from_check', count_from_check);
               localStorage.setItem('ec_elgible_flag', ec_elgible_flag);
               localStorage.setItem('changed_flag', changed_flag);
                */  
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
                          
                            
/* time delay stuff on JS works but should take care of it for each part not on the total count
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
                                  */
        /*      unpredictable results - changed to QR_checker but left the problem up                   
          if($('#switch_to_bc').val() ==1){
            window.location.replace("QR_BC_Checker2.php?activity_id="+activity_id);

          }              
                                 
   */                               
                               

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