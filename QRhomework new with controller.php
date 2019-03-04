<?php
	require_once "pdo.php";
	session_start();
	$_SESSION['progress']=0;
	$stu_name = '';
	$problem_id= '';
	$index='';
	$iid='';
	$instr_last='';

	// first time thru set scriptflag to zero - this will turn to 1 if the script ran
	if (!isset($sc_flag)){$sc_flag=0;}
			/* 
			Was setting this up to do more php input validation - but have put it off
			$PIN_LLimit = 1;
			$PIN_ULimit = 200;
			$prob_LLimit = 1;
			$prob_ULimit = 100000;
			$PIN_Check = array('options'=>array('min_range'=>$PIN_LLimit,'max_range'=>$PIN_ULimit,));
			$prob_Check = array('options'=>array('min_range'=>$prob_LLimit,'max_range'=>$prob_ULimit,)); */

	if(isset($_POST['stu_name'])){
		
		
		$stu_name = htmlentities($_POST['stu_name']);
		$_SESSION['stu_name']=$stu_name;
		
	} 

	if(isset($_POST['problem_id'])){
		
		$problem_id = htmlentities($_POST['problem_id']);
		
		$_SESSION['problem_id']=$problem_id;
	} 

	if(isset($_POST['problem_id'])){
		// echo ('im here');
		$problem_id = htmlentities($_POST['problem_id']);
			$_SESSION['problem_id']=$problem_id;
			
	} else {
		$_SESSION['error']='The Problem Number is Required';
		
	}

	if(isset($_POST['pin'])){
		
		$pin = htmlentities($_POST['pin']);
		if ($pin>10000 or $pin<1){
				$_SESSION['error']='Your PIN should be between 1 and 10000.';	
			} else {
				$_SESSION['pin']=$pin;
				
				$index = ($pin-1) % 199 + 2; // % is PHP mudulus function - changing the PIN to an index between 2 and 200
				$_SESSION['index'] = $index;
				
				
			}
		
	} else {
		$_SESSION['error']='Your PIN is Required';
	
	}

	if(isset($_POST['iid'])){
		$iid = htmlentities($_POST['iid']);
		$_SESSION['iid']=$iid;
		
				$sql = " SELECT 'user_id' FROM Users WHERE users_id = $iid" ;
					$stmt = $pdo->query($sql);
					if($stmt->rowCount()){
						$sql2 = " SELECT 'iid' FROM Assign WHERE iid = $iid" ;
						$stmt2 = $pdo->query($sql2);
						if($stmt2->rowCount()){
								$sql3 = "SELECT * FROM Assign WHERE iid=$iid AND prob_num=$problem_id" ;
								$stmt3 = $pdo->query($sql3);
								if($stmt3->rowCount()){
									// go the controller
									$_SESSION['progress']=1;
									echo('can I put anythiung here?');
									
									header("Location: QRcontroller.php");
									return; 
									
								} else {

									$_SESSION['error']	='The Instructor with this ID has not made this problem active.';	
								}									
					
					
					
						} else {
							
						$_SESSION['error']	='The Instructor for this ID has no active problems';	
						}
					
					} else {
					$_SESSION['error']	='The Instructor ID is not in the database.';	
					}
					
					
					} else {
						
					$_SESSION['error']='The Instructor ID is Required';	
						
						
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

<!--<h3>Print the problem statement with "Ctrl P"</h3>
 <p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p>  -->
<form method="POST">
	<p><font color=#003399>Name: </font><input type="text" name="stu_name" id = "stu_name_id" size= 20  value="<?php echo($stu_name);?>" ></p>
	
	<p><font color=#003399>PIN: </font><input type="number" name="pin" id="pin_id" size=3 value=<?php echo($pin);?> ></p>
	<p><font color=#003399>Instructor ID: </font><input type="text" name="iid" id="iid" size=5 value=<?php echo($iid.' ');?> >
	<font color=#003399 >  &nbsp; &nbsp; &nbsp;  or if you don't know: <a href="getiid.php"><b>Click Here</b></a></font></p>
<!--	<p><font color=#003399>script_flag: </font><input type="number" name="s_flag" id="script_flag" size=3 value=<?php echo($sc_flag);?> ></p>  -->
	<p><font color=#003399>Problem Number: </font><input type="number" name="problem_id" id="prob_id" size=3 value=<?php echo($problem_id);?> ></p>
	<p><input type = "submit" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	</form>

	
<script>
	/* 
	$(document).ready(function(){
	$('input#submit_id').on('click',function(event){
		event.preventDefault();
		var inde = $('input#index_id').val();
		var problem = $('input#prob_id').val();
		var s_name = $('input#stu_name_id').val();
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
					localStorage.setItem('index',inde);
					localStorage.setItem('static_flag',static_f);
			
			
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
		
	
		
		//window.location.href="uploads/"+openup;
			//	} else {
		
		//	alert('not a homework problem');
		//		} 
		//	} else {
				
			// alert('This problem is temporarily suspended, please check back later on2.');
		//				return;
				
				
			//}

   



			
 // });
  
  
  
  
 //	}
	//else{
		
	//	alert ('invalid user input');
		
		
//	}
// });
// }); */
</script>

</body>
</html>



