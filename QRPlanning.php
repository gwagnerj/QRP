<?php
 session_start();
 
Require_once "pdo.php";


	
	
	// if the student fills out all of the entries then change the pp1 to 2 in the activity table and make a datestamp for time_pp1 (just let the html check the input) and go back to the controller
	if(isset($_POST['submit']))	{
		
	
		$sql = "UPDATE Activity 
				SET   pp2 = 2, time_pp2 = now(), 
					est_what_concepts = :est_what_concepts, est_most_diff = :est_most_diff, est_do_first = :est_do_first, 
					est_how_long = :est_how_long, est_start = :est_start, est_conf = :est_conf
				WHERE activity_id = :activity_id";
		$stmt = $pdo->prepare($sql);
		$stmt -> execute(array(
					':activity_id' => $_SESSION['activity_id'],
					':est_what_concepts' => $_POST['est_what_concepts'],
					':est_most_diff' => $_POST['est_most_diff'],
					':est_do_first' => $_POST['est_do_first'],
					':est_how_long' => $_POST['est_how_long'],
					':est_start' => $_POST['est_start'],
					':est_conf' => $_POST['est_conf']
				));
			
			header("Location: QRcontroller.php");
			die();
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
	<link rel="stylesheet" type="text/css" href="DataTables-1.10.18/css/jquery.dataTables.css"/> 
</head>

<body>
<header>
<h1>QRHomework Preliminaries Estimates</h1>
</header>
<main>


<!-- <p> Problem Number: <?php echo ($_SESSION['problem_id']) ?> </p> -->
<?php
$_POST['problem_id']=$_SESSION['problem_id'];
$_POST['index']=$_SESSION['dex'];
//include("getBC.php");

//echo('<form action = "getBC.php" method = "POST" target = "output_frame"> <input type = "hidden" name = "problem_id" value = "'.$_SESSION['problem_id'].'"><input type = "hidden" name = "index" value = "1" ><input type = "submit" value ="PreView Basecase in Window"></form>');

//echo('<form action = "staticAUTO.php" method = "POST" target = "output_frame"> <input type = "hidden" name = "problem_id" value = "'.$_SESSION['problem_id'].'"><input type = "hidden" name = "index" value = "'.$_SESSION['dex'].'" ><input type = "submit" value ="PreView Problem in Window"></form>');


echo('<form action = "staticAUTO.php" method = "POST" target = "output_frame" id = "pblmInsert"> 
<input type = "hidden" name = "problem_id" value = "'.$_SESSION['problem_id'].'">
<input type = "hidden" name = "index" value = "'.$_SESSION['dex'].'" >
<input type = "hidden" name = "pin" value = "'.$_SESSION['pin'].'" >
</form>');

// open the problem in a new tab

echo('<p><form action = "staticAUTO.php" method = "POST" target = "_blank"> 
<input type = "hidden" name = "problem_id" value = "'.$_SESSION['problem_id'].'">
<input type = "hidden" name = "index" value = "'.$_SESSION['dex'].'" >
<input type = "submit" value ="PreView Problem in a Separate Browser Tab">
</form></p>');

?>

<iframe name = "output_frame" src = "" id = "output_frame" width = "100%" Height = "300" scrolling = "yes"> </iframe>
<form autocomplete="off" method="POST" >
<!-- <p>Problem Number: <input type="text" name="problem_number" ></p> -->
<!-- <p> Please put in your index number </p> 
<p><font color=#003399>PIN: </font><input type="text" name="dex_num" size=3 value="<?php //echo (htmlentities($_SESSION['dex']))?>"  ></p> -->
<p> <strong> Please answer the following about the above Problem - then select "Submit" </strong></p>
<div id='putpblm'></div>

<form autocomplete="off" method="POST">

<div id = "Concepts_div"> 
	<br>
	What engineering, scientific or mathematical concepts are covered in the problem  <br>
		&nbsp &nbsp <textarea name="est_what_concepts" id = "concepts" cols = "100" rows = "2" pattern=".{10,}" placeholder = "required" required title="10 characters minimum" maxlength = "500" ></textarea>
	</div>		
<div id = "diff_div"> 
	<br>
	What part of the problem do you think will give you the most difficulty? why?  <br>
		&nbsp &nbsp <textarea name="est_most_diff" id = "difficult" cols = "100" rows = "2" pattern=".{10,}" placeholder = "required" required title="10 characters minimum" maxlength = "500" ></textarea>
	</div>	
<div id = "dofirst_div"> 
	<br>
	When starting to solve this problem, what will you do first?  <br>
		&nbsp &nbsp <textarea name="est_do_first" id = "dofirst" cols = "100" rows = "2" placeholder = "required" required title="10 characters minimum"  maxlength = "500" ></textarea>
	</div>			

<div id = "external_data"> 
Does this problem require external data? <br> 
	&nbsp &nbsp <input type="radio" name="yesno" value = 1 id = "yes" size= 20 required >&nbsp &nbsp Yes <br>
	&nbsp &nbsp <input type="radio" name="yesno" value = 2 id = "no" size= 20  >&nbsp &nbsp No <br>
<p></p>
</div>		
	
	<div id = "time_estimate"> 
Estimate how long it will take you to solve this problem <br> 
	&nbsp &nbsp <input type="radio" name="est_how_long" value = 1 id = "one" size= 20  required>&nbsp &nbsp less than 5 min <br>
	&nbsp &nbsp <input type="radio" name="est_how_long" value = 2 id = "two" size= 20  >&nbsp &nbsp 5 - 15 min  <br>
	&nbsp &nbsp <input type="radio" name="est_how_long" value = 3 id = "three" size= 20  >&nbsp &nbsp  15 - 30 min <br>
	&nbsp &nbsp <input type="radio" name="est_how_long" value = 4 id = "four" size= 20  >&nbsp &nbsp   30 - 60 min <br>
	&nbsp &nbsp <input type="radio" name="est_how_long" value = 5 id = "five" size= 20  >&nbsp &nbsp  1 - 3 hrs <br>
	&nbsp &nbsp <input type="radio" name="est_how_long" value = 7 id = "six" size= 20  >&nbsp &nbsp  over 3 hrs <br>
<p></p>
</div>

<div id = "time_start"> 
How long before it is due do you plan to try to solve the problem <br> 
	&nbsp &nbsp <input type="radio" name="est_start" value = 1 id = "one" size= 20  required>&nbsp &nbsp less than 1 hr <br>
	&nbsp &nbsp <input type="radio" name="est_start" value = 2 id = "two" size= 20  >&nbsp &nbsp 1 - 5 hrs  <br>
	&nbsp &nbsp <input type="radio" name="est_start" value = 3 id = "three" size= 20  >&nbsp &nbsp  5 - 12 hrs <br>
	&nbsp &nbsp <input type="radio" name="est_start" value = 4 id = "four" size= 20  >&nbsp &nbsp   12 - 24 hrs <br>
	&nbsp &nbsp <input type="radio" name="est_start" value = 5 id = "five" size= 20  >&nbsp &nbsp  1 - 2 days <br>
	&nbsp &nbsp <input type="radio" name="est_start" value = 6 id = "six" size= 20  >&nbsp &nbsp  2 - 7 days <br>
	&nbsp &nbsp <input type="radio" name="est_start" value = 7 id = "seven" size= 20  >&nbsp &nbsp  over 1 week <br>
<p></p>
</div>	

<div id = "conf_div">
<table>		
	<td>How confident are you that you will be able to solve this problem by the time it is due: </td> <tr> <td> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp not confident
	<input type="radio" name="est_conf" value = 1 id = "one" size= 20  required>
	<input type="radio" name="est_conf" value = 2 id = "two" size= 20  >
	<input type="radio" name="est_conf" value = 3 id = "three" size= 20  >
	<input type="radio" name="est_conf" value = 4 id = "four" size= 20  >
	<input type="radio" name="est_conf" value = 5 id = "five" size= 20  >
	very confident</td></tr><tr></tr>
		<p></p>	
</table>
	</div>	
	

	

	


<!--<p>Grading Scheme: <input type="text" name="grade_scheme" ></p> -->
<p><input type = "submit" value="Submit" name = "submit" id="submitBtn" size="10" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp <b> <font size="4" color="Navy"></font></b></p>


</form>


</main>
<script>
	$(document).ready(function() {
	// fills out iframe with problem statement				
		$('#pblmInsert').submit();
			
	});
</script>



</body>
</html>