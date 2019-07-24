<?php
require_once "pdo.php";
session_start();

if (isset($_SESSION['username'])) {
	$username=$_SESSION['username'];
} else {
	 $_SESSION['error'] = 'Session was lost -  please log in again';
	header('Location: QRPRepo.php');
	return;
}



// Guardian: Make sure that problem_id is present
if ( ! isset($_GET['problem_id']) or ! isset($_GET['users_id']) ) {
  $_SESSION['error'] = "Missing problem_id";
  header('Location: QRPRepo.php');
  return;
}
	// $choice = '';
    $sql = "SELECT * FROM Problem WHERE problem_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_GET['problem_id']));
	$problem_data = $stmt -> fetch();
	
	
	
	// check to see if this is a new problem and they want the start over file issued
	if ($problem_data['status']=='num issued'){
		$_SESSION['error'] = 'The status of this problem is num issued and cannot be activated';
	 	header( 'Location: QRPRepo.php' ) ;
		return;
	}


	 $sql = "SELECT * FROM Users WHERE users_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_GET['users_id']));
	$Users_data = $stmt -> fetch();
	


	$sql = "SELECT * FROM Assign WHERE iid = :zip AND prob_num = :probnum";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_GET['users_id'],':probnum' => $_GET['problem_id']));
	$Assign_data = $stmt -> fetch();
	
		
	$prob_num=$_GET['problem_id'];	
	$iid = $_GET['users_id'];
	$university = $Users_data['university'];
	$instr_last = $Users_data['last'];


// if the assignment data is not equal to false then we already have an entry make the values of the variables equal to the values in the db
	if($Assign_data != false) {
		$assign_id = $Assign_data['assign_id'];
		// echo($assign_id);
		$assign_num = $Assign_data['assign_num'];
		$assign_t_created = $Assign_data['assign_t_created'];
		$pp_flag1 = $Assign_data['pp_flag1'];
		$pp_flag2 = $Assign_data['pp_flag2'];
		$pp_flag3 = $Assign_data['pp_flag3'];
		$pp_flag4 = $Assign_data['pp_flag4'];
		$postp_flag1 = $Assign_data['postp_flag1'];
		$postp_flag1 = $Assign_data['postp_flag2'];
		$postp_flag1 = $Assign_data['postp_flag3'];
		
		$reflect_flag = $Assign_data['reflect_flag'];
		$explore_flag = $Assign_data['explore_flag'];
		$connect_flag = $Assign_data['connect_flag'];
		$society_flag = $Assign_data['society_flag'];
		$choice = $Assign_data['ref_choice'];
		$activate_flag = 0;
	} else {
		
		// initialize a bunch of variables if we do not have a file in assign
		$activate_flag = 1;	
		
		$instr_last =  $assign_num =  "";
		$pp_flag1 = $pp_flag2 = $pp_flag3 =$pp_flag4 = $reflect_flag = $explore_flag= $choice =  "";
		$assign_id = $connect_flag = $society_flag = $postp_flag1 =$postp_flag2 = $postp_flag3 =  "";
		$assign_t_created = time();
		
	}

// we have a file and are trying to deactivate it  
   if(isset($_POST['Deactivate']) && $Assign_data != false){
	 
	 $sql = "DELETE FROM Assign WHERE assign_id = :zip";  
	   $stmt = $pdo -> prepare($sql);
	   $stmt -> execute(array(
		':zip' => $assign_id
	   ));
	 
	//echo('the problem was deactivated'.$assign_id);
	 $_SESSION['sucess'] = 'the problem was deactivated';
	 	header( 'Location: QRPRepo.php' ) ;
		return; 
   }



// we dont have an entry and we are trying to activate - create an new entry 
if(isset($_POST['Activate']) && $Assign_data==false){
	$activate_flag = 1;
			// Set parameters
           
		   $assign_num = htmlentities($_POST['Assig_num']);
			$instr_last = $Users_data['last'];
			$iid = $Users_data['users_id'];
			$assign_t_created = time();
			$university = $Users_data['university'];
			$prob_num = $_GET['problem_id'];
			if(isset($_POST['guess'])){
				$pp_flag1 = 1;
			}
			if(isset($_POST['q_on_q'])){
				$pp_flag2 = 1;
			}
			if(isset($_POST['Prelim_MC'])){
				$pp_flag3 = 1;
			}
			if(isset($_POST['Mics'])){
				$pp_flag4 = 1;
			}
			if(isset($_POST['reflect'])){
				$reflect_flag = 1;
			}
			if(isset($_POST['explore'])){
				$explore_flag = 1;
			}
			if(isset($_POST['connect'])){
				$connect_flag = 1;
			}
			if(isset($_POST['society'])){
				$society_flag = 1;
			}
			if(isset($_POST['postprob1'])){
				$postp_flag1 = 1;
			}
			if(isset($_POST['postprob2'])){
				$postp_flag2 = 1;
			}
			if(isset($_POST['postprob3'])){
				$postp_flag3 = 1;
			}
			if(isset($_POST['exp_date'])){
			$exp_date=$_POST['exp_date'];
		} 
 
 // Prepare an insert statement
        $sql = "INSERT INTO Assign (instr_last, iid, university, assign_t_created, assign_num, prob_num, pp_flag1, pp_flag2,pp_flag3, pp_flag4,reflect_flag,explore_flag,connect_flag,society_flag,postp_flag1,postp_flag2,postp_flag3,exp_date)
		VALUES (:instr_last, :iid,:university, :assign_t_created, :assign_num,:prob_num, :pp_flag1, :pp_flag2,:pp_flag3, :pp_flag4,:reflect_flag, :explore_flag,:connect_flag, :society_flag,:postp_flag1, :postp_flag2,:postp_flag3,:exp_date)";
         
       
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':instr_last' => $instr_last,
				':iid' => $iid,
				':university' => $university,				
				':assign_t_created' => $assign_t_created,
				':assign_num' => $assign_num,
				':prob_num' => $prob_num,
				':pp_flag1' => $pp_flag1,
				':pp_flag2' => $pp_flag2,
				':pp_flag3' => $pp_flag3,
				':pp_flag4' => $pp_flag4,
				':postp_flag1' => $postp_flag1,
				':postp_flag2' => $postp_flag2,
				':postp_flag3' => $postp_flag3,
				':reflect_flag' => $reflect_flag,
				':explore_flag' => $explore_flag,
				':connect_flag' => $connect_flag,
				':society_flag' => $society_flag,
				':exp_date' => $exp_date
				));

				header( 'Location: QRPRepo.php' ) ;
				return; 



	  
  // Close statement
   //     unset($stmt);
    }
	
	
	
	// We have a file and are trying to edit it- just update the entry
   if(isset($_POST['Submitted']) && $Assign_data !== false){ 
   
	/* echo ($_POST['Assig_num']);
	echo ('<br>');
	echo ($assign_num);
	echo ('<br>');
	echo ($pp_flag1);
	echo ('<br>');
	echo ($assign_t_created);
	echo ('<br>');
	echo ($prob_num);
	echo ('<br>');
	echo ($assign_id);
	echo ('<br>');
	die(); */
	
  // changing assignment number so need a new time 
   if($assign_num != $_POST['Assig_num'] ) {
	$assign_num = $_POST['Assig_num'];
	$assign_t_created = time();  	
   }
   
		if(isset($_POST['q_on_q'])){
			$pp_flag2 = 1;
		} else {
			$pp_flag2 = 0;
		}
		if(isset($_POST['guess'])){
			$pp_flag1 = 1;
		} else {
			$pp_flag1 = 0;
		}
		if(isset($_POST['Prelim_MC'])){
			$pp_flag3 = 1;
		} else {
			$pp_flag3 = 0;
		}
		if(isset($_POST['Mics'])){
			$pp_flag4 = 1;
		} else {
			$pp_flag4 = 0;
		}	
		if(isset($_POST['reflect'])){
			$reflect_flag = 1;
		} else {
			$reflect_flag = 0;
		}
		if(isset($_POST['explore'])){
			$explore_flag = 1;
		} else {
			$explore_flag = 0;
		}
		if(isset($_POST['connect'])){
			$connect_flag = 1;
		} else {
			$connect_flag = 0;
		}
		if(isset($_POST['society'])){
			$society_flag = 1;
		} else {
			$society_flag = 0;
		}
		if(isset($_POST['choice'])){
			$choice = $_POST['choice'];
		} else {
			$choice = 0;
		}
		if(isset($_POST['postprob1'])){
			$postp_flag1 = 1;
		} else {
			$postp_flag1 = 0;
		}
		if(isset($_POST['postprob2'])){
			$postp_flag2 = 1;
		} else {
			$postp_flag2 = 0;
		}
		if(isset($_POST['postprob3'])){
			$postp_flag3 = 1;
		} else {
			$postp_flag3 = 0;
		}
		
		if(isset($_POST['exp_date'])){
			$exp_date=$_POST['exp_date'];
		} 
		// check the grader_id1 

		
  // echo ('IM here');
 //  die();
   
   	$sql = "UPDATE Assign SET  assign_t_created = :assign_t_created, assign_num = :assign_num, pp_flag1 = :pp_flag1, pp_flag2= :pp_flag2,
			pp_flag3 = :pp_flag3, pp_flag4 = :pp_flag4, reflect_flag = :reflect_flag, explore_flag = :explore_flag, connect_flag = :connect_flag,
			society_flag = :society_flag, postp_flag1 = :postp_flag1, postp_flag2 = :postp_flag2, postp_flag3 = :postp_flag3, ref_choice = :choice, exp_date = :exp_date
					WHERE assign_id = :assign_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
			'assign_id' => $assign_id,
			'assign_t_created' => $assign_t_created,
			'assign_num' => $assign_num,
			'pp_flag1' => $pp_flag1,
			'pp_flag2' => $pp_flag2,
			'pp_flag3' => $pp_flag3,
			'pp_flag4' => $pp_flag4,
			'reflect_flag' => $reflect_flag,
			'explore_flag' => $explore_flag,
			'connect_flag' => $connect_flag,
			'society_flag' => $society_flag,
			'choice' => $choice,
			'postp_flag1' => $postp_flag1,
			'postp_flag2' => $postp_flag2,
			'postp_flag3' => $postp_flag3,
			'exp_date' => $_POST['exp_date'],
			));
			 $_SESSION['sucess'] = 'the problem was edited and remains active';
			header( 'Location: QRPRepo.php' ) ;
			return; 
   }
   
 
   
    // Close connection
  //  unset($pdo);


// set the suggested date

	

	
?>	
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRProblems</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
</head>

<body>
<header>
<h2>Activate / Deactivate - Please select the options that you want to assign with problem <?php echo ( $_GET['problem_id']); ?></h2>
</header>	
	 <div class="wrapper">
       
        <form  method="post">
			<?php
				if($activate_flag== 1){
							 echo('<h4><input type="checkbox" name="Activate" checked > Activate - make available to students </h4>');
					
				} else {
					
					echo('<h4><input type="checkbox" name="Deactivate" > Deactivate </h4>');
				}
			
			?>
			
				
				
							
			<input type= "text" Name="Assig_num" size="1" <?php if(strlen($assign_num) !== 0){echo ('value ='.$assign_num);  }?> required> Assignment Number <br>
			
           <?php 
				if(! empty(trim($problem_data['preprob_3']))){
					
					if($pp_flag3=='1'){
							echo ('&nbsp;&nbsp;<input type="checkbox" name="Prelim_MC" value="Prelim_MC_q" checked> Preliminary Multiple Choice Question <br>');
					} else {
					echo ('&nbsp;&nbsp;<input type="checkbox" name="Prelim_MC" value="Prelim_MC_q"> Preliminary Multiple Choice Question <br>');
					}
				}
				if(! empty(trim($problem_data['preprob_4']))){
					if($pp_flag2 == '1') {
						echo ('&nbsp;&nbsp; <input type="checkbox" name="Mics" value="Prelim_misc" checked> Additional Preliminary Activities <br>');	
					} else {
					echo ('&nbsp;&nbsp; <input type="checkbox" name="Mics" value="Prelim_misc"> Additional Preliminary Activities <br>');
					}
				}
				
			?>
				<p><font color=#003399>When Should This Activation Expire (max is 6 months from now) </font><input type="date" name="exp_date" value = "2019-05-13"  min="2019-05-13" max='2000-01-10' id="exp_date" ></p>
				
			
			<p><input type="checkbox" name="guess" <?php if($pp_flag1 =='1'){echo ('checked');  }?> > Preliminary Estimates </p>
			<p><input type="checkbox" name="q_on_q" <?php if($pp_flag2 =='1'){echo ('checked');  }?>> Planning Questions </p>
			 Reflections:<br>
			<!-- &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="choice" value = 0 <?php if($choice ==0){echo ('checked');  }?> > Specify  <br>  -->
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class = "reflection" name="reflect" <?php if($reflect_flag ==1){echo ('checked');  }?> > Reflect  <br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class = "reflection" name="explore" <?php if($explore_flag ==1){echo ('checked');  }?>> Explore  <br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class = "reflection" name="connect" <?php if($connect_flag ==1){echo ('checked');  }?> > Connect  <br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class = "reflection" name="society" <?php if($society_flag ==1){echo ('checked');  }?> > Society  <br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="choice" value = 1 <?php if($choice ==1){echo ('checked');  }?> > Any One  <br>
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="choice" value = 2 <?php if($choice ==2){echo ('checked');  }?> > Any Two  <br>
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="choice" value = 3 <?php if($choice ==3){echo ('checked');  }?> > Any Three  <br>
			
			<div id = "allow_grade">
				</br>
				&nbsp Who can see individual student results for this problem: </br>
				&nbsp &nbsp <input type="radio" name="allow_grade" value=0 checked> Only me <br>
				&nbsp &nbsp <input type="radio" name="allow_grade" value=1 id = "allow_edit1" > Allow myself and Users with the following IDs:
				<input type = "number" name = "grader_id1" id = "grader_id1" min = "0" max = "10000" value = "<?php if ($row['grader_id1'] !=null){echo $row['grader_id1'];} else{echo'';}?>">
				<input type = "number" name = "grader_id2" id = "grader_id2" min = "0" max = "10000" value = "<?php if ($row['grader_id2'] !=null){echo $row['grader_id2'];} else{echo'';}?>">
				<input type = "number" name = "grader_id3" id = "grader_id3" min = "0" max = "10000" value = "<?php if ($row['grader_id3'] !=null){echo $row['grader_id3'];} else{echo'';}?>">
				&nbsp; &nbsp; &nbsp;  for a listing of ID's: <a href="getiid.php" target = "_blank"><b>Click Here</b></a></font></br>
			</div>
			
			
			
			
			<input type="hidden" name="Submitted" value="name" />
			<p><input type = "submit"></p>
			
        </form>
    </div>    
 
	<?php
		if($activate_flag== 1){
				echo ('<p> &nbsp; </p><hr>');
				echo ('<p><a href="QRPRepo.php">Cancel</a></p>');
		}
		
		?>
		
	<script>
	
	
	
	
	$(document).ready( function () {
			$('input[type="radio"]').change(function() {
				if ($(this).is(':checked')){ //radio is now checked
					$(".reflection").prop('checked', false);
					
					// $('input[type="checkbox"]').prop('checked', false); //unchecks all checkboxes
				}
			});

			$('.reflection').change(function() {
			// $('input[type="checkbox"]').change(function() {
				if ($(this).is(':checked')){
					$('input[type="radio"]').prop('checked', false);
				}
			});
			
			
			// suggest a date as the end of the semester for the expiration date
		
		var m = 0;
		var d = new Date();   // current date
		var minDate = d.toISOString(true).slice(0,10);
		document.getElementById("exp_date").setAttribute("min", minDate);
		
		max_months = 6;
		var max_date = new Date();
		max_date.setMonth(max_date.getMonth() + max_months);
		//console.log("Date after " + max_months + " months:", maxDate);
		var maxDate = max_date.toISOString(true).slice(0,10);
		document.getElementById("exp_date").setAttribute("max", maxDate);
		
		var n = d.getMonth(); // current Month
		var y = d.getFullYear();
		if (n==11){
			m = 4;
			yr = y+1;
		} else if (n <=3) {
			m=4;
			yr = y;
		} else if (n >= 7) {
			m=11;
			yr = y;
		}	else {
			m=7;
			yr = y;
		}
		
		d.setFullYear(yr, m, 15);  // change d to the end of the semester
		var expDate = d.toISOString(true).slice(0,10);
		document.getElementById("exp_date").value = expDate;
	
	} );
	
	
</script>	
</body>
</html>
