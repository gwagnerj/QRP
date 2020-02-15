<?php
require_once "pdo.php";
session_start();

// THis is called by getGame.php to write the game variables to the game table (what is the name of rectangle varaible...) for a particular problem_id, iid and dex and return a game_id

	if (isset($_POST['problem_id'])){
		$problem_id = $_POST['problem_id'];
	} else {
		$_SESSION['error'] = 'problem_id not set';
		header("Location: getGame.php");
		return;
	}

	if (isset($_POST['iid'])){
		$iid = $_POST['iid'];
	} else {
		$_SESSION['error'] = 'iid not set';
		header("Location: getGame.php");
		return;
	}

	if (isset($_POST['dex'])){
		$dex = $_POST['dex'];
	} else {
		$_SESSION['error'] = 'dex not set';
		header("Location: getGame.php");
		return;
	}



	if (isset($_POST['rect_vnum'])){
		$rect_vnum = $_POST['rect_vnum'];
	} else {
		$rect_vnum = 'null';
	}

	if (isset($_POST['oval_vnum'])){
		$oval_vnum = $_POST['oval_vnum'];
	} else {
		$oval_vnum = 'null';
	}

	if (isset($_POST['trap_vnum'])){
		$trap_vnum = $_POST['trap_vnum'];
	} else {
		$trap_vnum = 'null';
	}

	if (isset($_POST['hexa_vnum'])){
		$hexa_vnum = $_POST['hexa_vnum'];
	} else {
		$hexa_vnum = 'null';
	}
	
	
	
	if (isset($_POST['rect'])){
		$rect = $_POST['rect'];
	} else {
		$rect = 'null';
	}

	if (isset($_POST['oval'])){
		$oval = $_POST['oval'];
	} else {
		$oval = 'null';
	}

	if (isset($_POST['trap'])){
		$trap = $_POST['trap'];
	} else {
		$trap = 'null';
	}

	if (isset($_POST['hexa'])){
		$hexa = $_POST['hexa'];
	} else {
		$hexa = 'null';
	}
	
	if (isset($_POST['prep_time'])){
		$prep_time = $_POST['prep_time'];
	} else {
		$prep_time = 1;  // 1 minutes
	}
	
	if (isset($_POST['work_time'])){
		$work_time = $_POST['work_time'];
	} else {
		$work_time = 15;  // 15 minutes
	}
	
	if (isset($_POST['post_time'])){
		$post_time = $_POST['post_time'];
	} else {
		$post_time = 1;  // 1 minutes
	}
	
	
	
	if (isset($_POST['days_till_delete'])){
		$days_till_delete = $_POST['days_till_delete'];
	} else {
		$days_till_delete = 30;  // 30 days
	}


	if (isset($_POST['activate_flag'])){
		$activate_flag = $_POST['activate_flag'];
	} else {
		$_SESSION['error'] = 'activate_flag not set';
		header("Location: getGame.php");
		return;
	}



		
 /* 
	 $problem_id = 222;
	 $iid = 5;
	 $dex = 12;
	 $rect = "foo";
	 $oval = "bar";
	 $trap = "wt";
	 $hexa = "g";
	 $activate_flag = 1;
	 $days_till_delete = 15;
	 $work_time = 20;
	 $post_time = 1;
	 $prep_time = 2;
	  $rect_vnum = "v_1";
	 $oval_vnum = "v_2";
	 $trap_vnum = "v_3";
	 $hexa_vnum = "v_4";
	  */
	  
	  
	 // get the expiration date
	 $exp_date=Date('y:m:d', strtotime("+".$days_till_delete." days"));

	
 
 
 
	
	if($activate_flag==1){
		//  we need to write the info to the Game table unless it is already there then we should update it. 
		
		
		$sql = "SELECT * FROM Game WHERE iid=:iid AND problem_id=:problem_id AND dex = :dex" ;
		$stmt = $pdo->prepare($sql);
		$stmt -> execute(array(
		':iid'=>$iid,
		':dex'=>$dex,
		':problem_id' => $problem_id
		));
		$game_row =$stmt ->fetch();
		if ( $game_row === false ) {
			
			
			$sql = 'INSERT INTO `Game` (problem_id, iid, prep_time, work_time, post_time, exp_date, dex, rect, oval, trap,hexa,rect_vnum, oval_vnum, trap_vnum,hexa_vnum)	
						VALUES (:problem_id,  :iid, :prep_time, :work_time, :post_time, :exp_date, :dex, :rect, :oval, :trap,:hexa,:rect_vnum, :oval_vnum, :trap_vnum,:hexa_vnum)';
				$stmt = $pdo->prepare($sql);
				$stmt -> execute(array(
				':problem_id' => $problem_id,
				':iid' => $iid,
				':dex' => $dex,
				':prep_time' => $prep_time,
				':work_time' => $work_time,
				':post_time' => $post_time,
				':exp_date' => $exp_date,
				':rect' => $rect,
				':oval' => $oval,
				':trap' => $trap,
				':hexa' => $hexa,
				':rect_vnum' => $rect_vnum,
				':oval_vnum' => $oval_vnum,
				':trap_vnum' => $trap_vnum,
				':hexa_vnum' => $hexa_vnum,
				
				));
			
			// we need to create the entry	
				// stopped here delete me //temp
				
		}	else {
			
			
			
			
			$sql = "UPDATE Game SET rect = :rect,  oval = :oval, trap = :trap, hexa = :hexa, 
			 rect_vnum = :rect_vnum,  oval_vnum = :oval_vnum, trap_vnum = :trap_vnum, hexa_vnum = :hexa_vnum ,prep_time = :prep_time,
			 work_time = :work_time, post_time = :post_time, exp_date = :exp_date
			WHERE problem_id = :problem_id AND iid = :iid AND dex = :dex ";
				$stmt = $pdo->prepare($sql);
				$stmt -> execute(array(
				':problem_id' => $problem_id,
				':iid' => $iid,
				':dex' => $dex,
				':prep_time' => $prep_time,
				':work_time' => $work_time,
				':post_time' => $post_time,
				':exp_date' => $exp_date,
				':rect' => $rect,
				':oval' => $oval,
				':trap' => $trap,
				':hexa' => $hexa,
				':rect_vnum' => $rect_vnum,
				':oval_vnum' => $oval_vnum,
				':trap_vnum' => $trap_vnum,
				':hexa_vnum' => $hexa_vnum,
				));
				
			
		}
		
		$stmt = $pdo->prepare("SELECT `game_id` FROM `Game` where problem_id = :problem_id AND dex = :dex AND iid = :iid");
		 $stmt->execute(array(":problem_id" => $problem_id,":dex" => $dex, ":iid" => $iid ));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
		if ( $row === false ) {
			$_SESSION['error'] = 'could not read game_id from Game table';
	
		}	
		
		$game_id = $row['game_id'];
		$resp_arr = array('game_id' => $game_id, 'exp_date' => $exp_date);
	 echo json_encode($resp_arr);
	}
	
	
	
	
	









?>