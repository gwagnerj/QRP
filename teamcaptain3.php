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


 
             

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="icon" type="image/png" href="McKetta.png" />  

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Game Results</title>

   
</head>
<body>

<h1> Game Results </h1>

    
</body>
</html>