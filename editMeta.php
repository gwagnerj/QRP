<?php
	session_start();
	require_once "pdo.php";

	if (isset($_SESSION['username'])) {
		$username=$_SESSION['username'];
	} else {
		 $_SESSION['error'] = 'Session was lost -  please log in again';
		header('Location: QRPRepo.php');
		die();
	}

// Guardian: Make sure that problem_id is present
	if (isset($_GET['problem_id']) ) {
		$problem_id = $_GET['problem_id'];
	} elseif (isset($_POST['problem_id'])){
		$problem_id = $_POST['problem_id'];
	} else { 
	 $_SESSION['error'] = "Missing problem_id";
	  header('Location: QRPRepo.php');
	 die();
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
		
		if ( isset($_POST['sug_hints']))  {
			$sql = "UPDATE Problem SET sug_hints = :sug_hints WHERE problem_id = :problem_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':sug_hints' => htmlentities($_POST['sug_hints']),
							':problem_id' => $problem_id));
		}
		
		if ( isset($_POST['prob_comments']))  {
			$sql = "UPDATE Problem SET prob_comments = :prob_comments WHERE problem_id = :problem_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':prob_comments' => htmlentities($_POST['prob_comments']),
							':problem_id' => $problem_id));
		}
		
		
		$units_titles = array("units_a","units_b","units_c","units_d","units_e","units_f","units_g","units_h","units_i","units_j");
		for ($j=0;$j<=9;$j++){
			$unit=$units_titles[$j];
			if ( isset($_POST[$unit]))  {
				$sql = "UPDATE Problem SET ".$unit." = :xyz WHERE problem_id = :problem_id";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(array(
								':xyz' => htmlentities($_POST[$unit]),
								':problem_id' => $problem_id));
			}
		}
		
		$tol_titles = array("tol_a","tol_b","tol_c","tol_d","tol_e","tol_f","tol_g","tol_h","tol_i","tol_j");
		for ($j=0;$j<=9;$j++){
			$tol=$tol_titles[$j];
			if ( isset($_POST[$tol]))  {
				$sql = "UPDATE Problem SET ".$tol." = :xyz WHERE problem_id = :problem_id";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(array(
								':xyz' => htmlentities($_POST[$tol]),
								':problem_id' => $problem_id));
			}
		}
		
		
		
		
	
	}
	
	$stmt = $pdo->prepare("SELECT * FROM Problem where problem_id = :problem_id");
	$stmt->execute(array(":problem_id" => $problem_id));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if ( $row === false ) {
		$_SESSION['error'] = 'Bad value for problem_id';
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
		<font color = "red"> (Caution) </font> -  indicates fields that were obtained from defined pulldown lists to avoid synonyms or one of a kind entries.
		<p>Problem Title <input type="text" name="title" id = "title" class = "long" value="<?php echo( $row['title']); ?>"></p>
		<p>Discipline <font color = "red"> (Caution) </font> <input type="text" name="discipline" id = "discipline" value="<?php echo($row['subject']); ?>"></p>
		<p>Course <font color = "red"> (Caution) </font><input type="text" name="course" id = "course" value="<?php echo( $row['course']); ?>"></p>
		<p>Primary Concept <font color = "red"> (Caution) </font>  <input type="primary_concept" name="primary_concept" id = "primary_concept" class = "long" value="<?php echo( $row['primary_concept']); ?>"></p>
		<p>Secondary Concept <input type="secondary_concept" name="secondary_concept" id = "secondary_concept" class = "long" value="<?php echo( $row['secondary_concept']); ?>"></p>
		<p>Other Descriptors <input type="tertiary_concept" name="tertiary_concept" id = "tertiary_concept" class = "long" value="<?php echo( $row['tertiary_concept']); ?>"></p>
		<p>Computational Method <font color = "red"> (Caution) </font>  <input type="text" name="computation_name" id = "computation_name" value="<?php echo( $row['computation_name']); ?>"></p>
		<p>Author of Published Problem <font color = "red"> (Caution) </font>  <input type="text" name="nm_author" id = "nm_author" value="<?php echo( $row['nm_author']); ?>"></p>
		<p>Author of Unpublished Problem <input type="text" name="unpubl_auth" id = "unpubl_auth" value="<?php echo( $row['unpubl_auth']); ?>"></p>
		<p>Specific Reference <input type="text" name="specif_ref" id = "specif_ref" value="<?php echo( $row['specif_ref']); ?>"></p>
		<p>Link to Web for Full Base-case solution <input type="text" name="link_to_web_full" id = "link_to_web_full" class = "long" value="<?php echo( $row['link_to_web_full']); ?>"></p>
		<p>Time Estimate by Contributor <input type="text" name="time_est_contrib" id = "time_est_contrib" value="<?php echo( $row['time_est_contrib']); ?>"></p>
		<p>Suggested Hints by Students <input type="textarea" class = "long" name="sug_hints" id = "sug_hints" value="<?php echo( $row['sug_hints']); ?>"></p>
		<p>Student Comments on Problem <input type="text" class = "long" name="prob_comments" id = "prob_comments" value="<?php echo( $row['prob_comments']); ?>"></p>

		<table id="units_table" class = "unitsTab" border="1" >
			<caption><font size = "+1"> Units for Each Part: </font> </caption>
			 <thead>
				 </td><th> a </th>
				 <th> b </th>
				 <th> c </th>
				 <th> d </th>
				 <th> e </th>
				 <th> f </th>
				 <th> g </th>
				 <th> h </th>
				 <th> i </th>
				 <th> j </th><tr>
			 <tbody>
				 <td> <input type = "text" name = "units_a" class = "short" id = "units_a" value = " <?php echo($row['units_a']);?>" </td>
				 <td> <input type = "text" name = "units_b" class = "short" id = "units_b" value = " <?php echo($row['units_b']);?>" </td>
				 <td> <input type = "text" name = "units_c" class = "short" id = "units_c" value = " <?php echo($row['units_c']);?>" </td>
				 <td> <input type = "text" name = "units_d" class = "short" id = "units_d" value = " <?php echo($row['units_d']);?>" </td>
				 <td> <input type = "text" name = "units_e" class = "short" id = "units_e" value = " <?php echo($row['units_e']);?>" </td>
				 <td> <input type = "text" name = "units_f" class = "short" id = "units_f" value = " <?php echo($row['units_f']);?>" </td>
				 <td> <input type = "text" name = "units_g" class = "short" id = "units_g" value = " <?php echo($row['units_g']);?>" </td>
				 <td> <input type = "text" name = "units_h" class = "short" id = "units_h" value = " <?php echo($row['units_h']);?>" </td>
				 <td> <input type = "text" name = "units_i" class = "short" id = "units_i" value = " <?php echo($row['units_i']);?>" </td>
				 <td> <input type = "text" name = "units_j" class = "short" id = "units_j" value = " <?php echo($row['units_j']);?>" </td>
			 </tbody>
		 </table>
		 <p> </p>
		 <table id="tol_table" class = "tolTab" border="1" >
			<caption><font size = "+1"> Tolerance for Each Part (15 = 1.5%): </font> </caption>
			 <thead>
				 </td><th> a </th>
				 <th> b </th>
				 <th> c </th>
				 <th> d </th>
				 <th> e </th>
				 <th> f </th>
				 <th> g </th>
				 <th> h </th>
				 <th> i </th>
				 <th> j </th><tr>
			 <tbody>
				 <td> <input type = "text" name = "tol_a" class = "short" id = "tol_a" value = " <?php echo($row['tol_a']);?>" </td>
				 <td> <input type = "text" name = "tol_b" class = "short" id = "tol_b" value = " <?php echo($row['tol_b']);?>" </td>
				 <td> <input type = "text" name = "tol_c" class = "short" id = "tol_c" value = " <?php echo($row['tol_c']);?>" </td>
				 <td> <input type = "text" name = "tol_d" class = "short" id = "tol_d" value = " <?php echo($row['tol_d']);?>" </td>
				 <td> <input type = "text" name = "tol_e" class = "short" id = "tol_e" value = " <?php echo($row['tol_e']);?>" </td>
				 <td> <input type = "text" name = "tol_f" class = "short" id = "tol_f" value = " <?php echo($row['tol_f']);?>" </td>
				 <td> <input type = "text" name = "tol_g" class = "short" id = "tol_g" value = " <?php echo($row['tol_g']);?>" </td>
				 <td> <input type = "text" name = "tol_h" class = "short" id = "tol_h" value = " <?php echo($row['tol_h']);?>" </td>
				 <td> <input type = "text" name = "tol_i" class = "short" id = "tol_i" value = " <?php echo($row['tol_i']);?>" </td>
				 <td> <input type = "text" name = "tol_j" class = "short" id = "tol_j" value = " <?php echo($row['tol_j']);?>" </td>
			 </tbody>
		 </table>
		 
		
		<p>


		<p><hr></p>
		<input type="hidden" name="problem_id" value="<?= $problem_id ?>">
		<p><input type="submit" value="Update" id="Update_btn"/>
		
	<!--	<a href="editpblm.php">Cancel</a></p>  -->
		<style>#Update_btn{background-color: lightyellow }</style>
		
		
		
	</form>
	<?php
		echo('<form action = "editpblm.php" method = "GET"> <input type = "hidden" name = "problem_id" value = "'.$problem_id.'"><input type = "submit" value ="Cancel/Finished"></form>');
		?>
	
	<p><hr></p>
	</body>
	</html>
