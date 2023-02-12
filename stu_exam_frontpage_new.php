<?php
	require_once "pdo.php";
    session_start();

// The purpose of this file is to display the problems for the Exam and any feedback and score the instructor provides.  It is initially called by QRExamRegistration2 but will be the place to
// That each problem will come back to.  It will QRdisplayExamPblm from the radio buttons.  Started with stu_frontpage which serves the same function for the homework assignment
// but added elements from QRexam that was the old file that did this.

// hang on to the eregistration_id initially it will come in on a Get from QRExamRegistration2.php

if (isset($_GET['eregistration_id'])){
    $eregistration_id =   $_GET['eregistration_id'];
 } elseif (isset($_POST['eregistration_id'])){
    $eregistration_id =   $_POST['eregistration_id'];
} else {
        $_SESSION['error'] = 'lost the eregistration_id in stu_exam_frontpage';
        header('Location:  QRExamRegistration1.php');
       die;
}

// echo 'eregistration_id: '.$eregistration_id;
// find out about the exam  this could check if it is a 
$sql = 'SELECT Eexamnow.eexamnow_id AS eexamnow_id, globephase, Eexamnow.eexamtime_id AS eexamtime_id, Eexamnow.exam_code AS exam_code,
               Eregistration.student_id AS student_id,dex, iid, currentclass_id,nom_time,game_flag,exam_num,last_name,first_name,university,currentclass_id
 FROM Eexamnow LEFT JOIN Eregistration ON Eexamnow.eexamnow_id = Eregistration.eexamnow_id
 LEFT JOIN Student ON Eregistration.student_id = Student.student_id
 LEFT JOIN Eexamtime ON Eexamnow.eexamtime_id = Eexamtime.eexamtime_id
 WHERE Eregistration.eregistration_id = :eregistration_id AND Eexamnow.globephase < 3';
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
  ':eregistration_id' => $eregistration_id,
  ));
  $big_data = $stmt -> fetch(PDO::FETCH_ASSOC);
 //var_dump($big_data);

if (!$big_data) {
    $_SESSION['error'] = 'big_data was not found or exam has ended';
    header('Location:  QRExamRegistration1.php');
    die;
} 

    $iid = $big_data['iid'];
    $globephase = $big_data['globephase'];
    $game_flag = $big_data['game_flag'];
    $eexamnow_id = $big_data['eexamnow_id'];
    $student_id = $big_data['student_id'];
    $currentclass_id = $big_data['currentclass_id'];
    $exam_num = $big_data['exam_num'];
    $stu_name = $big_data['first_name'].' '.$big_data['last_name'];   
    $currentclass_id = $big_data['currentclass_id'];
    $eexamtime_id = $big_data['eexamtime_id'];
    $exam_alias_number = $big_data['exam_num'];
    $nom_time = $big_data['nom_time'];
    $university = $big_data['university'];
  //  echo ('student_id: '.$student_id);


  
  $sql = ' SELECT `name`
    FROM `CurrentClass` WHERE `currentclass_id` = :currentclass_id ';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
    ':currentclass_id' => $currentclass_id
    ));
    $currentclass_data = $stmt->fetch();
    
     $currentclass_name = $currentclass_data['name'];

    $sql = ' SELECT *
    FROM `Eexamtime` WHERE `eexamtime_id` = :eexamtime_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
    ':eexamtime_id' => $eexamtime_id,
    ));
    $eexamtime_data = $stmt->fetch();
  
 
    // how many and which problem are on this Exam?  - what if I gave it more than one time?

 $sql = ' SELECT DISTINCT ( problem_id), alias_num
    FROM `Eexam` WHERE `iid` = :iid AND `exam_num` = :exam_num AND `currentclass_id` = :currentclass_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
    ':iid' => $iid,
    ':exam_num' => $exam_num,
    ':currentclass_id' => $currentclass_id
    ));
    $eexam_data = $stmt->fetchAll();



    $sql = ' SELECT COUNT( DISTINCT `problem_id`)
    FROM `Eactivity` WHERE eregistration_id = :eregistration_id ';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
    ':eregistration_id' => $eregistration_id,
   
    ));
    $eactivity_count = $stmt->fetch();
    $eactivity_count = $eactivity_count[0];
    // echo ' eactivity_count '.$eactivity_count;



   $sql = ' SELECT DISTINCT (`problem_id`), `P_num_score_net`, `ec_pts`, `alias_num` 
   FROM `Eactivity` WHERE eregistration_id = :eregistration_id ORDER BY `eactivity_id` DESC LIMIT '.$eactivity_count;
  //  $sql = ' SELECT `problem_id`, `P_num_score_net`, `ec_pts`, `alias_num` 
  //  FROM (SELECT `problem_id`, `P_num_score_net`, `ec_pts`, `alias_num` FROM `Eactivity` WHERE eregistration_id = :eregistration_id ORDER BY `eactivity_id` DESC LIMIT 2) ORDER BY `alias_num` ASC';


    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':eregistration_id' => $eregistration_id,
     
   
    ));
    $eactivity_data = $stmt->fetchAll();

    $eactivity_data = array_reverse($eactivity_data);
    
    
    // pass the $activity_data as an array into javascript

    $eactivity_data_pass = json_encode($eactivity_data);
   // echo the script tag and the array
    echo ('<script>let eactivity_data = '.$eactivity_data_pass.';</script>');

     foreach($eactivity_data as $eactivity_datum){

        $p_num_score_net = $eactivity_datum['P_num_score_net'];
    //    echo 'p_num_score_net '.$p_num_score_net;
     }

    $active_problem_number = count($eactivity_data);

// $_SESSION['error'] = 'active_problem_number: '.$active_problem_number.' student_id: '.$student_id.' eregistration_id: '.$eregistration_id.' eexamnow_id: '.$eexamnow_id;

  // echo 'active_problem_number: '.$active_problem_number;
    //  echo ('eregistration_id: '.$eregistration_id);
//   echo ('eexamnow_id: '.$eexamnow_id);
    if ($active_problem_number == 0) {
        foreach($eexam_data as $eexam_datum){
            $sql = "INSERT INTO `Eactivity` 
                        ( `student_id`,  `alias_num`, `eexamnow_id`, `eregistration_id`,`currentclass_id`,`problem_id`) 
                VALUES  ( :student_id, :alias_num,   :eexamnow_id,   :eregistration_id,    :currentclass_id,  :problem_id)";
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                    ':student_id'=> $student_id,
                    ':alias_num' => $eexam_datum['alias_num'],
                    ':eexamnow_id' => $eexamnow_id,
                    ':eregistration_id' => $eregistration_id,
                    ':currentclass_id' => $currentclass_id,
                    ':problem_id' => $eexam_datum['problem_id'],
                    )
                    );
              }

          }

        // display the problem we just need to know the submit button was pressed and the problem that was selected
          if(isset($_POST['submit'])){

            header('Location:QRdisplayExamPblm.php?eregistration_id='.$eregistration_id.'&problem_id='.$_POST["alias_num"]);
            die;

          }
   
        ?>


<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRExam</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">


<style>
  .btn-group-line{
   display: inline;
  }
  .hidden{ 
    display: none;
  }
  .disable{ 
    opacity: 0.5;
    pointer-events: none;
  }
table.main_table{
  table-layout: fixed;
  width: 100%;  
}
 
#feedback-container{
    display:flex;
}
.card-col{ 
  align-self: normal;
}
 

</style>
</head>

<body>
<header>
<h1> Quick Response Game/Exam </h1>
</header>

<?php

//	if(isset($_POST['pin']) || isset($_POST['problem_id']) || isset($_POST['iid'])){
		if ( isset($_SESSION['error']) ) {
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) ) {
			echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
			unset($_SESSION['success']);
		}
//	}
 
?>

<form name = "go-on-get" action="stu_getclass.php" method = "post" id="go-on-get">
            <input type = "hidden" name = "student_id" value = "<?php echo ( $student_id)?>" >
        </form>


<form id = "big_form" autocomplete="off" method="POST" >
	  
	<h5 >Name: <?php echo($stu_name);?></h5> 
	<input autocomplete="false" name="hidden" type="text" style="display:none;">
	
    <input type="hidden" id = "iid" name="iid" value="<?php echo ($iid);?>" > 
    <input type="hidden" id = "cclass_id" name="cclass_id" value="<?php echo ($currentclass_id);?>" >
    <input type="hidden" id = "stu_name" name="stu_name" value="<?php echo ($stu_name);?>" >
	<input type="hidden" id = "student_id" name="student_id" value="<?php echo ($student_id);?>" >
<!--	<div id ="current_class_dd">	-->
			<!-- <font color=#003399>Course: </font> -->
			

       <section id="question-cards" class="mt-1">
           <div id ="q-card-container" class = "feedback-container container-lg ms-2" >

               <div id = "q-alias_num_div" class="row my-5 align-items-center justify-conent-center">

                   <br>
                   <div id="qfiles_section">
                   </div>
               </div>
           </div>
       </section>


       <section id="problem-cards" class="mt-1">
           <div id ="p-card-container" class = "feedback-container container-lg ms-2" >




               <div id = "p-alias_num_div" class="row my-5 align-items-center justify-conent-center">

                   <br>
                   <div id="files_section">
                   </div>
               </div>
           </div>
       </section>
        
		
	<!-- <p><input type = "submit" name = "submit" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>   -->
	<!--  need to figure out which homeworks had reflections and are past the due date but before the date that they closes and needs rated -->
   
 
    </div>
    
  
    </form>
  

     <script>


console.log('eactivity_data', eactivity_data);
let activealias = eactivity_data
number_problems = eactivity_data.length;
let alias_nums = [];
let problem_id ='';

const p_card_container = document.getElementById('p-card-container');
const q_card_container = document.getElementById('q-card-container');


                for (i=0;i<number_problems;i++){  //! n here is the number of problems so in the card system this would be the number of cards
                                let problem_id = eactivity_data[i]['problem_id'];
                               
                                console.log('problem_id', problem_id);
                                alias_nums[i] = eactivity_data[i]['alias_num'];
                                let alias_number =eactivity_data[i]['alias_num'];
                             let card_html = '' 
                             let card = document.getElementById("alias_num_div");
                            let col_div  = document.createElement('div');
                            col_div.className = "card-col col-8 col-md-6 col-lg-4 g-2 ";
                           let inner_card = document.createElement('div');
                            inner_card.className = "card-body text-center py-2 px-0 bg-light ";
                            
//!                       card.innerHTML += '<div class = "col-5 col-lg-4 col-xl-3"> <div class = "card"> <div class ="card-body text-center py-4"> <button id = "problem-btn-'+activealias[i]+'" class = "btn btn-primary" type = "submit"  name = "submit" value = "'+activealias[i]+'"> Problem '+activealias[i]+'</button> <div id = "provisional-pts_'+activealias[i]+'"> </div><div id = "extra-credit-pts_'+activealias[i]+'"> </div><div id = "late-penalty_'+activealias[i]+'"> </div><div id = "survey-pts_'+activealias[i]+'"> </div></div> </div> </div>';
//!                          card.innerHTML += '<div class = "col-5 col-lg-4 col-xl-3"> <div class = "card"> <div class ="card-body text-center py-4"> <button id = "problem-btn-'+activealias[i]+'" class = "btn btn-primary" type = "submit"  name = "submit" value = "'+activealias[i]+'"> Problem '+activealias[i]+'</button> <div id = "provisional-pts_'+activealias[i]+'"> </div><div id = "extra-credit-pts_'+activealias[i]+'"> </div><div id = "late-penalty_'+activealias[i]+'"> </div><div id = "survey-pts_'+activealias[i]+'"> </div></div> </div> </div>';
                            
                            let card_element=document.createElement('div');
                            card_element.className = "card";


                            let card_body=document.createElement('div');
                            card_body.id = "card-body_"+problem_id;
                            card_body.className = "card-body text-center py-4  d-flex flex-column ";
                            card_html += `
                                  <div class = "card overflow-hidden " id = "card_${problem_id}">
                                  <div class="card-header"> <span class = "position-absolute top-0 start-0"> Problem: ${alias_number} </span> <span class = "" style = 'font-size: 0.5rem; font-weight: bold'> problem_id ${problem_id} </span>
                                  <span class = "problem_info">
                                  </span> 
                                  <button id = "selectProblem_${problem_id}" class = "select_btn btn btn-outline-secondary btn-sm position-absolute top-0 end-0" >Go To Problem</button></div>
                                      <div class = "card-body" id = "card_body_${problem_id}"></div>
                                          
                                          
                                      </div>
                                  </div>   
                                  `;

                                  card_body.innerHTML = card_html;


                            
                        inner_card.appendChild(card_body) ;
                        card_element.appendChild(inner_card)
                        col_div.appendChild(card_element);
                        p_card_container.appendChild(col_div)
                        // q_card_container.appendChild(col_div)
    
                                // card.appendChild(col_div);

                                // var card_bodies = new Array();
                                // card_bodies[i] = card_body;

                                // card += `
                                //   <div class = "card overflow-hidden " id = "card_${problem_id}">
                                //   <div class="card-header id">${problem_id}
                                //   <span class = "problem_info">
                                //       <span class = "status ${problem_status} ms-2" > ${problem_status}</span> 
                                //       <span class = "grade ${grade} ms-2" > ${grade}</span> 
                                //       <span class = "specif_ref ${specif_ref} ms-2" > ${specif_ref}</span> 
                                //       <span class = "unpubl_auth ${unpubl_auth} ms-2" > ${unpubl_auth}</span> 
                                //   </span> 
                                //   <button id = "enlargeProblem_${problem_id}" class = "enlarge_btn btn btn-outline-secondary btn-sm position-absolute top-0 end-0" data-bs-toggle="modal" data-bs-target="#problemModal" data-bs-prevnext="${problem_ids[i-1]},${problem_ids[i+1]}">Enlarge</button></div>
                                //       <div class = "card-body" id = "card_body_${problem_id}">${bc_html[i]}</div>
                                          
                                //           <p class = "author ${author_class}" > ${author}</p>
                                          
                                //       </div>
                                //   </div>   
                                //   `;

                        console.log ("card_bodies "+card_bodies)
                        }


</script>


</body>
</html>
