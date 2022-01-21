<?php

require_once 'pdo.php';
require_once "simple_html_dom.php";
// include 'phpqrcode/qrlib.php'; 
// require_once '../encryption_base.php';

$response = $student_id = $question_id = "";

if (isset($_GET['response'])){$response = $_GET['response'];}
if (isset($_GET['student_id'])){$student_id = $_GET['student_id'];}
if (isset($_GET['question_id'])){$question_id = $_GET['question_id'];}
  $response = htmlentities($response);
  $student_id = htmlentities($student_id);
  $question_id = htmlentities($question_id);
echo '<h1> you, new one with a student_id of ' . $student_id . ' responded to question id ' . $question_id.' with a response of ' . $response.'</h1>';

$sql = "SELECT * FROM Question WHERE question_id = :question_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
':question_id' => $question_id
));
    $question_data = $stmt->fetch(PDO::FETCH_ASSOC);

    $key = 'key_'.$response;
    $pblm_score = $question_data[$key];

    echo 'Your Score on this problem is '.$pblm_score.'%';

?>
 
