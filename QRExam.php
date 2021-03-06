<?php
	require_once "pdo.php";
	session_start();
	
 	// this is the  place where studetns pick their problem to work on.  It is fed from QRExamRegistration.php or QRdisplayExam.php it reads the values from the Examactivity table and diplays the options for them to choose there problem
    $complete = '';
    $alias_num = '';
    $iid = '';
    $cclass_id = '';
    $pin = '';
    $exam_code ='';
    $problem_id = '';
	$stu_name = '';
	$instr_last='';
    $cclass_name='';
    $dex='';
    $globephase = 0;
  
    // if we are comming into this with a Get from QRExam - this clears the address bars of the parameters
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

   if(isset($_POST['examactivity_id'])){
       $examactivity_id =  $_POST['examactivity_id'];
    } elseif(isset($_GET['examactivity_id'])){
       $examactivity_id =  $_GET['examactivity_id'];
    } else {
       // $_SESSION['error'] = 'examactivity_id was lost in QRExam.php';
        header("Location: QRExamRegistration.php");
		die();
        
    }
    
     $sql = "SELECT * FROM `Examactivity` WHERE examactivity_id = :examactivity_id";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
             ':examactivity_id' => $examactivity_id,
          )); 
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
             if($row != false){
                 $examtime_id = $row['examtime_id']; 
                $iid = $row['iid'];
                $pin = $row['pin'];
                $stu_name = $row['name'];
                $exam_code = $row['exam_code'];
                $cclass_id = $row['currentclass_id'];
                $suspend_flag = $row['suspend_flag'];

             } else {
                 $_SESSION['error'] = 'examactivity table could not be read in QRExam.php';
                header("Location: QRExamRegistration.php");
                die();  
             }

            $sql = " SELECT * FROM `Examtime` WHERE examtime_id = :examtime_id" ;
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                         ':examtime_id' => $examtime_id,
                    ));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($row != false){
                        $globephase = $row['globephase']; 
                        $exam_num = $row['exam_num'];
                    } else {
                       $_SESSION['error'] = 'examtime table could not read - Exam over or not Initiated';
                        header("Location: QRExamRegistration.php");
                        die();     
                    }
                    
              $sql = " SELECT `name` FROM `CurrentClass` WHERE currentclass_id = :currentclass_id" ;
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                         ':currentclass_id' => $cclass_id,
                    ));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($row != false){
                        $cclass_name = $row['name']; 
                    } else {
                       $_SESSION['error'] = 'Currentclass table could not read - Class Not Valid';
                        header("Location: QRExamRegistration.php");
                        die();     
                    }        
                    
         
                       
           if ($globephase==0){
               $_SESSION['success'] = 'Registration Successful - Waiting for Exam to Start';
           }
           if($globephase == 1){
               
               
               
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
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 

</head>

<body>
<header>
<h1>Quick Response Exam </h1>
</header>

<?php

//	if(isset($_POST['pin']) || isset($_POST['problem_id']) || isset($_POST['iid'])){
		
        
      //  $_SESSION['error'] = $_SESSION['error'].$_SESSION['error'];
 /*      
        echo 'current version 1';
        echo ('session error: '. $_SESSION['error_check']);
          if ( isset( $_SESSION['error_check']) ) {
			echo '<p style="color:red">'. $_SESSION['error_check']."</p>\n";
			//unset($_SESSION['error_check']);
		}
         */
        
        if ( isset($_SESSION['error']) ) {
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) ) {
			echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
			unset($_SESSION['success']);
		}
	//}
 
?>

<form autocomplete="off" method="POST" id = "the_form" action = "QRdisplayExamPblm.php" >
	
<p><font color=#003399>Exam Code:&nbsp;  <?php echo($exam_code);?> </font>
      
    </p>  
       
	<p><font color=#003399>Your Name: &nbsp;<?php echo($stu_name);?> </font>     </p>
	<p><font color=#003399>PIN: &nbsp;<?php echo($pin);?>    </font>   </p>
   
	
			<p><font color=#003399>Course:&nbsp;<?php echo($cclass_name);?> </font></p>
			
			<h2><font color=#003399>Select Problem from Exam: </font></h2>
            
            <?php
		
                 $sql = " SELECT * FROM `Exam` WHERE iid = :iid AND exam_num = :exam_num AND currentclass_id = :currentclass_id" ;
                       $stmt = $pdo->prepare($sql);
                        $stmt -> execute(array(
                         ':exam_num' => $exam_num,
                         ':iid' => $iid,
                         ':currentclass_id' => $cclass_id,
                        ));
                    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) 
                        { ?>
                           
                          <?php echo ($row['alias_num'])?>  <input  name="problem_id"  type="radio"  value= "<?php echo ($row['problem_id'])?>"> &nbsp;&nbsp;&nbsp;
                        <?php
                        } 
        
        
        
            ?>
		</br>	
		 <input type="hidden" id = "get_examactivity_id" name="get_examactivity_id" value="<?php echo ($_GET['examactivity_id']);?>" >

         <input type="hidden" id = "examactivity_id" name="examactivity_id" value="<?php echo ($examactivity_id)?>" >
		
	<p><input type = "submit" name = "submit_form" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	</form>
	

<script>
  
 function ipLookUp () {
             
   /*       
         $.get("https://ipinfo.io/json", function (response) {
    $("#ip").html("IP: " + response.ip);
    $("#address").html("Location: " + response.city + ", " + response.region);
    $("#details").html(JSON.stringify(response, null, 4));
}, "jsonp");
         
          */
         
         
         

             $.ajax('https://ipinfo.io/json')
              .then(
                  function success(response) {
                      console.log('User\'s Location Data is ', response);
                      console.log('User\'s Country', response.country);
                      console.log('User\'s City', response.city);
                      console.log('User\'s Region', response.region);
                        var country = response.country
                        var region = response.region
                        var city = response.city
                        var examactivity_id = $("#get_examactivity_id").val();
                         console.log('User\'s Region', region);
                           console.log('examactivity_id: ', examactivity_id);
                           // make an AJAX call to put this infor in the examactivity table

                      if (typeof examactivity_id != "undefined") {

                                $.ajax({
                                method: "post",
                                url: "putExamLocation.php",
                                data: {
                                    country :country,
                                    region :region,
                                    city :city,
                                    examactivity_id :examactivity_id
                                }
                            }).done();

                     }
                    
                 

                  function fail(data, status) {
                      console.log('Request failed.  Returned status of',
                                  status);
                  }
                }
             );
              
            }

























  
/* 
   function ipLookUp () {
              $.ajax('http://ip-api.com/json')
              .then(
                  function success(response) {
                      console.log('User\'s Location Data is ', response);
                      console.log('User\'s Country', response.country);
                      console.log('User\'s City', response.city);
                      console.log('User\'s Region', response.region);
                        var country = response.country
                        var region = response.region
                        var city = response.city
                        var examactivity_id = $("#get_examactivity_id").val();
                         console.log('User\'s Region', region);
                           console.log('examactivity_id: ', examactivity_id);
                           // make an AJAX call to put this infor in the examactivity table

                      if (typeof examactivity_id != "undefined") {

                                $.ajax({
                                method: "post",
                                url: "putExamLocation.php",
                                data: {
                                    country :country,
                                    region :region,
                                    city :city,
                                    examactivity_id :examactivity_id
                                }
                            }).done();

                     }
                    
                 

                  function fail(data, status) {
                      console.log('Request failed.  Returned status of',
                                  status);
                  }
                }
             );
              
            }
             */
          
       
              
             
              ipLookUp ();
         
      




















/* 
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
        

 */
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



