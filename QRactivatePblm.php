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
} else {

	$prob_num=$_GET['problem_id'];	
	$iid = $_GET['users_id'];

}

	// $choice = '';
    $sql = "SELECT * FROM Problem WHERE problem_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_GET['problem_id']));
	$problem_data = $stmt -> fetch();

	
	// Check to see if this instructor has any currentclasses
	 $sql = "SELECT * FROM CurrentClass WHERE iid = :iid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':iid' => $iid));
	$current_class_data = $stmt -> fetch();
	if ($current_class_data == false){
		$_SESSION['error'] = 'There are no current classes for this Instructor - Please Add a Class that you are Teaching';
		 header('Location: Current_Class.php');
		return;
	} else {
		
		/* $currentclass_id = $current_class_data['currentclass_id'];	
		$sec_desig_1 = 	$current_class_data['sec_desig_1'];	
		$sec_desig_2 = 	$current_class_data['sec_desig_2'];	
		$sec_desig_3 = 	$current_class_data['sec_desig_3'];	
		$sec_desig_4 = 	$current_class_data['sec_desig_4'];
		$sec_desig_5 = 	$current_class_data['sec_desig_5'];	
		$sec_desig_6 = 	$current_class_data['sec_desig_6'];
		$class_exp_date = $current_class_data['exp_date'];
		 */
	}
	
	
	
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
	
		
	
	$university = $Users_data['university'];
	$instr_last = $Users_data['last'];




// if the assignment data is not equal to false then we already have an entry make the values of the variables equal to the values in the db
	if($Assign_data != false) {
		$assign_id = $Assign_data['assign_id'];
		// echo($assign_id);
		$assign_num = $Assign_data['assign_num'];
		$alias_num = $Assign_data['alias_num'];

		$assign_t_created = $Assign_data['assign_t_created'];
		$pp_flag1 = $Assign_data['pp_flag1'];
		$pp_flag2 = $Assign_data['pp_flag2'];
		$pp_flag3 = $Assign_data['pp_flag3'];
		$pp_flag4 = $Assign_data['pp_flag4'];
		$postp_flag1 = $Assign_data['postp_flag1'];
		$postp_flag1 = $Assign_data['postp_flag2'];
		$postp_flag1 = $Assign_data['postp_flag3'];
		$alias_num = $Assign_data['alias_num'];
		$reflect_flag = $Assign_data['reflect_flag'];
		$explore_flag = $Assign_data['explore_flag'];
		$connect_flag = $Assign_data['connect_flag'];
		$society_flag = $Assign_data['society_flag'];
		$choice = $Assign_data['ref_choice'];
		$grader_id1 = $Assign_data['grader_id1'];
		$grader_id2 = $Assign_data['grader_id2'];
		$grader_id3 = $Assign_data['grader_id3'];
		$activate_flag = 0;
	} else {
		
		// initialize a bunch of variables if we do not have a file in assign
		$activate_flag = 1;	
		
		$instr_last =  $assign_num =  "";
		$pp_flag1 = $pp_flag2 = $pp_flag3 =$pp_flag4 = $reflect_flag = $explore_flag= $choice =  "";
		$assign_id = $alias_num = $connect_flag = $society_flag = $postp_flag1 =$postp_flag2 = $postp_flag3 =  "";
		$grader_id1 = $grader_id2 = $grader_id3 = "";
		$currentclass_id = '';
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
	
	$currentclass_id =  htmlentities($_POST['currentclass_id']);
	
		if(isset($_POST['exp_date'])){
			$exp_date=$_POST['exp_date'];
			} 
			
		 $sql = "SELECT exp_date FROM CurrentClass WHERE currentclass_id = :currentclass_id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(':currentclass_id' => $currentclass_id));
		$class_exp_data = $stmt -> fetch();
		$class_exp_date = $class_exp_data['exp_date'];

	if($exp_date > $class_exp_date){
	$_SESSION['error']	= 'Expiration date of Assignment cannot exceed the expiration date on the class';
		
		header( 'Location: QRPRepo.php' ) ;
		return; 
	}


		// Set parameters
           
		   $assign_num = htmlentities($_POST['assign_num']);
		     $alias_num = htmlentities($_POST['alias_num']);
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
			
				if(isset($_POST['grader_id1'])){
			$grader_id1=$_POST['grader_id1'];
			} 
			if(isset($_POST['grader_id2'])){
				$grader_id2=$_POST['grader_id2'];
			} 
			if(isset($_POST['grader_id3'])){
				$grader_id3=$_POST['grader_id3'];
			} 
		// check to make sure the problem number for that assignment and class is not already in the system
		
		$sql = "SELECT assign_id FROM Assign WHERE currentclass_id = :currentclass_id AND alias_num = :alias_num AND assign_num = :assign_num";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':currentclass_id' => $currentclass_id,
			':alias_num' => $alias_num,
			':assign_num' => $assign_num
		));
		$check = $stmt -> fetch();
		if ($check != false){
			$_SESSION['error']	= 'Duplication error - problems must have distinct problem numbers for an assignment in a class';
			header( 'Location: QRPRepo.php' ) ;
			return; 
		}
		
 
 // Prepare an insert statement
        $sql = "INSERT INTO Assign (instr_last, iid, university,  assign_num, prob_num, pp_flag1, pp_flag2,pp_flag3, pp_flag4,reflect_flag,explore_flag,
		connect_flag,society_flag,postp_flag1,postp_flag2,postp_flag3,exp_date,grader_id1,grader_id2,grader_id3,alias_num,
		currentclass_id,sec_desig_1,sec_desig_2,sec_desig_3,sec_desig_4,sec_desig_5,sec_desig_6)
		VALUES (:instr_last, :iid,:university,  :assign_num,:prob_num, :pp_flag1, :pp_flag2,:pp_flag3, :pp_flag4,:reflect_flag, :explore_flag,
		:connect_flag, :society_flag,:postp_flag1, :postp_flag2,:postp_flag3,:exp_date,:grader_id1,:grader_id2,:grader_id3,:alias_num,
		:currentclass_id,:sec_desig_1,:sec_desig_2,:sec_desig_3,:sec_desig_4,:sec_desig_5,:sec_desig_6)";
         
       
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':instr_last' => $instr_last,
				':iid' => $iid,
				':university' => $university,				
				':assign_num' => $assign_num,
				':alias_num' => $alias_num,
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
				':exp_date' => $exp_date,
				':grader_id1' => $grader_id1,
				':grader_id2' => $grader_id2,
				':grader_id3' => $grader_id3,
				':currentclass_id' => $currentclass_id,
				':sec_desig_1' => $sec_desig_1,
				':sec_desig_2' => $sec_desig_2,
				':sec_desig_3' => $sec_desig_3,
				':sec_desig_4' => $sec_desig_4,
				':sec_desig_5' => $sec_desig_5,
				':sec_desig_6' => $sec_desig_6
				));

				header( 'Location: QRPRepo.php' ) ;
				return; 
	
	}
	// We have a file and are trying to edit it- just update the entry
   if(isset($_POST['Submitted']) && $Assign_data !== false){ 
   
	
  // changing assignment number so need a new time 
   if($assign_num != $_POST['Assig_num'] ) {
	$assign_num = $_POST['Assig_num'];
	
   }
     if($alias_num != $_POST['alias_num'] ) {
	$alias_num = $_POST['alias_num'];
	
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
		if(isset($_POST['grader_id1'])){
			$grader_id1=$_POST['grader_id1'];
		} 
		if(isset($_POST['grader_id2'])){
			$grader_id2=$_POST['grader_id2'];
		} 
		if(isset($_POST['grader_id3'])){
			$grader_id3=$_POST['grader_id3'];
		} 
		
 
   
   	$sql = "UPDATE Assign SET  assign_t_created = :assign_t_created, assign_num = :assign_num, pp_flag1 = :pp_flag1, pp_flag2= :pp_flag2,
			pp_flag3 = :pp_flag3, pp_flag4 = :pp_flag4, reflect_flag = :reflect_flag, explore_flag = :explore_flag, connect_flag = :connect_flag,
			society_flag = :society_flag, postp_flag1 = :postp_flag1, postp_flag2 = :postp_flag2, postp_flag3 = :postp_flag3, ref_choice = :choice, exp_date = :exp_date,
			grader_id1 = :grader_id1, grader_id2 = :grader_id2, grader_id3 = :grader_id3, alias_num = :alias_num
					WHERE assign_id = :assign_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
			':assign_id' => $assign_id,
			
			':assign_t_created' => $assign_t_created,
			':assign_num' => $assign_num,
			':alias_num' => $alias_num,
			':pp_flag1' => $pp_flag1,
			':pp_flag2' => $pp_flag2,
			':pp_flag3' => $pp_flag3,
			':pp_flag4' => $pp_flag4,
			':reflect_flag' => $reflect_flag,
			':explore_flag' => $explore_flag,
			':connect_flag' => $connect_flag,
			':society_flag' => $society_flag,
			':choice' => $choice,
			':postp_flag1' => $postp_flag1,
			':postp_flag2' => $postp_flag2,
			':postp_flag3' => $postp_flag3,
			':exp_date' => $_POST['exp_date'],
			':grader_id1' => $grader_id1,
			':grader_id2' => $grader_id2,
			':grader_id3' => $grader_id3
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
				
				
				
				<div id ="current_class_dd">	
				Course: </br>
				<select name = "currentclass_id" id = "currentclass_id">
				<option value = "" selected disabled hidden >  Select Class  </option> 
				<?php
					$sql = 'SELECT * FROM `CurrentClass` WHERE `iid` = :iid';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(':iid' => $iid));
					while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) 
						{ ?>
						<option value="<?php echo $row['currentclass_id']; ?>" ><?php echo $row['name']; ?> </option>
						<?php
 							}
						?>
				</select>
				</div>
				</br>	
				
				<div id ="section_check">	

				</div>
				</br>		
			<div id = "active_assign">
			
			</div>
	
			</br> <input type= "text" Name="assign_num" id = "assign_num" size="1" <?php if(strlen($assign_num) !== 0){echo ('value ='.$assign_num);  }?> required> Assignment Number <br> </br>
			</br>
			<div id = "active_alias">
			
			</div>
			</br> <input type= "text" Name="alias_num" id = "alias_num" size="1" <?php if(strlen($alias_num) !== 0){echo ('value ='.$alias_num);  }?> required> Problem Number Within Assignment <br>

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
				<p><font >When Should This Activation Expire (max is 6 months from now) </font><input type="date" name="exp_date" value = "2019-05-13"  min="2019-05-13" max='2000-01-10' id="exp_date" ></p>
				
			
			<p><input type="checkbox" name="guess" <?php if($pp_flag1 =='1'){echo ('checked');  }?> > Preliminary Estimates </p>
			<p><input type="checkbox" name="q_on_q" <?php if($pp_flag2 =='1'){echo ('checked');  }?>> Planning Questions </p>
			 Reflections:<br>
			<!-- &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="choice" value = 0 <?php if($choice ==0){echo ('checked');  }?> > Specify  <br>  -->
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class = "reflection" name="reflect" <?php if($reflect_flag ==1){echo ('checked');  }?> > Reflect  <br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class = "reflection" name="explore" <?php if($explore_flag ==1){echo ('checked');  }?>> Explore  <br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class = "reflection" name="connect" <?php if($connect_flag ==1){echo ('checked');  }?> > Connect  <br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class = "reflection" name="society" <?php if($society_flag ==1){echo ('checked');  }?> > Society  <br>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="choice" class = "choice_class" value = 1 <?php if($choice ==1){echo ('checked');  }?> > Any One  <br>
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="choice" class = "choice_class" value = 2 <?php if($choice ==2){echo ('checked');  }?> > Any Two  <br>
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="choice" class = "choice_class" value = 3 <?php if($choice ==3){echo ('checked');  }?> > Any Three  <br>
			
			<div id = "allow_grade">
				</br>
				&nbsp Who can see individual student results for this problem: </br>
				&nbsp &nbsp <input type="radio" name="allow_grade" class = "allow_grade_class" value=0 checked> Only me <br>
				&nbsp &nbsp <input type="radio" name="allow_grade" class = "allow_grade_class" value=1 id = "allow_grade" > Allow myself and Users with the following IDs:
				<input type = "number" name = "grader_id1" id = "grader_id1" min = "0" max = "10000" value = "<?php if ($Assign_data['grader_id1'] !=null){echo $Assign_data['grader_id1'];} else{echo'';}?>">
				<input type = "number" name = "grader_id2" id = "grader_id2" min = "0" max = "10000" value = "<?php if ($Assign_data['grader_id2'] !=null){echo $Assign_data['grader_id2'];} else{echo'';}?>">
				<input type = "number" name = "grader_id3" id = "grader_id3" min = "0" max = "10000" value = "<?php if ($Assign_data['grader_id3'] !=null){echo $Assign_data['grader_id3'];} else{echo'';}?>">
				&nbsp; &nbsp; &nbsp;  for a listing of ID's: <a href="getiid.php" target = "_blank"><b>Click Here</b></a></font></br>
			</div>
			
			
			
			
			<input type="hidden" name="Submitted" value="name" />
			<p><input type = "submit"></p>
        </form>
		
		<a href="QRPRepo.php">Cancel - go back to Repository</a>
    </div>    
 
	<?php
		if($activate_flag== 1){
				echo ('<p> &nbsp; </p><hr>');
				echo ('<p><a href="QRPRepo.php">Cancel</a></p>');
		}
		
		?>
		
	<script>
	
	// still need to take care of sections and put them in the database 
	
	
	$(document).ready( function () {
		var sec_tot = 0;
		var currentclass_name = '';
		
			$("#currentclass_id").change(function(){
		var	 currentclass_id = $("#currentclass_id").val();
		//	console.log ('currentclass_id: '+currentclass_id);
			
			$.ajax({
					url: 'getsections.php',
					method: 'post',
						data: {currentclass_id:currentclass_id}
				//	data: 'currentclass_id=' + currentclass_id
				}).done(function(section){
					// console.log(section);
					var L = section.indexOf("]");
					//console.log("L: "+L);
					
					var section = section.substring(1, L);
					// console.log(section);
					section = JSON.parse(section);
					 console.log(section.sec_desig_1);
					 	 $('#section_check').empty();
						
				
					var i;
					for (i = 1; i < 6; i++) { 
					 
						var sec = "section.sec_desig_"+i;
					//	console.log (eval(sec));
						
						 if (eval(sec).length>1){
							// console.log ("WTH"+section.sec_desig_1);
							$('#section_check').append('<input class = "sel_checkbox" name="'+eval(sec)+'" id = "'+eval(sec)+'" type="checkbox" checked value="'+sec+'"/> '+eval(sec)+'<br/>') ;
							sec_tot = sec_tot+1;
						 }
					}			
					//console.log ("sec_tot: "+sec_tot);
				})
			
			});
			
			$("#assign_num").click(function() {
				
			// console.log ("clicked assignment");
			var num_checked_sec = $('.sel_checkbox:checked').length;
			console.log ("num_checked_sec: "+num_checked_sec);
			console.log ("sec_tot: "+sec_tot);
			if (num_checked_sec == 0){ // we have no sections then just get the active problems from the Assig Table 
				// need to give it 	
				var currentclass_id = $("#currentclass_id").val();
				 console.log("currentclass_id: "+currentclass_id);
					$.ajax({
						url: 'getactiveassignments.php',
						method: 'post',
					
					data: {currentclass_id:currentclass_id}
					}).done(function(activeass){
						console.log("activeass: "+activeass);
					 console.log(activeass);
					 activeass = JSON.parse(activeass);
					 	 $('#active_assign').empty();
						var i = 0;
						n = activeass.length;
						console.log("n: "+n);
						$('#active_assign').append("&nbsp;&nbsp;&nbsp;&nbsp;Current assignments in system for this course: ") ;	
						for (i=0;i<n;i++){
							console.log(activeass[i]);	
							$('#active_assign').append(activeass[i]) ;	
							
							if (i != n-1){
								$('#active_assign').append(', &nbsp;') ;	
							}
						}
						
					});	

					
					$("#alias_num").click(function() {
					var currentclass_id = $("#currentclass_id").val();
					var assign_num = $("#assign_num").val();
					 console.log("currentclass_id: "+currentclass_id);
					  console.log("assign_num: "+assign_num);
					$.ajax({
						url: 'getactivealias.php',
						method: 'post',
						data:{currentclass_id:currentclass_id, assign_num:assign_num }
					}).done(function(activealias){
						console.log("activealias: "+activealias);
					
					 activealias = JSON.parse(activealias);
					 	 $('#active_alias').empty();
						var i = 0;
						n = activealias.length;
						console.log("n_alias: "+n);
						$('#active_alias').append("&nbsp;&nbsp;&nbsp;&nbsp;Current problems in system for this assignment: ") ;	
						for (i=0;i<n;i++){
							console.log(activealias[i]);	
							$('#active_alias').append(activealias[i]) ;	
							
							if (i != n-1){
								$('#active_alias').append(', &nbsp;') ;	
							}
						}
						 
					});					
					
				});			
					
			}
				
				
				
			});
			 
			
			$('input[name="choice"]').change(function() {
				if ($(this).is(':checked')){ //radio is now checked
					$(".reflection").prop('checked', false);
					
					// $('input[type="checkbox"]').prop('checked', false); //unchecks all checkboxes
				}
			});

			$('.reflection').change(function() {
			// $('input[type="checkbox"]').change(function() {
				if ($(this).is(':checked')){
					$('input[name="choice"]').prop('checked', false);
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
		
		var n = d.getMonth()+1; // current Month
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
