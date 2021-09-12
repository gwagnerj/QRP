<?php
	require_once "pdo.php";
	session_start();
	
	// check get stuff from QRAssignmentStart0
 


 
 if (isset($_GET['iid'])) {
	$iid = $_GET['iid'];
    
    // fix this sloppy programming later
      if ((@$_POST['currentclass_id']==0 || @$_POST['currentclass_id']=='' || @$_POST['iid']==0 || @$_POST['iid']=='' )
          && (@$_GET['currentclass_id']==0 || @$_GET['currentclass_id']=='' || @$_GET['iid']==0 || @$_GET['iid']=='')) {
        $_SESSION['error'] = 'Class must be Selected';
        header( 'Location: QRAssignmentStart0.php?iid='.$iid ) ;
	   die();
    }
    
} else {
	 $_SESSION['error'] = 'invalid iid in QRAssignmentStart11.php ';
      header( 'Location: QRPRepo.php' ) ;
	 die();
}
 if (isset($_GET['currentclass_id'])&& $_GET['currentclass_id'] != 0 && $_GET['currentclass_id'] != '') {
	$currentclass_id = $_GET['currentclass_id'];
} else {
	 $_SESSION['error'] = 'invalid currentclass_id in QRAssignmentStart11.php ';
      header( 'Location: QRAssignmentStart0.php' ) ;
	 die();
}
 if (isset($_GET['assign_num']) && $_GET['assign_num'] != 0 && $_GET['assign_num'] != '') {
	$assign_num = $_GET['assign_num'];
} else {
	 $_SESSION['error'] = 'invalid assign_num in QRAssignmentStart11.php ';
      header( 'Location: QRAssignmentStart0.php' ) ;
	 die();
}
 if (isset($_GET['new_flag'])) {
	$new_flag = $_GET['new_flag'];
} else {
	 $_SESSION['error'] = 'invalid new_flag in QRAssignmentStart11.php ';
      header( 'Location: QRPRepo.php' ) ;
	 die();
}
// fix bug if no class is selected and get a pdo error____________________________________________

    // set the default values for the variables
    $bc_ans_t = 1;
    $bc_ans_n = 1;
    $p_bc_n = 3;
    $p_bc_t = 10;
    
    
    $help_n_stu = 99;
    $help_n_ta = 99;
    $help_n_instruct = 99;
    $help_n_hint = 2;
    
    
    $help_t_stu = 2;
    $help_t_ta = 5;
    $help_t_instruct = 10;
    $help_t_hint = 1;
    $time_sleep1_trip = 5;
    $time_sleep1 = 30;
    $time_sleep2_trip = 10;
    $time_sleep2 = 60;

    $work_time_per_problem = '';
    $max_attempts_per_problem = '';
    $perc_ec_max_p_assign = 20;
    $perc_ec_max_p_pblm = 20;
    $perc_ec_max_person_to_person = 5;
    $fixed_percent_decline = 34;
    
    $ec_daysb4due_elgible = 10;
    $ec_open_daysb4due_elgible = 2;
    
    $perc_ec_base_video = 3;
    $perc_ec_base_audio = 2;
    $perc_ec_base_written = 1;
    $peer_refl_n = 5;
    $peer_refl_t = 2;
    
    $assigntime_id = '';
    $window_opens = $window_closes = $due_date = '';
    
if($new_flag == 0){
    // redefine the varaibles read in from the Assigntime table values
          //  SELECT *, DATE_FORMAT(date_and_time, '%Y-%m-%dT%H:%i') AS custom_date 
       
    $sql = 'SELECT *, DATE_FORMAT(due_date, "%Y-%m-%dT%H:%i"),DATE_FORMAT(window_opens, "%Y-%m-%dT%H:%i")   FROM Assigntime WHERE currentclass_id = :currentclass_id AND iid = :iid AND assign_num = :assign_num';     
          $stmt = $pdo->prepare($sql);
           $stmt -> execute(array (
           ':currentclass_id' => $currentclass_id,
           ':assign_num' => $assign_num,
           ':iid' => $iid,
           )); 
            $assigntime_data = $stmt->fetch();   
            if($assigntime_data['time_sleep1_trip']!=NULL) { $time_sleep1_trip = $assigntime_data['time_sleep1_trip']; }
            if($assigntime_data['time_sleep1']!=NULL) { $time_sleep1 = $assigntime_data['time_sleep1']; }
            if($assigntime_data['time_sleep2_trip']!=NULL) { $time_sleep2_trip = $assigntime_data['time_sleep2_trip']; }
            if($assigntime_data['time_sleep2']!=NULL) { $time_sleep2 = $assigntime_data['time_sleep2']; }
            $bc_ans_t = $assigntime_data['bc_ans_t'];
          $bc_ans_n = $assigntime_data['bc_ans_n'];
          $p_bc_n = $assigntime_data['p_bc_n'];
          $p_bc_t = $assigntime_data['p_bc_t'];
          $help_n_instruct = $assigntime_data['help_n_instruct'];
          $help_n_hint = $assigntime_data['help_n_hint'];
          $help_n_ta = $assigntime_data['help_n_ta'];
          $help_n_stu = $assigntime_data['help_n_stu'];
          $help_t_instruct = $assigntime_data['help_t_instruct'];
          $help_t_hint = $assigntime_data['help_t_hint'];
          $help_t_ta = $assigntime_data['help_t_ta'];
          $help_t_stu = $assigntime_data['help_t_stu'];
          $work_time_per_problem = $assigntime_data['work_time_per_problem'];
          $max_attempts_per_problem = $assigntime_data['max_attempts_per_problem'];
          $perc_ec_max_p_assign = $assigntime_data['perc_ec_max_p_assign'];
          $perc_ec_max_p_pblm = $assigntime_data['perc_ec_max_p_pblm'];
          $perc_ec_max_person_to_person = $assigntime_data['perc_ec_max_person_to_person'];
         // $perc_ec_max_decrease = $assigntime_data['perc_ec_max_decrease'];
          
          $ec_daysb4due_elgible = $assigntime_data['ec_daysb4due_elgible'];
          $ec_open_daysb4due_elgible = $assigntime_data['ec_open_daysb4due_elgible'];
          $perc_ec_base_video = $assigntime_data['perc_ec_base_video'];
          $perc_ec_base_audio = $assigntime_data['perc_ec_base_audio'];
          $perc_ec_base_written = $assigntime_data['perc_ec_base_written'];
          $peer_refl_n = $assigntime_data['peer_refl_n'];
          $peer_refl_t =$assigntime_data['peer_refl_t'];
          
          
          $assigntime_id = $assigntime_data['assigntime_id'];
          $fixed_percent_decline = $assigntime_data['fixed_percent_decline'];
          $window_closes = new DateTime($assigntime_data['window_closes']);
          $window_opens = new DateTime($assigntime_data['window_opens']);
         
      //    $window_opens = date(DATE_RFC3339, strtotime($window_opens));
        //  $window_closes = $assigntime_data['window_closes'];
          $due_date = $assigntime_data['due_date'];
           $due_date = new DateTime($due_date);
          }



        // get the exp date from the CurrentClass table to set the default assignment clase date
            $sql = 'SELECT exp_date, DATE_FORMAT(exp_date, "%Y-%m-%dT%H:%i")  FROM CurrentClass WHERE currentclass_id = :currentclass_id ';     
          $stmt = $pdo->prepare($sql);
           $stmt -> execute(array (
           ':currentclass_id' => $currentclass_id,
           )); 
            $current_class_exp_date = $stmt->fetch();   
            $current_class_exp_date = $current_class_exp_date['exp_date'];
             $current_class_exp_date = strtotime($current_class_exp_date);
          //  echo('$current_class_exp_date '.$current_class_exp_date);
          //   $date = date('Y-m-d\TH:i',$current_class_exp_date);  echo($date);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_name'])) {
    
    
    if ($new_flag == 0) {
        
        $assigntime_id = $_POST['assigntime_id'];
        // update the table instead of insert
        
             $sql = 'UPDATE Assigntime SET 
             work_flow=:work_flow, 
             time_sleep1_trip =:time_sleep1_trip,
             time_sleep1 =:time_sleep1,
             time_sleep2_trip =:time_sleep2_trip,
             time_sleep2 =:time_sleep2,
             bc_ans_n=:bc_ans_n, 
             bc_ans_t=:bc_ans_t, 
             p_bc_n=:p_bc_n, 
             p_bc_t=:p_bc_t, 
             help_n_stu=:help_n_stu, 
             help_t_stu=:help_t_stu, 
             help_n_ta=:help_n_ta, 
             help_t_ta=:help_t_ta, 
             help_n_instruct=:help_n_instruct, 
             help_t_instruct=:help_t_instruct, 
             help_n_hint=:help_n_hint, 
             help_t_hint=:help_t_hint, 
             work_time_per_problem=:work_time_per_problem, 
             max_attempts_per_problem=:max_attempts_per_problem, 
             window_opens=:window_opens, 
             window_closes=:window_closes, 
             perc_ec_max_p_assign=:perc_ec_max_p_assign, 
             perc_ec_max_p_pblm=:perc_ec_max_p_pblm, 
             perc_ec_max_person_to_person=:perc_ec_max_person_to_person, 
             peer_refl_n = :peer_refl_n,
             peer_refl_t =:peer_refl_t,
             
             
             ec_daysb4due_elgible=:ec_daysb4due_elgible,
             ec_open_daysb4due_elgible=:ec_open_daysb4due_elgible,
             perc_ec_base_video=:perc_ec_base_video,
             perc_ec_base_audio=:perc_ec_base_audio,
             perc_ec_base_written=:perc_ec_base_written,
             
             
             due_date=:due_date, 
             credit=:credit, 
             late_points=:late_points, 
             fixed_percent_decline=:fixed_percent_decline
            WHERE assigntime_id = :assigntime_id';
             
            $stmt = $pdo->prepare($sql);
            $stmt -> execute(array(
              ':assigntime_id' => $assigntime_id,
              ':work_flow' => $_POST['work_flow'],
              ':time_sleep1_trip' => $_POST['time_sleep1_trip'],
              ':time_sleep1' => $_POST['time_sleep1'],
              ':time_sleep2_trip' => $_POST['time_sleep2_trip'],
              ':time_sleep2' => $_POST['time_sleep2'],
              ':bc_ans_n' => $_POST['bc_ans_n'],
              ':bc_ans_t' => $_POST['bc_ans_t'],
              ':p_bc_n' => $_POST['p_bc_n'],
              ':p_bc_t' => $_POST['p_bc_t'],
              ':help_n_stu' => $_POST['help_n_stu'],
              ':help_t_stu' => $_POST['help_t_stu'],
              ':help_n_instruct' => $_POST['help_n_instruct'],
              ':help_t_instruct' => $_POST['help_t_instruct'],
              ':help_n_hint' => $_POST['help_n_hint'],
              ':help_t_hint' => $_POST['help_t_hint'],
              ':help_n_ta' => $_POST['help_n_ta'],
              ':help_t_ta' => $_POST['help_t_ta'],
              ':work_time_per_problem' => $_POST['work_time_per_problem'],
              ':max_attempts_per_problem' => $_POST['max_attempts_per_problem'],
              ':window_opens' => $_POST['window_opens'],
              ':window_closes' => $_POST['window_closes'],
              ':perc_ec_max_p_assign' => $_POST['perc_ec_max_p_assign'],
              ':perc_ec_max_p_pblm' => $_POST['perc_ec_max_p_pblm'],
              ':perc_ec_max_person_to_person' => $_POST['perc_ec_max_person_to_person'],

              ':ec_daysb4due_elgible' => $_POST['ec_daysb4due_elgible'],
              ':ec_open_daysb4due_elgible' => $_POST['ec_open_daysb4due_elgible'],
              ':perc_ec_base_video' => $_POST['perc_ec_base_video'],
              ':perc_ec_base_audio' => $_POST['perc_ec_base_audio'],
              ':perc_ec_base_written' => $_POST['perc_ec_base_written'],
              ':peer_refl_t' => $_POST['peer_refl_t'],
              ':peer_refl_n' => $_POST['peer_refl_n'],
              ':due_date' => $_POST['due_date'],
              ':credit' => $_POST['credit'],
              ':late_points' => $_POST['late_points'],
              ':fixed_percent_decline' => $_POST['fixed_percent_decline'],
            ));


             
             
    } else {
    
   
   // input the values from the form into the Assigntime table - get the assigntime_id and then move onto page two to get points values for each part
   
       $sql = 'INSERT INTO `Assigntime` (assign_num, iid, currentclass_id, work_flow, time_sleep1_trip, time_sleep1,time_sleep2_trip, time_sleep2, bc_ans_n,bc_ans_t, p_bc_n, p_bc_t, help_n_stu, help_t_stu, help_n_ta, help_t_ta, help_n_instruct, help_t_instruct,help_n_hint, help_t_hint, work_time_per_problem, max_attempts_per_problem, window_opens, window_closes, perc_ec_max_p_assign, perc_ec_max_p_pblm, perc_ec_max_person_to_person, ec_daysb4due_elgible,ec_open_daysb4due_elgible,perc_ec_base_video, perc_ec_base_audio, perc_ec_base_written, peer_refl_t, peer_refl_n, due_date,credit, late_points, fixed_percent_decline)	
                                    VALUES (:assign_num, :iid, :currentclass_id, :work_flow, :time_sleep1_trip, :time_sleep1, :time_sleep2_trip, :time_sleep2,:bc_ans_n,:bc_ans_t, :p_bc_n, :p_bc_t, :help_n_stu, :help_t_stu, :help_n_ta, :help_t_ta, :help_n_instruct, :help_t_instruct, :help_n_hint, :help_t_hint,:work_time_per_problem, :max_attempts_per_problem, :window_opens, :window_closes, :perc_ec_max_p_assign, :perc_ec_max_p_pblm, :perc_ec_max_person_to_person, :ec_daysb4due_elgible, :ec_open_daysb4due_elgible,:perc_ec_base_video, :perc_ec_base_audio, :perc_ec_base_written,:peer_refl_t,:peer_refl_n, :due_date, :credit, :late_points, :fixed_percent_decline)';
                $stmt = $pdo->prepare($sql);
                $stmt -> execute(array(
                  ':assign_num' => $_POST['assign_num'],
                  ':iid' => $_POST['iid'],
                  ':currentclass_id' => $_POST['currentclass_id'],
                  ':work_flow' => $_POST['work_flow'],
                  ':time_sleep1_trip' => $_POST['time_sleep1_trip'],
                  ':time_sleep1' => $_POST['time_sleep1'],
                  ':time_sleep2_trip' => $_POST['time_sleep2_trip'],
                  ':time_sleep2' => $_POST['time_sleep2'],
                  ':bc_ans_n' => $_POST['bc_ans_n'],
                  ':bc_ans_t' => $_POST['bc_ans_t'],
                  ':p_bc_n' => $_POST['p_bc_n'],
                  ':p_bc_t' => $_POST['p_bc_t'],
                  ':help_n_stu' => $_POST['help_n_stu'],
                  ':help_t_stu' => $_POST['help_t_stu'],
                  ':help_n_instruct' => $_POST['help_n_instruct'],
                  ':help_t_instruct' => $_POST['help_t_instruct'],
                  ':help_n_hint' => $_POST['help_n_hint'],
                  ':help_t_hint' => $_POST['help_t_hint'],
                  ':help_n_ta' => $_POST['help_n_ta'],
                  ':help_t_ta' => $_POST['help_t_ta'],
                  ':work_time_per_problem' => $_POST['work_time_per_problem'],
                  ':max_attempts_per_problem' => $_POST['max_attempts_per_problem'],
                  ':window_opens' => $_POST['window_opens'],
                  ':window_closes' => $_POST['window_closes'],
                  ':perc_ec_max_p_assign' => $_POST['perc_ec_max_p_assign'],
                  ':perc_ec_max_p_pblm' => $_POST['perc_ec_max_p_pblm'],
                  ':perc_ec_max_person_to_person' => $_POST['perc_ec_max_person_to_person'],
                  ':ec_daysb4due_elgible' => $_POST['ec_daysb4due_elgible'],
                  ':ec_open_daysb4due_elgible' => $_POST['ec_open_daysb4due_elgible'],
                  ':perc_ec_base_video' => $_POST['perc_ec_base_video'],
                  ':perc_ec_base_audio' => $_POST['perc_ec_base_audio'],
                  ':perc_ec_base_written' => $_POST['perc_ec_base_written'],
                    ':peer_refl_t' => $_POST['peer_refl_t'],
                  ':peer_refl_n' => $_POST['peer_refl_n'],
                  ':due_date' => $_POST['due_date'],
                  ':credit' => $_POST['credit'],
                  ':late_points' => $_POST['late_points'],
                  ':fixed_percent_decline' => $_POST['fixed_percent_decline'],
                ));
                            
       $sql = 'SELECT assigntime_id FROM Assigntime ORDER BY `assigntime_id` DESC LIMIT 1';     
              $stmt = $pdo->prepare($sql);
               $stmt -> execute(); 
                $assigntime_data = $stmt->fetch();   
                $assigntime_id = $assigntime_data['assigntime_id'];
     
    

    }
    
      // now go to the QRAssignmentStart2 with the assigntime_id 
        header( 'Location: QRAssignmentStart2.php?assigntime_id='.$assigntime_id);
        die();
    

}



$_SESSION['counter']=0;  // this is for the score board

	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QR Assignment Start</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<style>
   .outer {
  width: 100%;
  margin: 0 auto;
 
}

.inner {
  margin-left: 50px;
 
} 


</style>



</head>

<body>
<header>
<h1>Quick Response Assignment Setup</h1>
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
	
 
 
 
?>

<!--<h3>Print the problem statement with "Ctrl P"</h3>
 <p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p>  -->
<form id = "the_form"  method = "POST"  >
	
    <div id ="current_class_dd">	
				Course: &nbsp;
				<span name = "currentclass_id" id = "currentclass_id">
				
				<?php
                   
					$sql = 'SELECT * FROM `CurrentClass` WHERE `currentclass_id` = :currentclass_id';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(':currentclass_id' => $currentclass_id));
					 $row = $stmt->fetch(); 
						 echo $row['name']; ?> 
						
                    
                    
				</span>
		</div>
             </br>
                <font color=#003399>Assignment Number: &nbsp; </font>
                    <?php echo ($assign_num);?>
                   
                </br>	
          <!--      
                <br>
                  <font color=#003399>new_flag: &nbsp; </font>
                    <?php //echo ($new_flag);?>
                   
              -->
                
  
                <br>
                <font color=#003399>Effort on base-case per problem part before answers are given: &nbsp; </font><br>
                    
                   &nbsp;&nbsp;Time (minutes): <input type = "number" min = "0" max = "20" id="bc_ans_t" name = "bc_ans_t" required value =<?php echo $bc_ans_t; ?> > </input><br>
                    &nbsp;&nbsp;Number of attempts: <input type = "number" min = "0" max = "20" id="bc_ans_n" name = "bc_ans_n" required value = <?php echo $bc_ans_n; ?>> </input><br>
                    
                   
                	    <br>
                <font color=#003399>Effort on base-case per problem part before help from: &nbsp; </font><br>
                     &nbsp;&nbsp; Other Students:<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Time(minutes): <input type = "number" min = "0" max = "20" id="help_t_stu" name = "help_t_stu" required value = <?php echo $help_t_stu; ?> > </input><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Number of attempts: <input type = "number" min = "0" max = "100" id="help_n_stu" name = "help_n_stu" required value = <?php echo $help_n_stu; ?>> </input><br>
                      &nbsp;&nbsp; Teaching Assistants or Tutors:<br>
                       &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;Time(minutes): <input type = "number" min = "0" max = "20" id="help_t_ta" name = "help_t_ta" required value = <?php echo $help_t_ta; ?> > </input><br>
                       &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;Number of attempts: <input type = "number" min = "0" max = "100" id="help_n_ta" name = "help_n_ta" required value = <?php echo $help_n_ta; ?>> </input><br>
                      &nbsp;&nbsp; Instructors:<br>
                       &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;Time(minutes): <input type = "number" min = "0" max = "20" id="help_t_instruct" name = "help_t_instruct" required value = <?php echo $help_t_instruct; ?> > </input><br>
                      &nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;Number of attempts: <input type = "number" min = "0" max = "100" id="help_n_instruct" name = "help_n_instruct" required value = <?php echo $help_n_instruct; ?>> </input><br>
                      &nbsp;&nbsp; Hints:<br>
                       &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;Time(minutes): <input type = "number" min = "0" max = "20" id="help_t_hint" name = "help_t_hint" required value = <?php echo $help_t_hint; ?> > </input><br>
                      &nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;Number of attempts: <input type = "number" min = "0" max = "100" id="help_n_hint" name = "help_n_hint" required value = <?php echo $help_n_hint; ?>> </input><br>
                        
              <br>
              <br>
                <font color=#003399>Time Delay on Problem: &nbsp; </font><br>
                   
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Number of tries till first delay: <input type = "number" min = "0" max = "20" id="time_sleep1_trip" name = "time_sleep1_trip" required value = <?php echo $time_sleep1_trip; ?> > </input><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;First time delay (sec): <input type = "number" min = "0" max = "1000" id="time_sleep1" name = "time_sleep1" required value = <?php echo $time_sleep1; ?>> </input><br><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Additional number of tries till 2nd delay: <input type = "number" min = "0" max = "20" id="time_sleep2_trip" name = "time_sleep2_trip" required value = <?php echo $time_sleep2_trip; ?> > </input><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2nd time delay (additional sec): <input type = "number" min = "0" max = "1000" id="time_sleep2" name = "time_sleep2" required value = <?php echo $time_sleep2; ?>> </input><br>
                        
              <br>
              <font color=#003399>Absolute Limits on Problem: &nbsp; </font><br>
               	
                  &nbsp;&nbsp;&nbsp;&nbsp;Max Time for the Problem (minutes, blank = infinite): <input type = "number" min = "0" max = "20" id="work_time_per_problem" name = "work_time_per_problem" value = <?php echo $work_time_per_problem; ?> > </input><br>
                  &nbsp;&nbsp;&nbsp;&nbsp;Max Total Number of Tries for the Problem (blank = infinite) : <input type = "number" min = "0" max = "20" id="max_attempts_per_problem" name = "max_attempts_per_problem" value = <?php echo $max_attempts_per_problem; ?>  > </input><br>
            
                   <br> <font color=#003399>Extra Credit: &nbsp; </font><br>
                  &nbsp;&nbsp;&nbsp;&nbsp; Number of days before the due date that problem and base-case is completed and any material is uploaded to be elgible for extra credit : <input type = "number" min = "0" max = "20" id="ec_daysb4due_elgible" name = "ec_daysb4due_elgible" value = <?php echo $ec_daysb4due_elgible; ?>  > </input><br>
                  &nbsp;&nbsp;&nbsp;&nbsp; Number of days before the due date that problem must be opened to be elgible for extra credit : <input type = "number" min = "0" max = "20" id="ec_open_daysb4due_elgible" name = "ec_open_daysb4due_elgible" value = <?php echo $ec_open_daysb4due_elgible; ?>  > </input><br>
                <br> &nbsp;&nbsp;Peer Instruction:<br>
                 &nbsp;&nbsp;&nbsp;&nbsp; Max percent Extra Credit per assignment for one student for peer assistance: <input type = "number" min = "0" max = "100" id="perc_ec_max_p_assign" name = "perc_ec_max_p_assign" value = <?php echo $perc_ec_max_p_assign; ?>  > </input><br>
                 &nbsp;&nbsp;&nbsp;&nbsp; Max percent Extra Credit per individual problem for one student for peer assistance: <input type = "number" min = "0" max = "50" id="perc_ec_max_p_pblm" name = "perc_ec_max_p_pblm" value = <?php echo $perc_ec_max_p_pblm; ?>  > </input><br>
                 &nbsp;&nbsp;&nbsp;&nbsp; Max percent Extra Credit per assignment for one student for peer assistance from one student: <input type = "number" min = "0" max = "50" id="perc_ec_max_person_to_person" name = "perc_ec_max_person_to_person" value = <?php echo $perc_ec_max_person_to_person; ?>  > </input><br>
                <br>&nbsp;&nbsp;Student generated instructional material:<br>
                 &nbsp;&nbsp;&nbsp;&nbsp; Base percent Extra Credit on assignment for video instruction on basecase: <input type = "number" min = "0" max = "100" id="perc_ec_base_video" name = "perc_ec_base_video" value = <?php echo $perc_ec_base_video; ?>  > </input><br>
              <!--    &nbsp;&nbsp;&nbsp;&nbsp; Base percent Extra Credit on assignment for audio instruction on basecase: --><input type = "hidden" min = "0" max = "50" id="perc_ec_base_audio" name = "perc_ec_base_audio" value = <?php echo $perc_ec_base_audio; ?>  > </input><br>
              <!--   &nbsp;&nbsp;&nbsp;&nbsp; Base percent Extra Credit on assignment for written instruction on basecase:  --><input type = "hidden" min = "0" max = "50" id="perc_ec_base_written" name = "perc_ec_base_written" value = <?php echo $perc_ec_base_written; ?>  > </input><br>
          
           </br>
                <font color=#003399>Work Flow: &nbsp; </font><br>
                &nbsp;&nbsp; <input type="radio" name="work_flow" id = "work_flow"
                    <?php if (isset($work_flow) && $work_flow=="open" ||!isset($work_flow)  ) echo "checked";?>
                    value="open"> Open - students can freely move between base-case and their problem &nbsp;&nbsp;&nbsp;&nbsp;
                    <br>&nbsp;&nbsp;&nbsp;<input type="radio" name="work_flow"
                    <?php if ((isset($work_flow) && $work_flow=="bc_if")) echo "checked";?>
                    value="bc_if" id = "bc_if"> Base-Case If - Students are routed to the base-case they get stuck on their problem &nbsp;&nbsp;&nbsp;&nbsp;
                   

                   <div id = "base_case_if">
                   
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; Time (min) before they are routed to corresponding base-case part <input type = "number" min = "0" max = "60"  name = "p_bc_t" value = 10 > </input><br>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; Number of tries before they are routed to corresponding base-case part  <input type = "number" min = "0" max = "20"  name = "p_bc_n" value = 3 > </input><br>
                    </div>
                    
                    
                  <br>&nbsp;&nbsp; <input type="radio" name="work_flow"
                    <?php if (isset($work_flow) && $work_flow=="bc_first") echo "checked";?>
                    value="bc_first"> Base-Case First - Students work the base-case before they can work on their problem &nbsp;&nbsp;&nbsp;&nbsp;
           
           </br> </br>
            <font color=#003399> Assignment Timing: &nbsp; </font><br>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date and Time Assignment Window Opens <input type="datetime-local" id="window_opens" name = "window_opens" required value="<?php if ($window_opens!=''){echo $window_opens->format('Y-m-d\TH:i');}
            else {$date = new DateTime();$timezone = new DateTimeZone('America/New_York');
            $date->setTimezone($timezone);  $interval = new DateInterval('P1D');   $date->sub($interval);  echo($date->format('Y-m-d\TH:i'));} 
            ?>"> </input><br><br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date and Time Assignment Due for Full Credit <input type="datetime-local" id="due_date" name = "due_date" required value="<?php if($due_date !=''){ echo $due_date->format('Y-m-d\TH:i'); }
            else {$date = new DateTime(); $timezone = new DateTimeZone('America/New_York');
            $date->setTimezone($timezone); $interval = new DateInterval('P7D');   $date->add($interval); echo($date->format('Y-m-d\TH:i'));}
            ?>"> </input><br><br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date and Time Assignment Window Closes - No Access After <input type="datetime-local" id="window_closes" name = "window_closes" required value="<?php
            // if($window_closes!='')
            // {echo $window_closes->format('Y-m-d\TH:i');}
            // else { 
            $date = date('Y-m-d\TH:i',$current_class_exp_date);  echo($date);
            ?>"> </input><br><br>
            
              <font color=#003399> Peer Rating of Reflections: &nbsp; </font><br>
            &nbsp;&nbsp;&nbsp;&nbsp; Number of days after assignment due date that students should complete the rating of the reflections: <input type = "number" min = "0" max = "20" id="peer_refl_t" name = "peer_refl_t" value = <?php echo $peer_refl_t; ?>  > </input><br><br>
           
           &nbsp;&nbsp;&nbsp;&nbsp; Number of reflections that students should rate per reflection submitted: <input type = "number" min = "0" max = "7" id="peer_refl_n" name = "peer_refl_n" value = <?php echo $peer_refl_n; ?>  > </input><br><br>

            <font color=#003399>Late Penalty Applies to: &nbsp; </font><br>
            <!--
           &nbsp;&nbsp; <input type="radio" name="credit" id = "credit"
                    <?php if (isset($credit) && $credit=="latetoall"  ) echo "checked";?>
                    value="latetoall"> Entire Assignment &nbsp;&nbsp;&nbsp;&nbsp;
              -->      
           &nbsp;&nbsp; <input type="radio" name="credit" id = "credit" value = "latetoproblems" checked> Each Problem
                    
            
           &nbsp;&nbsp; <input type="radio" name="credit" id = "credit"
                    <?php if ((isset($credit) && $credit=="latetoparts")||!isset($credit)) echo "checked";?>
                    value="latetoparts"> Parts of the Problem that are Late &nbsp;&nbsp;&nbsp;&nbsp;
                   
                    
            <br><font color=#003399> Late Policy &nbsp; </font><br>
        
           &nbsp;&nbsp; <input type="radio" name="late_points" id = "late_points"
                    <?php if ((isset($late_points) && $late_points=="fixedpercent") || !isset($late_points)  ) echo "checked";?>
                    value="fixedpercent"> Fixed Percent of Maximum per day after Due date of: &nbsp;
                <span id = "fixed_percent_per_day">
                   
                       &nbsp;&nbsp;  <input type = "number" min = "0" max = "100" id = "fixed_percent_decline" name = "fixed_percent_decline" <?php echo ("value = ".$fixed_percent_decline); ?> > </input><br>
                    </span>
           <p><input type="hidden" name="currentclass_id" id="currentclass_id" value=<?php echo($currentclass_id);?> ></p>
            <p><input type="hidden" name="assign_num" id="assign_num" value=<?php echo($assign_num);?> ></p>
            <p><input type="hidden" name="assigntime_id" id="assigntime_id" value=<?php echo($assigntime_id);?> ></p>

              <p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
			<p><input type = "submit" name = "submit_name" id = "submit_id" value = "Submit and go to Next Page"></p>
   
	
	</form>
  <p style="font-size:100px;"></p>   
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	
	<script>
	
	
	
	$(document).ready( function () {
		
		var currentclass_name = "";
		
			$("#currentclass_id").change(function(){
            var	 currentclass_id = $("#currentclass_id").val();
                console.log ('currentclass_id: '+currentclass_id);
				
				// need to give it 	
					$.ajax({
						url: 'getactiveassignments.php',
						method: 'post',
					
					data: {currentclass_id:currentclass_id}
					}).done(function(activeass){
						console.log("activeass: "+activeass);
					 console.log(activeass);
					 activeass = JSON.parse(activeass);
					 	 $('#active_assign').empty();
						var i = 0;
						n = activeass.length;
						console.log("n: "+n);
						for (i=0;i<n;i++){
							console.log(activeass[i]);	
                            var s_act=activeass[i].toString();
                            console.log(s_act);	
							 $("#active_assign").append("<option value="+activeass[i]+">"+s_act+"</option>");
							if (i != n-1){

							}
						}
						
					});	
				
			
			 
            } );
        
       $('input:radio[name="work_flow"]').change(
              function(){
                if ($(this).is(':checked') && $(this).val() == 'bc_if') {
                     $('#base_case_if').show();
                } else 
                {$('#base_case_if').hide();
                }
            
            //if($('#bc_if').is(':checked')) { $('#base_case_if').show(); } else {$('#base_case_if').hide();}
        });
        
           $('input:radio[name="late_points"]').change(
              function(){
                if ($(this).is(':checked') && $(this).val() == 'fixedpercent') {
                     $('#fixed_percent_decline').show();
                } else 
                {$('#fixed_percent_decline').hide();
                }
            
            //if($('#bc_if').is(':checked')) { $('#base_case_if').show(); } else {$('#base_case_if').hide();}
        });
      

       
	/* 	
			
            var	 assigntime_id = $("#assigntime_id").val();
                console.log ('assigntime_id: '+assigntime_id);
				
				// need to give it 	
					$.ajax({
						url: 'getactiveassignments3.php',
						method: 'post',
					
					data: {assigntime_id:assigntime_id}
					}).done(function(dates){
						console.log("dates: "+dates);
				  var window_opens = dates.split(",")[0].slice(2,-1);
                  var due_date = dates.split(",")[1].slice(1,-1);
                  var window_closes = dates.split(",")[2].slice(1,-8);
                  console.log("window_opens up: "+window_opens);
                     console.log("due_date up: "+due_date);
                      console.log("window_closes up: "+window_closes);
					 var dates = JSON.parse(dates);  
                      console.log("dates2: "+dates);
                      
                    });
                  //  var res = str.split(" ");
                 */
                    
      /*    
     // this is from https://stackoverflow.com/questions/24468518/html5-input-datetime-local-default-value-of-today-and-current-time using pure JS   
        window.addEventListener("load", function() {
    var now = new Date();
    var utcString = now.toISOString().substring(0,19);
    var year = now.getFullYear();
    var month = now.getMonth() + 1;
    var day = now.getDate();
    var hour = now.getHours();
    var minute = now.getMinutes();
    var second = now.getSeconds();
    var localDatetime = year + "-" +
                      (month < 10 ? "0" + month.toString() : month) + "-" +
                      (day < 10 ? "0" + day.toString() : day) + "T" +
                      (hour < 10 ? "0" + hour.toString() : hour) + ":" +
                      (minute < 10 ? "0" + minute.toString() : minute) ;
     if (month ==12){month = 1} else {month = month +1; }    // set default window closes to one month in the future            
    var localDatetime2 = year + "-" +
                      (month < 10 ? "0" + month.toString() : month) + "-" +
                      (day < 10 ? "0" + day.toString() : day) + "T" +
                      (hour < 10 ? "0" + hour.toString() : hour) + ":" +
                      (minute < 10 ? "0" + minute.toString() : minute) ;
   // var x = document.getElementById("myLocalDate").value;
    var window_opens = document.getElementById("window_opens").value;
    console.log(' window_opens: '+window_opens);
   var  n = window_opens.length;
      console.log(' window_opens length: '+n);
      var type = typeof(window_opens);
      console.log(' window_opens type: '+type);
   // if (n<3){
         console.log(' localdatetime: '+localDatetime);
         $('#window_opens').value = localDatetime;
      //  window_opens.value = localDatetime;
  //  }
     var window_closes = document.getElementById("window_closes").value;
      n = window_closes.length;
    
  // if (n<3){
        window_closes.value = localDatetime2;
  //  }
    var due_date = document.getElementById("due_date").value;
      n = due_date.length;
  // if (n<3){
        due_date.value = localDatetime2;
  //  }

});
 
       
        $("#submit_id").click(function(){
          
           
          $.ajax({
             type: "POST",
             url: "QREStart.php",
             data: {currentclass_id:currentclass_id,exam_num:exam_num},
             success: function(msg) {
                alert("Form Submitted: " + msg);
             }
          });
           
           $.ajax({
				url: 'QREStart.php',
				method: 'post',
				data: {currentclass_id:currentclass_id,exam_num:exam_num}
					})
          
          
        // $.post("QREStart.php",{currentclass_id:currentclass_id, exam_num:exam_num},);  
          
        });
	 */
	} );
	
	
</script>	

</body>
</html>



