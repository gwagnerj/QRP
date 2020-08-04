<?php
 session_start();
  require_once "pdo.php";
  include 'phpqrcode/qrlib.php'; 

if(isset($_GET['activity_id'])){
    $activity_id = $_GET['activity_id'];
}else{
    $_SESSION['error'] = 'activity-id lost in upload_work';  
    
}


if(isset($_POST['submit_button'])){
    $files = array_filter($_FILES['files']['name']);
    $total = count($_FILES['files']['name']);
   if ($total>0){
   
       for( $i=0 ; $i < $total ; $i++ ) {

            $file_name = $_FILES['files']['name'][$i];;
            $file_type = $_FILES['files']['type'][$i];
            $file_tmp_name = $_FILES['files']['tmp_name'][$i];
            $file_error = $_FILES['files']['error'][$i];
            $file_size = $_FILES['files']['size'][$i];
            
            $file_ext = explode('.',$file_name);
            $file_actual_ext = strtolower(end($file_ext));
            $allow = array('jpg','jpeg','png','pdf');

            
            if(in_array($file_actual_ext,$allow)){
                if($file_error ==0){
                    if ($file_size < 10000000){
                       $file_new_name = $activity_id.'-'.$i.'-'.$file_name.'.'.$file_actual_ext; 
                        $file_destination = 'student_work/'.$file_new_name;
                        // CHeck to see if there is a file by the same name
                        if (file_exists($file_destination)){
                            $_SESSION['error'] = 'File by the name, '.$file_name.', was already in system and  was overwritten';  
                        }  
                        
                        move_uploaded_file($file_tmp_name,$file_destination);
                       
                    
                     
                        
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
   
       $_SESSION['success'] = 'Your work has been successfully uploaded - '.$i.' files uploaded';
        echo('Your work has been successfully uploaded');
        // probably go somewhere else maybe apply the points so that they recieve the provisional points
   
   } else {
        $_SESSION['error'] = 'no files were selected';
       
   }
   
     
}


       $qrchecker_text = 'https://www.qrproblems.org/QRP/upload_work.php?activity_id='.$activity_id;

        $file = 'uploads/temp2 png'; 
        // $ecc stores error correction capability('L') 
        $ecc = 'M'; 
        $pixel_size = 2; 
        $frame_size = 1; 
          
        // Generates QR Code and Stores it in directory given 
          QRcode::png($qrchecker_text, $file, $ecc, $pixel_size, $frame_size); 
         
          $qrcode = "<span id = 'qrcode_id'><right><img src='".$file."'><p> Upload Files from Mobile Device by scanning the QRcode </p></right></span>"; 






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
<h1>Quick Response - Upload Your work for solving the numerical part of your problem (not the base-case or reflections)</h1>
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

<?php

        echo $qrcode;

?>


 <form method = "POST" enctype = "multipart/form-data" >
    <input type="file" name = "files[]" multiple = "multiple"> &nbsp;
    (Accepted file types: jpg, jpeg, png and pdf)<br><br>
    <button type = "submit" name = "submit_button">Subit Work Files</button>
 </form>
 
 </body>
 </html>
