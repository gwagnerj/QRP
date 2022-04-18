<?php

require_once 'pdo.php';
require_once "simple_html_dom.php";
// include 'phpqrcode/qrlib.php'; 
// require_once '../encryption_base.php';
$error = '';
$late_penalty_per_day = 0.2;
$days_for_repeat_if_wrong = 3;
$days_for_repeat_if_correct = 15;
$show_answer_flag = 1;

$changed_question_flag = 0;

$student_id = $question_id = "";
$shuffle_flag = 1;
if (isset($_GET['encode'])){
    $encode = $_GET['encode'];
    $codes = base64_decode($encode);
    $code_ar =explode('&',$codes);
    foreach ($code_ar as $code){
        $name = explode('=',$code)[0];
        $value = explode('=',$code)[1];
        $_GET[$name]=$value;
    } 

  //  var_dump($_GET);
}
$response = '';
$key_code = $email_flag = 0;

if (isset($_GET['student_id'])){$student_id = $_GET['student_id'];} elseif (isset($_POST['student_id'])){$student_id = $_POST['student_id'];} 
if (isset($_GET['questionset_id'])){$questionset_id = $_GET['questionset_id'];} elseif (isset($_POST['questionset_id'])){$questionset_id = $_POST['questionset_id'];} 
 if (isset($_GET['key_code'])){$key_code = $_GET['key_code'];} elseif (isset($_POST['key_code'])){$key_code = $_POST['key_code'];} 
// if (isset($_GET['key_code'])){$key_code = $_GET['key_code'];} elseif (isset($_POST['key_code'])){$key_code = 0;} 
if (isset($_GET['response'])){$response = $_GET['response'];} 
if (isset($_POST['email_flag'])){$email_flag = 0;} elseif (isset($_GET['email_flag'])){$email_flag = $_GET['email_flag'];}  

$_GET = array();


//  echo ' $student_id= '.$student_id.' $questionset_id= '.$questionset_id.' $key_code = '.$key_code.' $response= '.$response.' email_flag = '.$email_flag;  //! delete this after troubleshooting

if ($questionset_id ==0){

    		echo '<h1> questionset_id was lost - no questions today </h1>';
		// header('Location: QRPRepo.php');
		 die();
}


$total_correct = $total_score = $count = 0;
$question_id = 0;

// get the questiontime_id for the question that was sent

$sql = 'SELECT questiontime_id FROM QuestionSet WHERE questionset_id = :questionset_id';
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
':questionset_id' => $questionset_id

));
$questiontime_data = $stmt->fetch(PDO::FETCH_ASSOC);
$questiontime_id = $questiontime_data['questiontime_id'];


//? if email flag is 1 we are coming in from the email and the alias should be equal zero and we should not have reswcently worked the problem

if ($email_flag == 1){

$sql = 'SELECT * FROM QuestionSet WHERE  questionset_id = :questionset_id AND set_day_alias = 0';
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
':questionset_id' => $questionset_id,
));
    $questionset_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$questionset_data){
        $error = '<h2> There are no questions at this time - email question was not found in questionset</h2>';;
    }

}

//? see if they have worked the problems rescently

            $sql = 'SELECT *  FROM QuestionSet Join QuestionActivity
            ON QuestionActivity.questionset_id = QuestionSet.questionset_id 
            WHERE  student_id = :student_id AND QuestionSet.questionset_id = :questionset_id AND
            ( QuestionActivity.repeat_wrong_flag = 1 AND QuestionActivity.updated_at > (NOW() - INTERVAL :days_for_repeat_if_wrong  DAY)
            OR
            QuestionActivity.repeat_correct_flag = 1 AND QuestionActivity.updated_at > (NOW() - INTERVAL :days_for_repeat_if_correct  DAY))
            GROUP BY QuestionActivity.question_id ORDER BY set_date DESC, set_day_alias ASC 
            ';
           $stmt = $pdo->prepare($sql);
           $stmt->execute(array(
           ':questionset_id' => $questionset_id,
           ':student_id' => $student_id,
           ':days_for_repeat_if_wrong' => $days_for_repeat_if_wrong,
           ':days_for_repeat_if_correct' => $days_for_repeat_if_correct,
           ));
            $questionactivity_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if($questionactivity_data){  //? the have already answered this problem rescently

            $error = '<h2> Question has already been answered - please close browser window questionset_id = '.$questionset_id.' question_id = '.$question_id.' </h2>';
        }





    // $sql = 'SELECT questionactivity_id,question_id FROM QuestionActivity WHERE  questionset_id = :questionset_id AND student_id = :student_id AND ((repeat_correct_flag = 1 AND updated_at > NOW() - INTERVAL '.$days_for_repeat_if_correct.' DAY ) OR (repeat_wrong_flag = 1 AND updated_at > NOW() - INTERVAL '.$days_for_repeat_if_wrong.' DAY )  )';
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute(array(
    // ':questionset_id' => $questionset_id,
    // ':student_id' => $student_id,
    // ));
    // $questionactivity_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // if($questionactivity_data){  //? the have already answered this problem rescently

    //     $error = '<h2> Question has already been answered - please close browser window </h2>';
    // }

    $sql = 'SELECT * FROM QuestionSet WHERE  questionset_id = :questionset_id ';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
    ':questionset_id' => $questionset_id,
    ));
        $questionset_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if(!$questionset_data){
            $error = '<h2> There was no data for this problem questionset_id = '.$questionset_id.'</h2>';;
        }
    $question_id = $questionset_data['question_id'];




// see if the questionset that was sent is good or we need to look for another one

// $sql = 'SELECT questionactivity_id,question_id FROM QuestionActivity WHERE  questionset_id = :questionset_id AND student_id = :student_id AND ((repeat_correct_flag = 1 AND updated_at > NOW() - INTERVAL '.$days_for_repeat_if_correct.' DAY ) OR (repeat_wrong_flag = 1 AND updated_at > NOW() - INTERVAL '.$days_for_repeat_if_wrong.' DAY )  )';
// $stmt = $pdo->prepare($sql);
// $stmt->execute(array(
// ':questionset_id' => $questionset_id,
// ':student_id' => $student_id,
// ));
//     $questionactivity_data = $stmt->fetch(PDO::FETCH_ASSOC);


//     if(!$questionactivity_data){
     
//         $sql = 'SELECT question_id FROM QuestionSet WHERE  questionset_id = :questionset_id ';
//         $stmt = $pdo->prepare($sql);
//         $stmt->execute(array(
//         ':questionset_id' => $questionset_id,
//         ));
//             $questionid_data = $stmt->fetch(PDO::FETCH_ASSOC);
//             $question_id = $questionid_data['question_id'];
     
//         // $question_id = $questionactivity_data['question_id'];
//     } else {
//         $question_id = 0;
//     }

//     if ($question_id == 0  ){
//         //? need to look for another question

//        if($email_flag ==1){$changed_question_flag = 1;}
//         $response = '';
//         $error = '<h2> Question set has already been answered - please close browser window </h2>';

//         $sql = "SELECT question_id, set_day_alias  FROM QuestionSet
//             WHERE questiontime_id = :questiontime_id AND questionset_id != :questionset_id AND set_date < NOW()";
//             $stmt = $pdo->prepare($sql);
//             $stmt->execute(array(
//             ':questiontime_id' => $questiontime_id,
//             ':questionset_id' => $questionset_id
//             ));
//                 $questionset_alldata = $stmt->fetch(PDO::FETCH_ASSOC);

//                 if($questionset_alldata){
//                     $question_id = $questionset_alldata['question_id'];
//                     $set_day_alias = $questionset_alldata['set_day_alias'];
//                 } else {
//                     // we have no questions for you to

//                     $error = '<h2> There are no questions at this time </h2>';


//                 }

//     }

//? see how many question there are and if zero lock things down to prevent a refresh from adding more points_sur

$sql = "SELECT  set_day_alias  FROM QuestionSet
WHERE questionset_id = :questionset_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
':questionset_id' => $questionset_id
));
    $questionset_aliasdata = $stmt->fetch(PDO::FETCH_ASSOC);

    if($questionset_aliasdata){
        $set_day_alias = $questionset_aliasdata['set_day_alias'];
    }


$count_first_time = 0;
//?  check to see if there are any more problems that need worked in the question set_ but not ones that have already been answered
        $sql = "SELECT COUNT(*) as count_first_time FROM QuestionSet  
        WHERE questiontime_id = :questiontime_id AND questionset_id != :questionset_id AND set_date < NOW() AND set_day_alias >= :set_day_alias";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
        ':questiontime_id' => $questiontime_id,
        ':questionset_id' => $questionset_id,
        ':set_day_alias' => $set_day_alias,
        ));
            $questionset_alldata = $stmt->fetchALL(PDO::FETCH_ASSOC);

            if($questionset_alldata){
                $count_first_time = $questionset_alldata[0]['count_first_time'];



     $sql = 'SELECT COUNT(DISTINCT(question_id)) as count_repeat FROM QuestionActivity WHERE  questionset_id = :questionset_id AND student_id = :student_id AND ((repeat_correct_flag = 1 AND updated_at < NOW() - INTERVAL '.$days_for_repeat_if_correct.' DAY ) OR(repeat_wrong_flag = 1 AND updated_at < NOW() - INTERVAL '.$days_for_repeat_if_wrong .' DAY )  )';
             $stmt = $pdo->prepare($sql);
             $stmt->execute(array(
             ':questionset_id' => $questionset_id,
             ':student_id' => $student_id,
             ));
                 $questionactivity_data_count = $stmt->fetchALL(PDO::FETCH_ASSOC);
                 if($questionactivity_data_count){
                    $count_repeat = $questionactivity_data_count[0]['count_repeat'];
                 }
         }
         
         $total_count = $count_repeat+$count_first_time;

    //     echo 'total_count = ' . $total_count;




 //   echo ' question_id '.$question_id;
 if($question_id != 0){

    $sql = "SELECT * FROM Question WHERE question_id = :question_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
    ':question_id' => $question_id
    ));
    $question_data = $stmt->fetch(PDO::FETCH_ASSOC);

    $nm_author = $question_data['nm_author'];
    $nm_checker1 = $question_data['nm_checker1'];
    $nm_checker2 = $question_data['nm_checker2'];
    $nm_checker3 = $question_data['nm_checker3'];
    $nm_checker4 = $question_data['nm_checker4'];
    $nm_checker5 = $question_data['nm_checker5'];
    $explanation_filenm = $question_data['explanation_filenm'];


    //? look thru questiondata and see how many correct options there already
    $num_correct_responses = $num_responses = $key_total = 0;
    $select_one_flag = 1;
    $key = 'key_'.$response;
    foreach(range('a','j') as $v){
        $keys = 'key_'.$v;
        if($question_data[$keys]>0){ $num_correct_responses++;}
        $key_total += $question_data[$keys];
        if ($question_data[$keys]){
            $num_responses++;
        }
    }
  

    if( $num_correct_responses>1){$select_one_flag =0;}




   $htmlfilenm = $question_data['htmlfilenm'];
   $html = new simple_html_dom();
   $fullpath = 'uploads/'.$htmlfilenm;
   $html->load_file($fullpath); 

   $option_texts = $html->find ('.option_text');

$num_options = count($option_texts);
//echo ' num_options: '.$num_options;
if ($num_options != strlen($key_code)){  //? get another shuffled key code
    $shuffle_keys = array();
    $shuffle_keys = range(0,$num_options-1);
    shuffle($shuffle_keys);
    $key_code = "";
    for($j = 0; $j < $num_options; $j++){
       $key_code =$key_code.$shuffle_keys[$j];
    }
}
// echo ' key_code '.$key_code;

//    foreach($html->find('img') as $element){
      
    
 
//     $element2 = $element;
//     $element2 = '/uploads/'.$element2;
//        echo ( $element2);
//    }

   $html2 = $html;

   $anchors = $html2->find('a');
   foreach($anchors as $anchor){
        $anchor->href = '#';
   }
   $images = $html2->find('img');
    foreach ($images as $image){
       $src2 =  $image -> src;
 //      echo ' src2: '.$src2;
       $src2 = 'uploads/'.$src2;
       $image->src =$src2;
      //  $images->src = '/uploads/'$images->src;

    }

       $html2 = str_replace('##','',$html2);
       $html2 = str_replace('_blank','',$html2);
      
    //   echo $html2;

 }

//?   now get the question



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
// echo '<br>';
//     echo ' $student_id= '.$student_id.' $questionset_id= '.$questionset_id.' $key_code = '.$key_code.' $response= '.$response.' email_flag = '.$email_flag.' questiontime_id = '.$questiontime_id;
//     echo '<br>';
//     echo ' question_id = '.$question_id;

        //? see if the student has ever seen this problem before to see if they should rate it  Same code from question_check
        $sql = "SELECT * FROM QuestionActivity WHERE question_id = :question_id AND questionset_id = :questionset_id AND student_id = :student_id AND (repeat_correct_flag = 1 OR repeat_wrong_flag = 1) ORDER BY questionactivity_id DESC LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
        ':question_id' => $question_id,
        ':questionset_id' => $questionset_id,
        ':student_id' => $student_id,
        ));
            $questionactivity_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if($questionactivity_data){
                //? already answered this question activity
              $first_time_flag = 0;

            } else {
                $first_time_flag = 1;
            }


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
.disable{ 
    pointer-events:none;
}
.container{
    display: flex
    
}
.question{
    cursor: pointer;
}
.gray{ 
    opacity: 30%;
}

body {margin:2em;padding:0}

.slot{ 
    border: 2px dashed green
}
.wrong{
    background-color: red;
 }
.correct{
    background-color: green;
 }
 p{ 
     font-size: 1.2rem !important;
     line-height: 0.9 !important;

 }
 .quest_info{ 
     font-size: 0.8rem !important;
 }

 .rating {
  --dir: right;
  --fill: gold;
  --fillbg: rgba(100, 100, 100, 0.15);
  /* --heart: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 21.328l-1.453-1.313q-2.484-2.25-3.609-3.328t-2.508-2.672-1.898-2.883-0.516-2.648q0-2.297 1.57-3.891t3.914-1.594q2.719 0 4.5 2.109 1.781-2.109 4.5-2.109 2.344 0 3.914 1.594t1.57 3.891q0 1.828-1.219 3.797t-2.648 3.422-4.664 4.359z"/></svg>'); */
  --star: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 17.25l-6.188 3.75 1.641-7.031-5.438-4.734 7.172-0.609 2.813-6.609 2.813 6.609 7.172 0.609-5.438 4.734 1.641 7.031z"/></svg>');
  --stars: 5;
  --starsize: 2.5rem;
  --symbol: var(--star);
  --value: 0;
  --w: calc(var(--stars) * var(--starsize));
  --x: calc(100% * (var(--value) / var(--stars)));
  block-size: var(--starsize);
  inline-size: var(--w);
  position: relative;
  touch-action: manipulation;
  -webkit-appearance: none;
}
[dir="rtl"] .rating {
  --dir: left;
}
.rating::-moz-range-track {
  background: linear-gradient(to var(--dir), var(--fill) 0 var(--x), var(--fillbg) 0 var(--x));
  block-size: 100%;
  mask: repeat left center/var(--starsize) var(--symbol);
}
.rating::-webkit-slider-runnable-track {
  background: linear-gradient(to var(--dir), var(--fill) 0 var(--x), var(--fillbg) 0 var(--x));
  block-size: 100%;
  mask: repeat left center/var(--starsize) var(--symbol);
  -webkit-mask: repeat left center/var(--starsize) var(--symbol);
}
.rating::-moz-range-thumb {
  height: var(--starsize);
  opacity: 0;
  width: var(--starsize);
}
.rating::-webkit-slider-thumb {
  height: var(--starsize);
  opacity: 0;
  width: var(--starsize);
  -webkit-appearance: none;
}
.rating, .rating-label {
  display: block;
  font-family: ui-sans-serif, system-ui, sans-serif;
  font-size: 1.0rem;
  margin-left: 3rem;
 
}
.rating-label {
   margin-block-end: 1rem; 
  margin-top: 1.8rem;
}

/* NO JS */
/* .rating--nojs::-moz-range-track {
  background: var(--fillbg);
}
.rating--nojs::-moz-range-progress {
  background: var(--fill);
  block-size: 100%;
  mask: repeat left center/var(--starsize) var(--star);
}
.rating--nojs::-webkit-slider-runnable-track {
  background: var(--fillbg);
}
.rating--nojs::-webkit-slider-thumb {
  background-color: var(--fill);
  box-shadow: calc(0rem - var(--w)) 0 0 var(--w) var(--fill);
  opacity: 1;
  width: 1px;
}
[dir="rtl"] .rating--nojs::-webkit-slider-thumb {
  box-shadow: var(--w) 0 0 var(--w) var(--fill);
} */

</style>



</head>

<body>
<header>
<h1 class ="fs-3 mb-1">Quick Response Question</h1>
</header>

<div id = "question_info" class = "quest_info fs-6 mb-3 fw-light">
<?php
if (strlen($error)>2){
    echo $error;
    die();
}

echo 'Question Number: '.$question_id;
?>

<span id = "author_container" class = "hide">
        <span id = "nm_author" class = "text-dark mx-1 fw-normal">
        <?php
        if (isset($nm_author)&& strlen($nm_author)>1){
            echo 'Author: '.$nm_author;
        }
        ?>

        </span>
        <span id = "nm_checkers" class = " mx-1 fw-light">
        <?php
        if (isset($nm_checker1)&& strlen($nm_checker1)>1){ echo 'Editors: '.$nm_checker1;}
        if (isset($nm_checker2)&& strlen($nm_checker1)>2){ echo ', '.$nm_checker2;}
        if (isset($nm_checker3)&& strlen($nm_checker1)>3){ echo ', '.$nm_checker3;}
        if (isset($nm_checker4)&& strlen($nm_checker4)>1){ echo ', '.$nm_checker4;}
        if (isset($nm_checker5)&& strlen($nm_checker5)>1){ echo ', '.$nm_checker5;}
        ?>

    </span>
<span>


</div>
<div id ="results-container" class = "mt-1 ms-3 hide">
    <h2> 
    <div id = "results" class = "m-2 text-primary"></div>
    </h2>

</div>
<section id = "changed-question-message" class = "message">
<?php
if ( $changed_question_flag ==1){ echo '<p class = "text-primary">Original question had been attempted resently or not yet due </p>';}
?>
</section>

<section id = "question-container" class = "fs-3">

<?php
echo $html2;

?>
<div class = "star_rating hide" id = "star_rating">
<label class="rating-label id = "rating_clarity" mt-3">  Clarity of Question
            <input id = "clarity_rating" class="rating " max="5"  oninput="this.style.setProperty('--value', this.value)" step="1" type="range"  value="1">
            </label>
            <label class="rating-label">  Relavance of Question
            <input id = "relavance_rating" class="rating hide disable" max="5"  oninput="this.style.setProperty('--value', this.value)" step="0.5" type="range"  value="1">
            </label>
</div>

<button type = "button" id = "submit_button" class = "btn btn-primary mt-1 ms-5 hide">Submit</button>
</section>



    <div id = "move_on" class ="">
        <form id = "form" method = "POST" action = "question_show.php">

            <input type = "hidden" id = "questionset_id" name = "questionset_id" value = "<?php echo $questionset_id;?>"></input>
            <input type = "hidden" id = "question_id" name = "question_id" value = "<?php echo $question_id;?>"></input>
            <input type = "hidden" id = "student_id" name = "student_id" value = "<?php echo $student_id;?>"></input>
            <input type = "hidden" id = "select_one_flag" value = "<?php echo $select_one_flag;?>"></input>
            <input type = "hidden" id = "num_correct_responses" value = "<?php echo $num_correct_responses;?>"></input>
            <input type = "hidden" id = "key-code" name = "key_code" value = "<?php echo $key_code;?>"></input>
            <input type = "hidden" id = "response" value = "<?php echo $response;?>"></input>
            <input type = "hidden" id = "email-flag" name = "email-flag" value = "<?php echo $email_flag;?>"></input>
            <input type = "hidden" id = "show_answer_flag" name = "show_answer_flag" value = "<?php echo $show_answer_flag;?>"></input>
            <input type = "hidden" id = "total_count" name = "total_count" value = "<?php echo $total_count;?>"></input>
            <input type = "hidden" id = "first_time_flag" name = "first_time_flag" value = "<?php echo $first_time_flag;?>"></input>
            <input type = "hidden" id = "explanation_filenm" name = "explanation_filenm" value = "<?php echo $explanation_filenm;?>"></input>

            <button type = "submit" id = "next_question" class = "btn btn-warning ms-5 hide">Next Question</button>
            <h3 id = "no_more_questions" class = "text-success hide" > All Questions Completed.  <p> You may close the browser window </p> </h3>
          </form>
        <br>
          <div id = "explanation_container" class = "ms-2">

          </div>
  
        </div>

  

<script>

let images = document.getElementsByTagName('img'); 

// images.forEach((image)=>{
//     console.log ('image ',image);
// })

const star_rating = document.getElementById('star_rating');
const key_code = document.getElementById('key-code').value;
const response = document.getElementById('response').value;
console.log(' key_code ',key_code);
const email_flag = document.getElementById('email-flag').value;
const select_one_flag = document.getElementById('select_one_flag').value;
const num_correct_responses = document.getElementById('num_correct_responses').value;
console.log (' select_one_flag',select_one_flag);
const selects = document.querySelectorAll(".select");
const option_texts = document.querySelectorAll(".option_text");
console.log("option_texts",option_texts.length);
const student_id = document.getElementById('student_id').value;
const question_id = document.getElementById('question_id').value;
const questionset_id = document.getElementById('questionset_id').value;
const next_question = document.getElementById('next_question');
const results = document.getElementById('results');
const results_container = document.getElementById('results-container');
const changed_question_message = document.getElementById('changed-question-message');
const no_more_questions = document.getElementById('no_more_questions');
const total_count = document.getElementById('total_count');
const clarity_rating = document.getElementById('clarity_rating'); 
const relavance_rating = document.getElementById('relavance_rating'); 
const author_container = document.getElementById('author_container');
const first_time_flag = document.getElementById('first_time_flag').value;
const explanation_filenm = document.getElementById('explanation_filenm').value;
const explanation_container = document.getElementById('explanation_container');


 console.log ('explanation_filenm ', explanation_filenm);

clarity_rating.addEventListener('change', ()=>{
    // unhide the clarity rating
    if (first_time_flag ==1){
            relavance_rating.classList.remove('hide');
            relavance_rating.classList.remove('disable');
            star_rating.classList.remove('hide');
    }
})
    relavance_rating.addEventListener('change', ()=>{
    // unhide the submit button
    submit_button.classList.remove("hide");

    })

    // console.log ('clarity rating value ',clarity_rating.value)


//? shuffle the responses and keep track of how we shuffled them
// define a static method
Array.range = (start, end) => Array.from({length: (end - start)}, (v, k) => k + start);
let index_st = ''; 
console.log('key_code',key_code);

if(key_code ==0){
       
        let index_ar = Array.range(0,option_texts.length);  // creates an array that is [0,1,2..]
        // shuffle the index_ar array

            index_ar = shuffle(index_ar);
            for (let i = 0; i < index_ar.length; i++)
                {
                    index_st += index_ar[i];
                }
            }
            else {
                index_st = key_code;
                index_ar = index_st.split('');

            }
        console.log("index_st",index_st);
        // let option_text_key_ar = [];
        // let option_text_index_ar = [];
        let temp_ar = [];
        option_texts.forEach((option_text) => {
            temp_ar.push(option_text.innerText) 

        })
        console.log ("temp_ar",temp_ar);
        let i =0;

        option_texts.forEach((option_text) => {
            let item = index_ar[i];
            option_text.innerText = temp_ar[item];
            i++;
        })




// console.log (' num_correct_responses ', num_correct_responses);
//! the next line converts a letter to a number a = 0, b = 1... and the next line is back
//! const response_num = (letter_str) => letter_str.toLowerCase().charCodeAt(0) - 97 + 1
//! String.fromCharCode(97 + n)




function shuffle(array) {
  let currentIndex = array.length,  randomIndex;

  // While there remain elements to shuffle...
  while (currentIndex != 0) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex--;

    // And swap it with the current element.
    [array[currentIndex], array[randomIndex]] = [
      array[randomIndex], array[currentIndex]];
  }

  return array;
}


        function selectClick(event){
            console.log (' num_correct_responses ', num_correct_responses);


        //  console.log("draggablequestion ",draggablequestion);
            event.preventDefault();
            let selected = document.getElementById(event.target.id);
            if(selected.value =="unselected"){selected.value = "selected";}else{selected.value = "unselected";};
console.log ('select one flag',select_one_flag);

            
            
      //      if(selected.classList.contains("gray") && select_one_flag == 0 ){selected.classList.remove("gray"); selected.value = "selected"; submit_button.classList.remove("hide");}else{selected.classList.add("gray");  selected.value = "selected";};
            if(selected.classList.contains("gray") )
            // if(selected.classList.contains("gray") && select_one_flag == 1)
                {
                    if (select_one_flag == 1){
                    selects.forEach((select) =>{  //? put gray and unseleccted in all of them and then add back the one that is selected
                            select.classList.add("gray");
                     //       select.classList.add("unselected");
                            select.value = "unselected";


                        })

                    }
                        selected.classList.remove("gray");
                        selected.value = "selected";

                        if (first_time_flag == 1){star_rating.classList.remove("hide");} else {submit_button.classList.remove("hide");}
 //!                       submit_button.classList.remove("hide");
       //                 star_rating.classList.remove("hide");
              //          selected.classList.add("selected");

                        console.log (' selected', selected);

                }
                else 
                {
                    selected.classList.add("gray");
                };
            console.log (event.target.value)
        }


const submit_btn = document.getElementById('submit_button');
submit_btn.addEventListener('click',function(){
let num_selected=0;
let selected_ar = [];
console.log ('submit button click');
changed_question_message.classList.add('hide');
//? get the selected responses
    selects.forEach((select) =>{
        if (select.value=="selected"){
            selected_ar.push(select.id);
            num_selected++;
            console.log ('selected_id',select.id);
        } else {

        }
    })

    console.log ('selected_ar',selected_ar);
    console.log ('index_st',index_st);


//? put in the wrong and correct icons
                let m = 0;
        selects.forEach((select) =>{
            let v = String.fromCharCode(97 + m)  // for the icon id's

            let wrong_element = document.createElement("span");
            select.insertBefore(wrong_element,select.firstSibling);
            wrong_element.outerHTML = '<span id = "wrong_icon-'+v+'" class = "wrong-icon ms-1 hide"><i class="bi wrong bi-x-square"></i></span>';
            let correct_element = document.createElement("span");
            select.insertBefore(correct_element,select.firstSibling);
            correct_element.outerHTML = '<span id = "correct_icon-'+v+'" class = "correct_icon ms-1 hide"><i class="bi correct bi-check-circle"></i></span>';

            m++;
            })
        



    //? this is where we need to do AJAX and have it update the QuestionActivity table after it does the appropriate checks.  then it can hide or unhide the correct or wrong messages and unhide the correct
    //? get another question button if appropriate.  Get another question should call this file with either the existing id and have it figure it up top or do it with ajax and get the new number

// put everything in an array like object say infor and then sen it
console.log('selected_ar',selected_ar);
   let info=[];
   info[0] = index_st;  // how to decode the resonse(s)
   info[1] = question_id;
   info[2] = questionset_id;
   info[3] = student_id;
   info[4] = email_flag;
   info[5] = clarity_rating.value;
   info[6] = relavance_rating.value;
   let k = 7;
    selected_ar.forEach((selected)=>{
        info[k] = selected;
        console.log (info[k]);
        k++;
    })

//    info.push(selected_ar);
   console.log ("info",info);


            $.ajax({
                url: 'question_check.php',
                method: 'post',
                data: {info:info}
            }).done(function(return_data){
                    console.log (return_data)
                    let rd = JSON.parse(return_data);
                    console.log('rd_score',rd.score);
                    question_id.value = rd.question_id;
                    questionset_id.value = rd.questionset_id;

                    //? display the explaination if there is ones
                    if (explanation_filenm){
                        const full_path =  "uploads/"+explanation_filenm+".htm"
 //                       console.log("full_path",full_path);

                        explanation_container.innerHTML = '<object type="text/html" data="'+full_path+'" ></object>'
                    }



            //       if(questionset_id.value != 0){
                    // if(rd.count_first_time >= 0 || rd.count_repeat >= 0){

                      console.log ("  rd.questionset_id ", rd.questionset_id) ;

                        if(typeof   rd.questionset_id === 'undefined' ||  rd.questionset_id == 0){
                        // if(rd.count_first_time == 0 && rd.count_repeat == 0){
                            next_question.classList.add("hide");
                            no_more_questions.classList.remove("hide");
                        } else {
                            next_question.classList.remove("hide");
                        }
                        submit_button.classList.add("hide");
                        star_rating.classList.add("hide");
                        document.getElementById('email-flag').value = 0;
                        document.getElementById('question_id').value = rd.question_id;
                        document.getElementById('questionset_id').value = rd.questionset_id;
                        if(rd.percent_correct==100){results.innerHTML = '<span class = "text-success"> Correct </span>';} else if (rd.percent_correct==0){results.innerHTML = '<span class = "text-danger"> Not Correct </span>';} else {results.innerHTML = ' Partially Correct';}
                        results.innerHTML += '<p class = "text-secondary mt-2 fs-5"> Points= '+rd.score+' Total Points = '+rd.total_score+'</p>';

                        results_container.classList.remove('hide');
                        author_container.classList.remove('hide');

                        const correct_icons = document.querySelectorAll(".correct_icon");
                        let z = 0;

                        correct_icons.forEach((correct_icon) =>{
                            if(rd.selected_correct_alias[z]==1){
                                correct_icon.classList.remove('hide');
                            }
                            z++;  
                        })

                        const wrong_icons = document.querySelectorAll(".wrong-icon");
                            z = 0;

                        wrong_icons.forEach((wrong_icon) =>{
                            if(rd.selected_wrong_alias[z]==1){
                                wrong_icon.classList.remove('hide');
                            }
                            z++;  
                        })

                        selects.forEach((select) =>{
                            console.log('anchor ',select.href);
                            select.href = "javascript: void(0)";
                            console.log('anchor ',select.href);
                            select.classList.add("gray");
                            select.value = "unselected";
                            select.removeEventListener('click',selectClick);

                            
                    })



          //         }


            })


})


selects.forEach((select) =>{
            select.classList.add("gray");
            select.value= "unselected";
            select.addEventListener('click',selectClick)
    })



if (response != ''){  // we are coming in from the emailed question
    let selector_from_email = 'select-'+response;
    document.getElementById(selector_from_email).click();
}

</script>

</body>

</html>

