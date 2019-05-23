<?php
	require_once "pdo.php";
	session_start();
	
	// this is the normal place to start for students checking their homework and goes the the QRcontroller.  Can also come from the rtnCode.php or the back button on QRdisplay.php
	
	
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
	} elseif (isset($_SESSION['problem_id'])){
		$problem_id = htmlentities($_SESSION['problem_id']);
	}
	
	if(isset($_GET['pin'])){
		$pin = htmlentities($_GET['pin']);
	} elseif (isset($_SESSION['pin'])){
		$pin = htmlentities($_SESSION['pin']);
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

	if(isset($_POST['problem_id'])){
		
		$problem_id = htmlentities($_POST['problem_id']);
			$_SESSION['problem_id']=$problem_id;
	} else {
	//	$_SESSION['error']='The Problem Number is Required';
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
								$sql3 = "SELECT * FROM Assign WHERE iid=$iid AND prob_num=$pos_problem_id" ;
								$stmt3 = $pdo->query($sql3);
								if($stmt3->rowCount()){
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
									);
									// echo ($pass['society_flag']);
									//die();
									echo '<script>';
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

<!--<h3>Print the problem statement with "Ctrl P"</h3>
 <p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p>  -->
<form autocomplete="off" method="POST">
	
	<p><font color=#003399>Name: </font><input type="text" name="stu_name" id = "stu_name_id" size= 20  value="<?php echo($stu_name);?>" ></p>
	
	<p><font color=#003399>PIN: </font><input type="number" min = "1" max = "10000" name="pin" id="pin_id" size=3 required value=<?php echo($pin);?> ></p>
	<p><font color=#003399>Instructor ID: </font><input type="number"  min = 1 name="iid" id="iid" required size=5 value=<?php echo($iid.' ');?> >
	<font color=#003399 >  &nbsp; &nbsp; &nbsp;  or if you don't know: <a href="getiid.php"><b>Click Here</b></a></font></p>
<!--	<p><font color=#003399>script_flag: </font><input type="number" name="s_flag" id="script_flag" size=3 value=<?php echo($sc_flag);?> ></p>  -->
	<p><font color=#003399>Problem Number: </font><input type="number" name="problem_id" id="prob_id" required size=3 value=<?php echo($problem_id);?> ></p>
<!--		<p><font color=#003399>Assignment Number: </font><input type="number" name="assign_num" id="assign_num"  size=3 value=<?php // echo($Assign_num);?> ></p>  Could put the assignment in as an option if they don't know the problem num or if the instructor has multiple assignments for the same problem--> 
	<p><input type = "submit" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	</form>

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
	
		sessionStorage.setItem('dex',dex);
		sessionStorage.setItem('problem_id',problem);
		sessionStorage.setItem('stu_name',s_name);
		sessionStorage.setItem('pin',pin);
		sessionStorage.setItem('iid',iid);
		
		
	// this was the start of me doing the whole thing in JS and JQ - did it the other way - we will see	
		/*  $("form").submit(function(e){
			e.preventDefault();
		}); */
		
	//	$.post( "QRcontroller.php", { progress: "1", dex: dex } );
	//	 window.location.href = "QRcontroller.php";
	
	
	var file = "QRcontroller.php";
	 $.redirectPost(file, { progress: "1", dex: dex, problem_id: problem, stu_name: s_name, pin: pin, iid: iid });
	
	
		
</script>

</body>
</html>



