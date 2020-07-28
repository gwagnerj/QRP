<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['activity_id'])){
			$activity_id = $_POST['activity_id'];

			$sql = "UPDATE `Activity` SET `connect_text` = :connect_text WHERE `activity_id` = :activity_id";
			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ':connect_text' => htmlentities($_POST['connect_text']),
                ':activity_id' => $activity_id,
            ));
		echo ('changes have been saved');	
	}
 ?>





