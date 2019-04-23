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

if(isset($_GET['problem_id'])){
	
	$problem_id = htmlentities($_GET['problem_id']);
	
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
	width = 20
  // border: 1px solid black;
}
</style>


</head>

<body>
<header>
<h1>Quick Response Multiple Choice </h1>
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
// $problem_id = 238; // temp
 $index = 101;
?>


<form method="POST">
	
	<p><font color=#003399>Problem Number: </font><input type="number" name="problem_id" id="prob_id" size=3 value=<?php echo($problem_id);?> ></p>
	<p><font color=#003399>PIN: </font><input type="number" name="index" id="index_id" size=3 value=<?php echo($index);?> ></p>
	
	
	
	
	
	
	<table   class = "onePerColumn">
		<thead>
			<tr>
				<th><h4> <font color = "blue" size =5 >Problem Part </font> </th>
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
			
			
			
			
			<tr><td>	<font color = "blue" size =5 > Multiple Choice 1 </font> </td>
				
				<td><span class = "parta"><input type="radio" name="mc1" Value="ans_a" checked = "checked"> </span></td>
				<td><span class = "partb"><input type="radio" name="mc1" Value="ans_b"> </span></td>
				<td><span class = "partc"><input type="radio" name="mc1" Value="ans_c"> </span></td>
				<td><span class = "partd"><input type="radio" name="mc1" Value="ans_d"> </span></td>
				<td><span class = "parte"><input type="radio" name="mc1" Value="ans_e"> </span></td>
				<td><span class = "partf"><input type="radio" name="mc1" Value="ans_f"> </span></td>
				<td><span class = "partg"><input type="radio" name="mc1" Value="ans_g"> </span></td>
				<td><span class = "parth"><input type="radio" name="mc1" Value="ans_h"> </span></td>
				<td><span class = "parti"><input type="radio" name="mc1" Value="ans_i"> </span></td>
				<td><span class = "partj"><input type="radio" name="mc1" Value="ans_j"> </span></td>
			
			<tr><td>	<font color = "blue" size =5 > Multiple Choice 2 </font> </td>
				
				<td><span class = "parta"><input type="radio" name="mc2" Value="ans_a"> </span></td>
				<td><span class = "partb"><input type="radio" name="mc2" Value="ans_b" > </span></td>
				<td><span class = "partc"><input type="radio" name="mc2" Value="ans_c"> </span></td>
				<td><span class = "partd"><input type="radio" name="mc2" Value="ans_d"> </span></td>
				<td><span class = "parte"><input type="radio" name="mc2" Value="ans_e"> </span></td>
				<td><span class = "partf"><input type="radio" name="mc2" Value="ans_f"> </span></td>
				<td><span class = "partg"><input type="radio" name="mc2" Value="ans_g"> </span></td>
				<td><span class = "parth"><input type="radio" name="mc2" Value="ans_h"> </span></td>
				<td><span class = "parti"><input type="radio" name="mc2" Value="ans_i"> </span></td>
				<td><span class = "partj"><input type="radio" name="mc2" Value="ans_j"> </span></td>	
				
			<tr><td>	<font color = "blue" size =5 > Multiple Choice 3 </font> </td>
				
				<td><span class = "parta"><input type="radio" name="mc3" Value="ans_a"> </span></td>
				<td><span class = "partb"><input type="radio" name="mc3" Value="ans_b"> </span></td>
				<td><span class = "partc"><input type="radio" name="mc3" Value="ans_c" > </span></td>
				<td><span class = "partd"><input type="radio" name="mc3" Value="ans_d"> </span></td>
				<td><span class = "parte"><input type="radio" name="mc3" Value="ans_e"> </span></td>
				<td><span class = "partf"><input type="radio" name="mc3" Value="ans_f"> </span></td>
				<td><span class = "partg"><input type="radio" name="mc3" Value="ans_g"> </span></td>
				<td><span class = "parth"><input type="radio" name="mc3" Value="ans_h"> </span></td>
				<td><span class = "parti"><input type="radio" name="mc3" Value="ans_i"> </span></td>
				<td><span class = "partj"><input type="radio" name="mc3" Value="ans_j"> </span></td>	
				
			<tr><td>	<font color = "blue" size =5 > Give Answers </font> </td>
				
				<td><span class = "parta"><input type="checkbox" name="give_ans" value="ans_a"> </span></td>
				<td><span class = "partb"><input type="checkbox" name="give_ans" value="ans_b"> </span></td>
				<td><span class = "partc"><input type="checkbox" name="give_ans" value="ans_c"> </span></td>
				<td><span class = "partd"><input type="checkbox" name="give_ans" value="ans_d"> </span></td>
				<td><span class = "parte"><input type="checkbox" name="give_ans" value="ans_e"> </span></td>
				<td><span class = "partf"><input type="checkbox" name="give_ans" value="ans_f"> </span></td>
				<td><span class = "partg"><input type="checkbox" name="give_ans" value="ans_g"> </span></td>
				<td><span class = "parth"><input type="checkbox" name="give_ans" value="ans_h"> </span></td>
				<td><span class = "parti"><input type="checkbox" name="give_ans" value="ans_i"> </span></td>
				<td><span class = "partj"><input type="checkbox" name="give_ans" value="ans_j"> </span></td>	
				
			<tr class = "row5"><td>	<font color = "blue" size =5 > Remove </font> </td>
				
				<td><span class = "parta"><input type="checkbox" name="remove" value="ans_a"> </span></td>
				<td><span class = "partb"><input type="checkbox" name="remove" value="ans_b" checked = "checked"> </span></td>
				<td><span class = "partc"><input type="checkbox" name="remove" value="ans_c" checked = "checked"> </span></td>
				<td><span class = "partd"><input type="checkbox" name="remove" value="ans_d" checked = "checked"> </span></td>
				<td><span class = "parte"><input type="checkbox" name="remove" value="ans_e" checked = "checked"> </span></td>
				<td><span class = "partf"><input type="checkbox" name="remove" value="ans_f" checked = "checked"> </span></td>
				<td><span class = "partg"><input type="checkbox" name="remove" value="ans_g" checked = "checked"> </span></td>
				<td><span class = "parth"><input type="checkbox" name="remove" value="ans_h" checked = "checked"> </span></td>
				<td><span class = "parti"><input type="checkbox" name="remove" value="ans_i" checked = "checked"> </span></td>
				<td><span class = "partj"><input type="checkbox" name="remove" value="ans_j" checked = "checked">	
				
				
		</table>		
		
	<p><input type = "checkbox" value=1 name = "show_key" id="show_key" size="14" /> Show Key  </p>
	<p><input type = "submit" value="Submit" id="submit_id" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp; &nbsp; </p>
	</form>

	
<script>

	
	
	
	
	$(document).ready(function(){
	// this next bit makes it so you can not have two columns with two items checked
			 $(".onePerColumn :radio,:checkbox").change(function(){
				var col = $(this).attr("value");
					$(".onePerColumn :radio[value='" + col + "']:checked,.onePerColumn :checkbox[value='" + col + "']:checked").not(this).each(function(){
						$(this).prop('checked',false);
					});
			});
 	var problem = $('input#prob_id').val();
	


	
// find out how many parts are in the problem

	$.post('fetchPartsInProblem.php', {problem_id : problem, }, function(data){
				
				try{
					var arrn = JSON.parse(data);
				}
				catch(err) {
					alert ('problem data unavailable n tot found');
				}

	var n = arrn.n
	// console.log (n);

if (n<10){$(".partj").hide();}
if (n<9){$(".parti").hide();}
if (n<8){$(".parth").hide();}
if (n<7){$(".partg").hide();}
if (n<6){$(".partf").hide();}
if (n<5){$(".parte").hide();}
if (n<4){$(".partd").hide();}
if (n<3){$(".partc").hide();}
if (n<2){$(".partb").hide();}
	
	$('input#submit_id').on('click',function(event){
		
		
		event.preventDefault();
		var OneIsChecked = $('input[name = "mc1"]:checked').length ==1;
			if(!OneIsChecked){alert('Multiple choice 1 needs one radio button selected');
			return;
			}
		
	//	console.log (OneIsChecked);
	
	var mc1 = $('input[name = "mc1"]:checked').val();
	var mc2 = $('input[name = "mc2"]:checked').val();
	var mc3 = $('input[name = "mc3"]:checked').val();
	
	
	
	
	
	var give_ans =[];
	$.each($("input[name='give_ans']:checked"),function(){
		give_ans.push($(this).val());
	});
	
	console.log (give_ans);
	
	
	var show_key = $('input[name = "show_key"]:checked').val();
	if (show_key == undefined){show_key = 0;}
	
	//alert (show_key);
	
	var num_checked = 0;
	num_checked = $('input:checked').length;
	if (num_checked - show_key != 10){
		//	alert (num_checked);
		//	alert (show_key);
			alert('Every column should have one item checked');
			return;
	}
	
	

		var inde = $('input#index_id').val();
	
	//	var s_name = $('input#stu_name_id').val();
		var statusFlag=true;
	
		if($.trim(problem) != '' && problem > 0 && problem < 100000 && inde>=1 && inde<=200){
	// alert(1);
	
			 $.post('fetchpblminput.php', {problem_id : problem, index : inde }, function(data){
				
				try{
					var arr = JSON.parse(data);
				}
				catch(err) {
					alert ('problem input data unavailable');
				}
			// if (n<=2){mc3 = null;}
			// if (n==1){mc2=null;}
			
			
			console.log (mc1);
			console.log (mc2);
			console.log (mc3);
			
			// variables to pass
			var part_a = "";
			var part_b = "";
			var part_c = "";
			var part_d = "";
			var part_e = "";
			var part_f = "";
			var part_g = "";
			var part_h = "";
			var part_i = "";
			var part_j = "";
		
			console.log (n);
			console.log(problem);
			console.log(inde);
			console.log(mc1);
			console.log(mc2);
				console.log(mc3);
			
			
			$.post('fetchpblmqa.php', {problem_id : problem, dex : inde , mc1 : mc1, mc2 : mc2, mc3 : mc3 , n : n }, function(data){
				
				try{
					var arr2 = JSON.parse(data);
				}
				catch(err) {
				//	var arr2 -> data.text();
					
					alert ('problem qa data is unavailable')
					alert (err);
					alert (arr2);
				}
		// construct the multiple choice option strings
		var mc1_str = "<p ><font size = '3'> &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;       i) "+arr2.opt_i_1+"   &nbsp;&nbsp;   ii) " +arr2.opt_ii_1+"  &nbsp;&nbsp;    iii) "+ arr2.opt_iii_1 + "  &nbsp;&nbsp;    iv) "+arr2.opt_iv_1+"</font> </p>";
		var mc2_str = " <p ><font size = '3'>  &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;    &nbsp; &nbsp;&nbsp;        i) "+arr2.opt_i_2+"  &nbsp;&nbsp;    ii) " +arr2.opt_ii_2+"  &nbsp;&nbsp;    iii) "+ arr2.opt_iii_2 + "   &nbsp;&nbsp;   iv) "+arr2.opt_iv_2+"</font> </p>";
		var mc3_str = " <p ><font size = '3'>   &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;     &nbsp; &nbsp;&nbsp;      i) "+arr2.opt_i_3+"  &nbsp;&nbsp;    ii) " +arr2.opt_ii_3+"   &nbsp;&nbsp;   iii) "+ arr2.opt_iii_3 + " &nbsp;&nbsp;     iv) "+arr2.opt_iv_3+"</font> </p>";
		
		
		// give them the answers for the parts of the problem the instructor wants to give them the answers
			if(give_ans.includes("ans_a")){part_a = "<p ><font size = '2'> &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_a+"</font> </p>";}
			if(give_ans.includes("ans_b")){part_b = "<p ><font size = '2'> &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_b+"</font> </p>";}
			if(give_ans.includes("ans_c")){part_c = "<p ><font size = '2'> &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_c+"</font> </p>";}
			if(give_ans.includes("ans_d")){part_d = "<p ><font size = '2'> &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_d+"</font> </p>";}
			if(give_ans.includes("ans_e")){part_e = "<p ><font size = '2'>&nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;  &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_e+"</font> </p>";}
			if(give_ans.includes("ans_f")){part_f = "<p ><font size = '2'> &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_f+"</font> </p>";}
			if(give_ans.includes("ans_g")){part_g = "<p ><font size = '2'>&nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;  &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_g+"</font> </p>";}
			if(give_ans.includes("ans_h")){part_h = "<p ><font size = '2'> &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_h+"</font> </p>";}
			if(give_ans.includes("ans_i")){part_i = "<p ><font size = '2'>&nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;  &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_i+"</font> </p>";}
			if(give_ans.includes("ans_j")){part_j = "<p ><font size = '2'> &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_j+"</font> </p>";}
			
		// define the different parts of - know I should read into an array but...	
			if(mc1 == "ans_a"){part_a = mc1_str;}
			if(mc1 == "ans_b"){part_b = mc1_str;}
			if(mc1 == "ans_c"){part_c = mc1_str;}
			if(mc1 == "ans_d"){part_d = mc1_str;}
			if(mc1 == "ans_e"){part_e = mc1_str;}
			if(mc1 == "ans_f"){part_f = mc1_str;}
			if(mc1 == "ans_g"){part_g = mc1_str;}
			if(mc1 == "ans_h"){part_h = mc1_str;}
			if(mc1 == "ans_i"){part_i = mc1_str;}
			if(mc1 == "ans_j"){part_j = mc1_str;}
			
			if(mc2 == "ans_a"){part_a = mc2_str;}
			if(mc2 == "ans_b"){part_b = mc2_str;}
			if(mc2 == "ans_c"){part_c = mc2_str;}
			if(mc2 == "ans_d"){part_d = mc2_str;}
			if(mc2 == "ans_e"){part_e = mc2_str;}
			if(mc2 == "ans_f"){part_f = mc2_str;}
			if(mc2 == "ans_g"){part_g = mc2_str;}
			if(mc2 == "ans_h"){part_h = mc2_str;}
			if(mc2 == "ans_i"){part_i = mc2_str;}
			if(mc2 == "ans_j"){part_j = mc2_str;}
			
			if(mc3 == "ans_a"){part_a = mc3_str;}
			if(mc3 == "ans_b"){part_b = mc3_str;}
			if(mc3 == "ans_c"){part_c = mc3_str;}
			if(mc3 == "ans_d"){part_d = mc3_str;}
			if(mc3 == "ans_e"){part_e = mc3_str;}
			if(mc3 == "ans_f"){part_f = mc3_str;}
			if(mc3 == "ans_g"){part_g = mc3_str;}
			if(mc3 == "ans_h"){part_h = mc3_str;}
			if(mc3 == "ans_i"){part_i = mc3_str;}
			if(mc3 == "ans_j"){part_j = mc3_str;}
			
			
			console.log ('part_a', part_a);
			
			console.log ('part_d', part_d);
				console.log ('mc1_str', mc1_str);
			
			
			
			
			
			
			var key_1 = arr2.key_1;
			var opt_i_1 = arr2.opt_i_1;
			var opt_ii_1 = arr2.opt_ii_1;
			var opt_iii_1 = arr2.opt_iii_1;
			var opt_iv_1 = arr2.opt_iv_1;
			console.log('1st muliple choice Options');
			console.log(key_1);
			console.log(opt_i_1);
			console.log(opt_ii_1);
			console.log(opt_iii_1);
			console.log(opt_iv_1);
			console.log('2nd muliple choice Options');
			console.log(arr2.key_2);
			console.log(arr2.opt_i_2);
			console.log(arr2.opt_ii_2);
			console.log(arr2.opt_iii_2);
			console.log(arr2.opt_iv_2);
				console.log('3rd muliple choice Options');
			console.log(arr2.key_3);
			console.log(arr2.opt_i_3);
			console.log(arr2.opt_ii_3);
			console.log(arr2.opt_iii_3);
			console.log(arr2.opt_iv_3);
		
			// alert ('wowo');
			
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
						localStorage.setItem('key_1',arr2.key_1);
						localStorage.setItem('key_2',arr2.key_2);
						localStorage.setItem('key_3',arr2.key_3);
						localStorage.setItem('part_a',part_a);
						localStorage.setItem('part_b',part_b);
						localStorage.setItem('part_c',part_c);
						localStorage.setItem('part_d',part_d);
						localStorage.setItem('part_e',part_e);
						localStorage.setItem('part_f',part_f);
						localStorage.setItem('part_g',part_g);
						localStorage.setItem('part_h',part_h);
						localStorage.setItem('part_i',part_i);
						localStorage.setItem('part_j',part_j);
						localStorage.setItem('show_key',show_key);


						
						// if the give answers box is checked send the answers to the page
					//	if (){}
						
						
						
						
						
				
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
	
});
	
	
</script>

</body>
</html>



