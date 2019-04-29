<?php
require_once "pdo.php";
session_start();

$pp1=$_SESSION['pp1'];
$pp2=$_SESSION['pp2'];
$pp3=$_SESSION['pp3'];
$pp4=$_SESSION['pp4'];






	

	
?>	
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRProblems</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
<header>
<h2>There are Preliminaries assigned for this problem.</h2>
<h3>Please select one.</h3>
</header>	
	 <div class="wrapper">
       
        <form  method="post">
			

			
			<?php 
			
			
				// if the preproblem value is 0 it is not assigned, 1 is assigned and uncompleted and 2 is assigned and completed
				$pp1checked = ($pp1==2 ? 'checked' : '');
				//echo ($pp1checked);
				$pp2checked = ($pp2==2?'checked':'');
				$pp3checked = ($pp3==2?'checked':'');
				$pp4checked = ($pp4==2?'checked':'');
				
				if($pp1!=0){
					echo('<p><input type="checkbox"  name="guess" id = "pp1box" '.$pp1checked.' </p>'.'<a href="QRGuesser.php">Preliminary Estimates</a>');	
				}
				if($pp2!=0){
					echo('<p><input type="checkbox" name="qonq" id = "pp2box" '.$pp2checked.' </p>'.'<a href="QRPlanning.php">Planning Questions</a>');	
				}
				
				if($pp3!=0){
					echo('<p><input type="checkbox" name="MC" id = "pp3box" '.$pp3checked.' </p>'.'Preliminary Multiple Choice');	
				}
				
				if($pp4!=0){
					echo('<p><input type="checkbox" name="Supp" id = "pp4box" '.$pp4checked.' </p>'.'Preliminary Supplemental');	
				}
				
				
			?>
			
			
			<script>			
				$(document).ready(function() {
					
					$('#pp1box').prop("disabled", true);
					$('#pp2box').prop("disabled", true);
					$('#pp3box').prop("disabled", true);
					$('#pp4box').prop("disabled", true);
					
					
				});
			
			</script>
        </form>
    </div>    
 
	
		
</body>
</html>
