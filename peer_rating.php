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
// get what we are trying to find
$arr = explode(')', $peer_num);
$reflection_type = trim($arr[1]).'_text';
$reflection_review_count = trim($arr[1]).'_review_count';  // the catagory name of the reflection review count
echo(' reflection_review_count '.$reflection_review_count);
// now get the assign_id 

$sql = 'SELECT `assign_id` FROM Assign WHERE `assign_num` = :assign_num AND currentclass_id = :currentclass_id AND alias_num = :alias_num';
$stmt = $pdo->prepare($sql);
 $stmt->execute(array(
    ':assign_num' => $assign_num,
    ':currentclass_id' => $currentclass_id,
    ':alias_num' => $alias_num,
 ));
 
  $assign_data = $stmt -> fetch();
  $assign_id = $assign_data['assign_id'];
  
 echo(' assign_id '.$assign_id);


// need to check the rating table to see if the student rator has already started a rating ------------------------------------------


// ----------------------------------------------------------------------------------------------------------------------------------
//$student_id = 1;
$sql = 'SELECT '.$reflection_type.',`activity_id`, `student_id`,`problem_id` 
        FROM Activity WHERE `student_id`!=:student_id AND `currentclass_id` = :currentclass_id AND assign_id = :assign_id AND alias_num = :alias_num 
        ORDER BY '.$reflection_review_count.' DESC, RAND() LIMIT 0,5 ';
$stmt = $pdo->prepare($sql);
 $stmt->execute(array(
     ':assign_id' => $assign_id,
    ':currentclass_id' => $currentclass_id,
    ':alias_num' => $alias_num,
     ':student_id' => $student_id,
 ));
  $active_data = $stmt -> fetchALL();
   
 // add 1 to all the values that were selected and write the selections to a rating table
 
 foreach($active_data as $active_datum){
    $sql = 'UPDATE Activity 
            SET '.$reflection_review_count.' = '.$reflection_review_count.' + 1 
            WHERE activity_id = :activity_id';
     $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
     ':activity_id' => $active_datum['activity_id'],
        ));
     
     $sql 'INSERT INTO Rating ';
 }
 
 
 
echo('<br>');
 echo count($active_data);
  echo('<br>');
  var_dump($active_data);
 // echo(rand(0,100));














?>