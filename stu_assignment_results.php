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

   echo ('<table id="table_format" class = "a" border="2" >'."\n");
        echo("<thead>");

		echo("</td><th>");
		echo('<b> Name </b>');
	//	echo("</th><th>");
    //   echo(' id ');
       echo("</th><th>");
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
              foreach ($assign_data as $assign_datum){
                      $alias_num = $assign_datum['alias_num'];
                      $assign_id = $assign_datum['assign_id'];
                       echo("</th><th>");
                     echo(' Pblm '.$alias_num.' - '.$assign_datum['prob_num'].'<br> numeric score');
                     echo("</th><th>");
                    echo ('survey');
                    if($assign_datum['reflect_flag']==1){echo('</th><th>reflect');}
                    if($assign_datum['explore_flag']==1){echo('</th><th>explore');}
                    if($assign_datum['connect_flag']==1){echo('</th><th>connect');}
                    if($assign_datum['society_flag']==1){echo('</th><th>society');}
                     echo("</th><th>");
                    echo ('work'); 
                    echo("</th><th>");
                    echo ('Pblm Tot');
                } 
                 echo("</th><th>");
                echo ('Assn Tot');
            echo("</th></tr>\n");
		    echo("</thead>");
		  echo("<tbody><tr></tr><tr>");
          
          
           $sql = "SELECT DISTINCT `student_id`,`stu_name`,`dex`  FROM `Activity` WHERE assign_id = :assign_id AND currentclass_id = :currentclass_id ORDER BY `stu_name` ASC";
               $stmt = $pdo->prepare($sql);
               $stmt -> execute(array(
                 ':currentclass_id' => $currentclass_id,
                  ':assign_id' => $assign_id,
                    )); 

                    $activity_data = $stmt->fetchALL();
                  foreach ($activity_data as $activity_datum){
                    echo('<td>');
                   echo($activity_datum['stu_name']);
                    echo('</td><td>');
              //     echo($activity_datum['student_id']);
               //     echo('</td><td>');
                   echo('&nbsp;'.$activity_datum['dex']);
                    echo('</td>');
                     $default_assn_tot = 0;
                       foreach ($assign_data as $assign_datum){
                           $assign_id = $assign_datum['assign_id'];
                           $alias_num = $assign_datum['alias_num'];
                            $student_id = $activity_datum['student_id'];
                           $sql = "SELECT *  FROM `Activity` WHERE assign_id = :assign_id AND currentclass_id = :currentclass_id AND `student_id` = :student_id ";
                           $stmt = $pdo->prepare($sql);
                           $stmt -> execute(array(
                             ':currentclass_id' => $currentclass_id,
                              ':assign_id' => $assign_id,
                           //   ':alias_num' => $alias_num,
                               ':student_id' => $student_id,
                                )); 
                 /*                
                                echo ('  currentclass_id  '.$currentclass_id);
                                echo ('  assign_id  '.$assign_id);
                                echo ('  alias_num  '.$alias_num);
                                echo ('  student_id  '.$student_id);
 */
                            $stu_activity = $stmt->fetch();
                            echo('<td>');
                           echo($stu_activity['p_num_score_net']);
                            echo('</td>');
                             echo('<td>');
                           echo($stu_activity['survey_pts']);
                            echo('</td>');
                            $prob_default_tot = $stu_activity['p_num_score_net']+$stu_activity['survey_pts'];
                            $text_for_field = 'perc_'.$alias_num;
                            $default_assn_tot = $default_assn_tot + $prob_default_tot*$assigntime_data[$text_for_field]/100;
                             if($assign_datum['reflect_flag']==1){echo('<td><span class = "reflections">'.$stu_activity['reflect_text'].'</span></td>');}
                             if($assign_datum['explore_flag']==1){echo('<td><span class = "reflections">'.$stu_activity['explore_text'].'</span></td>');}
                             if($assign_datum['connect_flag']==1){echo('<td><span class = "reflections">'.$stu_activity['connect_text'].'</span></td>');}
                             if($assign_datum['society_flag']==1){echo('<td><span class = "reflections">'.$stu_activity['society_text'].'</span></td>');}
                            echo('<td>link to work</td>');
                            //echo('<td>prob_t input</td>');
                              echo('<td>');
                            echo('<input type = "number" min = "0" max = "100" id="prob_tot_'.$stu_activity['activity_id'].'" name = "prob_tot_'.$stu_activity['activity_id'].'" required value = '.$prob_default_tot.' > </input>');
                              echo('</td>');
                
                        
                        
                        
                        }
                         
                            echo('<td>');
                            echo('<input type = "number" min = "0" max = "100" id="assign_tot_'.$student_id.'" name = "assign_tot_'.$stu_activity['activity_id'].'" required value = '.$default_assn_tot.' > </input>');
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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


