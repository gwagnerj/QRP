<?php 
 require_once '../email_password.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';// Load Composer's autoloader


$mail = new PHPMailer(true);
// Define variables and initialize with empty values
$username = $password = $confirm_password = $university = "";
$username_err = $email = $email_err = $password_err = $confirm_password_err = $university_err = "";
$first_err = $last_err = $first = $last = $new_univ = $security_err = $sponsor_err = $university_err = $exp_date = "";


		$body="body of email";
				
					$subject = 'QR Retieval Practice';
								
					try {
					//Server settings
					$mail->CharSet = 'UTF-8';
					$mail->SMTPDebug = 2;                                       // Enable verbose debug output 0 is off and 4 is everything
					$mail->isSMTP();                                            // Set mailer to use SMTP
					$mail->Host       = $email_host;  // Specify main and backup SMTP servers
					$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
					$mail->Username   = $email_username;                     // SMTP username
					$mail->Password   =  $email_password;                              // SMTP password
					$mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
					$mail->Port       = 587;                                    // TCP port to connect to try new port if using ssl
                    $mail->DKIM_domain = 'qrproblems.org';
                    $mail->DKIM_private = '../DKIM_private.txt';
                    $mail->DKIM_selector = 'phpmailer';
                    $mail->DKIM_passphrase = '';
                    $mail->DKIM_identity = $mail->From;


					//Recipients
					//$mail -> setFrom($email);
					$mail->setFrom('wagnerj@qrproblems.org', 'John Wagner');
					$mail->addAddress('wagnerj@trine.edu');               // Name is optional	
				    $mail->addAddress('gwagnerj@gmail.com');               // Name is optional	


				//	$mail->addAddress($emaileo);
						
					
					$mail->isHTML(true);  	
					$mail -> Subject = $subject;
					
					$mail->Body    = $body;
					$mail->AltBody = strip_tags($body);
					$mail->send();    //! Put this in the active version


					} catch (Exception $e) {
						$_SESSION['failure'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
						// echo '<a href = "emailForm.php" >back to email form</a>';
					}

				$_SESSION['sucess'] = 'registration sucessful';
      
			 	
		
  
?>
 
