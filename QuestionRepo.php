<?php
require_once "pdo.php";
session_start();

$iid = '1';
if(isset($_POST["iid"])){
  $iid = $_POST["iid"];
}elseif(isset($_GET["iid"])){
  $iid = $_GET["iid"];
}

$sql = "SELECT Discipline.discipline_name AS discipline_name, Course.course_name AS course_name, Users.first AS first_nm, Users.last AS last_nm
FROM Users 
LEFT JOIN Discipline ON Users.discipline_id = Discipline.discipline_id
LEFT JOIN UserTypicalCourseConnect ON Users.users_id = UserTypicalCourseConnect.users_id
LEFT JOIN Course ON UserTypicalCourseConnect.course_id = Course.course_id
 WHERE Users.users_id = :iid";
$stmt = $pdo->prepare($sql);
$stmt->execute(array('iid' => $iid));
$users_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
$discipline_name = $users_info[0]['discipline_name'];
$users_name = $users_info[0]['first_nm']." ".$users_info[0]['last_nm'];
$typical_courses = [];
foreach ($users_info as $user_info){
    $typical_courses[] = $user_info['course_name'];
}



$sql = "SELECT discipline_name FROM Discipline ORDER BY discipline_id";
$stmt = $pdo->prepare($sql);
$stmt ->execute();
$disciplines = $stmt ->fetchAll(PDO::FETCH_COLUMN);

$sql = "SELECT Course.course_name AS course_name FROM Course
LEFT JOIN DisciplineCourseConnect ON Course.course_id = DisciplineCourseConnect.course_id
LEFT JOIN Discipline ON Discipline.discipline_id = DisciplineCourseConnect.discipline_id
 WHERE Discipline.discipline_name = :discipline_name
  ORDER BY course_name";
$stmt = $pdo->prepare($sql);
$stmt ->execute(array(
  ':discipline_name' => $discipline_name
));
$courses = $stmt ->fetchAll(PDO::FETCH_COLUMN);

$stmt = "SELECT CurrentClass.name as `name`,
                CurrentClass.currentclass_id AS currentclass_id,
                Course.course_name as course_name
			FROM CurrentClass
      LEFT JOIN Course ON CurrentClass.course_id = Course.course_id
			WHERE iid =:iid AND exp_date > NOW()"; 
			$stmt = $pdo->prepare($stmt);	
			$stmt->execute(array(':iid' => $iid));
			$currentclasses = $stmt->fetchAll(PDO::FETCH_ASSOC);


      $cclasses = array();
      $courses_list = array();
    $i = 0;
      foreach ($currentclasses as $currentclass){
        $cclasses[$i] = $currentclass['name'];
        $cclasses_id[$i] = $currentclass['currentclass_id'];
        if ($currentclass['course_name']&& !in_array($currentclass['course_name'], $courses_list, true)){
          array_push($courses_list,$currentclass['course_name']);
        }
        $i++;
      }
      $num_currentclasses = $i;
    $see_more_courses = true;

if ($see_more_courses){

    foreach($typical_courses as $typical_course){
      if(!in_array($typical_course, $courses_list, true)){
          array_push($courses_list,$typical_course);
      }
    }
  }

?>



<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
 <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<meta Charset = "utf-8">
<title>QR Question Repo</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 

<style>
#question_classes{
  z-index: 2 important;
}

    .cards{
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    p{
      font-size: 0.8em;
    }
    .card-body{ 
    height: 380px !important;
    }
    .preview{ 
      position: relative;
      overflow: hidden;
      padding-top: 75%;

      /* height:120%;
      width: 350px !important;
      margin: 0 !important;
      padding: 0;
      position: relative;
      z-index: 0; */
     
      
    }
    .preview-active{ 
      position: relative;
      overflow: hidden;
      padding-top: 75%;
      z-index: 0;
        /* width: 157% !important;
  height: 170% !important; */


    }

   .preview-active iframe{
      /* height:100%;
      width:300px !important; */
      transform: translate(-120px, -100px) scale(0.65);
  position: absolute;
  left: 0 !important;
  top: 0 !important;
  width: 157% !important;
  height: 170% !important;

  /* padding: 0 !important;
  margin-left: 0 !important; */
    }
   .preview iframe{
      /* height:100%;
      width:300px !important; */
      transform: translate(-100px, -70px) scale(0.65);
  position: absolute;
  left: 0 !important;
  top: 0 !important;
  width: 153% !important;
  height: 153% !important;

  /* padding: 0 !important;
  margin-left: 0 !important; */
    }
    /* #course_list{
      display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    } */
    .hide{ 
      display: none;
    }

    .active-card{
    background-color: lightyellow !important;
      height: 123% !important;
      }

    .emailed{
    background-color: #ffffcc !important;
   }

   

</style>



</head>

<body>
  <nav>
  <a id = "back_to_problem_repo" class="btn btn-outline-primary btn-sm m-2" href="QRPRepo.php"role="button">Problem Repository</a>
  </nav>


<header>
    <h1>Quick Response Questions</h1>

  </header>
  <div class="container-fluid mx-1">
        <div class="error-message">
            <?php
              if ( isset($_SESSION['error']) ) {
                echo '<h3 style="color:red">'.$_SESSION['error']."</h3>\n";
                unset($_SESSION['error']);
              }
            ?>
        </div>
        <!-- <form> -->
        <div class="currentclass bg-light " id="currentclass">
          <h3 class="current_class_title text-primary fs-2">Current Class <span class = "text-primary fs-5"> (group of students)</span></h3>
          <div class="row mx-2 mb-3 ms-1 border" id = "current_classes">
            <?php 

              if ($num_currentclasses == 0){
                echo '<h2 id = "no_currentclasses" class = "no-currentclasses text-danger">You have no unexpired current classes.  Please Renew or Create a Class to before using this module </h2> ';

              } else {

                $i = 0;
                foreach ($cclasses as $course){
                  echo '<div class = "form-check col-2">
                  <input type = "radio" class = "form-check-input course-list" data-ccid = "'.$cclasses_id[$i].'" id = "'.$course.'1" name = "currentclass" value = "'.$course.'"></input>
                  <label for="'.$course.'1" class = "form-check-label">'.$course.'</label>
                  </div>
                  ';
                  $i++;
                }
              }
                ?>
           </div>
        </div>
        <header class = "active_card_header mb-2">
            <span id="active_container_title" class="active-conatiner-title text-primary fs-4 me-5 hide ">Active Questions </span>
            <button id="display_all_active_btn" class="btn btn-outline-primary hide ">Display & Responses for Active Questions</button>
            <button id="display_rescent_activity_btn" class="btn btn-outline-secondary hide ms-5">Display Who Responded for me</button>
            <button id="make_a_problem_questionset_btn" class="btn btn-outline-secondary hide ms-5">Problem Question Set</button>
            <input id="problem_id" type = "number" min = "1" max = "99999" placeholder = "problem_id" class=" hide ms-5"></input>
            <span id = "problem_id_error" class = "hide text-danger">Please enter a valid problem_id</span>
            <span id = "no_problem_id_msg" class = "hide text-danger">No Active Problem with that ID was found</span>
            <span id = "something_went_wrong_msg" class = "hide text-danger">Something went wrong connecting the problem to the question</span>
            <span id = "success_msg" class = "hide text-success">Success- the questions and problem are linked</span>
            <button id="make_problem_question_submit" class="btn btn-primary hide ms-5">Submit</button>

        </header>
        <div class="row" id = "active_card_container">        
              
        </div>

        
<br>
<br>
<br>


            <!-- <input type="hidden" id="course" value="Material Balances"> -->
            <div class="form-group course-list-container hide row mt-3" id = "course_list" >
              
            <h3 class="course text-primary fs-2 mt-5">Select Questions from this Course </h3>
                <div class="row mx-2  mb-2 ms-1 border" id = "question_classes" >

                    <?php 
                    
                    foreach ($courses_list as $course){
                      echo '<div class = "form-check col-2">
                      <input type = "radio" class = "form-check-input course-list" id = "'.$course.'" name = "sendcourse" value = "'.$course.'"></input>
                      <label for="'.$course.'" class = "form-check-label">'.$course.'</label>
                      </div>
                      ';
                    }
                    ?>
                </div>
              </div>

            <input type="hidden" id="iid" value="<?php echo($iid);?>">
            <input type="hidden" id="discipline_name" value="<?php echo($discipline_name);?>">
            <br>
            <h5 class = "text-primary hide" id = "concept_title">Narrow Search by Selecting a Concept</h5>

            <section class = "concept-checks mb-3" id = "concept_checks">
             </section>
      



        <div id="quick_send" class = "offcanvas offcanvas-start" aria-labelledby="offcanvasQuickSend">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasQuickSend">Quick Send</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
                    <div class="quick-send-info">
                      <h4>Class Name <span id = "selected_class_name_holder1"></span></h4>
                      <h4 class = "text-primary">Question Number <span class = "text-primary" id = "selected_question_id_holder1"></span></h4>
                      <h4 class = "text-success">Class Number <span  id = "selected_class_id_holder1"></span></h4>
                    </div>

              <div class="hours-active-container" id="active_hours_container " title = "Time form now until the initial response from the students">
                <label for="hours_active" class = "form-label mt-4 fs-5 text-primary">Hours from now students can respond</label>
                  <input type = number class = " mb-4" id = "hours_active" min = "0.05" max = "999" step = "any" value = 3 ></input>
              </div>

              <div class="form-check form-switch">
                  <input class="form-check-input my-3 ms-5" type="checkbox" role="switch" id="shuffle_flag" name = "shuffle_flag" checked="true" autocompleted="">
                  <label class="form-check-label my-2 ms-1" for="shuffle_flag">Shuffle Options</label>
                </div>


            <div id = "send_control" class="dropdown mt-3">
              <button class="btn btn-primary" type="button" id="send_email_button">
                Send Email to Class
              </button>
              <button class="btn btn-secondary" type="button" id="make_active_question_button">
                Make Question Active
              </button>
            </div>
          </div>
        </div>
        <div id="quick1_response" class = "offcanvas offcanvas-end" aria-labelledby="offcanvasQuick1Response">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasQuick1Response">Student Response</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">

          
            <div>
              <button id = "toggle_chart"type="button" class="btn btn-outline-primary ">Toggle Chart</button>
              <button id = "display_chart" type="button" class="btn btn-danger ms-1 hide">Student Display</button>
            </div>

                  <div>
                  <canvas id="quick1_chart"></canvas>
                </div>
                
                <div class="quick-send-info mt-5">
                      <h4>Class Name <span id = "selected_class_name_holder2"></span></h4>
                      <h4 class = "text-primary">Question Number <span class = "text-primary" id = "selected_question_id_holder2"></span></h4>
                      <h4 class = "text-success">Class Number <span  id = "selected_class_id_holder2"></span></h4>
              </div>

              <div class="control-group mt-5" id="response_control">
                <button id = "discussion_start" class = "discussion-start btn btn-primary">Discussion Start</button>
                <button id = "allow_2nd_submit" class = "allow-2nd-submit btn btn-success hide">Allow 2nd Submit</button>
              </div>

          </div>
        </div>



        <div class="container-fluid">

          <div id="card_container" class = "card-container'">
              <div id = "cards_control"></div>



                <div id = "cards_section" class ="cards-section border-top"></div>

          <!-- <input class="fuzzy-search" data-search="title" placeholder="Search Name" /> -->

          </div>     

        
        </div>
    </div>
<!-- </form> -->

<script>

const iid = document.getElementById('iid').value;
// let course = document.getElementById('course').value;
 //const course = 'Material Balances';
const discipline_name = document.getElementById('discipline_name').value;
const course_list = document.getElementById('course_list');
const cards_section= document.getElementById('cards_section');
const cards_control= document.getElementById('cards_control');
const concept_checks= document.getElementById('concept_checks');
const current_classes = document.getElementById('current_classes');
const send_email_button = document.getElementById('send_email_button');
const make_active_question_button = document.getElementById('make_active_question_button');
const toggle_chart = document.getElementById('toggle_chart');
const display_chart = document.getElementById('display_chart');
const send_control = document.getElementById('send_control');
const discussion_start = document.getElementById('discussion_start');
const allow_2nd_submit = document.getElementById('allow_2nd_submit');
const active_card_container = document.getElementById('active_card_container');
let data1_flag = true;

const display_all_active_btn = document.getElementById('display_all_active_btn');
const display_rescent_activity_btn = document.getElementById('display_rescent_activity_btn');
const make_a_problem_questionset_btn = document.getElementById('make_a_problem_questionset_btn');
const problem_id = document.getElementById('problem_id');
const make_problem_question_submit = document.getElementById('make_problem_question_submit');


//const quick1_chart_ctx = document.getElementById('quick1_chart').getContext('2d');

let selected_sendcourse = '';
let question_id;
let currentclass_id;
const offcanvasQuick1Response = document.getElementById('offcanvasQuick1Response');

display_rescent_activity_btn.addEventListener('click', () =>{
  console.log ("rescent Activity clicked")
// open a page with the selected class that with the date range gets all the student activity for each question
    selected_sendcourse = current_classes.querySelector('input[name ="currentclass"]:checked').value;
  let url_str = `question_show_rescent_activity.php?iid=${iid}&currentclass=${selected_sendcourse}`;

    window.open(url_str, '_blank');

})




//! function to get the questions that are active and make cards from these active cards and put them in the active card sections

currentclass.addEventListener('click',displayActiveCards);

function displayActiveCards (){
  selected_sendcourse = current_classes.querySelector('input[name ="currentclass"]:checked').value;
 
    if (!selected_sendcourse){
      return
    }

    course_list.classList.remove('hide');
    active_card_container.innerHTML = '';
    document.getElementById('active_container_title').classList.remove('hide')
    display_all_active_btn.classList.remove('hide')
    display_rescent_activity_btn.classList.remove('hide')
    make_a_problem_questionset_btn.classList.remove('hide')

    currentclass_id = current_classes.querySelector('input[name ="currentclass"]:checked').getAttribute('data-ccid')
    fetch('getActiveQuickQuestion.php',{method: 'POST',
    headers: {
        
        "Content-Type": "application/json",
        "Accept":"application/json, text/plain, */*"
    },
    body: JSON.stringify({currentclass_id:currentclass_id}),
})
.then((res) => res.json())
.then((data) =>{
    // console.log('data');
    // console.log(data);
    // console.log(data.length);
    if (data.length > 0) {
      //? Put together the display all active Questions
      display_all_active_btn.addEventListener('click',()=>{
        // console.log ("Display All Active clicked")

         let send_course_name = current_classes.querySelector('input[name ="currentclass"]:checked').value;
          let course_question = localStorage.getItem('course');
          // console.log("send_course_name",send_course_name)
          // console.log("course_question",course_question)
          //? create string to send all of the problems to 
          let url_string = `question_show_response.php?course=${course_question}&currentcourse=${send_course_name}&iid=${iid}`;
            for (let i = 0; i <data.length; i++){
              url_string += `&question_id_${i}=${data[i].question_id}`;
            }
            url_string += `&current_question_id=${data[0].question_id}`;
          // console.log ("url_string",url_string);
              window.open(url_string, '_blank');


      })

      
      make_a_problem_questionset_btn.addEventListener('click',()=>{
        console.log ("make_a_problem_questionset_btn clicked")
        problem_id.classList.remove('hide')
        make_problem_question_submit.classList.remove('hide')
        make_problem_question_submit.addEventListener('click',()=>{
          let problem_id_value = problem_id.value;
          if (problem_id_value == "" || problem_id_value == null || isNaN(problem_id_value)){
            document.getElementById('problem_id_error').classList.remove('hide')
          }
          else{
            document.getElementById('problem_id_error').classList.add('hide')
            // now create entries in the ProblemQuestionConnect table using the data array and the problem id using a fetch call to problem_question_connect.php
           
           // prepare the string to send the problem_question_connect.php file
          let  problem_question_ids = {};
            problem_question_ids['problem_id'] = problem_id_value;
            for (let i = 0; i <data.length; i++){
              let j = i + 1;
             let key = `question_${j}`;
             problem_question_ids[key] = data[i].question_id;
            }
           
            console.log ('data sent to problem_question_connect',JSON.stringify(problem_question_ids))

            fetch('problem_question_connect.php',{method: 'POST',
            headers: {
                
                "Content-Type": "application/json",
                "Accept":"application/json, text/plain, */*"
            },
         
            body: JSON.stringify({
              problem_question_ids            }),
        })
        .then((res) => res.json())
          .then((respon)=>{
            console.log ("respon",respon)
            if (respon == "success"){
              console.log ("success")
              problem_id.classList.add('hide')
              make_problem_question_submit.classList.add('hide')
              document.getElementById('problem_id_error').classList.add('hide')
              document.getElementById('success_msg').classList.remove('hide')
              document.getElementById('no_problem_id_msg').classList.add('hide')
              document.getElementById('something_went_wrong_msg').classList.add('hide')

            }
            else if (respon == 'fail'){
              console.log ("fail")
              problem_id.classList.add('hide')
              make_problem_question_submit.classList.add('hide')
              document.getElementById('something_went_wrong_msg').classList.remove('hide')
              document.getElementById('success_msg').classList.add('hide')
              document.getElementById('no_problem_id_msg').classList.add('hide')
            }
            else if (respon == 'problem_not_found'){
              console.log ("problem_not_found")
              problem_id.classList.add('hide')
              make_problem_question_submit.classList.add('hide')
              document.getElementById('no_problem_id_msg').classList.remove('hide')
              document.getElementById('something_went_wrong_msg').classList.add('hide')
              document.getElementById('success_msg').classList.add('hide')
            } else {
              console.log ('something else happened',respon)
            }



           
          }
        
          )
          }
        })
          // let url_string = `make_problem_questionset.php?course=${course_question}&currentcourse=${send_course_name}&iid=${iid}&problem_id=${problem_id_value}`;
          // for (let i = 0; i <data.length; i++){
          //   url_string += `&question_id_${i}=${data[i].question_id}`;
          // }
          // url_string += `&current_question_id=${data[0].question_id}`;
          // console.log ("url_string",url_string);
          // window.open(url_string, '_blank');
        })
        


      


      //     let question_send_id = localStorage.getItem('question_id');
      //     let send_course_name = localStorage.getItem('sendcourse');
      //     let course_question = localStorage.getItem('course');

      //     // start the discussion stage for the question
      //     // discussion_start.click();
      //     display_chart.disabled = true;
      //       window.open(`question_show_response.php?question_id=${question_send_id}&course=${course_question}&currentcourse=${send_course_name}&iid=${iid}`, '_blank');
      //     })

    }

     //? build the active card
     cards = '<div class="cards row mb-3" id = "active_cards" >';
    data.forEach((question) =>{
        const author = question.nm_author ? 'by: '+question.nm_author : '';
        const author_class = question.nm_author ? 'card-footer' : '';
        let html_fn = question.htmlfilenm;
        if(html_fn.indexOf('.htm') == -1){
          html_fn += '.htm';
        }
        html_fn = 'uploads/'+html_fn;

        let explanation_html = '';
        let explanation_filenm = '';
        if (question.explanation_filenm){
          explanation_filenm = question.explanation_filenm;
            if(explanation_filenm.indexOf('.htm') == -1){
                explanation_filenm += '.htm';
            }
            explanation_filenm = 'uploads/'+explanation_filenm;
            //  console.log ('explanation_filenm',explanation_filenm);
            //  explanation_html = `<div class = "preview"><iframe class ="m-0" src ="${explanation_filenm}"  width="500px" height="150px" style="border:none;" ></iframe></div>`;
        }
        let key_str = '';
           

          if (question.key_a ==1){key_str +='a'}
          if (question.key_b ==1){key_str +='b'}
          if (question.key_c ==1){key_str +='c'}
          if (question.key_d ==1){key_str +='d'}
          if (question.key_e ==1){key_str +='e'}
          if (question.key_f ==1){key_str +='f'}
          if (question.key_g ==1){key_str +='g'}
          if (question.key_h ==1){key_str +='h'}
          if (question.key_i ==1){key_str +='i'}
          if (question.key_j ==1){key_str +='j'}

        // console.log ('email_flag from getactive',question.email_flag)

         if (question.email_flag == 1){
            cards += ` <div class = "card active-card emailed" id = "active_card_${question.question_id}">
            <div class="card-header id">${question.question_id}<span class = "fs-5 ms-5"> e-mailed to Students </span> <button id = "active_btn_${question.question_id}" class = "btn btn-outline-danger btn-sm position-absolute top-0 end-0"   aria-controls="quickSendSidebar">Responses</button></div>

            `
         } else {
            cards += ` <div class = "card active-card " id = "active_card_${question.question_id}">
            <div class="card-header id">${question.question_id} <button id = "active_btn_${question.question_id}" class = "btn btn-outline-primary btn-sm position-absolute top-0 end-0"   aria-controls="quickSendSidebar">Responses</button></div>

            `
         }



           cards += `    
                  <h6 class = "title card-title">${question.title}</h6>
                  <br><div class = "card-body">
                  <div class = "preview-active"><iframe class ="m-0" src ="${html_fn}"  style="border:none;" ></iframe></div>
                  <div class = "key" >Correct Response: ${key_str}</div>
                     
                      <p class = "primary-concept">${question.primary_concept}</p>
                      <p class = "course">${question.course}</p>
                      <p class = "author ${author_class}" > ${author}</p><br><br>
                      ${explanation_html}
                     
                  </div>
               </div>   
            `;
    })
    cards += '</div>';


    let card_element = document.createElement('DIV');
    card_element.innerHTML =cards;
    active_card_container.append(card_element);
  })
  
}


//! end listener

function getResponseData(question_id,currentclass_id,num_parts,options_str){
  fetch('get_quick1_question_response.php',{method: 'POST',
          headers: {
                
                "Content-Type": "application/json",
                "Accept":"application/json, text/plain, */*"
            },
            body: JSON.stringify({iid:iid, course:selected_sendcourse, question_id:question_id}),
        })
        .then((res1) => res1.json())
        .then((responses)=>{
          // console.log('response from get_quick1_question_response',responses);
          data_for_1 = [];
          data_for_2 = [];
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
          // console.log('data_for_1',data_for_1)
          // console.log('data_for_2',data_for_2)

          //? now graph the data
           const labels = options_str;

                      const chart_data = {
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
                options: {indexAxis: 'y',   }
              };

              const myChart = new Chart(
              document.getElementById('quick1_chart'),
              config
            );

            let quick1_response = document.getElementById('quick1_response');
              quick1_response.addEventListener('hidden.bs.offcanvas',  () => {
                discussion_start.classList.remove("hide")
                    allow_2nd_submit.classList.add("hide")

              myChart.destroy();
            })




            toggle_chart.addEventListener('click',()=>{
              // console.log('chart_data.datasets[0].data1',chart_data.datasets[0].data)
              if (data1_flag){
                data1_flag = false;
                chart_data.datasets[0].data = data_for_2;
                chart_data.datasets[0].label = "Response After Discussion";
                chart_data.datasets[0].backgroundColor = 'rgb(0, 72, 0)';
                chart_data.datasets[0].borderColor = 'rgb(0, 72, 0)';


              } else {
                data1_flag = true;
                chart_data.datasets[0].data = data_for_1;
                chart_data.datasets[0].label = "Initial Response";
                chart_data.datasets[0].backgroundColor = 'rgb(0, 73, 153)';
                chart_data.datasets[0].borderColor = 'rgb(0, 73, 153)';

              }
              myChart.update();
              // console.log('chart_data.datasets[0].data2',chart_data.datasets[0].data)


          })


//          })


     })
}



function showResponsesInSidebar(question_id,currentclass_id){
  selected_sendcourse = current_classes.querySelector('input[name ="currentclass"]:checked').value;
  //  console.log('selected currentclass_id',current_classes.querySelector('input[name ="currentclass"]:checked').getAttribute('data-ccid'))
  //  console.log ('question_id',question_id)
   localStorage.setItem('sendcourse',selected_sendcourse);
  localStorage.setItem('question_id',question_id);

     const quick1_response_offcanvas = new bootstrap.Offcanvas('#quick1_response')  // creates the offcanvas instance from the html element with the given ID
 
       quick1_response_offcanvas.show();

       document.getElementById('selected_class_name_holder2').innerText = selected_sendcourse;  
       document.getElementById('selected_class_id_holder2').innerText = currentclass_id;  
       document.getElementById('selected_question_id_holder2').innerText = question_id; 

       //? Getting the question data and put it in the side bar and also get the number of parts to the question

       let selector = "active_card_"+question_id
       selected_card = document.getElementById(selector);


//       let selected_card = .parentNode.parentNode.parentNode;
      let quick_response_card_iframe_conatiner = selected_card.querySelector('iframe');
      let q_title = selected_card.querySelector('.card-title')
      // console.log('card',selected_card);
      // console.log('q_title',q_title);

          let iframe = quick_response_card_iframe_conatiner.contentWindow.document;
          // console.log('iframe',iframe);

            //? query the cards iframe to get the title, options and stem for the question response graph
          let child_nodes = iframe.childNodes;
          // console.log('child_nodes', child_nodes);
          // let q_title = child_nodes[0];
          let q_stem_html = child_nodes[0].querySelector('#stem_text_1').innerHTML;
          let q_stem_text = child_nodes[0].querySelector('#stem_text_1').innerText;
          //  child_nodes[0].innerHTML = child_nodes[0].innerHTML.replaceAll('##',''); //! put this when you get all of the iframes
          // console.log('q_stem_text',q_stem_text);
          q_stem = `<h4>Student Response to:</h4><h3> ${q_title.innerText}</h3> <h5>${q_stem_text}</h5>`;
          offcanvasQuick1Response.innerHTML=q_stem;
          // console.log('title',q_title);
          let options=[];
          let options_str = [];
          let num_parts = 0;
          for (let i = 0; i <=9;i++) {    //? looping thru 0 a thru j
            let n = String.fromCharCode(97 + i);
            let selector = `#question_option_${n}`
              if(child_nodes[0].querySelector(selector)){
                num_parts ++;
                options[i]= child_nodes[0].querySelector(selector).innerHTML;
                options[i] = options[i].replaceAll('##','');
              options_str[i] = options[i].replace(/<[^>]+>/g, '');
        }

}

// console.log("options",options);
// console.log("options_str",options_str);
// console.log("num_parts",num_parts);

         let data_for_1 = [];
        let data_for_2 = [];

       getResponseData(question_id,currentclass_id,num_parts,options_str)

}


//!  listener is on the active cards if they click the response button on the active card bring it up in the right offcanvas 
active_card_container.addEventListener('click', (e)=>{

  const isActiveButton = event.target.nodeName === 'BUTTON';
  if (!isActiveButton) {
    return;
  }
  event.preventDefault();
   question_id_holder = event.target.id
   question_id =question_id_holder.split('_')[2];
   showResponsesInSidebar(question_id,currentclass_id)


})







//! listener is on the main cards section section and then just reutrns if it not a button  if it is a sendbutton then put the values in the offcanvas left 

cards_section.addEventListener('click', (event) => {
  const isButton = event.target.nodeName === 'BUTTON';
  if (!isButton) {
    return;
  }
  event.preventDefault();
   question_id = event.target.id
   selected_sendcourse = current_classes.querySelector('input[name ="currentclass"]:checked').value;
  //  console.log('selected',current_classes.querySelector('input[name ="currentclass"]:checked').getAttribute('data-ccid'))
      document.getElementById('selected_class_name_holder1').innerText = selected_sendcourse;  
      document.getElementById('selected_class_id_holder1').innerText = current_classes.querySelector('input[name ="currentclass"]:checked').getAttribute('data-ccid');  
      document.getElementById('selected_question_id_holder1').innerText = question_id;  

   //? the bootstrap offcanvas takes care of opening up the sidebar
})



//! generate and show cards for the selected course
course_list.addEventListener("click",(e)=>{
  course = e.target.value;

      localStorage.setItem('course', course);

      document.getElementById('concept_title').classList.remove('hide');  // this shows the Narrow Search by Selecting.... title

      getTheCards();
      filterCardsbyCheckbox()
      // show the filters and addeven listener for the filters change


      

      //   //? select the same course on the sidebar radio button list if available_funds
  const quick_send = document.getElementById('quick_send');  //this is the left off canvas to send the card
  // console.log('course',course);
   let selected_current_course = quick_send.querySelector(`input[value="${course}"]`)?quick_send.querySelector(`input[value="${course}"]`):'';  // this will look in the offcanvas and see if the card is selected 
   if(selected_current_course!=''){selected_current_course.setAttribute('checked',true)};  //? if the course is selected in the left offcanvas then check the check the course in the course_list
   
  //  console.log("selected_current_course",selected_current_course)


  })
   

//       //! put event listener on the div container both the send email button and the make question active buttons

      send_control.addEventListener('click',(e)=>{                        //? send control is the buttons on the left offcanvas to send the question or make it active
     let discuss_stage = 1;
    //  let discuss_stage = document.querySelector('input[name="discuss_stage"]:checked').value;
     let   email_flag = false;
        if (e.target.id == 'send_email_button'){
          email_flag = true;
        } 

      const  shuffle_flag = document.getElementById('shuffle_flag').checked
        // console.log('shuffle_flag',shuffle_flag);

      const  hours_active = document.getElementById('hours_active').value;
        // console.log('hours_active',hours_active);
        // console.log('e_target in send mail',e.target.id);
        // console.log('email flag',email_flag);
   selected_sendcourse = current_classes.querySelector('input[name ="currentclass"]:checked').value;
    //? get the current cuouse selected from the sidebar and the card that was sent

  //   console.log('the course is:',course);
  //   console.log('the selected_sendcourse  is:',selected_sendcourse);
  //   console.log('question_id',question_id);
  //   console.log('discuss_stage',discuss_stage);
  //  console.log(JSON.stringify({iid:iid, course:selected_sendcourse, question_id:question_id, email_flag:email_flag, discuss_stage:discuss_stage, hours_active:hours_active, shuffle_flag:shuffle_flag}))
    //? send the email using fetch
    fetch('email_quick_send.php',{method: 'POST',
    headers: {
        
        "Content-Type": "application/json",
        "Accept":"application/json, text/plain, */*"
    },
    body: JSON.stringify({iid:iid, course:selected_sendcourse, question_id:question_id, email_flag:email_flag, discuss_stage:discuss_stage, hours_active:hours_active, shuffle_flag:shuffle_flag}),
})
.then((res) => res.json())
.then((data) =>{

   displayActiveCards();

    // console.log(data);
    document.getElementById(question_id).click();  //? after you quick send the email send them back to the main page
    document.getElementById(question_id).classList.add("hide")
 

})
      })
  //! new adeventlistener end for sending email

//     //? change the background of the card that you emailed and add a show results button
//     document.getElementById("card_"+question_id).classList.add("emailed")  //? this turns the background of this card a different color
//     //? put in the buttons to show the responses from the students
//     //? make the button in text format 
//     let show_quick_response_button_html = `<button id = "quick-response1_${question_id}" class = "btn btn-outline-danger btn-sm position-absolute top-0 end-0"   aria-controls="quickSendSidebar">Responses</button>`

//     let show_quick_response_button = document.createElement('DIV');
//     show_quick_response_button.innerHTML =show_quick_response_button_html;
//     document.getElementById(question_id).parentElement.appendChild(show_quick_response_button);
//     show_quick_response_button.addEventListener('click', showResponseInSidebar=(e)=>{})
   
   
   
   
   
     
//     // quick1_response_offcanvas.clear();
//       let selected_card = e.target.parentNode.parentNode.parentNode;
//       let quick_response_card_iframe_conatiner = selected_card.querySelector('iframe');
//       let q_title = selected_card.querySelector('.card-title')
//       console.log('card',selected_card);
//       console.log('q_title',q_title);

//       let iframe = quick_response_card_iframe_conatiner.contentWindow.document;
//       console.log('iframe',iframe);

//         //? query the cards iframe to get the title, options and stem for the question response graph
//       let child_nodes = iframe.childNodes;
//       console.log('child_nodes', child_nodes);
//       // let q_title = child_nodes[0];
//       let q_stem_html = child_nodes[0].querySelector('#stem_text_1').innerHTML;
//       let q_stem_text = child_nodes[0].querySelector('#stem_text_1').innerText;
//       //  child_nodes[0].innerHTML = child_nodes[0].innerHTML.replaceAll('##',''); //! put this when you get all of the iframes
//       console.log('q_stem_text',q_stem_text);
//       q_stem = `<h4>Student Response to:</h4><h3> ${q_title.innerText}</h3> <h5>${q_stem_text}</h5>`;
//       offcanvasQuick1Response.innerHTML=q_stem;
//       console.log('title',q_title);
//       let options=[];
//       let options_str = [];
//       let num_parts = 0;
//       for (let i = 0; i <=9;i++) {    //? looping thru 0 a thru j
//         let n = String.fromCharCode(97 + i);
//         let selector = `#question_option_${n}`
//           if(child_nodes[0].querySelector(selector)){
//             num_parts ++;
//             options[i]= child_nodes[0].querySelector(selector).innerHTML;
//             options[i] = options[i].replaceAll('##','');
//           options_str[i] = options[i].replace(/<[^>]+>/g, '');
//     }

// }
// console.log("options",options);
// console.log("options_str",options_str);
// console.log("num_parts",num_parts);



// //? get the student responses 

//   let quick1_response_card_number =e.target.id.split('_')[1];
//   const quick1_response_offcanvas = new bootstrap.Offcanvas('#quick1_response')  // creates the offcanvas instance from the html element with the given ID
 
//   quick1_response_offcanvas.show();

//   document.getElementById('selected_class_name_holder2').innerText = selected_sendcourse;  
//       document.getElementById('selected_class_id_holder2').innerText = current_classes.querySelector('input[name ="currentclass"]:checked').getAttribute('data-ccid');  
//       document.getElementById('selected_question_id_holder2').innerText = quick1_response_card_number;  

  
//   console.log('quick1_response_card_number',quick1_response_card_number);
//   // selected_sendcourse = localStorage.getItem('selected_sendcourse');
//   console.log('selected_sendcourse',selected_sendcourse);
//   console.log('iid',iid);
//  // localStorage.clear();





//  localStorage.setItem('sendcourse',selected_sendcourse);
//  localStorage.setItem('question_id',quick1_response_card_number);




//   fetch('get_quick1_question_response.php',{method: 'POST',
//           headers: {
                
//                 "Content-Type": "application/json",
//                 "Accept":"application/json, text/plain, */*"
//             },
//             body: JSON.stringify({iid:iid, course:selected_sendcourse, question_id:quick1_response_card_number}),
//         })
//         .then((res1) => res1.json())
//         .then((responses)=>{
//           console.log('response from get_quick1_question_response',responses);
//          let data_for_1 = [];
//          let data_for_2 = [];
//          let count =0;

//           for(let i=0;i<num_parts; i++){
//             count =0;
//             responses.forEach((response)=>{
//               for (let j = 0; j < response.response_st.length; j++) {
//                 // console.log('response.response_st.charAt(j): ',response.response_st.charAt(j))
//                 if(response.response_st.charAt(j) == String.fromCharCode(i+97) && response.try_number==0){
//                  count += parseInt(response.count);
//                 }
//               }
//             })
//             data_for_1.push(count);
//           }
//           for(let i=0;i<num_parts; i++){
//             count =0;
//             responses.forEach((response)=>{
              
//               for (let j = 0; j < response.response_st.length; j++) {
//                //   console.log(response.charAt(i));
              
//               if(response.response_st.charAt(j) == String.fromCharCode(i+97) && response.try_number==1){
//                 count += parseInt(response.count);
//                 }
//               }
//             })
//             data_for_2.push(count);
//           }
//           console.log('data_for_1',data_for_1)
//           console.log('data_for_2',data_for_2)

//           // let initial_response=[];
//           let data1_flag = true;
//  //? draw the chart in the

//           const labels = options_str;
    
//   const chart_data = {
//                 labels: labels,
//                 datasets: [{
//                   label: 'Initial Response',
//                   backgroundColor: 'rgb(0, 73, 153)',
//                   borderColor: 'rgb(0, 73, 153)',
//                   data: data_for_1,
//                 }]
//               };

//     const config = {
//       type: 'bar',
     
//       data: chart_data,
//       options: {indexAxis: 'y',   }
//     };

//     const myChart = new Chart(
//     document.getElementById('quick1_chart'),
//     config
//   );

//   let quick1_response = document.getElementById('quick1_response');
//     quick1_response.addEventListener('hidden.bs.offcanvas',  () => {
//       discussion_start.classList.remove("hide")
//           allow_2nd_submit.classList.add("hide")

//     myChart.destroy();
//   })




//   toggle_chart.addEventListener('click',()=>{
//     console.log('chart_data.datasets[0].data1',chart_data.datasets[0].data)
//     if (data1_flag){
//       data1_flag = false;
//       chart_data.datasets[0].data = data_for_2;
//       chart_data.datasets[0].label = "Response After Discussion";
//       chart_data.datasets[0].backgroundColor = 'rgb(0, 72, 0)';
//       chart_data.datasets[0].borderColor = 'rgb(0, 72, 0)';


//     } else {
//       data1_flag = true;
//       chart_data.datasets[0].data = data_for_1;
//       chart_data.datasets[0].label = "Initial Response";
//       chart_data.datasets[0].backgroundColor = 'rgb(0, 73, 153)';
//       chart_data.datasets[0].borderColor = 'rgb(0, 73, 153)';

//     }
//     myChart.update();
//     console.log('chart_data.datasets[0].data2',chart_data.datasets[0].data)


// })


//          })



//     }) // for response button???? end add event listener

// }) //! original end of send_control addevent listener
// // })  //! original end of courselist add event listener
//! end of function








function filterCardsbyCheckbox(){
// console.log(selected_current_course);
course = localStorage.getItem('course');
  cards_section.innerHTML = '';
  fetch('getConceptsForRepo.php',{method: 'POST',
    headers: {
        
        "Content-Type": "application/json",
        "Accept":"application/json, text/plain, */*"
    },
      body: JSON.stringify({iid:iid, course:course}),
  })
  .then((res) => res.json())
  .then((data) =>{
      let checks = '<div class = "checksbox-container" id = "checksbox_container">';

      data.forEach((concept) =>{
        checks +=`
        <input type="checkbox" id = "${concept.concept_name}" class = "checkbox ms-3" value = "${concept.concept_name}"> ${concept.concept_name} </input>
      `
   })
   checks += '</div>';
  
   concept_checks.innerHTML = checks;
   let checksbox_container = document.getElementById('checksbox_container');
   checksbox_container.addEventListener('click',(e)=>{
    // console.log("click");
    let checked_concepts =[];
    let check_boxes = checksbox_container.childNodes;
      for (let i=0;i<check_boxes.length;i++){
        if (check_boxes[i].checked){
          checked_concepts.push(check_boxes[i].value);
        }
   }
  //  console.log (checked_concepts);
   const card_elements = card_container.querySelectorAll('.card');

        for (let i = 0; i < card_elements.length; i++) {
        let p_concept = card_elements[i].querySelector(".primary-concept").textContent;

        if (checked_concepts.includes(p_concept)) {
          
          card_elements[i].classList.remove("hide");

        
        } else {
          card_elements[i].classList.add("hide");
        }
        
      }
    })
  })
  
}

//  console.log(course_list);




  //? function that gets the student response data from the QQA table and put it in the right sidebar (off canvas)
  display_chart.addEventListener('click',() =>{

  let question_send_id = localStorage.getItem('question_id');
  let send_course_name = localStorage.getItem('sendcourse');
  let course_question = localStorage.getItem('course');

// start the discussion stage for the question
  // discussion_start.click();
  display_chart.disabled = true;
    window.open(`question_show_response.php?question_id=${question_send_id}&course=${course_question}&currentcourse=${send_course_name}&iid=${iid}`, '_blank');
  })






function getTheCards () {
 currentclass_id = document.getElementById('currentclass').querySelector('input[name ="currentclass"]:checked').getAttribute('data-ccid');
//  console.log ("currentclass 18 Aug 2022",currentclass_id)
// get all of the cards

// console.log ('iid: ',iid,'course: ',course,'discipline_name: ',discipline_name,'currentclass_id: ',currentclass_id);

fetch('getQuestionsForRepo.php',{method: 'POST',
    headers: {
        
        "Content-Type": "application/json",
        "Accept":"application/json, text/plain, */*"
    },
    body: JSON.stringify({iid:iid, course:course, discipline_name:discipline_name,currentclass_id:currentclass_id}),
})
.then((res) => res.json())
.then((data) =>{
  //? build the card
     cards = '<div class="cards" id = "cards" >';
    data.forEach((question) =>{
        const author = question.nm_author ? 'by: '+question.nm_author : '';
        const author_class = question.nm_author ? 'card-footer' : '';
        let html_fn = question.htmlfilenm;
        if(html_fn.indexOf('.htm') == -1){
          html_fn += '.htm';
        }
        html_fn = 'uploads/'+html_fn;
         cards += `
               <div class = "card" id = "card_${question.question_id}">
               <div class="card-header id">${question.question_id} <button id = "${question.question_id}" class = "btn btn-outline-secondary btn-sm position-absolute top-0 end-0" data-bs-toggle="offcanvas" data-bs-target="#quick_send" aria-controls="quickSendSidebar">Quick Send</button></div>
                  <h6 class = "title card-title">${question.title}</h6>
                
                    
                  <div class = "card-body">
                  <div class = "preview"><iframe class ="m-0" src ="${html_fn}"   style="border:none;" ></iframe></div>
                      <p class = "primary-concept">${question.primary_concept}</p>
                      <p class = "author ${author_class}" > ${author}</p>
                  </div>
               </div>   
            `;
    })
    cards += '</div>';

    let card_element = document.createElement('DIV');
    card_element.innerHTML =cards;
    
    cards_section.append(card_element);


    })
  }  //! end get the cards function

  // })

  discussion_start.addEventListener('click', ()=>{

     question_send_id = localStorage.getItem('question_id');
     send_course_name = localStorage.getItem('sendcourse');
     course_question = localStorage.getItem('course');
    // console.log ('iid',iid)
    // console.log('discussion start question id: ',question_send_id);
    // console.log('discussion selected_send_courrse',send_course_name);
    // console.log('discussion selected_send_courrse',course_question);
//? make an entry for each student in the table where the try_number is 1 and the discussion flag is still 1 in qqa table

      fetch('get_insert_quickquestion_trynum.php',{method: 'POST',
          headers: {
              
              "Content-Type": "application/json",
              "Accept":"application/json, text/plain, */*"
          },
          body: JSON.stringify({iid:iid, course:send_course_name, question_id:question_send_id}),
      })
      .then((res) => res.json())
      .then((data) =>{
          // console.log('data',data);
          discussion_start.classList.add("hide")
          allow_2nd_submit.classList.remove("hide")
      })
  })


  // just update the last entry and make discuss stage to 2 (allow submit)
    allow_2nd_submit.addEventListener('click',()=>{
      question_send_id = localStorage.getItem('question_id');
     send_course_name = localStorage.getItem('sendcourse');
     course_question = localStorage.getItem('course');

    // console.log ('iid',iid)
    // console.log('discussion start question id: ',question_send_id);
    // console.log('discussion selected_send_courrse',send_course_name);
    // console.log('discussion selected_send_courrse',course_question);


    fetch('get_update_quickquestion_discuss_stage.php',{method: 'POST',
          headers: {
              
              "Content-Type": "application/json",
              "Accept":"application/json, text/plain, */*"
          },
          body: JSON.stringify({iid:iid, course:send_course_name, question_id:question_send_id}),
      })
      .then((res) => res.json())
      .then((data) =>{
          // console.log('data',data);
          allow_2nd_submit.classList.add("hide")
      })


      
    })



  //? get the response data and question labels for the question at hand using the fetch method




</script>


</body>

