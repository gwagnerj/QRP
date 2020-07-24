<?php
 session_start();
  Require_once "pdo.php";

if(isset($_GET['activity_id'])){
    $activity_id = $_GET['activity_id'];
}else{
    $_SESSION['error'] = 'activity-id lost in upload_work';  
    
}


if(isset($_POST['submit_button'])){
    $file = $_FILES['file'];
    $file_name = $file['name'];
    $file_type = $file['type'];
    $file_tmp_name = $file['tmp_name'];
    $file_error = $file['error'];
    $file_size = $file['size'];
    
    $file_ext = explode('.',$file_name);
    $file_actual_ext = strtolower(end($file_ext));
    $allow = array('jpg','jpeg','png','pdf');
    if(in_array($file_actual_ext,$allow)){
        if($file_error ==0){
            if ($file_size < 10000000){
               $file_new_name = $activity_id.'.'.$file_actual_ext; 
                $file_destination = 'student_work/'.$file_new_name;
                move_uploaded_file($file_tmp_name,$file_destination);
                $_SESSION['success'] = 'Your work has been successfully uploaded';
                header('Location: blank.php');
                die();
                
            }else {
                 $_SESSION['error'] = 'File size is too large max file size is 10Mb'; 
            }
        } else {
                $_SESSION['error'] = 'This error in file of type'.$file_error; 
        }
    } else {
            $_SESSION['error'] = 'This file type is not allowed (only image and pdf files)';
    }
}










?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QR Assignment upload </title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<style>
 
</style>



</head>

<body>
<header>
<h1>Quick Response - Upload Your Work</h1>
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

 <form method = "POST" enctype = "multipart/form-data" >
    <input type="file" name = "file">
    <button type = "submit" name = "submit_button">Upload Your Work</button>
 </form>
 
 </body>
 </html>
