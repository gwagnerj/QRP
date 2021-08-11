<?php
require_once "pdo.php";
session_start();
	
    
    if (isset($_POST['student_id']) && isset($_POST['assign_num']) && isset($_POST['currentclass_id'])&& isset($_POST['n']) ){
		$student_id = $_POST['student_id'];
		$assign_num = $_POST['assign_num'];
        $currentclass_id = $_POST['currentclass_id'];
        $n = $_POST['n'];
   



    //   $student_id = 1;
    //     $assign_num = 1;
    //    $currentclass_id = 52;
    // $n = 4;

    $results = array();


    $sql = 'SELECT Assign.prob_num FROM Assign 
    INNER JOIN Assigntime ON (Assigntime.assign_num = Assign.assign_num AND Assigntime.currentclass_id = Assign.currentclass_id)
   WHERE Assign.currentclass_id = :currentclass_id AND Assign.assign_num = :assign_num 
   GROUP BY Assign.prob_num';
   
       $stmt = $pdo->prepare($sql);	
       $stmt->execute(array(
           ':assign_num' => $assign_num,
           ':currentclass_id' => $currentclass_id
       ));

       $problem_ids = $stmt->fetchALL();
    //    var_dump($problem_ids);
        $i =0;
       foreach ($problem_ids as $problem){
           $problem_id = $problem['prob_num'];
        //    echo("problem_id ".$problem_id);
      // var_dump($problem_id);

                    $sql = 'SELECT * FROM Activity 
                    INNER JOIN Assign ON Assign.assign_id = Activity.assign_id 
                INNER JOIN Problem ON Problem.problem_id = Assign.prob_num
                    INNER JOIN Assigntime ON (Assigntime.assign_num = Assign.assign_num AND Assigntime.currentclass_id = Assign.currentclass_id)
                WHERE Activity.student_id = :student_id AND Assign.currentclass_id = :currentclass_id AND Assign.assign_num = :assign_num AND Problem.problem_id = :problem_id
                GROUP BY activity_id
                ORDER BY Activity.alias_num ASC';
                
                    $stmt = $pdo->prepare($sql);	
                    $stmt->execute(array(
                        ':student_id' => $student_id,
                        ':assign_num' => $assign_num,
                        ':currentclass_id' => $currentclass_id,
                        ':problem_id' => $problem_id
                    ));

               // $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if($result) {
                   // print_r($result);
                    $results[$i] = $result;
                    // $results = array_merge($results, $result);
                }  else {
                    $sql = 'SELECT * FROM Assign 
                    INNER JOIN Problem ON Problem.problem_id =  Assign.prob_num
                    INNER JOIN Assigntime ON (Assigntime.assign_num = Assign.assign_num AND Assigntime.currentclass_id = Assign.currentclass_id)
                   WHERE Assign.currentclass_id = :currentclass_id AND Assign.assign_num = :assign_num AND Problem.problem_id = :problem_id
                   GROUP BY Problem.problem_id';
                   
                       $stmt = $pdo->prepare($sql);	
                       $stmt->execute(array(
                           // ':student_id' => $student_id,
                           ':assign_num' => $assign_num,
                           ':currentclass_id' => $currentclass_id,
                           ':problem_id' => $problem_id
                       ));
                       $result = $stmt->fetch(PDO::FETCH_ASSOC);
                     
                       if ($result){
                         //  print_r($result);
                        $results[$i] = $result;
                        //$results = array_merge($results, $result);
                       }



                } 

                $i++;

       }
    //   print_r ($results);
       echo json_encode($results);
      }









      
    
    //            $sql = 'SELECT * FROM Activity 
    //      INNER JOIN Assign ON Assign.assign_id = Activity.assign_id 
    //      INNER JOIN Problem ON Problem.problem_id = Assign.prob_num
    //      INNER JOIN Assigntime ON (Assigntime.assign_num = Assign.assign_num AND Assigntime.currentclass_id = Assign.currentclass_id)
    //     WHERE Activity.student_id = :student_id AND Assign.currentclass_id = :currentclass_id AND Assign.assign_num = :assign_num 
    //     GROUP BY activity_id
    //     ORDER BY Activity.alias_num ASC';
      
	// 		$stmt = $pdo->prepare($sql);	
	// 		$stmt->execute(array(
    //             ':student_id' => $student_id,
    //             ':assign_num' => $assign_num,
    //             ':currentclass_id' => $currentclass_id
    //         ));

    //   $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
    //    if (count($results)!=0){  // we have results for
    //     echo json_encode($results);

    //    } else {
       
        
    //     $sql = 'SELECT * FROM Assign 
    //      INNER JOIN Problem ON Problem.problem_id = Assign.prob_num
    //      INNER JOIN Assigntime ON (Assigntime.assign_num = Assign.assign_num AND Assigntime.currentclass_id = Assign.currentclass_id)
    //     WHERE Assign.currentclass_id = :currentclass_id AND Assign.assign_num = :assign_num 
    //     GROUP BY Problem.problem_id';
        
	// 		$stmt = $pdo->prepare($sql);	
	// 		$stmt->execute(array(
    //             // ':student_id' => $student_id,
    //             ':assign_num' => $assign_num,
    //             ':currentclass_id' => $currentclass_id
    //         ));
	// 		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
 //      print_r ($results);
	  
	
 ?>





