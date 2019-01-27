<?php
 session_start();
  Require_once "pdo.php";
 
 $score = $_SESSION['score'];
$score = 100;
// echo($score);
 
 if ((isset($_POST['effectiveness']) and isset($_POST['difficulty']) and isset($_POST['confidence'])and isset($_POST['t_take1'])and isset($_POST['t_take2'])and isset($_POST['t_b4due']))
	 or (isset($_POST['not_perfect']) and isset($_POST['t_take1_np']) and isset($_POST['t_b4due_np']) and isset($_POST['confidence_np']))
 )
 {
	if (isset($_POST['effectiveness']) and isset($_POST['difficulty']) and isset($_POST['confidence'])and isset($_POST['t_take1'])and isset($_POST['t_take2'])and isset($_POST['t_b4due']))
	{
	
			
			$effectiveness = $_POST['effectiveness'];
			$difficulty = $_POST['difficulty'];
			$confidence = $_POST['confidence'];
			// print $effectiveness;
			// print $difficulty;
			//print $performance;

		// put the values in the data base

		  // Get the correct effectiveness and difficulty  rating from database add 1 to it and put it back
		  if (isset($_SESSION['problem_id'])){ // get the correct value for the problem number parameter
				
				 $sql = "SELECT * FROM Problem WHERE problem_id = :problem_id";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(':problem_id' => $_SESSION['problem_id']));
				$data = $stmt -> fetch();	
					
				$nm_diff = 'diff_stu_'.$_POST['difficulty'];
				$nm_eff = 'eff_stu_'.$_POST['effectiveness'];	
					
				$val_diff = $data[$nm_diff]+1;	
				$val_eff = $data[$nm_eff]+1;
				
				
					$sql = "UPDATE Problem SET $nm_diff = :nmdiff WHERE problem_id = :pblm_num";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':nmdiff' => $val_diff,
							':pblm_num' => $_SESSION['problem_id']));
							
					$sql = "UPDATE Problem SET $nm_eff = :nmeff WHERE problem_id = :pblm_num";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
							':nmeff' => $val_eff,
							':pblm_num' => $_SESSION['problem_id']));		
							
					

			// change the headers to the rtnCode.php

					header( 'Location: rtnCode.php' ) ;
					return;		
	}	
}

 } else {
 
	print ('All catagories must be entered');
 
 
 }
 

?>

<link rel="icon" type="image/png" href="McKetta.png" />  
 <title>QRHomework</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"> </script>






<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />
<meta Charset = "utf-8">
<title>QRProblems</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
</head>

<body>
<header>
<!--<h1>this is an application that Gets the rating of the problem from the student</h1>-->
</header>
<main>
<h3>The problems attempted to give you practice with and allow discovery of certain concepts</h3>
<p><font color = 'blue' size='4'> Please answer honestly </font></p>




	<input type="hidden" name="score_val" id = "score" size= 20  value="<?php echo($score);?>" >




<form method="POST">

<!-- Give them additional questions if they did not get a good score  -->
<!--<p> <div id = 'score' type = 'input' name = 'score_val' value = "<?php echo($stu_name);?>">  </div> This is where it would go </p> -->




<div id = "not_perfect"> 
If I had to do it all over again, I would have: (responses are annonmous - selct all that apply) <br> 
	&nbsp &nbsp <input type="checkbox" name="not_perfect" value = 1 id = "one" size= 20  >&nbsp &nbsp started earlier <br>
	&nbsp &nbsp <input type="checkbox" name="not_perfect" value = 2 id = "two" size= 20  > &nbsp &nbsp sought help after an honest attempt at both my problem and the base-case  <br>
	&nbsp &nbsp <input type="checkbox" name="not_perfect" value = 3 id = "three" size= 20  >&nbsp &nbsp  spent more time in understanding what the problem was asking <br>
	&nbsp &nbsp <input type="checkbox" name="not_perfect" value = 4 id = "four" size= 20  >&nbsp &nbsp   used different tools to solve the problem <br>
	&nbsp &nbsp <input type="checkbox" name="not_perfect" value = 5 id = "five" size= 20  >&nbsp &nbsp  been more systematic in my problem solving approach <br>
	&nbsp &nbsp <input type="checkbox" name="not_perfect" value = 6 id = "six" size= 20  >&nbsp &nbsp  solved a simpler problem before attempting this one <br>
	&nbsp &nbsp <input type="checkbox" name="not_perfect" value = 7 id = "seven" size= 20  >&nbsp &nbsp  nothing. I did everything I could <br>
<p></p>
</div>

<div id = "time_take1"> 
Estimate how long you spent on just this problem <br> 
	&nbsp &nbsp <input type="radio" name="t_take1" value = 1 id = "one" size= 20  >&nbsp &nbsp less than 5 min <br>
	&nbsp &nbsp <input type="radio" name="t_take1" value = 2 id = "two" size= 20  > &nbsp &nbsp 5 - 15 min  <br>
	&nbsp &nbsp <input type="radio" name="t_take1" value = 3 id = "three" size= 20  >&nbsp &nbsp  15 - 30 min <br>
	&nbsp &nbsp <input type="radio" name="t_take1" value = 4 id = "four" size= 20  >&nbsp &nbsp   30 - 60 min <br>
	&nbsp &nbsp <input type="radio" name="t_take1" value = 5 id = "five" size= 20  >&nbsp &nbsp  1 - 2 hrs <br>
	&nbsp &nbsp <input type="radio" name="t_take1" value = 6 id = "six" size= 20  >&nbsp &nbsp  2 - 8 hrs <br>
	&nbsp &nbsp <input type="radio" name="t_take1" value = 7 id = "seven" size= 20  >&nbsp &nbsp  over 8 hrs <br>
<p></p>
</div>

<div id = "time_take1_np"> 
Estimate how long you spent on just this problem <br> 
	&nbsp &nbsp <input type="radio" name="t_take1_np" value = 1 id = "one" size= 20  >&nbsp &nbsp less than 5 min <br>
	&nbsp &nbsp <input type="radio" name="t_take1_np" value = 2 id = "two" size= 20  > &nbsp &nbsp 5 - 15 min  <br>
	&nbsp &nbsp <input type="radio" name="t_take1_np" value = 3 id = "three" size= 20  >&nbsp &nbsp  15 - 30 min <br>
	&nbsp &nbsp <input type="radio" name="t_take1_np" value = 4 id = "four" size= 20  >&nbsp &nbsp   30 - 60 min <br>
	&nbsp &nbsp <input type="radio" name="t_take1_np" value = 5 id = "five" size= 20  >&nbsp &nbsp  1 - 2 hrs <br>
	&nbsp &nbsp <input type="radio" name="t_take1_np" value = 6 id = "six" size= 20  >&nbsp &nbsp  2 - 8 hrs <br>
	&nbsp &nbsp <input type="radio" name="t_take1_np" value = 7 id = "seven" size= 20  >&nbsp &nbsp  over 8 hrs <br>
<p></p>
</div>

<div id = "time_take2"> 
Estimate how long it would now take you to solve a very similar problem <br> 
	&nbsp &nbsp <input type="radio" name="t_take2" value = 1 id = "one" size= 20  >&nbsp &nbsp less than 5 min <br>
	&nbsp &nbsp <input type="radio" name="t_take2" value = 2 id = "two" size= 20  > &nbsp &nbsp 5 - 15 min  <br>
	&nbsp &nbsp <input type="radio" name="t_take2" value = 3 id = "three" size= 20  >&nbsp &nbsp  15 - 30 min <br>
	&nbsp &nbsp <input type="radio" name="t_take2" value = 4 id = "four" size= 20  >&nbsp &nbsp   30 - 60 min <br>
	&nbsp &nbsp <input type="radio" name="t_take2" value = 5 id = "five" size= 20  >&nbsp &nbsp  1 - 2 hrs <br>
	&nbsp &nbsp <input type="radio" name="t_take2" value = 6 id = "six" size= 20  >&nbsp &nbsp  2 - 8 hrs <br>
	&nbsp &nbsp <input type="radio" name="t_take2" value = 7 id = "seven" size= 20  >&nbsp &nbsp  over 8 hrs <br>
<p></p>
</div>

<div id = "time_start"> 
How long before it was due did you first look at the problem <br> 
	&nbsp &nbsp <input type="radio" name="t_b4due" value = 1 id = "one" size= 20  >&nbsp &nbsp less than 1 hr <br>
	&nbsp &nbsp <input type="radio" name="t_b4due" value = 2 id = "two" size= 20  > &nbsp &nbsp 1 - 5 hrs  <br>
	&nbsp &nbsp <input type="radio" name="t_b4due" value = 3 id = "three" size= 20  >&nbsp &nbsp  5 - 12 hrs <br>
	&nbsp &nbsp <input type="radio" name="t_b4due" value = 4 id = "four" size= 20  >&nbsp &nbsp   12 - 24 hrs <br>
	&nbsp &nbsp <input type="radio" name="t_b4due" value = 5 id = "five" size= 20  >&nbsp &nbsp  1 - 2 days <br>
	&nbsp &nbsp <input type="radio" name="t_b4due" value = 6 id = "six" size= 20  >&nbsp &nbsp  2 - 7 days <br>
	&nbsp &nbsp <input type="radio" name="t_b4due" value = 7 id = "seven" size= 20  >&nbsp &nbsp  over 1 week <br>
<p></p>
</div>


<div id = "time_start_np"> 
How long before it was due did you first look at the problem <br> 
	&nbsp &nbsp <input type="radio" name="t_b4due_np" value = 1 id = "one" size= 20  >&nbsp &nbsp less than 1 hr <br>
	&nbsp &nbsp <input type="radio" name="t_b4due_np" value = 2 id = "two" size= 20  > &nbsp &nbsp 1 - 5 hrs  <br>
	&nbsp &nbsp <input type="radio" name="t_b4due_np" value = 3 id = "three" size= 20  >&nbsp &nbsp  5 - 12 hrs <br>
	&nbsp &nbsp <input type="radio" name="t_b4due_np" value = 4 id = "four" size= 20  >&nbsp &nbsp   12 - 24 hrs <br>
	&nbsp &nbsp <input type="radio" name="t_b4due_np" value = 5 id = "five" size= 20  >&nbsp &nbsp  1 - 2 days <br>
	&nbsp &nbsp <input type="radio" name="t_b4due_np" value = 6 id = "six" size= 20  >&nbsp &nbsp  2 - 7 days <br>
	&nbsp &nbsp <input type="radio" name="t_b4due_np" value = 7 id = "seven" size= 20  >&nbsp &nbsp  over 1 week <br>
<p></p>
</div>

<div id = "eff_div">
	<table><tr><td>Effectiveness: This problem caused me to think or provided effective reinforcment practice: &nbsp &nbsp </td><td> not effective
	<input type="radio" name="effectiveness" value = 1 id = "one" size= 20  >
	<input type="radio" name="effectiveness" value = 2 id = "two" size= 20  >
	<input type="radio" name="effectiveness" value = 3 id = "three" size= 20  >
	<input type="radio" name="effectiveness" value = 4 id = "four" size= 20  >
	<input type="radio" name="effectiveness" value = 5 id = "five" size= 20  >
	very effective</td></tr><tr></tr><tr></tr><tr></tr><tr>
<p></p>
</div>	
	
<div id = "diff_div">	
	<td>Difficulty: Took a long time or involved multiple complex concepts:  &nbsp &nbsp </td><td> easy
	<input type="radio" name="difficulty" value = 1  id = "one" size= 20  >
	<input type="radio" name="difficulty" value = 2 id = "two" size= 20  >
	<input type="radio" name="difficulty" value = 3 id = "three" size= 20  >
	<input type="radio" name="difficulty" value = 4 id = "four" size= 20  >
	<input type="radio" name="difficulty" value = 5 id = "five" size= 20  >
	very difficult</td></tr><tr></tr><tr></tr><tr></tr><tr>
<p></p>
</div>

<div id = "conf_div">		
	<td>How confident are you in your understanding of the concepts underlying this problem: &nbsp &nbsp </td><td> not confident
	<input type="radio" name="confidence" value = 1 id = "one" size= 20  >
	<input type="radio" name="confidence" value = 2 id = "two" size= 20  >
	<input type="radio" name="confidence" value = 3 id = "three" size= 20  >
	<input type="radio" name="confidence" value = 4 id = "four" size= 20  >
	<input type="radio" name="confidence" value = 5 id = "five" size= 20  >
	very confident</td></tr></table>
	
	<p></p>	
	</div>
	
	
<div id = "conf_div_np">		
	<td>How confident are you in your understanding of the concepts underlying this problem: &nbsp &nbsp </td><td> not confident
	<input type="radio" name="confidence_np" value = 1 id = "one" size= 20  >
	<input type="radio" name="confidence_np" value = 2 id = "two" size= 20  >
	<input type="radio" name="confidence_np" value = 3 id = "three" size= 20  >
	<input type="radio" name="confidence_np" value = 4 id = "four" size= 20  >
	<input type="radio" name="confidence_np" value = 5 id = "five" size= 20  >
	very confident</td></tr></table>
	
	<p></p>	
</div>

	
	<div id = "too_long_div"> 
If I had to do it all over again I would have:  <br> 
	&nbsp &nbsp <input type="checkbox" name="too_long" value = 1 id = "one" size= 20  >&nbsp &nbsp looked at the problem ealier so I could have come up with a more efficient solution strategy <br>
	&nbsp &nbsp <input type="checkbox" name="too_long" value = 2 id = "two" size= 20  > &nbsp &nbsp sought help earlier <br>
	&nbsp &nbsp <input type="checkbox" name="too_long" value = 3 id = "three" size= 20  >&nbsp &nbsp  spent more time in understanding what the problem was asking <br>
	&nbsp &nbsp <input type="checkbox" name="too_long" value = 4 id = "four" size= 20  >&nbsp &nbsp   used different tools to solve the problemd <br>
	&nbsp &nbsp <input type="checkbox" name="too_long" value = 5 id = "five" size= 20  >&nbsp &nbsp  been more systematic in my problem solving approach <br>
	&nbsp &nbsp <input type="checkbox" name="too_long" value = 6 id = "six" size= 20  >&nbsp &nbsp  solved a simpler problem before attempting this one <br>
	&nbsp &nbsp <input type="checkbox" name="too_long" value = 7 id = "seven" size= 20  >&nbsp &nbsp  nothing. I did everything I could <br>
</div>
	
	

 
 <hr>
<p><b><font Color="red">When Finished:</font></b></p>
  <b><input type="submit" value="Get rtn Code" style = "width: 30%; background-color:yellow "></b>
 <p><br> </p>
 <hr>
</form>
<script>






$(document).ready(function(){
	
	var score = $('input#score').val();
	
	if (score == 100){
		
	console.log(score);
	$('#not_perfect').hide();
	$('#time_start_np').hide();
	$('#time_start').show();
	$('#too_long_div').hide();
	$('#conf_div_np').hide();
	$('#conf_div').show();
	$('#time_take1_np').hide();
	$('#time_take1').show();
	
	} else {
		
	$('#time_take2').hide();
	$('#time_start').hide();
	$('#time_start_np').show();
	$('#eff_div').hide();
	$('#diff-div').hide();
	$('#too_long_div').hide();
	$('#conf_div_np').show();
	$('#conf_div').hide();
	$('#time_take1_np').show();
	$('#time_take1').hide();
	
	
	}
	
	});

</script>

</main>



<footer>
<!--<p>This is the footer</p> -->
</footer>
</body>
</html>