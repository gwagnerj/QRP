<?php
 session_start();
  Require_once "pdo.php";
 
 if (isset($_POST['effectiveness']) and isset($_POST['difficulty']) and isset($_POST['performance'])){
	
	$effectiveness = $_POST['effectiveness'];
	$difficulty = $_POST['difficulty'];
	$performance = $_POST['performance'];
	
	
	print $effectiveness;
	print $difficulty;
	print $performance;

	
 } else {
 
	print ('All values must be entered');
 
 
 }
 

?>

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
<p><font color = 'blue' size='4'> Please rate honestly </font></p>
<form method="POST">
	<table><tr><td>Effectiveness: &nbsp &nbsp </td><td> not effective
	<input type="radio" name="effectiveness" value = 1 id = "one" size= 20  >
	<input type="radio" name="effectiveness" value = 2 id = "two" size= 20  >
	<input type="radio" name="effectiveness" value = 3 id = "three" size= 20  >
	<input type="radio" name="effectiveness" value = 4 id = "four" size= 20  >
	<input type="radio" name="effectiveness" value = 5 id = "five" size= 20  >
	very effective</td></tr><tr>
	
	<td>Difficulty:  &nbsp &nbsp </td><td> easy
	<input type="radio" name="difficulty" value = 1  id = "one" size= 20  >
	<input type="radio" name="difficulty" value = 2 id = "two" size= 20  >
	<input type="radio" name="difficulty" value = 3 id = "three" size= 20  >
	<input type="radio" name="difficulty" value = 4 id = "four" size= 20  >
	<input type="radio" name="difficulty" value = 5 id = "five" size= 20  >
	very difficult</td></tr><tr>
	
	<td>Your Performance: &nbsp &nbsp </td><td> bad
	<input type="radio" name="performance" value = 1 id = "one" size= 20  >
	<input type="radio" name="performance" value = 2 id = "two" size= 20  >
	<input type="radio" name="performance" value = 3 id = "three" size= 20  >
	<input type="radio" name="performance" value = 4 id = "four" size= 20  >
	<input type="radio" name="performance" value = 5 id = "five" size= 20  >
	great</td></tr></table>
	



<p><br></p>
 
 <hr>
<p><b><font Color="red">When Finished:</font></b></p>
  <b><input type="submit" value="Get rtn Code" style = "width: 30%; background-color:yellow "></b>
 <p><br> </p>
 <hr>
</form>


</main>



<footer>
<!--<p>This is the footer</p> -->
</footer>
</body>
</html>