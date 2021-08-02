<?php

$activity_id = $_POST['activity_id'];
$problem_id = $_POST['problem_id'];
$i = 1;
// $file_name = "drawing_tool_image";
$file_name = $problem_id;
$file_new_name = $activity_id.'-drawing-'.$i.'-problem-'. $problem_id.'.png'; 
$dir = "drawing_tool_images/";
$file_destination = $dir.$file_new_name;


move_uploaded_file($_FILES["image"]["tmp_name"], $file_destination);

// move_uploaded_file($_FILES["image"]["tmp_name"], $dir. $_FILES["image"]["name"]);
echo "this is a message from upload_painterro_image ".$activity_id;
?>