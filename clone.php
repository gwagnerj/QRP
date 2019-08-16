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
	if (isset($_POST['problem_id'])) {
		$problem_id=$_POST['problem_id'];
	} else {
		 $_SESSION['error'] = 'No problem_id was transfered';
		header('Location: QRPRepo.php');
		die();
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
		
	
			
	// get everything from the current row to modify the file names
		$sql = 'SELECT * FROM Problem WHERE problem_id = :problem_id';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
		':problem_id' => $pblm_num
		));
		$p_row = $stmt->fetch(PDO::FETCH_ASSOC);
		$docxfilenm = $p_row['docxfilenm'];
		$infilenm = $p_row['infilenm'];
		$soln_pblm = $p_row['soln_pblm'];
		$soln_book = $p_row['soln_book'];
		$htmlfilenm = $p_row['htmlfilenm'];
		if ($p_row['orig_contr_id']== null) {
			$orig_contr_id = $p_row['users_id'];
		} else {
			$orig_contr_id = $p_row['orig_contr_id'];	
		}
		
		// make new file names by replacing the numbers
		
		$docxfilenm_n = str_replace($problem_id,$pblm_num,$docxfilenm);
		$infilenm_n = str_replace($problem_id,$pblm_num,$infilenm);
		$soln_pblm_n = str_replace($problem_id,$pblm_num,$soln_pblm);
		$soln_book_n = str_replace($problem_id,$pblm_num,$soln_book);
		$htmlfilenm_n = str_replace($problem_id,$pblm_num,$htmlfilenm);
		// copy and rename the files
		
		if (!copy('uploads/'.$docxfilenm,'uploads/'.$docxfilenm_n)){ $_SESSION['error'] = 'did not copy docx '.$docxfilenm;}	
		if (!copy('uploads/'.$infilenm,'uploads/'.$infilenm_n)){ $_SESSION['error'] = 'did not copy inputfile '.$infilenm;}
		if (!copy('uploads/'.$soln_pblm,'uploads/'.$soln_pblm_n)){ $_SESSION['error'] = 'did not copy soln pdf'.$soln_pblm;}
		if (!copy('uploads/'.$soln_book,'uploads/'.$soln_book_n)){  $_SESSION['error'] = 'did not copy Excel file'.$soln_book;}
		if (!copy('uploads/'.$htmlfilenm,'uploads/'.$htmlfilenm_n)){ $_SESSION['error'] = 'did not copy html file'.$htmlfilenm;}
	
	// update the problem table for the new problem with the new values for the filenames 
	$sql = 'UPDATE Problem SET docxfilenm = :docxfilenm, infilenm = :infilenm, soln_pblm = :soln_pblm, soln_book = :soln_book, htmlfilenm = :htmlfilenm, parent = :parent, users_id = :users_id, orig_contr_id = :orig_contr_id WHERE problem_id = :problem_id';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(
		':problem_id' => $pblm_num,
		':docxfilenm' => $docxfilenm_n,
		':infilenm' => $infilenm_n,
		':soln_pblm' => $soln_pblm_n,
		':soln_book' => $soln_book_n,
		':htmlfilenm' => $htmlfilenm_n,
		':parent' => $problem_id,
		':users_id' => $user_id,
		':orig_contr_id' => $orig_contr_id
		));
	
	// copy the directory that may contain the image files
	$dirnm = str_replace(".htm","_files",$htmlfilenm);
		$regex = '/p[0-9]*_ht_p/';
		$preg ='p';
					
		$dirnm = 'uploads/'.preg_replace($regex,$preg,$dirnm);
		//echo $dirnm;
		//echo '</br>';
		$dirnm_n = str_replace(".htm","_files",$htmlfilenm_n);
							
		$dirnm_n = 'uploads/'.preg_replace($regex,$preg,$dirnm_n);
		//echo $dirnm_n;
					
		mkdir($dirnm_n);
		// copy all the files out of the old directory to the new			
		
	 // this function comes from https://stackoverflow.com/questions/2050859/copy-entire-contents-of-a-directory-to-another-using-php
		function recurse_copy($src,$dst) { 
			$dir = opendir($src); 
			@mkdir($dst); 
			while(false !== ( $file = readdir($dir)) ) { 
				if (( $file != '.' ) && ( $file != '..' )) { 
					if ( is_dir($src . '/' . $file) ) { 
						recurse_copy($src . '/' . $file,$dst . '/' . $file); 
					} 
					else { 
						copy($src . '/' . $file,$dst . '/' . $file); 
					} 
				} 
			} 
			closedir($dir); 
		} 
	
		recurse_copy($dirnm,$dirnm_n);
		
		
		// copy the answers to the qa table and the input to the input table  will just input them from the file by the same code as in editpblm.php
		
			$sql = 'CREATE TEMPORARY TABLE tmptable_1 SELECT * FROM Qa WHERE problem_id = :problem_id;
				UPDATE tmptable_1 SET qa_id = NULL, problem_id = :pblm_num; 
				INSERT INTO Qa SELECT * FROM tmptable_1; 
				DROP TEMPORARY TABLE IF EXISTS tmptable_1;
			'; 
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':problem_id' => $problem_id,
				':pblm_num' => $pblm_num
				));
		
		
			$sql = 'CREATE TEMPORARY TABLE tmptable_1 SELECT * FROM Input WHERE problem_id = :problem_id ;
				UPDATE tmptable_1 SET input_id = NULL, problem_id = :pblm_num; 
				INSERT INTO Input SELECT * FROM tmptable_1; 
				DROP TEMPORARY TABLE IF EXISTS tmptable_1;
			'; 
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':problem_id' => $problem_id,
				':pblm_num' => $pblm_num
				));
		
		
		//  append the pblm_num to the children field of the old problem record
		$sql = 'UPDATE Problem SET children = CONCAT(children,:child) WHERE problem_id = :problem_id';
		$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':problem_id' => $problem_id,
				':child' => $pblm_num.", "
				));
		
		
		
		
		
		
		$_SESSION['success'] = 'problem '.$problem_id.' was cloned to problem '.$pblm_num;
		header('Location: QRPRepo.php');
		die();
		
	
	
	
	
	
	
	
	
	
	
	/* 
	
	
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
	<h3> Clone Problem - duplicates problem <?php echo ($problem_id) ?> giving it a new problem number under your name. </br> You will then be able to edit this new problem.
	  Please only clone a problem if you intend to edit it.</h3>
	<h4> <font color = "orange" > Note - the statistics for the clone will be removed when edited. </font></h4>
	<form action = "clone.php" method = "post">
	<p><input  type="submit" name = "submit" value="Clone Problem"/>
	<input  type="hidden" value=<?php echo ($problem_id) ?> name = "problem_id"/>
	&nbsp; &nbsp; 
	<a href="QRPRepo.php"><b><font color = "blue">Cancel </font></a></p>
	</form>
	
	
	</body>
	
	
	
	
	</html>