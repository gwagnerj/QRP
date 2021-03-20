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
			$sql = "SELECT `team_score` FROM Team WHERE `team_id` =:team_id";
			$stmt = $pdo->prepare($sql);
			$stmt ->execute(array(':team_id' => $team_id));
			$team_data = $stmt->fetch();
			$team_score = $team_data['team_score'];



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
<h2> Team Score: <?php echo $team_score; ?> </h>
</body>
</html>


