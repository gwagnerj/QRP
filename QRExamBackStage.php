<?php
	require_once "pdo.php";
	session_start();
  $individual_score= array();
  
   
?>
	 <!DOCTYPE html>
	<html lang = "en">
	<head>


	<link rel="icon" type="image/png" href="McKetta.png" />  
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta Charset = "utf-8">
	<title>QRExam BackStage</title>
  <!-- <meta http-equiv="refresh" content="10"> -->
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 
	
  
	<style type="text/css">

/* 

	div {
		width: 100%;
		height: 100%;
		border: 1px dotted black;
		overflow: auto;
	}
	


	body {
	   margin: 0;
	   overflow: hidden;
	}

	#request_prob{
		text-align: right;
		 color: blue;
		width: 100%;
		height: 100%;
		border: none;
		overflow: auto;
		padding:0px;
		margin:0px;
		font-size:70%;
		
	}


	#iframediv{
		position:relative;
		overflow:hidden;
		padding-top: 60%
	}
	#iframe1 {
		position:absolute;
		align:bottom;
		left: 0px;
		width: 100%;
		top: 0px;
		height: 100%;
	}

 



*/
.not_correct { 
    color:red;
  }
  .correct { 
    color:green;
  }
  .display_ans { 
    color:gold;
    font-weight:bold;
  }


	table.a {
		table-layout: auto;
		width: 80%;    
		}

/* 
		 .widget-1 { width:100px; } 
		  .widget-2 { width:100px; } 
		        
			.widget-0 { width:100px; } 
		 
		 
		 
	.column-filter-widget { float:left; padding: 20px; border : none; width:200px;}
	.column-filter-widget select { display: block; }
	.column-filter-widgets a.filter-term { display: block; text-decoration: none; padding-left: 10px; font-size: 90%; }
	.column-filter-widgets a.filter-term:hover { text-decoration: line-through !important; }
	.column-filter-widget-selected-terms { clear:left; }
		
	.half-line {
		line-height: 0.5em;
	}	
		 */
	</style>
							
		
		
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                    <link rel="stylesheet" type="text/css" href="DataTables-1.10.18/css/jquery.dataTables.css"/> 
                    <script type="text/javascript" src="DataTables-1.10.18/js/jquery.dataTables.js"></script>
                    <script type="text/javascript" charset="utf-8" src="DataTables-1.10.18/extras/js/ColumnFilterWidgets.js"></script>
                    
                
                    
                    
                      <meta name="viewport" content="width=device-width, initial-scale=1" /> 

                    <link rel="stylesheet" type="text/css" href="jquery.countdown.css"> 
                        
                    <script type="text/javascript" src="jquery.plugin.js"></script> 
                    <script type="text/javascript" src="jquery.countdown.js"></script>
                    
                    
                    
                    
                    
                    
				<!-- THis is from sparklines jquery plugin   -->	

				<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.js"></script>
		
	</head>

	<body>
	<header>
	<h2>Quick Response Exam Back Stage</h2>
    </header>
   
   
   

<?php
// data validation on the Post vaiables from QRGMaster.php

if(isset($_POST['eexamtime_id'])){
            $eexamtime_id = $_POST['eexamtime_id'];
        
        } elseif(isset($_GET['eexamtime_id'])){
            $eexamtime_id = $_GET['eexamtime_id'];
            
        
        } elseif(isset($_SESSION['eexamtime_id'])){
            $eexamtime_id = $_SESSION['eexamtime_id'];
        
        }else{
            $eexamtime_id = '';
            $_SESSION['error']= 'no examtime ID in post of session var for backstage';
   }


   if(isset($_POST['eexamnow_id'])){
    $eexamnow_id = $_POST['eexamnow_id'];

} elseif(isset($_GET['eexamnow_id'])){
    $eexamnow_id = $_GET['eexamnow_id'];
    

} elseif(isset($_SESSION['eexamnow_id'])){
    $eexamnow_id = $_SESSION['eexamnow_id'];

}else{
    $eexamnow_id = '';
    $_SESSION['error']= 'no eexamnow ID in post of session var for backstage';
}


	if ( isset($_SESSION['error']) ) {
		echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
		unset($_SESSION['error']);
	}
	if ( isset($_SESSION['success']) ) {
		echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
		unset($_SESSION['success']);
	}
  
  
// get the Eexamnow data for exams that are currently in progressive
$sql = 'SELECT * FROM Eexamnow WHERE eexamnow_id = :eexamnow_id';
  $stmt = $pdo->prepare($sql);         
  $stmt->execute(array(":eexamnow_id" => $eexamnow_id));
   $eexamnow_data = $stmt->fetch();   
   if ($eexamnow_data==false){
    $_SESSION['error'] = 'No Exam currently by that eexamnow_id in Eexamnow table';
       echo  "<script type='text/javascript'>";
      echo "window.close();";
      echo "</script>";
   } elseif ($eexamnow_data['globephase']>2){
        $_SESSION['error'] = 'Exam is closed and no longer active as indicated by the globalphase in eexamnow';
          echo  "<script type='text/javascript'>";
          echo "window.close();";
          echo "</script>";

   }



  $sql = 'SELECT Eregistration.student_id AS student_id,`dex`,`first_name`,`last_name`,`eregistration_id`,checker_only FROM Eregistration
   LEFT JOIN Student ON Student.student_id = Eregistration.student_id WHERE Eregistration.eexamnow_id = :eexamnow_id
   ORDER BY `last_name` ASC
   ';
  $stmt = $pdo->prepare($sql);         
  $stmt->execute(array(":eexamnow_id" => $eexamnow_id));
   $student_data = $stmt->fetchALL(PDO::FETCH_ASSOC);   

   // get the examtimedata to compute the scores_section
   $sql = 'SELECT * FROM Eexamtime WHERE eexamtime_id = :eexamtime_id';
      $stmt = $pdo->prepare($sql);         
      $stmt->execute(array(":eexamtime_id" => $eexamnow_data['eexamtime_id']));
      $eexamtime_data = $stmt->fetch();   
      $game_flag = $eexamtime_data['game_flag'];
      $gameboard_id = $eexamtime_data['gameboard_id'];
      $number_teams = $eexamtime_data['number_teams'];
      $currentclass_id = $eexamtime_data['currentclass_id'];
      $game_flag = $eexamtime_data['game_flag'];
      $game_flag_checked ='unchecked';
      if ($game_flag==1){$game_flag_checked='checked';}
     // echo ' $number_teams '.$number_teams;


  

// Insert or update the Team table putting in the (basically registering the teams in the teams table)

   if (isset($_POST['submit_team'])){
      for ( $i=1 ; $i <= $number_teams;$i++){
        // See if we have teams refined for this class
        $sql ='SELECT * FROM Team WHERE eexamnow_id = :eexamnow_id AND team_num = :team_num';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":eexamnow_id" => $eexamnow_data['eexamnow_id'], ":team_num" => $i));
        $team_data = $stmt->fetch();
         if ($team_data == false){
            $sql = "INSERT INTO `Team` (currentclass_id, eexamnow_id, team_num) VALUES (:currentclass_id,:eexamnow_id, :team_num) ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
              ":eexamnow_id" => $eexamnow_data['eexamnow_id'],
              ":currentclass_id" => $currentclass_id,
              ":team_num" => $i
              ));
              $sql = 'SELECT team_id AS team_ident FROM Team ORDER BY team_id DESC LIMIT 1';
              $stmt = $pdo->prepare($sql);
              $stmt->execute();
              $team_idss = $stmt->fetch();
              $team_ids[$i] = $team_idss['team_ident'];
           } else {
             $team_ids[$i] = $team_data['team_id'];
           }
       } 

          // team_ids is just an array containing the relationship between the team number and the team_id

               //now take care of connecting the students to the proper team

               // read data from the Post variable into associative arrays with the student_id as the key
          $dexs = $team_nums =  array();
          foreach ($student_data as $student_datum){
            $student_id = $student_datum['student_id'];
            if (isset($_POST['stu_'. $student_id])){
              $teams_flag = true;
              $student_on_teams['stu_'. $student_id] = $_POST['stu_'. $student_id];
              $params = explode('_', $_POST['stu_'. $student_id]);
              $team_nums[$student_id] = $params[1];
              $dexs[$student_id] = $params[3];
            } else{ $teams_flag = false;}
          }

          foreach($student_data as $student_datum){
            if($teams_flag){
              $student_id = $student_datum['student_id'];
              $dex = $dexs[$student_id];
              $team_num = $team_nums[$student_id];
              $team_id = $team_ids[$team_num];

              // see if we already have an entry - if so update it if not insert it (create it)
              $sql = 'SELECT team_id, team_num FROM TeamStudentConnect WHERE student_id = :student_id AND eexamnow_id = :eexamnow_id';
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array(
                ":student_id" => $student_id,
                ":eexamnow_id" => $eexamnow_id,
                ));
                $team_id2 = $stmt->fetch();
              if ($team_id2 == false){
                    $sql = 'INSERT INTO `TeamStudentConnect` (team_id, team_num, student_id,eexamnow_id, dex) VALUES (:team_id,:team_num,:student_id,:eexamnow_id,:dex)';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(
                      ":team_id" => $team_id,
                      ":team_num" => $team_num,
                      ":student_id" => $student_id,
                      ":eexamnow_id" => $eexamnow_id,
                      ":dex" => $dex
                      ));

                } else {
  /* 
                  echo 'student_id '.$student_id;
                  echo '  eexamnow_id '.$eexamnow_id;
                  echo '  team_id '.$team_id;
                  echo '  team_num '.$team_num;
  */
                  $sql = 'UPDATE `TeamStudentConnect` SET team_id = :team_id, team_num=:team_num, dex = :dex, team_cap = 0 WHERE  student_id = :student_id AND eexamnow_id = :eexamnow_id';
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute(array(
                    ":team_id" => $team_id,
                    ":team_num" => $team_num,
                    ":student_id" => $student_id,
                    ":eexamnow_id" => $eexamnow_id,
                    ":dex" => $dex
                    ));

              }
            }
          }
              
 } else {

    // get the student id / team number relationship if there is ones

    $sql = "SELECT * FROM TeamStudentConnect WHERE eexamnow_id = :eexamnow_id ORDER BY team_num ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ":eexamnow_id" => $eexamnow_id,
      ));
      $teamstudentconnect_data = $stmt->fetchALL(PDO::FETCH_ASSOC);   
      if ($teamstudentconnect_data != false) {
      //  var_dump($teamstudentconnect_data);
        foreach( $teamstudentconnect_data as $teamstudentconnect_datum){
           $team_nums[$teamstudentconnect_datum['student_id']] = $teamstudentconnect_datum['team_num'];
        }
      } else {
        $team_nums = '';
      }

 }
    
 if (isset($_POST['team_cap_assign'])){
 //  var_dump($_POST);
  
  for ($i=1;$i<=$number_teams;$i++){

    if (isset($_POST['team_'.$i])){
      $team_cap_stu_id[$i] = $_POST['team_'.$i];
      $sql = 'UPDATE `TeamStudentConnect` SET team_cap = 1  WHERE  student_id = :student_id AND eexamnow_id = :eexamnow_id AND team_num = :team_num';
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ":team_num" => $i,
        ":student_id" => $team_cap_stu_id[$i],
        ":eexamnow_id" => $eexamnow_id,
        ));


    }
  }
 }
// get the Team captains from the tables if they have been assigned so we can make the radio buttons setTickerSymbol

$sql ='SELECT student_id, team_num FROM TeamStudentConnect WHERE eexamnow_id = :eexamnow_id AND team_cap =1';
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
  ":eexamnow_id" => $eexamnow_id,
  ));
  $teamcap_data = $stmt->fetchALL(PDO::FETCH_ASSOC);   
if ($teamcap_data != false){
  foreach($teamcap_data as $teamcap_datum)
  $team_cap[$teamcap_datum['team_num']] = $teamcap_datum['student_id'];
  //  var_dump($team_cap);
 } else {
   $team_cap = '';
  }

  $chaos_team=array();
  // get the gameboards that are available for
  $sql = 'SELECT gameboard_id, game_board_title,board_catagory FROM GameBoard';
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $gameboard_data = $stmt->fetchALL(PDO::FETCH_ASSOC);  
  $options ='';
 
  foreach($gameboard_data as $gameboard_datum){
   // $options = $options.'<option value ='. $gameboard_datum["gameboard_id"].'>'.$gameboard_datum["game_board_title"].'</option>';
   if($gameboard_id == $gameboard_datum["gameboard_id"]) {
       $options = $options.'<option value ='. $gameboard_datum["gameboard_id"].' selected>'.$gameboard_datum["game_board_title"].'</option>';
      }
    else {
        $options = $options.'<option value ='. $gameboard_datum["gameboard_id"].'>'.$gameboard_datum["game_board_title"].'</option>'; 
      }
  } 

// build the student registration table and team student array _______________________________________________________________________________________________________________________________
  echo '<div id = team_assignments>';
   echo '<h2> Registered Students / Team Assignments</h2>';
      echo '<p>&nbsp;&nbsp; Number of Teams: <input type = "number" min = "1" max = "100" id = "team_num_update" name = "team_num_update" value ='.$number_teams.'>
       </input> <button name "update_num_teams" id = "update_num_teams"  style = "background-color:yellow;" value="update"> update </button>
      &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Game? <input type = "checkbox" id = "game_flag_box" name = "game_flag_box" '.$game_flag_checked.'> </input> 

      <select id="gameboard" name = "gameboard">
      <option value =0>Please Select</option>
      '.$options.'
      </select>

      </p>';

      // <button name "update_game_flag" id = "update_game_flag"  style = "background-color:yellow;" value="update"> update </button>

       
      echo '';
      echo '<form method = "POST" id = "team_assign">';
      echo ('<table id="table_registration" style = "text-align:center" class = "a" border="1" >'."\n");	
      echo("<thead>");
      echo("<tr>");
      echo("<th>");
      echo("</th>");
      echo("<th>");
      echo("</th>");
      echo('<th colspan ="'.$number_teams.'" >');
      echo ('<input type = "submit" style = "background-color:yellow;" id = "submit_team"  name = "submit_team" value = "Assign to Teams"></input>');

      echo("</th>");
      echo("</tr>");
      echo("<tr>");
      echo("<th>");
            echo('Name');
           echo("</th><th>");
            echo('dex - Checker Only');
            echo '</th>';
            for ($i=1;$i<=$number_teams;$i++){
              echo("<th>");
              echo $i;
              echo("</th>");
            }
            echo("</th></tr>\n");
        echo("</thead>");
          
                  

        echo("<tbody>");

          foreach ($student_data as $student_datum){
            $student_id = $student_datum['student_id'];
            $dex = $student_datum['dex'];
              echo('<tr>');
              echo('<td>');
              echo $student_datum['first_name'].' '.$student_datum['last_name'];
              echo('</td><td>');

              // this needs to be an input so I can change it on the fly
              echo '<input type = "number" id = "dex_change_stu_id_'.$student_id.'" name = "dex_change_stu_id_'.$student_id.'" min = 1 max = 200 value ='. $student_datum['dex'].'> </input>';
              if ($student_datum['checker_only']==1) {$checker_only="checked";} else {$checker_only="";}
             // echo 'checker_only '.$checker_only;
      //        echo '<input type = "checkbox" id = "checkbox_checker_stu_id_'.$student_id.'" name = "checkbox_checker_stu_id_'.$student_id.' value=" '.$student_datum['checker_only'].'" '.$checker_only.'> </input>';
              echo '<input type = "hidden" id = "reg_change_stu_id_'.$student_id.'" name = "reg_change_stu_id_'.$student_id.'" value ='. $student_datum['eregistration_id'].'> </input>';
              echo ('<input type = "button" style = "background-color:lightyellow;" class = "dex_change" id = "change_dex_id_'.$student_id.'"  name = "change_dex_id_'.$student_id.'" value = "Change"></input>');
              echo('</td>');

              echo '';
              for ($i=1;$i<=$number_teams;$i++){
                echo("<td>");
                  if (isset($team_nums[$student_id])){
                    if ($team_nums[$student_id]==$i){$check_flag = 'checked';}else{$check_flag ='';}
                  } else {$check_flag ='';}
                  echo '<input type = "radio"'.$check_flag.'  id = "stu_'.$student_id.'_team_'.$i.'"   class = "team_'.$i.'" name ="stu_'.$student_id.'" value = "team_'.$i.'_dex_'.$dex.'" ></input>';

                echo("</td>");
              }
              echo('</tr>');
          }
          echo('</tr>');
          echo("<th>");
          echo('Total on Team');
          echo("</th>");
          echo('<td>');
          echo('</td>');

          for ($i=1;$i<=$number_teams;$i++){
           // echo('<td id =  num_stu_team_'.$i.'>');
            echo('<td>');
            echo ('<span id = num_stu_team_'.$i.'></span>');
          echo('</td>');
          }


         echo("</tbody>");
   echo("</table>");
   
  echo' <input type="hidden" name="eexamtime_id"  value= '.$eexamtime_id.')';
  echo ' <input type="hidden" name="eexamnow_id"  value='.$eexamnow_id.')';

 echo '</form>';
echo '</div>';
  
   echo '<br>';
   echo '<br>';
   echo '<br>';

  echo '<h2> Individual Counts and Scores </h2>';

   echo ('<table id="table_individual_scores" style = "text-align:center" class = "a" border="1" >'."\n");	
      echo("<thead>");

            echo("<th>");
            echo('Name');
                echo("</th>");
                $sql = 'SELECT DISTINCT(problem_id), alias_num FROM Eactivity WHERE eexamnow_id = :eexamnow_id ORDER BY alias_num';
                $stmt = $pdo->prepare($sql);         
                $stmt->execute(array(":eexamnow_id" => $eexamnow_id));
                 $problem_ids = $stmt->fetchALL(PDO::FETCH_ASSOC);   
                 foreach ($problem_ids as $problem_id){
                    echo("<th>");
                    echo $problem_id['alias_num'].')  '. $problem_id['problem_id'];
                    echo("</th>");

                 }
                 echo("<th>");
                 echo('Total pts');
                     echo("</th>");


            echo("</th></tr>\n");
                  echo("</thead>");
            
        echo("<tbody>");

          foreach ($student_data as $student_datum){
            $student_assignment_total = 0;
              echo('<tr>');
              echo('<td>');
              echo $student_datum['first_name'].' '.$student_datum['last_name'];
              echo("</td>");
     
         foreach ($problem_ids as $problem_id){ 
         //  echo 'problem_ids '.$problem_id ['problem_id'];
                  $sql = 'SELECT  *   FROM Eactivity  WHERE eexamnow_id = :eexamnow_id AND student_id = :student_id  AND problem_id = :problem_id ORDER BY eactivity_id DESC LIMIT 1 ';

              //    $sql = 'SELECT DISTINCT * FROM (SELECT *,row_number() OVER (PARTITION BY problem_id ORDER BY eactivity_id DESC ) AS row_number FROM Eactivity ) AS ROWS  WHERE eexamnow_id = :eexamnow_id AND student_id = :student_id ORDER BY alias_num ';
                  $stmt = $pdo->prepare($sql);         
                  $stmt->execute(array(":eexamnow_id" => $eexamnow_id,
                  ':student_id' => $student_datum['student_id'],
                   ':problem_id' =>$problem_id['problem_id']));
                  $eactivity_data  = $stmt->fetchALL(PDO::FETCH_ASSOC);   
          //  var_dump($eactivity_data);

    
                  foreach ($eactivity_data as $eactivity_datum){
                      echo("<td>");
                      $problem_total = 0;
                      foreach(range('a','j') as $v){
                      
                          if($eactivity_datum['correct_'.$v] ==1){{echo'<span class = "correct">'. $v .')'.$eactivity_datum["wcount_".$v].' </span>';
                            $problem_total = $problem_total+ $eexamtime_data['perc_'.$v.'_'.$eactivity_datum['alias_num']];
                          }}
                          elseif($eactivity_datum['display_ans_'.$v] == 1) {echo'<span class = "display_ans">'. $v .')'.$eactivity_datum["wcount_".$v].' </span>';} 
                          elseif(is_null($eactivity_datum['correct_'.$v] )) {echo '__';} 
                          elseif($eactivity_datum['correct_'.$v] == 0) {echo'<span class = "not_correct">'. $v .')'.$eactivity_datum["wcount_".$v].' </span>';} 
                          else {echo'<span class = "correct">'. $v .')'.$eactivity_datum["wcount_".$v].'</span>';}
                      }
                      echo' '.$problem_total;
                      $student_assignment_total = $student_assignment_total + $problem_total*$eexamtime_data['perc_'.$eactivity_datum['alias_num']]/100;
                      $individual_score[$student_datum['student_id']] = round($student_assignment_total*10)/10;
                      echo("</td>");
                  }
              }

               echo("<td>");
               echo round($student_assignment_total*10)/10;
               echo("</td>");


              echo('</tr>');

          }

         echo("</tbody>");
   echo("</table>");
   
   
   
   
   echo '<br>';
   echo '<br>';
   echo '<br>';

   echo '<div id = team_score>';
      echo '<h2> Team Scores </h2>';
      echo '<form method = "POST" >';

      echo ('<table id="table_team_scores" style = "text-align:center" class = "a" border="1" >'."\n");	
          echo("<thead>");

                echo("<th>");
                echo('Team Name');
                    echo("</th><th>");
                echo('Members');
                echo ('</th>');
                echo ('<th>');
                echo ('dex');
                echo ('</th>');
                echo ('<th>');
                echo ('<input type = "submit" style = "background-color:yellow;" id = "team_cap_assign"  name = "team_cap_assign" value = "Assign Captains"></input>');
                         echo ('</th>');
                echo ('<th>');
                echo ('Individual Score');
                echo ('</th>');
                echo ('<th>');
               // echo ('Chaos Team');
                echo ('  <button id ="assign_chaos_team" style = "background-color:yellow;">Assign Chaos</botton> ');

                echo ('</th>');
                // echo ('<th>');
                // echo ('Team ID');
                // echo ('</th>');
                echo ('<th>');
                echo ('Team Cohesivity Inst.');
                echo ('</th>');
                echo ('<th>');
                echo ('Team Score');
                echo ('</th>');
                echo("</tr>\n");
                      echo("</thead>");
                
            echo("<tbody>");


            for ($i=1;$i<=$number_teams;$i++){
              $sql = "SELECT * FROM TeamStudentConnect  LEFT JOIN Student ON Student.student_id = TeamStudentConnect.student_id WHERE eexamnow_id = :eexamnow_id AND team_num = :team_num ORDER BY dex ASC";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array(
                ":eexamnow_id" => $eexamnow_id,
                ":team_num" => $i,
                ));
                $studentonteam_data = $stmt->fetchALL(PDO::FETCH_ASSOC);  

              
                if ( $studentonteam_data != false){$num_rows = count($studentonteam_data); } else {$num_rows = 1; }
              echo('<th rowspan ='. $num_rows.'>');
              echo ('Team '.$i);
            echo('</th>');
          //  echo 'num_stu on team: '.$num_stu_on_team;
            
            if ($studentonteam_data != false){
              

              $j = 1;


              $sql = "SELECT * FROM Team WHERE eexamnow_id = :eexamnow_id AND team_num = :team_num ";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array(
                ":eexamnow_id" => $eexamnow_id,
                ":team_num" => $i,
                ));
                $team_data = $stmt->fetch();  
                $chaos_team[$i]=$team_data['chaos_team'];




                // $team_weighted_ave = 0;
                // foreach($studentonteam_data as $studentonteam_datum){
                //   $student_id = $studentonteam_datum['student_id'];
                 
                  
                //   $team_weighted_ave = $team_weighted_ave + $individual_score[$student_id]/ count($studentonteam_data);  // this will be read from the Team Table and computed by scoreboard.php
                // }
                
                foreach($studentonteam_data as $studentonteam_datum){
                  $student_id = $studentonteam_datum['student_id'];
                  $team_id[$i] = $studentonteam_datum['team_id'];
                 // echo ' i '.$i;
                  echo('<td>');

                    echo ($studentonteam_datum['first_name'].' '.$studentonteam_datum['last_name']);
                    echo('</td>');
                    echo('<td>');
                    echo ($studentonteam_datum['dex']);
                    echo('</td>');
                    echo('<td>');
                    if (isset($team_cap[$i])){
                      if ($team_cap[$i]==$student_id){$check_flag = 'checked';}else{$check_flag ='';}
                    } else {$check_flag ='';}
                    echo '<input type = "radio" '.$check_flag.' id = "team_'.$i.'_stu_'.$student_id.'" name ="team_'.$i.'" value = "'.$student_id.'" ></input>';  // probably should not be an input box

                    echo('</td>');
                    echo('<td>');
                    echo ($individual_score[$student_id]);
                    if ($j==1){
                      echo('<td  rowspan ='. $num_rows.'>');
                      if (isset($chaos_team[$i])){
                        if ($chaos_team[$i]==1){$check_flag = 'checked';}else{$check_flag ='';}
                      } else {$check_flag ='';}
                      echo '<input type = "radio" '.$check_flag.' id = "chaos_team_'.$i.'" name ="chaos_team" value = "'.$team_id[$i].'" ></input>';  // probably should not be an input box
                      echo('</td>');
                   }
                //    if ($j==1){
                //     echo('<td  rowspan ='. $num_rows.'>');
                //     echo($team_id[$i]);
                //    echo('</td>');
                //  }
                  echo('</td>');
                    if ($j==1){
                      echo('<td  rowspan ='. $num_rows.'>');
                      echo ($team_data['team_cohesivity_inst'])/10;
                      echo('</td>');
                   }
               
                 if ($j==1){
                    echo('<td  rowspan ='. $num_rows.'>');
                    echo ($team_data['team_current_avg'])/10;
                    echo('</td>');
                   }
                    echo('<tr>');
                    // if($j!=1){
                    //   echo('<td>');
                    //   echo('</td>');
                    //   echo('<td>');
                    //   echo('</td>');
                    // }
                  $j++;
                }

            }
            echo('</tr>');
            }

         echo("</tbody>");
       echo("</table>");
   
       echo' <input type="hidden" name="eexamtime_id"  value= '.$eexamtime_id.')';
       echo ' <input type="hidden" name="eexamnow_id"  value='.$eexamnow_id.')';
     
      echo '</form>';
            
   echo '</div>';

 


  $pass = array(
    'number_teams' =>$number_teams,
    'currentclass_id' => $currentclass_id,
  );
  echo '<script>';
  echo 'var pass = ' . json_encode($pass) . ';';
  echo '</script>';

	function sigFig($value, $digits)
            {
                if ($value == 0) {
                    $decimalPlaces = $digits - 1;
                } elseif ($value < 0) {
                    $decimalPlaces = $digits - floor(log10($value * -1)) - 1;
                } else {
                    $decimalPlaces = $digits - floor(log10($value)) - 1;
                }

                $answer = round($value, $decimalPlaces);
                return $answer;
            }
       



       if ( isset($_SESSION['error']) ) {
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) ) {
			echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
			unset($_SESSION['success']);
		}       
            
            
            
?>
       
       <form  method="POST" action = "" id = "refresh_page">
       <p style="font-size:10px;"></p>
           page auto-refreshes every 30s
          <p><input type = "submit" name = "refresh" value="Refresh Page" id="refrsh_id" size="2" style = "width: 30%; background-color: blue; color: white"/> &nbsp &nbsp </p>  
          <input type="hidden" name="eexamtime_id"  value=<?php echo($eexamtime_id);?> >
          <input type="hidden" name="eexamnow_id"  value=<?php echo($eexamnow_id);?> >
       </form>  

       <form  method="POST" action = "scoreboard.php" id = "scoreboard" target = "_blank">
      <p style="font-size:50px;"></p>
      <p><input type="hidden" name="eexamtime_id" id="eexamtime_id" value=<?php echo($eexamtime_id);?> ></p>
      <p><input type="hidden" name="eexamnow_id" id="eexamnow_id" value=<?php echo($eexamnow_id);?> ></p>
      <p><input type="hidden" name="number_teams" id="number_teams" value=<?php echo($number_teams);?> ></p>
      <p><input type="hidden" name="studentonteam_data" id="studentonteam_data" value=<?php echo(implode(',',$studentonteam_data));?> ></p>
      <p><input type="hidden" name="individual_score" id="individual_score" value=<?php echo(implode(',',$individual_score));?> ></p>
      <p><input type="hidden" name="team_cap" id="team_cap" value=<?php echo(json_encode($team_cap));?> ></p>
      <p><input type = "submit" name = "scoreboard_submit" value="Show Score Board" id="scoreboard_submit" size="2" style = "width: 30%; background-color: green; color: white"/>  </p>  
  
  </form>

        <p style="font-size:75px;"></p>   
        <form method="POST" >
             <!-- <p><input type="hidden" name="eexamtime_id" id="eexamtime_id" value=<?php echo($eexamtime_id);?> ></p> -->
             <p><input type = "submit" name = "close" value="Exit - Close Window" id="close_id" size="2" style = "width: 40%; background-color: black; color: white"/> &nbsp &nbsp </p>
        </form>

	<script>


	$(document).ready( function () {	

    
     const number_teams = pass['number_teams']; 
     const currentclass_id = pass['currentclass_id']; 
    

        $('#team_assignments.input:radio').click(function(){
        // console.log('click');

          //console.log('num_teams: '+number_teams);
          let j = 0;
          for(i=1;i<=number_teams;i++){

          let sel_class = 'team_'+i;
          let num_teams = $('input:radio.'+sel_class+':checked').length;
          //console.log ('num teams: '+num_teams);
          let sel = '#num_stu_team_'+i;
          //console.log ('sel: '+sel);
              $(sel).text(num_teams);
          }

          // add the total for each team to the
        

      });
      
   // try using native JS

      document.getElementById('update_num_teams').addEventListener('click',(e)=>{
        e.preventDefault();
        const team_num_update = document.getElementById('team_num_update').value;
        console.log(`number of teams is ${team_num_update}`);
        const eexamtime_id = document.getElementById('eexamtime_id').value;
        console.log(`eexamtime_id ${eexamtime_id}`);
        $.ajax({   // this looks updates the eregistration with the new dex number
										url: 'update_number_teams.php',
										method: 'post',
						
									data: {eexamtime_id:eexamtime_id,number_teams:team_num_update}
									}).done(function(){
                   });
                   window.location.reload(1);

      })

    let game_flag_box = document.getElementById('game_flag_box').checked;
    console.log(`the game_flag_box is ${game_flag_box}`);

    if (game_flag_box==true){
      document.getElementById("gameboard").style.visibility = "visible";
        } else {
          document.getElementById("gameboard").style.display = "none";
        }
      


      document.getElementById('game_flag_box').addEventListener('change',(e)=>{call_update_game(e)
      });

      document.getElementById('gameboard').addEventListener('change',(e)=>{call_update_game(e)
      });



       function call_update_game(){ 
         //e.preventDefault();
       game_flag_box = document.getElementById('game_flag_box').checked;
        console.log(`game_flag_box is ${game_flag_box}`);
        const eexamtime_id = document.getElementById('eexamtime_id').value;
        const gameboard_id = document.getElementById('gameboard').value;
  //      console.log(`eexamtime_id ${eexamtime_id}`);
        console.log(`gameboard_id ${gameboard_id}`);

         let game_flag =1;
         if (game_flag_box==true){
            game_flag =1;
        } else {
            game_flag =0;
        }
        console.log(`game_flag is ${game_flag}`);
       
        $.ajax({  
										url: 'update_game_flag.php',
										method: 'post',
						
									data: {eexamtime_id:eexamtime_id,game_flag:game_flag,gameboard_id:gameboard_id}
									}).done(function(){
                   });
                  window.location.reload(1);

      }


      document.getElementById('assign_chaos_team').addEventListener('click',(e)=>{
        e.preventDefault();
        let chaos_team_number = 0;
      let chaos_team_num = document.getElementsByName('chaos_team');
      const eexamtime_id = document.getElementById('eexamtime_id').value;
      const eexamnow_id = document.getElementById('eexamnow_id').value;
     
      for (k=0; k<chaos_team_num.length; k++){
        if (chaos_team_num[k].checked) {
          chaos_team_number = chaos_team_num[k].value;
        }
      }
    
        console.log (chaos_team_number);
        console.log (eexamtime_id);
        console.log (eexamnow_id);
            $.ajax({   
										url: 'update_chaos_team.php',
										method: 'post',
									data: {chaos_team_number:chaos_team_number,eexamtime_id:eexamtime_id,eexamnow_id:eexamnow_id}
									})
                  .done(function(){
                   })
                   ;
                  window.location.reload(1);
      
    })
/* 

      document.getElementById('assign_chaos_team').addEventListener('click',(e)=>{
        e.preventDefault();
        const chaos_team_num = document.getElementById('team_num_update').value;
        console.log(`number of teams is ${team_num_update}`);
        const eexamtime_id = document.getElementById('eexamtime_id').value;
        console.log(`eexamtime_id ${eexamtime_id}`);
        $.ajax({   // this looks updates the eregistration with the new dex number
										url: 'update_number_teams.php',
										method: 'post',
						
									data: {eexamtime_id:eexamtime_id,number_teams:team_num_update}
									}).done(function(){
                   });
                   window.location.reload(1);

      })
 */

      $('.dex_change').on('click',function(event){  // this is to update the dex on a student

          const  stu_id_string = $(this).attr('id');
        //  console.log('stu_id_string: '+stu_id_string);
          // extract the student id from the button that was video_clip_checked

           const stu_id_arr= stu_id_string.split('_');
           const stu_id = stu_id_arr[3];
          console.log('stu_id: '+stu_id);
          // get the new dex for this student
          const new_dex = $('#dex_change_stu_id_'+stu_id).val();
        //  console.log('new_dex: '+new_dex);
           const eregistration_id = $('#reg_change_stu_id_'+stu_id).val();
        //   console.log('eregistration_id: '+eregistration_id);
        const checker_only_string = $('#checkbox_checker_stu_id_'+stu_id).prop('checked');
          console.log('checker_only_string: '+checker_only_string);
         
         let checker_only = 0;
          if (checker_only_string){checker_only =1;}
        

          // now upate the eregsitration table using ajax
        	$.ajax({   // this looks updates the eregistration with the new dex number
										url: 'update_registration_dex.php',
										method: 'post',
						
									data: {eregistration_id:eregistration_id,dex:new_dex,checker_only:checker_only}
									}).done(function(){
                   });
       });
  
  /* 
       setInterval(function(){ 
         
        document.getElementById("refresh_page").submit();


          }, 1000);
   */
  
   });
         
   setTimeout(function(){
      window.location.reload(1);
    }, 30000);
    
		
	</script>

	
	</body>
	</html>