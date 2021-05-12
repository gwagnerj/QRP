<?php
require_once 'pdo.php';
session_start();

// This file is entered from QRExamMgmt.  The purpose of this file is to keep the timing of an exam.  It does this by creating an entry in the eexamnow table
// The table keeps references the information in the eexamtime table through a one to many relationship.  can have many instances of a eexamnow from a single eexamtime.

// FIrst take in the values from the QRExammgmt post so we can identify insert an entry into the table
if (isset($_POST['from_QRExamMgmt'])) {
    // get all of the post information from that file and create an entry in the eexamnow table
    if (isset($_POST['eexamtime_id'])) {
        $eexamtime_id = htmlentities($_POST['eexamtime_id']);
    } else {
        $_SESSION['error'] = 'invalid eexamtime_id in  QREStart.php ';
        header('Location: QRPRepo.php');
        die();
    }
    if (isset($_POST['iid'])) {
        $iid = htmlentities($_POST['iid']);
    } else {
        $_SESSION['error'] = 'invalid iid in  QREStart.php ';
        header('Location: QRPRepo.php');
        die();
    }

    // check to see if there is an active exam already going on for this iid and this eexamtime_id if so we probably got disconnected and need to reconnect to the ongoing exam and

    $sql = "SELECT * FROM `Eexamtime` 
            LEFT JOIN Eexamnow ON Eexamtime.eexamtime_id = Eexamnow.eexamtime_id  
            WHERE Eexamtime.iid = :iid 
                  AND Eexamtime.eexamtime_id = :eexamtime_id 
                  AND Eexamnow.end_of_phase > CURRENT_TIMESTAMP() 
                  AND Eexamnow.globephase != 3
              ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':iid' => $iid,
        ':eexamtime_id' => $eexamtime_id,
    ]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row == false) {
        // we have to put in a new entry and get the values from the table we need
        $exam_code = rand(100, 9999);
        $sql =
            'INSERT INTO `Eexamnow` (eexamtime_id,exam_code,end_of_phase) VALUES (:eexamtime_id,:exam_code, DATE_ADD(now(), INTERVAL 1 HOUR))';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':eexamtime_id' => $eexamtime_id,
            ':exam_code' => $exam_code,
        ]);
        $eexamnow_id = $pdo->lastInsertId();
        // echo ('eexamnow_id=: '.$eexamnow_id);
        // get the value of the number of groups and put it in the qrexamtime table

        if (isset($_POST['number_teams']) && isset($_POST['game_flag'])) {
            $number_teams = $_POST['number_teams'];
            $sql = "UPDATE `Eexamtime`
            SET `number_teams` =:number_teams,game_flag = :game_flag
              WHERE eexamtime_id = :eexamtime_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':number_teams' => $_POST['number_teams'],
                ':eexamtime_id' => $eexamtime_id,
                ':game_flag' => $_POST['game_flag'],
            ]);
        }
    } else {
        // we are coming in from the QRExamMgmt but have an existing running exam going (disconnected or whatever)/ get the information from the table
        $eexamnow_id = $row['eexamnow_id'];
        $globephase = $row['globephase'];
        $end_of_phase = $row['end_of_phase'];
        $exam_code = $row['exam_code'];
        $_SESSION['error'] = 'Exam is already running';
    }

    // kill the history so that the back button does not reload the data
} elseif (isset($_POST['from_QREStart'])) {
    // coming in from this file
    if (isset($_POST['eexamnow_id'])) {
        $eexamnow_id = $_POST['eexamnow_id'];
    } else {
        $_SESSION['error'] = 'invalid eexamnow_id  QREStart.php ';
        header('Location: QRPRepo.php');
        die();
    }
    if (isset($_POST['currentclass_id'])) {
        $currentclass_id = htmlentities($_POST['currentclass_id']);
    } else {
        $_SESSION['error'] = 'invalid currentclass_id in  QREStart.php ';
        $currentclass_id = '';
    }

    if (isset($_POST['exam_code'])) {
        $exam_code = $_POST['exam_code'];
    } else {
        $sql =
            'SELECT `exam_code` from Eexamnow WHERE eexamnow_id = :eexamnow_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':eexamnow_id' => $eexamnow_id]);
        $row = $stmt->fetch();
        $exam_code = $row['exam_code'];
    }

    if (isset($_POST['globephase'])) {
        $globephase = $_POST['globephase'];
    } else {
        $sql =
            'SELECT `globephase` from Eexamnow WHERE eexamnow_id = :eexamnow_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':eexamnow_id' => $eexamnow_id]);
        $row = $stmt->fetch();
        $globephase = $row['globephase'];
    }

    if (isset($_POST['nom_time'])) {
        $nom_time = $_POST['nom_time'];
        //    echo ("nom time set to: ".$nom_time);
    }

    if (isset($_POST['phase_change'])) {
        if ($globephase == 0) {
            $sql = "UPDATE `Eexamnow`
        SET `end_of_phase` = DATE_ADD(UTC_TIMESTAMP() , INTERVAL :nom_time MINUTE)
         WHERE eexamnow_id = :eexamnow_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':eexamnow_id' => $eexamnow_id,
                ':nom_time' => $nom_time,
            ]);
        }

        $globephase = $globephase + 1;
        $sql = "UPDATE `Eexamnow`
            SET `globephase` =:globephase
              WHERE eexamnow_id = :eexamnow_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':eexamnow_id' => $eexamnow_id,
            ':globephase' => $globephase,
        ]);
    }

    if (isset($_POST['clear_data'])) {
        $clear_data = 1;
        // delete all of the students responses and activity from the current game/exam
        $sql = "DELETE Eresp, Eactivity 
            FROM Eresp INNER JOIN Eactivity 
            WHERE Eresp.eactivity_id = Eactivity.eactivity_id  
              AND 
                  Eactivity.eexamnow_id = :eexamnow_id
            ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':eexamnow_id' => $eexamnow_id,
        ]);

        // if they are temporary students disconect them from the class and delete them  - first need to get them out of eregistration

        $sql =
            'DELETE FROM Eregistration WHERE Eregistration.eexamnow_id = :eexamnow_id ';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':eexamnow_id' => $eexamnow_id,
        ]);

        $sql =
            "DELETE  StudentCurrentClassConnect 
              FROM
                StudentCurrentClassConnect INNER JOIN Student
              WHERE
                 StudentCurrentClassConnect.student_id = Student.student_id
               AND 
                  Student.username LIKE 'temp_" .
            $eexamnow_id .
            '_' .
            $currentclass_id .
            "%' 
               ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $sql =
            "DELETE  TeamStudentConnect, Team
              FROM
                  TeamStudentConnect INNER JOIN Student INNER JOIN Team
              WHERE
                TeamStudentConnect.student_id = Student.student_id
               AND 
                TeamStudentConnect.team_id = Team.team_id
               AND 
                  Student.username LIKE 'temp_" .
            $eexamnow_id .
            '_' .
            $currentclass_id .
            "%' 
               ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $sql =
            "DELETE  FROM
                     Student 
                WHERE
                    Student.username LIKE 'temp_" .
            $eexamnow_id .
            '_' .
            $currentclass_id .
            "%' 
                 ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        // $sql = "DELETE  TeamStudentConnect, Team
        // FROM
        //     TeamStudentConnect INNER JOIN Student INNER JOIN Team
        // WHERE
        //   TeamStudentConnect.student_id = Student.student_id
        //  AND
        //   TeamStudentConnect.team_id = Team.team_id
        //  AND
        //     Student.username LIKE 'temp_".$eexamnow_id."_".$currentclass_id."%'
        //  ";
        //   $stmt = $pdo->prepare($sql);
        //   $stmt ->execute();

        // $sql = "DELETE StudentCurrentClassConnect
        //          FROM
        //            StudentCurrentClassConnect INNER JOIN Student INNER JOIN Eexamnow INNER JOIN Eexamtime
        //          WHERE
        //          StudentCurrentClassConnect.currentclass_id = Eexamtime.currentclass_id
        //           AND
        //               Eexamtime.eexamtime_id = Eexamnow.eexamtime_id
        //           AND
        //               Eexamnow.eexamnow_id = :eexamnow_id
        //           AND
        //               StudentCurrentClassConnect.student_id = Student.student_id
        //           AND
        //              Student.username LIKE 'temp_".$eexamnow_id."_".$currentclass_id."%'
        // ";

        //       $sql = "DELETE Team
        //       FROM
        //         StudentCurrentClassConnect INNER JOIN Student INNER JOIN Eexamnow INNER JOIN Eexamtime
        //       WHERE
        //       StudentCurrentClassConnect.currentclass_id = Eexamtime.currentclass_id
        //       AND
        //           Eexamtime.eexamtime_id = Eexamnow.eexamtime_id
        //       AND
        //           Eexamnow.eexamnow_id = :eexamnow_id
        //       AND
        //           StudentCurrentClassConnect.student_id = Student.student_id
        //       AND
        //           Student.username LIKE 'temp_".$eexamnow_id."_".$currentclass_id."%'
        //       ";
        //     $stmt = $pdo->prepare($sql);
        //     $stmt ->execute(
        //       array(
        //         ":eexamnow_id" => $eexamnow_id
        //       ));

        // //disconnect the students from the teams and delete the teams
        // $sql = "DELETE TeamStudentConnect, Team
        //         FROM TeamStudentConnect INNER JOIN Team
        //         WHERE
        //             TeamStudentConnect.team_id = Team.team_id
        //          AND
        //             TeamStudentConnect.eexamnow_id = :eexamnow_id
        //     ";
        //         $stmt = $pdo->prepare($sql);
        //         $stmt ->execute(
        //           array(
        //             ":eexamnow_id" => $eexamnow_id
        //           ));

        // need to deplete activity from the Gameactivity table too

        // go back to the repo

        // header( 'Location: QRPRepo.php' ) ;
        // die();
    } else {
        $clear_data = 0;
    }
}
// now get the entris we need to populate the values in the html below for this particular exam

$sql =
    'SELECT * FROM `Eexamtime` LEFT JOIN Eexamnow ON Eexamtime.eexamtime_id = Eexamnow.eexamtime_id  WHERE Eexamnow.eexamnow_id = :eexamnow_id';
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':eexamnow_id' => $eexamnow_id,
]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row != false) {
    $eexamtime_id = $row['eexamtime_id'];
    $eexamnow_id = $row['eexamnow_id'];
    $globephase = $row['globephase'];
    $nom_time = $row['nom_time'];
    $end_of_phase = $row['end_of_phase'];
    $exam_code = $row['exam_code'];
    $currentclass_id = $row['currentclass_id'];
    $exam_num = $row['exam_num'];
    $iid = $row['iid'];
    $stop_time = $row['end_of_phase'];

    $sql =
        'SELECT name FROM `CurrentClass` WHERE `currentclass_id` = :currentclass_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':currentclass_id' => $currentclass_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $class_name = $row['name'];
}

if ($globephase == 0) {
    $start_stop = 'Start Exam';
} elseif ($globephase == 1) {
    $start_stop = 'Stop Exam';
} elseif ($globephase == 2) {
    $start_stop = 'Archive Data ';
} elseif ($globephase == 3) {
    // archive the data from the Examactivity table and Delete the values out of the Eexamnow table
    /*    
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
         */
    header('Location: QRPRepo.php');
    die();
}
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
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo '<p style="color:green">' . $_SESSION['success'] . "</p>\n";
    unset($_SESSION['success']);
}
?>



<form  method="POST" action = "" id = "submit_form">


 <h1 style = "color:blue;"> Game Code = <?php echo $eexamnow_id; ?></h1>
    <h2> Class: <?php echo $class_name; ?></h2>  
   <h2> Exam Number = <?php echo $exam_num; ?></h2>
   <h2> Current Phase = <?php echo $globephase; ?></h2>
   <h2> nomimal time for exam = <?php echo $nom_time; ?> min</h2>
   <h2> stop time for exam = <?php echo $stop_time; ?> </h2>
  
      <p><font color=#003399> </font><input type="hidden" id = "stop_time" name="stop_time" size=2 value="<?php echo htmlentities(
          $stop_time
      ); ?>"  ></p>

    	<h3><div id="defaultCountdown" style="font-size:300%;color:red;"> </div></h3> 
         <p style="font-size:40px;"></p>
        


 
    
    <input type="button" id="pause" value="Pause" />
    <input type="button" id="resume" value="Resume" />  
     <p style="font-size:50px;"></p>
  

  <!-- - If you pause and want to correct the score board clock  - upon resume - 1)close the scoreboard tab 2)change the working time to the remaining time 3)submit 4)reopen the Score Boeard  -->
  

  <hr color = "green"> <font color = "blue"> </font>
  <p><input type="hidden" name="from_QREStart" id="from_QREStart"  value=true ></p>
   <p><input type="hidden" name="globephase" id="globephase"  value=<?php echo $globephase; ?> ></p>
   <p><input type="hidden" name="currentclass_id" id="currentclass_id"  value=<?php echo $currentclass_id; ?> ></p>
   <p><input type="hidden" name="iid" id="iid"  value=<?php echo $iid; ?> ></p>
   <p><input type="hidden" name="nom_time" id="nom_time" value=<?php echo $nom_time; ?> ></p>
    <!--  <p><input type="hidden" name="exam_num_here" id="exam_num"  value=<?php echo $exam_num; ?> ></p>  -->
      <p><input type="hidden" name="eexamtime_id" id="examtime_id"  value=<?php echo $eexamtime_id; ?> ></p>
      <p><input type="hidden" name="eexamnow_id" id="eexamnow_id"  value=<?php echo $eexamnow_id; ?> ></p>
      <p><input type="hidden" name="exam_code" id="exam_code"  value=<?php echo $exam_code; ?> ></p>
      <!-- <?php echo ' game_code ' . $eexamnow_id; ?> -->
      <p><input type="hidden" name="end_of_phase" id="end_of_phase"  value=<?php echo $end_of_phase; ?> ></p>
   
   
	<p><input type="hidden" name="globephase" id="globephase" size=3 value=<?php echo $globephase; ?> ></p>
     <h2><input type="checkbox" name="phase_change" value = 1 style="height:15px; width:15px;" id="phase_change" ><?php echo $start_stop; ?> </h2>  
    <div id = "clear_data_div"> <h2><input type="checkbox" name="clear_data" value = 1 style="height:15px; width:15px;" id="clear_data" >Clear Data</h2>    </div>
  
     <p><input type = "submit" value="Submit Changes" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	
    <!--   <p>Working Time: <input type="number" name="work_time" id="work_time" size=5 value=<?php echo $work_time; ?>></p > -->
	</form>
    
  
  <form  method="POST" action = "QRExamBackStage.php" id = "backstage" target = "_blank">
      <p style="font-size:50px;"></p>
      <p><input type="hidden" name="eexamtime_id" id="eexamtime_id" value=<?php echo $eexamtime_id; ?> ></p>
      <p><input type="hidden" name="eexamnow_id" id="eexamnow_id" value=<?php echo $eexamnow_id; ?> ></p>
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
                if (globephase != "2"){document.getElementById('clear_data_div').style.display='none';}
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
   
       