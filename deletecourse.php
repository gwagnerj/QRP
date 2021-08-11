<?php
require_once "pdo.php";
session_start();
// this is to set the class list for a particular semester for an instructor with multiple classes that use the system
// activated by a link from QRPRepo so will have information in session varaibles

if(isset($_GET['currentclass_id'])){
// no need to confirm just delete it
	$currentclass_id = $_GET['currentclass_id'];
	$sql = "DELETE FROM StudentCurrentClassConnect WHERE currentclass_id = :currentclass_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':currentclass_id' => $currentclass_id));

	$sql = "DELETE FROM CurrentClass WHERE currentclass_id = :currentclass_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':currentclass_id' => $currentclass_id));
	
	$sql = "DELETE FROM  `Activity` WHERE `currentclass_id` = :currentclass_id";
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(':currentclass_id' => $currentclass_id ));
	
	$sql = "DELETE FROM  `Assign` WHERE `currentclass_id` = :currentclass_id";
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(':currentclass_id' => $currentclass_id ));
	
	
	
	
	$_SESSION['success'] = 'Class was removed including activity and assignments';
	

	header( 'Location: Current_Class.php' ) ;
	 
    return;


} else {
	
	$_SESSION['error'] = 'Something Went Wrong - Class name entry was NOT deleted';
	/* 
	echo 'wtf2';
	 die();
	 */
	header( 'Location: Current_Class.php' ) ;
    return;
	
}

?>
