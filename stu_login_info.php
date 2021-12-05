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


  //  if ($_POST['where_from'] != 'QRExamMgmt') {
  //       if (($_POST['currentclass_id']==0 || $_POST['currentclass_id']=='' || $_POST['iid']==0 || $_POST['iid']=='' )){
  //           $_SESSION['error'] = 'Class must be Selected';
  //          header( 'Location: QRAssignmentStart0.php?iid='.$iid ) ;
  //          die();
  //       }
  //     } else {
  //       if (($_POST['currentclass_id']==0 || $_POST['currentclass_id']=='' || $_POST['iid']==0 || $_POST['iid']=='' )){
  //         $_SESSION['error'] = 'Class must be Selected';
  //        header( 'Location: QRExamMgmt.php?iid='.$iid ) ;
  //        die();
  //       }

  //     }
   //   $assign_num = $_POST['active_assign'];
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
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Student Info</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
 <link rel="icon" type="image/png" href="McKetta.png" />  
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">


</head>
<body>
  
</body>
</html>
    <?php
    echo('<h2>Quick Response Student Login Information for '.$class_name.'  </h2>');
// table header
  

  echo ('<table id="table_format" class = "a table" border="2" rules = "rows" >'."\n");
        echo("<thead>");

		echo("</td><th>");
		echo(' First Name ');
		echo("</th><th>");
		echo(' Last Name ');
		echo("</th><th>");
        echo(' DEX from pin ');
		echo("</th><th>");
        echo(' User ID ');
		echo("</th><th>");
		echo('&nbsp; Password &nbsp;');
	
		echo("</th><th>");
		echo('&nbsp; QR student_id &nbsp;');
	
		echo("</th><th>");
		echo('&nbsp; Function &nbsp;');
	
		echo("</th></tr>\n");
		 echo("</thead>");
		 
		  echo("<tbody><tr></tr><tr>");

  
   
   $sql = 'SELECT `student_id`,`pin` FROM StudentCurrentClassConnect WHERE currentclass_id = :currentclass_id ';
   $stmt = $pdo->prepare($sql);
           $stmt -> execute(array (
           ':currentclass_id' => $currentclass_id,
           )); 
          $studentcurrentclass_data  = $stmt -> fetchALL();
          foreach ($studentcurrentclass_data as $studentcurrentclass_datum){
              $pin = $studentcurrentclass_datum['pin'];
              $dex = ($pin-1) % 199 + 2;
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
                echo('&nbsp; '.htmlentities($dex).'&nbsp; ');
                echo("</td><td>");	
                echo('&nbsp; '.htmlentities($username).'&nbsp; ');
                echo("</td><td>");	
                echo(' &nbsp;'.htmlentities($password).'&nbsp; ');
                echo("</td><td>");	
                echo(' &nbsp;'.htmlentities($student_id).'&nbsp; ');
                echo("</td><td>");	
                echo(' &nbsp; <button id = "remove-'.htmlentities($student_id).'" onClick = "remove_student(this.id,'.$currentclass_id.')" class = "remove btn btn-outline-primary p-1 b-1" style = "color-:red;">Remove</button> ');
                echo("</td></tr>");	
          }     echo ('</tbody></table><br><br>');
}
//echo(' $iid:  '.$iid);


           if (isset($_POST['active_assign']))
              { echo('<form action = "QRAssignmentStart0.php" method = "POST"> <input type = "hidden" name = "iid" value = "'.$iid.'"><input type = "submit" value ="Back to Edit Assignment"></form> &nbsp;');} 
           elseif(isset($_POST['active_exam'])) { 
               echo('<form action = "QRExamMgmt.php" method = "POST"> <input type = "hidden" name = "iid" value = "'.$iid.'"><input type = "submit" value ="Back to Edit Exam"></form> &nbsp;');
            } else { 
              echo " <a href = 'QRPRepo.php'>";
            }


?>
<SCRIPT>

//let remove = document.getElementsByClassName("remove");
function remove_student(student_id,currentclass_id){
  console.log("student_id",student_id) ;
  let student_id2 = student_id.split("-")[1];
  console.log("student_id2",student_id2) ;
  console.log("currentclass_id",currentclass_id) ;
  $.ajax({
            url: 'remove_student_from_class.php',
            method: 'post',
            data: {student_id:student_id2,currentclass_id:currentclass_id},
    
    success: function(message){
      console.log(message);                                                   
      location.reload();

    }

            }
            ); 
</SCRIPT>





