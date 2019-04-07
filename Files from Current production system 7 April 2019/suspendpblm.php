<?php
session_start();
require_once "pdo.php";

if ( isset($_POST['suspend']) && isset($_GET['problem_id']) ) {
  
  
	
// Now Change the status to suspended for the problem in the database
// if the problem is suspended change it to live otherwise change it to suspended	
// get status	
	
	
	
	
	$sql = "SELECT status FROM Problem where problem_id = :xyz";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(":xyz" => $_POST['problem_id']
		));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$status = $row['status'];	 
		
		// echo $status;
		// die();
		
	if ($status=='suspended'){	
		
		$status = 'live';
		
		$sql = "UPDATE Problem SET 
				status = :status
				WHERE problem_id = :problem_id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':problem_id' => $_POST['problem_id'],
			':status' => $status));
		$_SESSION['success'] = 'Record updated';	

		
		header( 'Location: QRPRepo.php' ) ;
		return;
	} else {
		
		$status = 'suspended';
		$sql = "UPDATE Problem SET 
				status = :status
				WHERE problem_id = :problem_id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':problem_id' => $_POST['problem_id'],
			':status' => $status));
		$_SESSION['success'] = 'Record updated';	
		
		header( 'Location: QRPRepo.php' ) ;
		return;
	}
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
<p>Confirm: Suspension status change of: <?= htmlentities($row['title']) ?></p>

<form method="post">
<input type="hidden" name="problem_id" value="<?= $row['problem_id'] ?>">
<input type="submit" value="Suspend /unSuspend" name="suspend">
<a href="QRPRepo.php">Cancel</a>
</form>
