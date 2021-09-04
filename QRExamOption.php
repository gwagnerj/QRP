<?php
	require_once "pdo.php";
	session_start();
	// this file needs to write the options for the exam on the Eexamtime table and move on to QRExamPoints which is based on 
	
    if(isset($_POST['iid'])){
        $iid = $_POST['iid'];
    } 
    elseif (isset($_GET['iid'])){
        $iid = $_GET['iid'];
    } 
    else {
         $_SESSION['error'] = 'invalid user id in QRExamOption.php';
              header( 'Location: QRPRepo.php' ) ;
            die();
    }
     
if(isset($_POST['currentclass_id'])){
    $currentclass_id = $_POST['currentclass_id'];
} 
elseif (isset($_GET['currentclass_id'])){
    $currentclass_id = $_GET['currentclass_id'];
} 
else {
     $_SESSION['error'] = 'invalid course name in QRExamOption.php';
          header( 'Location: QRPRepo.php' ) ;
        die();
}

if(isset($_POST['exam_num'])){
  $exam_num = $_POST['exam_num'];
} 
elseif (isset($_GET['exam_num'])){
  $exam_num = $_GET['exam_num'];
} 
else {
   $_SESSION['error'] = 'invalid exam num in QRExamOption.php';
        header( 'Location: QRPRepo.php' ) ;
      die();
}
if(isset($_POST['new_flag'])){
  $new_flag = $_POST['new_flag'];
} 
elseif (isset($_GET['new_flag'])){
  $new_flag = $_GET['new_flag'];
} 
else {
   $_SESSION['error'] = 'invalid new_flag in QRExamOption.php';
        header( 'Location: QRPRepo.php' ) ;
      die();
}

// get the name of the class from the db
$sql = 'SELECT `name` FROM `CurrentClass` WHERE `iid` = :iid && currentclass_id = :currentclass_id ';
$stmt = $pdo->prepare($sql);
$stmt -> execute(array(':iid' => $iid,':currentclass_id' => $currentclass_id));
$row = $stmt->fetch();
$class_name = $row['name'];


// Write a bunch of stuff to the  Eexamtime if we have it  check to see if this we have already written it so we can populate the form with the old stuff if we have the data
// this was modified from the QRAssignmentStart1.php file 
 // set the default values for the variables

$nom_time = 60;
$attempt_type = 1;
$work_flow = 1;

$ans_n = 3;
$ans_t = 1;
$num_attmepts = 1;
$game_flag = 0;

 $bc_ans_t = 1;
 $bc_ans_n = 1;
 $p_bc_n = 3;
 $p_bc_t = 10;
 
 // not sure we will use help on quizzes and examinations but leaving them in for the off chance
 $help_n_stu = 99;
 $help_n_ta = 99;
 $help_n_instruct = 99;
 
 
 $help_t_stu = 2;
 $help_t_ta = 5;
 $help_t_instruct = 10;
 $work_time_per_problem = '';
 $max_attempts_per_problem = '';
 $perc_ec_max_p_assign = 20;
 $perc_ec_max_p_pblm = 20;
 $perc_ec_max_person_to_person = 5;
 $fixed_percent_decline = 30;
 
 $ec_daysb4due_elgible = 10;
 
 $perc_ec_base_video = 3;
 $perc_ec_base_audio = 2;
 $perc_ec_base_written = 1;
 $peer_refl_n = 5;
 $peer_refl_t = 2;
 
 $eexamtime_id = '';
 $window_opens = $window_closes = $due_date = '';
 
if($new_flag == 0){
 // redefine the varaibles read in from the Assigntime table values
       //  SELECT *, DATE_FORMAT(date_and_time, '%Y-%m-%dT%H:%i') AS custom_date 
    
 $sql = 'SELECT *, DATE_FORMAT(due_date, "%Y-%m-%dT%H:%i"),DATE_FORMAT(window_opens, "%Y-%m-%dT%H:%i")   FROM Eexamtime WHERE currentclass_id = :currentclass_id AND iid = :iid AND exam_num = :exam_num';     
       $stmt = $pdo->prepare($sql);
        $stmt -> execute(array (
        ':currentclass_id' => $currentclass_id,
        ':exam_num' => $exam_num,
        ':iid' => $iid,
        )); 
         $eexamtime_data = $stmt->fetch();   
        $nom_time = $eexamtime_data['nom_time'];
        $attempt_type = $eexamtime_data['attempt_type'];
        $work_flow = $eexamtime_data['work_flow'];
        $ans_n = $eexamtime_data['ans_n'];
        $ans_t = $eexamtime_data['ans_t'];
        $num_attmepts = $eexamtime_data['num_attempts'];
        $game_flag = $eexamtime_data['game_flag'];
       $bc_ans_t = $eexamtime_data['bc_ans_t'];
       $bc_ans_n = $eexamtime_data['bc_ans_n'];
       $p_bc_n = $eexamtime_data['p_bc_n'];
       $p_bc_t = $eexamtime_data['p_bc_t'];
       $help_n_instruct = $eexamtime_data['help_n_instruct'];
       $help_n_ta = $eexamtime_data['help_n_ta'];
       $help_n_stu = $eexamtime_data['help_n_stu'];
       $help_t_instruct = $eexamtime_data['help_t_instruct'];
       $help_t_ta = $eexamtime_data['help_t_ta'];
       $help_t_stu = $eexamtime_data['help_t_stu'];
       $work_time_per_problem = $eexamtime_data['work_time_per_problem'];
       $max_attempts_per_problem = $eexamtime_data['max_attempts_per_problem'];
       $perc_ec_max_p_assign = $eexamtime_data['perc_ec_max_p_assign'];
       $perc_ec_max_p_pblm = $eexamtime_data['perc_ec_max_p_pblm'];
       $perc_ec_max_person_to_person = $eexamtime_data['perc_ec_max_person_to_person'];
      // $perc_ec_max_decrease = $eexamtime_data['perc_ec_max_decrease'];
       
       $ec_daysb4due_elgible = $eexamtime_data['ec_daysb4due_elgible'];
       $perc_ec_base_video = $eexamtime_data['perc_ec_base_video'];
       $perc_ec_base_audio = $eexamtime_data['perc_ec_base_audio'];
       $perc_ec_base_written = $eexamtime_data['perc_ec_base_written'];
       $peer_refl_n = $eexamtime_data['peer_refl_n'];
       $peer_refl_t =$eexamtime_data['peer_refl_t'];
       
       
       $eexamtime_id = $eexamtime_data['eexamtime_id'];
       $fixed_percent_decline = $eexamtime_data['fixed_percent_decline'];
       $window_closes = new DateTime($eexamtime_data['window_closes']);
       $window_opens = new DateTime($eexamtime_data['window_opens']);
      
   //    $window_opens = date(DATE_RFC3339, strtotime($window_opens));
     //  $window_closes = $eexamtime_data['window_closes'];
       $due_date = $eexamtime_data['due_date'];
        $due_date = new DateTime($due_date);
       }



     // get the exp date from the CurrentClass table to set the default examt clase date
         $sql = 'SELECT exp_date, DATE_FORMAT(exp_date, "%Y-%m-%dT%H:%i")  FROM CurrentClass WHERE currentclass_id = :currentclass_id ';     
       $stmt = $pdo->prepare($sql);
        $stmt -> execute(array (
        ':currentclass_id' => $currentclass_id,
        )); 
         $current_class_exp_date = $stmt->fetch();   
         $current_class_exp_date = $current_class_exp_date['exp_date'];
          $current_class_exp_date = strtotime($current_class_exp_date);
//         echo('$current_class_exp_date '.$current_class_exp_date);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_name'])) {
 
 
 if ($new_flag == 0) {
     
     $eexamtime_id = $_POST['eexamtime_id'];
     // update the table instead of insert
     
          $sql = 'UPDATE Eexamtime SET 
          work_flow=:work_flow, 
          ans_n =:ans_n,
          ans_t =:ans_t,
          num_attempts =:num_attempts,
          game_flag =:game_flag,
          nom_time=:nom_time, 
          attempt_type=:attempt_type, 
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
          perc_ec_base_video=:perc_ec_base_video,
          perc_ec_base_audio=:perc_ec_base_audio,
          perc_ec_base_written=:perc_ec_base_written,
          due_date=:due_date, 
          credit=:credit, 
          late_points=:late_points, 
          fixed_percent_decline=:fixed_percent_decline
         WHERE eexamtime_id = :eexamtime_id';
          
         $stmt = $pdo->prepare($sql);
         $stmt -> execute(array(
           ':eexamtime_id' => $eexamtime_id,
           ':work_flow' => $_POST['work_flow'],
           ':ans_n' => $_POST['ans_n'],
           ':ans_t' => $_POST['ans_t'],
           ':num_attempts' => $_POST['num_attempts'],
           ':game_flag' => $_POST['game_flag'],
           ':nom_time' => $_POST['nom_time'],
           ':attempt_type' => $_POST['attempt_type'],
           ':bc_ans_n' => $_POST['bc_ans_n'],
           ':bc_ans_t' => $_POST['bc_ans_t'],
           ':p_bc_n' => $_POST['p_bc_n'],
           ':p_bc_t' => $_POST['p_bc_t'],
           ':help_n_stu' => $_POST['help_n_stu'],
           ':help_t_stu' => $_POST['help_t_stu'],
           ':help_n_instruct' => $_POST['help_n_instruct'],
           ':help_t_instruct' => $_POST['help_t_instruct'],
           ':help_n_ta' => $_POST['help_n_ta'],
           ':help_t_ta' => $_POST['help_t_ta'],
           ':work_time_per_problem' => $_POST['work_time_per_problem'],
           ':max_attempts_per_problem' => $_POST['max_attempts_per_problem'],
           ':window_opens' => $_POST['window_opens'],
           ':window_closes' => $_POST['window_closes'],
           ':perc_ec_max_p_assign' => $_POST['perc_ec_max_p_assign'],
           ':perc_ec_max_p_pblm' => $_POST['perc_ec_max_p_pblm'],
           ':perc_ec_max_person_to_person' => $_POST['perc_ec_max_person_to_person'],
           ':peer_refl_n' => $_POST['peer_refl_n'],
           ':peer_refl_t' => $_POST['peer_refl_t'],
           ':ec_daysb4due_elgible' => $_POST['ec_daysb4due_elgible'],
           ':perc_ec_base_video' => $_POST['perc_ec_base_video'],
           ':perc_ec_base_audio' => $_POST['perc_ec_base_audio'],
           ':perc_ec_base_written' => $_POST['perc_ec_base_written'],
           ':due_date' => $_POST['due_date'],
           ':credit' => $_POST['credit'],
           ':late_points' => $_POST['late_points'],
           ':fixed_percent_decline' => $_POST['fixed_percent_decline'],
         ));


          
          
 } else {
 

// input the values from the form into the Assigntime table - get the assigntime_id and then move onto page two to get points values for each part

    $sql = 'INSERT INTO `Eexamtime` (exam_num, iid, currentclass_id, work_flow,ans_n, ans_t, num_attempts,game_flag,nom_time,attempt_type, bc_ans_n,bc_ans_t, p_bc_n, p_bc_t, help_n_stu, help_t_stu, help_n_ta, help_t_ta, help_n_instruct, help_t_instruct, work_time_per_problem, max_attempts_per_problem, window_opens, window_closes, perc_ec_max_p_assign, perc_ec_max_p_pblm, perc_ec_max_person_to_person, ec_daysb4due_elgible,perc_ec_base_video, perc_ec_base_audio, perc_ec_base_written, peer_refl_t, peer_refl_n, due_date,credit, late_points, fixed_percent_decline)	
                                 VALUES (:exam_num, :iid, :currentclass_id, :work_flow,:ans_n,:ans_t,:num_attempts,:game_flag,:nom_time,:attempt_type, :bc_ans_n,:bc_ans_t, :p_bc_n, :p_bc_t, :help_n_stu, :help_t_stu, :help_n_ta, :help_t_ta, :help_n_instruct, :help_t_instruct, :work_time_per_problem, :max_attempts_per_problem, :window_opens, :window_closes, :perc_ec_max_p_assign, :perc_ec_max_p_pblm, :perc_ec_max_person_to_person, :ec_daysb4due_elgible, :perc_ec_base_video, :perc_ec_base_audio, :perc_ec_base_written,:peer_refl_t,:peer_refl_n, :due_date, :credit, :late_points, :fixed_percent_decline)';
             $stmt = $pdo->prepare($sql);
             $stmt -> execute(array(
               ':exam_num' => $_POST['exam_num'],
               ':iid' => $_POST['iid'],
               ':currentclass_id' => $_POST['currentclass_id'],
               ':work_flow' => $_POST['work_flow'],
               ':ans_n' => $_POST['ans_n'],
               ':ans_t' => $_POST['ans_t'],
               ':num_attempts' => $_POST['num_attmepts'],
               ':game_flag' => $_POST['game_flag'],
               ':nom_time' => $_POST['nom_time'],
               ':attempt_type' => $_POST['attempt_type'],
               ':bc_ans_n' => $_POST['bc_ans_n'],
               ':bc_ans_t' => $_POST['bc_ans_t'],
               ':p_bc_n' => $_POST['p_bc_n'],
               ':p_bc_t' => $_POST['p_bc_t'],
               ':help_n_stu' => $_POST['help_n_stu'],
               ':help_t_stu' => $_POST['help_t_stu'],
               ':help_n_instruct' => $_POST['help_n_instruct'],
               ':help_t_instruct' => $_POST['help_t_instruct'],
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
                         
    $sql = 'SELECT eexamtime_id FROM Eexamtime ORDER BY `eexamtime_id` DESC LIMIT 1';     
           $stmt = $pdo->prepare($sql);
            $stmt -> execute(); 
             $eexamtime_data = $stmt->fetch();   
             $eexamtime_id = $eexamtime_data['eexamtime_id'];
  
 

 }
 
   // now go to the QRExampoint to fill in the points for each problem 
     header( 'Location: QRExamPoint.php?eexamtime_id='.$eexamtime_id);
     die();
 

}

$_SESSION['counter']=0;  // this is for the score board

	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRExam Start</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<style>
   .outer {
  width: 100%;
  margin: 0 auto;
}

.inner {
  margin-left: 50px;
</style>

</head>

<body>
<header>
<h1>Quick Response Exam - Setup Exam</h1>
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


<form id = "the_form"  method = "POST" action = "QRExamOption.php" >
	
        <h2> Class Name: <?php  echo ($class_name);?> </h2>
        <h2> Exam / Quiz Number: <?php  echo ($exam_num);?> </h2>       
    
        <font color=#003399>Minutes for Exam: &nbsp; </font>
       <input type = "number" name ="nom_time" id = "nom_time" min = "1", max = "300" value = <?php echo ($nom_time);?> required > </input>
           
           </br> </br>
                 <font color=#003399>Attempts per Problem: &nbsp; </font>
                  <div  class = "outer" >
                     <div  class = "inner" >
                            <div>
                               <input type = "radio" name ="attempt_type" id = "check_inf" value = "2" <?php if($attempt_type==1){echo 'checked';}?>  > </input>
                                   <label for "check_infin"> Check as they go - No limit </label>
                             </div>
                             </br>
                             <div>
                                <input type = "radio" name ="attempt_type" id = "check_limit" value = "2"  <?php if($attempt_type==2){echo 'checked';}?> > </input>
                                   <label for "check_limit"> No Feedback until they Submit.  Max number of Submits = 
                                    <input type = "number" name ="num_attempts" id = "num_attempts" min = "1", max = "50" value = "1" required > </input>
                                   </label>   
                            </div>
                      </div>  
                    </div>   
                    
                 <br>
                  <div  class = "outer" >   
                        <font color=#003399>Show Answer Button - Minimum Limits (blank is &infin;): &nbsp; </font><br>
                </div>
                  <div  class = "inner" >
                        <font color="black">Attempts on part:   &nbsp; </font>
                        <input type = "number" name ="ans_n" id = "ans_n"  min = "0" max = "99" value = "2"  > </input><br><br>
                        <font color="black">Elapsed time from first check on part in minutes: &nbsp; </font>
                        <input type = "number" name ="ans_t" id = "ans_t" min = "0" max = "99" value = "1" > </input>
                     </div>
                     <div  class = "outer" >   <br>
                        <font color=#003399>Are you setting up a <b>Game</b> instead of an Exam or Quiz? &nbsp; </font><br><br>
                </div>
                  <div  class = "inner" >
                  <font color="black"><input type = "checkbox" <?php if ($game_flag == 1){echo "checked";} else{echo "unchecked";}?> value = "1" name="game_flag"> Yes &nbsp; </font><br>
                        
                     </div>

                  <p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
                  <p><input type="hidden" name="currentclass_id" id="currentclass_id" value=<?php echo($currentclass_id);?> ></p>
                  <p><input type="hidden" name="exam_num" id="exam_num" value=<?php echo($exam_num);?> ></p>
                  <p><input type="hidden" name="new_flag" id="new_flag" value=<?php echo($new_flag);?> ></p>
                  <p><input type="hidden" name="eexamtime_id" id="eexamtime_id" value=<?php echo($eexamtime_id);?> ></p>
                            
         </br>
         <p>&nbsp;&nbsp;<input name = "submit_name" type = "submit" id = "submit_id"></p>
      
         <p style="font-size:50px;"></p>   
    
    <a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
    <p style="font-size:20px;"></p>   

         <hr>
         Nothing below this line is currently active in the checker.
         <hr>
         </br>
                      <font color=#003399>Checker Availibility: &nbsp; </font>
                 <div  class = "outer" >
                     <div  class = "inner" >
                        <div>
                          <input type = "radio" name ="attempt_avail" id = "check_avail" value = "1" checked > </input>
                              <label for "check_avail"> Always On </label>
                        </div>
                          </br>
                        <div  >
                            <input type = "radio" name ="attempt_avail" id = "check_avail" value = "2" > </input>
                              <label for "check_avail"> On After  
                                <input    type = "number" name ="on_after" id = "on_after" min = "1", max = "999" value = "1" required  > min</input>
                              
                              </label>   
                        </div>
                          </br>
                        <div>
                            <input type = "radio" name ="attempt_avail" id = "check_avail" value = "3" > </input>
                              <label for "check_avail"> Off After  
                                <input type = "number" name ="off_after" id = "off_after" min = "1", max = "999" value = "30" required > min </input>
                              
                              </label>  
                              
                        </div>
                        </br>
                        <div>
                            <input type = "radio" name ="attempt_avail" id = "check_avail" value = "4" > </input>
                              <label for "check_avail"> Repeating: On   
                                <input type = "number" name ="on_repeat" id = "on_repeat" min = "1", max = "999" value = "5" required > min then Off &nbsp; </input>
                                <input type = "number" name ="off_repeat" id = "off_repeat" min = "1", max = "999" value = "15" required > min </input>
                              </label>   
                        </div>
                    </div>

                </div>
         </br></br>
         
                      <font color=#003399>Exam Versions: &nbsp; </font>
                 
                   <div  class = "outer" >
                     <div  class = "inner" >
                 
                             <div>
                               <input type = "radio" name ="exam_version" id = "exam_version" value = "1" checked > </input>
                                   <label for "exam_version"> Different for Every Examinee </label>
                             </div>
                               </br>
                             <div  >
                                <input type = "radio" name ="exam_version" id = "exam_version" value = "2" > </input>
                                   <label for "exam_version">   
                                    <input    type = "number" name ="num_versions" id = "num_versions" min = "1", max = "999" value = "4" required  > min</input>
                                   
                                   </label>   
                            </div>
                            
                       </div>
                   </div>
                  </br>
               
                      <font color=#003399>Exam Timing: &nbsp; </font>
                 
                   <div  class = "outer" >
                     <div  class = "inner" >
                 
                             <div>
                               <input type = "radio" name ="exam_timing" id = "exam_timing" value = "1" checked > </input>
                                   <label for "exam_timing"> Synchonous (everyone takes the exam at the same time) </label>
                             </div>
                               </br>
                             <div  >
                                <input type = "radio" name ="exam_timing" id = "exam_timing" value = "2" > </input>
                                   <label for "exam_timing"> Asynchronous:  Exam Window Opens on:    
                                    <input type = "date" name ="open_e_window_d" id = "open_e_window_d" value="<?php  date_default_timezone_set('America/Indiana/Indianapolis'); echo date('Y-m-d');  ?>"  ></input>&nbsp; at: &nbsp;
                                      <input type = "time" name ="open_e_window_t" id = "open_e_window_t" value="<?php echo date('H:i'); ?>"> </input> 
                                   </br>
                                    &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;and Closes on: &nbsp; </input>
                                     <input type = "date" name ="close_e_window_d" id = "close_e_window-d" value="<?php $time_now = date('Y-m-d'); echo (string)$time_now; ?>"  ></input>&nbsp; at: &nbsp;
                                      <input type = "time" name ="close_e_window_t" id = "close_e_window_t" value="<?php echo date('H:i',strtotime("+3 hours")); ?>"> </input> 
                           </label>   
                            </div>
                            
                       </div>
                   </div>
                  </br> 

            
      <hr>
        Below are fields that are in the database that maybe used someday but default values get put in the database.
       <hr><br><br>
	
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
                        
              <br>
              <font color=#003399>Absolute Limits on Problem: &nbsp; </font><br>
               	
                  &nbsp;&nbsp;&nbsp;&nbsp;Max Time for the Problem (minutes, blank = infinite): <input type = "number" min = "0" max = "20" id="work_time_per_problem" name = "work_time_per_problem" value = <?php echo $work_time_per_problem; ?> > </input><br>
                  &nbsp;&nbsp;&nbsp;&nbsp;Max Total Number of Tries for the Problem (blank = infinite) : <input type = "number" min = "0" max = "20" id="max_attempts_per_problem" name = "max_attempts_per_problem" value = <?php echo $max_attempts_per_problem; ?>  > </input><br>
            
                   <br> <font color=#003399>Extra Credit: &nbsp; </font><br>
                  &nbsp;&nbsp;&nbsp;&nbsp; Number of days before the due date that problem and base-case is completed and any material is uploaded to be elgible for extra credit : <input type = "number" min = "0" max = "20" id="ec_daysb4due_elgible" name = "ec_daysb4due_elgible" value = <?php echo $ec_daysb4due_elgible; ?>  > </input><br>
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
            if($window_closes!='')
            {echo $window_closes->format('Y-m-d\TH:i');}
            else { 
            $date = date('Y-m-d\TH:i',$current_class_exp_date);  echo($date);}
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
                    
            <!--
           &nbsp;&nbsp; <input type="radio" name="credit" id = "credit"
                    <?php if ((isset($credit) && $credit=="latetoparts")||!isset($credit)) echo "checked";?>
                    value="latetoparts"> Parts of the Problem that are Late &nbsp;&nbsp;&nbsp;&nbsp;
              -->      
                    
            <br><font color=#003399> Late Policy &nbsp; </font><br>
         
           &nbsp;&nbsp; <input type="radio" name="late_points" id = "late_points"
                    <?php if ((isset($late_points) && $late_points=="fixedpercent") || !isset($late_points)  ) echo "checked";?>
                    value="fixedpercent"> Fixed Percent of Maximum per day after Due date of: &nbsp;
                <span id = "fixed_percent_per_day">
                   
                       &nbsp;&nbsp;  <input type = "number" min = "0" max = "100" id = "fixed_percent_decline" name = "fixed_percent_decline" <?php echo ("value = ".$fixed_percent_decline); ?> > </input><br>
                    </span>

	</fofixedrm>
	
	<script>
	
</script>	

</body>
</html>



