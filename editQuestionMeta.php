<?php
	session_start();
	require_once "pdo.php";

	// if (isset($_SESSION['username'])) {
	// 	$username=$_SESSION['username'];
	// } else {
	// 	 $_SESSION['error'] = 'Session was lost -  please log in again';
	// 	header('Location: QRPRepo.php');
	// 	die();
	// }

// Guardian: Make sure that question_id is present
	if (isset($_GET['question_id']) ) {
		$question_id = $_GET['question_id'];
	} elseif (isset($_POST['question_id'])){
		$question_id = $_POST['question_id'];
	} else { 
	 $_SESSION['error'] = "Missing question_id";
	  header('Location: QRPRepo.php');
	 die();
	}

	
	


	if ( isset($_POST['question_id']))  {
		
		if ( isset($_POST['title']))  {
			$sql = "UPDATE Question SET title = :title WHERE question_id = :question_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':title' => htmlentities($_POST['title']),
							':question_id' => $question_id));
		}

		if ( isset($_POST['discipline']))  {
			$sql = "UPDATE Question SET subject = :discipline WHERE question_id = :question_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':discipline' => htmlentities($_POST['discipline']),
							':question_id' => $question_id));
		}
		
		if ( isset($_POST['course']))  {
			$sql = "UPDATE Question SET course = :course WHERE question_id = :question_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':course' => htmlentities($_POST['course']),
							':question_id' => $question_id));
		}
		
		if ( isset($_POST['p_concept']))  {
			$sql = "UPDATE Question SET primary_concept = :primary_concept WHERE question_id = :question_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':primary_concept' => htmlentities($_POST['p_concept']),
							':question_id' => $question_id));
		}
		
		if ( isset($_POST['secondary_concept']))  {
			$sql = "UPDATE Question SET secondary_concept = :secondary_concept WHERE question_id = :question_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':secondary_concept' => htmlentities($_POST['secondary_concept']),
							':question_id' => $question_id));
		}
		
		if ( isset($_POST['tertiary_concept']))  {
			$sql = "UPDATE Question SET tertiary_concept = :tertiary_concept WHERE question_id = :question_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':tertiary_concept' => htmlentities($_POST['tertiary_concept']),
							':question_id' => $question_id));
		}
		
		
		if ( isset($_POST['nm_author']))  {
			$sql = "UPDATE Question SET nm_author = :nm_author WHERE question_id = :question_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':nm_author' => htmlentities($_POST['nm_author']),
							':question_id' => $question_id));
		}
		
		if ( isset($_POST['unpubl_auth']))  {
			$sql = "UPDATE Question SET unpubl_auth = :unpubl_auth WHERE question_id = :question_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':unpubl_auth' => htmlentities($_POST['unpubl_auth']),
							':question_id' => $question_id));
		}
		
		if ( isset($_POST['specif_ref']))  {
			$sql = "UPDATE Question SET specif_ref = :specif_ref WHERE question_id = :question_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':specif_ref' => htmlentities($_POST['specif_ref']),
							':question_id' => $question_id));
		}
		
		
		
        
	
	}
	
	$stmt = $pdo->prepare("SELECT * FROM Question where question_id = :question_id");
	$stmt->execute(array(":question_id" => $question_id));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if ( $row === false ) {
		$_SESSION['error'] = 'Bad value for question_id';
		header( 'Location: QRPRepo.php' ) ;
		return;
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
	<style>
	.long {
    width: 500px;
	}
	.short {
    width: 60px;
	}
    .text_box {
    width: 800px;
     height: 100px;
	}
	</style>
	
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<meta Charset = "utf-8">
	<title>QRQuestions</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	</head>

	<body>
	<header>
	<h2>Edit Meta Data for Questions</h2>
	</header>

	


	<p><b>Edit Question Data for Question <?php echo($question_id); ?></b></p>
	
	<form action="" method="post" enctype="multipart/form-data">
		<!-- <font color = "red"> (Caution) </font> -  indicates fields that were obtained from defined pulldown lists to avoid synonyms or one of a kind entries. -->
		<p>Question Title <input type="text" name="title" id = "title" class = "long" value="<?php echo( $row['title']); ?>"></p>


	<div class = "form-group">
		<label for = "Discipline">Discipline (e.g. Chemical Engineering):</label>
		<select class = "form-control" id = "discipline" name = "discipline">
			<?php if (isset($row['subject'])){echo '<option  selected = "" disabled = "" value = ""> '.$row['subject'].'</option>';} else { echo '<option  selected = "" disabled = "" value = ""> Select Discipline </option> ';}?>
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
	<br>

	<?php
	//  $sql = "SELECT course_id FROM `Course` WHERE course_name = :course_name ";
				
	//  $stmt = $pdo->prepare($sql);
	//   $stmt->execute(array(':course_name' => $row['course']));
	//  $courses = $stmt->fetch(PDO::FETCH_ASSOC);
	// // var_dump($courses);
	//  $course_id = $courses['course_id'];


	// echo 'course_id '.$course_id;
	?>
	<div class = "form-group">
		<label for = "Course">Course (e.g. Thermodynamics):</label>
		<select class = "form-control" id = "course" name = "course">
		<?php
		

			 if (isset($row['course'])){echo '<option id = "selected-course" selected = "" disabled = "" value = "'.$row['course'].'"> '.$row['course'].'</option>';} else { echo '<option  selected = "" disabled = "" value = ""> Select Course </option> ';}
			
				 $stmt = "SELECT * FROM `Course`";
				$stmt = $pdo->query($stmt);
				$stmt = $pdo->query("SELECT * FROM Course ORDER BY Course.course_name");
				$courses = $stmt->fetchALL(PDO::FETCH_ASSOC);
					 foreach ($courses as $course) {
						 $course_name_option = $course["course_name"];
						 $course_id = $course["course_id"];
							echo '<option  value="'. $course_name_option.'">'. $course_name_option.'</option>';
					 }
			?>
		</select>
	</div>
	
	<br>

		<!-- <p>Discipline <font color = "red"> (Caution) </font> <input type="text" name="discipline" id = "discipline" value="<?php echo($row['subject']); ?>"></p> -->



		<!-- <p>Course <font color = "red"> (Caution) </font><input type="text" name="course" id = "course" value="<?php echo( $row['course']); ?>"></p> -->
		<div class = "form-group">
		<label for = "course">Primary Concept (e.g. Conservation of Mass ):</label>
		<select  class = "form-control" id = "p_concept" name = "p_concept">	
		<option selected = "" disabled = "" value = "<?php echo( $row['primary_concept']); ?>"> <?php echo( $row['primary_concept']); ?> </option>	
		</select>
		</div>
		<!-- <p>Primary Concept <font color = "red"> (Caution) </font>  <input type="primary_concept" name="primary_concept" id = "primary_concept" class = "long" value="<?php echo( $row['primary_concept']); ?>"></p> -->
		<p>Secondary Concept <input type="secondary_concept" name="secondary_concept" id = "secondary_concept" class = "long" value="<?php echo( $row['secondary_concept']); ?>"></p>
		<p>Other Descriptors <input type="tertiary_concept" name="tertiary_concept" id = "tertiary_concept" class = "long" value="<?php echo( $row['tertiary_concept']); ?>"></p>
	
		<div class = "form-group">
		<label for = "nm_author">Author :</label>
		<select class = "form-control" id = "nm_author" name = "nm_author">
			<?php if (isset($row['nm_author'])){echo '<option  selected = "" disabled = "" value = ""> '.$row['nm_author'].'</option>';} else { echo '<option  selected = "" disabled = "" value = ""> Select Author </option> ';}
			
				 $stmt = "SELECT * FROM `Author`";
				$stmt = $pdo->query($stmt);
				$stmt = $pdo->query("SELECT * FROM Author ORDER BY Author.author_name");
				$authors = $stmt->fetchALL(PDO::FETCH_ASSOC);
					 foreach ($authors as $author) {
							echo "<option id='".$author['author_id']."' value='".$author['author_name']."'>".$author['author_name']."</option>";
					 }
			?>
		</select>
	</div>



		<!-- <p>Author of Published Question <font color = "red"> (Caution) </font>  <input type="text" name="nm_author" id = "nm_author" value="<?php echo( $row['nm_author']); ?>"></p> -->
		<p>Author of Unpublished Question <input type="text" name="unpubl_auth" id = "unpubl_auth" value="<?php echo( $row['unpubl_auth']); ?>"></p>
		<p>Specific Reference <input type="text" name="specif_ref" id = "specif_ref" value="<?php echo( $row['specif_ref']); ?>"></p>

		 
		
		<input type="hidden" name="question_id" value="<?= $question_id ?>">
		<p><input type="submit" value="Update" id="Update_btn"/>
		
	<!--	<a href="editpblm.php">Cancel</a></p>  -->
		<style>#Update_btn{background-color: lightyellow }</style>
		
		
		
	</form>
	<?php
		echo('<form action = "editquest.php" method = "GET"> <input type = "hidden" name = "question_id" value = "'.$question_id.'"><input type = "submit" value ="Cancel/Finished"></form>');
		?>
	
	<p><hr></p>

	<script type="text/javascript">

					
					let course = document.getElementById('course');
					course.addEventListener('change', get_concepts)
console.log ("course",course);

					 function get_concepts() {
						let selected_course = document.getElementById('selected-course');
						//  console.log("click");
							console.log('selected_course',selected_course.value);
							console.log ("course",course.value);

							var p_concept = document.getElementById('p_concept');
							// console.log('p_concept',p_concept);
							$.ajax({
							url: 'ccData.php',
							method: 'post',
							data: 'course=' + course.value
						}).done(function(concepts){
							concepts = JSON.parse(concepts);
							// console.log('concepts',concepts);
							while (p_concept.options.length > 0) {
								p_concept.remove(0);
							}
							concepts.forEach(function(concepts){
								let newOption = new Option(concepts.concept_name);
								p_concept.add(newOption);
							})

							});
						}

// console.log('p_concepot ',p_concept);
	</script>
	</body>
	</html>
