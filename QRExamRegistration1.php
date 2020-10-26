<?php
// Include config file
require_once 'pdo.php';
session_start();


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
     
      if($dex_code < 10000 ||$dex_code >= 100000 )  // should be a 5 digit number if it is not it is an error
          {
        $code_err = 'version code number is in error.  - This is the number below the QR code on a printed exam';
       } else {
     
           $dex_code_string = (string)$dex_code;  
             $key = $dex_code_string[0];
             $mid_three = ($dex_code_string[1].$dex_code_string[2].$dex_code_string[3])+0;
             $last_dig = $dex_code_string[4];
             
             
             If ($mid_three < 300){  // dex is over a three digit number
                 $dex_print = $mid_three - $key-$last_dig;
             } elseif($mid_three < 600){  // dex is a one digit number
                $dex_print = $mid_three - 300 - $last_dig;
            } else {  // dex is a two digit number
                $dex_print = $mid_three - 600 - $last_dig;
             }
         if($dex_print >= 201 || $dex_print < 0){
            
            $_SESSION['error'] = '<h3 style="color:red;"> &nbsp; Version Code is in error.  Please re-input this number </h3>';
            $code_err = 'qrcode number is in error.  Please re-input this number';
             header('Location:  QRExamRegistration.php');
            die;
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

if (isset($_SESSION['success'])){
	echo $_SESSION['success'];	
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])){
	echo $_SESSION['error'];
	unset($_SESSION['error']);
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
		
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
              
                <span name="dex_code"><?php echo $dex_code; ?> </span>
         

            
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

