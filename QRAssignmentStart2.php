<?php
	require_once "pdo.php";
	session_start();
// this is called from the QRAssignmentSart.php and this will Collect the Points on a particular assignment from the instructor .  THis file was coppied form QRAssignmentSart.php
	
    if (isset($_GET['assigntime_id'])) {
	$assigntime_id = $_GET['assigntime_id'];
} else {
	 $_SESSION['error'] = 'invalid assigntime_id in QRExamStart2.php ';
      			header( 'Location: QRPRepo.php' ) ;
				die();
}
echo('<h1>Quick Response Assignment Setup - Grade Matrix</h1>');
// table header
  
echo ('<form method = "POST">');
  echo ('<table id="table_format" class = "a" border="1" >'."\n");
        echo("<thead>");

		echo("</td><th>");
		echo('Pblm Num');
		echo("</th><th>");
		echo('% of Assign');
		echo("</th><th>");
		echo('part a)');
		 echo("</th><th>");
		
		echo('part b)');
		echo("</th><th>");
		echo('part c)');
		 echo("</th><th>");
		echo('part d)');
		echo("</th><th>");
		echo('part e)');
		echo("</th><th>");
		echo('part f)');
		 echo("</th><th>");
		 echo('part g)');
		echo("</th><th>");
		echo('part h)');
		echo("</th><th>");
		echo('part i)');
		echo("</th><th>");
		echo('part j)');
		echo("</th><th>");
        echo('reflect');
		echo("</th><th>");
        echo('explore');
        echo("</th><th>");
        echo('connect');
		echo("</th><th>");
         echo('society');
         echo("</th><th>");
          echo('reflection choice');
         echo("</th><th>");
           echo('Pre-pblm1');
         echo("</th><th>");
           echo('Pre-pblm2');
         echo("</th><th>");
         echo('Sum for pblm');
	
		echo("</th></tr>\n");
		 echo("</thead>");
		 
		  echo("<tbody>");

// first get how many problems that the assignment has and how many parts to each problem
 $sql = "SELECT * FROM `Assigntime` WHERE assigntime_id = :assigntime_id";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
				':assigntime_id' => $assigntime_id,
				)); 
            $assigntime_data = $stmt->fetch();
        $assign_num = $assigntime_data['assign_num'];
        $iid = $assigntime_data['iid'];
        $currentclass_id = $assigntime_data['currentclass_id'];
       // echo ('currentclass_id: '.$currentclass_id);
        
        $sql = "SELECT count(*) AS cnt FROM `Assign` WHERE iid = :iid AND assign_num = :assign_num AND currentclass_id = :currentclass_id ORDER BY alias_num ";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
				':assign_num' => $assign_num,
                ':iid' => $iid,
                ':currentclass_id' => $currentclass_id,
				)); 
            $ns = $stmt->fetch();
           

           $n = $ns['cnt'];  // the number of problems in the assignment
           $point_p_pblm_default = round(100/$n);
           $points_last_p = 100 - $point_p_pblm_default*($n-1);
         
     //   echo('point_p_pblm_default: '.$point_p_pblm_default);
     //  echo('  points_last_p: '.$points_last_p);
      
        $i = 1;
       



       $sql = "SELECT * FROM `Assign` WHERE iid = :iid AND assign_num = :assign_num AND currentclass_id = :currentclass_id ORDER BY alias_num ";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
				':assign_num' => $assign_num,
                ':iid' => $iid,
                ':currentclass_id' => $currentclass_id,
				)); 
            $assign_datas = $stmt->fetchALL();
       foreach($assign_datas as $assign_data){
           $problem_id = $assign_data['prob_num'];
           
       //  echo   ('assign_data[prob_num]: '.$assign_data['prob_num']);
    
         echo "<tr><td>";
			
			
			echo(htmlentities($assign_data['alias_num']));
			
			echo("</td><td>");	
            
            
            if ($i!=$n){
                echo('<input type = "number" min = "0" max = "100" id="perc_'.$i.'" name = "perc_'.$i.'" required value = '.$point_p_pblm_default.' > </input>');
            } else {
                echo('<input type = "number" min = "0" max = "100" id="perc_'.$i.'" name = "perc_'.$i.'" required value = '.$points_last_p.' > </input>');
            }


           echo("</td>");
            $n_parts = 0; // get total parts in the problem so I can estimate the points per part 
           
            
              $sql = "SELECT * FROM `Qa` WHERE problem_id = :problem_id AND dex = :dex ";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
				':problem_id' => $problem_id,
                ':dex' => 1,
				)); 
            $Qa_data = $stmt->fetch();
           
           foreach(range('a','j') as $x){  // only getting one row 
                //echo("</td><td>");	
               if($Qa_data['ans_'.$x]<1e43){
                    $n_parts++;   
                } 
              }
                     
             $perc_per_part_default =  round(100/$n_parts);  
              $perc_per_part_last =  100 - $perc_per_part_default*($n_parts-1);  
              $j=1;
            
           foreach(range('a','j') as $x){  // only getting one row 
                //echo("</td><td>");	
               if($Qa_data['ans_'.$x]<1e43){
                       echo("<td>");
                       
                       
                       if($j != $n_parts)
                       {
                            echo('<input type = "number" min = "0" max = "100" id="perc_'.$x.'_'.$i.'" name = "perc_'.$x.'_'.$i.'" required value ='.$perc_per_part_default.' > </input>');
                       } else {
                           
                            echo('<input type = "number" min = "0" max = "100" id="perc_'.$x.'_'.$i.'" name = "perc_'.$x.'_'.$i.'" required value ='.$perc_per_part_last.' > </input>');
                       }



                       echo("</td>");
              } else {
                   echo("<td bgcolor = 'lightgray'> </td>");
                  
                 }
                 $j++;
              }
              
              
          if ($assign_data['reflect_flag']==1){
               echo("<td>");
              echo('<input type = "number" min = "0" max = "100" id="perc_'.$x.'_'.$i.'" name = "perc_'.$x.'_'.$i.'" required  > </input>');
                echo("</td>");
           } else {echo("<td bgcolor = 'lightgray'> </td>");}
             
            if ($assign_data['explore_flag']==1){
                echo("<td>");
                echo('<input type = "number" min = "0" max = "100" id="perc_'.$x.'_'.$i.'" name = "perc_'.$x.'_'.$i.'" required  > </input>');
                echo("</td>");
            } else {echo("<td bgcolor = 'lightgray'> </td>");}
             
              if ($assign_data['connect_flag']==1){
                 echo("<td>");
                 echo('<input type = "number" min = "0" max = "100" id="perc_'.$x.'_'.$i.'" name = "perc_'.$x.'_'.$i.'" required  > </input>');
                  echo("</td>");
            } else {echo("<td bgcolor = 'lightgray'> </td>");}
            
              if ($assign_data['society_flag']==1){
                 echo("<td>");
                 echo('<input type = "number" min = "0" max = "100" id="perc_'.$x.'_'.$i.'" name = "perc_'.$x.'_'.$i.'" required  > </input>');
                 echo("</td>");
           } else {echo("<td bgcolor = 'lightgray'> </td>");}
            
               if ($assign_data['ref_choice']==1){
                   echo("<td>");
                   echo('<input type = "number" min = "0" max = "100" id="perc_'.$x.'_'.$i.'" name = "perc_'.$x.'_'.$i.'" required  > </input>');
                   echo("</td>");
            } else {echo("<td bgcolor = 'lightgray'> </td>");}
            
               if ($assign_data['pp_flag1']==1){
                   echo("<td>");
                   echo('<input type = "number" min = "0" max = "100" id="perc_'.$x.'_'.$i.'" name = "perc_'.$x.'_'.$i.'" required  > </input>');
                   echo("</td>");
           } else {echo("<td bgcolor = 'lightgray'> </td>");}
             
               if ($assign_data['pp_flag2']==1){
                   echo("<td>");
                   echo('<input type = "number" min = "0" max = "100" id="perc_'.$x.'_'.$i.'" name = "perc_'.$x.'_'.$i.'" required  > </input>');
                  echo("</td>");
           } else {echo("<td bgcolor = 'lightgray' </td>");}
            
            echo("<td>");	
            echo('<span id = "sum_pblm_'.$i.'"></span>');
           echo("</td></tr>");	
           $i++;
       }  
       echo('<td>Total</td><td><span id = "sum_assignment"></span></td></tbody></table><br><br>');
        echo('<input type="submit" class="btn btn-primary"  style="width:20%"    value="Submit">');
       
       echo ('</form>');

//if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_name'])) {
    
   // echo'first page submitted';
   // input the values from the form into the Assigntime table - get the assigntime_id and then move onto page two to get points values for each part
  


        
/* 		
			$alias_num = $exam_num = $cclass_id = '';   
			
			
            $sql_stmt = "SELECT * FROM Exam WHERE DATE(NOW())<= exp_date AND iid = :iid order by exam_id";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt -> execute(array(':iid' => $iid));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 */
     
// this will be called form the main repo when the game master wants to run a game
// this is just to get the game number and go on to QRGMaster.php with a post of the game number.
// Validity will be checked in that file and sent back here if it is not valid

$_SESSION['counter']=0;  // this is for the score board


	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QR Assignment Start</title>
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
	<script>
	
	
	
	$(document).ready( function () {
		var sum_assign = 0;
        var i;
        for (i = 1; i <= 20; i++) {
        //  console.log(' i: '+i);
          
         
         var per_prob =  $('#perc_'+i).val();
         
           console.log(' per_prob: '+per_prob);
          if (per_prob != undefined) {
         sum_assign =  sum_assign + parseInt(per_prob);
          }
         
        }
		console.log(' sum_assign: '+sum_assign);
        $("#sum_assignment").text(sum_assign);
	} );
	
	
</script>	

</body>
</html>



