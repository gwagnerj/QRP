<?php
require_once "pdo.php";
session_start();
$username=$_SESSION['username'];
$discipline = '';	
// get the name email and school name using the username from the users table

	$sql = " SELECT * FROM Users where username = :username";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(array(
						':username' => $username));
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						$first = $row['first'];
						$last = $row['last'];
						$email = $row['email'];
						$security = $row['security'];
						// $school_id=$row['school_id'];
						$name = $first. " " . $last;
						$university=$row['university'];
						$users_id=$row['users_id'];
						

if(isset($_POST['title'])){	
	

		// Data validation Stuff
		
		/* if ( strlen($_POST['title']) < 5 ) {
			$_SESSION['error'] = 'Please include a longer title';
			 header("Location: requestPblmNum.php");
			  return;
		}
		

				if ($_POST['p_concept']=='Select') {
						$_SESSION['error'] = 'Primary Concept Not Set';
						header("Location: QRPRepo.php");
						 return;
					}
					if ($_POST['s_concept']=='Select') {
						
						$_POST['s_concept']='';
					}
					
					if(isset($_POST['game'])){
						$game_prob_flag=1;	
					}
					else {
						$game_prob_flag=0;
					}
					if(isset($_POST['nm_author'])){
						$nm_author=$_POST['nm_author'];	
					}
					else {
						$nm_author="Null";
					}
		 */
		
// Process the data and put it in the problem sheet
	  $game_prob_flag=0;
	  $sql = "INSERT INTO Problem (users_id, title, nm_author, game_prob_flag, subject, course, primary_concept, secondary_concept,tertiary_concept, status, specif_ref, computation_name)	
	  VALUES (:users_id, :title,:nm_author, :game_prob_flag, :subject, :course, :primary_concept, :secondary_concept, :tertiary_concept, :status, :specref, :computation)";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':users_id' => $users_id,
				':title' => $_POST['title'],
				':nm_author' => $_POST['nm_author'],				
				':game_prob_flag' => $game_prob_flag,
				':subject' => $_POST['subject'],
				':course' => $_POST['course'],
				':primary_concept' => $_POST['p_concept'],
				':secondary_concept' => $_POST['s_concept'],
				':tertiary_concept' => $_POST['t_concept'],
				':status' => 'num issued',
				':specref' => $_POST['spec_ref'],
				':computation' => $_POST['computation']
				));			
				
				
			$pblm_num=$pdo->lastInsertId();
			
			
			// reserve the values in Qa table for the problem so all subsequent edits will be updates the other values will initialize to null in sql
			for ($i = 1; $i <= 200; $i++) {
					$sql = "INSERT INTO Qa (problem_id, dex)	
						VALUES (:problem_id, :dex)";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
						':problem_id'=> $pblm_num,
						':dex' => $i));
			
			}
				// reserve the values in Input table for the problem so all subsequent edits will be updates the other values will initialize to null in sql
			for ($i = 1; $i <= 200; $i++) {
					$sql = "INSERT INTO Input (problem_id, dex)	
						VALUES (:problem_id, :dex)";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
						':problem_id'=> $pblm_num,
						':dex' => $i));
			}
			
		// delivers the problem	
			
				$_SESSION['success'] = 'your problem number is '.$pblm_num;
				$_SESSION['game_prob_flag']=0;
				$file_name = 'p'.$pblm_num.'_0_'.$_POST['title'];
				$_SESSION['file_name']=$file_name;
				 header( 'Location: downloadDocx.php' ) ;
				 return;
				
				
	 
	


	// Flash pattern
	if ( isset($_SESSION['error']) ) {
		echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
		unset($_SESSION['error']);
	}

}


/* // Get the school names from the database so we can use them in drop down selection box
$sql="SELECT DISTINCT s_name from School ORDER BY s_name";
$stmt = $pdo->query($sql);
// I'm pretty sure this is not the best way but I/m just going to read it into an array variable 
$i=0;
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
	$s_name[$i]=htmlentities($row['s_name']);
	$i=$i+1;
} */

?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRProblems</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="DataTables-1.10.18/js/jquery.dataTables.js"></script>
</head>

<body>
<header>
<h2>Quick Response Problems</h2>
</header>


<p>Please provide:</p>
<form role = "form" method="POST" action ="" >
<p></p>
<div class = "row">
	<p>a Problem Title:
		<input required  minlength="7" type="text" name="title" ></p>
</div>	


<div class = "row">
	<div class = "form-group">
		<label for = "Discipline">Discipline (e.g. Chemical Engineering):</label>
		<select class = "form-control" id = "discipline" name = "subject">
			<option required selected = "" disabled = "" value = ""> Select Discipline </option>
			<?php
				 $stmt = "SELECT * FROM `Discipline`";
				$stmt = $pdo->query($stmt);
				$stmt = $pdo->query("SELECT * FROM Discipline ORDER BY Discipline.discipline_name");
				$disciplines = $stmt->fetchALL(PDO::FETCH_ASSOC);
				// require 'dccData.php';
				// $disciplines = loadDiscipline();
				// echo $disciplines;
					 foreach ($disciplines as $discipline) {
							echo "<option id='".$discipline['discipline_id']."' value='".$discipline['discipline_name']."'>".$discipline['discipline_name']."</option>";
					 }
			?>
		</select>
	</div>
</div>
</br>
<div class = "row">	
	<div class = "form-group">
		<label for = "course">Course Name (e.g. Thermodynamics):</label>
		<select required class = "form-control" id = "course" name = "course">	
		<option selected = "" disabled = "" value = "" > Select Course </option>
			
		</select>
	</div>
</div>		
</br>		
<div class = "row">	
	<div class = "form-group">
		<label for = "course">Primary Concept (e.g. Conservation of Mass ):</label>
		<select  class = "form-control" id = "p_concept" name = "p_concept">	
		<option selected = "" disabled = "" value = ""> Select Primary Concept </option>	
		</select>
	</div>
</div>			
</br>			
<div class = "row">	
	<div class = "form-group">
		<label for = "course">Secondary Concept (e.g. Ideal Gas Law ):</label>
		<select class = "form-control" id = "s_concept" name = "s_concept">	
		<option selected = "" disabled = ""> Select Secondary Concept </option>		
		</select>
	</div>
</div>					

</br>
<div id = "add_concept">
			<b>Don't see an Appropriate Concept in the Dropdown? 
				<a href="inputConcept.php">Input Concept</b></a> 
			</br>
</div>
<p>Other Descriptor(s) Instructors may Search for (e.g. water treatment cooling tower )(optional):
<input type="text" name="t_concept" ></p>



<div class = "row">	
	<div class = "form-group">
		<label for = "course">Computation (e.g. single algebraic equations ):</label>
		<select required class = "form-control" id = "computation" name = "computation">	
		
		<option required  disabled = "" value = ""> Select Computation </option>
			<?php
				// $stmt = "SELECT * FROM `Computation`";
				// $stmt = $pdo->query($stmt);
				$stmt = $pdo->query("SELECT * FROM Computation ORDER BY Computation.computation_order");
				$computations = $stmt->fetchALL(PDO::FETCH_ASSOC);
				// require 'dccData.php';
				// $disciplines = loadDiscipline();
				// echo $disciplines;
					 foreach ($computations as $computation) {
							echo "<option id='".$computation['computation_id']."' value='".$computation['computation_name']."'>".$computation['computation_name']."</option>";
					 }
			?>

		
		</select>
	</div>
</div>				
<!-- <div class = "row">
	<p> The Author of the Base-Case (if different than Contributor):
	<input type="text" name="nm_author" ></p>
</div>  -->
</br>			
<div class = "row">	
	<div class = "form-group">
		<label for = "course">Author of the Base-Case (if different than Contributor):</label>
		<select required class = "form-control" id = "nm_author" name = "nm_author">	
		<option selected = "" disabled = ""> Select Author </option>		
		</select>
	</div>
</div>					
<p>
<div id = "add_auth">	
	<b>Don't see an Appropriate Author in the Dropdown? 
			<a href="inputAuthor.php">Add an Author</b></a> 
</div>			
</br>
<div class = "row">
	<p>Specific Reference (e.g. Felder 4th ex 3.2):
	<input type="text" name="spec_ref" ></p>
</div>
<p>
<p></p>
<p><input  type="submit" value="Get Problem Number"/>

<a href="QRPRepo.php">Cancel</a></p>
</form>

	<script type="text/javascript">
	
$(document).ready(function(){
			$('#add_auth').hide();
			$('#add_concept').hide();
			
			$("#discipline").change(function(){
				
				var discipline = $("#discipline").val();
				 // console.log (discipline);
				$.ajax({
					url: 'dcData.php',
					method: 'post',
					data: 'discipline=' + discipline
				}).done(function(course){
					// console.log(course);
					 course = JSON.parse(course);
					// $('#course').empty();
					course.forEach(function(course){
						$('#course').append('<option>' + course.course_name + '</option>') 
						
					 })
				})
			})
			
			$("#course").change(function(){
				$('#add_auth').show();
				$('#add_concept').show();
				
				var course = $("#course").val();
				
				//  console.log (course);
				$.ajax({
					
					url: 'ccData.php',
					method: 'post',
					data: 'course=' + course
				}).done(function(p_concept){
					// console.log(p_concept);
					 concept = JSON.parse(p_concept);
					// $('#p_concept').empty();
					concept.forEach(function(concept){
						$('#p_concept').append('<option>' + concept.concept_name + '</option>') 
										
						
					 })
					 concept.forEach(function(concept){
						$('#s_concept').append('<option>' + concept.concept_name + '</option>') 
					 })
					 
					  $.ajax({
						url: 'caData.php',
						method: 'post',
						data: 'course=' + course
					}).done(function(author) {
						var authors = JSON.parse(author);
						console.log(authors);
						authors.forEach(function(authors){
								$('#nm_author').append('<option>' + authors.author_name + '</option>') 
						})
					});  
					 
				})
			
			})
			
	
		})
		
		


	</script>


</body>
</html>