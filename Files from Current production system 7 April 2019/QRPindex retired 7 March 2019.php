<?php
//require_once "pdo.php";

session_unset();
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

$p_num = "";
$index = "";
$gs_num = "";
?>

<form action = "QRChecker.php" method = "GET" autocomplete="off">
	<p><font color=#003399>Problem Number: </font><input type="text" name="problem_id" size=3 value="<?php echo (htmlentities($p_num))?>"  ></p>
	<p><font color=#003399>PIN: </font><input type="text" name="dex_num" size=3 value="<?php echo (htmlentities($index))?>"  ></p>
	<!-- <p><font color=#003399>Grading Scheme Number: </font><input type="text" name="gs_num" size=3 value="<?php echo (htmlentities($gs_num))?>"  ></p> -->

	<p><input type = "submit" value="Submit" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>
	</form>

</body>
</html>
<!--<form action="rtnCode.php" method="POST">
 <hr>
<p><b><font Color="red">When Finished:</font></b></p>
  <!--<input type="hidden" name="score" value=<?php echo ($score) ?> /> 
  <?php $_SESSION['score'] = $PScore; $_SESSION['index'] = $index; $_SESSION['count'] = $count; ?>
 <b><input type="submit" value="Get rtn Code" style = "width: 30%; background-color:yellow "></b>
 <p><br> </p>
 <hr>
</form> -->


