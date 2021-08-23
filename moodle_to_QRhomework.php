<?php
	require_once "pdo.php";
   $error = '';

    if (isset($_GET['school_email'])){
        $school_email =   $_GET['school_email'];
    } else {
        $error  += " school_email not found ";
    }
    if (isset($_GET['school_id'])){
        $school_id =   $_GET['school_id'];
    } else {
        $error   += " school_id not found ";
    }
    if (isset($_GET['first_name'])){
        $first_name =   $_GET['first_name'];
    } else {
        $error  += " first_name not found ";
    }
    if (isset($_GET['last_name'])){
        $last_name =   $_GET['last_name'];
    } else {
        $error  += " last_name not found";
    }
    if (isset($_GET['university'])){
        $university =   $_GET['university'];
    } else {
        $university =   'Trine University';
    }

    if(strlen($error)>1){

        $error  += " Please contact your instructor or report this error to John Wagner at wagnerj@trine.edu ";
    
        echo '<h3 style ="color: red;">'.$error.'</h3>';
       
        die();
    }


        $sql = 'SELECT student_id FROM Student WHERE `school_email` = :school_email';
        $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':school_email' => $school_email));
         $student_data = $stmt -> fetch();

         if ($student_data){

            $student_id = $student_data['student_id'];
            header("Location: stu_frontpage.php?student_id=$student_id");

         } else {

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
                $student_id = $student_data['student_id'];
                header("Location: stu_frontpage.php?student_id=$student_id");
    
            
         }

?>