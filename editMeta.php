<?php
	session_start();
	require_once "pdo.php";

	if (isset($_SESSION['username'])) {
		$username=$_SESSION['username'];
	} else {
		 $_SESSION['error'] = 'Session was lost -  please log in again';
		header('Location: QRPRepo.php');
		return;
	}

// Guardian: Make sure that problem_id is present
	if (isset($_GET['problem_id']) ) {
		$problem_id = $_GET['problem_id'];
	} elseif (isset($_POST['problem_id'])){
		$problem_id = $_POST['problem_id'];
	} else { 
	 $_SESSION['error'] = "Missing problem_id";
	  header('Location: QRPRepo.php');
	  return;
	}

	$stmt = $pdo->prepare("SELECT * FROM Problem where problem_id = :problem_id");
	$stmt->execute(array(":problem_id" => $problem_id));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if ( $row === false ) {
		$_SESSION['error'] = 'Bad value for problem_id';
		header( 'Location: QRPRepo.php' ) ;
		return;
	}
	


	if ( isset($_POST['problem_id']))  {
		
		if ( isset($_POST['title']))  {
			$sql = "UPDATE Problem SET title = :title WHERE problem_id = :problem_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':title' => htmlentities($_POST['title']),
							':problem_id' => $problem_id));
		}

		if ( isset($_POST['discipline']))  {
			$sql = "UPDATE Problem SET subject = :discipline WHERE problem_id = :problem_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':discipline' => htmlentities($_POST['discipline']),
							':problem_id' => $problem_id));
		}
		
		if ( isset($_POST['course']))  {
			$sql = "UPDATE Problem SET course = :course WHERE problem_id = :problem_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':course' => htmlentities($_POST['course']),
							':problem_id' => $problem_id));
		}
		
		if ( isset($_POST['primary_concept']))  {
			$sql = "UPDATE Problem SET primary_concept = :primary_concept WHERE problem_id = :problem_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':primary_concept' => htmlentities($_POST['primary_concept']),
							':problem_id' => $problem_id));
		}
		
		if ( isset($_POST['secondary_concept']))  {
			$sql = "UPDATE Problem SET secondary_concept = :secondary_concept WHERE problem_id = :problem_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':secondary_concept' => htmlentities($_POST['secondary_concept']),
							':problem_id' => $problem_id));
		}
		
		if ( isset($_POST['tertiary_concept']))  {
			$sql = "UPDATE Problem SET tertiary_concept = :tertiary_concept WHERE problem_id = :problem_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':tertiary_concept' => htmlentities($_POST['tertiary_concept']),
							':problem_id' => $problem_id));
		}
		
		if ( isset($_POST['computation_name']))  {
			$sql = "UPDATE Problem SET computation_name = :computation_name WHERE problem_id = :problem_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':computation_name' => htmlentities($_POST['computation_name']),
							':problem_id' => $problem_id));
		}
		
		if ( isset($_POST['nm_author']))  {
			$sql = "UPDATE Problem SET nm_author = :nm_author WHERE problem_id = :problem_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':nm_author' => htmlentities($_POST['nm_author']),
							':problem_id' => $problem_id));
		}
		
		if ( isset($_POST['unpubl_auth']))  {
			$sql = "UPDATE Problem SET unpubl_auth = :unpubl_auth WHERE problem_id = :problem_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':unpubl_auth' => htmlentities($_POST['unpubl_auth']),
							':problem_id' => $problem_id));
		}
		
		if ( isset($_POST['specif_ref']))  {
			$sql = "UPDATE Problem SET specif_ref = :specif_ref WHERE problem_id = :problem_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':specif_ref' => htmlentities($_POST['specif_ref']),
							':problem_id' => $problem_id));
		}
		
		if ( isset($_POST['link_to_web_full']))  {
			$sql = "UPDATE Problem SET link_to_web_full = :link_to_web_full WHERE problem_id = :problem_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':link_to_web_full' => htmlentities($_POST['link_to_web_full']),
							':problem_id' => $problem_id));
		}
		
		if ( isset($_POST['time_est_contrib']))  {
			$sql = "UPDATE Problem SET time_est_contrib = :time_est_contrib WHERE problem_id = :problem_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':time_est_contrib' => htmlentities($_POST['time_est_contrib']),
							':problem_id' => $problem_id));
		}
		
		
		
		
		
		
		
		
		
	/* 
			
		
			// put the time estimate into the database
			if (isset($_POST['time_est'])){
					$sql = "UPDATE Problem SET time_est_contrib = :timeest WHERE problem_id = :pblm_num";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':timeest' => $_POST['time_est'],
							':pblm_num' => $_POST['problem_id']));
			}	

			// put the time estimate into the database
			if (isset($_POST['diff_est'])){
					$sql = "UPDATE Problem SET diff_contrib = :diffest WHERE problem_id = :pblm_num";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':diffest' => $_POST['diff_est'],
							':pblm_num' => $_POST['problem_id']));
			}	
			if (isset($_POST['web_ref'])){
					$sql = "UPDATE Problem SET link_to_web_full = :webref WHERE problem_id = :pblm_num";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':webref' => $_POST['web_ref'],
							':pblm_num' => $_POST['problem_id']));
			}	

		
					
	
				
			
	$_SESSION['success'] = 'Record updated';
		
				
					
				// this should conserve the data already input and 
	
		
		
		
		$sql = "UPDATE Problem SET 
				title = :title,
				status = :status
				WHERE problem_id = :problem_id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':title' => $_POST['title'],
			':problem_id' => $_POST['problem_id'],
			':status' => $status));
		$_SESSION['success'] = 'Record updated';
		
		// If all fields have values we should set the status to new file
		
		header( 'Location: QRPRepo.php' ) ;
		return;
	 */
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
	</style>
	
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<meta Charset = "utf-8">
	<title>QRProblems</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 
	</head>

	<body>
	<header>
	<h2>Edit Meta Data for Problems</h2>
	</header>

	


	<p><b>Edit Problem Data for Problem <?php echo($problem_id); ?></b></p>
	<form action="" method="post" enctype="multipart/form-data">

	<p>Problem Title <input type="text" name="title" id = "title" class = "long" value="<?php echo( $row['title']); ?>"></p>
	<p>Discipline<input type="text" name="discipline" id = "discipline" value="<?php echo($row['subject']); ?>"></p>
	<p>Problem Course <input type="text" name="course" id = "course" value="<?php echo( $row['course']); ?>"></p>
	<p>Primary Concept <input type="primary_concept" name="primary_concept" id = "primary_concept" class = "long" value="<?php echo( $row['primary_concept']); ?>"></p>
	<p>Secondary Concept <input type="secondary_concept" name="secondary_concept" id = "secondary_concept" class = "long" value="<?php echo( $row['secondary_concept']); ?>"></p>
	<p>Other Descriptors <input type="tertiary_concept" name="tertiary_concept" id = "tertiary_concept" class = "long" value="<?php echo( $row['tertiary_concept']); ?>"></p>
	<p>Computational Method <input type="text" name="computation_name" id = "computation_name" value="<?php echo( $row['computation_name']); ?>"></p>
	<p>Author of Published Problem <input type="text" name="nm_author" id = "nm_author" value="<?php echo( $row['nm_author']); ?>"></p>
	<p>Author of Unpublished Problem <input type="text" name="unpubl_auth" id = "unpubl_auth" value="<?php echo( $row['unpubl_auth']); ?>"></p>
	<p>Specific Reference <input type="text" name="specif_ref" id = "specif_ref" value="<?php echo( $row['specif_ref']); ?>"></p>
	<p>Link to Web for Full Base-case solution <input type="text" name="link_to_web_full" id = "link_to_web_full" class = "long" value="<?php echo( $row['link_to_web_full']); ?>"></p>
	<p>Time Estimate by Contributor <input type="text" name="time_est_contrib" id = "time_est_contrib" value="<?php echo( $row['time_est_contrib']); ?>"></p>

	
	
	
	
	<p>


	<p><hr></p>
	<input type="hidden" name="problem_id" value="<?= $problem_id ?>">
	<p><input type="submit" value="Update" id="Update_btn"/>
	<a href="QRPRepo.php">Cancel</a></p>
	<style>#Update_btn{background-color: lightyellow }</style>
	<p><hr></p>
	
	</form>

	
	</body>
	</html>
