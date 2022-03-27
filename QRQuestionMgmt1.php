<?php
require_once 'pdo.php';
session_start();

if (isset($_POST['iid'])) {
    $iid = $_POST['iid'];
} elseif (isset($_GET['iid'])) {
    $iid = $_GET['iid'];
} else {
    $_SESSION['error'] = 'invalid User_id in QRQuestionMgmt1 ';
    header('Location: QRPRepo.php');
    die();
}
// fix bug if no class is selected and get a pdo error____________________________________________

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_name'])) {
    // We are coming in from this file
    if (isset($_POST['currentclass_id'])) {
        $currentclass_id = $_POST['currentclass_id'];
        // echo ("current_class_id=" . $currentclass_id);

        if (isset($_POST['discipline_id'])) {
            $discipline_name = $_POST['discipline_id'];
        
            $sql = 'SELECT discipline_id FROM Discipline WHERE discipline_name = :discipline_name';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':discipline_name' => $discipline_name,
            ]);
            $discipline_iddata = $stmt->fetch();
            $discipline_id= $discipline_iddata['discipline_id'];
        
            // echo (' discipline_id= '.$discipline_id);
        } else {
            $_SESSION['error'] = 'Please Select a Discipline ';
            header('Location: QRQuestionMgmt1.php?iid='.$iid);
            die();
        }
        
        if (isset($_POST['current_course'])&& $_POST['current_course']!="Select Course") {
            $current_course = $_POST['current_course'];
         
        } else {
            $_SESSION['error'] = 'Please Select a Current Course ';
            header('Location: QRQuestionMgmt1.php?iid='.$iid);
            die();
        }
        



    } else {
        $_SESSION['error'] = 'Please Select a Current Class ';
        header('Location: QRQuestionMgmt1.php?iid='.$iid);
        die();
    }
    $new_flag = 0;

    $sql = 'SELECT * FROM QuestionTime WHERE currentclass_id = :currentclass_id AND iid = :iid';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':currentclass_id' => $_POST['currentclass_id'],
        ':iid' => $iid,
    ]);
    $questiontime_iddata = $stmt->fetch();
    if ($questiontime_iddata == false) {
        $new_flag = 1;
// we have nothing in the data table for this class
// fisrt see if they hgave set the Discipline and selected a current course or 



//! Now look for all the input
// echo ('current_course '.$current_course);

$sql = "SELECT course_id FROM Course WHERE course_name = :course_name";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':course_name' => $current_course,
]);

$course_iddata = $stmt->fetch();
$course_id = $course_iddata['course_id'];
// echo(' course_id '.$course_id);
 //var_dump($_POST);
//! start putting stuff in the questiontime table
                $sql = "INSERT INTO QuestionTime
                (currentclass_id,currentcourse_id,currentdiscipline_id,iid,start_date,stop_date,start_time,num_mon,num_tue,num_wed,num_thu,num_fri,num_sat,num_sun,grade,target_percent_current,target_percent_basic)	
						VALUES (:currentclass_id,:currentcourse_id,:currentdiscipline_id,:iid,:start_date,:stop_date,:start_time,:num_mon,:num_tue,:num_wed,:num_thu,:num_fri,:num_sat,:num_sun,:grade,:target_percent_current,:target_percent_basic)";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
						':currentclass_id'=>  $currentclass_id,
                        ':currentcourse_id' =>  $course_id,
                             ':currentdiscipline_id' => $discipline_id,
                             ':iid' => $iid,
                             ':start_date' => $_POST['global_start_date'],
                             ':stop_date' => $_POST['global_end_date'],
                             ':start_time' =>$_POST['global_start_time'],
                             ':num_mon' => $_POST['num_mon'],
                             ':num_tue' => $_POST['num_tue'],
                             ':num_wed' => $_POST['num_wed'],
                             ':num_thu' => $_POST['num_thu'],
                             ':num_fri' => $_POST['num_fri'],
                             ':num_sat' => $_POST['num_sat'],
                             ':num_sun' => $_POST['num_sun'],
                             ':grade' => $_POST['grade'],
                             ':target_percent_current' => $_POST['target_percent_current'],
                             ':target_percent_basic' => $_POST['target_percent_basic']
						));
                $sql = 'SELECT LAST_INSERT_ID()';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                ));
                $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                $questiontime_id=$row2['LAST_INSERT_ID()'];
        

//? get the current concepts 
        $current_starts_with = "current_concept_id";
        $past_starts_with = "past_concept_id";
        foreach($_POST as $key => $value){
         //   echo (' key '.$key);
            $explode_key = explode('-',$key);
            if($explode_key[0]==$current_starts_with){
                $concept_id = $explode_key[2];
                $course = $explode_key[1];
                $start_data_key = 'current_concept_date-'.$course.'-'.$concept_id;
                $priority_data_key = 'current_concept_priority-'.$course.'-'.$concept_id;
                $start_date = $_POST[$start_data_key];
                $priority = $_POST[ $priority_data_key];
                // echo (' start_date: '.$start_date);
                // echo (' priority: '.$priority);
                // echo (' concept_id '.$concept_id);
                $sql = "INSERT INTO QuestiontimeConceptConnect (questiontime_id,concept_id,priority,current_flag,concept_start_date) 
                VALUES(:questiontime_id,:concept_id,:priority,:current_flag,:concept_start_date)";
                					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
						':questiontime_id'=>  $questiontime_id,
                        ':concept_id' =>  $concept_id,
                             ':priority' => $priority,
                             ':current_flag' => '1',
                             ':concept_start_date' => $start_date,
						));
            }

            if($explode_key[0]==$past_starts_with){
                $concept_id = $explode_key[2];
                $course = $explode_key[1];
                $start_data_key = 'past_concept_date-'.$course.'-'.$concept_id;
                $priority_data_key = 'past_concept_priority-'.$course.'-'.$concept_id;
                $start_date = $_POST[$start_data_key];
                $priority = $_POST[ $priority_data_key];
                $sql = "INSERT INTO QuestiontimeConceptConnect (questiontime_id,concept_id,priority,current_flag,past_course_id,concept_start_date) 
                VALUES(:questiontime_id,:concept_id,:priority,:current_flag,:past_course_id,:concept_start_date)";
                					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
						':questiontime_id'=>  $questiontime_id,
                        ':concept_id' =>  $concept_id,
                             ':priority' => $priority,
                             ':current_flag' => '0',
                             ':past_course_id' => $course,
                             ':concept_start_date' => $start_date,
						));
            }

        }


    } else {

    //?    we are comming in from this file and have questiontime_id  we need to update instead of Insert

        $questiontime_id = $questiontime_iddata['questiontime_id'];
       if (!isset($questiontime_id)){
           $_SESSION["error"] = "Could not find the questiontime_id ";
           header('Location: QRQuestionMgmt1.php?iid='.$iid);
           die();
       }
   $course_id = $questiontime_iddata['currentcourse_id'];    
   $discipline_id = $questiontime_iddata['currentdiscipline_id'];    
       
    //   echo "update tables";
       //! start putting stuff in the questiontime table
       $sql = "REPLACE INTO QuestionTime
       (questiontime_id,currentclass_id,currentcourse_id,currentdiscipline_id,iid,start_date,stop_date,start_time,num_mon,num_tue,num_wed,num_thu,num_fri,num_sat,num_sun,grade,target_percent_current,target_percent_basic)	
               VALUES (:questiontime_id,:currentclass_id,:currentcourse_id,:currentdiscipline_id,:iid,:start_date,:stop_date,:start_time,:num_mon,:num_tue,:num_wed,:num_thu,:num_fri,:num_sat,:num_sun,:grade,:target_percent_current,:target_percent_basic)";
           $stmt = $pdo->prepare($sql);
           $stmt->execute(array(
               ':questiontime_id'=>  $questiontime_id,
               ':currentclass_id'=>  $currentclass_id,
               ':currentcourse_id' =>  $course_id,
                    ':currentdiscipline_id' => $discipline_id,
                    ':iid' => $iid,
                    ':start_date' => $_POST['global_start_date'],
                    ':stop_date' => $_POST['global_end_date'],
                    ':start_time' =>$_POST['global_start_time'],
                    ':num_mon' => $_POST['num_mon'],
                    ':num_tue' => $_POST['num_tue'],
                    ':num_wed' => $_POST['num_wed'],
                    ':num_thu' => $_POST['num_thu'],
                    ':num_fri' => $_POST['num_fri'],
                    ':num_sat' => $_POST['num_sat'],
                    ':num_sun' => $_POST['num_sun'],
                    ':grade' => $_POST['grade'],
                    ':target_percent_current' => $_POST['target_percent_current'],
                    ':target_percent_basic' => $_POST['target_percent_basic']
               ));
               $sql = 'DELETE FROM  QuestiontimeConceptConnect WHERE questiontime_id = :questiontime_id ';
               $stmt = $pdo->prepare($sql);
               $stmt ->execute(array(
                   ':questiontime_id' => $questiontime_id,
               ));

       
//? get the current concepts 
        $current_starts_with = "current_concept_id";
        $past_starts_with = "past_concept_id";
        foreach($_POST as $key => $value){
         //   echo (' key '.$key);
            $explode_key = explode('-',$key);
            if($explode_key[0]==$current_starts_with){
                $concept_id = $explode_key[2];
                $course = $explode_key[1];
                $start_data_key = 'current_concept_date-'.$course.'-'.$concept_id;
                $priority_data_key = 'current_concept_priority-'.$course.'-'.$concept_id;
                $start_date = $_POST[$start_data_key];
                $priority = $_POST[ $priority_data_key];
                // echo (' start_date: '.$start_date);
                // echo (' priority: '.$priority);
                // echo (' concept_id '.$concept_id);



                $sql = "REPLACE INTO QuestiontimeConceptConnect (questiontime_id,concept_id,priority,current_flag,concept_start_date) 
                VALUES(:questiontime_id,:concept_id,:priority,:current_flag,:concept_start_date)";
                					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
						':questiontime_id'=>  $questiontime_id,
                        ':concept_id' =>  $concept_id,
                             ':priority' => $priority,
                             ':current_flag' => '1',
                             ':concept_start_date' => $start_date,
						));
            }

            if($explode_key[0]==$past_starts_with){
                $concept_id = $explode_key[2];
                $course = $explode_key[1];
                $start_data_key = 'past_concept_date-'.$course.'-'.$concept_id;
                $priority_data_key = 'past_concept_priority-'.$course.'-'.$concept_id;
                $start_date = $_POST[$start_data_key];
                $priority = $_POST[ $priority_data_key];



                $sql = "REPLACE INTO QuestiontimeConceptConnect (questiontime_id,concept_id,priority,current_flag,past_course_id,concept_start_date) 
                VALUES(:questiontime_id,:concept_id,:priority,:current_flag,:past_course_id,:concept_start_date)";
                					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
						':questiontime_id'=>  $questiontime_id,
                        ':concept_id' =>  $concept_id,
                             ':priority' => $priority,
                             ':current_flag' => '0',
                             ':past_course_id' => $course,
                             ':concept_start_date' => $start_date,
						));
            }

        }









        }
  //! we have submitted it is time to move on to the
  //echo "time to move on";
  $_SESSION['success'] = 'data was successfully submitted';
  header('Location: QRQuestionMgmt2.php?iid='.$iid.'&questiontime_id='.$questiontime_id);
  die();

    
}
        //? check to see if the currentclass_id is a get parameter - that would mean they changed the currentclass and the page reloaded via js
        //? Not coming in from this file need to get the information from the table if it is available in
    

            //?         Set some default values
            $num_mon = $num_tue = $num_wed = $num_thu = $num_fri =  "2";
            $num_sat = $num_sun = 0;
            $start_date = date("Y-m-d");
            $stop_date = date("Y-m-d", strtotime("+4 months", strtotime(date("Y-m-d"))));
            $start_time = "08:00";
            $grade = 4;
            $target_percent_basic = 100;
            $target_percent_current = 100;

            $currentclass_id = '';
        if (isset($_GET['iid'])&& isset($_GET['currentclass_id'])){
        $currentclass_id = $_GET['currentclass_id'];
        $sql = 'SELECT `name` FROM `CurrentClass` WHERE `currentclass_id` = :currentclass_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':currentclass_id' => $currentclass_id]);
        $currentclass_data = $stmt->fetch(PDO::FETCH_ASSOC);
        $currentclassName = $currentclass_data['name'];


      $stmt = "SELECT *
        FROM QuestionTime JOIN QuestiontimeConceptConnect
        ON QuestiontimeConceptConnect.questiontime_id = QuestionTime.questiontime_id 
        WHERE  QuestionTime.currentclass_id = :currentclass_id";
            $stmt = $pdo->prepare($stmt);	
            $stmt->execute(array(
                ':currentclass_id' => $currentclass_id,
            ));
             $qt_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
           
           
             if($qt_data){
                $num_mon = $qt_data[0]['num_mon'];
                $num_tue = $qt_data[0]['num_tue'];
                $num_wed = $qt_data[0]['num_wed'];
                $num_thu = $qt_data[0]['num_thu'];
                $num_fri = $qt_data[0]['num_fri'];
                $num_sat = $qt_data[0]['num_sat'];
                $num_sun = $qt_data[0]['num_sun'];
                $start_date = $qt_data[0]['start_date'];
                $start_date = date('Y-m-d',strtotime($start_date));
              //  var_dump($start_date);
              //  echo('start_date = ' . $start_date);
                $stop_date = $qt_data[0]['stop_date'];
                $stop_date =date('Y-m-d',strtotime($stop_date));
              //  echo('stop_date = ' . $stop_date);
                $start_time = $qt_data[0]['start_time'];
                $start_time =date('H:i',strtotime($start_time));
            //    echo 'start_time = ' . $start_time;
                $grade = $qt_data[0]['grade'];
                $target_percent_basic = $qt_data[0]['target_percent_basic'];
                $target_percent_current = $qt_data[0]['target_percent_current'];
                $discipline_id = $qt_data[0]['currentdiscipline_id'];
                if ($discipline_id){
                    $sql = 'SELECT `discipline_name` FROM `Discipline` WHERE `discipline_id` = :discipline_id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':discipline_id' => $discipline_id]);
                    $discipline_data = $stmt->fetch(PDO::FETCH_ASSOC);
                    $disciplineName = $discipline_data['discipline_name'];
            
                }
                $currentcourse_id = $qt_data[0]['currentcourse_id'];
            //    echo '$currentcourse_id = '.$currentcourse_id;             
                if ($currentcourse_id){
                    $sql = 'SELECT `course_name` FROM `Course` WHERE `course_id` = :currentcourse_id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':currentcourse_id' => $currentcourse_id]);
                    $course_data = $stmt->fetch(PDO::FETCH_ASSOC);
                    $current_course =  $course_data['course_name'];
            
                }


            }

//! from QRdisplaypblm
           
            $i = 0;
            $j = 0;
           
             $pass = array();
            foreach ($qt_data as $qt_datum){
              //  var_dump($qt_datum);
                if ($qt_datum['current_flag']==1){
                    $c_id = $qt_datum['concept_id'];
                   
                    $check_concept_id['ccId_'.$i] = $current_course.'-'.$qt_datum["concept_id"];
                    $check_concept_date['ccDate_'.$i] = $qt_datum["concept_start_date"];
                    $check_concept_priority['ccPriority_'.$i] = $qt_datum["priority"];
                    
                  
                  //   echo (' $check_concept_id["ccId_".$i] = '.$check_concept_id["ccId_".$i]);
                    // echo (' $check_concept_date[$i] = '.$check_concept_date[$i]);
                    // echo (' $check_concept_priority[$i] = '.$check_concept_priority[$i]);
                    $i++;
                } else {

                    //? get the data of the past courses and send the info to the script
                    $check_past_concept_id['pcId_'.$j] = $qt_datum["past_course_id"].'-'.$qt_datum["concept_id"];
            //        echo ("  past_concept_id   ". $check_past_concept_id['pcId_'.$j]);
                    // $check_past_concept_id['pcId_'.$j] = 'past_concept_id-'.$c_id.'-'.$qt_datum["concept_id"];
                    $check_past_concept_date['pcDate_'.$j] =  $qt_datum["concept_start_date"];
                    $check_past_concept_priority['pcPriority_'.$j] =  $qt_datum["priority"];
         //            echo '  $check_past_concept_id["pcId_".$j] '. $check_past_concept_id["pcId_".$j] ;
                    // echo '   $check_past_concept_date[$j] '.  $check_past_concept_date[$j];
                    // echo '  $check_past_concept_priority[$j] '. $check_past_concept_priority[$j];
                    $j++;
                }


                // echo (' qt_datum["concept_id"] ='.$qt_datum["concept_id"]);
                // echo (' qt_datum["questiontime_id"] ='.$qt_datum["questiontime_id"]);
                // echo (' qt_datum["current_flag"] ='.$qt_datum["current_flag"]);
                // echo (' qt_datum["concept_start_date"] ='.$qt_datum["concept_start_date"]);

            }
            if( isset( $check_concept_id) && $check_concept_date && $check_concept_priority){
                $pass['num_cc'] = $i-1;
            $pass = $pass + array_merge($check_concept_id,$check_concept_date,$check_concept_priority);
            }
            if(isset($check_past_concept_id) && $check_past_concept_date && $check_past_concept_priority){
                $pass['num_pc'] = $j-1;

            $pass = $pass + array_merge($check_past_concept_id,$check_past_concept_date, $check_past_concept_priority);
            }


            echo '<script>';
            echo 'var pass = ' . json_encode($pass) . ';';
            echo '</script>';


        }





// this will be called form the main repo when the game master wants to run a game
// this is just to get the game number and go on to QRGMaster.php with a post of the game number.
// Validity will be checked in that file and sent back here if it is not valid

$_SESSION['counter'] = 0; // this is for the score board

if (isset($_SESSION['error'])) {
    echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo '<p style="color:green">' . $_SESSION['success'] . "</p>\n";
    unset($_SESSION['success']);
}
?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QR Question Mgmt</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<style>
   .outer {
  width: 100%;
  margin: 0 auto;
 
}
.hide{ 
display: none;

}

body {margin:2em;padding:0}

</style>



</head>

<body>
<header>
<h1>Quick Response Question Managment</h1>
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

<!--<h3>Print the problem statement with "Ctrl P"</h3>
 <p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p>  -->
<form id = "the_form"  method = "POST"  >

<h4>Target Number of New Questions to Deliver:</h4>
<label for = "num-mon">Monday</label>
<input type = "number" min = "0" max = "99"  name = "num_mon" id = "num-mon" class - "num-Q-per_day input-group-number p-2 me-2" value = "<?php echo $num_mon;?>" ></input> &nbsp;
<label for = "num-tue">Tuesday</label>
<input type = "number" min = "0" max = "99"  name = "num_tue" id = "num-tue" class - "num-Q-per_day input-group-number" value = "<?php echo $num_tue;?>" ></input> &nbsp;
<label for = "num-wed">Wednesday</label>
<input type = "number" min = "0" max = "99"  name = "num_wed" id = "num-wed" class - "num-Q-per_day input-group-number p-2 me-2" value = "<?php echo $num_wed;?>" ></input> &nbsp;
<label for = "num-thu">Thursday</label>
<input type = "number" min = "0" max = "99"  name = "num_thu" id = "num-thu" class - "num-Q-per_day input-group-number" value = "<?php echo $num_thu;?>" ></input> &nbsp;
<label for = "num-fri">Friday</label>
<input type = "number" min = "0" max = "99"  name = "num_fri" id = "num-fri" class - "num-Q-per_day input-group-number" value = "<?php echo $num_fri;?>" ></input> &nbsp;
<label for = "num-sat">Saturday</label>
<input type = "number" min = "0" max = "99"  name = "num_sat" id = "num-sat" class - "num-Q-per_day input-group-number p-2 me-2" value = "<?php echo $num_sat;?>" ></input> &nbsp;
<label for = "num-sun">Sunday</label>
<input type = "number" min = "0" max = "99"  name = "num_sun" id = "num-sun" class - "num-Q-per_day input-group-number" value = "<?php echo $num_sun;?>" ></input> &nbsp;
<br>
<br>
<h4>Delivery Timing:</h4>
<label for = global_start_date>Global Start Date </label>
<input type = "date" name = "global_start_date" id = "global_start_date" class = "form-control-inline m-3" value = "<?php echo($start_date);?>"></input>
<label for = global_end_date>Global End Date </label>
<input type = "date" name = "global_end_date" id = "global_end_date" class = "form-control-inline m-3" value = "<?php echo( $stop_date);?>"></input>
<label for = global_start_time>Time to Email First Question</label>
<input type = "time" name = "global_start_time" id = "global_start_time" class = "form-control-inline m-3" value =  "<?php echo( $start_time);?>" ></input>
<br>
<h4>Participants:</h4>
<div id ="current_class_dd">	
				Current Class: &nbsp;
				<select name = "currentclass_id" id = "currentclass_id" required>
                    <?php if(isset($currentclass_id)){echo ('<option value = "'.$currentclass_id.'" selected > '.$currentclassName.'  </option> ');} else {echo ('<option value = "" selected disabled hidden > Select Current Class  </option> ');}?>
			
				<?php
    $sql = 'SELECT * FROM `CurrentClass` WHERE `iid` = :iid';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':iid' => $iid]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
						<option value="<?php echo $row['currentclass_id']; ?>" ><?php echo $row['name']; ?> </option>	<?php }
    ?>
                    
                    
				</select>
		</div>
        <br>

    <h4>Types of Problems:</h4>
    <label for = grade>Grade Level </label>
    <input type = "number" min = "1" max = "4" name = "grade" id = "grade" class = "form-control-inline m-3" value =  "<?php echo( $grade);?>"></input>
    <label for = target_percent_current>Target Percentage of Current Questions</label>
    <input type = "number" min = "0" max = "100" name = "target_percent_current" id = "target_percent_current" class = "form-control-inline m-3" value =  "<?php echo( $target_percent_current);?>"></input>
    <label for = target_percent_basic>Target Percentage of Basic Level Questions </label>
    <input type = "number" min = "0" max = "100" name = "target_percent_basic" id = "target_percent_basic" class = "form-control-inline m-3" value =  "<?php echo( $target_percent_basic);?>"></input>

        <br>

<h4>Concepts Covered:</h4>

    <div id ="discipline_id">	
				Discipline (re-select to edit): &nbsp;
				<select name = "discipline_id" id = "discipline-id">
				 <option value = "" selected disabled hidden > Select Discipline</option>  
                 <!-- <?php if(isset($discipline_id)){echo ('<option value = "'.$discipline_id.'" selected > '.$disciplineName.'  </option> ');} else {echo ('<option value = "" selected disabled hidden > Select Discipline  </option> ');}?> -->
				<?php
    $sql = 'SELECT * FROM `Discipline`';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
						<option value="<?php echo $row['discipline_name']; ?>" ><?php echo $row['discipline_name']; ?> </option> <?php } ?>
                    
                    
				</select>
		</div>

            <br>
                <font color=#003399>Current Course &nbsp; </font>
                    
                    <select id="current-course" name = "current_course"  >
                    <!-- <?php if(isset($currentcourse_id)){echo ('<option value = "'.$currentcourse_id.'" selected > '.$current_course.'  </option> ');} else {echo ('<option value = "" selected disabled hidden > Select Course  </option> ');}?> -->
                       <option value = ""  selected disabled hidden >- Select Course -</option>
                    </select>
                <br>

                <br>

                <div id="p_concept">



                </div>                    
                 <br>
                <br>


<button type = "button" class = "btn btn-outline-secondary hide" id = "add-past-btn" >Add Concepts From Other Courses</button>
<div id = "past-courses" class = "hide">

    </div>
                    
            <p><input type="hidden" name="iid" id="iid" value=<?php echo $iid; ?> ></p>
			<p><input type="hidden" name="where_from" id="where_from" value="QRQuestionMgmt1" ></p>
			<p><input type = "submit" name = "submit_name" id = "submit_id" value = "Submit"></p><hr><br>
	</form>

  <p style="font-size:20px;"></p>   
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	

  


	<script>
 
 $(document).ready(function(){





let iid = document.getElementById("iid").value;
let current_class = document.getElementById('currentclass_id');
current_class.addEventListener('change', getClass_id);
function getClass_id(){
    //console.log ("change",current_class.value);
    location.href= 'QRQuestionMgmt1.php?iid='+iid+'&currentclass_id='+current_class.value
}



function addPastClasses (){
  //  console.log ("addPastClasses");
//? this one just takes off the hidden class from the past classes checkboxes
        let past_courses= document.getElementById("past-courses");
        let selected_current_course = document.getElementById('current-course');

      //  console.log ("selected_current_course",selected_current_course);
        selected_current_course = selected_current_course.options[selected_current_course.options.selectedIndex].dataset.course_id;
   //     console.log ("selected_current_course",selected_current_course);
        let current_course_from_past_list = document.getElementById("past_course_button_id-"+selected_current_course);
        current_course_from_past_list.classList.add("hide");
            // console.log ("past_courses",past_courses);
            past_courses.classList.remove("hide");


            const past_course = document.querySelectorAll(".past-course");
     //       console.log ("past_course",past_course);
            for (let i of past_course) {
            i.addEventListener("click", (e) => {
                if (e.target.classList.contains("past-course")) {
                    console.log ("e",e.target);
                   let previous_checkbox_toggle = document.getElementById("check_btn_"+e.target.id);
                   if (previous_checkbox_toggle){
                       previous_checkbox_toggle.classList.add("hide");
                   }
                    let checkButton = document.createElement("button");
                    checkButton.setAttribute("type", "button");
                    checkButton.setAttribute("value", "toggleCheck");
                    checkButton.setAttribute("id", "check_btn_"+e.target.id);
                    checkButton.setAttribute("class", "btn btn-outline-primary ms-2 checkPastConcept");
                    checkButton.innerHTML = "Toggle Check Boxes";
                    checkButton.addEventListener("click", (event) => {
                        console.log("event.id",event.target.id);
                        let event_target_number = event.target.id.slice(event.target.id.lastIndexOf("-"))
                        console.log("event_target_number",event_target_number);
                        let past_concept2 = "past_concept_id"+ event_target_number;
                        console.log ("past_concept2",past_concept2);
                        var $eles2 = $(":input[name^='"+past_concept2+"']");  //? select all of the elements whos name starts with current_concept_id using JQ
                        var checkboxes2 = $eles2.get();                   //? change it into a dom element using the get method 
                        console.log("checkboxes2",checkboxes2);


          //              const checkboxes2 = document.querySelectorAll('input[name="'+past_concept2+'"]');
                        checkboxes2.forEach((checkbox) => {
                            if(checkbox.checked == true) {checkbox.checked = false;} else {checkbox.checked = true;};
                            // checkbox.checked = checked;
                        });
                    })

                    // checkButton.setAttribute("", "toggleCheck");
                    e.target.parentNode.insertBefore(checkButton,e.target.nextSibling);
                  let past_course_id = e.target.id;
                  past_course_id = past_course_id.slice(past_course_id.lastIndexOf('-'));
            //      console.log ("past_course_id",past_course_id);
                  past_concept_class = "oldConcept"+ past_course_id;
                  let past_concept = document.querySelectorAll("."+past_concept_class);
                  console.log('past_concept',past_concept.length);
                  for (let j=0; j<past_concept.length;j++){
                        console.log ("past_concept",past_concept[j]);
                        if ( past_concept[j].classList.contains("hide")){past_concept[j].classList.remove("hide")} else {past_concept[j].classList.add("hide")}

                  }
                }
            })
            }

// put in the values from the data base via php pass variable: 


                for (let i = 0; i <= pass['num_pc']; i++) {
                        let identifier = 'past_concept_id-'+pass['pcId_'+i];
                       console.log(" identifier = ",identifier);
                     let  pc_checkbox = document.getElementById(identifier);
                //     console.log(" pc_checkbox = ",pc_checkbox);
                     if(pc_checkbox){
                        pc_checkbox.checked = true;
                     }

                     identifier = 'past_concept_date-'+pass['pcId_'+i];
             //          console.log(" identifier = ",identifier);
                     let  pc_date = document.getElementById(identifier);
                 //    console.log(" pc_date = ",pc_date);
                     if(pc_date){
                   //      console.log ('pass["pcDate"+i]',pass["pcDate_"+i]);
                         let pcDate = new Date(pass["pcDate_"+i]).toISOString().split('T')[0];
                    //     console.log (" pcDate = " , pcDate);
                        pc_date.value =pcDate;
                     }
                     identifier = 'past_concept_priority-'+pass['pcId_'+i];
                       console.log(" identifier = ",identifier);
                     let  pc_priority = document.getElementById(identifier);
                     console.log(" pc_priority = ",pc_priority);
                     if(pc_date){
                         console.log ('pass["pcPriority"+i]',pass["pcPriority_"+i]);
                        pc_priority.value =pass["pcPriority_"+i];
                     }

                           
                    }






    }

    $("#discipline-id").change(function(){
        let add_past_btn = document.getElementById('add-past-btn');
     
       
        // add_past_btn.classList.remove("hide");
        add_past_btn.addEventListener("click",addPastClasses)

				var discipline = $("#discipline-id").val();
           //     console.log("discipline",discipline);
				$.ajax({
					url: 'dcData.php',
					method: 'post',
					data: 'discipline=' + discipline
				}).done(function(course){
					 course = JSON.parse(course);
                     let current_course = document.getElementById('current-course');
                     current_course.options.length = 0;
                     $('#current-course').append('<option> Select Course</option>') 
					course.forEach(function(course){
						$('#current-course').append('<option data-course_id = ' + course.course_id + '>' + course.course_name + '</option>') 
			//			 $('#current-course').append('<span id = "course_id-'+course.course_id+'" class = "course-container"><input  class="current-course" type ="checkbox">' + course.course_name + '</input><br></span>') 
						 $('#past-courses').append('<span id = "past_course_id-'+course.course_id+'" class = "past-course-container"><button  class="past-course" type ="button" id = "past_course_button_id-'+course.course_id+'" >' + course.course_name + '</button><br></span>') 
                        
                         // get the concepts for that course_name
                        $.ajax({
                            url: 'ccData.php',
                            method: 'post',
                            data: 'course=' + course.course_name
                        }).done(function(p_concept){
                            concept = JSON.parse(p_concept);
                       //     console.log ("concept",concept);

                            concept.forEach(function(concept){
                                let now =new Date();
                                    now=now.toISOString().substring(0,10);
                    //            console.log("now",now);
                      //              let past_course_id = "course_id"

                                 $('#past_course_id-'+course.course_id).append('<span class = "hide oldConcept-'+course.course_id+'"  >&nbsp;<input name = "past_concept_id-'+course.course_id+'-'+concept.concept_id+'" id = "past_concept_id-'+course.course_id+'-'+concept.concept_id+'" class = "form-check-input m-2 mt-3" type = "checkbox">' + concept.concept_name + '</input>&nbsp;<input type = "date" name = "past_concept_date-'+course.course_id+'-'+concept.concept_id+'" id = "past_concept_date-'+course.course_id+'-'+concept.concept_id+'" class = "form-control-inline m-2" value= "'+now+'"></input><input type = "number"  min = "1" max ="3" name = "past_concept_priority-'+course.course_id+'-'+concept.concept_id+'" id = "past_concept_priority-'+course.course_id+'-'+concept.concept_id+'" class = "form-control-inline m-2" value= "1">Priority (high = 1)</input><br></span>') 
                                //  $('#past_course_id-'+course.course_id).append('<span class = "hide oldConcept-'+course.course_id+'"  >&nbsp;<input name = "past_concept_id-'+course.course_id+'-'+concept.concept_id+'" id = "past_concept_id-'+concept.concept_id+'" class = "form-check-input m-2 mt-3" type = "checkbox">' + concept.concept_name + '</input>&nbsp;<input type = "date" name = "past_concept_date-'+course.course_id+'-'+concept.concept_id+'" id = name = "past_concept_date-'+concept.concept_id+'" class = "form-control-inline m-2" value= "'+now+'"></input><input type = "number"  min = "1" max ="3" name = "past_concept_priority-'+course.course_id+'-'+concept.concept_id+'" id = "past_concept_priority-'+concept.concept_id+'" class = "form-control-inline m-2" value= "1">Priority (high = 1)</input><br></span>') 
                                //  $('#past_course_id-'+course.course_id).append('<span class = "hide oldConcept-'+course.course_id+'"  >&nbsp;<input name = "past_concept_id-'+course.course_id+'-'+concept.concept_id+'" id = "past_concept_id-'+concept.concept_id+'" class = "form-check-input m-2 mt-3" type = "checkbox">' + concept.concept_name + '</input>&nbsp;<input type = "date" name = "past_concept_date-'+course.course_id+'-'+concept.concept_id+'" class = "form-control-inline m-2" value= "'+now+'"></input><input type = "number"  min = "1" max ="3" name = "past_concept_priority-'+course.course_id+'-'+concept.concept_id+'" id = "past_concept_priority-'+course.course_id+'-'+concept.concept_id+'" class = "form-control-inline m-2" value= "1">Priority (high = 1)</input><br></span>') 
                                // $('#past_course_id-'+course.course_id).append('<span class = "hide oldConcept-'+course.course_id+'"  >&nbsp;<input name = "past_concept_id-'+course.course_id+'-'+concept.concept_id+'" id = "past_concept_id-'+concept.concept_id+'" class = "form-check-input m-2 mt-3" type = "checkbox">' + concept.concept_name + '</input>&nbsp;<input type = "date" name = "past_concept_date-'+course.course_id+'-'+concept.concept_id+'" class = "form-control-inline m-2" value= "'+now+'"></input><br></span>') 
                            })
                        })

					 })
				})


			})

            function check(checked = true) {
                var $eles = $(":input[name^='current_concept_id']");  //? select all of the elements whos name starts with current_concept_id using JQ
                var checkboxes = $eles.get();                   //? change it into a dom element using the get method 
                checkboxes.forEach((checkbox) => {
                    if(checkbox.checked == true) {checkbox.checked = false;} else {checkbox.checked = true;};
            });
        }


			$("#current-course").change(function(){
                let add_past_btn2 = document.getElementById('add-past-btn');

                add_past_btn2.classList.remove("hide");

				var course = $("#current-course").val();
				$.ajax({
					url: 'ccData.php',
					method: 'post',
					data: 'course=' + course
				}).done(function(p_concept){
					 concept = JSON.parse(p_concept);
             //        console.log (concept);
                    let pp_concept = document.getElementById('p_concept');
                    pp_concept.innerHTML = '';
                    $('#p_concept').append('<button type = "button" class="btn btn-primary mt-3 mb-2" id = "check-all-btn"> Toggle Checkboxes </button> <span>  Note - you can adjust start date for each concept</span><br>') 
                    const check_all_btn = document.querySelector('#check-all-btn');
                    check_all_btn.addEventListener('click',check)
                     

					concept.forEach(function(concept){
                        let now =new Date();
                            now=now.toISOString().substring(0,10);
            //            console.log("now",now);
						$('#p_concept').append('&nbsp;<input name = "current_concept_id-'+course+'-'+concept.concept_id+'" id = "current_concept_id-'+course+'-'+concept.concept_id+'" class = "form-check-input m-2 mt-3" type = "checkbox">' + concept.concept_name + '</input>&nbsp;<input name = "current_concept_date-'+course+'-'+concept.concept_id+'" id = "current_concept_date-'+course+'-'+concept.concept_id+'" type = "date" class = "form-control-inline m-2" value= "'+now+'"></input><input type = "number" min = "1" max ="3" name = "current_concept_priority-'+course+'-'+concept.concept_id+'" id = "current_concept_priority-'+course+'-'+concept.concept_id+'" class = "form-control-inline m-2" value= "1">Priority (high = 1)</input><br>') 
					 })
                     
                     for (let i = 0; i <= pass['num_cc']; i++) {
                        let identifier = 'current_concept_id-'+pass['ccId_'+i];
              //         console.log(" identifier = ",identifier);
                     let  cc_checkbox = document.getElementById(identifier);
                //     console.log(" cc_checkbox = ",cc_checkbox);
                     if(cc_checkbox){
                        cc_checkbox.checked = true;
                     }

                     identifier = 'current_concept_date-'+pass['ccId_'+i];
             //          console.log(" identifier = ",identifier);
                     let  cc_date = document.getElementById(identifier);
                 //    console.log(" cc_date = ",cc_date);
                     if(cc_date){
                   //      console.log ('pass["ccDate"+i]',pass["ccDate_"+i]);
                         let ccDate = new Date(pass["ccDate_"+i]).toISOString().split('T')[0];
                    //     console.log (" ccDate = " , ccDate);
                        cc_date.value =ccDate;
                     }
                     identifier = 'current_concept_priority-'+pass['ccId_'+i];
                  //     console.log(" identifier = ",identifier);
                     let  cc_priority = document.getElementById(identifier);
                 //    console.log(" cc_priority = ",cc_priority);
                     if(cc_date){
                  //       console.log ('pass["ccPriority"+i]',pass["ccPriority_"+i]);
                        cc_priority.value =pass["ccPriority_"+i];
                     }

                           
                    }
                     
                   


				})

			})

})

	
</script>	

</body>
</html>



