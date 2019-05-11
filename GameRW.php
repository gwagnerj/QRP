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
if (isset($_POST['work_time'])){
	$work_time = $_POST['work_time'];
} else {
	$work_time = 15;  // 15 minutes
}
if (isset($_POST['time_delete'])){
	$time_delete = $_POST['time_delete'];
} else {
	$time_delete = 10;  // 10 days
}


if (isset($_POST['activate_flag'])){
	$activate_flag = $_POST['activate_flag'];
} else {
	$_SESSION['error'] = 'activate_flag not set';
	header("Location: getGame.php");
	return;
}
	
 
 /* $problem_id = 222;
 $iid = 5;
 $dex = 12;
 $rect = "foo";
 $oval = "bar";
 $trap = "wt";
 $hexa = "g";
 $activate_flag = 1; */
 
 
 
 
 
 
 
 
	
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
			
			
			$sql = 'INSERT INTO `Game` (problem_id, iid, work_time, time_delete, dex, rect, oval, trap,hexa)	
						VALUES (:problem_id,  :iid, :work_time, :time_delete, :dex, :rect, :oval, :trap,:hexa)';
				$stmt = $pdo->prepare($sql);
				$stmt -> execute(array(
				':problem_id' => $problem_id,
				':iid' => $iid,
				':dex' => $dex,
				':work_time' => $work_time,
				':time_delete' => $time_delete,
				':rect' => $rect,
				':oval' => $oval,
				':trap' => $trap,
				':hexa' => $hexa,
				
				));
			
			// we need to create the entry	
				// stopped here delete me //temp
				
		}	else {
			
			
			
			
			$sql = "UPDATE Game SET rect = :rect,  oval = :oval, trap = :trap, hexa = :hexa, work_time = :work_time, time_delete = :time_delete
			WHERE problem_id = :problem_id AND iid = :iid AND dex = :dex ";
				$stmt = $pdo->prepare($sql);
				$stmt -> execute(array(
				':problem_id' => $problem_id,
				':iid' => $iid,
				':dex' => $dex,
				':work_time' => $work_time,
				':time_delete' => $time_delete,
				':rect' => $rect,
				':oval' => $oval,
				':trap' => $trap,
				':hexa' => $hexa,
				));
				
			
		}
		
		$stmt = $pdo->prepare("SELECT `game_id` FROM `Game` where problem_id = :problem_id AND dex = :dex AND iid = :iid");
		 $stmt->execute(array(":problem_id" => $problem_id,":dex" => $dex, ":iid" => $iid ));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
		if ( $row === false ) {
			$_SESSION['error'] = 'could not read game_id from Game table';
	
		}	
		
		$game_id = $row['game_id'];
		$resp_arr = array('game_id' => $game_id);
	 echo json_encode($resp_arr);
	}
	
	
	
	
	









?>