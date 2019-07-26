<?php
// Include config file
require_once 'pdo.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';// Load Composer's autoloader
require_once "random_compat-2.0.18/lib/random.php"; // needed this for the random_bytes function did not work on the online version

session_start();
session_unset();
$mail = new PHPMailer(true);
// Define variables and initialize with empty values
$username = $password = $confirm_password = $university = "";
$username_err = $email = $email_err = $password_err = $confirm_password_err = $university_err = "";
$first_err = $last_err = $first = $last = $new_univ = $security_err = $sponsor_err = $university_err = $exp_date = "";
 
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
    } elseif(strlen(trim($_POST['password'])) < 6){
        $password_err = "Password must have at least 6 characters.";
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
	
	// Validate university
    if(empty(trim($_POST['university'])) && empty(trim($_POST['new_univ']))){
        $university_err = "Please enter a school or organization.";     
    } elseif(empty(trim($_POST['university'])) && strlen(trim($_POST['new_univ'])) < 2){
        $university_err = "School or organization name must have at least 2 characters.";
    } elseif( empty(trim($_POST['university']))){ // we should add the new_univ to the University table
        $university = htmlentities(trim($_POST['new_univ']));
		$sql = 'INSERT INTO University (university_name) VALUES (:university_name)';
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':university_name' => $university
				));			
		
	}	else {
			
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
	
	// validate security level
	 if(empty(trim($_POST['security']))){
        $security_err = "Please enter a valid type of account.";     
    
    } else{
        $security = $_POST['security'];
    }
	
	// if the security level is TA then check for that at least one course is selected
	if ($security == 'TA'){
			if(empty($_POST['course_name'])){
				$course_name_error = 'you need to check at least one course';
			
			} else {
				$course_name_error ='';
				//print_r ($_POST['course_name'][0]);
				//die();
				if (!empty($_POST['course_name'][0])){$TA_course_1=  $_POST['course_name'][0];}
				if (!empty($_POST['course_name'][1])){$TA_course_2=  $_POST['course_name'][1];}
				if (!empty($_POST['course_name'][2])){$TA_course_3=  $_POST['course_name'][2];}
				if (!empty($_POST['course_name'][3])){$TA_course_4=  $_POST['course_name'][3];}
				$exp_date = date("Y-m-d", strtotime("+6 month"));
			}
		
	}
	// if the security level is grader then set the expiration date	
	if ($security == 'grader'){
		$exp_date = date("Y-m-d", strtotime("+6 month"));
	}
	
	// Validate Sponsor
	 if(empty(trim($_POST['sponsor']))){
        $sponsor_err = "Please enter a valid sponsor.";     
    
    } else{
       
		//check the security level of the sponsor
			$sql = 'SELECT * FROM `Users` WHERE users_id = :users_id';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(
					':users_id' => $_POST['sponsor']
					));
				
					$s_row = $stmt->fetch(PDO::FETCH_ASSOC);
					$security_spon = $s_row['security'];
					$email_spon = $s_row['email'];
				if (empty(trim($security_spon))){		
					 $sponsor_err = "Could not find sponsor security level in Users table.";  
				} elseif ($security_spon == 'stu_contrib' || $security_spon == 'TA' || $security_spon == 'grader'){
					 $sponsor_err = "Sponsor must be a contributor or instructor.";  
				} elseif ($security == 'contrib' && $security_spon == 'instruct' ){
					 $sponsor_err = "Sponsor must be a contributor .";  
				}  elseif ($security == 'contrib' && $security_spon == 'contrib' ){
					 $sponsor_err = " For contributors - Sponsor must be an administrator.";  // may delte this later or add more administrators
				} else {

		   $sponsor_id = htmlentities(trim($_POST['sponsor']));
		}
    }
	$allow_clone_default = $_POST['allow_clone_default'];
	$allow_edit_default = $_POST['allow_edit_default'];
	$grade_level = $_POST['grade_level'];
	$default_subject = $_POST['subject'];
	
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($university_err) && 
	empty($email_err) && empty($first_err) && empty($last_err) && empty($security_err) && empty($sponsor_err) && 
	empty($university_err)  && empty($course_name_error)){
        
        // Prepare an insert statement
		
        $sql = "INSERT INTO `Users` (`username`, `password`, `university`, `security`, `email`, first, `last`, `sponsor_id`, `grade_level`, `allow_clone_default`, `allow_edit_default`, `TA_course_1`, `TA_course_2`, `TA_course_3`, `TA_course_4`, `exp_date`)
		VALUES (:username, :password, :university, :security, :email, :first, :last, :sponsor_id, :grade_level, :allow_clone_default, :allow_edit_default, :TA_course_1, :TA_course_2, :TA_course_3, :TA_course_4, :exp_date)";
  
		
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $param_password, PDO::PARAM_STR);
            $stmt->bindParam(':university', $param_university, PDO::PARAM_STR);
            $stmt->bindParam(':security', $param_security, PDO::PARAM_STR);
		    $stmt->bindParam(':email', $param_email, PDO::PARAM_STR);
		    $stmt->bindParam(':first', $param_first, PDO::PARAM_STR);
			$stmt->bindParam(':last', $param_last, PDO::PARAM_STR);
			$stmt->bindParam(':sponsor_id', $param_sponsor_id, PDO::PARAM_STR);
			$stmt->bindParam(':grade_level', $param_grade_level, PDO::PARAM_STR);
			$stmt->bindParam(':allow_clone_default', $param_allow_clone_default, PDO::PARAM_STR);
			$stmt->bindParam(':allow_edit_default', $param_allow_edit_default, PDO::PARAM_STR);
			$stmt->bindParam(':TA_course_1', $param_TA_course_1, PDO::PARAM_STR);
			$stmt->bindParam(':TA_course_2', $param_TA_course_2, PDO::PARAM_STR);
			$stmt->bindParam(':TA_course_3', $param_TA_course_3, PDO::PARAM_STR);
			$stmt->bindParam(':TA_course_4', $param_TA_course_4, PDO::PARAM_STR);
			$stmt->bindParam(':exp_date', $param_exp_date, PDO::PARAM_STR);
			
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_university = $university;
			 $param_security = $security;  
			 $param_email = $email;
		     $param_first = $first; 
		     $param_last = $last;
			 $param_sponsor_id = $sponsor_id;
			 $param_grade_level = $grade_level;
			 $param_allow_clone_default = $allow_clone_default;
			 $param_allow_edit_default = $allow_edit_default;
			 $param_TA_course_1 = $TA_course_1;
			 $param_TA_course_2 = $TA_course_2;
			 $param_TA_course_3 = $TA_course_3;
			 $param_TA_course_4 = $TA_course_4;
			 $param_exp_date = $exp_date;

					 
            // Attempt to execute the prepared statement
            if($stmt->execute()){
				
				
				// now send email
				
				// get the email of the sponsor
				
					$subject = 'QRProblems - Registering a user';
					$body = '<p>QRProblems has recieved a registration request for  '.$first.' '.$last.' at email '.$email.' <p> 
							<p> Welcome to the QRproblems! Please help to keep the educational value of this system intact.';
								
					try {
					//Server settings
					$mail->SMTPDebug = 0;                                       // Enable verbose debug output 0 is off and 4 is everything
					$mail->isSMTP();                                            // Set mailer to use SMTP
					$mail->Host       = 'ns8363.hostgator.com;ns8364.hostgator.com';  // Specify main and backup SMTP servers
					$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
					$mail->Username   = 'wagnerj@excelproblempedia.org';                     // SMTP username
					$mail->Password   = 'Iron26Men&FeMarines';                               // SMTP password
					$mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
					$mail->Port       = 587;                                    // TCP port to connect to try new port if using ssl

					//Recipients
					//$mail -> setFrom($email);
					$mail->setFrom('wagnerj@excelproblempedia.org', 'John');
					$mail->addAddress($email);     // Add a recipient
					$mail->addAddress('wagnerj@trine.edu');               // Name is optional	
					$mail->addAddress('gwagnerj@gmail.com');               // Name is optional	
							
					
					$mail->isHTML(true);  	
					$mail -> Subject = $subject;
					
					$mail->Body    = $body;
					$mail->AltBody = strip_tags($body);			
					
					$mail->send();
					   // echo 'Message has been sent';
					//	$response = "Email is Sent - to system administrator and sponsor";
					//	echo ($response);
					
					//	echo '</br></br>';
						// echo '<a href = "emailForm.php" >back to email form</a>';


					} catch (Exception $e) {
						$_SESSION['failure'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
						// echo '<a href = "emailForm.php" >back to email form</a>';
					}	
				$_SESSION['sucess'] = 'registration sucessful';
				
				$mail2 = new PHPMailer(true);  // trying a new instance to mail a secound email
				// send an email to sponsor and admin
				// get the email of the sponsor
				
					$subject = 'QRProblems - Registering a user - using you as a sponsor - if you do not know them suspend them';
					$body = '<p>QRProblems has recieved a registration request for  '.$first.' '.$last.' at email '.$email.' <p> 
							<p> They are using you as a sponsor.  If you do not know and/or trust this person please log onto QRproblems.org/login and suspend their account ';
								
					try {
					//Server settings
					$mail2->SMTPDebug = 0;                                       // Enable verbose debug output 0 is off and 4 is everything
					$mail2->isSMTP();                                            // Set mailer to use SMTP
					$mail2->Host       = 'ns8363.hostgator.com;ns8364.hostgator.com';  // Specify main and backup SMTP servers
					$mail2->SMTPAuth   = true;                                   // Enable SMTP authentication
					$mail2->Username   = 'wagnerj@excelproblempedia.org';                     // SMTP username
					$mail2->Password   = 'Iron26Men&FeMarines';                               // SMTP password
					$mail2->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
					$mail2->Port       = 587;                                    // TCP port to connect to try new port if using ssl

					//Recipients
					//$mail -> setFrom($email);
					$mail2->setFrom('wagnerj@excelproblempedia.org', 'John');
					$mail2->addAddress($email_spon);     // Add a recipient
					$mail2->addAddress('gwagnerj@gmail.com');               // Name is optional	
					$mail2->addAddress('wagnerj@trine.edu');               // Name is optional			
					
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
				$_SESSION['sucess'] = $_SESSION['sucess'].' Email notification has also been sent to your sponsor  - Please log in';
                header("location: login.php");
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
    <title>Sign Up</title>
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
			
			
			<div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>email you check regularly</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
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

			<div class="form-group <?php  echo (!empty($university_err)) ? 'has-error' : ''; ?>">
                <label>School or Organization</label></br>
				
				<div id ="univ_drop_down">	
				<select name = "university" id = "university">
				<?php
					$sql = 'SELECT `university_name` FROM `University` ';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute();
					while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) 
						{ ?>
						<option value="<?php echo $row['university_name']; ?>"<?php //  if($row['university_name']=='Trine University'){echo 'selected';}?> ><?php echo $row['university_name']; ?> </option>
						<?php
 							}
						?>
				</select>
				</div>
				<div id = "new_univ">
					 <input type="text" name="new_univ" value="<?php echo $new_univ; ?>"> Please Be careful with spelling and capitalization - this will go in the data base
				</div>
				</br> If your School or Organization is not shown -  <span id = "addUniv"><button id = "addUniv_button" name = "addUniv_button"><b>Click Here</b></button></span></br>
					
				
				<span class="help-block"><?php echo $university_err; ?></span>
			 </div>    


			
			
			<div id = "security_block">
			<label> Type of account you would like? </label> </br>
               &nbsp; &nbsp; &nbsp; <input type="radio" name="security" class = "security" value = "contrib" checked >  Contributor - Can contribute and use all problems </br>
               &nbsp; &nbsp; &nbsp; <input type="radio" name="security" class = "security" value = "instruct">  Instructor - Can use all problems </br>
		       &nbsp; &nbsp; &nbsp; <input type="radio" name="security" class = "security" value = "stu_contrib">  Student Contributor - Can contribute problems and also edit/clone those problems specified by any Contributor</br>
		       &nbsp; &nbsp; &nbsp; <input type="radio" name="security" class = "security" value = "TA">  Teaching Assistant - Similar to Instructor but restricted to problems for selected courses of instructor sponsor - also has Grader priviledges</br>
		       &nbsp; &nbsp; &nbsp; <input type="radio" name="security" class = "security" value = "grader">  Grader - Can see the problems and student results of problems activated by Instructor sponsor
           </div>
			</br>
			<div id = "sponsor_block">
			 <label>Sponsor ID</label> </br>
				<input type = "number" name = "sponsor" value = "<?php echo $sponsor_id; ?>" required> ID of person that can vouch for you.
					&nbsp;   To open a listing of ID's in a separate tab: <a href="getiid.php" target = "_blank"><b>Click Here</b></a></font></br>
			<span class="help-block"><?php echo $sponsor_err; ?></span>
			</div>
			</br>
			<div id ="course_checkbox">	
				<label> Select up to three courses that you will be a TA for the sponsoring instructor: </label> </br>
				<?php
					$sql = 'SELECT * FROM `Course` ';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute();
					while ( $row3 = $stmt->fetch(PDO::FETCH_ASSOC)) 
							
						{ ?>
						 &nbsp; &nbsp; &nbsp; <input type = "checkbox" class = "course" value="<?php echo $row3['course_name']; ?>" name = "course_name[]" > <?php echo $row3['course_name']; ?>  </br>
						<?php
 							}  // need to be able to select multiple of make check boxes
						?>
				</div>
			
			</br>
			<div id = "cloning">
			<label> Initial default value for allowing <font color = "green">Cloning</font> of problems you contribute: </label> </br>
               &nbsp; &nbsp; &nbsp; <input type="radio" name="allow_clone_default" value = 1 checked>  Allow Clones</br>
               &nbsp; &nbsp; &nbsp; <input type="radio" name="allow_clone_default" value = 0>  Do Not Allow Clones</br>
           
			</br>
			</div>
			<div id = "editing"> 
			<label> Initial default value for allowing <font color = "green">Editing</font> of problems you contribute: </label> </br>
               &nbsp; &nbsp; &nbsp; <input type="radio" name="allow_edit_default" value = 2 checked>  Allow all to edit</br>
               &nbsp; &nbsp; &nbsp; <input type="radio" name="allow_edit_default" value = 1> Select who can edit</br>
			    &nbsp; &nbsp; &nbsp; <input type="radio" name="allow_edit_default" value = 0>  Only I can edit</br>
            </div> 
			</br>
			<div id = "Grade Level">
			 <label>School Level of problems you are interested in?</label> </br>
			&nbsp; &nbsp; &nbsp; &nbsp; <select name = "grade_level">
				 <option value = '1'> Elementary</option>
				 <option value = '2'> Middle</option>
				 <option value = '3'> High</option>
				 <option selected = "selected" value = '4'> College or Post Graduate</option> 
			</select>
			</div> 
			</br>
			<div id = "subject">
			 <label>Default Quantitative Subject?</label> </br>
			&nbsp; &nbsp; &nbsp; &nbsp; <select name = "subject">
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
			</br>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
			</br>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>

<script>
	var checklimit = 3;
	$("#course_checkbox").hide();
	$("#security_block").on('change',function(){
		var security = $("input[name = security]:checked","#security_block").val();
		// console.log (security);
		if (security != "contrib" ){
			$("#cloning").hide();
			$("#editing").hide();
			
		} else {
			$("#cloning").show();
			$("#editing").show();
		}
		if (security == "TA" ){
			$("#course_checkbox").show();
			// check the number of boxes is less than checklimit
			$(".course").on("change",function(evt){
				if($(this).siblings(":checked").length>= checklimit){
					this.checked=false;
				}
			});
			
			
		} else {
			$("#course_checkbox").hide();
		}
	});
	$("#new_univ").hide();
	
	
	$("#addUniv_button").on('click',function(){
		$("#new_univ").show();
		$("#univ_drop_down").hide();
		$("#university").val("");
		 
	});
	
	// show this list of courses if they select TA
	
	
	 
</script>
</html>