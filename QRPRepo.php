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
						
	<!-- THis is from Simple jQuery Dropdown Table Filter Plugin - ddtf.js    -->
	
						<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
						<script src="ddtf.js"></script> 
						
						
		<!-- THis is from DataTable jquery plugin   -->				
						
						<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  
						<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
		
	
			<!-- THis is from sparklines jquery plugin   -->	

			<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.js"></script>
	
					<!--	<script src="sparklines.js"></script>   -->
	
	
</head>

<body>
<header>
<h2>Quick Response Problems</h2>
<p> <font size = '1' color = 'Blue'>(Try "ctrl +" or "ctrl -" to resize)</font>
</header>



<div id="sparklinedash">
    <span class="bar"></span>
  </div>
  
  
  
  
  
  
  
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

echo('<br> bulshit');
echo('<script>const data = [0, 5, 6, 10, 9, 12,]
			const config = {type: "bar",height: "50", barWidth: "10",resize: true,barSpacing: "5", barColor: "#7ace4c"}
		$("#sparklinedash").sparkline(data, config)</script>');
		
		

echo ('<table id="table_format" class = "a" border="1" >'."\n");
	
//echo ('<div class="w3-container">');	

	
	 echo("<thead>");
//echo(breakit)
	echo("</td><th>");
	echo('<b>Num</b>');
	echo("</th><th>");
	echo('<b>eff</b>');
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
	 echo("</thead>");
	 
	  echo("<tbody>");
	
	// add the effectiveness and rating stuff here so I can either display it or compute the average and display that along with the total ratings
	
$qstmnt="SELECT Problem.problem_id AS problem_id,Users.username AS username, Users.first AS name,Problem.subject as subject,Problem.course as course,Problem.primary_concept as p_concept,Problem.secondary_concept as s_concept,Problem.title as title,Problem.specif_ref as ref,Problem.status as status, Problem.soln_pblm as soln_pblm,Problem.game_prob_flag as game_prob_flag, Problem.nm_author as nm_author,Problem.docxfilenm as docxfilenm,Problem.infilenm as infilenm,Problem.pdffilenm as pdffilenm, Users.university as s_name
FROM Problem LEFT JOIN Users ON Problem.users_id=Users.users_id;";
$stmt = $pdo->query($qstmnt);
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr><td>";
	
	echo ('<div class = "probnum">');
	
	echo(htmlentities($row['problem_id']));
	echo ('</div>');
	
    echo("</td><td>");	
	
	// echo ('<script>var nm = "#sparklinedash"+$(#probnum).$row["problem_id"]</script>');
	echo('<script> var data2 = [20, 50, 60, 80, 50]
			 var config2 = {type: "bar",height: "50", barWidth: "10",resize: true,barSpacing: "5", barColor: "#7ace4c"}
			// var nm = "sparklinedash"+$(".probnum");
			// console.log(nm);
		$(".sparklinedash2").sparkline(data2, config2)</script>');
	echo ('<div class = "sparklinedash2">
    <span class="bar"></span>
  </div>');	
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
		echo('<a href="suspendpblm.php?problem_id='.$row['problem_id'].'">Susp-unSus</a> / ');
	}
	echo('<a href="downloadpblm.php?problem_id='.$row['problem_id'].'">Download</a>');
	  echo("</td><td>");
	echo('<form action = "getBC.php" method = "POST" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "hidden" name = "index" value = "1" ><input type = "submit" value ="PreView"></form>');
   	  echo("</td><td>");
	if(isset($_SESSION['username'])){
		echo('<form action = "QRPRepo.php" method = "POST" > <input type = "hidden" name = "soln_preview" value ="'.$row['soln_pblm'].'"><input type = "submit" value ="PreView"></form>');
	}
   echo("</td></tr>\n");
     echo("</tbody>");
echo ('</div>');	
}
//echo ('"'.$preview.'"');
?>
<!-- <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="ddtf.js"></script>  -->
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