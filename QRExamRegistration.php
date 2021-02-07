<?php
// Include config file
require_once 'pdo.php';
session_start();


//session_unset();
 // check for a printed exam
 //$_GET['dex_code'] = 9784;
 if(isset($_GET['dex_code'])){  // probably comming in with a scanned qrcode
     $dex_code=$_GET['dex_code'];
     header('Location:QRExamRegistration1.php?dex_code='.$dex_code);  
        die;
 } elseif(isset($_POST['dex_code'])&& strlen($_POST['dex_code'])>0){
     $dex_code = $_POST['dex_code'];
     header('Location:QRExamRegistration1.php?dex_code='.$dex_code);  
 }  else {
      $dex_code = 0;
 }
 $code_err = '';
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  
   
  
   if ( $_POST['qrcode']=='yes') {
        $_SESSION['error'] = ' Must Input Version Code';

   } else {
         header('Location:QRExamRegistration1.php?dex_code='.$dex_code);   
   }
 
 
    
    // Close connection
    unset($pdo);
}
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="icon" type="image/png" href="McKetta.png" />  
    <meta charset="UTF-8">
<title>QRP Student Exam Login</title>
 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ padding: 20px; }
        .
    </style>
</head>
<body>
    <div class="wrapper">
	
        <h2>Welcome to Quick Response Exam Student Login</h2> <br>
		
        <form id = "form_id" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
             <div class="form-group">
                 <?php echo (!empty($code_err)) ? 'has-error' : ''; ?>
                <label><h3>Do you have a printed form of the exam or Quiz that <b> has a <u>QRcode</u></b> on the first page?</h3></label><br>
               <h4>&nbsp; <input type="radio" id = "yes" name="qrcode" value = "yes" required> <label for="yes"> Yes </Label>&nbsp; <span id = "dex_code_input" >Input Version Code located Below the QRcode: <input type ="number" id = "dex_code" name = "dex_code" min = "10000" max = "99999">  </h4>
                <h4>&nbsp; <input type="radio" id = "no" name="qrcode" value = "no" > <label for="no"> No </Label> </h4>
             
            </div>    
            <div class="form-group <?php echo (!empty($code_err)) ? 'has-error' : ''; ?>">
            
               <span class="help-block"><?php echo $code_err; ?></span>
            <div class="form-group">
            <br>
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
		</form>
		
		
		</br>
		  
       
    </div>
</body>
</html>
<script>
$(document).ready( function () {
    $('#dex_code_input').hide();
   
   $('#form_id input').on('change', function(){
       
     //  console.log($('input[name=qrcode]:checked','#form_id').val());
       
       if($('input[name=qrcode]:checked','#form_id').val()=='yes'){
            $('#dex_code_input').show();
        } else {
            $('#dex_code_input').hide();
        }
     });   
     
});
</script>
