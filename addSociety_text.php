<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['activity_id'])){
			$activity_id = $_POST['activity_id'];

			$sql = "UPDATE `Activity` SET `society_text` = :society_text WHERE `activity_id` = :activity_id";
			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ':society_text' => htmlentities($_POST['society_text']),
                ':activity_id' => $activity_id,
            ));
		echo ('changes have been saved');	
	}
 ?>





