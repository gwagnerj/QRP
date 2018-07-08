<?php
// Include config file
require_once 'pdo.php';
session_start();
session_unset();
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = $university = "";
$username_err = $password_err = $confirm_password_err = $university_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT users_id FROM Users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        unset($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST['password']))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST['password'])) < 6){
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST['password']);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = 'Please confirm password.';     
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if($password != $confirm_password){
            $confirm_password_err = 'Password did not match.';
        }
    }
	
	// Validate university
    if(empty(trim($_POST['university']))){
        $password_err = "Please enter a school or organization.";     
    } elseif(strlen(trim($_POST['university'])) < 2){
        $university_err = "School or organization name must have at least 2 characters.";
    } else{
        $university = htmlentities(trim($_POST['university']));
    }
	// Validate email
    if(empty(trim($_POST['email']))){
        $email_err = "Please enter a valid email address that you check regularly.";     
    } elseif(strlen(trim($_POST['email'])) < 4){
        $email_err = "email input too short to be a valid email address.";
    } else{
        $email = htmlentities(trim($_POST['email']));
    }
	// Validate first name
    if(empty(trim($_POST['first']))){
        $first_err = "Please enter a valid first name.";     
    } elseif(strlen(trim($_POST['first'])) < 2){
        $first_err = "First name too short.";
    } else{
        $first = htmlentities(trim($_POST['first']));
    }
	// Validate last name
    if(empty(trim($_POST['last']))){
        $last_err = "Please enter a valid last name.";     
    } elseif(strlen(trim($_POST['last'])) < 2){
        $last_err = "last name too short.";
    } else{
        $last = htmlentities(trim($_POST['last']));
    }
	
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($university_err) && empty($email_err) && empty($first_err) && empty($last_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO Users (username, password, university, security, email, first, last) VALUES (:username, :password, :university, :security,:email, :first, :last)";
         
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $param_password, PDO::PARAM_STR);
            $stmt->bindParam(':university', $param_university, PDO::PARAM_STR);
            $stmt->bindParam(':security', $param_security, PDO::PARAM_STR);
		    $stmt->bindParam(':email', $param_email, PDO::PARAM_STR);
		    $stmt->bindParam(':first', $param_first, PDO::PARAM_STR);
			$stmt->bindParam(':last', $param_last, PDO::PARAM_STR);
			
			
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_university = $university;
			 $param_security = 'contrib';  //its either contrib or admin
			 $param_email = $email;
		     $param_first = $first; 
		     $param_last = $last;
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
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
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username"class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
			 <div class="form-group <?php echo (!empty($university_err)) ? 'has-error' : ''; ?>">
                <label>School or Organization</label>
                <input type="text" name="university"class="form-control" value="<?php echo $university; ?>">
                <span class="help-block"><?php echo $university_err; ?></span>
				 </div>     <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>email you check regularly</label>
                <input type="text" name="email"class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>    
             <div class="form-group <?php echo (!empty($first_err)) ? 'has-error' : ''; ?>">
                <label>First Name</label>
                <input type="text" name="first"class="form-control" value="<?php echo $first; ?>">
                <span class="help-block"><?php echo $first_err; ?></span>
            </div>  
			<div class="form-group <?php echo (!empty($last_err)) ? 'has-error' : ''; ?>">
                <label>Last Name</label>
                <input type="text" name="last"class="form-control" value="<?php echo $last; ?>">
                <span class="help-block"><?php echo $last_err; ?></span>
            </div>    
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>