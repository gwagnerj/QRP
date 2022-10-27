<?php
    session_start();

    // testing block 

    // $school_email = 'wagnerj@trine.edu';
    // $first_name = 'John';
    // $last_name = 'Wagner';
    // $school_id = '1024216';
    // $currentclass_id = '';
    // $course_short_name = 'CHE--203';
    // $university =   'TrineUniversity';
    // $question_id = '';


   



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
    }  else {
    $school_id = '';
    }
   
    if (isset($_POST['currentcourse_id'])){
        $currentcourse_id = $_POST['currentcourse_id'];
    } elseif(isset($_GET['currentcourse_id'])){
        $currentcourse_id = $_GET['currentcourse_id'];
    } elseif (isset($_SESSION['currentcourse_id'])){
        $currentcourse_id = $_SESSION['currentcourse_id'];
    } else {
    $currentcourse_id = '';
    }
   
   
    if (isset($_POST['question_id'])){
        $question_id = $_POST['question_id'];
    } elseif(isset($_GET['question_id'])){
        $question_id = $_GET['question_id'];
    } elseif (isset($_SESSION['question_id'])){
        $question_id = $_SESSION['question_id'];
    } else {
    $question_id = '';
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
    <h1 class = "fs-1 my-5"> QR Quick Question </h1>
        <div class = "text-danger fs-4 mb-4">
            <?php
            
            if (isset($_SESSION['error'])){
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            } ?>
         </div>

        
    

        <form class = "row g-3" name = "go-on-get" action="url_to_quickQuestion_input_check.php" method = "post" id="go-on-get">
            <div class="col-md-6">
                <label for="school_email" class="form-label fs-3">School email</label>
                <input type="email" name = "school_email" class="form-control" id="school_email"  pattern ="(\w\.?)+@[\w\.-]+\.edu" required  value = "<?php if(strlen($school_email)){echo $school_email;}?>">
                <div id="school_email_help" class="form-text">school email having an edu domain.</div>

            </div>

            <div class="col-md-6" id = "school_id_block">
                <label for="school_id" class="form-label fs-3">Student School ID Number</label>
                <div class="input-group">
                    <input type="password" maxlength = "12" name = "school_id" class=" " id="school_id" required  autocomplete="off"  value = "<?php if(strlen($school_id)){echo $school_id;}?>">
                    <i class="bi bi-eye-slash  ms-1 mt-1 " style="font-size: 1.3rem;" id="toggle_password"></i>
                 </div>
            </div>

            <div class="col-6 mt-5">
                <label for="question_id" class="form-label fs-3 text-primary">Question Number</label>
                <input type="number" name = "question_id" min = "1" max = "999" class=" text-primary fs-5" id="question_id" required  value = "<?php if(strlen($question_id)){echo $question_id;}?>">
                <div id="question_id_help" class="form-text text-primary">This number will be on the projected problem.</div>

            </div>


            <div class="col-6 mt-5 ">
                <label for="currentclass_id" class="form-label fs-3 text-success ">Class Number</label>
                <input type="number" name = "currentclass_id" min = "1" max = "999" class="text-success  fs-5" id="currentclass_id" required  value = "<?php if(strlen($currentcourse_id)){echo $currentcourse_id;}?>">
                <div id="class_id_help" class="form-text text-success">This number will be on the projected problem.</div>
            </div>

            <button type="submit"  id = "go-on-get"  class="btn btn-primary btn-lg mt-5">Submit</button>

        </form>
  </div>
<script>

    const school_id = document.getElementById('school_id');
    const toggle_password = document.getElementById('toggle_password');
    toggle_password.addEventListener('click',()=>{
            const type = school_id.getAttribute('type') === 'password' ?
                'text' : 'password';
                  
                school_id.setAttribute('type', type);
  
            // Toggle the eye and bi-eye icon
            toggle_password.classList.toggle('bi-eye');

    })
       
        // let go_on_get = document.getElementById('go-on-get');
        // console.log('go_on_get',go_on_get);
        //     go_on_get.submit();


</script>
        </body>
</html>