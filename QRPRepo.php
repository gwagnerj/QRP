<?php
	require_once "pdo.php";
	session_start();
    
    
    
    // Should put the redirects up hear or the header location will have already been sent
?>




	 <!DOCTYPE html>
	<html lang = "en">
	<head>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta Charset = "utf-8">
	<title>QRP Repo</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 
	<style>
	div {
		/*background-color: #eee;*/
		width: 100%;
		height: 100%;
		border: 1px dotted black;
		overflow: auto;
	}
	</style>
	<style type="text/css">

	body {
	   margin: 0;
	   overflow: hidden;
	}

	#request_prob{
		text-align: right;
		 color: blue;
		width: 100%;
		height: 100%;
		border: none;
		overflow: auto;
		padding:0px;
		margin:0px;
		font-size:70%;
		
	}


	#iframediv{
		position:relative;
		overflow:hidden;
		padding-top: 60%
	}
	#iframe1 {
		position:absolute;
		align:bottom;
		left: 0px;
		width: 100%;
		top: 0px;
		height: 100%;
	}
	table.a {
		table-layout: fixed;
		width: 100%;    
		}
		 .widget-1 { width:100px; } 
		  .widget-2 { width:150px; } 
		  .widget-3 { width:150px; } 
		  .widget-4 { width:150px; } 
		  .widget-5 { width:100px; } 
		  .widget-6 { width:150px; } 
		  .widget-7 { width:150px; } 
		  .widget-8 { width:150px; } 
		  .widget-9 { width:150px; } 
		  .widget-10 { width:150px; } 
		  .widget-11 { width:150px; } 
		  .widget-12 { width:150px; } 
		   .widget-13 { width:150px; }
            .widget-14 { width:150px; }  
            .widget-15 { width:150px; }            
             .widget-16 { width:150px; }            
             .widget-17 { width:150px; }            
			.widget-0 { width:150px; } 
		 
		 
		 
	.column-filter-widget { float:left; padding: 20px; border : none; width:200px;}
	.column-filter-widget select { display: block; }
	.column-filter-widgets a.filter-term { display: block; text-decoration: none; padding-left: 10px; font-size: 90%; }
	.column-filter-widgets a.filter-term:hover { text-decoration: line-through !important; }
	.column-filter-widget-selected-terms { clear:left; }
		
	.half-line {
		line-height: 0.5em;
	}	
		
	</style>
							
		
		
							<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
							<link rel="stylesheet" type="text/css" href="DataTables-1.10.18/css/jquery.dataTables.css"/> 
							<script type="text/javascript" src="DataTables-1.10.18/js/jquery.dataTables.js"></script>
							<script type="text/javascript" charset="utf-8" src="DataTables-1.10.18/extras/js/ColumnFilterWidgets.js"></script>
		
		
				<!-- THis is from sparklines jquery plugin   -->	

				<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.js"></script>
		
	</head>

	<body>
	<header>
	<h2>Quick Response Problems</h2>
	 <font size = '1' color = 'Blue'>(Try "ctrl +" or "ctrl -" to resize)</font>
	</header>


	  
<?php
	if ( isset($_SESSION['error']) ) {
		echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
		unset($_SESSION['error']);
	}
	if ( isset($_SESSION['success']) ) {
		echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
		unset($_SESSION['success']);
	}

	$preview="Null";
	//if they request the file then set the $preview variable to the name of the file
	if (isset($_POST['preview']) ){
		$preview='uploads/'.htmlentities($_POST['preview']);
	}
	if (isset($_POST['soln_preview']) ){
			$preview='uploads/'.htmlentities($_POST['soln_preview']);
	}

	//find out what kind of security level they have if they are logged in 
	if(isset($_SESSION['username'])){
		$username=$_SESSION['username'];
	$sql = " SELECT * FROM Users where username = :username";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
			':username' => $username));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$security = $row['security'];
			$user_sponsor_id = $row['sponsor_id'];
			$user_grade_level = $row['grade_level'];
			$user_university = $row['university'];
			
			$users_id=$row['users_id'];
			$_SESSION['iid']=$users_id;
			$suspended = $row['suspended'];
			$TA_course_1 = $row['TA_course_1'];
			$TA_course_2 = $row['TA_course_2'];
			$TA_course_3 = $row['TA_course_3'];
			$TA_course_4 = $row['TA_course_4'];
			$user_signon_date = $row['created_at'];
			$users_exp_date = $row['exp_date'];
	}
	
	if ($suspended == 1){
		 $_SESSION['failure'] = 'Please Check with Administrator wagnerj@trine.edu - Account has been Suspended';
		 header("location: login.php");
		 die();
	}
	
	// check to see if the user is past the exp date
		$now = time();
		$exp_date = strtotime($users_exp_date);
		$diff = $now - $exp_date;
		if ( strtolower($exp_date) != 'null' && $exp_date != 0 && $diff > 0 ) {
			 $_SESSION['failure'] = 'Please Check with Administrator wagnerj@trine.edu - Account is past the expiration date';
			 header("location: login.php");
			 die();
		}
	
// find out what kind of threat Level is currently active
	$sql = 'SELECT * FROM `Threat` ORDER BY `threat_id` DESC LIMIT 1';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(
					));
				
					$t_row = $stmt->fetch(PDO::FETCH_ASSOC);
					$threat_level = $t_row['threat_level'];
					
					
	// check to see if the userhas signed up in the last three months and the threat level is high				

	$signon_date = strtotime($user_signon_date);
			$diff = $now - $signon_date; 
			$crit = 3*30*24*3600; // 3 months in secounds
			
			if ( $diff < $crit && $threat_level >=3 ) {  // not allowing new users in when the threat level is high
				 $_SESSION['failure'] = 'Please Check with Administrator wagnerj@trine.edu - Threat level is too high and your account will be restored as soon as possible';
				 header("location: login.php");
				 die();
			}
			if ($threat_level == 4 && $security != 'admin'){  // locks everyone out except the administrators when the threat level gets to 4
				 $_SESSION['failure'] = 'Please Check with Administrator wagnerj@trine.edu - Threat level is too high system has been locked down - your account will be restored as soon as possible';
				 header("location: login.php");
				 die();
			}
				


	if (isset($_SESSION['username'])){
		if ($security =='admin'){
			
			echo '<a href="threat_change.php">Change Threat Level </b></a>';
			echo '&nbsp; &nbsp;&nbsp;';
			echo '<a href="delete_expired.php">Delete Expired Activity</b></a>';
			echo '&nbsp; &nbsp;&nbsp;';
		}
		if ($security =='admin' || $security =='contrib' || $security =='instruct'){
			
			echo '<a href="Current_Class.php">Add / Delete Current Classes </b></a>';
			echo '&nbsp; &nbsp;&nbsp;';
			echo '<a href="QRhomeworkBypass.php" target = "_blank">Look at Active Problem </a>';
			echo '&nbsp; &nbsp;&nbsp;';
			echo '<a href="checkerBypass.php" target = "_blank">Solution Check Problem</b></a>';
			echo '&nbsp; &nbsp;&nbsp;';}
          

// Put this in so students or TA's counld run game 11 March 2020 - may want to rethink this later ========================================

          echo '<a href="QRGameMasterStart.php" >Game Master Screen</b></a>';
			echo '</p>';
            echo ('<style> form {display:inline}</style>');
            echo('<form action = "QRExamStart.php" method = "POST"> <input type = "hidden" name = "iid" value = "'.$users_id.'"><input type = "submit" value ="Start Exam"></form> &nbsp;');
            echo('<form action = "QRExamRetrieve.php" method = "POST"> <input type = "hidden" name = "iid" value = "'.$users_id.'"><input type = "submit" value ="Retrieve Exam Results"></form>');

          //     echo '<a href="QRExamStart.php?iid ='.$users_id.'" >Start an Exam</b></a>';
			echo '&nbsp; &nbsp;&nbsp;';
		
		
		// check to see if the current user has sponsored anyone - if yes then allow them to suspend the them
		$sql = 'SELECT * FROM `Users` WHERE `sponsor_id` = :users_id';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(
					':users_id' => $users_id
					));
		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)!= false) {
		
		echo '<a href="suspend_user.php">Suspend / unsuspend one of users you sponsored</b></a>';
			echo '<br>';
		}
		if (($security == 'admin' || $security == 'contrib' || $security == 'stu_contrib') && $threat_level <= 3){
		echo '<div id = "request_prob">';
		echo '<b>Contributing a New Problem? </br>';
		echo '<a href="requestPblmNum.php">Request Problem Number</b></a>';
		echo '</div>';
		
	}
		//echo '<br>';
		echo '<hr>';
		echo '<a href="login.php"><b>logout</b></a>';
		echo ' <p> </p> ';
		echo ' <b> Filter Criteria: </b>';
	} else {
	   echo '<hr>';
	   echo '<p><h4>log in to use repository <a href="login.php">Login here</a>.</h4></p>';
	   echo '<br>';
	}


	echo ('<table id="table_format" class = "a" border="1" >'."\n");
		
		 echo("<thead>");

		echo("</td><th>");
		echo('Num');
		echo("</th><th>");
		echo('eff');
		echo("</th><th>");
		echo('diff');
		 echo("</th><th>");
		
		echo('Spec');
		echo("</th><th>");
		echo('Contrib');
		 echo("</th><th>");
		echo('Enhanc');
		echo("</th><th>");
		echo('Ref');
		echo("</th><th>");
		echo('Discip');
		 echo("</th><th>");
		 echo('Course');
		echo("</th><th>");
		echo('Concept');
		echo("</th><th>");
		echo('Compute');
		echo("</th><th>");
		echo('Title');
		echo("</th><th>");
		echo('Status');
		echo("</th><th>");
        echo('Class');
		echo("</th><th>");
        echo('Asn');
        echo("</th><th>");
        echo('Exam');
		echo("</th><th>");
         echo('pblm');
		echo("</th><th>");
		echo('Author');
		echo("</th><th>");
		 echo('Func');
		   echo("</th><th>");
		 echo('Display');
		//echo("</th><th>");
		// echo('Soln');
		echo("</th></tr>\n");
		 echo("</thead>");
		 
		  echo("<tbody>");
		//
		
		
		
		// add the effectiveness and rating stuff here so I can either display it or compute the average and display that along with the total ratings
		
	$qstmnt="SELECT Problem.problem_id AS problem_id,Users.username AS username, Users.first AS name,Problem.subject as subject,Problem.course as course,Problem.primary_concept as p_concept,Users.users_id as users_id,
	Problem.secondary_concept as s_concept,Problem.title as title,Problem.specif_ref as ref,Problem.status as status, Problem.soln_pblm as soln_pblm,Problem.game_prob_flag as game_prob_flag, 
	Problem.nm_author as nm_author,Problem.docxfilenm as docxfilenm,Problem.infilenm as infilenm,Problem.pdffilenm as pdffilenm,
	Problem.eff_stu_1 as eff_stu_1,Problem.eff_stu_2 as eff_stu_2,Problem.eff_stu_3 as eff_stu_3,Problem.eff_stu_4 as eff_stu_4,Problem.eff_stu_5 as eff_stu_5,
	Problem.diff_stu_1 as diff_stu_1,Problem.diff_stu_2 as diff_stu_2,Problem.diff_stu_3 as diff_stu_3,Problem.diff_stu_4 as diff_stu_4,Problem.diff_stu_5 as diff_stu_5,
	Problem.t_take1_1 as t_take1_1,Problem.t_take1_2 as t_take1_2,Problem.t_take1_3 as t_take1_3,Problem.t_take1_4 as t_take1_4,Problem.t_take1_5 as t_take1_5,Problem.t_take1_6 as t_take1_6,Problem.t_take1_7 as t_take1_7,
	Problem.t_take1_np_1 as t_take1_np_1,Problem.t_take1_np_2 as t_take1_np_2,Problem.t_take1_np_3 as t_take1_np_3,Problem.t_take1_np_4 as t_take1_np_4,Problem.t_take1_np_5 as t_take1_np_5, Problem.t_take1_np_6 as t_take1_np_6,Problem.t_take1_np_7 as t_take1_np_7,
	Problem.t_take2_1 as t_take2_1,Problem.t_take2_2 as t_take2_2,Problem.t_take2_3 as t_take2_3,Problem.t_take2_4 as t_take2_4,Problem.t_take2_5 as t_take2_5,Problem.t_take2_6 as t_take2_6,Problem.t_take2_7 as t_take2_7,
	Problem.t_b4due_1 as t_b4due_1,Problem.t_b4due_2 as t_b4due_2,Problem.t_b4due_3 as t_b4due_3,Problem.t_b4due_4 as t_b4due_4,Problem.t_b4due_5 as t_b4due_5,Problem.t_b4due_6 as t_b4due_6,Problem.t_b4due_7 as t_b4due_7,
	Problem.t_b4due_np_1 as t_b4due_np_1,Problem.t_b4due_np_2 as t_b4due_np_2,Problem.t_b4due_np_3 as t_b4due_np_3,Problem.t_b4due_np_4 as t_b4due_np_4,Problem.t_b4due_np_5 as t_b4due_np_5, Problem.t_b4due_np_6 as t_b4due_np_6, Problem.t_b4due_np_7 as t_b4due_np_7,
	Problem.confidence_1 as confidence_1,Problem.confidence_2 as confidence_2,Problem.confidence_3 as confidence_3,Problem.confidence_4 as confidence_4,Problem.confidence_5 as confidence_5,
	Problem.confidence_np_1 as confidence_np_1,Problem.confidence_np_2 as confidence_np_2,Problem.confidence_np_3 as confidence_np_3,Problem.confidence_np_4 as confidence_np_4,Problem.confidence_np_5 as confidence_np_5,
	 Users.university as s_name, Problem.preprob_3 as mc_prelim, Problem.preprob_4 as misc_prelim, Problem.hint_a as hint_a, Problem.hint_b as hint_b, Problem.hint_c as hint_c, Problem.hint_d as hint_d, Problem.hint_e as hint_e,
	 Problem.hint_f as hint_f,Problem.hint_g as hint_g,Problem.hint_h as hint_h, Problem.hint_i as hint_i, Problem.hint_j as hint_j, Problem.video_clip as video_clip, Problem.simulation as simulation, Problem.demonstration_directions as demo_directions,
	 Problem.activity_directions as activity_directions, Problem.computation_name as computation_name, Problem.allow_clone as allow_clone, Problem.allow_edit as allow_edit, Problem.parent as parent, Problem.children as children, Problem.orig_contr_id as orig_contr_id,
	Problem.edit_id1 as edit_id1, Problem.edit_id2 as edit_id2, Problem.edit_id3 as edit_id3
	FROM Problem LEFT JOIN Users ON Problem.users_id=Users.users_id ORDER BY problem_id DESC";


	$stmt = $pdo->query($qstmnt);
	while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		
		// needed to put this in for the grader criteria - copied from furthrer down on active
               $sql = "SELECT assign_num FROM Assign WHERE prob_num = :prob_num AND iid = :iid";
                $stmt8 = $pdo->prepare($sql);
                $stmt8 -> execute(array(
                     ':prob_num' => $row['problem_id'],
                      ':iid' => $user_sponsor_id,
                    ));
                    $row2 = $stmt8->fetch(PDO::FETCH_ASSOC); 

          
		
		
		
	   if($row['game_prob_flag']== 0 && 
		   (
				    ( $users_id == $row['edit_id1'] || $users_id == $row['edit_id2'] || $users_id == $row['edit_id3'] )||
				   //($security =='stu_contrib' && $user_sponsor_id == $row['users_id']) ||
                   //  ($security =='stu_contrib' && $user_sponsor_id == $row['users_id']) ||
				    ($security =='stu_contrib' && $users_id == $row['users_id']) ||
				   ($security == 'grader' && $row2 !=false)||
				   ($security == 'admin')||
				   ($security == 'contrib')||
				   ($security == 'instruct') ||
				   ($security == 'TA' && ($row['course'] == $TA_course_1 || $row['course'] == $TA_course_2 || $row['course'] == $TA_course_3 || $row['course'] == $TA_course_4))
		   )
	   )


	   {
			 echo "<tr><td>";
			
			
			echo(htmlentities($row['problem_id']));
			
			echo("</td><td>");	
			
			if(!isset($row["eff_stu_1"])){$eff_stu_1 = 0;} else {$eff_stu_1 = $row["eff_stu_1"];}
			if(!isset($row["eff_stu_2"])){$eff_stu_2 = 0;} else {$eff_stu_2 = $row["eff_stu_2"];}
			if(!isset($row["eff_stu_3"])){$eff_stu_3 = 0;} else {$eff_stu_3 = $row["eff_stu_3"];}
			if(!isset($row["eff_stu_4"])){$eff_stu_4 = 0;} else {$eff_stu_4 = $row["eff_stu_4"];}
			if(!isset($row["eff_stu_5"])){$eff_stu_5 = 0;} else {$eff_stu_5 = $row["eff_stu_5"];}
			
			if(!isset($row["confidence_np_1"])){$confidence_np_1 = 0;} else {$confidence_np_1 = $row["confidence_np_1"];}
			if(!isset($row["confidence_np_2"])){$confidence_np_2 = 0;} else {$confidence_np_2 = $row["confidence_np_2"];}
			if(!isset($row["confidence_np_3"])){$confidence_np_3 = 0;} else {$confidence_np_3 = $row["confidence_np_3"];}
			if(!isset($row["confidence_np_4"])){$confidence_np_4 = 0;} else {$confidence_np_4 = $row["confidence_np_4"];}
			if(!isset($row["confidence_np_5"])){$confidence_np_5 = 0;} else {$confidence_np_5 = $row["confidence_np_5"];}
			
			$confidence_np_tot = $confidence_np_1+$confidence_np_2+$confidence_np_3+$confidence_np_4+$confidence_np_5;
			
			$eff_stu_tot = $eff_stu_1+$eff_stu_2+$eff_stu_3+$eff_stu_4+$eff_stu_5;
			
			if($eff_stu_tot==0) {
				
			echo(' ');	
			} else {
				
				$tot_eff_score =  $eff_stu_1*1+$eff_stu_2*2+$eff_stu_3*3+$eff_stu_4*4+$eff_stu_5*5;
				$ave_eff = round($tot_eff_score/$eff_stu_tot*10)/10;
				
				echo('<font size="2"> ave = '.$ave_eff);
				
				echo('<span class="inlinebar1">'.$eff_stu_1.", ".$eff_stu_2.", ".$eff_stu_3.", ".$eff_stu_4.", ".$eff_stu_5.'</span>');	
						echo('<br><font size="1"> &nbsp;&nbsp; eff'."</font>");
					echo('<font size="1"> &nbsp;&nbsp; n ='.$eff_stu_tot."</font>");
			}
			
		  
			echo("</td><td>");
			
			if(!isset($row["diff_stu_1"])){$diff_stu_1 = 0;} else {$diff_stu_1 = $row["diff_stu_1"];}
			if(!isset($row["diff_stu_2"])){$diff_stu_2 = 0;} else {$diff_stu_2 = $row["diff_stu_2"];}
			if(!isset($row["diff_stu_3"])){$diff_stu_3 = 0;} else {$diff_stu_3 = $row["diff_stu_3"];}
			if(!isset($row["diff_stu_4"])){$diff_stu_4 = 0;} else {$diff_stu_4 = $row["diff_stu_4"];}
			if(!isset($row["diff_stu_5"])){$diff_stu_5 = 0;} else {$diff_stu_5 = $row["diff_stu_5"];}
			
			$confidence_np_tot = $confidence_np_1+$confidence_np_2+$confidence_np_3+$confidence_np_4+$confidence_np_5;
			
			$diff_stu_tot = $diff_stu_1+$diff_stu_2+$diff_stu_3+$diff_stu_4+$diff_stu_5;
			$tot_attempt = $confidence_np_tot+$diff_stu_tot;
			if($tot_attempt!=0){
				$percent_np = round($confidence_np_tot/($confidence_np_tot+$diff_stu_tot)*100);
			} else {
				$percent_np= '';
			}
			if($diff_stu_tot==0) {
				
				echo(' ');	
			} else {
				
					$tot_diff_score =  $diff_stu_1*1+$diff_stu_2*2+$diff_stu_3*3+$diff_stu_4*4+$diff_stu_5*5;
					$ave_diff = round($tot_diff_score/$diff_stu_tot*10)/10;
					echo('<font size="2"> ave = '.$ave_diff);
					echo('<span class="inlinebar2">'.$diff_stu_1.", ".$diff_stu_2.", ".$diff_stu_3.", ".$diff_stu_4.", ".$diff_stu_5.'</span>');
					echo('<br><font size="1"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; diff'."</font>");
					
					
					if ($percent_np!=0){
							echo('<br><font size="1"> n_tot ='.$tot_attempt."</font>");
							echo('<br><font size="1"> < 100 ='.$percent_np.' %'."</font>");
					}
			}
			
			echo("</td><td>");
			

            echo('<a target = "_blank" href="QRPStuResults.php?problem_id='.$row['problem_id'].'">Show</a> ');            
			/* 	
				if ($tot_attempt ==0){
					echo(' ');	
				} else {
						echo('<a target = "_blank" href="QRPStuResults.php?problem_id='.$row['problem_id'].'">Show</a> ');
				}
				 */
				// if it is clonable and the user is a contributor or admin then put up a clone botton
				if (($security == 'contrib' || $security == 'admin') && $row['allow_clone'] ==1){
							echo('<form action = "clone.php" method = "POST"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "submit" value ="Clone"></form>');

				}
				if ($row['parent'] != 0){
					echo 'parent: </br>'.$row['parent'].'</br>';
					echo 'by: </br>';
					echo 'user id '.$row['orig_contr_id'];
				}
				if ($row['children'] != ''){
					echo 'children: </br>'.$row['children'];
				}
				
			echo("</td><td>");
			
			
			echo(htmlentities($row['name']));
			echo("</td><td>");
			// these are the problem assets
			if ($row['hint_a']!=NULL || $row['hint_b']!=NULL ||$row['hint_c']!=NULL ||$row['hint_d']!=NULL ||$row['hint_e']!=NULL ||$row['hint_f']!=NULL ||$row['hint_g']!=NULL ||$row['hint_h']!=NULL ||$row['hint_i']!=NULL ||$row['hint_j']!=NULL) {
				echo('hints ');
			}
			if ($row['mc_prelim']!= Null){
				echo('MC prelim ');
			}
			if ($row['video_clip']== 1){
				echo('Video Clip ');
			}
			if ($row['simulation']== 1){
				echo('Simulation ');
			}
			
			
			echo("</td><td>");
			echo(htmlentities($row['ref']));
			echo("</td><td>");
			echo(htmlentities($row['subject']));
			echo("</td><td>");  
			echo(htmlentities($row['course']));
			echo("</td><td>");
			
/* 			$sec_des="";
			if(strlen($row['s_concept'])!=0){$sec_des="<br>2)&nbsp;";}
			
			echo("1)&nbsp;".htmlentities($row['p_concept']).$sec_des.htmlentities($row['s_concept'])); 
*/			
			echo (htmlentities($row['p_concept']));
			echo("</td><td>");
			echo(htmlentities($row['computation_name']));
			echo("</td><td>");  
			echo(htmlentities($row['title']));
			echo("</td><td>");
			// if we have over 7 students that have completed it successfully we should change the status to circulated if it is not already
			if($eff_stu_tot > 6 && $row['status'] != 'Circulated'){
				$status_update = 'Circulated';
				$sql = "UPDATE Problem SET status = :status WHERE problem_id =:problem_id";
				$stmt4 = $pdo->prepare($sql);
					$stmt4->execute(array(
					'status' => $status_update,
					'problem_id' => $row['problem_id']
					));
			} else {
				$status_update = '';
			}
			
			// if it is active for this user print active for the status
					$sql = "SELECT Assign.assign_num AS assign_ass_num, Assign.alias_num AS alias_num,Assign.currentclass_id as currentclass_id FROM Assign WHERE prob_num = :prob_num and iid = :iid";
                    $stmt8 = $pdo->prepare($sql);
                    $stmt8 -> execute(array(
                     ':prob_num' => $row['problem_id'],
                      ':iid' => $users_id,
                    ));
                    $row2 = $stmt8->fetch(PDO::FETCH_ASSOC); 
                    
                    
                   /*  
                    $asstmnt = "SELECT Assign.assign_num AS assign_ass_num, Assign.alias_num AS alias_num,Assign.currentclass_id as currentclass_id
					FROM Assign 
					WHERE (Assign.prob_num =". $row['problem_id']." AND Assign.iid=".$users_id.");";
						
					$stmt2 = $pdo->query($asstmnt);
					 $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                      */
                     
					if($row2 == false){
						if($status_update =='Circulated'){
							echo('Circulated');
						} else {
						echo($row['status']);
						}
                         $active_flag = 0;
					} else {
                        $active_flag = 1;
                       // if I turn it red the filter wont pick it up
                       echo ('Active');
                       
						//echo('<span style = "color: red;" > Active </br> Class# '.$row2["currentclass_id"].'</br> Asn '.$row2["assign_ass_num"].'</br> Pblm '.$row2["alias_num"] .'<br>  </span>');
					}
                    
 

 

                   // if it is Staged for an exam by the user this user print staged for the status
					$sql = "SELECT Exam.exam_num AS exam_e_num, Exam.alias_num AS e_alias_num,Exam.currentclass_id as e_currentclass_id FROM Exam WHERE problem_id = :problem_id AND iid = :iid";
                   $stmt9 = $pdo->prepare($sql);
                    $stmt9 -> execute(array(
                     ':problem_id' => $row['problem_id'],
                      ':iid' => $users_id,
                    ));
                    $row2e = $stmt9->fetch(PDO::FETCH_ASSOC); 
/*                     
                    $asstmnt = "SELECT Exam.exam_num AS exam_e_num, Exam.alias_num AS e_alias_num,Exam.currentclass_id as e_currentclass_id
					FROM Exam 
					WHERE (Exam.problem_id =". $row['problem_id']." AND Exam.iid=".$users_id.");";
					$stmt2e = $pdo->query($asstmnt);
                    
                 
					 $row2e = $stmt2e->fetch(PDO::FETCH_ASSOC);
     */                    
                     
                     
					if($row2e == false){
                         $staged_flag = 0;
					} else {
                        $staged_flag = 1;
                       // if I turn it red the filter wont pick it up
                       echo ('<br> Staged ');
                       
						//echo('<span style = "color: red;" > Active </br> Class# '.$row2["currentclass_id"].'</br> Asn '.$row2["assign_ass_num"].'</br> Pblm '.$row2["alias_num"] .'<br>  </span>');
					}
                    
                    
                    
					// test to see if it is being used by other people and display in use
				
                $sql = "SELECT Assign.instr_last AS instr_last_nm FROM Assign WHERE prob_num = :problem_id AND iid <> :iid";
                   $stmt5 = $pdo->prepare($sql);
                    $stmt5 -> execute(array(
                     ':problem_id' => $row['problem_id'],
                      ':iid' => $users_id,
                    ));
/* 

                $usestmnt = "SELECT Assign.instr_last AS instr_last_nm 
					FROM Assign 
					WHERE (Assign.prob_num =". $row['problem_id']." AND Assign.iid <>".$users_id.");";
						$stmt5 = $pdo->query($usestmnt);
                    */     
                        
                        
						$i=1;
						while ( $row5 = $stmt5->fetch(PDO::FETCH_ASSOC) ) {
								if($i==1){
									echo('<br><font size=1> in use by:');
									$i=0;
								}
							echo("<br><font size=1>".$row5['instr_last_nm']);
						}
					
			
			echo("</td><td>");
            if ($active_flag ==1){
                
                // echo ($row2["currentclass_id"]);
                 // find the current class from the Currentclass table
              
                $sql = "SELECT CurrentClass.name AS class_name FROM CurrentClass WHERE currentclass_id = :currentclass_id";
                  $stmt6 = $pdo->prepare($sql);
                    $stmt6 -> execute(array(
                     ':currentclass_id' => $row2['currentclass_id'],
                    ));
/* 
              $stmnt = "SELECT CurrentClass.name AS class_name
					FROM CurrentClass
					WHERE (currentclass_id = ".$row2['currentclass_id'].");";
                 $stmt6 = $pdo->query($stmnt);
                 
                 */ 
                 
                 $row6 = $stmt6->fetch(PDO::FETCH_ASSOC);
              
                 $class_words = str_word_count($row6['class_name'],1); // an array of the words
                 foreach ($class_words as $class_word){
                        echo(substr($class_word,0,4));
                        echo(" ");
                 }
               
            }
            
              if ($staged_flag ==1){

                $sql = "SELECT CurrentClass.name AS class_name_e FROM CurrentClass WHERE currentclass_id = :currentclass_id";
                $stmt6e = $pdo->prepare($sql);
                    $stmt6e -> execute(array(
                     ':currentclass_id' => $row2e['e_currentclass_id'],
                    ));

/* 


                 // find the current class from the Currentclass table
                 $stmnte = "SELECT CurrentClass.name AS class_name_e
					FROM CurrentClass
					WHERE (currentclass_id = ".$row2e['e_currentclass_id'].");";
                 $stmt6e = $pdo->query($stmnte);
             */     
                 
                 $row6e = $stmt6e->fetch(PDO::FETCH_ASSOC);
              
                 $class_wordse = str_word_count($row6e['class_name_e'],1); // an array of the words
                 foreach ($class_wordse as $class_word){
                        echo(substr($class_word,0,4));
                        echo(" ");
                 }
               
            }
            
           
             echo("</td><td>");
              if ($active_flag ==1){
                   echo ($row2["assign_ass_num"]);
              }
            echo("</td><td>");
 
                 if ($staged_flag ==1){
                   echo ($row2e["exam_e_num"]);
              }


 
             echo("</td><td>");
            if ($active_flag ==1){
                echo ($row2["alias_num"]);
            }
            
             if ($staged_flag ==1){
                echo ($row2e["e_alias_num"]);
            }
            echo("</td><td>");
			echo(htmlentities($row['nm_author']));
			
			echo("</td><td>"); 
			if($row['username']==$username || $security=='admin' || ($security == 'contrib' && $row['allow_edit'] == 2) || (($security == 'contrib') && ($users_id == $row['edit_id1'] || $users_id == $row['edit_id2'] || $users_id == $row['edit_id3']))){
				echo('<form action = "editpblm.php" method = "GET"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "submit" value ="Edit"></form>');
			}
			if($row['username']==$username || $security=='admin'){
				echo("<p class='half-line'>");
			//	echo('<a href="editpblm.php?problem_id='.$row['problem_id'].'">Edit</a> / ');
				echo('<form action = "deletepblm.php" method = "GET"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "submit" value ="Del"></form>');
				echo("<p class='half-line'>");
				echo('<form action = "suspendpblm.php" method = "GET"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "submit" value ="susp/uns"></form>');
				echo("<p class='half-line'>");
			
//				echo('<a href="deletepblm.php?problem_id='.$row['problem_id'].'">Del</a> / ');
			//	echo('<a href="suspendpblm.php?problem_id='.$row['problem_id'].'">Susp-unSus</a> / ');
			}
			
			if ($security != 'grader'){
			echo('<form action = "QRactivatePblm.php" method = "GET" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "hidden" name = "users_id" value = "'.$users_id.'"><input type = "submit" value ="Activate"></form>');

		//	echo('<a href="QRactivatePblm.php?problem_id='.$row['problem_id'].'&users_id='.$users_id.'">Act-deAct</a>');
				
			echo("</td><td>");
			}
			
			if($row['status']!='num issued') {
				echo('<form action = "getBC.php" method = "POST" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "hidden" name = "index" value = "1" ><input type = "submit" value ="Base-case"></form>');
				echo("&nbsp; ");
				echo('<form action = "getGame.php" method = "POST" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "hidden" name = "iid" value = "'.$users_id.'"><input type = "submit" value ="Game"></form>');
				echo("&nbsp; ");
				echo('<form action = "stageExam.php" method = "POST" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "hidden" name = "iid" value = "'.$users_id.'"><input type = "submit" value ="Stage Exam"></form>');
				echo("&nbsp; ");
				echo('<form action = "makeMoodleXML.php" method = "POST" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "hidden" name = "iid" value = "'.$users_id.'"><input type = "submit" value ="MoodleXML"></form>');

                
                if ($security == 'contrib' ||
                    $security == 'admin'||
                    $security == 'contrib'||
                    $security == 'instruct' ||
                    $security == 'TA'){
                echo("&nbsp; ");
				echo('<form action = "numericToMC.php" method = "POST" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "submit" value ="Make MC"></form>');

                   }


				
			}
		
			echo("</td></tr>\n");
	   }
	}

		echo("</tbody>");
		 echo("</table>");
		echo ('</div>');	



	//echo ('"'.$preview.'"');
?>

	<script>
		
		$(".inlinebar1").sparkline("html",{type: "bar", height: "50", barWidth: "10", resize: true, barSpacing: "5", barColor: "#7ace4c"});
		$(".inlinebar2").sparkline("html",{type: "bar", height: "50", barWidth: "10", resize: true, barSpacing: "5", barColor: "orange"});
		
		localStorage.setItem('MC_flag','false');  // initialize multiple choice flag to false
		
		
		
		$(document).ready( function () {
		$('#table_format').DataTable({"sDom": 'W<"clear">lfrtip',
			"order": [[ 0, 'dsc' ] ],
			 "lengthMenu": [ 50, 100, 200 ],
			"oColumnFilterWidgets": {
			"aiExclude": [ 0,1,2,3,6,11,17,18] }});
		

		// jQuery('#table_format').ddTableFilter();
		} );
		
		
	</script>

	</table>
	<p></p>
	<!-- <p><a href="add.php">Add New Manual</a></P> -->
	<!--<a href="addPblm.php">Add Data and Pblm Files</a> -->
	<p></p>


	<!-- <object data=<?php// echo('"'.$preveiw.'"'); ?> 
	type= "application/pdf" width="100%" Height="50%"> -->

	<?php 

	if($preview !== "uploads/" and $preview !== "Null") {
		echo ('<div id ="iframediv"><iframe id = "iframe1" src="'.$preview.'"'.'></iframe></div>');

	}
	?>
	<!-- </object> -->
	</body>
	</html>