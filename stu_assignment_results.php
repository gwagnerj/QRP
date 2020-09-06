<?php
	require_once "pdo.php";
	session_start();

 
   

	
if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {	
    if (isset($_POST['iid'])) {
	$iid = $_POST['iid'];
   
    if ($_POST['currentclass_id']==0 || $_POST['currentclass_id']=='' || $_POST['iid']==0 || $_POST['iid']=='' ){
        $_SESSION['error'] = 'Class must be Selected';
        header( 'Location: QRAssignmentStart0.php?iid='.$iid ) ;
	   die();
    }
   
} else {
    
    
	 $_SESSION['error'] = 'invalid User_id in stu_assignment_results.php ';
     echo('no iid');
      			header( 'Location: QRPRepo.php' ) ;
				die();
}
    
    if ($_POST['currentclass_id']==0 || $_POST['currentclass_id']=='' || $_POST['active_assign']==0 || $_POST['active_assign']=='' || $_POST['iid']==0 || $_POST['iid']=='' ){
        $_SESSION['error'] = 'class and assignment number must be set';
        header( 'Location: QRAssignmentStart0.php' ) ;
	   die();
    }
    
    $assign_num = $_POST['active_assign'];
    $currentclass_id = $_POST['currentclass_id'];
    
  
   //   echo(' currentclass_id:  '.$currentclass_id);
   
      $sql = "SELECT `name`  FROM `CurrentClass` WHERE currentclass_id = :currentclass_id";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
			 ':currentclass_id' => $currentclass_id,
				)); 
          
          $currentclass_data = $stmt->fetch();
          
   
           $sql = "SELECT *  FROM `Assigntime` WHERE assign_num = :assign_num AND currentclass_id = :currentclass_id";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
			 ':currentclass_id' => $currentclass_id,
              ':assign_num' => $assign_num,
				)); 
          
          $assigntime_data = $stmt->fetch();
           
            $assigntime_id = $assigntime_data['assigntime_id'];
            

            
?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QR Student Results</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<style>
   .outer {
  width: 100%;
  margin: 0 auto;
  
}



</style>

<?php
   // start the table
   
   echo('<h2>Student Assignment Results in '.$currentclass_data['name'].' for Assignment '.$assign_num.'</h2>');

   echo ('<table id="table" class = "a" border="2" >'."\n");
        echo("<thead>");

		echo("<th>");
		echo('First');
          echo("</th><th>");
        echo('Last');
       /*       echo("</th><th>");
        echo('email');
        
     
		echo("</th><th>");
       echo(' student_id '); */
       echo('</th><th style="vertical-align: top; border-right-color:#B22222; border-right:solid 2px red; ">');
       echo(' dex ');

   
     //  figure out how many problems are in the assignment  
  
    $sql = 'SELECT * FROM Assign WHERE currentclass_id = :currentclass_id AND iid = :iid AND assign_num = :assign_num ORDER by alias_num ASC';     
          $stmt = $pdo->prepare($sql);
           $stmt -> execute(array (
           ':currentclass_id' => $currentclass_id,
           ':assign_num' => $assign_num,
           ':iid' => $iid,
           )); 
          $assign_data = $stmt -> fetchALL();
         // var_dump($assign_data);
             $prob_weight = array();  // this is so the max on the imput field is the max numerical points for that problem
             $i = 0;
             foreach ($assign_data as $assign_datum){
                      $alias_num = $assign_datum['alias_num'];
                      $assign_id = $assign_datum['assign_id'];
                       echo("</th><th>");
                       // get the total for the numerical part of the problem
                        $numeric_points = 0;
                        foreach(range('a','j') as $v){
                            $text_for_field = 'perc_'.$v.'_'.$alias_num;  
                             $numeric_points = $numeric_points + $assigntime_data[$text_for_field];
                         }
                        $prob_weight[$i]= $numeric_points;
                        $i =$i+1;
                    
                  // echo(' Pblm '.$alias_num.' - '.$assign_datum['prob_num'].'<br> numeric part <p style="color:blue;font-size:10px;">'. $numeric_points.'% of Pblm </p>');
                  echo(' Pblm '.$alias_num.' <form action = "bc_preview.php" method = "GET" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$assign_datum['prob_num'].'"><input type = "submit" value ="BC -'.$assign_datum['prob_num'].'"></form> - <br> numeric part <p style="color:blue;font-size:10px;">'. $numeric_points.'% of Pblm </p>');
                //    echo('<form action = "bc_preview.php" method = "GET" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$assign_datum['prob_num'].'"><input type = "submit" value ="BC -'.$assign_datum['prob_num'].'"></form>');

                 echo("</th><th>");
                      $text_for_field = 'survey_'.$alias_num;
                      $survey_perc_of_pblm = $assigntime_data[$text_for_field];
                    echo ('Xtra Cred / Survey<p style="color:blue;font-size:10px;">'.$survey_perc_of_pblm.'% of pblm / </p>');
                    
                    
                    if($assign_datum['reflect_flag']==1){$text_for_field = 'perc_ref_'.$alias_num; echo('</th><th>Reflect <p style="color:blue;font-size:10px;"> '.$assigntime_data[$text_for_field].'% of pblm </p>');}
                    if($assign_datum['explore_flag']==1){$text_for_field = 'perc_exp_'.$alias_num; echo('</th><th>Explore <p style="color:blue;font-size:10px;"> '.$assigntime_data[$text_for_field].'% of pblm </p>');}
                    if($assign_datum['connect_flag']==1){$text_for_field = 'perc_con_'.$alias_num; echo('</th><th>Connect <p style="color:blue;font-size:10px;"> '.$assigntime_data[$text_for_field].'% of pblm </p>');}
                    if($assign_datum['society_flag']==1){$text_for_field = 'perc_soc_'.$alias_num; echo('</th><th>Society <p style="color:blue;font-size:10px;"> '.$assigntime_data[$text_for_field].'% of pblm </p>');}
  /*                    echo("</th><th>");
                    echo ('Work');  */
                    echo('</th><th style="vertical-align: top; border-right-color:#B22222; border-right:solid 3px red; ">');
                      $text_for_field = 'perc_'.$alias_num;
                      $perc_of_Assign = $assigntime_data[$text_for_field];
                    echo (' <p style="color:red;font-size:14px;">Prob '.$alias_num.' Tot</p>  <p <span class = "pblm_weight" style="color:red;font-size:10px;"> '. $perc_of_Assign.'% of Assign </span> </p>');
                } 
                 echo('</th><th  style = "border-left: solid 3px orange; border-right: solid 3px orange;">');
                echo ('QR total');  
                 echo("</th><th>");
                echo ('Other Pblms');  
                 echo("</th><th>");
                echo ('Assn EC');  
                 echo("</th><th>");
                echo ('Assn Tot');  
                echo("</th><th>");
                echo ('Row Action'); 
            echo("</th></tr>\n");
		    echo("</thead>");
		  echo("<tbody><tr></tr><tr>");
          
          // get all the students in the class
          // first try a Joint search
          
             $sql = "SELECT * FROM `Student` INNER JOIN `StudentCurrentClassConnect` ON Student.student_id = StudentCurrentClassConnect.student_id WHERE StudentCurrentClassConnect.currentclass_id = :currentclass_id ORDER BY Student.last_name ASC";
               $stmt = $pdo->prepare($sql);
               $stmt -> execute(array(
                 ':currentclass_id' => $currentclass_id,
                    )); 

                    $class_student_data = $stmt->fetchALL();
          
           // var_dump($class_student_data);
          
          foreach($class_student_data as $class_student_datum){
              
    
                
               




        
               $student_id = $class_student_datum['student_id'];
              
                   // get any previous data for the assignment 
          
              $sql = "SELECT *  FROM `Assignscore` WHERE  student_id = :student_id AND assigntime_id = :assigntime_id";
               $stmt = $pdo->prepare($sql);
               $stmt -> execute(array(
                 ':assigntime_id' => $assigntime_id,
                  ':student_id' => $student_id,
                    )); 
                $assignscore_data = $stmt->fetch();
                 if ($assignscore_data != false) {
                    
                    
                    $qr_tot = $assignscore_data['qr_tot'];
                    $other_pblm = $assignscore_data['other_pblm'];
                    $assign_ec = $assignscore_data['assign_ec'];
                    $assign_tot = $assignscore_data['assign_tot'];
                   // echo (' qr_tot '.$qr_tot);
                    
                } else {
                    
                    $qr_tot = $other_pblm = $assign_ec = $assign_tot = 0;
                    
                }
                
                
              
              // get their pin and dex 
              $sql = "SELECT `pin`  FROM `StudentCurrentClassConnect` WHERE  currentclass_id = :currentclass_id AND student_id = :student_id";
               $stmt = $pdo->prepare($sql);
               $stmt -> execute(array(
                 ':currentclass_id' => $currentclass_id,
      //            ':assign_id' => $assign_id,
                  ':student_id' => $student_id,
                    )); 

                $pins = $stmt->fetch();
                $pin = $pins['pin'];
                
                $dex = ($pin-1) % 199 + 2;
                
             //  foreach ($activity_data as $activity_datum){
                     
           /*    
              get their 
               $sql = "SELECT DISTINCT `student_id`,`stu_name`,`dex`  FROM `Activity` WHERE assign_id = :assign_id AND currentclass_id = :currentclass_id ORDER BY `stu_name` ASC";
                   $stmt = $pdo->prepare($sql);
                   $stmt -> execute(array(
                     ':currentclass_id' => $currentclass_id,
                      ':assign_id' => $assign_id,
                        )); 

                        $activity_data = $stmt->fetchALL();
                       foreach ($activity_data as $activity_datum){
             */                 
                           
                           echo('<td style="vertical-align: top;">');
                             echo($class_student_datum['first_name']);
                                echo('</td><td style="vertical-align: top;">');
                           //  echo($class_student_datum['last_name']);
                            //    echo('</td><td style="vertical-align: top;">');
                                 echo('<a href ="mailto: '.$class_student_datum['school_email'].'">'.$class_student_datum['last_name'].'</a>');
                                echo('</td><td style="vertical-align: top; border-right-color:#B22222; border-right:solid 2px red; ">');
                             /*   echo($student_id);
                                echo('</td><td>'); */
                               echo('&nbsp;'.$dex);
                                echo('</td>');
                                 $default_assn_tot = 0;
                                 $i = 0;
                           foreach ($assign_data as $assign_datum){
                               $assign_id = $assign_datum['assign_id'];
                               $alias_num = $assign_datum['alias_num'];
                               
                               $sql = "SELECT *  FROM `Activity` WHERE assign_id = :assign_id  AND `student_id` = :student_id ";
                               $stmt = $pdo->prepare($sql);
                               $stmt -> execute(array(
                                  ':assign_id' => $assign_id,
                                   ':student_id' => $student_id,
                                    )); 
                    
                                $stu_activity = $stmt->fetch();
                                $activity_id = $stu_activity['activity_id'];
                                
                                 
                                      
                                echo('<td style="vertical-align: top;">');
                                if ($stu_activity['activity_id']>0){
                                    $p_num_score_net = 0;
                                     if($stu_activity['p_num_score_net']>0 || $stu_activity['fb_p_num_score_net']>0){  // put stuff in here if you don't want it to show up if there is no net score
                                            
                                            if($stu_activity['fb_p_num_score_net']>0){$p_num_score_net = $stu_activity['fb_p_num_score_net'];} else {$p_num_score_net = $stu_activity['p_num_score_net'];}
                                            
                                            
                                            } 
                                            
                                           echo('<input type = "number" min = "0" max = "'.$prob_weight[$i].'" class = "goestodb_'.$student_id.'" id = "pNumScoreNet_'.$student_id.'_'.$activity_id.'" name = "p_num_score_net_'.$student_id.'_'.$activity_id.'" value = '.$p_num_score_net.'></input>');
                                           echo'<br>';
     
                                            echo('<form action = "activity_details.php" method = "GET" target = "_blank"> <input type = "hidden" name = "activity_id" value = "'.$activity_id.'"><input type = "submit" value ="Details"></form>');
                                 
                                        
                                       
                                        echo('<form action = "get_pdf.php" method = "GET" target = "_blank"> <input type = "hidden" name = "activity_id" value = "'.$activity_id.'"><input type = "submit" value ="Show Work"></form>');
                                       echo('<br>');
                                   }      
                                        $i=$i+1;
                                       echo('<form action = "QRproblem_preview.php" method = "GET" target = "_blank"> <input type = "hidden" name = "activity_id" value = "'.$activity_id.'"><input type = "hidden" name = "problem_id" value = "'.$assign_datum['prob_num'].'"><input type = "hidden" name = "dex" value = "'.$dex.'"><input type = "submit" value ="Help Student"></form>');
                               /*          
                                     // trying to display the thumbnail   
                                        $all_files = array();
                                        $dir =  'student_work';
                                        $prefix = $activity_id.'-';
                                    //    chdir($dir);
                                        $matches = glob("$prefix*");
                                        if(is_array($matches) && !empty($matches)){
                                            foreach($matches as $match){
                                                $all_files[] = $match;
                                            }
                                        }  
                                        
                                        foreach($all_files as $all_file){
                                          // check the extension of the file 
                                             $tmp = explode('.', $all_file);
                                             $extension = end($tmp);
                                            // echo(' $extension '.$extension);
                                             if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' ){
                                          
                                                    echo'<br>';
                                                    echo '<img src="/student_work/'.$all_file.'"   width="10%" >';
                                             } elseif($extension == 'pdf'){
                                               // echo(' all_file  '.$all_file); 
                                                echo(' <iframe frameborder="0" scrolling="no"');
                                                 echo('width="10%" ');
                                                 echo('src="student_work/'.$all_file.'">');
                                                 
                                             echo '</iframe>';
                                             } else {
                                                 $_SESSION['error'] = $extention.' file type not allowed';
                                                   echo ($extention.'<h2> file type not allowed </h2>');
                                             }
                                        }
                                        
                                         */
                                        
                                        


                                echo('</td>');
                                            if ($stu_activity['survey_pts']!=null){
                                                 echo('<td style="vertical-align: top;">');
                                              // echo'<br>&nbsp;&nbsp;&nbsp;/<br>';
                                               echo('<input type = "number" min = "-100" max = "200"  class = "goestodb_'.$student_id.'"  id = "ecPts_'.$student_id.'_'.$activity_id.'" name = "ecPts_'.$student_id.'_'.$activity_id.'" placeholder = "E.C." value = '.$stu_activity["ec_pts"].'></input>');
                                               echo('<span class = "'.$student_id.'" id = "survey_pts_'.$student_id.'_'.$activity_id.'" >'.$stu_activity['survey_pts'].'</span>');

                                                echo('</td>');
                                    }else {echo'<td></td>';}
                                $prob_default_tot = $stu_activity['p_num_score_net']+$stu_activity['survey_pts'];
                                $text_for_field = 'perc_'.$alias_num;
                                $default_assn_tot = $default_assn_tot + $prob_default_tot*$assigntime_data[$text_for_field]/100;
                                 
                                 if($assign_datum['reflect_flag']==1){
                                     if($stu_activity['activity_id']>0){
                                           $text_for_field = 'perc_ref_'.$alias_num;
                                            $max_points = $assigntime_data[$text_for_field];
                                             echo('<td style="width:200px; vertical-align: top;"><input type = "number" min = "0" max = "'.$max_points.'" class = "goestodb_'.$student_id.'"  id="reflect_'.$student_id.'_'.$stu_activity['activity_id'].'" name ="reflect_'.$student_id.'_'.$stu_activity['activity_id'].'"placeholder = "Score" value = '.$stu_activity["reflect_pts"].'  > </input>'); 
                                             echo'<br>';
                                              echo('<br><input type = "text"  class = "goestodb_'.$student_id.'" id="fb_reflect_'.$student_id.'_'.$stu_activity['activity_id'].'" name ="fb_reflect_'.$student_id.'_'.$stu_activity['activity_id'].'" placeholder = "__Feedback to Students__"  value = '.$stu_activity["fb_reflect"].'  > </input>');
                                                 if($stu_activity['reflect_text']!=null){
                                                    echo('<span class = "reflections">'.$stu_activity['reflect_text'].'</span>');
                                                 }
                                                 echo ('</td>');
                                     }else {echo'<td></td>';}
                                  }
                               
                               if($assign_datum['explore_flag']==1){ 
                                     if($stu_activity['activity_id']>0){
                                        $text_for_field = 'perc_exp_'.$alias_num;
                                        $max_points = $assigntime_data[$text_for_field];
                                        echo('<td style="width:200px; vertical-align: top;"> <input type = "number" min = "0" max = "'.$max_points.'"   class = "goestodb_'.$student_id.'" id="explore_'.$student_id.'_'.$stu_activity['activity_id'].'" name ="explore_'.$student_id.'_'.$stu_activity['activity_id'].'" placeholder = "Score" value = '.$stu_activity["explore_pts"].'  > </input>');
                                         echo'<br>';
                                       echo('<br><input type = "text"  class = "goestodb_'.$student_id.'" id="fb_explore_'.$student_id.'_'.$stu_activity['activity_id'].'" name ="fb_explore_'.$student_id.'_'.$stu_activity['activity_id'].'" placeholder = "__Feedback to Students__"  value = '.$stu_activity["fb_explore"].'  > </input>');
                                        
                                    if($stu_activity['explore_text']!=null){
                                        echo('<span class ="reflections">'.$stu_activity['explore_text'].'</span>');    
                                    }
                                      echo ('</td>');
                                      }else {echo'<td></td>';}
                                    }
                                 if($assign_datum['connect_flag']==1){
                                           if($stu_activity['activity_id']>0){
                                                 $text_for_field = 'perc_con_'.$alias_num;
                                                  $max_points = $assigntime_data[$text_for_field];
                                                echo('<td style="width:200px; vertical-align: top;"> <input type = "number" min = "0" max = "'.$max_points.'"   class = "goestodb_'.$student_id.'" id="connect_'.$student_id.'_'.$stu_activity['activity_id'].'" name ="connect_'.$student_id.'_'.$stu_activity['activity_id'].'" placeholder = "Score" value = '.$stu_activity["connect_pts"].'   > </input>');
                                                 echo'<br>';
                                                echo('<br><input type = "text"  class = "goestodb_'.$student_id.'" id="fb_connect_'.$student_id.'_'.$stu_activity['activity_id'].'" name ="fb_connect_'.$student_id.'_'.$stu_activity['activity_id'].'" placeholder = "__Feedback to Students__"  value = '.$stu_activity["fb_connect"].'  > </input>');
                                              if($stu_activity['connect_text']!=null){   
                                                 echo('<span class = "reflections">'.$stu_activity['connect_text'].'</span>');
                                              }
                                               echo ('</td>');
                                            }else {echo'<td></td>';}
                                     }
                                 if($assign_datum['society_flag']==1){
                                        if($stu_activity['activity_id']>0){
                                         $text_for_field = 'perc_soc_'.$alias_num;
                                          $max_points = $assigntime_data[$text_for_field];
                                        echo('<td style="width:200px; vertical-align: top;"> <input type = "number" min = "0" max = "'.$max_points.'"  class = "goestodb_'.$student_id.'" id="society_'.$student_id.'_'.$stu_activity['activity_id'].'" name ="society_'.$student_id.'_'.$stu_activity['activity_id'].'" placeholder = "Score" value = '.$stu_activity["society_pts"].' > </input>');
                                        echo'<br>';
                                        echo('<br><input type = "text"  class = "goestodb_'.$student_id.'"  id="fb_society_'.$student_id.'_'.$stu_activity['activity_id'].'" name ="fb_society_'.$student_id.'_'.$stu_activity['activity_id'].'" placeholder = "__Feedback to Students__" value = '.$stu_activity["fb_society"].'  > </input>');
                                        if($stu_activity['society_text']!=null){   
                                        echo('<span class = "reflections">'.$stu_activity['society_text'].'</span>');
                                        }
                                          echo ('</td>');
                                    }else {echo'<td></td>';}
                                   }
               // the button to get the student work
               //                 echo ('<form action = "get_pdf.php" method = "GET" target = "_blank"> ');
                //               echo(' <input type = "hidden" name = "activity_id" value = "'.$activity_id.'"></form>');
              
                          //   echo('<td style="vertical-align: top;">');
                          //  echo('</td>');
                           //  echo('<td>link to work</td>');
                                //echo('<td>prob_t input</td>');
                                
                           if ($stu_activity['fb_probtot_pts']>0){$prob_tot_pts = $stu_activity['fb_probtot_pts'];} else {$prob_tot_pts = $prob_default_tot;}
                                  echo('<td style="vertical-align: top; border-right-color:#B22222; border-right:solid 3px red; ">');
                                echo('<input class = "probtot_'.$student_id.' goestodb_'.$student_id.'"  type = "number" min = "0" max = "100"   id="probtot_'.$student_id.'_'.$stu_activity['activity_id'].'" name = "probtot_'.$stu_activity['activity_id'].'" readonly value = '.$prob_tot_pts.' > </input>');
                                  echo('</td>');
                        
                        }
                            echo('<td style="vertical-align: top;  border-left: solid 3px orange; border-right: solid 3px orange;">');
                            if ($qr_tot == 0) {$qr_tot = $default_assn_tot;}
                              echo('<input type = "number" min = "0" max = "200" id = "qr_tot_'.$student_id.'" name = "qr_tot_'.$student_id.'" readonly value = '.$qr_tot.'></input>');

                             echo('</td>');
                        
                            echo('<td style="vertical-align: top;">');
                              echo('<input type = "number" min = "0" max = "200" id = "other_pblm_'.$student_id.'" name = "other_pblm_'.$student_id.'" value ='.$other_pblm.' ></input>');

                             echo('</td>');
                            echo('<td style="vertical-align: top;">');
                              echo('<input type = "number" min = "0" max = "200" id = "assign_ec_'.$student_id.'" name = "assign_ec_'.$student_id.'" value = '.$assign_ec.'></input>');
                              echo('</td>');
                            echo('<td style="vertical-align: top;">');
                             if ($assign_tot == 0) {$assign_tot = $default_assn_tot;}
                            echo('<input type = "number" min = "0" max = "100"  class = "assignment_tot"  id="assign_tot_'.$student_id.'" name = "assign_tot_'.$stu_activity['activity_id'].'" readonly value = '.$assign_tot.' > </input>');
                              echo('</td>');
                              echo('<td style="vertical-align: top;">');
                              
                              // this should be done by an AJAX call I think
                             echo('<button  method = "POST" class = "save_row" id = "saverow_'.$student_id.'"> <input type = "hidden" name = "student_id" value = "'.$student_id.'"> <input type = "hidden" id = "assigntime_id" name = "assigntime_id" value = "'.$assigntime_id.'"><input type = "submit" value ="Save"></form>');

                              echo('</td>');
                         echo('</tr><tr>');

                }

       echo('</tbody></table><br><br>');

      
}



?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QR Assignment Results</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
		<link rel="stylesheet" type="text/css" href="jquery.countdown.css"> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript" src="jquery.plugin.js"></script> 
		<script type="text/javascript" src="jquery.countdown.js"></script>
		
<style>
   .outer {
  width: 100%;
  margin: 0 auto;
 
}

.inner {
  margin-left: 50px;
 
} 


</style>



</head>

<body>


<?php
	
		if ( isset($_SESSION['error']) ) {
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) ) {
			echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
			unset($_SESSION['success']);
		}
	
 
 
 
?>


    <br><br>
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	<br>

<script>

$(document).ready( function () {
    // get the weights for each part of the assignemnt
$('#table input').on('change',function(e){	
    var row = $(this).closest('tr');
   // console.log (' row '+row);
    var ident = $(this).attr('id');
    var value = $(this).val();
    //  console.log('  ident '+ident);
   // console.log('  value '+value);
    var activity_id_str = ident.split('_');
 //  console.log('  activity_id_str '+activity_id_str);
    
    activity_id = activity_id_str.slice(-1)[0];
 //  console.log('  activity_id '+activity_id);
   
 //  console.log(typeof activity_id_str.slice(-2)[0]);
   
     if (activity_id_str.slice(-2)[0] == "ec" || activity_id_str.slice(-2)[0] == "pblm"){var student_id = activity_id_str.slice(-1)[0];} else { var student_id = activity_id_str.slice(-2)[0];}  // gets the student id if we select the other problem or assn EC values
   //  var student_id = activity_id_str.slice(-2)[0];  // get the last two then select the first one
 //    console.log('  student_id '+student_id);
      var column_type = activity_id_str.slice(0)[0];
 //    console.log('  column_type '+column_type);
  
    
    if (column_type == "reflect" || column_type == "explore"|| column_type == "connect"|| column_type == "society" || column_type == "pNumScoreNet" || column_type == "ecPts" || column_type == "assign" || column_type == "other"){
       // recompute problem total and the assignment total (need to somehow get the weights for the assignment) 
        var selector_end = '_'+student_id+'_'+activity_id;
        var probtot_sel =  '#probtot'+selector_end;
       // console.log(' probtot_sel  '+probtot_sel);
        var survey_sel = '#survey_pts'+selector_end;
        var p_num_score_net_sel = '#pNumScoreNet'+selector_end;
        var ec_pts_sel = '#ecPts'+selector_end;
        
      //   console.log('  p_num_score_net_sel '+p_num_score_net_sel);
        var p_num_score_net = $(p_num_score_net_sel).val();
        var ec_pts = $(ec_pts_sel).val();
        var survey_pts = $(survey_sel).text();
  //      console.log('  p_num_score_net '+p_num_score_net);
   //     console.log (typeof(p_num_score_net));
        p_num_score_net = parseInt(p_num_score_net);
  //      console.log (typeof(p_num_score_net));
        var reflect_sel = '#reflect'+selector_end;      
        var explore_sel = '#explore'+selector_end;      
        
        
        var connect_sel = '#connect'+selector_end;      
        var society_sel = '#society'+selector_end;    
        var reflect_pts = $(reflect_sel).val();
        var explore_pts = $(explore_sel).val();
   //       console.log(' explore_pts  '+explore_pts);
   //      console.log (typeof(explore_pts));
        var connect_pts = $(connect_sel).val();
        var society_pts = $(society_sel).val();
    
          
        
        // get values and turn them all into integers
         if(typeof(reflect_pts )=="undefined" || reflect_pts == "" || isNaN(reflect_pts) ){reflect_pts = 0;}else{ reflect_pts = parseInt(reflect_pts);}
         if(typeof(explore_pts )=="undefined" || explore_pts == "" || isNaN(explore_pts)){explore_pts = 0;}else{ explore_pts = parseInt(explore_pts);}
         if(typeof(connect_pts )=="undefined" || connect_pts == "" || isNaN(connect_pts)){connect_pts = 0;}else{ connect_pts = parseInt(connect_pts);}
         if(typeof(society_pts )=="undefined" || society_pts == "" || isNaN(society_pts)){society_pts = 0;}else{ society_pts = parseInt(society_pts);}

         if(typeof(survey_pts )=="undefined" || survey_pts == "" || isNaN(survey_pts)){survey_pts = 0;}else{ survey_pts = parseInt(survey_pts);}
         if(typeof(p_num_score_net )=="undefined" || p_num_score_net == "" || isNaN(p_num_score_net)){p_num_score_net = 0;}else{ p_num_score_net = parseInt(p_num_score_net);}
         if(typeof(ec_pts )=="undefined" || ec_pts == "" || isNaN(ec_pts)){ec_pts = 0;}else{ ec_pts = parseInt(ec_pts);}
  //        console.log(' ec_pts2  '+ec_pts);
        var probtot = p_num_score_net+survey_pts+reflect_pts+explore_pts+connect_pts+society_pts+ec_pts;
   //     console.log('  probtot '+probtot);
      $(probtot_sel).val(probtot);
      // now get the total for the assignment need to select all of the probtot for this row
      var assign_tot = 0;
      var qr_tot = 0;

      var prob_tots = [];
      var prob_weights = [];
      //  $('row [id^="probtot"]').each(function(){
       var  probtot_class_sel = '.probtot_'+student_id;
        $(probtot_class_sel).each(function(){
            prob_tots.push(parseInt($(this).val()));
 //           console.log(' this value '+$(this).val());
            });
            
           var pblm_weight_sel = '.pblm_weight' 
          $('.pblm_weight').each(function(){
            prob_weights.push(parseInt($(this).text()));
           // console.log($(this).val());
            });   
            var arrayLength = prob_tots.length;
            
            
            for (var i = 0; i < arrayLength; i++) {
                
              var problem_tot = prob_tots[i];
              var prob_weight = prob_weights[i];
              if(problem_tot == null || typeof(problem_tot)=="undefined" || isNaN(problem_tot)){
                  problem_tot = 0;
              }
               qr_tot += prob_weight*problem_tot/100;
               assign_tot += prob_weight*problem_tot/100;
            }
       
       
         var    other_pblm_sel =   '#other_pblm_'+student_id;     
        var    assign_ec_sel =   '#assign_ec_'+student_id;  
   //     console.log (' assign_ec_sel  '+assign_ec_sel);
        var assign_ec = parseFloat($(assign_ec_sel).val());
   //    console.log('  assign_ec  '+assign_ec);

 //      console.log('  assign_tot  '+assign_tot);
      
       var qr_tot_sel = '#qr_tot_'+student_id;
       
       
       var assign_tot_sel = '#assign_tot_'+student_id;
       var other_pblm = parseFloat($(other_pblm_sel).val());
     

      assign_tot += other_pblm + assign_ec;
       
       
//                console.log('  assign_tot type  '+typeof(assign_tot));
//                console.log('  assign_tot   '+assign_tot);
             // change the color of the save button
            var save_row_sel = '#saverow_'+student_id;
//            console.log(' save_row_sel '+save_row_sel);
            $(save_row_sel).css("background-color", "red");
            $(save_row_sel).on('click', function(){
                $(this).css("background-color", "lightgray");
            });
            


     //   assign_tot = assign_tot.toFixed(1);
      assign_tot = Math.round((assign_tot + Number.EPSILON) * 10) / 10
      $(qr_tot_sel).val(qr_tot.toFixed(1));
       $(assign_tot_sel).val(assign_tot);
    }

   var sum_assign = 0;
    var sum_problem = 0;
  
	
      });
      //
        // MAKE THE SAVE BUTTON TURN RED IF ANY OF THE problem totals of that row change
            $(".assignment_tot").change(function(e){
            var stu_id = $(this).attr('id');
 //             console.log('stu_id  '+stu_id);
            
            
            
            });
      
      // take care of pressing the submit row button
      $('.save_row').on('click',function(e){	
      var assigntime_id = $('#assigntime_id').val();
 //      console.log('assigntime_id  '+assigntime_id);
          var button_id = $(this).attr("id");
          var student_id_str = button_id.split('_');
           student_id = student_id_str.slice(-1)[0]; 
 //           console.log('student_id  '+student_id);
            var qr_tot_sel = "#qr_tot_"+student_id;
            var qr_tot = $(qr_tot_sel).val();
 //            console.log('qr_tot  '+qr_tot);
             var other_pblm_sel = "#other_pblm_"+student_id;
            var other_pblm = $(other_pblm_sel).val();
  //           console.log('other_pblm  '+other_pblm);
            var assign_ec_sel = "#assign_ec_"+student_id;
            var assign_ec = $(assign_ec_sel).val();
  //           console.log('assign_ec  '+assign_ec);
            var assign_tot_sel = "#assign_tot_"+student_id;
            var assign_tot = $(assign_tot_sel).val();
   //          console.log('assign_tot2  '+assign_tot);
            
            // put the assignment information for that student in the Assignscore table using AJAX
            $.ajax({
                    url: 'putInAssignscore.php',
                    method: 'post',
                
                data: {assigntime_id:assigntime_id,student_id:student_id,qr_tot:qr_tot,other_pblm:other_pblm,assign_ec:assign_ec,assign_tot:assign_tot}
                }).done(function(){
                    
                });	
              // need to get the problem information from the row and put it in the activity table for each problem   
                var key = '';
                var val = '';
                var goestodb = {}; 
                var  goestodb_class_sel = '.goestodb_'+student_id;
                $(goestodb_class_sel).each(function(){
                    key = $(this).attr('id');
                    val = $(this).val();
                   var inputkv = key+':'+val;
                 goestodb[key]=val;
             
                });
                 goestodb = JSON.stringify(goestodb);
  //               console.log(' goestobd '+goestodb);
                    
                 $.ajax({
                    url: 'putInActivity.php',
                    method: 'post',
                
                   data: goestodb
                }).done(function(result){
                    console.log('result '+result);
                });	
                
       });
      
      
    });
	    
    
</script>
