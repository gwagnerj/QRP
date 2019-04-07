<?php
session_start();
require_once "pdo.php";

if ( isset($_POST['name']) or isset($_POST['email'])
     or isset($_POST['title'])  ) {

   // Data validation
	
	if ( strlen($_POST['title']) < 1) {
        $_SESSION['error'] = 'Valid title missing';
        header("Location: editpblm.php?problem_id=".$_POST['problem_id']);
        return;
    }

   
	$problem_id=$_POST['problem_id'];

	
	//Get the filename from the docxfile that was uploaded
	
		if($_FILES['docxfile']['name']) {
			$filename=explode(".",$_FILES['docxfile']['name']); // divides the file into its name and extension puts it into an array
				if ($filename[1]=='docx'){ // this is the extension
					$docxfile=addslashes($_FILES['docxfile']['tmp_name']);
					$docxname=addslashes($_FILES['docxfile']['name']);
					$docxfile=file_get_contents($docxfile);
					
	//this code needs work			
					$docxname = $_FILES['docxfile']['name'];
					$tmp_docxname =  $_FILES['docxfile']['tmp_name'];
					$location = "uploads/"; // This is the local file directory name where the files get saved
				}
				
				
				// insert into problems with temporary file names for the docx, input data and pdf file
				$sql = "UPDATE Problem SET  docxfilenm = :docxfilenm 	
				WHERE problem_id=:problem_id";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(array(':docxfilenm'=> $docxname,	':problem_id' => $_POST['problem_id']));
				
				
				if (fnmatch("P*_d_*",$docxname,FNM_CASEFOLD ) ){ // ignore the case when matching
						$newDocxNm = $docxname;
				}
				else if($docxname!==""){
						$newDocxNm = "P".$problem_id."_d_".$docxname;
				} else {
					$newDocxNm = "P".$problem_id."_d_problemStatement.docx";
				}
				
				$sql = "UPDATE Problem SET docxfilenm = :newDocxNm WHERE problem_id = :pblm_num";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':newDocxNm' => $newDocxNm,
					':pblm_num' => $_POST['problem_id']));
				
			// now upload docx, input and pdf files
				$pathName = 'uploads/'.$newDocxNm;
				if (move_uploaded_file($_FILES['docxfile']['tmp_name'], $pathName)){
					$_SESSION['success'] = $_SESSION['success'].'DocxFile upload successful';
				}
		}  


//Get the filename from the pdffile (base-case) that was uploaded
		if($_FILES['pdffile']['name']) {
			$filename=explode(".",$_FILES['pdffile']['name']); // divides the file into its name and extension puts it into an array
			if ($filename[1]=='pdf'){ // this is the extension
				$pdffile=addslashes($_FILES['pdffile']['tmp_name']);
				$pdfname=addslashes($_FILES['pdffile']['name']);
				$pdffile=file_get_contents($pdffile);
				$pdfname = $_FILES['pdffile']['name'];
				$tmp_pdfname =  $_FILES['pdffile']['tmp_name'];
				$location = "uploads/"; // This is the local file directory name where the files get saved
			}

			$sql = "UPDATE Problem SET  pdffilenm = :pdffilenm 	
					WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':pdffilenm'=> $pdfname,	':problem_id' => $_POST['problem_id']));

			if (fnmatch("P*_p_*",$pdfname,FNM_CASEFOLD ) ){
					$newPdfNm = $pdfname;
			}
			elseif($pdfname !=="" ) {
					$newPdfNm = "P".$problem_id."_p_".$pdfname;
			} else {
					$newPdfNm = "P".$problem_id."_p_basecase.pdf";
			}
		
			$sql = "UPDATE Problem SET pdffilenm = :newPdfNm WHERE problem_id = :pblm_num";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':newPdfNm' => $newPdfNm,
				':pblm_num' => $_POST['problem_id']));
		
		//upload file
			$pathName = 'uploads/'.$newPdfNm;
			if (move_uploaded_file($_FILES['pdffile']['tmp_name'], $pathName)){
				
				$_SESSION['success'] = $_SESSION['success'].'PdfFile upload successful';
			}
		} 
		
		//Get the filename from the solnfile if it was uploaded
		if($_FILES['solnfile']['name']) {
			$filename=explode(".",$_FILES['solnfile']['name']); // divides the file into its name and extension puts it into an array
			if ($filename[1]=='pdf'){ // this is the extension
				$solnfile=addslashes($_FILES['solnfile']['tmp_name']);
				$solnname=addslashes($_FILES['solnfile']['name']);
				$solnfile=file_get_contents($solnfile);
				$solnname = $_FILES['solnfile']['name'];
				$tmp_solnname =  $_FILES['solnfile']['tmp_name'];
				$location = "uploads/"; // This is the local file directory name where the files get saved
			}
		
			$sql = "UPDATE Problem SET  soln_pblm = :solnfilenm 	
					WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':solnfilenm'=> $solnname,	':problem_id' => $_POST['problem_id']));
			
			if (fnmatch("P*_s_*",$solnname,FNM_CASEFOLD ) ){
				$newSolnNm = $solnname;
			}
			else if ($solnname !=="" ){
				$newSolnNm = "P".$problem_id."_s_".$solnname;
			} else {
				$newSolnNm = "P".$problem_id."_s_solnfile.pdf";
			}
	
			$sql = "UPDATE Problem SET soln_pblm = :newSolnNm WHERE problem_id = :pblm_num";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':newSolnNm' => $newSolnNm,
				':pblm_num' => $_POST['problem_id']));
	
			$pathName = 'uploads/'.$newSolnNm;
			if (move_uploaded_file($_FILES['solnfile']['tmp_name'], $pathName)){
				
				$_SESSION['success'] = $_SESSION['success'].'solnFile upload successful';
			}
		} 
	//Get the solution book name  that was uploaded
		if($_FILES['solnbook']['name']) {
			$filename=explode(".",$_FILES['solnbook']['name']); // divides the file into its name and extension puts it into an array
			if ($filename[1]=='xlsm'){ // this is the extension
				$xlsmfile=addslashes($_FILES['solnbook']['tmp_name']);
				$xlsmname=addslashes($_FILES['solnbook']['name']);
				$xlsmfile=file_get_contents($xlsmfile);
				$xlsmname = $_FILES['solnbook']['name'];
				$tmp_xlsmname =  $_FILES['solnbook']['tmp_name'];
				$location = "uploads/"; // This is the local file directory name where the files get saved
			}

			$sql = "UPDATE Problem SET  soln_book = :xlsmfilenm 	
					WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':xlsmfilenm'=> $xlsmname,	':problem_id' => $_POST['problem_id']));

			if (fnmatch("P*_x_*",$xlsmname,FNM_CASEFOLD ) ){
					$newxlsmNm = $xlsmname;
			}
			elseif($xlsmfname !=="" ) {
					$newxlsmNm = "P".$problem_id."_x_".$xlsmname;
			} else {
					$newxlsmNm = "P".$problem_id."_x_solnbook.xlsm";
			}
		
			$sql = "UPDATE Problem SET soln_book = :newxlsmNm WHERE problem_id = :pblm_num";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':newxlsmNm' => $newxlsmNm,
				':pblm_num' => $_POST['problem_id']));
		
		//upload file
			$pathName = 'uploads/'.$newxlsmNm;
			if (move_uploaded_file($_FILES['solnbook']['tmp_name'], $pathName)){
				
				$_SESSION['success'] = $_SESSION['success'].'xlsmFile upload successful';
			}
		} 	
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

		// now get input data and put it as a file into the directory structure
	if($_FILES['inputdata']['name']) {
			$filename=explode(".",$_FILES['inputdata']['name']); // divides the file into its name and extension puts it into an array
			if ($filename[1]=='csv'){ // this is the extension
				
				// now put the name of the variables into the Problem table and the values into the Input Table
				$handle = fopen($_FILES['inputdata']['tmp_name'], "r");
				$lines=0;  //set this to ignore the header row in the csv file
		
						While($data=fgetcsv($handle)) {
							 If ($lines==0){
								// put the variable names in the problem table
								$sql = "UPDATE Problem SET nv_1 = :nv_1, nv_2 = :nv_2,nv_3 = :nv_3, nv_4 = :nv_4,nv_5 = :nv_5, nv_6 = :nv_6,nv_7 = :nv_7, 
										nv_8 = :nv_8, nv_9 = :nv_9,nv_10 = :nv_10, nv_11 = :nv_11,nv_12 = :nv_12, nv_13 = :nv_13,nv_14 = :nv_14
										WHERE problem_id = :pblm_num";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
										':nv_1' => $data[1],
										':nv_2' => $data[2],
										':nv_3' => $data[3],
										':nv_4' => $data[4],
										':nv_5' => $data[5],
										':nv_6' => $data[6],
										':nv_7' => $data[7],
										':nv_8' => $data[8],
										':nv_9' => $data[9],
										':nv_10' => $data[10],
										':nv_11' => $data[11],
										':nv_12' => $data[12],
										':nv_13' => $data[13],
										':nv_14' => $data[14],
										':pblm_num' => $_POST['problem_id']));
							} 
							
							If ($lines>0){
								
								// put the input values Input table
								$sql = "UPDATE Input SET problem_id = :problem_id, dex = :dex, v_1 = :v_1, v_2 = :v_2, v_3 = :v_3, v_4 = :v_4,v_5 = :v_5, v_6 = :v_6, v_7 = :v_7, 
									v_8 = :v_8, v_9 = :v_9, v_10 = :v_10, v_11 = :v_11,v_12 = :v_12, v_13 = :v_13, v_14 = :v_14 
									WHERE problem_id = :problem_id AND dex = :dex";
								
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
									':problem_id' => $_POST['problem_id'],
									':dex' => $data[0],
									':v_1' => $data[1],
									':v_2' => $data[2],
									':v_3' => $data[3],
									':v_4' => $data[4],
									':v_5' => $data[5],
									':v_6' => $data[6],
									':v_7' => $data[7],
									':v_8' => $data[8],
									':v_9' => $data[9],
									':v_10' => $data[10],
									':v_11' => $data[11],
									':v_12' => $data[12],
									':v_13' => $data[13],
									':v_14' => $data[14]));
				
							}
							$lines = $lines+1;
						}
						fclose($handle);
				
				$inputdata=addslashes($_FILES['inputdata']['tmp_name']);
				$inputname=addslashes($_FILES['inputdata']['name']);
				$inputdata=file_get_contents($inputdata);
				$inputname = $_FILES['inputdata']['name'];
				$tmp_inputname =  $_FILES['inputdata']['tmp_name'];
				$location = "uploads/"; // This is the local file directory name where the files get saved
			
		
			$sql = "UPDATE Problem SET  infilenm = :infilenm 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':infilenm'=> $inputname,	':problem_id' => $_POST['problem_id']));

			if (fnmatch("P*_i_*",$inputname,FNM_CASEFOLD ) ){
				$newInputNm = $inputname;
			}
			else if($inputname !==""){
				$newInputNm = "P".$problem_id."_i_".$inputname;
			} else {
				$newInputNm = "P".$problem_id."_i_inputfile.csv";
			}
	
			$sql = "UPDATE Problem SET infilenm = :newInputNm WHERE problem_id = :pblm_num";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':newInputNm' => $newInputNm,
				':pblm_num' => $_POST['problem_id']));	
			
			$pathName = 'uploads/'.$newInputNm;
			if (move_uploaded_file($_FILES['inputdata']['tmp_name'], $pathName)){
				$_SESSION['success'] = $_SESSION['success'].'Input data file upload successful';
			}
			
			
		}else {$_SESSION['error']=' Input file is not a csv file';}
				
	}
			
	// html problem statement file
		if($_FILES['htmlfile']['name']) {
			$filename=explode(".",$_FILES['htmlfile']['name']); // divides the file into its name and extension puts it into an array
			
			$htmlfile=addslashes($_FILES['htmlfile']['tmp_name']);
			$htmlname=addslashes($_FILES['htmlfile']['name']);
			$htmlfile=file_get_contents($htmlfile);
			$htmlname = $_FILES['htmlfile']['name'];
			$tmp_htmlfile =  $_FILES['htmlfile']['tmp_name'];
			$location = "uploads/"; // This is the local file directory name where the files get saved
			
			$sql = "UPDATE Problem SET  htmlfilenm = :htmlfilenm 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':htmlfilenm'=> $htmlname,	':problem_id' => $_POST['problem_id']));
			
			if (fnmatch("p*_ht_*",$htmlname,FNM_CASEFOLD ) ){ // ignore the case when matching
			$newhtmlNm = $htmlname;
			}
			else if($htmlname !== ""){
				$newhtmlNm = "p".$problem_id."_ht_".$htmlname;
			} else {
				$newhtmlNm = "p".$problem_id."_ht_htmlpblm.html";
			}
			
			$sql = "UPDATE Problem SET  htmlfilenm = :htmlfilenm 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':htmlfilenm'=> $newhtmlNm,	':problem_id' => $_POST['problem_id']));
			
			$pathName = 'uploads/'.$newhtmlNm;
			if (move_uploaded_file($_FILES['htmlfile']['tmp_name'], $pathName)){
				$_SESSION['success'] = $_SESSION['success'].'Html problem statement upload successful';
			}
		}		
		
			$count = 0;
			
			//upload the subdirectory of picture files
			
			
			if(isset($_FILES['picfiles']['name'])) {
				
				$sql = " SELECT * FROM Problem where problem_id = :problem_id";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(array(
						':problem_id' => $_POST['problem_id']));
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						$htmlfilenm = $row['htmlfilenm'];
				$dirnm = str_replace(".htm","_files",$htmlfilenm);
				$regex = '/p[0-9]*_ht_p/';
				$preg ='p';
				
				$dirnm = 'uploads/'.preg_replace($regex,$preg,$dirnm);
				//echo $dirnm;
				//die();
				if(!file_exists($dirnm)){
								mkdir($dirnm);
				}			
				$dirnm = $dirnm."/";
				
				// Count # of uploaded files in array
					$total = count($_FILES['picfiles']['name']);

					// Loop through each file
					for( $i=0 ; $i < $total ; $i++ ) {

					  //Get the temp file path
					  $tmpFilePath = $_FILES['picfiles']['tmp_name'][$i];

					  //Make sure we have a file path
					  if ($tmpFilePath != ""){
						//Setup our new file path
						$newFilePath = $dirnm . $_FILES['picfiles']['name'][$i];
						/* echo $newFilePath;
						echo '<hr>';
						echo $tmpFilePath;
						Die(); */

						//Upload the file into the temp dir
						if(move_uploaded_file($tmpFilePath, $newFilePath)) {

						  //Handle other code here

						}
					  }
					}
				
			}		
			
			
			
	 	 
// hint_a file
		if($_FILES['hint_aFile']['name']) {
			$filename=explode(".",$_FILES['hint_aFile']['name']); // divides the file into its name and extension puts it into an array
			
			$hint_aFile=addslashes($_FILES['hint_aFile']['tmp_name']);
			$hint_aname=addslashes($_FILES['hint_aFile']['name']);
			$hint_aFile=file_get_contents($hint_aFile);
			$hint_aname = $_FILES['hint_aFile']['name'];
			$tmp_hint_aname =  $_FILES['hint_aFile']['tmp_name'];
			$location = "uploads/"; // This is the local file directory name where the files get saved
			
			$sql = "UPDATE Problem SET  hint_a = :hint_a 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_a'=> $hint_aname,	':problem_id' => $_POST['problem_id']));
			
			if (fnmatch("P*_ha_*",$hint_aname,FNM_CASEFOLD ) ){ // ignore the case when matching
			$newhint_aNm = $hint_aname;
			}
			else if($hint_aname !== ""){
				$newhint_aNm = "P".$problem_id."_ha_".$hint_aname;
			} else {
				$newhint_aNm = "P".$problem_id."_ha_hint_a.html";
			}
			
			$sql = "UPDATE Problem SET  hint_a = :hint_a 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_a'=> $newhint_aNm,	':problem_id' => $_POST['problem_id']));
			
			$pathName = 'uploads/'.$newhint_aNm;
			if (move_uploaded_file($_FILES['hint_aFile']['tmp_name'], $pathName)){
				$_SESSION['success'] = $_SESSION['success'].'Hint_aFile upload successful';
			}
		}

	// hint_b file
		if($_FILES['hint_bFile']['name']) {
			$filename=explode(".",$_FILES['hint_bFile']['name']); // divides the file into its name and extension puts it into an array
			
			$hint_bFile=addslashes($_FILES['hint_bFile']['tmp_name']);
			$hint_bname=addslashes($_FILES['hint_bFile']['name']);
			$hint_bFile=file_get_contents($hint_bFile);
			$hint_bname = $_FILES['hint_bFile']['name'];
			$tmp_hint_bname =  $_FILES['hint_bFile']['tmp_name'];
			$location = "uploads/"; // This is the local file directory name where the files get saved
			
			$sql = "UPDATE Problem SET  hint_b = :hint_b 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_b'=> $hint_bname,	':problem_id' => $_POST['problem_id']));
			
			if (fnmatch("P*_hb_*",$hint_bname,FNM_CASEFOLD ) ){ // ignore the case when matching
			$newhint_bNm = $hint_bname;
			}
			else if($hint_bname !== ""){
				$newhint_bNm = "P".$problem_id."_hb_".$hint_bname;
			} else {
				$newhint_bNm = "P".$problem_id."_hb_hint_b.html";
			}
			$sql = "UPDATE Problem SET  hint_b = :hint_b 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_b'=> $newhint_bNm,	':problem_id' => $_POST['problem_id']));
			
			$pathName = 'uploads/'.$newhint_bNm;
			if (move_uploaded_file($_FILES['hint_bFile']['tmp_name'], $pathName)){
				$_SESSION['success'] = $_SESSION['success'].'hint_bFile upload successful';
			}
		}	
		
// hint c file
		if($_FILES['hint_cFile']['name']) {
			$filename=explode(".",$_FILES['hint_cFile']['name']); // divides the file into its name and extension puts it into an array
			
			$hint_cFile=addslashes($_FILES['hint_cFile']['tmp_name']);
			$hint_cname=addslashes($_FILES['hint_cFile']['name']);
			$hint_cFile=file_get_contents($hint_cFile);
			$hint_cname = $_FILES['hint_cFile']['name'];
			$tmp_hint_cname =  $_FILES['hint_cFile']['tmp_name'];
			$location = "uploads/"; // This is the local file directory name where the files get saved
			
			$sql = "UPDATE Problem SET  hint_c = :hint_c 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_c'=> $hint_cname,	':problem_id' => $_POST['problem_id']));
			
			if (fnmatch("P*_hc_*",$hint_cname,FNM_CASEFOLD ) ){ // ignore the case when matching
			$newhint_cNm = $hint_cname;
			}
			else if($hint_cname !== ""){
				$newhint_cNm = "P".$problem_id."_hc_".$hint_cname;
			} else {
				$newhint_cNm = "P".$problem_id."_hc_hint_c.html";
			}
			$sql = "UPDATE Problem SET  hint_c = :hint_c 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_c'=> $newhint_cNm,	':problem_id' => $_POST['problem_id']));
			
			$pathName = 'uploads/'.$newhint_cNm;
			if (move_uploaded_file($_FILES['hint_cFile']['tmp_name'], $pathName)){
				$_SESSION['success'] = $_SESSION['success'].'hint_cFile upload successful';
			}
		}		
// hint_d file
		if($_FILES['hint_dFile']['name']) {
			$filename=explode(".",$_FILES['hint_dFile']['name']); // divides the file into its name and extension puts it into an array
			
			$hint_dFile=addslashes($_FILES['hint_dFile']['tmp_name']);
			$hint_dname=addslashes($_FILES['hint_dFile']['name']);
			$hint_dFile=file_get_contents($hint_dFile);
			$hint_dname = $_FILES['hint_dFile']['name'];
			$tmp_hint_dname =  $_FILES['hint_dFile']['tmp_name'];
			$location = "uploads/"; // This is the local file directory name where the files get saved
			
			$sql = "UPDATE Problem SET  hint_d = :hint_d 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_d'=> $hint_dname,	':problem_id' => $_POST['problem_id']));
			
			if (fnmatch("P*_hd_*",$hint_dname,FNM_CASEFOLD ) ){ // ignore the case when matching
			$newhint_dNm = $hint_dname;
			}
			else if($hint_dname !== ""){
				$newhint_dNm = "P".$problem_id."_hd_".$hint_dname;
			} else {
				$newhint_dNm = "P".$problem_id."_hd_hint_d.html";
			}
			$sql = "UPDATE Problem SET  hint_d = :hint_d 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_d'=> $newhint_dNm,	':problem_id' => $_POST['problem_id']));
			
			$pathName = 'uploads/'.$newhint_dNm;
			if (move_uploaded_file($_FILES['hint_dFile']['tmp_name'], $pathName)){
				$_SESSION['success'] = $_SESSION['success'].'hint_dFile upload successful';
			}
		}		
		
	// hint_e file
		if($_FILES['hint_eFile']['name']) {
			$filename=explode(".",$_FILES['hint_eFile']['name']); // divides the file into its name and extension puts it into an array
			
			$hint_eFile=addslashes($_FILES['hint_eFile']['tmp_name']);
			$hint_ename=addslashes($_FILES['hint_eFile']['name']);
			$hint_eFile=file_get_contents($hint_eFile);
			$hint_ename = $_FILES['hint_eFile']['name'];
			$tmp_hint_ename =  $_FILES['hint_eFile']['tmp_name'];
			$location = "uploads/"; // This is the local file directory name where the files get saved
			
			$sql = "UPDATE Problem SET  hint_e = :hint_e 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_e'=> $hint_ename,	':problem_id' => $_POST['problem_id']));
			
			if (fnmatch("P*_he_*",$hint_ename,FNM_CASEFOLD ) ){ // ignore the case when matching
			$newhint_eNm = $hint_ename;
			}
			else if($hint_ename !== ""){
				$newhint_eNm = "P".$problem_id."_he_".$hint_ename;
			} else {
				$newhint_eNm = "P".$problem_id."_he_hint_e.html";
			}
			$sql = "UPDATE Problem SET  hint_e = :hint_e 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_e'=> $newhint_eNm,	':problem_id' => $_POST['problem_id']));
			
			$pathName = 'uploads/'.$newhint_eNm;
			if (move_uploaded_file($_FILES['hint_eFile']['tmp_name'], $pathName)){
				$_SESSION['success'] = $_SESSION['success'].'hint_eFile upload successful';
			}
		}
// hint_f file
		if($_FILES['hint_fFile']['name']) {
			$filename=explode(".",$_FILES['hint_fFile']['name']); // divides the file into its name and extension puts it into an array
			
			$hint_fFile=addslashes($_FILES['hint_fFile']['tmp_name']);
			$hint_fname=addslashes($_FILES['hint_fFile']['name']);
			$hint_fFile=file_get_contents($hint_fFile);
			$hint_fname = $_FILES['hint_fFile']['name'];
			$tmp_hint_fname =  $_FILES['hint_fFile']['tmp_name'];
			$location = "uploads/"; // This is the local file directory name where the files get saved
			
			$sql = "UPDATE Problem SET  hint_f = :hint_f 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_f'=> $hint_fname,	':problem_id' => $_POST['problem_id']));
			
			if (fnmatch("P*_hf_*",$hint_fname,FNM_CASEFOLD ) ){ // ignore the case when matching
			$newhint_fNm = $hint_fname;
			}
			else if($hint_fname !== ""){
				$newhint_fNm = "P".$problem_id."_hf_".$hint_fname;
			} else {
				$newhint_fNm = "P".$problem_id."_hf_hint_f.html";
			}
			$sql = "UPDATE Problem SET  hint_f = :hint_f 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_f'=> $newhint_fNm,	':problem_id' => $_POST['problem_id']));
			
			$pathName = 'uploads/'.$newhint_fNm;
			if (move_uploaded_file($_FILES['hint_fFile']['tmp_name'], $pathName)){
				$_SESSION['success'] = $_SESSION['success'].'hint_fFile upload successful';
			}
		}
// hint_g file
		if($_FILES['hint_gFile']['name']) {
			$filename=explode(".",$_FILES['hint_gFile']['name']); // divides the file into its name and extension puts it into an array
			
			$hint_gFile=addslashes($_FILES['hint_gFile']['tmp_name']);
			$hint_gname=addslashes($_FILES['hint_gFile']['name']);
			$hint_gFile=file_get_contents($hint_gFile);
			$hint_gname = $_FILES['hint_gFile']['name'];
			$tmp_hint_gname =  $_FILES['hint_gFile']['tmp_name'];
			$location = "uploads/"; // This is the local file directory name where the files get saved
			
			$sql = "UPDATE Problem SET  hint_g = :hint_g 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_g'=> $hint_gname,	':problem_id' => $_POST['problem_id']));
			
			if (fnmatch("P*_hg_*",$hint_gname,FNM_CASEFOLD ) ){ // ignore the case when matching
			$newhint_gNm = $hint_gname;
			}
			else if($hint_gname !== ""){
				$newhint_gNm = "P".$problem_id."_hg_".$hint_gname;
			} else {
				$newhint_gNm = "P".$problem_id."_hg_hint_g.html";
			}
			$sql = "UPDATE Problem SET  hint_g = :hint_g 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_g'=> $newhint_gNm,	':problem_id' => $_POST['problem_id']));
			$pathName = 'uploads/'.$newhint_gNm;
			if (move_uploaded_file($_FILES['hint_gFile']['tmp_name'], $pathName)){
				$_SESSION['success'] = $_SESSION['success'].'hint_gFile upload successful';
			}
		}
// hint_h file
		if($_FILES['hint_hFile']['name']) {
			$filename=explode(".",$_FILES['hint_hFile']['name']); // divides the file into its name and extension puts it into an array
			
			$hint_hFile=addslashes($_FILES['hint_hFile']['tmp_name']);
			$hint_hname=addslashes($_FILES['hint_hFile']['name']);
			$hint_hFile=file_get_contents($hint_hFile);
			$hint_hname = $_FILES['hint_hFile']['name'];
			$tmp_hint_hname =  $_FILES['hint_hFile']['tmp_name'];
			$location = "uploads/"; // This is the local file directory name where the files get saved
			
			$sql = "UPDATE Problem SET  hint_h = :hint_h 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_h'=> $hint_hname,	':problem_id' => $_POST['problem_id']));
			
			if (fnmatch("P*_hh_*",$hint_hname,FNM_CASEFOLD ) ){ // ignore the case when matching
			$newhint_hNm = $hint_hname;
			}
			else if($hint_hname !== ""){
				$newhint_hNm = "P".$problem_id."_hh_".$hint_hname;
			} else {
				$newhint_hNm = "P".$problem_id."_hh_hint_h.html";
			}
			$sql = "UPDATE Problem SET  hint_h = :hint_h 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_h'=> $newhint_hNm,	':problem_id' => $_POST['problem_id']));
			
			$pathName = 'uploads/'.$newhint_hNm;
			if (move_uploaded_file($_FILES['hint_hFile']['tmp_name'], $pathName)){
				$_SESSION['success'] = $_SESSION['success'].'hint_hFile upload successful';
			}
		}
// hint_i file
		if($_FILES['hint_iFile']['name']) {
			$filename=explode(".",$_FILES['hint_iFile']['name']); // divides the file into its name and extension puts it into an array
			
			$hint_iFile=addslashes($_FILES['hint_iFile']['tmp_name']);
			$hint_iname=addslashes($_FILES['hint_iFile']['name']);
			$hint_iFile=file_get_contents($hint_iFile);
			$hint_iname = $_FILES['hint_iFile']['name'];
			$tmp_hint_iname =  $_FILES['hint_iFile']['tmp_name'];
			$location = "uploads/"; // This is the local file directory name where the files get saved
			
			$sql = "UPDATE Problem SET  hint_i = :hint_i 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_i'=> $hint_iname,	':problem_id' => $_POST['problem_id']));
			
			if (fnmatch("P*_hi_*",$hint_iname,FNM_CASEFOLD ) ){ // ignore the case when matching
			$newhint_iNm = $hint_iname;
			}
			else if($hint_iname !== ""){
				$newhint_iNm = "P".$problem_id."_hi_".$hint_iname;
			} else {
				$newhint_iNm = "P".$problem_id."_hi_hint_i.html";
			}
			$sql = "UPDATE Problem SET  hint_i = :hint_i 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_i'=> $newhint_iNm,	':problem_id' => $_POST['problem_id']));
			
			$pathName = 'uploads/'.$newhint_iNm;
			if (move_uploaded_file($_FILES['hint_iFile']['tmp_name'], $pathName)){
				$_SESSION['success'] = $_SESSION['success'].'hint_iFile upload successful';
			}
		}
// hint_j file
		if($_FILES['hint_jFile']['name']) {
			$filename=explode(".",$_FILES['hint_jFile']['name']); // divides the file into its name and extension puts it into an array
			
			$hint_jFile=addslashes($_FILES['hint_jFile']['tmp_name']);
			$hint_jname=addslashes($_FILES['hint_jFile']['name']);
			$hint_jFile=file_get_contents($hint_jFile);
			$hint_jname = $_FILES['hint_jFile']['name'];
			$tmp_hint_jname =  $_FILES['hint_jFile']['tmp_name'];
			$location = "uploads/"; // This is the local file directory name where the files get saved
			
			$sql = "UPDATE Problem SET  hint_j = :hint_j 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_j'=> $hint_jname,	':problem_id' => $_POST['problem_id']));
			
			if (fnmatch("P*_hj_*",$hint_jname,FNM_CASEFOLD ) ){ // ignore the case when matching
			$newhint_jNm = $hint_jname;
			}
			else if($hint_jname !== ""){
				$newhint_jNm = "P".$problem_id."_hj_".$hint_jname;
			} else {
				$newhint_jNm = "P".$problem_id."_hj_hint_j.html";
			}
			$sql = "UPDATE Problem SET  hint_j = :hint_j 	
						WHERE problem_id=:problem_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':hint_j'=> $newhint_jNm,	':problem_id' => $_POST['problem_id']));
			
			$pathName = 'uploads/'.$newhint_jNm;
			if (move_uploaded_file($_FILES['hint_jFile']['tmp_name'], $pathName)){
				$_SESSION['success'] = $_SESSION['success'].'hint_jFile upload successful';
			}
		}
	
		
			$_SESSION['success'] = 'Record updated';
	
			if($_FILES['Qa']['name']){
					$filename=explode(".",$_FILES['Qa']['name']);
					if($filename[1]=='csv') {  // this is the file extension where the 0 entry is the file name
						$handle = fopen($_FILES['Qa']['tmp_name'], "r");
						$lines=0;  //set this to ignore the header row in the csv file
		
						While($data=fgetcsv($handle)) {
							 If ($lines==0){
								// put the tolerances in the problem table
								$sql = "UPDATE Problem SET tol_a = :tol_a, tol_b = :tol_b,tol_c = :tol_c, tol_d = :tol_d, 
										tol_e = :tol_e, tol_f = :tol_f,tol_g = :tol_g, tol_h = :tol_h,tol_i = :tol_i, tol_j = :tol_j
										WHERE problem_id = :pblm_num";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
										':tol_a' => $data[1],
										':tol_b' => $data[2],
										':tol_c' => $data[3],
										':tol_d' => $data[4],
										':tol_e' => $data[5],
										':tol_f' => $data[6],
										':tol_g' => $data[7],
										':tol_h' => $data[8],
										':tol_i' => $data[9],
										':tol_j' => $data[10],
										':pblm_num' => $_POST['problem_id']));
							} 
							If ($lines==1){
								// put the units in the problem table
								$sql = "UPDATE Problem SET units_a = :units_a, units_b = :units_b,units_c = :units_c, units_d = :units_d, 
										units_e = :units_e, units_f = :units_f,units_g = :units_g, units_h = :units_h,units_i = :units_i, units_j = :units_j
										WHERE problem_id = :pblm_num";
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
										':units_a' => $data[1],
										':units_b' => $data[2],
										':units_c' => $data[3],
										':units_d' => $data[4],
										':units_e' => $data[5],
										':units_f' => $data[6],
										':units_g' => $data[7],
										':units_h' => $data[8],
										':units_i' => $data[9],
										':units_j' => $data[10],
										':pblm_num' => $_POST['problem_id']));
							} 
							If ($lines>1){
								
								// put the answer data into the data base
								$sql = "UPDATE Qa SET problem_id = :problem_id, dex = :dex, ans_a = :ans_a, ans_b = :ans_b, ans_c = :ans_c
									,ans_d = :ans_d, ans_e = :ans_e, ans_f = :ans_f, ans_g = :ans_g, ans_h = :ans_h, ans_i = :ans_i, ans_j = :ans_j,g1 = :g1, g2 = :g2, g3 = :g3
									WHERE problem_id = :problem_id AND dex = :dex";
								
								$stmt = $pdo->prepare($sql);
								$stmt->execute(array(
									':problem_id' => $_POST['problem_id'],
									':dex' => $data[0],
									':ans_a' => $data[1],
									':ans_b' => $data[2],
									':ans_c' => $data[3],
									':ans_d' => $data[4],
									':ans_e' => $data[5],
									':ans_f' => $data[6],
									':ans_g' => $data[7],
									':ans_h' => $data[8],
									':ans_i' => $data[9],
									':ans_j' => $data[10],
									':g1' => $data[11],
									':g2' => $data[12],
									':g3' => $data[13]));
				
							}
							$lines = $lines+1;
						}
						fclose($handle);
					}else {$_SESSION['error']=' Answer file is not a csv file';}
				}else {$_SESSION['error']=' Warning - Ans file not updated';}
				
			// this should conserve the data already input and 
	//die();
	$sql = "SELECT * FROM Problem JOIN Qa ON ( Qa.problem_id=Problem.problem_id AND Qa.dex=1 )";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(
	));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$status = $row['problem.status'];
	
	
	If($row['problem.game_prob_flag']==0){
		IF (($row['problem.docxfilenm']!=="NULL") AND ($row['problem.infilenm']!=="NULL") AND ($row['qa.dex']!=="NULL")){
			$status = "New Compl";
		}
	} Elseif(($row['problem.docxfilenm']!=="NULL") AND ($row['qa.dex']!=="NULL")){
		$status = "New Compl";
		
	}
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
}

// Guardian: Make sure that problem_id is present
if ( ! isset($_GET['problem_id']) ) {
  $_SESSION['error'] = "Missing problem_id";
  header('Location: QRPRepo.php');
  return;
}


$stmt = $pdo->prepare("SELECT * FROM Problem where problem_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['problem_id']));
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


$p = htmlentities($row['title']);
$gf = htmlentities($row['game_prob_flag']);
//print_r($gf);
$in = htmlentities($row['infilenm']);

$df = htmlentities($row['docxfilenm']);
$sb = htmlentities($row['soln_book']);
 
$problem_id = $row['problem_id'];

	$file_pathdocx='uploads/'.$df;
	$file_pathsb='uploads/'.$sb;

	

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

<?php	
	if(strlen($sb)>2) {
		
			echo 'Current solution workbook to this problem - click to download';
			echo "<br>";
			echo "<br>";
			echo "<a href='".$file_pathsb."'>".$sb."</a>";
			echo "<br>";
			echo "<hr>";
	}
	?>


<p>Edit Problem Meta Data</p>
<form action="" method="post" enctype="multipart/form-data">

<p>title:
<input type="text" name="title" value="<?= $p ?>"></p>
<p>


</p>
<p>Answers File: <input type='file' accept='.csv' name='Qa'/></p>

<?php if(!$gf){ // only have this input if it is not a game problem
	?>  
<p><font color="black">Input File: </font><input type='file' accept='.csv'  name='inputdata'/></p>
<?php } 
?>

<p>Problem Statement File: <input type='file' accept='.docx' name='docxfile'/></p>
<?php if(!$gf){ // only have this input if it is not a game problem
	?>  
<p>html Problem Statement File: <input type='file' accept='.htm' name='htmlfile'/></p>
<p> html Problem Associated Directory Containing Pictures (only if pictures are used): <input type="file" name="picfiles[]" id="HTMLPics" multiple="" directory="" webkitdirectory="" mozdirectory="">
<?php } 
?>

<p>Base-case  file: <input type='file' accept='.pdf' name='pdffile'/></p>
<p>Worked out pdf Solution file: <input type='file' accept='.pdf' name='solnfile'/></p>
<p> Median time estimate for students to solve in whole minutes:
<input type="integer" name="time_est" ></p>
<p> Estimated difficulty 1=easy 5=difficult:
<input type="integer" name="diff_est" ></p>
<p> link to web solution (if available):
<input type="text" name="web_ref" ></p>
<p>
<p>Solution Spreadsheet (this is optional and only visible to contributor)- will increase upload time: <input type='file' accept='.xlsm' name='solnbook'/></p>
<p><hr></p>
<input type="hidden" name="problem_id" value="<?= $problem_id ?>">
<p><input type="submit" value="Update"/>
<a href="QRPRepo.php">Cancel</a></p>
<p><hr></p>
<p>hint_a file: <input type='file' accept='.html' name='hint_aFile'/></p>
<p>hint_b file: <input type='file' accept='.html' name='hint_bFile'/></p>
<p>hint_c file: <input type='file' accept='.html' name='hint_cFile'/></p>
<p>hint_d file: <input type='file' accept='.html' name='hint_dFile'/></p>
<p>hint_e file: <input type='file' accept='.html' name='hint_eFile'/></p>
<p>hint_f file: <input type='file' accept='.html' name='hint_fFile'/></p>
<p>hint_g file: <input type='file' accept='.html' name='hint_gFile'/></p>
<p>hint_h file: <input type='file' accept='.html' name='hint_hFile'/></p>
<p>hint_i file: <input type='file' accept='.html' name='hint_iFile'/></p>
<p>hint_j file: <input type='file' accept='.html' name='hint_jFile'/></p>

</form>
</body>
</html>
