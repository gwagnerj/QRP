<?php
require_once "pdo.php";
session_start();

// passing my php varables into the js varaibles needed for the script below
$pass = array(
    'dex' => $_SESSION['dex'],
    'problem_id' => $_SESSION['problem_id'],
    'stu_name' => $_SESSION['stu_name'],
	'pin' => $_SESSION['pin'],
	'reflect_flag' => $_SESSION['reflect_flag'],
	'explore_flag' => $_SESSION['explore_flag'],
	'connect_flag' => $_SESSION['connect_flag'],
	'society_flag' => $_SESSION['society_flag'],
	'choice' => $_SESSION['choice'],
	'iid' => $_SESSION['iid'],
	'pp1' => $_SESSION['pp1'],
	'pp2' => $_SESSION['pp2'],
	'pp3' => $_SESSION['pp3'],
	'pp4' => $_SESSION['pp4'],
	'time_pp1' => $_SESSION['time_pp1'],
	'time_pp2' => $_SESSION['time_pp2'],
	'time_pp3' => $_SESSION['time_pp3'],
	'time_pp4' => $_SESSION['time_pp4'],
	
);
// echo ($pass['society_flag']);
//die();
echo '<script>';
echo 'var pass = ' . json_encode($pass) . ';';
echo '</script>';



?>

<!DOCTYPE html>
<html lang = "en">
<head>
<!--<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRHomework</title> -->
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>

<body>

<script>
$(document).ready(function(){
	
		
		var dex = pass['dex'];
		var problem = pass['problem_id'];
		var s_name = pass['stu_name'];
		var pin = pass['pin'];
		var iid = pass['iid'];
		var reflect_flag = pass['reflect_flag'];
		
		var explore_flag = pass['explore_flag'];
		var connect_flag = pass['connect_flag'];
		var society_flag = pass['society_flag'];
		var choice = pass['choice'];
		var pp1 = pass['pp1'];
		var pp2 = pass['pp2'];
		var pp3 = pass['pp3'];
		var pp4 = pass['pp4'];
		var time_pp1 = pass['time_pp1'];
		var time_pp2 = pass['time_pp2'];
		var time_pp3 = pass['time_pp3'];
		var time_pp4 = pass['time_pp4'];
		
		var statusFlag=true;
			//alert ('here I am');
		if($.trim(problem) != '' && problem > 0 && problem < 100000 && dex>=1 && dex<=200){
	// alert(1);
	
				 $.post('fetchpblminput.php', {problem_id : problem, index : dex }, function(data){
					
					try{
						var arr = JSON.parse(data);
					}
					catch(err) {
						alert ('problem data unavailable');
					}
				
				// Get the html file name from the database
					
					var openup = arr.htmlfilenm;
					
					
					// alert(openup);
					var game = arr.game_prob_flag;
					var status = arr.status;
					var prob_num = arr.problem_id;
					var contrib_first = arr.first;
					var contrib_last = arr.last;
					var contrib_university = arr.university;
					var static_f = false;
					localStorage.setItem('contrib_first',contrib_first);
					localStorage.setItem('contrib_last',contrib_last);
					localStorage.setItem('contrib_university',contrib_university);
					localStorage.setItem('nm_author',arr.nm_author);
					localStorage.setItem('specif_ref',arr.specif_ref);
					
					console.log(contrib_first);
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
							localStorage.setItem('stu_name',s_name);
							localStorage.setItem('problem_id',problem);
							localStorage.setItem('dex',dex);
							localStorage.setItem('pin',pin);
							localStorage.setItem('reflect_flag',reflect_flag);
							localStorage.setItem('explore_flag',explore_flag);
							localStorage.setItem('connect_flag',connect_flag);
							localStorage.setItem('society_flag',society_flag);
							localStorage.setItem('choice',choice);
							localStorage.setItem('static_flag',static_f);
							localStorage.setItem('iid',iid);
							localStorage.setItem('pp1',pp1);
							localStorage.setItem('pp2',pp2);
							localStorage.setItem('pp3',pp3);
							localStorage.setItem('pp4',pp4);
							localStorage.setItem('time_pp1',time_pp1);
							localStorage.setItem('time_pp2',time_pp2);
							localStorage.setItem('time_pp3',time_pp3);
							localStorage.setItem('time_pp4',time_pp4);
					
					//	window.location.href="uploads/"+openup;
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
		  
		  // get the basecase data
		   $.post('fetchpblminput.php', {problem_id : problem, index : 1 }, function(data){
					
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
							
							/* localStorage.setItem('title',arr2.title);
							localStorage.setItem('stu_name',s_name);
							localStorage.setItem('problem_id',problem);
							localStorage.setItem('index',inde); */
						
					// redirect the browser to the problem file
					
				// alert (statusFlag);

				// should run the php in the model to test the user input make sure the instructor ID or last name is vaiid and create and entry in the temp table if there 
				// isnt one and read the status if there is one and put it in the hidden html or get it via Json and AJAX
				
			
				
				window.location.href="uploads/"+openup;
						} else {
				
					alert('not a homework problem');
						} 
					} else {
						
					// alert('This problem is temporarily suspended, please check back later on2.');
								return;
						
						
					}

		   



					
		  });
		  
		  
		  
		  
			}
			else{
				
				alert ('invalid user input');
				
				
			}

});

</script>



</body>
</html>