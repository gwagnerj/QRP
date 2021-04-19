<?php
    require_once "pdo.php";
    session_start();

    $team_id = $_GET['team_id'];
    // echo 'available funds: '.$available_funds;
    // echo '<br>';
    // echo 'team_id: '.$team_id;
    // echo '<br>';

    $sql = "SELECT * FROM Team WHERE `team_id` =:team_id";
        $stmt = $pdo->prepare($sql);
        $stmt ->execute(array(':team_id' => $team_id));
        $team_data = $stmt->fetch();

        $team_score = $team_data['team_score'];
        $chaos_team = $team_data['chaos_team'];   // this will either be 0 or 1
        $eexamnow_id= $team_data['eexamnow_id'];   
        $available_funds = $team_data['pol_points'];
        $chaos_team = $team_data['chaos_team'];


    $sql = "SELECT * FROM GamePolitical";
        $stmt = $pdo->prepare($sql);
        $stmt -> execute();
        $gamepolitical_data = $stmt->fetchALL(PDO::FETCH_ASSOC);
    $options ='';

    $cards = array();
    $k = 0; 	
			 foreach ($gamepolitical_data as $gamepolitical_datum){
                 $fin_wt = $gamepolitical_datum['fin_wt'];
                 $soc_wt = $gamepolitical_datum['soc_wt'];
                 $env_wt = $gamepolitical_datum['env_wt'];
                 $political_image_file = $gamepolitical_datum['political_image_file'];
                    $cards[$k] = '<div class = "political_card card_container">'.
                        '<div class = "card" id = "card_'.$gamepolitical_datum['gamepolitical_id'].'">
                          <h2>'.$gamepolitical_datum["game_political_title"].'</h2>'.
                                '<div class = "card_data">'.
                            '<img src ="'. $political_image_file.'">'.
                            '<h2> Weights </h2>'.
                                '<p> Financial: <span id = "finwt_'.$gamepolitical_datum["gamepolitical_id"].'">'.$fin_wt.'</span></p>'.
                                '<p> Environmental: <span id = "envwt_'.$gamepolitical_datum["gamepolitical_id"].'">'.$env_wt.'</span></p>'.
                                '<p> Societal: <span id = "socwt_'.$gamepolitical_datum["gamepolitical_id"].'">'.$soc_wt.'</span></p>'.
                        '</div>'.
                    '</div>'.
                 '</div>';

                 $k=$k+1;
             }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="icon" type="image/png" href="McKetta.png" />  

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polictical Pick</title>

    <style>

    h2{ 
padding:0.5em 0em 0.5em 0em;

    }

    .gray_out_card{ 
			background-color: gray;
  			 opacity:0.4; 
		}

    #button_container{
        padding:0em 0em 0em 2em;
    }

    #submit_button{

        padding:1em;
        font-size:+2em;
        background-color:darkred; 
        color: white


    }

input[type=radio] {
    margin: 1em 1em 1em 2em;
    padding:3em;
    transform: scale(2, 2);
}
    p{ 
		padding:3px;
        font-size: 1.3em;
	
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

    </style>

</head>
<body>

<h1> Select Desired Political Environment </h1>
<h1><span id = "error"></span></h1>
<?php if ($chaos_team == 1){
	echo '<h2 style = "color:red;"> This is the Chaos Team </h2>';

	echo '<h2>Selected Hits:  <span style = "color:#892816;">  &nbsp; Financial  <span id = "financial_hits" value="0" > '.$team_data["fin_hit"].' </span></span>
    <span style = "color:#4c884a;">&nbsp; Environmental  <span id = "environmental_hits" value="0" >'.$team_data["env_hit"].'</span> </span>
    <span style = "color:#6e4a88;">  &nbsp; Societal  <span id = "societal_hits" value="0" > '.$team_data["soc_hit"].' </span></span></h2>';
	
} else {
	echo '<h2>Selected Points:  &nbsp; <span style = "color:#892816;">  Financial  <span id = "financial_points" value="0" > '.$team_data["fin_score"].' </span></span>
    <span style = "color:#4c884a;"> &nbsp; Environmental  <span id = "environmental_points" value="0" > '.$team_data["env_score"].' </span></span>
    <span style = "color:#6e4a88;">    &nbsp; Societal  <span id = "societal_points" value="0" > '.$team_data["soc_score"].' </span></span></h2>';
	echo '<h2>Selected Blocks:  &nbsp;  <span style = "color:#892816;">  Financial  <span id = "financial_blocks" value="0" > '.$team_data["fin_block"].' </span></span>
    <span style = "color:#4c884a;"> &nbsp; Environmental  <span id = "environmental_blocks" value="0" > '.$team_data["env_block"].' </span> </span>
    <span style = "color:#6e4a88;">    &nbsp; Societal  <span id = "societal_blocks" value="0" > '.$team_data["soc_block"].' </span></span></h2>';
	
	// echo '<h2> This is a Normal Team </h2>';
}
?>
<h2> Funds for Selection: <span id ="available_funds"> <?php echo $available_funds; ?></span></h2>
<input type="hidden" id = "gamepolitical_id" value = ""></input>
<div id = "no_selection_error"> </div>'

<div class = "container-1">
            <?php 
            foreach($cards as $card){
                        echo $card;
            }
            ?>
            
        <input type="hidden" id = "team_id" name="team_id" value = "<?php echo $team_id; ?>"></input>


</div>
<div id = "button_container">
    <button id="submit_button"> Submit </button>
</div>
<script>
    document.getElementById("submit_button").addEventListener("click",()=>{
       // console.log("Submit Button");

      let gamepolitical_id = document.getElementById("gamepolitical_id").value;
      console.log ("gamepolitical_id "+gamepolitical_id);
      if(!gamepolitical_id){
        document.getElementById("no_selection_error").innerHTML = '<<h1 style = "color:red;"> Selection Must Be made Before Submit </h1>';
        return;
      } 

      document.getElementById("no_selection_error").innerHTML = '';
      const team_id = document.getElementById("team_id").value;
      $.ajax({  
					url: 'update_team_politics.php',
					method: 'post',
				    data: {
						team_id:team_id,
						gamepolitical_id:gamepolitical_id
					}
						}).done(function(){
			 			   window.location.replace("teamcaptain3.php?team_id="+team_id);

                   });



      // get the values for the fin
        // make sure one of the radio buttons is selected if not make error, get the value of the radio button, get the funds avaialble and Ajax to a table then go to next or exit and values are on score board

    })

    let cards = document.getElementsByClassName('card');
    for (let i = 0; i < cards.length; i++){
        let card = parseInt(cards[i].textContent,10);
		let card_id = cards[i].id;
		card_id = card_id.split('_')[1];
        // console.log(`card id is ${card_id}`);
        document.getElementById("card_"+card_id).addEventListener("click", () => {
				addCard(card_id);
			});


    }

    function addCard(card_id){

        console.log (`gamepolitical_id is ${card_id}`);
        document.getElementById("gamepolitical_id").value = card_id;

        for (let i = 0; i < cards.length; i++){
            let card = parseInt(cards[i].textContent,10);
            let card_id2 = cards[i].id;
            card_id2 = card_id2.split('_')[1];
            if (card_id2 != card_id){
                document.getElementById("card_"+card_id2).classList.add("gray_out_card");   

            } else {
                document.getElementById("card_"+card_id2).classList.remove("gray_out_card");  
            }

        }
    }


</script>
    
</body>
</html>