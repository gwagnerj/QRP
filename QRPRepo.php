<?php
require_once "pdo.php";
session_start();
?>
 <!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRP Repo</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 

<style type="text/css">
body {
   margin: 0;
   overflow: hidden;
}
#iframediv{
	position:relative;
	overflow:hidden;
	padding-top: 56.25%
}
#iframe1 {
    position:absolute;
	align:bottom;
    left: 0px;
    width: 100%;
    top: 0px;
    height: 100%;
}
</style>



</head>

<body>
<header>
<h2>Quick Response Problems</h2>
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

$preview="Null";
//if they request the file then set the $preview variable to the name of the file
	if (isset($_POST['preview']) ){
		$preview='uploads/'.htmlentities($_POST['preview']);
	}
	if (isset($_POST['soln_preview']) ){
			$preview='uploads/'.htmlentities($_POST['soln_preview']);
		}


echo('<table border="1">'."\n");
	echo("</td><td>");
	echo('<b>Problem Num</b>');
	echo("</td><td>");
	echo('<b>Contributor Name</b>');
    echo("</td><td>");
    echo('<b>Contributor Email</b>');
	 echo("</td><td>");
	 echo('<b>University</b>');
    echo("</td><td>");
    echo('<b>Pblm Title</b>');
    echo("</td><td>");
	echo('<b>Status</b>');
    echo("</td><td>");
	echo('<b>Game?</b>');
    echo("</td><td>");
	echo('<b>Orig Author</b>');
    echo("</td><td>");
	 echo('<b>Functions</b>');
	   echo("</td><td>");
	 echo('<b>Base-Case</b>');
    echo("</td><td>");
	 echo('<b>Soln</b>');
	echo("</td></tr>\n");
$qstmnt="SELECT Problem.problem_id AS problem_id,Users.first AS name,Users.email as email,Problem.title as title,Problem.status as status, Problem.soln_pblm as soln_pblm,Problem.game_prob_flag as game_prob_flag, Problem.nm_author as nm_author,Problem.docxfilenm as docxfilenm,Problem.infilenm as infilenm,Problem.pdffilenm as pdffilenm, Users.university as s_name
FROM Problem LEFT JOIN Users ON Problem.users_id=Users.users_id;";
$stmt = $pdo->query($qstmnt);
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr><td>";
	echo(htmlentities($row['problem_id']));
    echo("</td><td>");	
	echo(htmlentities($row['name']));
    echo("</td><td>");
    echo(htmlentities($row['email']));
	echo("</td><td>");  
	echo(htmlentities($row['s_name']));
    echo("</td><td>");
    echo(htmlentities($row['title']));
    echo("</td><td>");
	echo($row['status']);
    echo("</td><td>");
	echo(htmlentities($row['game_prob_flag']));
    echo("</td><td>");
	echo(htmlentities($row['nm_author']));
    
    echo("</td><td>");
	if(isset($_SESSION['username'])){
    echo('<a href="editpblm.php?problem_id='.$row['problem_id'].'">Edit</a> / ');
    echo('<a href="deletepblm.php?problem_id='.$row['problem_id'].'">Del</a> / ');
	}
	echo('<a href="downloadpblm.php?problem_id='.$row['problem_id'].'">Download</a>');
	  echo("</td><td>");
	echo('<form action = "QRPRepo.php" method = "POST" > <input type = "hidden" name = "preview" value ="'.$row['pdffilenm'].'"><input type = "submit" value ="PreView"></form>');
   	  echo("</td><td>");
	echo('<form action = "QRPRepo.php" method = "POST" > <input type = "hidden" name = "soln_preview" value ="'.$row['soln_pblm'].'"><input type = "submit" value ="PreView"></form>');

   echo("</td></tr>\n");
	
}
//echo ('"'.$preview.'"');
?>
</table>
<p></p>
<!-- <p><a href="add.php">Add New Manual</a></P> -->
<!--<a href="addPblm.php">Add Data and Pblm Files</a> -->
<p></p>
<?php
if (isset($_SESSION['username'])){
	echo '<a href="requestPblmNum.php"><b>Request New Problem Number</b></a>';
} else {
	   echo '<p>log in to contribute, edit, or delete problems <a href="login.php">Login here</a>.</p>';
}
?>

<!-- <object data=<?php// echo('"'.$preveiw.'"'); ?> 
type= "application/pdf" width="100%" Height="50%"> -->

<?php 

if($preview !== "uploads/" and $preview !== "Null") {
	echo ('<div id ="iframediv"><iframe id = "iframe1" src="'.$preview.'"'.'></iframe></div>');

	//echo ('</iframe>');
}
?>
<!-- </object> -->
</body>
</html>