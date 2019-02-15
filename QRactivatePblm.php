<?php
require_once "pdo.php";
session_start();

// Guardian: Make sure that problem_id is present
if ( ! isset($_GET['problem_id']) or ! isset($_GET['users_id']) ) {
  $_SESSION['error'] = "Missing problem_id";
  header('Location: QRPRepo.php');
  return;
}
	
    $sql = "SELECT * FROM Problem WHERE problem_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_GET['problem_id']));
	$data = $stmt -> fetch();
	
	// check to see if this is a new problem and they want the start over file issued
	if ($data['status']=='num issued'){
	// put code in here to set ask then go to downloadDocx
	// may set some session variables
		$pblm_num=$data['problem_id'];
		$game_prob_flag=$data['game_prob_flag'];
		$_SESSION['error'] = 'The status of this problem is num issued and cannot be activated';
		
	 	header( 'Location: QRRepo.php' ) ;
		return;
	}
	if ($data['status']=='New Compl'){
	// put code in here to set ask then go to downloadDocx
	// may set some session variables
		$pblm_num=$data['problem_id'];
		$game_prob_flag=$data['game_prob_flag'];
		$_SESSION['success'] = 'The status of this problem is num issued - the next step has not been completed. Your problem number is '.$pblm_num;
		$_SESSION['game_prob_flag']=$game_prob_flag;
		$file_name = 'p'.$pblm_num.'_'.$game_prob_flag.'_'.$data['title'];
		$_SESSION['file_name']=$file_name; 
	 
	}
	
	$docxfilenm=$data['docxfilenm'];
	$inputdata=$data['infilenm'];
	$pdffilenm=$data['pdffilenm'];
	
	$file_pathdocx='uploads/'.$docxfilenm;
	$file_pathinput='uploads/'.$inputdata;
	$file_pathpdf='uploads/'.$pdffilenm;
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
<h2>Quick Response Download</h2>
</header>	
	
<?php	
	if($game_prob_flag == 1){
	echo 'Click on file name to download';
	
	}
	else {
	echo 'Click on file names to download both - you will need to merge input data and your class list into the problem statement using the merger workbook';
	}
	echo "<br>";
	echo "<br>";
    echo "<a href='".$file_pathdocx."'>".$docxfilenm."</a>";
	echo "<br>";
    echo "<a href='".$file_pathinput."'>".$inputdata."</a>";
	echo "<br>";
	echo "<hr>";
	if($game_prob_flag == 0){

	echo "<p> The latest merger workbook is below. ";	
	  echo "You will have to enable macros to use it. </p>";	
    echo "<a href='downloads/QRP Merger A500C.xlsm'> QRP Merger </a>";	
	echo "<br>";	
	}
?>
<br>
<a href="QRPRepo.php">Finished or Cancel</a>
</body>
</html>
