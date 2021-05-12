<?php
	require_once "pdo.php";
	session_start();

  $range_limit = 25;  //Goes to scoreing team cohesivity
  $stdev_limit = 10;
  $pre_qr_weight = 25;  // percent of the score made up by the QRgame

//  unset ($_SESSION['rando']);

// this is the value that will be used to pick the political environment
    if(isset($_SESSION['rando'])){
      $rando = $_SESSION['rando'];

    }else{
      $rando = rand(1,1000);
      $_SESSION['rando'] = $rando;
      $_SESSION['success']= 'rando is set';
    }


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
  if(isset($_POST['pre_qr_weight'])){
    $pre_qr_weight = $_POST['pre_qr_weight'];
    
    } elseif(isset($_GET['pre_qr_weight'])){
    $pre_qr_weight = $_GET['pre_qr_weight'];
    
    
    } elseif(isset($_SESSION['pre_qr_weight'])){
    $pre_qr_weight = $_SESSION['pre_qr_weight'];
    
    }else{
    $pre_qr_weight = 25;
    }
  

?>

<!DOCTYPE html>
     <html lang="en">
     <head>
     <link rel="icon" type="image/png" href="McKetta.png" />  
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">

     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">        <!--    <meta http-equiv="refresh" content="10">
        <meta http-equiv="refresh" content="5; URL=scoreboard.php">  -->
         <meta http-equiv="X-UA-Compatible" content="IE=edge">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>Score Board</title>
  <style>
          #page-wrap {
            width: 800px;
            margin: 0 auto;
       }
        img.animated-gif{
          width: 40px;
          height: auto;   
      }
      td {
        height: 20px;
        /* width:0.8rem; */
        /* vertical-align: bottom; */
      }
      #table_team_results_before{
        width: 120%;
      }
   
      /* #table_team_results_after tr  {
        /* width: 110%; */
        height: 300%;
      } */
      /* 
      .ah_row{ 
        display: none;
      } */
      tr.first_place {
        font-size:2rem;
        font-weight:bold;
        color:red;


      }


  </style>

     </head>
     <body>
     <div id = "page-wrap">

     <header>
         <!-- <h1>Quick Response Score Board</h1> -->
         </header>  

         <!-- <input type = "hidden" id = "rando" name="rando" value = "<?php echo ($rando); ?>"></input> -->
     



<?php

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
      $number_teams = $eexamtime_data['number_teams'];
      $currentclass_id = $eexamtime_data['currentclass_id'];
     // echo ' $number_teams '.$number_teams;

// get the individual scores_section


echo("</th>");
$sql = 'SELECT DISTINCT(problem_id), alias_num FROM Eactivity WHERE eexamnow_id = :eexamnow_id ORDER BY alias_num';
$stmt = $pdo->prepare($sql);         
$stmt->execute(array(":eexamnow_id" => $eexamnow_id));
 $problem_ids = $stmt->fetchALL(PDO::FETCH_ASSOC);   

 echo '<h2 style ="text-align:center" > Quick Response Score Board - Game Code <span  style = "color:red">'.$eexamnow_id.' </span></h2>';


     echo '<div id = team_score>';

     
      echo '<form method = "POST" >';
      echo '<h3>  Pre QR Weight: <input type = "number" min = 0 max = 100 id = "pre_qr_weight" name = "pre_qr_weight" value ="'.$pre_qr_weight.'"></input>&nbsp;<input type = "submit" value = "Submit"> </input></h3>';
      echo ('<table id="table_team_scores" cellpadding ="0" style = "text-align:center"  border="1" >'."\n");	
          echo("<thead>");

                echo("<th>");
                echo('Team Num');
                echo("</th>");
                echo("<th>");
                echo('Team Name');
                echo("</th>");
                echo("<th>");
                echo('Member');
                echo ('</th>');
                echo("<th>");
                echo('Pre_QR');
                echo ('</th>');
                // echo ('<th>');
                // echo ('dex'); 
              //  echo ('</th>');
                 echo ('<th>');
                // echo ('Team Captain');
                // echo ('</th>');
                // echo ('<th>');
                echo ('Individual Score');
                echo ('</th>');
                echo ('<th>');
                echo ('Team pre_QR');
                echo ('</th>');
                echo ('<th>');
                echo ('Current Cohesivity');
                echo ('</th>');
                echo ('<th>');
                echo ('Average Cohesivity');
                echo ('</th>');
                echo ('<th>');
                echo ('Team Score');
                echo ('</th>');
                echo("</tr>\n");
                      echo("</thead>");
                
            echo("<tbody>");

  

           // Compute who got the high and low kahoot score from their input initialize the vaiables
            $max_kahoot_score =1;
            $min_kahoot_score = 100000;

            //Get all of the Kahoot Scores

            $sql = "SELECT kahoot_points FROM Eregistration WHERE eexamnow_id = :eexamnow_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
              ":eexamnow_id" => $eexamnow_id,
              ));
              $eregistration_kahoot_scores = $stmt->fetchAll();
     //         var_dump ($eregistration_kahoot_scores);
            
              foreach ($eregistration_kahoot_scores as $kahoot_scores){
                if($kahoot_scores['kahoot_points'] > $max_kahoot_score){$max_kahoot_score = $kahoot_scores['kahoot_points'];}
                if($kahoot_scores['kahoot_points'] < $min_kahoot_score){$min_kahoot_score = $kahoot_scores['kahoot_points'];}
              }

// $i is the team number  big loop on the data

            for ($i=1;$i<=$number_teams;$i++){

  

            
              $max_ind_score [$i] = 0.0;  // initializing these max score for the team
              $min_ind_score[$i] = 100.0;
              $team_cohesivity[$i] =1000;
              $team_kahoot[$i] = 0;
              $team_kahoot_cumm[$i] =0;
              $cumm[$i] =0;

              $sql = "SELECT `team_name` FROM Team  WHERE team_num = :team_num AND eexamnow_id = :eexamnow_id";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array(
                ":eexamnow_id" => $eexamnow_id,
                ":team_num" => $i,
                ));

                $teams_name  = $stmt->fetch();   
                if ($teams_name){
                    $team_name = $teams_name['team_name'];
                } else {
                  $team_name ='';
                }


              $sql = "SELECT * FROM TeamStudentConnect 
               LEFT JOIN Student ON Student.student_id = TeamStudentConnect.student_id 
               LEFT JOIN Eregistration ON Eregistration.student_id = Student.student_id AND Eregistration.eexamnow_id = TeamStudentConnect.eexamnow_id 
               WHERE TeamStudentConnect.eexamnow_id = :eexamnow_id AND team_num = :team_num ORDER BY TeamStudentConnect.dex ASC";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array(
                ":eexamnow_id" => $eexamnow_id,
                ":team_num" => $i,
                ));
                $studentonteam_data = $stmt->fetchALL(PDO::FETCH_ASSOC);  
             //     var_dump($studentonteam_data);

      


                if ( $studentonteam_data != false){$num_rows = count($studentonteam_data); } else {$num_rows = 1; }
              echo('<tr id = "team_'.$i.'"> <th  rowspan ='. $num_rows.'>');
              echo ('Team '.$i);
            echo('</th>');
            echo ('<th  rowspan ='. $num_rows.'>');
              echo ($team_name);
            echo('</th>');

         //   echo 'num_stu on team: '.$num_stu_on_team;
            
            if ($studentonteam_data != false){

              $j = 1; // student number 
                $team_weighted_ave[$i] = 0;
                foreach($studentonteam_data as $studentonteam_datum){
                  $student_id = $studentonteam_datum['student_id'];
                  $game_name = $studentonteam_datum['game_name'];
                  $kahoot_points = $studentonteam_datum['kahoot_points']/$max_kahoot_score*100;
                  $team_kahoot_cumm[$i]=$team_kahoot_cumm[$i]+$kahoot_points;



          //    var_dump($student_data);
                    $student_assignment_total = 0;
             
                 foreach ($problem_ids as $problem_id){ 
                          $sql = 'SELECT  *   FROM Eactivity  WHERE eexamnow_id = :eexamnow_id AND student_id = :student_id  AND problem_id = :problem_id ORDER BY eactivity_id DESC LIMIT 1 ';
                          $stmt = $pdo->prepare($sql);         
                          $stmt->execute(array(":eexamnow_id" => $eexamnow_id,
                          ':student_id' => $student_id,
                           ':problem_id' =>$problem_id['problem_id']));
                          $eactivity_data  = $stmt->fetchALL(PDO::FETCH_ASSOC);   
                  //  var_dump($eactivity_data);
        
            
                          foreach ($eactivity_data as $eactivity_datum){
                              $problem_total = 0;
                              foreach(range('a','j') as $v){
                              
                                  if($eactivity_datum['correct_'.$v] ==1){
                                      $problem_total = $problem_total+ $eexamtime_data['perc_'.$v.'_'.$eactivity_datum['alias_num']];
                                  }
                                  elseif($eactivity_datum['display_ans_'.$v] == 1) {
                                  } 
                                  elseif(is_null($eactivity_datum['correct_'.$v] )) {
                                   // echo '__';
                                  } 
                                  elseif($eactivity_datum['correct_'.$v] == 0) {
                                  } 
                                  else {echo'<span class = "correct">'. $v .')'.$eactivity_datum["wcount_".$v].'</span>';}
                              }
                          }

                          $student_assignment_total = $student_assignment_total + $problem_total*$eexamtime_data['perc_'.$eactivity_datum['alias_num']]/100;
                          $individual_score[$student_id] = round($student_assignment_total*10)/10;

                            // getting the max and min score on the team

                      }

                      if($individual_score[$student_id]>$max_ind_score[$i]){$max_ind_score[$i] = $individual_score[$student_id];}
                      if($individual_score[$student_id]<$min_ind_score[$i]){$min_ind_score[$i] = $individual_score[$student_id];}

        
                      

                      $team_weighted_ave[$i] = $team_weighted_ave[$i] + $individual_score[$student_id]/ count($studentonteam_data);
                      $team_kahoot[$i] =$team_kahoot_cumm[$i]/ count($studentonteam_data);

                }
                 // var_dump($min_ind_score);
                // Compute the instantaneous team_cohesivity on the team
                $num_stu_on_team[$i] = 0;
            foreach($studentonteam_data as $studentonteam_datum){
              $student_id = $studentonteam_datum['student_id'];
              $dev = $team_weighted_ave[$i]-($individual_score[$student_id]);
                  $cumm[$i] = $cumm[$i]+$dev*$dev;
                  $num_stu_on_team[$i]++;
                }
               // echo $cumm[$i];
               $range[$i]= $max_ind_score[$i]-$min_ind_score[$i];
               $sdev[$i] = pow($cumm[$i]/$num_stu_on_team[$i],0.5);
               
                  if($range[$i]>=$range_limit){
                    $team_cohesivity[$i] =  $team_cohesivity[$i]-500;
                  }
                  if($sdev[$i]>=$stdev_limit){
                    $team_cohesivity[$i] =  $team_cohesivity[$i]-500;
                  }
                  // echo '<br>';
                  // echo ($team_cohesivity[$i]+0)/10;


                  $sql = 'SELECT *, TIMESTAMPDIFF(SECOND,created_at,updated_at) AS diff_in_secs 
                  FROM Team
                  WHERE team_num = :team_num AND eexamnow_id = :eexamnow_id';
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute(array(
                    ":eexamnow_id" => $eexamnow_id,
                    ":team_num" => $i,
                    ));
                    $team_data  = $stmt->fetch();   
                    $elapsed_time1 = $team_data['diff_in_secs'];
                    $team_cohesivity_avg = $team_data['team_cohesivity_avg'];
                    if ((!is_numeric($team_cohesivity_avg))){$team_cohesivity_avg = 0.0;}
                    $counter = $team_data['counter']+1;
                    $team_cohesivity_avg = ($team_cohesivity_avg*($counter-1) + $team_cohesivity[$i])/$counter;
                    $team_cohesivity_avg = round($team_cohesivity_avg);
                    $team_score = (($team_weighted_ave[$i]*$team_cohesivity_avg/1000)*(100-$pre_qr_weight)+$team_kahoot[$i]*$pre_qr_weight)/100 ;
                    
                  //  echo ' elapse_time1 '. $elapsed_time1;
                
                 $sql = "UPDATE `Team` 
                 SET team_range = :team_range, team_sd = :team_sd, team_cohesivity_inst = :team_cohesivity_inst, 
                 `counter` = :countr, team_cohesivity_avg = :team_cohesivity_avg,team_score = :team_score, team_current_avg = :team_current_avg
                 WHERE team_num = :team_num AND eexamnow_id = :eexamnow_id";
                         $stmt = $pdo->prepare($sql);
                         $stmt -> execute(array(
                             ':team_num' => $i,
                             ":eexamnow_id" => $eexamnow_id,
                             ':team_range' => $range[$i],
                             ':team_current_avg' => $team_weighted_ave[$i]*10,
                             ':team_sd' => $sdev[$i],
                             ':team_cohesivity_inst' => $team_cohesivity[$i],
                             ':team_cohesivity_avg' => $team_cohesivity_avg,
                             ':team_score' => $team_score,
                             ':countr' => $counter,
                         ));
// could use the time difference instead of the counter if you were afraid of differences in time between the interval
                  // $sql = 'SELECT TIMESTAMPDIFF(SECOND,created_at,updated_at) AS diff_in_secs 
                  //       FROM Team
                  //       WHERE team_num = :team_num AND eexamnow_id = :eexamnow_id';
                  //       $stmt = $pdo->prepare($sql);
                  //       $stmt->execute(array(
                  //         ":eexamnow_id" => $eexamnow_id,
                  //         ":team_num" => $i,
                  //         ));
                  //         $team_data  = $stmt->fetch();   
                  //         $elapsed_time2 = $team_data['diff_in_secs'];
                  //     //    echo ' elapse_time2 '. $elapsed_time2;
                  //         echo ' elapse_time diff '. $elapsed_time2 - $elapsed_time1;

                    
                foreach($studentonteam_data as $studentonteam_datum){
                  $student_id = $studentonteam_datum['student_id'];
                  $game_name = $studentonteam_datum['game_name'];
                  $kahoot_points = $studentonteam_datum['kahoot_points'];
                  
                  echo('<td>');

                    echo $game_name;
                    echo('</td>');
                    echo('<td>');

                    echo (round($kahoot_points/$max_kahoot_score*1000)/10);
                    echo('</td>');
                    echo('</td>');
                    echo('<td>');
                    echo ($individual_score[$student_id]);
                    
                    echo('</td>');
                    if ($j==1){
                      echo('<td  rowspan ='. $num_rows.'>');
                      echo (round($team_kahoot[$i]*10)/10);
                      echo('</td>');
                   }
                   if ($j==1){
                    $cohesivity_background= "";
                    if ($team_cohesivity[$i] < 950){ $cohesivity_background='yellow';} 
                    if ($team_cohesivity[$i] < 400){ $cohesivity_background='pink';} 
                     echo('<td style = "background-color:'.$cohesivity_background.';" rowspan ='. $num_rows.'>');
                     echo ($team_cohesivity[$i]/10);
                    echo('</td>');
                 }
                 if ($j==1){
                   $cohesivity_background= "";
                   if ($team_cohesivity_avg < 950){ $cohesivity_background='lightyellow';} 
                   if ($team_cohesivity_avg < 600){ $cohesivity_background='pink';} 
                    echo('<td style = "background-color:'.$cohesivity_background.';" rowspan ='. $num_rows.'>');
                    echo ($team_cohesivity_avg/10);
                    // echo '&nbsp;';
                    // echo ($team_cohesivity_avg);
                    echo('</td>');
                 }
                 if ($j==1){
                    echo('<td  rowspan ='. $num_rows.'>');
                    echo  (round($team_score*10)/10);
                    echo('</td>');
                   }
                  echo('<tr >');
                  $j++;
                }

            }
            echo('</tr>');
        }

      echo("</tbody>");
    echo("</table>");

    echo '<input type="hidden" name="eexamnow_id" value ="'.$eexamnow_id.'"></input>';
    echo '<input type="hidden" name="eexamtime_id" value ="'.$eexamtime_id.'"></input>';
    echo("</form>");

        // get how many political cards there are for this game_name

      echo "<br>";
      echo '<button id = "show_results_button" class="btn btn-info" >Show Team Selections</button>';
      echo "<br>";

      //-----------------------------------------------------------------------------show results before hits table --------------------------------------------------------------------
      echo "<br>";
        echo '<div id = "show_results" class="table">';
            echo '<table id="table_team_results_before" style = "text-align:bottom; " class = "table"  border="1"> ';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Team </th>';
            echo '<th>Score</th>';
            // echo '<th colspan="3" id = >SCORE</th>';
            // echo '<th>Env</th>';
            // echo '<th>Soc</th>';
            echo '<th >Blocks %</th>';
            // echo '<th>E Block</th>';
            // echo '<th>S Block</th>';
            echo '<th>Hits % </th>';
            // echo '<th>E Hit</th>';
            // echo '<th>S Hit</th>';
            echo '<th>Pol Points</th>';
            echo '<th>Political Pick</th>';
            echo '<tr>';
            echo '</thead>';
            echo '<tbody>';
    // set some initial values for computing the political environmental_hits
            

        // echo ('rando is '.$rando);

        $pol_picks = array();
        $fin_hit = $env_hit = $soc_hit =0;
        $chaos_team_num = 0;            // chaos team will be zero if there is no chaos team

            for ($i=1;$i<=$number_teams;$i++){
                $sql = "SELECT * FROM Team LEFT JOIN GamePolitical ON GamePolitical.gamepolitical_id = Team.gamepolitical_id WHERE eexamnow_id = :eexamnow_id AND team_num = :team_num ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                  ":eexamnow_id" => $eexamnow_id,
                  ":team_num" => $i,
                  ));
                  $team_datum = $stmt->fetch();  
                
                  // this tallys the votes for each of the political envioronment
                  if (isset($pol_picks[$team_datum["gamepolitical_id"]])){
                    $pol_picks[$team_datum["gamepolitical_id"]]+=$team_datum["pol_points"];
                  } else {
                    $pol_picks[$team_datum["gamepolitical_id"]]=$team_datum["pol_points"];
                  }

                  $fin_hit +=  $team_datum['fin_hit'];          
                  $env_hit +=  $team_datum['env_hit'];          
                  $soc_hit +=  $team_datum['soc_hit'];     
                  
                  $fin_score[$i]= $team_datum['fin_score'];
                  $env_score[$i]= $team_datum['env_score'];
                  $soc_score[$i]= $team_datum['soc_score'];
                  $fin_block[$i]= $team_datum['fin_block'];
                  $env_block[$i]= $team_datum['env_block'];
                  $soc_block[$i]= $team_datum['soc_block'];
                  $team_id[$i]= $team_datum['team_id'];
                  $pol_points[$i] = $team_datum['pol_points'];
                  $political_image_file[$i] = $team_datum['political_image_file'];
                  $gamepolitical_id [$i] = $team_datum['gamepolitical_id'];
                  $game_political_title[$i]= $team_datum['game_political_title'];
           
                  if ($team_datum['chaos_team']==1){
                    $chaos_team_num = $i;
                  }
            }
                $fin_score_max = max($fin_score);
                $env_score_max = max($env_score);
                $soc_score_max = max($soc_score);
                $grand_score_max = max($fin_score_max,$env_score_max,$soc_score_max);
                if ( $grand_score_max==0){$grand_score_max =1;}
                $fin_block_max = max($fin_block);
                $env_block_max = max($env_block);
                $soc_block_max = max($soc_block);
                $grand_block_max = max($fin_block_max,$env_block_max,$soc_block_max,1);
           

            for ($i=1;$i<=$number_teams;$i++){
              echo '<span id = "number_teams" style = "display:none;">'.$number_teams.'</span>';
                  echo ('<tr id = "bh_row_'.$i.'" class = "ah_row" style = "display:none;">');
                  echo ('<th >');
                 if ($chaos_team_num !=$i) {echo 'Team '.$i;} else {echo 'Chaos-Team '.$i;} 
                  echo ('</th>');
                  echo ('<td>');


                      echo '<table  class="charts-css column hide-data show-labels data-spacing-1">';
                     echo '<thead>
                      <tr>
                      </tr>
                    </thead>';
                      echo '<tbody>';
                     if( $fin_score[$i]>1){$FS = $fin_score[$i];} else {$FS = "";} 
                     if( $env_score[$i]>1){$ES = $env_score[$i];} else {$ES = "";} 
                     if( $soc_score[$i]>1){$SS = $soc_score[$i];} else {$SS = "";} 
                     if( $fin_block[$i]>1){$FB = $fin_block[$i];} else {$FB = "";} 
                     if( $env_block[$i]>1){$EB = $env_block[$i];} else {$EB = "";} 
                     if( $soc_block[$i]>1){$SB = $soc_block[$i];} else {$SB = "";} 
                     if($i == $chaos_team_num && $fin_hit>1){$FH = $fin_hit;} else {$FH = "";} 
                     if($i == $chaos_team_num &&  $env_hit>1){$EH = $env_hit;} else {$EH = "";} 
                     if($i == $chaos_team_num &&  $soc_hit>1){$SH = $soc_hit;} else {$SH = "";} 

                          echo ('<tr><th class="gx_label" scope = "row">'. $FS .'</th><td style="--size: calc( '. $fin_score[$i]/$grand_score_max.' ) ;--color:#892816; vertical-align:bottom;"> ');
                          // echo $fin_score[$i];
                          echo ('</td></tr>');
                          echo ('<tr><th class="gx_label" scope = "row">'.$ES .'</th><td style="--size: calc( '. $env_score[$i]/$grand_score_max.'); --color:#4c884a; vertical-align:bottom;"> ');
                          // echo $env_score[$i];
                          echo ('</td></tr>');
                          echo ('<tr><th class="gx_label" scope = "row">'.$SS.'</th><td  style="--size: calc(  '. $soc_score[$i]/$grand_score_max.');  --color:#6e4a88; vertical-align:bottom;">');
                          // echo $soc_score[$i];
                          echo ('</td></tr>');
                  echo '</tbody></table>';

                  echo ('<td>');
                    echo '<table  class="charts-css column hide-data show-labels data-spacing-1">';
                        echo '<tbody>';
                            echo ('<tr><th class="gx_label" scope = "row">'.$FB.'</th><td style="--size: calc( '. $fin_block[$i].'/100 ) ;--color:#892816; vertical-align:bottom;"> ');
                            // echo $fin_score[$i];
                            echo ('</td></tr>');
                            echo ('<tr><th scope = "row">'. $EB.'</th><td style="--size: calc( '. $env_block[$i].'/100 ); --color:#4c884a; vertical-align:bottom;"> ');
                            // echo $env_score[$i];
                            echo ('</td></tr>');
                            echo ('<tr><th scope = "row">'. $SB.'</th><td  style="--size: calc(  '. $soc_block[$i].'/100 );  --color:#6e4a88; vertical-align:bottom;">');
                            // echo $soc_score[$i];
                            echo ('</td></tr>');
                      echo '</tbody></table>';

                  echo ('</td>');

              
                  echo ('<td>');
                      echo '<table  class="charts-css column hide-data show-labels data-spacing-1">';
                      echo '<tbody>';
                          echo ('<tr><th class="gx_label" scope = "row">'.$FH.'</th><td style="--size: calc( '. $FH.'/100 ) ;--color:#892816; vertical-align:bottom;"> ');
                          // echo $fin_score[$i];
                          echo ('</td></tr>');
                          echo ('<tr><th scope = "row">'. $EH.'</th><td style="--size: calc( '. $EH.'/100 ); --color:#4c884a; vertical-align:bottom;"> ');
                          // echo $env_score[$i];
                          echo ('</td></tr>');
                          echo ('<tr><th scope = "row">'. $SH.'</th><td  style="--size: calc(  '. $SH.'/100 );  --color:#6e4a88; vertical-align:bottom;">');
                          // echo $soc_score[$i];
                          echo ('</td></tr>');
                      echo '</tbody></table>';
                  echo ('<td style = "font-size:2rem; align:center; font-weight:bold; padding: 30px 0px 30px 50px;" >');
                  echo $pol_points[$i];
                  echo ('</td>');
                  echo ('<td>');
                  echo  '<img src ="'. $political_image_file[$i].'">';
                  echo ('</td>');
                  echo '</tr>';
            }

            
            echo '</tbody>';
          echo '</table>';
// echo 'fin hit'.$fin_hit;
// echo 'env hit'.$env_hit;
// echo 'soc hit'.$soc_hit;

            echo "<br>";

            echo '<button id = "after_hits_button" class="btn btn-info" >Results After Hits</button>';
            echo "<br>";
         echo '</div>';
         echo '<div id = after_hits>';
        //  echo' <iframe src="https://giphy.com/embed/YlHNdso9AwzpjntD1g" width="480" height="480" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><p><a href="https://giphy.com/gifs/cartoonhangover-cute-cartoon-hangover-YlHNdso9AwzpjntD1g">via GIPHY</a></p>';
        //  echo' <iframe src="https://giphy.com/embed/3ohfFH3gJpepwS5DEY" width="480" height="270" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><p><a href="https://giphy.com/gifs/filmeditor-christmas-movies-bill-murray-3ohfFH3gJpepwS5DEY">via GIPHY</a></p>';

         echo "<br>";
// ----------------------------------------------------------------------------------show results after hits --------------------------------------------------------------------------------------------------------

        echo ' <table id="table_team_results_after" style = "text-align:center" class = "table" border="1"> ';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Team Number</th>';
        echo '<th>Score After Hits</th>';
        // echo '<th>Env</th>';
        // echo '<th>Soc</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // compute the hits if there is no chaos team for now just using average of the team scores to get the hits
          if ($chaos_team_num == 0){
            $fin_hit = array_sum($fin_score) / count($fin_score);
            $env_hit = array_sum($env_score) / count($env_score);
            $soc_hit = array_sum($soc_score) / count($soc_score);

          }


        $fin_tot_block = $env_tot_block = $soc_tot_block =0;

        for ($i=1;$i<=$number_teams;$i++){
            $fin_lost[$i] = $fin_score[$i]*((min(100,100-$fin_block[$i]))/100)*$fin_hit/100;
            $fin_tot_block +=$fin_lost[$i];
            $fin_score[$i] = $fin_score[$i]-$fin_lost[$i];
            $env_lost[$i] = $env_score[$i]*((min(100,100-$env_block[$i]))/100)*$env_hit/100;
      //  echo ' env lost '.$env_lost[$i];
            $env_tot_block +=$env_lost[$i];
            $env_score[$i] = $env_score[$i]-$env_lost[$i];
      //  echo ' env score '.$env_score[$i];

            $soc_lost[$i] = $soc_score[$i]*((min(100,100-$soc_block[$i]))/100)*$soc_hit/100;
            $soc_tot_block +=$soc_lost[$i];
            $soc_score[$i] = $soc_score[$i]-$soc_lost[$i];
           

                if ($i!=$chaos_team_num){
                  echo '<tr class = "ah_row" id = "ah_row_'.$i.'" style=" height:100px; display:none;">';  // id on the row so I can display them one at a time
                  echo ('<th>');
                  echo 'Team '.$i;
                  echo ('</th>');
                  echo ('<td>');
                    // echo $fin_score[$i];

                            echo '<table  class="charts-css column multiple stacked hide-data show-labels data-spacing-1">';
                            echo '<tbody>';
                            if( $fin_score[$i]>1){$FS = $fin_score[$i];} else {$FS = "";} 
                            if( $env_score[$i]>1){$ES = $env_score[$i];} else {$ES = "";} 
                            if( $soc_score[$i]>1){$SS = $soc_score[$i];} else {$SS = "";} 
                            if( $fin_block[$i]>1){$FB = $fin_block[$i];} else {$FB = "";} 
                            if( $env_block[$i]>1){$EB = $env_block[$i];} else {$EB = "";} 
                            if( $soc_block[$i]>1){$SB = $soc_block[$i];} else {$SB = "";} 
                            // if($i == $chaos_team_num && $fin_hit>1){$FH = $fin_hit;} else {$FH = "";} 
                            // if($i == $chaos_team_num &&  $env_hit>1){$EH = $env_hit;} else {$EH = "";} 
                            // if($i == $chaos_team_num &&  $soc_hit>1){$SH = $soc_hit;} else {$SH = "";} 

                                echo ('<tr><th class="gx_label" scope = "row">'. $FS .'</th><td style="--size: calc('. $fin_score[$i]/$grand_score_max.') ;--color:#892816; vertical-align:bottom;"> ');
                                // echo ('<th class="gx_label" scope = "row"></th><td style="--size: calc('.$fin_lost[$i]/$grand_score_max.') ;--color:red; vertical-align:bottom;"> ');
                                // echo ('<td style="--size: calc('.$fin_lost[$i]/$grand_score_max.') ;--color:red; vertical-align:bottom;"> ');
                                echo ('<td style="--size: calc('.$fin_lost[$i]/$grand_score_max.') ;--color:#FE000B; vertical-align:bottom;"> ');
                                // echo $fin_score[$i];
                                echo ('</td></tr>');
                                echo ('<tr><th class="gx_label" scope = "row">'.$ES .'</th><td style="--size: calc( '. $env_score[$i]/$grand_score_max.'); --color:#4c884a; vertical-align:bottom;"> ');
                                echo ('</th><td style="--size: calc( '. $env_lost[$i]/$grand_score_max.'); --color:#FE000B; vertical-align:bottom;"> ');
  //                              echo ('</th><td style="--size: calc( '. $env_lost[$i]/$grand_score_max.'); --color:#eefae1; vertical-align:bottom;"> ');
                                // echo $env_score[$i];
                                echo ('</td></tr>');
                                echo ('<tr><th class="gx_label" scope = "row">'.$SS.'</th><td  style="--size: calc(  '. $soc_score[$i]/$grand_score_max.');  --color:#6e4a88; vertical-align:bottom;">');
                                echo ('</th><td  style="--size: calc(  '. $soc_lost[$i]/$grand_score_max.');  --color:#FE000B; vertical-align:bottom;">');
                                // echo $soc_score[$i];
                                echo ('</td></tr>');
                        echo '</tbody></table>';

                    echo ('</td>');
                echo'</tr>';
              } 

          }
          // now calculate the chaos teams values - base it on how the average number of points they took away in each catagory

          $chaos_team_factor = 2;
          if ($chaos_team_num != 0){$denominator = $number_teams-1;} else {$denominator = $number_teams;}

          $fin_score[$chaos_team_num] = $fin_tot_block/($denominator)*$chaos_team_factor;
          $env_score[$chaos_team_num] = $env_tot_block/($denominator)*$chaos_team_factor;
          $soc_score[$chaos_team_num] = $soc_tot_block/($denominator)*$chaos_team_factor;
          if ($chaos_team_num != 0){
              echo '<tr class = "ah_row" id = "ah_row_'.$i.'" style=" height:100px; display:none;">';
              echo ('<th>');
              echo 'Chaos - Team '. $chaos_team_num;
              echo ('</th>');
              echo ('<td>');

              echo '<table  class="charts-css column hide-data show-labels data-spacing-1">';
              echo '<tbody>';
                           if(  $fin_score[$chaos_team_num]>1){$FH =   $fin_score[$chaos_team_num];} else {$FH = "";} 
                            if(  $env_score[$chaos_team_num] >1){$EH =  $env_score[$chaos_team_num] ;} else {$EH = "";} 
                            if(  $soc_score[$chaos_team_num] >1){$SH =  $soc_score[$chaos_team_num] ;} else {$SH = "";} 
                            $max_hit = max(  $fin_score[$chaos_team_num], $env_score[$chaos_team_num] , $soc_score[$chaos_team_num],1 );

                            echo ('<tr><th class="gx_label" scope = "row">'. $FH .'</th><td style="--size: calc('.   $fin_score[$chaos_team_num]/$max_hit.') ;--color:#892816; vertical-align:bottom;"> ');
                            // echo $fin_score[$i];
                            echo ('</td></tr>');
                            echo ('<tr><th class="gx_label" scope = "row">'.$EH .'</th><td style="--size: calc('.  $env_score[$chaos_team_num] /$max_hit.'); --color:#4c884a; vertical-align:bottom;"> ');
                            // echo $env_score[$i];
                            echo ('</td></tr>');
                            echo ('<tr><th class="gx_label" scope = "row">'.$SH.'</th><td  style="--size: calc( '.  $soc_score[$chaos_team_num] /$max_hit.');  --color:#6e4a88; vertical-align:bottom;">');
                            // echo $soc_score[$i];
                            echo ('</td></tr>');
                    echo '</tbody></table>';
                echo ('</td>');
                echo'</tr>';
          }

        echo '</tbody>';
        echo '</table>';


        echo '<audio id = "drum_roll" src = ""></audio>';  // add the applause sound when button is pushed


        echo "<br>";
        echo '<button id = "political_environment_button" class="btn btn-info" >Political Environment</button>';
        echo "<br>";
        echo "<br>";



        echo '</div>';

          echo '<div id = "political_environment">';


         // computer points after hits are
         //
        //  var_dump($pol_picks);
         $pol_picks_keys = array_keys($pol_picks);
         // now need to select the political environment
         $tot_points = 0;
        //  $i = 0;
         foreach($pol_picks as $pol_pick){
          // var_dump($pol_pick);
          // echo ('<br>');
          // echo ($pol_pick);
          $tot_points+=$pol_pick;
          // echo ('<br>');
          // echo ($pol_picks_keys[$i]);
          // $i++;
        }
        // echo '<br> total points are '.$tot_points;
        $breaks[0] = 0;
        $i=1;
        if ($tot_points ==0){$tot_points=1;}
        $gamepolitical_id = $fin_wt = $env_wt = $soc_wt =0;  // just initializing some vars

        foreach($pol_picks as $pol_pick){
          $breaks[$i]= $breaks[$i-1]+intdiv(($pol_pick+0)*1000,$tot_points);
          if($rando<$breaks[$i] && $rando >= $breaks[$i-1]){$gamepolitical_id = $pol_picks_keys[$i-1];}
          $i=$i+1;

        }

       $sql = "SELECT * FROM GamePolitical WHERE gamepolitical_id = :gamepolitical_id";
       $stmt = $pdo->prepare($sql);
       $stmt->execute(array(
         ":gamepolitical_id" => $gamepolitical_id,
         ));
         $gamepolitical_datum = $stmt->fetch();  

         if($gamepolitical_datum){
         $game_political_title = $gamepolitical_datum['game_political_title'];
        //  echo 'title '.$game_political_title;
         $fin_wt = $gamepolitical_datum['fin_wt'];
         $env_wt = $gamepolitical_datum['env_wt'];
         $soc_wt = $gamepolitical_datum['soc_wt'];
         $political_image_file = $gamepolitical_datum['political_image_file'];

         echo '<h2>Political Climate - '. $game_political_title.'</h2>';
         echo '<img src ="'. $political_image_file.'"> ';
         }
         echo "<br>";
         echo "<br>";
         echo '<button id = "final_results_button" class="btn btn-info" >Final Results</button>';

         echo '<audio id = "applause" src = ""></audio>';  // add the applause sound when button is pushed
         echo "<br>";
         echo '</div>';
         echo '<div id = "final_results">';
         echo "<br>";
         echo '  <table id="table_team_results_after" style = "text-align:center" class = "table" border="1"> ';
         echo '<thead>';
         echo '<tr>';
         echo '<th>Team Number</th>';
         echo '<th>Final Score</th>';
         echo '<tr>';
         echo '</thead>';
         echo '<tbody>';
         $final_scores_assoc = array();
         for ($i=1;$i<=$number_teams;$i++){
           $fin_score[$i] = $fin_score[$i]*$fin_wt/100;
           $env_score[$i] = $env_score[$i]*$env_wt/100;
           $soc_score[$i] = $soc_score[$i]*$soc_wt/100;



            $final_scores[$i] = $fin_score[$i]+$env_score[$i]+$soc_score[$i];
            $final_scores_assoc["team_".$i] =  $final_scores[$i] ;

          }
            $final_score_max = max($final_scores);

            asort($final_scores_assoc);
            $final_scores_assoc = array_reverse($final_scores_assoc);
            $first_place = key(array_slice( $final_scores_assoc, 0, 1));
            $second_place =  key(array_slice( $final_scores_assoc, 1, 2));
            $third_place =  key(array_slice( $final_scores_assoc, 2, 3));
            $first_place_team_num = substr($first_place, strrpos( $first_place, '_') + 1);
            $second_place_team_num = substr($second_place, strrpos( $second_place, '_') + 1);
            $third_place_team_num = substr($third_place, strrpos( $third_place, '_') + 1);
            // echo ' first place team is '.$first_place_team_num;

            // var_dump(  $first_place_team_num);
            // var_dump( $final_scores_assoc);
      
         for ($i=1;$i<=$number_teams;$i++){

            // echo '<tr>';

            if ($i == $first_place_team_num){echo '<tr style="background-color:blue; color:gold; font-size:2rem;" class = "first_place">';}
            elseif ($i == $second_place_team_num){echo '<tr style="background-color:red; color:silver; font-size:1.6rem;" class = "second_place">';}
            elseif ($i == $third_place_team_num){echo '<tr style="background-color:white; color:bronze; font-size:1.2rem;" class = "third_place">';}
            else{ echo '<tr>';}

            echo ('<th>');
            echo ' Team '. $i;
            if ($i == $first_place_team_num){echo ' - 1st Place ';}
            if ($i == $second_place_team_num){echo ' - 2nd Place ';}
            if ($i == $third_place_team_num){echo ' - 3rd Place ';}

            echo ('</th>');
            echo ('<th>');
            $round_final_score = round($final_scores[$i]*100)/100;
            echo '<span class = "final_scores">'.$round_final_score.'</span>';
            echo ('</th>');

            echo'</tr>';


            $sql = 'UPDATE Team SET 
                -- fin_score = :fin_score, 
                -- env_score = :env_score,
                -- soc_score = :soc_score,
                final_score = :final_score
              WHERE
                team_id = :team_id
              ';
            			$stmt = $pdo->prepare($sql);	
                  $stmt->execute(array(
                    ":team_id"   =>  $team_id[$i], 
                    ":final_score" => intval($final_scores[$i])
                  ));
         
           }
         echo '</tbody>';
       echo '</table>';
 
    //   echo '</div>';


        // var_dump($final_scores);
         
         
   

?>


    <br>
    <button id = "save_final_results" class="btn btn-info" >Save Results and Close</button>
    <br>
    </div>
    <div id = "save_and_exit">
    <br>



<!-- </div> -->
</div>

<script>
      // console.log('window.location.reload');

    var update_time = 5000;
    var cycle = 1;
  
    const number_teams = document.getElementById("number_teams").innerText;
    const number_teams_plus = +number_teams +1;
    console.log (`number of teams is ${number_teams}`);
    document.getElementById("show_results").style.display="none";
    document.getElementById("after_hits").style.display="none";
    document.getElementById("political_environment").style.display="none";
    document.getElementById("final_results").style.display="none";

         document.getElementById("show_results_button").addEventListener("click", () => {
          showFirstSection(show_results);
            // show the rows one at a time for dramatic effect
            for (i=1;i<=number_teams_plus;i++){
             (function(i){
              setTimeout(function(){
                if(document.getElementById("bh_row_"+i)){document.getElementById("bh_row_"+i).style.display="";}
                const drum_roll = document.getElementById("drum_roll")
                const drum_src = '/QRP/gamesounds/bbc_electronic_07014150.mp3'
                drum_roll.src = drum_src;
                // drum_roll.play();


                }, 2000 * i);
              }(i));
            }
         })

         document.getElementById("after_hits_button").addEventListener("click", () => {
          showNextSection(after_hits);
          // show the rows one at a time for dramatic effect
            for (i=1;i<=number_teams_plus;i++){
             (function(i){
              setTimeout(function(){
              
                if(document.getElementById("ah_row_"+i)){document.getElementById("ah_row_"+i).style.display="";   
                  const drum_roll = document.getElementById("drum_roll")
                const drum_src = '/QRP/gamesounds/8d82b5_Ba_Dum_Tss_Sound_Effect.mp3'
                drum_roll.src = drum_src;
                drum_roll.play();
              }
                }, 2500 * i);
              }(i));
            }
         })
         

         document.getElementById("political_environment_button").addEventListener("click", () => {
          showNextSection(political_environment);
          const drum_roll = document.getElementById("drum_roll")
            const drum_src = '/QRP/gamesounds/8d82b5_Ba_Dum_Tss_Sound_Effect.mp3'
            drum_roll.src = drum_src;
            drum_roll.play();

         })
         document.getElementById("final_results_button").addEventListener("click", () => {
          const drum_roll = document.getElementById("drum_roll")
            const drum_src = '/QRP/gamesounds/8d82b5_Ba_Dum_Tss_Sound_Effect.mp3'
            drum_roll.src = drum_src;
            drum_roll.play();

          const applause = document.getElementById("applause")
            const applause_src = '/QRP/gamesounds/cheer3.mp3'
            applause.src = applause_src;
            applause.play();

          showNextSection(final_results);
         })
         document.getElementById("save_final_results").addEventListener("click", () => {
         
         // this will make an ajax call to store the data in the Team Table for the 
         
         
          console.log("click");
         })



         function showNextSection(section){
            const x = document.getElementById(section.id);
            if (x.style.display === "none") {
              x.style.display = "block";
            } else {
              x.style.display = "none";
            }
         }

         function showFirstSection(section){
            const x = document.getElementById(section.id);
            if (x.style.display === "none") {
             window['cycle'] = 0;
              x.style.display = "block";
            } else {
              x.style.display = "none";
              window['cycle'] = 1;
            }
         }

         function computeFinalResults(){
            console.log("show final results table");
          }

          setTimeout(function(){

         if(cycle == 1){ window.location.reload(1)};
          // console.log('window.location.reload');
        }, update_time);



</script>
</body>
</html>