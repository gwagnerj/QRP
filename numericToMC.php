<?php

session_start();

// this is a project to get a four part multiple choice questions from the nummeic problems in the repository
// this will be good for objective test questions and test retention.  THis started with static.php and 
// THis is only really good for printing out problems since the answers are in javascript - we need a server side version for 
// multiple choice questions that are given on the WEB



//$stu_name = '';
$problem_id= '';
$index='';
/* 
Was setting this up to do more php input validation - but have put it off
$PIN_LLimit = 1;
$PIN_ULimit = 200;
$prob_LLimit = 1;
$prob_ULimit = 100000;
$PIN_Check = array('options'=>array('min_range'=>$PIN_LLimit,'max_range'=>$PIN_ULimit,));
$prob_Check = array('options'=>array('min_range'=>$prob_LLimit,'max_range'=>$prob_ULimit,)); */

/* if(isset($_POST['stu_name'])){
	
	
	$stu_name = htmlentities($_POST['stu_name']);
	$_SESSION['stu_name']=$stu_name;
	
}  */

if(isset($_POST['problem_id'])){
	
	$problem_id = htmlentities($_POST['problem_id']);
	
	$_SESSION['problem_id']=$problem_id;
}

if(isset($_POST['index'])){
	
	$index = htmlentities($_POST['index']);
	/*
		See comment above
	if(filter_var($index,FILTER_VALIDATE_INT,$PIN_Check) === FALSE){
		set($_SESSION['error']);
		echo'invalid data';
	} */
	$_SESSION['index']=$index;
	
	
}

?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRHomework MC</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<style>
table, th, td {
  border: 1px solid black;
}
</style>


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

<h3>Print the problem statement with "Ctrl P"</h3>
<p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p>
<form method="POST">
	<!-- <p><font color=#003399>Name: </font><input type="text" name="stu_name" id = "stu_name_id" size= 20  value="<?php echo($stu_name);?>" ></p> -->
	<p><font color=#003399>Problem Number: </font><input type="number" name="problem_id" id="prob_id" size=3 value=<?php echo($problem_id);?> ></p>
	<p><font color=#003399>PIN: </font><input type="number" name="index" id="index_id" size=3 value=<?php echo($index);?> ></p>
	
	
	
	
	
	
	<table  width="600" class = "onePerColumn">
		<thead>
			<tr>
				<th><h4> <font color = "blue" size =5 >Problem Part </font>&nbsp; &nbsp; &nbsp; &nbsp; </th>
				<th>	<span class = "parta" > a) </span> </th>
				<th>	<span class = "partb"> b) </span> </th>
				<th>	<span class = "partc"> c) </span> </th>
				<th>	<span class = "partd" > d) </span> </th>
				<th>	<span class = "parte"> e) </span> </th>
				<th>	<span class = "partf"> f) </span> </th>
				<th>	<span class = "partg"> g) </span> </th>
				<th>	<span class = "parth" > h) </span> </th>
				<th>	<span class = "parti"> i) </span> </th>
				<th>	<span class = "partj"> j) </span> </th>
			
			
			
			<div id = mc1>
			<tr><td>	<font color = "blue" size =5 > Multiple Choice 1 </font> </td>
				
				<td><span class = "parta"><input type="radio" name="mc1" value="parta"> </span></td>
				<td><span class = "partb"><input type="radio" name="mc1" value="partb"> </span></td>
				<td><span class = "partc"><input type="radio" name="mc1" value="partc"> </span></td>
				<td><span class = "partd"><input type="radio" name="mc1" value="partd"> </span></td>
				<td><span class = "parte"><input type="radio" name="mc1" value="parte"> </span></td>
				<td><span class = "partf"><input type="radio" name="mc1" value="partf"> </span></td>
				<td><span class = "partg"><input type="radio" name="mc1" value="partg"> </span></td>
				<td><span class = "parth"><input type="radio" name="mc1" value="parth"> </span></td>
				<td><span class = "parti"><input type="radio" name="mc1" value="parti"> </span></td>
				<td><span class = "partj"><input type="radio" name="mc1" value="partj"> </span></td>
			
			<tr><td>	<font color = "blue" size =5 > Multiple Choice 2 </font> </td>
				
				<td><span class = "parta"><input type="radio" name="mc2" value="parta"> </span></td>
				<td><span class = "partb"><input type="radio" name="mc2" value="partb"> </span></td>
				<td><span class = "partc"><input type="radio" name="mc2" value="partc"> </span></td>
				<td><span class = "partd"><input type="radio" name="mc2" value="partd"> </span></td>
				<td><span class = "parte"><input type="radio" name="mc2" value="parte"> </span></td>
				<td><span class = "partf"><input type="radio" name="mc2" value="partf"> </span></td>
				<td><span class = "partg"><input type="radio" name="mc2" value="partg"> </span></td>
				<td><span class = "parth"><input type="radio" name="mc2" value="parth"> </span></td>
				<td><span class = "parti"><input type="radio" name="mc2" value="parti"> </span></td>
				<td><span class = "partj"><input type="radio" name="mc2" value="partj"> </span></td>	
				
			<tr><td>	<font color = "blue" size =5 > Multiple Choice 3 </font> </td>
				
				<td><span class = "parta"><input type="radio" name="mc3" value="parta"> </span></td>
				<td><span class = "partb"><input type="radio" name="mc3" value="partb"> </span></td>
				<td><span class = "partc"><input type="radio" name="mc3" value="partc"> </span></td>
				<td><span class = "partd"><input type="radio" name="mc3" value="partd"> </span></td>
				<td><span class = "parte"><input type="radio" name="mc3" value="parte"> </span></td>
				<td><span class = "partf"><input type="radio" name="mc3" value="partf"> </span></td>
				<td><span class = "partg"><input type="radio" name="mc3" value="partg"> </span></td>
				<td><span class = "parth"><input type="radio" name="mc3" value="parth"> </span></td>
				<td><span class = "parti"><input type="radio" name="mc3" value="parti"> </span></td>
				<td><span class = "partj"><input type="radio" name="mc3" value="partj"> </span></td>	
				
			<tr><td>	<font color = "blue" size =5 > Give Answers </font> </td>
				
				<td><span class = "parta"><input type="checkbox" name="give_ans" value="parta"> </span></td>
				<td><span class = "partb"><input type="checkbox" name="give_ans" value="partb"> </span></td>
				<td><span class = "partc"><input type="checkbox" name="give_ans" value="partc"> </span></td>
				<td><span class = "partd"><input type="checkbox" name="give_ans" value="partd"> </span></td>
				<td><span class = "parte"><input type="checkbox" name="give_ans" value="parte"> </span></td>
				<td><span class = "partf"><input type="checkbox" name="give_ans" value="partf"> </span></td>
				<td><span class = "partg"><input type="checkbox" name="give_ans" value="partg"> </span></td>
				<td><span class = "parth"><input type="checkbox" name="give_ans" value="parth"> </span></td>
				<td><span class = "parti"><input type="checkbox" name="give_ans" value="parti"> </span></td>
				<td><span class = "partj"><input type="checkbox" name="give_ans" value="partj"> </span></td>	
				
			<tr><td>	<font color = "blue" size =5 > Remove </font> </td>
				
				<td><span class = "parta"><input type="checkbox" name="remove" value="parta"> </span></td>
				<td><span class = "partb"><input type="checkbox" name="remove" value="partb"> </span></td>
				<td><span class = "partc"><input type="checkbox" name="remove" value="partc"> </span></td>
				<td><span class = "partd"><input type="checkbox" name="remove" value="partd"> </span></td>
				<td><span class = "parte"><input type="checkbox" name="remove" value="parte"> </span></td>
				<td><span class = "partf"><input type="checkbox" name="remove" value="partf"> </span></td>
				<td><span class = "partg"><input type="checkbox" name="remove" value="partg"> </span></td>
				<td><span class = "parth"><input type="checkbox" name="remove" value="parth"> </span></td>
				<td><span class = "parti"><input type="checkbox" name="remove" value="parti"> </span></td>
				<td><span class = "partj"><input type="checkbox" name="remove" value="partj">	
				
				
		</table>		
		<!--	
			
				<h4> <font color = "blue" size =5 >Part </font>&nbsp; &nbsp; &nbsp; &nbsp; MC Question 1 &nbsp; &nbsp; MC Question 1 &nbsp; &nbsp; MC Question 1 &nbsp; &nbsp; Remove &nbsp; &nbsp; Give Answers   </br>
		<div id = parta>
			<font color = "blue" size =5 > a: </font>
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="parta" value="mc1"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="parta" value="mc2"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="parta" value="mc3">
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="parta" value="remove" checked = "Checked"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="parta" value="give_ans"> 
	<!--
		</div>
		<div id = partb>
			<font color = "blue" size =5 > b: </font>
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partb" value="mc1"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="partb" value="mc2"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="partb" value="mc3">
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partb" value="remove" checked = "Checked"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partb" value="give_ans"> 
	
		</div>
		<div id = partc>
			<font color = "blue" size =5 > c: </font>
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partc" value="mc1"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="partc" value="mc2"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="partc" value="mc3">
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partc" value="remove" checked = "Checked"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partc" value="give_ans"> 
		</div>
		<div id = partd>
			<font color = "blue" size =5 > d: </font>
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partd" value="mc1"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="partd" value="mc2"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="partd" value="mc3">
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partd" value="remove" checked = "Checked"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partd" value="give_ans"> 
		</div>
		<div id = parte>
			<font color = "blue" size =5 > e: </font>
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="parte" value="mc1"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="parte" value="mc2"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="parte" value="mc3">
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="parte" value="remove" checked = "Checked"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="parte" value="give_ans"> 
		</div>
		<div id = partf>
			<font color = "blue" size =5 > f: </font>
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partf" value="mc1"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="partf" value="mc2"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="partf" value="mc3">
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partf" value="remove" checked = "Checked"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partf" value="give_ans"> 
		</div>
		<div id = partg>
			<font color = "blue" size =5 > g: </font>
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partg" value="mc1"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="partg" value="mc2"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="partg" value="mc3">
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partg" value="remove" checked = "Checked"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partg" value="give_ans"> 
		</div>
		<div id = parth>
			<font color = "blue" size =5 > h: </font>
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="parth" value="mc1"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="parth" value="mc2"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="parth" value="mc3">
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="parth" value="remove" checked = "Checked"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="parth" value="give_ans"> 
		</div>
		<div id = parti>
			<font color = "blue" size =5 > i: </font>
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="parti" value="mc1"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="parti" value="mc2"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="parti" value="mc3">
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="parti" value="remove" checked = "Checked"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="parti" value="give_ans"> 
		</div>
		<div id = partj>
			<font color = "blue" size =5 > j: </font>
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partj" value="mc1"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="partj" value="mc2"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<input type="radio" name="partj" value="mc3">
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partj" value="remove" checked = "Checked"> 
			&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
			<input type="radio" name="partj" value="give_ans"> 
		</div>
	-->	
	
	<p><input type = "submit" value="Submit" id="submit_id" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp; &nbsp; </p>
	</form>

	
<script>
//Relies on a CSS class name onePerColumn being applied to the table.




	
	
	
	
	
	
	
	
	
	
	
	$(document).ready(function(){
		
		  $(".onePerColumn :radio").change(function(){
				var col = $(this).attr("value");
				$(".onePerColumn :radio[value='" + col + "']:checked").not(this).each(function(){
				  $(this).prop('checked',false);
				});
			});
 
		
		
	$('input#submit_id').on('click',function(event){
		
		
		
		
		
		
		
		event.preventDefault();
		var inde = $('input#index_id').val();
		var problem = $('input#prob_id').val();
	//	var s_name = $('input#stu_name_id').val();
		var statusFlag=true;
	
		if($.trim(problem) != '' && problem > 0 && problem < 100000 && inde>=1 && inde<=200){
	// alert(1);
	
			 $.post('fetchpblminput.php', {problem_id : problem, index : inde }, function(data){
				
				try{
					var arr = JSON.parse(data);
				}
				catch(err) {
					alert ('problem data unavailable');
				}
			
			
			
			$.post('fetchpblmqa.php', {problem_id : problem, index : inde , mc1 : mc1, mc2 : mc2, mc3 : mc3 , give_ans : give_ans, remove : remove }, function(data){
				
				try{
					var arr2 = JSON.parse(data);
				}
				catch(err) {
					alert ('problem data unavailable');
				}
			
			
			var key_a = arr2.key_a;
			var opt_a_1 = arr2.opt_a_1;
			var opt_a_2 = arr2.opt_a_2;
			var opt_a_3 = arr2.opt_a_3;
			var opt_a_4 = arr2.opt_a_4;
			
			console.log(key_a);
			console.log(opt_a_1);
			console.log(opt_a_2);
			console.log(opt_a_3);
			console.log(opt_a_4);
			
		
			
			
		
			
			
			
			//console.log(resp_a[50]);
		// console.log (resp_a);	
			
			
			
			
			
			
			
			// Get the html file name from the database
				
				
				
				var static_f = true;
				var openup = arr.htmlfilenm;
				
				// alert(openup);
				
				var game = arr.game_prob_flag;
				var status = arr.status;
				var prob_num = arr.problem_id;
				var contrib_first = arr.first;
				var contrib_last = arr.last;
				var contrib_university = arr.university;
				localStorage.setItem('contrib_first',contrib_first);
				localStorage.setItem('contrib_last',contrib_last);
				localStorage.setItem('contrib_university',contrib_university);
				localStorage.setItem('nm_author',arr.nm_author);
				localStorage.setItem('specif_ref',arr.specif_ref);
				
			//	console.log(contrib_first);
			//	console.log('arr', arr);
				if (status !== 'suspended'){
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
						localStorage.setItem('nv_11',arr.nv_11);
						localStorage.setItem(arr.nv_11,arr.v_11);
						localStorage.setItem('nv_12',arr.nv_12);
						localStorage.setItem(arr.nv_12,arr.v_12);
						localStorage.setItem('nv_13',arr.nv_13);
						localStorage.setItem(arr.nv_13,arr.v_13);
						localStorage.setItem('nv_14',arr.nv_14);
						localStorage.setItem(arr.nv_14,arr.v_14);
						
						
						localStorage.setItem('title',arr.title);
						//localStorage.setItem('stu_name',s_name);
						localStorage.setItem('problem_id',problem);
						localStorage.setItem('index',inde);
						localStorage.setItem('static_flag',static_f);
						localStorage.setItem('MC_flag','true');
						localStorage.setItem('key_a',key_a);
						localStorage.setItem('opt_a_1',opt_a_1);
						localStorage.setItem('opt_a_2',opt_a_2);
						localStorage.setItem('opt_a_3',opt_a_3);
						localStorage.setItem('opt_a_4',opt_a_4);
						localStorage.setItem('key_b',arr2.key_b);
						localStorage.setItem('opt_b_1',arr2.opt_b_1);
						localStorage.setItem('opt_b_2',arr2.opt_b_2);
						localStorage.setItem('opt_b_3',arr2.opt_b_3);
						localStorage.setItem('opt_b_4',arr2.opt_b_4);
						localStorage.setItem('key_c',arr2.key_c);
						localStorage.setItem('opt_c_1',arr2.opt_c_1);
						localStorage.setItem('opt_c_2',arr2.opt_c_2);
						localStorage.setItem('opt_c_3',arr2.opt_c_3);
						localStorage.setItem('opt_c_4',arr2.opt_c_4);
						
						
						
						
						
						
				
					window.location.href="uploads/"+openup;
						} else {
				
				alert('not a homework problem');
						} 
				 } else {
					
						alert('This problem is temporarily suspended, please check back later.');
						//window.location.href="QRhomework.php";
						
						statusFlag=false;
						//return;
					

				 }

				 
			
		});
	  });			
	  
	  /*  $.post('fetchpblminput.php', {problem_id : problem, index : 1 }, function(data){
				
				var arr2 = JSON.parse(data);
			// Get the html file name from the database
				
			//	var openup = arr.htmlfilenm;
			
			var openup = arr2.htmlfilenm;		
			//	alert(openup);
			
		//	alert (openup);
			if (openup == null){
				
			alert('problem not present');
			return;
				
			}
			
			
			var game = arr2.game_prob_flag;
				
			//	Set up the basecase values into the local variables
				if (statusFlag){
					if (game==0){
						
						var x = "bc_"+arr2.nv_1;
						localStorage.setItem(x,arr2.v_1);
						
						x = "bc_"+arr2.nv_2;
						localStorage.setItem(x,arr2.v_2);
						
						x = "bc_"+arr2.nv_3;
						localStorage.setItem(x,arr2.v_3);
							x = "bc_"+arr2.nv_4;
						localStorage.setItem(x,arr2.v_4);
						x = "bc_"+arr2.nv_5;
						localStorage.setItem(x,arr2.v_5);
						x = "bc_"+arr2.nv_6;
						localStorage.setItem(x,arr2.v_6);
							x = "bc_"+arr2.nv_7;
						localStorage.setItem(x,arr2.v_7);
						x = "bc_"+arr2.nv_8;
						localStorage.setItem(x,arr2.v_8);
						x = "bc_"+arr2.nv_9;
						localStorage.setItem(x,arr2.v_9);
							x = "bc_"+arr2.nv_10;
						localStorage.setItem(x,arr2.v_10);
						x = "bc_"+arr2.nv_11;
						localStorage.setItem(x,arr2.v_11);
						x = "bc_"+arr2.nv_12;
						localStorage.setItem(x,arr2.v_12);
							x = "bc_"+arr2.nv_13;
						localStorage.setItem(x,arr2.v_13);
						x = "bc_"+arr2.nv_14;
						localStorage.setItem(x,arr2.v_14);
						
						 localStorage.setItem('title',arr2.title);
						localStorage.setItem('stu_name',s_name);
						localStorage.setItem('problem_id',problem);
						localStorage.setItem('index',inde); 
					
				// redirect the browser to the problem file
				
			// alert (statusFlag);

			window.location.href="uploads/"+openup;
					} else {
			
				alert('not a homework problem');
					} 
				} else {
					
				// alert('This problem is temporarily suspended, please check back later on2.');
							return;
					
					
				}

	   



				
	  }); */
	  
	  
	  
	  
			}
				else{
					
					alert ('invalid user input');
					
					
					}
			});
	});
	
	
	
	
</script>

</body>
</html>



