<?php
	require_once "pdo.php";
	session_start();
	if (isset($_SESSION['error'])){
	echo $_SESSION['error']	;
	unset($_SESSION['error']);
	}
	// this is the normal place to start for students checking their homework and goes the the QRcontroller.  Can also come from the rtnCode.php or the back button on QRdisplay.php  This will
    
	$alias_num = $problem_id = $assign_num = $cclass_id ='';
	$progress = 0;
	$stu_name = '';
	$problem_id= '';
	$index='';
	$pin='';
	$iid='';
	$instr_last='';
 
	// first time thru set scriptflag to zero - this will turn to 1 if the script ran
//	if (!isset($sc_flag)){$sc_flag=0;}
	
    if(isset($_GET['activity_id']) || isset($_POST['activity_id'])){
		if(isset($_GET['activity_id'])){$activity_id = htmlentities($_GET['activity_id']);}
        if(isset($_POST['activity_id'])){$activity_id = htmlentities($_POST['activity_id']);}
        // get the information from the activity table
       // most things will be the same except the problem_id, alias number and once they select a problem we need to 
       // check the Activity table of send all of these to the controller or somekind of pass through file


       $sql = 'SELECT * FROM `Activity` WHERE `activity_id` = :activity_id';
        $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':activity_id' => $activity_id));
         $activity_data = $stmt -> fetch();
         $iid = $activity_data['iid'];   
         $pin = $activity_data['pin'];   
         $stu_name = $activity_data['stu_name'];   
         $cclass_id = $activity_data['currentclass_id']; 
         $currentclass_id = $activity_data['currentclass_id'];            
        // $instr_last = $activity_data['instr_last'];   
        // $university = $activity_data['university'];   
         $dex = $activity_data['dex'];  
         $alias_num = $activity_data['alias_num'];  
         $assign_id = $activity_data['assign_id'];  
         $progess = $activity_data['progress'];  
         // $problem_id = $activity_data['problem_id'];  
        
        $sql = 'SELECT name FROM Currentclass WHERE currentclass_id = :currentclass_id';
         $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':currentclass_id' => $currentclass_id));
         $class_data = $stmt -> fetch();
         $class_name = $class_data['name'];
         $cclass_name = $class_data['name'];
         
        $sql = 'SELECT `assign_num` FROM Assign WHERE assign_id = :assign_id AND iid = :iid AND currentclass_id = :currentclass_id';
         $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':assign_id' => $assign_id,
                    ':iid' => $iid,
                     ':currentclass_id' => $cclass_id,
         ));
         $assign_data = $stmt -> fetch();
         $assign_num = $assign_data['assign_num'];
        
         
      } 
        // this is the first time through if we do not have an activity_id - The activity is unique for a particular student for a particular problem
        $activity_id = '';
      
    
    
	if(isset($_POST['stu_name'])){
		$stu_name = htmlentities($_POST['stu_name']);
	} 
    
   
    
// Go get the problem id from the Assignment table
	if(isset($_POST['submit'])&& isset($_POST['assign_num'])&& isset($_POST['alias_num'])&& isset($_POST['iid']) && isset($_POST['cclass_id']) && isset($_POST['pin'])) {
        $assign_num = htmlentities($_POST['assign_num']);
		$alias_num = htmlentities($_POST['alias_num']);
		$cclass_id = htmlentities($_POST['cclass_id']);
		$iid = htmlentities($_POST['iid']);
        $pin = htmlentities($_POST['pin']);

// need to get the info from the assignment table so I can check to see if we already have an activity Id for this
        $sql = 'SELECT * FROM `Assign` WHERE `currentclass_id` = :cclass_id  AND `iid`=:iid  AND `assign_num` = :assign_num AND `alias_num` = :alias_num';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':cclass_id' => $cclass_id,
                                ':iid' => $iid,
                                ':assign_num' => $assign_num,
                                ':alias_num' => $alias_num,
         ));
         $assign_data = $stmt -> fetch();
         
         $problem_id = $assign_data['prob_num'];
          $assign_id = $assign_data['assign_id'];   
        
        // check to see if we already have activity if we do we can get the activity id and move on to controller
       $sql = 'SELECT `activity_id` FROM `Activity` WHERE `problem_id` = :problem_id AND `currentclass_id` = :cclass_id AND `pin` = :pin AND `iid`=:iid AND `assign_id` = :assign_id ';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':problem_id' => $problem_id,
                                ':cclass_id' => $cclass_id,
                                ':pin' => $pin,
                                ':iid' => $iid,
                                ':assign_id' => $assign_id
         ));
         $activity_data = $stmt -> fetch();
         $activity_id = $activity_data['activity_id'];
      //   echo (" activity_data (already have value):  ".$activity_id); //__________________________________________________________________________
         
         if(strlen($activity_id)< 1){
           
           // make a new entry in the activity table 
           
           if ($pin>10000 or $pin<0){
                 $_SESSION['error']='Your PIN should be between 1 and 10000.';	
            } else {
                $dex = ($pin-1) % 199 + 2; // % is PHP mudulus function - changing the PIN to an index between 2 and 200
            }
					
            // get the name of the current class from the CurrentClass table
            $sql = "SELECT * FROM `CurrentClass` WHERE currentclass_id = :cclass_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':cclass_id' => $cclass_id,
                    ));        
                 $currentclass_data = $stmt -> fetch();
                 $cclass_name = $currentclass_data['name'];
             
             // get the info on the instructor from the Users table
            
                $sql = "SELECT `last` FROM `Users` WHERE users_id = :iid";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(
                        ':iid' => $iid,
                        ));        
                     $users_data = $stmt -> fetch();
                     $instr_last = $users_data['last'];
                    

                        // go the controller
                    /* 
                    
                        // We are going transfer the variables that we have so far - iid, pin, problem_id, to js and that script will put these in local session varaibles for the subsequent
                        // files - this will allow the student to pull up muliple sessions in different tabs of the same browser
                        $pass = array(
                            'dex' => $_SESSION['dex'],
                            'problem_id' => $_SESSION['problem_id'],
                            'stu_name' => $_SESSION['stu_name'],
                            'pin' => $_SESSION['pin'],
                            'iid' => $_SESSION['iid'],
                            'assign_num' => $_SESSION['assign_num'],
                            'alias_num' => $alias_num,
                            'cclass_id' => $cclass_id,
                            'cclass_name' => $cclass_name,
                            'society_flag' => $row3['society_flag'],
                            'explore_flag' => $row3['explore_flag'],
                            'reflect_flag' => $row3['reflect_flag'],
                            'connect_flag' => $row3['connect_flag'],
                            'activity_id' => $activity_id,
                            'ref_choice' => $row3['ref_choice']
                                                                    
                        );
                
                        echo '<script>';
                        echo 'var pass = ' . json_encode($pass) . ';';
                        echo '</script>';
                     */
                    // check the activity table and see if there is an entry if not make a new entry and go to the controller

                         
                        $sql = 'INSERT INTO Activity (problem_id, pin, iid, dex,     assign_id,  instr_last, university, pp1, pp2, pp3, pp4, post_pblm1, post_pblm2, post_pblm3, score, progress, stu_name, alias_num, currentclass_id, count_tot)	
                                             VALUES (:problem_id, :pin, :iid, :dex, :assign_id, :instr_last,:university,:pp1,:pp2,:pp3,:pp4,:post_pblm1,:post_pblm2,:post_pblm3, :score,:progress, :stu_name, :alias_num, :cclass_id, :count_tot)';
                        $stmt = $pdo->prepare($sql);
                        $stmt -> execute(array(
                        ':problem_id' => $problem_id,
                        ':pin' => $pin,
                        ':iid' => $iid,
                        ':dex' => $dex,
                       ':assign_id' => $assign_id,
                       ':instr_last' => $instr_last,
                        ':university' => $assign_data['university'],
                        ':pp1' => $assign_data['pp_flag1'],
                        ':pp2' => $assign_data['pp_flag2'],
                        ':pp3' => $assign_data['pp_flag3'],
                        ':pp4' => $assign_data['pp_flag4'],
                        ':post_pblm1' => $assign_data['postp_flag1'],
                        ':post_pblm2' => $assign_data['postp_flag2'],
                        ':post_pblm3' => $assign_data['postp_flag3'],
                        ':score' => 0,
                        ':progress' => 1,
                        ':stu_name' => $stu_name,
                        ':alias_num' => $alias_num,
                        ':cclass_id' => $cclass_id,
                         ':count_tot' => 0
                        ));
                                    
                          $sql = 'SELECT `activity_id` FROM `Activity` WHERE `problem_id` = :problem_id AND `currentclass_id` = :cclass_id AND `pin` = :pin AND `iid`=:iid AND `assign_id` = :assign_id ';
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute(array(':problem_id' => $problem_id,
                                ':cclass_id' => $cclass_id,
                                ':pin' => $pin,
                                ':iid' => $iid,
                                ':assign_id' => $assign_id
                                ));
                                $activity_row =$stmt ->fetch();		
                                $activity_id = $activity_row['activity_id'];
                  //   echo (" activity_data (new value):  ".$activity_id;     
         }   

          header("Location: QRcontroller.php?activity_id=".$activity_id);
			return; 
		
     } elseif(isset($_POST['submit'])) {
		
		 $_SESSION['error']='The Class, Assignment, Problem and instructor ID are all Required';
	}
    
		if (isset($_POST['reset']))	{
			
			$iid = '';
			$stu_name = '';
			$pin = '';
			$last = '';
			$first = '';
			$alias_num = $assign_num = $cclass_id = $activity_id = '';
		//	session_destroy();
			
		}

	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRHomework</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>

<body>
<header>
<h1>Quick Response Homework </h1>
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

<form autocomplete="off" method="POST" >
	  
	<p><font color=#003399>Your Name: </font><input type="text" name="stu_name" id = "stu_name_id" size= 20  value="<?php echo($stu_name);?>" ></p>
	<input autocomplete="false" name="hidden" type="text" style="display:none;">
	<p><font color=#003399>Your PIN: </font><input type="number"  min = "1" max = "10000" name="pin" id="pin_id" size=3 required value=<?php echo($pin);?> ></p>
	<div id ="instructor_id">	
				<font color=#003399> Instructor: &nbsp; </font>
				<?php 
					// $iid=1;
                   /*  
                    echo (' iid: '.$iid);
                    echo (' cclass_id: '.$cclass_id);
                    echo (' cclass_name: '.$cclass_name);
                    echo (' assign_num: '.$assign_num);
                     */
					if (strlen($iid)>0 && strlen($cclass_id)>0 && strlen($cclass_name)>0 && strlen($assign_num)>0 ){
						
						
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
			
			
					if (strlen($iid)>0 && strlen($cclass_id)>0 && strlen($cclass_name)>0 && strlen($assign_num)>0 ){
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
		<font color=#003399>Assignment Number: </font>
			<?php
			
			if (strlen($iid)>0 && strlen($cclass_id)>0 && strlen($cclass_name)>0 && strlen($assign_num)>0 ){
				echo ('<input type = "hidden" name = "assign_num" id = "have_assign_num" value = "'.$assign_num.'"></input>'); 
				echo $assign_num;
			} else {
			
			echo(' &nbsp;<select name = "assign_num" id = "assign_num">');
			echo('</select>');
			}
			
			?>
		</br>	
		<br>
		
		<div id = "alias_num_div">
		
		</div>
		
		
	<p><input type = "submit" name = "submit" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	</form>
	</br>
	<form method = "POST">
		<p><input type = "submit" value="Reset Input" name = "reset"  size="2" style = "width: 30%; background-color: #FAF1BC; color: black"/> &nbsp &nbsp </p>  
	</form>

<script>
	// already been through and worked a problem and now getting another one all of the input fields should be defined just need another problem
	if($('#have_iid').val()!= undefined && $('#have_cclass_id').val()!= undefined && $('#have_cclass_name').val()!= undefined && $('#have_assign_num').val()!= undefined){
	
		var iid = $('#have_iid').val();
 		var cclass_id = $('#have_cclass_id').val();
		var cclass_name = $('#have_cclass_name').val();
		var assign_num = $('#have_assign_num').val();	
			console.log("iid: "+iid);
			console.log("cclass_id: "+cclass_id);
			console.log("cclass_name: "+cclass_name);
			console.log("assign_num: "+assign_num);
			$.ajax({
					url: 'getactivealias.php',
					method: 'post',
					data: {assign_num:assign_num,currentclass_id:cclass_id}
				
				}).done(function(activealias){
				
					activealias = JSON.parse(activealias);
					 	 $('#alias_num_div').empty();
					n = activealias.length;
						$('#alias_num_div').append(" <font color=#003399> Select Problem for this Assignment : </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;
						for (i=0;i<n;i++){
							$('#alias_num_div').append('<input  name="alias_num"  type="radio"  value="'+activealias[i]+'"/> '+activealias[i]+'&nbsp; &nbsp; &nbsp;') ;

					}
								$('#alias_num_div').append(" </br> </br> <font> Note - After pressing submit, if the problem fails to fully load - refresh the page using the browser </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;

				}) 
		
	} else {
		$("#iid").change(function(){
		var	 iid = $("#iid").val();
		 $('#alias_num_div').empty();
		  $('#assign_num').empty();
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
			
			// this is getting the assignment number once the course has been selected
			$("#current_class_dd").change(function(){
				 $('#alias_num_div').empty();
		var	 currentclass_id = $("#current_class_dd").val();
			console.log ('currentclass_id: '+currentclass_id);
			$.ajax({
					url: 'getactiveassignments.php',
					method: 'post',
					data: {currentclass_id:currentclass_id}
				}).done(function(activeass){
					activeass = JSON.parse(activeass);
					 	 $('#assign_num').empty();
				
					n = activeass.length;
						$('#assign_num').append("<option selected disabled hidden>  </option>") ;
						for (i=0;i<n;i++){
							  $('#assign_num').append('<option  value="' + activeass[i] + '">' + activeass[i] + '</option>');
					}
				}) 
			});
			
			// this is getting the problem numbers (alias number) once the course has been selected
			$("#assign_num").change(function(){
		var	 assign_num = $("#assign_num").val();
		var	 currentclass_id = $("#current_class_dd").val();
		
			// console.log ('currentclass_id 2nd time: '+currentclass_id);
			$.ajax({
					url: 'getactivealias.php',
					method: 'post',
					data: {assign_num:assign_num,currentclass_id:currentclass_id}
				
				}).done(function(activealias){
				
					activealias = JSON.parse(activealias);
					 	 $('#alias_num_div').empty();
					n = activealias.length;
						$('#alias_num_div').append(" <font color=#003399> Select Problem for this Assignment : </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;
						for (i=0;i<n;i++){
							
							//could put in code to get from the activity table which problems have been attempted and which are complete and color the radio buttons different
							
							
							
							
							$('#alias_num_div').append('<input  name="alias_num"  type="radio"  value="'+activealias[i]+'"/> '+activealias[i]+'&nbsp; &nbsp; &nbsp;') ;

					}
					$('#alias_num_div').append(" </br> </br> <font> Note - After pressing submit, if the problem fails to fully load - refresh the page using the browser </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;
				}) 
			});

</script>

<script>

 /* 
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
						$(form).appendTo('body').submit();
					}
				});
		var activity_id = pass['activity_id'];
		var dex = pass['dex'];
		var problem = pass['problem_id'];
		var s_name = pass['stu_name'];
		var pin = pass['pin'];
		var iid = pass['iid'];
		var assign_num = pass['assign_num'];
		var alias_num = pass['alias_num'];
		var cclass_id = pass['cclass_id'];
		var cclass_name = pass['cclass_name'];
		var society_flag = pass['society_flag'];
		var reflect_flag = pass['reflect_flag'];
		var connect_flag = pass['connect_flag'];
		var explore_flag = pass['explore_flag'];
		var ref_choice = pass['ref_choice'];
	console.log('society_flag: '+society_flag);
		sessionStorage.setItem('dex',dex);
		sessionStorage.setItem('problem_id',problem);
		sessionStorage.setItem('stu_name',s_name);
		sessionStorage.setItem('pin',pin);
		sessionStorage.setItem('iid',iid);
		sessionStorage.setItem('assign_num',assign_num);
		sessionStorage.setItem('alias_num',alias_num);
		sessionStorage.setItem('cclass_id',cclass_id);
		sessionStorage.setItem('cclass_name',cclass_name);
		sessionStorage.setItem('society_flag',society_flag);
		sessionStorage.setItem('reflect_flag',reflect_flag);
		sessionStorage.setItem('explore_flag',explore_flag);
		sessionStorage.setItem('connect_flag',connect_flag);
		sessionStorage.setItem('ref_choice',ref_choice);
	
	var file = "QRcontroller.php";
// $.redirectPost(file, { progress: "1", dex: dex, problem_id: problem, stu_name: s_name, pin: pin, iid: iid, assign_num: assign_num, alias_num: alias_num, cclass_id: cclass_id });
	
	  
		  */
</script>

</body>
</html>



