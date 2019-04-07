<?php
require_once "pdo.php";
session_start();

?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<meta Charset = "utf-8">
<title>Find Instructor ID</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 

<style>
div {
    /*background-color: #eee;*/
    width: 100%;
    height: 100%;
    border: 1px dotted black;
    overflow: auto;
}
</style>
<style type="text/css">
body {
   margin: 0;
   overflow: hidden;
}


#iframediv{
	position:relative;
	overflow:hidden;
	padding-top: 60%
}
#iframe1 {
    position:absolute;
	align:bottom;
    left: 0px;
    width: 100%;
    top: 0px;
    height: 100%;
}
table.a {
    table-layout: fixed;
    width: 60%;    
	}
	
	 

	
	
</style>


</head>

<body>
<header>
<h2>Quick Response Problems</h2>
<p> <font size = '1' color = 'Blue'>(Try "ctrl +" or "ctrl -" to resize)</font>
</header>

<?php
	$sql = "SELECT * FROM Users" ;
	$stmt = $pdo->query($sql);
	
	
	echo ('<table id="table_format" class = "a" border="1" >'."\n");
		
	 echo("<thead>");

	echo("</td><th>");
	echo('First');
	echo("</th><th>");
	echo('Last');
    echo("</th><th>");
	echo('University');
	 echo("</th><th>");
	echo('Instructor ID');
	echo("</th></tr>\n");
	 echo("</thead>");
	
	
	
	
	while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
		 echo("<tbody>");
		 echo "<tr><td>";
		echo(htmlentities($row['first']));
		echo("</td><td>");	
		echo(htmlentities($row['last']));
		echo("</td><td>");					
		echo(htmlentities($row['university']));
		echo("</td><td>");	
		echo(htmlentities($row['users_id']));
		  echo("</td></tr>\n");
		  
	}
		echo("</tbody>");
		echo("</table>");
		echo ('</div>');	

		echo ('<p></p>');
		echo ('<p></p>');
		if($_SESSION['checker']!=1){
				echo ('<font color=#003399 >  &nbsp; &nbsp; &nbsp;  <a href="QRhomework.php"><b>Return</b></a></font></p>');
		} else {
				echo ('<font color=#003399 >  &nbsp; &nbsp; &nbsp;  <a href="QRPindex.php"><b>Return</b></a></font></p>');	
			
		}
?>

