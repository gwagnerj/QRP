<?php
require_once 'pdo.php';
require_once '../email_password.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';// Load Composer's autoloader
session_start();



$mail = new PHPMailer(true);
if (isset($_POST["reset-request-submit"])){
	// See if email they input is in system
	$school_email = $_POST['email'];
	// see if this email is in the Users table
	$sql = 'SELECT * FROM `Student` WHERE school_email = :school_email';
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
			':school_email' => $school_email
			));
			
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($row == false){
				$_SESSION['error'] = 'email address that was input does not match school email address in system';
				header("Location: stu_pswdRecovForm.php");
				exit();	
			} 
			$username = $row['username'];
            $password = $row['password'];
            $email2 = $row['email2'];
	
	
	

// now send email
	$subject = 'QRsystem - Password Information';
	$body = '<p>The Quick Response System has recieved a password recovery request for username: '.$username.' at email: '.$school_email.' </p> 
			<p> Your password is: '.$password.' </p> <p> <b> Please, delete this email. </b> </p>';
				
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
    $mail->addAddress($email2);     // Add a recipient
    $mail->addAddress('gwagnerj@gmail.com');               // Name is optional	
	$mail->addAddress('wagnerj@trine.edu');               // Name is optional			
	
	$mail->isHTML(true);  	
	$mail -> Subject = $subject;
	$mail -> Body = $body;
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);			
	
	$mail->send();
 
    
    
	   // echo 'Message has been sent';
		$response = "Email is Sent - Please check your email for log on information";
		$_SESSION['success'] = $response.'<br><br>';
	
		
		// echo '<a href = "emailForm.php" >back to email form</a>';


	} catch (Exception $e) {
		$_SESSION['error']  = $_SESSION['error']." If you keep having problems contact the system administrator at wagnerj@trine.edu - Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		// echo '<a href = "emailForm.php" >back to email form</a>';
	}	
				

}
	
    header ('Location: QRhomework.php');
   die();
 
    
?>
