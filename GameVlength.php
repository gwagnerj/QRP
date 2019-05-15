<?php
require_once "pdo.php";
session_start();

// THis is called by getGame.php to write length of the varibles to the Game table and to return the lenth of those variables to the getGame program.  If dex is -1 it will get the longest one int he data set whereas if dex has a value between 1 and 200 it will return that particular value
 	if (isset($_POST['problem_id'])){
		$problem_id = $_POST['problem_id'];
	} else {
		$_SESSION['error'] = 'problem_id not set';
		header("Location: getGame.php");
		return;
	}

	if (isset($_POST['game_id'])){
		$game_id = $_POST['game_id'];
	} else {
		$_SESSION['error'] = 'game_id not set';
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

	
 
 
	/* $problem_id = 293;  //temp
	 $dex=-1;
	 $rect_vnum = 'v_1';
	 $oval_vnum = 'v_2';
	 $trap_vnum = 'v_3';
	 $hexa_vnum = 'v_4'; */
	
	 
	if ($dex !=-1){
		
		$sql = "SELECT * FROM `Input` WHERE dex=:dex AND problem_id=:problem_id" ;
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(
			':dex'=>$dex,
			':problem_id' => $problem_id
			));
		$input_row =$stmt ->fetch();	
		
		$rect_val = $input_row[$rect_vnum];
		$oval_val = $input_row[$oval_vnum];
		$trap_val = $input_row[$trap_vnum];
		$hexa_val = $input_row[$hexa_vnum];
	}	else {
		
		$sql = "SELECT ".$rect_vnum." FROM `Input` WHERE  problem_id=:problem_id ORDER BY CHAR_LENGTH (".$rect_vnum.") desc" ;
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(
			':problem_id' => $problem_id
			));
		$input_row =$stmt ->fetch();
		$rect_val = $input_row[$rect_vnum];
		
		$sql = "SELECT ".$oval_vnum." FROM `Input` WHERE  problem_id=:problem_id ORDER BY CHAR_LENGTH (".$oval_vnum.") desc" ;
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(
			':problem_id' => $problem_id
			));
		$input_row =$stmt ->fetch();
		$oval_val = $input_row[$oval_vnum];
		
		$sql = "SELECT ".$trap_vnum." FROM `Input` WHERE  problem_id=:problem_id ORDER BY CHAR_LENGTH (".$trap_vnum.") desc" ;
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(
			':problem_id' => $problem_id
			));
		$input_row =$stmt ->fetch();
		$trap_val = $input_row[$trap_vnum];
		
		$sql = "SELECT ".$hexa_vnum." FROM `Input` WHERE  problem_id=:problem_id ORDER BY CHAR_LENGTH (".$hexa_vnum.") desc" ;
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(
			':problem_id' => $problem_id
			));
		$input_row =$stmt ->fetch();
		$hexa_val = $input_row[$hexa_vnum];
	}	
		
		
		// try this next bit to handle html codes as input to the table so it counts subscripts as just one character etc.
		$rect_val = html_entity_decode($rect_val );
		$oval_val = html_entity_decode($oval_val );
		$trap_val = html_entity_decode($trap_val );
		$hexa_val = html_entity_decode($hexa_val );
		
		
		
		$rect_length = strlen((string)$rect_val);
		$oval_length = strlen((string)$oval_val);
		$trap_length = strlen((string)$trap_val);
		$hexa_length = strlen((string)$hexa_val);
		
		
		
		
		// write the values to the `Game` table
		
			$sql = "UPDATE Game SET rect_length = :rect_length,  oval_length = :oval_length, trap_length = :trap_length, hexa_length = :hexa_length 
			WHERE game_id = :game_id ";
				$stmt = $pdo->prepare($sql);
				$stmt -> execute(array(
				':game_id' => $game_id,
				':rect_length' => $rect_length,
				':oval_length' => $oval_length,
				':trap_length' => $trap_length,
				':hexa_length' => $hexa_length,
				));
		
		
		// return them to the calling program
		
		
		
		$resp_arr = array('rect_length' => $rect_length, 'oval_length' => $oval_length, 'trap_length' => $trap_length, 'hexa_length' => $hexa_length, );
	 echo json_encode($resp_arr);
	
	die();
	
	
	
	









?>