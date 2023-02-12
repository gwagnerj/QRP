<?php
	require_once "pdo.php";
	session_start();

	
	if (isset($_POST['eexamtime_id'])) {
		$eexamtime_id = htmlentities($_POST['eexamtime_id']);
	  } else {
		   $_SESSION['error'] = 'invalid eexamtime_id in  QREPrint0.php ';
		   header( 'Location: QRPRepo.php' ) ;
			die();
	  }
	
// get the current class name and the course name
$sql = 'SELECT currentclass_id, exam_num,iid,game_flag FROM Eexamtime  WHERE eexamtime_id = :eexamtime_id';
	$stmt = $pdo->prepare($sql);
	$stmt -> execute(array (
	':eexamtime_id' => $eexamtime_id,
	)); 
	$examtime_data = $stmt->fetch();  
	$currentclass_id = $examtime_data['currentclass_id'];
	$exam_num = $examtime_data['exam_num'];
	$iid = $examtime_data['iid'];
	$game_flag = $examtime_data['game_flag'];

	$sql = 'SELECT `name` FROM CurrentClass  WHERE currentclass_id = :currentclass_id';
	$stmt = $pdo->prepare($sql);
	$stmt -> execute(array (
	':currentclass_id' => $currentclass_id,
	)); 
	$currentclass_data = $stmt->fetch();  
	$class_name = $currentclass_data['name'];
	



//$iid = 1;  // temporary
	?>




<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRExam Print</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">


<style>
.show {
 font-size:1em;
 display: inline;
}
.hide { 
	display:none;
}


</style>



</head>

<body>


<header>
<h1>Quick Response Exam - Print Exam</h1>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</header>

<?php
	
		if ( isset($_SESSION['error']) ) {
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) ) {
			echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
			unset($_SESSION['success']);
		}
	
 
 
 
?>

<!--<h3>Print the problem statement with "Ctrl P"</h3>
 <p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p>  -->

 <div class = "container" >
<form id = "the_form"  method = "POST" action = "QRExamPrint1.php" >
	
<h3> Class Name: <?php  echo $class_name; ?> <span class = "ms-3">Exam Number: <?php  echo $exam_num; ?></span></h3>
           
         
<h3 class = "mt-4">Exam Options:  </h3>
                 

				 <div class = "container-fluid mt-3">
                 
                             <div>
								<div>
									<input type = "radio" name ="exam_version" id = "exam_version1" value = "1"  > </input>
										<label for "exam_version"> Personal print page (name printed on exam/game) </label>
								</div>
									<div id = "student_version_code_container" class = "hide ms-5"> <input type = "checkbox" class = "btn btn-primary" name = "student_version_code" id = "student_version_code" > Version Code from student Index</checkbox></div>
                             </div>
                               </br>
                             <div>
                                <input type = "radio" name ="exam_version" id = "exam_version2" value = "2" checked > </input>
                                   <label for "exam_version"> Generic or limited versions (no name printed on exam/game)
                                   </label>
								  
							</div >
							<div class = "ms-5"  id = "generic_container">
							Number of versions (or max number of team members):
								   <input    type = "number" name ="num_versions" id = "num_versions" min = "1", max = "9" value = "3" required  > </input>
                                    Number of print copies for each version (only version code changes) <input type = "number" name ="sets" id = "sets" min = "1", max = "99" value = "1" required  > </input>
                                   Index that the print starts on (1 to 190) <input type = "number" name ="index_start" id = "index_start" min = "1", max = "190" value = "140" required  > </input>
                                   
                                      
                            <div class = "mt-3" id = "print_set_container">
									<input type = "checkbox" name = "print_set" id = "print_set"> Print set of problems for team i student j </input>
									<div class = "ms-5 d-none" id = "num_teams_container">
									<input class = "mb-4" type = "number" min = "0" max = "20" value = "0"  name = "num_teams" id = "num_teams"> Print set of problems for team i student j </input>
								</div>
								</div>
						</div>
						<div class = "mt-5 mb-3">
							<input type = "checkbox" name = "print_blanks" id = "print_blanks"> Print Variables as Blanks</input>
						</div>
						<div>
							<input type = "checkbox" name = "game_flag" id = "game_flag" <?php if ($game_flag == "1") echo'checked';?>> This is a Game (effects QR Code)</input>
						</div>  

			</div>
                  </br>
     
            
				  <p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
				  <p><input type="hidden" name="exam_num" id="exam_num" value=<?php echo($exam_num);?> ></p>
				  <p><input type="hidden" name="currentclass_id" id="currentclass_id" value=<?php echo($currentclass_id);?> ></p>
				  <p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
			<p><input type = "submit" id = "submit_id"></p>
   
	
	</form>
  <p style="font-size:100px;"></p>   
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	</div>
	<script>
	
const student_version_code_container = document.getElementById('student_version_code_container');
const student_version_code = document.getElementById('student_version_code');
const exam_version1 = document.getElementById('exam_version1');
const exam_version2 = document.getElementById('exam_version2');
const exam_version2_container = document.getElementById('exam_version2_container');

const print_set = document.getElementById('print_set');
const num_teams = document.getElementById('num_teams');
const num_teams_container = document.getElementById('num_teams_container');
const generic_container = document.getElementById('generic_container');


exam_version2.addEventListener('change',function(ev){
	console.log ('exam_version2',exam_version2.checked)
	if (exam_version2.checked) {
		generic_container.classList.remove('d-none');
	} else {
		generic_container.classList.add('d-none');}
		num_teams.value = 0;
	
});


print_set.addEventListener('change',function(ev){
		if (print_set.checked) {
			num_teams_container.classList.remove('d-none');
			num_teams.value = 5;
			
		} else {
			num_teams_container.classList.add('d-none');
			num_teams.value = 0;
		}	
});

// console.log(exam_version1.value);
exam_version1.addEventListener('change',function(ev){

	// console.log('exam_version1',exam_version1.checked);
		student_version_code_container.classList.remove('hide');
		student_version_code_container.classList.add('show');
		generic_container.classList.add('d-none');}
);

exam_version2.addEventListener('change',function(ev){
		student_version_code_container.classList.remove('show');
		student_version_code_container.classList.add('hide');
		student_version_code.checked = false;
});

// console.log(student_version_code_container);
//  console.log(exam_version1);
	
</script>	

</body>
</html>



