<?php
require_once "pdo.php";
session_start();

// Guardian: Make sure that problem_id is present
if ( ! isset($_GET['problem_id']) or ! isset($_GET['users_id']) ) {
  $_SESSION['error'] = "Missing problem_id";
  header('Location: QRPRepo.php');
  return;
}
	
    $sql = "SELECT * FROM Problem WHERE problem_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_GET['problem_id']));
	$problem_data = $stmt -> fetch();
	
	 $sql = "SELECT * FROM Users WHERE users_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_GET['users_id']));
	$Users_data = $stmt -> fetch();
	
	// check to see if this is a new problem and they want the start over file issued
	if ($data['status']=='num issued'){
		$_SESSION['error'] = 'The status of this problem is num issued and cannot be activated';
	 	header( 'Location: QRRepo.php' ) ;
		return;
	}
	
	if ($data['status']=='Active'){
	// put code in here to confirm deactivation and then change status to inactive
		header( 'Location: QRRepo.php' ) ;
		return;
	}
	
	// here we will check to see if all of the information is filled out if it is then 
	// write the information to the assignment table and return to the QRrepo







	
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
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRProblems</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
</head>

<body>
<header>
<h2>Activate Problem - Please select the options that you want with this problem</h2>
</header>	
	 <div class="wrapper">
       
        <form  method="post">
           <?php 
				if(! empty(trim($problem_data['preprob_3']))){
					echo ('<input type="checkbox" name="Prelim_MC" value="Prelim_MC_q"> Preliminary Multiple Choice Question <br>');
				}
			<?php 
				if(! empty(trim($problem_data['preprob_4']))){
					echo ('<input type="checkbox" name="Mics" value="Prelim_misc"> Additional Preliminary Activities <br>');
				}
			?>
			
			
			<input type="checkbox" name="guess" value="Prelim_guess"> Preliminary Estimates <br>
			<input type="checkbox" name="q_on_q" value="Prelim_q_on_q"> Questions about the Question <br>
			
			
			
			
        </form>
    </div>    
 
<br>
<a href="QRPRepo.php">Finished or Cancel</a>
</body>
</html>
