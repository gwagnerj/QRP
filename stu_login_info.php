<?php
	require_once "pdo.php";
	session_start();
	
if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {	
    if (isset($_POST['iid'])) {
	$iid = $_POST['iid'];
   
} else {
	 $_SESSION['error'] = 'invalid User_id in stu_assignment_results.php ';
     echo('no iid');
      			header( 'Location: QRPRepo.php' ) ;
				die();
}


    
    if ($_POST['currentclass_id']==0 || $_POST['currentclass_id']=='' || $_POST['iid']==0 || $_POST['iid']=='' ){
        $_SESSION['error'] = 'Class must be Selected';
        header( 'Location: QRAssignmentStart0.php?iid='.$iid ) ;
	   die();
    }
    
      $assign_num = $_POST['active_assign'];
    $currentclass_id = $_POST['currentclass_id'];
    
      $sql = 'SELECT name FROM CurrentClass WHERE currentclass_id = :currentclass_id';
                 $stmt = $pdo->prepare($sql);
               $stmt -> execute(array (
               ':currentclass_id' => $currentclass_id,
               )); 
               
               $stmt -> execute(); 
              $currentclass_data  = $stmt -> fetch();
                $class_name = $currentclass_data['name'];
  
    // set up the table
    
    echo('<h2>Quick Response Student Login Information for '.$class_name.'  </h2>');
// table header
  

  echo ('<table id="table_format" class = "a" border="1" >'."\n");
        echo("<thead>");

		echo("</td><th>");
		echo(' First Name ');
		echo("</th><th>");
		echo(' Last Name ');
		echo("</th><th>");
        echo(' User ID ');
		echo("</th><th>");
		echo('&nbsp; Password &nbsp;');
	
		echo("</th></tr>\n");
		 echo("</thead>");
		 
		  echo("<tbody><tr></tr><tr>");

  
   
   $sql = 'SELECT `student_id` FROM StudentCurrentClassConnect WHERE currentclass_id = :currentclass_id ';
   $stmt = $pdo->prepare($sql);
           $stmt -> execute(array (
           ':currentclass_id' => $currentclass_id,
           )); 
          $studentcurrentclass_data  = $stmt -> fetchALL();
          foreach ($studentcurrentclass_data as $studentcurrentclass_datum){
              
                $student_id = $studentcurrentclass_datum['student_id'];
              $sql = 'SELECT * FROM Student WHERE student_id = :student_id';
                 $stmt = $pdo->prepare($sql);
               $stmt -> execute(array (
               ':student_id' => $student_id,
               )); 
               
               $stmt -> execute(); 
              $student_data  = $stmt -> fetch();
              $first_name = $student_data['first_name'];
              $last_name = $student_data['last_name'];
              $username = $student_data['username'];
              $password = $student_data['password'];
                echo "<tr><td>";
                echo('&nbsp; '.htmlentities($first_name).'&nbsp; ');
                echo("</td><td>");	
                echo('&nbsp; '.htmlentities($last_name).'&nbsp; ');
                echo("</td><td>");	
                echo('&nbsp; '.htmlentities($username).'&nbsp; ');
                echo("</td><td>");	
                echo(' &nbsp;'.htmlentities($password).'&nbsp; ');
                echo("</td></tr>");	
          }     echo ('</tbody></table><br><br>');
}
//echo(' $iid:  '.$iid);
            echo('<form action = "QRAssignmentStart0.php" method = "POST"> <input type = "hidden" name = "iid" value = "'.$iid.'"><input type = "submit" value ="Back to Edit Assignment"></form> &nbsp;');


?>



