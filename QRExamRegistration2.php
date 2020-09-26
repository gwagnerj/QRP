<?php
	require_once "pdo.php";
	session_start();
	
 	// this is the normal place to start for students taking an exam and is the file redirected to from QRexam.com and QRexamPblm.org and then goes the the QRExam.php 
//  This file takes the input from the examanee and puts it in the examactivity table then goes to QRExam once this is complete
    $complete = '';
    $exam_num = '';
    $alias_num = '';
    $iid = '';
    $cclass_id = '';
    if (isset($_POST['pin'])){$pin = $_POST['pin'];} else {$pin ='';}
    $exam_code ='';
    $problem_id = '';
	$exam_code_error = 0; 
	$stu_name = '';
	$instr_last='';
    $cclass_name='';
    $dex=0;
    $examactivity_id = '';
  
       
        if (isset($_GET['student_id'])){
      $student_id =   $_GET['student_id'];
     //   echo(' $student_id  '.$student_id);
    //  die();
     
      
       $sql = 'SELECT * FROM Student WHERE `student_id` = :student_id';
        $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':student_id' => $student_id));
         $student_data = $stmt -> fetch();
         $first_name = $student_data['first_name'];
         $last_name = $student_data['last_name'];
         $stu_name = $first_name.' '.$last_name;
         $university = $student_data['university'];
   
    // look in the StudentCurrentClassConnect table to see how many entries there are if only one then go that course assignment
      $sql = 'SELECT COUNT(`currentclass_id`) FROM `StudentCurrentClassConnect` WHERE `student_id` = :student_id';
      $stmt = $pdo->prepare($sql);
              $stmt ->execute(array(
            ':student_id' => $student_id
              ));
             $num_classes = $stmt -> fetchColumn();
             
            // echo('$num_classes: '.$num_classes);
        if($num_classes ==0){
            // need to go to the select class  either modify this file to be more general or make a new file !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
              header("Location: stu_getclass.php?student_id=".$student_id);
              return; 
        } elseif ($num_classes == 1){
           // echo (' 52 ');
              // student has just one class so no need to select 
              // we can get there pin and class_id
            $sql = 'SELECT * FROM `StudentCurrentClassConnect` WHERE `student_id` = :student_id';
            $stmt = $pdo->prepare($sql);
             $stmt->execute(array(':student_id' => $student_id));
             $class_data = $stmt -> fetch();
             $pin = $class_data['pin'];   
             $currentclass_id = $class_data['currentclass_id'];
             
              $sql = 'SELECT * FROM `CurrentClass` WHERE `currentclass_id` = :currentclass_id';
            $stmt = $pdo->prepare($sql);
             $stmt->execute(array(':currentclass_id' => $currentclass_id));
             $cclass_data = $stmt -> fetch();
             $iid = $cclass_data['iid'];   
             $cclass_name = $cclass_data['name']; 
             // echo('$iid: '.$iid);
             $cclass_id = $currentclass_id;
            
        } else {
   // we have more than one currentclass - not sure this does anything we took care of it down below it looks like
   /*          
 
            $sql = 'SELECT * FROM `StudentCurrentClassConnect` WHERE `student_id` = :student_id';
            $stmt = $pdo->prepare($sql);
             $stmt->execute(array(':student_id' => $student_id));
             $class_data = $stmt -> fetch();
            
             
             $pin = $class_data['pin'];   
              echo (' pin '.$pin);
             
             $currentclass_id = $class_data['currentclass_id'];
             
              $sql = 'SELECT * FROM `CurrentClass` WHERE `currentclass_id` = :currentclass_id';
            $stmt = $pdo->prepare($sql);
             $stmt->execute(array(':currentclass_id' => $currentclass_id));
             $cclass_data = $stmt -> fetch();
             $iid = $cclass_data['iid'];   
             $cclass_name = $cclass_data['name']; 
             // echo('$iid: '.$iid);
             $cclass_id = $currentclass_id; 
             // put a while $class data not false and create the drop down list or do it with JS
         //    $pin = $class_data['pin'];   
         //    $currentclass_id = $class_data['currentclass_id'];
             */
        }
       
       
    } else {
        
         $_SESSION['error'] = 'lost the student_id in QRExamRegistration2';
         header('Location:  QRExamRegistration.php');
        die;
    }
      if(isset($_POST['exam_code'])){
       $exam_code =  $_POST['exam_code'];
      }
 

 // Go get the problem id from the Exam table
	if(isset($_POST['submit_form'])&& isset($_POST['iid']) && isset($_POST['cclass_id'])&& isset($_POST['exam_code'])  ){
		$exam_code = htmlentities($_POST['exam_code']);
		$cclass_id = htmlentities($_POST['cclass_id']);
		$iid = htmlentities($_POST['iid']);
        
        
            // Check the exam_code to see if it matches the value in the exam_time table 
            if (strlen($examactivity_id)<=0){  
            $sql = " SELECT * FROM `Examtime` WHERE iid = :iid AND exam_code = :exam_code AND currentclass_id = :currentclass_id"   ;
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                         ':exam_code' => $exam_code,
                         ':iid' => $iid,
                         ':currentclass_id' => $cclass_id,
                    ));
                    $row8 = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($row8 != false){
                        $examtime_id = $row8['examtime_id']; 
                        $work_time = $row8['work_time'];
                        $exam_num = $row8['exam_num'];
                        
     
                      // get the problem(s)
                      
                            
                            $sql = " SELECT * FROM `Exam` WHERE iid = :iid AND exam_num = :exam_num AND currentclass_id = :currentclass_id" ;
                           $stmt = $pdo->prepare($sql);
                            $stmt -> execute(array(
                             ':exam_num' => $exam_num,
                             ':iid' => $iid,
                             ':currentclass_id' => $cclass_id,
                        ));
                            $rows = $stmt->fetchALL(PDO::FETCH_ASSOC);
                            
                           // $n = count($row);
                          //  echo('n = '.$n);
                         
                          $problem_id1 = $problem_id2 =  $problem_id3 = $problem_id4 =$problem_id5 = 0;
                          
                          foreach ($rows as $row){
                               // print_r ($row);
                                   if ($row['alias_num']==1){$problem_id1=$row['problem_id'];}
                                   if ($row['alias_num']==2){$problem_id2=$row['problem_id'];}
                                   if ($row['alias_num']==3){$problem_id3=$row['problem_id'];}
                                   if ($row['alias_num']==4){$problem_id4=$row['problem_id'];}
                                   if ($row['alias_num']==5){$problem_id5=$row['problem_id'];}
                           }
              // check to see if there is already an entry and they are trying to re-register then just read the Examactivity table
                

                $sql = " SELECT * FROM `Examactivity` WHERE iid = :iid AND exam_code = :exam_code AND currentclass_id = :currentclass_id AND pin = :pin AND examtime_id = :examtime_id"   ;
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                         ':exam_code' => $exam_code,
                         ':iid' => $iid,
                         ':currentclass_id' => $cclass_id,
                         ':pin' => $pin,
                         ':examtime_id' => $examtime_id,
                    ));
                    
                    $row8 = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($row8 != false){
                            $examactivity_id = $row8['examactivity_id'];        
                    } else {      



                // ----------------------- put the entry into the examactivity table for this user  ------------------------------------------------------
                                

                            // get the pin from the student class connect table
                            
                               $sql = 'SELECT * FROM `StudentCurrentClassConnect` WHERE `student_id` = :student_id AND currentclass_id = :currentclass_id';
                                $stmt = $pdo->prepare($sql);
                                 $stmt->execute(array(
                                 ':student_id' => $student_id,
                                 ':currentclass_id' => $cclass_id
                                 
                                 ));
                                 $class_data = $stmt -> fetch();
                                
                                 
                                 $pin = $class_data['pin'];   
     //                             echo (' pin '.$pin);
                                   $dex = ($pin-1) % 199 + 2; // % is PHP mudulus function - changing the PIN to an index between 2 and 200



                                
                                        
                            $_SESSION['examactivity_id'] = $examactivity_id;
                            $complete = 'QRExam.php'; 
                            $sql = 'INSERT INTO `Examactivity` (examtime_id, iid, currentclass_id, name, work_time,exam_code, dex, pin, problem_id1, problem_id2, problem_id3, problem_id4, problem_id5,suspend_flag,extend_time_flag,minutes,taker_id)	
                            VALUES (:examtime_id, :iid, :currentclass_id,:name ,:work_time, :exam_code, :dex, :pin, :problem_id1, :problem_id2, :problem_id3, :problem_id4, :problem_id5,0,0,:extend_time,:taker_id)';
                            $stmt = $pdo->prepare($sql);
                            $stmt -> execute(array(
                            ':examtime_id' => $examtime_id,
                            ':iid' => $iid,
                            ':currentclass_id' => $cclass_id,
                            ':name' => $stu_name,
                            ':work_time' => $work_time,
                            ':exam_code' => $exam_code,
                             ':dex' => $dex,
                              ':pin' => $pin,
                              ':problem_id1' => $problem_id1,
                              ':problem_id2' => $problem_id2,
                              ':problem_id3' => $problem_id3,
                              ':problem_id4' => $problem_id4,
                              ':problem_id5' => $problem_id5,
                               ':extend_time' => $work_time,
                               ':taker_id' => $student_id,
                             
                            ));
                                
                                // get the examtime_id
                           
                               $sql = "SELECT `examactivity_id` FROM `Examactivity` ORDER BY examactivity_id DESC LIMIT 1";
                               $stmt = $pdo->prepare($sql);
                               $stmt -> execute(); 
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $examactivity_id = $row['examactivity_id'];
                                        
                                        
                          }

                                            
                         header("Location: QRExam.php?examactivity_id=".$examactivity_id
						);
						die();
                                            
                        
                            } else {
                                
                                   $_SESSION['error'] = 'Exam Not Yet Active or Input Incorrect'; 
                            }
                    }          
           
      } elseif(isset($_POST['submit_form'])) {
            //$_SESSION['cclass_id'] = $_SESSION['cclass_name'] = '';
             $_SESSION['error']='The Class, Exam Code, Instructor are all Required ';
            }

// echo(' pin2 - 235 '. $pin);
/* 
    if (!is_numeric($pin)||$pin>10000 || $pin<0){
        $_SESSION['error']='Your PIN should be between 1 and 10000.';	
    } else {
        $_SESSION['pin']=$pin;
        $dex = ($pin-1) % 199 + 2; // % is PHP mudulus function - changing the PIN to an index between 2 and 200
        $_SESSION['dex'] = $dex;
    
 */

// Go get the problem id from the Exam table - this is comming from below and we have all of the infromation
	if(isset($_POST['submit_form'])&& isset($_POST['iid']) && isset($_POST['cclass_id'])&& isset($_POST['exam_code'])  ){
		$exam_code = htmlentities($_POST['exam_code']);
		$cclass_id = htmlentities($_POST['cclass_id']);
		$iid = htmlentities($_POST['iid']);
        $pin = $_POST['iid'];
        
            // Check the exam_code to see if it matches the value in the exam_time table 
            if (strlen($examactivity_id)<=0){  
            $sql = " SELECT * FROM `Examtime` WHERE iid = :iid AND exam_code = :exam_code AND currentclass_id = :currentclass_id"   ;
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                         ':exam_code' => $exam_code,
                         ':iid' => $iid,
                         ':currentclass_id' => $cclass_id,
                    ));
                    $row8 = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($row8 != false){
                        $examtime_id = $row8['examtime_id']; 
                        $work_time = $row8['work_time'];
                        $exam_num = $row8['exam_num'];
                        
             
                              // get the problem(s)
                              
                                    
                                    $sql = " SELECT * FROM `Exam` WHERE iid = :iid AND exam_num = :exam_num AND currentclass_id = :currentclass_id" ;
                                   $stmt = $pdo->prepare($sql);
                                    $stmt -> execute(array(
                                     ':exam_num' => $exam_num,
                                     ':iid' => $iid,
                                     ':currentclass_id' => $cclass_id,
                                ));
                                    $rows = $stmt->fetchALL(PDO::FETCH_ASSOC);
                                    
                                   // $n = count($row);
                                  //  echo('n = '.$n);
                                 
                                  $problem_id1 = $problem_id2 =  $problem_id3 = $problem_id4 =$problem_id5 = 0;
                                  
                                  foreach ($rows as $row){
                                       // print_r ($row);
                                           if ($row['alias_num']==1){$problem_id1=$row['problem_id'];}
                                           if ($row['alias_num']==2){$problem_id2=$row['problem_id'];}
                                           if ($row['alias_num']==3){$problem_id3=$row['problem_id'];}
                                           if ($row['alias_num']==4){$problem_id4=$row['problem_id'];}
                                           if ($row['alias_num']==5){$problem_id5=$row['problem_id'];}
                                   }
              // check to see if there is already an entry and they are trying to re-register then just read the Examactivity table
                

                $sql = " SELECT * FROM `Examactivity` WHERE iid = :iid AND exam_code = :exam_code AND currentclass_id = :currentclass_id AND pin = :pin AND examtime_id = :examtime_id"   ;
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                         ':exam_code' => $exam_code,
                         ':iid' => $iid,
                         ':currentclass_id' => $cclass_id,
                         ':pin' => $pin,
                         ':examtime_id' => $examtime_id,
                    ));
                    
                    $row8 = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($row8 != false){
                            $examactivity_id = $row8['examactivity_id'];        
                    } else {      
                   

                // ----------------------- put the entry into the examactivity table for this user  ------------------------------------------------------
                                       
                                    
                          $sql = 'INSERT INTO `Examactivity` (examtime_id, iid, currentclass_id, name, work_time,exam_code, dex, pin, problem_id1, problem_id2, problem_id3, problem_id4, problem_id5,suspend_flag,extend_time_flag,minutes)	
                                    VALUES (:examtime_id, :iid, :currentclass_id,:name ,:work_time, :exam_code, :dex, :pin, :problem_id1, :problem_id2, :problem_id3, :problem_id4, :problem_id5,0,0,:extend_time)';
                            $stmt = $pdo->prepare($sql);
                            $stmt -> execute(array(
                            ':examtime_id' => $examtime_id,
                            ':iid' => $iid,
                            ':currentclass_id' => $cclass_id,
                            ':name' => $stu_name,
                            ':work_time' => $work_time,
                            ':exam_code' => $exam_code,
                             ':dex' => $dex,
                              ':pin' => $pin,
                              ':problem_id1' => $problem_id1,
                              ':problem_id2' => $problem_id2,
                              ':problem_id3' => $problem_id3,
                              ':problem_id4' => $problem_id4,
                              ':problem_id5' => $problem_id5,
                               ':extend_time' => $work_time,
                             
                            ));
                            
                            // get the examtime_id
                       
                           $sql = "SELECT `examactivity_id` FROM `Examactivity` ORDER BY examactivity_id DESC LIMIT 1";
                           $stmt = $pdo->prepare($sql);
                           $stmt -> execute(); 
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            $examactivity_id = $row['examactivity_id'];
                                    
                                    
                      }
                                    $_SESSION['examactivity_id'] = $examactivity_id;
                                    $complete = 'QRExam.php';
                                    
                 header("Location: QRExam.php?examactivity_id=".$examactivity_id
                );
                die();
                                    
                
                    } else {
                        
                           $_SESSION['error'] = 'Exam Not Yet Active or Input Incorrect'; 
                    }
            }          
   
      } elseif(isset($_POST['submit_form'])) {
            //$_SESSION['cclass_id'] = $_SESSION['cclass_name'] = '';
             $_SESSION['error']='The Class, Exam Code, and Instructor are all Required ';
            }
            
            
    //   }
 //comming back from exam QRExam - not sure I need this for this file this wqas stole from stu_frontpage that actually gives the problem numbers 
 
 
    if(isset($_GET['examactivity_id']) || isset($_POST['examactivity_id'])){
		if(isset($_GET['examactivity_id'])){$examactivity_id = htmlentities($_GET['examactivity_id']);}
        if(isset($_POST['examactivity_id'])){$examactivity_id = htmlentities($_POST['examactivity_id']);}
        // get the information from the examactivity table
       // most things will be the same except the problem_id, alias number and once they select a problem we need to 
       // check the examactivity table of send all of these to the controller or somekind of pass through file
       $sql = 'SELECT * FROM `Examactivity` WHERE `examactivity_id` = :examactivity_id';
        $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':examactivity_id' => $examactivity_id));
         $examactivity_data = $stmt -> fetch();
         $exam_code = $examactivity_data['exam_code'];
         $iid = $examactivity_data['iid'];   
         $pin = $examactivity_data['pin'];   
         $stu_name = $examactivity_data['name'];   
         $cclass_id = $examactivity_data['currentclass_id']; 
         $currentclass_id = $examactivity_data['currentclass_id'];  
          $student_id = $examactivity_data['taker_id']; 
         $dex = $examactivity_data['dex'];  
           $exam_id = $examactivity_data['assign_id']; 
     /*     
         $alias_num = $examactivity_data['alias_num'];  
        
         $progess = $examactivity_data['progress'];  

 */

     $sql = 'SELECT COUNT(`currentclass_id`) FROM `StudentCurrentClassConnect` WHERE `student_id` = :student_id';
      $stmt = $pdo->prepare($sql);
              $stmt ->execute(array(
            ':student_id' => $student_id
              ));
             $num_classes = $stmt -> fetchColumn();
        
        $sql = 'SELECT name FROM CurrentClass WHERE currentclass_id = :currentclass_id';
         $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':currentclass_id' => $currentclass_id));
         $class_data = $stmt -> fetch();
         $class_name = $class_data['name'];
         $cclass_name = $class_data['name'];
         
        $sql = 'SELECT `exam_num` FROM Exam WHERE exam_id = :exam_id AND iid = :iid AND currentclass_id = :currentclass_id';
         $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':exam_id' => $exam_id,
                    ':iid' => $iid,
                     ':currentclass_id' => $cclass_id,
         ));
         $exam_data = $stmt -> fetch();
         $exam_num = $exam_data['exam_num'];
      } 
 
 
		// this should probably go back the previous screen			
    if (isset($_POST['reset']))	{
        
        $iid = '';
        $stu_name = '';
      
        $last = '';
        $first = $exam_code = '';
        $alias_num = $exam_num = $cclass_id = '';
        $been_there_flag = 0;
        
    //	session_destroy();
        
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

</head>

<body>
<header>
<h1>Quick Response Exam Registration</h1>
</header>

<?php

	if(isset($_POST['pin']) || isset($_POST['problem_id']) || isset($_POST['iid'])){
		if ( isset($_SESSION['error']) ) {
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) ) {
			echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
			unset($_SESSION['success']);
		}
	}
 
?>






<form autocomplete="off" method="POST" id = "the_form" action = "<?php echo($complete);?>" >
	
<p><font color=#003399>Exam Code: </font><input type="text" name="exam_code" id = "exam_code_id" size= 5 value="<?php echo($exam_code);?>" >
       - this is provided by the instructor
    </p>  
       
	<p><font color=#003399>Your Name: </font><?php echo($stu_name);?> </p>
	<input autocomplete="false" name="hidden" type="text" style="display:none;">
    <input type="hidden"  name="pin" id="pin_id" value=<?php echo($pin);?> ></p>
             
             
            
    	      
    
    <div id ="instructor_id">	
				<font color=#003399> Instructor: &nbsp; </font>
				<?php 
					// $iid=1;
					if (strlen($iid)>0 && strlen($cclass_id)>0 && strlen($cclass_name)>0 && strlen($exam_num)>0 ){
						
						
						$sql = 'SELECT users_id, last, first FROM Users WHERE `users_id` = :iid';
						$stmt = $pdo->prepare($sql);
						$stmt -> execute(array(':iid' => $iid));
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						$last = $row['last'];
						$first = $row['first'];
						echo ('<input type = "hidden" name = "iid" id = "have_iid" value = "'.$iid.'"></input>'); 
						echo ('<input type = "hidden" name = "have_last" value = "'.$last.'"></input>'); 
						echo ('<input type = "hidden" name = "have_first" value = "'.$first.'"></input>'); 
						echo ($last.', '.$first);
					} else {
						
						echo('<select name = "iid" id = "iid">');
						echo ('	<option value = "" selected disabled hidden >  Select Instructor  </option> ');
						$sql = 'SELECT DISTINCT iid, last, first FROM Users RIGHT JOIN CurrentClass ON Users.users_id = CurrentClass.iid';
						$stmt = $pdo->prepare($sql);
						$stmt -> execute();
						while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) 
							{ ?>
								<option value="<?php echo $row['iid']; ?>" ><?php echo ($row['last'].", ".$row['first']); ?> </option>
							<?php
							} 
						echo ('</select>');
					}
					?>
						
				
				</div>
				</br>
	
<!--	<div id ="current_class_dd">	-->
			<font color=#003399>Class: </font>
			
			<?php
			//$cclass_id = 2;
			//$cclass_name = 'Particle Technology';
			//echo ('cclass_id: ');
			
			//echo $cclass_id;
			
					if (strlen($iid)>0 && strlen($cclass_id)>0 && strlen($cclass_name)>0 && strlen($exam_num)>0 ){
						echo ('<input type = "hidden" name = "cclass_id" id = "have_cclass_id" value = "'.$cclass_id.'"></input>'); 
						echo ('<input type = "hidden" name = "cclass_name" id = "have_cclass_name" value = "'.$cclass_name.'"></input>'); 
						echo $cclass_name;
			} else {
				
			echo ('&nbsp;<select name = "cclass_id" id = "current_class_dd" >');
		
			echo('</select>');
				
				
			}
			
			
		
		?>
		</br>	
		</br>
	
       
         <input type="hidden" id = "examactivity_id" name="examactivity_id" value="<?php echo ($examactivity_id)?>" >
	
	<p><input type = "submit" name = "submit_form" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	</form>
	</br>
    </br>
    
    
	<form method = "POST">
		<p><input type = "submit" value="Reset Input" name = "reset"  size="2" style = "width: 30%; background-color: #FAF1BC; color: black"/> &nbsp &nbsp </p>  
	</form>

<script>

$("#iid").change(function(){
		var	 iid = $("#iid").val();
		
			$.ajax({
					url: 'getcurrentclass.php',
					method: 'post',
						data: {iid:iid}
				
				}).done(function(cclass){
					cclass = JSON.parse(cclass);
					 // now get the currentclass_id
			$.ajax({
					url: 'getcurrentclass_id.php',
					method: 'post',
						data: {iid:iid}
				
				}).done(function(cclass_id){
					cclass_id = JSON.parse(cclass_id);
					 $('#current_class_dd').empty();
					n = cclass.length;
				//		console.log("n: "+n);
						$('#current_class_dd').append("<option selected disabled hidden> Please Select Course </option>") ;
						for (i=0;i<n;i++){
							
							  $('#current_class_dd').append('<option  value="' + cclass_id[i] + '">' + cclass[i] + '</option>');
					}
				})
			})
		});
  

</script>

</body>
</html>



