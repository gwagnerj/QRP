<?php
	require_once "pdo.php";
	session_start();

	
	if (isset($_POST['eexamtime_id'])) {
		$eexamtime_id = htmlentities($_POST['eexamtime_id']);
	  } else {
		   $_SESSION['error'] = 'invalid eexamtime_id in  QREPrint0.php ';
		   header( 'Location: QRPRepo.php' ) ;
			die();
	  }
	
// get the current class name and the course name
$sql = 'SELECT currentclass_id, exam_num,iid,game_flag FROM Eexamtime  WHERE eexamtime_id = :eexamtime_id';
	$stmt = $pdo->prepare($sql);
	$stmt -> execute(array (
	':eexamtime_id' => $eexamtime_id,
	)); 
	$examtime_data = $stmt->fetch();  
	$currentclass_id = $examtime_data['currentclass_id'];
	$exam_num = $examtime_data['exam_num'];
	$iid = $examtime_data['iid'];
	$game_flag = $examtime_data['game_flag'];

	$sql = 'SELECT `name` FROM CurrentClass  WHERE currentclass_id = :currentclass_id';
	$stmt = $pdo->prepare($sql);
	$stmt -> execute(array (
	':currentclass_id' => $currentclass_id,
	)); 
	$currentclass_data = $stmt->fetch();  
	$class_name = $currentclass_data['name'];
	



//$iid = 1;  // temporary
	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRExam Print</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<style>
   .outer {
  width: 100%;
  margin: 0 auto;
 
}

.inner {
  margin-left: 50px;
 
} 


</style>



</head>

<body>
<header>
<h1>Quick Response Exam - Print Exam</h1>
	 <!DOCTYPE html>
	<html lang = "en">
	<head>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta Charset = "utf-8">
	<title>QR Exam Print</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 

</header>

<?php
	
		if ( isset($_SESSION['error']) ) {
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) ) {
			echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
			unset($_SESSION['success']);
		}
	
 
 
 
?>

<!--<h3>Print the problem statement with "Ctrl P"</h3>
 <p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p>  -->
<form id = "the_form"  method = "POST" action = "QRExamPrint1.php" >
	
<h3> Class Name: <?php  echo $class_name; ?></h3>
<h3> Exam Number: <?php  echo $exam_num; ?></h3>
           
         
<font color=#003399>Exam Versions: &nbsp; </font>
                 
                   <div  class = "outer" >

				 
                     <div  class = "inner" >
                 
                             <div>
                               <input type = "radio" name ="exam_version" id = "exam_version" value = "1" checked > </input>
                                   <label for "exam_version"> Different for Every Examinee (name printed on exam) </label>
                             </div>
                               </br>
                             <div  >
                                <input type = "radio" name ="exam_version" id = "exam_version" value = "2" > </input>
                                   <label for "exam_version">   
                                   Number of versions: <input    type = "number" name ="num_versions" id = "num_versions" min = "1", max = "9" value = "3" required  > </input>
                                   &nbsp;&nbsp;&nbsp;&nbsp; Number of print copies for each version (only version code changes) <input type = "number" name ="sets" id = "sets" min = "1", max = "99" value = "1" required  > </input>
                                   &nbsp;&nbsp;&nbsp;&nbsp; Index that the print starts on (1 to 190) <input type = "number" name ="index_start" id = "index_start" min = "1", max = "190" value = "140" required  > </input>
                                   
                                   </label>   
                            </div>
							<br>
							<input type = "checkbox" name = "print_blanks" id = "print_blanks"> Print Variables as Blanks</input>
							<br>
							<br>
							<input type = "checkbox" name = "game_flag" id = "game_flag" <?php if ($game_flag == "1") echo'checked';?>> This is a Game (effects QR Code)</input>
                            
                       </div>
                   </div>
                  </br>
     
            
				  <p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
				  <p><input type="hidden" name="exam_num" id="exam_num" value=<?php echo($exam_num);?> ></p>
				  <p><input type="hidden" name="currentclass_id" id="currentclass_id" value=<?php echo($currentclass_id);?> ></p>
				  <p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
			<p><input type = "submit" id = "submit_id"></p>
   
	
	</form>
  <p style="font-size:100px;"></p>   
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	
	<script>
	

	
</script>	

</body>
</html>



