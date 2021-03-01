<?php
	require_once "pdo.php";
	session_start();

  $range_limit = 25;  //Goes to scoreing team cohesivity
  $stdev_limit = 10;

    
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


?>

<!DOCTYPE html>
     <html lang="en">
     <head>
     <link rel="icon" type="image/png" href="McKetta.png" />  
         <meta charset="UTF-8">
        <!--    <meta http-equiv="refresh" content="10">  -->
         <meta http-equiv="X-UA-Compatible" content="IE=edge">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>Score Borad</title>
     </head>
     <body>
     <header>
         <h2>Quick Response Score Board</h2>
         </header>  
     



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


            for ($i=1;$i<=$number_teams;$i++){
            
              $max_ind_score [$i] = 0.0;  // initializing these
              $min_ind_score[$i] = 100.0;
              $team_cohesivity[$i] =1000;
              $cumm[$i] =0;

              $sql = "SELECT * FROM TeamStudentConnect  LEFT JOIN Student ON Student.student_id = TeamStudentConnect.student_id WHERE eexamnow_id = :eexamnow_id AND team_num = :team_num ORDER BY dex ASC";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array(
                ":eexamnow_id" => $eexamnow_id,
                ":team_num" => $i,
                ));
                $studentonteam_data = $stmt->fetchALL(PDO::FETCH_ASSOC);  
//var_dump($studentonteam_data);

                if ( $studentonteam_data != false){$num_rows = count($studentonteam_data); } else {$num_rows = 1; }
              echo('<th rowspan ='. $num_rows.'>');
              echo ('Team '.$i);
            echo('</th>');
         //   echo 'num_stu on team: '.$num_stu_on_team;
            
            if ($studentonteam_data != false){

              $j = 1;
                $team_weighted_ave[$i] = 0;
                foreach($studentonteam_data as $studentonteam_datum){
                  $student_id = $studentonteam_datum['student_id'];
                  
                  // get the individual scores



                  foreach ($student_data as $student_datum){
                    $student_assignment_total = 0;
                      //  echo('<tr>');
                      //  echo('<td>');
                      //  echo $student_datum['first_name'].' '.$student_datum['last_name'];
                      //  echo("</td>");
             
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
                            //  echo("<td>");
                              $problem_total = 0;
                              foreach(range('a','j') as $v){
                              
                                  if($eactivity_datum['correct_'.$v] ==1){
                                    {//echo'<span class = "correct">'. $v .')'.$eactivity_datum["wcount_".$v].' </span>';
                                    $problem_total = $problem_total+ $eexamtime_data['perc_'.$v.'_'.$eactivity_datum['alias_num']];
                                  }
                                }
                                  elseif($eactivity_datum['display_ans_'.$v] == 1) {
                                    //echo'<span class = "display_ans">'. $v .')'.$eactivity_datum["wcount_".$v].' </span>';
                                  } 
                                  elseif(is_null($eactivity_datum['correct_'.$v] )) {
                                   // echo '__';
                                  } 
                                  elseif($eactivity_datum['correct_'.$v] == 0) {
                                   // echo'<span class = "not_correct">'. $v .')'.$eactivity_datum["wcount_".$v].' </span>';
                                  } 
                                  else {echo'<span class = "correct">'. $v .')'.$eactivity_datum["wcount_".$v].'</span>';}
                              }
                            //  echo' '.$problem_total;
                            
                              $student_assignment_total = $student_assignment_total + $problem_total*$eexamtime_data['perc_'.$eactivity_datum['alias_num']]/100;
                              $individual_score[$student_datum['student_id']] = round($student_assignment_total*10)/10;


                              if($individual_score[$student_datum['student_id']]>$max_ind_score[$i]){$max_ind_score[$i] = $individual_score[$student_datum['student_id']];}
                              if($individual_score[$student_datum['student_id']]<$min_ind_score[$i]){$min_ind_score[$i] = $individual_score[$student_datum['student_id']];}
            


                            //  echo("</td>");
                          }
                      }
        
                      //  echo("<td>");
                      //  echo round($student_assignment_total*10)/10;
                      //  echo("</td>");
        
        
                      // echo('</tr>');
        
                  }

                  $team_weighted_ave[$i] = $team_weighted_ave[$i] + $individual_score[$student_id]/ count($studentonteam_data);




                }

                // Cmpute the instantaneous team_cohesivity on the team
                $num_stu_on_team[$i] = 0;
                foreach ($student_data as $student_datum){
                  $dev = $team_weighted_ave[$i]-($individual_score[$student_datum['student_id']]);
                  $cumm[$i] = $cumm[$i]+$dev*$dev;
                  $num_stu_on_team[$i]++;
                }
               // echo $cumm[$i];
               $range[$i]= $max_ind_score[$i]-$min_ind_score[$i];
               $sdev[$i] = pow($cumm[$i]/$num_stu_on_team[$i],0.5);
              //  echo ' range '.$range[$i]; 
              //  echo ' max '.$max_ind_score[$i];
              //  echo ' min '.$min_ind_score[$i];
              //  echo ' sdev '.$sdev[$i];
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
              //      $team_score = $team_data['team_score'];
                    if ((!is_numeric($team_cohesivity_avg))){$team_cohesivity_avg = 0.0;}
                  //  if ((!is_numeric($team_score))){$team_score = 0.0;}
                    $counter = $team_data['counter']+1;
                    $team_cohesivity_avg = ($team_cohesivity_avg*($counter-1) + $team_cohesivity[$i])/$counter;
                    $team_cohesivity_avg = round($team_cohesivity_avg);
               //     echo $team_cohesivity_avg;
                    $team_score = $team_weighted_ave[$i]*$team_cohesivity_avg/1000;
                    
                  //  echo ' elapse_time1 '. $elapsed_time1;
                
                 $sql = "UPDATE `Team` 
                 SET team_range = :team_range, team_sd = :team_sd, team_cohesivity_inst = :team_cohesivity_inst, `counter` = :countr, team_cohesivity_avg = :team_cohesivity_avg,team_score = :team_score
                 WHERE team_num = :team_num AND eexamnow_id = :eexamnow_id";
                         $stmt = $pdo->prepare($sql);
                         $stmt -> execute(array(
                             ':team_num' => $i,
                             ":eexamnow_id" => $eexamnow_id,
                             ':team_range' => $range[$i],
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
                 // echo ' i '.$i;
                  echo('<td>');

                    echo $j;
                    echo('</td>');
                    // echo('<td>');
                    // echo ($studentonteam_datum['dex']);
                    // echo('</td>');
                    // echo('<td>');
                    // if (isset($team_cap[$i])){
                    //   if ($team_cap[$i]==$student_id){$check_flag = 'checked';}else{$check_flag ='';}
                    // } else {$check_flag ='';}
                    // echo '<input type = "radio" '.$check_flag.' id = "team_'.$i.'_stu_'.$student_id.'" name ="team_'.$i.'" value = "'.$student_id.'" ></input>';
                    echo('</td>');
                    echo('<td>');
                    echo ($individual_score[$student_id]);
                    
                    echo('</td>');
                    if ($j==1){
                      echo('<td  rowspan ='. $num_rows.'>');
                      echo ($team_cohesivity[$i]/10);
                      echo('</td>');
                   }
                   if ($j==1){
                    echo('<td  rowspan ='. $num_rows.'>');
                    echo ($team_cohesivity_avg/10);
                    echo('</td>');
                 }
                 if ($j==1){
                    echo('<td  rowspan ='. $num_rows.'>');
                    echo (round($team_score*10)/10);
                    echo('</td>');
                   }
                    echo('<tr>');
                    if($j!=1){
                      echo('<td>');
                      echo('</td>');
                      echo('<td>');
                      echo('</td>');
                    }
                  $j++;
                }

            }
            echo('</tr>');
            }

         echo("</tbody>");
       echo("</table>");



?>





</body>
</html>