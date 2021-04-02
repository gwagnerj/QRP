<?php
require_once "pdo.php";
session_start();

// initialize a bunch of vars a Domain
$game_action_title = $action_cost = $fin_benefit = $env_benefit = $soc_benefit = $gameboard_action = $action_image_file = '';
$game_action_error = $action_cost_error = $benefit_error = $gameboard_id = '';
$fin_block = $env_block = $soc_block = 0;


if (isset($_POST['submit_action_form'])){

    if(strlen($_POST['game_action_title'])>2){
        $game_action_title = $_POST['game_action_title'];
    } else {
        $game_action_error = 'Title is required for Action submitssion';
    }


    if(strlen($_POST['gameboard_action'])>0){ // this is the gameboard number from the first input box and is the same for all cards set by script into an input
        $gameboard_id = $_POST['gameboard_action'];
        // echo ' gameboard_id '.$gameboard_id;
    } else {
        $gameboard_action_error = 'gameboard is not set';
    }

    if(strlen($_POST['action_cost'])>0){
        $action_cost = $_POST['action_cost'];
    } else {
        $action_cost_error = 'Cost is required for Action submitssion';
    }


    
    if(strlen($_POST['fin_benefit'])>0) {$fin_benefit = $_POST['fin_benefit'];}
    if(strlen($_POST['env_benefit'])>0){$env_benefit = $_POST['env_benefit'];} 
    if(strlen($_POST['soc_benefit'])>0){$soc_benefit = $_POST['soc_benefit'];} 

   
    // die();


    if(isset($_FILES['action_image_file'])){
        $action_image_file = '';  // this needs to get the name of the file and put it in the data base and call a sub to upload the file
       
      //   $action_image_file = $_FILES['action_image_file'];
    } 

    if (!isset($fin_benefit) && !isset($env_benefit) && !isset($soc_benefit)){
       $benefit_error = 'One of the benefits must be set';
    }

    
   

  //  echo 'isset game action'.isset( $game_action_error);
    if (strlen( $game_action_error)>0 || strlen($action_cost_error)>0 || strlen($benefit_error)>0){
        $_SESSION['error'] = 'error in the input for the Action Card';
    } else {
// - we don't have any errors can submit the data to the action data table and the action connect table

        $sql = 'INSERT INTO GameAction 
             (game_action_title,cost,fin_benefit,env_benefit,soc_benefit,fin_block,env_block,soc_block,action_image_file) 
        VALUES (:game_action_title,:cost,:fin_benefit,:env_benefit,:soc_benefit,:fin_block,:env_block,:soc_block,:action_image_file)
         ';
			$stmt = $pdo->prepare($sql);
			$stmt ->execute(array(
                ':game_action_title' => $game_action_title,
                ':cost' => $action_cost,
                ':fin_benefit' => $fin_benefit,
                ':env_benefit' => $env_benefit,
                ':soc_benefit' => $soc_benefit,
                ':fin_block' => $fin_block,
                ':env_block' => $env_block,
                ':soc_block' => $soc_block,
                ':action_image_file' => $action_image_file,
            ));
            $gameaction_id = $pdo->lastInsertId();
          //  echo (' $gameaction_id '.$gameaction_id);

          //now put in gameaction_id and the $gameboard_action in the gameboardgameaction connect table


            $sql = 'INSERT INTO GameBoardGameActionConnect
                (gameaction_id, gameboard_id)
                VALUES (:gameaction_id, :gameboard_id)
            ';
            $stmt = $pdo->prepare($sql);
            $stmt ->execute(array(
                ':gameaction_id' => $gameaction_id,
                ':gameboard_id' => $gameboard_id,
            ));



      //  echo 'insert into action table';
    }
    } elseif (isset($_POST['submit_chaos_form'])){
    //    echo 'chaos form submitted'; 


    } elseif (isset($_POST['submit_political_form'])){
  
}


$sql = "SELECT * FROM GameBoard";
$stmt = $pdo->prepare($sql);
$stmt -> execute();
$gameboard_data = $stmt ->fetchAll();
// var_dump($gameboard_data);

$gameboards = '<select  id = "game_board" name = "game_board"  >
<option value = "" selected disabled hidden >--Choose Game Board--</option>';
foreach ($gameboard_data as $gameboard_datum){
    $gameboards = $gameboards.'<option value = "'.$gameboard_datum['gameboard_id'].'" >'.$gameboard_datum['game_board_title'].'</option>';
}
$gameboards = $gameboards.'</select>';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Board Setup</title>

    <style>
    .container{
        padding: 20px;
    }
    .form_content{
        padding-left: 20px;
    }
    p { 
        margin-bottom: 5px;
    }
    h1 { 
        margin-bottom: 5px;
    }
     h2 { 
        margin-top: 5px;
    }
    
    </style>
</head>
<body>
<h1> &nbsp; Game Board SetUp</h1>

<div class="container">


<h2> Game Board:  <?php echo $gameboards; ?> </h2>



    <h2> Create Actions (aka action cards): </h2>
    <form id="action_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
        <div class ="form_content">
            <p>Title of the Action Card <input type = "text" name = "game_action_title" id = "game_action_title" value ="<?php echo $game_action_title;?>" minlength = "3" maxlength = "50" ></input></p>
            <p> Cost:  <input type = "number"  id = "action_cost" name = "action_cost" min = "0" max = "9999" value ="<?php echo $action_cost;?>" ></input></p>
            <p>  Financial Benefit <input type = "number"  id = "fin_benefit" name = "fin_benefit" value = "0" min = "0" max = "99" ></input></p>
            <p>  Environmental Benefit <input type = "number"  id = "env_benefit" name = "env_benefit" value = "0" min = "0" max = "99" ></input></p>
            <p>  Societal Benefit <input type = "number"  id = "soc_benefit" name = "soc_benefit" value = "0" min = "0" max = "99" ></input></p>
            <p>  Financial Block <input type = "number"  id = "fin_block" name = "fin_block" value = "0" min = "0" max = "99" ></input></p>
            <p>  Environmental Block <input type = "number"  id = "env_block" name = "env_block" value = "0" min = "0" max = "99" ></input></p>
            <p>  Societal Block <input type = "number"  id = "soc_block" name = "soc_block" value = "0" min = "0" max = "99" ></input></p>
            <p><label for="action_imgage_file">Select Action Card Image: </label>  <input type="file" id="action_imgage_file" name="action_imgage_file" accept="image/*"></p>
            <p><label for="action_html_file">Select Action Card html file (if any) :</label>  <input type="file" id="action_html_file" name="action_html_file" accept=".html,.htm"></p>
            <p><label for="action_video_file">Select Action Card video file (if any):</label>  <input type="file" id="action_video_file" name="action_video_file" accept=".mp4"></p>
            <input type="hidden" name="gameboard_action" name = "gameboard_action" id = "gameboard_action" >
            </div>
                    <input type="submit" name = "submit_action_form" id = "submit_action_form" value = "Submit">
    </form>

</div> 
<div class="container">
    <h2> Create Chaos </h2>
    <form id="chaos_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class ="form_content">
            <p>Title of the Chaos Card <input type = "text" name = "game_chaos_title" id = "game_chaos_title"  minlength = "3" maxlength = "50" ></input></p>
            <p> Cost:  <input type = "number"  id = "chaos_cost" name = "chaos_cost" min = "0" max = "9999" ></input></p>

            <p> Main Effect   <select  id = "chaos_main_effect" name = "chaos_main_effect"  >
            <option value = "" selected disabled hidden >--Choose Catagory--</option>
            <option value = "financial" >Financial</option>
            <option value = "environmental" >Environmental</option>
            <option value = "societal" >Societal</option>
            
            </select></p>
            <p> Financial Hit  <input type = "number"  id = "fin_hit" name = "fin_hit" value = "0" min = "0" max = "100" ></input></p>
            <p> Environmental Hit  <input type = "number"  id = "env_hit" name = "env_hit" value = "0" min = "0" max = "100" ></input></p>
            <p> Societal Hit  <input type = "number"  id = "soc_hit" name = "soc_hit" value = "0" min = "0" max = "100" ></input></p>
            <p><label for="chaos_imgage_file">Select chaos Image: </label>  <input type="file" id="chaos_imgage_file" name="chaos_imgage_file" accept="image/*"></p>
            <p><label for="chaos_html_file">Select chaos html file (if any) :</label>  <input type="file" id="chaos_html_file" name="chaosn_html_file" accept=".html,.htm"></p>
            <p><label for="chaos_video_file">Select chaos video file (if any):</label>  <input type="file" id="chaos_video_file" name="chaos_video_file" accept=".mp4"></p>
            <input type="hidden" name="gameboard_chaos" name = "gameboard_chaos" id = "gameboard_chaos" >

            </div>
                    <input type="submit" name = "submit_chaos_form" id = "submit_chaos_form" value = "Submit">
    </form>

</div> 

<div class="container">
    <h2> Create Political Cards: </h2>
    <form id="political_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class ="form_content">
            <p>Title of the Political Card <input type = "text" name = "game_political_title" id = "game_political_title"  minlength = "3" maxlength = "50" ></input></p>
            <p>  Financial Weight (0 to 100) <input type = "number"  id = "fin_wt" name = "fin_wt" value = "0" min = "0" max = "99" ></input></p>
            <p>  Environmental Weight (0 to 100) <input type = "number"  id = "env_wt" name = "env_wt" value = "0" min = "0" max = "99" ></input></p>
            <p>  Societal Weight (0 to 100) <input type = "number"  id = "soc_wt" name = "soc_wt" value = "0" min = "0" max = "99" ></input></p>
            <p><label for="political_imgage_file">Select Political Card Image: </label>  <input type="file" id="political_imgage_file" name="political_imgage_file" accept="image/*"></p>
            <p><label for="political_html_file">Select political Card html file (if any) :</label>  <input type="file" id="political_html_file" name="political_html_file" accept=".html,.htm"></p>
            <p><label for="political_video_file">Select political Card video file (if any):</label>  <input type="file" id="political_video_file" name="political_video_file" accept=".mp4"></p>
            <input type="hidden" name="gameboard_political" name = "gameboard_political" id = "gameboard_political" >

            </div>
                    <input type="submit" name = "submit_political_form" id = "submit_political_form" value = "Submit">
    </form>

</div> 

<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
<script>

        document.getElementById("game_board").addEventListener("change", myFunction);

        function myFunction() {
           let  game_board = document.getElementById("game_board").value;
            console.log('game_board'+game_board);
            document.getElementById("gameboard_action").value = game_board;
            document.getElementById("gameboard_chaos").value = game_board;
            document.getElementById("gameboard_political").value = game_board;

        // document.getElementById("demo").innerHTML = "Hello World";
        }

</script>
</body>
</html>