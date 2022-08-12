<?php
require_once "pdo.php";
session_start();

$discipline = '';
     $json = file_get_contents("php://input"); // json string

    $object = json_decode($json); // php object
    if ($object){
            $discipline = $object->discipline;  // pulls the discipline value out of the key value 
    }

  
       if (strlen($discipline)<2){
             $_SESSION['error'] = ' discipline not sent to get data file getProblems...'.$discipline;
            die();
       }


            $sql = " SELECT Course.course_name AS course_name, Course.course_id AS course_id
                    FROM Course 
                    LEFT JOIN DisciplineCourseConnect ON DisciplineCourseConnect.course_id = Course.course_id
                    LEFT JOIN Discipline ON DisciplineCourseConnect.discipline_id = Discipline.discipline_id
                    WHERE Discipline.discipline_name = :discipline_name";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(":discipline_name" => $discipline));
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

			
		 echo json_encode($courses);
	
 ?>





