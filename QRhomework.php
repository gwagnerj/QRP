<?php
//require_once "pdo.php";
session_start();
$stu_name = '';
$problem_id= '';
$index='';

if(isset($_POST['stu_name'])){
	
	
	$stu_name = htmlentities($_POST['stu_name']);
	$_SESSION['stu_name']=$stu_name;
} 




if(isset($_POST['problem_id'])){
	
	$problem_id = htmlentities($_POST['problem_id']);
	$_SESSION['problem_id']=$problem_id;
}

if(isset($_POST['index'])){
	
	$index = htmlentities($_POST['index']);
	$_SESSION['index']=$index;
}

?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRHomework</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>

<body>
<header>
<h1>Quick Response Homework </h1>
</header>

<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}

 
?>
<p> Add information, click on submit and print the page that is generated<br>
<font color = 'blue' size='2'> note - you may want to resize before printing.
Try "ctrl +" and "ctrl -" for resizing and "ctrl p" for printing</font></p>
<form method = POST>
	<p><font color=#003399>Name: </font><input type="text" name="stu_name" id = "stu_name_id" size= 20  value="<?php echo($stu_name);?>" ></p>
	<p><font color=#003399>Problem Number: </font><input type="number" name="problem_id" id="prob_id" size=3 value=<?php echo($problem_id);?> min="1" Max = "100000" required></p>
	<p><font color=#003399>Index Number: </font><input type="number" name="index" id="index_id" size=3 value=<?php echo($index);?> min="2" Max="200" ></p>

	<p><input type = "submit" value="Submit" id="submit_id" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>
	</form>

	
<script>
	
	$(document).ready(function(){
	$('input#submit_id').on('click',function(){
	var inde = $('input#index_id').val();
	var problem = $('input#prob_id').val();
	var s_name = $('input#stu_name_id').val();
	
	
	if($.trim(problem) != '' && problem > 0 && problem < 100000 && inde>=2 && inde<=200){
	//alert(1);
		 $.post('fetchpblminput.php',{problem_id : problem, index : inde },function(data){
		
			
			var arr = JSON.parse(data);
			var openup = arr.htmlfilenm;
			var game = arr.game_prob_flag;
			var prob_num = arr.problem_id;
			

			
			
			if (game==0){
			localStorage.setItem('nv_1',arr.nv_1);
			localStorage.setItem(arr.nv_1,arr.v_1);
			localStorage.setItem('nv_2',arr.nv_2);
			localStorage.setItem(arr.nv_2,arr.v_2);
			localStorage.setItem('nv_3',arr.nv_3);
			localStorage.setItem(arr.nv_3,arr.v_3);
			localStorage.setItem('nv_4',arr.nv_4);
			localStorage.setItem(arr.nv_4,arr.v_4);
			localStorage.setItem('nv_5',arr.nv_5);
			localStorage.setItem(arr.nv_5,arr.v_5);
			localStorage.setItem('nv_6',arr.nv_6);
			localStorage.setItem(arr.nv_6,arr.v_6);
			localStorage.setItem('nv_7',arr.nv_7);
			localStorage.setItem(arr.nv_7,arr.v_7);
			localStorage.setItem('nv_8',arr.nv_8);
			localStorage.setItem(arr.nv_8,arr.v_8);
			localStorage.setItem('nv_9',arr.nv_9);
			localStorage.setItem(arr.nv_9,arr.v_9);
			localStorage.setItem('nv_10',arr.nv_10);
			localStorage.setItem(arr.nv_10,arr.v_10);
			
			localStorage.setItem('title',arr.title);
			localStorage.setItem('stu_name',s_name);
			localStorage.setItem('problem_id',problem);
			localStorage.setItem('index',inde);
			
			
			window.location.href="uploads/"+openup;
			} else {
	
	alert('not a homework problem');
			}
			
			
  });
 	}
	else{
		
		Alert ('invalid user input');
		
		
	}
});
});
</script>

</body>
</html>



