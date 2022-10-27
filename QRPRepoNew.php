<?php
require_once "pdo.php";
session_start();


$iid = '5';




?>



<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">


<meta Charset = "utf-8">
<title>QR New Repository</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 

<style>

</style>



</head>

<body>

<header>
    <h1>Quick Response Homework</h1>
</header>

<?php
	if ( isset($_SESSION['error']) ) {
		echo '<h3 style="color:red">'.$_SESSION['error']."</h3>\n";
		unset($_SESSION['error']);
	}
?>

<form>
    <input type="hidden" id="iid" value="<?php echo($iid);?>"
</form>

<div class="table-container">
    <table class="table table-striped">
        <thead>
            <th>Num</th>
            <th>Dicip</th>
            <th>Course</th>
            <th>Concept</th>
            <th>Title</th>
            <th>Status</th>
            <th>Class</th>
            <th>Asn</th>
            <th>Exam</th>
            <th>Pblm</th>
            <th>Author</th>
            <th>Contributor</th>
        </thead>
       <tbody id = "main_table_body">

       </tbody>
    </table>
</div>

<script>
const iid = document.getElementById('iid').value;

const main_table_body = document.getElementById('main_table_body'); 
fetch('getProblemsForRepo.php',{method: 'POST',
    headers: {
        
        "Content-Type": "application/json",
        "Accept":"application/json, text/plain, */*"
    },
    body: JSON.stringify({iid:iid}),
})
.then((res) => res.json())
.then((data) =>{
    // console.log(data);
    let table_row = '<tr></tr>';
    data.forEach((problem)=>{
       // console.log ()
        table_row += `<tr id = "${problem.problem_id}"><td>${problem.problem_id}</td>
        <td>${problem.subject}</td>        
        <td>${problem.course}</td>        
        <td>${problem.p_concept}</td>        
        <td>${problem.title}</td>        
        <td>${problem.status}</td>        
        <td>${problem.currentclass_id?problem.class_name:''}</td>        
        <td>${problem.assign_num?problem.assign_num:''}</td>        
        <td>${problem.exam_e_num?problem.exam_e_num:''}</td>  
        <td>${problem.e_alias_num?problem.e_alias_num:''}</td>        
        <td>${problem.author?problem.author:''}</td>        
        <td>${problem.e_alias_num?problem.e_alias_num:''}</td>        
      
        </tr>`;
    })
    // console.log(table_row);
    main_table_body.innerHTML = table_row;
})



</script>


</body>

