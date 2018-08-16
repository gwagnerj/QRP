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
<title>QRP Repo</title>
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
    width: 100%;    
	}
	
</style>
						
	<!-- THis is from Simple jQuery Dropdown Table Filter Plugin - ddtf.js    
	
						<script src="//code.jquery.com/jquery-1.11.3.min.js"></script> -->
						<script src="ddtf.js"></script> 
	
</head>

<body>
<header>
<h2>Quick Response Problems</h2>
<p> <font size = '1' color = 'Blue'>(Try "ctrl +" or "ctrl -" to resize)</font>
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

		//find out what kind of security level they have if they are logged in 
		if(isset($_SESSION['username'])){
			$username=$_SESSION['username'];
		$sql = " SELECT * FROM Users where username = :username";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
				':username' => $username));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$security = $row['security'];
				$users_id=$row['users_id'];
		}
	//THis is from Simple jQuery Dropdown Table Filter Plugin - ddtf.js    -->	

//<script> $('table').ddTableFilter(); 


if (isset($_SESSION['username'])){
	echo '<a href="requestPblmNum.php"><b>Request New Problem Number</b></a>';
	echo '<br>';
	echo '<br>';
	echo '<hr>';
	echo '<a href="login.php"><b>logout</b></a>';
} else {
	   echo '<hr>';
	   echo '<p><h4>log in to contribute, edit, or delete problems <a href="login.php">Login here</a>.</h4></p>';
}


echo ('<table id="table_format" class = "a" border="1" >'."\n");
	
//echo ('<div class="w3-container">');	

	


	echo("</td><th>");
	echo('<b>Num</b>');
	echo("</th><th>");
	echo('<b>Game?</b>');
    echo("</th><th>");
	echo('<b>Contrib</b>');
    echo("</th><th>");
	echo('<b>Ref</b>');
    echo("</th><th>");
    echo('<b>Discip</b>');
	 echo("</th><th>");
	 echo('<b>course</b>');
    echo("</th><th>");
	echo('<b>Concept 1</b>');
    echo("</th><th>");
	echo('<b>Concept 2</b>');
    echo("</th><th>");
    echo('<b>Title</b>');
    echo("</th><th>");
	echo('<b>Status</b>');
    echo("</th><th>");
	echo('<b>Author</b>');
    echo("</th><th>");
	 echo('<b>Functions</b>');
	   echo("</th><th>");
	 echo('<b>Base-Case</b>');
    echo("</th><th>");
	 echo('<b>Soln</b>');
	echo("</th></tr>\n");
$qstmnt="SELECT Problem.problem_id AS problem_id,Users.username AS username, Users.first AS name,Problem.subject as subject,Problem.course as course,Problem.primary_concept as p_concept,Problem.secondary_concept as s_concept,Problem.title as title,Problem.specif_ref as ref,Problem.status as status, Problem.soln_pblm as soln_pblm,Problem.game_prob_flag as game_prob_flag, Problem.nm_author as nm_author,Problem.docxfilenm as docxfilenm,Problem.infilenm as infilenm,Problem.pdffilenm as pdffilenm, Users.university as s_name
FROM Problem LEFT JOIN Users ON Problem.users_id=Users.users_id;";
$stmt = $pdo->query($qstmnt);
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr><td>";
	echo(htmlentities($row['problem_id']));
    echo("</td><td>");	
	echo(htmlentities($row['game_prob_flag']));
    echo("</td><td>");
	echo(htmlentities($row['name']));
    echo("</td><td>");
	echo(htmlentities($row['ref']));
    echo("</td><td>");
    echo(htmlentities($row['subject']));
	echo("</td><td>");  
	echo(htmlentities($row['course']));
    echo("</td><td>");
	echo(htmlentities($row['p_concept']));
    echo("</td><td>");
	echo(htmlentities($row['s_concept']));
    echo("</td><td>");
    echo(htmlentities($row['title']));
    echo("</td><td>");
	echo($row['status']);
    echo("</td><td>");

	echo(htmlentities($row['nm_author']));
    
    echo("</td><td>");
	if($row['username']==$username or $security=='admin'){
		echo('<a href="editpblm.php?problem_id='.$row['problem_id'].'">Edit</a> / ');
		echo('<a href="deletepblm.php?problem_id='.$row['problem_id'].'">Del</a> / ');
	}
	echo('<a href="downloadpblm.php?problem_id='.$row['problem_id'].'">Download</a>');
	  echo("</td><td>");
	echo('<form action = "QRPRepo.php" method = "POST" > <input type = "hidden" name = "preview" value ="'.$row['pdffilenm'].'"><input type = "submit" value ="PreView"></form>');
   	  echo("</td><td>");
	if(isset($_SESSION['username'])){
		echo('<form action = "QRPRepo.php" method = "POST" > <input type = "hidden" name = "soln_preview" value ="'.$row['soln_pblm'].'"><input type = "submit" value ="PreView"></form>');
	}
   echo("</td></tr>\n");
echo ('</div>');	
}
//echo ('"'.$preview.'"');
?>
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="ddtf.js"></script>
<script>
	jQuery('#table_format').ddTableFilter();
</script>

</table>
<p></p>
<!-- <p><a href="add.php">Add New Manual</a></P> -->
<!--<a href="addPblm.php">Add Data and Pblm Files</a> -->
<p></p>


<!-- <object data=<?php// echo('"'.$preveiw.'"'); ?> 
type= "application/pdf" width="100%" Height="50%"> -->

<?php 

if($preview !== "uploads/" and $preview !== "Null") {
	echo ('<div id ="iframediv"><iframe id = "iframe1" src="'.$preview.'"'.'></iframe></div>');

}
?>
<!-- </object> -->
</body>
</html>