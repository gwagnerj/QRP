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
  $corr_spec_num = array();  // initialize var so we dont get undefined var error 
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
    // $count_tot = $activity_data['count_tot']; 
     $progress = $activity_data['progress']; 
       
        if (isset($_SESSION['count_tot'])){
            $count_tot = $_SESSION['count_tot']; 
        } else {
           $count_tot = 0; 
        }
     
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
     $sequential = $assign_data['sequential'];  

       $sql = 'SELECT * FROM Assigntime WHERE assign_num = :assign_num AND currentclass_id = :currentclass_id ORDER BY assigntime_id DESC' ; // may not want everything here
     $stmt = $pdo->prepare($sql);
     $stmt->execute(array(':assign_num' => $assignment_num,
                          ':currentclass_id' => $currentclass_id));
     $assigntime_data = $stmt -> fetch();
     
     $work_flow =  $assigntime_data['work_flow'];
     $bc_ans_t = $assigntime_data['bc_ans_t'];  // how long before we can show the answers
      $bc_ans_n = $assigntime_data['bc_ans_n'];
     $help_n_stu = $assigntime_data['help_n_stu'];  // how many tries before you can get help from fellow students
     $help_t_stu = $assigntime_data['help_t_stu'];  
     $help_n_ta = $assigntime_data['help_n_ta'];  
     $help_t_ta = $assigntime_data['help_t_ta'];  
     $help_n_instruct = $assigntime_data['help_n_instruct'];  
     $help_t_instruct = $assigntime_data['help_t_instruct'];  
     $help_n_hint = $assigntime_data['help_n_hint'];  
     $help_t_hint = $assigntime_data['help_t_hint'];  
     
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

     
     
     
     
     /*  
         $sql = 'SELECT * FROM Assigntime WHERE assign_num = :assign_num AND currentclass_id = :currentclass_id'; // may not want everything here
     $stmt = $pdo->prepare($sql);
     $stmt->execute(array(':assign_num' => $assignment_num,
                          ':currentclass_id' => $currentclass_id));
     $assigntime_data = $stmt -> fetch();
     $perc_of_assign = $assigntime_data['perc_'.$alias_num];
      */
     
      

         
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
            $resp = array('a'=>0,'b'=>2,'c'=>0,'d'=>0,'e'=>0,'f'=>0,'g'=>0,'h'=>0,'i'=>0,'j'=>0);
           $get_flag=0;
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
            $tol_type=array('a'=>0,'b'=>0,'c'=>0,'d'=>0,'e'=>0,'f'=>0,'g'=>0,'h'=>0,'i'=>0,'j'=>0);

            $ansFormat=array('ans_a' =>"",'ans_b' =>"",	'ans_c' =>"",'ans_d' =>"",'ans_e' =>"",'ans_f' =>"",	'ans_g' =>"",'ans_h' =>"",'ans_i' =>"",'ans_j'=>"");
			
			
			$hintLimit = 3;
			$dispBase = 1;
			
			
            $tol_key=array_keys($tol);
            $tol_type_key=array_keys($tol_type);
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
			$probData = $stmt -> fetch();
			if ( $probData === false ) {
				$_SESSION['error'] = 'Bad value for problem_id';
				header( 'Location: QRExam.php' ) ;
				return;
			}	
			
			$probStatus = $probData['status'];
			if ($probStatus =='suspended'){
				$_SESSION['error'] = 'problem has been suspended, check back later';
				header( 'Location: QRExam.php' ) ;
				return;	
			}
	
			// get the tolerances and if the part has any hintfile	
				// initialize some arrays
                foreach(range('a','j') as $v){
                    $tol_type[$v] = $probData['tol_'.$v.'_type'];	 
                    if( $tol_type[$v] == 1){
                        $tol[$v] = $probData['tol_'.$v]/1000000;
                    } else {
                        $tol[$v] = $probData['tol_'.$v]*0.001;	
                    }
                 }
        //         echo "hint_a".$probData['hint_a'];
		
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
					
			$unit = array_slice($probData,22,20);  // does the same thing but easier so long as the table always has the same structure
		
			// Next check the Qa table and see which values have non null values - for those 
			$probParts=0;
			$stmt = $pdo->prepare("SELECT * FROM Qa where problem_id = :problem_id AND dex = :dex");
			$stmt->execute(array(":problem_id" => $problem_id, ":dex" => 1));
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
          
          $oldresp_flag = 0;
          for ($j=0; $j<=9; $j++) {
              
             if($corr_spec_num  && $corr_spec_num[$j]==1){ 
                    $resp[$resp_key[$j]]=$soln[$j];  
             }
             
             
             $oldresp_flag = 1;  
           }
    
     if( $get_flag == 0 ){ // if we are comming in from this file on a post
    // get the old repsonses from the response table check to see which ones have changed and 
      $i =0;
      $changed_flag = false;
      foreach(range('a','j') as $v){
          if( $partsFlag[$i]){ 
                $sql = 'SELECT `resp_value` FROM Bc_resp WHERE `activity_id` = :activity_id AND `part_name` = :part_name ORDER BY `bc_resp_id` DESC LIMIT 1';
                 $stmt = $pdo->prepare($sql);
                  $stmt ->execute(array(
                ':activity_id' => $activity_id,
                ':part_name' => $v
            ));
            $resp_data = $stmt -> fetch();
            if ($resp_data != false){
            $old_resp[$i] = $resp_data['resp_value'];
            } else {
                $resp[$v]= '';
            }
            
            
            if(isset($_POST[$v])&& $_POST[$v] !== ''){
                $resp[$v]=(float)$_POST[$v]+0.0;
            } else {
                 $resp[$v]= '';
            }
            
            
            // now get the counts for all of the previous tries from the table
           $sql = 'SELECT COUNT(`resp_value`) FROM `Bc_resp` WHERE `activity_id` = :activity_id AND `part_name` = :part_name';
                 $stmt = $pdo->prepare($sql);
                  $stmt ->execute(array(
                ':activity_id' => $activity_id,
                ':part_name' => $v
            ));
             $count_data = $stmt -> fetchColumn();
             $wrongCount[$i] = $count_data;
             $count_tot = $count_tot + $count_data;
             
            
             
               // put the wrong count values in activity table for easy access by other files
            $sql = 'UPDATE `Activity` SET wcount_bc_'.$v.'= :wcount_x WHERE activity_id = :activity_id';
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
//                var_dump($tol_type);
 //               echo(" resp ".$resp[$v]);
                if (($resp[$v] && $tol_type[$v]==0 && $resp[$v] !=0) || ($resp[$v] && $tol_type[$v] ==1)){    //? this condition was put in as a hack to make sure we are not recording so many 0s in the resp data

                $sql = 'INSERT INTO Bc_resp (activity_id, resp_value,part_name) VALUES (:activity_id, :resp_value, :part_name)';
                $stmt = $pdo->prepare($sql);
                $stmt ->execute(array(
                    ':activity_id' => $activity_id,
                    ':resp_value' => $resp[$v],
                    ':part_name' => $v
                 ));
                 
                 
                 // get the time they have been working on each part in minutes
                 $diff_time_min = array();
                 
                  $sql = 'SELECT UNIX_TIMESTAMP(`created_at`) AS created_at FROM Bc_resp WHERE activity_id = :activity_id AND part_name = :part_name ORDER BY bc_resp_id DESC LIMIT 1';
                $stmt = $pdo->prepare($sql);
                $stmt ->execute(array(
                    ':activity_id' => $activity_id,
                    ':part_name' => $v,
                 ));
                 $original_dates = $stmt -> fetch();                
                 $last_date = $original_dates['created_at'];
               
                // get the time they have been working on this part from the Bc_resp table
                $sql = 'SELECT UNIX_TIMESTAMP(`created_at`) AS created_at FROM Bc_resp WHERE activity_id = :activity_id AND part_name = :part_name ORDER BY bc_resp_id ASC LIMIT 1';
                $stmt = $pdo->prepare($sql);
                $stmt ->execute(array(
                    ':activity_id' => $activity_id,
                    ':part_name' => $v,
                 ));
                 $original_dates = $stmt -> fetch();                
                 $first_date = $original_dates['created_at'];
                 
                if (is_numeric($last_date) && is_numeric($first_date))
                {$diff_time_min[$i] = round(($last_date - $first_date)/60);} else {$diff_time_min[$i]=0;}
                 
            }
        }
        
        }
        $i++;  
      }
     /*  
      if ($changed_flag){
            $count_tot++;
            $_SESSION['count_tot'] = $count_tot;
      }
		 */
        for ($j=0; $j<=9; $j++) {
			if($partsFlag[$j] ) {

         /*        
                echo 'resp_'.$j.'  '.$resp[$resp_key[$j]] .'<br>';			
                echo 'tol_key_type_'.$j.'  '.$tol_type[$tol_type_key[$j]].'<br>';			
                echo 'tol_'.$j.'  '.$tol[$tol_key[$j]] .'<br>';		
                echo 'soln_'.$j.'  '.$soln[$j].'<br>';		
                echo '<br><br>';
                 */
                
                if($soln[$j]==0){  // take care of the zero solution case
                    $sol=1;
                } else {
                    $sol=$soln[$j];
                }	
                
                if($tol_type[$tol_type_key[$j]]==0 && $resp[$resp_key[$j]] != '' &&	(abs(($soln[$j]-(float)$resp[$resp_key[$j]])/$sol)<= $tol[$tol_key[$j]])) {   // first condition makes sure we have a relative error
                    $corr_num[$corr_key[$j]]=1;
                    $corr[$corr_key[$j]]='Correct';
                    $score=$score+1;
                                          
               } elseif($tol_type[$tol_type_key[$j]]==1 &&  $resp[$resp_key[$j]] !== '' && (abs(($soln[$j]-(float)$resp[$resp_key[$j]]))<= $tol[$tol_key[$j]]) ){  // looking for a absolute error
                    $corr_num[$corr_key[$j]]=1;
                    $corr[$corr_key[$j]]='Correct';
                    $score=$score+1;
               }
                else  // got it wrong or did not attempt
                {
 
                    if ($resp[$resp_key[$j]]=='')  // did not attempt it
                    {
                        $corr_num[$corr_key[$j]]=0;
                        $corr[$corr_key[$j]]='';
                   
                    }
                    else  // response is  probably did not answer (better to use POST value I suppose - fix later
                    {
                        $wrongCount[$j] = $wrongCount[$j]+1;
                            $corr_num[$corr_key[$j]]=0;
                            $corr[$corr_key[$j]]='Not Correct';
                    }
                }		
			}
		}
     
    $switch_to_bc = 0;  // need to put in logic to cahnge this if necessary!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 
 
     $sql ='UPDATE `Activity` SET  bc_correct_a = :bc_correct_a,bc_correct_b = :bc_correct_b,bc_correct_c = :bc_correct_c,bc_correct_d = :bc_correct_d,bc_correct_e = :bc_correct_e,bc_correct_f = :bc_correct_f,bc_correct_g = :bc_correct_g,bc_correct_h = :bc_correct_h,bc_correct_i = :bc_correct_i,bc_correct_j = :bc_correct_j, switch_to_bc = :switch_to_bc
                              WHERE activity_id = :activity_id';
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute(array(
                ':activity_id' => $activity_id,
                ':bc_correct_a' => $corr_num['a'],
                ':bc_correct_b' => $corr_num['b'],
                ':bc_correct_c' => $corr_num['c'],
                ':bc_correct_d' => $corr_num['d'],
                ':bc_correct_e' => $corr_num['e'],
                ':bc_correct_f' => $corr_num['f'],
                ':bc_correct_g' => $corr_num['g'],
                ':bc_correct_h' => $corr_num['h'],
                ':bc_correct_i' => $corr_num['i'],
                ':bc_correct_j' => $corr_num['j'],
                ':switch_to_bc' => $switch_to_bc,
                 ));
 
            
    }
  
    $sequential_part_display_ar = array();  // tells if I should display part or not
    $sequential_part_display_question_ar = array();  // tells if I should display or blur the question in the checker 
    $sequential_part_disable_input_ar = array();  // this should disable the input button on non active questions 
    $sequential_part_disable_button_ar = array();  // this should disable the show answer  button on non active questions 
    $first_one_index = 0;
    for( $j=9; $j>=0; $j--){
        if ($partsFlag[$j]){
            $sequential_part_disable_input_ar[$j]="";
            $sequential_part_disable_button_ar[$j]="";
            $first_one_index = $j;
        }
    }
    $sequential_part_display_ar[ $first_one_index] = "display";  // always display the first one - usually part a)

 //   find the current part that the student is working on
    $current_part=-1;
    $k = 0;
    foreach(range('a','j') as $v){
       if ($corr[$v] =="Correct"){
           $current_part = $k;
       }
       $k++;
    }

    // echo ("current part ".$current_part);
    // echo '<br>';
    $next_one = true;
    for ($m=$current_part;$m<=8; $m++)  {
        if($partsFlag[$m+1] && $next_one){
            $current_part = $m+1;
            $next_one = false;
        }
}
// echo ("current part ".$current_part);
// echo '<br>';
   
    for( $j=$first_one_index+1; $j<=9; $j++){  // set them all to display or display none then correct them later
     if ($sequential == 0){ 
      $sequential_part_display_question_ar[$j] = "display";
//      $sequential_part_disable_input_ar[$j]= "";
     } else {
//!      $sequential_part_display_ar[$j]= "display_none";
 //!     $sequential_part_disable_input_ar[$j]= "disabled";
 //! temp removed so hints show up for all parts if avaialbe
     } 
  }
          $next_one = false;
    for( $j=$first_one_index; $j<=8; $j++){
      if (($sequential == 1 && $corr[$corr_key[$j]]=="Correct" || $next_one)  ){  // logic if the next one is not a checker problem part (e.g. part b and d are checker problem part but a and c are free response or drawing...)
  
          $next_one = true;
          if ($partsFlag[$j+1] && $next_one ){  // only display the current part
              $next_one = false;
             $sequential_part_display_ar[$j+1]= "display";
              $sequential_part_disable_input_ar[$j+1]= "";
            
          }

  
      }
  }

  for ($j = $first_one_index;$j<=9; $j++){
    if ($j == $current_part){
        $sequential_part_display_question_ar[$j]= "display";
        $sequential_part_disable_button_ar[$j] = "";
      } else {
        $sequential_part_display_question_ar[$j]= "display_blur"; 
        $sequential_part_disable_button_ar[$j] = "disabled";
      }

  }
 // $sequential_part_disable_input_ar = sort($sequential_part_disable_input_ar, SORT_REGULAR);

//   var_dump($sequential_part_display_ar);
//   echo '<br>';
//   echo '<br>';
//   var_dump($activity_data);
//   echo '<br>';
//   echo '<br>';
//   var_dump( $sequential_part_disable_input_ar);
//   echo '<br>';
//   echo '<br>';
//   var_dump($sequential_part_disable_button_ar);
//   echo '<br>';
//   echo '<br>';

//   var_dump( $sequential_part_display_question_ar);
// var_dump( $activity_data['wcount_bc_c']);
//   echo '<br>';
//   echo '<br>';
 //  var_dump( $diff_time_min);
 //  echo($diff_time_min[3]);
//   echo '<br>';
//   echo '<br>';
//   var_dump( $assigntime_data['bc_ans_t']);

		
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
        <link rel="stylesheet" href="displayProblem.css"> 
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>       
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
<style>

    #peer_help_button{
      opacity: 0.6;
      cursor: not-allowed;
    }
    body{ 
        background-color:  #e6f7ff;
    }
    

</style>	
	</head>

	<body>
	<header>
	<h4>Base-Case Checker
             <!--     <span><input type="button" id="show_answer_button" class="btn-default" value="Show Answer"> </span>&nbsp;
        <input type="button" id="review_concepts_button" class="btn-default" value="Review Concepts">
            </span>&nbsp;
              <input type="button" id="peer_discuss_button" class="btn-default" value="Forum">
            </span>&nbsp;&nbsp;
            
            <span style ="font-size:14px;" >  Request Help From: </span>
            <span>
            <input type="button" id="peer_help_button" class="btn-default" value="Students">
            </span>&nbsp;
            <span>
            <span>
            <input type="button" id="TA_help_button" class="btn-default" value="Tutors / TA">
            </span>&nbsp;
            <span>
            <span>
            <input type="button" id="instructor_help_button" class="btn-default" value="Instructor">
            </span>&nbsp;
            <span>
        -->    
            </h4>
	</header>
	<main>
	<h6 class = "container-float" style = "font-size: 0.8rem;"> Name: <?php echo($stu_name);?> &nbsp; &nbsp; Assignment Number: <?php echo($assignment_num);?>&nbsp; &nbsp;  Problem: <?php echo($alias_num);?> &nbsp; &nbsp;   Max Attempts: <?php if ($attempt_type==1){echo('infinite');}else{echo($num_attempts);} ?> &nbsp; &nbsp; Answer Thresholds:  count = <?php echo($assigntime_data['bc_ans_n']);?>&nbsp; time = <?php echo($assigntime_data['bc_ans_t']);?>,&nbsp; min&nbsp; &nbsp; Hint Thresholds:  count = <?php echo $help_n_hint ?>,&nbsp; time = <?php echo $help_t_hint?> min </h6>
   
 <!--
	<font size = "1"> Problem Number: <?php echo ($problem_id) ?> -  <?php echo ($dex) ?> </font>
    <div id = "test"> test <?php print_r ($wrongCount);?></div>
    <div id = "test2"> parts_flag <?php print_r ($partsFlag);?></div>
     <div id = "test3"> soln <?php print_r ($soln);?></div>
      <div id = "test4"> soln_part_pblm <?php print_r ($corr_spec_num);?></div>
-->
	<form autocomplete="off" id = "check_form" method="POST" >
	<!--<p><font color=#003399>Index: </font><input type="text" name="dex_num" size=3 value="<?php echo (htmlentities($_SESSION['index']))?>"  ></p> -->

	<?php
 /*   
   echo(' resp["a"]: '.$resp["a"]);
    echo(' resp["b"]: '.$resp["b"]);
    echo(' get_flag: '.$get_flag);
    echo(' progress: '.$progress);
      echo(' old_resp[0]: '.$old_resp[0]); 
      echo(' old_resp[1]: '.$old_resp[1]); 
      echo(' partsFlag[0]: '.$partsFlag[0]); 
      echo(' partsFlag[1]: '.$partsFlag[1]); 
        */
       
    if($attempt_type ==1 || ($attempt_type ==2 && $count_tot <= $num_attempts)){
	if ($partsFlag[0]){ ?> 
    <div id = "part-a-BC-container" class = "checker-parts-conatiner">
    <div id = "part-a-BC-question" class = "parts-question  <?php echo $sequential_part_display_question_ar[0]; ?>"></div>
    <div id = "part-a-BC-display" class = "display_none"><?php echo $sequential_part_display_question_ar[0]; ?></div>
	<div id = "part-a-BC" class = "problem-parts"> a)(<?php echo $assigntime_data['perc_a_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="a" id = "a" size = 10% <?php echo ( $sequential_part_disable_input_ar[0] )?> value="<?php echo (htmlentities($resp['a']))?>" > <?php echo(htmlspecialchars_decode($unit[0])) ?> &nbsp - <b><?php echo ($corr['a']) ?> </b> count <?php echo(@$wrongCount[0].' '); ?> 
	 <?php 
    if ( (( $activity_data['wcount_bc_a']>= $assigntime_data['bc_ans_n'] && @$diff_time_min[0]>= $assigntime_data['bc_ans_t'])|| $activity_data['correct_a']==1 ) && $corr['a']!="Correct")
     { 
        echo('<span><input type="button" id="show_answer_button_a" class= "btn  btn-outline-primary show_answer" '.$sequential_part_disable_button_ar[0].'  value="Show Answer"> </span>&nbsp;');
    }
    if ( (( $activity_data['wcount_bc_a']>= $help_n_hint && @$diff_time_min[0]>= $help_t_hint && $hintaPath != "uploads/default_hints.html") ) )
    {echo '<a href="'.$hintaPath.'"target = "_blank" class = "btn  btn-outline-primary"> <i class="bi bi-info-circle"></i> Hint - opens in new window </a>';}
  //   { echo('<span><input type="button" id="show_hint_button_a" class="btn-default" '.$sequential_part_disable_button_ar[0].' value="'.$hintaPath.'"> </span>&nbsp;');}
     if ($corr['a']=="Correct")
    {echo '<span id = "show_ans_a" class = "show_ans"> - Computed value is: '.$soln[0].'</span>';} 
 
      ?>  
          <input type="hidden" id="ans_a" value="<?php echo ($soln[0])?>" >
    <!--
	 -->

     </div></div>
	<?php } 
    

	if ($partsFlag[1]){ ?> 
        <div id = "part-b-BC-container" class = "checker-parts-conatiner">
    <div id = "part-b-BC-question" class = "parts-question <?php echo $sequential_part_display_question_ar[1]; ?>"></div>
    <div id = "part-b-BC-display" class = "display_none"><?php echo $sequential_part_display_question_ar[1]; ?></div>
	<div id = "part-b-BC" class = "problem-parts"> b)(<?php echo $assigntime_data['perc_b_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="b" id = "b" size = 10% <?php echo ( $sequential_part_disable_input_ar[1] )?> value="<?php echo (htmlentities($resp['b']))?>" > <?php echo(htmlspecialchars_decode($unit[1])) ?> &nbsp - <b><?php echo ($corr['b']) ?> </b> count <?php echo(@$wrongCount[1].' '); ?> 

     <?php 
    if ( (( $activity_data['wcount_bc_b']>= $assigntime_data['bc_ans_n'] && @$diff_time_min[1]>= $assigntime_data['bc_ans_t'])|| $activity_data['correct_b']==1 ) && $corr['b']!="Correct")
     { echo('<span><input type="button" id="show_answer_button_b" class="btn  btn-outline-primary show_answer" '.$sequential_part_disable_button_ar[1].'  value="Show Answer"> </span>&nbsp;');}
     if ( (( $activity_data['wcount_bc_b']>= $help_n_hint && @$diff_time_min[1]>= $help_t_hint && $hintbPath != "uploads/default_hints.html") ) )
     {echo '<a href="'.$hintbPath.'"target = "_blank" class = "btn  btn-outline-primary"> <i class="bi bi-info-circle"></i> Hint - opens in new window </a>';}
 
     if ($corr['b']=="Correct")
    {echo '<span id = "show_ans_b" class = "show_ans"> - Computed value is: '.$soln[1].'</span>';} 
      ?>  
          <input type="hidden" id="ans_b" value="<?php echo ($soln[1])?>" >
	</div></div>
	<?php } 
  
	if ($partsFlag[2]){ ?> 
    <div id = "part-c-BC-container" class = "checker-parts-conatiner">
    <div id = "part-c-BC-question" class = "parts-question <?php echo $sequential_part_display_question_ar[2]; ?>"></div>
    <div id = "part-c-BC-display" class = "display_none"><?php echo $sequential_part_display_question_ar[2]; ?></div>
	<div id = "part-c-BC" class = "problem-parts"> c)(<?php echo $assigntime_data['perc_c_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="c" id = "c" size = 10% <?php echo ( $sequential_part_disable_input_ar[2])?> value="<?php echo (htmlentities($resp['c']))?>" > <?php echo(htmlspecialchars_decode($unit[2])) ?> &nbsp - <b><?php echo ($corr['c']) ?> </b> count <?php echo(@$wrongCount[2].' '); ?> 

     <?php 
    if ( (( $activity_data['wcount_bc_c']>= $assigntime_data['bc_ans_n'] && @$diff_time_min[2]>= $assigntime_data['bc_ans_t'])|| $activity_data['correct_c']==1 ) && $corr['c']!="Correct")
     { echo('<span><input type="button" id="show_answer_button_c" class="btn  btn-outline-primary show_answer" '.$sequential_part_disable_button_ar[2].'  value="Show Answer"> </span>&nbsp;');}
     if ( (( $activity_data['wcount_bc_c']>= $help_n_hint && @$diff_time_min[2]>= $help_t_hint && $hintcPath != "uploads/default_hints.html") ) )
     {echo '<a href="'.$hintcPath.'"target = "_blank" class = "btn  btn-outline-primary"> <i class="bi bi-info-circle"></i> Hint - opens in new window </a>';}

     if ($corr['c']=="Correct")
    {echo '<span id = "show_ans_c" class = "show_ans"> - Computed value is: '.$soln[2].'</span>';} 
      ?>  
          <input type="hidden" id="ans_c" value="<?php echo ($soln[2])?>" >	
    
    
	</div></div>
	<?php } 

	if ($partsFlag[3]){ ?> 
    <div id = "part-d-BC-container" class = "checker-parts-conatiner">
    <div id = "part-d-BC-question" class = "parts-question <?php echo $sequential_part_display_question_ar[3]; ?>"></div>
    <div id = "part-d-BC-display" class = "display_none"><?php echo $sequential_part_display_question_ar[3]; ?></div>
	<div id = "part-d-BC" class = "problem-parts"> d)(<?php echo $assigntime_data['perc_d_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="d" id = "d" size = 10% <?php echo ( $sequential_part_disable_input_ar[3])?> value="<?php echo (htmlentities($resp['d']))?>" > <?php echo(htmlspecialchars_decode($unit[3])) ?> &nbsp - <b><?php echo ($corr['d']) ?> </b> count <?php echo(@$wrongCount[3].' '); ?> 

    <?php 
    if ( (( $activity_data['wcount_bc_d']>= $assigntime_data['bc_ans_n'] && @$diff_time_min[3]>= $assigntime_data['bc_ans_t'])|| $activity_data['correct_d']==1 ) && $corr['d']!="Correct")
     { echo('<span><input type="button" id="show_answer_button_d" class="btn  btn-outline-primary show_answer" '.$sequential_part_disable_button_ar[3].'  value="Show Answer"> </span>&nbsp;');}
     if ( (( $activity_data['wcount_bc_d']>= $help_n_hint && @$diff_time_min[3]>= $help_t_hint && $hintdPath != "uploads/default_hints.html") ) )
     {echo '<a href="'.$hintdPath.'"target = "_blank" class = "btn  btn-outline-primary"> <i class="bi bi-info-circle"></i> Hint - opens in new window </a>';}

     if ($corr['d']=="Correct")
    {echo '<span id = "show_ans_d" class = "show_ans"> - Computed value is: '.$soln[3].'</span>';} 
      ?>  
          <input type="hidden" id="ans_d" value="<?php echo ($soln[3])?>" >		
	</div></div>
	<?php } 

	if ($partsFlag[4]){ ?> 
    <div id = "part-e-BC-container" class = "checker-parts-conatiner">
    <div id = "part-e-BC-question" class = "parts-question <?php echo $sequential_part_display_question_ar[4]; ?>"></div>
    <div id = "part-e-BC-display" class = "display_none"><?php echo $sequential_part_display_question_ar[4]; ?></div>
	<div id = "part-e-BC" class = "problem-parts"> e)(<?php echo $assigntime_data['perc_e_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="e" id = "e" size = 10% <?php echo ( $sequential_part_disable_input_ar[4])?> value="<?php echo (htmlentities($resp['e']))?>" > <?php echo(htmlspecialchars_decode($unit[4])) ?> &nbsp - <b><?php echo ($corr['e']) ?> </b> count <?php echo(@$wrongCount[4].' '); ?> 
     <?php 
    if ( (( $activity_data['wcount_bc_e']>= $assigntime_data['bc_ans_n'] && @$diff_time_min[4]>= $assigntime_data['bc_ans_t'])|| $activity_data['correct_e']==1 ) && $corr['e']!="Correct")
     { echo('<span><input type="button" id="show_answer_button_e" class="btn  btn-outline-primary show_answer" '.$sequential_part_disable_button_ar[4].'  value="Show Answer"> </span>&nbsp;');}
     if ( (( $activity_data['wcount_bc_e']>= $help_n_hint && @$diff_time_min[4]>= $help_t_hint && $hintePath != "uploads/default_hints.html") ) )
     {echo '<a href="'.$hintePath.'"target = "_blank" class = "btn  btn-outline-primary"> <i class="bi bi-info-circle"></i> Hint - opens in new window </a>';}

     if ($corr['e']=="Correct")
    {echo '<span id = "show_ans_e" class = "show_ans"> - Computed value is: '.$soln[4].'</span>';} 
      ?>  
          <input type="hidden" id="ans_e" value="<?php echo ($soln[4])?>" >	
          
	</div></div>
	<?php } 

	if ($partsFlag[5]){ ?> 
    <div id = "part-f-BC-container" class = "checker-parts-conatiner">
    <div id = "part-f-BC-question" class = "parts-question <?php echo $sequential_part_display_question_ar[5]; ?>"></div>
    <div id = "part-f-BC-display" class = "display_none"><?php echo $sequential_part_display_question_ar[5]; ?></div>
	<div id = "part-f-BC" class = "problem-parts"> f)(<?php echo $assigntime_data['perc_f_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="f" id = "f" size = 10% <?php echo ( $sequential_part_disable_input_ar[5])?> value="<?php echo (htmlentities($resp['f']))?>" > <?php echo(htmlspecialchars_decode($unit[5])) ?> &nbsp - <b><?php echo ($corr['f']) ?> </b> count <?php echo(@$wrongCount[5].' '); ?> 
    <?php 
    if ( (( $activity_data['wcount_bc_f']>= $assigntime_data['bc_ans_n'] && @$diff_time_min[5]>= $assigntime_data['bc_ans_t'])|| $activity_data['correct_f']==1 ) && $corr['f']!="Correct")
     { echo('<span><input type="button" id="show_answer_button_f" class="btn  btn-outline-primary show_answer" '.$sequential_part_disable_button_ar[5].'  value="Show Answer"> </span>&nbsp;');}
     if ( (( $activity_data['wcount_bc_f']>= $help_n_hint && @$diff_time_min[5]>= $help_t_hint && $hintfPath != "uploads/default_hints.html") ) )
     {echo '<a href="'.$hintfPath.'"target = "_blank" class = "btn  btn-outline-primary"> <i class="bi bi-info-circle"></i> Hint - opens in new window </a>';}

     if ($corr['f']=="Correct")
    {echo '<span id = "show_ans_f" class = "show_ans"> - Computed value is: '.$soln[5].'</span>';} 
      ?>  
          <input type="hidden" id="ans_f" value="<?php echo ($soln[5])?>" >	
          
          
	</div></div>
	<?php } 

	if ($partsFlag[6]){ ?> 
    <div id = "part-g-BC-container" class = "checker-parts-conatiner">
    <div id = "part-g-BC-question" class = "parts-question <?php echo $sequential_part_display_question_ar[6]; ?>"></div>
    <div id = "part-g-BC-display" class = "display_none"><?php echo $sequential_part_display_question_ar[6]; ?></div>
	<div id = "part-g-BC" class = "problem-parts"> g)(<?php echo $assigntime_data['perc_g_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="g" id = "g" size = 10% <?php echo ( $sequential_part_disable_input_ar[6])?> value="<?php echo (htmlentities($resp['g']))?>" > <?php echo(htmlspecialchars_decode($unit[6])) ?> &nbsp - <b><?php echo ($corr['g']) ?> </b> count <?php echo(@$wrongCount[6].' '); ?> 
    <?php 
    if ( (( $activity_data['wcount_bc_g']>= $assigntime_data['bc_ans_n'] && @$diff_time_min[6]>= $assigntime_data['bc_ans_t'])|| $activity_data['correct_g']==1 ) && $corr['g']!="Correct")
     { echo('<span><input type="button" id="show_answer_button_g" class="btn  btn-outline-primary show_answer" '.$sequential_part_disable_button_ar[6].'  value="Show Answer"> </span>&nbsp;');}
     if ( (( $activity_data['wcount_bc_g']>= $help_n_hint && @$diff_time_min[6]>= $help_t_hint && $hintgPath != "uploads/default_hints.html") ) )
     {echo '<a href="'.$hintgPath.'"target = "_blank" class = "btn  btn-outline-primary"> <i class="bi bi-info-circle"></i> Hint - opens in new window </a>';}

     if ($corr['g']=="Correct")
    {echo '<span id = "show_ans_g" class = "show_ans"> - Computed value is: '.$soln[6].'</span>';} 
      ?>  
          <input type="hidden" id="ans_g" value="<?php echo ($soln[6])?>" >	
	</div></div>
	<?php } 

	if ($partsFlag[7]){ ?> 
    <div id = "part-h-BC-container" class = "checker-parts-conatiner">
    <div id = "part-h-BC-question" class = "parts-question <?php echo $sequential_part_display_question_ar[7]; ?>"></div>
    <div id = "part-h-BC-display" class = "display_none"><?php echo $sequential_part_display_question_ar[7]; ?></div>
	<div id = "part-h-BC" class = "problem-parts"> h)(<?php echo $assigntime_data['perc_h_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="h" id = "h" size = 10% <?php echo ( $sequential_part_disable_input_ar[7])?> value="<?php echo (htmlentities($resp['h']))?>" > <?php echo(htmlspecialchars_decode($unit[7])) ?> &nbsp - <b><?php echo ($corr['h']) ?> </b> count <?php echo(@$wrongCount[7].' '); ?> 
    <?php 
    if ( (( $activity_data['wcount_bc_h']>= $assigntime_data['bc_ans_n'] && @$diff_time_min[7]>= $assigntime_data['bc_ans_t'])|| $activity_data['correct_h']==1 ) && $corr['h']!="Correct")
     { echo('<span><input type="button" id="show_answer_button_h" '.$sequential_part_disable_button_ar[7].'  class="btn  btn-outline-primary show_answer" value="Show Answer"> </span>&nbsp;');}
     if ( (( $activity_data['wcount_bc_h']>= $help_n_hint && @$diff_time_min[7]>= $help_t_hint && $hinthPath != "uploads/default_hints.html") ) )
     {echo '<a href="'.$hinthPath.'"target = "_blank" class = "btn  btn-outline-primary"> <i class="bi bi-info-circle"></i> Hint - opens in new window </a>';}

     if ($corr['h']=="Correct")
    {echo '<span id = "show_ans_h" class = "show_ans"> - Computed value is: '.$soln[7].'</span>';} 
      ?>  
          <input type="hidden" id="ans_h" value="<?php echo ($soln[7])?>" >	
	</div></div>
	<?php } 

	if ($partsFlag[8]){ ?> 
    <div id = "part-i-BC-container" class = "checker-parts-conatiner">
    <div id = "part-i-BC-question" class = "parts-question <?php echo $sequential_part_display_question_ar[8]; ?>"></div>
    <div id = "part-i-BC-display" class = "display_none"><?php echo $sequential_part_display_question_ar[8]; ?></div>
	<div id = "part-i-BC" class = "problem-parts"> i)(<?php echo $assigntime_data['perc_i_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="i" id = "i" size = 10% <?php echo ( $sequential_part_disable_input_ar[8])?> value="<?php echo (htmlentities($resp['i']))?>" > <?php echo(htmlspecialchars_decode($unit[8])) ?> &nbsp - <b><?php echo ($corr['i']) ?> </b> count <?php echo(@$wrongCount[8].' '); ?> 
<?php 
    if ( (( $activity_data['wcount_bc_i']>= $assigntime_data['bc_ans_n'] && @$diff_time_min[8]>= $assigntime_data['bc_ans_t'])|| $activity_data['correct_i']==1 ) && $corr['i']!="Correct")
     { echo('<span><input type="button" id="show_answer_button_i" class="btn  btn-outline-primary show_answer" '.$sequential_part_disable_button_ar[8].'  value="Show Answer"> </span>&nbsp;');}
     if ( (( $activity_data['wcount_bc_i']>= $help_n_hint && @$diff_time_min[8]>= $help_t_hint && $hintiPath != "uploads/default_hints.html") ) )
     {echo '<a href="'.$hintiPath.'"target = "_blank" class = "btn  btn-outline-primary"> <i class="bi bi-info-circle"></i> Hint - opens in new window </a>';}

     if ($corr['i']=="Correct")
    {echo '<span id = "show_ans_i" class = "show_ans"> - Computed value is: '.$soln[8].'</span>';} 
      ?>  
          <input type="hidden" id="ans_i" value="<?php echo ($soln[8])?>" >	
	</div></div>
	<?php } 

	if ($partsFlag[9]){ ?> 
    <div id = "part-j-BC-container" class = "checker-parts-conatiner">
    <div id = "part-j-BC-question" class = "parts-question  <?php echo $sequential_part_display_question_ar[9]; ?>"></div>
    <div id = "part-j-BC-display" class = "display_none"><?php echo $sequential_part_display_question_ar[9]; ?></div>
	<div id = "part-j-BC" class = "problem-parts"> j)(<?php echo $assigntime_data['perc_j_'.$alias_num]; ?>%) <input [ type=number]{width: 5%;} name="j" id = "j" size = 10% <?php echo ( $sequential_part_disable_input_ar[9])?> value="<?php echo (htmlentities($resp['j']))?>" > <?php echo(htmlspecialchars_decode($unit[9])) ?> &nbsp - <b><?php echo ($corr['j']) ?> </b> count <?php echo(@$wrongCount[9].' '); ?> 
   <?php 
    if ( (( $activity_data['wcount_bc_j']>= $assigntime_data['bc_ans_n'] && @$diff_time_min[9]>= $assigntime_data['bc_ans_t'])|| $activity_data['correct_j']==1 ) && $corr['j']!="Correct")
     { echo('<span><input type="button" id="show_answer_button_j" class="btn  btn-outline-primary show_answer" '.$sequential_part_disable_button_ar[9].' value="Show Answer"> </span>&nbsp;');}
     if ( (( $activity_data['wcount_bc_j']>= $help_n_hint && @$diff_time_min[9]>= $help_t_hint && $hintjPath != "uploads/default_hints.html") ) )
     {echo '<a href="'.$hintjPath.'"target = "_blank" class = "btn  btn-outline-primary"> <i class="bi bi-info-circle"></i> Hint - opens in new window </a>';}

     if ($corr['j']=="Correct")
    {echo '<span id = "show_ans_j" class = "show_ans"> - Computed value is: '.$soln[9].'</span>';} 
      ?>  
          <input type="hidden" id="ans_j" value="<?php echo ($soln[9])?>" >	

	</div></div>
	<?php } 
    }

	
	?>
 Base-Case Count: <?php echo ($count_tot) ?>  <span id ="t_delay_message"></span>
	<!-- <p><input type = "submit" id = "check_submit" name = "check" value="Check" size="10" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp <b> <font size="4" color="Navy"></font></b></p> -->
   <div class = "container-fluid">
    <div class = "row mt-3 ms-2">
        <div class = "col-3">
            <button type = "submit" id = "check_submit" class = "btn btn-primary" style="font-size: 1.5rem;" name = "check" > Check <i class="bi bi-card-checklist" ></i> </button>
        </div>
        <div class = "col-3">
            <button type = "button" id = "help" class = "btn btn-outline-danger  d-none" style="font-size: 1.0rem;" name = "help" > Help <i class="bi bi-question-circle" ></i> </button>
            <button type = "button" id = "show_bot_output" class = "btn btn-success d-none me-1 " data-bs-toggle="modal" data-bs-target="#bot_response_modal" style="font-size: 1.0rem;"  > Show Bot Response <i  class="bi bi-robot" ></i> </button>
            <div id = "spinner" class = "spinner-border text-primary d-none" style="width: 3rem; height: 3rem;" role = "status" style="font-size: 1.5.0rem;" name = "spinner" > </div>
        </div>
        
        <div class = "col-6">
            <button type = "button" id = "check_list" class = "btn btn-outline-secondary d-none me-1 " data-bs-toggle="modal" data-bs-target="#check_list_modal" style="font-size: 1.0rem;" name = "check_list" > Check List <i  class="bi bi-card-checklist" ></i> </button>
            <button type = "button" id = "ask_bot" class = "btn btn-outline-secondary d-none me-1" style="font-size: 1.0rem;" name = "ask_bot" > Ask Bot <i  class="bi bi-robot" ></i> </button>
            <button type = "button" id = "post_to_students" class = "btn btn-outline-secondary d-none " disabled style="font-size: 1.0rem;" name = "post_to_students" > Post ? to Forum <i  class="bi bi-people" ></i> </button>
            <button type = "button" id = "email_prof" class = "btn btn-outline-secondary d-none " style="font-size: 1.0rem;" disabled data-bs-toggle="modal" data-bs-target="#email_prof_modal" > Email Prof <i  class="bi bi-envelope" ></i> </button>
        </div>
    </div>
    <div class = "row">
        
        <div class = "col">
            <div id = "bot_message" class = "d-none"></div>
         </div>

    </div>
  </div>
             <input type="hidden" id = "activity_id" name="activity_id" value="<?php echo ($activity_id)?>" >
              <input type="hidden" id = "prob_parts" value="<?php echo ($probParts)?>" >
               <input type="hidden" id = "count_tot" value="<?php echo ($count_tot)?>" >
               <input type="hidden" id = "problem_id" value="<?php echo ($problem_id)?>" >
          
	</form>

<!-- put a check box list in a  bootstrap 5 modal -->
<div id="check_list_modal" class = "modal" tabindex="-1" aria-labelledby="list-modal-title" aria-hidden="true">
 <div class="modal-dialog modal-xl"> 
    <div class="modal-content">
                <div class="modal-header">
                    <div class = "modal-title" id = "list-modal-title"> <h3>Check List for Problem Solving</h3>
                    <h5> Most common mistakes when solving quatitative problems</h5></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-check  ">
                            <input class="form-check-input" type="checkbox" name="check_list[]" value="1" id="check_list_1">
                            <label class="form-check" for="check_list_1"> Carefully re-read the problem statement and make sure diagram or relisted information matches statement </label> 
                        </div>
                        <div class="form-check  ">
                        <input class="form-check-input" type="checkbox" name="check_list[]" value="2" id="check_list_2">            
                            <label class="form-check" for="check_list_2"> Make sure you are answering the question being asked </label>
                        </div>
                        <div class="form-check">   
                            <input class="form-check-input" type="checkbox" name="check_list[]" value="3" id="check_list_3">
                            <label class="form-check" for="check_list_3"> Lower the level of abstraction by taking a basis of calculation  </label>
                        </div>
                        <div class="form-check">   
                            <input class="form-check-input" type="checkbox" name="check_list[]" value="4" id="check_list_4">
                            <label class="form-check" for="check_list_4"> Make sure calculations with units cancel to expected units </label>
                        </div>
                        <div class="form-check">   
                            <input class="form-check-input" type="checkbox" name="check_list[]" value="5" id="check_list_5">
                            <label class="form-check" for="check_list_5"> Keep precision in calculation until the end - that is do not round until the end</label>
                        </div>
                        <div class="form-check">   
                            <input class="form-check-input" type="checkbox" name="check_list[]" value="6" id="check_list_6">
                            <label class="form-check" for="check_list_6"> Take a break from the problem and do something else - come back to it later</label>
                        </div>

                    </div>
                </div>
            </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
      </div>
  </div>
 </div>
    
<div id="bot_response_modal" class = "modal" tabindex="-1" aria-labelledby="bot-response-modal-title" aria-hidden="true">
 <div class="modal-dialog modal-xl"> 
    <div class="modal-content">
                <div class="modal-header">
                    <div class = "modal-title" id = "bot-response-modal-title"> <h3>OpenAI Model Output</h3>
                    <h5> Warning - this is the output of a large <span style="color:blue"> language </span> model not an engineering model.  It can give responses that sound reasonable 
                        but are totally <span style="color:red">wrong!</span> Never the less, its response may be useful and it may spark ideas that you would not have had otherwise.
                    </h5></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body" id = "bot_output">
            </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
      </div>
  </div>
 </div>
    

<div id="email_prof_modal" class = "modal" tabindex="-1" aria-labelledby="email_prof-modal-title" aria-hidden="true">
 <div class="modal-dialog modal-xl"> 
    <div class="modal-content">
                <div class="modal-header">
                    <div class = "modal-title" id = "email_prof-modal-title"> <h3>Email Prof</h3>
                    <h5> To email the professor, you will need to both describe where you are stuch and submit your work with a screenshot or picture.  
                    </h5></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body" id = "email_modal_body">
                <div class="row">
                    <div class="col-12">
                        <form id = "email_modal_form">
                        <div class="form-group">
                            <label for="describe_question">Describe Question</label>
                            <input type="text" class="form-control" id="describe_question" aria-describedby="emailProf" placeholder="I am having problems with part c"> 
                        </div>
                        </form>
                    </div>   
                </div>     
            </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id = "email_prof_submit" class="btn btn-primary" >Submit</button>
                    </div>
      </div>
  </div>
 </div>
    

 <script src="https://npmcdn.com/pdfjs-dist/build/pdf.js"></script>

	<script>
 const email_prof_submit = document.getElementById('email_prof_submit');
 const activity_id = document.getElementById('activity_id').value;
 const show_bot_output = document.getElementById('show_bot_output');
 const spinner = document.getElementById('spinner');
 const email_prof = document.getElementById('email_prof');
 const post_to_students = document.getElementById('post_to_students');
 const ask_bot = document.getElementById('ask_bot');
 const check_list = document.getElementById('check_list');
 const help = document.getElementById('help');
 help.addEventListener('click', function(){
    console.log('help clicked');
    email_prof.classList.remove('d-none');
    post_to_students.classList.remove('d-none');
    ask_bot.classList.remove('d-none');
    check_list.classList.remove('d-none');
    help.classList.add('d-none');

 });

    check_list.addEventListener('click', function(){
        //show the modal
        $('#check_list_modal').modal('show');

        console.log('check_list clicked');
    });

 ask_bot.addEventListener('click', function(){
    console.log('ask_bot clicked');
    ask_bot_func();

 });

email_prof_submit.addEventListener('click', function(){
    console.log('email_prof_submit clicked');
   describe_question = document.getElementById('describe_question').innerText;
    // change the location of the window to upload_work_for_email.php and include the activity_id and problem_id
    window.location.href = 'upload_work_for_email.php?activity_id=' + activity_id + '&problem_id=' + problem_id + '&describe_question=' + describe_question;
});


 // set up an eventlistener on the document and if the user clicks any button that has a class of show_answer then display the email_prof button
    document.addEventListener('click', function(e){
        // iff the class list conatins show_answer then remove the d-none class
        if(e.target.classList.contains('show_answer')){
            help.classList.remove('d-none');
        }
    });

  $('#show_answer_button_a').click(function(){
        $(this).css('color','red');
       var ans_a = $('#ans_a').val();
        $('#a').val(ans_a);
    });
    
    
   $('#show_answer_button_b').click(function(){
        $(this).css('color','red');
       var ans_b = $('#ans_b').val();
        $('#b').val(ans_b);
    });
    
    $('#show_answer_button_c').click(function(){
        $(this).css('color','red');
       var ans_c = $('#ans_c').val();
        $('#c').val(ans_c);
    });

     $('#show_answer_button_d').click(function(){
        $(this).css('color','red');
       var ans_d = $('#ans_d').val();
        $('#d').val(ans_d);
    });


    $('#show_answer_button_e').click(function(){
        $(this).css('color','red');
       var ans_e = $('#ans_e').val();
        $('#e').val(ans_e);
    });

    $('#show_answer_button_f').click(function(){
        $(this).css('color','red');
       var ans_f = $('#ans_f').val();
        $('#f').val(ans_f);
    });

    $('#show_answer_button_g').click(function(){
        $(this).css('color','red');
       var ans_g = $('#ans_g').val();
        $('#g').val(ans_g);
    });

    $('#show_answer_button_h').click(function(){
        $(this).css('color','red');
       var ans_h = $('#ans_h').val();
        $('#h').val(ans_h);
    });

    $('#show_answer_button_i').click(function(){
        $(this).css('color','red');
       var ans_i = $('#ans_i').val();
        $('#i').val(ans_i);
    });

    $('#show_answer_button_j').click(function(){
        $(this).css('color','red');
       var ans_j = $('#ans_j').val();
        $('#j').val(ans_j);
    });


// get the data for the basecase in the parent document of the iframe

   function ask_bot_func(event) {
    spinner.classList.remove('d-none');
//   // Get the form input values
        const problem_id = document.getElementById('problem_id').value;

        const base_case = parent.document.getElementById('base_case')
        const base_case_text= base_case.innerText
        console.log('base_case_text', base_case_text)
        // find the button with class show_answer that does not hav  display_none and get the id of that button
        const show_answer_button = document.querySelector('.show_answer:not(.display-none)')
        console.log ('show_answer_button', show_answer_button)  
        const show_answer_button_id = show_answer_button.id
        console.log ('show_answer_button_id', show_answer_button_id)
        const show_answer_button_id_split = show_answer_button_id.split('_')
        const show_answer_button_last= show_answer_button_id_split[show_answer_button_id_split.length-1]
        console.log ('show_answer_button_last', show_answer_button_last)
        const text_4_inquery = `ans_${show_answer_button_last}`
        const ans_for_part = document.querySelector(`#${text_4_inquery}`).value;
        console.log('ans_for_part', ans_for_part)
    //    console.log('base_case_text', base_case_text)

      let dataToBot = {
        question_text: base_case_text,
         part: show_answer_button_last,
         answer: ans_for_part,
         problem_id: problem_id
     };


console.log('dataToBot', dataToBot)

  fetch('BC_problem_help_from_bot.php', {
    method: 'POST',
    body: JSON.stringify(dataToBot)
  })
    .then((response) => response.json())
    .then((data) => {
      
      console.log('data from the AI',data);
    // if the error flag is false then redirect to the smartForm.php page posting the form_id
   
      // make the bot_response_modal modal visible
        document.getElementById('bot_output').innerHTML = data;
        const bot_response_modal = document.getElementById('bot_response_modal')
        console.log('bot_response_modal', bot_response_modal);
       bot_response_modal.classList.remove('d-none');
       show_bot_output.classList.remove('d-none');
       ask_bot.classList.add('d-none');
       show_bot_output.click();
       spinner.classList.add('d-none');
   
   });
};

  // get a regular experssion that starts with P then the problem_id then _s_ and end with a .pdf
  // then get the text from the pdf
  



function gettext(pdfUrl){
        var pdf = pdfjsLib.getDocument(pdfUrl);
        return pdf.then(function(pdf) { // get all pages text
            var maxPages = pdf.pdfInfo.numPages;
            var countPromises = []; // collecting all page promises
            for (var j = 1; j <= maxPages; j++) {
            var page = pdf.getPage(j);

            var txt = "";
            countPromises.push(page.then(function(page) { // add page promise
                var textContent = page.getTextContent();
                return textContent.then(function(text){ // return content promise
                return text.items.map(function (s) { return s.str; }).join(''); // value page text 
                });
            }));
            }
            // Wait for all pages and join text
            return Promise.all(countPromises).then(function (texts) {
            return texts.join('');
            });
        });
        }

	</script>

	</main>
	</body>
	</html>