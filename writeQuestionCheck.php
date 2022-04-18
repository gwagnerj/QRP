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
     $qw_sentback_data = $stmt -> fetchAll();

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



            $sql = 'SELECT course FROM QuestionWomb 
        WHERE ( `status` = "started" OR `status` = "reviewed1" OR `status` = "reviewed2") AND user_id = 0 AND num_reject < 2 AND updated_at <= NOW() - INTERVAL 5 MINUTE AND
       :student_id NOT IN (student_id, id_checker1, id_checker2, id_checker3, id_checker4, id_checker5)
          GROUP BY `course`
           ';
       $stmt = $pdo->prepare($sql);
           $stmt->execute(array(':student_id' => $student_id));
           $questionwomb_course_data = $stmt -> fetchAll();


            $sql = 'SELECT * FROM QuestionWomb 
        WHERE ( `status` = "started" OR `status` = "reviewed1" OR `status` = "reviewed2") AND user_id = 0 AND num_reject < 2 AND updated_at <= NOW() - INTERVAL 5 MINUTE AND  
        :student_id NOT IN (student_id, id_checker1, id_checker2, id_checker3, id_checker4, id_checker5)
           ';
       $stmt = $pdo->prepare($sql);
           $stmt->execute(array(':student_id' => $student_id));
           $qw_data = $stmt -> fetchAll();
   


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
    <button type="button" id = "check_status_btn" title = "Check Status of Questions I have Authored" class="btn btn-outline-secondary btn-lg m-4">Check Status of my Questions</button>

</div>

    <h1> Quick Response Review and Edit Questions </h1>

    <h3 class = "text-secondary ms-4 fs-4"> <?php echo $stu_name .' has authored '.$num_auth.' and reviewed '.$num_check. ' for a question contribution score of '.$qw_score; ?> </h3>

        <?php
           if (isset($_GET['success_flag']) && $_GET['success_flag'] == '0'){
            echo '<h2 class = "text-danger"> Last Question Was Not Approved - Something Went Wrong</h2>';
        } elseif (isset($_GET['success_flag']) && $_GET['success_flag'] == '2'){
            echo '<h2 class = "text-danger">  Question was not approved- someone else edited the question while you had it open</h2>';
        } elseif (isset($_GET['success_flag']) && $_GET['success_flag'] == '3'){
            echo '<h2 class = "text-danger">  Last Question Was Not Approved - Input to approval file not correct</h2>';
        } elseif (isset($_GET['success_flag']) && $_GET['success_flag'] == '1') {
            echo '<h2 class = "text-success"> Last Question Successfully Checked and Processed </h2>';
        }
     
        ?>

    <form>
        <input type="hidden" name="student_id" id = "student_id" value="<?php echo ($student_id)?>">
            <?php
                if ($qw_sentback_data) {

                   echo ' <table id = "sent_back"  class = "table table-striped mt-1 mx-4">';

                   // echo 'print out the table of problems that were sent back';
                   ?>
                       <h4 class = "ms-3 mt-4 text-primary"> The following Questions were Sent Back to you from the instructor for correction</h4>

                   <thead>
                       <tr>
                           <th> Select </th>
                           <th> Concept </th>
                           <th> Title </th>
                           <th> Status </th>
                           <th> Message </th>
                           <th> Type </th>
                           <th> Use </th>
                           <th> questionw_id </th>
                       </tr>
                   </thead>
                    <tbody>
                   <?php
                   $question_use_ar = array(1=>'Basic Knowledge',2=> 'Basic Concept',3 =>'More Advanced Concept',4=>'Involving Calculations');
                   $question_type_ar = array(1=>'Single Correct',2=> 'Images- Single Correct',3 =>'Multiple Correct');
                   $i = 0;
                   foreach ($qw_sentback_data as $qwsb_datum){
                      $q_use =  $question_use_ar[$qwsb_datum["question_use"]];
                      $q_type =  $question_type_ar[$qwsb_datum["question_type"]];
           
                      echo '<tr class = "table_row_sb '.$qwsb_datum["course"].'"">
                               <td >
                              <button type="button" id = "btn_'.$qwsb_datum["questionwomb_id"].'" class = " select_sb btn btn-outline-primary btn-sm '.$qwsb_datum["course"].'">select</button>
                               </td>
                               <td >
                               '.$qwsb_datum["primary_concept"].'
                               </td>
                               <td >
                               '.$qwsb_datum["title"].'
                               </td>
                               <td >
                               '.$qwsb_datum["status"].'
                               </td>
                               <td >
                               '.$qwsb_datum["message"].'
                               </td>
                               <td >
                               '.$q_type.'
                               </td>
                               <td >
                               '.$q_use.'
                               </td>
                               <td >
                               '.$qwsb_datum["questionwomb_id"].'
                               </td>
                               
                               </tr>' ;
                     $i++;
                   }
                   ?>
                    </tbody>
               </table>
               <hr style="height:4px;">
               <br>
           <?php



                }
            ?>
    </table>
    <h4 class = "ms-3 mt-4 text-primary"> The following Questions were written by others for your review</h4>

    <div class = "row "  id = "course_container">
            <div class = "form-group" style = "display: inline;">
                <label class = "fs-4 text-primary" for = "course">Course Name:</label>
                <select  id = "course" name = "course"  >
                    <option  selected = "" disabled = "" value = ""> Select Course </option>
                    <?php
                           foreach ($questionwomb_course_data as $qw_course_datum){
                            if(isset($_SESSION['course'])&& $qw_course_datum['course'] == $_SESSION['course']){$sel = 'selected';} else {$sel='';}

                            echo "<option  id='".$qw_course_datum['course']."' ".$sel." value='".$qw_course_datum['course']."'>".$qw_course_datum['course']."</option>";
                        }
                    ?>
                </select>
            </div>
     <div id = "question_in_use_error" class = " hide text-danger fs-2"> Question is currently being used by someone else </div>   
    <table id = "edit_question_tbl" class = "table table-striped mt-3 mx-4">
    <!-- <caption class = "text-primary mt-4">  </caption> -->

        <thead>
            <tr>
                <th> Select </th>
                <th> Concept </th>
                <th> Title </th>
                <th> Status </th>
                <th> Type </th>
                <th> Use </th>
                <th> questionw_id </th>
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

           echo '<tr class = " hide table_row '.$qw_datum["course"].'"">
                    <td >
                   <button type="button" id = "btn_'.$qw_datum["questionwomb_id"].'" class = " select btn btn-outline-primary btn-sm '.$qw_datum["course"].'">select</button>
                    </td>
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
                    '.$q_type.'
                    </td>
                    <td >
                    '.$q_use.'
                    </td>
                    <td >
                    '.$qw_datum["questionwomb_id"].'
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
    let selections_sb = document.getElementsByClassName('select_sb');
    const student_id = document.getElementById('student_id').value;
    const check_status_btn = document.getElementById('check_status_btn');
    var num_looks = 0;
    const question_in_use_error = document.getElementById('question_in_use_error');




    check_status_btn.addEventListener('click', () =>{
	let location = 'writeQuestionStatusCheck.php?student_id='+student_id;
	 window.location.href = location;
})
    write_btn.addEventListener('click', () =>{
	let location = 'writeQuestion.php?student_id='+student_id;
	 window.location.href = location;
})

if (course.val != 'Select Course'){
    let course_val = $("#course").val();
               
               for (let i=0; i < table_row.length; i++){
                   table_row[i].classList.add('hide');
               }
               let row_visible = document.getElementsByClassName(course_val);
               for (let i=0; i<row_visible.length; i++){
                   row_visible[i].classList.remove('hide');
               }

}

    course.addEventListener('change', ()=>{
				
				let course_val = $("#course").val();
               
                for (let i=0; i < table_row.length; i++){
                    table_row[i].classList.add('hide');
                }
                let row_visible = document.getElementsByClassName(course_val);
                for (let i=0; i<row_visible.length; i++){
                    row_visible[i].classList.remove('hide');
                }
			})


        for (let i=0; i<selections.length; i++) {
            selections[i].addEventListener('click',(e)=>{
                let questionwomb_id = e.target.id.split('_')[1];
                //? we are going to update the num_looks in the QuesitonWomb table  so that the update_at field will change and get the num_looks to make sure 
                $.ajax({
					url: 'writeQuestion_num_looks.php',
					method: 'post',
					data: {questionwomb_id:questionwomb_id,student_id:student_id}
				
				}).done(function(num_looks){
					console.log ('num_looks',num_looks);
                    num_looks = parseInt(num_looks);


			
                if (num_looks ==-1){
                    question_in_use_error.classList.remove('hide');
                }
                if(num_looks > 0){
                   let location = 'writeQuestionPreview.php?student_id='+student_id+'&questionwomb_id='+questionwomb_id+'&course='+course.value+'&checker_flag=1&num_looks='+num_looks;
                   console.log ('location',location);
                   window.location.href = location;
                }
              })


            })
        }

        for (let i=0; i<selections_sb.length; i++) {
            selections_sb[i].addEventListener('click',(e)=>{
                let questionwomb_id = e.target.id.split('_')[1];
                let location = 'writeQuestionPreview.php?student_id='+student_id+'&questionwomb_id='+questionwomb_id+'&course='+course.value+'&checker_flag=0';
                console.log ('location',location);
                window.location.href = location;
            })
        }

})
	</script>


</body>
</html>