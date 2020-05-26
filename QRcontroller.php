<?php
require_once "pdo.php";
session_start();

if (isset($_POST['activity_id'])){
	$activity_id = $_POST['activity_id'];
}elseif(isset($_GET['activity_id'])){
	$activity_id = $_GET['activity_id'];
} elseif(isset($_SESSION['activity_id'])){
	$activity_id = $_SESSION['activity_id'];
} else {
	
	$_SESSION['error'] = 'problem ID not set';
}




if (isset($_POST['problem_id'])){
	$problem_id = $_POST['problem_id'];
}elseif(isset($_GET['problem_id'])){
	$problem_id = $_GET['problem_id'];
} elseif(isset($_SESSION['problem_id'])){
	$problem_id = $_SESSION['problem_id'];
} else {
	
	$_SESSION['error'] = 'problem ID not set';
}

if (isset($_POST['iid'])){
	$iid = $_POST['iid'];
}  elseif(isset($_GET['iid'])){
	$iid = $_GET['iid'];
} elseif(isset($_SESSION['iid'])){
	$iid = $_SESSION['iid'];
} else {
	$_SESSION['error'] = 'instructor ID not set';
}
if (isset($_POST['dex'])){
	$dex = $_POST['dex'];
}  elseif(isset($_GET['dex'])){
	$dex = $_GET['dex'];
} elseif(isset($_SESSION['iid'])){
	$dex = $_SESSION['dex'];
} else {
	$_SESSION['error'] = 'dex not set';
}
if (isset($_POST['pin'])){
	$pin = $_POST['pin'];
}  elseif(isset($_GET['pin'])){
	$pin = $_GET['pin'];
} elseif(isset($_SESSION['iid'])){
	$pin = $_SESSION['pin'];
} else {
	$_SESSION['error'] = 'pin not set';
}
if (isset($_POST['assign_num'])){
	$assign_num = $_POST['assign_num'];
}  elseif(isset($_GET['assign_num'])){
	$assign_num = $_GET['assign_num'];
} elseif(isset($_SESSION['assign_num'])){
	$assign_num = $_SESSION['assign_num'];
} else {
	$_SESSION['error'] = 'assign_num not set';
}
if (isset($_POST['alias_num'])){
	$alias_num = $_POST['alias_num'];
}  elseif(isset($_GET['alias_num'])){
	$alias_num = $_GET['alias_num'];
} elseif(isset($_SESSION['alias_num'])){
	$alias_num = $_SESSION['alias_num'];
} else {
	
	$_SESSION['error'] = 'alias_num not set';
}
if (isset($_POST['cclass_id'])){
	$cclass_id = $_POST['cclass_id'];
}  elseif(isset($_GET['cclass_id'])){
	$cclass_id = $_GET['cclass_id'];
} elseif(isset($_SESSION['cclass_id'])){
	$cclass_id = $_SESSION['cclass_id'];
} else {
	
	$_SESSION['error'] = 'cclass_id not set';
}

if(isset($_POST['stu_name'])) {
	$stu_name = $_POST['stu_name'];
} else {
	$stu_name = '';
}

if (isset($_POST['progress'])) {
	$_SESSION['progress'] = $_POST['progress'];
}


if (!isset($_POST['progress']) && !isset($_SESSION['progress'])) {
	$_SESSION['error'] = 'error occured in controller - progress not set';
	header("Location: QRhomework.php");
	die();
	
} else {
	
	if($_SESSION['progress']==1){
		/*  is there an entry in the Activity table for this instructor, student PIN and problem number? 
		  if there is not create an entry  if these is then get students current progress in either case get the 
		reqirements for the problem and check against student progress to know where to go next
		
		 */
		// get the information from the Assign table
		
		
		$sql = "SELECT * FROM Assign WHERE iid=:iid AND prob_num=:prob_num" ;
		$stmt = $pdo->prepare($sql);
		$stmt -> execute(array(
		':iid'=>$iid,
		':prob_num' => abs($problem_id)
		));
		$assn_row =$stmt ->fetch();
		if ( $assn_row === false ) {
				$_SESSION['error'] = 'controller could not read assignment';
				header( 'Location: QRhomework.php' ) ; 
				die();
		}	else {
			// we got information from the assignment table Now Check if there is an entry in the activity table 
			// read assignment data and put what you need into Session variables
			$_SESSION['reflect_flag']=$assn_row['reflect_flag'];
			$_SESSION['explore_flag']=$assn_row['explore_flag'];
			$_SESSION['connect_flag']=$assn_row['connect_flag'];
			$_SESSION['society_flag']=$assn_row['society_flag'];
			$_SESSION['choice']=$assn_row['ref_choice'];
			
			
			$sql = "SELECT * FROM Activity WHERE iid=:iid AND problem_id=:problem_id AND pin =:pin" ;
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(
			':iid'=>$iid,
			':problem_id' =>abs($problem_id),
			':pin' =>$pin
			));
			$activity_row =$stmt ->fetch();
			if ( $activity_row === false ) {
			
			//  create and entry activity table
				$sql = 'INSERT INTO Activity (problem_id, pin,iid, dex, assign_id, instr_last, university,pp1,pp2,pp3,pp4,post_pblm1,post_pblm2,post_pblm3,score,help_coins_used,assist_coins_gained,stu_name,alias_num,currentclass_id)	
						VALUES (:problem_id, :pin, :iid, :dex, :assign_id, :instr_last,:university,:pp1,:pp2,:pp3,:pp4,:post_pblm1,:post_pblm2,:post_pblm3, :score,:help_coins_used, :assist_coins_gained, :stu_name, :alias_num, :cclass_id)';
				$stmt = $pdo->prepare($sql);
				$stmt -> execute(array(
				':problem_id' => $assn_row['prob_num'],
				':pin' => $pin,
				':iid' => $assn_row['iid'],
				':dex' => $dex,
				':assign_id' => $assn_row['assign_id'],
				':instr_last' => $assn_row['instr_last'],
				':university' => $assn_row['university'],
				':pp1' => $assn_row['pp_flag1'],
				':pp2' => $assn_row['pp_flag2'],
				':pp3' => $assn_row['pp_flag3'],
				':pp4' => $assn_row['pp_flag4'],
				':post_pblm1' => $assn_row['postp_flag1'],
				':post_pblm2' => $assn_row['postp_flag2'],
				':post_pblm3' => $assn_row['postp_flag3'],
				':score' => 0,
				':help_coins_used' => 0,
				':assist_coins_gained' => 0,
				':stu_name' => $stu_name,
				':alias_num' => $alias_num,
				':cclass_id' => $cclass_id,
				));
			
			$sql = "SELECT * FROM Activity WHERE iid=:iid AND problem_id=:problem_id AND pin =:pin" ;
				$stmt = $pdo->prepare($sql);
				$stmt -> execute(array(
				':iid'=>$iid,
				':problem_id' =>abs($problem_id),
				':pin' =>$pin
				));
				$activity_row =$stmt ->fetch();		
                $activity_id = $activity_row['activity_id'];
			
			}  
			
			// now decide what to do depending on what is in the progress
			$pp1 = $activity_row['pp1'];
			$pp2 = $activity_row['pp2'];
			$pp3 = $activity_row['pp3'];
			$pp4 = $activity_row['pp4'];
			$post_pblm1=$activity_row['post_pblm1'];
			$post_pblm1=$activity_row['post_pblm2'];
			$post_pblm1=$activity_row['post_pblm3'];
			
			$_SESSION['pp1'] = $pp1;
			$_SESSION['pp2'] = $pp2;
			$_SESSION['pp3'] = $pp3;
			$_SESSION['pp4'] = $pp4;
			$_SESSION['time_pp1']=$activity_row['time_pp1'];
			$_SESSION['time_pp2']=$activity_row['time_pp2'];
			$_SESSION['time_pp3']=$activity_row['time_pp3'];
			$_SESSION['time_pp4']=$activity_row['time_pp4'];	
			$_SESSION['activity_id'] = $activity_row['activity_id'];
				
				
				// so the logic is read from the assignment table and if say pre-problem 1 was assigned put it in the activity table
				// then read from the activity table if say proproblem 1 is not 1 then move on but if it is one we need to display the pre problem.  once preproblem 1 is done that will write a 2 to the activity table 
				// so if pre problem 1 is ero that should mean it was never assigned.  If preproblem 1 (pp1) is 1 that means it is assined and undone.  If pp1 is 2 that means it 
				// was assigned and completed.  QRguesser.php should set pp1 in the acitvity table to 2 where as QRplanning would set pp2
				
				if (($pp1 != 1 && $pp2 !=1 && $pp3 !=1 && $pp4 != 1) || $problem_id<0 || $pin == 0 ){
					
					// show them the actual numbered problem
						$_SESSION['problem_id'] = abs($problem_id);
						$problem_id = abs($problem_id);
				
					// echo('<a href="QRhomework.php?problem_id='.$problem_id.'&pin='.$pin.'&iid='.$iid.'&stu_name='.$stu_name.'"><b> Return to Main Screen</b></a>');
// ------------------------------------------send the display problem the activity_id ---------------------------------------------------------------------------------------------------------------						
						header("Location: QRdisplayPblm.php?activity_id=".$activity_id
						
						);
						die();
				}
				
			// this problem has pre-problem assigned
				
				header("Location: QRdisplayPre.php");
				die();
		}
	}
	
}


?>