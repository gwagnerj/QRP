<?php
	require_once "pdo.php";
    session_start();

// The purpose of this file is to display the problems for the Exam and any feedback and score the instructor provides.  It is initially called by QRExamRegistration2 but will be the place to
// That each problem will come back to.  It will QRdisplayExamPblm from the radio buttons.  Started with stu_frontpage which serves the same function for the homework assignment
// but added elements from QRexam that was the old file that did this.

// hang on to the eregistration_id initially it will come in on a Get from QRExamRegistration2.php

if (isset($_GET['eregistration_id'])){
    $eregistration_id =   $_GET['eregistration_id'];
 } elseif (isset($_POST['eregistration_id'])){
    $eregistration_id =   $_POST['eregistration_id'];
} else {
        $_SESSION['error'] = 'lost the eregistration_id in stu_exam_frontpage';
        header('Location:  QRExamRegistration1.php');
       die;
}

// echo 'eregistration_id: '.$eregistration_id;
// find out about the exam  this could check if it is a 
$sql = 'SELECT Eexamnow.eexamnow_id AS eexamnow_id, globephase, Eexamnow.eexamtime_id AS eexamtime_id, Eexamnow.exam_code AS exam_code,
               Eregistration.student_id AS student_id,dex, iid, currentclass_id,nom_time,game_flag,exam_num,last_name,first_name,university,currentclass_id
 FROM Eexamnow LEFT JOIN Eregistration ON Eexamnow.eexamnow_id = Eregistration.eexamnow_id
 LEFT JOIN Student ON Eregistration.student_id = Student.student_id
 LEFT JOIN Eexamtime ON Eexamnow.eexamtime_id = Eexamtime.eexamtime_id
 WHERE Eregistration.eregistration_id = :eregistration_id AND Eexamnow.globephase < 3';
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
  ':eregistration_id' => $eregistration_id,
  ));
  $big_data = $stmt -> fetch(PDO::FETCH_ASSOC);
// var_dump($big_data);

if ($big_data == false) {
    $_SESSION['error'] = 'big_data was not found or exam has ended';
    header('Location:  QRExamRegistration1.php');
    die;
} 

    $iid = $big_data['iid'];
    $globephase = $big_data['globephase'];
    $game_flag = $big_data['game_flag'];
    $eexamnow_id = $big_data['eexamnow_id'];
    $student_id = $big_data['student_id'];
    $currentclass_id = $big_data['currentclass_id'];
    $exam_num = $big_data['exam_num'];
    $stu_name = $big_data['first_name'].' '.$big_data['last_name'];   
    $currentclass_id = $big_data['currentclass_id'];
    $eexamtime_id = $big_data['eexamtime_id'];
    $exam_alias_number = $big_data['exam_num'];
    $nom_time = $big_data['nom_time'];
    $university = $big_data['university'];
  //  echo ('student_id: '.$student_id);


  
  $sql = ' SELECT `name`
    FROM `CurrentClass` WHERE `currentclass_id` = :currentclass_id ';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
    ':currentclass_id' => $currentclass_id
    ));
    $currentclass_data = $stmt->fetch();
    
     $currentclass_name = $currentclass_data['name'];

    $sql = ' SELECT *
    FROM `Eexamtime` WHERE `eexamtime_id` = :eexamtime_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
    ':eexamtime_id' => $eexamtime_id,
    ));
    $eexamtime_data = $stmt->fetch();
  
 
    // how many and which problem are on this Exam?  - what if I gave it more than one time?

 $sql = ' SELECT DISTINCT ( problem_id), alias_num
    FROM `Eexam` WHERE `iid` = :iid AND `exam_num` = :exam_num AND `currentclass_id` = :currentclass_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
    ':iid' => $iid,
    ':exam_num' => $exam_num,
    ':currentclass_id' => $currentclass_id
    ));
    $eexam_data = $stmt->fetchAll();



    $sql = ' SELECT COUNT( DISTINCT `problem_id`)
    FROM `Eactivity` WHERE eregistration_id = :eregistration_id ';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
    ':eregistration_id' => $eregistration_id,
   
    ));
    $eactivity_count = $stmt->fetch();
    $eactivity_count = $eactivity_count[0];
   // echo ' eactivity_count '.$eactivity_count;



   $sql = ' SELECT DISTINCT (`problem_id`), `P_num_score_net`, `ec_pts`, `alias_num` 
   FROM `Eactivity` WHERE eregistration_id = :eregistration_id ORDER BY `eactivity_id` DESC LIMIT '.$eactivity_count;
  //  $sql = ' SELECT `problem_id`, `P_num_score_net`, `ec_pts`, `alias_num` 
  //  FROM (SELECT `problem_id`, `P_num_score_net`, `ec_pts`, `alias_num` FROM `Eactivity` WHERE eregistration_id = :eregistration_id ORDER BY `eactivity_id` DESC LIMIT 2) ORDER BY `alias_num` ASC';


    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':eregistration_id' => $eregistration_id,
     
   
    ));
    $eactivity_data = $stmt->fetchAll();

    $eactivity_data = array_reverse($eactivity_data);
    
    
   //  var_dump($eactivity_data);
     foreach($eactivity_data as $eactivity_datum){

        $p_num_score_net = $eactivity_datum['P_num_score_net'];
   //     echo $p_num_score_net;
     }

    $active_problem_number = count($eactivity_data);


  //  echo 'active_problem_number: '.$active_problem_number;
    //  echo ('eregistration_id: '.$eregistration_id);
//   echo ('eexamnow_id: '.$eexamnow_id);
    if ($active_problem_number == 0) {
        foreach($eexam_data as $eexam_datum){
            $sql = "INSERT INTO `Eactivity` 
                        ( `student_id`,  `alias_num`, `eexamnow_id`, `eregistration_id`,`currentclass_id`,`problem_id`) 
                VALUES  ( :student_id, :alias_num,   :eexamnow_id,   :eregistration_id,    :currentclass_id,  :problem_id)";
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                    ':student_id'=> $student_id,
                    ':alias_num' => $eexam_datum['alias_num'],
                    ':eexamnow_id' => $eexamnow_id,
                    ':eregistration_id' => $eregistration_id,
                    ':currentclass_id' => $currentclass_id,
                    ':problem_id' => $eexam_datum['problem_id'],
                    )
                    );
              }

          }

        // display the problem we just need to know the submit button was pressed and the problem that was selected
          if(isset($_POST['submit'])){

            header('Location:QRdisplayExamPblm.php?eregistration_id='.$eregistration_id.'&problem_id='.$_POST["alias_num"]);
            die;

          }
   
        ?>


<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRExam</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<style>
    table#problem_table{
    table-layout: fixed;
    width: 80%; 
   
    
    }
    caption{
        
        text-align: center;
        font-size:  20px
    }
    td {
        text-align: center;
        vertical-align: middle;
        height: 30px;
        }

</style>
</head>

<body>
<header>
<h1> Quick Response Exam </h1>
</header>

<?php

//	if(isset($_POST['pin']) || isset($_POST['problem_id']) || isset($_POST['iid'])){
		if ( isset($_SESSION['error']) ) {
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) ) {
			echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
			unset($_SESSION['success']);
		}
//	}
 
?>

<form id = "big_form" autocomplete="off" method="POST" >
<p><font color=#003399>Institution: <?php echo($university);?> </p>
<p><font color=#003399>Name: <?php echo($stu_name);?> </p>
<p><font color=#003399>Course: <?php echo($currentclass_name);?> </p>
<p><font color=#003399>Exam Number: <?php echo($exam_alias_number);?> </p>
<p><font color=#003399>Nominal Time: <?php echo($nom_time." min");?> </p>
<table  id = "problem_table">
  <caption> Select Problem</caption>
    <tr>
    <?php 
        echo '<table id = "problem_table"><tr>';
        $num_problems = 0;
        echo'<th></th>';
    foreach ($eexam_data as $eexam_datum){
       echo'<th>';
       echo $eexam_datum['alias_num'];
       $num_problems++;
       echo'</th>';
     }
     echo'<th>';
     echo 'Total';
     echo'</th>';

      
     
     // points per problem
            
        echo'<tr>';
        $total_Exam_Points = 0;
        echo'<th  style = "text-align:Left;padding-left:10px;" > Percent of Exam</th>';
        foreach($eexam_data as $eexam_datum){
            $a_num = $eexam_datum['alias_num'];
            echo '<td>';
            if( $eexamtime_data['perc_'.$a_num] == null){echo '';} else {echo $eexamtime_data['perc_'.$a_num];}
            echo'</td>';
            $total_Exam_Points = $total_Exam_Points + $eexamtime_data['perc_'.$a_num];
            $exam_pts_per_problem[$a_num] = $eexamtime_data['perc_'.$a_num];
        }
        echo'<th>';
        echo $total_Exam_Points;
        echo'</th>';





        // radio buttons:
        $i = 1;
        echo'<tr style = background-color:lightyellow>';
        echo'<th> Problem number </th>';
        foreach ($eexam_data as $eexam_datum){
            echo'<td>';
            if ($i ==1){ echo '<input  name="alias_num" type="radio"  value= '.$eexam_datum["problem_id"].'></input>';} else {
            echo '<input  name="alias_num"  required type="radio"  value= '.$eexam_datum["problem_id"].'></input>';
            }
        echo'</td>';
        $i++;
        }


      
   
           
        // computer graded points
        $prov_exam_pts_earned = 0;
        echo'<tr>';
        echo'<th  style = "text-align:Left;padding-left:10px;" > Provisional Computer Graded Problem Pts</th>';
    
        foreach($eactivity_data as $eactivity_datum){


            $a_num = $eactivity_datum['alias_num'];
            echo '<td>';
          //  echo 'a_num: '.$a_num.' -';
            if( $eactivity_datum['P_num_score_net'] == null){echo '0';} else {echo $eactivity_datum['P_num_score_net'];}
             echo'</td>';
        //     $prov_exam_pts_earned =  ($prov_exam_pts_earned +0)+ ($eactivity_datum['P_num_score_net']+0)*($exam_pts_per_problem+0);
             $prov_exam_pts_earned =  ($prov_exam_pts_earned +0.0)+ $eactivity_datum['P_num_score_net']*$exam_pts_per_problem[$a_num]/100;
        }
        echo'<th>';
        echo round($prov_exam_pts_earned*10)/10;
        echo'</th>';



        echo'</tr>';
        echo'<tr>';
        
        echo'<th  style = "text-align:Left;padding-left:10px;"> Partial Credit Points</th>';
        foreach($eactivity_data as $eactivity_datum){
            echo '<td>';
            if( $eactivity_datum['ec_pts'] == null){echo '0';} else {echo $eactivity_datum['ec_pts'];}
             echo'</td>';
        }

        echo'<tr>';
        echo'<th style = "text-align:Left;padding-left:10px;"> Reflections:</th>';
        echo'<tr>';


// Reflect

        echo'<th style = "text-align:Left;padding-left:30px;"> Reflect</th>';
        foreach($eactivity_data as $eactivity_datum){
            echo '<td>';
            if( $eactivity_datum['ec_pts'] == null){echo '0';} else {echo $eactivity_datum['ec_pts'];}
             echo'</td>';
        }

      

        echo '</table>';



?>
<!--
	<input autocomplete="false" name="hidden" type="text" style="display:none;">
	
				</br>
    <input type="hidden" id = "iid" name="iid" value="<?php echo ($iid);?>" > 
    <input type="hidden" id = "pin" name="pin" value="<?php echo ($pin);?>" >
    <input type="hidden" id = "cclass_id" name="cclass_id" value="<?php echo ($currentclass_id);?>" >
    <input type="hidden" id = "stu_name" name="stu_name" value="<?php echo ($stu_name);?>" >
	<input type="hidden" id = "student_id" name="student_id" value="<?php echo ($student_id);?>" >
	<div id ="current_class_dd">	
			<font color=#003399>Course: </font>
			
			<?php
			
						echo ('<input type = "hidden" name = "cclass_id" id = "have_cclass_id" value = "'.$currentclass_id.'"></input>'); 
						echo ('<input type = "hidden" name = "cclass_name" id = "have_cclass_name" value = "'.$cclass_name.'"></input>'); 
						echo $cclass_name;
		
		
		?>
		</br>	
		</br>
	
			
              <input type="hidden" name = "assign_num" id = "have_assign_num"  value="<?php echo ($assign_num);?>" >
            <?php
	
            echo(' &nbsp;<select name = "assign_num" id = "assign_num">');
			echo('</select>');
			?>
		</br>	
		<br>

	-->	

    
		<div id = "alias_num_div">
		
		</div>
			<br>	
          <br>
		<div id = "files_section">
		</div>  
            
            

	<p><input type = "submit" name = "submit" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	<!--  need to figure out which homeworks had reflections and are past the due date but before the date that they closes and needs rated -->
   
 
   <br>
    <div id = "peer_rating_div">
    </div>
 </form>
  
 </br>
 </br>


<?php

    // see if 1) this student is a team leader 2) The game flag has been set and 3) the phase is 2 or 3

    $sql = "SELECT team_cap FROM TeamStudentConnect WHERE student_id = :student_id AND eexamnow_id = :eexamnow_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':student_id' => $student_id,
        ':eexamnow_id' => $eexamnow_id,
   
    ));
    $teamcap_data = $stmt->fetch();

    if ( $teamcap_data!= false){
     $team_cap = $teamcap_data['team_cap'];
    } else {
        $team_cap ='';
    }
/* 
    echo ' team_cap '.$team_cap;
   echo ' globephase '.$globephase;
    echo ' game_flag '.$game_flag;
 */
    if ($team_cap ==1 && $globephase == 2 && $game_flag == 1){
        echo '<form method = "POST" action = "QRTeamCaptain.php">';
        echo ('<input type = "hidden" name = "eregistration_id" value = "'.$eregistration_id.'"></input>'); 
        echo ('<input type = "hidden" name = "globephase" value = "'.$globephase.'"></input>'); 
        echo '<p><input type = "submit" value="Go to Team Captain Input" name = to_team_cap_screen"  size="2" style = "width: 30%; background-color: #FAF1BC; color: black"/> &nbsp &nbsp </p>';
        echo '</form>';
    }
 
 ?>
  <br>
   <!--
   
	</br>
	<form method = "POST">
		<p><input type = "submit" value="Back to Login" name = "reset"  size="2" style = "width: 30%; background-color: #FAF1BC; color: black"/> &nbsp &nbsp </p>  
	</form>
    <br>
 
  
 
 

































<script>
$(document).ready( function () {
   var p_num_score_net =  [];
   var p_num_score_raw = [];
   var survey_pts = [];
   var alias_nums = [];    

       function getResults (assign_num,currentclass_id,p_num_score_net,p_num_score_raw,survey_pts){

            $.ajax({
                url: 'getassign_id.php',
                method: 'post',
                data: {assign_num:assign_num,currentclass_id:currentclass_id}
				
		    }).done(function(assignids){
					var assign_ids = JSON.parse(assignids);
                   var n = assign_ids.length;
                    for (i=0;i<n;i++){
                       var assign_id = JSON.parse(assign_ids[i]);
                             $.ajax({
                            url: 'getresults.php',
                            method: 'post',
                            data: {assign_id:assign_id,student_id:student_id}
                        
                        }).done(function(results){
                            var result = JSON.parse(results);
                             p_num_score_net[i] = result["p_num_score_net"];
                             p_num_score_raw[i] = result["p_num_score_raw"];
                             survey_pts[i] = result["survey_pts"];
   //                           console.log('p_num_score_net1  '+ p_num_score_net[i]);
                       });
                        
                     }
                 }); 
                   
         }

   

	// already been through and worked a problem and now getting another one all of the input fields should be defined just need another problem
		if($('#have_assign_num').val()!= undefined){
          var assign_num = $('#have_assign_num').val();	
 //       console.log("assign_num: "+assign_num);
        }
        
    if($('#have_iid').val()!= undefined && $('#have_cclass_id').val()!= undefined && $('#have_cclass_name').val()!= undefined && $('#have_assign_num').val()!= undefined){
	
		var iid = $('#have_iid').val();
 		var cclass_id = $('#have_cclass_id').val();
		var cclass_name = $('#have_cclass_name').val();
	
//			console.log("iid: "+iid);
//			console.log("cclass_id: "+cclass_id);
//			console.log("cclass_name: "+cclass_name);
			
			$.ajax({
					url: 'getactivealias.php',
					method: 'post',
					data: {assign_num:assign_num,currentclass_id:cclass_id}
				
				}).done(function(activealias){
//				console.log('Im at 594');
					activealias = JSON.parse(activealias);
					 	 $('#alias_num_div').empty();
                         
					n = activealias.length;
						$('#alias_num_div').append(" <font color=#003399> Select Problem for this Assignment : </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;
						for (i=0;i<n;i++){
							$('#alias_num_div').append('<input  name="alias_num"  type="radio"  value="'+activealias[i]+'"/> '+activealias[i]+'&nbsp; &nbsp; &nbsp;') ;
                            alias_nums[i] = activealias[i];
                    }
   //                 console.log(' alias_nums1 '+alias_nums);
                   /*  
                    	$.ajax({
                            url: 'getresults.php',
                            method: 'post',
                            data: {assign_num:assign_num,currentclass_id:currentclass_id,student_id:student_id}
				
                            }).done(function(results){
                        //	    console.log ('Im on 611');
                               results = JSON.parse(results);
                                    // $('#alias_num_div').empty();
                                    n = results.length;
                                      console.log(' alias_nums2 '+alias_nums);   
                                    console.log('  n2 '+n);
                                 $('#alias_num_div').append('<tr><td>&nbsp; &nbsp; &nbsp;</td></tr>') ;
                                 $('#alias_num_div').append('<td>&nbsp; &nbsp; Pblm Points &nbsp;</td>') ;
                   //             console.log('typeof results'+typeof results);
                   //             console.log(' results'+results.activity_id);
                                //console.log('typeof results'+typeof results);
                                console.log(results);
                                    for (i=0;i<n;i++){
                                    var row = results[i];
                                        if(row.p_num_score_net != null){
                                            $('#alias_num_div').append('<td>&nbsp; &nbsp;'+row["p_num_score_net"]+'&nbsp;</td>') ;
                                        } else {
                                            
                                             $('#alias_num_div').append('<td></td>') ;
                                            
                                        }
                                        
                                    }
                            });
                    
                     */
				}) 
	} 
			
			// this is getting the assignment number once the course has been selected
		
        $('#alias_num_div').empty();
        
       if($('#have_cclass_id2').val()!= undefined){
          var cclass_id = $('#have_cclass_id2').val();	
//        console.log("cclass_id: "+cclass_id);
        }
        else if($('#have_cclass_id').val()!= undefined){
          var cclass_id = $('#have_cclass_id').val();	
//        console.log("cclass_id:----- "+cclass_id);
        }
        
        // var cclass_id = $('#have_cclass_id').val(); 
  //          console.log("cclass_id: "+cclass_id);
            
            var currentclass_id = cclass_id;
      if (!isNaN(cclass_id))  {
               $.ajax({
					url: 'getactiveassignments2.php',
					method: 'post',
					data: {currentclass_id:currentclass_id}
				}).done(function(activeass){
					activeass = JSON.parse(activeass);
					 	 $('#assign_num').empty();
				
					n = activeass.length;
                   
						$('#assign_num').append("<option selected disabled hidden>  </option>") ;
                    
						for (i=0;i<n;i++){
							  $('#assign_num').append('<option  value="' + activeass[i] + '">' + activeass[i] + '</option>');
                              		 

                            //  $('#assign_num').append('<option '+if(activeass[i]==assign_num){"selected";} +'value="' + activeass[i] + '">' + activeass[i] + '</option>');
					}
                    
                    if (!isNaN(assign_num))  { $('#assign_num').val(assign_num).change();}
				}) 
      } else {
 //                  console.log('need to get ir from drop down');
                   
                 
		
        $("#current_class_dd").change(function(){
            var currentclass_id = $("#current_class_dd").val();
//			console.log ('currentclass_id: '+currentclass_id);
      
            $.ajax({
					url: 'getactiveassignments2.php',
					method: 'post',
					data: {currentclass_id:currentclass_id}
				}).done(function(activeass){
					activeass = JSON.parse(activeass);
					 $('#assign_num').empty();
				
					n = activeass.length;
  //                  console.log ('typeof activeass '+typeof activeass);
 //                    console.log ('n '+n);
					$('#assign_num').append("<option selected disabled hidden>  </option>") ;
					for (i=0;i<n;i++){
							  $('#assign_num').append('<option  value="' + activeass[i] + '">' + activeass[i] + '</option>');
					
                    }
				
                
                }) 
		}) 
      }	
      

     	$("#assign_num").change(function(){
                var	 assign_num = $("#assign_num").val();
                //   $('#have_assign_num').val(assign_num);
                if (!isNaN(cclass_id))  {currentclass_id = cclass_id;} else {
          
                var currentclass_id = $("#current_class_dd").val();}
           
 //           console.log('assign_num: '+assign_num);
 //           console.log(' currentclass_id: '+currentclass_id);
//			 console.log ('currentclass_id 2nd time: '+currentclass_id);
           



	         $.ajax({
					url: 'getactivealias.php',
					method: 'post',
					data: {assign_num:assign_num,currentclass_id:currentclass_id},
				 
                    success: function(activealias,status,xhr){
  //                    console.log ('Im on 827 really'); 
  //                    console.log (activealias);
                      activealias = JSON.parse(activealias);
    //                  console.log (activealias);    
			 	      $('#alias_num_div').empty();
					    n = activealias.length;
						$('#alias_num_div').append(" <font color=#003399> Select Problem for this Assignment : </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;
                       $('#alias_num_div').append('<div style = "overflow-x:auto;">');

                       $('#alias_num_div').append('<table class = "main_table">');
						$('#alias_num_div').append('<th  style="text-align:center" width="20%"> Select</th>') ;
                      
                         
                        for (i=0;i<n;i++){
							//could put in code to get from the activity table which problems have been attempted and which are complete and color the radio buttons different
                        
                               $('#alias_num_div').append('<th  style="text-align:center" width="10%" ><input  name="alias_num"  type="radio"  value="'+activealias[i]+'"/> '+activealias[i]+'</th>') ;
                        
                                alias_nums[i] = activealias[i];
                        
 
                           }
   //                        console.log('alias_nums 846 two ' + alias_nums); 
                             // console.log(' assign_num: '+assign_num);
                             // console.log(' currentclass_id: '+currentclass_id);
                              student_id = $("#student_id").val();
                             // console.log(' student_id: '+student_id);
                              

                              $.ajax({
                                    url: 'getresults.php',
                                    method: 'post',
                                    data: {assign_num:assign_num,currentclass_id:currentclass_id,student_id:student_id},
                                    success: function(results,status,xhr){
                                      //  console.log ('Im on 842');
                                        
                                        
    //                                   console.log('alias_nums 861 three ' + alias_nums); 

                                        results = JSON.parse(results);
                                        n2 = results.length;
  //                                            console.log(' alias_nums three '+alias_nums);   
   //                                        console.log('  n2 '+n2);
    //                                    console.log('  n '+n);
    //                                       console.log(results);
  //                                          
                                      // write the net score in the table 
                                        $('#alias_num_div').append('<tr><td>&nbsp;</td></tr>') ;
                                        $('#alias_num_div').append('<td  style="text-align:center" > Provisional Pts </td>') ;
                                        for (i=0;i<n;i++){
                                            var found = false;
                                             for (j=0;j<n2;j++){ 
                                                var row = results[j];
                                                   if (typeof row.alias_num != "undefined"  ){
                                                         if(row.alias_num ==activealias[i]){
                                                             found = true;
                                                             var row_found = row;
                                                         }
                                                    }                                                       
                                             }
                                             if (found){
                                                if (row_found["p_num_score_net"] != null){
                                                  $('#alias_num_div').append('<td  style="text-align:center" >'+row_found["p_num_score_net"]+'</td>') ;
                                                } else {
                                                  $('#alias_num_div').append('<td style="text-align:center"  > 0 </td>') ;
                                                }
                                             } else 
                                             {
                                                 $('#alias_num_div').append('<td></td>') ;  
                                             }                                                           
                                        }   
                                        
                                        // write the extra credit from the feedback
                                        $('#alias_num_div').append('<tr><td>&nbsp;</td></tr>') ;
                                        $('#alias_num_div').append('<td  style="text-align:center"> Extra Credit </td>') ;
                                        for (i=0;i<n;i++){
                                            var found = false;
                                             for (j=0;j<n2;j++){ 
                                                var row = results[j];
                                                   if (typeof row.alias_num != "undefined"  ){
                                                         if(row.alias_num ==activealias[i]){
                                                             found = true;
                                                             var row_found = row;
                                                         }
                                                    }                                                       
                                             }
                                             if (found){
                                                if (row_found["ec_pts"] != null){
                                                  $('#alias_num_div').append('<td  style="text-align:center" >'+row_found["ec_pts"]+'</td>') ;
                                                } else {
                                                  $('#alias_num_div').append('<td  style="text-align:center"> &nbsp; </td>') ;
                                                }
                                             } else 
                                             {
                                                 $('#alias_num_div').append('<td></td>') ;  
                                             }                                                           
                                        }                                        
                                            
                                        
                       
                                            
    
    
                                        $('#alias_num_div').append('<tr><td>&nbsp; </td></tr>') ;
                                        $('#alias_num_div').append('<td  style="text-align:center"> Late Penalty </td>') ;
                                        for (i=0;i<n;i++){
                                            var found = false;
                                             for (j=0;j<n2;j++){ 
                                                var row = results[j];
                                                   if (typeof row.alias_num != "undefined"  ){
                                                         if(row.alias_num == activealias[i]){
                                                             found = true;
                                                             var row_found = row;
                                                         }
                                                    }                                                       
                                             }
                                             if (found){
  //                                               console.log('late p '+row_found.late_penalty );
 //                                               console.log('raw p '+row_found["p_num_score_raw"] );
                                                if (row_found["late_penalty"] != null){
                                                    if (row_found["late_penalty"] > row_found["p_num_score_raw"]){var late_penalty = row_found["p_num_score_raw"];} else {var late_penalty = row_found["late_penalty"];}
                                                    $('#alias_num_div').append('<td  style="text-align:center">'+late_penalty+'</td>') ;
                                                } else {
                                                  $('#alias_num_div').append('<td>&nbsp; </td>') ;
                                                }
                                             } else 
                                             {
                                                 $('#alias_num_div').append('<td></td>') ;  
                                             }                                                           
                                        }
                                 
                                        
                                       $('#alias_num_div').append('<tr><td>&nbsp; </td></tr>') ;
                                       $('#alias_num_div').append('<td style="text-align:center" > Survey </td>') ;
                                        for (i=0;i<n;i++){
                                            var found = false;
                                            for (j=0;j<n2;j++){ 
                                                var row = results[j];
                                                   if (typeof row.alias_num != "undefined" ){
                                                         if(row.alias_num == activealias[i] && row.survey_pts > 0 && row.survey_pts != null ){
                                                             var row_found = row;
  //                                                           console.log (row.survey_pts);
                                                             found = true;
                                                         }
                                                    }                                                       
                                            }
                                             if (found){
                                                 $('#alias_num_div').append('<td  style="text-align:center"> '+row_found["survey_pts"]+' </td>') ;
                                             } else 
                                             {
                                                 $('#alias_num_div').append('<td></td>') ;  
                                             }                                                           
                                        }

          // start problem  feedback
                                        $('#alias_num_div').append('<tr><td>&nbsp;</td></tr>') ;
                                        $('#alias_num_div').append('<td style="text-align:center">Comments</td>') ;
                                       for (i=0;i<n;i++){  // go through and see if they have attempted that part at all
                                            var found = false;
                                            var fbtext = false;
                                            for (j=0;j<n2;j++){
                                                  var row = results[j];
                                                   if (typeof row.alias_num != "undefined" ){
                                                              found = true;
                                                             if (row.fb_problem != null){
                                                                     if (row.fb_problem.length > 1 ) { var row_found = row; fbtext = true;}
                                                              }
                                                    }
                                              }       
                                            
                                             if(fbtext && found){
                                                  $('#alias_num_div').append('<td  style="text-align:center"> '+row_found["fb_problem"].replace(/_/g, ' ')+'</td>') ;
                                             } else {
                                             $('#alias_num_div').append('<td> </td>') ;  
                                             } 
                                                                                       
                                        }

                             //  }
                               
                               
                               
                               
        // start reflection part of the feedback
                                        $('#alias_num_div').append('<tr><td>&nbsp;</td></tr>') ;
                                        $('#alias_num_div').append('<td style="text-align:center">Reflect </td>') ;
                                        for (i=0;i<n;i++){
                                            var found = false;
                                            var wrote = false;
                                            var fbpts = false;
                                            var fbtext = false;
                                            for (j=0;j<n2;j++){ 
                                                var row = results[j];
                                                   if (typeof row.alias_num != "undefined" ){
                                                         if(row.alias_num == activealias[i] && row.reflect_flag ==1){
                                                              found = true;
                                                             if (row.reflect_pts > 0 ) { var row_found = row; fbpts = true;}
                                                             if (row.fb_reflect != null){
                                                                if (row.fb_reflect.length > 1 ) { var row_found = row; fbtext = true;}
                                                             }
                                                             if (row.reflect_text != null){
                                                                 if (row.reflect_text.length > 2){
                                                                     wrote = true;
                                                                     if (row.reflect_pts > 0 ) { var row_found = row; fbpts = true;}
                                                                     if (row.fb_reflect.length > 1 ) { var row_found = row; fbtext = true;}
                                                                  }
                                                              }
                                                         }                                                       
                                                    }
                                                    
                                            }
                                             if(fbpts && fbtext){
                                                  $('#alias_num_div').append('<td  style="text-align:center">'+row_found["reflect_pts"]+'  <br> '+row_found["fb_reflect"].replace(/_/g, ' ')+'</td>') ;
                                             } else if (fbpts) {
                                                  $('#alias_num_div').append('<td  style="text-align:center">'+row_found["reflect_pts"]+'  </td>') ;
                                             } else if (fbtext) {
                                                  $('#alias_num_div').append('<td  style="text-align:center"> '+row_found["fb_reflect"].replace(/_/g, ' ')+'</td>') ;
                                             }
                                             else if (found && wrote){
                                                 $('#alias_num_div').append('<td style="text-align:center"> &check; </td>') ;
                                             } else if(found){
                                                   $('#alias_num_div').append('<td style="text-align:center"> x </td>') ;
                                             } else {
                                            
                                             $('#alias_num_div').append('<td> </td>') ;  
                                             }                                                          
                                        }



                    // explore feedback           
                                       $('#alias_num_div').append('<tr><td>&nbsp; </td></tr>') ;
                                       $('#alias_num_div').append('<td  style="text-align:center"> Explore </td>') ;
                                        for (i=0;i<n;i++){
                                            var found = false;
                                            var wrote = false;
                                            var fbpts = false;
                                            var fbtext = false;
                                            for (j=0;j<n2;j++){ 
                                                var row = results[j];
                                                   if (typeof row.alias_num != "undefined" ){
                                                         if(row.alias_num == activealias[i] && row.explore_flag ==1){
                                                              found = true;
                                                             if (row.explore_pts > 0 ) { var row_found = row; fbpts = true;}
                                                             if (row.fb_explore != null){
                                                                 if (row.fb_explore.length > 2 ) { var row_found = row; fbtext = true;}
                                                             }
                                                              if (row.explore_text != null){
                                                                 if (row.explore_text.length > 2){
                                                                     wrote = true;
                                                                  }
                                                              }
                                                         }                                                       
                                                    }
                                            }
                                             if(fbpts && fbtext){
                                                  $('#alias_num_div').append('<td  style="text-align:center">'+row_found["explore_pts"]+'  <br> '+row_found["fb_explore"].replace(/_/g, ' ')+'</td>') ;
                                             } else if (fbpts) {
                                                  $('#alias_num_div').append('<td  style="text-align:center">'+row_found["explore_pts"]+'  </td>') ;
                                             } else if (fbtext) {
                                                  $('#alias_num_div').append('<td  style="text-align:center"> '+row_found["fb_explore"].replace(/_/g, ' ')+'</td>') ;
                                             }
                                             else if (found && wrote){
                                                 $('#alias_num_div').append('<td style="text-align:center"> &check; </td>') ;
                                             } else if(found){
                                                   $('#alias_num_div').append('<td style="text-align:center"> x </td>') ;
                                             } else {
                                            
                                             $('#alias_num_div').append('<td> </td>') ;  
                                             }                                                          
                                        }
                   // connect feedback            
                                       $('#alias_num_div').append('<tr><td> &nbsp;</td></tr>') ;
                                       $('#alias_num_div').append('<td style="text-align:center"> Connect </td>') ;
                                        for (i=0;i<n;i++){
                                            var found = false;
                                            var wrote = false;
                                            var fbpts = false;
                                            var fbtext = false;
                                            for (j=0;j<n2;j++){ 
                                                var row = results[j];
                                                   if (typeof row.alias_num != "undefined" ){
                                                         if(row.alias_num == activealias[i] && row.connect_flag ==1){
                                                              found = true;
                                                             if (row.connect_pts > 0 ) { var row_found = row; fbpts = true;}
                                                             if (row.fb_connect != null){
                                                                 if (row.fb_connect.length > 2 ) { var row_found = row; fbtext = true;}
                                                             }
                                                              if (row.connect_text != null){
                                                                 if (row.connect_text.length > 2){
                                                                     wrote = true;
                                                                  }
                                                              }
                                                         }                                                       
                                                    }
                                            }
                                             if(fbpts && fbtext){
                                                  $('#alias_num_div').append('<td  style="text-align:center">'+row_found["connect_pts"]+'  <br> '+row_found["fb_connect"].replace(/_/g, ' ')+'</td>') ;
                                             } else if (fbpts) {
                                                  $('#alias_num_div').append('<td  style="text-align:center">'+row_found["connect_pts"]+'  </td>') ;
                                             } else if (fbtext) {
                                                  $('#alias_num_div').append('<td  style="text-align:center"> '+row_found["fb_connect"].replace(/_/g, ' ')+'</td>') ;
                                             }
                                             else if (found && wrote){
                                                 $('#alias_num_div').append('<td style="text-align:center"> &check; </td>') ;
                                             } else if(found){
                                                   $('#alias_num_div').append('<td style="text-align:center"> x </td>') ;
                                             } else {
                                            
                                             $('#alias_num_div').append('<td> </td>') ;  
                                             }                                                          
                                        }
                        // society
                                       $('#alias_num_div').append('<tr><td> &nbsp;</td></tr>') ;
                                       $('#alias_num_div').append('<td style="text-align:center"> Society </td>') ;
                                        for (i=0;i<n;i++){
                                            var found = false;
                                            var wrote = false;
                                            var fbpts = false;
                                            var fbtext = false;
                                            for (j=0;j<n2;j++){ 
                                                var row = results[j];
                                                   if (typeof row.alias_num != "undefined" ){
                                                         if(row.alias_num == activealias[i] && row.society_flag ==1){
                                                              found = true;
                                                             if (row.society_pts > 0 ) { var row_found = row; fbpts = true;}
                                                             if (row.fb_society != null){
                                                                 if (row.fb_society.length > 2 ) { var row_found = row; fbtext = true;}
                                                             }
                                                              if (row.society_text != null){
                                                                 if (row.society_text.length > 2){
                                                                     wrote = true;
                                                                  }
                                                              }
                                                         }                                                       
                                                    }
                                            }
                                             if(fbpts && fbtext){
                                                  $('#alias_num_div').append('<td  style="text-align:center">'+row_found["society_pts"]+'  <br> '+row_found["fb_society"].replace(/_/g, ' ')+'</td>') ;
                                             } else if (fbpts) {
                                                  $('#alias_num_div').append('<td  style="text-align:center">'+row_found["society_pts"]+'  </td>') ;
                                             } else if (fbtext) {
                                                  $('#alias_num_div').append('<td  style="text-align:center"> '+row_found["fb_society"].replace(/_/g, ' ')+'</td>') ;
                                             }
                                             else if (found && wrote){
                                                 $('#alias_num_div').append('<td style="text-align:center"> &check; </td>') ;
                                             } else if(found){
                                                   $('#alias_num_div').append('<td style="text-align:center"> x </td>') ;
                                             } else {
                                            
                                             $('#alias_num_div').append('<td> </td>') ;  
                                             }                                                          
                                        }
                                        
             // write the score from the feedback
                                        $('#alias_num_div').append('<tr><td>&nbsp;</td></tr>') ;
                                        $('#alias_num_div').append('<td  style="text-align:center"> Actual Pts</td>') ;
                                        for (i=0;i<n;i++){
                                            var found = false;
                                             for (j=0;j<n2;j++){ 
                                                var row = results[j];
                                                   if (typeof row.alias_num != "undefined"  ){
                                                         if(row.alias_num ==activealias[i]){
                                                             found = true;
                                                             var row_found = row;
                                                         }
                                                    }                                                       
                                             }
                                             if (found){
                                                if (row_found["fb_p_num_score_net"] != null){
                                                  $('#alias_num_div').append('<td  style="text-align:center" >'+row_found["fb_p_num_score_net"]+'</td>') ;
                                                } else {
                                                  $('#alias_num_div').append('<td  style="text-align:center"> &nbsp; </td>') ;
                                                }
                                             } else 
                                             {
                                                 $('#alias_num_div').append('<td></td>') ;  
                                             }                                                           
                                        }                                       


                  // write the score from the feedback
                                        $('#alias_num_div').append('<tr><td>&nbsp;</td></tr>') ;
                                        $('#alias_num_div').append('<td  style="text-align:center; background-color: lightgray" > Total Pts </td>') ;
                                        for (i=0;i<n;i++){
                                            var found = false;
                                             for (j=0;j<n2;j++){ 
                                                var row = results[j];
                                                   if (typeof row.alias_num != "undefined"  ){
                                                         if(row.alias_num ==activealias[i]){
                                                             found = true;
                                                             var row_found = row;
                                                         }
                                                    }                                                       
                                             }
                                             if (found){
                                                if (row_found["fb_probtot_pts"] != null){
                                                  $('#alias_num_div').append('<td  style="text-align:center ; background-color: lightgray" >'+row_found["fb_probtot_pts"]+'</td>') ;
                                                } else {
                                                  $('#alias_num_div').append('<td  style="text-align:center ; background-color: lightgray"> &nbsp; </td>') ;
                                                }
                                             } else 
                                             {
                                                 $('#alias_num_div').append('<td style="text-align:center ; background-color: lightgray"></td>') ;  
                                             }                                                           
                                        }                                       
                                        
                                        
                                                    $('#alias_num_div').append('<tr ></tr>') ;
                   // look for files that have been submitted to the system for the problem need to do this with AJAX i think
                                        //  $('#files_section').append('</tr><tr>') ;
                                        //  $('#alias_num_div').append('</tr><tr>') ;
                                       $('#alias_num_div').append('<tr><td>&nbsp;</td></tr>') ;
                                       $('#alias_num_div').append('<td style="text-align:center"> Files Uploaded </td>') ;
                                        
                                        
                                        var activityIds = [];
                                         for (j=0;j<n2;j++){ 
                                             var row = results[j];
                                             activityIds[j] =  row.activity_id ;
   //                                           console.log(' activityIds  '+activityIds);
                                         }  
                                        $.ajax({
                                        url: 'getfilenumber.php',
                                        method: 'post',
                                        data: {activity_ids:activityIds},
                                        
                                            success: function(num_files,status,xhr){
                                                num_files = JSON.parse(num_files);
      //                                            console.log (' num files '+ num_files);
                                                  var position = '';
                                                 for (i=0;i<n;i++){
                                                    var found = false;
                                                     for (j=0;j<n2;j++){ 
                                                        var row = results[j];
                                                           if (typeof row.alias_num != "undefined"  ){
                                                                 if(row.alias_num == activealias[i]){
                                                                     found = true;
                                                                     position = j;
                                                                 }
                                                            }                                                       
                                                     }
                                                     if (found){
                                                         
                                                        if (num_files[position] != null){
                                                          $('#alias_num_div').append('<td style="text-align:center">'+num_files[position]+'</td>') ;
                                                          position = '';
                                                        } else {
                                                          $('#alias_num_div').append('<td style="text-align:center"> 0 </td>') ;
                                                        }
                                                     } else 
                                                     {
                                                         $('#alias_num_div').append('<td></td>') ;  
                                                     }                                                           
                                                }
     
// should put in stuff from the assignscore for the points for the overall assignment and the weights for each part from assigntime table

                                                    
                                                    $('#alias_num_div').append('</table>') ;
                                                    $('#alias_num_div').append('</div>') ;
                                             }
                                        
                                        });
                                    
                                    }   
                              
                               });

                        }
                 
                 });
				
                       
                });
            });
             
    
                    
                       // this bit should get the peer rating reflections from the assignment
            /*               
                          $.ajax({
                           url: 'get_peer_reflections.php',
                            method: 'post',
                            data: {assign_num:assign_num,currentclass_id:currentclass_id}
                          }).done(function(peer_reflections){
                      //      console.log(' peer_reflections: '+peer_reflections);
                           peer_reflections = JSON.parse(peer_reflections);
                            
                            const peer_reflection = Object.values(peer_reflections);
                            
                      //      console.log(' peer_reflection: '+peer_reflection);
                             n = peer_reflection.length;
                      //       console.log(' n: '+n);
                            
                        if(n>0) {    
                            $('#peer_rating_div').append("<hr><br> <font color=#003399> The following reflections from assignment "+assign_num+" are ready to be rated </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;
                            for (i=0;i<n;i++){
                                //could put in code to get from the activity table which problems have been attempted and which are complete and color the radio buttons different
                                $('#peer_rating_div').append('<input  name="peer_num"  type="radio"  value="'+peer_reflection[i]+'"/> '+peer_reflection[i]+'&nbsp; &nbsp; &nbsp;') ;
                             }
                             var text = '<br><p><input type = "submit" name = "Rate Reflection" formaction="peer_rating.php" value="Rate Reflection" id="Rate Reflection" size="2" style = "width: 30%; background-color:navy; color: white"/> <br><hr>&nbsp &nbsp </p>';
                                $('#peer_rating_div').append(text) ;  
                        }
                    })
                    
 
     
    if(isset($assigntime_id)){
       $now = time();
        $sql = 'SELECT `peer_refl_t`,`peer_refl_n` FROM Assigntime WHERE assigntime_id = :assigntime_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':assigntime_id' => $assigntime_id));
        $assigntime_data = $stmt -> fetch();
        $peer_refl_t = $assigntime_data['peer_refl_t'];
         $peer_refl_n = $assigntime_data['peer_refl_n'];
        $due_cutoff = $now - $peer_refl_t*24*60*60;


        $sql = 'SELECT * FROM Assigntime WHERE assigntime_id = :assigntime_id AND UNIX_TIMESTAMP(`due_date`) <= :now AND UNIX_TIMESTAMP(`due_date`) >= :due_cutoff '; 
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
        ':assigntime_id' => $assigntime_id,
        ':now' => $now,
        ':due_cutoff' => $due_cutoff,
        ));
        $assigntime_data = $stmt -> fetch();
       $current_class_id_peer = $assigntime_data['current_class_id'];
       $assign_num_peer = $assigntime_data['assign_num'];
       $assign_num_peer = $assigntime_data['assign_num'];
       $iid_peer = $assigntime_data['iid'];
       $reflections = array(); 
       for ($i=0;$i<=20;$i++){
            if($assigntime_data['perc_ref_'.$i]>0){$reflections['perc_ref_'.$i] = $assigntime_data['perc_ref_'.$i];}
            if($assigntime_data['perc_exp_'.$i]>0){$reflections['perc_exp_'.$i] = $assigntime_data['perc_exp_'.$i];}
            if($assigntime_data['perc_con_'.$i]>0){$reflections['perc_con_'.$i] = $assigntime_data['perc_con_'.$i];}
            if($assigntime_data['perc_soc_'.$i]>0){$reflections['perc_soc_'.$i] = $assigntime_data['perc_soc_'.$i];}
            
        }
   
      print_r( $reflections);
     }  

 */
 // });
//});
</script>


</body>
</html>



