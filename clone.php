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
	if (isset($_POST['problem_id'])) {
		$problem_id=$_POST['problem_id'];
	} else {
		 $_SESSION['error'] = 'No problem_id was transfered';
		header('Location: QRPRepo.php');
		return;
	}
	
	if (isset($_POST['submit'])){
		
		// get the users_id
		$sql = 'SELECT * FROM Users WHERE username = :username';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
		':username' => $username
		));
		$user_row = $stmt->fetch(PDO::FETCH_ASSOC);
		$user_id = $user_row['users_id'];
	
	// Request a problem number duplicating all of the information from the current problem
	 echo $problem_id;
	// get the data from the problem
	/* 
			UPDATE tmptable_1 SET problem_id = NULL; 
			INSERT INTO Problem SELECT * FROM tmptable_1; 
			DROP TEMPORARY TABLE IF EXISTS tmptable_1;';
	 */
	// just duplicate the entire row this is modified  from https://stackoverflow.com/questions/4039748/in-mysql-can-i-copy-one-row-to-insert-into-the-same-table
	
	
	$sql = 'CREATE TEMPORARY TABLE tmptable_1 SELECT * FROM Problem WHERE problem_id = :problem_id;
			UPDATE tmptable_1 SET problem_id = NULL; 
			INSERT INTO Problem SELECT * FROM tmptable_1; 
			DROP TEMPORARY TABLE IF EXISTS tmptable_1;
		'; 
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':problem_id' => $problem_id
			));
	
	// get the last inserted row
		$sql = 'SELECT LAST_INSERT_ID()';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
				
		));
		$row2 = $stmt->fetch(PDO::FETCH_ASSOC);
		// print_r ($row2);
		
	
		$pblm_num=$row2['LAST_INSERT_ID()'];
		
	
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
	
	
	/* 
	$sql = "SELECT * FROM Problem JOIN Qa ON ( Qa.problem_id=Problem.problem_id AND Problem.problem_id=:problem_id )";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
		':problem_id' => $problem_id
		
		));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
	
	
		// insert this into the data into the Problem table
	 $game_prob_flag=0;
	  $sql = "INSERT INTO Problem (users_id, title, nm_author, game_prob_flag, subject, course, primary_concept, secondary_concept,tertiary_concept, status, specif_ref, computation_name, unpubl_auth )	
	  VALUES (:users_id, :title,:nm_author, :game_prob_flag, :subject, :course, :primary_concept, :secondary_concept, :tertiary_concept, :status, :specref, :computation, :unpubl_auth)";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':users_id' => $users_id,
				':title' => $row['title'],
				':nm_author' => $row['nm_author'],				
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
	 */
	}
	?>
	
	<html>
	<head>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<meta Charset = "utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 
	
	</head>
	
	<body>
	<header>
	<h2>Clone a Problem</h2>
	</header>
	<h3> Clone Problem - duplicates problem <?php echo ($problem_id) ?> giving it a new problem number under your name. </br> You will then be able to edit this new problem.</h3>
	<form action = "clone.php" method = "post">
	<p><input  type="submit" name = "submit" value="Clone Problem"/>
	<input  type="hidden" value=<?php echo ($problem_id) ?> name = "problem_id"/>
	&nbsp; &nbsp; 
	<a href="QRPRepo.php"><b><font color = "blue">Cancel </font></a></p>
	</form>
	
	
	</body>
	
	
	
	
	</html>