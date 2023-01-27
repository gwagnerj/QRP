<?php
 session_start();
 // require_once "pdo.php";
  include 'phpqrcode/qrlib.php'; 
  // include the email php mailer
    require 'PHPMailer/PHPMailerAutoload.php';  // copilot so who knows


if(isset($_GET['activity_id'])){
    $activity_id = $_GET['activity_id'];
 //   echo 'activity_id on GET '.$activity_id;
} elseif(isset($_POST['activity_id'])){
    $activity_id = $_POST['activity_id'];

}else{
    $_SESSION['error'] = 'activity-id lost in upload_work';  
    
}

if(isset($_GET['activity_id'])){
    $problem_id = $_GET['problem_id'];
} elseif(isset($_POST['problem_id'])){
    $problem_id = $_POST['problem_id'];

}else{
    $_SESSION['error'] = 'problem_id lost in upload_work';  
}

 if(isset($_GET['describe_question'])){
    $describe_question = $_GET['describe_question'];
} elseif(isset($_POST['describe_question'])){
    $describe_question = $_POST['describe_question'];
}else{
    $_SESSION['error'] = 'describe_question lost in upload_work';  
}






if(isset($_POST['submit_button'])){
    $files = array_filter($_FILES['files']['name']);
        @$total = count($files);


  if ($total>=1){  
   
       for( $i=0 ; $i < $total ; $i++ ) {

            $file_name = $_FILES['files']['name'][$i];
            $file_name = preg_replace("/[^\w\-\.]/",'',$file_name);  // put this in to take care of students putting # sign in file name
   
            $file_type = $_FILES['files']['type'][$i];
            $file_tmp_name = $_FILES['files']['tmp_name'][$i];
            $file_error = $_FILES['files']['error'][$i];
            $file_size = $_FILES['files']['size'][$i];
            
            $file_ext = explode('.',$file_name);
            $file_actual_ext = strtolower(end($file_ext));
            $allow = array('jpg','jpeg','png','pdf');

            
            if(in_array($file_actual_ext,$allow)){
                if($file_error ==0){
                    if ($file_size > 2){
                        if ($file_size < 20000000){
                           $file_new_name = $activity_id.'-'.$i.'-'.$file_name; 
                         //  echo 'file_new_name: '.$file_new_name;
                            $file_destination = 'student_work/'.$file_new_name;
                            // CHeck to see if there is a file by the same name
                            if (file_exists($file_destination)){
                                $_SESSION['error'] = 'File by the name, '.$file_name.', was already in system and  was overwritten';  
                            }  
                            move_uploaded_file($file_tmp_name,$file_destination);
                        }else {
                             $_SESSION['error'] = 'Error - File size is too large max file size is 20Mb'; 
                        }
                    } else {
                             $_SESSION['error'] = 'Error - File size is less that 2 bites'; 
                    }                        
                } else {
                        $_SESSION['error'] = ' Error in file of type'.$file_error; 
                }
            } else {
                    $_SESSION['error'] = 'This file type is not allowed (only image and pdf files)';
            }
        
       }
   
       $_SESSION['success'] = 'Your work has been successfully uploaded - '.$i.' activity_id is '.$activity_id.' files uploaded destination '.$file_destination;
                header("Location: finished_uploading.php");
                 die();

   
   } else {
        $_SESSION['error'] = 'no files were selected';
       
   }
   
     
}


       $qrchecker_text = 'https://www.qrproblems.org/QRP/upload_work_for_email.php?activity_id='.$activity_id;

        $file = 'uploads/temp2 png'; 
        // $ecc stores error correction capability('L') 
        $ecc = 'M'; 
        $pixel_size = 2; 
        $frame_size = 1; 
          
        // Generates QR Code and Stores it in directory given 
          QRcode::png($qrchecker_text, $file, $ecc, $pixel_size, $frame_size); 
         
          $qrcode = "<span id = 'qrcode_id'><right><img src='".$file."'><p> You can upload work from a Mobile Device by scanning the QRcode then Selecting Choose Files or from you computer </p></right></span>"; 


?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QR Base-Case Upload for Email</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<style>
 #spinner {
    position:fixed;
    top:50%;
    left:50%;
    
}
</style>



</head>

<body>
<header>
<h1>Quick Response - Upload Your work on the base-case for email to the professor</h1>
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
<div di = "spinner" style = "display:none;"> 
    <img id = "img_spinner" src = "spinner.gif" alt = "loading" >
</div>

     <form method = "POST" enctype = "multipart/form-data" >
        <input type="file" id = "files" name = "files[]" multiple = "multiple"> &nbsp;
        <input type="hidden" id = "activity_id" name = "activity_id" value = "<?php echo $activity_id ?>"> 
        (Accepted file types: jpg, jpeg, png and pdf)<br><br>
       

       <button type = "submit" style = "width: 30%; background-color: red; color: white" id = "submit_button" name = "submit_button">Submit Work Files</button> <br><br><br>
        


     </form>
     
     <script>
     	$(document).ready(function(){
              $('#submit_button').hide();
             $('#files').change(function(){
                   $('#submit_button').show(); 
                   $('#spinner').show(); 
             })
      });
     </script>
     </body>
     
    
     </html>
