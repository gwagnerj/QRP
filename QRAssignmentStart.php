<?php
	require_once "pdo.php";
	session_start();
	
	
    if (isset($_POST['iid'])) {
	$iid = $_POST['iid'];
} else {
	 $_SESSION['error'] = 'invalid User_id in QRExamStart.php ';
      			header( 'Location: QRPRepo.php' ) ;
				die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_name'])) {
    
   // echo'first page submitted';
   // input the values from the form into the Assigntime table - get the assigntime_id and then move onto page two to get points values for each part
   
   $sql = 'INSERT INTO `Assigntime` (assign_num, iid, currentclass_id, work_flow, bc_ans_n,bc_ans_t, p_bc_n, p_bc_t, help_n_stu, help_t_stu, help_n_ta, help_t_ta, help_n_instruct, help_t_instruct, work_time_per_problem, max_attempts_per_problem, window_opens, window_closes, perc_ec_max_p_assign, perc_ec_max_p_pblm, perc_ec_max_person_to_person)	
                                    VALUES (:assign_num, :iid, :currentclass_id, :work_flow, :bc_ans_n,:bc_ans_t, :p_bc_n, :p_bc_t, :help_n_stu, :help_t_stu, :help_n_ta, :help_t_ta, :help_n_instruct, :help_t_instruct, :work_time_per_problem, :max_attempts_per_problem, :window_opens, :window_closes, :perc_ec_max_p_assign, :perc_ec_max_p_pblm, :perc_ec_max_person_to_person)';
                            $stmt = $pdo->prepare($sql);
                            $stmt -> execute(array(
                              ':assign_num' => $_POST['active_assign'],
                              ':iid' => $_POST['iid'],
                              ':currentclass_id' => $_POST['currentclass_id'],
                              ':work_flow' => $_POST['work_flow'],
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
                            ));
                            
   $sql = 'SELECT assigntime_id FROM Assigntime ORDER BY `assigntime_id` DESC LIMIT 1';     
          $stmt = $pdo->prepare($sql);
           $stmt -> execute(); 
            $assigntime_data = $stmt->fetch();   
            $assigntime_id = $assigntime_data['assigntime_id'];
  // echo('assigntime_id: '.$assigntime_id);
  // now go to the QRAssignmentStart2 with the assigntime_id 
       			header( 'Location: QRAssignmentStart2.php?assigntime_id='.$assigntime_id);
				die();
}

// this is called from the main repo and this will Collect the information on a particular assignment from the instructor then moves onto .  THis file was coppied form QRExamStart.php

        
/* 		
			$alias_num = $exam_num = $cclass_id = '';   
			
			
            $sql_stmt = "SELECT * FROM Exam WHERE DATE(NOW())<= exp_date AND iid = :iid order by exam_id";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt -> execute(array(':iid' => $iid));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 */
     
// this will be called form the main repo when the game master wants to run a game
// this is just to get the game number and go on to QRGMaster.php with a post of the game number.
// Validity will be checked in that file and sent back here if it is not valid

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
				<select name = "currentclass_id" id = "currentclass_id">
				 <option value = "" selected disabled hidden > Select Course  </option> 
				<?php
                   
					$sql = 'SELECT * FROM `CurrentClass` WHERE `iid` = :iid';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(':iid' => $iid));
					while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) 
						{ ?>
						<option value="<?php echo $row['currentclass_id']; ?>" ><?php echo $row['name']; ?> </option>
						<?php
 							}
                    ?>
                    
                    
				</select>
		</div>
             </br>
                <font color=#003399>Assignment Number: &nbsp; </font>
                    
                    <select id="active_assign" name = "active_assign" required >
                       <option value="0">- Select Assignment -</option>
                    </select>
                </br>	
                <br>
                <font color=#003399>Effort on base-case per problem part before answers are given: &nbsp; </font><br>
                    
                   &nbsp;&nbsp;Time (minutes): <input type = "number" min = "0" max = "20" id="bc_ans_t" name = "bc_ans_t" required value = 1 > </input><br>
                    &nbsp;&nbsp;Number of attempts: <input type = "number" min = "0" max = "20" id="bc_ans_n" name = "bc_ans_n" required value = 1> </input><br>
                    
                   
                	    <br>
                <font color=#003399>Effort on base-case per problem part before help from: &nbsp; </font><br>
                     Other Students:<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;Time(minutes): <input type = "number" min = "0" max = "20" id="help_t_stu" name = "help_t_stu" required value = 2 > </input><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;Number of attempts: <input type = "number" min = "0" max = "20" id="help_n_stu" name = "help_n_stu" required value = 2> </input><br>
                     Teaching Assistants or Tutors:<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;Time(minutes): <input type = "number" min = "0" max = "20" id="help_t_ta" name = "help_t_ta" required value = 5 > </input><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;Number of attempts: <input type = "number" min = "0" max = "20" id="help_n_ta" name = "help_n_ta" required value = 4> </input><br>
                     Instructors:<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;Time(minutes): <input type = "number" min = "0" max = "20" id="help_t_instruct" name = "help_t_instruct" required value = 10 > </input><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;Number of attempts: <input type = "number" min = "0" max = "20" id="help_n_instruct" name = "help_n_instruct" required value = 5> </input><br>
                        
              <br>
              <font color=#003399>Absolute Limits on Problem: &nbsp; </font><br>
               	
                  &nbsp;&nbsp;&nbsp;&nbsp;Max Time for the Problem (minutes, blank = infinite): <input type = "number" min = "0" max = "20" id="work_time_per_problem" name = "work_time_per_problem"  > </input><br>
                  &nbsp;&nbsp;&nbsp;&nbsp;Max Total Number of Tries for the Problem (blank = infinite) : <input type = "number" min = "0" max = "20" id="max_attempts_per_problem" name = "max_attempts_per_problem"   > </input><br>
                 Extra Credit:<br>
                 &nbsp;&nbsp;&nbsp;&nbsp;Max percent Extra Credit per assignment for one student for peer assistance: <input type = "number" min = "0" max = "100" id="perc_ec_max_p_assign" name = "perc_ec_max_p_assign" value = "20"  > </input><br>
                 &nbsp;&nbsp;&nbsp;&nbsp;Max percent Extra Credit per individual problem for one student for peer assistance: <input type = "number" min = "0" max = "50" id="perc_ec_max_p_pblm" name = "perc_ec_max_p_pblm" value = "20"  > </input><br>

                 &nbsp;&nbsp;&nbsp;&nbsp;Max percent Extra Credit per assignment for one student for peer assistance from one student: <input type = "number" min = "0" max = "50" id="perc_ec_max_person_to_person" name = "perc_ec_max_person_to_person" value = "5"  > </input><br>

           </br>
                <font color=#003399>Work Flow: &nbsp; </font><br>
                &nbsp;&nbsp; <input type="radio" name="work_flow" id = "work_flow"
                    <?php if (isset($work_flow) && $work_flow=="open"  ) echo "checked";?>
                    value="open"> Open - students can freely move between base-case and their problem &nbsp;&nbsp;&nbsp;&nbsp;
                    <br>&nbsp;&nbsp;&nbsp;<input type="radio" name="work_flow"
                    <?php if ((isset($work_flow) && $work_flow=="bc_if")||!isset($work_flow)) echo "checked";?>
                    value="bc_if" id = "bc_if"> Base-Case - Students are routed to the base-case they get stuck on their problem &nbsp;&nbsp;&nbsp;&nbsp;
                   

                   <div id = "base_case_if">
                   
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; Time (min) before they are routed to corresponding base-case part <input type = "number" min = "0" max = "60"  name = "p_bc_t" value = 10 > </input><br>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; Number of tries before they are routed to corresponding base-case part  <input type = "number" min = "0" max = "20"  name = "p_bc_n" value = 3 > </input><br>
                    </div>
                    
                    
                  <br>&nbsp;&nbsp; <input type="radio" name="work_flow"
                    <?php if (isset($work_flow) && $work_flow=="bc_first") echo "checked";?>
                    value="bc_first"> Base-Case First - Students work the base-case before they can work on their problem &nbsp;&nbsp;&nbsp;&nbsp;
           
           </br> </br>
            <font color=#003399> When is Assignment Available for Students? &nbsp; </font><br>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date and Time Assignment Window Opens <input type="datetime-local" id="window_opens" name = "window_opens" required value="<?php// echo ($date_time); ?>"> </input><br><br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date and Time Assignment Window Closes <input type="datetime-local" id="window_closes" name = "window_closes" required value="<?php// echo ($date_time); ?>"> </input><br><br>
            
             <p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
			<p><input type = "submit" name = "submit_name" id = "submit_id" value = "Submit First Page"></p>
   
	
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
    var window_opens = document.getElementById("window_opens");
    window_opens.value = localDatetime;
    var window_closes = document.getElementById("window_closes");
    window_closes.value = localDatetime2;

});
        
        $("#submit_id").click(function(){
          
        /*    
          $.ajax({
             type: "POST",
             url: "QREStart.php",
             data: {currentclass_id:currentclass_id,exam_num:exam_num},
             success: function(msg) {
                alert("Form Submitted: " + msg);
             }
          });
           */
         /*  $.ajax({
				url: 'QREStart.php',
				method: 'post',
				data: {currentclass_id:currentclass_id,exam_num:exam_num}
					})
           */
          
        // $.post("QREStart.php",{currentclass_id:currentclass_id, exam_num:exam_num},);  
          
        });
	
	} );
	
	
</script>	

</body>
</html>



