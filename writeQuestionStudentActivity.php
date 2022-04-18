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

$question_use_ar = array(1=>'Basic Knowledge',2=> 'Basic Concept',3 =>'More Advanced Concept',4=>'Involving Calculations');
$question_type_ar = array(1=>'Single Correct',2=> 'Images- Single Correct',3 =>'Multiple Correct');


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

    <h1> Quick Response Review Student Activity by Class </h1>

	<div class="goback ms-4 my-4"><a  href="QRPRepo.php">Finished / Cancel - go back to Repository</a></div>
  
        <?php
        if (isset($_GET['success_flag']) && $_GET['success_flag'] != '0'){
            echo '<h2 class = "text-success"> Last Question Successfully Checked and Processed </h2>';
        }
        if (isset($_GET['success_flag']) && $_GET['success_flag'] == '0'){
            echo '<h2 class = "text-danger"> Last Question Was Not Approved - Something Went Wrong</h2>';
        }
        ?>

    <form>
        <h3 class = "text-primary">Set Dates First</h3>
    From: <input type = "date" class = "my-2" name ="open_window_d" id = "open_window_d" value="<?php $time_start_default = DATETime::createFromFormat('Y-m-d',date('Y-m-d')); $time_start_default -> modify('-7 day'); date_default_timezone_set('America/Indiana/Indianapolis'); echo $time_start_default->format('Y-m-d');  ?>"  ></input>&nbsp;
       To: <input type = "date" class = "my-2" name ="close_window_d" id = "close_window_d" value="<?php $time_now = date('Y-m-d'); echo (string)$time_now; ?>"  ></input>&nbsp;

    <div id ="current_class_dd">	
				Course: &nbsp;
				<select name = "currentclass_id" id = "currentclass_id_dd">
				 <option value = "" selected disabled hidden > Select Course  </option> 
				<?php
    $sql = 'SELECT * FROM `CurrentClass` WHERE `iid` = :iid';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':iid' => $iid]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
						<option value="<?php echo $row['currentclass_id']; ?>" ><?php echo $row[
    'name'
]; ?> </option>
						<?php }
    ?>
                    
                    
				</select>
		</div>

        <span class = "fs-3 mx-2">Student Role:</span>
        <select name = "role_dd" id = "role_dd" class = "select_what_to_see my-2 fs-3">
        
        <option value = "" selected disabled hidden> Select </option>
        <option value = "author">Author</option>
        <option value = "reviewer">Reviewer</option>
        <option value = "all">All</option>


        </select>

<input type="hidden" id = "iid" value = "<?php echo $iid;?>"></input>
<h2 class = "mt-3">Questions Needing Approval </h2>
    <table id = "edit_question_tbl" class = "table table-striped mt-1 mx-4">
       
        <thead>
            <tr>
                 <th> Select </th>
                <th> Name </th>
                <th> Activity </th>
                <th> Score </th>
                <th> Title </th>
                <th> Concept </th>
                <th> Accepts </th>
                <th> Reject </th>
                <th> Status </th>
                <th> Type </th>
                <th> Use </th>
                <th> questionw_id </th>
            </tr>
        </thead>
         <tbody id = "mainTable" class = "table">>

       
        


         </tbody>




    </table>

<h2 class = "mt-3">All Activities </h2>
    <table id = "all_activity_tble" class = "table table-striped mt-1 mx-4">
       
        <thead>
            <tr>
               
                <th>  Name </th>
                <th> Activity </th>
                <th> Score </th>
                <th> Kill Justification </th>
                <th> questionw_id </th>
                <th> question_id </th>
            </tr>
        </thead>
         <tbody id = "allActivityTable" class = "table">>

         </tbody>




    </table>

    </form>

<script type="text/javascript">
	
    $(document).ready(function(){

        const role_dd = document.getElementById('role_dd');
        const currentclass_id = document.getElementById('currentclass_id');
       const mainTable = document.getElementById('mainTable');
       const allActivityTable = document.getElementById('allActivityTable');
        const q_use_ar = {1:'Basic Knowledge',2:'Basic Concept',3:'More Advanced Concept',4:'Involving Calculations'}
        const q_type_ar = {1:'Single Correct',2:'Images- Single Correct',3:'Multiple Correct'}
// $question_type_ar = array(1=>'Single Correct',2=> 'Images- Single Correct',3 =>'Multiple Correct');


     role_dd.addEventListener("change",()=>{
            const role = role_dd.value;
            const currentclass_id = currentclass_id_dd.value
            const open_window_d = document.getElementById('open_window_d').value;
            const close_window_d = document.getElementById('close_window_d').value;

    //?         do some error checking here to make sure we have a course selected for

            mainTable.innerHTML ='';
            allActivityTable.innerHTML = '';
            // console.log ("currentclass",currentclass_id);
            // console.log("open_window_d",open_window_d)
            // console.log("close_window_d",close_window_d)
            $.ajax({
                url: 'getstudentquestionactivity.php',
                method: 'post',
                data: {currentclass_id:currentclass_id,open_window_d:open_window_d,close_window_d:close_window_d,role:role}
		
            }).done(function(message){
                let data = JSON.parse(message)
                let tot_score = 0;
                let new_student = true;
                for (let i = 0; i < data.length; i++){
                    let j = i;
                    if (j>0) {j = i-1;}
                    let student_id = data[i]['student_id'] ;
                    let student_name = data[i]['first_name']+' '+data[i]['last_name'];
                    if (i==0){
                        tot_score = parseInt(data[i]['score']);
                    }
                    if (data[i]['student_id']==data[j]['student_id'] && i != 0){
                        new_student = false;
                         student_name  = ''; 
                         tot_score = tot_score + parseInt(data[i]['score']) ;
                         k=0;
                    } else if (i != 0){
                        mainTable.innerHTML += `<tr> <td >Total for </td> <td class = "text-primary"> ${data[j]['first_name']} = ${tot_score} </td></tr>`
                        tot_score =  parseInt(data[i]['score']);
                        new_student = true; 
                    } 

                   mainTable.innerHTML += `<tr> <td> <button type = "button" id = "btn_${data[i]['questionwomb_id']}" class = "btn btn-outline-primary btn-sm select">Select</button></td><td> ${student_name}</td> <td> ${data[i]['activity']}</td><td> ${data[i]['score']}</td><td> ${data[i]['title']}</td> <td> ${data[i]['primary_concept']}</td><td> ${data[i]['num_accept']}</td><td> ${data[i]['num_reject']}</td><td> ${data[i]['status']}</td><td> ${q_type_ar[data[i]['question_type']]}</td><td> ${q_use_ar[data[i]['question_use']]}</td><td> ${data[i]['questionwomb_id']}</td>
                   </tr>`;
                }
                let table_row = document.getElementsByClassName('table_row');
                let selections = document.getElementsByClassName('select');
                const iid = document.getElementById('iid').value;

            for (let i=0; i<selections.length; i++) {
                selections[i].addEventListener('click',(e)=>{
                    let questionwomb_id = e.target.id.split('_')[1];
                    let location = 'writeQuestionPromotePreview.php?questionwomb_id='+questionwomb_id+'&iid='+iid;
                    console.log ('location',location);
                    window.open(location,'_blank');
                    // window.location.href = location;
                })
            }
        })
            $.ajax({
                url: 'getstudentquestionactivity_all.php',
                method: 'post',
                data: {currentclass_id:currentclass_id,open_window_d:open_window_d,close_window_d:close_window_d}
		
            }).done(function(message){
                // console.log ("message",message);
                let data = JSON.parse(message)
                let tot_score = 0;
                let new_student = true;
                for (let i = 0; i < data.length; i++){
                    let j = i;
                    if (j>0) {j = i-1;}
                    let student_id = data[i]['student_id'] ;
                    let student_name = data[i]['first_name']+' '+data[i]['last_name'];
                    if (i==0){
                        tot_score = parseInt(data[i]['score']);
                    }
                    if (data[i]['student_id']==data[j]['student_id'] && i != 0){
                        new_student = false;
                         student_name  = ''; 
                         tot_score = tot_score + parseInt(data[i]['score']) ;
                         k=0;
                    } else if (i != 0){
                        allActivityTable.innerHTML += `<tr> <td >Total for </td> <td class = "text-primary"> ${data[j]['first_name']} = ${tot_score} </td></tr>`
                        tot_score =  parseInt(data[i]['score']);
                        new_student = true; 
                    } 

                    allActivityTable.innerHTML += `<tr> <td> ${student_name}</td> <td> ${data[i]['activity']}</td><td> ${data[i]['score']}</td><td> ${data[i]['kill_justification']}</td> <td> ${data[i]['questionwomb_id']}</td><td> ${data[i]['question_id']}</td>
                   </tr>`;
                }
                let table_row = document.getElementsByClassName('table_row');
                let selections = document.getElementsByClassName('select');
                const iid = document.getElementById('iid').value;

            for (let i=0; i<selections.length; i++) {
                selections[i].addEventListener('click',(e)=>{
                    let questionwomb_id = e.target.id.split('_')[1];
                    let location = 'writeQuestionPromotePreview.php?questionwomb_id='+questionwomb_id+'&iid='+iid;
                    console.log ('location',location);
                    window.open(location,'_blank');
                    // window.location.href = location;
                })
            }
        })


    })



        let table_row = document.getElementsByClassName('table_row');
        let selections = document.getElementsByClassName('select');
        const iid = document.getElementById('iid').value;

            for (let i=0; i<selections.length; i++) {
                selections[i].addEventListener('click',(e)=>{
                    let questionwomb_id = e.target.id.split('_')[1];
                    let location = 'writeQuestionPromotePreview.php?questionwomb_id='+questionwomb_id+'&iid='+iid;
                    // console.log ('location',location);
                    window.location.href = location;
                })
            }

    })
	</script>


</body>
</html>