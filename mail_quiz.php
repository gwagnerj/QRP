<?php
// Include config file
require_once 'pdo.php';
require_once '../email_password.php';
require_once "simple_html_dom.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';// Load Composer's autoloader
require_once "random_compat-2.0.18/lib/random.php"; // needed this for the random_bytes function did not work on the online version


$mail = new PHPMailer(true);
// Define variables and initialize with empty values
$username = $password = $confirm_password = $university = "";
$username_err = $email = $email_err = $password_err = $confirm_password_err = $university_err = "";
$first_err = $last_err = $first = $last = $new_univ = $security_err = $sponsor_err = $university_err = $exp_date = "";
 
$first = "John";
$last = "Wagner";
$email = "Wagnerj@trine.edu";
					 
            
      $student_id = 1;
	  $question_id = 2;

	  $sql = "SELECT * FROM Question WHERE question_id = :question_id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
		':question_id' => $question_id
		));
        $question_data = $stmt->fetch(PDO::FETCH_ASSOC);
       $htmlfilenm = $question_data['htmlfilenm'];
	   $html = new simple_html_dom();
	   $fullpath = 'uploads/'.$htmlfilenm;
              
	   $html->load_file($fullpath); 

$needle = 'student_id=1';
$replacement = 'student_id='.$student_id;
$html = str_replace($needle,$replacement,$html);
	   echo ($html);
$body=$html;


				
				// now send email
				
				// get the email of the sponsor
				
					$subject = 'QR Retieval Practice';
					// $body = '<body class="" style="background-color: #f6f6f6;font-family: sans-serif;-webkit-font-smoothing: antialiased;font-size: 14px;line-height: 1.4;margin: 0;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
  
					// <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;background-color: #f6f6f6;">
					//   <tr>
					// 	<td style="font-family: sans-serif;font-size: 14px;vertical-align: top;">&nbsp;</td>
					// 	<td class="container" style="font-family: sans-serif;font-size: 14px;vertical-align: top;display: block;max-width: 580px;padding: 10px;width: 580px;margin: 0 auto !important;">
					// 	  <div class="content" style="box-sizing: border-box;display: block;margin: 0 auto;max-width: 580px;padding: 10px;">
				
					// 		<!-- START CENTERED WHITE CONTAINER -->
					// 		<table role="presentation" class="main" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;background: #ffffff;border-radius: 3px;">
				
					// 		  <!-- START MAIN CONTENT AREA -->
					// 		  <tr>
					// 			<td class="wrapper" style="font-family: sans-serif;font-size: 14px;vertical-align: top;box-sizing: border-box;padding: 20px;">
					// 			  <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;">
					// 				<tr>
					// 				  <td style="font-family: sans-serif;font-size: 14px;vertical-align: top;">
					// 					<p style="font-family: sans-serif;font-size: 14px;font-weight: normal;margin: 0;margin-bottom: 15px;">Question 1</p>
					// 					<p style="font-family: sans-serif;font-size: 14px;font-weight: normal;margin: 0;margin-bottom: 15px;">What type of Quantity is generally given the letter G</p>
					// 					<table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;">
					// 					  <tbody>
					// 						<tr>
					// 						  <td align="left" style="font-family: sans-serif;font-size: 14px;vertical-align: top;">
					// 							<table role="presentation" border="0" cellpadding="0" cellspacing="4em" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;">
					// 							  <tbody>
					// 								<tr>
					// 								  <td id="select-a" class="select btn btn-primary" style="font-family: sans-serif;font-size: 14px;vertical-align: top;box-sizing: border-box;width: 100%;background: white !important;"> <a href="https://www.qrproblems.org/QRP/mail_quiz_receive.php?response=a&question_id=1&student_id=1" target="_blank" style="color: #ffffff;text-decoration: none;background-color: #3498db;border: solid 1px #3498db;border-radius: 5px;box-sizing: border-box;cursor: pointer;display: inline-block;font-size: 14px;font-weight: bold;margin: 0;padding: 12px 25px;text-transform: capitalize;border-color: #3498db;">a) entropy</a> </td>
					// 								</tr>
					// 								<tr>
					// 								  <td id="select-b" class="select btn btn-secondary" style="font-family: sans-serif;font-size: 14px;vertical-align: top;box-sizing: border-box;width: 100%;background: white !important;"> <a href="https://www.qrproblems.org/QRP/mail_quiz_receive.php?response=b&question_id=1&student_id=1" target="_blank" style="color: #ffffff;text-decoration: none;background-color: red;border: solid 1px #3498db;border-radius: 5px;box-sizing: border-box;cursor: pointer;display: inline-block;font-size: 14px;font-weight: bold;margin: 0;padding: 12px 25px;text-transform: capitalize;border-color: red;">b) internal energy</a> </td>
					// 								</tr>
					// 								<tr>
					// 								  <td id="select-a" class="select btn btn-tertiary" style="font-family: sans-serif;font-size: 14px;vertical-align: top;box-sizing: border-box;width: 100%;background: white !important;"> <a href="https://www.qrproblems.org/QRP/mail_quiz_receive.php?response=c&question_id=1&student_id=1" target="_blank" style="color: #ffffff;text-decoration: none;background-color: green;border: solid 1px #3498db;border-radius: 5px;box-sizing: border-box;cursor: pointer;display: inline-block;font-size: 14px;font-weight: bold;margin: 0;padding: 12px 25px;text-transform: capitalize;border-color: green;">c) free energy</a> </td>
					// 								</tr>
					// 								<tr>
					// 								  <td id="select-b" class="select btn btn-quaternary" style="font-family: sans-serif;font-size: 14px;vertical-align: top;box-sizing: border-box;width: 100%;background: white !important;"> <a href="https://www.qrproblems.org/QRP/mail_quiz_receive.php?response=d&question_id=1&student_id=1" target="_blank" style="color: #ffffff;text-decoration: none;background-color: purple;border: solid 1px #3498db;border-radius: 5px;box-sizing: border-box;cursor: pointer;display: inline-block;font-size: 14px;font-weight: bold;margin: 0;padding: 12px 25px;text-transform: capitalize;border-color: purple;">d) enthalpy</a> </td>
					// 								</tr>
					// 			  <!--                  
					// 								<tr >
					// 								  <td id = "select-a" class = "select btn btn-quinary"> <a href="https://www.qrproblems.org/QRP/mail_quiz_receive.php?response=e&question_id=1&student_id=1" target="_blank">e) Helmholts free energy</a> </td>
					// 								</tr>
					// 			   --> 
					// 			   <!--                 
					// 								<tr >
					// 								  <td  id = "select-b" class = "select btn btn-senary"> <a href="https://www.qrproblems.org/QRP/mail_quiz_receive.php?response=f&question_id=1&student_id=1" target="_blank">f) work</a> </td>
					// 								</tr>
					// 					--> 
				
					// 							  </tbody>
					// 							</table>
					// 						  </td>
					// 						</tr>
					// 					  </tbody>
					// 					</table>
					// 					<p style="font-family: sans-serif;font-size: 14px;font-weight: normal;margin: 0;margin-bottom: 15px;"></p>
					// 				  </td>
					// 				</tr>
					// 			  </table>
					// 			</td>
					// 		  </tr>
				
					// 		<!-- END MAIN CONTENT AREA -->
					// 		</table>
				
					// 	  </div>
					// 	</td>
					// 	<td style="font-family: sans-serif;font-size: 14px;vertical-align: top;">&nbsp;</td>
					//   </tr>
					// </table>';
								
					try {
					//Server settings
					$mail->CharSet = 'UTF-8';
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
				
				
      
		
		
  
?>
 
