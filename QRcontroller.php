<?php
require_once "pdo.php";
session_start();



if (!isset($_SESSION['progress'])) {
	
	$_SESSION['error'] = 'error occured in controller - progress not set';
	header("Location: QRhomework.php");
	return;
	
} else {
	
	if($_SESSION['progress']==1){
		
		echo('life is good need to get students progress on this assignment and check problem requirements');
		die();
		
		
		
		
	}
	
	
	
	
	
}








?>