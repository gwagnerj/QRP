<?php
require_once "pdo.php";
session_start();
// this is to set the class list for a particular semester for an instructor with multiple classes that use the system
// activated by a link from QRPRepo so will have information in session varaibles

if(isset($_POST['currentclass_id'])){
// no need to confirm just delete it
	$currentclass_id = $_POST['currentclass_id'];
    $new_exp_date = $_POST['new_exp_date'];
	$sql = "DELETE FROM StudentCurrentClassConnect WHERE currentclass_id = :currentclass_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':currentclass_id' => $currentclass_id));

	
	$sql = "DELETE FROM  `Activity` WHERE `currentclass_id` = :currentclass_id";
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(':currentclass_id' => $currentclass_id ));


	$sql = 'UPDATE CurrentClass SET exp_date = :exp_date WHERE `currentclass_id` = :currentclass_id';
    $stmt = $pdo->prepare($sql);
    $stmt -> execute(array(
        ':currentclass_id' => $currentclass_id, 
        ':exp_date' => $new_exp_date
    
    ));

	
	
	
	
	$_SESSION['success'] = 'Class was updated to new expiration date of '.$new_exp_date.' - Individual assignments dates need updated individually';
	

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
