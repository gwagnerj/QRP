<?php
require_once "pdo.php";
session_start();
	
    
    if (isset($_POST['student_id']) && isset($_POST['assign_num']) && isset($_POST['currentclass_id']) ){
		$student_id = $_POST['student_id'];
		$assign_num = $_POST['assign_num'];
        $currentclass_id = $_POST['currentclass_id'];
   



    //   $student_id = 1;
    //     $assign_num = 1;
    //    $currentclass_id = 52;
      
    
               $sql = 'SELECT * FROM Activity 
         INNER JOIN Assign ON Assign.assign_id = Activity.assign_id 
         INNER JOIN Problem ON Problem.problem_id = Assign.prob_num
         INNER JOIN Assigntime ON (Assigntime.assign_num = Assign.assign_num AND Assigntime.currentclass_id = Assign.currentclass_id)
        WHERE Activity.student_id = :student_id AND Assign.currentclass_id = :currentclass_id AND Assign.assign_num = :assign_num 
        GROUP BY activity_id
        ORDER BY Activity.alias_num ASC';
      
			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ':student_id' => $student_id,
                ':assign_num' => $assign_num,
                ':currentclass_id' => $currentclass_id
            ));

      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
       if (count($results)!=0){  // we have results for
        echo json_encode($results);

       } else {
       
        
        $sql = 'SELECT * FROM Assign 
         INNER JOIN Problem ON Problem.problem_id = Assign.prob_num
         INNER JOIN Assigntime ON (Assigntime.assign_num = Assign.assign_num AND Assigntime.currentclass_id = Assign.currentclass_id)
        WHERE Assign.currentclass_id = :currentclass_id AND Assign.assign_num = :assign_num ';
        
			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                // ':student_id' => $student_id,
                ':assign_num' => $assign_num,
                ':currentclass_id' => $currentclass_id
            ));
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
		  echo json_encode($results);
        }
 //      print_r ($results);
	  }
	
 ?>





