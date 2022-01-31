<?php
require_once 'pdo.php';
session_start();

if (isset($_POST['iid'])) {
    $iid = $_POST['iid'];
} elseif (isset($_GET['iid'])) {
    $iid = $_GET['iid'];
} else {
    $_SESSION['error'] = 'invalid User_id in QRQuestionMgmt ';
    header('Location: QRPRepo.php');
    die();
}
// fix bug if no class is selected and get a pdo error____________________________________________

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_name'])) {
    // We are coming in from this file
    if (isset($_POST['currentclass_id'])) {
        $currentclass_id = $_POST['currentclass_id'];
        echo ("current_class_id=" . $currentclass_id);
    } else {
        $_SESSION['error'] = 'Please Select a Current Class ';
        header('Location: QRQuestionMgmt.php?iid='.$iid);
        die();
    }
    $new_flag = 0;

    $sql = 'SELECT questiontime_id FROM QuestionTime WHERE currentclass_id = :currentclass_id ';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':currentclass_id' => $_POST['currentclass_id'],
       
    ]);
    $questiontime_iddata = $stmt->fetch();
    if ($questiontime_iddata == false) {
        $new_flag = 1;
// we have nothing in the data table for this class
// fisrt see if they hgave set the Discipline and selected a current course or 

if (isset($_POST['discipline_id'])) {
    $discipline_id = $_POST['discipline_id'];
} else {
    $_SESSION['error'] = 'Please Select a Discipline ';
    header('Location: QRQuestionMgmt.php?iid='.$iid);
    die();
}

if (isset($_POST['current_course'])&& $_POST['current_course']!="Select Course") {
    $current_course = $_POST['current_course'];
    echo ('current_course '.$current_course);
} else {
    $_SESSION['error'] = 'Please Select a Current Course ';
    header('Location: QRQuestionMgmt.php?iid='.$iid);
    die();
}

//! Now look for all the input

// var_dump($_POST);
//! start putting stuff in the questiontime table
                // $sql = "INSERT INTO QuestionTime
                // (currentclass_id,currentcourse_id,currentdiscipline_id,start_date,stop_date,start_time,num_mon,num_tue,num_wed,num_thu,num_fri,num_sat,num_sun,grade,target_percent_current,target_percent_basic)	
				// 		VALUES (:currentclass_id,:currentcourse_id,:currentdiscipline_id,:start_date,:stop_date,:start_time,:num_mon,:num_tue,:num_wed,:num_thu,:num_fri,:num_sat,:num_sun,:grade,:target_percent_current,:target_percent_basic)";
				// 	$stmt = $pdo->prepare($sql);
				// 	$stmt->execute(array(
				// 		':currentclass_id'=>  $currentclass_id,
                //         ':currentcourse_id' =>  $current_course,
                //              ':currentdiscipline_id' => $discipline_id,
                //              ':start_date' => $_POST['global_start_date'],
                //              ':stop_date' => $_POST['global_end_date'],
                //              ':start_time' =>$_POST['global_start_time'],
                //              ':num_mon' => $_POST['num_mon'],
                //              ':num_tue' => $_POST['num_tue'],
                //              ':num_wed' => $_POST['num_wed'],
                //              ':num_thu' => $_POST['num_thu'],
                //              ':num_fri' => $_POST['num_fri'],
                //              ':num_sat' => $_POST['num_sat'],
                //              ':num_sun' => $_POST['num_sun'],
                //              ':grade' => $_POST['grade'],
                //              ':target_percent_current' => $_POST['target_percent_current'],
                //              ':target_percent_basic' => $_POST['target_percent_basic']
				// 		));



    } else {

        $stmt = "SELECT *
        FROM QuestionTime JOIN QuestiontimeConceptConnect
    
        ON QuestiontimeConceptConnect.questiontime_id = QuestionTime.questiontime_id 
        
        WHERE  QuestionTime.questiontime_id = :questiontime_id";
            $stmt = $pdo->prepare($stmt);	
            $stmt->execute(array(
                ':questiontime_id' => $questiontime_id,
            ));
            $questiontime_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
             var_dump($questiontime_data); //! replace this once we have something in the data tables
        }
    
    
}

// this is called from the main repo and this will Collect the information on a particular assignment from the instructor then moves onto .  THis file was coppied form QRExamStart.php

/* 		
			$alias_num = $exam_num = $cclass_id = '';   
			
			
            $sql_stmt = "SELECT * FROM Exam WHERE DATE(NOW())<= exp_date AND iid = :iid order by exam_id";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt -> execute(array(':iid' => $iid));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 */

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
/* 
.inner {
  margin-left: 50px;
  
} 
*/

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

<h4>Target Number of Question to Deliver:</h4>
<label for = "num-mon">Monday</label>
<input type = "number" min = "0" max = "99"  name = "num_mon" id = "num-mon" class - "num-Q-per_day input-group-number p-2 me-2" value = "2" ></input> &nbsp;
<label for = "num-tue">Tuesday</label>
<input type = "number" min = "0" max = "99"  name = "num_tue" id = "num-tue" class - "num-Q-per_day input-group-number" value = "2" ></input> &nbsp;
<label for = "num-wed">Wednesday</label>
<input type = "number" min = "0" max = "99"  name = "num_wed" id = "num-wed" class - "num-Q-per_day input-group-number p-2 me-2" value = "2" ></input> &nbsp;
<label for = "num-thu">Thursday</label>
<input type = "number" min = "0" max = "99"  name = "num_thu" id = "num-thu" class - "num-Q-per_day input-group-number" value = "2" ></input> &nbsp;
<label for = "num-fri">Friday</label>
<input type = "number" min = "0" max = "99"  name = "num_fri" id = "num-fri" class - "num-Q-per_day input-group-number" value = "2" ></input> &nbsp;
<label for = "num-sat">Saturday</label>
<input type = "number" min = "0" max = "99"  name = "num_sat" id = "num-sat" class - "num-Q-per_day input-group-number p-2 me-2" value = "0" ></input> &nbsp;
<label for = "num-sun">Sunday</label>
<input type = "number" min = "0" max = "99"  name = "num_sun" id = "num-sun" class - "num-Q-per_day input-group-number" value = "0" ></input> &nbsp;
<br>
<br>
<h4>Delivery Timing:</h4>
<label for = global_start_date>Global Start Date </label>
<input type = "date" name = "global_start_date" id = "global_start_date" class = "form-control-inline m-3" value = "<?php echo( date("Y-m-d"));?>"></input>
<label for = global_end_date>Global End Date </label>
<input type = "date" name = "global_end_date" id = "global_end_date" class = "form-control-inline m-3" value = "<?php echo( date("Y-m-d", strtotime("+4 months", strtotime(date("Y-m-d")))));?>"></input>
<label for = global_start_time>Time to Email First Question</label>
<input type = "time" name = "global_start_time" id = "global_start_time" class = "form-control-inline m-3" value = "08:00"></input>
<br>
<h4>Participants:</h4>
<div id ="current_class_dd">	
				Current Class: &nbsp;
				<select name = "currentclass_id" id = "currentclass_id">
				 <option value = "" selected disabled hidden > Select Current Class  </option> 
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
    <input type = "number" min = "1" max = "4" name = "grade" id = "grade" class = "form-control-inline m-3" value = "4"></input>
    <label for = target_percent_current>Target Percentage of Current Questions</label>
    <input type = "number" min = "0" max = "100" name = "target_percent_current" id = "target_percent_current" class = "form-control-inline m-3" value = "100"></input>
    <label for = target_percent_basic>Target Percentage of Basic Level Questions </label>
    <input type = "number" min = "0" max = "100" name = "target_percent_basic" id = "target_percent_basic" class = "form-control-inline m-3" value = "100"></input>

        <br>

<h4>Concepts Covered:</h4>

    <div id ="discipline_id">	
				Discipline: &nbsp;
				<select name = "discipline_id" id = "discipline-id">
				 <option value = "" selected disabled hidden > Select Discipline</option>  
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
			<p><input type="hidden" name="where_from" id="where_from" value="QRQuestionMgmt" ></p>
			<p><input type = "submit" name = "submit_name" id = "submit_id" value = "Submit"></p><hr><br>
	</form>
<!-- 
    <p style="font-size:100px;"></p>   
            <a href ="see_all_assignments.php?iid=<?php echo $iid?>"target="_blank"><button>See All Your Assignments in New Tab</button></a> -->

  <p style="font-size:20px;"></p>   
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	
	<script>

 $(document).ready(function(){


function addPastClasses (){
  //  console.log ("addPastClasses");
//? this one just takes off the hidden class from the past classes checkboxes
        let past_courses= document.getElementById("past-courses")
            past_courses.classList.remove("hide");


            const past_course = document.querySelectorAll(".past-course");
     //       console.log ("past_course",past_course);
            for (let i of past_course) {
            i.addEventListener("click", (e) => {
                if (e.target.classList.contains("past-course")) {
                    console.log ("e",e.target);
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
                //        past_concept[j].classList.remove("hide")
                  //      console.log(j);

                  }
                }
            })
            }

    }




    $("#discipline-id").change(function(){
        let add_past_btn = document.getElementById('add-past-btn');
        add_past_btn.classList.remove("hide");
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
						$('#current-course').append('<option>' + course.course_name + '</option>') 
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

                                $('#past_course_id-'+course.course_id).append('<span class = "hide oldConcept-'+course.course_id+'"  >&nbsp;<input name = "past_concept_id-'+course.course_id+'-'+concept.concept_id+'" id = "past_concept_id-'+concept.concept_id+'" class = "form-check-input m-2 mt-3" type = "checkbox">' + concept.concept_name + '</input>&nbsp;<input type = "date" name = "past_concept_date-'+course.course_id+'-'+concept.concept_id+'" class = "form-control-inline m-2" value= "'+now+'"></input><br></span>') 
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
				
				var course = $("#current-course").val();
				$.ajax({
					url: 'ccData.php',
					method: 'post',
					data: 'course=' + course
				}).done(function(p_concept){
					 concept = JSON.parse(p_concept);
             //        console.log (concept);
                    let pp_concept = document.getElementById('p_concept');
                    $('#p_concept').append('<button type = "button" class="btn btn-primary mt-3 mb-2" id = "check-all-btn"> Toggle Checkboxes </button> <span>  Note - you can adjust start date for each concept</span><br>') 
                    const check_all_btn = document.querySelector('#check-all-btn');
                    check_all_btn.addEventListener('click',check)

					concept.forEach(function(concept){
                        let now =new Date();
                            now=now.toISOString().substring(0,10);
            //            console.log("now",now);
						$('#p_concept').append('&nbsp;<input name = "current_concept_id-'+course+'-'+concept.concept_id+'" id = "current_concept_id-'+course+'-'+concept.concept_id+'" class = "form-check-input m-2 mt-3" type = "checkbox">' + concept.concept_name + '</input>&nbsp;<input name = "current_concept_date-'+course+'-'+concept.concept_id+'" id = "current_concept_date-'+course+'-'+concept.concept_id+'" type = "date" class = "form-control-inline m-2" value= "'+now+'"></input><br>') 
					 })
				})

			})







})

	
</script>	

</body>
</html>



