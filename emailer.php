<?php
// directly from phpmailer gethub main page
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

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
    $mail->setFrom('wagnerj@excelproblempedia.org', 'John');
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
	
	$body = '<p><strong> Hello</strong> this is my first email with PHPMAILEER </p>';
	
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'This is a test email';
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}