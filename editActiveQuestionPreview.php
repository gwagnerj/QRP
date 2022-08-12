<?php
	session_start();
	require_once "pdo.php";
    require_once "simple_html_dom.php";
    require_once 'Encryption.php';
    require_once '../encryption_base.php';
	$_SESSION['success'] = '';


	$question_use_ar = array('1'=>'Basic Knowledge','2'=>'Basic Concept','3'=>'More Advanced Concepts','4'=>'Applications Involving Calculations');
	$question_type_ar = array('1'=>'Just Text - Single Correct','2'=>'Contains Images - Single Correct','3'=>'Multiple Correct');


//? get info from the post information from writeQuestion and put all meta data in the questionwomb table
$letters = range ('a','j');    //? this bit initializzes the correct key to zero so if we change it only one is selected

if(isset($_POST['question_id'])){
    $question_id = $_POST['question_id'];
}
elseif (isset($_GET['question_id']) && $_GET['question_id'] != '0'){ 
    $question_id = $_GET['question_id'];
} else {
    $_SESSION['error'] ='question_id lost in editActiveQuestionPreview.php';
}
if (isset($_POST['iid'])){
    $iid = $_POST['iid'];
} 
elseif (isset($_GET['iid'])){
    $iid = $_GET['iid'];
} else {
    $_SESSION['error'] ='iid lost in editActiveQuestionPreview.php';

}


// echo 'WTF';
// die();

// get the keys to mark the correct answer(s) below
	$sql = "SELECT * FROM Question WHERE question_id = :question_id";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(':question_id' => $question_id));
	$question_data = $stmt -> fetch();
	$question_type = $question_data['question_type'];  //! just text - single correct .....
	$question_use = $question_data['question_use'];  //! basic knowledge basic concept .....
	for ($i = 0; $i < 10; $i++){
		$sel = 'key_'.$letters[$i];
		$key[$i] = $question_data[$sel];
	}

    $html = new simple_html_dom();
    
 $htmlfilenm = $question_data['htmlfilenm'];
$full_htmlfilenm = 'uploads/'.$htmlfilenm.'.htm';
$html=file_get_html($full_htmlfilenm);
$html_str = $html;
$html_str = str_replace('##','',$html_str);
$html = str_get_html($html_str);


$explanation_filenm = $question_data['explanation_filenm'];
$full_explan_htmlfilenm = 'uploads/'.$explanation_filenm.'.htm';
$html_explan=file_get_html($full_explan_htmlfilenm);

if ($html_explan){
$html_explan_str = $html_explan;
// $html_explan = str_get_html($html_str);
} else {
	$html_explan_str = '';
}

// echo $html;
		
?>
<!DOCTYPE HTML>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRQuestions</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
         <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"> 

<style>
.disabled{
  pointer-events: none;
  opacity: 0.3 !important;
}
.dim{
	opacity: 0.4 !important;

}
.hide{
	display: none !important;
}
#checkbox_table{
	width: 80%;
	margin-left: 4rem;
	/* background-color: #ececec !important; */
}
#instructions{
	background-color: #ececec !important;
}


</style>



		</head>

		<body>
<div id = "btn_group ">
    <button type="button" id = "edit_btn" title = "Edit this question" class="btn btn-outline-success btn-lg ms-4">Edit This Question</button>
    <button type="button" id = "check_btn" title = "If you do not want to review this Questions " class="btn btn-outline-secondary btn-lg ms-4">Skip - Back to Questions</button>
<!-- 
	<button type="button" id = "delete_btn_activate"  title = "enable /disable the Delete button" class="btn btn-outline-danger btn-sm ms-5 ">Enable Delete</button>
	<button type="button" id = "delete_btn"  title = "Delete this question and go back to previous window" class="btn btn-outline-danger btn-lg ms-1 disabled">Delete This Question</button>
 -->
	<button type="button" id = "approve_btn"  class="btn btn-success btn-lg ms-4 hide disabled ">Approve </button> 
	<button type="button" id = "approve_btn_ghost"  title = "Check all of the items below to activate approval button." class="btn btn-success btn-lg ms-4 dim ">Approve </button> 
	<button type="button" id = "reject_btn"  title = "Activate button by typing a justification for rejecting" class="btn btn-danger btn-lg ms-4 hide disabled ">Kill - Send to QuestionTomb  </button> 
	<button type="button" id = "reject_btn_ghost"  title = "Question is not worth repairing by me or someone else" class="btn btn-outline-danger btn-lg ms-4 "> <span id = "reject_text" class = "text-secondary">Kill - Send to QuestionTomb </span> </button> 
</div>
<section>
<div id = "reject_justification_container" class = "my-4 input-group input-group-lg hide">
	<label for = "reject_justification"  class = " input-group input-group-lg "> Please explain why this problem is not worth repairing to activate Reject Button:</label>
	<input id = "reject_justification" class="form-control" spellcheck ="true" name = "reject_justification" type = "text" style = "width: 90%;" maxlength = "512" value ="" ></input>

</div>

</section>
<section id = "instructions" class = "instructions my-4">
		<label for = "check_items" class = "fw-bold"> Please Check Question for: </label>
	<div id = "check_items" class = " p-2 ">
	<div class = "row  ">
			<div class = "col-3 " title = "It should be clear what the question is asking">
				<input class ="form-check-input p-2" type = "checkbox"   > Clarity </input>
			</div>
			<div class = "col-3" title = "Question should fit the catagory and be a good question for that catagory and grade">
				<input class ="form-check-input p-2" type = "checkbox"   > Relavance </input>
			</div>
			<div class = "col-3"  title = "Catagories are Basic Knowledge, Basic Concept, More Advanced Concept, and Involving Calculations">
				<input class ="form-check-input p-2" type = "checkbox"  > Question Usage & Type</input>
			</div>
			<div class = "col-3"  title = "No spelling or gramatical errors and sentences should be clear and easy to read">
				<input class ="form-check-input p-2" type = "checkbox"  > Spelling/Grammer</input>
			</div>
		</div>
		<div class = "row">
			<div class = "col-3" title = "Options marked as correct should be unequivocally correct">
				<input class ="form-check-input" type = "checkbox"  > Key is Correct</input>
			</div>
			<div class = "col-3" title = "Options that are not the key should be clearly incorrect">
				<input class ="form-check-input" type = "checkbox"  > Non Key Options Incorrect</input>
			</div>
			<div class = "col-3"  title = "Catagories are Just Text - Single Correct, Contains images - Single Correct and Multiple Correct">
				<input class ="form-check-input p-2" type = "checkbox"  > Title, Discipline, Course, Concept</input>
			</div>

			<div class = "col-3">
				<input id = "explaination_check" class ="form-check-input" type = "checkbox"  title = "Numerical questions should have a clear solution and conceptual questions should have a clear explaination"  > <span id = "explaination_check_text"  title = "Numerical questions should have a clear solution and conceptual questions should have a clear explaination" class = ""> Explaination/Solution is clear </span></input>
		</div>
</div>

</section>
<h6> &nbsp;  qw_id = <?php echo ($question_id)?></h6>
<h3 class = 'mt-1 text-primary'> Problem Preview </h3>


<?php echo $html;

echo '<br>';
echo '<hr>';
echo '<br>';

echo '<span class = "text-primary"> key(s): </span>';

for ($i = 0; $i < 10; $i++){
  if ($key[$i]=="1"){
        echo '<span>&nbsp;'.$letters[$i].'</span>';
  }  
}
echo '<br>';

// $question_use
// echo '<br>';

// $question_use
echo '<span class = "text-primary">Question Usage: </span>'.$question_use_ar[$question_use];
echo '<br>';
echo '<span class = "text-primary">Question Type: </span>'.$question_type_ar[$question_type];
echo '<br>';
echo '<br>';
echo '<span class = "text-primary">Title: </span>'.$question_data['title'];
echo '<br>';
echo '<span class = "text-primary">Discipline: </span>'.$question_data['subject'];
echo '<br>';

echo '<span class = "text-primary">Course: </span>'.$question_data['course'];
echo '<br>';

echo '<span class = "text-primary">Primary Concept: </span>'.$question_data['primary_concept'];

echo '<hr>';

echo $html_explan_str;

// echo 'iid'.$iid;

?>
		<input type="hidden" id="iid" value="<?php echo ($iid)?>">
		<input type="hidden" id="question_id" value="<?php echo ($question_id)?>">
		<input type="hidden" id="question_use" value="<?php echo ($question_use)?>">

<script>

// const write_btn = document.getElementById('write_btn');
const edit_btn = document.getElementById('edit_btn');
const check_btn = document.getElementById('check_btn');
const iid = document.getElementById('iid').value;
const question_id = document.getElementById('question_id').value;
const delete_btn = document.getElementById('delete_btn');
const delete_btn_activate = document.getElementById('delete_btn_activate');
const approve_btn = document.getElementById('approve_btn');
const approve_btn_ghost = document.getElementById('approve_btn_ghost');
const checkboxes = document.querySelectorAll('input[type="checkbox"]')
const reject_btn = document.getElementById('reject_btn');
const reject_btn_ghost = document.getElementById('reject_btn_ghost');
const instructions = document.getElementById('instructions');
const reject_justification_container = document.getElementById('reject_justification_container');
const reject_text = document.getElementById('reject_text');
const reject_justification = document.getElementById('reject_justification');
const explaination_check = document.getElementById('explaination_check');
const explaination_check_text = document.getElementById('explaination_check_text');
const question_use = document.getElementById('question_use').value;
const sendback_btn = document.getElementById('sendback_btn');
const sendback_btn_ghost = document.getElementById('sendback_btn_ghost');
const sendback_justification_container = document.getElementById('sendback_justification_container');
const sendback_text = document.getElementById('sendback_text');

approve_btn.classList.add("hide");
approve_btn_ghost.classList.add("hide");
// reject_btn.classList.add("hide");
// reject_btn_ghost.classList.add("hide");



console.log('question_use',question_use);

if(question_use == 1){  //! basic knowledge question
	explaination_check.classList.add("hide");
	explaination_check_text.classList.add("hide");
}

console.log("marker");


reject_justification.addEventListener('keypress',(e)=>{
	if(e.target.value.length >15){
		reject_btn_ghost.classList.add("hide");
		reject_btn.classList.remove("hide");
		reject_btn.classList.remove("disabled");
	}
})
console.log("marker0");

reject_btn.addEventListener("click",()=>{
	console.log('use ajax to reject problem and add justification to data table then go to the skir')
	// console.log ('student_id',student_id,'question_id',question_id,'reject_justification',reject_justification.value)
	$.ajax({
			url: 'editActiveQuestionKill.php',
			method: 'post',
			data: {question_id:question_id,iid:iid,reject_justification:reject_justification.value}
		
		}).done(function(message){
			console.log ('message',message);

			if (message == 1){

			let location = 'editActiveQuestion.php?iid='+iid+'&success_flag=1';
			window.location.href = location;
			} else {
				let location = 'editActiveQuestion.php?iid='+iid+'&success_flag=0';
				window.location.href = location;
			}
	})
})
console.log("marker1");

reject_btn_ghost.addEventListener('click',()=>{
			reject_btn_ghost.classList.add("hide");
		reject_btn.classList.remove("hide");
		reject_btn.classList.remove("disabled");

	
	instructions.classList.toggle("hide");

	reject_justification_container.classList.toggle('hide');
	reject_text.classList.toggle('text-secondary');
})


// sendback_justification.addEventListener('keypress',(e)=>{
// 	if(e.target.value.length >15){
// 		sendback_btn_ghost.classList.add("hide");
// 		sendback_btn.classList.remove("hide");
// 		sendback_btn.classList.remove("disabled");
// 	}
// })

// sendback_btn.addEventListener("click",()=>{
// 	console.log('use ajax to reject problem and add justification to data table then go to the skir')
// 	// console.log ('student_id',student_id,'question_id',question_id,'sendback_justification',sendback_justification.value)
// 	$.ajax({
// 			url: 'writeQuestionSendback.php',
// 			method: 'post',
// 			data: {question_id:question_id,iid:iid,reject_justification:sendback_justification.value}
		
// 		}).done(function(message){
// 			console.log ('message',message);

// 			if (message == 1){

// 			let location = 'writeQuestionBirth.php?iid='+iid+'&success_flag=1';
// 			window.location.href = location;
// 			} else {
// 				let location = 'writeQuestionBirth.php?iid='+iid+'&success_flag=0';
// 				window.location.href = location;
// 			}
// 	})
// })
console.log("marker2");

// sendback_btn_ghost.addEventListener('click',()=>{
// 	instructions.classList.toggle("hide");
// 	sendback_justification_container.classList.toggle('hide');
// 	sendback_text.classList.toggle('text-secondary');
// })


let count_checks = function () {
	let count = 0;

	 for (let i = 0; i < checkboxes.length; i++) {
		// console.log ('checkboxes',checkboxes[i])

		 if (checkboxes[i].checked || checkboxes[i].classList.contains('hide')){
			 count++;
		 } else {
			// approve_btn_ghost.classList.remove('hide');
			approve_btn.classList.add('hide');
			approve_btn.classList.add('disabled');
		 }
	 }
	 return count;

 }

console.log (count_checks());
for (let i = 0; i < checkboxes.length; i++) {
	checkboxes[i].addEventListener('click',()=>{

		console.log(count_checks());



		if (count_checks()==checkboxes.length){
			approve_btn_ghost.classList.add('hide');
			approve_btn.classList.remove('hide');
			approve_btn.classList.remove('disabled');

		}
	})

}
console.log("marker3");

// const go_to_edit_flag = document.getElementById('go_to_edit_flag').value;

// write_btn.addEventListener('click', () =>{
// 	let location = 'writeQuestion.php?student_id='+student_id;
// 	console.log ('location',location);
// 	 window.location.href = location;
// })
edit_btn.addEventListener('click', () =>{
	let location = 'writeQuestionInstructorLiveQuest.php?iid='+iid+'&question_id='+question_id+'&check_flag=1';
	console.log ('location',location);
	 window.location.href = location;
})
check_btn.addEventListener('click', () =>{
	let location = 'editActiveQuestion.php?iid='+iid;
	console.log ('location',location);
	 window.location.href = location;
})
approve_btn.addEventListener('click', () =>{

    //? put the approval in the table using AJAX and go back to edit
	$.ajax({
					url: 'writeQuestionPromote.php',
					method: 'post',
					data: {question_id:question_id,iid:iid}
				
				}).done(function(message){
					console.log ('message',message);

					if (message == 1){

					let location = 'writeQuestionBirth.php?iid='+iid+'&success_flag=1';
					window.location.href = location;
					} else {
						let location = 'writeQuestionBirth.php?iid='+iid+'&success_flag=0';
						window.location.href = location;
					}

				})


	// let location = 'writeQuestionCheck.php?student_id='+student_id;
	// console.log ('location',location);
	//  window.location.href = location;
})







</script>
	
	</body>
	</html>