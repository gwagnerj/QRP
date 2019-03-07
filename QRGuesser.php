<?php
 session_start();
   $_SESSION['score'] = "0";
	
	//$_SESSION['count'] = 0;
	
Require_once "pdo.php";

$pass = array(
    'dex' => $_SESSION['dex'],
    'problem_id' => $_SESSION['problem_id'],
	'pin' => $_SESSION['pin'],
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

echo '<script>';
echo 'var pass = ' . json_encode($pass) . ';';
echo '</script>';

	
	// initialize some variables
	// put this in to get the file up needs to be removed when ready for prime time
		
		

	
	$probParts=0;
	//$partsFlag = array('a'=>false,'b'=>false,'c'=>false,'d'=>false,'e'=>false,'f'=>false,'g'=>false,'h'=>false,'i'=>false,'j'=>false);
	$resp = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
	$unit = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
	
	
	$dispBase = 1;
	

	$resp_key=array_keys($resp);
	
		
	// Next check the Qa table and see which values have non null values - for those 

$stmt = $pdo->prepare("SELECT * FROM Qa where problem_id = :problem_id AND dex = :dex");
$stmt->execute(array(":problem_id" => $_SESSION['problem_id'], ":dex" => $_SESSION['dex']));
//$row = $stmt->fetch(PDO::FETCH_ASSOC);
$row = $stmt -> fetch();
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for problem_id';
    header( 'Location: QRPindex.php' ) ;
    return;
}	
		$soln = array_slice($row,6); // this would mean the database table Qa would have the dame structure
	

	for ($i = 0;$i<=9; $i++){  
		if ($soln[$i]==1.2345e43) {
			$partsFlag[$i]=false;
		} else {
			$probParts = $probParts+1;
			$partsFlag[$i]=true;
		}
	}
	//get the tolerance for each part - only really need to do this once on the get request - change if it is slow
	$stmt = $pdo->prepare("SELECT * FROM Problem where problem_id = :problem_id");
	$stmt->execute(array(":problem_id" => $_SESSION['problem_id']));
	//$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$row = $stmt -> fetch();
	
	$probData=$row;	
	
	$probStatus = $probData['status'];
	if ($probStatus =='suspended'){
		$_SESSION['error'] = 'problem has been suspended, check back later';
		header( 'Location: QRPindex.php' ) ;
		return;	
	}
	

	
	$unit = array_slice($row,22,20);  // shows the same thing but easier so long as the table always has the same structure
	//print_r($unit);

	
// read the student responses into an array
	$resp['a']=$_POST['a']+0;
	$resp['b']=$_POST['b']+0;
	$resp['c']=$_POST['c']+0;
	$resp['d']=$_POST['d']+0;
	$resp['e']=$_POST['e']+0;
	$resp['f']=$_POST['f']+0;
	$resp['g']=$_POST['g']+0;
	$resp['h']=$_POST['h']+0;
	$resp['i']=$_POST['i']+0;
	$resp['j']=$_POST['j']+0;
	
	
	
	
	// if the student fills out all of the entries then change the pp1 to 2 in the activity table and make a datestamp for time_pp1 (just let the html check the input)
	if(isset($_POST['submit']))	{
		
		echo('Oh Yeah');
		
		
	}
	
	
	?>



<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRGuestimate</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
<header>
<h1>QRHomework Preliminaries Estimates for Base-Case</h1>
</header>
<main>


<p> Problem Number: <?php echo ($_SESSION['problem_id']) ?> </p>
<?php
$_POST['problem_id']=$_SESSION['problem_id'];
$_POST['index']=$_SESSION['dex'];
//include("getBC.php");

echo('<form action = "getBC.php" method = "POST" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$_SESSION['problem_id'].'"><input type = "hidden" name = "index" value = "1" ><input type = "submit" value ="PreView Basecase in a new Window"></form>');


?>

<form autocomplete="off" method="POST" >
<!-- <p>Problem Number: <input type="text" name="problem_number" ></p> -->
<!-- <p> Please put in your index number </p> 
<p><font color=#003399>PIN: </font><input type="text" name="dex_num" size=3 value="<?php //echo (htmlentities($_SESSION['dex']))?>"  ></p> -->
<p> <strong> Make a thoughtful guestimate after reading the base-case problem statement - then select "Submit" </strong></p>
<div id='putpblm'></div>

<?php

if ($partsFlag[0]){ ?> 
<p> a): <input  type=number {width: 5%} name="a" size = 10% value="<?php if ($resp['a']!=0){echo (htmlentities($resp['a']));}?>" required > <?php echo($unit[0]) ?>
  </p>
<?php } 

if ($partsFlag[1]){ ?> 
<p> b): <input  type=number {width: 5%;} name="b" size = 10% value="<?php if ($resp['b']!=0){echo (htmlentities($resp['b']));}?>" required > <?php echo($unit[1]) ?>
</p>
<?php } 

if ($partsFlag[2]){ ?> 
<p> c): <input  type=number {width: 5%;} name="c" size = 10% value="<?php if ($resp['c']!=0){echo (htmlentities($resp['c']));}?>" required> <?php echo($unit[2]) ?> 
</p>
<?php } 

if ($partsFlag[3]){ ?> 
<p> d): <input  type=number {width: 5%;} name="d" size = 10% value="<?php if ($resp['d']!=0){echo (htmlentities($resp['d']));}?>" required> <?php echo($unit[3]) ?> 
</p>
<?php } 

if ($partsFlag[4]){ ?> 
<p> e): <input  type=number {width: 5%;} name="e" size = 10% value="<?php if ($resp['e']!=0){echo (htmlentities($resp['e']));}?>" required > <?php echo($unit[4]) ?>
<?php } 

if ($partsFlag[5]){ ?> 
<p> f): <input  type=number {width: 5%;} name="f" size = 10% value="<?php if ($resp['f']!=0){echo (htmlentities($resp['f']));}?>" required> <?php echo($unit[5]) ?> 
</p>
<?php } 

if ($partsFlag[6]){ ?> 
<p> g): <input  type=number {width: 5%;} name="g" size = 10% value="<?php if ($resp['g']!=0){echo (htmlentities($resp['g']));}?>" required> <?php echo($unit[6]) ?> 
</p>
<?php } 

if ($partsFlag[7]){ ?> 
<p> h): <input type=number {width: 5%;} name="h" size = 10% value="<?php if ($resp['h']!=0){echo (htmlentities($resp['h']));}?>" required> <?php echo($unit[7]) ?> 
</p>
<?php } 

if ($partsFlag[8]){ ?> 
<p> i): <input  type=number {width: 5%;} name="i" size = 10% value="<?php if ($resp['i']!=0){echo (htmlentities($resp['i']));}?>" required> <?php echo($unit[8]) ?>
</p>
<?php } 

if ($partsFlag[9]){ ?> 
<p> j): <input  type=number {width: 5%;} name="j" size = 10% value="<?php if ($resp['j']!=0){echo (htmlentities($resp['j']));}?>" required> <?php echo($unit[9]) ?>
</p>

<?php } 


?>

<!--<p>Grading Scheme: <input type="text" name="grade_scheme" ></p> -->
<p><input type = "submit" value="Submit" name = "submit" id="submitBtn" size="10" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp <b> <font size="4" color="Navy"></font></b></p>


</form>
<!--
<form>
<p><input type = "submit" value="See Problem Statement in a new window" name = "openpblm" id="openProblem" size="10" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp <b> <font size="4" color="Navy"></font></b></p>
</form>
-->
<?php
// need to check to make sure they are all filled out (score is 100) then write the time stamp for time_pp1 in the Activity table
// also need to get the PIN IID and problem number from the Session data that checker.php should be sending
// write 2 into the Activity table for pp1


?>




 <!--<form method="get" >
<p><input type = "submit" value="Finished"/> </p>
</form> 

  <?php $_SESSION['score'] = $PScore; $_SESSION['index'] = $index; $_SESSION['count'] = $count; ?>
 <b><input type="submit" value="Rate & Get rtn Code" style = "width: 30%; background-color:yellow "></b>
 <p><br> </p>
 <hr>
</form>

<form method = "POST">
<p><input type = "submit" value="Get Base-Case Answers" name = "show_base" size="10" style = "width: 30%; background-color: green; color: white"/> &nbsp &nbsp <b> <font size="4" color="Green"></font></b></p>
</form>  
-->


</main>
<script>
/* 		// display the static problem (or basecase) I have not decided which
		var dex = pass['dex'];
		var problem = pass['problem_id'];
		var pin = pass['pin'];
		var iid = pass['iid'];
		var pp1 = pass['pp1'];
		var pp2 = pass['pp2'];
		var pp3 = pass['pp3'];
		var pp4 = pass['pp4'];
		var time_pp1 = pass['time_pp1'];
		var time_pp2 = pass['time_pp2'];
		var time_pp3 = pass['time_pp3'];
		var time_pp4 = pass['time_pp4'];
// copied the following code from the static.php file so need to reallign some variables
$(document).ready(function(){
	
	
		var inde = dex;
		
		
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
					//localStorage.setItem('stu_name',s_name);
					localStorage.setItem('problem_id',problem);
					localStorage.setItem('index',inde);
					localStorage.setItem('static_flag',static_f);
					localStorage.setItem('pin',pin);
			
			
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
					
					localStorage.setItem('title',arr2.title);
					localStorage.setItem('stu_name',s_name);
					localStorage.setItem('problem_id',problem);
					localStorage.setItem('index',inde);
				
			// redirect the browser to the problem file
			
		// alert (statusFlag);
		//alert (openup);
		var openup2 =  openup.replace(/\s/g, "%");
		//alert(openup2);
		//window.location.href="uploads/"+openup;
			//	 $('#putpblm').load("uploads/"+openup2);
		
		
		$('#openProblem').on('click',function(event){
		event.preventDefault();
		window.open("uploads/"+openup,"_blank");
		});
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



 */
</script>



</body>
</html>