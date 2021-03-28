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

			 	$k = 0;
			 foreach ($gameaction_data as $gameaction_datum){

				if (isset($gameaction_datum["fin_onetime_cost"])){$fin_ongoing_benefit = '<p> Financial Benefit: '.$gameaction_datum["fin_onetime_cost"].'</p>';} else {$fin_ongoing_benefit ='';}  // placeholder until get a different catagory in data table
				if (isset($gameaction_datum["env_ongoing_benefit"])){$env_ongoing_benefit = '<p> Environmental Benefit: '.$gameaction_datum["env_ongoing_benefit"].'</p>';} else {$env_ongoing_benefit ='';}
				if (isset($gameaction_datum["soc_ongoing_benefit"])){$soc_ongoing_benefit = '<p> Societal Benefit: '.$gameaction_datum["soc_ongoing_benefit"].'</p>';} else {$soc_ongoing_benefit ='';}
				if (isset($gameaction_datum["action_image_file"])){$action_image_file = '<img src = " '.$gameaction_datum["action_image_file"].'">';} else {$action_image_file ='';}

			

				if($chaos_team == 0){

				 $cards[$k] = '<div class = "action_card" id = "action_card_'.$gameaction_datum["gameaction_id"].'">
				 	<h3>'.$gameaction_datum["game_action_title"].'</h3>'.
					 '<h4> Cost = '.$gameaction_datum["fin_onetime_cost"].'</h4>'.
					//  '<p> Cost = '.$gameaction_datum["fin_onetime_cost"].'</h4>'.
					 $fin_ongoing_benefit.
					 $env_ongoing_benefit.
					 $soc_ongoing_benefit.
					 $action_image_file.
					 
					 '<button type="button" value = "'.$gameaction_datum["gameaction_id"].'">Add</button>'.

					 '</div>';

					$k=$k+1;










				// $options = $options.'<option value ='. $gameaction_datum["gameaction_id"].'>'.$gameaction_datum["game_action_title"].
				// ' Cost: '.$gameaction_datum["fin_onetime_cost"].
				// ', Financial: '.$gameaction_datum["fin_onetime_cost"].
				// ', Environmental: '.$gameaction_datum["env_ongoing_benefit"].
				// ', Societal: '.$gameaction_datum["soc_ongoing_benefit"].
				// ', Blocks: '.$gameaction_datum["game_development_title"].
				// ' with effect = '.$gameaction_datum["blocking_effect"].
				// '</option>'; 	
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

<style>
		.container-1{
			display:flex;
			
		}

		.container-1 div{
			border:1px #ccc solid;
			padding:10px;
		}
		.card{
			flex:1;
		}

</style>
</head>


<body>

<h1> Team Captains Page </h1>
<h2><div id = "team_score" value = "<?php echo $team_score; ?>"> Team Score: <?php echo $team_score; ?> </div></h2>
<?php if ($chaos_team == 1){
	echo '<h2> This is the Chaos Team </h2>';
} else {

	echo '<h2> This is a Normal Team </h2>';
}

?>
<h2> Spend Action Points </h2>
<div class = "container-1">
	
	<?php 
	foreach($cards as $card){
		echo '<div class = "card">';
			echo $card;
		echo '</div>';

	}


	?>
</div>

<script>

if (document.addEventListener){
    document.addEventListener("click", function(event){
        let targetElement = event.target || event.srcElement;
        console.log(targetElement);
    });
} else if (document.attachEvent) {    
    document.attachEvent("onclick", function(){
        let targetElement = event.target || event.srcElement;
        console.log(targetElement);
    });
}





    //   document.getElementByClassName('card').addEventListener('click',(e)=>{
    //     e.preventDefault();
    //     const team_num_update = document.getElementById('team_num_update').value;
    //     console.log(`number of teams is ${team_num_update}`);
    //     const eexamtime_id = document.getElementById('eexamtime_id').value;
    //     console.log(`eexamtime_id ${eexamtime_id}`);
    //     $.ajax({   // this looks updates the eregistration with the new dex number
	// 									url: 'update_number_teams.php',
	// 									method: 'post',
						
	// 								data: {eexamtime_id:eexamtime_id,number_teams:team_num_update}
	// 								}).done(function(){
    //                });
    //                window.location.reload(1);

    //   })



</script>

</body>
</html>


