<?php
require_once "pdo.php";
session_start();

 $file_name=$_SESSION['file_name'];
 $pblm_num=$_SESSION['success'];
 $game_prob_flag=$_SESSION['game_prob_flag'];
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
<h2>Quick Response Problems</h2>
</header>

 
 
<?php
 echo '<p><font size = 5>'.$pblm_num.'</font></p>';
 echo "<br>"; 
 echo "<hr>";
	echo '<font size=5 color=red><p>'."Click below to download the template for this problem".'</p></font>'; 
	if ($game_prob_flag==0) {
		echo '<a href="downloads/QR Template v500C.docx" download="'.$file_name. '">'.$file_name.'.docx </a>';
	}else {
		echo '<a href="downloads/QR Game Template v500C.docx" download="'.$file_name. '">'.$file_name.'.docx </a>';
}
	echo '<font size=5 color=black><p>'."You may want to create a directory for it using the problem number".'</p></font>';
	echo "<hr>";
	  echo "<br>"; 
    echo 'The latest QR Solver excel macro enabled template is below - you may have to enable macros to use it.';
	echo "<br>";
	echo "<br>";
    echo '<a href="downloads/QRP solver A500C.xltm"> QRP solver A500C.xltm </a>';
	echo "<br>";
	echo "<hr>";

?>
<p> </p>
<a href="QRPRepo.php">Finished or Cancel</a>
</body>
</html>
