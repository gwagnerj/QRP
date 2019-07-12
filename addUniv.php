<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['university'])){
		
// add the University to a session varaible and add it when form is submitted that way you have to add university 
	
			$stmt = "SELECT Course.course_name 
			FROM Discipline JOIN DisciplineCourseConnect JOIN Course
			ON DisciplineCourseConnect.discipline_id = Discipline.discipline_id AND DisciplineCourseConnect.course_id = Course.course_id

			WHERE Discipline.discipline_name ="."'". $_POST['discipline']."' ORDER BY Course.course_name"; 
			$stmt = $pdo->prepare($stmt);	
			$stmt->execute();
			$course = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		 echo json_encode($course);
	}
	
 ?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add University</title>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 50%; padding: 20px; }
    </style>
</head>



