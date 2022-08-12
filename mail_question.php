<?php 
// Include config file
require_once 'pdo.php';
require_once '../email_password.php';
require_once "simple_html_dom.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';// Load Composer's autoloader
require_once "random_compat-2.0.18/lib/random.php"; // needed this for the random_bytes function did not work on the online version

$production_flag = true;
$shuffle_flag = 1;

$mail = new PHPMailer(true);
// Define variables and initialize with empty values
$username = $password = $confirm_password = $university = "";
$username_err = $email = $email_err = $password_err = $confirm_password_err = $university_err = "";
$first_err = $last_err = $first = $last = $new_univ = $security_err = $sponsor_err = $university_err = $exp_date = "";
 
// $first = "John";
// $last = "Wagner";
// $email = "Wagnerj@trine.edu";

if (isset($_GET['cc_id'])){
	$currentclass_id = $_GET['cc_id'];
} else {
	$currentclass_id = 44;  //?  44 is the testing problems class

}
	
// echo ' currentclass_id: '.$currentclass_id;
date_default_timezone_set('America/New_York');
// $timezone = date_default_timezone_get();
// echo "The current server timezone is: " . $timezone;
$now = date('Y-m-d');
// echo ' now: '.$now;

	$question_id = $questionset_id = 0;

   $sql = 'SELECT * FROM QuestionTime
    JOIN QuestionSet ON QuestionTime.questiontime_id = QuestionSet.questiontime_id
	WHERE currentclass_id = :currentclass_id AND QuestionSet.set_day_alias = 0 AND date(set_date) = CURDATE() LIMIT 1';  
//    $sql = 'SELECT * FROM QuestionTime
//     JOIN QuestionSet ON QuestionTime.questiontime_id = QuestionSet.questiontime_id
// 	WHERE currentclass_id = :currentclass_id AND QuestionSet.set_day_alias = 0 ORDER BY QuestionSet.questionset_id DESC';  
//    $sql = 'SELECT * FROM QuestionTime
//     JOIN QuestionSet ON QuestionTime.questiontime_id = QuestionSet.questiontime_id
// 	WHERE currentclass_id = :currentclass_id';  
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':currentclass_id' => $currentclass_id
				));
				$qt_datum = $stmt->fetch(PDO::FETCH_ASSOC);

				if (!$qt_datum){  // guard clause
					echo '<h1> No Question today </h1>';
					die();
				}
			//	var_dump($questionTime_data);
			// var_dump($qt_datum);
	  			$questionset_id = $qt_datum['questionset_id'];
				  $question_id = $qt_datum['question_id'];
				//   echo 'question_id: '.$questionset_id;
				  $set_day_alias = $qt_datum['set_day_alias'];
				$set_date = explode(" ",$qt_datum['set_date'])[0];
			//	 echo ' question_id: '. $question_id .'  questionset_id: '. $questionset_id .' set_date '.$set_date.'<br>';  //! echo statement

			

			
// 				 $questionTime_data = $stmt->fetchALL(PDO::FETCH_ASSOC);
// 			//	var_dump($questionTime_data);
// 		foreach ($questionTime_data as $qt_datum){
// 				$question_id = $qt_datum['question_id'];
// 				$set_day_alias = $qt_datum['set_day_alias'];
// 				$set_date = explode(" ",$qt_datum['set_date'])[0];
// 				// echo ' question_id: '. $question_id .' set_date '.$set_date.'<br>';
// //! final version uncomment the next line and comment the one below it
// 				if ($set_date == $now && $set_day_alias == 0){
// 			//	 if ( $set_day_alias == 0){
// 					$question_id = $qt_datum['question_id'];
// 					$questionset_id = $qt_datum['questionset_id'];
// 				}

// 			}

			//? could put a if set_date = $now then check get the address of all of the students in the current class

	
		//	 if ($question_id !=0 ){

				$sql = 'SELECT * FROM StudentCurrentClassConnect
				JOIN Student ON StudentCurrentClassConnect.student_id = Student.student_id
				WHERE currentclass_id = :currentclass_id';  
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':currentclass_id' => $currentclass_id
					));
				$studentccconnect_data = $stmt->fetchALL(PDO::FETCH_ASSOC);
				$i=0;
				foreach ($studentccconnect_data as $sccc_datum){
					$student_ids[$i] = $sccc_datum['student_id'];
					$emails[$i] = $sccc_datum['school_email'];
					// echo ' student_id: '.$student_ids[$i].' email '.$emails[$i].'<br>';
					$i++;
				}
			

	$letter = array("a","b","c","d","e","f","g","h","i","j");

	// die();

   //    $student_id = 1;
	//   $question_id = 2;

	  $sql = "SELECT * FROM Question WHERE question_id = :question_id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
		':question_id' => $question_id
		));
        $question_data = $stmt->fetch(PDO::FETCH_ASSOC);
       $htmlfilenm = $question_data['htmlfilenm'];
	   $html = new simple_html_dom();
	   $fullpath = 'uploads/'.$htmlfilenm;
	//    echo 'fullpath: '.$fullpath;
              
	   $html->load_file($fullpath); 

//? find out how may options we have for this question
$option_texts = $html->find ('.select');

$num_options = count($option_texts);
//  echo ' num_options: '.$num_options;
// echo ' question_id '.$question_id;

$option_text = array();
$shuffle_keys = array();
$shuffle_keys = range(0,$num_options-1);

// var_dump ($shuffle_keys);
// echo '<br>';
$option_text_temp = array();
for($i = 1; $i <= $num_options; $i++){
	$k = $i-1;
	 $key = '#question_option_'.$letter[$k];
	//  echo 'key: '.$key;
	//  echo "<br>";
	//  $key = '#option_text-'.$i;
	 $option = $html->find($key)[0];
//	 $option = $option[0];
//	var_dump ($option);
	//  $option = $html->find($key)[0];

	  $option_text[$k] = $option ->innertext;
	
	// $option_text_temp[$k] = 'temp___'.$i;
	$option_text_temp[$k] = 'temp___'.$i;
}

// var_dump($option_text);
// echo '<br>';


//var_dump($emails);

$i = 0;
 foreach ($emails as $email)	{
	if ($shuffle_flag ==1){shuffle($shuffle_keys);}


// var_dump($shuffle_keys);


	$html2 = $html;
	// echo 'num_options1  '.$num_options;

	$key_code = "";
	 for($j = 0; $j < $num_options; $j++){
		$key_code =$key_code.$shuffle_keys[$j];
		$option_text2[$j] = $option_text[$shuffle_keys[$j]] ;
		$option_text_temp2[$j] = $option_text_temp[$shuffle_keys[$j]] ; // temp2 is shuffled in the same way
	
	 }
	//  var_dump($option_text2);
	//   echo '<br>';
	//   echo '<br>';
	//  echo 'key_code: '.$key_code;

			//? this is where I woule re-arrange the file to randomize the order of the responses but the res-number would stay the same 
		//	echo ($html2); 
	   




		$needle = 'student_id=0';
		$replacement = 'student_id='.$student_ids[$i].'&email_flag=1&questionset_id='.$questionset_id.'&key_code='.$key_code;
//		echo ' replacement = '.$replacement;
//		$replacement_enc='encode='.base64_encode($replacement);  //! put this in at the end after trouble shooting - will have to add the decode statement to question_show
		// $replacement_enc='encode='.urlencode(base64_encode($replacement));
		// echo ' replacement: '.$replacement_enc;
		$html2 = str_replace($needle,$replacement,$html2);        //! comment this out after  after trouble shooting - 
	 	$needle = 'question_id=0';
		 $replacement = 'question_id='.$question_id;
		 $html2 = str_replace($needle,$replacement,$html2);

	//	$html2 = str_replace($needle,$replacement_enc,$html2);        //! put this in at the end after trouble shooting - will have to add the decode statement to question_show
		// $replacement_enc='encode='.urlencode(base64_encode($replacement));
		
		
		// echo '<br>';
		// var_dump ($option_text);
		// echo '<br>';
		// var_dump ($option_text_temp2);
		// echo '<br>';
		// var_dump ($option_text2);
		// echo '<br>';


		// $html2 = str_replace($option_text,$option_text2,$html2);
// echo 'num_options: '.$num_options;
// echo '<br>';

// 		for($j = 0; $j < $num_options; $j++){
// 			echo 'replace '.$option_text[$j].' with '.$option_text_temp2[$j]; 
// 			echo '<br>';
// 			$html2 = str_replace($option_text[$j],$option_text_temp2[$j],$html2);
// 			echo '<br>';
// 			echo 'html2: '.$html2;
// 			echo '<br>';
// 			echo 'end';
// 			echo '<br>';
	   
// 		 }
	



		$html2 = str_replace($option_text,$option_text_temp2,$html2);
		$html2 = str_replace($option_text_temp2,$option_text2,$html2);

		 $html2 = str_replace('##','',$html2);


		if(!$production_flag){
				$html2 = str_replace('https://www.qrproblems.org/QRP/','',$html2);  //! kill this in the final version this will take it to a local file instead of the server
		}

		for($j = 0; $j < $num_options; $j++){
			$k = $j+1;

			$needle = 'class="option_text">'.$option_text[$j];
			// echo ' needle ' . $needle;
			$replacement = 'class="option_text_temp">'.$option_text2[$j];
			// echo ' replacement ' . $replacement;

			$html2 = str_replace($needle,$replacement,$html2);


		 }

		$body=$html2;
				
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
					$mail->SMTPDebug = 4;                                       // Enable verbose debug output 0 is off and 4 is everything
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
					// $mail->addAddress('wagnerj@trine.edu');               // Name is optional	
					// $mail->addAddress('gwagnerj@gmail.com');               // Name is optional	


				//	$mail->addAddress($emaileo);
						
					
					$mail->isHTML(true);  	
					$mail -> Subject = $subject;
					
					$mail->Body    = $body;
					$mail->AltBody = strip_tags($body);
					
	//				echo 'email'.$email;  //! get rid of this after trouble shooting
			if (!$production_flag){
					echo ($html2);  //! get rid of this in final version	
				}	
				
				if ($production_flag){
					$mail->send();    //! Put this in the active version
				}
				$mail->clearAllRecipients( ); // clear all		
				//	$mail->clearAddresses();
					   // echo 'Message has been sent';
					//	$response = "Email is Sent - to system administrator and sponsor";
					//	echo ($response);
					
					//	echo '</br></br>';
						// echo '<a href = "emailForm.php" >back to email form</a>';


					} catch (Exception $e) {
						$_SESSION['failure'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
						// echo '<a href = "emailForm.php" >back to email form</a>';
					}
			$i++;		
			}

				$_SESSION['sucess'] = 'registration sucessful';
			if ($production_flag){

				echo '<h1> Success - '.$i.' emails sent ';
			}
	//	}			
      
			 	
		
  
?>
 
