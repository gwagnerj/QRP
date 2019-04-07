<?php
require_once "pdo.php";
session_start();

if(isset($_SESSION['stu_name'])) {
	$stu_name = $_SESSION['stu_name'];
} else {
	$stu_name = '';
}

if (!isset($_SESSION['progress'])) {
	
	$_SESSION['error'] = 'error occured in controller - progress not set';
	header("Location: QRhomework.php");
	return;
	
} else {
	
	if($_SESSION['progress']==1){
		/*  is there an entry in the Activity table for this instructor, student PIN and problem number? 
		  if there is not create and entry  if these is then get students current progress in either case get the 
		reqirements for the problem and check against student progress to know where to go next
		
		 */
		// get the information from the Assign table
		
		$sql = "SELECT * FROM Assign WHERE iid=:iid AND prob_num=:prob_num" ;
		$stmt = $pdo->prepare($sql);
		$stmt -> execute(array(
		':iid'=>$_SESSION['iid'],
		':prob_num' =>$_SESSION['problem_id']
		));
		$assn_row =$stmt ->fetch();
		if ( $assn_row === false ) {
				$_SESSION['error'] = 'controller could not read assignment';
				header( 'Location: QRhomework.php' ) ; 
				return;
		}	else {
			// we got information from the assignment table Now Check if there is an entry in the activity table 
			// read assignment data and put what you need into Session variables
			$_SESSION['reflect_flag']=$assn_row['reflect_flag'];
			$_SESSION['explore_flag']=$assn_row['explore_flag'];
			$_SESSION['connect_flag']=$assn_row['connect_flag'];
			$_SESSION['society_flag']=$assn_row['society_flag'];
			
			
			
			$sql = "SELECT * FROM Activity WHERE iid=:iid AND problem_id=:problem_id AND pin =:pin" ;
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(
			':iid'=>$_SESSION['iid'],
			':problem_id' =>$_SESSION['problem_id'],
			':pin' =>$_SESSION['pin']
			));
			$activity_row =$stmt ->fetch();
			if ( $activity_row === false ) {
			
			// put code in to create and entry activity table
				$sql = 'INSERT INTO Activity (problem_id, pin,iid, dex, assign_id, instr_last, university,pp1,pp2,pp3,pp4,post_pblm1,post_pblm2,post_pblm3,score,help_coins_used,assist_coins_gained,stu_name)	
						VALUES (:problem_id, :pin, :iid, :dex, :assign_id, :instr_last,:university,:pp1,:pp2,:pp3,:pp4,:post_pblm1,:post_pblm2,:post_pblm3, :score,:help_coins_used, :assist_coins_gained, :stu_name)';
				$stmt = $pdo->prepare($sql);
				$stmt -> execute(array(
				':problem_id' => $assn_row['prob_num'],
				':pin' => $_SESSION['pin'],
				':iid' => $assn_row['iid'],
				':dex' => $_SESSION['dex'],
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
				));
			
			$sql = "SELECT * FROM Activity WHERE iid=:iid AND problem_id=:problem_id AND pin =:pin" ;
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(
			':iid'=>$_SESSION['iid'],
			':problem_id' =>$_SESSION['problem_id'],
			':pin' =>$_SESSION['pin']
			));
			$activity_row =$stmt ->fetch();		
			
			
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
				
				
				
				
				
				
				
				if ($pp1 != 1 && $pp2 !=1 && $pp3 !=1 && $pp4 != 1 ){
					
					// show them the actual numbered problem
					
				
					
						header("Location: QRdisplayPblm.php");
						return;
					
					
				}
				
			// this problem has pre-problem assigned
				
				header("Location: QRdisplayPre.php");
				return;
				//echo ('RiGt here');
				
			
			
		}
		
		
	}
	
	
	
	
	
}








?>