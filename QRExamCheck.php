<?php
session_start();
//	if(session_status()!=PHP_SESSION_ACTIVE) session_start();  // put this in to try to get rid of a warning of headers already sent - didn't work
	require_once "pdo.php";

    



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
      $get_flag = 0;
      if (isset($_POST['examactivity_id'])){
        $examactivity_id = $_POST['examactivity_id'];
        
        
        
        
        } elseif($_GET['examactivity_id']){
        
                $get_flag =1; // coming in from an external file
                
               $examactivity_id = $_GET['examactivity_id'];


 
        } elseif(isset($_SESSION['examactivity_id'])){
         $examactivity_id = $_SESSION['examactivity_id'];
        } else{
       $_SESSION['error_check'] = "Missing examactivity_id from QRExamCheck";
	  header('Location: QRexam_closed.php');
	  return;   
    }
    $_SESSION['examactivity_id'] = $examactivity_id;
   
   // get the 
       
        // initialize a few variables
        
         $_SESSION['score'] = "0";
			
			$count = 0;
			for ($j=0;$j<=9;$j++){
					$wrongCount[$j]=0;		// accumulates how many times they missed a part
//				$_SESSION['wrongC'[$j]]=0; // temp
					$changed[$j]=0;		// 1 if they changed their response ero otherwise
					$addCount[$j]=0;  // this is zero if they get it right and 1 if they get it wrong
			}	
			
	
			
			
			$score = 0;
			$PScore = 0;
			$partsFlag = array();
			$resp = array('a'=>0,'b'=>0,'c'=>0,'d'=>0,'e'=>0,'f'=>0,'g'=>0,'h'=>0,'i'=>0,'j'=>0);
			//$resp = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
			$corr = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
     		$corr_num = array('a'=>0,'b'=>0,'c'=>0,'d'=>0,'e'=>0,'f'=>0,'g'=>0,'h'=>0,'i'=>0,'j'=>0);

			$unit = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
			$tol=array('a'=>0.02,'b'=>0.02,'c'=>0.02,'d'=>0.02,'e'=>0.02,'f'=>0.02,'g'=>0.02,'h'=>0.02,'i'=>0.02,'j'=>0.02);
			$ansFormat=array('ans_a' =>"",'ans_b' =>"",	'ans_c' =>"",'ans_d' =>"",'ans_e' =>"",'ans_f' =>"",	'ans_g' =>"",'ans_h' =>"",'ans_i' =>"",'ans_j'=>"");
			
			
			$hintLimit = 3;
			$dispBase = 1;
			
			$_SESSION['wrote_try_flag']=false;

			$tol_key=array_keys($tol);
			$resp_key=array_keys($resp);
			$corr_key=array_keys($corr);
			$ansFormat_key=array_keys($ansFormat);
			
			$time_sleep1 = 10;  // time delay in seconds
			$time_sleep1_trip = 5;  // number of trials it talkes to trip the time delay
			$time_sleep2 = 10;  // additional time if hit the next limit
			$time_sleep2_trip = 30;	
			
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
				header( 'Location: QRexam_closed.php' ) ;
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
			
        
             $stmt = $pdo->prepare("SELECT *  FROM `Examactivity` WHERE examactivity_id = :examactivity_id");
			$stmt->execute(array(":examactivity_id" => $examactivity_id));
			$row = $stmt -> fetch();
            $examtime_id = $row['examtime_id'];
            $suspend_flag = $row['suspend_flag'];
			$work_time = $row['work_time'];
            $minutes = $row['minutes'];
            $created_at = $row['created_at'];
            $name = $row['name'];
      

            $updated_at = $row['updated_at'];
            $problem_id1 = $row['problem_id1'];
            $problem_id2 = $row['problem_id2'];
            $problem_id3 = $row['problem_id3'];
            $problem_id4 = $row['problem_id4'];
            $problem_id5 = $row['problem_id5'];
            if($problem_id == $problem_id1){$response_key = 'response_pblm1'; $display_ans_key = 'display_ans_pblm1'; $problem_score_key = 'pblm_1_score'; $trynum_pblm = 'trynum_pblm1'; $count = $row['trynum_pblm1'];$response = $row['response_pblm1'];$display_ans = $row['display_ans_pblm1'];}
            if($problem_id == $problem_id2){$response_key = 'response_pblm2'; $display_ans_key = 'display_ans_pblm2'; $problem_score_key = 'pblm_2_score'; $trynum_pblm = 'trynum_pblm2'; $count = $row['trynum_pblm2'];$response = $row['response_pblm2'];$display_ans = $row['display_ans_pblm2'];}
            if($problem_id == $problem_id3){$response_key = 'response_pblm3'; $display_ans_key = 'display_ans_pblm3'; $problem_score_key = 'pblm_3_score'; $trynum_pblm = 'trynum_pblm3'; $count = $row['trynum_pblm3'];$response = $row['response_pblm3'];$display_ans = $row['display_ans_pblm3'];}
            if($problem_id == $problem_id4){$response_key = 'response_pblm4'; $display_ans_key = 'display_ans_pblm4'; $problem_score_key = 'pblm_4_score'; $trynum_pblm = 'trynum_pblm4'; $count = $row['trynum_pblm4'];$response = $row['response_pblm4'];$display_ans = $row['display_ans_pblm4'];}
            if($problem_id == $problem_id5){$response_key = 'response_pblm5'; $display_ans_key = 'display_ans_pblm5'; $problem_score_key = 'pblm_5_score'; $trynum_pblm = 'trynum_pblm5'; $count = $row['trynum_pblm5'];$response = $row['response_pblm5'];$display_ans = $row['display_ans_pblm5'];}
            

            $response = explode(",",$response);
         //  print_r('response array 1 =  '.$response[0]);
          $oldresp_flag = 0;
          for ($j=0; $j<=9; $j++) {
               if (@$response[$j]==1){
                   $resp[$resp_key[$j]]=$soln[$j];
                   $oldresp_flag = 1;
               } 
           }
  
             $stmt = $pdo->prepare("SELECT *  FROM `Examtime` WHERE examtime_id = :examtime_id");
			$stmt->execute(array(":examtime_id" => $examtime_id));
			$row = $stmt -> fetch();
            if ($row == false){
                  header('Location: QRexam_closed.php');
                return;     
                
            }
            $globephase = $row['globephase'];
            $attempt_type = $row['attempt_type'];
             $num_attempts = $row['num_attempts'];
            $ans_n = $row['ans_n'];
            $ans_t = $row['ans_t'];             
            if ($globephase != 1){
                header('Location:QRexam_closed.php');
                return;    
            }
     // keep track of the number of tries the student makes
	// get the count from the examactivity table
  
   if(is_null($count)){   // first time no tries initialise count and wrong count
		$count = 0;

		for ($j=0;$j<=9;$j++){
					$wrongCount[$j]=0;
					@$_SESSION['wrongC'[$j]]=$wrongCount[$j]; 
				}
	}

    
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
	
  
    
    
    
		$_SESSION['points']=$score; // this will cause problems if running multiple browser windows on the same machine - testing on localhost
		$corr_num_st = implode(",",$corr_num);
        
      
           $stmt = $pdo->prepare("UPDATE `Examactivity` SET ".$trynum_pblm." = :trynum_pblm,".$response_key." = :response_key WHERE examactivity_id = :examactivity_id ");
			$stmt->execute(array(
            ":examactivity_id" => $examactivity_id,
            ":trynum_pblm" => $count,
             ":response_key" => $corr_num_st, 
            ));
            
    }
  
		
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
	<h2>Quick Response Exam Checker</h2>
	</header>
	<main>
	<h3> Name: <?php echo($name);?> &nbsp; &nbsp; Exam Number: <?php echo($exam_num);?>&nbsp; &nbsp;    Max Attempts: <?php if ($attempt_type==1){echo('infinite');}else{echo($num_attempts);}$display_ans = explode(',',$display_ans) ?>  </h3>
    

	<font size = "1"> Problem Number: <?php echo ($problem_id) ?> -  <?php echo ($dex) ?> </font>
    <!--  <font size = "2"> ans_t: <?php echo ($ans_t); ?> </font>
    <font size = "2"> ans_n: <?php echo ($ans_n); ?> </font>
    <font size = "2"> name: <?php echo ($name); ?> </font>
  <font size = "2"> Get_Flag: <?php echo ($get_flag) ?> </font>
      <font size = "2"> oldresp_flag: <?php echo ($oldresp_flag) ?> </font> 
      -->

	<form autocomplete="off" id = "check_form" method="POST" >
	<!--<p><font color=#003399>Index: </font><input type="text" name="dex_num" size=3 value="<?php echo (htmlentities($_SESSION['index']))?>"  ></p> -->

	<?php
    if($attempt_type ==1 || ($attempt_type ==2 && $count <= $num_attempts)){
	if ($partsFlag[0]){ ?> 
	<p> <span id = "input_a"> a): <input [ type=number]{width: 5%;} id = "a" name="a" size = 10% value="<?php echo (htmlentities($resp['a']))?>" > <?php echo($unit[0]) ?> &nbsp - <b><?php echo ($corr['a']) ?> </b> </span><span id = "disp_ans_a"></span>
	
	<?php if (isset($_POST['pin']) and @$wrongCount[0]>$hintLimit and $corr['a']=="Not Correct" && $hintaPath != "uploads/default_hints.html" ){echo '<a href="'.$hintaPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[0] and @$wrongCount[0]>$time_sleep1_trip and @$wrongCount[0]< $time_sleep2_trip and $corr['a']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[0] and @$wrongCount[0]>=$time_sleep2_trip and $corr['a']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
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
	<p>  <span id = "input_b"> b): <input [ type=number]{width: 5%;} id = "b" name="b" size = 10% value="<?php echo (htmlentities($resp['b']))?>" > <?php echo($unit[1]) ?> &nbsp - <b> <?php echo ($corr['b']) ?> </b> </span><span id = "disp_ans_b"></span>
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
	<p>  <span id = "input_c"> c): <input [ type=number]{width: 5%;}  id = "c" name="c" size = 10% value="<?php echo (htmlentities($resp['c']))?>" > <?php echo($unit[2]) ?> &nbsp - <b><?php echo ($corr['c']) ?> </b> </span><span id = "disp_ans_c"></span>
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
	<p>  <span id = "input_d"> d): <input [ type=number]{width: 5%;} id = "d" name="d" size = 10% value="<?php echo (htmlentities($resp['d']))?>" > <?php echo($unit[3]) ?> &nbsp - <b><?php echo ($corr['d']) ?> </b> </span><span id = "disp_ans_d"></span>
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
	<p>  <span id = "input_e"> e): <input [ type=number]{width: 5%;} id = "e" name="e" size = 10% value="<?php echo (htmlentities($resp['e']))?>" > <?php echo($unit[4]) ?> &nbsp - <b><?php echo ($corr['e']) ?> </b> </span><span id = "disp_ans_e"></span>
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
	<p>  <span id = "input_f"> f): <input [ type=number]{width: 5%;} id = "f" name="f" size = 10% value="<?php echo (htmlentities($resp['f']))?>" > <?php echo($unit[5]) ?> &nbsp - <b><?php echo ($corr['f']) ?> </b> </span><span id = "disp_ans_f"></span>
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
	<p>  <span id = "input_g"> g): <input [ type=number]{width: 5%;} id = "g" name="g" size = 10% value="<?php echo (htmlentities($resp['g']))?>" > <?php echo($unit[6]) ?> &nbsp - <b><?php echo ($corr['g']) ?> </b> </span><span id = "disp_ans_g"></span>
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
	<p>  <span id = "input_h"> h): <input [ type=number]{width: 5%;} id = "h" name="h" size = 10% value="<?php echo (htmlentities($resp['h']))?>" > <?php echo($unit[7]) ?> &nbsp - <b><?php echo ($corr['h']) ?> </b> </span><span id = "disp_ans_h"></span>
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
	<p>  <span id = "input_i"> i): <input [ type=number]{width: 5%;} id = "i" name="i" size = 10% value="<?php echo (htmlentities($resp['i']))?>" > <?php echo($unit[8]) ?> &nbsp - <b><?php echo ($corr['i']) ?> </b> </span><span id = "disp_ans_i"></span>
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
	<p>  <span id = "input_j"> j): <input [ type=number]{width: 5%;}  id = "j" name="j" size = 10% value="<?php echo (htmlentities($resp['j']))?>" > <?php echo($unit[9]) ?> &nbsp - <b><?php echo ($corr['j']) ?> </b> </span><span id = "disp_ans_j"></span>
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
	$_SESSION['time']=time();
	?>
    
<!--Score:  <?php echo (round($PScore)) ?>%-->
	<p><input type = "submit" id = "check_submit" value="Check" size="10" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp <b> <font size="4" color="Navy"></font></b></p>
			<p><font color=#003399> </font><input type="hidden" id = "dex" name="dex" size=3 value="<?php echo (htmlentities($dex))?>"  ></p>
            <input type = "number" hidden name = "pin"  value = <?php echo ($pin); ?> > </input>
			<p><font color=#003399> </font><input type="hidden" id = "problem_id" name="problem_id" size=3 value="<?php echo (htmlentities($problem_id))?>"  ></p>
			<p><font color=#003399> </font><input type="hidden" id = "exam_num" name="exam_num" size=3 value="<?php echo (htmlentities($exam_num))?>"  ></p>
			<p><font color=#003399> </font><input type="hidden" id = "stop_time" name="stop_time" size=3 value="<?php echo (htmlentities($stop_time))?>"  ></p>
             <input type="hidden" id = "examactivity_id" name="examactivity_id" value="<?php echo ($examactivity_id)?>" >
             <input type="hidden" id = "display_ans_key" name="display_ans_key" value="<?php echo ($display_ans_key)?>" >
             <input type="hidden" id = "count_tot" name="count_tot" value="<?php echo ($count)?>" >
            <input type="hidden" id = "prob_parts" name="prob_parts" value="<?php echo ($probParts)?>" >
	</form>

	
    
    <p> Count: <?php echo ($count) ?>  <span id ="t_delay_message"></span></p>
   <p> <span id ="t_delay_limits"> time delay - 5s at count > <?php echo (3*$probParts) ?>, 30s at count > <?php echo (5*$probParts) ?> </span> </p>
    
    
    
    
	<form action="upload_exam_work.php" method="GET" id = "the_form">
    

			<input type="hidden" id = "problem_id" name="problem_id" value="<?php echo (htmlentities($problem_id))?>"  >
            <input type="hidden" name="examactivity_id" value="<?php echo ($examactivity_id)?>" >
            <input type="hidden" name="globephase" id = "globephase" >
    
    <hr>
	<p><b><font Color="red">Finished:</font></b></p>
	  <!--<input type="hidden" name="score" value=<?php echo ($score) ?> /> -->
	   <?php $_SESSION['score'] = round($PScore);  ?>
	 <b><input type="submit" value="Finished / Upload Work" name="score" style = "width: 30%; background-color:yellow "></b>
	 <p><br> </p>
	 <hr>
	</form>


	<script>
        
    
		$(document).ready( function () {
            
             var examactivity_id = $('#examactivity_id').val();
                     var display_ans_key = $('#display_ans_key').val();
                console.log(' examactivity_id '+  examactivity_id);
                console.log(' display_ans_key '+  display_ans_key);
              // disable right mouse click copy and copy paste  From https://www.codingbot.net/2017/03/disable-copy-paste-mouse-right-click-using-javascript-jquery-css.html
            //Disable cut copy paste
            $('body').bind('cut copy paste', function (e) {
                e.preventDefault();
            });
            
            //Disable mouse right click
            $("body").on("contextmenu",function(e){
                return false;
            });
        
        
        
                var ans_a = $('#ans_a').val();
               var display_ans_a = $('#display_ans_a').val();
               
               console.log(' display_ans_a '+display_ans_a);
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
                    // update the Examactivity table to disp_ans_a value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            examactivity_id:examactivity_id,
                            display_ans_key:display_ans_key,
                            part:0
                        }
                    }).done(function(){
                        
                    })
                    
                  });    
                    
                    
                    
                 var ans_b = $('#ans_b').val();
               var display_ans_b = $('#display_ans_b').val();
               console.log(' display_ans_b '+display_ans_b);
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
                    // update the Examactivity table to disp_ans_b value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            examactivity_id:examactivity_id,
                            display_ans_key:display_ans_key,
                            part:1
                        }
                    }).done(function(){
                        
                    })
               })


                 var ans_c = $('#ans_c').val();
               var display_ans_c = $('#display_ans_c').val();
               console.log(' display_ans_c '+display_ans_c);
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
                    // update the Examactivity table to disp_ans_c value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            examactivity_id:examactivity_id,
                            display_ans_key:display_ans_key,
                            part:2
                        }
                    }).done(function(){
                        
                    })
               })

 
                 var ans_d = $('#ans_d').val();
               var display_ans_d = $('#display_ans_d').val();
               console.log(' display_ans_d '+display_ans_d);
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
                    // update the Examactivity table to disp_ans_d value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            examactivity_id:examactivity_id,
                            display_ans_key:display_ans_key,
                            part:3
                        }
                    }).done(function(){
                        
                    })
               })
                    
               
                 var ans_e = $('#ans_e').val();
               var display_ans_e = $('#display_ans_e').val();
               console.log(' display_ans_e '+display_ans_e);
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
                    // update the Examactivity table to disp_ans_e value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            examactivity_id:examactivity_id,
                            display_ans_key:display_ans_key,
                            part:4
                        }
                    }).done(function(){
                        
                    })
               })


                 var ans_f = $('#ans_f').val();
               var display_ans_f = $('#display_ans_f').val();
               console.log(' display_ans_f '+display_ans_f);
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
                    // update the Examactivity table to disp_ans_f value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            examactivity_id:examactivity_id,
                            display_ans_key:display_ans_key,
                            part:5
                        }
                    }).done(function(){
                        
                    })
               })


                 var ans_g = $('#ans_g').val();
               var display_ans_g = $('#display_ans_g').val();
               console.log(' display_ans_g '+display_ans_g);
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
                    // update the Examactivity table to disp_ans_g value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            examactivity_id:examactivity_id,
                            display_ans_key:display_ans_key,
                            part:6
                        }
                    }).done(function(){
                        
                    })
               })


                 var ans_h = $('#ans_h').val();
               var display_ans_h = $('#display_ans_h').val();
               console.log(' display_ans_h '+display_ans_h);
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
                    // update the Examactivity table to disp_ans_h value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            examactivity_id:examactivity_id,
                            display_ans_key:display_ans_key,
                            part:7
                        }
                    }).done(function(){
                        
                    })
               })


                 var ans_i = $('#ans_i').val();
               var display_ans_i = $('#display_ans_i').val();
               console.log(' display_ans_i '+display_ans_i);
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
                    // update the Examactivity table to disp_ans_i value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            examactivity_id:examactivity_id,
                            display_ans_key:display_ans_key,
                            part:8
                        }
                    }).done(function(){
                        
                    })
               })


                 var ans_j = $('#ans_j').val();
               var display_ans_j = $('#display_ans_j').val();
               console.log(' display_ans_j '+display_ans_j);
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
                    // update the Examactivity table to disp_ans_j value pressed via AJAX
                    $.ajax({
					url: 'qrexam_record_show_ans.php',
					method: 'post',
						data: {
                            examactivity_id:examactivity_id,
                            display_ans_key:display_ans_key,
                            part:9
                        }
                    }).done(function(){
                        
                    })
               })





              
				// get the current phase
				var examtime_id = $("#examtime_id").val();
				console.log ('examtime_id = ',examtime_id);
                
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
                           if(phase != 1){  // submit away work time has eneded this is going to stop Exam and not back to the router
                               $("#phase").attr('value', phase);
                               SubmitAway(); 
                            }
                        }
                    });
                }
                setInterval(function() {
                    if (request) request.abort();
                    fetchPhase();
                }, 10000);


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
                                 
                                 
                                 
 
                
                     function SubmitAway() { 
                        window.close();
                       // document.getElementById('the_form').submit();
                    }
                });
         

         
	</script>

	</main>
	</body>
	</html>