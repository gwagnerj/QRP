<?php 
require_once "pdo.php";

// defaults for testing
$question_id = 66;
$currentclass_id = 44;

$error ='';

if(isset($_GET['question_id'])){
    $question_id = $_GET['question_id'];
} else {
    $error = $error.' question id not set in QR_to_quickQuestion. ';
}
if(isset($_GET['currentclass_id'])){
    $currentclass_id = $_GET['currentclass_id'];
} else {
    $error = $error.' currentclass id not set in QR_to_quickQuestion. ';
}

if (strlen($error)>1){
    echo $error;
    die();
}

header('Location: url_to_quickQuestion.php?question_id='.$question_id.'&currentcourse_id='.$currentclass_id);

die();


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>



<script>



</script>
</body>
</html>