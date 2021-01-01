<?php
require_once "pdo.php";
//session_start();
    


if (isset($_POST['currentclass_id']) && isset($_POST['exam_num'])){
        $currentclass_id = $_POST['currentclass_id'];
        $exam_num = $_POST['exam_num'];
/* 
$currentclass_id = 29;
$exam_num = 1;
 */
			$sql = "SELECT game_flag, eexamtime_id
			FROM Eexamtime
			WHERE currentclass_id = :currentclass_id AND exam_num = :exam_num ORDER BY exam_num DESC"; 
			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array (
                ':currentclass_id' => $currentclass_id,
                ':exam_num' => $exam_num
                )); 
			$activegameorexam = $stmt->fetchAll(PDO::FETCH_NUM);
			
		 echo json_encode($activegameorexam);
	}
 ?>





