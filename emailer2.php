<?php
use PHPMailer\PHPMailer\PHPMailer;// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';// Load Composer's autoloader

// emailer was code directly from phpmailer gethub main page, emailer 2 is a service program that is mostly from 


// These must be at the top of your script, not inside a function




// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);


	if(isset($_POST['name']) && isset($_POST['subject']) && isset($_POST['email'])) {
		$name = $_POST['name'];
		$email = $_POST['email'];
		$subject = $_POST['subject'];
		//$body = 'body of email did I get this one';
		 $body = $_POST['body'];
		
		 
		/*  
		$name = 'John';
		$email = 'gwagnerj@gmail.com';
		$subject = 'Getting closer?';
		
	 */






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
	$mail -> setFrom($email,$name);
   // $mail->setFrom('wagnerj@excelproblempedia.org', 'John');
    $mail->addAddress('wagnerj@trine.edu', 'John');     // Add a recipient
    $mail->addAddress('gwagnerj@gmail.com');               // Name is optional
   /* 
   $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');
 */
    // Attachments
	/* 
    $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
 */
    // Content
	
	//$body = '<p><strong> Hello</strong> this is my first email with PHPMAILEER </p>';
	
    $mail->isHTML(true);  
     // Set email format to HTML
    //$mail->Subject = 'This is a test email';
	$mail -> Subject = $subject;
	$mail -> Body = $body;
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);

/* 
		if ($mail ->send()) {
				// echo "Email Sent";
				$response = "Email is Sent";
				
			} else {
				// echo "Something is Wrong: <br><br>" . $mail-> ErrorInfo;
				$response =  "Something is Wrong: <br><br>" . $mail-> ErrorInfo;
				
			}
		echo ($response);
		 */
	/* 	exit(json_encode(array("response" => $response)));
	} else {
		echo "all input fields not specified";
 */
	




		$mail->send();
	   // echo 'Message has been sent';
		$response = "Email is Sent";
		echo ($response);
		//echo 'wtf';
		echo '</br></br>';
		echo '<a href = "emailForm.php" >back to email form</a>';
//		return;
//		exit(json_encode(array("response" => $response)));
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		echo '<a href = "emailForm.php" >back to email form</a>';
	}
}