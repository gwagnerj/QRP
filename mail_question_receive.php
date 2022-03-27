<?php

require_once 'pdo.php';
require_once "simple_html_dom.php";
// include 'phpqrcode/qrlib.php'; 
// require_once '../encryption_base.php';
$late_penalty_per_day = 0.2;

$response = $student_id = $question_id = "";

if (isset($_GET['response'])){$response = $_GET['response'];}
if (isset($_GET['student_id'])){$student_id = $_GET['student_id'];}
if (isset($_GET['question_id'])){$question_id = $_GET['question_id'];}
if (isset($_GET['email_flag'])){$email_flag = $_GET['email_flag'];} else {$email_flag =0;}
if (isset($_GET['questionset_id'])){$questionset_id = $_GET['questionset_id']; $set_day_alias = 0;} else {$questionset_id =0; $set_day_alias = '';}
if (isset($_GET['key_code'])){$key_code = $_GET['key_code'];} else {$key_code ='0123';}


  $response = htmlentities($response);
 
  $student_id = htmlentities($student_id);
  $question_id = htmlentities($question_id);
  $email_flag = htmlentities($email_flag);
  $questionset_id = htmlentities($questionset_id);
    $key_code = htmlentities($key_code);
  // COnvert the response to a base response object using the 




// echo '<h1> you, new one with a student_id of ' . $student_id . ' responded to question id ' . $question_id.' with a response of ' . $response.'</h1>';

    $sql = "SELECT * FROM Question WHERE question_id = :question_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
    ':question_id' => $question_id
    ));
        $question_data = $stmt->fetch(PDO::FETCH_ASSOC);

// get the information from the questionset data
// echo ' quiestionset_id '.$questionset_id;

$sql = "SELECT * FROM QuestionSet
JOIN QuestionTime ON QuestionSet.questiontime_id = QuestionTime.questiontime_id
 WHERE questionset_id = :questionset_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
':questionset_id' => $questionset_id
));
    $questionset_data = $stmt->fetch(PDO::FETCH_ASSOC);
    $questiontime_id = $questionset_data['questiontime_id'];

    //! check the quesitonactivity and make sure they have not already done this and they are trying to repeat the problem again
    $sql = "SELECT questionactivity_id FROM QuestionActivity WHERE question_id = :question_id AND questionset_id = :questionset_id AND student_id = :student_id AND (repeat_correct_flag = 1 OR repeat_wrong_flag = 1)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
    ':question_id' => $question_id,
    ':questionset_id' => $questionset_id,
    ':student_id' => $student_id,
    ));
        $questionactivity_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if($questionactivity_data){
            //? already answered this question activity
            $_SESSION['error'] = ' This question has already been answered by this student ';
            // header('Location: question_show.php?questionset_id='.$questionset_id.'&student_id='.$student_id);
            // die();
        }



    // echo $questionset_data['set_date'];
    $start_time = $questionset_data['start_time'];

   $start_time = explode(":",$questionset_data['start_time'])[0].':'.explode(":",$questionset_data['start_time'])[1];

//    echo ' start_time '.$start_time;


   date_default_timezone_set('America/New_York');

    $now = date('Y-m-d');
    $set_date = explode(" ",$questionset_data['set_date'])[0].' '.$start_time;
  //  $set_date = $questionset_data['set_date'];

    $set_date_dt = new \DateTime($set_date);
    $now_dt = new DateTime();
    $interval = $now_dt->diff($set_date_dt);
    // $interval = $set_date_dt->diff($now_dt);
    $diffInDays   = $interval->d; 
    if ($now_dt < $set_date_dt){
        $diffInDays = 0;
       // echo 'now more than set date by '.$diffInHours;
    } 
    //  echo ' diffInDays= '.$diffInDays;


// echo " set_date: " . $set_date;
// echo " now: " . $now;


        $num_responses = 0;

    // need to change the response to the response to the base question (before shuffling)  with the key_code
    //convert the response ot a number 
    $m = 0;
    foreach(range('a','j') as $v){
        $letter_number[$v] = $m;
        $number_letter[$m] = $v;
        $m++;
    }
    $key_code_ar = array_map('intval', str_split($key_code));
    $number_response = $letter_number[$response];
    $response_alias = $number_response;
    $response_base_num = $key_code_ar[$number_response];
    $response = $number_letter[$response_base_num];
    // echo ' response '.$response;

        $key = 'key_'.$response;
        $key_total = 0;
        foreach(range('a','j') as $v){
        $keys = 'key_'.$v;
        $key_total += $question_data[$keys];
        if ($question_data[$keys]){
            $num_responses++;
        }
    }

// var_dump($question_data);

    $pblm_score = $question_data[$key];  //! really only works with one correct answer question - OK for emailed questions


    $percent_total = 0;
    if ($key_total != 0){$percent_total = $pblm_score / $key_total * 100;}
    $score = 10 - $late_penalty_per_day*$diffInDays;  // no matter if they get the first on write or wrong they get full credit as long as they try but will be repeated more often where they can possibly get it wrong
    if($score<0){$score = 0;}

    if ($percent_total > 90){
        $correct_flag = 1;
        $repeat_correct_flag = 1;
        $repeat_wrong_flag = 0;
     } else {
        $correct_flag = 0;
        $repeat_correct_flag = 0;
        $repeat_wrong_flag = 1;
     }

//     //? put the values in the questionactivity table_format
    $sql = "INSERT INTO QuestionActivity (question_id,questionset_id,student_id,response_alias,response_base,repeat_correct_flag,repeat_wrong_flag,correct_flag,score) 
    VALUES(:question_id,:questionset_id,:student_id,:response_alias,:response_base,:repeat_correct_flag,:repeat_wrong_flag,:correct_flag,:score)";
              $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                ':question_id'=>  $question_id,
                ':questionset_id' =>  $questionset_id,
                 ':student_id' => $student_id,
                 ':response_alias' => $response_alias,
                 ':response_base' => $response_base_num,
                 ':repeat_correct_flag' => $repeat_correct_flag,
                 ':repeat_wrong_flag' => $repeat_wrong_flag,
                 ':correct_flag' => $correct_flag,
                 ':score' => $score,
                    )
        );



    //? get the day today

// date_default_timezone_set('America/New_York');
// $now = date('Y-m-d');
$count = 0;
//?  check to see if there are any more problems that need worked in the question set_
        $sql = "SELECT COUNT(*) as count FROM QuestionSet
        WHERE questiontime_id = :questiontime_id AND questionset_id != :questionset_id AND set_date < NOW()";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
        ':questiontime_id' => $questiontime_id,
        ':questionset_id' => $questionset_id
        ));
            $questionset_alldata = $stmt->fetchALL(PDO::FETCH_ASSOC);

            if($questionset_alldata){
                $count = $questionset_alldata[0]['count'];
            }
//? get the total score for this student on these questions and
            $sql = "SELECT  score,correct_flag  FROM QuestionActivity
            JOIN QuestionSet ON QuestionActivity.questionset_id = QuestionSet.questionset_id
            WHERE student_id = :student_id AND questiontime_id = :questiontime_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
            ':questiontime_id' => $questiontime_id,
            ':student_id' => $student_id
            ));
                $questionset_scoredata = $stmt->fetchALL(PDO::FETCH_ASSOC);

            $total_score =0;
            $total_correct = 0;
            foreach($questionset_scoredata as $qs_score_datum){
                $total_score +=  $qs_score_datum['score'];
                $total_correct += $qs_score_datum['correct_flag'];
            }

            // echo ' total score: '.$total_score;
            // echo ' total correct: '.$total_correct;



?>
 
 <!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QR Question</title>
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
<h1>Quick Response Question</h1>
</header>
<div id ="results-container" class = "mt-4 ms-3">
    <?php
if($diffInDays>0){
    echo '<h3>'.$diffInDays.' days late with a point reduction of '.$late_penalty_per_day*$diffInDays.'</h3>';
}
    
    if ($percent_total >99 )
          {echo '<h2 class = ""> Correct! </h2> <h3>You scored '.$score.' points out of 10 points on this question <h3>';
          } else 
          {echo '<h2> Not Correct. You scored '.$score.' points out of 10 points for responding to this question. <br><br>  This question may be repeated fairly soon. <h2>';}

    ?>
</div>

<div id = "move_on">
<form id = "form" method = "POST" action = "question_show.php">

    <input type = "hidden" name = "questionset_id" value = "<?php echo $questionset_id;?>"></input>
    <input type = "hidden" name = "student_id" value = "<?php echo $student_id;?>"></input>

<br>
        <h2> 
        <?php echo' For this class you have answered '.$total_correct.' QR Questions Correctly.<br><br>  Your total score is now '.$total_score.' Points <br><br>' ;  ?>

            <?php if ($count == 1){echo' You have '.$count.' more question due <br><br> <button type = "submit" id = "submit_btn" class = "btn btn-primary bs-4">See More Questions</button>'; }
             elseif ($count > 1){echo' You have '.$count.' more questions due <br><br> <button type = "submit" id = "submit_btn" class = "btn btn-primary bs-4">See More Questions</button>'; }
             else {echo 'You have no more questions due now  You may close this window.';}



        ?>
        </h2>
 </form>
 </div>
</body>

</html>

