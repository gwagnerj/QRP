<?php
session_start();
//	if(session_status()!=PHP_SESSION_ACTIVE) session_start();  // put this in to try to get rid of a warning of headers already sent - didn't work
	require_once "pdo.php";

    $get_flag = 1;
    if (isset($_POST['post_submit'])){
      $get_flag = 0;

    }
// echo ($_POST['b']);


 if (!empty($_GET)) {
        $_SESSION['got'] = $_GET;
    } else{
        if (!empty($_SESSION['got'])) {
            $_GET = $_SESSION['got'];
            unset($_SESSION['got']);
        }
    }
        
 
	   if (isset($_POST['exam_num'])){
        $exam_num = $_POST['exam_num'];
        } elseif($_GET['exam_num']){
         $exam_num = $_GET['exam_num'];
        } elseif(isset($_SESSION['exam_num'])){
         $exam_num = $_SESSION['exam_num'];
        } else{
       $_SESSION['error_check'] = "Missing exam_num from QRExamCheck";
	  header('Location: QRexam_closed.php');
	  return;   
    }
    $_SESSION['exam_num'] = $exam_num;
   

  
        
        
        
      if (isset($_POST['problem_id'])){
        $problem_id = $_POST['problem_id'];
        } elseif($_GET['problem_id']){
         $problem_id = $_GET['problem_id'];
        } elseif(isset($_SESSION['problem_id'])){
         $problem_id = $_SESSION['problem_id'];
        } else{
       $_SESSION['error_check'] = "Missing problem_id from QRExamCheck";
	  header('Location: QRexam_closed.php');
	  return;   
    }
    $_SESSION['problem_id'] = $problem_id;
   
   
      if (isset($_POST['cclass_id'])){
        $cclass_id = $_POST['cclass_id'];
        } elseif($_GET['cclass_id']){
         $cclass_id = $_GET['cclass_id'];
        } elseif(isset($_SESSION['cclass_id'])){
         $cclass_id = $_SESSION['cclass_id'];
        } else{
       $_SESSION['error_check'] = "Missing cclass_id from QRExamCheck";
	  header('Location: QRexam_closed.php');
	  return;   
    }
    $_SESSION['cclass_id'] = $cclass_id;
     
      if (isset($_POST['alias_num'])){
        $alias_num = $_POST['alias_num'];
        } elseif($_GET['alias_num']){
         $alias_num = $_GET['alias_num'];
        } elseif(isset($_SESSION['alias_num'])){
         $alias_num = $_SESSION['alias_num'];
        } else{
       $_SESSION['error_check'] = "Missing alias_num from QRExamCheck";
	  header('Location: QRexam_closed.php');
	  return;   
    }
    $_SESSION['alias_num'] = $alias_num;
     
      if (isset($_POST['pin'])){
        $pin = $_POST['pin'];
        } elseif($_GET['pin']){
         $pin = $_GET['pin'];
        } elseif(isset($_SESSION['pin'])){
         $pin = $_SESSION['pin'];
        } else{
      $_SESSION['error_check'] = "Missing pin from QRExamCheck";
	  header('Location: QRexam_closed.php');
	  return;   
    }
    $_SESSION['pin'] = $pin;
     
      if (isset($_POST['iid'])){
        $iid = $_POST['iid'];
        } elseif($_GET['iid']){
         $iid = $_GET['iid'];
        } elseif(isset($_SESSION['iid'])){
         $iid = $_SESSION['iid'];
        } else{
       $_SESSION['error_check'] = "Missing iid from QRExamCheck";
	  header('Location: QRexam_closed.php');
	  return;   
    }
    $_SESSION['iid'] = $iid;
      
          $_SESSION['pin'] = $pin;
     
      if (isset($_POST['dex'])){
        $dex = $_POST['dex'];
        } elseif($_GET['dex']){
         $dex = $_GET['dex'];
        } elseif(isset($_SESSION['dex'])){
         $dex = $_SESSION['dex'];
        } else{
       $_SESSION['error_check'] = "Missing dex from QRExamCheck";
	  header('Location: QRexam_closed.php');
	  return;   
    }
    $_SESSION['dex'] = $dex;
     
      if (isset($_POST['eactivity_id'])){
        $eactivity_id = $_POST['eactivity_id'];
        
        } elseif($_GET['eactivity_id']){
        
            
                
               $eactivity_id = $_GET['eactivity_id'];


 
        } elseif(isset($_SESSION['eactivity_id'])){
         $eactivity_id = $_SESSION['eactivity_id'];
        } else{
       $_SESSION['error_check'] = "Missing eactivity_id from QRExamCheck";
	  header('Location: QRexam_closed.php');
	  return;   
    }

   // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ Session
    $_SESSION['eactivity_id'] = $eactivity_id;
  	$_SESSION['wrote_try_flag']=false;


   // get the 
       
        // initialize a few variables
    	$score = 0;
			$PScore = 0;
			$count = 0;
      $count_tot = 0;

  // Initialize all of the arrays we need to zero out
  $i = 0;
      foreach(range('a','j') as $v){
       $resp[$v] =0;
       $old_resp[$v] = 0;  // don't ask why this is not an associative array
        $corr[$v] ="";
        $corr_num[$v] =0;
        $unit[$v] = "";
        $tol[$v] = 0;
        $tol_type[$v] =0;  // tol type zero is relative error 
        $wrongCount[$i]=0; 	// accumulates how many times they missed a part
        $changed[$i]=false;		// 1 if they changed their response ero otherwise
        $i++;
    }
			$partsFlag = array();
			$ansFormat=array('ans_a' =>"",'ans_b' =>"",	'ans_c' =>"",'ans_d' =>"",'ans_e' =>"",'ans_f' =>"",	'ans_g' =>"",'ans_h' =>"",'ans_i' =>"",'ans_j'=>"");
			

			$tol_key=array_keys($tol);
			$tol_type_key=array_keys($tol_type);
			$resp_key=array_keys($resp);
			$corr_key=array_keys($corr);
			$ansFormat_key=array_keys($ansFormat);
      
      
// these need to be read in +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

			$time_sleep1 = 15;  // time delay in seconds
			$time_sleep1_trip = 3;  // number of trials it talkes to trip the time delay
			$time_sleep2 = 60;  // additional time if hit the next limit
      $time_sleep2_trip = 6;	
    	$hintLimit = 3;
			$dispBase = 1;
     
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// see if the problem has been suspended	
				
			$stmt = $pdo->prepare("SELECT * FROM Problem where problem_id = :problem_id");
			$stmt->execute(array(":problem_id" => $problem_id));
			$row = $stmt -> fetch();
			if ( $row === false ) {
				$_SESSION['error'] = 'Bad value for problem_id';
				header( 'Location: QRexam_closed.php' ) ;
				return;
			}	
			$probData=$row;	
			
			$probStatus = $probData['status'];
			if ($probStatus =='suspended'){
				$_SESSION['error'] = 'problem has been suspended, check back later';
				header( 'Location: QRexam_closed.php' ) ;
				return;	
			}
	
			// get the tolerances and if the part has any hintfile	
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
			// +++++++++++++++++++++++++ fix this array slice bs +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			
		
			// Next check the Qa table and see which values have non null values - for those 
			$probParts=0;
			$stmt = $pdo->prepare("SELECT * FROM Qa where problem_id = :problem_id AND dex = :dex");
			$stmt->execute(array(":problem_id" => $problem_id, ":dex" => $dex));
			$row = $stmt -> fetch();
			if ( $row === false ) {
				$_SESSION['error_check'] = 'Bad value for problem_id';
				header( 'Location: QRexam_closed.php' ) ;
				return;
			}	
				$soln = array_slice($row,6,20); // this would mean the database table Qa would have the same structure - change the structure of the table and you break the code
			// +++++++++++++++++++++++++ fix this array slice bs +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

			for ($i = 0;$i<=9; $i++){  
				if ($soln[$i]>=1.2e43 && $soln[$i] < 1.3e43) {
					$partsFlag[$i]=false;
				} else {
					$probParts = $probParts+1;
					$partsFlag[$i]=true;
				}
			}
      
    //  var_dump($partsFlag); 

       $sql = 'SELECT Eactivity.created_at AS created_at, last_name, first_name, eexamnow_id, Eactivity.updated_at AS updated_at
                FROM `Eactivity`
                LEFT JOIN 
                     Student ON Student.student_id = Eactivity.student_id
                WHERE eactivity_id = :eactivity_id'; 
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(":eactivity_id" => $eactivity_id));
            $row = $stmt -> fetch();
            $eexamnow_id = $row['eexamnow_id'];
            $suspend_flag = 0;
            $created_at = $row['created_at'];
            $stu_name = $row['first_name'].' '.$row['last_name'];
            $updated_at = $row['updated_at'];
 // echo 'eexamnow_id: '.$eexamnow_id;
     
     $sql = 'SELECT * FROM  Eexamnow
     WHERE  eexamnow_id = :eexamnow_id';
       $stmt = $pdo->prepare($sql);
       $stmt->execute(array(':eexamnow_id' => $eexamnow_id));
       $eexamnow_data = $stmt -> fetch();
       if ($eexamnow_data == false){
        $_SESSION['error_check'] = 'eexamnow not defined in QEExamCheck';
            header('Location: QRexam_closed.php');
          return;     
      }
  //    echo ('eexamnow_id: '.$eexamnow_id); 
 //      echo ('eactivity_id: '.$eactivity_id);
      $globephase = $eexamnow_data['globephase'];
    //   echo ('globephase: '.$globephase);
    
       $eexamtime_id = $eexamnow_data['eexamtime_id'];
       $exam_code = $eexamnow_data['exam_code'];
 
     
     $sql = ' SELECT * 
            FROM  Eexamtime 
            WHERE  eexamtime_id = :eexamtime_id';
          $stmt = $pdo->prepare($sql);
  	    	$stmt->execute(array(":eexamtime_id" => $eexamtime_id));
		  	 $eexamtime_data = $stmt -> fetch();
            if ($eexamtime_data == false){
              $_SESSION['error_check'] = 'phase is not for active exam eexamnow_id ='.$eexamnow_id;
                  header('Location: QRexam_closed.php');
                return;     
            }


            $work_flow = $eexamtime_data['work_flow'];
            $attempt_type = $eexamtime_data['attempt_type'];
             $num_attempts = $eexamtime_data['num_attempts'];
            $ans_n = $eexamtime_data['ans_n'];
            $ans_t = $eexamtime_data['ans_t'];             
            if ($globephase != 1){
              $_SESSION['error_check'] = 'phase indicates exam is over.  globephae = '.$globephase. ' eexamnow_id is '.$eexamnow_id;

                header('Location: QRexam_closed.php');
                return;    
            }
     // keep track of the number of tries the student makes
	// get the count from the eactivity table
  
 /*   if(is_null($count)){   // first time no tries initialise count and wrong count
		$count = 0;
   }



 */

  // if there are entries in the eresp table use those for the old response



 if($dex !=1 ) {

    $sql = 'SELECT DISTINCT part_name FROM Eresp WHERE eactivity_id = :eactivity_id';
          $stmt = $pdo->prepare($sql);
          $stmt ->execute(array(
          ':eactivity_id' => $eactivity_id,
          ));
          $part_names = $stmt ->fetchAll();
          if ($part_names != false){
            foreach($part_names as $part_name){
              $sql2 = 'SELECT part_name, resp_value FROM Eresp WHERE part_name = :part_name AND eactivity_id = :eactivity_id ORDER BY eresp_id DESC LIMIT 1';
              $stmt = $pdo->prepare($sql2);
              $stmt ->execute(array(
                ':eactivity_id' => $eactivity_id,
                ':part_name' => $part_name['part_name'],
              ));
              $old_resp_data = $stmt ->fetch();
              $old_resp[$old_resp_data['part_name']] = $old_resp_data['resp_value'];
            }
      }

    // check if they have selected the answer on any parts also look for Wrong counts
      $sql = 'SELECT * FROM Eactivity WHERE eactivity_id = :eactivity_id ';
        $stmt = $pdo->prepare($sql);
        $stmt ->execute(array(
          ':eactivity_id' => $eactivity_id,
          ));
          $eactivity_data = $stmt ->fetch();
          $count_tot = $eactivity_data['count_tot'];
         
          if (is_null($count_tot)){
            $count_tot = 0;
          }
        
        $i = 0;
          foreach(range('a','j') as $v){
              $display_ans[$i] = $eactivity_data['display_ans_'.$v];
              $wrongCount[$i] = $eactivity_data['wcount_'.$v];
              $corr_num[$v] = $eactivity_data['correct_'.$v];
              if($corr_num[$v] ==1){$corr[$v] = 'Correct';} // else {$corr[$v] = 'Not Correct';}
            $i++;
            }
  }  
  else {
// Do the same thing as above for is this is the basecase


    $sql = 'SELECT DISTINCT part_name FROM Ebc_resp WHERE eactivity_id = :eactivity_id';
    $stmt = $pdo->prepare($sql);
    $stmt ->execute(array(
    ':eactivity_id' => $eactivity_id,
    ));
    $part_names = $stmt ->fetchAll();
    if ($part_names != false){
      foreach($part_names as $part_name){
        $sql2 = 'SELECT part_name, resp_value FROM Ebc_resp WHERE part_name = :part_name AND eactivity_id = :eactivity_id ORDER BY ebc_resp_id DESC LIMIT 1';
        $stmt = $pdo->prepare($sql2);
        $stmt ->execute(array(
          ':eactivity_id' => $eactivity_id,
          ':part_name' => $part_name['part_name'],
        ));
        $old_resp_data = $stmt ->fetch();
        $old_resp[$old_resp_data['part_name']] = $old_resp_data['resp_value'];
      }
}


$sql = 'SELECT * FROM Eactivity WHERE eactivity_id = :eactivity_id ';
  $stmt = $pdo->prepare($sql);
  $stmt ->execute(array(
    ':eactivity_id' => $eactivity_id,
    ));
    $eactivity_data = $stmt ->fetch();

    /* 
    $count_tot = $eactivity_data['count_tot'];
    if (is_null($count_tot)){
      $count_tot = 0;
    }

 */
  $i = 0;
    foreach(range('a','j') as $v){
        $display_ans[$i] = $eactivity_data['display_bc_ans_'.$v];
        $wrongCount[$i] = $eactivity_data['wcount_bc_'.$v];
        $corr_num[$v] = $eactivity_data['bc_correct_'.$v];
        if($corr_num[$v] ==1){$corr[$v] = 'Correct';} // else {$corr[$v] = 'Not Correct';}
      $i++;
      }

  }      

// var_dump($old_resp);
    $i = 0;
    if ($get_flag == 1){   // coming in on a get (not a recheck) but either from a refresh or coming back to problem from qrdisplayPblm)
        foreach(range('a','j') as $v){
          //  echo ('i: '.$i);
            if( $partsFlag[$i] && isset($old_resp[$v])){ 
              $resp[$v]=$old_resp[$v];
            }
            $i++;
         }
    }


/* 

    $sql = 'SELECT * FROM Eresp WHERE eresp_id IN (SELECT max(eresp_id) From Eresp WHERE eactivity_id = :eactivity_id Group BY part_name)'; // this took longer than it should have to come up with - I hope it works
         $stmt = $pdo->prepare($sql);
         $stmt ->execute(array(
          ':eactivity_id' => $eactivity_id,
        ));
        $old_resp_data = $stmt ->fetchAll();
        if ($old_resp_data != false){
          foreach($old_resp_data as $old_resp_datum){
            $old_resp[$old_resp_datum['part_name']]=$old_resp_datum['resp_value'];
          }
        }

    */     
       


// $resp_data = $stmt -> fetch();
 // var_dump($get_flag);



  // this is the big if that was taken from the QRchecker2.php for the homework 
if( $get_flag ==0){ // if we are comming in from this file on a post
    // get the old repsonses from the response table check to see which ones have changed and 
     
  //    $changed_flag = false;
  
      $changed_flag = false;  // this will eventually counts the number of times they hit check and have changed at least one value
      $switch_to_bc = 0;

  // get the values from the post and see if they have changed and if they have put them in the resp table

  $i =0;
  foreach(range('a','j') as $v){
      if( $partsFlag[$i]){ 
        $resp[$v]=(float)$_POST[$v]+0.0;
        if(isset($old_resp[$v]) && isset($resp[$v])){
          if($_POST[$v]==$old_resp[$v] ){
              $changed[$i]= false;
          } else { 
            $changed[$i]=true;
            $changed_flag = true;


            if ($dex !=1) {


                $sql = 'INSERT INTO Eresp (eactivity_id, resp_value,part_name) VALUES (:eactivity_id, :resp_value, :part_name)';
                $stmt = $pdo->prepare($sql);
                $stmt ->execute(array(
                    ':eactivity_id' => $eactivity_id,
                    ':resp_value' => $resp[$v],
                    ':part_name' => $v
                ));
                // this next part gets the time that they have been working on the problem and probably belongs somewhere elseif
                $sql = 'SELECT UNIX_TIMESTAMP(`created_at`) AS created_at FROM Eresp WHERE eactivity_id = :eactivity_id AND part_name = :part_name ORDER BY eresp_id DESC LIMIT 1';
                  $stmt = $pdo->prepare($sql);
                  $stmt ->execute(array(
                    ':eactivity_id' => $eactivity_id,
                    ':part_name' => $v,
                  ));
                  $original_dates = $stmt -> fetch();                
                  $last_date = $original_dates['created_at'];
                
                // get the time they have been working on this part from the Eresp table  - this is sorted in ascending order and the one above descending 
                $sql = 'SELECT UNIX_TIMESTAMP(`created_at`) AS created_at FROM Eresp WHERE eactivity_id = :eactivity_id AND part_name = :part_name ORDER BY eresp_id ASC LIMIT 1';
                $stmt = $pdo->prepare($sql);
                $stmt ->execute(array(
                    ':eactivity_id' => $eactivity_id,
                    ':part_name' => $v,
                  ));
                  $original_dates = $stmt -> fetch();                
                  $first_date = $original_dates['created_at'];
                  
                if (is_numeric($last_date) && is_numeric($first_date))
                {$diff_time_min = round(($last_date - $first_date)/60);} else {$diff_time_min=0;}
             } else {


              $sql = 'INSERT INTO Ebc_resp (eactivity_id, resp_value,part_name) VALUES (:eactivity_id, :resp_value, :part_name)';
              $stmt = $pdo->prepare($sql);
              $stmt ->execute(array(
                  ':eactivity_id' => $eactivity_id,
                  ':resp_value' => $resp[$v],
                  ':part_name' => $v
              ));
              // this next part gets the time that they have been working on the basecase and probably belongs somewhere elseif
              $sql = 'SELECT UNIX_TIMESTAMP(`created_at`) AS created_at FROM Ebc_resp WHERE eactivity_id = :eactivity_id AND part_name = :part_name ORDER BY ebc_resp_id DESC LIMIT 1';
                $stmt = $pdo->prepare($sql);
                $stmt ->execute(array(
                  ':eactivity_id' => $eactivity_id,
                  ':part_name' => $v,
                ));
                $original_dates = $stmt -> fetch();                
                $last_date = $original_dates['created_at'];
              
              // get the time they have been working on this part from the Eresp table  - this is sorted in ascending order and the one above descending 
              $sql = 'SELECT UNIX_TIMESTAMP(`created_at`) AS created_at FROM Ebc_resp WHERE eactivity_id = :eactivity_id AND part_name = :part_name ORDER BY ebc_resp_id ASC LIMIT 1';
              $stmt = $pdo->prepare($sql);
              $stmt ->execute(array(
                  ':eactivity_id' => $eactivity_id,
                  ':part_name' => $v,
                ));
                $original_dates = $stmt -> fetch();                
                $first_date = $original_dates['created_at'];
                
              if (is_numeric($last_date) && is_numeric($first_date))
              {$diff_time_min = round(($last_date - $first_date)/60);} else {$diff_time_min=0;}


             }



/* 
              // this is the logic to see if we should go to the basecase because of taking too much time or getting too many wrong
              if($work_flow == 'bc_if' && $count_data >= $p_bc_n && $diff_time_min > $p_bc_t && $activity_data["bc_correct_".$v] != 1)
              {$go_to_bc[$i] = 1; $switch_to_bc = 1;} else {$go_to_bc[$i] = 0;}   

 */              
          }
        }
      }
      $i++;
   }
   if ($changed_flag){
        $count_tot++;
    }

// See whcih ones they got correct

 
// var_dump($changed); echo('<br><br>');
// echo ' changed flag'.$changed_flag.'<br>';
// echo ' count_tot'.$count_tot.'<br>';
//var_dump($wrongCount); echo('<br><br>');
//var_dump($soln); echo('<br><br>');
// var_dump($resp); echo('<br><br>');
//var_dump($old_resp) ;echo('<br><br>');
 
		for ($j=0; $j<=9; $j++) {

			if($changed[$j] ) {

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
              else  // got it wrong 
              {
                    $wrongCount[$j] = $wrongCount[$j]+1;
                    $corr_num[$corr_key[$j]]=0;
                  $corr[$corr_key[$j]]='Not Correct';
              }
		    	}
		}
     

		
        $num_score_possible = 0;
        $PScore=0; 
         foreach(range('a','j') as $x){ 
         $PScore = $PScore + ($corr_num[$x]*$eexamtime_data['perc_'.$x.'_'.$alias_num]);
          $num_score_possible = $num_score_possible + $eexamtime_data['perc_'.$x.'_'.$alias_num];
         }
   
  if ($dex != 1)  {     
    
     $sql ='UPDATE `Eactivity` SET `score` = :score, `count_tot` = :count_tot, correct_a = :correct_a,correct_b = :correct_b,correct_c = :correct_c,
                                      correct_d = :correct_d,correct_e = :correct_e,correct_f = :correct_f,correct_g = :correct_g,correct_h = :correct_h,
                                      correct_i = :correct_i,correct_j = :correct_j, wcount_a = :wcount_a, wcount_b = :wcount_b, wcount_c = :wcount_c,
                                       wcount_d = :wcount_d, wcount_e = :wcount_e, wcount_f = :wcount_f, wcount_g = :wcount_g, wcount_h = :wcount_h,
                                       wcount_i = :wcount_i, wcount_j = :wcount_j,
                                       switch_to_bc = :switch_to_bc, P_num_score_net = :P_num_score_net
                              WHERE eactivity_id = :eactivity_id';
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute(array(
                ':score' => $score,
                ':count_tot' => $count_tot,
                ':eactivity_id' => $eactivity_id,
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
                ':wcount_a' => $wrongCount[0],
                ':wcount_b' => $wrongCount[1],
                ':wcount_c' => $wrongCount[2],
                ':wcount_d' => $wrongCount[3],
                ':wcount_e' => $wrongCount[4],
                ':wcount_f' => $wrongCount[5],
                ':wcount_g' => $wrongCount[6],
                ':wcount_h' => $wrongCount[7],
                ':wcount_i' => $wrongCount[9],
                ':wcount_j' => $wrongCount[9],
                ':switch_to_bc' => $switch_to_bc,
                ':P_num_score_net' => $PScore,
                 ));
    
    
//		$_SESSION['points']=$score; // this will cause problems if running multiple browser windows on the same machine - testing on localhost
    $corr_num_st = implode(",",$corr_num);
    
   } else {  
     
    // record the base case info
      $sql ='UPDATE `Eactivity` SET  bc_correct_a = :bc_correct_a,bc_correct_b = :bc_correct_b,bc_correct_c = :bc_correct_c,
      bc_correct_d = :bc_correct_d,bc_correct_e = :bc_correct_e,bc_correct_f = :bc_correct_f,bc_correct_g = :bc_correct_g,bc_correct_h = :bc_correct_h,
      bc_correct_i = :bc_correct_i,bc_correct_j = :bc_correct_j, wcount_bc_a = :wcount_bc_a, wcount_bc_b = :wcount_bc_b, wcount_bc_c = :wcount_bc_c,
      wcount_bc_d = :wcount_bc_d, wcount_bc_e = :wcount_bc_e, wcount_bc_f = :wcount_bc_f, wcount_bc_g = :wcount_bc_g, wcount_bc_h = :wcount_bc_h,
      wcount_bc_i = :wcount_bc_i, wcount_bc_j = :wcount_bc_j,
      switch_to_bc = :switch_to_bc, P_num_score_net_bc = :P_num_score_net_bc
            WHERE eactivity_id = :eactivity_id';
            $stmt = $pdo -> prepare($sql);
            $stmt -> execute(array(
          
          
            ':eactivity_id' => $eactivity_id,
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
            ':wcount_bc_a' => $wrongCount[0],
            ':wcount_bc_b' => $wrongCount[1],
            ':wcount_bc_c' => $wrongCount[2],
            ':wcount_bc_d' => $wrongCount[3],
            ':wcount_bc_e' => $wrongCount[4],
            ':wcount_bc_f' => $wrongCount[5],
            ':wcount_bc_g' => $wrongCount[6],
            ':wcount_bc_h' => $wrongCount[7],
            ':wcount_bc_i' => $wrongCount[9],
            ':wcount_bc_j' => $wrongCount[9],
            ':switch_to_bc' => $switch_to_bc,
            ':P_num_score_net_bc' => $PScore,
            ));


          //       $_SESSION['points']=$score; // this will cause problems if running multiple browser windows on the same machine - testing on localhost
          //          $corr_num_st = implode(",",$corr_num);



   }


/*         
      // this was from the old exam checker 
    $stmt = $pdo->prepare("UPDATE `Eactivity` SET ".$trynum_pblm." = :trynum_pblm,".$response_key." = :response_key WHERE eactivity_id = :eactivity_id ");
    $stmt->execute(array(
          ":eactivity_id" => $eactivity_id,
          ":trynum_pblm" => $count,
           ":response_key" => $corr_num_st, 
          ));     
    
   */  
    
    // time delay on total tries for the problem - try this in the JS
    
            
    }
  





















/* 
    
 if( $get_flag ==0){ // if we are comming in from this file
 
     $count = $count + 1;
    
	// read the student responses into an array
		@$resp['a']=$_POST['a']+0;
		@$resp['b']=$_POST['b']+0;
		@$resp['c']=$_POST['c']+0;
		@$resp['d']=$_POST['d']+0;
		@$resp['e']=$_POST['e']+0;
		@$resp['f']=$_POST['f']+0;
		@$resp['g']=$_POST['g']+0;
		@$resp['h']=$_POST['h']+0;
		@$resp['i']=$_POST['i']+0;
		@$resp['j']=$_POST['j']+0;
	
		
	//}	 
		for ($j=0; $j<=9; $j++) {
			if($partsFlag[$j]) {
					if($soln[$j]==0){  // take care of the zero solution case
						$sol=1;
					} else {
						$sol=$soln[$j];
					}	
					
					if(	abs(($soln[$j]-(float)$resp[$resp_key[$j]])/$sol)<= $tol[$tol_key[$j]]) {
								
								
										$corr_num[$corr_key[$j]]=1;
										$corr[$corr_key[$j]]='Correct';
										$score=$score+1;
										$_SESSION['$wrongC'[$j]] = 0;
										$wrongCount[$j]=0;
												
								}
					else  // got it wrong or did not attempt
					{
							
								if(!(isset($_SESSION['wrongC'[$j]])))  // needs initialized
								{
									
									$_SESSION['$wrongC'[$j]] = 0;
									$wrongCount[$j]=0;
								
									
								}
								elseif ($resp[$resp_key[$j]]==0)  // did not attempt it
								{
									
									@$wrongCount[$j] = ($_SESSION['wrongC'[$j]]);
                                    $corr_num[$corr_key[$j]]=0;
									$corr[$corr_key[$j]]='';
								}
								else  // response is equal to zero so probably did not answer (better to use POST value I suppose - fix later
								{
									$wrongCount[$j] = ($_SESSION['wrongC'[$j]])+1;
									$_SESSION['wrongC'[$j]] = $wrongCount[$j];
                                        $corr_num[$corr_key[$j]]=0;
										$corr[$corr_key[$j]]='Not Correct';
								}
					}		
			}
		}

		
		$PScore=$score/$probParts*100;  
	
    $sql ='UPDATE `Eactivity` SET `score` = :score, `count_tot` = :count_tot, correct_a = :correct_a,correct_b = :correct_b,correct_c = :correct_c,correct_d = :correct_d,correct_e = :correct_e,correct_f = :correct_f,correct_g = :correct_g,correct_h = :correct_h,correct_i = :correct_i,correct_j = :correct_j, switch_to_bc = :switch_to_bc
                              WHERE eactivity_id = :eactivity_id';
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute(array(
                ':score' => $score,
                ':count_tot' => $count_tot,
                ':eactivity_id' => $eactivity_id,
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
        
      
           $stmt = $pdo->prepare("UPDATE `eactivity` SET ".$trynum_pblm." = :trynum_pblm,".$response_key." = :response_key WHERE eactivity_id = :eactivity_id ");
			$stmt->execute(array(
            ":eactivity_id" => $eactivity_id,
            ":trynum_pblm" => $count,
             ":response_key" => $corr_num_st, 
            ));
            
    }
   */

   









  
	?>
	</table>



	<!DOCTYPE html>
	<html lang = "en">
	<head>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<meta Charset = "utf-8">
	<title>QRExamCheck</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" /> 
		<link rel="stylesheet" type="text/css" href="jquery.countdown.css"> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript" src="jquery.plugin.js"></script> 
		<script type="text/javascript" src="jquery.countdown.js"></script>
		

		<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
		
	</head>
<style>
.btn-default {
       position: absolute;
        right: 0;
       
}
</style>
	<body>
	<header>
<?php

if ($dex == 1) {
  echo('<h2>Quick Response Exam Base-Case Checker</h2>');
} else{
  echo('<h2>Quick Response Exam Problem Checker</h2>');
  
}

?>

	
	</header>
	<main>
	<!-- <h3> Name: <?php echo($stu_name);?> &nbsp; &nbsp; Exam Number: <?php echo($exam_num);?>&nbsp; &nbsp;    Max Attempts: <?php if ($attempt_type==1){echo('infinite');}else{echo($num_attempts);} ?>  </h3> -->
	<h3> Name: <?php echo($stu_name);?> &nbsp; &nbsp; Exam Number: <?php echo($exam_num);?>&nbsp; &nbsp;    Max Attempts: <?php if ($attempt_type==1){echo('infinite');}else{echo($num_attempts);} ?>  </h3>
  

	<font size = "1"> Problem Number: <?php echo ($problem_id) ?> -  <?php echo ($dex) ?> </font>
    <!--  <font size = "2"> ans_t: <?php echo ($ans_t); ?> </font>
    <font size = "2"> ans_n: <?php echo ($ans_n); ?> </font>
    <font size = "2"> name: <?php echo ($name); ?> </font>
   
  <font size = "2"> Get_Flag: <?php echo ($get_flag) ?> </font>
      <font size = "2"> oldresp_flag: <?php echo ($oldresp_flag) ?> </font> 
       -->     
      <font size = "2"> eactivity_id: <?php echo ($eactivity_id) ?> </font> 
 

	<form autocomplete="off" id = "check_form" method="POST" >
	<!--<p><font color=#003399>Index: </font><input type="text" name="dex_num" size=3 value="<?php echo (htmlentities($_SESSION['index']))?>"  ></p> -->

	<?php
    if($attempt_type ==1 || ($attempt_type ==2 && $count <= $num_attempts)){
	if ($partsFlag[0]){ ?> 

	<p> <span id = "input_a"> a) <?php if($dex!=1) { echo '('. $eexamtime_data['perc_a_'.$alias_num].'%):';} ?> <input [ type=number]{width: 5%;} id = "a" name="a" size = 10% value="<?php echo (htmlentities($resp['a']))?>" > <?php echo($unit[0]) ?> &nbsp - <b><?php echo ($corr['a']) ?> </b> &nbsp; count <?php echo(@$wrongCount[0].' ');?> </span><span id = "disp_ans_a"></span>
	<?php //echo ('wrongcount: '.$wrongCount[0]); ?>
	<?php if (isset($_POST['pin']) && @$wrongCount[0]>$hintLimit && $corr['a']=="Not Correct" && $hintaPath != "uploads/default_hints.html" ){echo '<a href="'.$hintaPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) && $changed[0] && @$wrongCount[0]>$time_sleep1_trip && @$wrongCount[0]< $time_sleep2_trip && $corr['a']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) && $changed[0] && @$wrongCount[0]>=$time_sleep2_trip && $corr['a']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	 <input type="hidden" id="ans_a" value="<?php echo ($soln[0])?>" >
     	 <input type="hidden" id="display_ans_a" value="<?php echo ($display_ans[0])?>" >
       
	<?php 
    if ( $ans_n!="" && $ans_n!=""&& $corr['a']!="Correct" )
     { echo('<span class="btn-default">&nbsp;&nbsp;&nbsp;<input type = "checkbox" id = "show_answer_check_a" class = "show_answer_check" >&nbsp;&nbsp;&nbsp;</input><input type="button" id="show_answer_button_a" disabled value="Show Answer"></input> </span>&nbsp;');}
     if ($corr['a']=="Correct")
    {echo '<span id = "show_ans_a" class = "show_ans"> - Computed value is: '.$soln[0].'</span>';} 
    } 
    echo '</p>';
    

	if ($partsFlag[1]){ ?> 
	<p>  <span id = "input_b"> b)<?php if($dex!=1) { echo '('. $eexamtime_data['perc_b_'.$alias_num].'%):';} ?><input [ type=number]{width: 5%;} id = "b" name="b" size = 10% value="<?php echo (htmlentities($resp['b']))?>" > <?php echo($unit[1]) ?> &nbsp - <b> <?php echo ($corr['b']) ?> </b> &nbsp; count <?php echo(@$wrongCount[1].' ');?>  </span><span id = "disp_ans_b"></span>
	<?php // if (isset($_POST['pin']) and $corr['b']=="Correct" ){echo '- Computed value is: '.$soln[1];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[1]>$hintLimit and $corr['b']=="Not Correct" && $hintbPath != "uploads/default_hints.html" ){echo '<a href="'.$hintbPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[1] and @$wrongCount[1]>$time_sleep1_trip and @$wrongCount[1]< $time_sleep2_trip and $corr['b']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[1] and @$wrongCount[1]>=$time_sleep2_trip and $corr['b']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	 <input type="hidden" id="ans_b" value="<?php echo ($soln[1])?>" >
     	 <input type="hidden" id="display_ans_b" value="<?php echo ($display_ans[1])?>" >
        
	<?php 
    if ( $ans_n!="" && $ans_n!=""&& $corr['b']!="Correct" )
     { echo('<span class="btn-default">&nbsp;&nbsp;&nbsp;<input type = "checkbox" id = "show_answer_check_b" class = "show_answer_check" >&nbsp;&nbsp;&nbsp;</input><input type="button" id="show_answer_button_b" disabled value="Show Answer"></input> </span>&nbsp;');}
     if ($corr['b']=="Correct")
    {echo '<span id = "show_ans_b" class = "show_ans"> - Computed value is: '.$soln[1].'</span>';} 
    } 
    echo '</p>';
  
	if ($partsFlag[2]){ ?> 
	<p>  <span id = "input_c"> c)<?php if($dex!=1) { echo '('. $eexamtime_data['perc_c_'.$alias_num].'%):';} ?>  <input [ type=number]{width: 5%;}  id = "c" name="c" size = 10% value="<?php echo (htmlentities($resp['c']))?>" > <?php echo($unit[2]) ?> &nbsp - <b><?php echo ($corr['c']) ?> </b> &nbsp;  count <?php echo(@$wrongCount[2].' ');?>  </span><span id = "disp_ans_c"></span>
	<?php if (isset($_POST['pin']) and @$wrongCount[2]>$hintLimit and $corr['c']=="Not Correct"&& $hintcPath != "uploads/default_hints.html" ){echo '<a href="'.$hintcPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[2] and @$wrongCount[2]>$time_sleep1_trip and @$wrongCount[2]< $time_sleep2_trip and $corr['c']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[2] and @$wrongCount[2]>=$time_sleep2_trip and $corr['c']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	 <input type="hidden" id="ans_c" value="<?php echo ($soln[2])?>" >
     	 <input type="hidden" id="display_ans_c" value="<?php echo ($display_ans[2])?>" >
        
	<?php 
    if ( $ans_n!="" && $ans_n!=""&& $corr['c']!="Correct" )
     { echo('<span class="btn-default">&nbsp;&nbsp;&nbsp;<input type = "checkbox" id = "show_answer_check_c" class = "show_answer_check" >&nbsp;&nbsp;&nbsp;</input><input type="button" id="show_answer_button_c"  disabled value="Show Answer"> </span>&nbsp;');}
     if ($corr['c']=="Correct")
    {echo '<span id = "show_ans_c" class = "show_ans"> - Computed value is: '.$soln[2].'</span>';} 
    } 
    echo '</p>';

	if ($partsFlag[3]){ ?> 
	<p>  <span id = "input_d"> d)<?php if($dex!=1) { echo '('. $eexamtime_data['perc_d_'.$alias_num].'%):';} ?>  <input [ type=number]{width: 5%;} id = "d" name="d" size = 10% value="<?php echo (htmlentities($resp['d']))?>" > <?php echo($unit[3]) ?> &nbsp - <b><?php echo ($corr['d']) ?> </b> &nbsp; count <?php echo(@$wrongCount[3].' ');?>  </span><span id = "disp_ans_d"></span>
	<?php if (isset($_POST['pin']) and @$wrongCount[3]>$hintLimit and $corr['d']=="Not Correct"&& $hintdPath != "uploads/default_hints.html" ){echo '<a href="'.$hintdPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[3] and @$wrongCount[3]>$time_sleep1_trip and @$wrongCount[3]< $time_sleep2_trip and $corr['d']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[3] and @$wrongCount[3]>=$time_sleep2_trip and $corr['d']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	 <input type="hidden" id="ans_d" value="<?php echo ($soln[3])?>" >
     	 <input type="hidden" id="display_ans_d" value="<?php echo ($display_ans[3])?>" >
        
	<?php 
    if ( $ans_n!="" && $ans_n!=""&& $corr['d']!="Correct" )
     { echo('<span class="btn-default">&nbsp;&nbsp;&nbsp;<input type = "checkbox" id = "show_answer_check_d" class = "show_answer_check" >&nbsp;&nbsp;&nbsp;</input><input type="button" id="show_answer_button_d" disabled value="Show Answer"> </span>&nbsp;');}
     if ($corr['d']=="Correct")
    {echo '<span id = "show_ans_d" class = "show_ans"> - Computed value is: '.$soln[3].'</span>';} 
    } 
    echo '</p>';

	if ($partsFlag[4]){ ?> 
	<p>  <span id = "input_e"> e)<?php if($dex!=1) { echo '('. $eexamtime_data['perc_e_'.$alias_num].'%):';} ?>  <input [ type=number]{width: 5%;} id = "e" name="e" size = 10% value="<?php echo (htmlentities($resp['e']))?>" > <?php echo($unit[4]) ?> &nbsp - <b><?php echo ($corr['e']) ?> </b> &nbsp; count <?php echo(@$wrongCount[4].' ');?>  </span><span id = "disp_ans_e"></span>
	<?php if (isset($_POST['pin']) and @$wrongCount[4]>$hintLimit and $corr['e']=="Not Correct"&& $hintePath != "uploads/default_hints.html" ){echo '<a href="'.$hintePath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[4] and @$wrongCount[4]>$time_sleep1_trip and @$wrongCount[4]< $time_sleep1_trip and $corr['e']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[4] and @$wrongCount[4]>=$time_sleep2_trip and $corr['e']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	 <input type="hidden" id="ans_e" value="<?php echo ($soln[4])?>" >
     	 <input type="hidden" id="display_ans_e" value="<?php echo ($display_ans[4])?>" >
        
	<?php 
    if ( $ans_n!="" && $ans_n!=""&& $corr['e']!="Correct" )
     { echo('<span class="btn-default">&nbsp;&nbsp;&nbsp;<input type = "checkbox" id = "show_answer_check_e" class = "show_answer_check" >&nbsp;&nbsp;&nbsp;</input><input type="button" id="show_answer_button_e" disabled value="Show Answer"> </span>&nbsp;');}
     if ($corr['e']=="Correct")
    {echo '<span id = "show_ans_e" class = "show_ans"> - Computed value is: '.$soln[4].'</span>';} 
    } 
    echo '</p>';

	if ($partsFlag[5]){ ?> 
	<p>  <span id = "input_f"> f)<?php if($dex!=1) { echo '('. $eexamtime_data['perc_f_'.$alias_num].'%):';} ?>  <input [ type=number]{width: 5%;} id = "f" name="f" size = 10% value="<?php echo (htmlentities($resp['f']))?>" > <?php echo($unit[5]) ?> &nbsp - <b><?php echo ($corr['f']) ?> </b> &nbsp; count <?php echo(@$wrongCount[5].' ');?>  </span><span id = "disp_ans_f"></span>
	<?php if (isset($_POST['pin']) and @$wrongCount[5]>$hintLimit and $corr['f']=="Not Correct"&& $hintfPath != "uploads/default_hints.html" ){echo '<a href="'.$hintfPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[5] and @$wrongCount[5]>$time_sleep1_trip and @$wrongCount[5]< $time_sleep2_trip and $corr['f']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[5] and @$wrongCount[5]>=$time_sleep2_trip and $corr['f']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	 <input type="hidden" id="ans_f" value="<?php echo ($soln[5])?>" >
     	 <input type="hidden" id="display_ans_f" value="<?php echo ($display_ans[5])?>" >
        
	<?php 
    if ( $ans_n!="" && $ans_n!=""&& $corr['f']!="Correct" )
     { echo('<span class="btn-default">&nbsp;&nbsp;&nbsp;<input type = "checkbox" id = "show_answer_check_f" class = "show_answer_check" >&nbsp;&nbsp;&nbsp;</input><input type="button" id="show_answer_button_f" disabled value="Show Answer"> </span>&nbsp;');}
     if ($corr['f']=="Correct")
    {echo '<span id = "show_ans_f" class = "show_ans"> - Computed value is: '.$soln[5].'</span>';} 
    } 
    echo '</p>';

	if ($partsFlag[6]){ ?> 
	<p>  <span id = "input_g"> g)<?php if($dex!=1) { echo '('. $eexamtime_data['perc_g_'.$alias_num].'%):';} ?>  <input [ type=number]{width: 5%;} id = "g" name="g" size = 10% value="<?php echo (htmlentities($resp['g']))?>" > <?php echo($unit[6]) ?> &nbsp - <b><?php echo ($corr['g']) ?> </b> &nbsp; count <?php echo(@$wrongCount[6].' ');?>  </span><span id = "disp_ans_g"></span>
	<?php if (isset($_POST['pin']) and @$wrongCount[6]>$hintLimit and $corr['g']=="Not Correct"&& $hintgPath != "uploads/default_hints.html" ){echo '<a href="'.$hintgPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[6] and @$wrongCount[6]>$time_sleep1_trip and @$wrongCount[6]< $time_sleep2_trip and $corr['g']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[6] and @$wrongCount[6]>=$time_sleep2_trip and $corr['g']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
		 <input type="hidden" id="ans_g" value="<?php echo ($soln[6])?>" >
     	 <input type="hidden" id="display_ans_g" value="<?php echo ($display_ans[6])?>" >
        
	<?php 
    if ( $ans_n!="" && $ans_n!=""&& $corr['g']!="Correct" )
     { echo('<span class="btn-default">&nbsp;&nbsp;&nbsp;<input type = "checkbox" id = "show_answer_check_g" class = "show_answer_check" >&nbsp;&nbsp;&nbsp;</input><input type="button" id="show_answer_button_g" disabled value="Show Answer"> </span>&nbsp;');}
     if ($corr['g']=="Correct")
    {echo '<span id = "show_ans_g" class = "show_ans"> - Computed value is: '.$soln[6].'</span>';} 
    } 
    echo '</p>';


	if ($partsFlag[7]){ ?> 
	<p>  <span id = "input_h"> h)<?php if($dex!=1) { echo '('. $eexamtime_data['perc_h_'.$alias_num].'%):';} ?>  <input [ type=number]{width: 5%;} id = "h" name="h" size = 10% value="<?php echo (htmlentities($resp['h']))?>" > <?php echo($unit[7]) ?> &nbsp - <b><?php echo ($corr['h']) ?> </b> &nbsp;  count <?php echo(@$wrongCount[7].' ');?>  </span><span id = "disp_ans_h"></span>
	<?php if (isset($_POST['pin']) and @$wrongCount[7]>$hintLimit and $corr['h']=="Not Correct"&& $hinthPath != "uploads/default_hints.html" ){echo '<a href="'.$hinthPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[7] and @$wrongCount[7]>$time_sleep1_trip and @$wrongCount[7]< $time_sleep2_trip and $corr['h']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[7] and @$wrongCount[7]>=$time_sleep2_trip and $corr['h']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
		 <input type="hidden" id="ans_h" value="<?php echo ($soln[7])?>" >
     	 <input type="hidden" id="display_ans_h" value="<?php echo ($display_ans[7])?>" >
        
	<?php 
    if ( $ans_n!="" && $ans_n!=""&& $corr['h']!="Correct" )
     { echo('<span class="btn-default">&nbsp;&nbsp;&nbsp;<input type = "checkbox" id = "show_answer_check_h" class = "show_answer_check" >&nbsp;&nbsp;&nbsp;</input><input type="button" id="show_answer_button_h" disabled value="Show Answer"> </span>&nbsp;');}
     if ($corr['h']=="Correct")
    {echo '<span id = "show_ans_h" class = "show_ans"> - Computed value is: '.$soln[7].'</span>';} 
    } 
    echo '</p>';


	if ($partsFlag[8]){ ?> 
	<p>  <span id = "input_i"> i)<?php if($dex!=1) { echo '('. $eexamtime_data['perc_i_'.$alias_num].'%):';} ?> <input [ type=number]{width: 5%;} id = "i" name="i" size = 10% value="<?php echo (htmlentities($resp['i']))?>" > <?php echo($unit[8]) ?> &nbsp - <b><?php echo ($corr['i']) ?> </b> &nbsp; count <?php echo(@$wrongCount[8].' ');?>  </span><span id = "disp_ans_i"></span>
	<?php if (isset($_POST['pin']) and @$wrongCount[8]>$hintLimit and $corr['i']=="Not Correct"&& $hintiPath != "uploads/default_hints.html" ){echo '<a href="'.$hintiPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[8] and @$wrongCount[8]>$time_sleep1_trip and @$wrongCount[8]< $time_sleep2_trip and $corr['i']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[8] and @$wrongCount[8]>=$time_sleep2_trip and $corr['i']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
		 <input type="hidden" id="ans_i" value="<?php echo ($soln[8])?>" >
     	 <input type="hidden" id="display_ans_i" value="<?php echo ($display_ans[8])?>" >
        
	<?php 
    if ( $ans_n!="" && $ans_n!=""&& $corr['i']!="Correct" )
     { echo('<span class="btn-default">&nbsp;&nbsp;&nbsp;<input type = "checkbox" id = "show_answer_check_i" class = "show_answer_check" >&nbsp;&nbsp;&nbsp;</input><input type="button" id="show_answer_button_i" disabled value="Show Answer"> </span>&nbsp;');}
     if ($corr['i']=="Correct")
    {echo '<span id = "show_ans_i" class = "show_ans"> - Computed value is: '.$soln[8].'</span>';} 
    } 
    echo '</p>';


	if ($partsFlag[9]){ ?> 
	<p>  <span id = "input_j"> j)<?php if($dex!=1) { echo '('. $eexamtime_data['perc_j_'.$alias_num].'%):';} ?>  <input [ type=number]{width: 5%;}  id = "j" name="j" size = 10% value="<?php echo (htmlentities($resp['j']))?>" > <?php echo($unit[9]) ?> &nbsp - <b><?php echo ($corr['j']) ?> </b> &nbsp;  count <?php echo(@$wrongCount[9].' ');?>  </span><span id = "disp_ans_j"></span>
	<?php if (isset($_POST['pin']) and @$wrongCount[9]>$hintLimit and $corr['j']=="Not Correct"&& $hintjPath != "uploads/default_hints.html" ){echo '<a href="'.$hintjPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[9] and @$wrongCount[9]>$time_sleep1_trip and @$wrongCount[9]< $time_sleep2_trip and $corr['j']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[9] and @$wrongCount[9]>=$time_sleep2_trip and $corr['j']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
		 <input type="hidden" id="ans_j" value="<?php echo ($soln[9])?>" >
     	 <input type="hidden" id="display_ans_j" value="<?php echo ($display_ans[9])?>" >
        
	<?php 
    if ( $ans_n!="" && $ans_n!=""&& $corr['j']!="Correct" )
     { echo('<span class="btn-default">&nbsp;&nbsp;&nbsp;<input type = "checkbox" id = "show_answer_check_b" class = "show_answer_check" >&nbsp;&nbsp;&nbsp;</input><input type="button" id="show_answer_button_j" disabled value="Show Answer"> </span>&nbsp;');}
     if ($corr['j']=="Correct")
    {echo '<span id = "show_ans_j" class = "show_ans"> - Computed value is: '.$soln[9].'</span>';} 
    } 
    echo '</p>';
    }
//	$_SESSION['time']=time();
	?>
  Provisional Score on Problem:  <?php echo (round($PScore)) ?> %&nbsp; 

  <br> note - Score only includes quatitative parts of the problem.  These points awarded when work is uploaded and evaluated. <br>

<!--Score:  <?php echo (round($PScore)) ?>%-->
	<p><input type = "submit" name = "post_submit" id = "check_submit" value="Check" size="10" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp <b> <font size="4" color="Navy"></font></b></p>
			<p><font color=#003399> </font><input type="hidden" id = "dex" name="dex" size=3 value="<?php echo (htmlentities($dex))?>"  ></p>
            <input type = "number" hidden name = "pin"  value = <?php echo ($pin); ?> > </input>
			<p><font color=#003399> </font><input type="hidden" id = "problem_id" name="problem_id" size=3 value="<?php echo (htmlentities($problem_id))?>"  ></p>
			<p><font color=#003399> </font><input type="hidden" id = "exam_num" name="exam_num" size=3 value="<?php echo (htmlentities($exam_num))?>"  ></p>
			<p><font color=#003399> </font><input type="hidden" id = "stop_time" name="stop_time" size=3 value="<?php echo (htmlentities($stop_time))?>"  ></p>
             <input type="hidden" id = "eactivity_id" name="eactivity_id" value="<?php echo ($eactivity_id)?>" >
             <input type="hidden" id = "display_ans_key" name="display_ans_key" value="<?php echo ($display_ans_key)?>" >
             <input type="hidden" id = "count_tot" name="count_tot" value="<?php echo ($count)?>" >
            <input type="hidden" id = "prob_parts" name="prob_parts" value="<?php echo ($probParts)?>" >
	</form>

	
    
    <p> Count: <span id = "total_count" > <?php echo (@$count_tot) ?> </span> <span id ="t_delay_message"></span></p>
   <!--<p> <span id ="t_delay_limits"> time delay - 10s at count > <?php echo (2*$probParts) ?>, 60s at count > <?php echo (4*$probParts) ?> </span> </p> -->
   <p> <span id ="t_delay_limits"> time delay - 10s at count > <?php echo (floor(3*$probParts**0.7)) ?>, 60s at count > <?php echo (floor(5*$probParts**0.7)) ?> </span> </p>
    
    
    
    
	<form action="upload_exam_work.php" method="GET" id = "the_form">
    

			<input type="hidden" id = "problem_id" name="problem_id" value="<?php echo (htmlentities($problem_id))?>"  ></input>
            <input type="hidden" name="eactivity_id" value="<?php echo ($eactivity_id)?>" ></input>
            <!-- <input type="hidden" name="globephase" id = "globephase" > -->
            <input type="hidden" name="eexamnow_id" id = "eexamnow_id" value="<?php echo (htmlentities($eexamnow_id))?>"  ></input>
    
    <hr>

    <?php
      if ($dex !=1){
        echo('<p><b><font Color="red">Finished:</font></b></p>');
        echo('<b><input type="submit" value="Finished / Upload Work" name="score" style = "width: 30%; background-color:yellow "></b>');
        echo' <p><br> </p>
        <hr>';
      }
    ?>

     <!-- <?php // $_SESSION['score'] = round($PScore);  ?> -->
     

	 
	
	</form>

	<script>
        
    
	 	$(document).ready( function () {
    
      var eactivity_id = $('#eactivity_id').val();
      let dex = $('#dex').val();
                     var display_ans_key = $('#display_ans_key').val();
                    // console.log(' eactivity_id '+  eactivity_id);
                   //  console.log(' dex '+  dex);
            //    console.log(' display_ans_key '+  display_ans_key);
              // disable right mouse click copy and copy paste  From https://www.codingbot.net/2017/03/disable-copy-paste-mouse-right-click-using-javascript-jquery-css.html
            //Disable cut copy paste
            
            
            $('body').bind('cut copy paste', function (e) {
                e.preventDefault();
            });
            
            //Disable mouse right click
            
  /*           
            $("body").on("contextmenu",function(e){
                return false;
            });
        */ 
        
        
                var ans_a = $('#ans_a').val();
               var display_ans_a = $('#display_ans_a').val();
               
               if(display_ans_a==1){
                      $('#input_a').hide();
                      $('#disp_ans_a').text('a) '+ans_a);
                       $('#show_answer_button_a').hide();
                       $('#show_answer_check_a').hide();
               }
               
             
              $('#show_answer_check_a').change(function(){
                  if(this.checked){
                     $('#show_answer_button_a').prop('disabled',false);
                    $('#show_answer_button_a').css('color','red');
                    
                  } else {
                    $('#show_answer_button_a').prop('disabled',true);
                    $('#show_answer_button_a').css('color','lightgray');
                  }
             });
               
               
              $('#show_answer_button_a').click(function(){
                    $(this).css('color','red');
                  
                     $('#input_a').hide();
                    $('#disp_ans_a').text(ans_a);
                    // update the eactivity table to disp_ans_a value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            eactivity_id:eactivity_id,
                            dex:dex,
                            part:'a'
                        }
                    }).done(function(){
                        
                    })
                    
                  });    
                    
                    
                    
                 var ans_b = $('#ans_b').val();
               var display_ans_b = $('#display_ans_b').val();
               if(display_ans_b==1){
                      $('#input_b').hide();
                      $('#disp_ans_b').text('b) '+ans_b)
                       $('#show_answer_button_b').hide();
                       $('#show_answer_check_b').hide();

               }
               
               $('#show_answer_check_b').change(function(){
                  if(this.checked){
                     $('#show_answer_button_b').prop('disabled',false);
                    $('#show_answer_button_b').css('color','red');
                    
                  } else {
                    $('#show_answer_button_b').prop('disabled',true);
                    $('#show_answer_button_b').css('color','lightgray');
                  }
                });
             
               
              $('#show_answer_button_b').click(function(){
                    $(this).css('color','red');
                  
                     $('#input_b').hide();
                    $('#disp_ans_b').text(ans_b);
                    // update the eactivity table to disp_ans_b value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            eactivity_id:eactivity_id,
                            dex:dex,
                            part:'b'
                        }
                    }).done(function(){
                        
                    })
               })


                 var ans_c = $('#ans_c').val();
               var display_ans_c = $('#display_ans_c').val();
               if(display_ans_c==1){
                      $('#input_c').hide();
                      $('#disp_ans_c').text('c) '+ans_c)
                       $('#show_answer_button_c').hide();
                       $('#show_answer_check_c').hide();

               }
               
               $('#show_answer_check_c').change(function(){
                  if(this.checked){
                     $('#show_answer_button_c').prop('disabled',false);
                    $('#show_answer_button_c').css('color','red');
                    
                  } else {
                    $('#show_answer_button_c').prop('disabled',true);
                    $('#show_answer_button_c').css('color','lightgray');
                  }
                });
                
              $('#show_answer_button_c').click(function(){
                    $(this).css('color','red');
                  
                     $('#input_c').hide();
                    $('#disp_ans_c').text(ans_c);
                    // update the eactivity table to disp_ans_c value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            eactivity_id:eactivity_id,
                            dex:dex,
                            part:'c'
                        }
                    }).done(function(){
                        
                    })
               })

 
                 var ans_d = $('#ans_d').val();
               var display_ans_d = $('#display_ans_d').val();
               if(display_ans_d==1){
                      $('#input_d').hide();
                      $('#disp_ans_d').text('d) '+ans_d)
                       $('#show_answer_button_d').hide();
                       $('#show_answer_check_d').hide();
               }

               $('#show_answer_check_d').change(function(){
                  if(this.checked){
                     $('#show_answer_button_d').prop('disabled',false);
                    $('#show_answer_button_d').css('color','red');
                    
                  } else {
                    $('#show_answer_button_d').prop('disabled',true);
                    $('#show_answer_button_d').css('color','lightgray');
                  }
                });

              $('#show_answer_button_d').click(function(){
                    $(this).css('color','red');
                  
                     $('#input_d').hide();
                    $('#disp_ans_d').text(ans_d);
                    // update the eactivity table to disp_ans_d value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            eactivity_id:eactivity_id,
                            dex:dex,
                            part:'d'
                        }
                    }).done(function(){
                        
                    })
               })
                    
               
                 var ans_e = $('#ans_e').val();
               var display_ans_e = $('#display_ans_e').val();
               if(display_ans_e==1){
                      $('#input_e').hide();
                      $('#disp_ans_e').text('e) '+ans_e)
                       $('#show_answer_button_e').hide();
                       $('#show_answer_check_e').hide();
               }

               $('#show_answer_check_e').change(function(){
                  if(this.checked){
                     $('#show_answer_button_e').prop('disabled',false);
                    $('#show_answer_button_e').css('color','red');
                    
                  } else {
                    $('#show_answer_button_e').prop('disabled',true);
                    $('#show_answer_button_e').css('color','lightgray');
                  }
                });

              $('#show_answer_button_e').click(function(){
                    $(this).css('color','red');
                  
                     $('#input_e').hide();
                    $('#disp_ans_e').text(ans_e);
                    // update the eactivity table to disp_ans_e value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            eactivity_id:eactivity_id,
                            dex:dex,
                            part:'e'
                        }
                    }).done(function(){
                        
                    })
               })


                 var ans_f = $('#ans_f').val();
               var display_ans_f = $('#display_ans_f').val();
               if(display_ans_f==1){
                      $('#input_f').hide();
                      $('#disp_ans_f').text('f) '+ans_f)
                       $('#show_answer_button_f').hide();
                       $('#show_answer_check_f').hide();
               }

               $('#show_answer_check_f').change(function(){
                  if(this.checked){
                     $('#show_answer_button_f').prop('disabled',false);
                    $('#show_answer_button_f').css('color','red');
                    
                  } else {
                    $('#show_answer_button_f').prop('disabled',true);
                    $('#show_answer_button_f').css('color','lightgray');
                  }
                });

              $('#show_answer_button_f').click(function(){
                    $(this).css('color','red');
                  
                     $('#input_f').hide();
                    $('#disp_ans_f').text(ans_f);
                    // update the eactivity table to disp_ans_f value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            eactivity_id:eactivity_id,
                            dex:dex,
                            part:'f'
                        }
                    }).done(function(){
                        
                    })
               })


                 var ans_g = $('#ans_g').val();
               var display_ans_g = $('#display_ans_g').val();
               if(display_ans_g==1){
                      $('#input_g').hide();
                      $('#disp_ans_g').text('g) '+ans_g)
                       $('#show_answer_button_g').hide();
                       $('#show_answer_check_g').hide();
               }

               $('#show_answer_check_g').change(function(){
                  if(this.checked){
                     $('#show_answer_button_g').prop('disabled',false);
                    $('#show_answer_button_g').css('color','red');
                    
                  } else {
                    $('#show_answer_button_g').prop('disabled',true);
                    $('#show_answer_button_g').css('color','lightgray');
                  }
                });

              $('#show_answer_button_g').click(function(){
                    $(this).css('color','red');
                  
                     $('#input_g').hide();
                    $('#disp_ans_g').text(ans_g);
                    // update the eactivity table to disp_ans_g value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            eactivity_id:eactivity_id,
                            dex:dex,
                            part:'g'
                        }
                    }).done(function(){
                        
                    })
               })


                 var ans_h = $('#ans_h').val();
               var display_ans_h = $('#display_ans_h').val();
               if(display_ans_h==1){
                      $('#input_h').hide();
                      $('#disp_ans_h').text('h) '+ans_h)
                       $('#show_answer_button_h').hide();
                       $('#show_answer_check_h').hide();
               }

               $('#show_answer_check_h').change(function(){
                  if(this.checked){
                     $('#show_answer_button_h').prop('disabled',false);
                    $('#show_answer_button_h').css('color','red');
                    
                  } else {
                    $('#show_answer_button_h').prop('disabled',true);
                    $('#show_answer_button_h').css('color','lightgray');
                  }
                });

              $('#show_answer_button_h').click(function(){
                    $(this).css('color','red');
                  
                     $('#input_h').hide();
                    $('#disp_ans_h').text(ans_h);
                    // update the eactivity table to disp_ans_h value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            eactivity_id:eactivity_id,
                            dex:dex,
                            part:'h'
                        }
                    }).done(function(){
                        
                    })
               })


                 var ans_i = $('#ans_i').val();
               var display_ans_i = $('#display_ans_i').val();
               if(display_ans_i==1){
                      $('#input_i').hide();
                      $('#disp_ans_i').text('i) '+ans_i)
                       $('#show_answer_button_i').hide();
                       $('#show_answer_check_i').hide();
               }

               $('#show_answer_check_i').change(function(){
                  if(this.checked){
                     $('#show_answer_button_i').prop('disabled',false);
                    $('#show_answer_button_i').css('color','red');
                    
                  } else {
                    $('#show_answer_button_i').prop('disabled',true);
                    $('#show_answer_button_i').css('color','lightgray');
                  }
                });

              $('#show_answer_button_i').click(function(){
                    $(this).css('color','red');
                  
                     $('#input_i').hide();
                    $('#disp_ans_i').text(ans_i);
                    // update the eactivity table to disp_ans_i value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            eactivity_id:eactivity_id,
                            dex:dex,
                            part:'i'
                        }
                    }).done(function(){
                        
                    })
               })


                 var ans_j = $('#ans_j').val();
               var display_ans_j = $('#display_ans_j').val();
               if(display_ans_j==1){
                      $('#input_j').hide();
                      $('#disp_ans_j').text('j) '+ans_j)
                       $('#show_answer_button_j').hide();
                       $('#show_answer_check_j').hide();
               }

               $('#show_answer_check_j').change(function(){
                  if(this.checked){
                     $('#show_answer_button_j').prop('disabled',false);
                    $('#show_answer_button_j').css('color','red');
                    
                  } else {
                    $('#show_answer_button_j').prop('disabled',true);
                    $('#show_answer_button_j').css('color','lightgray');
                  }
                });

              $('#show_answer_button_j').click(function(){
                    $(this).css('color','red');
                  
                     $('#input_j').hide();
                    $('#disp_ans_j').text(ans_j);
                    // update the eactivity table to disp_ans_j value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            eactivity_id:eactivity_id,
                            dex:dex,
                            part:'j'
                        }
                    }).done(function(){
                        
                    })
               })




		// get the current phase
               let eexamnow_id = document.getElementById('eexamnow_id').value;
               let request;
                function fetchPhase() {
                    request = $.ajax({
                        type: "POST",
                        url: "fetchGPhase.php",
                        data: "eexamnow_id="+eexamnow_id,
                        success: function(data){
                           try{
                                var arrn = JSON.parse(data);
                            }
                            catch(err) {
                                return;
                            }
                           let globephase = arrn.globephase;

                           if(globephase > 1){  // submit away work time has eneded this is going to stop Exam and not back to the router
                               SubmitAway(); 
                            }
                        }
                    });
                }
                // setInterval(function() {
                //     if (request) request.abort();
                //     fetchPhase();
                // }, 10000);


                // Delay if they take to many total attempts

                    
                        
                            var count_tot = $("#count_tot").val();
                            var prob_parts = $("#prob_parts").val();
                            console.log ("count_tot = "+count_tot);
                             console.log ("prob_parts = "+prob_parts);
                            var delay1 = Math.floor(3*prob_parts**0.7);
                             var delay2 = Math.floor(5*prob_parts**0.7);
                            
/* 
                            var check_form = document.getElementById("check_form"), check_submit = document.getElementById("check_submit");
                            check_form.onsubmit = function() {
                                return false;
                            }

                            check_submit.onclick = function() {
                            
                                 if (count_tot > delay2){
                                        $("#t_delay_message").html('<span style = "color:red;font-weight:bold;">  60s time delay limit exceeded </span>');
                                      setTimeout(function() {
                                              check_form.submit();
                                         }, 60000);
                                           return false;
                                  } else if (count_tot > delay1){
                                      $("#t_delay_message").html('<span style = "color:red;">  10s time delay limit exceeded </span>');
                                      setTimeout(function() {
                                              check_form.submit();
                                         }, 10000);
                                           return false; 
                                  } else {
                                      
                                      check_form.submit();
                                      return false; 
                                  }
                            }      
                            
                            
                            */  
                                 
                                 
                                 
 
                
                     function SubmitAway() { 
                        window.close();
                        document.getElementById('the_form').submit();
                    }
                });
         

          
	</script>

	</main>
	</body>
	</html>