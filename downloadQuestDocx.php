<?php
require_once "pdo.php";
session_start();
$question_id = $_SESSION['question_id'];
 $file_name=$_SESSION['file_name'];
 $quest_num=$_SESSION['success'];
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
<h2>Quick Response Questions</h2>
</header>

 
 
<?php
 echo '<p><font size = 5>'.$quest_num.'</font></p>';

 echo "<br>"; 
 echo "<hr>";
	echo '<font size=5 color=red><p>'."Click below to download the template for this problem".'</p></font>'; 
	
		// echo '<a href="downloads/QR Template v500C.docx" download="'.$file_name. '">'.$file_name.'.docx </a>';
		echo '<a href="downloads/QR Template questionA.docx" download="'.$file_name. '">'.$file_name.'.docx </a>';

	echo '<font size=5 color=black><p>'."You may want to create a directory for it using the problem number.  After modifying the document, save the document as an html file using the save as options".'</p></font>';
	echo "<hr>";
	  echo "<br>"; 
      
echo '<a href="editquest.php?question_id='.$question_id.'">Link to upload the Question HTML file</a>';


?>
<p> </p>
<a href="QRPRepo.php">Finished or Cancel</a>
</body>
</html>
