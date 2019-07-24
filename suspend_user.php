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



$username = $user_id = "";
$threat_err = $user_id_err = "";

 
 if (isset( $_SESSION['username'])){
	 $username = $_SESSION['username'];
 } else  {
	$_SESSION['error'] = 'session lost go - log back on or go through php my admin to change threat level';
      header("location: QRPRepo.php"); 
 }
 

 
 //Get information for the current user
 $sql = 'SELECT * FROM `Users` WHERE username = :username';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(
					':username' => $username
					));
				
					$s_row = $stmt->fetch(PDO::FETCH_ASSOC);
					$security = $s_row['security'];
					$_SESSION['security'] = $security;
					$email = $s_row['email'];
					$sponsor_id = $s_row['users_id'];
					$_SESSION['sponsor_id'] = $sponsor_id;
					$first = $s_row['first'];
					$last = $s_row['last'];
					
echo ('<table id="table_format" class = "a" border="1" >'."\n");
		
		 echo("<thead>");

		echo("</td><th>");
		echo('users_id');
		echo("</th><th>");
		echo('username');
		echo("</th><th>");
		echo('first');
		 echo("</th><th>");
		
		echo('Last');
		echo("</th><th>");
		echo('email');
		 echo("</th><th>");
		echo('univ');
		echo("</th><th>");
		echo('security');
		echo("</th><th>");
		echo('grade');
		 echo("</th><th>");
		 echo('created_at');
		echo("</th><th>");
		echo('suspended');
		
		echo("</th></tr>\n");
		 echo("</thead>");
		 
		  echo("<tbody>");
 
 // get current the sponsored users
 
 
 
 $sql = 'SELECT * FROM `Users` WHERE `sponsor_id` = :sponsor_id';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(
					':sponsor_id' => $sponsor_id
					));
		
				// $stmt = $pdo->query($stmt);
	while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		
		echo("</td><td>");
		echo(htmlentities($row['users_id']));
		echo("</td><td>");
		echo(htmlentities($row['username']));
		echo("</td><td>");
		echo(htmlentities($row['first']));
		echo("</td><td>");
		echo(htmlentities($row['last']));
		echo("</td><td>");
		echo(htmlentities($row['email']));
		echo("</td><td>");
		echo(htmlentities($row['university']));
		echo("</td><td>");
		echo(htmlentities($row['security']));
		echo("</td><td>");
		echo(htmlentities($row['grade_level']));
		echo("</td><td>");
		echo(htmlentities($row['created_at']));
		echo("</td><td>");
		echo(htmlentities($row['suspended']));
		echo("</td></tr>\n");
	
	}
	echo("</tbody>");
	echo("</table>");
	echo ('</div>');				


 if (isset($_POST['users_id'])){
	
	 $users_id = htmlentities($_POST['users_id']);
	$sql = 'SELECT * FROM `Users` WHERE `users_id`= :users_id' ;
	$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(
					':users_id' => $users_id,
					));
	 $u_row = $stmt->fetch(PDO::FETCH_ASSOC);
	 $suspended = $u_row['suspended'];
	
	 if($suspended ==1 ){
		$suspended = 0;
	 } else {
		$suspended = 1;
	 }
	
	  $sql = "UPDATE `Users` SET `suspended` = :suspended WHERE `users_id` = :users_id AND `sponsor_id` = :sponsor_id";
	    if($stmt = $pdo->prepare($sql)){
          $stmt = $pdo->prepare($sql);
			$stmt->execute(array(
			':suspended' => $suspended,
			':users_id' => $users_id,
			':sponsor_id' => $_SESSION['sponsor_id']
			));
		// go back to QRPRepo
		if ($suspended == 1){$sus_status = 'suspended';} else {$sus_status = 'un-suspended';}
		$_SESSION['success'] = 'The user with users_id '.$users_id.' suspension status is now '.$sus_status;
		header("location: QRPRepo.php"); 
		
		 die();
		}
 }				
/*  
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
 */
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Suspend User</title>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 30%; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Suspend User</h2>
		
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		
		 <div class="form-group <?php echo (!empty($user_id_err)) ? 'has-error' : ''; ?>">
                <label>User_id of the sponsored user you want to Toggle</label>
                <input type="number" name="users_id" class="form-control"  value="<?php echo $user_id; ?>">
                <span class="help-block"><?php echo $user_id_err; ?></span>
            </div>    
		
			</br>
			
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
               
            </div>
			</br>
            <p>Cancel - <a href="QRPRepo.php">Back to Repository Without Suspending User</a>.</p>
        </form>
    </div>    
</body>

<script>
	
</script>
</html>