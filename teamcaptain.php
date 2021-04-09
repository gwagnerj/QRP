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
//  echo ' gameboard_id'.$gameboard_id;

	       $sql = "SELECT * FROM GameBoard WHERE gameboard_id =:gameboard_id";
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(
				':gameboard_id' => $gameboard_id,
					
			));
			$gameboard_data = $stmt->fetch(PDO::FETCH_ASSOC);
	// var_dump($gameboard_data);
			$game_board_title = $gameboard_data['game_board_title'];
			$board_image_file = $gameboard_data['board_image_file'];

			$sql = "SELECT * FROM GameAction
			 LEFT JOIN GameBoardGameActionConnect
			 ON GameBoardGameActionConnect.gameaction_id = GameAction.gameaction_id
			 WHERE GameBoardGameActionConnect.gameboard_id = :gameboard_id";
			 $stmt = $pdo->prepare($sql);
			 $stmt -> execute(array(
				 ':gameboard_id' => $gameboard_id,
					 
			 ));
			 $gameaction_data = $stmt->fetchALL(PDO::FETCH_ASSOC);
	//  var_dump($gameaction_data);

			 $sql = "SELECT * FROM GameChaos
			 LEFT JOIN GameBoardGameChaosConnect
			 ON GameBoardGameChaosConnect.gamechaos_id = GameChaos.gamechaos_id
			 WHERE GameBoardGameChaosConnect.gameboard_id = :gameboard_id";
			 $stmt = $pdo->prepare($sql);
			 $stmt -> execute(array(
				 ':gameboard_id' => $gameboard_id,
					 
			 ));
			 $gamechaos_data = $stmt->fetchALL(PDO::FETCH_ASSOC);

			 $sql = "SELECT * FROM GamePolitical";
			 $stmt = $pdo->prepare($sql);
			 $stmt -> execute();
			 $gamepolitical_data = $stmt->fetchALL(PDO::FETCH_ASSOC);
			 $options ='';
			
		$cards = array();
		if($chaos_team == 0){

			$k = 0; 	
			 foreach ($gameaction_data as $gameaction_datum){

				if (isset($gameaction_datum["cost"])){$cost = '<h4> Cost <span class ="cost" id = "cost_'.$gameaction_datum["gameaction_id"].'">'.$gameaction_datum["cost"].'</span></h4>';} else {$cost ='';}  
				if (isset($gameaction_datum["max_select"])){$max_select = $gameaction_datum["max_select"].'</p>';} else {$max_select =0;}  
				if (isset($gameaction_datum["fin_benefit"])){$fin_benefit = $gameaction_datum["fin_benefit"];} else {$fin_benefit ='';}
				if (isset($gameaction_datum["env_benefit"])){$env_benefit = $gameaction_datum["env_benefit"];} else {$env_benefit ='';}
				if (isset($gameaction_datum["soc_benefit"])){$soc_benefit = $gameaction_datum["soc_benefit"];} else {$soc_benefit ='';}
				if (isset($gameaction_datum["fin_block"])){$fin_block = $gameaction_datum["fin_block"];} else {$fin_block ='';}
				if (isset($gameaction_datum["env_block"])){$env_block = $gameaction_datum["env_block"];} else {$env_block ='';}
				if (isset($gameaction_datum["soc_block"])){$soc_block = $gameaction_datum["soc_block"];} else {$soc_block ='';}
				if (isset($gameaction_datum["action_image_file"])){$action_image_file = '<img src = " '.$gameaction_datum["action_image_file"].'">';} else {$action_image_file ='';}

				 $cards[$k] = '<div class = "action_card card_container">'.
				 	'<div class = "card" id = "card_'.$gameaction_datum["gameaction_id"].'">
							<h3>'.$gameaction_datum["game_action_title"].'</h3>'.
							$cost.
							'<div class = "card_data">'.
								'<p> Available <span id = "available_'.$gameaction_datum["gameaction_id"].'"> '.$max_select.'</span></p>'.
								'<p> Num Selected: <span id = "numselected_'.$gameaction_datum["gameaction_id"].'">0</span></p> <br>'.
								$action_image_file.
								'<p> Fin Benefit: <span id = "finben_'.$gameaction_datum["gameaction_id"].'">'.$fin_benefit.'</span></p>'.
								'<p> Env Benefit: <span id = "envben_'.$gameaction_datum["gameaction_id"].'">'.$env_benefit.'</span></p>'.
								'<p> Soc Benefit: <span id = "socben_'.$gameaction_datum["gameaction_id"].'">'.$soc_benefit.'</span></p><br>'.
								'<p> Fin Block: <span id = "finblock_'.$gameaction_datum["gameaction_id"].'">'.$fin_block.'</span></p>'.
								'<p> Env Block: <span id = "envblock_'.$gameaction_datum["gameaction_id"].'">'.$env_block.'</span></p>'.
								'<p> Soc Block: <span id = "socblock_'.$gameaction_datum["gameaction_id"].'">'.$soc_block.'</span></p>'.
							'</div>'.
					'</div> <div class = "remove_button"> <button class ="button" type="button" id = "removebutton_'.$gameaction_datum["gameaction_id"].'" value = "'.$gameaction_datum["gameaction_id"].'">Drop One</button> </div>'.
					'</div>';
					$k=$k+1;
				}

			} else {
				$k = 0;
				foreach ($gamechaos_data as $gamechaos_datum){
					if (isset($gamechaos_datum["chaos_main_effect"])){$chaos_main_effect = $gamechaos_datum["chaos_main_effect"].'</p>';} else {$chaos_main_effect ='';}  
					if (isset($gamechaos_datum["max_select"])){$max_select = $gamechaos_datum["max_select"].'</p>';} else {$max_select =0;}  
					if (isset($gamechaos_datum["cost"])){$cost = '<h4> Cost <span class ="cost" id = "cost_'.$gamechaos_datum["gamechaos_id"].'">'.$gamechaos_datum["cost"].'</span></h4>';} else {$cost ='';}  
					if (isset($gamechaos_datum["fin_hit"])){$fin_hit = $gamechaos_datum["fin_hit"];} else {$fin_hit ='';}
					if (isset($gamechaos_datum["env_hit"])){$env_hit = $gamechaos_datum["env_hit"];} else {$env_hit ='';}
					if (isset($gamechaos_datum["soc_hit"])){$soc_hit = $gamechaos_datum["soc_hit"];} else {$soc_hit ='';}
					if (isset($gamechaos_datum["chaos_image_file"])){$chaos_image_file = '<img src = " '.$gamechaos_datum["chaos_image_file"].'">';} else {$chaos_image_file ='';}

					$cards[$k] = '<div class = "chaos_card card_container">
					<div class= "card" id = "card_'.$gamechaos_datum["gamechaos_id"].'">
						<h3>'.$gamechaos_datum["game_chaos_title"].'</h3>'.
						
						$cost.
						'<div class = "card_data">'.
							'<p> Available <span id = "available_'.$gamechaos_datum["gamechaos_id"].'">'.$max_select.'</span></p>'.
							'<p>Num Selected <span id = "numselected_'.$gamechaos_datum["gamechaos_id"].'"> 0 </span></p>'.
							$chaos_image_file.
							'<br><p> Fin Hit: <span id = "finhit_'.$gamechaos_datum["gamechaos_id"].'">'.$fin_hit.'</span></p>'.
							'<p> Env Hit:<span id = "envhit_'.$gamechaos_datum["gamechaos_id"].'">'.$env_hit.'</span></p>'.
							'<p> Soc Hit:<span id = "sochit_'.$gamechaos_datum["gamechaos_id"].'">'.$soc_hit.'</span></p>'.
						'</div>'.
						'</div> <div class = "remove_button"> <button class ="button" type="button" id = "removebutton_'.$gamechaos_datum["gamechaos_id"].'" value = "'.$gamechaos_datum["gamechaos_id"].'">Drop One</button> </div>'.
					'</div>';

				   $k=$k+1;
			}
		}

	} else {
		$_SESSION['error'] = "at least one parameter not set";

	}


?>


<!DOCTYPE html>
	<html lang = "en">
	<head>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta Charset = "utf-8">
	<title>Team Captain</title>
  <!-- <meta http-equiv="refresh" content="10"> -->
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 

<style>
	p{ 
		padding:0px;
	
	}
	ul {
			list-style-type: none;
		}
		#available_funds{
			/* width: 20em;
			height: 20em; */
			padding: 10px;
			margin:2em;
			border: 2px solid green;
		}

		.container-1{
			padding: 0.5em;
			display:grid;
			/* grid-template-columns: 18em 18em 18em 18em; */
			grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
			grid-gap:0.5em;
			justify-content: center;

		}
		.card_container{
			display: flex;
			padding:10px;
			border:1px solid black;
		
			flex-direction:column;
			/* flex-grow:column; */

		}	
		.card{
			padding:30px;
			/* display: flex; */
		}
		.card_data{
			line-height:6px;
		}

		.button{

			width:100%;
		}
		#submit_values{
			background-color:darkred;
			color:white;
			
		}
		.class_button{
			justify-content:right;
			padding-left:200px;
			border-right:100px; 
		}

 

</style>
</head>


<body>

<h1> Team Captains Page </h1>

<h2><span id = "team_score" value = "<?php echo $team_score; ?>"> Action Points Earned: <?php echo $team_score; ?> </span>  &nbsp; &nbsp; Available  <span id = "available_funds" value="0" > <?php echo $team_score; ?> </span></h2>
<?php if ($chaos_team == 1){
	echo '<h2 style = "color:red;"> This is the Chaos Team </h2>';
	echo '<h2> Hits: &nbsp; Financial  <span id = "financial_hits" value="0" > 0 </span> &nbsp; Environmental  <span id = "environmental_hits" value="0" > 0 </span>  &nbsp; Societal  <span id = "societal_hits" value="0" > 0 </span></h2>';
	
} else {
	echo '<h2> Points:  &nbsp; Financial  <span id = "financial_points" value="0" > 0 </span> &nbsp; Environmental  <span id = "environmental_points" value="0" > 0 </span>  &nbsp; Societal  <span id = "societal_points" value="0" > 0 </span></h2>';
	echo '<h2> Blocks:  &nbsp; Financial  <span id = "financial_blocks" value="0" > 0 </span> &nbsp; Environmental  <span id = "environmental_blocks" value="0" > 0 </span>  &nbsp; Societal  <span id = "societal_blocks" value="0" > 0 </span></h2>';
	
	// echo '<h2> This is a Normal Team </h2>';
}

?>
<h2> Click Card to Add &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<span class = "left_button"> <button id = "submit_values"  > Submit Values </button></span> </h2>
<div class = "container-1">
	
	<?php 
	foreach($cards as $card){
				echo $card;
	}


	?>
 <input type="hidden" id = "team_id" name="team_id" value = "<?php echo $team_id; ?>"></input>


</div>

<script>

	let available_funds = document.getElementById("available_funds").textContent;
	available_funds = parseInt(available_funds,10);
	let costs = document.getElementsByClassName('cost');
	document.getElementById("submit_values").addEventListener("click",()=>{
		submitValues();
	});

	for (let i = 0; i < costs.length; i++){
		let cost = parseInt(costs[i].textContent,10);
		let cost_id = costs[i].id;
		cost_id = cost_id.split('_')[1];


		if (cost<=available_funds){
			// change the avialability to based on how much they have
			let available = Math.floor(available_funds/cost);
				// console.log(`The avaiable is ${available}`);
				document.getElementById("available_"+cost_id).innerText = +available;


				document.getElementById("card_"+cost_id).addEventListener("click", function selectcard() {
				addCard(cost_id,cost);
				});

				if(document.getElementById("removebutton_"+cost_id)){
					document.getElementById("removebutton_"+cost_id).addEventListener("click", () =>{
						removeCard(cost_id,cost);
				});
				}
			
		} else {
			document.getElementById("available_"+cost_id).innerText = 0;
			// this should gray out the 
		}
	}




		function addCard(cost_id,cost){
				let available_funds = document.getElementById("available_funds").textContent;
				available_funds = parseInt(available_funds,10);
				let available = document.getElementById("available_"+cost_id).innerText;
				available =  parseInt(available,10);
				// console.log ('available '+available)
				if (cost>available_funds || available < 1 ){
					return;
				}

				let env_benefit = fin_benefit = soc_benefit = fin_block = env_block = soc_block = fin_hit = env_hit = soc_hit = 0;
				let num_selected = document.getElementById("numselected_"+cost_id).innerText;

				if (document.getElementById("finben_"+cost_id)){fin_benefit =  parseInt(document.getElementById("finben_"+cost_id).innerText,10); if(isNaN(fin_benefit)){fin_benefit = 0;} document.getElementById("financial_points").innerText = parseInt(document.getElementById("financial_points").innerText,10)+fin_benefit; } 
			    if (document.getElementById("envben_"+cost_id)){env_benefit =  parseInt(document.getElementById("envben_"+cost_id).innerText,10); if(isNaN(env_benefit)){env_benefit = 0;} document.getElementById("environmental_points").innerText = parseInt(document.getElementById("environmental_points").innerText,10)+env_benefit;} 
				if (document.getElementById("socben_"+cost_id)){soc_benefit =  parseInt(document.getElementById("socben_"+cost_id).innerText,10); if(isNaN(soc_benefit)){soc_benefit = 0;} document.getElementById("societal_points").innerText = parseInt(document.getElementById("societal_points").innerText,10)+soc_benefit;} 

				if (document.getElementById("finblock_"+cost_id)){fin_block =  parseInt(document.getElementById("finblock_"+cost_id).innerText,10); if(isNaN(fin_block)){fin_block = 0;} document.getElementById("financial_blocks").innerText = parseInt(document.getElementById("financial_blocks").innerText,10)+fin_block;} 
				if (document.getElementById("envblock_"+cost_id)){env_block =  parseInt(document.getElementById("envblock_"+cost_id).innerText,10); if(isNaN(env_block)){env_block = 0;} document.getElementById("environmental_blocks").innerText = parseInt(document.getElementById("environmental_blocks").innerText,10)+env_block;} 
				if (document.getElementById("socblock_"+cost_id)){soc_block =  parseInt(document.getElementById("socblock_"+cost_id).innerText,10); if(isNaN(soc_block)){soc_block = 0;} document.getElementById("societal_blocks").innerText = parseInt(document.getElementById("societal_blocks").innerText,10)+soc_block;}

				if (document.getElementById("finhit_"+cost_id)){fin_hit =  parseInt(document.getElementById("finhit_"+cost_id).innerText,10); if(isNaN(fin_hit)){fin_hit = 0;} document.getElementById("financial_hits").innerText = parseInt(document.getElementById("financial_hits").innerText,10)+fin_hit;} 
				if (document.getElementById("envhit_"+cost_id)){env_hit =  parseInt(document.getElementById("envhit_"+cost_id).innerText,10); if(isNaN(env_hit)){env_hit = 0;} document.getElementById("environmental_hits").innerText = parseInt(document.getElementById("environmental_hits").innerText,10)+env_hit;} 
				if (document.getElementById("sochit_"+cost_id)){soc_hit =  parseInt(document.getElementById("sochit_"+cost_id).innerText,10); if(isNaN(soc_hit)){soc_hit = 0;} document.getElementById("societal_hits").innerText = parseInt(document.getElementById("societal_hits").innerText,10)+soc_hit;} 

			//	 console.log(`soc hit is ${soc_hit}`)
					num_selected = parseInt(num_selected,10) + 1;

					// change the number selected on the card
					document.getElementById("numselected_"+cost_id).innerText = num_selected;
					// reduce the avialable funds
					available_funds = available_funds - cost;
					document.getElementById("available_funds").innerText = available_funds;
					// now we need to recompute the number avaialbe for all of the cards 
					// should also update all of the catagories for the score for that team (how many fin, env and soc they are recieving)
					// console.log(`the cost down in the click function is ${costs}`);
					for (let i = 0; i < costs.length; i++){
					    cost = parseInt(costs[i].textContent,10);
					    cost_id = costs[i].id;
						cost_id = cost_id.split('_')[1];

						// console.log(`id of card ${i} is ${cost_id}`);
						// console.log(`cost of card ${i} is ${cost}`);

						if (cost<=available_funds){
							// change the avialability to based on how much they have
							let available = Math.floor(available_funds/cost);
								// console.log(`The avaiable is ${available}`);
								document.getElementById("available_"+cost_id).innerText = +available;
								// document.getElementById("card_"+cost_id).addEventListener("click", () =>{
								// addCard(cost_id,cost);
							// });

							
							
						} else {

							document.getElementById("available_"+cost_id).innerText = 0;
								// document.getElementById("card_"+cost_id).removeEventListener("click",  addCard);
						


							// this should gray out the 
						}
					}
					// recheck all of the cards to make sure if they  we should remove event listeners

			}

			function removeCard(cost_id,cost){
				
				let num_selected = parseInt(document.getElementById("numselected_"+cost_id).innerText,10);
				if (num_selected ==0){
					return;
				}

				let env_benefit = fin_benefit = soc_benefit = fin_block = env_block = soc_block = fin_hit = env_hit = soc_hit = 0;
				if (document.getElementById("finben_"+cost_id)){fin_benefit =  parseInt(document.getElementById("finben_"+cost_id).innerText,10); if(isNaN(fin_benefit)){fin_benefit = 0;} document.getElementById("financial_points").innerText = parseInt(document.getElementById("financial_points").innerText,10)-fin_benefit; } 
			    if (document.getElementById("envben_"+cost_id)){env_benefit =  parseInt(document.getElementById("envben_"+cost_id).innerText,10); if(isNaN(env_benefit)){env_benefit = 0;} document.getElementById("environmental_points").innerText = parseInt(document.getElementById("environmental_points").innerText,10)-env_benefit;} 
				if (document.getElementById("socben_"+cost_id)){soc_benefit =  parseInt(document.getElementById("socben_"+cost_id).innerText,10); if(isNaN(soc_benefit)){soc_benefit = 0;} document.getElementById("societal_points").innerText = parseInt(document.getElementById("societal_points").innerText,10)-soc_benefit;} 

				if (document.getElementById("finblock_"+cost_id)){fin_block =  parseInt(document.getElementById("finblock_"+cost_id).innerText,10); if(isNaN(fin_block)){fin_block = 0;} document.getElementById("financial_blocks").innerText = parseInt(document.getElementById("financial_blocks").innerText,10)-fin_block;} 
				if (document.getElementById("envblock_"+cost_id)){env_block =  parseInt(document.getElementById("envblock_"+cost_id).innerText,10); if(isNaN(env_block)){env_block = 0;} document.getElementById("environmental_blocks").innerText = parseInt(document.getElementById("environmental_blocks").innerText,10)-env_block;} 
				if (document.getElementById("socblock_"+cost_id)){soc_block =  parseInt(document.getElementById("socblock_"+cost_id).innerText,10); if(isNaN(soc_block)){soc_block = 0;} document.getElementById("societal_blocks").innerText = parseInt(document.getElementById("societal_blocks").innerText,10)-soc_block;}

				if (document.getElementById("finhit_"+cost_id)){fin_hit =  parseInt(document.getElementById("finhit_"+cost_id).innerText,10); if(isNaN(fin_hit)){fin_hit = 0;} document.getElementById("financial_hits").innerText = parseInt(document.getElementById("financial_hits").innerText,10)-fin_hit;} 
				if (document.getElementById("envhit_"+cost_id)){env_hit =  parseInt(document.getElementById("envhit_"+cost_id).innerText,10); if(isNaN(env_hit)){env_hit = 0;} document.getElementById("environmental_hits").innerText = parseInt(document.getElementById("environmental_hits").innerText,10)-env_hit;} 
				if (document.getElementById("sochit_"+cost_id)){soc_hit =  parseInt(document.getElementById("sochit_"+cost_id).innerText,10); if(isNaN(soc_hit)){soc_hit = 0;} document.getElementById("societal_hits").innerText = parseInt(document.getElementById("societal_hits").innerText,10)-soc_hit;} 
				let available = document.getElementById("available_"+cost_id).innerText;
				available =  parseInt(available,10)+1;
				document.getElementById("available_"+cost_id).innerText = available;

				document.getElementById("numselected_"+cost_id).innerText = num_selected-1;

				let available_funds = document.getElementById("available_funds").textContent;
				available_funds = parseInt(available_funds,10);
				available_funds = available_funds + cost;
					document.getElementById("available_funds").innerText = available_funds;

					for (let i = 0; i < costs.length; i++){
					    cost = parseInt(costs[i].textContent,10);
					    cost_id = costs[i].id;
						cost_id = cost_id.split('_')[1];

						// console.log(`id of card ${i} is ${cost_id}`);
						// console.log(`cost of card ${i} is ${cost}`);

						if (cost<=available_funds){
							// change the avialability to based on how much they have
							let available = Math.floor(available_funds/cost);
								// console.log(`The avaiable is ${available}`);
								document.getElementById("available_"+cost_id).innerText = +available;
								// document.getElementById("card_"+cost_id).addEventListener("click", () =>{
								// addCard(cost_id,cost);
							// });
							
						} else {

							document.getElementById("available_"+cost_id).innerText = 0;
								// document.getElementById("card_"+cost_id).removeEventListener("click",  addCard);
							// this should gray out the 
						}
					}

			}

			function submitValues(){
				console.log(`submit values`);
				let fin_score = env_score = soc_score = fin_block = env_block = soc_block = fin_hit = env_hit = soc_hit = 0;

				// this is where I get the values into the data base via AJAX then move onto another page - maybe teamcaptain 2
				if (document.getElementById("financial_points")){ fin_score = document.getElementById("financial_points").innerText;}
				if(document.getElementById("environmental_points")){ env_score = document.getElementById("environmental_points").innerText;}
				if( document.getElementById("societal_points")){ soc_score = document.getElementById("societal_points").innerText;}
				if( document.getElementById("financial_blocks")) {fin_block = document.getElementById("financial_blocks").innerText;}
				if(document.getElementById("environmental_blocks")) {env_block = document.getElementById("environmental_blocks").innerText;}
				if(document.getElementById("societal_blocks")) { soc_block = document.getElementById("societal_blocks").innerText;}
				if(document.getElementById("financial_hits")){fin_hit = document.getElementById("financial_hits").innerText;}
				if(document.getElementById("environmental_hits")) {env_hit = document.getElementById("environmental_hits").innerText;}
				if(document.getElementById("societal_hits")) {soc_hit = document.getElementById("societal_hits").innerText;}
				const team_id = document.getElementById("team_id").value;
				let available_funds = document.getElementById("available_funds").textContent;
				console.log (`team_id is ${team_id}`);



				$.ajax({  
					url: 'update_team_data.php',
					method: 'post',
				    data: {
						team_id:team_id,
						fin_score:fin_score,
						env_score:env_score,
						soc_score:soc_score,
						fin_block:fin_block,
						env_block:env_block,
						soc_block:soc_block,
						fin_block:fin_block,
						fin_hit:fin_hit,
						env_hit:env_hit,
						soc_hit:soc_hit
					}
						}).done(function(){
                   });

				   window.location.replace("teamcaptain2.php?team_id="+team_id+"&available_funds="+available_funds);


			}




</script>

</body>
</html>


