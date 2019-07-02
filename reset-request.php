<?php
require_once 'pdo.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';// Load Composer's autoloader
require_once "random_compat-2.0.18/lib/random.php"; // needed this for the random_bytes function did not work on the online version
session_start();
// this all comes from a tutorial by mmtuts at https://www.youtube.com/watch?v=wUkKCMEYj9M
$mail = new PHPMailer(true);
if (isset($_POST["reset-request-submit"])){
	
	// Create tokens
	$selector = bin2hex(random_bytes(8));
	$token = random_bytes(32);

	$url = "www.qrproblems.org/QRP/create-new-password.php?selector=".$selector."&validator=".bin2hex($token);
	/* 
	$token_exp = new DateTime('NOW');
	$token_exp->add(new DateInterval('PT01H')); // 1 hour
	 */
	
	$token_exp = date('U')+1800;
	// Get email from database
	
	
	// delete any previous tokens 
	$sql = 'DELETE FROM Pswdreset WHERE email=:email';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(
			':email' => $email
			));
	$hashed_token = password_hash($token,PASSWORD_DEFAULT);		
	$sql = "INSERT INTO `Pswdreset` (`email`,`selector`,`token`, `token_exp`) VALUES (:email, :selector, :token, :token_exp)";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(
			':email' => $email,
			':selector' => $selector,
			':token' => $hashed_token,
			':token_exp' => $token_exp,
			));

// now send email
	$subject = 'QRProblems - Change your Password';
	$body = '<p>QRProblems has recieved a password change request<p> 
			<p> Your password change link is:</br> <a href ="'.$url.'">'.$url.'</a></p>';
				
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
	
	$mail->isHTML(true);  	
	$mail -> Subject = $subject;
	$mail -> Body = $body;
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);			
	
	$mail->send();
	   // echo 'Message has been sent';
		$response = "Email is Sent - Please check your email for a link to reset your password";
		echo ($response);
	
		echo '</br></br>';
		// echo '<a href = "emailForm.php" >back to email form</a>';


	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		// echo '<a href = "emailForm.php" >back to email form</a>';
	}	
				
				
				
				
				
				/* 	
					$sql = "UPDATE Problem SET docxfilenm = :newDocxNm WHERE problem_id = :pblm_num";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
						':newDocxNm' => $newDocxNm,
						':pblm_num' => $_POST['problem_id']));
						 */
/* 
		// sprintf outputs an formatted string brother to printf also http_build_query is used to generate URL encoding string form an associated or indexed array
	$url = sprintf('%sreset.php?%s', ABS_URL, http_build_query([  
		'selector' => $selector,
		'validator' => bin2hex($token)
	]));
 */
	// Token expiration




} else {
	header ('Location: login.php');
}
/* 
// Delete any existing tokens for this user
$this->db->delete('password_reset', 'email', $user->email);

// Insert reset token into database
$insert = $this->db->insert('password_reset', 
    array(
        'email'     =>  $user->email,
        'selector'  =>  $selector, 
        'token'     =>  hash('sha256', $token),
        'expires'   =>  $expires->format('U'),
    )
);

 */






?>
