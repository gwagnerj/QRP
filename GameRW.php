<?php
require_once "pdo.php";
session_start();


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
if (isset($_POST['activate_flag'])){
	$activate_flag = $_POST['activate_flag'];
} else {
	$_SESSION['error'] = 'activate_flag not set';
	header("Location: getGame.php");
	return;
}
	
 
	
	if($activate_flag==1){
		//  we need to write the info to the Game table unless it is already there then we should update it. 
		
		
		$sql = "SELECT * FROM Game WHERE iid=:iid AND problem_id=:problem_id" ;
		$stmt = $pdo->prepare($sql);
		$stmt -> execute(array(
		':iid'=>$iid,
		':problem_id' => $problem_id
		));
		$game_row =$stmt ->fetch();
		if ( $game_row === false ) {
			
			
			$sql = 'INSERT INTO `Game` (problem_id, iid,  rect, oval, trap,hexa)	
						VALUES (:problem_id,  :iid, :rect, :oval, :trap,:hexa)';
				$stmt = $pdo->prepare($sql);
				$stmt -> execute(array(
				':problem_id' => $problem_id,
				':iid' => $iid,
				':rect' => $rect,
				':oval' => $oval,
				':trap' => $trap,
				':hexa' => $hexa,
				
				));
			
			// we need to create the entry	
				// stopped here delete me //temp
				
		}	else {
			
			$sql = 'UPDATE `Game` SET `rect` = :rect, `oval` = :oval, `trap` = :trap, `hexa` = :hexa,
			WHERE `problem_id` = :problem_id AND `iid` = :iid,';
				$stmt = $pdo->prepare($sql);
				$stmt -> execute(array(
				':problem_id' => $problem_id,
				':iid' => $iid,
				':rect' => $rect,
				':oval' => $oval,
				':trap' => $trap,
				':hexa' => $hexa,
				));
				
			
		}
		
		
	}
	
	
	
	
	









?>