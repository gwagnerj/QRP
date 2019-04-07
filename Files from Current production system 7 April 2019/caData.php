<?php
require_once "pdo.php";
session_start();
	if (isset($_POST['course'])){
	
	$_SESSION['course'] = $_POST['course'];
	 $stmt = "SELECT Author.author_name 
	FROM Course JOIN CourseAuthorConnect JOIN Author

	ON CourseAuthorConnect.course_id = Course.course_id AND CourseAuthorConnect.author_id = Author.author_id
	
	WHERE Course.course_name ="."'".$_POST['course']."'ORDER BY Author.author_name";
		$stmt = $pdo->prepare($stmt);	
		$stmt->execute();
		$course = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		 echo json_encode($course);
	}
 ?>





