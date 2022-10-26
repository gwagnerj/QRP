<?php
	require_once "pdo.php";
    require_once "simple_html_dom.php";

    $school_email = 'wagnerj@trine.edu';
    $first_name = 'John';
    $last_name = 'Wagner';
    $school_id = '1024216';
    $course_short_name = 'CHE--203';
    $university =   'TrineUniversity';


    session_start();
   $error = '';
   $email_flag = 0;
   

    // if (isset($_GET['school_email'])){
    //     $school_email =   $_GET['school_email'];
    //     // check to see if the school email is well formatted
    //     if (!filter_var($school_email, FILTER_VALIDATE_EMAIL)){
    //         $error = $error . 'school email address from Moodle was not well formed ';
    //     }


    // } else {

    //     $error = $error." school_email not found ";
    // }
    // if (isset($_GET['school_id'])){
    //     $school_id =   $_GET['school_id'];
    // } else {
    //     $error   = $error." school_id not found ";
    // }
    // if (isset($_GET['first_name'])){
    //     $first_name =   $_GET['first_name'];
    // } else {
    //     $error = $error." first_name not found ";
    // }
    // if (isset($_GET['last_name'])){
    //     $last_name =   $_GET['last_name'];
    // } else {
    //     $error = $error." last_name not found";
    // }
    // if (isset($_GET['university'])){
    //     $university =   $_GET['university'];
    // } else {
    //     $university =   'TrineUniversity';
    // }
    // if (isset($_GET['course_short_name'])){
    //     $course_short_name =   $_GET['course_short_name'];
    //     $course_short_name = substr($course_short_name,0,8);
    // } else {
    //     $course_short_name = '';
    // }


    if(strlen($error)>1){

        $error  = $error." Please contact your instructor or report this error to John Wagner at wagnerj@trine.edu ";
    
        echo '<h3 style ="color: red;">'.$error.'</h3>';
       
        die();
    }


        $sql = 'SELECT student_id FROM Student WHERE `school_email` = :school_email';
        $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':school_email' => $school_email));
         $student_data = $stmt -> fetch();

         if ($student_data){

            $student_id = $student_data['student_id'];
            $_SESSION['student_id'] = $student_id;
      //      header("Location: stu_frontpage.php?student_id=$student_id");  //? the student_id is from the QR system Student table

         } else {

          // checked  school_email  becuase I get an error that username cannot be null from the error log will have to address this if the code checking the correct form does not

            $sql = 'INSERT INTO Student (`username`, `first_name`, `last_name`, `school_email`, `password`)	
            VALUES (:username, :first_name, :last_name, :school_email, :password)';
                $stmt = $pdo->prepare($sql);
                $stmt -> execute(array(
                ':username' => $school_email,
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':school_email' => $school_email,
                ':password' => $school_id,
                ));




                $_SESSION["success"] ='<h1 style ="color: #0d6efd;">Welcome to the QR (Quick Response) System </h1>';
                $_SESSION["success"] = '<h2> You are now registered on the QR system.  If you ever need to log into the system outside of Moodle (at QRHomework.org) Your username is your school email and your password is your school id </h2>';
                $_SESSION["success"] = '<br><h4> You may now close the browser button and log into the system using a link within Moodle </h4>';

                
                $sql = 'SELECT student_id FROM Student WHERE `school_email` = :school_email';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(':school_email' => $school_email));
                $student_data = $stmt -> fetch();

                $student_id = $student_data['student_id'];
                $_SESSION['student_id'] = $student_id;
        //        header("Location: stu_frontpage.php?student_id=$student_id");
    
            
         }

         //? get the currentclass_id looking in the UniversityCourseQRCourseConnect  to get course_id then currentclass table to get the currentclass_id
// first see if we can get the course_id from the course_short_name
         if(strlen($course_short_name)>1){ 
            $sql = "SELECT course_id FROM UniversityCourseQRCourseConnect 
                WHERE universitycourse_name = :course_short_name AND university_name = :university
            ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':course_short_name' => $course_short_name,
                    ':university' => $university
                    ));
                $course_ids = $stmt -> fetch(PDO::FETCH_ASSOC);

                if($course_ids){
                    $sql = "SELECT currentclass_id, count(*) AS count  FROM CurrentClass 
                    WHERE course_id = :course_id AND NOW() < exp_date
                    ORDER BY input_time DESC
                ";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(
                        ':course_id' => $course_ids['course_id'],
                
                       )); 
                       $currentclass_ids = $stmt -> fetch(PDO::FETCH_ASSOC);

                       if ($currentclass_ids && $currentclass_ids['count'] ==1){
                        //? we have found the currentclass_ids 

                        $currentclass_id = $currentclass_ids['currentclass_id'];

                      //?  Now find the question_id 

                      $sql = "SELECT * FROM QuickQuestionActivity 
                        WHERE currentclass_id = :currentclass_id AND student_id = :student_id
                        ORDER BY updated_at DESC LIMIT 1
                        ";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(array(
                            ':currentclass_id' => $currentclass_id,
                            ':student_id' => $student_id,
                            )); 
                            $quickquestion_data = $stmt -> fetch(PDO::FETCH_ASSOC);

                    if ($quickquestion_data){

                        $question_id = $quickquestion_data['question_id'];
                        $questionset_id = $quickquestion_data['questionset_id'];
                        $try_number = $quickquestion_data['try_number'];
                        
                    }

                    $key_code = getKeyCode($question_id,$pdo);

                  //  echo 'key code: '.$key_code;



                  }

    
            }
          }
            
         
function getKeyCode($question_id,$pdo){
    require_once "simple_html_dom.php";
    $letter = array("a","b","c","d","e","f","g","h","i","j");
    $shuffle_flag = 1;

    $sql = "SELECT * FROM Question WHERE question_id = :question_id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
      ':question_id' => $question_id
      ));
      $question_data = $stmt->fetch(PDO::FETCH_ASSOC);
     $htmlfilenm = $question_data['htmlfilenm'];
     $html = new simple_html_dom();
     $fullpath = 'uploads/'.$htmlfilenm.'.htm';
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


  if ($shuffle_flag ==1){shuffle($shuffle_keys);}


// var_dump($shuffle_keys);

  $key_code = "";
   for($j = 0; $j < $num_options; $j++){
      $key_code =$key_code.$shuffle_keys[$j];
  
   }

return $key_code;

}


?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
<title>Moodle Pass through</title>
 
    </style>
</head>
<body>

<form name = "go-on-get" action="question_quick_show.php" method = "post" id="go-on-get">
            <input type = "hidden" name = "student_id" value = "<?php echo ( $student_id)?>" >
            <input type = "hidden" name = "question_id" value = "<?php echo ( $question_id)?>" >
            <input type = "hidden" name = "currentclass_id" value = "<?php echo ( $currentclass_id)?>" >
            <input type = "hidden" name = "key_code" value = "<?php echo ( $key_code)?>" >
        </form>

<script>
       
        let go_on_get = document.getElementById('go-on-get');
        console.log('go_on_get',go_on_get);
            go_on_get.submit();


</script>
        </body>
</html>