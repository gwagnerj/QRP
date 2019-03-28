<?php
require_once "pdo.php";
session_start();
$username=$_SESSION['username'];

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
	if ( isset($_POST['title']) ) {

		// Data validation
		if ( strlen($_POST['title']) < 5 ) {
			$_SESSION['error'] = 'Please include a longer title';
			header("Location: QRPRepo.php");
			return;
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
		
		

	  
	  $sql = "INSERT INTO Problem (users_id, title, nm_author, game_prob_flag, subject, course, primary_concept, secondary_concept, status, specif_ref)	
	  VALUES (:users_id, :title,:nm_author, :game_prob_flag, :subject, :course, :primary_concept, :secondary_concept,:status, :specref)";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':users_id' => $users_id,
				':title' => $_POST['title'],
				':nm_author' => $nm_author,				
				':game_prob_flag' => $game_prob_flag,
				':subject' => $_POST['subject'],
				':course' => $_POST['course'],
				':primary_concept' => $_POST['p_concept'],
				':secondary_concept' => $_POST['s_concept'],
				':status' => 'num issued',
				':specref' => $_POST['spec_ref']));
				
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
			
				$_SESSION['success'] = 'your problem number is '.$pblm_num;
				$_SESSION['game_prob_flag']=$game_prob_flag;
				$file_name = 'p'.$pblm_num.'_'.$game_prob_flag.'_'.$_POST['title'];
				$_SESSION['file_name']=$file_name;
				header( 'Location: downloadDocx.php' ) ;
				return;
				
				
	 
	}
	else {
			$_SESSION['error'] = 'All inputs are required';
			header("Location: QRPRepo.php");
			return;
	}


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
</head>

<body>
<header>
<h2>Quick Response Problems</h2>
</header>


<p>Please provide:</p>
<form  method="POST" >
<p></p>

<p>a Problem Title:
<input type="text" name="title" ></p>
<p> The Author of the Base-Case (if different than Contributor):
<input type="text" name="nm_author" ></p>
<p>

<p>Specific Reference (e.g. Felder 4th ex 3.2):
<input type="text" name="spec_ref" ></p>

<p>Discipline (e.g. Chemical Engineering):
<input type="text" name="subject" ></p>

<p>Course Name (e.g. Thermodynamics):
<input type="text" name="course" ></p>


<?php

	echo ('<p>Primary Concept: ');
	 $stmt = "SELECT * FROM `concept`";
	 $stmt2 = $pdo->query($stmt);
		 echo "<select name='p_concept'>";
		 while ( $row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
			echo "<option value='" . $row['concept_name'] . "'>" . $row['concept_name'] . "</option>";
		}
	echo "</select>";
	
	echo ('<p>Secondary Concept');
	 $stmt = "SELECT * FROM `concept`";
	 $stmt2 = $pdo->query($stmt);
		 echo "<select name='s_concept'>";
		 while ( $row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
			echo "<option value='" . $row['concept_name'] . "'>" . $row['concept_name'] . "</option>";
		}
	echo "</select>";
	
?>


</br>

</br>
	<b>Don't see an Appropriate Concept in the Dropdown? 
	<a href="inputConcept.php">Input Concept</b></a>

<!-- <input type="checkbox" name="game" Value = "checked"> This is a Game Problem</p> -->
<!--<label> School:
		<select required name = "s_name">
			<option> --Select the School or Organization (Required)--</option>
			<?php foreach ($s_name as $values){?>
			<option><?php echo $values;?></option>
			<?php }?>
		</select>
	</label> -->
<p></p>
<p><input  type="submit" value="Get Problem Number"/>

<a href="QRPRepo.php">Cancel</a></p>
</form>
</body>
</html>