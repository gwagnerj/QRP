<?php
	require_once "pdo.php";
    session_start();
   $error = '';

    if (isset($_GET['school_email'])){
        $school_email =   $_GET['school_email'];
        // check to see if the school email is well formatted
        if (!filter_var($school_email, FILTER_VALIDATE_EMAIL)){
            $error = $error . 'school email address from Moodle was not well formed ';
        }


    } else {

        $error = $error." school_email not found ";
    }
    if (isset($_GET['school_id'])){
        $school_id =   $_GET['school_id'];
    } else {
        $error   = $error." school_id not found ";
    }
    if (isset($_GET['first_name'])){
        $first_name =   $_GET['first_name'];
    } else {
        $error = $error." first_name not found ";
    }
    if (isset($_GET['last_name'])){
        $last_name =   $_GET['last_name'];
    } else {
        $error = $error." last_name not found";
    }
    if (isset($_GET['university']) && strlen($_GET['university']) > 2 ){
        $university =   $_GET['university'];
    } else {
        $university =   'Trine University';
    }

    if(strlen($error)>1){

        $error  = "<h3> If you have reached this page after slecting a URL in Moodle,</h3>
        <h3 style ='color: navy;'>Try hovering over the link in Moodle, right clicking it and selecting -<span style ='color: blue;' > open in new tab </span> - (or similar) from the right click menu.</h3>
         <h3 style ='color: navy;'>If that does not work, you can sign up directly at <a style ='color: blue;' href = 'https://www.qrhomework.org' target='_blank'> qrhomework.org </a> and use your <span style ='color: blue;'> full university email address </span> for your username and <span style ='color: blue;'> student ID number </span> for your password. </h3>
         <h3> If that does not work, please contact your instructor or report this error to John Wagner at wagnerj@trine.edu </h3>
         <h4>Specific error message in file moodle_to_QRhomework.php to follow: ".$error."</h4>";
    
        echo $error;
       
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

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
<title>Moodle Pass through</title>
 
    </style>
</head>
<body>

<form name = "go-on-get" action="stu_frontpage.php" method = "post" id="go-on-get">
            <input type = "hidden" name = "student_id" value = "<?php echo ( $student_id)?>" >
        </form>

<script>
       
        let go_on_get = document.getElementById('go-on-get');
        console.log('go_on_get',go_on_get);
            go_on_get.submit();


</script>
        </body>
</html>