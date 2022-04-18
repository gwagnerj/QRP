<?php
	session_start();
	require_once "pdo.php";
    require_once "simple_html_dom.php";
    require_once 'Encryption.php';
    require_once '../encryption_base.php';
	$_SESSION['success'] = '';



	if (isset($_POST['check_flag']) && $_POST['check_flag'] != '0'){  //? this is the question editor (checker and not the author of the problem)
		$check_flag = $_POST['check_flag'];
	} else {$check_flag = 0;}

	if (isset($_POST['num_looks']) && $_POST['num_looks'] != '0'){  //? this is the question editor (checker and not the author of the problem)
		$num_looks = $_POST['num_looks'];
	} else {$num_looks = 0;}



//? get info from the post information from writeQuestion and put all meta data in the questionwomb table
$letters = range ('a','j');    //? this bit initializzes the correct key to zero so if we change it only one is selected

		if (isset($_POST['user_id'])){
			$user_id = $_POST['user_id'];
		} else {$user_id = '';}

		if (isset($_POST['student_id'])){
			$student_id = $_POST['student_id'];
		} else {$student_id = '';}

		$go_to_edit_flag = false;

		if (isset($_POST['questionwomb_id']) && $_POST['questionwomb_id'] != '0'){  //? we are editing a problem
			$questionwomb_id = $_POST['questionwomb_id'];
		} elseif (isset($_GET['questionwomb_id']) && $_GET['questionwomb_id'] != '0'){ 
			$questionwomb_id = $_GET['questionwomb_id'];
		} else {$questionwomb_id = 0;}




		if(isset($_POST['title'])){
			if (isset($_POST['grade'])){$grade = $_POST['grade'];} else {$grade = 4;}
			$title = htmlentities($_POST['title']);
			$t_concept = htmlentities($_POST['t_concept']);
			$un_nm_author = htmlentities($_POST['un_nm_author']);
			$nm_author = htmlentities($_POST['nm_author']);
			$spec_ref = htmlentities($_POST['spec_ref']);
			$num_options = $_POST['num_options'];
//			$questionwomb_id = 0;  //! delete this when done editing

			$num_correct = count($_POST['correct_option']);  //? to get the correct options if there is more than one and put them in the table
			$correct_val = array_fill(0,$num_correct,1);
			$correct_val = implode(', ',$correct_val);
			//  echo ' correct_val '.$correct_val;

		$correct_option = implode(", ",$_POST['correct_option']);
		//  echo ' correct option '. $correct_option;
		}

		//? this next bit is to make sure the author does not get reset when a person is editing the problems

// 		if($check_flag == 1 && $questionwomb_id != 0 ){
// 			$sql = 'SELECT * FROM QuestionWomb WHERE 
// 			 `questionwomb_id` = :questionwomb_id
// 			';
// 			$stmt = $pdo->prepare($sql);
// 			$stmt->execute(array(
// 				':questionwomb_id' => $questionwomb_id,
// 			));
// 			$qw_existing_data = $stmt -> fetch();

// //? just do an update and return to the writeQuestionPreview file.  Do not update the student Id or the nm_author



// 			// $student_id = $qw_existing_data['student_id'];  //! these are the troubled lines
// 			// $nm_author = $qw_existing_data['nm_author'];
// 		}




		//? prevent refresh of the page causing another insert by looking in table for that problem but not by questionwomb id

		if(isset($_POST['title'])){
			$sql = 'SELECT questionwomb_id FROM QuestionWomb 
				WHERE student_id =:student_id AND user_id = :user_id AND title = :title AND `course`= :course AND `primary_concept`= :primary_concept';
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':student_id' => $student_id,
					':user_id' => $user_id,
					':course' => $_POST['course'],
					':primary_concept' =>$_POST['p_concept'],
					':title' => $title,
				));
				$questionwomb_id_data = $stmt -> fetch();

				If ($questionwomb_id_data){
					 $questionwomb_id = $questionwomb_id_data['questionwomb_id'];
					$go_to_edit_flag = true;
					
				}

		} 

	if ($questionwomb_id != 0){  // we have an edited problem and need to update it
			
		$correct_entry = '';
		for ($i = 0; $i <$num_correct; $i++) {
		$correct_entry .= $_POST['correct_option'][$i].' = 1,';
		}
		$correct_entry = rtrim($correct_entry,',');


			//! this was put in to make sure there is no more than the correct key written but right now is erasign all

		if (isset($_POST['correct_option'])){
			for ($i = 0; $i < $num_options; $i++){  //? this bit initializzes the correct key to zero so if we change it only one is selected
				$sel = 'key_'.$letters[$i];
				$sql = 'UPDATE QuestionWomb SET	'.$sel.' = 0 WHERE questionwomb_id = :questionwomb_id';
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(':questionwomb_id'=>$questionwomb_id));

			}
		}
			


			$question_use_ar = array('_BK_','_BC_','_AC_','_Num_');
				$index = $_POST['question_usage']-1;
				$question_use_tx = $question_use_ar[$index];
				$question_format_ar = array('_SC_','_img_','_MC_');
				$index = $_POST['question_type']-1;
				$question_format_tx = $question_format_ar[$index];


			$htmlfilenm = 'qw_'.$questionwomb_id.'_'.$question_use_tx.$question_format_tx.$_POST['title'];



			$sql = "UPDATE QuestionWomb SET 
			`htmlfilenm` = :htmlfilenm, 
			`unpubl_auth`= :unpubl_auth,
			`specif_ref` = :specif_ref,
			`subject`= :subject,
			`course`= :course,
			`primary_concept`= :primary_concept,
			`secondary_concept`= :secondary_concept,
			`tertiary_concept` =:tertiary_concept,
			`grade`= :grade,
			`title`= :title,
			`question_type`= :question_type,
			`question_use`= :question_use,
			`status`= :status,
			`university`= :university,
			".$correct_entry."
		WHERE questionwomb_id=:questionwomb_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':htmlfilenm'=> $htmlfilenm,
					':unpubl_auth' => $un_nm_author,
					':specif_ref' => $spec_ref,
					':subject' => $_POST['subject'],
					':course' => $_POST['course'],
					':primary_concept' =>$_POST['p_concept'],
					':secondary_concept' =>$_POST['s_concept'],
					':tertiary_concept' =>$t_concept,
					':grade' => $grade,
						':title' => $title,
					':question_type' => $_POST['question_type'],
					':question_use' => $_POST['question_usage'],
					':status' => $_POST['status'],
					':university' => $_POST['university'],
					':questionwomb_id' => $questionwomb_id,
					

				));
		
		
		} else {



					$sql = "INSERT INTO QuestionWomb (
						`nm_author`,
						`email`,
						`student_id`,
						`user_id`,
						`unpubl_auth`,
						`specif_ref`,
						`subject`,
						`course`,
						`primary_concept`,
						`secondary_concept`,
						`tertiary_concept`,
						`grade`,
						`title`,
						`question_type`,
						`question_use`,
						`status`,
						`university`,
						".$correct_option."
						)
					VALUES (
						:nm_author,
						:email,
						:student_id,
						:user_id,
						:unpubl_auth,
						:specif_ref,
						:subject,
						:course,
						:primary_concept,
						:secondary_concept,
						:tertiary_concept,
						:grade,
						:title,
						:question_type,
						:question_use,
						:status,
						:university,
						".$correct_val."
						) ";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
					':nm_author' => $nm_author,
					':email' => $_POST['email'],
					':student_id' => $student_id,
					':user_id' => $user_id,
					':unpubl_auth' => $un_nm_author,
					':specif_ref' => $spec_ref,
					':subject' => $_POST['subject'],
					':course' => $_POST['course'],
					':primary_concept' =>$_POST['p_concept'],
					':secondary_concept' =>$_POST['s_concept'],
					':tertiary_concept' =>$t_concept,
					':grade' => $grade,
						':title' => $title,
					':question_type' => $_POST['question_type'],
					':question_use' => $_POST['question_usage'],
					':status' => $_POST['status'],
					':university' => $_POST['university'],

					));	

					$questionwomb_id = $pdo->lastInsertId();

					//? figure out score for this

					$question_use = $_POST['question_usage'];

					if ($question_use == 1){ $score = 10;}
					if ($question_use == 2){ $score = 15;}
					if ($question_use == 3){ $score = 20;}
					if ($question_use == 4){ $score = 25;}
		


					$sql = "INSERT INTO QuestionWombActivity (
						`student_id`,
						`questionwomb_id`,
						`activity`,
						`score`
						)
					VALUES (
						:student_id,
						:questionwomb_id,
						:activity,
						:score
						) ";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
					':student_id' => $student_id,
					':questionwomb_id' => $questionwomb_id,
					':activity' => 'author',
					':score' => $score,
					));	
	
	
		}
		// echo ' questionwomb_id '. $questionwomb_id;
		// echo '<br>';

		$question_use_ar = array('_BK_','_BC_','_AC_','_Num_');
		$index = $_POST['question_usage']-1;
		$question_use_tx = $question_use_ar[$index];

		$question_format_ar = array('_SC_','_img_','_MC_');
		$index = $_POST['question_type']-1;
		$question_format_tx = $question_format_ar[$index];
		// echo ' index ',$index;
		//  echo ' question_format_tx ',$question_format_tx;

		$htmlfilenm = 'qw_'.$questionwomb_id.'_'.$question_use_tx.$question_format_tx.$_POST['title'];
		$auth_solnfilenm = 'qw_soln_'.$questionwomb_id.'_'.$question_use_tx.$question_format_tx.$_POST['title'];
		$check1_solnfilenm = 'qw_check1_soln_'.$questionwomb_id.'_'.$question_use_tx.$question_format_tx.$_POST['title'];
		$check2_solnfilenm = 'qw_check2_soln_'.$questionwomb_id.'_'.$question_use_tx.$question_format_tx.$_POST['title'];
		$check3_solnfilenm = 'qw_check3_soln_'.$questionwomb_id.'_'.$question_use_tx.$question_format_tx.$_POST['title'];
		$explanation_filenm = 'qw_explanation_'.$questionwomb_id.'_'.$question_use_tx.$question_format_tx.$_POST['title'];

		$sql = "UPDATE QuestionWomb SET 
		htmlfilenm = :htmlfilenm, 
		auth_solnfilenm = :auth_solnfilenm, 
		check1_solnfilenm = :check1_solnfilenm,
		check2_solnfilenm = :check2_solnfilenm, 
		check3_solnfilenm = :check3_solnfilenm,	
		explanation_filenm = :explanation_filenm	
		WHERE questionwomb_id=:questionwomb_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':htmlfilenm'=> $htmlfilenm,
					':auth_solnfilenm' => $auth_solnfilenm,
					':questionwomb_id' => $questionwomb_id,
					':check1_solnfilenm' => $check1_solnfilenm,
					':check2_solnfilenm' => $check2_solnfilenm,
					':check3_solnfilenm' => $check3_solnfilenm,
					':explanation_filenm' => $explanation_filenm,
				));



// get the keys to mark the correct answer(s) below
	$sql = "SELECT * FROM QuestionWomb WHERE questionwomb_id = :questionwomb_id";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(':questionwomb_id' => $questionwomb_id));
	$questionwomb_data = $stmt -> fetch();
	for ($i = 0; $i < $num_options; $i++){
		$sel = 'key_'.$letters[$i];
		$key[$i] = $questionwomb_data[$sel];
	}
// if ($go_to_edit_flag){
// 	header('Location: writeQuestion.php?questionwomb_id='.$questionwomb_id.'&student_id='.$student_id);
//     die();
// }

//? make the html file with simple_html_dom
  $question_stem_text_1 = strip_ps( $_POST['question_stem_text_1']);

function strip_ps($x){  // strips out the first and lat paragraph tags from a string
	$x = preg_replace('/<p>/', '',  $x,1);
	$x =strrev ($x);
	$x=preg_replace('/>p\/</', '', $x,1);
	$x =strrev ($x);
	return($x);
}

$html = new simple_html_dom();

$alphabet = array('a','b','c','d','e','f','g','h','i','j');
$btn_color = array("#005073", "#107dac", "#189ad3", "#1ebbd7", "#71c7ec", "#8594a3", "#088F8F", "#5F9EA0", "#00A36C", "#228B22");


$body = '
<html>
   <head>
      <meta http-equiv=Content-Type content="text/html; charset=utf-8">
      </head>
      <body class="" style="background-color: #f6f6f6; font-family: sans-serif;-webkit-font-smoothing: antialiased;font-size: 1.4rem;line-height: 1.4;margin: 2rem;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
         <div class=WordSection1>
			<div class=MsoNormal><div id = "stem_text_1">'.$question_stem_text_1.'</div></div>
			';
for ($i = 0; $i < $num_options ;$i++){
	$body = $body.'<br><br><a id = "select-'.$alphabet[$i].'" class = "select" style ="margin-right:0.5rem;font-size:1.2rem;padding-right:0.5rem; padding-top:0.3rem;padding-bottom:0.3rem; text-decoration: none; padding-left:0.5rem;   text-align: center;font-weight: bold; line-height: 25px; border-radius: 5px; border:3px solid; border-color:  black; background-color:'.$btn_color[$i].';color:white;" href="https://www.qrproblems.org/QRP/question_show.php?response='.$alphabet[$i].'&question_id=0&student_id=0" target="_blank">'.$alphabet[$i].')</a>';  
	$option_sel = 'question_option_'.$alphabet[$i];
	$option = strip_ps($_POST[$option_sel]);
	$body = $body.'<span id = "'.$option_sel.'">##'.$option.'##</span>';

}	
 $body = $body. ' </div>
      </body>
   </html>
';


$html ->load($body);

$full_htmlfilenm = 'uploads/'.$htmlfilenm.'.htm';
$html->save($full_htmlfilenm);
//! this is the same as above but without the ## around the option to display the preview
$body = '
<html>
   <head>
      <meta http-equiv=Content-Type content="text/html; charset=utf-8">
      </head>
      <body class="" style="background-color: #f6f6f6; font-family: sans-serif;-webkit-font-smoothing: antialiased;font-size: 1.4rem;line-height: 1.4;margin: 2rem;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
         <div class=WordSection1>
            <div class=MsoNormal><div id = "stem_text_1">'.$question_stem_text_1.'</div></div>
			';
for ($i = 0; $i < $num_options ;$i++){
	if ($key[$i]==1) {$correct = "&#10004;";} else {$correct = "";}  // check the correct ones
	$body = $body.'<br><br>'.$correct.'<a id = "select-'.$alphabet[$i].'" class = "select" style ="margin-right:0.5rem;font-size:1.2rem;padding-right:0.5rem; padding-top:0.3rem;padding-bottom:0.3rem; text-decoration: none; padding-left:0.5rem;   text-align: center;font-weight: bold; line-height: 25px; border-radius: 5px; border:3px solid; border-color:  black; background-color:'.$btn_color[$i].';color:white;" href="https://www.qrproblems.org/QRP/question_show.php?response='.$alphabet[$i].'&question_id=0&student_id=0" target="_blank">'.$alphabet[$i].')</a>';  
	$option_sel = 'question_option_'.$alphabet[$i];
	$option =strip_ps($_POST[$option_sel]);

	$body = $body.'<span id = "'.$option_sel.'">'.$option.'</span>';
}	
 $body = $body. ' </div>
      </body>
   </html>
';


$html ->load($body);  //? this is the one for the preview


$html_explanation = '';

if (isset($_POST['explanation'])){
	$explanation = $_POST['explanation'];

	$body = '
<html>
   <head>
      <meta http-equiv=Content-Type content="text/html; charset=utf-8">
    </head>
    <body class="" style="background-color: #f6f6f6; font-family: sans-serif;-webkit-font-smoothing: antialiased;font-size: 1.4rem;line-height: 1.4;margin: 2rem;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
			<h4 class = "mt-4 text-primay"> Explanation: </h4>
			<div id = "explanation" class = "ms-3 mt-2" >'.$explanation.'</div>
	</body>
</html>	';

$html_explain = new simple_html_dom();

$html_explain ->load($body);
$full_html_explain_filenm = 'uploads/'.$explanation_filenm.'.htm';

$html_explain->save($full_html_explain_filenm);

$html_explanation = $html_explain;

}

			
			if (isset($_POST['feedback_a'])){
				$sql = "UPDATE QuestionWomb SET fbtext_a = :fbtext_a WHERE questionwomb_id = :questionwomb_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_a' => $_POST['feedback_a'],
					':questionwomb_id' => $_POST['questionwomb_id']));	
			}
			
			
			if (isset($_POST['feedback_b'])){
				$sql = "UPDATE QuestionWomb SET fbtext_b = :fbtext_b WHERE questionwomb_id = :questionwomb_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_b' => $_POST['feedback_b'],
					':questionwomb_id' => $_POST['questionwomb_id']));	
			}
			
			
			if (isset($_POST['feedback_c'])){
				$sql = "UPDATE QuestionWomb SET fbtext_c = :fbtext_c WHERE questionwomb_id = :questionwomb_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_c' => $_POST['feedback_c'],
					':questionwomb_id' => $_POST['questionwomb_id']));	
			}
			
			
			if (isset($_POST['feedback_d'])){
				$sql = "UPDATE QuestionWomb SET fbtext_d = :fbtext_d WHERE questionwomb_id = :questionwomb_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_d' => $_POST['feedback_d'],
					':questionwomb_id' => $_POST['questionwomb_id']));	
			}
			
			
			if (isset($_POST['feedback_e'])){
				$sql = "UPDATE QuestionWomb SET fbtext_e = :fbtext_e WHERE questionwomb_id = :questionwomb_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_e' => $_POST['feedback_e'],
					':questionwomb_id' => $_POST['questionwomb_id']));	
			}
			
			
			if (isset($_POST['feedback_f'])){
				$sql = "UPDATE QuestionWomb SET fbtext_f = :fbtext_f WHERE questionwomb_id = :questionwomb_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_f' => $_POST['feedback_f'],
					':questionwomb_id' => $_POST['questionwomb_id']));	
			}
			
			
			if (isset($_POST['feedback_g'])){
				$sql = "UPDATE QuestionWomb SET fbtext_g = :fbtext_g WHERE questionwomb_id = :questionwomb_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_g' => $_POST['feedback_g'],
					':questionwomb_id' => $_POST['questionwomb_id']));	
			}
			
			
			if (isset($_POST['feedback_h'])){
				$sql = "UPDATE QuestionWomb SET fbtext_h = :fbtext_h WHERE questionwomb_id = :questionwomb_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_h' => $_POST['feedback_h'],
					':questionwomb_id' => $_POST['questionwomb_id']));	
			}
			
			
			if (isset($_POST['feedback_i'])){
				$sql = "UPDATE QuestionWomb SET fbtext_i = :fbtext_i WHERE questionwomb_id = :questionwomb_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_i' => $_POST['feedback_i'],
					':questionwomb_id' => $_POST['questionwomb_id']));	
			}
			
			
			if (isset($_POST['feedback_j'])){
				$sql = "UPDATE QuestionWomb SET fbtext_j = :fbtext_j WHERE questionwomb_id = :questionwomb_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_j' => $_POST['feedback_j'],
					':questionwomb_id' => $_POST['questionwomb_id']));	
			}
			
if ($check_flag != 0){

	 	header('Location: writeQuestionPreview.php?questionwomb_id='.$questionwomb_id.'&student_id='.$student_id.'&check_flag='.$check_flag.'&num_looks='.$num_looks);
		die();
}
			
				$_SESSION['success'] = 'Record updated';
		
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


</style>



		</head>

		<body>
<div id = "btn_group">
    <button type="button" id = "write_btn" title = "Write a new question" class="btn btn-outline-primary btn-lg ms-4">Write Another Question</button>
    <button type="button" id = "edit_btn" title = "Edit this question" class="btn btn-outline-success btn-lg ms-4">Edit This Question</button>
    <button type="button" id = "check_btn" title = "Check Questions Others have Written" class="btn btn-outline-secondary btn-lg ms-4">Check Others Questions</button>

	<!-- <button type="button" id = "delete_btn_activate"  title = "enable /disable the Delete button" class="btn btn-outline-danger btn-sm ms-5 ">Enable Delete</button> -->
	<button type="button" id = "delete_btn"  title = "Delete this question and go back to previous window" class="btn btn-outline-danger btn-lg ms-1 disabled">Delete This Question</button>
	<!-- <button type="button" id = "copy_btn"  title = "copy this question and edit the copy" class="btn btn-outline-secondary btn-lg ms-4 ">Copy & Edit </button> -->

</div>



<h2 class = 'mt-3'> Problem Preview </h2>
<h6 class = "mb-1 ms-4 text-secondary"> questionw_id: <?php echo $questionwomb_id;?></h6>

<hr>

<?php echo $html;?>

<br><br>
<hr>

<?php echo $html_explanation;?>

		<input type="hidden" id="student_id" value="<?php echo ($student_id)?>">
		<input type="hidden" id="questionwomb_id" value="<?php echo ($questionwomb_id)?>">
		<input type="hidden" id="go_to_edit_flag" value="<?php echo ($go_to_edit_flag)?>">

<script>

const write_btn = document.getElementById('write_btn');
const edit_btn = document.getElementById('edit_btn');
const check_btn = document.getElementById('check_btn');
const student_id = document.getElementById('student_id').value;
const questionwomb_id = document.getElementById('questionwomb_id').value;
const delete_btn = document.getElementById('delete_btn');
const delete_btn_activate = document.getElementById('delete_btn_activate');
const copy_btn = document.getElementById('copy_btn');
const go_to_edit_flag = document.getElementById('go_to_edit_flag').value;

write_btn.addEventListener('click', () =>{
	let location = 'writeQuestion.php?student_id='+student_id;
	console.log ('location',location);
	 window.location.href = location;
})
edit_btn.addEventListener('click', () =>{
	let location = 'writeQuestion.php?student_id='+student_id+'&questionwomb_id='+questionwomb_id;
	console.log ('location',location);
	 window.location.href = location;
})
check_btn.addEventListener('click', () =>{
	let location = 'writeQuestionCheck.php?student_id='+student_id;
	console.log ('location',location);
	 window.location.href = location;
})

delete_btn.addEventListener('click', () =>{
	console.log ('delete entry probably with ajax then go on to write another')

})

delete_btn_activate.addEventListener('click', () =>{
	console.log ('activate delete entry')
	delete_btn.classList.toggle('disabled');

})
//! need to fix this so that it only goes back to the edit when we want it too
// if (go_to_edit_flag){
// 	console.log ('go_to_edit_flag',go_to_edit_flag);
// 	let location = 'writeQuestion.php?student_id='+student_id+'&questionwomb_id='+questionwomb_id;
// 	console.log ('location',location);
// 	 window.location.href = location;
// }

copy_btn.addEventListener('click', () =>{
	
	window.location.reload();
	// const new_question_id = parseInt(questionwomb_id) +1;
	// let location = 'writeQuestionCatcher.php?student_id='+student_id+'&questionwomb_id='+new_question_id;
	// console.log ('location',location);
	//  window.location.href = location;


})


</script>
	
	</body>
	</html>