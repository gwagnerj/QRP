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
    $_SESSION["error"] = "no exam_num in remove_exam_1";
    header('Location: QRPRepo.php');
    die();

}
if(isset($_POST["eexamtime_id"])){
    $eexamtime_id = $_POST["eexamtime_id"];
} else {
    $_SESSION["error"] = "no exam_time_id in remove_exam_1";
    header('Location: QRPRepo.php');
    die();

}
if(isset($_POST["iid"])){
    $iid = $_POST["iid"];
} else {
    $_SESSION["error"] = "no iid in remove_exam_1";
    header('Location: QRPRepo.php');
    die();

}

if(isset($_POST["eexamnow_id"])){
    $eexamnow_id = $_POST["eexamnow_id"];
} else {
    $_SESSION["error"] = "no eexamnow_id in remove_exam_1";
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


//  var_dump($row);

//  var_dump($_POST);
//  die();

$sql = "UPDATE Eexamnow SET globephase = 1, end_of_phase = NOW() + INTERVAL 2 HOUR
        WHERE eexamnow_id = :eexamnow_id";
$stmt = $pdo->prepare($sql);
$stmt -> execute(array(':eexamnow_id' => $eexamnow_id));


    $_SESSION["success"] = "Exam / Game Global Progress was set to ongoing and 2 hrs was added to the end_time";
    header('Location: QREStart.php?eexamnow_id='.$eexamnow_id);
    die();

?>



