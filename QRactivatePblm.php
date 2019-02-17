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
	
	// initialize a bunch of variables
	$instr_last = $assign_t_created = $assign_num = $activate_flag = "";
	$pp_flag1 = $pp_flag2 = $pp_flag3 =$pp_flag4 = $reflect_flag = $explore_flag=  "";
	$assign_id = $connect_flag = $society_flag = $postp_flag1 =$postp_flag2 = $postp_flag3 =  "";
	
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
	} else {
		$activate_flag = 1;	
	}

// we dont have an entry and we are trying to activate - create a new entry 
if(isset($_POST['Activate']) && $Assign_data==false){
	$activate_flag = 1;

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
				 $_SESSION['sucess'] = 'the problem was activated';
				header( 'Location: QRPRepo.php' ) ;
				return; 
               
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
  // Close statement
        unset($stmt);
    }
	
	
	
	// We are tryiung to activate but already have an entry - just update the entry
   if(isset($_POST['Activate']) && $Assign_data !== false){ 
   
	
   
  // changing assignment number so need a new time 
   if($assign_num != $_POST['Assig_num'] ) {
	$assign_num = $_POST['Assig_num'];
	$assign_t_created = time();  
		
   }
   	$sql = "UPDATE Assign SET  assign_t_created = ':assign_t_created', assign_num = ':assign_num', pp_flag1 = ':pp_flag1', pp_flag2= ':pp_flag2',
			pp_flag3 = ':pp_flag3', pp_flag4 = ':pp_flag4', reflect_flag = ':reflect_flag', explore_flag = ':explore_flag', connect_flag = ':connect_flag',
			society_flag = ':society_flag', postp_flag1 = ':postp_flag1', postp_flag2 = ':postp_flag2', postp_flag3 = ':postp_flag3'
					WHERE assign_id =$assign_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
			':assign_t_created' => $assign_t_created,
			':assign_num' => $assign_num,
			':pp_flag1' => $pp_flag1,
			':pp_flag2' => $pp_flag2,
			':pp_flag3' => $pp_flag3,
			':pp_flag4' => $pp_flag4,
			':reflect_flag' => $reflect_flag,
			':explore_flag' => $explore_flag,
			':connect_flag' => $connect_flag,
			':society_flag' => $society_flag,			
			':postp_flag1' => $postp_flag1,
			':postp_flag2' => $postp_flag2,
			':postp_flag3' => $postp_flag3
			));
			 $_SESSION['sucess'] = 'the problem was edited and remains active';
			header( 'Location: QRPRepo.php' ) ;
			return; 
   }
   
  // we are trying to de-activate the problem 
   if(isset($_POST['Deactivate']) && $Assign_data !== false){
	 
			/* $sql = "DELETE FROM Problem WHERE problem_id = :zip";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(':zip' => $_POST['problem_id'])); */


	 
	 $sql = "DELETE FROM Assign WHERE assign_id = :zip";  
	   $stmt = $pdo -> prepare($sql);
	   $stmt -> execute(array(
		':zip' => $assign_id
	   ));
	 
	echo('the problem was deactivated'.$assign_id);
	 $_SESSION['sucess'] = 'the problem was deactivated';
	 	header( 'Location: QRPRepo.php' ) ;
		return; 
   }
   
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
</head>

<body>
<header>
<h2>Activate / Deactivate the Problem - Please select the options that you want to assign with this problem</h2>
</header>	
	 <div class="wrapper">
       
        <form  method="post">
			<?php
				if($activate_flag== 1){
							 echo('<h4><input type="checkbox" name="Activate"  > Activate - make available to students </h4>');
					
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
			
			
			<p><input type="checkbox" name="guess" <?php if($pp_flag1 =='1'){echo ('checked');  }?> > Preliminary Estimates </p>
			<p><input type="checkbox" name="q_on_q" <?php if($pp_flag2 =='1'){echo ('checked');  }?>> Questions about the Question </p>
			 Reflections:<br>
			
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="reflect" <?php if($reflect_flag ==1){echo ('checked');  }?> > Reflect  <br>
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="explore" <?php if($explore_flag ==1){echo ('checked');  }?>> Explore  <br>
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="connect" <?php if($connect_flag ==1){echo ('checked');  }?> > Connect  <br>
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="society" <?php if($reflect_flag ==1){echo ('checked');  }?> > Society  <br>
			<p><input type = "submit"></p>
			
			
			
        </form>
    </div>    
 
<br>
<a href="QRPRepo.php">Finished or Cancel</a>
</body>
</html>
