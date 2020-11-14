<?php
// Include config file
require_once 'pdo.php';
require_once '../email_password.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';// Load Composer's autoloader
require_once "random_compat-2.0.18/lib/random.php"; // needed this for the random_bytes function did not work on the online version

session_start();
session_unset();
$mail = new PHPMailer(true);
// Define variables and initialize with empty values
$username = $password = $confirm_password = $university = "";
$username_err = $email2 = $school_email_err = $password_err = $confirm_password_err = $university_err = "";
$first_err = $last_err = $first = $last = $new_univ = $school_email =  $university_err = $exp_date = $email2_err= "";
 
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
                    $username = trim(htmlentities($_POST["username"]));
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
    } elseif(strlen(trim($_POST['password'])) < 5){
        $password_err = "Password must have at least 5 characters.";
    } else{
        $password = trim(htmlentities($_POST['password']));
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
	
	// This needs to be a drop down from university_id (thats what needs to go in the student table --------------
    if(empty(trim($_POST['university'])) ){
        $university_err = "Please enter a school or organization.";     
    } elseif(empty(trim($_POST['university'])) ){
        $university_err = "School or organization name must have at least 2 characters.";
    } 	else {
			
		  $university = htmlentities(trim($_POST['university']));	
			
	}
		
    
	// Validate school email this should only have a specific form for appprove universities ---------------
    if(empty(trim($_POST['school_email']))){
        $School_email_err = "Please enter your school or University email address that you check regularly.";     
    } elseif(strlen(trim($_POST['school_email'])) < 4){
        $school_email_err = "email input too short to be a valid email address.";
    } else{
        $school_email = htmlentities(trim($_POST['school_email']));
    }
    
    // Validate secondary email (optional) ---------------
    if(empty(trim($_POST['email2']))){
        $email2_err = "Please enter a valid email address that you check regularly.";     
    } elseif(strlen(trim($_POST['email2'])) < 4){
        $email2_err = "email input too short to be a valid email address.";
    } else{
        $email2 = htmlentities(trim($_POST['email2']));
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
	
	$default_subject = $_POST['subject'];
	
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($university_err) && 
	empty($email_err) && empty($first_err) && empty($last_err) && empty($security_err) && empty($sponsor_err) && 
	empty($university_err)  && empty($course_name_error)){
        
    // Prepare an insert statement ------------------------------------------------(fix this)-------------
    
    $sql = "INSERT INTO `Student` (`username`, `password`, `university`, `school_email`, `email2`, first_name, `last_name`, `exp_date`)
                           VALUES (:username, :password, :university, :school_email, :email2, :first_name, :last_name,  :exp_date)";

    
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $param_password, PDO::PARAM_STR);
        $stmt->bindParam(':university', $param_university, PDO::PARAM_STR);
        $stmt->bindParam(':school_email', $param_school_email, PDO::PARAM_STR);
        $stmt->bindParam(':email2', $param_email2, PDO::PARAM_STR);
        $stmt->bindParam(':first_name', $param_first, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $param_last, PDO::PARAM_STR);
        $stmt->bindParam(':exp_date', $param_exp_date, PDO::PARAM_STR);
        
        // Set parameters
        $param_username = $username;
        $param_password = ($password); 
        $param_university = $university;
         $param_school_email = $school_email;  
         $param_email2 = $email2;
         $param_first = $first; 
         $param_last = $last;
         $param_exp_date = $exp_date;

                 
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            
            
            // now send email
            
            // get the email of the sponsor
            
                $subject = 'QRProblems - Registering a user';
                $body = '<p>QRProblems has recieved a registration request for  '.$first.' '.$last.' at email '.$school_email.' <p> 
                        <p> Welcome to the QRproblems! You can log on to the system at QRhomework.org - you may want to bookmark this site. 
                        If you have questions or issues on using the system please contact the current system administrator John Wagner at wagnerj@trine.edu';
                            
                try {
                //Server settings
                $mail->SMTPDebug = 0;                                       // Enable verbose debug output 0 is off and 4 is everything
                $mail->isSMTP();                                            // Set mailer to use SMTP
					$mail->Host       = $email_host;  // Specify main and backup SMTP servers
					$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
					$mail->Username   = $email_username;                     // SMTP username
					$mail->Password   =  $email_password;                              // SMTP password
                $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
                $mail->Port       = 587;                                    // TCP port to connect to try new port if using ssl

                //Recipients
                //$mail -> setFrom($email);
                $mail->setFrom('wagnerj@excelproblempedia.org', 'John');
                $mail->addAddress($school_email);     // Add a recipient
               // $mail->addAddress('wagnerj@trine.edu');               // Name is optional	
                $mail->addAddress('gwagnerj@gmail.com');               // Name is optional	
                        
                
                $mail->isHTML(true);  	
                $mail -> Subject = $subject;
                
                $mail->Body    = $body;
                $mail->AltBody = strip_tags($body);			
                
                $mail->send();
                

                } catch (Exception $e) {
                    $_SESSION['failure'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    // echo '<a href = "emailForm.php" >back to email form</a>';
                }	
            $_SESSION['sucess'] = 'registration sucessful';
            
            $mail2 = new PHPMailer(true);  // trying a new instance to mail a secound email
            // send an email to sponsor and admin
            // get the email of the sponsor
            
                $subject = 'QRProblems - Registering a user ';
                $body = '<p>QRProblems has recieved a registration request for  '.$first.' '.$last.' at email '.$email2.' <p> 
                        <p> Welcome to the QRproblems! You can log on to the system at QRhomework.org - you may want to bookmark this site. 
                        If you have questions or issues on using the system please contact the current system administrator John Wagner at wagnerj@trine.edu';
                            
                try {
                //Server settings
                $mail2->SMTPDebug = 0;                                       // Enable verbose debug output 0 is off and 4 is everything
                $mail2->isSMTP();                                            // Set mailer to use SMTP
					$mail->Host       = $email_host;  // Specify main and backup SMTP servers
					$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
					$mail->Username   = $email_username;                     // SMTP username
					$mail->Password   =  $email_password;                              // SMTP password
                $mail2->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
                $mail2->Port       = 587;                                    // TCP port to connect to try new port if using ssl

                //Recipients
                //$mail -> setFrom($email);
                $mail2->setFrom('wagnerj@excelproblempedia.org', 'John');
                $mail2->addAddress($email2);     // Add a recipient
            //    $mail2->addAddress('gwagnerj@gmail.com');               // Name is optional	
           //     $mail2->addAddress('wagnerj@trine.edu');               // Name is optional			
                
                $mail2->isHTML(true);  	
                $mail2 -> Subject = $subject;
                $mail2 -> Body = $body;
            
                $mail2->AltBody = strip_tags($body);			
                
                $mail2->send();
                   // echo 'Message has been sent';
                //	$response = "Email is Sent - to system administrator and sponsor";
                //	echo ($response);
                
                //	echo '</br></br>';
                    // echo '<a href = "emailForm.php" >back to email form</a>';


                } catch (Exception $e) {
                    $_SESSION['failure'] = "Message could not be sent. Mailer Error: {$mail2->ErrorInfo}";
                    // echo '<a href = "emailForm.php" >back to email form</a>';
                }	
            // send an email to the new user welcoming them
            
            // Redirect to login page
            $_SESSION['sucess'] = $_SESSION['sucess'].' Welcome e-mail should have been sent to your email account(s)  - Please log in';
            header("location: QRhomework.php");
        } else{
            echo "Something went wrong. Please try again later.";
        }
    }
         
    // Close statement
    unset($stmt);
    
   } else {
    
    echo ' There is an error in one of the parameters';
    echo '</br>';
    if (!empty($username_err)){echo $username_err; echo '</br>';}
    if (!empty($password_err)){echo $password_err; echo '</br>';}
    if (!empty($university_err)){echo $university_err; echo '</br>';}
    if (!empty($sponsor_err)){echo $sponsor_err; echo '</br>';}
    if (!empty($security_err)){echo $security_err; echo '</br>';}
    
	}
   
}
$_SESSION['checker'] = 2;  // for getid.php and the sponsor ID number
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Sign-up for Quick Response System</title>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 70%; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Student Sign Up for Quick Response System</h2>
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
			
			
			<div class="form-group <?php echo (!empty($school_email_err)) ? 'has-error' : ''; ?>" >
                <label>University e-mail Address</label>
                <input type="text" name="school_email" class="form-control" value="<?php echo $school_email; ?>">
                <span class="help-block"><?php echo $school_email_err; ?></span>
            </div>	
             <div class="form-group <?php echo (!empty($email2_err)) ? 'has-error' : ''; ?>">
                <label>Alternate e-mail Address (optional)</label>
                <input type="text" name="email2" class="form-control" value="<?php echo $email2; ?>">
                <span class="help-block"><?php echo $email2_err; ?></span>
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

			<div class="form-group <?php  echo (!empty($university_err)) ? 'has-error' : ''; ?>">
                <label>School or Organization</label></br>
				
				<div id ="univ_drop_down">	
				&nbsp; &nbsp; <select name = "university" id = "university">
				<?php
					$sql = 'SELECT `university_name` FROM `University` ';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute();
					while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) 
						{ ?>
						<option value="<?php echo $row['university_name']; ?>"<?php   if($row['university_name']=='Trine University'){echo 'selected';}?> ><?php echo $row['university_name']; ?> </option>
						<?php
 							}
						?>
				</select>
				</div>
				
				<span class="help-block"><?php echo $university_err; ?></span>
			 <p>&nbsp; &nbsp; School or Organization Not Shown?  Contact: <a href="mailto:wagnerj@trine">John Wagner, System Administrator</a>.<br>
             </div>    
			
						
			</br>
			
			
			</br>
			<div id = "subject">
			 <label>Default Quantitative Subject?</label> </br>
			&nbsp; &nbsp; <select name = "subject">
				 <option value = '1'> Math</option>
				 <option value = '2'> Chemistry</option>
				 <option value = '3'> Biology</option>
				 <option value = '4'> Medicine</option>
				 <option value = '5'> Other Science</option>
				 <option value = '6'> Business / Economics</option>
				 <option selected = "selected" value = '7'> Chemical Engineering</option> 
				 <option value = '8'> Civil Engineering</option>
				 <option value = '9'> Mechanical Engineering</option>
				 <option value = '10'> Electrical Engineering</option>
				 <option value = '11'> Biomedical Engineering</option>
				 <option value = '12'> Other Engineering</option>
				 <option value = '13'> Other - Not Listed</option>
				 
			</select>
			</div>
			<br /></br />
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
			</br>
            <p>Already have an account? <a href="QRhomework.php">Login here</a>.</p>
           
        </form>
    </div>    
</body>

<script>
	
</script>
</html>