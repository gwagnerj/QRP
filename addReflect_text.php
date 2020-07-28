<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['activity_id'])){
			$activity_id = $_POST['activity_id'];

			$sql = "UPDATE `Activity` SET `reflect_text` = :reflect_text WHERE `activity_id` = :activity_id";
			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ':reflect_text' => htmlentities($_POST['reflect_text']),
                ':activity_id' => $activity_id,
            ));
		echo ('changes have been saved');	
	}
 ?>





