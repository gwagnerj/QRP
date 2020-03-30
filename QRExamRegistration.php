<?php
	require_once "pdo.php";
	session_start();
	
 	// this is the normal place to start for students taking an exam and is the file redirected to from QRexam.com and QRexamPblm.org and then goes the the QRExam.php 
//  This file takes the input from the examanee and puts it in the examactivity table then goes to QRExam once this is complete
    $complete = '';
    $exam_num = '';
    $alias_num = '';
    $iid = '';
    $cclass_id = '';
    $pin = '';
    $exam_code ='';
    $problem_id = '';
	$exam_code_error = 0; 
	$stu_name = '';
	$instr_last='';
    $cclass_name='';
    $dex=0;
  
    if(isset($_POST['examactivity_id'])){
       $examactivity_id =  $_POST['examactivity_id'];
    } else {
         $examactivity_id ='';
    }
      if(isset($_POST['exam_code'])){
       $exam_code =  $_POST['exam_code'];
      }
      if(isset($_POST['stu_name'])){
       $stu_name =  $_POST['stu_name'];
      }
     if(isset($_POST['pin'])){
       $pin =  $_POST['pin']+0;
    }
  
    
    // if we are comming into this with a Get from QRExam
     if (!empty($_GET)) {
        $_SESSION['got'] = $_GET;
        header('Location: QRExam.php');
        die;
    } else{
        if (!empty($_SESSION['got'])) {
            $_GET = $_SESSION['got'];
            unset($_SESSION['got']);
        }
    }
 
if(isset($_POST['pin'])){
    if (!is_numeric($pin)||$pin>10000 || $pin<0){
        $_SESSION['error']='Your PIN should be between 1 and 10000.';	
    } else {
        $_SESSION['pin']=$pin;
        $dex = ($pin-1) % 199 + 2; // % is PHP mudulus function - changing the PIN to an index between 2 and 200
        $_SESSION['dex'] = $dex;
    


// Go get the problem id from the Exam table
	if(isset($_POST['submit_form'])&& isset($_POST['iid']) && isset($_POST['cclass_id'])&& isset($_POST['exam_code'])  ){
		$exam_code = htmlentities($_POST['exam_code']);
		$cclass_id = htmlentities($_POST['cclass_id']);
		$iid = htmlentities($_POST['iid']);
        
        
            // Check the exam_code to see if it matches the value in the exam_time table 
            if (strlen($examactivity_id)<=0){  
            $sql = " SELECT * FROM `Examtime` WHERE iid = :iid AND exam_code = :exam_code AND currentclass_id = :currentclass_id"   ;
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                         ':exam_code' => $exam_code,
                         ':iid' => $iid,
                         ':currentclass_id' => $cclass_id,
                    ));
                    $row8 = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($row8 != false){
                        $examtime_id = $row8['examtime_id']; 
                        $work_time = $row8['work_time'];
                        $exam_num = $row8['exam_num'];
                        
             
                              // get the problem(s)
                              
                                    
                                    $sql = " SELECT * FROM `Exam` WHERE iid = :iid AND exam_num = :exam_num AND currentclass_id = :currentclass_id" ;
                                   $stmt = $pdo->prepare($sql);
                                    $stmt -> execute(array(
                                     ':exam_num' => $exam_num,
                                     ':iid' => $iid,
                                     ':currentclass_id' => $cclass_id,
                                ));
                                    $rows = $stmt->fetchALL(PDO::FETCH_ASSOC);
                                    
                                   // $n = count($row);
                                  //  echo('n = '.$n);
                                 
                                  $problem_id1 = $problem_id2 =  $problem_id3 = $problem_id4 =$problem_id5 = 0;
                                  
                                  foreach ($rows as $row){
                                       // print_r ($row);
                                           if ($row['alias_num']==1){$problem_id1=$row['problem_id'];}
                                           if ($row['alias_num']==2){$problem_id2=$row['problem_id'];}
                                           if ($row['alias_num']==3){$problem_id3=$row['problem_id'];}
                                           if ($row['alias_num']==4){$problem_id4=$row['problem_id'];}
                                           if ($row['alias_num']==5){$problem_id5=$row['problem_id'];}
                                   }
                        
                  // ----------------------- put the entry into the examactivity table for this user  ------------------------------------------------------
                                       
                                            
                                          $sql = 'INSERT INTO `Examactivity` (examtime_id, iid, currentclass_id, name, work_time,exam_code, dex, pin, problem_id1, problem_id2, problem_id3, problem_id4, problem_id5,suspend_flag,minutes)	
                                                    VALUES (:examtime_id, :iid, :currentclass_id,:name ,:work_time, :exam_code, :dex, :pin, :problem_id1, :problem_id2, :problem_id3, :problem_id4, :problem_id5,0,:work_time)';
                                            $stmt = $pdo->prepare($sql);
                                            $stmt -> execute(array(
                                            ':examtime_id' => $examtime_id,
                                            ':iid' => $iid,
                                            ':currentclass_id' => $cclass_id,
                                            ':name' => $stu_name,
                                            ':work_time' => $work_time,
                                            ':exam_code' => $exam_code,
                                             ':dex' => $dex,
                                              ':pin' => $pin,
                                              ':problem_id1' => $problem_id1,
                                              ':problem_id2' => $problem_id2,
                                              ':problem_id3' => $problem_id3,
                                              ':problem_id4' => $problem_id4,
                                              ':problem_id5' => $problem_id5,
                                              ':work_time' => $work_time,
                                            ));
                                            
                                            // get the examtime_id
                                       
                                           $sql = "SELECT `examactivity_id` FROM `Examactivity` ORDER BY examactivity_id DESC LIMIT 1";
                                           $stmt = $pdo->prepare($sql);
                                           $stmt -> execute(); 
                                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                            $examactivity_id = $row['examactivity_id'];
                                            $_SESSION['examactivity_id'] = $examactivity_id;
                                            $complete = 'QRExam.php';
                                            
                         header("Location: QRExam.php?examactivity_id=".$examactivity_id
						);
						die();
                                            
                        
                            } else {
                                
                                   $_SESSION['error'] = 'Exam Not Yet Active or Input Incorrect'; 
                            }
                    }          
           
      } elseif(isset($_POST['submit_form'])) {
            //$_SESSION['cclass_id'] = $_SESSION['cclass_name'] = '';
             $_SESSION['error']='The Class, Exam number, Problem and instructor ID are all Required ';
            }
            
            
       }
}
 
					
    if (isset($_POST['reset']))	{
        
        $iid = '';
        $stu_name = '';
        $pin = '';
        $last = '';
        $first = $exam_code = '';
        $alias_num = $exam_num = $cclass_id = '';
        $been_there_flag = 0;
        
    //	session_destroy();
        
    }


	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRExam</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>

<body>
<header>
<h1>Quick Response Exam Registration</h1>
</header>

<?php

	if(isset($_POST['pin']) || isset($_POST['problem_id']) || isset($_POST['iid'])){
		if ( isset($_SESSION['error']) ) {
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) ) {
			echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
			unset($_SESSION['success']);
		}
	}
 
?>

<form autocomplete="off" method="POST" id = "the_form" action = "<?php echo($complete);?>" >
	
<p><font color=#003399>Exam Code: </font><input type="text" name="exam_code" id = "exam_code_id" size= 5 value="<?php echo($exam_code);?>" >
       - this is provided by the instructor
    </p>  
       
	<p><font color=#003399>Your Name: </font><input type="text" name="stu_name" id = "stu_name_id" size= 20 Required value="<?php echo($stu_name);?>" ></p>
	<input autocomplete="false" name="hidden" type="text" style="display:none;">
	<p><font color=#003399>Your PIN: </font><input type="number"  min = "1" max = "10000" name="pin" id="pin_id" size=3 required value=<?php echo($pin);?> ></p>
             
             
            
    	      
    
    <div id ="instructor_id">	
				<font color=#003399> Instructor: &nbsp; </font>
				<?php 
					// $iid=1;
					if (strlen($iid)>0 && strlen($cclass_id)>0 && strlen($cclass_name)>0 && strlen($exam_num)>0 ){
						
						
						$sql = 'SELECT users_id, last, first FROM Users WHERE `users_id` = :iid';
						$stmt = $pdo->prepare($sql);
						$stmt -> execute(array(':iid' => $iid));
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						$last = $row['last'];
						$first = $row['first'];
						echo ('<input type = "hidden" name = "iid" id = "have_iid" value = "'.$iid.'"></input>'); 
						echo ('<input type = "hidden" name = "have_last" value = "'.$last.'"></input>'); 
						echo ('<input type = "hidden" name = "have_first" value = "'.$first.'"></input>'); 
						echo ($last.', '.$first);
					} else {
						
						echo('<select name = "iid" id = "iid">');
						echo ('	<option value = "" selected disabled hidden >  Select Instructor  </option> ');
						$sql = 'SELECT DISTINCT iid, last, first FROM Users RIGHT JOIN CurrentClass ON Users.users_id = CurrentClass.iid';
						$stmt = $pdo->prepare($sql);
						$stmt -> execute();
						while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) 
							{ ?>
								<option value="<?php echo $row['iid']; ?>" ><?php echo ($row['last'].", ".$row['first']); ?> </option>
							<?php
							} 
						echo ('</select>');
					}
					?>
						
				
				</div>
				</br>
	
<!--	<div id ="current_class_dd">	-->
			<font color=#003399>Course: </font>
			
			<?php
			//$cclass_id = 2;
			//$cclass_name = 'Particle Technology';
			//echo ('cclass_id: ');
			
			//echo $cclass_id;
			
					if (strlen($iid)>0 && strlen($cclass_id)>0 && strlen($cclass_name)>0 && strlen($exam_num)>0 ){
						echo ('<input type = "hidden" name = "cclass_id" id = "have_cclass_id" value = "'.$cclass_id.'"></input>'); 
						echo ('<input type = "hidden" name = "cclass_name" id = "have_cclass_name" value = "'.$cclass_name.'"></input>'); 
						echo $cclass_name;
			} else {
				
			echo ('&nbsp;<select name = "cclass_id" id = "current_class_dd" >');
		
			echo('</select>');
				
				
			}
			
			
		
		?>
		</br>	
		</br>
	
       
         <input type="hidden" id = "examactivity_id" name="examactivity_id" value="<?php echo ($examactivity_id)?>" >
		
	<p><input type = "submit" name = "submit_form" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	</form>
	</br>
    </br>
    
    
	<form method = "POST">
		<p><input type = "submit" value="Reset Input" name = "reset"  size="2" style = "width: 30%; background-color: #FAF1BC; color: black"/> &nbsp &nbsp </p>  
	</form>

<script>

$("#iid").change(function(){
		var	 iid = $("#iid").val();
		
			$.ajax({
					url: 'getcurrentclass.php',
					method: 'post',
						data: {iid:iid}
				
				}).done(function(cclass){
					cclass = JSON.parse(cclass);
					 // now get the currentclass_id
			$.ajax({
					url: 'getcurrentclass_id.php',
					method: 'post',
						data: {iid:iid}
				
				}).done(function(cclass_id){
					cclass_id = JSON.parse(cclass_id);
					 $('#current_class_dd').empty();
					n = cclass.length;
				//		console.log("n: "+n);
						$('#current_class_dd').append("<option selected disabled hidden> Please Select Course </option>") ;
						for (i=0;i<n;i++){
							
							  $('#current_class_dd').append('<option  value="' + cclass_id[i] + '">' + cclass[i] + '</option>');
					}
				})
			})
		});
        


	/* 
    
    // already been through and worked a problem and now getting another one all of the input fields should be defined just need another problem
	   
        var examtime_id = $('#examtime_id').val();		
            console.log("examtime_id: "+examtime_id);  
      //  examtime_id = 2;  // override to see if I can get it working
               console.log("globephase "+$('#globephase').val());  
               
       if($('#have_iid').val()!= undefined && $('#have_cclass_id').val()!= undefined && $('#have_cclass_name').val()!= undefined && $('#have_exam_num').val()!= undefined && $('#exam_code_error').val()!= 1 && $('#globephase').val()!= 0 ){

		var iid = $('#have_iid').val();
 		var cclass_id = $('#have_cclass_id').val();
		var cclass_name = $('#have_cclass_name').val();
		var exam_num = $('#have_exam_num').val();	
		
            console.log("iid: "+iid);
			console.log("cclass_id: "+cclass_id);
			console.log("cclass_name: "+cclass_name);
			console.log("exam_num: "+exam_num);
	
            
            
            
            $.ajax({
					url: 'getactivealiasexam.php',
					method: 'post',
					data: {exam_num:exam_num,currentclass_id:cclass_id}
				
				}).done(function(activealiasexam){
				
					activealias = JSON.parse(activealiasexam);
					 	 $('#alias_num_div').empty();
					n = activealias.length;
						$('#alias_num_div').append(" <font color=#003399> Select Problem for this Exam : </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;
						for (i=0;i<n;i++){
							$('#alias_num_div').append('<input  name="alias_num"  type="radio"  value="'+activealias[i]+'"/> '+activealias[i]+'&nbsp; &nbsp; &nbsp;') ;

					}
								$('#alias_num_div').append(" </br> </br> <font> Note - After pressing submit, if the problem fails to fully load - refresh the page using the browser </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;

				}) 
		
	} else {
		$("#iid").change(function(){
		var	 iid = $("#iid").val();
		
			$.ajax({
					url: 'getcurrentclass.php',
					method: 'post',
						data: {iid:iid}
				
				}).done(function(cclass){
					cclass = JSON.parse(cclass);
					 // now get the currentclass_id
			$.ajax({
					url: 'getcurrentclass_id.php',
					method: 'post',
						data: {iid:iid}
				
				}).done(function(cclass_id){
					cclass_id = JSON.parse(cclass_id);
					 $('#current_class_dd').empty();
					n = cclass.length;
				//		console.log("n: "+n);
						$('#current_class_dd').append("<option selected disabled hidden> Please Select Course </option>") ;
						for (i=0;i<n;i++){
							
							  $('#current_class_dd').append('<option  value="' + cclass_id[i] + '">' + cclass[i] + '</option>');
					}
				})
			})
		});
        
        
        
        
        
        
			
	}	
			


    // this is getting the Exam number once the course has been selected
			$("#current_class_dd").change(function(){
				 $('#alias_num_div').empty();
		var	 currentclass_id = $("#current_class_dd").val();
			console.log ('currentclass_id: '+currentclass_id);
			$.ajax({
					url: 'getactiveexam.php',
					method: 'post',
					data: {currentclass_id:currentclass_id}
				}).done(function(activeexam){
					activeexam = JSON.parse(activeexam);
					 	 $('#exam_num').empty();
						
				
					n = activeexam.length;
						$('#exam_num').append("<option selected disabled hidden>  </option>") ;
						for (i=0;i<n;i++){
							  $('#exam_num').append('<option  value="' + activeexam[i] + '">' + activeexam[i] + '</option>');
					}
				}) 
			});
			
			// this is getting the problem numbers (alias number) once the course has been selected
            
            
            
     // Need to periodically check the examtime table to see if the globephase has changed	

                 var request;
                function fetchPhase() {
                    request = $.ajax({
                        method: "post",
                        url: "fetchGPhase.php",
                        data: {examtime_id:examtime_id},
                        success: function(data){
                           try{
                                var arrn = JSON.parse(data);
                            }
                            catch(err) {
                                console.log(data);
                                console.log ('globephase error = '+globephase);
                               console.log (err);
                                return;
                            }
                            
                             var globephase = arrn.globephase;
                            var end_of_phase = arrn.end_of_phase;
                            	console.log ('globephase = '+globephase);
                             // var alias_num = $('#alias_num_div').val(); 
                               var alias_num_val = $("input[name='alias_num']:checked").val();
                                	console.log ('alias_num_37 = '+alias_num_val);
                                console.log ('globephase = '+globephase);
                          



                           if(globephase == 1 && alias_num_val>=0 ){  // submit away work time has eneded this is going to stop game and not back to the router
                            
                               $('#alias_num_id').val(alias_num_val);

                               $('#the_form').attr('action',"QRExamcontroller.php")


                            //  $("#globephase").attr('value', globephase);
                             //  SubmitAway(); 
                            } 
                        }
                    });
                }
               
                 function SubmitAway() { 
                  
                        document.getElementById('the_form').submit();
                    }


    setInterval(function() {
                    if (request) request.abort();
                    fetchPhase();
                }, 3000);







           

                
     
     
     
         var submitted = $("#submitted").val();   
            console.log("submitted: "+submitted);
			if(submitted ==1) {
                
            var	 exam_num = $("#exam_num").val();
            var	 currentclass_id = $("#current_class_dd").val();
            var	 globephase = $("#globephase").val();
            var	 exam_code_error = $("#exam_code_error").val();
        
         console.log("globephase: "+globephase);
         console.log("exam_code_error 21: "+exam_code_error);
        
        
			// console.log ('currentclass_id 2nd time: '+currentclass_id);
			$.ajax({
					url: 'getactivealiasexam.php',
					method: 'post',
					data: {exam_num:exam_num,currentclass_id:currentclass_id}
				
				}).done(function(activealiasexam){
				
					activealiasexam = JSON.parse(activealiasexam);
                    console.log(activealiasexam);
                    
					 	 $('#alias_num_div').empty();
					n = activealiasexam.length;
					console.log('n: '+ n);	
                     alert('n='+n);   
                        if(exam_code_error!=1){
                              if(globephase ==1){  
                                
                                $('#alias_num_div').append(" <font color=#003399> Select Problem for this Exam : </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;

                                for (i=0;i<n-1;i++){
                                        
                                        //could put in code to get from the activity table which problems have been attempted and which are complete and color the radio buttons different
                                        $('#alias_num_div').append('<input  name="alias_num"  type="radio"  value="'+activealiasexam[i]+'"/> '+activealiasexam[i]+'&nbsp; &nbsp; &nbsp;') ;
                                }
                                $('#alias_num_div').append(" </br> </br> <font> Note - After pressing submit, if the problem fails to fully load - refresh the page using the browser </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;
                            
                            } else {
                                
                                  $('#alias_num_div').append(" <font color=#003399> Instructor Has Not Yet Started Exam </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;
                            }
                
                        } else {
                              $('#alias_num_div').append(" <font color=#003399> Exam Code Error </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;
                        }
                }) 
			};
		
		var dex = pass['dex'];
		var problem = pass['problem_id'];
		var s_name = pass['stu_name'];
		var pin = pass['pin'];
		var iid = pass['iid'];
		var exam_num = pass['exam_num'];
		var alias_num = pass['alias_num'];
		var cclass_id = pass['cclass_id'];
		var cclass_name = pass['cclass_name'];
        var examactivity_id = pass['examactivity_id'];
         var exam_code = pass['exam_code'];
         var exam_flag = 1;


    
		sessionStorage.setItem('dex',dex);
		sessionStorage.setItem('problem_id',problem);
		sessionStorage.setItem('stu_name',s_name);
		sessionStorage.setItem('pin',pin);
		sessionStorage.setItem('iid',iid);
		sessionStorage.setItem('exam_num',exam_num);
        sessionStorage.setItem('exam_code',exam_code);
		sessionStorage.setItem('alias_num',alias_num);
		sessionStorage.setItem('cclass_id',cclass_id);
		sessionStorage.setItem('cclass_name',cclass_name);
        sessionStorage.setItem('exam_flag',exam_flag);
        sessionStorage.setItem('examactivity_id',examactivity_id);
        

 
	// this is a function from 	https://stackoverflow.com/questions/19036684/jquery-redirect-with-post-data to post data and redirect without building a hidden form
	 	$.extend(
				{
					redirectPost: function(location, args)
					{
						var form = $('<form></form>');
						form.attr("method", "post");
						form.attr("action", location);

						$.each( args, function( key, value ) {
							var field = $('<input></input>');

							field.attr("type", "hidden");
							field.attr("name", key);
							field.attr("value", value);

							form.append(field);
						});
						$(form).appendTo('body').submit_form();
					}
				});

        
        
      
		sessionStorage.setItem('society_flag',society_flag);
		sessionStorage.setItem('reflect_flag',reflect_flag);
		sessionStorage.setItem('explore_flag',explore_flag);
		sessionStorage.setItem('connect_flag',connect_flag);
		sessionStorage.setItem('ref_choice',ref_choice);

     
     console.log('dex5 '+dex)
     
     
     
     
     
	var file = "QRExamcontroller.php";
	 $.redirectPost(file, { dex: dex, problem_id: problem, stu_name: s_name, pin: pin, iid: iid, exam_num: exam_num, alias_num: alias_num_val, cclass_id: cclass_id, examactivity_id :examactivity_id, exam_code :exam_code });
	
	  
		  */
</script>

</body>
</html>



