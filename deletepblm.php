<?php
session_start();
require_once "pdo.php";

if ( isset($_POST['delete']) && isset($_POST['problem_id']) ) {
  
  // Delete the word file from the upload file folder
	$sql = "SELECT * FROM Problem WHERE problem_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['problem_id']));
	$data = $stmt -> fetch();
	$docxfilenm=$data['docxfilenm'];  // 
	$inputfilenm=$data['infilenm'];  // these are the name of the files we need to delete
	$pdffilenm=$data['pdffilenm'];
	$hint[0]=$data['hint_a'];
	$hint[1]=$data['hint_b'];
	$hint[2]=$data['hint_c'];
	$hint[3]=$data['hint_d'];
	$hint[4]=$data['hint_e'];
	$hint[5]=$data['hint_f'];
	$hint[6]=$data['hint_g'];
	$hint[7]=$data['hint_h'];
	$hint[8]=$data['hint_i'];
	$hint[9]=$data['hint_j'];
	$soln_pblm=$data['soln_pblm'];
	
// Now delete the row from the database
	$sql = "DELETE FROM Problem WHERE problem_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['problem_id']));
    
	if(unlink('uploads/'.$docxfilenm)){ // unlink is the command to delet a file
		$_SESSION['success'] = 'Docxfile deleted';
	}
	if(unlink('uploads/'.$inputfilenm)){
	
		$_SESSION['success'] = 'Input data file deleted';
	}
	if(unlink('uploads/'.$pdffilenm)){ // unlink is the command to delete a file
		$_SESSION['success'] = 'pdffile deleted';
	}	
	for ($i=0;$i<=9;$i++){	
		unlink('uploads/'.$hint[$i]); // unlink is the command to delete a file	
	}
	unlink('uploads/'.$soln_pblm);
	
    header( 'Location: QRPRepo.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['problem_id']) ) {
  $_SESSION['error'] = "Missing user_id";
  header('Location: QRPRepo.php');
  return;
}

$stmt = $pdo->prepare("SELECT title, problem_id FROM Problem where problem_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['problem_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for user_id';
    header( 'Location: QRPRepo.php' ) ;
    return;
}

?>
<p>Confirm: Deleting <?= htmlentities($row['title']) ?></p>

<form method="post">
<input type="hidden" name="problem_id" value="<?= $row['problem_id'] ?>">
<input type="submit" value="Delete" name="delete">
<a href="QRPRepo.php">Cancel</a>
</form>
