<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['activity_id'])){
			$activity_id = $_POST['activity_id'];

			$sql = "UPDATE `Activity` SET `explore_text` = :explore_text WHERE `activity_id` = :activity_id";
			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ':explore_text' => htmlentities($_POST['explore_text']),
                ':activity_id' => $activity_id,
            ));
		echo ('changes have been saved');	
	}
 ?>





