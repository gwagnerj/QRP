<?php
require_once "pdo.php";
session_start();
 

if (isset($_POST['iid'])){
    $iid = $_POST['iid'];
}else if(isset($_GET['iid'])){
    $iid = $_GET['iid'];
} else {
    $_SESSION['error'] = 'iid was lost in writeQuestionBirth';
    header('Location: QRPRepo.php');
    die();
}

//$student_id = 1; //! delete this line after edits

$discipline = 'Chemical Engineering';
    
            $sql = 'SELECT * FROM QuestionWomb   
        WHERE `subject` = :discipline 
        ORDER BY num_reject DESC, num_accept DESC, course DESC, question_use ASC
           ';
       $stmt = $pdo->prepare($sql);
           $stmt->execute(array(':discipline' => $discipline));
           $qw_data = $stmt -> fetchAll();
        //    var_dump($qw_data);


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
    <!-- <button type="button" id = "write_btn" title = "Write a question" class="btn btn-outline-primary btn-lg m-4">Write a Question</button>
    <button type="button" id = "check_status_btn" title = "Check Status of Questions I have Authored" class="btn btn-outline-secondary btn-lg m-4">Check Status of my Questions</button> -->

</div>

    <h1> Quick Response Review and Promote Questions </h1>


        <?php
        if (isset($_GET['success_flag']) && $_GET['success_flag'] != '0'){
            echo '<h2 class = "text-success"> Last Question Successfully Checked and Processed </h2>';
        }
        if (isset($_GET['success_flag']) && $_GET['success_flag'] == '0'){
            echo '<h2 class = "text-danger"> Last Question Was Not Approved - Something Went Wrong</h2>';
        }
        ?>

    <form>
<input type="hidden" id = "iid" value = "<?php echo $iid;?>"></input>
    <table id = "edit_question_tbl" class = "table table-striped mt-5 mx-4">
        <thead>
            <tr>
                 <th> Select </th>
                <th> Course </th>
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

           echo '<tr id = "table_row'.$i.'" class = "table_row">
                    <td >
                   <button type="button" id = "btn_'.$qw_datum["questionwomb_id"].'" class = " select btn btn-outline-primary btn-sm '.$qw_datum["course"].'">select</button>
                    </td>
                    <td >
                    '.$qw_datum["course"].'
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

        let table_row = document.getElementsByClassName('table_row');
        let selections = document.getElementsByClassName('select');
        const iid = document.getElementById('iid').value;

            for (let i=0; i<selections.length; i++) {
                selections[i].addEventListener('click',(e)=>{
                    let questionwomb_id = e.target.id.split('_')[1];
                    let location = 'writeQuestionPromotePreview.php?questionwomb_id='+questionwomb_id+'&iid='+iid;
                    console.log ('location',location);
                    window.location.href = location;
                })
            }

    })
	</script>


</body>
</html>