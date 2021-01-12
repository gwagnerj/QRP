<?php
require_once "pdo.php";

session_unset();
session_start();
$_SESSION['checker']=1;

if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	if (isset($_POST['deleteClass'])){
	
		$sql = "DELETE FROM  `CurrentClass` WHERE `exp_date` < CURDATE()";
			$stmt = $pdo->prepare($sql);
			$stmt -> execute();
	}
	
	
	
	
	if (isset($_POST['deleteAssignment'])){
			$sql = "DELETE FROM `Assign` WHERE `exp_date` < CURDATE()";
			$stmt = $pdo->prepare($sql);
			$stmt -> execute();
	}

	if (isset($_POST['disconnectStudents'])){
		$sql = "SELECT currentclass_id FROM `CurrentClass` WHERE `exp_date` < CURDATE()";
		$stmt = $pdo->prepare($sql);
		$stmt -> execute();
		$currentclass_data = $stmt->fetchAll();

		foreach($currentclass_data as $currentclass_datum){
			$currentclass_id = $currentclass_datum['currentclass_id'];

		//	echo ' $currentclass_id '.$currentclass_id;
			$sql ='DELETE  FROM `StudentCurrentClassConnect` WHERE currentclass_id = :currentclass_id';	
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(':currentclass_id'=>$currentclass_id));
		}

		
		
		
		$sql = "DELETE FROM `Assign` WHERE `exp_date` < CURDATE()";
		$stmt = $pdo->prepare($sql);
		$stmt -> execute();
}








	if (isset($_POST['deleteUsers'])){
			$sql = "DELETE FROM `Users` WHERE `exp_date` < CURDATE()";
			$stmt = $pdo->prepare($sql);
			$stmt -> execute();
	}

	if (isset($_POST['deleteExam'])){
		$sql = "SELECT * FROM `Exam` WHERE `exp_date` < CURDATE()";
		$stmt = $pdo->prepare($sql);
		$stmt -> execute();
		$row =$stmt ->fetch();
		$iid = $row['iid'];
		$currentclass_id = $row['currentclass_id'];
	   
	   $sql = "DELETE FROM `Examactivity` WHERE `iid` = :iid AND `currentclass_id` = :currentclass_id ";
		$stmt = $pdo->prepare($sql);
		$stmt -> execute(array(
			':iid'=>$iid,
			':currentclass_id'=>$currentclass_id ));
	  
		$sql = "DELETE FROM `Exam` WHERE `exp_date` < CURDATE()";
		$stmt = $pdo->prepare($sql);
		$stmt -> execute();

	  

}





    
	if (isset($_POST['deleteUsers'])){
			$sql = "DELETE FROM `Users` WHERE `exp_date` < CURDATE()";
			$stmt = $pdo->prepare($sql);
			$stmt -> execute();
	}
    
    
    
    
	
	header( 'Location: QRPRepo.php' ) ;
	 
    return;

	if ( isset($_SESSION['error']) ) {
		echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
		unset($_SESSION['error']);
	}
	if ( isset($_SESSION['success']) ) {
		echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
		unset($_SESSION['success']);
	}

}	









?>

<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>Delete Old Classes</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
</head>
<style type="text/css">
.container{padding: 20px; }


}
</style>
<body>

<div class = "container">
<header>
<h1>Delete Expired Entries Only for Admin </h1>
</header>





<form  method = "POST" >

<p><input type="checkbox" checked name="disconnectStudents" ><font color=#003399> Disconnect all students from all expired current classes</font> </p>

<p><input type="checkbox" name="deleteClass" ><font color=#003399>Expired CurrentClasses and Activity of Expired CurrentClasses</font> </p>

	
	<p> <input type="checkbox" name="deleteUsers" > <font color=#003399>Expired Users </font> </p>
	<p> <input type="checkbox" name="deleteAssignment"  > <font color=#003399>Expired Assignments </font> </p>
   	<p><input type="checkbox" name="deleteExam"  >  <font color=#003399>Expired Exams and Activity of Expired Exam </font> </p>

	</br>
	<!-- <p><font color=#003399>Grading Scheme Number: </font><input type="text" name="gs_num" size=3 value="<?php echo (htmlentities($gs_num))?>"  ></p> -->

	<p><input type = "submit" value="Submit" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>
	</form>
	
<a href="QRPRepo.php">Cancel - go back to Repository</a>
</div>

</body>
</html>



