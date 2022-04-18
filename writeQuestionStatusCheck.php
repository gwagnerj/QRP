<?php
require_once "pdo.php";
session_start();
 

if (isset($_GET['student_id'])){

    $student_id = $_GET['student_id'];

    
} else {

    $_SESSION['error'] = 'student_id was lost in writeQuestioncatcher';
    header('Location: writeQuestion.php');
    die();
}

//$student_id = 1; //! delete this line after edits

$sql = 'SELECT * FROM Student 
 WHERE Student.student_id = :student_id';
$stmt = $pdo->prepare($sql);
    $stmt->execute(array(':student_id' => $student_id));
    $student_data = $stmt -> fetch();


    $first_name = $student_data['first_name'];
    $last_name = $student_data['last_name'];
    $stu_name = $first_name.' '.$last_name;
    $university = $student_data['university'];
    $email = $student_data['school_email'];
    $user_id = '';


    
            $sql = 'SELECT * FROM QuestionWomb   
        WHERE student_id = :student_id 
        AND  `status` = "sent_back" 
           ';
       $stmt = $pdo->prepare($sql);
           $stmt->execute(array(':student_id' => $student_id));
           $questionwomb_sentback_data = $stmt -> fetchAll();

            $sql = 'SELECT * FROM QuestionWombActivity   
        WHERE student_id = :student_id 
           ';
            $stmt = $pdo->prepare($sql);
           $stmt->execute(array(':student_id' => $student_id));
           $qwa_data = $stmt -> fetchAll();

           $qw_score = $num_auth = $num_check = 0;
           foreach ($qwa_data as $qwa_datum){
                $qw_score += $qwa_datum['score'];
                if ($qwa_datum['activity']=='author'){$num_auth++;}
                if ($qwa_datum['activity']=='reviewed1' || $qwa_datum['activity']=='reviewed2' || $qwa_datum['activity']=='reviewed3'){$num_check++;}
           }

           $sql = 'SELECT * FROM QuestionWomb 
           WHERE student_id = :student_id
              ';
          $stmt = $pdo->prepare($sql);
              $stmt->execute(array(':student_id' => $student_id));
              $qw_data = $stmt -> fetchAll();
      
//    var_dump($qw_data);

   


//     }
    


    //? comming from an edit and need to see if this is the author 1st reviewer or 2nd reviewer
    // $sql = "SELECT * FROM QuestionWomb WHERE questionwomb_id = :questionwomb_id";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute(array(':questionwomb_id' => $questionwomb_id));
    // $questionwomb_data = $stmt -> fetch();

?>
<!DOCTYPE HTML>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRQuestions</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
         <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"> 
         <script src="https://cdn.tiny.cloud/1/85w3ssemz2iqrt9zi0qce5e3emgos9nsyvkfv9bt0loc3twd/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
     <style>
body{ 
    margin-left: 15px;
}
table {
  width: 95% !important;
}
.hide{
    display: none;
}


     </style>

</head>
<body>

<div id = "btn_group">
    <button type="button" id = "write_btn" title = "Write a question" class="btn btn-outline-primary btn-lg m-4">Write a Question</button>
    <button type="button" id = "check_btn" title = "Check Questions Others have Written" class="btn btn-outline-secondary btn-lg ms-4">Check Others Questions</button>

</div>

    <h1> Quick Response Review and Edit Questions </h1>

    <h3 class = "text-secondary ms-4 fs-4"> <?php echo $stu_name .' has authored '.$num_auth.' and reviewed '.$num_check. ' for a question contribution score of '.$qw_score; ?> </h3>

        <?php
        if (isset($_GET['success_flag']) && $_GET['success_flag'] != '0'){
            echo '<h2 class = "text-success"> Last Question Successfully Checked and Processed </h2>';
        }
        if (isset($_GET['success_flag']) && $_GET['success_flag'] == '0'){
            echo '<h2 class = "text-danger"> Last Question Was Not Approved - Something Went Wrong</h2>';
        }
        ?>

    <form>
        <input type="hidden" name="student_id" id = "student_id" value="<?php echo ($student_id)?>">
    <table id = "sent_back">
            <?php
                if ($questionwomb_sentback_data) {
                    echo 'print out the table of problems that were sent back';
                }
            ?>
    </table>

           
    <table id = "edit_question_tbl" class = "table table-striped mt-5 mx-4">
        <thead>
            <tr>
                <th> Concept </th>
                <th> Title </th>
                <th> Status </th>
                <th> # accepts </th>
                <th> # rejects </th>
                <th> Type </th>
                <th> Use </th>
                <th> questionw_id </th>
                <th> Link </th>
            </tr>
        </thead>
         <tbody>
        <?php
        $question_use_ar = array(1=>'Basic Knowledge',2=> 'Basic Concept',3 =>'More Advanced Concept',4=>'Involving Calculations');
        $question_type_ar = array(1=>'Single Correct',2=> 'Images- Single Correct',3 =>'Multiple Correct');
        $i = 0;
        foreach ($qw_data as $qw_datum){
           $q_use =  $question_use_ar[$qw_datum["question_use"]];
           $q_type =  $question_type_ar[$qw_datum["question_type"]];

           echo '<tr >
                    <td >
                    '.$qw_datum["primary_concept"].'
                    </td>
                    <td >
                    '.$qw_datum["title"].'
                    </td>
                    <td >
                    '.$qw_datum["status"].'
                    </td>
                    <td >
                    '.$qw_datum["num_accept"].'
                    </td>
                    <td >
                    '.$qw_datum["num_reject"].'
                    </td>
                    <td >
                    '.$q_type.'
                    </td>
                    <td >
                    '.$q_use.'
                    </td>
                    <td >
                    '.$qw_datum["questionwomb_id"].'
                    </td>
                    <td >
                   <a href="uploads/'.$qw_datum["htmlfilenm"].'.htm" target="_blank">Open</a>
                    </td>
                    
                    </tr>' ;
          $i++;
        }
        
        ?>


         </tbody>




    </table>

    </form>

<script type="text/javascript">
	
$(document).ready(function(){

    const write_btn = document.getElementById('write_btn');
    let course = document.getElementById('course');
    let table_row = document.getElementsByClassName('table_row');
    let selections = document.getElementsByClassName('select');
    const student_id = document.getElementById('student_id').value;
    const check_btn = document.getElementById('check_btn');





    check_btn.addEventListener('click', () =>{
	let location = 'writeQuestionCheck.php?student_id='+student_id;
	 window.location.href = location;
})
    write_btn.addEventListener('click', () =>{
	let location = 'writeQuestion.php?student_id='+student_id;
	 window.location.href = location;
})

})
	</script>


</body>
</html>