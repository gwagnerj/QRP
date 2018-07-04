<?php
require_once "pdo.php";
session_start();

// Guardian: Make sure that problem_id is present
if ( ! isset($_GET['problem_id']) ) {
  $_SESSION['error'] = "Missing problem_id";
  header('Location: QRPRepo.php');
  return;
}
  
	
    $sql = "SELECT * FROM Problem WHERE problem_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_GET['problem_id']));
	$data = $stmt -> fetch();
	//print_r ($data);
	//die ();
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
	echo 'Click on file names to download both - you will need to merge input data and your class list into the problem statement using the merger workbook';
	echo "<br>";
	echo "<br>";
    echo "<a href='".$file_pathdocx."'>".$docxfilenm."</a>";
	echo "<br>";
    echo "<a href='".$file_pathinput."'>".$inputdata."</a>";
	echo "<br>";
	echo "<hr>";
	 echo "<p> The latest merger workbook is below. ";	
	  echo "You will have to enable macros to use it. </p>";	
    echo "<a href='downloads/QRP Merger A500C.xlsm'> QRP Merger </a>";	
	echo "<br>";	
	
   

?>



<p>Edit Problem Meta Data</p>
<p> </p>
<a href="QRPRepo.php">Finished or Cancel</a>
</body>
</html>
