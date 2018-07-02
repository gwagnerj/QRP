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
	
	
	
	
	echo 'Click on files to download';
	echo "<br>";
	echo "<br>";
    echo "<a href='".$file_pathdocx."'>".$docxfilenm."</a>";
	echo "<br>";
    echo "<a href='".$file_pathinput."'>".$inputdata."</a>";
	echo "<br>";
    echo "<a href='".$file_pathpdf."'>".$pdffilenm."</a>";	
	echo "<br>";
	echo "<hr>";
	 echo "<p> The latest template to generate the problem files for students using your class list is below </p>";	
	  echo "<p> for non game problems - You will have to enable macros to use it </p>";	
    echo "<a href='downloads/QRP Merger A500C.xlsm'> QRP Merger </a>";	
	echo "<br>";	
	
   

?>

<p> </p>
<a href="QRPRepo.php">Finished or Cancel</a>

<!--

$stmt = $pdo->prepare("SELECT name, users2_id FROM users2 where users2_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['users2_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for users2_id';
    header( 'Location: QRPRepo.php' ) ;
    return;
} 

?>
<p>Confirm: Download <?= htmlentities($row['name']) ?></p>

<form method="post">
<input type="hidden" name="users2_id" value="<?= $row['users2_id'] ?>">
<input type="submit" value="Download" name="download">
<a href="QRPRepo.php">Finished or Cancel</a>
</form>
-->