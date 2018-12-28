<?php
 session_start();
  
 

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
<h3>Problems attempt to give you practice with and allow discovery of certain concepts</h3>
<p><font color = 'blue' size='2'> Please rate honestly </font></p>
<form method="POST">
	<p><font color=#003399>Effectiveness (5 = very effective 1 = not effective)</font><input type="text" name="effectiveness" id = "stu_name_id" size= 20  value="<?php echo($stu_name);?>" ></p>
	<p><font color=#003399>Problem Number: </font><input type="number" name="problem_id" id="prob_id" size=3 value=<?php echo($problem_id);?> min="1" Max = "100000" required></p>
	<p><font color=#003399>PIN: </font><input type="number" name="index" id="index_id" size=3 value=<?php echo($index);?> min="2" Max="200" ></p>

	<p><input type = "submit" value="Submit" id="submit_id" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>
	</form>


<p><br></p>

<a href="rtnCode.php"><b> Get Rtn Code</b></a>

</main>



<footer>
<!--<p>This is the footer</p> -->
</footer>
</body>
</html>