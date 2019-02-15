<?php
require_once "pdo.php";
session_start();

// Guardian: Make sure that problem_id is present
if ( ! isset($_GET['problem_id']) or ! isset($_GET['users_id']) ) {
  $_SESSION['error'] = "Missing problem_id";
  header('Location: QRPRepo.php');
  return;
}
	
    $sql = "SELECT * FROM Problem WHERE problem_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_GET['problem_id']));
	$problem_data = $stmt -> fetch();
	
	 $sql = "SELECT * FROM Users WHERE users_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_GET['users_id']));
	$Users_data = $stmt -> fetch();
	
	// check to see if this is a new problem and they want the start over file issued
	if ($data['status']=='num issued'){
		$_SESSION['error'] = 'The status of this problem is num issued and cannot be activated';
	 	header( 'Location: QRRepo.php' ) ;
		return;
	}
	
	if ($data['status']=='Active'){
	// put code in here to confirm deactivation and then change status to inactive
		header( 'Location: QRRepo.php' ) ;
		return;
	}
	
	// here we will check to see if all of the information is filled out if it is then 
	// write the information to the assignment table and return to the QRrepo

$instr_last = $iid = $assign_t_created = $assing_num = "";
$prob_num = $pp_flag1 = $pp_flag2 = $pp_flag3 =$pp_flag4 = $reflect_flag = $explore_flag=  "";
$university = $connect_flag = $society_flag = $postp_flag1 =$postp_flag2 = $postp_flag3 =  "";


if(isset($_POST['Assig_num'])){
	



  // Prepare an insert statement
        $sql = "INSERT INTO Assign (instr_last, iid, university, assign_t_created, assign_num, prob_num, pp_flag1, pp_flag2,pp_flag3, pp_flag4,reflect_flag,explore_flag,connect_flag,society_flag,postp_flag1,postp_flag2,postp_flag3)
		VALUES (:instr_last, :iid,:university, :assign_t_created, :assign_num,:prob_num, :pp_flag1, :pp_flag2,:pp_flag3, :pp_flag4,:reflect_flag, :explore_flag,:connect_flag, :society_flag,:postp_flag1, :postp_flag2,:postp_flag3)";
         
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':instr_last', $instr_last, PDO::PARAM_STR);
            $stmt->bindParam(':iid', $iid, PDO::PARAM_STR);
            $stmt->bindParam(':university', $university, PDO::PARAM_STR);
            $stmt->bindParam(':assign_t_created', $assign_t_created, PDO::PARAM_STR);
		    $stmt->bindParam(':assign_num', $assign_num, PDO::PARAM_STR);
		    $stmt->bindParam(':prob_num', $prob_num, PDO::PARAM_STR);
			$stmt->bindParam(':pp_flag1', $pp_flag1, PDO::PARAM_STR);
			$stmt->bindParam(':pp_flag2', $pp_flag2, PDO::PARAM_STR);
			$stmt->bindParam(':pp_flag3', $pp_flag3, PDO::PARAM_STR);
			$stmt->bindParam(':pp_flag4', $pp_flag4, PDO::PARAM_STR);
			$stmt->bindParam(':postp_flag1', $postp_flag1, PDO::PARAM_STR);
			$stmt->bindParam(':postp_flag2', $postp_flag2, PDO::PARAM_STR);
			$stmt->bindParam(':postp_flag3', $postp_flag3, PDO::PARAM_STR);
			$stmt->bindParam(':connect_flag', $connect_flag, PDO::PARAM_STR);
			$stmt->bindParam(':explore_flag', $explore_flag, PDO::PARAM_STR);
			$stmt->bindParam(':reflect_flag', $reflect_flag, PDO::PARAM_STR);
			$stmt->bindParam(':society_flag', $society_flag, PDO::PARAM_STR);
			
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
		   
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: QRPRepo.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
  // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);


	

	
?>	
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRProblems</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
</head>

<body>
<header>
<h2>Activate Problem - Please select the options that you want to assign with this problem</h2>
</header>	
	 <div class="wrapper">
       
        <form  method="post">
		
			<input type= "text" Name="Assig_num" size="1" required> Assignment Number <br>
           <?php 
				if(! empty(trim($problem_data['preprob_3']))){
					echo ('&nbsp;&nbsp;<input type="checkbox" name="Prelim_MC" value="Prelim_MC_q"> Preliminary Multiple Choice Question <br>');
				}
			
				if(! empty(trim($problem_data['preprob_4']))){
					echo ('&nbsp;&nbsp; <input type="checkbox" name="Mics" value="Prelim_misc"> Additional Preliminary Activities <br>');
				}
			?>
			
			
			<p><input type="checkbox" name="guess" value="Prelim_guess"> Preliminary Estimates </p>
			<p><input type="checkbox" name="q_on_q" value="Prelim_q_on_q"> Questions about the Question </p>
			 Reflections:<br>
			
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="reflect" value="reflection_reflect"> Reflect  <br>
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="explore" value="reflection_explore"> Explore  <br>
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="connect" value="reflection_connect"> Connect  <br>
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="society" value="reflection_society"> Society  <br>
			<p><input type = "submit"></p>
			
			
			
        </form>
    </div>    
 
<br>
<a href="QRPRepo.php">Finished or Cancel</a>
</body>
</html>
