<?php
require_once "pdo.php";
	session_start();

// THis files is called from the QRExamstart.php.  The purpose of this module is to control the Examtime table which the student exam checkers read to see what phase the exam is in
// THis file will also give a link to the backstage  to look at the progress of the studetns through another file looking at the examactivity table that the student exam checkers will be filling
    
    if (isset($_POST['currentclass_id'])) {
        $currentclass_id = htmlentities($_POST['currentclass_id']);
      } else {
           $_SESSION['error'] = 'invalid examination number in  QREStart.php ';
            header( 'Location: QRPRepo.php' ) ;
            die();
    }
    
     if (isset($_POST['iid'])) {
        $iid = htmlentities($_POST['iid']);
      } else {
           $_SESSION['error'] = 'invalid iid in  QREStart.php ';
            header( 'Location: QRPRepo.php' ) ;
            die();
    }
    
     if (isset($_POST['nom_time'])) {
        $work_time = htmlentities($_POST['nom_time']);
      } else {
           $_SESSION['error'] = 'invalid nominal time  QREStart.php ';
            header( 'Location: QRPRepo.php' ) ;
            die();
    }
     if (isset($_POST['attempt_type'])) {
        $attempt_type = htmlentities($_POST['attempt_type']);
      } else {
           $_SESSION['error'] = 'invalid attempt_type  QREStart.php ';
            header( 'Location: QRPRepo.php' ) ;
            die();
    }
     if (isset($_POST['num_attempts'])) {
        $num_attempts = htmlentities($_POST['num_attempts']);
      } else {
           $_SESSION['error'] = 'invalid num_attempts QREStart.php ';
            header( 'Location: QRPRepo.php' ) ;
            die();
    }
    
    // Test to see if your coming in from QREExamStart 	
    if (isset($_POST['exam_num'])) {
        $exam_num = htmlentities($_POST['exam_num']);

    $globephase = 0;
      $stop_time  =0;
        // Get the information from the various tables and put  the information in the 
        
      /*   
        echo 'num_attempts ='.$num_attempts;
        echo '  attempt_type ='.$attempt_type;
        die();
        */
       // see if the game is already running
     
                      $sql = "SELECT * FROM `Examtime` WHERE exam_num = :exam_num AND iid = :iid AND currentclass_id = :currentclass_id";
                   $stmt = $pdo->prepare($sql);
                   $stmt -> execute(array(
                        ':exam_num' => $exam_num,
                        ':iid' => $iid,
                         ':currentclass_id' => $currentclass_id,
                        )); 
                    $row5 = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                   if ($row5 != false) { 
                       
                   
                         $examtime_id = $row5['examtime_id'];
                         $globephase = $row5['globephase'];
                        $work_time = $row5['work_time'];
                        $end_of_phase = $row5['end_of_phase'];
                                
                                    


                   } else {
                 
                
                        // Create the table entry into the Gmact table from the values that were put in the Game table
                        
                        $sql = 'INSERT INTO `Examtime` (exam_num, iid, currentclass_id, globephase, work_time,attempt_type, num_attempts)	
                                    VALUES (:exam_num, :iid, :currentclass_id,:globephase ,:work_time, :attempt_type, :num_attempts)';
                            $stmt = $pdo->prepare($sql);
                            $stmt -> execute(array(
                            ':exam_num' => $exam_num,
                            ':iid' => $iid,
                            ':currentclass_id' => $currentclass_id,
                            ':globephase' => $globephase,
                            ':work_time' => $work_time,
                            ':attempt_type' => $attempt_type,
                             ':num_attempts' => $num_attempts,
                            ));
                            
                            // get the examtime_id
                       
                           $sql = "SELECT `examtime_id` FROM `Examtime` ORDER BY examtime_id DESC LIMIT 1";
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
                        
                      if(isset($_POST['examtime_id'])){  
                        $examtime_id = $_POST['examtime_id'];
                      }

                  if(isset($_POST['phase_change'])){
                       if($_POST['phase_change']==1 &&  $globephase ==0){

                           $globephase =1;
                           
                           $sql = "UPDATE `Examtime`
                             SET `end_of_phase` = (UTC_TIMESTAMP() + INTERVAL :work_time MINUTE)
                              WHERE examtime_id = :examtime_id";
                                $stmt = $pdo->prepare($sql);
                                $stmt -> execute(array(
                                    ':examtime_id' => $examtime_id,
                                    ':work_time' => $work_time,
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
    
            	$sql = 'SELECT name FROM `Currentclass` WHERE `currentclass_id` = :currentclass_id';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(':currentclass_id' => $currentclass_id));
                    	$row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $class_name = $row['name'];
                
            	$sql = 'SELECT end_of_phase FROM `Examtime` WHERE `examtime_id` = :examtime_id';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(':examtime_id' => $examtime_id));
                    	$row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $stop_time = $row['end_of_phase'];
                            
            
          If ($globephase == 0){
              $start_stop = 'Start Exam';
              
          } elseif($globephase == 1){
               $start_stop = 'Stop Exam';
              
          }  elseif($globephase == 2){
               $start_stop = 'Archive Data';
              
          } elseif($globephase == 3){
               // archive the data from the Examactivity table and Delete the values out of the Examtime table
               
               $sql = 'DELETE FROM Examtime WHERE examtime_id = :examtime_id';
               $stmt = $pdo->prepare($sql);
                $stmt -> execute(array(
                ':examtime_id' => $examtime_id,
                ));      
               
               header( 'Location: QRPRepo.php' ) ;
                die();
              
          }   
                     $sql = "UPDATE `Examtime` 
				SET globephase = :globephase
				WHERE examtime_id = :examtime_id";
                $stmt = $pdo->prepare($sql);
                $stmt -> execute(array(
                ':globephase' => $globephase,
                ':examtime_id' => $examtime_id
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

<!--<h3>Print the problem statement with "Ctrl P"</h3>
 <p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p> 



 <h1><font  style="font-size:300%; color:blue;"> <?php echo $stage;?> </font>
    	<div id="defaultCountdown" style="font-size:300%;color:red;"> </div></h1> 
<div id = 'backstage_view'>
    <iframe src="QRPGamePlayers.php?gmact_id=<?php echo($gmact_id);?>" style = "width:100%; height:700px;"></iframe>
    
</div>

<div id = 'scorebrd'>
 <iframe src="QRPGameScoreBoard.php?gmact_id=<?php echo($gmact_id);?>" style = "width:100%; height:700px;"></iframe>
</div>
 -->

<form  method="POST" action = "" id = "submit_form">



    <h2> Class: <?php echo $class_name; ?></h2>  
   <h2> Exam Number = <?php echo $exam_num; ?></h2>
    <h2> Current Phase = <?php echo $globephase; ?></h2>
   <!-- 
	<p><input type="hidden" name="game_num" id="game_num" size=3 value=<?php echo($game_id);?> ></p>
    <p style="font-size:100px;">
    <p><input type="hidden" name="gmact_id" id="gmact_id" size=3 value=<?php echo($gmact_id);?> ></p>
    <p><input type="hidden" name="on_the_fly" id="on_the_fly" size=3 value=1 ></p>
   <p> This phase ends at: <?php if (isset($stop_time)) {echo $stop_time;}   ?> UTC </p>-->
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
   <p><input type="hidden" name="nom_time" id="work_time" value=<?php echo($work_time);?> ></p>
      <p><input type="hidden" name="exam_num_here" id="exam_num"  value=<?php echo($exam_num);?> ></p>
   <p><input type="hidden" name="examtime_id" id="examtime_id"  value=<?php echo($examtime_id);?> ></p>
   
   
   
	<p><input type="hidden" name="globephase" id="globephase" size=3 value=<?php echo($globephase);?> ></p>
    <h2><input type="checkbox" name="phase_change" value = 1 style="height:15px; width:15px;" id="phase_change" ><?php echo($start_stop);?> </h2>
     <p><input type = "submit" value="Submit Changes" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	
    <!--   <p>Working Time: <input type="number" name="work_time" id="work_time" size=5 value=<?php echo($work_time);?>></p > -->
	</form>
    
  
    <form  method="POST" action = "QRExamBackStage.php" id = "backstage" target = "_blank">
   <p style="font-size:50px;"></p>
   
   <p><input type="hidden" name="examtime_id" id="examtime_id" value=<?php echo($examtime_id);?> ></p>
   
  <p><input type = "submit" name = "backstage" value="Back Stage Exam Data" id="backstage_submit" size="2" style = "width: 30%; background-color: green; color: white"/>  </p>  
  
  </form>
    
    <!--  
   
    
   <form  method="POST" action = "" id = "finish_form">
   <p style="font-size:50px;"></p>
   
   <p><input type="hidden" name="gmact_id" id="gmact_id" value=<?php echo($gmact_id);?> ></p>
   
  <p><input type = "submit" name = "finished" value="Kill Game - Clear Data & Return to Repo" id="submit_id" size="2" style = "width: 30%; background-color: red; color: white"/> &nbsp &nbsp </p>  
  <p> Note - there is a delay of about 3 to 5 seconds to finish game</p>
  </form>
    		
   -->

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
                     $("#defaultCountdown").hide();
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
   
       