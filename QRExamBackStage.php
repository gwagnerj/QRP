<?php
	require_once "pdo.php";
	session_start();
    
   
?>
	 <!DOCTYPE html>
	<html lang = "en">
	<head>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta Charset = "utf-8">
	<title>QRExam BackStage</title>
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
                    <!-- <meta http-equiv="refresh" content="10"/> -->
                
                    
                    
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



  $sql = 'SELECT Eregistration.student_id AS student_id,`dex`,`first_name`,`last_name` FROM Eregistration
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
              $sql = 'SELECT LAST_INSERT_ID AS team_ident FROM Team';
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

          foreach ($student_data as $student_datum){
            $student_id = $student_datum['student_id'];
            if (isset($_POST['stu_'. $student_id])){
              $student_on_teams['stu_'. $student_id] = $_POST['stu_'. $student_id];
              $params = explode('_', $_POST['stu_'. $student_id]);
              $team_nums[$student_id] = $params[1];
              $dexs[$student_id] = $params[3];
            }
          }

          foreach($student_data as $student_datum){
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
                $sql = 'UPDATE `TeamStudentConnect` SET team_id = :team_id, team_num=:team_num, dex = :dex WHERE  student_id = :student_id AND eexamnow_id = :eexamnow_id';
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
    







// build the student registration table and team student array _______________________________________________________________________________________________________________________________

   echo '<h2> Registered Students / Team Assignments</h2>';
echo '<form method = "POST" id = "team_assign">';

   echo ('<table id="table_registration" style = "text-align:center" class = "a" border="1" >'."\n");	
      echo("<thead>");
      echo("<tr>");
      echo("<th>");
      echo("</th>");
      echo("<th>");
      echo("</th>");
      echo('<th colspan ="'.$number_teams.'" >');
      echo('Team Number');
      echo("</th>");
      echo("</tr>");
      echo("<tr>");
      echo("<th>");
            echo('Name');
           echo("</th><th>");
            echo('dex');
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
              echo $student_datum['dex'];
              echo('</td>');

              echo '';
              for ($i=1;$i<=$number_teams;$i++){
                echo("<td>");
                //  if (isset($team_nums)){
                    if ($team_nums[$student_id]==$i){$check_flag = 'checked';}else{$check_flag ='';}
               //   } else {$check_flag ='';}
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
   echo ('<input type = "submit" style = "background-color:yellow;" id = "submit_team"  name = "submit_team" value = "Assign to Teams"></input>');
   
  echo' <input type="hidden" name="eexamtime_id"  value= '.$eexamtime_id.')';
  echo ' <input type="hidden" name="eexamnow_id"  value='.$eexamnow_id.')';

 echo '</form>';

  
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
              $sql = 'SELECT * FROM Eactivity WHERE eexamnow_id = :eexamnow_id AND student_id = :student_id ORDER BY alias_num';
              $stmt = $pdo->prepare($sql);         
              $stmt->execute(array(":eexamnow_id" => $eexamnow_id,':student_id' => $student_datum['student_id']));
               $eactivity_data  = $stmt->fetchALL(PDO::FETCH_ASSOC);   
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
                  echo("</td>");
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

   echo '<h2> Team Scores </h2>';

   echo ('<table id="table_team_scores" style = "text-align:center" class = "a" border="1" >'."\n");	
      echo("<thead>");

            echo("<th>");
            echo('Team Name');
                echo("</th><th>");
            echo('Members');
            echo ('</th>');
            echo ('<th>');
            echo ('dex');
            echo("</th></tr>\n");
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



          // echo('<td id =  num_stu_team_'.$i.'>');
           echo('<th rowspan ='. $num_rows.'>');
           echo ('Team '.$i);
         echo('</th>');
      //  echo 'num_stu on team: '.$num_stu_on_team;
        
            if ($studentonteam_data != false){

              $j = 1;
                foreach($studentonteam_data as $studentonteam_datum){
                  echo('<td>');

                    echo ($studentonteam_datum['first_name'].' '.$studentonteam_datum['last_name']);
                    echo('</td>');
                    echo('<td>');
                    echo ($studentonteam_datum['dex']);
                    echo('</td>');
                    echo('<tr>');
                    if($j!=1){
                      echo('<td>');
                      echo('</td>');
                    }
                    
                  $j++;
                }


            }
            

         




         echo('</tr>');

         }

/* 
      
          foreach ($student_data as $student_datum){
              echo('<tr>');
              echo('<td>');
              echo $student_datum['first_name'].' '.$student_datum['last_name'];
              echo('</td><td>');
              echo $student_datum['dex'];
              echo('</td>');
              echo('</tr>');

          }
 */
         echo("</tbody>");
   echo("</table>");


/* 

	 echo ('<table id="table_format" style = "text-align:center" class = "a" border="1" >'."\n");	
		 echo("<thead>");

		echo("<th>");
		echo('name');
        echo("</th><th>");
		echo('pin');
          echo("</th><th>");
		echo('Extend t');
         echo("</th><th>");
        echo('Correct 1');
		echo("</th><th>");
        echo('Correct 2');
		echo("</th><th>");
         echo('Correct 3');
		echo("</th><th>");
        echo('Correct 4');
        echo("</th><th>");
        echo('Correct 5');
         echo("</th><th>");   
        echo('P 1');
        echo('</br>');
        // echo('<font font-size = "1"> a b c d e f g h i j </font>');
		echo("</th><th>");
        echo('P 2');
		echo("</th><th>");
         echo('P 3');
		echo("</th><th>");
        echo('P 4');
        echo("</th><th>");
        echo('P 5');
          echo("</th><th>");
            echo('Location');
          echo("</th><th>");
        echo('Total');
		echo("</th></tr>\n");
		 echo("</thead>");
		 
		  echo("<tbody>");
		//

 
 
		
		// Get the team_id of all the teams in the game_prob_flag
   // $stmt = $pdo->prepare("SELECT *  FROM `Eactivity` WHERE eexamnow_id = :eexamnow_id ORDER BY SUBSTR(name, CHAR_LENGTH(name) - LOCATE(' ', REVERSE(name))+1)");          //SUBSTR(name, CHAR_LENGTH(name) - LOCATE(' ', REVERSE(name))+1)
    $stmt = $pdo->prepare("SELECT *  FROM `Eactivity` WHERE eexamnow_id = :eexamnow_id ");         
    $stmt->execute(array(":eexamnow_id" => $eexamnow_id));
             $rows = $stmt->fetchALL(PDO::FETCH_ASSOC); 
           //  print_r($row[0]);
             
             foreach($rows as $row){
            
                 echo "<tr><td>";
                echo($row['name']);
                echo("</td><td>");	
                  echo($row['dex']);
                  echo("</td><td>");
                  
                   
              
                
                  if($row['extend_time_flag']==1){echo('<p>ex time</p>');} 
                  //  echo('<form action = "QRExamEditExaminee.php" method = "POST" target = "_blank" > <input type = "hidden" name = "examactivity_id" value = "'.$row['examactivity_id'].'"><input type = "submit" name = "edit" value ="Edit"></form>');
                   //   echo('<form action = "QRExamEditExaminee.php" method = "POST" target = "_blank" > <input type = "hidden" name = "examactivity_id" value = "'.$row['examactivity_id'].'"><input type = "submit" name = "edit" value ="Edit2"></form>');
                    
                    echo '<a href = "QRExamEditExaminee.php?examactivity_id='.$row['examactivity_id'].'" target = "_blank"> edit </a>';
 //--------------------------------------------------------------------------------------------------------fix this-------------------------------------                 
                  
                  
                    echo("</td><td>");	
                  print('<span class="inlinebar1">');
                echo($row['response_pblm1']);
                   print('</span>');
                 echo("</td><td>");
                 
                   print('<span class="inlinebar1">');
                echo($row['response_pblm2']);
                   print('</span>');
                 echo("</td><td>");
                 
                  print('<span class="inlinebar1">');
                  echo($row['response_pblm3']);
                   print('</span>');
                 echo("</td><td>");
                 
                 
                   print('<span class="inlinebar1">');
                echo($row['response_pblm4']);
                   print('</span>');
                 echo("</td><td>");
                 
                   print('<span class="inlinebar1">');
                echo($row['response_pblm5']);
                   print('</span>');
                 echo("</td><td>");
              //  echo($row['pblm_1_score']);
                
                
                  if(empty($row['response_pblm1'])){
                    $resp_1 =array(0,0,0,0,0,0,0,0,0,0);
                } else {
                 $resp_1 = explode(",",$row['response_pblm1'] );
                }
            if(isset($_POST['p1a'])){
                $points_1 = $resp_1[0]*intval($_POST['p1a'])
                +$resp_1[1]*intval($_POST['p1b'])
                +$resp_1[2]*intval($_POST['p1c'])
                +$resp_1[3]*intval($_POST['p1d'])
                +$resp_1[4]*intval($_POST['p1e'])
                +$resp_1[5]*intval($_POST['p1f'])
                +$resp_1[6]*intval($_POST['p1g'])
                +$resp_1[7]*intval($_POST['p1h'])
                +$resp_1[8]*intval($_POST['p1i'])
                +$resp_1[9]*intval($_POST['p1j']);
            } else {
                $points_1 = 0;
            }
                echo($points_1);
              
                 echo("</td><td>");
               // echo($row['pblm_2_score']);
                 
                if(empty($row['response_pblm2'])){
                    $resp_2 =array(0,0,0,0,0,0,0,0,0,0);
                } else {
                 $resp_2 = explode(",",$row['response_pblm2'] );
                }
                if(isset($_POST['p2a'])){   
                    $points_2 = $resp_2[0]*intval($_POST['p2a'])
                    +$resp_2[1]*intval($_POST['p2b'])
                    +$resp_2[2]*intval($_POST['p2c'])
                    +$resp_2[3]*intval($_POST['p2d'])
                    +$resp_2[4]*intval($_POST['p2e'])
                    +$resp_2[5]*intval($_POST['p2f'])
                    +$resp_2[6]*intval($_POST['p2g'])
                    +$resp_2[7]*intval($_POST['p2h'])
                    +$resp_2[8]*intval($_POST['p2i'])
                    +$resp_2[9]*intval($_POST['p2j']);
                 } else {
                    $points_2 = 0;
                }
                
                echo($points_2);
            
                 echo("</td><td>");
              
                 if(empty($row['response_pblm3'])){
                    $resp_3 =array(0,0,0,0,0,0,0,0,0,0);
                } else {
                 $resp_3 = explode(",",$row['response_pblm3'] );
                }
                
                 if(isset($_POST['p3a'])){   
                    $points_3 = $resp_3[0]*intval($_POST['p3a'])
                    +$resp_3[1]*intval($_POST['p3b'])
                    +$resp_3[2]*intval($_POST['p3c'])
                    +$resp_3[3]*intval($_POST['p3d'])
                    +$resp_3[4]*intval($_POST['p3e'])
                    +$resp_3[5]*intval($_POST['p3f'])
                    +$resp_3[6]*intval($_POST['p3g'])
                    +$resp_3[7]*intval($_POST['p3h'])
                    +$resp_3[8]*intval($_POST['p3i'])
                    +$resp_3[9]*intval($_POST['p3j']);
                 } else {
                    $points_3 = 0;
                }

               echo($points_3);
            
                 echo("</td><td>");
               
                 if(empty($row['response_pblm4'])){
                    $resp_4 =array(0,0,0,0,0,0,0,0,0,0);
                } else {
                 $resp_4 = explode(",",$row['response_pblm4'] );
                }
                
                 if(isset($_POST['p4a'])){   
                    $points_4 = $resp_4[0]*intval($_POST['p4a'])
                    +$resp_4[1]*intval($_POST['p4b'])
                    +$resp_4[2]*intval($_POST['p4c'])
                    +$resp_4[3]*intval($_POST['p4d'])
                    +$resp_4[4]*intval($_POST['p4e'])
                    +$resp_4[5]*intval($_POST['p4f'])
                    +$resp_4[6]*intval($_POST['p4g'])
                    +$resp_4[7]*intval($_POST['p4h'])
                    +$resp_4[8]*intval($_POST['p4i'])
                    +$resp_4[9]*intval($_POST['p4j']);
                } else {
                    $points_4 = 0;
                }


               echo($points_4);
            
                 echo("</td><td>");
                 if(empty($row['response_pblm5'])){
                    $resp_5 =array(0,0,0,0,0,0,0,0,0,0);
                } else {
                 $resp_5 = explode(",",$row['response_pblm5'] );
                }
                 if(isset($_POST['p5a'])){   
                    $points_5 = $resp_5[0]*intval($_POST['p5a'])
                    +$resp_5[1]*intval($_POST['p5b'])
                    +$resp_5[2]*intval($_POST['p5c'])
                    +$resp_5[3]*intval($_POST['p5d'])
                    +$resp_5[4]*intval($_POST['p5e'])
                    +$resp_5[5]*intval($_POST['p5f'])
                    +$resp_5[6]*intval($_POST['p5g'])
                    +$resp_5[7]*intval($_POST['p5h'])
                    +$resp_5[8]*intval($_POST['p5i'])
                    +$resp_5[9]*intval($_POST['p5j']);
                 } else {
                    $points_5 = 0;
                }
                
                echo($points_5);
                
              
                echo("</td><td>");
                   echo($row['city'].', '.$row['region'].', '.$row['country']);
                  echo("</td><td>");
                 
                $total_score = $points_1+$points_2+$points_3+$points_4+$points_5;

                 echo($total_score);
               
                echo("</td></tr>\n");
           
            // now update the examactivity table with the scores
    /*             $sql = "UPDATE `Examactivity` SET pblm_1_score = :pblm_1_score, pblm_2_score = :pblm_2_score , pblm_3_score = :pblm_3_score, pblm_4_score = :pblm_4_score, pblm_5_score = :pblm_5_score WHERE examactivity_id = :examactivity_id ";
             $stmt = $pdo->prepare($sql);
			$stmt->execute(array(
            ":examactivity_id" => $row['examactivity_id'],
            ":pblm_1_score" => $points_1,
            ":pblm_2_score" => $points_2,
            ":pblm_3_score" => $points_3,
            ":pblm_4_score" => $points_4,
            ":pblm_5_score" => $points_5,
            ));
            
            

           }
                 
              
               
               // echo('<form action = "QRGameFixSum.php" method = "POST" target = "_blank"> <input type = "hidden" name = "gmact_id" value = "'.$row['gmact_id'].'"><input type = "hidden" name = "team_id" value =  "'.$row['team_id'].'"><input type = "submit" value ="Fix Team Sums"></form>');
	          //   echo('<form action = "QRGameDeletePlayer.php" method = "POST" target = "_blank">  <input type = "hidden" name = "gameactivity_id" value = "'.$row['gameactivity_id'].'"><input type = "submit" value ="Delete Player"></form>');
             //    echo('<form action = "QRGameEditPlayer.php" method = "POST" target = "_blank"> <input type = "hidden" name = "gameactivity_id" value = "'.$row['gameactivity_id'].'"><input type = "submit" value ="Edit Player Data"></form>');

                // echo("&nbsp; ");
				// echo('<form action = "getGame.php" method = "POST" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "hidden" name = "iid" value = "'.$users_id.'"><input type = "submit" value ="Game"></form>');
				// echo("&nbsp; ");
				// echo('<form action = "numericToMC.php" method = "POST" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "submit" value ="Make MC"></form>');
				                
                    
            
                 
           
            echo("</tbody>");
             echo("</table>");
             
                if(isset($_POST['close'])){
                    echo  "<script type='text/javascript'>";
                    echo "window.close();";
                echo "</script>";
     }

  */

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



        <p style="font-size:75px;"></p>   
        <form method="POST" >
             <p><input type="hidden" name="eexamtime_id" id="eexamtime_id" value=<?php echo($eexamtime_id);?> ></p>
             <p><input type = "submit" name = "close" value="Exit - Close Window" id="close_id" size="2" style = "width: 40%; background-color: black; color: white"/> &nbsp &nbsp </p>
        </form>

	<script>


	$(document).ready( function () {	

    
     const number_teams = pass['number_teams']; 
     const currentclass_id = pass['currentclass_id']; 
    

      $('input:radio').click(function(){
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
    
 /*  
	 	
     $(".inlinebar1").sparkline("html",{type: "bar", height: "20", barWidth: "5", resize: true, barSpacing: "2", barColor: "navy"});
	   	
    
        $(".inlinebar2").sparkline("html",{type: "bar", height: "50", barWidth: "10", resize: true, barSpacing: "5", barColor: "orange"});
		
		localStorage.setItem('MC_flag','false');  // initialize multiple choice flag to false
            // auto refresh page 
            
             setInterval("$('#refresh_page').submit()",30000);
          
            

                $('#table_format').DataTable({
                        "order": [[ 1, 'asc' ] ],
                        "lengthMenu": [ 30, 50, 100 ]
                });

                    */
		} );
         
         
		
	</script>

	
	</body>
	</html>