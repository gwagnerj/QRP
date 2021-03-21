<?php
require_once "pdo.php";
session_start();

	if (isset($_GET['eexamnow_id']) && isset($_GET['student_id'])   ){

		$eexamnow_id = $_GET['eexamnow_id'];
		$student_id = $_GET['student_id'];

			$sql = "SELECT * FROM TeamStudentConnect WHERE eexamnow_id =:eexamnow_id AND student_id = :student_id";
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(
				':eexamnow_id' => $eexamnow_id,
				':student_id' => $student_id,
					
			));
			$teamstudentconnect_data = $stmt->fetch(PDO::FETCH_ASSOC);
				if($teamstudentconnect_data != false){
					$team_id = $teamstudentconnect_data['team_id']; 
					$team_num = $teamstudentconnect_data['team_num']; 
					$team_cap = $teamstudentconnect_data['team_cap'];
						if ($team_cap ==0){
							$_SESSION['error'] = 'Exam is not in progress';
					//		echo 'team_cap was zero';
									header("Location: stu_exam_frontpage.php?eregistration_id=".$eregistration_id );
							die();     
						}

				}  else {
					$_SESSION['error'] = "could not find the student and eexamnow in student team connection table";
					echo 'teamstudentconnect was false';
					header("Location: stu_exam_frontpage.php?eregistration_id=".$eregistration_id );
					die();     

				}
			$sql = "SELECT team_score,chaos_team FROM Team WHERE `team_id` =:team_id";
			$stmt = $pdo->prepare($sql);
			$stmt ->execute(array(':team_id' => $team_id));
			$team_data = $stmt->fetch();
			$team_score = $team_data['team_score'];
			$chaos_team = $team_data['chaos_team'];   // this will either be 0 or 1


			$sql = "SELECT eexamtime_id FROM Eexamnow WHERE eexamnow_id = :eexamnow_id";
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(
				':eexamnow_id' => $eexamnow_id,
					
			));
			$eexamnow_data = $stmt->fetch(PDO::FETCH_ASSOC);
			$eexamtime_id = $eexamnow_data['eexamtime_id'];



			$sql = "SELECT gameboard_id FROM Eexamtime WHERE eexamtime_id =:eexamtime_id ";
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(
				':eexamtime_id' => $eexamtime_id,
					
			));
			$eexamtime_data = $stmt->fetch(PDO::FETCH_ASSOC);
			$gameboard_id = $eexamtime_data['gameboard_id'];
//			echo ' gameboard_id'.$gameboard_id;

	       $sql = "SELECT * FROM GameBoard WHERE gameboard_id =:gameboard_id";
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(
				':gameboard_id' => $gameboard_id,
					
			));
			$gameboard_data = $stmt->fetch(PDO::FETCH_ASSOC);
//			var_dump($gameboard_data);
			$game_board_title = $gameboard_data['game_board_title'];
			$board_image_file = $gameboard_data['board_image_file'];

			$sql = "SELECT * FROM GameAction
			 LEFT JOIN GameActionGameDevelopmentConnect
			 ON GameActionGameDevelopmentConnect.gameaction_id = GameAction.gameaction_id
			 LEFT JOIN GameDevelopment

			 ON GameActionGameDevelopmentConnect.gamedevelopment_id = GameDevelopment.gamedevelopment_id
			 WHERE GameActionGameDevelopmentConnect.gameboard_id = :gameboard_id";
			 $stmt = $pdo->prepare($sql);
			 $stmt -> execute(array(
				 ':gameboard_id' => $gameboard_id,
					 
			 ));
			 $gameaction_data = $stmt->fetchALL(PDO::FETCH_ASSOC);
			 $options ='';
//var_dump($gameaction_data);
			 foreach ($gameaction_data as $gameaction_datum){

				if($chaos_team == 0){

				$options = $options.'<option value ='. $gameaction_datum["gameaction_id"].'>'.$gameaction_datum["game_action_title"].
				' Cost: '.$gameaction_datum["fin_onetime_cost"].
				', Financial: '.$gameaction_datum["fin_onetime_cost"].
				', Environmental: '.$gameaction_datum["env_ongoing_benefit"].
				', Societal: '.$gameaction_datum["soc_ongoing_benefit"].
				', Blocks: '.$gameaction_datum["game_development_title"].
				' with effect = '.$gameaction_datum["blocking_effect"].
				'</option>'; 	
				} else {

					$options = $options.'<option value ='. $gameaction_datum["gamedevelopment_id"].'>'.$gameaction_datum["game_development_title"].
					' Cost: '.$gameaction_datum["fin_onetime_change"].
					', Financial: '.$gameaction_datum["fin_onetime_cost"].
					', Blocked by: '.$gameaction_datum["game_action_title"].
					' with effect = '.$gameaction_datum["blocking_effect"].
					'</option>'; 	

				}
			}
// var_dump($gameaction_data);


 

	} else {
		$_SESSION['error'] = "at least one parameter not set";

	}


?>


<!DOCTYPE html>
	<html lang = "en">
	<head>


	<link rel="icon" type="image/png" href="McKetta.png" />  
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta Charset = "utf-8">
	<title>Team Captain</title>
  <!-- <meta http-equiv="refresh" content="10"> -->
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 
</head>
<body>

<h1> Team Captains Page </h1>
<h2> Team Score: <?php echo $team_score; ?> </h2>
<?php if ($chaos_team == 1){
	echo '<h2> This is the Chaos Team </h2>';
} else {

	echo '<h2> This is a Normal Team </h2>';
}

?>
<h2> Spend Action Points </h2>
&nbsp;&nbsp;&nbsp;
<select id = "action_points" name = "action_points">
<option value =''> - Select Action - </option>
<?php
echo $options;
 ?>

></select>


</body>
</html>


