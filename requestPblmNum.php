<?php
require_once "pdo.php";
session_start();

$discipline = '';	

if (isset($_SESSION['username'])) {
	$username=$_SESSION['username'];
} else {
	 $_SESSION['error'] = 'Session was lost -  please log in again';
	header('Location: QRPRepo.php');
	die();
}

if (isset($_POST['title'])){
		$title = $_POST['title'];
		$_SESSION['title'] = $title;
} elseif(isset($_SESSION['title']))	{
		$title = $_SESSION['title'];
} else {
	$title = '';
}
	
	

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
						

if(isset($_POST['title'])&&  isset($_POST['subject'])&& isset($_POST['course']) && isset($_POST['p_concept'])&& isset($_POST['submit'])){	
	
$title = $_POST['title'];
$_SESSION['title'] = $title;
		// Data validation Stuff
		// will try to put this in the script
		
		
// Process the data and put it in the problem sheet
	  $game_prob_flag=0;
	  $sql = "INSERT INTO Problem (users_id, title, nm_author, game_prob_flag, subject, course, primary_concept, secondary_concept,tertiary_concept, status, specif_ref, computation_name, unpubl_auth )	
	  VALUES (:users_id, :title,:nm_author, :game_prob_flag, :subject, :course, :primary_concept, :secondary_concept, :tertiary_concept, :status, :specref, :computation, :unpubl_auth)";
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
				':computation' => $_POST['computation'],
				':unpubl_auth' => $_POST['un_nm_author']
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
				die();
	

} else {
	if (isset($_POST['submit'])){
		$_SESSION['error'] = 'Title, Discipline, Course Name and Primary Concept must all be filled out';
		
	}
}

if (isset($_POST['reset']))	{
			
			$title = '';
			unset($_SESSION['title']);
			 unset($_POST);
			header('Location: requestPblmNum.php'); // reloads the page
			die();
		}
// Flash pattern
	if ( isset($_SESSION['error']) ) {
		echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
		unset($_SESSION['error']);
	}

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

<p><font size = "2">note - If you must correct an input higher in the form - please reset the form and start over. Currently, adding a <u>new</u> author or concept resets the form</font></p>
<p><b>Please provide:</b></p>
<form role = "form" method="POST" action ="" >
<p></p>
<div class = "row">
	<p>a Problem Title:
		<input   type="text" name="title" id = "title"></p>
</div>	


<div class = "row">
	<div class = "form-group">
		<label for = "Discipline">Discipline (e.g. Chemical Engineering):</label>
		<select class = "form-control" id = "discipline" name = "subject">
			<option  selected = "" disabled = "" value = ""> Select Discipline </option>
			<?php
				 $stmt = "SELECT * FROM `Discipline`";
				$stmt = $pdo->query($stmt);
				$stmt = $pdo->query("SELECT * FROM Discipline ORDER BY Discipline.discipline_name");
				$disciplines = $stmt->fetchALL(PDO::FETCH_ASSOC);
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
		<select  class = "form-control" id = "course" name = "course">	
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


<div id = "add_concept">
</br>
			<b>Don't see an Appropriate Concept in the Dropdown? 
				<a href="inputConcept.php?title='.$title.'" target = "_blank"> Input Concept </a> 
			<!--	 <form>
					<input type = "submit" name = "inputConcept" value = "Input New Concept"></input>
				</form> -->
				
			</br>
			
</div>
<p>Other Descriptor(s) Instructors may Search for (e.g. water treatment cooling tower )(optional):
<input type="text" name="t_concept" ></p>

</br>

<div class = "row">	
	<div class = "form-group">
		<label for = "course">Computation (e.g. single algebraic equations ):</label>
		<select required class = "form-control" id = "computation" name = "computation">	
		
		<option required  disabled = "" value = ""> Select Computation </option>
			<?php
			
				$stmt = $pdo->query("SELECT * FROM Computation ORDER BY Computation.computation_order");
				$computations = $stmt->fetchALL(PDO::FETCH_ASSOC);
					 foreach ($computations as $computation) {
							echo "<option id='".$computation['computation_id']."' value='".$computation['computation_name']."'>".$computation['computation_name']."</option>";
					 }
			?>

		
		</select>
	</div>
</div>		
</br>		
<!-- <div class = "row">
	<p> The Author of the Base-Case (if different than Contributor):
	<input type="text" name="nm_author" ></p>
</div>  -->
</br>	
<div id = "publ_auth">		
	<div class = "row" >	
		<div class = "form-group">
			<label for = "course">Author of Published Base-Case (if different than Contributor):</label>
			<select  class = "form-control" id = "nm_author" name = "nm_author">	
			<option selected = "" disabled = ""> Select Author </option>		
			</select>
		</div>
	</div>					

	<div id = "add_auth">	
		<b>Don't see an Appropriate Published Author in the Dropdown? 
			<a href="inputAuthor.php">Add an Author</b></a>  
		
				
	</div>	
</div>	
</br>
<div class = "row" id = "unpub_author">	
	<div class = "form-group">
		<label for = "course">Author of <u> Unpublished </u> Base-Case (if different than Contributor):</label>
		<input   type="text" name="un_nm_author" id = "un_nm_author" ></p>
		
		</select>
		
	</div>
</div>					
<p>
<div class = "row">
	<p>Specific Reference (e.g. Felder 4th ex 3.2):
	<input type="text" name="spec_ref" ></p>
</div>
<p>
</br>
<p></p>
<p><input  type="submit" value="Get Problem Number" name = "submit" size="2" style = "width: 15%; background-color: #C9DE86; color: black"/>
&nbsp; &nbsp; 

	<form method = "POST">
		<input type = "submit" value="Reset Input" name = "reset"  size="2" style = "width: 10%; background-color: #FAF1BC; color: black"/> &nbsp &nbsp 
	</form>
	&nbsp; &nbsp;
<a href="QRPRepo.php"><b><font color = "blue">Cancel - to Repository </font></a></p>
</form>

	<script type="text/javascript">
	
$(document).ready(function(){
			$('#add_auth').hide();
			$('#add_concept').hide();
			
			$("#discipline").change(function(){
				
				var discipline = $("#discipline").val();
				$.ajax({
					url: 'dcData.php',
					method: 'post',
					data: 'discipline=' + discipline
				}).done(function(course){
					 course = JSON.parse(course);
					course.forEach(function(course){
						$('#course').append('<option>' + course.course_name + '</option>') 
						
					 })
				})
			})
			
			$("#course").change(function(){
				$('#add_auth').show();
				$('#add_concept').show();
				
				var course = $("#course").val();
				$.ajax({
					
					url: 'ccData.php',
					method: 'post',
					data: 'course=' + course
				}).done(function(p_concept){
					 concept = JSON.parse(p_concept);
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
						authors.forEach(function(authors){
								$('#nm_author').append('<option>' + authors.author_name + '</option>') 
						})
					});  
					 
				})
			
			$("#nm_author").change(function(){
				$('#unpub_author').hide();
			})
			$("input[name=un_nm_author]").keypress(function(){
				$('#publ_auth').hide();
			})
			
			
			
			
			})
			
		})
		
		


	</script>


</body>
</html>