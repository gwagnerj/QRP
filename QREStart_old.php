<?php
require_once "pdo.php";
	session_start();

// THis file will also give a link to the backstage  to look at the progress of the studetns through another file looking at the examactivity table that the student exam checkers will be filling
  /*   
    if (isset($_POST['currentclass_id'])) {
        $currentclass_id = htmlentities($_POST['currentclass_id']);
      } else {
           $_SESSION['error'] = 'invalid examination number in  QREStart.php ';
            header( 'Location: QRPRepo.php' ) ;
            die();
    }
 */
    if (isset($_POST['eexamtime_id'])) {
      $eexamtime_id = htmlentities($_POST['eexamtime_id']);
    } else {
         $_SESSION['error'] = 'invalid eexamtime_id in  QREStart.php ';
         header( 'Location: QRPRepo.php' ) ;
          die();
  }
  if (isset($_POST['nom_time'])) {
    $nom_time = htmlentities($_POST['nom_time']);
  } 
   /*  
     if (isset($_POST['iid'])) {
        $iid = htmlentities($_POST['iid']);
      } else {
           $_SESSION['error'] = 'invalid iid in  QREStart.php ';
            header( 'Location: QRPRepo.php' ) ;
            die();
    } */
      if (isset($_POST['exam_code'])) {
        $exam_code = htmlentities($_POST['exam_code']);
      }
/* 
    // these need to be read infrom the data table eexamtime
      if (isset($_POST['attempt_type'])) {
        $attempt_type = htmlentities($_POST['attempt_type']);
      } else {
        $attempt_type = 1;
    }
    if (isset($_POST['num_attempts'])) {
      $num_attempts = htmlentities($_POST['num_attempts']);
    } else {
      $num_attempts =0; 
  }



 */
     /*
    
     if (isset($_POST['nom_time'])) {
        $work_time = htmlentities($_POST['nom_time']);
      } else {
           $_SESSION['error'] = 'invalid nominal time  QREStart.php ';
            header( 'Location: QRPRepo.php' ) ;
            die();
    }
    
    
     if (isset($_POST['ans_n'])) {
        $ans_n = htmlentities($_POST['ans_n']);
      } else {
         $ans_n = ""; 
    }
     if (isset($_POST['ans_t'])) {
        $ans_t = htmlentities($_POST['ans_t']);
      } else {
         $ans_t = ""; 
    }
  */  
    // Test to see if your coming in from if we are coming in for the first time need to generate an erty in the Eexamnow table

  if (isset($_POST['eexamnow_id'])){
    
       $eexamnow_id = htmlentities($_POST['eexamnow_id']);
     echo ('eexamnow_id: '.$eexamnow_id);  
    } elseif(isset($_GET['eexamnow_id'])){
        $eexamnow_id  = $_GET['eexamnow_id'];  
    } else {
      $exam_code = rand(100,9999);

      $sql = "INSERT INTO `Eexamnow` (eexamtime_id,exam_code) VALUES (:eexamtime_id,:exam_code)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ':eexamtime_id' => $eexamtime_id,
        ':exam_code' => $exam_code
      ));
      $eexamnow_id = $pdo->lastInsertId();
      echo ('eexamnow_id=: '.$eexamnow_id);

    }


if (isset($_POST['globephase'])){
  $globephase = $_POST['globephase'];
} else {

  $sql = 'SELECT globephase FROM `Eexamnow` WHERE `eexamnow_id` = :eexamnow_id';
  $stmt = $pdo->prepare($sql);
  $stmt -> execute(array(':eexamnow_id' => $eexamnow_id));
              $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $globephase = $row['globephase'];
}





    if(isset($_POST['phase_change'])){

      if($globephase == 0){
    
        $sql = "UPDATE `Eexamnow`
        SET `end_of_phase` = (UTC_TIMESTAMP() + INTERVAL :nom_time MINUTE)
         WHERE eexamnow_id = :eexamnow_id";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
               ':eexamnow_id' => $eexamnow_id,
               ':nom_time' => 300,
           ));
      } 
       $globephase = $globephase +1;
        $sql = "UPDATE `Eexamnow`
         SET `globephase` =:globephase
           WHERE eexamnow_id = :eexamnow_id";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
               ':eexamnow_id' => $eexamnow_id,
               ':globephase' => $globephase
           ));
      }
// now get the information from the tables

$sql = "SELECT * FROM `Eexamtime` LEFT JOIN Eexamnow ON Eexamtime.eexamtime_id = Eexamnow.eexamtime_id  WHERE Eexamnow.eexamnow_id = :eexamnow_id";
$stmt = $pdo->prepare($sql);
$stmt -> execute(array(
     ':eexamnow_id' => $eexamnow_id,
     )); 
 $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
if ($row != false) { 
      $eexamtime_id = $row['eexamtime_id'];
      $eexamnow_id = $row['eexamnow_id'];
      $globephase = $row['globephase'];
      $nom_time = $row['nom_time'];
      $work_time = $nom_time;
      $end_of_phase = $row['end_of_phase'];
       $exam_code = $row['exam_code'];       
       $ans_n = $row['ans_n'];         
       $ans_t = $row['ans_t'];  
       $currentclass_id = $row['currentclass_id'];       
      $exam_num = $row['exam_num'];
      $iid = $row['iid'];
      $attempt_type = $row['attempt_type'];
      $num_attempts = $row['num_attempts'];
      $stop_time = $row['end_of_phase'];

      $sql = 'SELECT name FROM `CurrentClass` WHERE `currentclass_id` = :currentclass_id';
      $stmt = $pdo->prepare($sql);
      $stmt -> execute(array(':currentclass_id' => $currentclass_id));
                  $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $class_name = $row['name'];
}







/* 



if($_POST['phase_change']==1 &&  $globephase ==0){

  $globephase =1;
  
  $sql = "UPDATE `Eexamnow`
    SET `end_of_phase` = (UTC_TIMESTAMP() + INTERVAL :work_time MINUTE)
     WHERE eexamtime_id = :eexamtime_id";
       $stmt = $pdo->prepare($sql);
       $stmt -> execute(array(
           ':eexamtime_id' => $eexamtime_id,
           ':work_time' => $nom_time,
       ));
} elseif($_POST['phase_change']==1 &&  $globephase ==1) {
   $globephase =2;
}  elseif($_POST['phase_change']==1 &&  $globephase ==2) {
   $globephase =3;
} 







     
                      $sql = "SELECT * FROM `Eexamtime` LEFT JOIN Eexamnow ON Eexamtime.eexamtime_id = Eexamnow.eexamtime_id  WHERE exam_num = :exam_num AND iid = :iid AND currentclass_id = :currentclass_id";
                   $stmt = $pdo->prepare($sql);
                   $stmt -> execute(array(
                        ':exam_num' => $exam_num,
                        ':iid' => $iid,
                         ':currentclass_id' => $currentclass_id,
                        )); 
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                   if ($row != false) { 
                       
                   
                    $eexamtime_id = $row['eexamtime_id'];
                    $eexamnow_id = $row['eexamnow_id'];
                    $globephase = $row['globephase'];
                        $work_time = $row['work_time'];
                        $end_of_phase = $row['end_of_phase'];
                          $exam_code = $row['exam_code'];       
                          $ans_n = $row['ans_n'];         
                          $ans_t = $row['ans_t'];         


                   } else {
                 
                        $exam_code = rand(100,9999);
                        
                        $sql = 'INSERT INTO `Eexamnow` (eexamtime_id, globephase, work_time,attempt_type, num_attempts, exam_code,ans_n,ans_t)	
                                    VALUES (:exam_num, :iid, :currentclass_id,:globephase ,:work_time, :attempt_type, :num_attempts, :exam_code,:ans_n,:ans_t)';
                            $stmt = $pdo->prepare($sql);
                            $stmt -> execute(array(
                            ':exam_num' => $exam_num,
                            ':iid' => $iid,
                            ':currentclass_id' => $currentclass_id,
                            ':globephase' => $globephase,
                            ':work_time' => $work_time,
                            ':attempt_type' => $attempt_type,
                             ':num_attempts' => $num_attempts,
                              ':exam_code' => $exam_code,
                              ':ans_n' => $ans_n,
                              ':ans_t' => $ans_t,
                            ));
                            
                            // get the examtime_id
                       
                           $sql = "SELECT `eexamtime_id` FROM `Eexamtime` ORDER BY eexamtime_id DESC LIMIT 1";
                           $stmt = $pdo->prepare($sql);
                           $stmt -> execute(); 
                            $row3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach($row3 as $row){
                         //   print_r ($row);
                            $examtime_id = $row['examtime_id'];
                            }
                          // echo 'gmact_id top = '.$gmact_id;
                   }  
              } elseif(isset($_POST['exam_num_here'])){ // now test to see if we are coming from this file
                    $exam_num = $_POST['exam_num_here'];
                   

                    $globephase = $_POST['globephase'];
                        
                      if(isset($_POST['eexamtime_id'])){  
                        $examtime_id = $_POST['eexamtime_id'];
                      }

                  if(isset($_POST['phase_change'])){
                       if($_POST['phase_change']==1 &&  $globephase ==0){

                           $globephase =1;
                           
                           $sql = "UPDATE `Eexamnow`
                             SET `end_of_phase` = (UTC_TIMESTAMP() + INTERVAL :work_time MINUTE)
                              WHERE eexamtime_id = :eexamtime_id";
                                $stmt = $pdo->prepare($sql);
                                $stmt -> execute(array(
                                    ':eexamtime_id' => $eexamtime_id,
                                    ':work_time' => $nom_time,
                                ));
                        } elseif($_POST['phase_change']==1 &&  $globephase ==1) {
                            $globephase =2;
                        }  elseif($_POST['phase_change']==1 &&  $globephase ==2) {
                            $globephase =3;
                        } 
                   }
              }  else {
           $_SESSION['error'] = 'invalid examination number in  QREStart.php ';
            header( 'Location: QRPRepo.php' ) ;
            die();
    }
    
    // in either case get the class and 
    
            	$sql = 'SELECT name FROM `CurrentClass` WHERE `currentclass_id` = :currentclass_id';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(':currentclass_id' => $currentclass_id));
                    	$row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $class_name = $row['name'];
      
               $sql = "SELECT end_of_phase FROM `Eexamtime` LEFT JOIN Eexamnow ON Eexamtime.eexamtime_id = Eexamnow.eexamtime_id  WHERE exam_num = :exam_num AND iid = :iid AND currentclass_id = :currentclass_id";

     //       	$sql = 'SELECT end_of_phase FROM `Eexamtime` WHERE `eexamtime_id` = :eexamtime_id';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(':eexamtime_id' => $eexamtime_id));
                    	$row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $stop_time = $row['end_of_phase'];
       */                          
            
          If ($globephase == 0){
              $start_stop = 'Start Exam';
              
          } elseif($globephase == 1){
               $start_stop = 'Stop Exam';
              
          }  elseif($globephase == 2){
               $start_stop = 'Archive Data';
              
          } elseif($globephase == 3){
               // archive the data from the Examactivity table and Delete the values out of the Eexamnow table
               
               $sql = 'DELETE FROM Eexamnow WHERE eexamnow_id = :eexamnow_id';
               $stmt = $pdo->prepare($sql);
                $stmt -> execute(array(
                ':eexamnow_id' => $eexamnow_id,
                ));      
               
               
                $new_eexamtime_id = $eexamtime_id + 1000000;
                $sql = "UPDATE `Eexamactivity` 
				SET eexamtime_id = :new_eexamtime_id 
				WHERE eexamtime_id = :eexamtime_id";
                $stmt = $pdo->prepare($sql);
                $stmt -> execute(array(
                    
                    ':eexamtime_id' => $eexamtime_id,
                    ':new_eexamtime_id' => $new_eexamtime_id,
                    
                ));
               
               header( 'Location: QRPRepo.php' ) ;
                die();
              
          }   
                     $sql = "UPDATE `Eexamnow` 
				SET globephase = :globephase
				WHERE eexamnow_id = :eexamnow_id";
                $stmt = $pdo->prepare($sql);
                $stmt -> execute(array(
                ':globephase' => $globephase,
                ':eexamnow_id' => $eexamnow_id
                ));      
          
    

   
?>
  <!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRExam Proctor</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="jquery.countdown.css"> 
	
<script type="text/javascript" src="jquery.plugin.js"></script> 
<script type="text/javascript" src="jquery.countdown.js"></script>
</head>

<body>
<header>
<h1>Quick Response Exam</h1>
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

 
 
 
?>



<form  method="POST" action = "" id = "submit_form">


 <h1> Exam Code = <?php echo $exam_code; ?></h1>
    <h2> Class: <?php echo $class_name; ?></h2>  
   <h2> Exam Number = <?php echo $exam_num; ?></h2>
   <h2> Current Phase = <?php echo $globephase; ?></h2>
   <h2> nomimal time for exam = <?php echo $nom_time; ?> min</h2>
   <h2> stop time for exam = <?php echo $stop_time; ?> </h2>
  
      <p><font color=#003399> </font><input type="hidden" id = "stop_time" name="stop_time" size=2 value="<?php echo (htmlentities($stop_time))?>"  ></p>

    	<h3><div id="defaultCountdown" style="font-size:300%;color:red;"> </div></h3> 
         <p style="font-size:40px;"></p>
        


 
    
    <input type="button" id="pause" value="Pause" />
    <input type="button" id="resume" value="Resume" />  
     <p style="font-size:50px;"></p>
  

  <!-- - If you pause and want to correct the score board clock  - upon resume - 1)close the scoreboard tab 2)change the working time to the remaining time 3)submit 4)reopen the Score Boeard  -->
  

  <hr color = "green"> <font color = "blue"> </font>
   <p><input type="hidden" name="globephase" id="globephase"  value=<?php echo($globephase);?> ></p>
   <p><input type="hidden" name="currentclass_id" id="currentclass_id"  value=<?php echo($currentclass_id);?> ></p>
   <p><input type="hidden" name="iid" id="iid"  value=<?php echo($iid);?> ></p>
   <p><input type="hidden" name="attempt_type" id="attempt_type" value=<?php echo($attempt_type);?> ></p>
    <p><input type="hidden" name="num_attempts" id="num_attempts"  value=<?php echo($num_attempts);?> ></p>
   <p><input type="hidden" name="nom_time" id="nom_time" value=<?php echo($nom_time);?> ></p>
      <p><input type="hidden" name="exam_num_here" id="exam_num"  value=<?php echo($exam_num);?> ></p>
      <p><input type="hidden" name="eexamtime_id" id="examtime_id"  value=<?php echo($eexamtime_id);?> ></p>
      <p><input type="hidden" name="eexamnow_id" id="eexamnow_id"  value=<?php echo($eexamnow_id);?> ></p>
    <p><input type="hidden" name="exam_code" id="exam_code"  value=<?php echo($exam_code);?> ></p>
   
   
	<p><input type="hidden" name="globephase" id="globephase" size=3 value=<?php echo($globephase);?> ></p>
    <h2><input type="checkbox" name="phase_change" value = 1 style="height:15px; width:15px;" id="phase_change" ><?php echo($start_stop);?> </h2>
     <p><input type = "submit" value="Submit Changes" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	
    <!--   <p>Working Time: <input type="number" name="work_time" id="work_time" size=5 value=<?php echo($work_time);?>></p > -->
	</form>
    
  
    <form  method="POST" action = "QRExamBackStage.php" id = "backstage" target = "_blank">
   <p style="font-size:50px;"></p>
   
   <p><input type="hidden" name="eexamtime_id" id="eexamtime_id" value=<?php echo($eexamtime_id);?> ></p>
   
  <p><input type = "submit" name = "backstage" value="Back Stage Exam Data" id="backstage_submit" size="2" style = "width: 30%; background-color: green; color: white"/>  </p>  
  
  </form>
    
   

<script>
	$(document).ready( function () {
                    
                var stop_time;
                stop_time = $("#stop_time").val();
                 console.log ("stop time1 UTC = "+stop_time);
                 
                     console.log (typeof(stop_time));  
                        var stop_time = new Date(stop_time)
                        var current_time = new Date();
                        var offset = current_time.getTimezoneOffset();
                        
                        console.log ("stop time UTC = "+stop_time);
                        console.log ("current_time UTC = "+current_time);
                         console.log ("offset = "+offset);
                         
                       //  var new_stop = stop_time.subtract(3, 'minutes').toDate();
                        
                        var new_stop=stop_time.setMinutes(stop_time.getMinutes()-offset);
                        Start();
                
                   console.log (typeof(stop_time));  
                 
                var globephase = $("#globephase").val();
                console.log("globephase is = "+globephase);
                 if (globephase ==0 || globephase >= 2){
                     $("#defaultCountdown").show();
                } else {
                   
                     $("#defaultCountdown").show();
                     
                     
                }
 /*               
 */
               function Start(){
				$('#defaultCountdown').countdown({until: stop_time, format: 'ms',  layout: '{d<}{dn} {dl} {d>}{h<}{hn} {hl} {h>}{m<}{mn} {ml} {m>}{s<}{sn} {sl}{s>}',onExpiry: SubmitAway}); 
			//	$('#defaultCountdown').countdown({until: stop_time, format: 'ms',  layout: '{d<}{dn} {dl} {d>}{h<}{hn} {hl} {h>}{m<}{mn} {ml} {m>}{s<}{sn} {sl}{s>}'}); 

                }
                
                function pause() {
                    $('#defaultCountdown').countdown('pause');
                }
                 function resume() {
                    $('#defaultCountdown').countdown('resume');
                }
                
  
                $('#pause').click(pause);
                $('#resume').click(resume);
            //    $('#addaminute').click(addaminute);
                Start();
                
                
                function SubmitAway() { 
               
                        $("#phase_change").prop("checked",true);
                       document.getElementById('submit_form').submit();
                    }

    });


</script>





</body>
</html>
   
       