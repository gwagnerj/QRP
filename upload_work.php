<?php
 session_start();
 // require_once "pdo.php";
  include 'phpqrcode/qrlib.php'; 
/* 
$url_array = explode('?','http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
$url = $url_array[0].'&output=embed';

// include your composer dependencies
//require_once 'vendor/autoload.php';
require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_DriveService.php';

$client = new Google_Client();
$client->setClientId('930402235853-osbdph28qi2laqkl3rs1g3q9k6k02pot.apps.googleusercontent.com');

$client->setClientSecret('vTbxb3tubQwZnFtt0Uo-XBL1');

$client -> setRedirectUri($url);
$client->setScopes(array('https://www.googleapis.com/auth/drive'));

if (isset($_GET['code'])) {
    $_SESSION['accessToken'] = $client->authenticate($_GET['code']);
    header('location:'.$url);exit;
} elseif (!isset($_SESSION['accessToken'])) {
    $client->authenticate();
}



 */

/* 
$client->setApplicationName("Client_Library_Examples");
$client->setDeveloperKey("MY_SIMPLE_API_KEY");

$service = new Google_Service_Books($client);
$optParams = array('filter' => 'free-ebooks');
$results = $service->volumes->listVolumes('Henry David Thoreau', $optParams);

foreach ($results->getItems() as $item) {
  echo $item['volumeInfo']['title'], "<br /> \n";
}
 */

// to upload to google drive we have for the client Id  930402235853-osbdph28qi2laqkl3rs1g3q9k6k02pot.apps.googleusercontent.com and for the 
// client secret we have vTbxb3tubQwZnFtt0Uo-XBL1  



if(isset($_GET['activity_id'])){
    $activity_id = $_GET['activity_id'];
}else{
    $_SESSION['error'] = 'activity-id lost in upload_work';  
    
}

/* 
if (isset($_POST['finished_button'])){
    
        
             $sql ='UPDATE `Activity` SET `progress` = :progress  WHERE activity_id = :activity_id';
                $stmt = $pdo -> prepare($sql);
                $stmt -> execute(array(
                        ':progress' => 9,  // this should reset the page pack to the frontpage
                        ':activity_id' => $activity_id
                     )); 
        
    console.log('trying to close');
    echo '<script> window.top.location.reload(); </script>';
    
}
 */
if(isset($_POST['submit_button'])){
    $files = array_filter($_FILES['files']['name']);
 //echo (' files '.$files);
        @$total = count($files);

 // echo(' $total   '.$total);

  if ($total>=1){  
   
       for( $i=0 ; $i < $total ; $i++ ) {

            $file_name = $_FILES['files']['name'][$i];
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
   
       $_SESSION['success'] = 'Your work has been successfully uploaded - '.$i.' files uploaded';
     //   echo('Your work has been successfully uploaded');
        // probably go somewhere else maybe apply the points so that they recieve the provisional points
                header("Location: finished_uploading.php");
                 die();

   
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
         
          $qrcode = "<span id = 'qrcode_id'><right><img src='".$file."'><p> You can upload work from a Mobile Device by scanning the QRcode then Selecting Choose Files or from you computer </p></right></span>"; 






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
 #spinner {
    position:fixed;
    top:50%;
    left:50%;
    
}
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
<div di = "spinner" style = "display:none;"> 
    <img id = "img_spinner" src = "spinner.gif" alt = "loading" >
</div>

     <form method = "POST" enctype = "multipart/form-data" >
        <input type="file" id = "files" name = "files[]" multiple = "multiple"> &nbsp;
        (Accepted file types: jpg, jpeg, png and pdf)<br><br>
       

       <button type = "submit" style = "width: 30%; background-color: red; color: white" id = "submit_button" name = "submit_button">Submit Work Files</button> <br><br><br>
        
    <!--            <button type = "submit" style = "width: 30%; background-color: blue; color: white" id = "finished_button" name = "finished_button">Finished with Problem</button>
-->
<h3> When Finished use the "back" button at the top of the page</h3>

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
