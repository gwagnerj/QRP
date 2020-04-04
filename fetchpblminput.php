<?php
require_once "pdo.php";
session_start();

// Guardian: Make sure that problem_id is present
/* if ( ! isset($_POST['problem_id'])) {
  $_SESSION['error'] = "Missing problem_id";

 } */
 
 /* If (strlen($_SESSION['stu_name'])<1){
	 $_SESSION['error'] = 'Name is required';
	 return;
 }

 If ($_SESSION['problem_id']<1 || $_SESSION['problem_id']>200000){
	 $_SESSION['error'] = 'invalid problem number';
	  return;
 }

 If ($_SESSION['index']>200 ){
	 $_SESSION['error'] = 'Index should be between 2 and 200';
	  return;
 } */

// getting stuff from the problem table
 
    $sql = "SELECT * FROM Problem WHERE problem_id = :problem_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':problem_id' => $_POST['problem_id']));
	$data = $stmt -> fetch();
	// need to put some error checking here
		$rows=$data;
// query the input table for the actual input values
	

	 $users_id = $rows['users_id'];
	
	$sql = "SELECT * FROM Users WHERE users_id = :users_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':users_id' => $users_id));
	$data2 = $stmt -> fetch();
		$row_user=$data2;
		$rows=array_merge($rows,$row_user);
	

	
 
	$stmt = $pdo->prepare("SELECT * FROM Input where problem_id = :problem_id AND dex = :dex");

	$stmt->execute(array(":problem_id" => $_POST['problem_id'], ":dex" => $_POST['index']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	//$row = $stmt -> fetch();
		if ( $row === false ) {
			$_SESSION['error'] = 'could not read row of table Input values';
	// put em both together		
		}	
	$rows=array_merge($rows,$row);
	
	//$rows = array ('first'=>'first thing','second'=>'second thing');
	
	
	echo json_encode($rows);
	
	
?>	

