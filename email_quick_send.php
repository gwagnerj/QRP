<?php
require_once "pdo.php";
require_once '../email_password.php';
require_once "simple_html_dom.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';// Load Composer's autoloader
// require_once "random_compat-2.0.18/lib/random.php"; // needed this for the random_bytes function did not work on the online version

// this is called by QuestionRepo.php and makes a question active in the QuickQuestionActivity table then if email flag is true emails the question to all of the students
// the email flag is set by email_flag from the calling page




$mail = new PHPMailer(true);
$production_flag = true;
$shuffle_flag = true;
$hours_active = "3";


//! This file will given a question id and current class email them a question
session_start();
 $iid = 1;
 $currentclass_name = "Testing Problems";
 $question_id = 61;
 $currentclass_id = 44;  //?  44 is the testing problems class
 $email_flag = true;
 $discuss_stage ="1";

     $json = file_get_contents("php://input"); // json string

    $object = json_decode($json); // php object
    if ($object){
            $iid = $object->iid;  // pulls the iid value out of the key value 
            $currentclass_name = $object->course;  
            $question_id = $object->question_id;  
            $email_flag = $object->email_flag;  
            $discuss_stage = $object->discuss_stage;  
			 $hours_active = $object->hours_active; 
			 $shuffle_flag = $object->shuffle_flag; 
    }


//? get the info for the current class
        $sql = "SELECT currentclass_id FROM CurrentClass WHERE `name` = :currentclass_name AND iid = :iid";
			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
                ':currentclass_name'	=> $currentclass_name,
                ':iid'	=> $iid,
            ));
			$currentclass_id= $stmt->fetch(PDO::FETCH_COLUMN);

            // echo ($currentclass_id);

			// date_default_timezone_set('America/New_York');
			// $now = date('Y-m-d');

//? get all of the students info in that class

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
					$i++;
				}
//? need to get the 
//? make the question active by putting an entry for all of the students in the QuickQuestionActivity table with a try_number of 0 (the default) and 


//? first quesry to see if an entry already exists for this

foreach ($student_ids as $student_id){

//? first querry to see if an entry already exists for this

		$sql = "SELECT COUNT(*) 
		FROM QuickQuestionActivity 
		WHERE student_id = :student_id AND question_id = :question_id AND currentclass_id = :currentclass_id AND try_number = 0 AND  created_at  >= NOW() - INTERVAL :hours_active  HOUR
		";
					$stmt = $pdo->prepare($sql);	
					$stmt->execute(array(
						':student_id'	=> $student_id,
						':question_id'	=> $question_id,
						':currentclass_id'	=> $currentclass_id,
						':hours_active'	=> $hours_active,

					));
					$previos_entry = $stmt->fetch();
		// echo "previos enrty ".$previos_entry[0];

		if($previos_entry[0]==0){

			$sql = "INSERT INTO QuickQuestionActivity (question_id, questionset_id, currentclass_id, student_id,email_flag,discuss_stage, try_number,expires_at)
			VALUES (:question_id, :questionset_id,:currentclass_id, :student_id,:email_flag,:discuss_stage, :try_number, DATE_ADD(NOW(),INTERVAL :hours_active HOUR)) ";
						$stmt = $pdo->prepare($sql);	
						$stmt->execute(array(
							':question_id'	=> $question_id,
							':questionset_id' => 1000,
							':currentclass_id'	=> $currentclass_id,
							':student_id'	=> $student_id,
							':email_flag'	=> $email_flag,
							':discuss_stage'	=> $discuss_stage,
							':hours_active'	=> $hours_active,
							':try_number'	=> 0,
						));
		} else {
			$sql = "UPDATE QuickQuestionActivity
			SET discuss_stage = :discuss_stage, email_flag = :email_flag, expires_at = DATE_ADD(NOW(),INTERVAL :hours_active HOUR)
		   WHERE currentclass_id = :currentclass_id AND student_id = :student_id AND question_id = :question_id AND try_number = :try_number
	   ";
					  $stmt = $pdo->prepare($sql);	
					  $stmt->execute(array(
						':question_id'	=> $question_id,
						':currentclass_id'	=> $currentclass_id,
						':student_id'	=> $student_id,
						':email_flag'	=> $email_flag,
						':discuss_stage'	=> $discuss_stage,
						':hours_active'	=> $hours_active,
						':try_number'	=> 0,
				  ));


		}
}


		$success['num_emails'] = 0;
		$success['flag'] = true;


			

	$letter = array("a","b","c","d","e","f","g","h","i","j");


	  $sql = "SELECT * FROM Question WHERE question_id = :question_id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
		':question_id' => $question_id
		));
        $question_data = $stmt->fetch(PDO::FETCH_ASSOC);
    //    $htmlfilenm = $question_data['htmlfilenm'];

	   $html_fn = $question_data['htmlfilenm'];
	   if(!strpos($html_fn,'.htm')){
		   $html_fn = $html_fn.'.htm';
	   }
   // echo('$html_fn: '.$html_fn);
	  $html_fp = 'uploads/'.$html_fn;  // the file path to the question
   // echo('$html_fp: '.$html_fp);



	   $html = new simple_html_dom();
	//    $fullpath = 'uploads/'.$htmlfilenm.'.htm';
	//    echo 'fullpath: '.$fullpath;
              
	   $html->load_file($html_fp); 

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


	 //? update the quickquestionactivity table with the key_code and

	 $sql = "UPDATE QuickQuestionActivity
	 		 SET key_code = :key_code
			 WHERE currentclass_id = :currentclass_id AND student_id = :student_id AND question_id = :question_id AND try_number = :try_number
	     ";
						$stmt = $pdo->prepare($sql);	
						$stmt->execute(array(
							':question_id'	=> $question_id,
							':key_code' => $key_code,
							':currentclass_id'	=> $currentclass_id,
							':student_id'	=> $student_ids[$i],
							':try_number'	=> 0,
						));


		if ($email_flag){

	//  var_dump($option_text2);
	//   echo '<br>';
	//   echo '<br>';
	//  echo 'key_code: '.$key_code;

			//? this is where I woule re-arrange the file to randomize the order of the responses but the res-number would stay the same 

		$needle = 'student_id=0';
		$replacement = 'student_id='.$student_ids[$i].'&email_flag=1&questionset_id=0&currentclass_id='.$currentclass_id.'&key_code='.$key_code;
		// $replacement = 'student_id='.$student_ids[$i].'&email_flag=1&questionset_id='.$questionset_id.'&key_code='.$key_code;
//		echo ' replacement = '.$replacement;
//		$replacement_enc='encode='.base64_encode($replacement);  //! put this in at the end after trouble shooting - will have to add the decode statement to question_show
		// $replacement_enc='encode='.urlencode(base64_encode($replacement));
		// echo ' replacement: '.$replacement_enc;
		$html2 = str_replace($needle,$replacement,$html2);        //! comment this out after  after trouble shooting - 
	 	$needle = 'question_id=0';
		 $replacement = 'question_id='.$question_id;
		 $html2 = str_replace($needle,$replacement,$html2);

	$html2 = str_replace('question_show','question_quick_show',$html2);  // this will send it to a different file that does not worry about questionsets

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
				
					$subject = 'QR Retieval Practice';
								
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
                    $mail->DKIM_domain = 'qrproblems.org';
                    $mail->DKIM_private = '../DKIM_private.txt';
                    $mail->DKIM_selector = 'phpmailer';
                    $mail->DKIM_passphrase = '';
                    $mail->DKIM_identity = $mail->From;

					//Recipients
					//$mail -> setFrom($email);
					$mail->setFrom('wagnerj@qrproblems.org', 'John Wagner');
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
                        $success['num_emails'] = 0;
                        $success['flag'] = false;
					}
			} //? end if email_flag condition

			$i++;	
			
			

		}  //? end for each email

				$_SESSION['success'] = 'registration sucessful';
			if ($production_flag){


                $success['num_emails'] = $i;
                $success['flag'] = true;

			}
	
		 echo json_encode($success);
	
 ?>





