<?php
require_once "pdo.php";
session_start();

$pp1=$_SESSION['pp1'];
$pp2=$_SESSION['pp2'];
$pp3=$_SESSION['pp3'];
$pp4=$_SESSION['pp4'];




// Guardian: Make sure that problem_id is present

	
   /*  $sql = "SELECT * FROM Problem WHERE problem_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_GET['problem_id']));
	$problem_data = $stmt -> fetch();
	
	
	
	// check to see if this is a new problem and they want the start over file issued
	if ($problem_data['status']=='num issued'){
		$_SESSION['error'] = 'The status of this problem is num issued and cannot be activated';
	 	header( 'Location: QRRepo.php' ) ;
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
		$activate_flag = 0;
	} else {
		
		// initialize a bunch of variables if we do not have a file in assign
		$activate_flag = 1;	
		
		$instr_last =  $assign_num =  "";
		$pp_flag1 = $pp_flag2 = $pp_flag3 =$pp_flag4 = $reflect_flag = $explore_flag=  "";
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



// we dont have a file and we are trying to activate - create an new entry 
if(isset($_POST['Activate']) && $Assign_data==false){
	$activate_flag = 1;
			// Set parameters
           
		   $assign_num = htmlentities($_POST['Assig_num']);
			$instr_last = $Users_data['last'];
			$iid = $Users_data['users_id'];
			$assign_t_created = time();
			$university = $Users_data['university'];
			$prob_num = $_GET['problem_id'];
			if(isset($_POST['q_on_q'])){
				$pp_flag1 = 1;
			}
			if(isset($_POST['guess'])){
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
 
 // Prepare an insert statement
        $sql = "INSERT INTO Assign (instr_last, iid, university, assign_t_created, assign_num, prob_num, pp_flag1, pp_flag2,pp_flag3, pp_flag4,reflect_flag,explore_flag,connect_flag,society_flag,postp_flag1,postp_flag2,postp_flag3)
		VALUES (:instr_last, :iid,:university, :assign_t_created, :assign_num,:prob_num, :pp_flag1, :pp_flag2,:pp_flag3, :pp_flag4,:reflect_flag, :explore_flag,:connect_flag, :society_flag,:postp_flag1, :postp_flag2,:postp_flag3)";
         
       
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
				':society_flag' => $society_flag	
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
  /*  if($assign_num != $_POST['Assig_num'] ) {
	$assign_num = $_POST['Assig_num'];
	$assign_t_created = time();  	
   }
   
		if(isset($_POST['q_on_q'])){
			$pp_flag1 = 1;
		} else {
			$pp_flag1 = 0;
		}
		if(isset($_POST['guess'])){
			$pp_flag2 = 1;
		} else {
			$pp_flag2 = 0;
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
  // echo ('IM here');
 //  die();
   
   	$sql = "UPDATE Assign SET  assign_t_created = :assign_t_created, assign_num = :assign_num, pp_flag1 = :pp_flag1, pp_flag2= :pp_flag2,
			pp_flag3 = :pp_flag3, pp_flag4 = :pp_flag4, reflect_flag = :reflect_flag, explore_flag = :explore_flag, connect_flag = :connect_flag,
			society_flag = :society_flag, postp_flag1 = :postp_flag1, postp_flag2 = :postp_flag2, postp_flag3 = :postp_flag3
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
			'postp_flag1' => $postp_flag1,
			'postp_flag2' => $postp_flag2,
			'postp_flag3' => $postp_flag3
			));
			 $_SESSION['sucess'] = 'the problem was edited and remains active';
			header( 'Location: QRPRepo.php' ) ;
			return; 
   }
   
  */
   
    // Close connection
  //  unset($pdo);
 

	

	
?>	
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRProblems</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
<header>
<h2>There are Preliminaries assigned for this problem.</h2>
<h3>Please select one.</h3>
</header>	
	 <div class="wrapper">
       
        <form  method="post">
			

			
			<?php 
			
			
				$pp1checked = ($pp1==2?'checked':'');
				$pp2checked = ($pp2==2?'checked':'');
				$pp3checked = ($pp3==2?'checked':'');
				$pp4checked = ($pp4==2?'checked':'');
				
				if($pp1!=0){
					echo('<p><input type="checkbox"  name="guess" id = "pp1box"'.$pp1checked.'</p>'.'<a href="QRGuesser.php">Preliminary Estimates</a>');	
				}
				if($pp2!=0){
					echo('<p><input type="checkbox" name="qonq" id = "pp2box"'.$pp2checked.'</p>'.'Planning Questions');	
				}
				
				if($pp3!=0){
					echo('<p><input type="checkbox" name="MC" id = "pp3box"'.$pp3checked.'</p>'.'Preliminary Multiple Choice');	
				}
				
				if($pp4!=0){
					echo('<p><input type="checkbox" name="Supp" id = "pp4box"'.$pp4checked.'</p>'.'Preliminary Supplemental');	
				}
				
				
			?>
			
			
			<script>			
				$(document).ready(function() {
					
					$('#pp1box').prop("disabled", true);
					$('#pp2box').prop("disabled", true);
					$('#pp3box').prop("disabled", true);
					$('#pp4box').prop("disabled", true);
					
					
				});
			
			</script>
        </form>
    </div>    
 
	
		
</body>
</html>
