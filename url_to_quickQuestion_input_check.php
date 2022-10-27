<?php
	require_once "pdo.php";
    session_start();

//! comes from url_to_quickQuestion.php and goes to question_quick_show if everything is okay otherwise go back to url_to_quickQuestion.php

//! note currentcourse_id changed to currentclass_id

    // testing block 

    // $school_email = 'wagnerj@trine.edu';
    // $first_name = 'John';
    // $last_name = 'Wagner';
    // $school_id = 'jkjkwag';
    // $currentclass_id = '41';
    // $course_short_name = 'CHE--203';
    // $university =   'TrineUniversity';
    // $question_id = '67';


   



     $error = '';

    $school_email = '';
    $first_name = '';
    $last_name = '';
    $school_id = '';
    $course_short_name = '';
    $university =   '';

    if (isset($_POST['school_email'])){
        $school_email = $_POST['school_email'];
    } elseif(isset($_GET['school_email'])){
        $school_email = $_GET['school_email'];
    } elseif (isset($_SESSION['school_email'])){
        $school_email = $_SESSION['school_email'];
    } else {
    $school_email = '';
    $error = $error.' school eamil not set in url to quickQuestion ';
    }
   
    if (isset($_POST['school_id'])){
        $school_id = $_POST['school_id'];
    } elseif(isset($_GET['school_id'])){
        $school_id = $_GET['school_id'];
    } elseif (isset($_SESSION['school_id'])){
        $school_id = $_SESSION['school_id'];
    } else {
    $school_id = '';
    $error = $error.' school_id not set in url to quickQuestion ';
    }
   
    if (isset($_POST['currentclass_id'])){
        $currentclass_id = $_POST['currentclass_id'];
    } elseif(isset($_GET['currentclass_id'])){
        $currentclass_id = $_GET['currentclass_id'];
    } elseif (isset($_SESSION['currentclass_id'])){
        $currentclass_id = $_SESSION['currentclass_id'];
    } else {
    $currentclass_id = '';
    $error = $error.' currentclass_id not set in url to quickQuestion ';
    }
   
   
    if (isset($_POST['question_id'])){
        $question_id = $_POST['question_id'];
    } elseif(isset($_GET['question_id'])){
        $question_id = $_GET['question_id'];
    } elseif (isset($_SESSION['question_id'])){
        $question_id = $_SESSION['question_id'];
    } else {
    $question_id = '';
    $error = $error.' question_id not set in url to quickQuestion ';
    }


    if(strlen($error)>1){
        echo '<h3 style ="color: red;">'.$error.'</h3>';
       $_SESSION['error'] = $error;
       header('Location: url_to_quickQuestion.php?school_id='.$school_id.'&school_email='.$school_email.'&question_id='.$question_id.'&currentcourse_id='.$currentclass_id);
       die();
    }
 
  // $email_flag = 0;

//? Check that student is in the system

        $sql = 'SELECT count(*) FROM Student WHERE `school_email` = :school_email AND `password` = :password';
        $stmt = $pdo->prepare($sql);
         $stmt->execute(array(
            ':school_email' => $school_email,
            ':password' => $school_id,

            ));
         $entry = $stmt -> fetch(PDO::FETCH_NUM);
         $num_entries = $entry[0];
     //    echo 'num_entries: ' . $num_entries;


            if($num_entries==0){
                    $error = ' Student email and/or school student ID not found in Student data table.  Register for QR system';
                    $_SESSION['error'] = $error;
                    header('Location: url_to_quickQuestion.php?school_id='.$school_id.'&school_email='.$school_email.'&question_id='.$question_id.'&currentcourse_id='.$currentclass_id);
                    die();
                }    

            if($num_entries>1){
                    $error = ' Multiple Enties for Student email - School student ID found in Student data table.  Ask instructor to remove an entry.';
                    $_SESSION['error'] = $error;
                    header('Location: url_to_quickQuestion.php');
                    die();
                }  
                
 //? get the student_id for the qr system               

            $sql = 'SELECT student_id FROM Student WHERE `school_email` = :school_email AND `password` = :password';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':school_email' => $school_email,
                ':password' => $school_id,

                ));
            $student_id_ar = $stmt -> fetch(PDO::FETCH_ASSOC);
            $student_id = $student_id_ar['student_id'];

//? check to see that the currentclass is valid for
                $sql = 'SELECT count(*) FROM CurrentClass WHERE currentclass_id = :currentclass_id';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    'currentclass_id' => $currentclass_id,
                    ));
                $entry = $stmt -> fetch(PDO::FETCH_NUM);
                $num_entries = $entry[0];
                if($num_entries==0){
                    $error = ' Current class Number is not valid.  Check the Class Number';
                    $_SESSION['error'] = $error;
                    header('Location: url_to_quickQuestion.php?school_id='.$school_id.'&school_email='.$school_email.'&question_id='.$question_id.'&currentcourse_id='.$currentclass_id);
                    die();
                }    


//? check to see student is in Currentclass
                $sql = 'SELECT count(*) FROM StudentCurrentClassConnect WHERE `student_id` = :student_id AND currentclass_id = :currentclass_id';
                $stmt = $pdo->prepare($sql);
                 $stmt->execute(array(
                    ':student_id' => $student_id,
                    'currentclass_id' => $currentclass_id,
        
                    ));
                 $entry = $stmt -> fetch(PDO::FETCH_NUM);
                 $num_entries = $entry[0];
                 if($num_entries==0){
                    $error = ' Student not registered for the current class - Register for the class';
                    $_SESSION['error'] = $error;
                    header('Location: url_to_quickQuestion.php?school_id='.$school_id.'&school_email='.$school_email.'&question_id='.$question_id.'&currentcourse_id='.$currentclass_id);
                    die();
                 }
     //? check to see that the currentclass is in the QuickQuestionActivity
                    $sql = 'SELECT count(*) FROM QuickQuestionActivity WHERE currentclass_id = :currentclass_id ';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(
                        'currentclass_id' => $currentclass_id,
                        ));
                    $entry = $stmt -> fetch(PDO::FETCH_NUM);
                    $num_entries = $entry[0];
                    if($num_entries==0){
                        $error = ' Class is not in the Quick Question Activity Table.  Check the Class Number';
                        $_SESSION['error'] = $error;
                        header('Location: url_to_quickQuestion.php?school_id='.$school_id.'&school_email='.$school_email.'&question_id='.$question_id.'&currentcourse_id='.$currentclass_id);
                        die();
                    }
     //? check to see that the question is in the QuickQuestionActivity table for this currentclass

                    $sql = 'SELECT count(*) FROM QuickQuestionActivity WHERE currentclass_id = :currentclass_id AND student_id = :student_id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(
                        ':student_id' => $student_id,
                        'currentclass_id' => $currentclass_id,

                        ));
                    $entry = $stmt -> fetch(PDO::FETCH_NUM);
                    $num_entries = $entry[0];
                    if($num_entries==0){
                        $error = ' No question for this student in the quick question activity table';
                        $_SESSION['error'] = $error;
                        header('Location: url_to_quickQuestion.php?school_id='.$school_id.'&school_email='.$school_email.'&question_id='.$question_id.'&currentcourse_id='.$currentclass_id);
                        die();

                    $sql = 'SELECT count(*) FROM QuickQuestionActivity WHERE currentclass_id = :currentclass_id AND student_id = :student_id AND question_id = :question_id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(
                        ':student_id' => $student_id,
                        'currentclass_id' => $currentclass_id,

                        ));
                    $entry = $stmt -> fetch(PDO::FETCH_NUM);
                    $num_entries = $entry[0];
                    if($num_entries==0){
                        $error = ' Student has not been given a question for this current class. Check the Class Number';
                        $_SESSION['error'] = $error;
                        header('Location: url_to_quickQuestion.php?school_id='.$school_id.'&school_email='.$school_email.'&question_id='.$question_id.'&currentcourse_id='.$currentclass_id);
                        die();
                    }
                }
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
<title>URL to quickQuestion</title>
        <link rel="icon" type="image/png" href="McKetta.png" />  
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <style>
        /* #school_id_block{
            display: grid;
        } */
    </style>
</head>
<body>
    
  <div class="container">
    <h1 class = "fs-2 my-3"> QR Quick Question </h1>

    

        <form class = "row g-3" name = "go-on-get" action="question_quick_show.php" method = "post" id="go-on-get">
            <input type = "hidden" name = "student_id" value = "<?php echo ( $student_id)?>" >
            <input type = "hidden" name = "question_id" value = "<?php echo ( $question_id)?>" >
            <input type = "hidden" name = "currentclass_id" value = "<?php echo ( $currentclass_id)?>" >
            <input type = "hidden" name = "school_id" value = "<?php echo ( $school_id)?>" >
            <input type = "hidden" name = "school_email" value = "<?php echo ( $school_email)?>" >

            <button  id = "go-on-get"  class="btn btn-primary btn-lg mt-5">Submit</button>

        </form>
  </div>
<script>

       
        let go_on_get = document.getElementById('go-on-get');
            go_on_get.submit();


</script>
        </body>
</html>