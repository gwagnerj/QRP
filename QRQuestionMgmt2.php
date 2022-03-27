<?php
require_once 'pdo.php';
session_start();

if (isset($_POST['iid'])) {
    $iid = $_POST['iid'];
} elseif (isset($_GET['iid'])) {
    $iid = $_GET['iid'];
} else {
    $_SESSION['error'] = 'invalid iid in QRQuestionMgmt ';
    header('Location: QRPRepo.php');
    die();
}

if (isset($_POST['questiontime_id'])) {
    $questiontime_id = $_POST['questiontime_id'];
} elseif (isset($_GET['questiontime_id'])) {
    $questiontime_id = $_GET['questiontime_id'];
} else {
    $_SESSION['error'] = 'invalid questiontime_id in QRQuestionMgmt ';
    header('Location: QRPRepo.php');
    die();
}


$sql = 'SELECT * 
       FROM QuestionTime
        WHERE  QuestionTime.questiontime_id = :questiontime_id';
            $stmt = $pdo->prepare($sql);	
            $stmt->execute(array(
                ':questiontime_id' => $questiontime_id,
            ));
             $qt_datum = $stmt->fetch(PDO::FETCH_ASSOC);

// $sql = 'SELECT * 
//        FROM QuestionTime
//         JOIN QuestiontimeConceptConnect
//         ON QuestiontimeConceptConnect.questiontime_id = QuestionTime.questiontime_id 
//         JOIN Concept
//          ON QuestiontimeConceptConnect.concept_id = Concept.concept_id 
//         WHERE  QuestionTime.questiontime_id = :questiontime_id';
//             $stmt = $pdo->prepare($sql);	
//             $stmt->execute(array(
//                 ':questiontime_id' => $questiontime_id,
//             ));
//              $qt_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

             if(!$qt_datum){
                 $_SESSION['error'] = 'No data found for in QRQuestionMgmt2 for questiontime_id';
                 header('Location: QRQuestionMgmt2.php?iid='.$iid.'&questiontime_id='.$questiontime_id);
                 die();
             }
         //    var_dump($qt_datum);
            $currentclass_id = $qt_datum['currentclass_id'];
            $currentcourse_id = $qt_datum['currentcourse_id'];
            $currentdiscipline_id = $qt_datum['currentdiscipline_id'];
            $start_date = new DateTime($qt_datum['start_date']);
            $stop_date = new DateTime($qt_datum['stop_date']);
          //  echo ($stop_date);
            $start_time = $qt_datum['start_time'];
            $num_slots = array(
                'Sun' => $qt_datum['num_sun'],
                'Mon' => $qt_datum['num_mon'],
                'Tue' => $qt_datum['num_tue'],
                'Wed' => $qt_datum['num_wed'],
                'Thu' => $qt_datum['num_thu'],
                'Fri' => $qt_datum['num_fri'],
                'Sat' => $qt_datum['num_sat'],
            );
            $grade = $qt_datum['grade'];
            $target_percent_basic = $qt_datum['target_percent_basic'];
            $target_percent_current = $qt_datum['target_percent_current'];


             $num_days =(array) date_diff($stop_date , $start_date);
             $num_days = $num_days["days"];
//? now need to get all of the questions that where specified in the Questiontime table from
        $sql = 'SELECT * FROM QuestiontimeConceptConnect
            JOIN Concept
          ON QuestiontimeConceptConnect.concept_id = Concept.concept_id 
         WHERE  QuestiontimeConceptConnect.questiontime_id = :questiontime_id ORDER BY QuestiontimeConceptConnect.concept_start_date, QuestiontimeConceptConnect.priority';
             $stmt = $pdo->prepare($sql);	
             $stmt->execute(array(
                 ':questiontime_id' => $questiontime_id,
             ));
              $qconcept_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //  var_dump($qconcept_data);





        if (isset($_POST['submit_button'])){
        //    var_dump($_POST);
       //      echo 'sumtitted form';
            $set_date = array();
            $i = 0;
            $alias = 0;
            foreach($_POST as $key => $value){
                $set_date_ar = explode('_',$key);

                // print_r($set_date_ar);
                // echo '<br>';
                $question_id = explode("_",$value)[0];
                if ( count($set_date_ar)>1 && strlen($set_date_ar[1])>5 && $question_id ){
                       // echo (' set_date_ar: '.$set_date_ar[1]);
                    $set_date[$i] =  $set_date_ar[1];
                    
                  //!  $date = "Tue 2 Sep 2014";
                    $set_date_dt = date("Y-m-d H:i:s", strtotime( $set_date[$i]));
                //    echo ' set_date_dt = '.$set_date_dt;
                   
                    $j = $i-1;
                    if ($i>0 && $set_date[$i] != $set_date[ $j]){
                        $alias = 0;
                    }

                  //  echo 'alias '.$alias.' question_id: ' . $question_id . ' i: ' . $i . ' set_date: ' . $set_date[$i] .'<br>';
                  $sql = "INSERT INTO QuestionSet (questiontime_id,question_id,set_day_alias,set_date) 
                  VALUES(:questiontime_id,:question_id,:set_day_alias,:set_date)
                  ON DUPLICATE KEY UPDATE question_id = :question_id
                 ";
                                      $stmt = $pdo->prepare($sql);
                      $stmt->execute(array(
                          ':questiontime_id'=>  $questiontime_id,
                          ':question_id' =>  $question_id,
                               ':set_day_alias' => $alias,
                               ':set_date' =>  $set_date_dt,
                          ));

                    $i++;
                    $alias++;
            }
                //    echo '  key: ' . $key . ' value: ' . $value . '<br>';

                
            }

        }

            //? see what we have in the questionset if anything

            $sql = 'SELECT * FROM `QuestionSet`
            JOIN Question ON QuestionSet.question_id = Question.question_id
             WHERE `questiontime_id` = :questiontime_id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':questiontime_id' => $questiontime_id]);
            $questionset_data = $stmt->fetchALL(PDO::FETCH_ASSOC);
            foreach ($questionset_data as $qs_datum){
            //    echo '$questionset_datum[question_id] = ' . $questionset_datum['question_id'];
            $date2 = new DateTime( $qs_datum["set_date"]);
          
            $date_str_y2 =  $date2->format('M-d-y');
            
            $slot_key = 'slot_'.$date_str_y2.'_'.$qs_datum["set_day_alias"];
          //  echo ' slot_key:  '.$slot_key;
          $question_ident = $qs_datum["question_id"].'_'.$qs_datum["title"];
                
            //    echo ' question_ident: '.$question_ident;

            $slot_ar[$slot_key] = $question_ident;

            }

// var_dump ($slot_ar);



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
.container{
    display: flex
    
}
.question{
    cursor: pointer;
}
.gray{ 
    opacity: 50%;
}

body {margin:2em;padding:0}

.slot{ 
    border: 2px dashed green
}

</style>



</head>

<body>
<header>
<h1>Quick Response Question Managment</h1>
</header>
<h4> For the Question slots you want to define, drag the question from the list on the right.  Undefined slots will be filled in by the system. Right click on a Question to Preview. Submit when Finished</h4>
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


<form id = "main_form" name = "main_form" method = "post" >

<button type="submit" name = "submit_button" id = "submit_button" class="btn btn-primary">Submit</button>
<button type="button" name = "clear_all_button" id = "clear_all_button" class="btn btn-warning ms-4">Clear All Slots</button>


<input type = "hidden" name = "iid" value = "<?php echo $iid; ?>"></input>
<input type = "hidden" name = "questiontime_id" value = "<?php echo $questiontime_id; ?>"></input>

'<div class = "container ms-0">
'<div id = "target-container" class = "ms-2">
<?php
if ($start_date >= new DateTime()){$date = $start_date;} else {$date = new DateTime();}

for ($i = 0; $i < $num_days ; $i++){

    echo '<p class = "my-0">';
    $day = $date->format('D');
    $date_str = $date->format('M-d');
    $date_str_y = $date->format('M-d-y');
    $slot = $num_slots[$day];
     if($day == "Mon"){echo '<hr class = "hr">';}
    if ($slot != 0){
        echo $day.' &nbsp;';
        echo  $date_str;
        echo '<button type = "button" id = "add-slot-btn_'.$date_str_y.'" class="btn btn-outline-secondary mx-2 my-0 p-1">Add Slot</button>';
        echo '<button type = "button" id = "remove-slot-btn_'.$date_str_y.'" class="btn btn-outline-secondary mx-2 my-0 p-1">Remove Slot</button>';
        echo '<button type = "button" id = "clear-slot-btn_'.$date_str_y.'" class="btn btn-outline-secondary mx-2 my-0 p-1">Clear Slot</button>';
        echo '<br>';
        echo '<div id = "slot-container_'.$date_str.'"class = "mb-4">';
    }
    for ($j = 0; $j < $slot; $j++){
        $k = $j+1;
        if ($j==0){ echo ' &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;';}
        $slot_key = "slot_". $date_str_y."_".$j;
        if (isset($slot_ar[$slot_key])&& strlen($slot_ar[$slot_key])>5 ){$slot_value = $slot_ar[$slot_key];} else {$slot_value = "";} //? see if it already in the table and put it in there if it is
         echo '<input name = "slot_'. $date_str_y.'_'.$j.'" id = "slot_'. $date_str_y.'_'.$j.'" class = "slot primary me-3 mb-1 mt-1 mt-0" type = "text" disabled value ="'.$slot_value.'"> </input>';
    }
    if ($slot != 0){
    echo '</div>';
    }
    echo '</p>';
    $date -> add(new DateInterval('P1D'));
}




?>
</div>
<div id = "token-container" class = "ms-5">
<?php

//! need to clean this up by moving most of this to the top of the file and getting it out of the html
             foreach($qconcept_data as $qconcept_daum){
                $concept_id = $qconcept_daum['concept_id'];
                $concept_name = $qconcept_daum['concept_name'];
                $past_course_id = $qconcept_daum['past_course_id'];
                $concept_start_date = $qconcept_daum['concept_start_date'];
                $concept_stop_date = $qconcept_daum['concept_stop_date'];
// get all of the questions
                $sql = 'SELECT * FROM Question
                    WHERE  Question.primary_concept = :primary_concept';
            $stmt = $pdo->prepare($sql);	
            $stmt->execute(array(
                ':primary_concept' => $concept_name,
            ));
             $q_data = $stmt->fetchALL(PDO::FETCH_ASSOC);
            //  echo '$concept_id = ' . $concept_id;
            //  echo '$concept_name = ' . $concept_name;
            $i = 0;
          //   var_dump($q_data);
          foreach ($q_data as $q_datum){
              if (count($q_datum)!=0 && $i==0){
             //   echo  $concept_id;
                echo '<h3> Concept: '. $concept_name.'</h3>';
                echo '<br>';
   
              }
              $question_id = $q_datum['question_id'];
              $question_title = $q_datum['title'];
              echo '<div id = "'.$question_id.'_'.$question_title.'"  class = "question my-3" draggable = "true" ondragstart = "onDragStart(event);">'.$question_id. ') '.$question_title.'</div>';
              $i++;
          }
          echo '<br>';

             }

?>
</div>
</div>
</form>




<!--<h3>Print the problem statement with "Ctrl P"</h3>
 <p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p>  -->

 <a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>

	<script>

// turn all the questions gray that are already in the table into






function onDragStart(event){
//  console.log(event.target);
draggablequestion = event.target.id;
dragedquestion =event;
//console.log(dragedquestion);

}

function dragDrop(event){
  //  console.log("draggablequestion ",draggablequestion);
 //   event.preventDefault();
 //console.log (this.disabled);
  //  this.disabled = false;
    this.value =draggablequestion;
    dragedquestion.target.classList.add("gray");
}
function dragEnd(event){
    draggablequestion = "null";
    dragedquestion = "null";
 //   console.log(event.target.id);
}
function dragOver(event){
 //   console.log(event);
    event.preventDefault();
 //   this.value = event.target.id;
}
function dragEnter(event){
 //   console.log(event.target.id);

}
function dragLeave(event){
 //   console.log(event.target.id);

}



 
 $(document).ready(function(){
    let draggablequestion = "null";
    let dragedquestion = "null";

    let slots = document.querySelectorAll(".slot");
    slots.forEach((slot) =>{
        
        let slot_value = slot.value;
        console.log("slot_value",slot_value);
        
        if (slot_value){document.getElementById(slot_value).classList.add("gray");}
        // let gray_out = document.getElementById(slot_value);
        // gray_out.classList.add("gray");


        slot.addEventListener('drop',dragDrop)
        slot.addEventListener('dragover',dragOver)
        slot.addEventListener('dragenter',dragEnter)
        slot.addEventListener('dragleave',dragLeave)
    })
    let questions = document.querySelectorAll(".question");
    questions.forEach((question) =>{
        question.addEventListener('dragend',dragEnd)
    })

    let main_form = document.getElementById('main_form');
    let submit_button = document.getElementById('submit_button');
    submit_button.addEventListener('click', function(e) {
   // console.log ("clk");
    slots.forEach((slot) =>{
         slot.disabled = false;
          if (!slot.value){ 
              // fill in the slots with 
            let i = 0;
            let j = 0;
              questions.forEach((question) =>{
                    if (j< questions.length){
                        if (!question.classList.contains("gray")&& i == 0){
                            i = 1;
                            slot.value = question.id;
                            question.classList.add("gray");
                        }
                    }

                    j++;
                    if (j == questions.length){
                      //  slot.disabled = true;
                    }
                })

               // slot.value = "1";
            }
        })
        main_form.submit();

    })


    let clear_all_button = document.getElementById('clear_all_button');
    clear_all_button.addEventListener('click',function(e){
    
        slots.forEach((slot) =>{
            slot.value = "";
        })
        questions.forEach((question) =>{
            question.classList.remove("gray");
        })


    })

})	
</script>	

</body>
</html>



