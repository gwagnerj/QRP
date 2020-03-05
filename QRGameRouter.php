<?php
session_start();
require_once "pdo.php";


// Router takes input from anywhere and routs them the the correct file depending on phase
 
// first do some error checking on the input.  If it is not OK set the session failure and send them back to QRGameIndex or getGamePblmNum.php.

        if (isset($_POST['game_id'])){
            $game_id = $_POST['game_id'];
    }  elseif(isset($_SESSION['game_id'])){
            $game_id = $_SESSION['game_id'];
    } else  {
            $_SESSION['error'] = "Missing game_id from QRGameRouter";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['game_id'] = $game_id;
   

   
     if (isset($_POST['pin'])){
        $pin = $_POST['pin'];
    }  elseif(isset($_SESSION['pin'])){
         $pin = $_SESSION['pin'];
    } else  {
       $_SESSION['error'] = "Missing pin from QRGameRouter";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['pin'] = $pin;
    
    
      if (isset($_POST['team_id'])){
        $team_id = $_POST['team_id'];
    }  elseif(isset($_SESSION['team_id'])){
         $team_id = $_SESSION['team_id'];
    } else  {
       $_SESSION['error'] = "Missing team_id from QRGameRouter";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['team_id'] = $team_id;
    
      if (isset($_POST['gmact_id'])){
        $gmact_id = $_POST['gmact_id'];
    }  elseif(isset($_SESSION['gmact_id'])){
         $gmact_id = $_SESSION['gmact_id'];
    } else  {
       $_SESSION['error'] = "Missing gmact_id from QRGameRouter";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['gmact_id'] = $gmact_id;
    
      if (isset($_POST['gameactivity_id'])){
        $gameactivity_id = $_POST['gameactivity_id'];
    }  elseif(isset($_SESSION['gameactivity_id'])){
         $gameactivity_id = $_SESSION['gameactivity_id'];
    } else  {
       $_SESSION['error'] = "Missing gameactivity_id from QRGameRouter";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['gameactivity_id'] = $gameactivity_id;
    
      if (isset($_POST['name'])){
        $name = $_POST['name'];
    }  elseif(isset($_SESSION['name'])){
         $name = $_SESSION['name'];
    } else  {
       $_SESSION['error'] = "Missing name from QRGameRouter";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['name'] = $name;
    
      if (isset($_POST['problem_id'])){
        $problem_id = $_POST['problem_id'];
    }  elseif(isset($_SESSION['problem_id'])){
         $problem_id = $_SESSION['problem_id'];
    } else  {
       $_SESSION['error'] = "Missing problem_id from QRGameRouter";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['problem_id'] = $problem_id;
    
   
      if (isset($_POST['dex'])){
        $dex = $_POST['dex'];
    }  elseif(isset($_SESSION['dex'])){
         $dex = $_SESSION['dex'];
    } else  {
       $_SESSION['error'] = "Missing dex from QRGameRouter";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['dex'] = $dex;
   
    if (isset($_POST['iid'])){
        $iid = $_POST['iid'];
    }  elseif(isset($_SESSION['iid'])){
         $iid = $_SESSION['iid'];
    } else  {
       $_SESSION['error'] = "Missing iid from QRGameRouter";
	  header('Location: index.php');
	  return;   
    }
    $_SESSION['iid'] = $iid;

     if (isset($_POST['phase'])){
        $phase = $_POST['phase'];
    }  elseif(isset($_SESSION['phase'])){
         $phase = $_SESSION['phase'];
    } else  {
      
      // get it from the Gmact table
       $sql_stmt = "SELECT phase FROM Gmact WHERE `game_id`= :game_id ORDER BY gmact_id DESC LIMIT 1)";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt->execute(array(':game_id' => $game_id, ':iid' => $iid));
            $row = $stmt->fetch();
            $phase = $row['phase'];
      
    }
    $_SESSION['phase'] = $phase;
?>

	<!DOCTYPE html>
	<html lang = "en">
	<head>
	<meta Charset = "utf-8">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>
	<body>


<?php
// Dump all of the vars in session vars and send on the way
/* 
          $_SESSION['name']=$name;
           $_SESSION['pin']=$pin;
           $_SESSION['team_id']=$team_id;
           $_SESSION['problem_id']=$problem_id;
           $_SESSION['dex']=$dex;
           
            $_SESSION['phase']=$phase;
           $_SESSION['game_id']=$game_id;
            $_SESSION['gmact_id']=$gmact_id;
            $_SESSION['gameactivity_id']=$gameactivity_id;
            $_SESSION['iid']=$iid;
            
            
           if ($phase >=6 && $phase <=7){
               header( 'Location: QRGamePblmPost.php' ) ;
				return;
           } elseif ($phase == 4){
              header( 'Location: QRGameGetIn.php' ) ;
				return;
            } elseif ($phase == 5){
              header( 'Location: StopGame.php' ) ;
				return;
                  
           } else {
                header( 'Location: QRGamePblmPlan.php' ) ;
				return;
           }
            */
//echo ("phase = ".$phase);
?>
    
    <form  method = "POST" id = "the_form">
            <input type="hidden" name="name"  value="<?php echo ($name)?>" >
            <input type="hidden" name="pin" value="<?php echo ($pin)?>" >
            <input type="hidden" name="team_id"  value="<?php echo ($team_id)?>" >
            <input type="hidden" name="problem_id"  value="<?php echo ($problem_id)?>" >
            <input type="hidden" name="dex" value="<?php echo ($dex)?>" >
            <input type="hidden" name="game_id" value="<?php echo ($game_id)?>" >
            <input type="hidden" name="gmact_id" value="<?php echo ($gmact_id)?>" >
            <input type="hidden" name="gameactivity_id" value="<?php echo ($gameactivity_id)?>" >
            <input type="hidden" name="phase" id = "phase" value="<?php echo ($phase)?>" >
            <input type="hidden" name="iid" value="<?php echo ($iid)?>" >
       
         
        <p><input type = "submit" value="Go To Game" name = "submit_name" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>
    </form>

   <script>
			
            $(document).ready( function () {
               var phase = $("#phase").val(); 
               console.log ("phase "+phase);
                if (phase >=6 && phase <=7){
                    $("#the_form").attr('action', 'QRGamePblmPost.php');
                } else if(phase == 4){
                     $("#the_form").attr('action', 'QRGameGetIn.php');
                } else if (phase == 5){
                     $("#the_form").attr('action', 'StopGame.php');
                } else if(phase==8) {
                     $("#the_form").attr('action', 'QRGameShowResults.php');
                } else if(phase < 0 || phase >= 9){
                      $("#the_form").attr('action', 'index.php');
                } else {
                     $("#the_form").attr('action', 'QRGamePblmPlan.php');
                }
                $("#the_form").submit();    
             });
             
		</script>





