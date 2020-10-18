<?php
// Include config file
require_once 'pdo.php';
session_start();
if (isset($_SESSION['success'])){
	echo $_SESSION['success'];	
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])){
	echo $_SESSION['error'];
	unset($_SESSION['error']);
}

//session_unset();
 // check for a printed exam
 //$_GET['dex_code'] = 9784;
 if(isset($_GET['dex_code'])){
     $_SESSION['dex_code'] = $_GET['dex_code'];
     $dex_code = $_GET['dex_code'];
 } elseif(isset($_POST['dex_code'])){
     $_SESSION['dex_code'] = $_POST['dex_code'];
     $dex_code = $_POST['dex_code'];
 } elseif(isset($_SESSION['dex_code'])){
      $dex_code = $_SESSION['dex_code'];
 } else {
      $dex_code = 0;
 }
 
 // Define variables and initialize with empty values
$username = $password = "";
$username_err = $code_err = $password_err = "";
 
 if ($dex_code != 0){
     
     // need some error checking on the dex code if it is there but does not result in 150 thru 159 then there was an error
      if($dex_code < 1000 ||$dex_code >= 10000 )
          {
        $code_err = 'qrcode number is in error.  Please re-input this number';
       } else {
     
           $dex_code_string = (string)$dex_code;  
             $key = $dex_code_string[0];
             $mid_two = ($dex_code_string[1].$dex_code_string[2])+0;
             $last_dig = $dex_code_string[3];
             
             if ($key==1){$ver = $mid_two - $last_dig - 16;}
             if ($key==2){$ver = $mid_two - $last_dig - 25;}
             if ($key==3){$ver = $mid_two - $last_dig - 36;}
             if ($key==4){$ver = $mid_two - $last_dig - 49;}
             if ($key==5){$ver = $mid_two - $last_dig - 64;}
             if ($key==6){$ver = $mid_two - $last_dig - 81;}
             if ($key==7){$ver = $mid_two - $last_dig - 53;}
             if ($key==8){$ver = $mid_two - $last_dig - 23;}
             if ($key==9){$ver = $mid_two - $last_dig - 73;}
             
             $dex_print = 150 + $ver;
      
         if(($dex_print >= 1 && $dex_print <= 149)||($dex_print >= 160 )||$dex_print < 0){
             
            $code_err = 'qrcode number is in error.  Please re-input this number';
            
         }
      }
   //  echo('dex_print'.$dex_print);
 } else {
     $dex_print = 0;
 }
 
 
 
 
 // see if they entered a username or an email address
 
 

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = 'Please enter username.';
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST['password']))){
        $password_err = 'Please enter your password.';
    } else{
        $password = trim($_POST['password']);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err) && empty($code_err)){
        // Prepare a select statement
        $sql = "SELECT username, password,student_id FROM Student WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if username exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $password_table = $row['password'];
                        if($password_table==$password){
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            session_start();
                            $_SESSION['username'] = $username;      
                            header("location: QRExamRegistration2.php?student_id=".$row['student_id']."&dex_print=".$dex_print);
                        } else{
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered was not valid.';
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = 'No account found with that username.';
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>

<link rel="icon" type="image/png" href="McKetta.png" />  
    <meta charset="UTF-8">
<title>QRP Student Exam Login</title>
 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
	
        <h2>Welcome to Quick Response Exam Student Login</h2>
        <p>Please fill in your credentials to login.</p>
		
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
              <div class="form-group <?php echo (!empty($code_err)) ? 'has-error' : ''; ?>">
                <label>If taking a printed exam or quiz that has a QR Code the number below the QRcode (otherwise leave blank)</label>
                <input type="number" name="dex_code" class="form-control" value="<?php echo $dex_code; ?>">
                <span class="help-block"><?php echo $code_err; ?></span>
            </div>    
            
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username"class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
		</form>
		
		 </p> </br>
			<p>Forgot your Password or Usename? <a href="stu_pswdRecovForm.php">Click Here</a>.</p> </br>
			<p>Don't have an account? <a href="stu_register.php">Sign up now</a>.</p>
			<br/>
		</br>
		  
       
    </div>
</body>
</html>

