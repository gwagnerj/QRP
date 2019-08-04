<?php
require_once "pdo.php";

session_unset();
session_start();
$_SESSION['checker']=1;

?>

<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>checker Bypass</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
</head>

<body>
<header>
<h1>Quick Response Problems</h1>
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

<form action = "QRChecker.php" method = "GET" autocomplete="off">
	<p><font color=#003399>Problem Number: </font><input type="number" name="problem_id" required min = "1" size=3 value="<?php echo (htmlentities($p_num))?> "  ></p>
	<p><font color=#003399>PIN: </font><input type="number"  name="pin" required min = "1" max = "10000" size=3 value="<?php echo (htmlentities($index))?> "  ></p>
		<p><font color=#003399></font><input type="hidden"  name="iid" required min = "1" max = "10000" size=3 value="1"  ></p>

	<!-- <p><font color=#003399>Grading Scheme Number: </font><input type="text" name="gs_num" size=3 value="<?php echo (htmlentities($gs_num))?>"  ></p> -->

	<p><input type = "submit" value="Submit" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>
	</form>

</body>
</html>



