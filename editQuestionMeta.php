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
		
		if ( isset($_POST['primary_concept']))  {
			$sql = "UPDATE Question SET primary_concept = :primary_concept WHERE question_id = :question_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':primary_concept' => htmlentities($_POST['primary_concept']),
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
		
		if ( isset($_POST['computation_name']))  {
			$sql = "UPDATE Question SET computation_name = :computation_name WHERE question_id = :question_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':computation_name' => htmlentities($_POST['computation_name']),
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
	</head>

	<body>
	<header>
	<h2>Edit Meta Data for Questions</h2>
	</header>

	


	<p><b>Edit Question Data for Question <?php echo($question_id); ?></b></p>
	
	<form action="" method="post" enctype="multipart/form-data">
		<font color = "red"> (Caution) </font> -  indicates fields that were obtained from defined pulldown lists to avoid synonyms or one of a kind entries.
		<p>Question Title <input type="text" name="title" id = "title" class = "long" value="<?php echo( $row['title']); ?>"></p>
		<p>Discipline <font color = "red"> (Caution) </font> <input type="text" name="discipline" id = "discipline" value="<?php echo($row['subject']); ?>"></p>
		<p>Course <font color = "red"> (Caution) </font><input type="text" name="course" id = "course" value="<?php echo( $row['course']); ?>"></p>
		<p>Primary Concept <font color = "red"> (Caution) </font>  <input type="primary_concept" name="primary_concept" id = "primary_concept" class = "long" value="<?php echo( $row['primary_concept']); ?>"></p>
		<p>Secondary Concept <input type="secondary_concept" name="secondary_concept" id = "secondary_concept" class = "long" value="<?php echo( $row['secondary_concept']); ?>"></p>
		<p>Other Descriptors <input type="tertiary_concept" name="tertiary_concept" id = "tertiary_concept" class = "long" value="<?php echo( $row['tertiary_concept']); ?>"></p>
		<p>Author of Published Question <font color = "red"> (Caution) </font>  <input type="text" name="nm_author" id = "nm_author" value="<?php echo( $row['nm_author']); ?>"></p>
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
	</body>
	</html>
