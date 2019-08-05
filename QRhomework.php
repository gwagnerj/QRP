<?php
	require_once "pdo.php";
	session_start();
	if (isset($_SESSION['error'])){
	echo $_SESSION['error']	;
	unset($_SESSION['error']);
	}
	// this is the normal place to start for students checking their homework and goes the the QRcontroller.  Can also come from the rtnCode.php or the back button on QRdisplay.php
	$alias_num = $problem_id = $assign_num = $cclass_id ='';
	
	$_SESSION['progress']=0;
	$_SESSION['checker']=0;  // tells where the getiid where to come back to here or the checker
	$stu_name = '';
	$problem_id= '';
	$index='';
	$pin='';
	$iid='';
	$instr_last='';
 
	// first time thru set scriptflag to zero - this will turn to 1 if the script ran
	if (!isset($sc_flag)){$sc_flag=0;}
			

	// if Get is set then it is coming from rtncode.php and may wnat another problem
	
	if(isset($_GET['stu_name'])){
		
		$stu_name = htmlentities($_GET['stu_name']);
	} elseif (isset($_SESSION['stu_name'])){
		$stu_name = htmlentities($_SESSION['stu_name']);
	}
	
	if(isset($_GET['problem_id'])){
		$problem_id = htmlentities($_GET['problem_id']);
	} 
	
	/* elseif (isset($_SESSION['problem_id'])){
		$problem_id = htmlentities($_SESSION['problem_id']);
	} */
	
	if(isset($_GET['pin'])){
		$pin = htmlentities($_GET['pin']);
	} elseif (isset($_SESSION['pin'])){
		$pin = htmlentities($_SESSION['pin']);
	}
	if(isset($_GET['assign_num'])){
		$assign_num = htmlentities($_GET['assign_num']);
	} elseif(isset($_SESSION['assign_num'])){
		 	$assign_num = htmlentities($_SESSION['assign_num']);
	} 
	
	if(isset($_GET['iid'])){
		$iid = htmlentities($_GET['iid']);
	} elseif (isset($_SESSION['iid'])){
		$iid = htmlentities($_SESSION['iid']);
	}
	
	
	if(isset($_POST['stu_name'])){
		$stu_name = htmlentities($_POST['stu_name']);
		$_SESSION['stu_name']=$stu_name;
	} 
// Go get the problem id from the Assignment table
	if(isset($_POST['assign_num'])&& isset($_POST['alias_num'])&& isset($_POST['iid']) && isset($_POST['cclass_id']) ){
		$assign_num = htmlentities($_POST['assign_num']);
		$alias_num = htmlentities($_POST['alias_num']);
		$cclass_id = htmlentities($_POST['cclass_id']);
		$iid = htmlentities($_POST['iid']);
		$_SESSION['assign_num'] = $assign_num;
		$_SESSION['cclass_id'] = $cclass_id;
		//$_SESSION['alias_num'] = $alias_num;
		$sql = " SELECT * FROM `Assign` WHERE iid = $iid AND assign_num = $assign_num AND alias_num = $alias_num AND currentclass_id = $cclass_id"   ;
				$stmt = $pdo->query($sql);
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				if ( $row == false) {
					$_SESSION['ERROR'] = 'No Problem found for these Input Values';
				} else {
					$problem_id = $row['prob_num'];
					$_SESSION['problem_id']=$problem_id;
					// get the name of the current class from the CurrentClass table
					$sql = "SELECT * FROM `CurrentClass` WHERE currentclass_id = $cclass_id";
					$stmt = $pdo->query($sql);
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
					if ( $row == false) {
						$cclass_name = "";
					} else {
							$cclass_name = $row['name'];
						
					}
				}
	
	//	$problem_id = htmlentities($_POST['problem_id']);
	//		$_SESSION['problem_id']=$problem_id;
	} else {
	
		 $_SESSION['error']='The Class, Assignment, Problem and instructor ID are all Required';
		}
	if(isset($_POST['pin'])){
		$pin = htmlentities($_POST['pin']);
		if ($pin>10000 or $pin<0){
				$_SESSION['error']='Your PIN should be between 1 and 10000.';	
			} else {
				$_SESSION['pin']=$pin;
				$dex = ($pin-1) % 199 + 2; // % is PHP mudulus function - changing the PIN to an index between 2 and 200
				$_SESSION['dex'] = $dex;
			}
	} else {
		//$_SESSION['error']='Your PIN is Required';
	}

	if(isset($_POST['iid'])){
		$iid = htmlentities($_POST['iid']);
		
		$_SESSION['iid']=$iid;
		
				$sql = " SELECT 'user_id' FROM Users WHERE users_id = $iid" ;
					$stmt = $pdo->query($sql);
					if($stmt->rowCount()){
						$sql2 = " SELECT 'iid' FROM Assign WHERE iid = $iid" ;
						$stmt2 = $pdo->query($sql2);
						if($stmt2->rowCount()){
							// put this in so that if a negative problem_id is put in we go right  to the problem
								$pos_problem_id = abs($problem_id);
								// check to see that we have an active assignment for that problem by that instructor
								$sql3 = "SELECT * FROM Assign WHERE iid=:iid AND prob_num=$pos_problem_id" ;
								$stmt3 = $pdo->prepare($sql3);
								$stmt3->execute(array(':iid' => $iid));
								$row3 =$stmt3 ->fetch();
			//if ( $activity_row === false ) {
			/* 					
								$stmt = "SELECT DISTINCT assign_num
			FROM Assign 
			WHERE currentclass_id ='".$currentclass_id."' ORDER BY assign_num DESC"; 
			$stmt = $pdo->prepare($stmt);	
			$stmt->execute();
			$activeass = $stmt->fetchAll(PDO::FETCH_NUM);
					 */			
								
								
								
								if($row3 != false){
									// go the controller
									$_SESSION['progress']=1;
								
									$_POST['progress']=0;
									$_POST['checker']=0; 
								
								
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
										'ref_choice' => $row3['ref_choice']
																				
									);
								// echo $row3['society_flag'];
							// die();
									echo '<script>';
								//	echo 'console.log('.$pass.');';
									
									echo 'var pass = ' . json_encode($pass) . ';';
									echo '</script>';
								

									//header("Location: QRcontroller.php");
									//return; 
									
								} else {

									$_SESSION['error']	='The Instructor with this ID has not made this problem active.';	
								}									
					
					
					
						} else {
							
						$_SESSION['error']	='The Instructor for this ID has no active problems';	
						}
					
					} else {
					$_SESSION['error']	='The Instructor ID is not in the database.';	
					}
					
					
					} else {
						
					// $_SESSION['error']='The Instructor ID is Required';	
						
						
					}
		if (isset($_POST['reset']))	{
			
			$iid = '';
			$stu_name = '';
			$pin = '';
			'session_unset';
			$last = '';
			$first = '';
			$alias_num = $assign_num = $cclass_id = '';
			
			
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
	
	<p><font color=#003399>Your PIN: </font><input type="number" min = "1" max = "10000" name="pin" id="pin_id" size=3 required value=<?php echo($pin);?> ></p>
	<div id ="instructor_id">	
				<font color=#003399> Instructor: &nbsp; </font>
				<?php 
					// $iid=1;
					if (strlen($iid)>0 ){
						
						
						$sql = 'SELECT users_id, last, first FROM Users WHERE `users_id` = :iid';
						$stmt = $pdo->prepare($sql);
						$stmt -> execute(array(':iid' => $iid));
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						$last = $row['last'];
						$first = $row['first'];
						echo ('<input type = "hidden" name = "have_iid" id = "have_iid" value = "'.$iid.'"></input>'); 
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
			 &nbsp;<select name = "cclass_id" id = "current_class_dd" required>
		
		</select>
		</br>	
		</br>
		<font color=#003399>Assignment Number: </font>
			 &nbsp;<select name = "assign_num" id = "assign_num">
			
		
		</select>
		</br>	
		<br>
		
		<div id = "alias_num_div">
		
		</div>
		</br>	
		<br>
		
	<p><input type = "submit" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	</form>
	</br>
	<form method = "POST">
		<p><input type = "submit" value="Reset form" name = "reset"  size="2" style = "width: 30%; background-color: light yellow; color: black"/> &nbsp &nbsp </p>  
	</form>

<script>
	var haveval = $('#have_iid').val();
	console.log("haveval: "+haveval);
	if($('#have_iid').val()!= undefined){
		console.log("yip");
		var iid = $('#have_iid').val();
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
			console.log ('currentclass_id 2nd time: '+currentclass_id);
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
							$('#alias_num_div').append('<input  name="alias_num"  type="radio"  value="'+activealias[i]+'"/> '+activealias[i]+'&nbsp; &nbsp; &nbsp;') ;

					}
					
				}) 
			});

</script>

<script>

 
	// this is a function from 	https://stackoverflow.com/questions/19036684/jquery-redirect-with-post-data to post data and redirect without building a hidden from
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
	 $.redirectPost(file, { progress: "1", dex: dex, problem_id: problem, stu_name: s_name, pin: pin, iid: iid, assign_num: assign_num, alias_num: alias_num, cclass_id: cclass_id });
	
	  
		 
</script>

</body>
</html>



