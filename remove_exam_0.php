<?php
require_once 'pdo.php';
session_start();


if (isset($_POST["currentclass_id"])){
    $currentclass_id = $_POST["currentclass_id"];
} else{
    $_SESSION["error"] = "no currentclass_id in remove_exam";
    header('Location: QRPRepo.php');
    die();

}

if(isset($_POST["exam_num"])){
    $exam_num = $_POST["exam_num"];
} else {
    $_SESSION["error"] = "no exam_num in remove_exam";
    header('Location: QRPRepo.php');
    die();

}
if(isset($_POST["eexamtime_id"])){
    $eexamtime_id = $_POST["eexamtime_id"];
} else {
    $_SESSION["error"] = "no exam_time_id in remove_exam";
    header('Location: QRPRepo.php');
    die();

}
if(isset($_POST["iid"])){
    $iid = $_POST["iid"];
} else {
    $_SESSION["error"] = "no iid in remove_exam";
    header('Location: QRPRepo.php');
    die();

}

// $sql = "SELECT * FROM `Eexamtime` 
// LEFT JOIN Eexamnow ON Eexamtime.eexamtime_id = Eexamnow.eexamtime_id  
// WHERE Eexamtime.iid = :iid 
//       AND Eexamtime.eexamtime_id = :eexamtime_id 
//       AND Eexamnow.end_of_phase > CURRENT_TIMESTAMP() 
//       AND Eexamnow.globephase != 3
//   ";

// $stmt = $pdo->prepare($sql);
// $stmt->execute([
// ':iid' => $iid,
// ':eexamtime_id' => $eexamtime_id,
// ]);
// $row = $stmt->fetch(PDO::FETCH_ASSOC);


// get class name
$sql = 'SELECT `name` FROM `CurrentClass` WHERE `iid` = :iid && currentclass_id = :currentclass_id ';
$stmt = $pdo->prepare($sql);
$stmt -> execute(array(':iid' => $iid,':currentclass_id' => $currentclass_id));
$row = $stmt->fetch();
$class_name = $row['name'];


// var_dump($row);

// var_dump($_POST);
// die();


?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Exam </title>
</head>
<body>
    <h1><?php echo ($class_name)?></h1>

    <form id = "the_form"  method = "POST" action = "remove_exam_1.php" >
	
    
                   
					
                    
             <h1>
			 <?php  echo (" Number ".$exam_num);?> </h1>
			

			
		
             </br>
                <font color=#003399>Time that the Exam / Quiz was given (latest on top of list): &nbsp; </font>
                    
                    <select id="eexamnow_id" name = "eexamnow_id" required >
                       <option value="0">- Select Date Last Update -</option>

					   <?php
/* 
							$sql = "SELECT DISTINCT exam_code
							FROM Eactivity LEFT JOIN Eexamnow ON Eactivity.eexamnow_id = Eexamnow.eexamnow_id
							WHERE Eactivity.currentclass_id =:currentclass_id  ORDER BY Eactivity.created_at DESC"; 
							
							$stmt = $pdo->prepare($sql);
							$stmt -> execute(array(':currentclass_id' => $currentclass_id));
							while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) 
							{ ?>
							<option value="<?php echo $row['exam_code']; ?>" ><?php echo $row['exam_code']; ?> </option>
							<?php
							} */
							$sql = "SELECT DISTINCT eexamnow_id, updated_at
								FROM Eactivity 
									WHERE currentclass_id =:currentclass_id GROUP BY eexamnow_id ORDER BY created_at DESC"; 
									
							$stmt = $pdo->prepare($sql);
							$stmt -> execute(array(':currentclass_id' => $currentclass_id));
							while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) 
								{ ?>
								<option value="<?php echo $row['eexamnow_id']; ?>" ><?php echo 'last_updated at '. $row['updated_at']; ?> </option>
								<?php
							}
				   ?>
                    </select>
                </br>	
            
				<p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
				<p><input type="hidden" name="currentclass_id" id="currentclass_id" value=<?php echo($currentclass_id);?> ></p>
				<p><input type="hidden" name="eexamtime_id" id="eexamtime_id" value=<?php echo($eexamtime_id);?> ></p>
				<p><input type="hidden" name="exam_num" id="exam_num" value=<?php echo($exam_num);?> ></p>
			<p><input type = "submit" id = "submit_id"></p>
   
	
	</form>
  <p style="font-size:100px;"></p>   
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>

</body>
</html>



