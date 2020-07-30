<?php

session_start();
require_once "pdo.php";
if(isset($_POST['student_id'])){
    echo(' student_id '.$_POST['student_id']);
    $student_id = $_POST['student_id'];
} else {
    echo(' no student_id ');
   
}

if(isset($_POST['peer_num'])){
    echo(' peer_num '.$_POST['peer_num']);
     $peer_num = $_POST['peer_num'];
} else {
    echo(' no peer_num ');
}
if(isset($_POST['assign_num'])){
    echo(' assign_num '.$_POST['assign_num']);
     $assign_num = $_POST['assign_num'];
} else {
    echo(' no assign_num ');
}
if(isset($_POST['alias_num'])){
    echo(' alias_num '.$_POST['alias_num']);
      $alias_num = $_POST['alias_num'];
} else {
    echo(' no alias_num ');
}

if(isset($_POST['cclass_id'])){
    echo(' cclass_id '.$_POST['cclass_id']);
      $currentclass_id = $_POST['cclass_id'];
} else {
    echo(' no cclass_id ');
}

$params['student_id'] = 2;

//$sql = 'SELECT `reflect_text` FROM Activity WHERE `student_id`=:student_id ORDER BY RAND() LIMIT 0,3 ';
$sql = 'SELECT `reflect_text` FROM Activity ORDER BY RAND() LIMIT 0,5 ';
$stmt = $pdo->prepare($sql);
 $stmt->execute($params);
  $active_data = $stmt -> fetchALL();
  //echo count($active_data);
  echo('<br>');
 // var_dump($active_data);
 // echo(rand(0,100));














?>