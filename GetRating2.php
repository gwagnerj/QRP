<?php
 session_start();
  Require_once "pdo.php";
	 // values from QRChecker2 that need put in the activity table
	 if (isset($_POST['PScore'])&& isset($_POST['activity_id']) && isset($_POST['perc_late_p_prob'])&& isset($_POST['pscore_less'])&& isset($_POST['ec_elgible_flag'])&& isset($_POST['num_score_possible'])&& isset($_POST['credit'])){
		// put these in the activity table and from the previous page 
       $activity_id = $_POST['activity_id'];
        $ec_elgible_flag =$_POST['ec_elgible_flag'];
        $p_num_score_net = $_POST['pscore_less'];
		$credit = $_POST['credit'];
		
        if($credit == 'latetoproblems'){
        
           $sql ='UPDATE `Activity` SET `p_num_score_raw` = :p_num_score_raw, `late_penalty` = :late_penalty, `p_num_score_net` = :p_num_score_net, `ec_elgible_flag` = :ec_elgible_flag 
           WHERE activity_id = :activity_id';
            $stmt = $pdo -> prepare($sql);
            $stmt -> execute(array(
                ':p_num_score_raw' => $_POST['PScore'],
                ':late_penalty' => $_POST['perc_late_p_prob'],
                ':p_num_score_net' => $p_num_score_net,
                ':ec_elgible_flag' => $ec_elgible_flag,
                ':activity_id' => $activity_id
                 ));
			}

    } 
    /* 
	  if (isset($_POST['count'])){
		$count = $_POST['count'];
	} else {
		$_SESSION['error'] = 'count not set in GetRating2.php';
	}
     */
      if (isset($_POST['num_score_possible'])){
		$num_score_possible = $_POST['num_score_possible'];
	} else {
		$_SESSION['error'] = 'num_score_possible not set in GetRating2.php';
	}


     if (isset($_POST['activity_id'])){
		$activity_id = $_POST['activity_id'];
	} else {
		$_SESSION['error'] = 'activity_id not set in GetRating2.php';
	}
	 
 // get the info from the Activity table
 
             $sql = "SELECT * FROM Activity WHERE activity_id = :activity_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(':activity_id' => $activity_id));
            $activity_data = $stmt -> fetch();	
				$iid = 	$activity_data['pin'];			
                $problem_id = $activity_data['problem_id'];
                $student_id = $activity_data['student_id'];
                $assigntime_id = $activity_data['assigntime_id'];
                $alias_num = $activity_data['alias_num'];
                $ec_elgible_flag =$activity_data['ec_elgible_flag'];
                
           // get how many points they get for filling out survey
              $sql = "SELECT * FROM Assigntime WHERE assigntime_id = :assigntime_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(':assigntime_id' => $assigntime_id));
            $assigntime_data = $stmt -> fetch();	
            $survey_pts =  $assigntime_data['survey_'.$alias_num];
	

	$move_on = false;

	
      
			 if ((isset($_POST['effectiveness']) && isset($_POST['difficulty']) && isset($_POST['confidence'])&& isset($_POST['t_take1'])&& isset($_POST['t_take2'])&& isset($_POST['t_b4due']))
				 || (isset($_POST['not_perfect']) && isset($_POST['t_take1_np']) && isset($_POST['t_b4due_np']) && isset($_POST['confidence_np']))
			 )
			 {
				if (isset($_POST['effectiveness']) && isset($_POST['difficulty']) && isset($_POST['confidence'])&& isset($_POST['t_take1'])&& isset($_POST['t_take2'])&& isset($_POST['t_b4due']))
				{
				if ($_POST['peer_instruction']=='yes'){$peer_instruction = 1;} else {$peer_instruction = 0;}
				if ($_POST['video_instruction']=='yes'){$video_instruction = 1;} else {$video_instruction = 0;}
               

             //  if ($_POST['audio_instruction']=='yes'){$audio_instruction = 1;} else {$audio_instruction = 0;}
			//	if ($_POST['written_instruction']=='yes'){$written_instruction = 1;} else {$written_instruction = 0;}

					// put the values in the data base

					  // Get the correct effectiveness and difficulty  rating from database add 1 to it and put it back
					 
							
								 $sql = "SELECT * FROM Problem WHERE problem_id = :problem_id";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(':problem_id' => $problem_id));
								$data = $stmt -> fetch();	
									
								$nm_diff = 'diff_stu_'.$_POST['difficulty'];
								$nm_eff = 'eff_stu_'.$_POST['effectiveness'];	
								$nm_t_take1 = 't_take1_'.$_POST['t_take1'];	
								$nm_t_take2 = 't_take2_'.$_POST['t_take2'];	
								$nm_t_b4due = 't_b4due_'.$_POST['t_b4due'];	
								$nm_confidence = 'confidence_'.$_POST['confidence'];	
								
									
								$val_diff = $data[$nm_diff]+1;	
								$val_eff = $data[$nm_eff]+1;
								$val_t_take1 = $data[$nm_t_take1]+1;
								$val_t_take2 = $data[$nm_t_take2]+1;
								$val_t_b4due = $data[$nm_t_b4due]+1;
								$val_confidence = $data[$nm_confidence]+1;

							
								$sql = "UPDATE Problem SET $nm_diff = :nmdiff WHERE problem_id = :pblm_num";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
										':nmdiff' => $val_diff,
										':pblm_num' => $problem_id));
										
								$sql = "UPDATE Problem SET $nm_eff = :nmeff WHERE problem_id = :pblm_num";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
										':nmeff' => $val_eff,
										':pblm_num' => $problem_id));		
										
								$sql = "UPDATE Problem SET $nm_t_take1 = :holder WHERE problem_id = :pblm_num";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
										':holder' => $val_t_take1,
										':pblm_num' => $problem_id));	

								$sql = "UPDATE Problem SET $nm_t_take2 = :holder WHERE problem_id = :pblm_num";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
										':holder' => $val_t_take2,
										':pblm_num' => $problem_id));	

								$sql = "UPDATE Problem SET $nm_t_b4due = :holder WHERE problem_id = :pblm_num";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
										':holder' => $val_t_b4due,
										':pblm_num' => $problem_id));	

								$sql = "UPDATE Problem SET $nm_confidence = :holder WHERE problem_id = :pblm_num";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
										':holder' => $val_confidence,
										':pblm_num' => $problem_id));	
									
				} else {
					
					// they did not get 100% on the problem - input that data into the table
					
					
						 $sql = "SELECT * FROM Problem WHERE problem_id = :problem_id";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(':problem_id' => $problem_id));
								$data = $stmt -> fetch();	
									
								
								$nm_t_take1_np = 't_take1_np_'.$_POST['t_take1_np'];	
								$not_perfect = 'not_perfect_'.$_POST['not_perfect'];	
								$nm_t_b4due_np = 't_b4due_np_'.$_POST['t_b4due_np'];	
								$nm_confidence_np = 'confidence_np_'.$_POST['confidence_np'];	
									
								
								$val_t_take1_np = $data[$nm_t_take1_np]+1;
								$val_not_perfect = $data[$not_perfect]+1;
								$val_t_b4due_np = $data[$nm_t_b4due_np]+1;
								$val_confidence_np = $data[$nm_confidence_np]+1;
							
								
										
								$sql = "UPDATE Problem SET $nm_t_take1_np = :holder WHERE problem_id = :pblm_num";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
										':holder' => $val_t_take1_np,
										':pblm_num' => $problem_id));	

								$sql = "UPDATE Problem SET $not_perfect = :holder WHERE problem_id = :pblm_num";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
										':holder' => $not_perfect,
										':pblm_num' => $problem_id));	

								$sql = "UPDATE Problem SET $nm_t_b4due_np = :holder WHERE problem_id = :pblm_num";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
										':holder' => $val_t_b4due_np,
										':pblm_num' => $problem_id));	

								$sql = "UPDATE Problem SET $nm_confidence_np = :holder WHERE problem_id = :pblm_num";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
										':holder' => $val_confidence_np,
										':pblm_num' => $problem_id));	
				}
					

	// add the problem comments if they have been added

				
				$prefix = '   name - '.$activity_data['stu_name'].' score (less late) - '. $p_num_score_net.' Comment--';
				


				if (isset($_POST['prob_comments']) && strlen($_POST['prob_comments']) >7 )
				{
					
					$prob_comments =$prefix. htmlentities($_POST['prob_comments']).$data['prob_comments'];
					
					
						
								$sql = "UPDATE Problem SET prob_comments = :holder WHERE problem_id = :pblm_num";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
										':holder' => $prob_comments,
										':pblm_num' => $problem_id));	
				}
				if (isset($_POST['sug_hints']) && strlen($_POST['sug_hints']) >7 )
				{
					
					$sug_hints =$prefix. htmlentities($_POST['sug_hints']).$data['sug_hints'];
					
					
						
								$sql = "UPDATE Problem SET sug_hints = :holder WHERE problem_id = :pblm_num";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
										':holder' => $sug_hints,
										':pblm_num' => $problem_id));	
				}

	
		
		// give them the points for filling out the survey
              $sql ='UPDATE `Activity` SET survey_pts = :survey_pts 
           WHERE activity_id = :activity_id';
            $stmt = $pdo -> prepare($sql);
            $stmt -> execute(array(
                ':survey_pts' => $survey_pts,
                ':activity_id' => $activity_id
                 ));
            
            
		// change the headers to the rtnCode.php  instead of doing this I will post all the information to the rtn code
			
            
           // $move_on = true;

		//echo ('<script> document.getElementById("move_on").submit() </script>');
		


				header( 'Location: upload_work.php?activity_id='.$activity_id ) ;
				die();	
					


			 } else {
				
				print ('<p><b><font Color="red">All check box type catagories must be entered</font></b></p>');
			 
			 
			 }

			//  echo '$num_score_possible = '.$num_score_possible.'<br>';
			//  echo '$p_num_score_net = '.$p_num_score_net.'<br>';
	  		if($num_score_possible == $p_num_score_net && $p_num_score_net != 0 && $p_num_score_net != '' && $p_num_score_net != null ){$perf_num_score_flag = 1;} else {$perf_num_score_flag = 0;}		

	?>

	<link rel="icon" type="image/png" href="McKetta.png" />  
	 <title>QRHomework</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"> </script>






	<!DOCTYPE html>
	<html lang = "en">
	<head>
	<link rel="icon" type="image/png" href="McKetta.png" />
	<meta Charset = "utf-8">
	<title>QRProblems</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 
	</head>

	<body>
	<header>
	<!--<h1>this is an application that Gets the rating of the problem from the student</h1>-->
	</header>
	<main>
	<h3>The problems attempted to give you practice with and allow discovery of certain concepts</h3>
	<p><font color = 'blue' size='4'> Please answer honestly </font></p>




		<input type="hidden" name="score_val" id = "score" size= 20  value="<?php echo($score);?>" >




	<form method="POST">

    <input type = "number" hidden name = "activity_id"  value = <?php echo ($activity_id); ?> > </input>
   <input type = "number" hidden id = "ec_elgible_flag" name = "ec_elgible_flag"  value = <?php echo ($ec_elgible_flag); ?> > </input>
   <input type = "number" hidden id = "perf_num_score_flag" name = "perf_num_score_flag"  value = <?php echo ($perf_num_score_flag); ?> > </input>
   <input type = "number" hidden id = "num_score_possible" name = "num_score_possible"  value = <?php echo ($num_score_possible); ?> > </input>

    <div id = "peer_instruction"> 
	Make myself avalable for peer instruction on this problem for possible extra credit - Base Case must be solved<br> 
		&nbsp &nbsp <input type="radio" name="peer_instruction" value = "yes"  size= 20  >&nbsp &nbsp Yes <br>
		&nbsp &nbsp <input type="radio" name="peer_instruction" value = "no"  size= 20  >&nbsp &nbsp No  <br>
	<p></p>
    Intend to submit video instruction on the solution to part of the Base-Case by the due date for extra credit?<br> 
		&nbsp &nbsp <input type="radio" name="video_instruction" value = "yes"  size= 20  >&nbsp &nbsp Yes <br>
		&nbsp &nbsp <input type="radio" name="video_instruction" value = "no"  size= 20  >&nbsp &nbsp No  <br>
        <p></p>
        
        
	</div>



	<div id = "not_perfect"> 
	If I had to do it all over again, the one thing that I would change: <br> 
		&nbsp &nbsp <input type="radio" name="not_perfect" value = 1 id = "one" size= 20  >&nbsp &nbsp started earlier <br>
		&nbsp &nbsp <input type="radio" name="not_perfect" value = 2 id = "two" size= 20  >&nbsp &nbsp sought help after an honest attempt at both my problem and the base-case  <br>
		&nbsp &nbsp <input type="radio" name="not_perfect" value = 3 id = "three" size= 20  >&nbsp &nbsp  spent more time in understanding what the problem was asking <br>
		&nbsp &nbsp <input type="radio" name="not_perfect" value = 4 id = "four" size= 20  >&nbsp &nbsp   used different tools to solve the problem <br>
		&nbsp &nbsp <input type="radio" name="not_perfect" value = 5 id = "five" size= 20  >&nbsp &nbsp  been more systematic in my problem solving approach <br>
		&nbsp &nbsp <input type="radio" name="not_perfect" value = 6 id = "six" size= 20  >&nbsp &nbsp  solved a simpler problem before attempting this one <br>
		&nbsp &nbsp <input type="radio" name="not_perfect" value = 7 id = "seven" size= 20  >&nbsp &nbsp  nothing. I did everything I could <br>
	<p></p>
	</div>

	<div id = "time_take1"> 
	Estimate how long you spent on just this problem <br> 
		&nbsp &nbsp <input type="radio" name="t_take1" value = 1 id = "one" size= 20  >&nbsp &nbsp less than 5 min <br>
		&nbsp &nbsp <input type="radio" name="t_take1" value = 2 id = "two" size= 20  >&nbsp &nbsp 5 - 15 min  <br>
		&nbsp &nbsp <input type="radio" name="t_take1" value = 3 id = "three" size= 20  >&nbsp &nbsp  15 - 30 min <br>
		&nbsp &nbsp <input type="radio" name="t_take1" value = 4 id = "four" size= 20  >&nbsp &nbsp   30 - 60 min <br>
		&nbsp &nbsp <input type="radio" name="t_take1" value = 5 id = "five" size= 20  >&nbsp &nbsp  1 - 3 hrs <br>
		&nbsp &nbsp <input type="radio" name="t_take1" value = 7 id = "six" size= 20  >&nbsp &nbsp  over 3 hrs <br>
	<p></p>
	</div>

	<div id = "time_take1_np"> 
	Estimate how long you spent on just this problem <br> 
		&nbsp &nbsp <input type="radio" name="t_take1_np" value = 1 id = "one" size= 20  >&nbsp &nbsp less than 5 min <br>
		&nbsp &nbsp <input type="radio" name="t_take1_np" value = 2 id = "two" size= 20  >&nbsp &nbsp 5 - 15 min  <br>
		&nbsp &nbsp <input type="radio" name="t_take1_np" value = 3 id = "three" size= 20  >&nbsp &nbsp  15 - 30 min <br>
		&nbsp &nbsp <input type="radio" name="t_take1_np" value = 4 id = "four" size= 20  >&nbsp &nbsp   30 - 60 min <br>
		&nbsp &nbsp <input type="radio" name="t_take1_np" value = 5 id = "five" size= 20  >&nbsp &nbsp  1 - 3 hrs <br>
		&nbsp &nbsp <input type="radio" name="t_take1_np" value = 7 id = "six" size= 20  >&nbsp &nbsp  over 3 hrs <br>
	<p></p>
	</div>

	<div id = "time_take2"> 
	Estimate how long it would now take you to solve a very similar problem <br> 
		&nbsp &nbsp <input type="radio" name="t_take2" value = 1 id = "one" size= 20  >&nbsp &nbsp less than 5 min <br>
		&nbsp &nbsp <input type="radio" name="t_take2" value = 2 id = "two" size= 20  >&nbsp &nbsp 5 - 15 min  <br>
		&nbsp &nbsp <input type="radio" name="t_take2" value = 3 id = "three" size= 20  >&nbsp &nbsp  15 - 30 min <br>
		&nbsp &nbsp <input type="radio" name="t_take2" value = 4 id = "four" size= 20  >&nbsp &nbsp   30 - 60 min <br>
		&nbsp &nbsp <input type="radio" name="t_take2" value = 5 id = "five" size= 20  >&nbsp &nbsp  1 - 3 hrs <br>
		&nbsp &nbsp <input type="radio" name="t_take2" value = 7 id = "six" size= 20  >&nbsp &nbsp  over 3 hrs <br>
	<p></p>
	</div>

	<div id = "time_start"> 
	How long before it was due did you first look at the problem <br> 
		&nbsp &nbsp <input type="radio" name="t_b4due" value = 1 id = "one" size= 20  >&nbsp &nbsp less than 1 hr <br>
		&nbsp &nbsp <input type="radio" name="t_b4due" value = 2 id = "two" size= 20  >&nbsp &nbsp 1 - 5 hrs  <br>
		&nbsp &nbsp <input type="radio" name="t_b4due" value = 3 id = "three" size= 20  >&nbsp &nbsp  5 - 12 hrs <br>
		&nbsp &nbsp <input type="radio" name="t_b4due" value = 4 id = "four" size= 20  >&nbsp &nbsp   12 - 24 hrs <br>
		&nbsp &nbsp <input type="radio" name="t_b4due" value = 5 id = "five" size= 20  >&nbsp &nbsp  1 - 2 days <br>
		&nbsp &nbsp <input type="radio" name="t_b4due" value = 6 id = "six" size= 20  >&nbsp &nbsp  2 - 7 days <br>
		&nbsp &nbsp <input type="radio" name="t_b4due" value = 7 id = "seven" size= 20  >&nbsp &nbsp  over 1 week <br>
	<p></p>
	</div>


	<div id = "time_start_np"> 
	How long before it was due did you first look at the problem <br> 
		&nbsp &nbsp <input type="radio" name="t_b4due_np" value = 1 id = "one" size= 20  >&nbsp &nbsp less than 1 hr <br>
		&nbsp &nbsp <input type="radio" name="t_b4due_np" value = 2 id = "two" size= 20  >&nbsp &nbsp 1 - 5 hrs  <br>
		&nbsp &nbsp <input type="radio" name="t_b4due_np" value = 3 id = "three" size= 20  >&nbsp &nbsp  5 - 12 hrs <br>
		&nbsp &nbsp <input type="radio" name="t_b4due_np" value = 4 id = "four" size= 20  >&nbsp &nbsp   12 - 24 hrs <br>
		&nbsp &nbsp <input type="radio" name="t_b4due_np" value = 5 id = "five" size= 20  >&nbsp &nbsp  1 - 2 days <br>
		&nbsp &nbsp <input type="radio" name="t_b4due_np" value = 6 id = "six" size= 20  >&nbsp &nbsp  2 - 7 days <br>
		&nbsp &nbsp <input type="radio" name="t_b4due_np" value = 7 id = "seven" size= 20  >&nbsp &nbsp  over 1 week <br>
	<p></p>
	</div>


	<div id = "eff_div">
	<table>
		<tr><td>Effectiveness: This problem caused me to think or provided effective reinforcment practice:</td> <tr>  <td> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp not effective
		<input type="radio" name="effectiveness" value = 1 id = "one" size= 20  >
		<input type="radio" name="effectiveness" value = 2 id = "two" size= 20  >
		<input type="radio" name="effectiveness" value = 3 id = "three" size= 20  >
		<input type="radio" name="effectiveness" value = 4 id = "four" size= 20  >
		<input type="radio" name="effectiveness" value = 5 id = "five" size= 20  >
		very effective</td> </tr> <tr></tr>
	<p></p>
	</table>
	</div>	
		
	<div id = "diff_div">
	<table>	
		<td>Difficulty: Took a long time or involved multiple complex concepts:</td> <tr> <td> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp easy
		<input type="radio" name="difficulty" value = 1  id = "one" size= 20  >
		<input type="radio" name="difficulty" value = 2 id = "two" size= 20  >
		<input type="radio" name="difficulty" value = 3 id = "three" size= 20  >
		<input type="radio" name="difficulty" value = 4 id = "four" size= 20  >
		<input type="radio" name="difficulty" value = 5 id = "five" size= 20  >
		very difficult</td></tr><tr></tr>
	<p></p>
	</table>
	</div>

	<div id = "conf_div">
	<table>		
		<td>How confident are you in your understanding of the concepts underlying this problem: </td> <tr> <td> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp not confident
		<input type="radio" name="confidence" value = 1 id = "one" size= 20  >
		<input type="radio" name="confidence" value = 2 id = "two" size= 20  >
		<input type="radio" name="confidence" value = 3 id = "three" size= 20  >
		<input type="radio" name="confidence" value = 4 id = "four" size= 20  >
		<input type="radio" name="confidence" value = 5 id = "five" size= 20  >
		very confident</td></tr><tr></tr>
			<p></p>	
	</table>
		</div>
		
		
	<div id = "conf_div_np">
	<table>		
		<td>How confident are you in your understanding of the concepts underlying this problem: </td><tr>  <td> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp not confident
		<input type="radio" name="confidence_np" value = 1 id = "one" size= 20  >
		<input type="radio" name="confidence_np" value = 2 id = "two" size= 20  >
		<input type="radio" name="confidence_np" value = 3 id = "three" size= 20  >
		<input type="radio" name="confidence_np" value = 4 id = "four" size= 20  >
		<input type="radio" name="confidence_np" value = 5 id = "five" size= 20  >
		very confident</td></tr>
		<p></p>	
		</table>
		</div>
		

		
		<div id = "too_long_div"> 
		
	If I had to do it all over again I would have:  <br> 
		&nbsp &nbsp <input type="checkbox" name="too_long" value = 1 id = "one" size= 20  >&nbsp &nbsp looked at the problem ealier so I could have come up with a more efficient solution strategy <br>
		&nbsp &nbsp <input type="checkbox" name="too_long" value = 2 id = "two" size= 20  >&nbsp &nbsp sought help earlier <br>
		&nbsp &nbsp <input type="checkbox" name="too_long" value = 3 id = "three" size= 20  >&nbsp &nbsp  spent more time in understanding what the problem was asking <br>
		&nbsp &nbsp <input type="checkbox" name="too_long" value = 4 id = "four" size= 20  >&nbsp &nbsp   used different tools to solve the problemd <br>
		&nbsp &nbsp <input type="checkbox" name="too_long" value = 5 id = "five" size= 20  >&nbsp &nbsp  been more systematic in my problem solving approach <br>
		&nbsp &nbsp <input type="checkbox" name="too_long" value = 6 id = "six" size= 20  >&nbsp &nbsp  solved a simpler problem before attempting this one <br>
		&nbsp &nbsp <input type="checkbox" name="too_long" value = 7 id = "seven" size= 20  >&nbsp &nbsp  nothing. I did everything I could <br>
	</div>
	 <hr>
		<p><b><font Color="red">When Finished:</font></b></p>
		  <b><input type="submit" value="Submit Survey" style = "width: 30%; background-color:yellow "></b>
		 <p><br> </p>
	 <hr>	
		
		<div id = "p_comments_div"> 
		<br>
		Comments or suggestions for this problem (optional):  <br>
			&nbsp &nbsp <textarea name="prob_comments" id = "one" cols = "100" rows = "2"  maxlength = "2000" ></textarea>
		</div>		
		
		
		<div id = "hints_div"> 
		<br>
		Suggestions for hint topics related to this problem (optional):  <br>
			&nbsp &nbsp <textarea name="sug_hints" id = "one" cols = "100" rows = "2"  maxlength = "2000" ></textarea>
		</div>	

		
</form>
	 
	 <!--<div id = "qr_comments_div"> 
	 <br>
		Comments or suggestions for the QRhomework system:  <br>
			&nbsp &nbsp <textarea name="qr_comments" id = "one" cols = "100" rows = "2" placeholder = "optional" maxlength = "2000" ></textarea>
		</div>		
	
	





			<form action="upload_work.php" id = "move_on" method="POST">
				
				  <input type = "number" hidden id = "ec_elgible_flag" name = "ec_elgible_flag"  value = <?php echo ($ec_elgible_flag); ?> > </input>
	               <input type = "number" hidden name = "activity_id"  value = <?php echo ($activity_id); ?> > </input>

                 <input type = "number" hidden name = "problem_id"  value = <?php echo ($problem_id); ?> > </input>
				  <input type = "number" hidden name = "iid"  value = <?php echo ($iid); ?> > </input>
					<input type = "number" hidden name = "pin"  value = <?php echo ($pin); ?> > </input>
					 <input type = "number" hidden name = "count"  value = <?php echo ($count); ?> > </input>
				
				<b><input id "move_on_submit" hidden type="submit" ></b>
				
				</form>

 -->




	<?php
/* 
	if ($move_on){
		
		
		echo ('<script> document.getElementById("move_on").submit() </script>');
		
	}
 */

	?>




	<script>






	$(document).ready(function(){
		
	//	$('#move_on_submit').hide();
		
		var ec_elgible_flag = $('#ec_elgible_flag').val();
        var perf_num_score_flag = $('#perf_num_score_flag').val();
		
		if(perf_num_score_flag==1){
            $('#not_perfect').hide();
            $('#time_start_np').hide();
            $('#time_start').show();
            $('#too_long_div').hide();
            $('#conf_div_np').hide();
            $('#conf_div').show();
            $('#time_take1_np').hide();
            $('#time_take1').show();
        } else {
              $('#time_take2').hide();
            $('#time_start').hide();
            $('#time_start_np').show();
            $('#eff_div').hide();
            $('#diff_div').hide();
            $('#too_long_div').hide();
            $('#conf_div_np').show();
            $('#conf_div').hide();
            $('#time_take1_np').show();
            $('#time_take1').hide();
        }
        
        if (ec_elgible_flag == 1){
            $('#peer_instruction').show();
		} else {
            $('#peer_instruction').hide();
		}
		
   });

	</script>

	</main>



	<footer>
	<!--<p>This is the footer</p> -->
	</footer>
	</body>
	</html>