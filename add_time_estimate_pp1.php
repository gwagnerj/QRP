<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['activity_id'])){
			$activity_id = $_POST['activity_id'];

			$sql = "UPDATE `Activity` SET `time_est` = :time_est, `pp1_pts`=:perc_pp1 WHERE `activity_id` = :activity_id";
			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ':time_est' => htmlentities($_POST['time_est']),
                ':activity_id' => $activity_id,
                ':perc_pp1' => htmlentities($_POST['perc_pp1'])
            ));
		// echo ('changes have been saved');	
	}
 ?>





