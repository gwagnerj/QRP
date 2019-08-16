<?php
	require_once "pdo.php";
	session_start();
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
			/* 
			Was setting this up to do more php input validation - but have put it off
			$PIN_LLimit = 1;
			$PIN_ULimit = 200;
			$prob_LLimit = 1;
			$prob_ULimit = 100000;
			$PIN_Check = array('options'=>array('min_range'=>$PIN_LLimit,'max_range'=>$PIN_ULimit,));
			$prob_Check = array('options'=>array('min_range'=>$prob_LLimit,'max_range'=>$prob_ULimit,)); */

	// if Get is set then it is coming from a back button of a problem
	
	if(isset($_GET['stu_name'])){
		
		$stu_name = htmlentities($_GET['stu_name']);
	} 
	
	
	if(isset($_GET['problem_id'])){
		
		$problem_id = htmlentities($_GET['problem_id']);
	} 
	if(isset($_GET['pin'])){
		$pin = htmlentities($_GET['pin']);
	}
	if(isset($_GET['iid'])){
		$iid = htmlentities($_GET['iid']);
	}
	
	
	if(isset($_POST['stu_name'])){
		
		
		$stu_name = htmlentities($_POST['stu_name']);
		$_SESSION['stu_name']=$stu_name;
		
	} 

	

	if(isset($_POST['problem_id'])){
		
		$problem_id = htmlentities($_POST['problem_id']);
			$_SESSION['problem_id']=$problem_id;
			
	} else {
		$_SESSION['error']='The Problem Number is Required';
		
	}

	if(isset($_POST['pin'])){
		
		$pin = htmlentities($_POST['pin']);
		if ($pin>10000 or $pin<1){
				$_SESSION['error']='Your PIN should be between 1 and 10000.';	
			} else {
				$_SESSION['pin']=$pin;
				
				$dex = ($pin-1) % 199 + 2; // % is PHP mudulus function - changing the PIN to an index between 2 and 200
				$_SESSION['dex'] = $dex;
				
				
			}
		
	} else {
		$_SESSION['error']='Your PIN is Required';
	
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
								$sql3 = "SELECT * FROM Assign WHERE iid=$iid AND prob_num=$problem_id" ;
								$stmt3 = $pdo->query($sql3);
								if($stmt3->rowCount()){
									// go the controller
									$_SESSION['progress']=1;
								//	echo('can I put anythiung here?');
									
									header("Location: QRcontroller.php");
									die();
									
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
						
					$_SESSION['error']='The Instructor ID is Required';	
						
						
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
<h1>Quick Response Homework Bypass</h1>
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
	
	<p><font color=#003399>PIN: </font><input type="number" name="pin" id="pin_id" size=3 value=<?php echo($pin);?> ></p>
	<p><font color=#003399>Instructor ID: </font><input type="text" name="iid" id="iid" size=5 value=<?php echo($iid.' ');?> >
	<font color=#003399 >  &nbsp; &nbsp; &nbsp;  or if you don't know: <a href="getiid.php"><b>Click Here</b></a></font></p>
<!--	<p><font color=#003399>script_flag: </font><input type="number" name="s_flag" id="script_flag" size=3 value=<?php echo($sc_flag);?> ></p>  -->
	<p><font color=#003399>Problem Number: </font><input type="number" name="problem_id" id="prob_id" size=3 value=<?php echo($problem_id);?> ></p>
	<p><input type = "submit" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	</form>

	


</body>
</html>



