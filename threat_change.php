<?php
// Include config file
require_once 'pdo.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';// Load Composer's autoloader
require_once "random_compat-2.0.18/lib/random.php"; // needed this for the random_bytes function did not work on the online version

session_start();

$mail = new PHPMailer(true);
// Define variables and initialize with empty values



$username = "";
$threat_err = "";
$security_err = "";
 
 
 
 if (isset( $_SESSION['username'])){
	 $username = $_SESSION['username'];
 } else  {
	$_SESSION['error'] = 'session lost go - log back on or go through php my admin to change threat level';
      header("location: QRPRepo.php"); 
 }
 //check the security level of the user to see if they have admin security
 $sql = 'SELECT * FROM `Users` WHERE username = :username';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(
					':username' => $username
					));
				
					$s_row = $stmt->fetch(PDO::FETCH_ASSOC);
					$security = $s_row['security'];
					$email = $s_row['email'];
					$users_id = $s_row['users_id'];
					$first = $s_row['first'];
					$last = $s_row['last'];
					
 if ($security != 'admin') {
	 $_SESSION['error'] = 'not authorized to change threat level';
	// echo 'security is ';
	// echo $security;
	// die();
	 
      header("location: QRPRepo.php"); 
 }
 
 // get current threat level
 
 
 
 $sql = 'SELECT * FROM `Threat` ORDER BY `threat_id` DESC LIMIT 1';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(
					));
				
					$t_row = $stmt->fetch(PDO::FETCH_ASSOC);
					$old_threat_level = $t_row['threat_level'];
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
  
   
	
	// validate security level
	 if(empty(trim($_POST['threat_level']))){
        $threat_err = "Please enter a valid threat level.";     
    
    } else{
        $threat_level = $_POST['threat_level'];
		if ($threat_level == $old_threat_level){
			$threat_err = 'Threat Level was not Changed by User';	
		}
    }
	
	
    // Check input errors before inserting in database
    if(empty($threat_err)){
        
        // Prepare an insert statement
		
        $sql = "INSERT INTO `Threat` (`threat_level`, `users_id`) VALUES (:threat_level, :users_id)";
  
		
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':threat_level', $param_threat_level, PDO::PARAM_STR);
            $stmt->bindParam(':users_id', $param_users_id, PDO::PARAM_STR);
			
            // Set parameters
            $param_threat_level = $threat_level;
            $param_users_id = $users_id;
			 

					 
            // Attempt to execute the prepared statement
            if($stmt->execute()){
				
				
				// now send email
				
				// get the email of the sponsor
				
					$subject = 'QRProblems - Changing Threat Level';
					$body = '<p>The Threat Level was changed by '.$first.' '.$last.' to Threat Level '.$threat_level.' <p> 		
							';
								
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
					$mail->addAddress('gwagnerj@gmail.com');               // Name is optional	
					$mail->addAddress('wagnerj@trine.edu');               // Name is optional			
					
					$mail->isHTML(true);  	
					$mail -> Subject = $subject;
					$mail -> Body = $body;
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
				
				
				
                // Redirect to REPO page
				$_SESSION['sucess'] = 'Threat Level was Changed. Email notification has been sent';
                header("location: QRPRepo.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
       unset($stmt);
		
		
    } else {
		
		echo ' There is an error in one of the parameters';
		echo '</br>';
		if (!empty($threat_err)){echo $threat_err; echo '</br>';}
	}
   
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Threat Level</title>
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
        <h2>Change Theat Level</h2>
		
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		
			<div id = "threat_block">
			<label> Pick New Threat Level - the Current Level is <?php echo $old_threat_level;?> </label> </br>
               &nbsp; &nbsp; &nbsp; <input type="radio" name="threat_level" class = "threat_level" value = 1 > 1 - Green - all features active </br>
               &nbsp; &nbsp; &nbsp; <input type="radio" name="threat_level" class = "threat_level" value = 2 > 2 - Yellow - No cloning or Editing except by contributor  </br>
		       &nbsp; &nbsp; &nbsp; <input type="radio" name="threat_level" class = "threat_level" value = 3 >  3 - Orange - Accounts of Users registered within the last week are suspended + yellow </br>
		       &nbsp; &nbsp; &nbsp; <input type="radio" name="threat_level" class = "threat_level" value = 4>  4 - Red - Active problems can still be used by students all other functions suspended</br>
           </div>
			</br>
			
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
               
            </div>
			</br>
            <p>Cancel - <a href="QRPRepo.php">Back to Repository Without Changing Threat level</a>.</p>
        </form>
    </div>    
</body>

<script>
	
</script>
</html>