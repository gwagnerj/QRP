<?php
require_once "pdo.php";
require_once "simple_html_dom.php";

session_start();
    
$iid = 0;
      
           if (isset($_POST['iid'])){   //! uncomment this after testing
               $iid = $_POST['iid'];  }
            else {
                $_SESSION['error'] = "no  iid in writeQuestion";
            }
            // var_dump($_FILES);
            // var_dump($_POST);
            if($_FILES['csvFile']['name']) {
				$filename=explode(".",$_FILES['csvFile']['name']); // divides the file into its name and extension puts it into an array
				if ($filename[1]=='csv'){ // this is the extension
					
					// now put the name of the variables into the Problem table and the values into the Input Table
					$handle = fopen($_FILES['csvFile']['tmp_name'], "r");
					$lines=0;  //set this to ignore the header row in the csv file
                    echo("have a handle");
							// While($data=fgetcsv($handle)) {
							// 	$input_values[$lines] = $data;
							// 	 If ($lines==0){
							// 		// put the variable names in the problem table
							// 		$sql = "UPDATE Problem SET nv_1 = :nv_1, nv_2 = :nv_2,nv_3 = :nv_3, nv_4 = :nv_4,nv_5 = :nv_5, nv_6 = :nv_6,nv_7 = :nv_7, 
							// 				nv_8 = :nv_8, nv_9 = :nv_9,nv_10 = :nv_10, nv_11 = :nv_11,nv_12 = :nv_12, nv_13 = :nv_13,nv_14 = :nv_14
							// 				WHERE problem_id = :pblm_num";
							// 		$stmt = $pdo->prepare($sql);
							// 		$stmt->execute(array(
							// 				':nv_1' => $data[1],
							// 				':nv_2' => $data[2],
							// 				':nv_3' => $data[3],
							// 				':nv_4' => $data[4],
							// 				':nv_5' => $data[5],
							// 				':nv_6' => $data[6],
							// 				':nv_7' => $data[7],
							// 				':nv_8' => $data[8],
							// 				':nv_9' => $data[9],
							// 				':nv_10' => $data[10],
							// 				':nv_11' => $data[11],
							// 				':nv_12' => $data[12],
							// 				':nv_13' => $data[13],
							// 				':nv_14' => $data[14],
							// 				':pblm_num' => $_POST['problem_id']));
							// 	} 
								
							// 	If ($lines>0){
									
	
									
							// 		// put the input values Input table

							// 		$sql = "UPDATE Input SET problem_id = :problem_id, dex = :dex, v_1 = :v_1, v_2 = :v_2, v_3 = :v_3, v_4 = :v_4,v_5 = :v_5, v_6 = :v_6, v_7 = :v_7, 
							// 			v_8 = :v_8, v_9 = :v_9, v_10 = :v_10, v_11 = :v_11,v_12 = :v_12, v_13 = :v_13, v_14 = :v_14 
							// 			WHERE problem_id = :problem_id AND dex = :dex";
									
							// 		$stmt = $pdo->prepare($sql);
							// 		$stmt->execute(array(
							// 			':problem_id' => $_POST['problem_id'],
							// 			':dex' => $data[0],
							// 			':v_1' => $data[1],
							// 			':v_2' => $data[2],
							// 			':v_3' => $data[3],
							// 			':v_4' => $data[4],
							// 			':v_5' => $data[5],
							// 			':v_6' => $data[6],
							// 			':v_7' => $data[7],
							// 			':v_8' => $data[8],
							// 			':v_9' => $data[9],
							// 			':v_10' => $data[10],
							// 			':v_11' => $data[11],
							// 			':v_12' => $data[12],
							// 			':v_13' => $data[13],
							// 			':v_14' => $data[14]));
					
							// 	}
							// 	$lines = $lines+1;
							// }
							fclose($handle);
               			// var_dump($input_values);
						//    echo '<br><br>';
						//    die();

					
			// 		$inputdata=addslashes($_FILES['inputdata']['tmp_name']);
			// 		$inputname=addslashes($_FILES['inputdata']['name']);
			// 		$inputdata=file_get_contents($inputdata);
			// 		$inputname = $_FILES['inputdata']['name'];
			// 		$tmp_inputname =  $_FILES['inputdata']['tmp_name'];
			// 		$location = "uploads/"; // This is the local file directory name where the files get saved
				
			
			// 	$sql = "UPDATE Problem SET  infilenm = :infilenm 	
			// 				WHERE problem_id=:problem_id";
			// 	$stmt = $pdo->prepare($sql);
			// 	$stmt->execute(array(':infilenm'=> $inputname,	':problem_id' => $_POST['problem_id']));

			// 	if (fnmatch("P*_i_*",$inputname,FNM_CASEFOLD ) ){
			// 		$newInputNm = $inputname;
			// 	}
			// 	else if($inputname !==""){
			// 		$newInputNm = "P".$problem_id."_i_".$inputname;
			// 	} else {
			// 		$newInputNm = "P".$problem_id."_i_inputfile.csv";
			// 	}
		
			// 	$sql = "UPDATE Problem SET infilenm = :newInputNm WHERE problem_id = :pblm_num";
			// 	$stmt = $pdo->prepare($sql);
			// 	$stmt->execute(array(
			// 		':newInputNm' => $newInputNm,
			// 		':pblm_num' => $_POST['problem_id']));	
				
			// 	$pathName = 'uploads/'.$newInputNm;
			// 	if (move_uploaded_file($_FILES['inputdata']['tmp_name'], $pathName)){
			// 		$_SESSION['success'] = $_SESSION['success'].'Input data file upload successful';
			// 	}
				
				
			// }else {$_SESSION['error']=' Input file is not a csv file';}
					
		}
    }
      
    


// Flash pattern
	if ( isset($_SESSION['error']) ) {
		echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
		unset($_SESSION['error']);
	}

?>
<!DOCTYPE HTML>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRQuestions</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
         <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"> 




<style type="text/css">
.hide{
    display: none;
}

body{ 
    margin-left: 3rem;
    background-color: #FFFAFA;
}

.form-group{
    width: 50% !important;
    display:inline-block !important;
    font-size: 1.3rem;
    color: #000000;
}
.optional{ 
    color: gray;
}


</style>
</head>

<body>
<header>
<h2>Quick Response Question Writing System</h2>

</header>
<div id = "btn_group" class = "mb-5">
        <button type="button" id = "return_to_repo_btn" title = "Return to Repository" class="btn btn-outline-secondary btn-lg ms-4 my-5">Return to Repo</button>
        <button type="button" id = "download_template" title = "Download an Excel template to Create Questions" class="btn btn-outline-success btn-lg ms-4 my-5">Download Template</button>
    </div>

	<form action="" method="post" enctype="multipart/form-data">

    <input type = "hidden" name = "iid"  value = "<?= $iid ?>">

    <label for="csvFile">Select a CSV file that has the requred form:</label>
     <input type="file" accept=".csv" id="csvFile" name="csvFile">
     <input type="submit" value="upload"  name="submit">
</form> 
<!-- put in a hide class to the meta  container in the final version -->

<script>
</script>

</body>
</html>