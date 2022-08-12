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
// var_dump($courses);

$stmt = "SELECT name
			FROM CurrentClass
			WHERE iid =:iid"; 
			$stmt = $pdo->prepare($stmt);	
			$stmt->execute(array(':iid' => $iid));
			$cclasses = $stmt->fetchAll(PDO::FETCH_COLUMN);

$courses_list = $cclasses;
// loop thru the typical courses array and if it is not in the courses_list add it
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
      height:120%;
      width: 350px !important;
      margin: 0 !important;
      padding: 0;
      position: relative;
      
    }
    iframe{
      /* height:100%;
      width:300px !important; */
      transform: scale(0.45);
  position: absolute;
  left: -120px !important;
  top: -100px !important;
  padding: 0 !important;
  margin-left: 0 !important;
    }
    #course_list{
      display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    .hide{ 
      display: none;
    }
   .emailed{
    background-color: lightyellow !important;
   }

   

</style>



</head>

<body>
  <nav>
  <a id = "back_to_problem_repo" class="btn btn-outline-primary btn-sm m-2" href="#"role="button">Problem Repository</a>
  <!-- <a id = "back_to_problem_repo" class="btn btn-outline-primary" href="QRPRepo.php?iid=<?php echo$iid; ?>"role="button">Problem Repository</a> -->
  </nav>


<header>
    <h1>Quick Response Questions</h1>
<?php
	if ( isset($_SESSION['error']) ) {
		echo '<h3 style="color:red">'.$_SESSION['error']."</h3>\n";
		unset($_SESSION['error']);
	}
?>

<form>
    <!-- <input type="hidden" id="course" value="Material Balances"> -->
    <div class="form-group" id = "course_list">
    <?php 
    
    foreach ($courses_list as $course){
      echo '<div class = "form-check">
      <input type = "radio" class = "form-check-input course-list" id = "'.$course.'" name = "course" value = "'.$course.'"></input>
      <label for="'.$course.'" class = "form-check-label">'.$course.'</label>
      </div>
      ';
    }
    ?>
    </div>


    <input type="hidden" id="iid" value="<?php echo($iid);?>">
    <input type="hidden" id="discipline_name" value="<?php echo($discipline_name);?>">
    <br><hr>

    <section class = "concept-checks" id = "concept_checks">

</section>
<hr>



<div id="quick_send" class = "offcanvas offcanvas-start" aria-labelledby="offcanvasQuickSend">
<div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasQuickSend">Quick Send</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div>
      Pick the class that you would like the question emailed
    </div>
      <div class = "current-classes" id = "current_classes">
      <?php 
    
    foreach ($cclasses as $course){
      echo '<div class = "form-check">
      <input type = "radio" class = "form-check-input course-list send-course" id = "'.$course.'" name = "sendcourse" value = "'.$course.'"></input>
      <label for="'.$course.'" class = "form-check-label">'.$course.'</label>
      </div>
      ';}
      ?>
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
      <button id = "display_chart" type="button" class="btn btn-outline-secondary ms-1">Create Chart in Separate Tab</button>
    </div>

          <div>
          <canvas id="quick1_chart"></canvas>
        </div>
        

  </div>
</div>



<div class="container-fluid">

   <div id="card_container" class = "card-container'">
      <div id = "cards_control"></div>



        <div id = "cards_section"></div>

   <!-- <input class="fuzzy-search" data-search="title" placeholder="Search Name" /> -->

   </div>     

 
</div>
</form>

<script>

const iid = document.getElementById('iid').value;
// const course = document.getElementById('course').value;
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
//const quick1_chart_ctx = document.getElementById('quick1_chart').getContext('2d');

let selected_sendcourse = '';
let question_id;
const offcanvasQuick1Response = document.getElementById('offcanvasQuick1Response');

//! listener is on the entire section and then just reutrns if it not a button

cards_section.addEventListener('click', (event) => {
  const isButton = event.target.nodeName === 'BUTTON';
  if (!isButton) {
    return;
  }
  event.preventDefault();
   question_id = event.target.id
})


course_list.addEventListener("click",(e)=>{
  course = e.target.value;
   console.log(course);

   localStorage.setItem('course', course);

  //? select the same course on the sidebar radio button list if available_funds
  const quick_send = document.getElementById('quick_send');
   let selected_current_course = quick_send.querySelector(`input[value="${course}"]`)?quick_send.querySelector(`input[value="${course}"]`):'';
   if(selected_current_course!=''){selected_current_course.setAttribute('checked',true)};
   
   
  //  make_active_question_button.addEventListener("click",()=>{
  //   selected_sendcourse = current_classes.querySelector('input[name ="sendcourse"]:checked').value;
  //   //   console.log('make_active_question_button clicked')
  //   //   console.log('the course is:',course);
  //   // console.log('the selected_sendcourse  is:',selected_sendcourse);
  //   // console.log('question_id',question_id);
  //  //  localStorage.clear();
  //    localStorage.setItem('selected_sendcourse',selected_sendcourse);
  //    selected_sendcourse =localStorage.getItem('selected_sendcourse');
  //    console.log('the selected_sendcourse  is now:',selected_sendcourse);
  //    fetch('quick_send_active.php',{method: 'POST',
  //   headers: {
        
  //       "Content-Type": "application/json",
  //       "Accept":"application/json, text/plain, */*"
  //           },
  //           body: JSON.stringify({iid:iid, course:selected_sendcourse, question_id:question_id}),
  //       })
  //       .then((res) => res.json())
  //       .then((return_data) =>{
  //         console.log(return_data);
  //       })

  //   })

    // function sendMail(email_flag){

      //! put event listener on the div container both the send email button and the make question active buttons

      send_control.addEventListener('click',(e)=>{
     let   email_flag = false;
        if (e.target.id == 'send_email_button'){
          email_flag = true;
        } 
        console.log('e_target in send mail',e.target.id);
        console.log('email flag',email_flag);
   selected_sendcourse = current_classes.querySelector('input[name ="sendcourse"]:checked').value;

    //? get the current cuouse selected from the sidebar and the card that was sent

    console.log('the course is:',course);
    console.log('the selected_sendcourse  is:',selected_sendcourse);
    console.log('question_id',question_id);
   
    //? send the email using fetch
    fetch('email_quick_send.php',{method: 'POST',
    headers: {
        
        "Content-Type": "application/json",
        "Accept":"application/json, text/plain, */*"
    },
    body: JSON.stringify({iid:iid, course:selected_sendcourse, question_id:question_id, email_flag:email_flag}),
})
.then((res) => res.json())
.then((data) =>{
    console.log(data);
    document.getElementById(question_id).click();  //? after you quick send the email send them back to the main page
    document.getElementById(question_id).classList.add("hide")


    //? change the background of the card that you emailed and add a show results button
    document.getElementById("card_"+question_id).classList.add("emailed")  //? this turns the background of this card a different color
    //? put in the buttons to show the responses from the students
    //? make the button in text format 
    let show_quick_response_button_html = `<button id = "quick-response1_${question_id}" class = "btn btn-outline-danger btn-sm position-absolute top-0 end-0"   aria-controls="quickSendSidebar">Responses</button>`

    let show_quick_response_button = document.createElement('DIV');
    show_quick_response_button.innerHTML =show_quick_response_button_html;
    document.getElementById(question_id).parentElement.appendChild(show_quick_response_button);
    show_quick_response_button.addEventListener('click',(e)=>{
    // quick1_response_offcanvas.clear();
      let selected_card = e.target.parentNode.parentNode.parentNode;
      let quick_response_card_iframe_conatiner = selected_card.querySelector('iframe');
      let q_title = selected_card.querySelector('.card-title')
      console.log('card',selected_card);
      console.log('q_title',q_title);

      let iframe = quick_response_card_iframe_conatiner.contentWindow.document;
      console.log('iframe',iframe);

        //? query the cards iframe to get the title, options and stem for the question response graph
      let child_nodes = iframe.childNodes;
      console.log('child_nodes', child_nodes);
      // let q_title = child_nodes[0];
      let q_stem_html = child_nodes[0].querySelector('#stem_text_1').innerHTML;
      let q_stem_text = child_nodes[0].querySelector('#stem_text_1').innerText;
      //  child_nodes[0].innerHTML = child_nodes[0].innerHTML.replaceAll('##',''); //! put this when you get all of the iframes
      console.log('q_stem_text',q_stem_text);
      q_stem = `<h4>Student Response to:</h4><h3> ${q_title.innerText}</h3> <h5>${q_stem_text}</h5>`;
      offcanvasQuick1Response.innerHTML=q_stem;
      console.log('title',q_title);
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
console.log("options",options);
console.log("options_str",options_str);
console.log("num_parts",num_parts);



//? get the student responses 

  let quick1_response_card_number =e.target.id.split('_')[1];
  const quick1_response_offcanvas = new bootstrap.Offcanvas('#quick1_response')  // creates the offcanvas instance from the html element with the given ID
 
  quick1_response_offcanvas.show();

  
  console.log('quick1_response_card_number',quick1_response_card_number);
  // selected_sendcourse = localStorage.getItem('selected_sendcourse');
  console.log('selected_sendcourse',selected_sendcourse);
  console.log('iid',iid);
 // localStorage.clear();
 localStorage.setItem('sendcourse',selected_sendcourse);
 localStorage.setItem('question_id',quick1_response_card_number);

  display_chart.addEventListener('click',() =>{

    let question_send_id = localStorage.getItem('question_id');
    let send_course_name = localStorage.getItem('selected_sendcourse');
    let course_question = localStorage.getItem('course');
   //  window.open('question_show_response.php?question_id='+question_send_id+'&course='+course+'&currentcourse='+selected_sendcourse+'&iid='+iid+'', '_blank');
      window.open(`question_show_response.php?question_id=${question_send_id}&course=${course_question}&currentcourse=${send_course_name}&iid=${iid}`, '_blank');
  })


  fetch('get_quick1_question_response.php',{method: 'POST',
          headers: {
                
                "Content-Type": "application/json",
                "Accept":"application/json, text/plain, */*"
            },
            body: JSON.stringify({iid:iid, course:selected_sendcourse, question_id:quick1_response_card_number}),
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
                if(response.response_st.charAt(j) == String.fromCharCode(i+97) && response.try_number==1){
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
              
              if(response.response_st.charAt(j) == String.fromCharCode(i+97) && response.try_number==2){
                count += parseInt(response.count);
                }
              }
            })
            data_for_2.push(count);
          }
          console.log('data_for_1',data_for_1)
          console.log('data_for_2',data_for_2)

          // let initial_response=[];
          let data1_flag = true;
 //? draw the chart in the

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
    myChart.destroy();
  })




  toggle_chart.addEventListener('click',()=>{
    console.log('chart_data.datasets[0].data1',chart_data.datasets[0].data)
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
    console.log('chart_data.datasets[0].data2',chart_data.datasets[0].data)


})


         })



    })

})
})
//! end of function


// console.log(selected_current_course);

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
   const card_elements = document.querySelectorAll('.card');

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
  


//  console.log(course_list);

fetch('getQuestionsForRepo.php',{method: 'POST',
    headers: {
        
        "Content-Type": "application/json",
        "Accept":"application/json, text/plain, */*"
    },
    body: JSON.stringify({iid:iid, course:course, discipline_name:discipline_name}),
})
.then((res) => res.json())
.then((data) =>{
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
                  <div class = "preview"><iframe class ="m-0" src ="${html_fn}"  width="500px" height="400px" style="border:none;" ></iframe></div>

                  <div class = "card-body">
                      <p class = "primary-concept">${question.primary_concept}</p>
                      <p class = "course">${question.course}</p>
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

  })




  //? get the response data and question labels for the question at hand using the fetch method




</script>


</body>

