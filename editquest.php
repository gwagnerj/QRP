<?php
	session_start();
	require_once "pdo.php";
    require_once "simple_html_dom.php";
    require_once 'Encryption.php';
    require_once '../encryption_base.php';
	$_SESSION['success'] = '';


	$sql = 'SELECT MAX(question_id) FROM Question';
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$question_data = $stmt->fetch(PDO::FETCH_ASSOC);
	$max_question_id=$question_data['MAX(question_id)'];



	if (isset($_GET['question_id'])) {
		$question_id=$_GET['question_id'];
	} else if(isset($_POST['question_id'])){
        $question_id = $_POST['question_id'];
    } else
    {
		$question_id = $max_question_id;

		//  $_SESSION['error'] = 'question_id was lost -  please log in again';
		// header('Location: QRPRepo.php');
		// die();
	}
	// echo $question_id;
$sql = "SELECT * FROM Question WHERE question_id = :question_id";
$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
		':question_id' => $question_id
		));
        $question_data = $stmt->fetch(PDO::FETCH_ASSOC);
	if (!$question_data){
			$_SESSION['error'] = 'question_id does not exist';
			header("Location: editquest.php?question_id=".$max_question_id);
		}
		$user_id = $question_data['user_id'];
// get the users_id
		$sql = 'SELECT * FROM Users WHERE users_id = :users_id';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
		':users_id' => $user_id
		));
		$user_row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (    isset($_POST['title'])  ) {

      // Data validation
       
       if ( strlen($_POST['title']) < 1) {
           $_SESSION['error'] = 'Valid title missing';
           header("Location: editquest.php?question_id=".$_POST['question_id']);
           die();
       }


		$enc_key = $enc_key.$question_id*$question_id;

		
		//Get the filename from the docxfile that was uploaded
		
			if($_FILES['docxfile']['name']) {
				$filename=explode(".",$_FILES['docxfile']['name']); // divides the file into its name and extension puts it into an array
					if ($filename[1]=='docx'){ // this is the extension
						$docxfile=addslashes($_FILES['docxfile']['tmp_name']);
						$docxname=addslashes($_FILES['docxfile']['name']);
						$docxfile=file_get_contents($docxfile);
						
		//this code needs work			
						$docxname = $_FILES['docxfile']['name'];
						$tmp_docxname =  $_FILES['docxfile']['tmp_name'];
						$location = "uploads/"; // This is the local file directory name where the files get saved
					}
					
					
					// insert into questions with temporary file names for the docx
					$sql = "UPDATE Question SET  docxfilenm = :docxfilenm 	
					WHERE question_id=:question_id";
							$stmt = $pdo->prepare($sql);
							$stmt->execute(array(':docxfilenm'=> $docxname,	':question_id' => $_POST['question_id']));
					
					// not sure why I need to add stuff to the file names - this is legacy stuff that adds complication
					if (fnmatch("Q*_d_*",$docxname,FNM_CASEFOLD ) ){ // ignore the case when matching
							$newDocxNm = $docxname;
					}
					else if($docxname!==""){
							$newDocxNm = "Q".$question_id."_d_".$docxname;
					} else {
						$newDocxNm = "Q".$question_id."_d_questionStatement.docx";
					}
					
					$sql = "UPDATE Question SET docxfilenm = :newDocxNm WHERE question_id = :pblm_num";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
						':newDocxNm' => $newDocxNm,
						':pblm_num' => $_POST['question_id']));
					
				// now upload docx, input and pdf files
				//	$pathName = 'uploads/'.$tmp_name;
					$pathName = 'uploads/'.$newDocxNm;
					if (move_uploaded_file($_FILES['docxfile']['tmp_name'], $pathName)){
						$_SESSION['success'] = $_SESSION['success'].'DocxFile upload successful';
					}
			}  
			if (isset($_POST['title'])){
				$sql = "UPDATE Question SET title = :title WHERE question_id = :question_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':title' => $_POST['title'],
					':question_id' => $_POST['question_id']));	
			}

			
			if (isset($_POST['grade_level'])){
				$sql = "UPDATE Question SET grade = :grade WHERE question_id = :question_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':grade' => $_POST['grade_level'],
					':question_id' => $_POST['question_id']));	
			}
			
			
			if (isset($_POST['question_type'])){
				$sql = "UPDATE Question SET question_type = :question_type WHERE question_id = :question_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':question_type' => $_POST['question_type'],
					':question_id' => $_POST['question_id']));	
			}
			
			
			if (isset($_POST['question_use'])){
				$sql = "UPDATE Question SET question_use = :question_use WHERE question_id = :question_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':question_use' => $_POST['question_use'],
					':question_id' => $_POST['question_id']));	
			}

				
		// html question statement file
			if($_FILES['htmlfile']['name']) {
				$filename=explode(".",$_FILES['htmlfile']['name']); // divides the file into its name and extension puts it into an array
				$htmlfile=addslashes($_FILES['htmlfile']['tmp_name']);
				$htmlname=addslashes($_FILES['htmlfile']['name']);
				$htmlfile=file_get_contents($htmlfile);
				$htmlname = $_FILES['htmlfile']['name'];
				$tmp_htmlfile =  $_FILES['htmlfile']['tmp_name'];
				$location = "uploads/"; // This is the local file directory name where the files get saved
				
				$sql = "UPDATE Question SET  htmlfilenm = :htmlfilenm 	
							WHERE question_id=:question_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(':htmlfilenm'=> $htmlname,	':question_id' => $_POST['question_id']));
				
				if (fnmatch("q*_ht_*",$htmlname,FNM_CASEFOLD ) ){ // ignore the case when matching
				$newhtmlNm = $htmlname;
				}
				else if($htmlname !== ""){
					$newhtmlNm = "q".$question_id."_ht_".$htmlname;
				} else {
					$newhtmlNm = "q".$question_id."_ht_htmlpblm.html";
				}
				
				$sql = "UPDATE Question SET  htmlfilenm = :htmlfilenm 	
							WHERE question_id=:question_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(':htmlfilenm'=> $newhtmlNm,	':question_id' => $_POST['question_id']));
				
				$pathName = 'uploads/'.$newhtmlNm;
				if (move_uploaded_file($_FILES['htmlfile']['tmp_name'], $pathName)){
					$_SESSION['success'] = $_SESSION['success'].'Html question statement upload successful';
				}
			}		
			
				$count = 0;
				
				//upload the subdirectory of picture files
				
				
				if(isset($_FILES['picfiles']['name'])) {
					
					$sql = " SELECT * FROM Question where question_id = :question_id";
							$stmt = $pdo->prepare($sql);
							$stmt->execute(array(
							':question_id' => $_POST['question_id']));
							$row = $stmt->fetch(PDO::FETCH_ASSOC);
							$htmlfilenm = $row['htmlfilenm'];
					$dirnm = str_replace(".htm","_files",$htmlfilenm);
				//	$regex = '/q[0-9]*_q/';
					 $regex = '/q[0-9]*_ht_q/';
					$preg ='q';
					
					$dirnm = 'uploads/'.preg_replace($regex,$preg,$dirnm);
		//			echo $dirnm;
					//die();
					if(!file_exists($dirnm)){
									mkdir($dirnm);
					}			
					$dirnm = $dirnm."/";
                //    echo'<br>';
                //     echo $dirnm;

					// Count # of uploaded files in array
						$total = count($_FILES['picfiles']['name']);

						// Loop through each file
						for( $i=0 ; $i < $total ; $i++ ) {

						  //Get the temp file path
						  $tmpFilePath = $_FILES['picfiles']['tmp_name'][$i];

						  //Make sure we have a file path
						  if ($tmpFilePath != ""){
							//Setup our new file path
							$newFilePath = $dirnm . $_FILES['picfiles']['name'][$i];
                            // echo '<hr><br>';

							//  echo $newFilePath;
							// echo '<hr>';
							// echo $tmpFilePath;
							// Die(); */

							//Upload the file into the temp dir
							if(move_uploaded_file($tmpFilePath, $newFilePath)) {

							  //Handle other code here

							}
						  }
						}
					
				}	

			
			$problem_id ='';


			$sql = "SELECT * FROM QuestionProblemConnect WHERE question_id = :question_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':question_id' => $question_id));
			$questionproblemconnect_data = $stmt -> fetch();

			   if($questionproblemconnect_data){
				   if (isset($questionproblemconnect_data['problem_id']) && $questionproblemconnect_data['problem_id']> 0 ){
					$problem_id = $questionproblemconnect_data['problem_id'];  // we already have a problem associated with this question
				   }
			   }
	   // have a new problem
			if (isset($_POST['problem_id']) && $_POST['problem_id']!='' && $_POST['problem_id']>0 && $problem_id ==''){  // we posted a problem_id in the form and don't have on in the data base
				$sql = "INSERT INTO QuestionProblemConnect (`question_id`,`problem_id`)
				 VALUES (:question_id,:problem_id) ";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':problem_id' => $_POST['problem_id'],
					':question_id' => $_POST['question_id']));	
			}
		// need to update the Table structure
		if (isset($_POST['problem_id'])&& $problem_id > 0 && $_POST['problem_id'] != $problem_id ){
			$sql = "UPDATE QuestionProblemConnect SET problem_id = :problem_id WHERE question_id = :question_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':problem_id' => $_POST['problem_id'],
				':question_id' => $_POST['question_id']));	


				$problem_id = $_POST['problem_id'];
		}
		

			
			if (isset($_POST['feedback_a'])){
				$sql = "UPDATE Question SET fbtext_a = :fbtext_a WHERE question_id = :question_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_a' => $_POST['feedback_a'],
					':question_id' => $_POST['question_id']));	
			}
			
			
			if (isset($_POST['feedback_b'])){
				$sql = "UPDATE Question SET fbtext_b = :fbtext_b WHERE question_id = :question_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_b' => $_POST['feedback_b'],
					':question_id' => $_POST['question_id']));	
			}
			
			
			if (isset($_POST['feedback_c'])){
				$sql = "UPDATE Question SET fbtext_c = :fbtext_c WHERE question_id = :question_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_c' => $_POST['feedback_c'],
					':question_id' => $_POST['question_id']));	
			}
			
			
			if (isset($_POST['feedback_d'])){
				$sql = "UPDATE Question SET fbtext_d = :fbtext_d WHERE question_id = :question_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_d' => $_POST['feedback_d'],
					':question_id' => $_POST['question_id']));	
			}
			
			
			if (isset($_POST['feedback_e'])){
				$sql = "UPDATE Question SET fbtext_e = :fbtext_e WHERE question_id = :question_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_e' => $_POST['feedback_e'],
					':question_id' => $_POST['question_id']));	
			}
			
			
			if (isset($_POST['feedback_f'])){
				$sql = "UPDATE Question SET fbtext_f = :fbtext_f WHERE question_id = :question_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_f' => $_POST['feedback_f'],
					':question_id' => $_POST['question_id']));	
			}
			
			
			if (isset($_POST['feedback_g'])){
				$sql = "UPDATE Question SET fbtext_g = :fbtext_g WHERE question_id = :question_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_g' => $_POST['feedback_g'],
					':question_id' => $_POST['question_id']));	
			}
			
			
			if (isset($_POST['feedback_h'])){
				$sql = "UPDATE Question SET fbtext_h = :fbtext_h WHERE question_id = :question_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_h' => $_POST['feedback_h'],
					':question_id' => $_POST['question_id']));	
			}
			
			
			if (isset($_POST['feedback_i'])){
				$sql = "UPDATE Question SET fbtext_i = :fbtext_i WHERE question_id = :question_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_i' => $_POST['feedback_i'],
					':question_id' => $_POST['question_id']));	
			}
			
			
			if (isset($_POST['feedback_j'])){
				$sql = "UPDATE Question SET fbtext_j = :fbtext_j WHERE question_id = :question_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fbtext_j' => $_POST['feedback_j'],
					':question_id' => $_POST['question_id']));	
			}
			

			
				$_SESSION['success'] = 'Record updated';
		
	}				
        
  //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------      
           // Using the html file as a template provide markup so that the system can more easily modify it use question_id----------------------------------------------------------------
           // this code came from format_html that uses the varaible question_id  so that is what I am using in this section
      
        $sql = "SELECT * FROM Question WHERE question_id = :question_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':question_id' => $question_id));
        $quest_data = $stmt -> fetch();
      //  echo ('question data  ');
       // var_dump($quest_data['htmlfilenm']);
         if ($quest_data['htmlfilenm']!=null){      
             $htmlfilenm = "uploads/".$quest_data['htmlfilenm'];

              $html = new simple_html_dom();
              
              $html->load_file($htmlfilenm); 
             
			$param2 = $param1 = $param3 = false;          //? initialize some vars
// 			echo "what +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++";
// 			echo '<br>';

// 			echo 'post '. isset($_POST['htmlfile']);
// echo '<br>';
			if (isset($_FILES['htmlfile']) && $_FILES['htmlfile']['size'] != 0){
// echo "what +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++";
               $tags = $html->find('p');
            //    $html = str_replace($html->find('p' , 0),'<div id = "quote">'.$html->find('p' , 0).'</div>',$html);
             $k=1;
                foreach ($tags as $tag){
                    //  if (strpos(trim($tag->plaintext),'q==')!== false) {$html = str_replace($tag->outertext,'</div><div id="question-stem">' . $tag->outertext,$html);}
                    //  if (strpos(trim($tag->plaintext),'==q')!== false) {$html = str_replace($tag->outertext,$tag->outertext."</div>",$html);}       

                    if (strpos(trim($tag->plaintext),'##option')!== false) {        
                        $html = str_replace($tag->outertext,'<div id="option-'.$k.'" class = "options">' . $tag->outertext,$html);
                        $k = $k+1;
                     if (strpos(trim($tag->plaintext),'##')!== false) {$html = str_replace($tag->outertext,$tag->outertext."</div>",$html);}
				}


            }

            $htmlGetVals = str_get_html($html);            //? convert it back to an html document
          //  var_dump($html);

		//	$option1 = $html->find("#option-1");
			$options = $htmlGetVals->find(".options");
          //  var_dump($option1);
          $L=count($options);
            $needle = '<body>';
          $pos = strpos($html,$needle);
          if ($pos !== false) {
              $html = substr_replace($html,'<body><div id = "big-container">', $pos, strlen($needle));
          }
   //         echo $html;

          $alphabet = array('a','b','c','d','e','f','g','h','i','j');
        $btn_color = array("dodgerBlue", "IndianRed", "LightSeaGreen", "RebeccaPurple", "blue", "orange", "teal", "gray", "fuchsia", "lime");
          for($i=0;$i<$L;$i++){
            $opt = $options[$i];
            $letter = $alphabet[$i];
            $opt_plaintext = $opt -> plaintext;
			// echo 'opt_plaintext '.$opt_plaintext;
            $key[$i]= explode(';',$opt_plaintext)[1];
            $text[$i]= explode(';',$opt_plaintext)[2];
            $text[$i] = str_replace("##","",$text[$i]);
            $key_text = 'key_'.$letter;
            $text_key = 'text_'.$letter;
            
            $sql = 'UPDATE Question SET '.$key_text.' = :key,'.$text_key.' =:text WHERE question_id = :question_id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':key' => $key[$i],
                ':text' => $text[$i],
                ':question_id' => $question_id));	


                // get rid of the markup and put in links to take them to the email recieving file
                
               $needle = "##option;";
               $anchor_tag = '<br><br><a id = "select-'.$alphabet[$i].'" class = "select" style ="margin-right:0.5rem;font-size:1.2rem;padding-right:0.5rem; padding-top:0.3rem;padding-bottom:0.3rem; text-decoration: none; padding-left:0.5rem;   text-align: center;font-weight: bold; line-height: 25px; border-radius: 5px; border:3px solid; border-color:  black; background-color:'.$btn_color[$i].';color:white;" href="https://www.qrproblems.org/QRP/mail_quiz_receive.php?response='.$alphabet[$i].'&question_id='.$question_id.'&student_id=1" target="_blank">'.$alphabet[$i].')</a>';
                $pos = strpos($html,$needle);
                if ($pos !== false) {
                    $html = substr_replace($html,$anchor_tag, $pos, strlen($needle));
                }

               $needle =  $key[$i].';';
                $pos = strpos($html,$needle,$pos);
                if ($pos !== false) {
                    $html = substr_replace($html,"", $pos, strlen($needle));
                }

               $needle = "##";
                $pos = strpos($html,$needle,$pos);
                if ($pos !== false) {
                    $html = substr_replace($html,"", $pos, strlen($needle));
                }


          }

            $html2 = str_replace('src="','src="uploads/',$html);
     //      echo($html2);
            $html = str_get_html($html); 
            $html->save($htmlfilenm);

		}   

  //      
            $html = str_get_html($html);
        
	
                    
     }
   echo ($html);
   echo '<hr>';

            //! added three brackets to get it to wrk when commented all the way down 
 //  }
    

        

 	$p = htmlentities($question_data['title']);
 	$df = htmlentities($question_data['docxfilenm']);
 	$hf = htmlentities($question_data['htmlfilenm']);
  

 		$file_pathdocx='uploads/'.$df;
 		$file_pathhtml='uploads/'.$hf;
 	$docxfilenm_strip = substr($df,strpos($df,'_d_')+3);
 	$htmlfilenm_strip = substr($hf,strpos($hf,'_ht_')+4);

	 
	 $sql = "SELECT * FROM QuestionProblemConnect WHERE question_id = :question_id";
	 $stmt = $pdo->prepare($sql);
	 $stmt->execute(array(':question_id' => $question_id));
	 $questionproblemconnect_data = $stmt -> fetch();
		if($questionproblemconnect_data){
			
			$problem_id = $questionproblemconnect_data['problem_id'];
		//	$problem_id = 0;
		}

		$sql = "SELECT * FROM Question WHERE question_id = :question_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':question_id' => $question_id));
        $quest_data = $stmt -> fetch();
		$grade = $quest_data['grade'];
		$question_type = $quest_data['question_type'];
		$question_use = $quest_data['question_use'];
		$fbtext_a = $question_data['fbtext_a'];
		$fbtext_b = $question_data['fbtext_b'];
		$fbtext_c = $question_data['fbtext_c'];
		$fbtext_d = $question_data['fbtext_d'];
		$fbtext_e = $question_data['fbtext_e'];
		$fbtext_f = $question_data['fbtext_f'];
		$fbtext_g = $question_data['fbtext_g'];
		$fbtext_h = $question_data['fbtext_h'];
		$fbtext_i = $question_data['fbtext_i'];
		$fbtext_j = $question_data['fbtext_j'];
		//   echo '$fbtext_b '.$fbtext_b;
		// echo 'question_use: '.$question_use;

	
 	?>
	<!DOCTYPE html>
	<html lang = "en">
	<head>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<meta Charset = "utf-8">
	<title>QRQuestions</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<style>
#interactive_video{margin-left:20px;}




	</style>


	</head>

	<body>
	<header>
	<h2>Quick Response Problems</h2>
	</header>

	<?php	
		
		if ( isset($_SESSION['error']) ) {
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) ) {
			echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
			unset($_SESSION['success']);
		}
	

		if(strlen($df)>2) {
				echo 'Current Document File for this Problem - click to download ';				
				echo "<a href='".$file_pathdocx."' download = '".$docxfilenm_strip."'>".$docxfilenm_strip."</a>";
				echo "<br>";
		}
		if(strlen($hf)>2) {
				echo 'Html File for this Problem - click to open - right click to open in new tab ';
				echo "<a href='".$file_pathhtml."' download = '".$htmlfilenm_strip."'>".$htmlfilenm_strip."</a>";
				echo "<br>";
		}
		


			echo "<hr>";
		
		?>
	<form action = "editQuestionMeta.php" method = "GET"> <input type = "hidden" name = "question_id" value = "<?php echo($question_id)?>"><input type = "submit" value ="Edit Meta Data"></form>
		<input type = "hidden" id = "max-question-id" name = "max-question-id" value = "<?php echo($max_question_id)?>"></input>
	<p><b>Edit Problem Data for Problem <input id = "question-id" type = "number" name = "question_id" min = "1" max = "99999" value = <?php  echo($question_id); ?>></b><button id = "duplicate-question" class = "btn primary-btn" name = "duplicate-question" style = "padding-left: 0.3rem; margin-left:1rem;" >Duplicate Question</button></p> 
	<form action="" method="post" enctype="multipart/form-data">

	<p>Title:
	<input type="text" name="title" value="<?= $p ?>"></p>
	<p>




	
	<p>Html Problem Statement File: <span style = "color:red; vertical-align:super">*</span> <input  type='file' accept='.htm, .html' name='htmlfile'/></p>
	<p> Html Problem Associated Directory Containing Pictures (only if figures are used): <input type="file" name="picfiles[]" id="HTMLPics" multiple="" directory="" webkitdirectory="" mozdirectory="">
	



	<p>Problem Statement File - docx: <input type='file' accept='.docx' name='docxfile'/></p>
	<br>


	<div id = "Grade Level">
			 <label>Level of question?</label> </br>
	
			&nbsp; &nbsp; &nbsp; &nbsp; <select name = "grade_level">
				 <option <?php if (isset($grade)&&$grade==1) {echo "selected";} ?> value = '1'> Elementary</option>
				 <option <?php if (isset($grade)&&$grade==2) {echo "selected";} ?> value = '2'> Middle</option>
				 <option <?php if (isset($grade)&&$grade==3) {echo 'selected = "selected"';} ?> value = '3'> High</option>
				
				 <option <?php if (!isset($grade)||$grade==4) {echo "selected";} ?>  value = '4'> College or Post Graduate</option> 
			</select>
			</div> 
	
<br>
	<div id = "question-type"  title = "Text Based, Single Correct Answer Questions will be used for email hook questions">
			 <label>Question Type?</label> </br>
			
             &nbsp; &nbsp; &nbsp; &nbsp; <input type = "radio" name = "question_type" <?php if (!isset($question_type)||$question_type==1) {echo "checked";} ?> value = '1'> Text based, Single Answer</option><br>
			 &nbsp; &nbsp; &nbsp; &nbsp;	 <input type = "radio" name = "question_type" <?php if (isset($question_type)&&$question_type==2) {echo "checked";} ?> value = '2'> Single Correct - Containing Images or Media </option><br>
			 &nbsp; &nbsp; &nbsp; &nbsp;	 <input type = "radio" name = "question_type"  <?php if (isset($question_type)&&$question_type==3) {echo "checked";} ?> value = '3'>Multiple Correct</option><br>
			</div> 
	
<br>
	<div id = "question-use"  title = "Will determine how the problems are used in the system">
			 <label>Question Usage?</label> </br>
			
             &nbsp; &nbsp; &nbsp; &nbsp; <input type = "radio" name = "question_use" <?php if (!isset($question_use)||$question_use==1) {echo "checked";} ?>  value = '1'> Basic Knowledge - Fact Student Should Know and Retain</option><br>
			 &nbsp; &nbsp; &nbsp; &nbsp;	 <input type = "radio" name = "question_use" <?php if (isset($question_use)&&$question_use==2) {echo "checked";} ?>  value = '2'> Basic Concept Question - Applications of Course Principles - Minimal Calculations</option><br>
			 &nbsp; &nbsp; &nbsp; &nbsp;	 <input type = "radio" name = "question_use" <?php if (isset($question_use)&&$question_use==3) {echo "checked";} ?> value = '3'>More Advanced Concept Question - Minimal Calculations</option><br>
			 &nbsp; &nbsp; &nbsp; &nbsp;	 <input type = "radio" name = "question_use" <?php if (isset($question_use)&&$question_use==4) {echo "checked";} ?> value = '4'>Applications Involving Calculations</option><br>
			</div> 
	
	
            <br>
	<div id = "question-pairing"  >
			 <label>Complementary problem_id (if any)?</label> 
			
            <input type = "number" name = "problem_id" min = "0" max = "99999"  value = <?php if (isset($problem_id)){ echo($problem_id);} ?> ></input>
			</div> 
	




<hr>
<p> Some changes might take a page refresh to see the updates show up on this page </p>
	<input type="hidden" name="question_id" value="<?= $question_id ?>">
	<p><input type="submit" value="Update" id="Update_btn"/>
	<a href="QRPRepo.php">Cancel</a></p>
	<style>#Update_btn{background-color: lightyellow }</style>

    <p> feedback_a: <input title = "feedback text for students that picked this part" type="text" name = "feedback_a" value = "<?php if(isset($fbtext_a)){echo $fbtext_a;}?>" size = "<?php if(isset($fbtext_a)&&strlen($fbtext_a)>30){echo strlen($fbtext_a);} else {echo '30';}?>" ></input> </p>
    <p> feedback_b: <input title = "feedback text for students that picked this part" type="text" name = "feedback_b"  value = "<?php if(isset($fbtext_b)){echo $fbtext_b;}?>" size = "<?php if(isset($fbtext_b)&&strlen($fbtext_b)>30){echo strlen($fbtext_b);} else {echo '30';}?>" ></input> </p>
    <p> feedback_c: <input title = "feedback text for students that picked this part" type="text" name = "feedback_c"  value = "<?php if(isset($fbtext_c)){echo $fbtext_c;}?>" size = "<?php if(isset($fbtext_c)&&strlen($fbtext_c)>30){echo strlen($fbtext_c);} else {echo '30';}?>" ></input> </p>
    <p> feedback_d: <input title = "feedback text for students that picked this part" type="text" name = "feedback_d"  value = "<?php if(isset($fbtext_d)){echo $fbtext_d;}?>" size = "<?php if(isset($fbtext_d)&&strlen($fbtext_d)>30){echo strlen($fbtext_d);} else {echo '30';}?>" ></input> </p>
    <p> feedback_e: <input title = "feedback text for students that picked this part" type="text" name = "feedback_e"  value = "<?php if(isset($fbtext_e)){echo $fbtext_e;}?>" size = "<?php if(isset($fbtext_e)&&strlen($fbtext_e)>30){echo strlen($fbtext_e);} else {echo '30';}?>" ></input> </p>
    <p> feedback_f: <input title = "feedback text for students that picked this part" type="text" name = "feedback_f"  value = "<?php if(isset($fbtext_f)){echo $fbtext_f;}?>" size = "<?php if(isset($fbtext_f)&&strlen($fbtext_f)>30){echo strlen($fbtext_f);} else {echo '30';}?>" ></input> </p>
    <p> feedback_g: <input title = "feedback text for students that picked this part" type="text" name = "feedback_g"  value = "<?php if(isset($fbtext_g)){echo $fbtext_g;}?>" size = "<?php if(isset($fbtext_g)&&strlen($fbtext_g)>30){echo strlen($fbtext_g);} else {echo '30';}?>"></input> </p>
    <p> feedback_h: <input title = "feedback text for students that picked this part" type="text" name = "feedback_h"  value = "<?php if(isset($fbtext_h)){echo $fbtext_h;}?>" size = "<?php if(isset($fbtext_h)&&strlen($fbtext_h)>30){echo strlen($fbtext_h);} else {echo '30';}?>"></input> </p>
    <p> feedback_i: <input title = "feedback text for students that picked this part" type="text" name = "feedback_i"  value = "<?php if(isset($fbtext_i)){echo $fbtext_i;}?>" size = "<?php if(isset($fbtext_i)&&strlen($fbtext_i)>30){echo strlen($fbtext_i);} else {echo '30';}?>" ></input> </p>
    <p> feedback_j: <input title = "feedback text for students that picked this part" type="text" name = "feedback_j"  value = "<?php if(isset($fbtext_j)){echo $fbtext_j;}?>" size = "<?php if(isset($fbtext_j)&&strlen($fbtext_j)>30){echo strlen($fbtext_j);} else {echo '30';}?>"></input> </p>

<!--

	<p><hr></p>
	<p>hint_a file: <input type='file' accept='.html' name='hint_aFile'/></p>
	<p>hint_b file: <input type='file' accept='.html' name='hint_bFile'/></p>
	<p>hint_c file: <input type='file' accept='.html' name='hint_cFile'/></p>
	<p>hint_d file: <input type='file' accept='.html' name='hint_dFile'/></p>
	<p>hint_e file: <input type='file' accept='.html' name='hint_eFile'/></p>
	<p>hint_f file: <input type='file' accept='.html' name='hint_fFile'/></p>
	<p>hint_g file: <input type='file' accept='.html' name='hint_gFile'/></p>
	<p>hint_h file: <input type='file' accept='.html' name='hint_hFile'/></p>
	<p>hint_i file: <input type='file' accept='.html' name='hint_iFile'/></p>
	<p>hint_j file: <input type='file' accept='.html' name='hint_jFile'/></p>
	<p><hr></p>
    -->
	</form>


<script>
	var question_id_element = document.getElementById('question-id');
	var max_question_id = document.getElementById('max-question-id').value;
	console.log ("max_question_id",max_question_id);
// let new_question_btn = document.getElementById('new-question-btn');
question_id_element.addEventListener('change', reset_form,event);

function reset_form(event){
    // console.log("click");
   
  //  let question_id_element = document.getElementById('question-id');
    // console.log("question_id_element",question_id_element.value);
    const question_id = question_id_element.value;
    // reset the form with the new question_id_element
	//if (question_id <= max_question_id){
		const url = "editquest.php?question_id="+question_id;
    	window.location.href = url;
	// } else {
	// 	const url = "editquest.php?question_id="+max_question_id;
    // 	window.location.href = url;

	// }
}
let duplicate_question = document.getElementById('duplicate-question');
duplicate_question.addEventListener('click', duplicate_quest,event);

function duplicate_quest(event){
 //    console.log("click");
   
   // let question_id_element = document.getElementById('question-id');
    // console.log("question_id_element",question_id_element.value);
    const question_id = question_id_element.value;
    //! duplicate the data from the current question
	$.ajax({
					url: 'duplicate_question_info.php',
					method: 'post',
					data: {question_id:question_id},
			
			success: function(new_question_id){
			let url2 = "editquest.php?question_id="+new_question_id;       
			window.location.href = url2;
     }                               
			

		}); 

}

</script>

	
	</body>
	</html>