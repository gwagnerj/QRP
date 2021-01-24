<?php
	require_once "pdo.php";
	session_start();
	
   
    
	
     if(isset($_POST['iid'])){
            $iid = $_POST['iid'];
        } 
        elseif (isset($_GET['iid'])){
            $iid = $_GET['iid'];
        } 
        else {
			 $_SESSION['error'] = 'invalid User_id (iid) in QRExamRetrieve.php '.$_GET['iid'];
      			header( 'Location: QRPRepo.php' ) ;
				die();
		}

		if(isset($_POST['currentclass_id'])){
            $currentclass_id = $_POST['currentclass_id'];
        } 
        elseif (isset($_GET['currentclass_id'])){
            $currentclass_id = $_GET['currentclass_id'];
        } 
        else {
			 $_SESSION['error'] = 'invalid course name in QRExamRetrieve.php';
      			header( 'Location: QRPRepo.php' ) ;
				die();
		}
		
		if(isset($_POST['exam_num'])){
            $exam_num = $_POST['exam_num'];
        } 
        elseif (isset($_GET['exam_num'])){
            $exam_num = $_GET['exam_num'];
        } 
        else {
			 $_SESSION['error'] = 'invalid exam num in QRExamRetrieve.php';
      			header( 'Location: QRPRepo.php' ) ;
				die();
		}

// get the name of the class from the db
		$sql = 'SELECT `name` FROM `CurrentClass` WHERE `iid` = :iid && currentclass_id = :currentclass_id ';
		$stmt = $pdo->prepare($sql);
		$stmt -> execute(array(':iid' => $iid,':currentclass_id' => $currentclass_id));
		$row = $stmt->fetch();
		$class_name = $row['name'];
		//echo $class_name;





    
        
/* 		
			$alias_num = $exam_num = $cclass_id = '';   
			
			
            $sql_stmt = "SELECT * FROM Exam WHERE DATE(NOW())<= exp_date AND iid = :iid order by exam_id";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt -> execute(array(':iid' => $iid));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 */
     
// this will be called form the main repo when the game master wants to run a game
// this is just to get the game number and go on to QRGMaster.php with a post of the game number.
// Validity will be checked in that file and sent back here if it is not valid

$_SESSION['counter']=0;  // this is for the score board


	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRExam Retrieve</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>

<body>
<header>
<h1>Quick Response Exam - Retrieve Exam Data</h1>
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
<form id = "the_form"  method = "POST" action = "stu_exam_results.php" >
	
    
                   
					
                    
             <h2>
			 Class Name: <?php  echo ($class_name);?> </h2>
			 <h2>
			 Exam / Quiz Number: <?php  echo ($exam_num);?>
			 </h2>       
			

			
		
             </br>
                <font color=#003399>Exam Code (latest on top of list): &nbsp; </font>
                    
                    <select id="exam_code" name = "exam_code" required >
                       <option value="0">- Select Exam Code -</option>

					   <?php

							$sql = "SELECT DISTINCT exam_code
							      FROM Eactivity LEFT JOIN Eexamnow ON Eactivity.eexamnow_id = Eexamnow.eexamnow_id
									WHERE Eactivity.currentclass_id =:currentclass_id  ORDER BY Eactivity.created_at DESC"; 
									
							$stmt = $pdo->prepare($sql);
							$stmt -> execute(array(':currentclass_id' => $currentclass_id));
							while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) 
								{ ?>
								<option value="<?php echo $row['exam_code']; ?>" ><?php echo $row['exam_code']; ?> </option>
								<?php
							}
				   ?>
                    </select>
                </br>	
            
				<p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
				<p><input type="hidden" name="currentclass_id" id="currentclass_id" value=<?php echo($currentclass_id);?> ></p>
				<p><input type="hidden" name="exam_num" id="exam_num" value=<?php echo($exam_num);?> ></p>
			<p><input type = "submit" id = "submit_id"></p>
   
	
	</form>
  <p style="font-size:100px;"></p>   
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	
	<script>

</script>	

</body>
</html>



