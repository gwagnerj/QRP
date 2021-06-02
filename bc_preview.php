<?php
session_start();
require_once 'pdo.php';
require_once 'simple_html_dom.php';
require_once '..\encryption_base.php';

if (isset($_POST['problem_id'])) {
    $problem_id = $_POST['problem_id'];
} elseif (isset($_GET['problem_id'])) {
    $problem_id = $_GET['problem_id'];
} elseif ($_SESSION['problem_id']) {
    $problem_id = $_SESSION['problem_id'];
} else {
    $_SESSION['error'] = 'no problem id in bc_preview';
}
$salt = $problem_id*$problem_id;
$salt2 = $problem_id*$problem_id+$problem_id;
$enc_key = $enc_key.$salt;
$vid_enc_key = $vid_enc_key.$salt2;


$sql = 'SELECT * FROM Problem WHERE problem_id = :problem_id';
$stmt = $pdo->prepare($sql);
$stmt->execute([':problem_id' => $problem_id]);
$pblm_data = $stmt->fetch();
$contrib_id = $pblm_data['users_id'];
$nm_author = $pblm_data['nm_author'];
$specif_ref = $pblm_data['specif_ref'];
$htmlfilenm = $pblm_data['htmlfilenm'];

$solnfilenm = $pblm_data['soln_pblm'];
$solnfilenm = 'uploads/' . $solnfilenm;

$htmlfilenm = 'uploads/' . $htmlfilenm;

//             echo ('<h2> htmlfilenm: '.$htmlfilenm.'</h2>');

// read in the names of the variables for the problem
$nv = 0; // number of non-null variables
for ($i = 0; $i <= 13; $i++) {
    if ($pblm_data['nv_' . ($i + 1)] != 'Null') {
        $nvar[$i] = $pblm_data['nv_' . ($i + 1)];
        $nv++;
    }
}

// read in the input varaibles for the basecase
$stmt = $pdo->prepare(
    'SELECT * FROM Input where problem_id = :problem_id AND dex = :dex'
);
$stmt->execute([':problem_id' => $problem_id, ':dex' => 1]);
$BC_row = $stmt->fetch();

// Read in the value for the input variables

for ($i = 0; $i <= $nv; $i++) {
    if ($BC_row['v_' . ($i + 1)] != 'Null') {
        $vari[$i] = $BC_row['v_' . ($i + 1)];
        $BC_vari[$i] = $BC_row['v_' . ($i + 1)];
        $pattern[$i] = '/##' . $nvar[$i] . ',.+?##/';
    }
}
?>

<!DOCTYPE html >
<html lang = "en">
<head>
<meta charset="UTF-8">

<link rel="icon" type="image/png" href="McKetta.png" >
<!--
<title>QRHomework</title> 
<meta name="viewport" content="width=device-width, initial-scale=1" /> 

-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<script src="./crypto-js-4.0.0/crypto-js.js"></script><!-- https://github.com/brix/crypto-js/releases crypto-js.js can be download from here -->
<script src="Encryption.js"></script>
<script type="text/javascript" charset="utf-8" src="qrcode.js"></script>
<title><?php echo 'bc_' . $problem_id; ?></title>
</head>

<body>
<?php
//  I'm using reading from the $html and buiding the file $this_html.  I had to build it in two parts because of putting the
//i-frame for the checker in the middle of the document

$html = new simple_html_dom();
$html->load_file($htmlfilenm);
//            echo ('<h2> htmlfilenm: '.$htmlfilenm.'</h2>');

$base_case = $html->find('#problem', 0);
$reflection_text = $html->find('#reflections', 0);
// substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced

for ($i = 0; $i < $nv; $i++) {
    if ($BC_row['v_' . ($i + 1)] != 'Null') {
        $base_case = preg_replace($pattern[$i], $BC_vari[$i], $base_case);
        $reflection_text = preg_replace(
            $pattern[$i],
            $BC_vari[$i],
            $reflection_text
        );
    }
}

// add some markup to specific to the basecase since I just created it from the problem
$base_case = preg_replace(
    '/<div id="problem">/',
    '<div id="BC_problem">',
    $base_case
);
$base_case = preg_replace(
    '/<div id="questions">/',
    '<div id="BC_questions">',
    $base_case
);

foreach (range('a', 'j') as $m) {
    $let_pattern = 'part' . $m;
    $base_case = preg_replace(
        '/<div id="' . $let_pattern . '">/',
        '<div id="BC_' . $let_pattern . '">',
        $base_case
    );
}

// substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
for ($i = 0; $i < $nv; $i++) {
    if ($BC_row['v_' . ($i + 1)] != 'Null') {
        $base_case = preg_replace($pattern[$i], $vari[$i], $base_case);
    }
}

$dom = new DOMDocument();
libxml_use_internal_errors(true); // this gets rid of the warning that the p tag isn't closed explicitly
$dom->loadHTML('<?xml encoding="utf-8" ?>' . $base_case);
$images = $dom->getElementsByTagName('img');
foreach ($images as $image) {
    $src = $image->getAttribute('src');
    $src = 'uploads/' . $src;
    $src = urldecode($src);
    $type = pathinfo($src, PATHINFO_EXTENSION);
    $base64 =
        'data:image/' .
        $type .
        ';base64,' .
        base64_encode(file_get_contents($src));
    $image->setAttribute('src', $base64);
    $base_case = $dom->saveHTML();
}

$dom = new DOMDocument();
libxml_use_internal_errors(true); // this gets rid of the warning that the p tag isn't closed explicitly
$dom->loadHTML('<?xml encoding="utf-8" ?>' . $reflection_text);
$images = $dom->getElementsByTagName('img');
foreach ($images as $image) {
    $src = $image->getAttribute('src');
    $src = 'uploads/' . $src;
    $src = urldecode($src);
    $type = pathinfo($src, PATHINFO_EXTENSION);
    $base64 =
        'data:image/' .
        $type .
        ';base64,' .
        base64_encode(file_get_contents($src));
    $image->setAttribute('src', $base64);
    $reflection_text = $dom->saveHTML();
}

// turn base-case back into and simple_html_dom object that I can replace the varaible images on

if (str_get_html($base_case) != false) {
    $base_case = str_get_html($base_case);

    $keep = 0;
    $varImages = $base_case->find('.var_image');
    foreach ($varImages as $varImage) {
        $var_image_id = $varImage->id;

        for ($i = 0; $i < $nv; $i++) {
            if (trim($var_image_id) == trim($BC_vari[$i])) {
                $keep = 1;
            }
        }
        if ($keep == 0) {
            //  get rid of the caption and the image
            $varImage->find('.MsoNormal', 0)->outertext = '';
            $varImage->find('.MsoCaption', 0)->outertext = '';
        } else {
            //  get rid of the caption
            $varImage->find('.MsoCaption', 0)->outertext = '';
        }
        $keep = 0;
    }
}

// only include the document above the checker
$this_html =
    ' <div id = "base_case"><h4>Base Case Problem ' .
    $problem_id .
    '.</h4>' .
    $base_case .
    '</div>';
/* 
   // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
            $this_html = preg_replace($pattern[$i],$vari[$i],$this_html);
        }
         */
echo $this_html;
echo '<hr>';
echo '<p style="page-break-before: always"> ';

// put in the pdf for the solution to the basecase
echo '<h4>Solution to Base Case</h4>';
// echo ('<iframe src="'.$solnfilenm.'" width="90%"  ></iframe>');
echo '<iframe src="' . $solnfilenm . '" width="60%" height="600px" ></iframe>';
echo '<hr>';
echo '<p style="page-break-before: always"> ';

echo $reflection_text;

//! This is a security hole but could be used if the server were having problems keeping up we could put this in and check the answers with the data parameters in the page in any case we should have a separate encryption key for the
//!  video question and data as well as the Parameters which dont matter that much anyway.  
//! echo "<script>document.write(localStorage.setItem('enc_key', '".$enc_key."'))</script>";  // this is not the answer key encryption key that is $enc_key_key in editpblm file - different for each part of problem
//? put a way to tell if the server is overused and then the code to handle it in the file and uncover the above key - lookback at the editpblem file to see how to decode the $enc_key_key

echo "<script>document.write(localStorage.setItem('vid_enc_key', '".$vid_enc_key."'))</script>";

?>
<style>
.hidden {
    visibility: hidden;
}
.display_none { display: none;
}
.gray-out { background-color:black;
            padding: 0 3px 0 3px;
           
}
.gray-out:after{
   color:white;
    content:'watch video';
}
.question{
   display:flexbox;
}
.wrong {
    background-color:pink;
}
.correct {
    background-color:lightgreen;
}


</style>


<script> 
// initialize variables

var score = 0;
var vid_current_time_ar = new Array(0, 0, 0); //? this should be updated as the video gets pause either by the program or user
var vid_current_index_ar = new Array(0, 0, 0); //? this is the progress through each video - index will need to be updated as we go
var pause_vid_ar = new Array(); //? for each video we have an array of pause times
var vid_ar = new Array();
var vid_question_ar = new Array(); //? for each video we have an array of questions elements
var quiz_items_ar = new Array(); //? for each video have an array of questions that have an array of 

var vid_num_questions_ar = new Array();
var vid_container_ar = new Array();

vid_ar[0] = document.getElementById("vid1");
vid_ar[1] = document.getElementById("vid2");
vid_ar[2] = document.getElementById("vid3");

vid_container_ar[0] = document.getElementById("vid1_container");
vid_container_ar[1] = document.getElementById("vid2_container");
vid_container_ar[2] = document.getElementById("vid3_container");


console.log("vid_ar: ", vid_ar);

vid_question_ar[0] = document.getElementById("vid1_question_container");
vid_question_ar[1] = document.getElementById("vid2_question_container");
vid_question_ar[2] = document.getElementById("vid3_question_container");



console.log("vid_question_ar ", vid_question_ar);

var vid_duration_ar = new Array(); // how long each video is

var encryption = new Encryption();

//! get the elements in the videos into the array variables and add event listeners to the videos
for (let i = 0; i < 3; i++) {
    if (vid_ar[i]) {

        //  console.log ('i ',i);

        vid_ar[i].onloadeddata = function () {

            //       console.log(" vid_ar[i] ", vid_ar[i]);
            vid_duration_ar[i] = vid_ar[i].duration;
            console.log(" duration ", vid_duration_ar[i]);
            quiz_items_ar[i] = vid_question_ar[i].querySelectorAll(".vid-question")

            var p_time = new Array();
            var m = 0;
            for (let quiz_item of quiz_items_ar[i]) { //? an array of array of array - actually nodelists


                //     console.log("quiz_item ", quiz_item)
                p_time[m] = quiz_item.querySelector(".time").innerHTML;
                p_time[m] = encryption.decrypt(p_time[m], localStorage.getItem('vid_enc_key'));;

                // console.log("pause_item", p_time[m])

                m++;
            }
            pause_vid_ar[i] = p_time;

            // console.log("quiz_items_ar ", quiz_items_ar)
            console.log(" pause_vid_ar[i] ", pause_vid_ar)


            console.log("the quiz_items are ", quiz_items_ar[i]);
            var question_number = 0; // counter for displaying the right question

            vid_num_questions_ar[i] = quiz_items_ar[i].length;
            console.log(' vid_num_questions ', vid_num_questions_ar)

            // vid_ar[i].addEventListener("click", e => {
            //     e.preventDefault();
            //     video = e.target;
            //     runTillPause(video, i, 0, vid_current_time_ar[i]) 
            // })
            // vid_container_ar[i].addEventListener("click", e => {
            //     e.preventDefault();
            //    let  video = e.target
            // //     console.log("video_container",video_container);
            // //    let  video = video_container.querySelector("video");
            //     console.log ("video",video);
            //     runTillPause(video, i, 0, vid_current_time_ar[i]) 
            // })
            vid_ar[i].removeAttribute("controls")
           
           
         

            vid_container_ar[i].innerHTML += '<button class = "start-video-button"> Start Video </button>';
            vid_container_ar[i].innerHTML += '<button class = "resume-video-button display_none"> Resume Video </button>';
            vid_container_ar[i].innerHTML += '<button class = "pause-video-button display_none"> Pause Video </button>';
           vid_container_ar[i].querySelector(".start-video-button").addEventListener("click",function(e){
               console.log("start video damnit");
              let  video_container = e.target.parentNode;
              let video = video_container.querySelector(".video");
              video_container.querySelector(".pause-video-button").classList.remove("display_none");
               console.log ("video",video);
               this.classList.add("display_none");
               runTillPause(video, i, 0, vid_current_time_ar[i])
           })

           vid_container_ar[i].querySelector(".pause-video-button").addEventListener("click",function(e){
               console.log("pause video damnit");
              let  video_container = e.target.parentNode;
              let video = video_container.querySelector(".video");
              video_container.querySelector(".resume-video-button").classList.remove("display_none");
               console.log ("video",video);
               this.classList.add("display_none");
               video.pause();
             
           })
           vid_container_ar[i].querySelector(".resume-video-button").addEventListener("click",function(e){
               console.log("start it back damnit");
              let  video_container = e.target.parentNode;
              let video = video_container.querySelector(".video");
              video_container.querySelector(".pause-video-button").classList.remove("display_none");
               console.log ("video",video);
               this.classList.add("display_none");
               video.play();
             
           })
         

            //  vid_ar[i].onplay = function (event) {
            //     event.preventDefault();
            //     video = event.target
            //     console.log ("video",video)
            //     runTillPause(video, i, 0, vid_current_time_ar[i]) 
            //  }
          
            // vid_ar[i].addEventListener("pause", e => {
            //     manualPause(e, i, 0, vid_current_time_ar[i]) 
            // })
        }
    }
}

        function runTillPause(video, video_index, upcoming_q_index, start_time) { //? Play the selected video from the start time until the pause time of the upcoming question then call a function to display the upcoming question by sending them the upcoming question element
          
      //    
      video.removeAttribute("controls")
    //   video.addEventListener("click", e => {
    //             e.preventDefault();
    //             console.log ("wtf");
    //         })


      var man_pause = false;

            pause_time = pause_vid_ar[video_index][upcoming_q_index];
      

            let upcoming_question = quiz_items_ar[video_index][upcoming_q_index];
            console.log("upcoming_question ", upcoming_question);

            // let video = e.currentTarget;
            console.log(" video ", {video});

          
        //    console.log("video.currentTime:",  video.currentTime);

            var trip = 0;

      
        console.log ("pause_time",pause_time);
           
          

                video.currentTime = start_time;
                console.log("video.currentTime:",  video.currentTime);

                video.play();
                isPlaying = true;
          

             //   video.setAttribute("controls","controls")   
          
                setInterval(function () {
                   
                    if (video.currentTime > pause_time ) {
                        video.pause();
                      // isPlaying = false;//* maybe call a function that pauses the video changews the starttime and advances the index for the problem number
                        video.classList.add("display_none");
                        video.parentNode.querySelector(".pause-video-button").classList.add("display_none");
                        if (trip == 0) {
                            displayQuestion(upcoming_question,video_index);          //! this is where we call to diplay the function
                           
                            vid_current_index_ar[video_index] ++;

                            trip = 1; // kind of a bad way to only do it once - figure a better way
                        }
                    }
                    // else if (man_pause){
                    //     start_time = video.currentTime;
                    //     video.pause();
                       
                    //     console.log ("jane stop this bloody thing");
                    //     let video_id = video.id;
                    //     let video_container = document.getElementById("vid1_container");
                    //     video_container.innerHTML += '<br><button class = "resume-video" onclick = "resumeVideo()"> Resume Video </button>';
                      

                    // }

                }, 250);
           
        }



        function displayQuestion(upcoming_question, video_index) {
    
            //   vid_question_container = upcoming_question.parentElement;
            let vid_question = document.getElementById(upcoming_question.id);
            let vid_question_container = vid_question.parentElement;
            vid_question_container.classList.remove("hidden");
            let display_question = vid_question_container.querySelector(".display-question");
            console.log("display_question", display_question);
            display_question.innerHTML += '<h2 id = "the_question">' + encryption.decrypt(upcoming_question.querySelector(".text").innerText, localStorage.getItem('vid_enc_key')) + '</h2>'; //* display the question text
            let options = upcoming_question.querySelectorAll(".option");
            let keys = upcoming_question.querySelector(".key").innerText;
            keys = encryption.decrypt(keys, localStorage.getItem('vid_enc_key'));
            keys = keys.split(";")
            //           console.log("keys ", keys);
            let key_total = 0;

            for (var m = 0; m < keys.length; m++) {
                key_total += parseInt(keys[m]);
            }
            let one_answer = false;
            for (var m = 0; m < keys.length; m++) {
                if (key_total == keys[m] || one_answer) {
                    one_answer = true;
                }
            }
            // console.log("one_answer: ", one_answer);
            // console.log("key_total: ", key_total);
            // console.log(" keys: ",  keys);

            if (one_answer) {
                // put in radio buttons 
                let k = 1;
              
                for (const option of options) {
                    let key = keys[k - 1];
                    display_question.innerHTML += '<input type="radio" class = "response" id = "response_' + k + '" name  = "response"  value = "'+key+'"  >' + encryption.decrypt(option.innerText, localStorage.getItem('vid_enc_key')) + '</input><br><br>'
                    k++;
                }
                display_question.innerHTML += '<button class = "submit_button" id = "active-button option-' + k + '" onclick = "checkResponse(' + keys + ',' + video_index + ' )" > Submit </button>';

            } else {
                // put in checkboxes
                let k = 1;
               
                for (const option of options) {
                    let key = keys[k - 1]
                    display_question.innerHTML += '<input type="checkbox" class = "response" id = "response_' + k + '" value = "'+key+'" >' + encryption.decrypt(option.innerText, localStorage.getItem('vid_enc_key')) + '</input><br><br>'
                    k++;
                }
                display_question.innerHTML += '<button class = "submit_button" id = "active-button option-' + k + '" onclick = "checkResponse(' + keys + ',' + video_index + ' )" > Submit </button>';



            }
          
           // let k = 1;
            // //todo: will make this into radio buttons if there is only one choice and checkbox if there are multiple correct choices - do this by checking the key and see if one of them is 100 and all the rest are zero


            // for (const option of options) {
            //     let key = keys[k - 1]
            //     display_question.innerHTML += '<button  class = "active-button option-' + k + '" onclick = "checkResponse(' + key + ',' + video_index + ' )" >' + encryption.decrypt(option.innerText, localStorage.getItem('vid_enc_key')) + '</button>&nbsp;&nbsp;';
            //     k++;
            // }
            let vid_container = vid_question_container.parentElement.id;


            vid_question_container.innerHTML += '<br><button class = "resume-video hidden" onclick = "resumeVideo(' + vid_container + ',' + video_index + ' )"> Resume Video </button>';
        }




        function checkResponse(key,video_index) {
           document.querySelector(".submit_button").classList.add("hidden");
           
            let selected_keys = document.querySelectorAll('input[class="response"]:checked');
            n = selected_keys.length;

            console.log("n:",n)
            console.log("selected_keys ",selected_keys)

            let question_total = 0;

            for (var j = 0; j < n; j++) {
                console.log("selected_keys[j] ",selected_keys[j].value)
                question_total += parseInt(selected_keys[j].value);
            }
               

            
            console.log("click ",  question_total);
          
            let question = document.querySelector(".display-question")  //todo: since there will be multiple videos this wont work in general and will put info in the first video question container
            console.log("question ", question);
            let resume_video_button = document.querySelector(".resume-video");
            resume_video_button.classList.remove("hidden");


            if (question_total < 99) {
                score += parseInt(question_total);

                question.classList.add("wrong");
                question.innerHTML += "<h2> Incorrect </h2>";
                question.innerHTML += "<h2> Total Score is: " + score + " </h2>";

            } else {
                score += parseInt(question_total);
                question.classList.add("correct");
                question.innerHTML += "<h2> Correct </h2>";
                question.innerHTML += "<h2> Total Score is: " + score + " </h2>";
            }

          

}


function resumeVideo(video_container, video_index) {
    console.log("vid_current_index_ar ", vid_current_index_ar)
    console.log("answered Question", video_container);
    let video_question_container = video_container.querySelector(".vid_questions")
    let diplay_question = video_question_container.querySelector(".display-question");
    let resume_video_button = document.querySelector(".resume-video");
    resume_video_button.classList.add("hidden");
    diplay_question.classList.remove("wrong");
    diplay_question.classList.remove("correct");
    diplay_question.innerHTML = '';
    // video_question_container.classList.add("hidden");

    // video_container.querySelector(".vid_questions").classList.add("hidden");
    let video = video_container.querySelector(".video");
    video.classList.remove("display_none");
    video.parentNode.querySelector(".pause-video-button").classList.remove("display_none");
    console.log("video2", video);
    video.play();
    current_index = vid_current_index_ar[video_index];
    previous_index = current_index - 1;
    start_time = pause_vid_ar[video_index][previous_index];
    //! if the next value of the pause vid array does not exits then the there are not more questions

    runTillPause(video, video_index, current_index, start_time);

}


function manualPause(e, video_index, upcoming_q_index, start_time) {
            console.log ("videos manually paused");
        }

        </script>




