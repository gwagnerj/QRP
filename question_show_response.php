<?php
require_once "pdo.php";
include 'phpqrcode/qrlib.php'; 
session_start();

    $iid = '1';
   
    if(isset($_POST["iid"])){
        $iid = $_POST["iid"];
    }elseif(isset($_GET["iid"])){
        $iid = $_GET["iid"];
    }
    
    $course = 'Testing Problems';
    if(isset($_POST["course"])){
        $course = $_POST["course"];
    }elseif(isset($_GET["course"])){
        $course = $_GET["course"];
    }

    $currentclass = 'Testing Problems';
    if(isset($_POST["currentcourse"])){
        $currentclass = $_POST["currentcourse"];
    }elseif(isset($_GET["currentcourse"])){
        $currentclass = $_GET["currentcourse"];
    }
    // echo ' $course '.$course;
    // var_dump($course);

    // $url_str = 'question_show_response.php?course='.$course;
    $url_str = 'question_show_response.php?course=';

    $url_str = 'question_show_response.php?course='.$course.'&amp;currentcourse='.$currentclass.'&amp;iid='.$iid;  // this is tied into next and prev to load another questions results
    // echo ' $url_str2 '.$url_str;

    $question_id = '1';
    $question_ids = array();
    for ($i = 0; $i <10 ; $i++){
        $selector_str = 'question_id_'.$i;
        if(isset($_POST[$selector_str])){
            $question_ids[$i]=$_POST[$selector_str];
            $url_str=$url_str.'&amp;question_id_'.$i.'='.$_POST[$selector_str];
        }elseif(isset($_GET[$selector_str])){
            $question_ids[$i]=$_GET[$selector_str];
            $url_str=$url_str.'&amp;question_id_'.$i.'='.$_GET[$selector_str];
        }
    }
    // echo ' $url_str '.$url_str;
    
    if(isset($_POST["current_question_id"])){
        $question_id = $_POST["current_question_id"];
    }elseif(isset($_GET["current_question_id"])){
        $question_id = $_GET["current_question_id"];
    }

    // figure out what is the next question number and the previous question number

    $next_question = $prev_question = $question_id;  // initialize
    $key = array_search($question_id,$question_ids);
    // echo "key ".$key;
    if ($key == 0){
        $prev_key = sizeof($question_ids)-1;
    } else {
        $prev_key = $key-1;
    }
    if( $question_ids[$prev_key]){
        $prev_question = $question_ids[$prev_key];
    }
    if ($key == sizeof($question_ids)-1){
        $next_key = 0;
    } else {
        $next_key = $key+1;
    }
    if( $question_ids[$next_key]){
        $next_question = $question_ids[$next_key];
    }

    // echo ' next question: ' . $next_question;
    // echo ' prev question: ' . $prev_question;




    $sql = "SELECT currentclass_id FROM CurrentClass WHERE  `name` = :course";

    $stmt = $pdo->prepare($sql);	
    $stmt->execute(array(
        ':course'	=> $currentclass,
       
    ));
    $currentclass_id_ar = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentclass_id = $currentclass_id_ar['currentclass_id'];

    // $sql = "SELECT * FROM Question WHERE  `question_id`=:question_id ";
    $sql = "SELECT question_id, htmlfilenm, email,  explanation_filenm, title,
        key_a, key_b,key_c,key_d,key_e,key_f,key_g,key_h,key_i,key_j,
        nm_author, nm_checker1, nm_checker2, nm_checker3, nm_checker4, nm_checker5,
        Student.student_id as student_id, Student.student_pic_fn as student_pic_fn, Student.student_pic_show as student_pic_show
     FROM Question 
     LEFT JOIN Student ON Question.email = Student.school_email
     WHERE  `question_id`=:question_id ";

    $stmt = $pdo->prepare($sql);	
    $stmt->execute(array(
        ':question_id'	=> $question_id
    ));
    $question = $stmt->fetch(PDO::FETCH_ASSOC);

    $nm_author = $question['nm_author'];
    $nm_checker1 = $question['nm_checker1'];
    $nm_checker2 = $question['nm_checker2'];
    $nm_checker3 = $question['nm_checker3'];
    $nm_checker4 = $question['nm_checker4'];
    $nm_checker5 = $question['nm_checker5'];
    $student_id =$question['student_id'];
    $student_pic_show=$question['student_pic_show'];

    if ($nm_checker1 == 0){$nm_checker1 = null;}
    if ($nm_checker2 == 0){$nm_checker2 = null;}
    if ($nm_checker3 == 0){$nm_checker3 = null;}
    if ($nm_checker4 == 0){$nm_checker4 = null;}
    if ($nm_checker5 == 0){$nm_checker5 = null;}

   //! put this next bit in a function

    $student_pic_fn = $question['student_pic_fn'];
    if (!$student_pic_fn){
       $student_pic_fn = strtolower($nm_author);
       $student_pic_fn = str_replace(' ','_',$student_pic_fn);
       $student_pic_fp = 'student_pics/'.$student_pic_fn.'.jpg';
    }
    if (!file_exists($student_pic_fp)){$student_pic_fp = 'student_pics/'.str_replace(' ','_',strtolower($student_pic_fn)).'.png';} 
    if(!file_exists($student_pic_fp)){$student_pic_fp = '';} 

       $checker1_pic_fn = strtolower($nm_checker1);
       $checker1_pic_fn = str_replace(' ','_',$checker1_pic_fn);
       $checker1_pic_fp = 'student_pics/'.$checker1_pic_fn.'.jpg';
      
       //? these single lines do the same as the above three lines
       $checker2_pic_fp = 'student_pics/'.str_replace(' ','_',strtolower($nm_checker2)).'.jpg';
       $checker3_pic_fp = 'student_pics/'.str_replace(' ','_',strtolower($nm_checker3)).'.jpg';
       $checker4_pic_fp = 'student_pics/'.str_replace(' ','_',strtolower($nm_checker4)).'.jpg';
       $checker5_pic_fp = 'student_pics/'.str_replace(' ','_',strtolower($nm_checker5)).'.jpg';

       if(!file_exists($checker1_pic_fp)){$checker1_pic_fp = 'student_pics/'.str_replace(' ','_',strtolower($nm_checker1)).'.png';} 
       if(!file_exists($checker2_pic_fp)){$checker2_pic_fp = 'student_pics/'.str_replace(' ','_',strtolower($nm_checker2)).'.png';}
       if(!file_exists($checker3_pic_fp)){$checker3_pic_fp = 'student_pics/'.str_replace(' ','_',strtolower($nm_checker3)).'.png';}
       if(!file_exists($checker4_pic_fp)){$checker4_pic_fp = 'student_pics/'.str_replace(' ','_',strtolower($nm_checker4)).'.png';}
       if(!file_exists($checker5_pic_fp)){$checker5_pic_fp = 'student_pics/'.str_replace(' ','_',strtolower($nm_checker5)).'.png';}

       if(!file_exists($checker1_pic_fp)){$checker1_pic_fp = '';} 
       if(!file_exists($checker2_pic_fp)){$checker2_pic_fp = '';} 
       if(!file_exists($checker3_pic_fp)){$checker3_pic_fp = '';} 
       if(!file_exists($checker4_pic_fp)){$checker4_pic_fp = '';} 
       if(!file_exists($checker5_pic_fp)){$checker5_pic_fp = '';} 



    $html_fn = $question['htmlfilenm'];
    if(!strpos($html_fn,'.htm')){
        $html_fn = $html_fn.'.htm';
    }
   $html_fp = 'uploads/'.$html_fn;  // the file path to the question

   $explanation_filenm = $question['explanation_filenm'];
   if(!strpos($explanation_filenm,'.htm')){
        $explanation_filenm = $explanation_filenm.'.htm';
    }

    $explanation_fp = 'uploads/'.$explanation_filenm;  // the file path to the question

    $answer_key_ar = array();
    $answer_key_st = '';
    $letters = range('a','j');
    foreach ($letters as $letter){
        $selector = 'key_'.$letter;
        if ($question[$selector] != 0){
            array_push($answer_key_ar,$letter);
            $answer_key_st = $answer_key_st.' '.$letter;
        }
    }
    // var_dump($answer_key_ar);
    // echo 'answer_key_st '.$answer_key_st; 



// echo 'currentclass_id'.$currentclass_id;


// $qrquestion_text =  'https://www.qrproblems.org/QRP/QR_BC_Checker2.php?question_id='.$question_id.'&currentcourse='.$currentclass; 
$qrquestion_text =  'https://www.qrproblems.org/QRP/QR_to_quickQuestion.php?question_id='.$question_id.'&currentclass_id='.$currentclass_id; 

$file_qrcode = 'uploads/temp_bc.png'; 
        // $ecc stores error correction capability('L') 
        $ecc = 'XL'; 
        $pixel_size = 4; 
        $frame_size = 3; 
          
        // Generates QR Code and Stores it in directory given 
          QRcode::png($qrquestion_text, $file_qrcode, $ecc, $pixel_size, $frame_size); 
          $qrcode = "<span id = 'qrcode_id_bc'><img src='".$file_qrcode."'><h2> QR Quick Question </h2></span>"; 


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="McKetta.png" />  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Response Chart</title>

    <style>
#iframe{

width: 90vw;
height: 500px !important;
/*transform: scale(0.9);
 position: absolute;
left: -300px !important;
top: -100px !important;
padding: 0 !important;
margin-left: 0 !important; */

}
#iframe_expl{
    width: 80vw;
    height: auto !important;
}
#question_stem{ 
   display:flex;
}

.hide{ 

display:none !important;

}
.col{position:relative;}
    </style>




</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
            <button id = "toggle_question"type="button" class="btn btn-outline-secondary mx-3 "> <i class="bi bi-question-circle"></i> Toggle Question</button>
             <button id = "show_chart" type="button" class="btn btn-outline-primary me-4"><i class="bi bi-bar-chart-line"></i> Show Results</button>
             <button type="button" id = "navPrev" class="btn btn-light mx-2"  data-perv_id ="" aria-label="Prev"><i class="bi bi-arrow-left-circle px-1"></i>Previous</button>
            <button type="button" id = "navNext" class="btn btn-light"  aria-label="Next"><i class="bi bi-arrow-right-circle px-1"></i>Next</button>
            <!-- <div class="control-group mt-5" id="response_control"> -->

                <button id = "enable_discussion" class = "discussion-start btn btn-outline-secondary ms-5"><i class="bi bi-unlock px-1"></i>Enable Discussion</button>
                <!-- <button id = "show_author" class = "show_author btn btn-outline-secondary ms-5"><i class="bi bi-person"></i>Show Author</button> -->
                <button id = "discussion_start" class = "discussion-start btn btn-outline-primary ms-5 hide"><i class="bi bi-people px-1"></i>Discussion Start</button>
                <button id = "allow_2nd_submit" class = "allow-2nd-submit btn btn-outline-primary ms-5 hide"><i class="bi bi-2-circle"></i> Allow 2nd Submit</button>
                <span id = "discussion_stop_watch" class = "ms-4 text-primary hide"><span id="mins">00</span>:<span id="seconds">00</span></span>
                
              <!-- </div> -->

            <button id = "toggle_QRCode" type="button" class="btn btn-outline-success position-absolute top-0 end-0 m-3"> <i class="bi bi-qr-code"></i> Toggle QRCode</button>
           
        </nav>

        <input type="hidden" id = "next_question" value = "<?php echo($next_question);?>">
        <input type="hidden" id = "prev_question" value = "<?php echo($prev_question);?>">
        <input type="hidden" id = "url_str" value = "<?php echo($url_str);?>">
        <input type="hidden" id = "answer_key_st" value = "<?php echo($answer_key_st);?>">
        <!-- <input type="hidden" id = "iid" value = "<?php echo($iid);?>">
        <input type="hidden" id = "course" value = "<?php echo($course);?>">
        <input type="hidden" id = "currentclass" value = "<?php echo($currentclass);?>"> -->
        <div id="question_info">
            <div class="row m-2  ">
                <div class="col">
                    <h4 class = "  ms-5">qrquestion.org </h4>
                </div>
                <div class="col">
                    <h4 class = "text-primary  ms-5">Question Number: <span class="text-primary fw-bold border rounded"> <?php echo $question_id ?></span></h4>
                </div>
            <div class="col">
                    <h4 class = "text-success  ms-5">Class Number: <span class="text-success fw-bold border rounded"><?php echo $currentclass_id ?></span></h4>
            </div>
        </div>
        </div>

    <div id = "question_title" class="question_title h1 mb-2"> <?php echo $question['title']?> Question Response:</div>

            <div class="response_container">

                <div class = "position-relative">
                    <div class = "qrcode my-5 ms-5 hide position-relative top-0 start-50 translate-middle-x p-5" id = "qrcode">
                        <?php echo $qrcode; ?>
                    </div>
                </div>

                </div>
                

                <div id = "question_stem" class="question_stem">
                    <iframe id="iframe" class = "iframe mb-4" src = "<?php echo $html_fp; ?>">
                    </iframe>
                </div>


                <div id = "question_explanation" class="question_stem hide">
                    <iframe id="iframe_expl" class = "iframe mb-4" src = "<?php echo $explanation_fp; ?>">

                    </iframe>

                </div>
                <div id = "question_answers_display" class=" hide fs-3 text-danger">
                    Answer: <?php echo $answer_key_st; ?>
                </div>

                    <div class="question_results_graph hide" id="question_results_graph">
                        
                        <div class="results-control" id="results_control">
                            <div class="row">
                                <div class="col">
                                    <button id = "toggle_chart" type="button" class="btn btn-outline-primary btn-lg fs-2 fw-bold mt-4">Initial Response</button>
                                 </div>
                                <div class="col">
                                    <button id = "show_answer" type="button" class="btn btn-outline-secondary btn-sm fs-4 fw-bold mt-4">Answers</button>
                                    <button id = "show_explanation" type="button" class="btn btn-outline-secondary btn-sm fs-4 fw-bold mt-4">Explanation</button>
                                 </div>
                                 <div class="col">
                                        <button id = "refresh_chart" type="button" title = "Refresh Chart" class="btn btn-outline-secondary position-absolute top-0 end-0 m-3"><i class="bi bi-arrow-clockwise"style = "font-size:1.5rem;"></i></button>
                                 </div>
                            </div>
                        </div>


                

                        <canvas id = "quick1_chart"></canvas>
                    </div>
                    <div id = "author_container" class="question_author_container fs-5">
                    <span id = "author_title" class = "fs-5 hide ">Author:</span>

                        <span id = "author" class="question_author btn btn-sm"><?php echo $nm_author?></span>
                        <img id = "author_pic" class = "pictures hide" src =" <?php echo $student_pic_fp; ?>">
                      <span class="editor_container">
                        <span id = "editor_title" class = "fs-5 hide ">Editor:</span>

                        <span id = "nm_checker1" class = "question_editor btn btn-sm" > <?php echo $nm_checker1; ?> </span>
                        <img id = "checker1_pic" class = "pictures hide" src = "<?php echo $checker1_pic_fp; ?>"></img>
                        <span id = "nm_checker2" class = "question_editor btn btn-sm" > <?php echo $nm_checker2; ?> </span>
                        <img id = "checker2_pic" class = "pictures hide" src = "<?php echo $checker2_pic_fp; ?>"></img>
                        <span id = "nm_checker3" class = "question_editor btn btn-sm" > <?php echo $nm_checker3; ?> </span>
                        <img id = "checker3_pic" class = "pictures hide" src = "<?php echo $checker3_pic_fp; ?>"></img>
                        <span id = "nm_checker4" class = "question_editor btn btn-sm" > <?php echo $nm_checker4; ?> </span>
                        <img id = "checker4_pic" class = "pictures hide" src = "<?php echo $checker4_pic_fp; ?>"></img>
                        <span id = "nm_checker5" class = "question_editor btn btn-sm" > <?php echo $nm_checker5; ?> </span>
                        <img id = "checker5_pic" class = "pictures hide" src = "<?php echo $checker5_pic_fp; ?>"></img>
                      </span>      
                           
                      
                    </div>

                
            </div>

    </div>


    <script type="text/javascript" charset="utf-8">

        // use the window object to get the url parameters from

        const navPrev = document.getElementById("navPrev")
        const navNext = document.getElementById("navNext")
        const next_question = document.getElementById("next_question").value
        const prev_question = document.getElementById("prev_question").value
        const url_str = document.getElementById("url_str").value
        // console.log ("prev_question", prev_question)
        // console.log ("url_str", url_str)

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const question_id = urlParams.get('current_question_id')
        const iid = urlParams.get('iid')
        const course = urlParams.get('course')  //? how the question is classified
        const currentcourse = urlParams.get('currentcourse')  //? the question the class was sent to
       // console.log('from q show response question_id',question_id);

       const toggle_chart = document.getElementById('toggle_chart')
       const question_title = document.getElementById('question_title')
        const iframe = document.getElementById('iframe')
        const toggle_question = document.getElementById('toggle_question')
        const show_chart = document.getElementById('show_chart')
        const question_results_graph = document.getElementById('question_results_graph');
        const toggle_QRCode = document.getElementById('toggle_QRCode');
        const qrcode = document.getElementById('qrcode');
        const refresh_chart = document.getElementById('refresh_chart');
        let num_parts =0;
        let data1_flag = true;
        let chart_data ='';
        let myChart ='';

            const enable_discussion = document.getElementById('enable_discussion');
         const discussion_start = document.getElementById("discussion_start");
        const allow_2nd_submit = document.getElementById('allow_2nd_submit');
        const discussion_stop_watch = document.getElementById('discussion_stop_watch');
        let seconds = 00;
        let mins = 00;
        var Interval;
        const appendMins = document.getElementById("mins")
        const appendSeconds = document.getElementById("seconds")

        const show_answer = document.getElementById("show_answer");
        const show_explanation = document.getElementById("show_explanation");
        const question_explanation = document.getElementById("question_explanation");
        const answer_key_st = document.getElementById("answer_key_st").value;
        const author = document.getElementById("author");
        const author_pic = document.getElementById("author_pic");

        if (author.innerText.length > 1) {document.getElementById("author_title").classList.remove("hide")}

        const nm_checker1 = document.getElementById("nm_checker1");
        console.log ('nm_checker1',nm_checker1.innerText);
            if (nm_checker1.innerText.length > 1) {document.getElementById("editor_title").classList.remove("hide")}

        const checker1_pic = document.getElementById("checker1_pic");
        const nm_checker2 = document.getElementById("nm_checker2");
        const checker2_pic = document.getElementById("checker2_pic");
        const nm_checker3 = document.getElementById("nm_checker3");
        const checker3_pic = document.getElementById("checker3_pic");
        const nm_checker4 = document.getElementById("nm_checker4");
        const checker4_pic = document.getElementById("checker4_pic");
        const nm_checker5 = document.getElementById("nm_checker5");
        const checker5_pic = document.getElementById("checker5_pic");

        author.addEventListener('click',()=>{
            author_pic.classList.toggle("hide")
        })

        nm_checker1.addEventListener('click',()=>{
            checker1_pic.classList.toggle("hide")
        })
        nm_checker2.addEventListener('click',()=>{
            checker2_pic.classList.toggle("hide")
        })
        nm_checker3.addEventListener('click',()=>{
            checker3_pic.classList.toggle("hide")
        })
        nm_checker4.addEventListener('click',()=>{
            checker4_pic.classList.toggle("hide")
        })
        nm_checker5.addEventListener('click',()=>{
            checker5_pic.classList.toggle("hide")
        })

        show_answer.addEventListener('click', ()=>{
            console.log ("answer clicked",answer_key_st);
            document.getElementById("question_answers_display").classList.toggle("hide")
        })

        show_explanation.addEventListener('click', ()=>{
            question_explanation.classList.toggle("hide")
        })



        function startTimer () {
            seconds++; 
            if(seconds <= 9){
                appendSeconds.innerHTML = "0" + seconds;
            }
            
            if (seconds > 9){
                appendSeconds.innerHTML = seconds;
            } 
            if (seconds > 59){
                mins++;
                appendMins.innerHTML = mins
                seconds = 0;
                appendSeconds.innerHTML = "0" + seconds;
            } 
        }
  
        enable_discussion.addEventListener('click',()=>{
            enable_discussion.classList.add('hide')
            discussion_start.classList.remove('hide')
        })
    

        discussion_start.addEventListener('click', ()=>{

            //? start the timers
            discussion_stop_watch.classList.remove("hide")
                clearInterval(Interval);
                Interval = setInterval(startTimer, 1000);
            // //? make an entry for each student in the table where the try_number is 1 and the discussion flag is still 1 in QuickQuestion table

            fetch('get_insert_quickquestion_trynum.php',{method: 'POST',
                headers: {
                    
                    "Content-Type": "application/json",
                    "Accept":"application/json, text/plain, */*"
                },
                body: JSON.stringify({iid:iid, course:course, question_id:question_id}),
            })
            .then((res) => res.json())
            .then((data) =>{
                console.log('data giqtrynum',data);
                discussion_start.classList.add("hide")
                allow_2nd_submit.classList.remove("hide")
            })
        })

        // just update the last entry and make discuss stage to 2 (allow submit)
        allow_2nd_submit.addEventListener('click',()=>{
                discussion_stop_watch.classList.add("hide")
                clearInterval(Interval);
                allow_2nd_submit.classList.add("hide")

                fetch('get_update_quickquestion_discuss_stage.php',{method: 'POST',
                    headers: {
                        
                        "Content-Type": "application/json",
                        "Accept":"application/json, text/plain, */*"
                    },
                    body: JSON.stringify({iid:iid, course:course, question_id:question_id}),
                })
                .then((res) => res.json())
                .then((data) =>{
                    console.log('data',data);
                })
            
         })



        toggle_QRCode.addEventListener('click', ()=>{
            qrcode.classList.toggle("hide");
        })

        show_chart.addEventListener('click', ()=>{
            question_results_graph.classList.remove('hide');
            show_chart.classList.add('hide');
            toggle_question.click();
          //  fetch_data ();
        })

        toggle_question.addEventListener('click', ()=>{
            iframe.classList.toggle("hide")
        })

        refresh_chart.addEventListener('click', ()=>{
            update_chart ();
        })



        navPrev.addEventListener('click', ()=>{
            const urlstring = url_str + '&current_question_id='+prev_question
            window.location.replace(urlstring)

        })

        navNext.addEventListener('click', ()=>{
            const urlstring2 = url_str + '&current_question_id='+next_question
            window.location.replace(urlstring2)
        })


  const fetch_data = () =>{
        
        fetch('getQuestionForDisplayResults.php',{method: 'POST',
          headers: {
                
                "Content-Type": "application/json",
                "Accept":"application/json, text/plain, */*"
            },
            body: JSON.stringify({iid:iid, course:course, question_id:question_id}),
        })
        .then((res1) => res1.json())
        .then((question)=>{
            console.log (question);


            
            let i_frame=iframe.contentWindow.document;  //? this should be the dom for the iframe
            console.log("i_frame",i_frame);
            console.log ('wtf')
            let child_nodes = i_frame.childNodes;
            console.log('child_nodes',child_nodes);
            let q_stem_text = child_nodes[0].querySelector('#stem_text_1').innerText;
            let q_stem_html = child_nodes[0].querySelector('#stem_text_1').innerHTML;

            console.log('i_frame.childNodes',child_nodes)
            console.log('child_nodes[0]',child_nodes[0])
            child_nodes[0].innerHTML = child_nodes[0].innerHTML.replaceAll('##','');

            let options=[];
            let options_str = [];
             num_parts = 0;
            for (let i = 0; i <=9;i++) {    //? looping thru 0 a thru j
            let n = String.fromCharCode(97 + i);
            let selector = `#question_option_${n}`
                if(child_nodes[0].querySelector(selector)){
                num_parts ++;
                options[i]= child_nodes[0].querySelector(selector).innerText;
                options[i] = options[i].replaceAll('##','');
                options_str[i] = options[i].replace(/<[^>]+>/g, '');
                }
            
            }
                console.log("options",options);
                console.log("options_str",options_str);
                console.log("num_parts",num_parts);
    
// get the chart responses just like in QuestionRepo.php
        
            console.log('question_id', question_id);
            console.log('course',course);
            console.log('currentcourse',currentcourse);
            console.log('iid',iid);

    fetch('get_quick1_question_response.php',{method: 'POST',
          headers: {
                
                "Content-Type": "application/json",
                "Accept":"application/json, text/plain, */*"
            },
            body: JSON.stringify({iid:iid, course:currentcourse, question_id:question_id}),
        })
        .then((res1) => res1.json())
        .then((responses)=>{
          console.log('response from get_quick1_question_response',responses);
         let data_for_1 = [];
         let data_for_2 = [];
         let count =0;

          for(let i=0;i<num_parts; i++){
            count =0;
            responses.forEach((response)=>{
              for (let j = 0; j < response.response_st.length; j++) {
                // console.log('response.response_st.charAt(j): ',response.response_st.charAt(j))
                if(response.response_st.charAt(j) == String.fromCharCode(i+97) && response.try_number==0){
                 count += parseInt(response.count);
                }
              }
            })
            data_for_1.push(count);
          }
          for(let i=0;i<num_parts; i++){
            count =0;
            responses.forEach((response)=>{
              
              for (let j = 0; j < response.response_st.length; j++) {
               //   console.log(response.charAt(i));
              
              if(response.response_st.charAt(j) == String.fromCharCode(i+97) && response.try_number==1){
                count += parseInt(response.count);
                }
              }
            })
            data_for_2.push(count);
          }
          console.log('data_for_1',data_for_1)
          console.log('data_for_2',data_for_2)
      

 
 
          data1_flag = true;

            //? draw the chart 

            const labels = options_str;
                
             chart_data = {
                            labels: labels,
                            datasets: [{
                            label: 'Initial Response',
                            backgroundColor: 'rgb(0, 73, 153)',
                            borderColor: 'rgb(0, 73, 153)',
                            data: data_for_1,
                            }]
                        };

                const config = {
                type: 'bar',
                
                data: chart_data,
                options: {
                    
                        indexAxis: 'y',
                        scales: {
                        x: {
                            ticks: {
                                stepSize:1,
                                    font: {
                                        size: 20
                                    }
                            },
                            title: {
                            display: true,
                            text: 'Votes',
                            font: {
                                size: 25
                            }
                            }
                        },
                        y: {
                            ticks: {
                            font: {
                                size: 14
                            }
                            },
                            title: {
                            display: false,
                            text: 'titleY',
                            font: {
                                size: 20
                            }
                            }
                        }
                        }
                    },
                    legend: {position: 'bottom' , display: false}
                };
            
                 myChart = new Chart(
                document.getElementById('quick1_chart'),
                config
            );

            toggle_chart.addEventListener('click',()=>{
                update_chart ();
                console.log('chart_data.datasets[0].data1',chart_data.datasets[0].data)
                toggle_chart.classList.toggle('btn-outline-primary')
                        toggle_chart.classList.toggle('btn-outline-success')
                        

                if (data1_flag){
                data1_flag = false;
                chart_data.datasets[0].data = data_for_2;
                chart_data.datasets[0].label = "Response After Discussion";
                chart_data.datasets[0].backgroundColor = 'rgb(0, 72, 0)';
                chart_data.datasets[0].borderColor = 'rgb(0, 72, 0)';
                toggle_chart.innerText = 'Response After Discussion';


                } else {
                data1_flag = true;
                chart_data.datasets[0].data = data_for_1;
                chart_data.datasets[0].label = "Initial Response";
                chart_data.datasets[0].backgroundColor = 'rgb(0, 73, 153)';
                chart_data.datasets[0].borderColor = 'rgb(0, 73, 153)';
                toggle_chart.innerText = 'Initial Response';

                }
                myChart.update();
                console.log('chart_data.datasets[0].data2',chart_data.datasets[0].data)


            }) 



           })  

        
 })

}  

const update_chart = () => {
    console.log('question_id', question_id);
            console.log('course',course);
            console.log('currentcourse',currentcourse);
            console.log('iid',iid);


    
    fetch('get_quick1_question_response.php',{method: 'POST',
          headers: {
                
                "Content-Type": "application/json",
                "Accept":"application/json, text/plain, */*"
            },
            body: JSON.stringify({iid:iid, course:currentcourse, question_id:question_id}),
        })
        .then((res1) => res1.json())
        .then((responses)=>{
          console.log('response from get_quick1_question_response',responses);
         let data_for_1 = [];
         let data_for_2 = [];
         let count =0;

          for(let i=0;i<num_parts; i++){
            count =0;
            responses.forEach((response)=>{
              for (let j = 0; j < response.response_st.length; j++) {
                // console.log('response.response_st.charAt(j): ',response.response_st.charAt(j))
                if(response.response_st.charAt(j) == String.fromCharCode(i+97) && response.try_number==0){
                 count += parseInt(response.count);
                }
              }
            })
            data_for_1.push(count);
          }
          for(let i=0;i<num_parts; i++){
            count =0;
            responses.forEach((response)=>{
              
              for (let j = 0; j < response.response_st.length; j++) {
               //   console.log(response.charAt(i));
              
              if(response.response_st.charAt(j) == String.fromCharCode(i+97) && response.try_number==1){
                count += parseInt(response.count);
                }
              }
            })
            data_for_2.push(count);
          }
          console.log('data_for_1',data_for_1)
          console.log('data_for_2',data_for_2)

       
        if (data1_flag){
                chart_data.datasets[0].data = data_for_1;
                } else {
                chart_data.datasets[0].data = data_for_2;
                }
                myChart.update();
                console.log('chart_data.datasets[0].data2',chart_data.datasets[0].data)
         })
      
}

fetch_data();
    </script>
    
</body>
</html>