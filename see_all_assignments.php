<?php
require_once 'pdo.php';
session_start();

if (isset($_POST['iid'])) {
    $iid = $_POST['iid'];
} elseif (isset($_GET['iid'])) {
    $iid = $_GET['iid'];
} else {
    $_SESSION['error'] = 'invalid User_id in QRAssignmentStart0 ';
    header('Location: QRPRepo.php');
    die();
}


// $iid = 1;

// get all the assignments for this instructor on the system

$sql = 'SELECT * FROM Assigntime JOIN Assign 
ON Assign.iid = Assigntime.iid AND Assign.currentclass_id = Assigntime.currentclass_id AND Assign.assign_num = Assigntime.assign_num
JOIN CurrentClass ON Assign.currentclass_id = CurrentClass.currentclass_id
JOIN Problem ON Assign.prob_num = Problem.problem_id
WHERE Assign.iid = :iid
GROUP BY Assign.prob_num
 ORDER BY Assign.currentclass_id , Assign.assign_num, Assign.alias_num ';
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':iid' => $iid));


$assignment_data = $stmt -> fetchALL(PDO::FETCH_ASSOC);
 //var_dump($assignment_data);

$num_records = count($assignment_data);
$i = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR All Assignments</title>
</head>
<body>
<table>
    <thead>

    </thead>
    <tbody>
        <tr>
            <th>Class Name</th>
            <th>Assignment</th>
            <th>Problem</th>
            <th>Title</th>
            <th>Concept</th>
            <th>problem_id</th>
            <th>Due Date</th>
        </tr>
        <tr>
  
<?php
foreach($assignment_data as $assignment_datum){
    // var_dump($assignment_datum);
    $course_name[$i] = $assignment_datum["name"];
    $assign_num[$i] = $assignment_datum["assign_num"];
    $alias_num[$i] = $assignment_datum["alias_num"];
    if($alias_num[$i] == 1 && $i!=0){echo '<td>&nbsp;</td></tr><tr>';}
    if($i ==0 || $course_name[$i] != $course_name[$i-1]){echo '<td>'.$course_name[$i].'</td>';} else {echo '<td></td>';}
    if($i ==0 || $assign_num[$i] != $assign_num[$i-1]){echo '<td>'.$assign_num[$i].'</td>';} else {echo '<td></td>';}
  if ($alias_num[$i]==1){}
    echo '<td>'.$assignment_datum["alias_num"].'</td>';
    echo '<td>'.$assignment_datum["title"].'</td>';
    echo '<td>'.$assignment_datum["primary_concept"].'</td>';
    echo '<td><form action = "bc_preview.php" method = "GET" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$assignment_datum['problem_id'].'"><input type = "submit" value ="BC -'.$assignment_datum['problem_id'].'"></form></td>';
    // echo '<td>'.$assignment_datum["problem_id"].'</td>';
    echo '<td>'.$assignment_datum["due_date"].'</td>';
    echo'<tr>';
    // echo $assignment_datum["name"].' '.$assignment_datum["assign_num"].' '.$assignment_datum["alias_num"].' '.$assignment_datum["prob_num"].' '.$assignment_datum["title"].' '.$assignment_datum["primary_concept"];
    // echo '<br>';
    // echo '<br>';
    // echo '<br>';
    $i++;
}
die();



if (isset($_SESSION['error'])) {
    echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo '<p style="color:green">' . $_SESSION['success'] . "</p>\n";
    unset($_SESSION['success']);
}
?>
  </tbody>

</table>
    
</body>
</html>
<style>

</style>



</head>

<body>
<header>
<h1>Quick Response See Assignments</h1>
</header>

<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo '<p style="color:green">' . $_SESSION['success'] . "</p>\n";
    unset($_SESSION['success']);
}
?>

    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	
	<script>
	
	
	
	
</script>	

</body>
</html>



