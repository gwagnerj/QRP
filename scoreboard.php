<?php
	require_once "pdo.php";
	session_start();
    
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
      $number_teams = $eexamtime_data['number_teams'];
      $currentclass_id = $eexamtime_data['currentclass_id'];
     // echo ' $number_teams '.$number_teams;
?>
     <!DOCTYPE html>
     <html lang="en">
     <head>
     <link rel="icon" type="image/png" href="McKetta.png" />  
         <meta charset="UTF-8">
           <!-- <meta http-equiv="refresh" content="10">  -->
         <meta http-equiv="X-UA-Compatible" content="IE=edge">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>Score Borad</title>
     </head>
     <body>
     <header>
         <h2>Quick Response Score Board</h2>
         </header>  
     

 <?php



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
                echo ('</th>');
                // echo ('<th>');
                // echo ('Team Captain');
                // echo ('</th>');
                // echo ('<th>');
                echo ('Individual Score');
                echo ('</th>');
                echo ('<th>');
                echo ('Team Cohesivity');
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
                $team_weighted_ave = 0;
                foreach($studentonteam_data as $studentonteam_datum){
                  $student_id = $studentonteam_datum['student_id'];
                  
                  $team_weighted_ave = $team_weighted_ave + $individual_score[$student_id]/ count($studentonteam_data);
                }
                
                foreach($studentonteam_data as $studentonteam_datum){
                  $student_id = $studentonteam_datum['student_id'];
                 // echo ' i '.$i;
                  echo('<td>');

                    echo ' Team Member '.$j;
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
                      echo (1);
                      echo('</td>');
                   }
                   if ($j==1){
                    echo('<td  rowspan ='. $num_rows.'>');
                    echo (round($team_weighted_ave*10)/10);
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