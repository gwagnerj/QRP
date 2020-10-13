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

// this is called from the main repo and this will collect initial data from instructor and proceed to QREStart.php






    
        
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
 
} 


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

<!--<h3>Print the problem statement with "Ctrl P"</h3>
 <p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p>  -->
<form id = "the_form"  method = "POST" action = "QREStart.php" >
	
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
                <font color=#003399>Exam Number: &nbsp; </font>
                    
                    <select id="exam_num" name = "exam_num" required >
                       <option value="0">- Select Exam -</option>
                    </select>
                </br>	
    
           </br>
                <font color=#003399>Minutes for Exam: &nbsp; </font>
           <input type = "number" name ="nom_time" id = "nom_time" min = "1", max = "300" value = "60" required > </input>
           
           </br> </br>
           
                 <font color=#003399>Attempts per Problem: &nbsp; </font>
                
                  <div  class = "outer" >
                     <div  class = "inner" >


                            <div>
                               <input type = "radio" name ="attempt_type" id = "check_inf" value = "1" checked > </input>
                                   <label for "check_infin"> Check as they go - No limit </label>
                             </div>
                             </br>
                             <div>
                                <input type = "radio" name ="attempt_type" id = "check_limit" value = "2" > </input>
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
                            
                            
         </br>
         <hr>
         Nothing below this line is currently active.
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
            
            
             <p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
			<p><input type = "submit" id = "submit_id"></p>
   
	
	</form>
  <p style="font-size:100px;"></p>   
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	
	<script>
	
	
	
	$(document).ready( function () {
      

 /*
      var start_def = new Date();
       start_time = start_def.getTime();
           document.getElementById("open_e_window_t").defaultValue = start_time;
    // set the date for the open window to default to right now
    
    
      Date.prototype.toDateInputValue = (function() {
            var local = new Date(this);
                    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
                    return local.toJSON().slice(0,10);
                });
    
    
    
            $('#open_e_window').val(new Date().toDateInputValue());
        
     
        */
       
       
       
		
		var currentclass_name = "";
		
			$("#currentclass_id").change(function(){
            var	 currentclass_id = $("#currentclass_id").val();
                console.log ('currentclass_id: '+currentclass_id);
				
				// need to give it 	
					$.ajax({
						url: 'getactiveexam.php',
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
						// $('#active_assign').append("&nbsp;&nbsp;&nbsp;&nbsp;Current exams in system for this course: ") ;	
						for (i=0;i<n;i++){
							console.log(activeass[i]);	
							//$('#exam_num').append(activeass[i]) ;	
                            var s_act=activeass[i].toString();
                            console.log(s_act);	
							 $("#exam_num").append("<option value="+activeass[i]+">"+s_act+"</option>");
							if (i != n-1){
								          //    $("#exam_num").append("<option value='"+activeass[i]+"'></option>");

							}
						}
						
					});	
				
			
			 
		} );
        
        $("#exam_num").change(function(){
            var exam_num = $("#exam_num").val();
            console.log("exam_num: "+exam_num)
            
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



