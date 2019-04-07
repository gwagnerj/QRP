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
	 .widget-7 { width:150px; } 
	  .widget-8 { width:150px; } 
	  .widget-9 { width:150px; } 
	  .widget-5 { width:150px; } 
	  .widget-11 { width:150px; } 
	  .widget-12 { width:150px; } 
	 
	 
.column-filter-widget { float:left; padding: 20px; border : none; width:200px;}
.column-filter-widget select { display: block; }
.column-filter-widgets a.filter-term { display: block; text-decoration: none; padding-left: 10px; font-size: 90%; }
.column-filter-widgets a.filter-term:hover { text-decoration: line-through !important; }
.column-filter-widget-selected-terms { clear:left; }
	
	
	
</style>
						
	<!-- THis is from Simple jQuery Dropdown Table Filter Plugin - ddtf.js    -->
	
						<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
				<!--		<script src="ddtf.js"></script> -->
						
						
						<link rel="stylesheet" type="text/css" href="DataTables-1.10.18/css/jquery.dataTables.css"/> 
						<script type="text/javascript" src="DataTables-1.10.18/js/jquery.dataTables.js"></script>
						
				<!--		<link rel="stylesheet" type="text/css" href="DataTables-1.10.18/extras/css/ColumnFilterWidgets.css"/> 
				-->
						
						<script type="text/javascript" charset="utf-8" src="DataTables-1.10.18/extras/js/ColumnFilterWidgets.js"></script>
						
						
		<!-- THis is from DataTable jquery plugin 	 		
						
						<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  
						<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
		 -->		
	
			<!-- THis is from sparklines jquery plugin   -->	

			<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.js"></script>
	
					<!--	<script src="sparklines.js"></script>   -->
	
	
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


if (isset($_SESSION['username'])){
	echo '<a href="requestPblmNum.php"><b>Request New Problem Number</b></a>';
	echo '<br>';
	echo '<hr>';
	echo '<a href="login.php"><b>logout</b></a>';
	echo ' <p> </p> ';
	echo ' <b> Filter Criteria: </b>';
} else {
	   echo '<hr>';
	   echo '<p><h4>log in to contribute, edit, or delete problems <a href="login.php">Login here</a>.</h4></p>';
	   echo '<br>';
}
	



echo ('<table id="table_format" class = "a" border="1" >'."\n");
	

	
	 echo("<thead>");

	echo("</td><th>");
	echo('Num');
	echo("</th><th>");
	echo('eff');
    echo("</th><th>");
	echo('diff');
	 echo("</th><th>");
	echo('t_b4due');
    echo("</th><th>");
	echo('t_spent');
    echo("</th><th>");
	echo('Contrib');
    echo("</th><th>");
	echo('Ref');
    echo("</th><th>");
    echo('Discip');
	 echo("</th><th>");
	 echo('Course');
    echo("</th><th>");
	echo('Concepts ');
    
    echo("</th><th>");
    echo('Title');
    echo("</th><th>");
	echo('Status');
    echo("</th><th>");
	echo('Author');
    echo("</th><th>");
	 echo('Functions');
	   echo("</th><th>");
	 echo('Base-Case');
    echo("</th><th>");
	 echo('Soln');
	echo("</th></tr>\n");
	 echo("</thead>");
	 
	  echo("<tbody>");
	//
	
	
	
	// add the effectiveness and rating stuff here so I can either display it or compute the average and display that along with the total ratings
	
$qstmnt=("SELECT Problem.problem_id AS problem_id,Users.username AS username, Users.first AS name,Problem.subject as subject,Problem.course as course,Problem.primary_concept as p_concept,Users.users_id as users_id,
Problem.secondary_concept as s_concept,Problem.title as title,Problem.specif_ref as ref,Problem.status as status, Problem.soln_pblm as soln_pblm,Problem.game_prob_flag as game_prob_flag, 
Problem.nm_author as nm_author,Problem.docxfilenm as docxfilenm,Problem.infilenm as infilenm,Problem.pdffilenm as pdffilenm,
Problem.eff_stu_1 as eff_stu_1,Problem.eff_stu_2 as eff_stu_2,Problem.eff_stu_3 as eff_stu_3,Problem.eff_stu_4 as eff_stu_4,Problem.eff_stu_5 as eff_stu_5,
Problem.diff_stu_1 as diff_stu_1,Problem.diff_stu_2 as diff_stu_2,Problem.diff_stu_3 as diff_stu_3,Problem.diff_stu_4 as diff_stu_4,Problem.diff_stu_5 as diff_stu_5,
Problem.t_take1_1 as t_take1_1,Problem.t_take1_2 as t_take1_2,Problem.t_take1_3 as t_take1_3,Problem.t_take1_4 as t_take1_4,Problem.t_take1_5 as t_take1_5,Problem.t_take1_6 as t_take1_6,Problem.t_take1_7 as t_take1_7,
Problem.t_take1_np_1 as t_take1_np_1,Problem.t_take1_np_2 as t_take1_np_2,Problem.t_take1_np_3 as t_take1_np_3,Problem.t_take1_np_4 as t_take1_np_4,Problem.t_take1_np_5 as t_take1_np_5, Problem.t_take1_np_6 as t_take1_np_6,Problem.t_take1_np_7 as t_take1_np_7,
Problem.t_take2_1 as t_take2_1,Problem.t_take2_2 as t_take2_2,Problem.t_take2_3 as t_take2_3,Problem.t_take2_4 as t_take2_4,Problem.t_take2_5 as t_take2_5,Problem.t_take2_6 as t_take2_6,Problem.t_take2_7 as t_take2_7,
Problem.t_b4due_1 as t_b4due_1,Problem.t_b4due_2 as t_b4due_2,Problem.t_b4due_3 as t_b4due_3,Problem.t_b4due_4 as t_b4due_4,Problem.t_b4due_5 as t_b4due_5,Problem.t_b4due_6 as t_b4due_6,Problem.t_b4due_7 as t_b4due_7,
Problem.t_b4due_np_1 as t_b4due_np_1,Problem.t_b4due_np_2 as t_b4due_np_2,Problem.t_b4due_np_3 as t_b4due_np_3,Problem.t_b4due_np_4 as t_b4due_np_4,Problem.t_b4due_np_5 as t_b4due_np_5, Problem.t_b4due_np_6 as t_b4due_np_6, Problem.t_b4due_np_7 as t_b4due_np_7,
Problem.confidence_1 as confidence_1,Problem.confidence_2 as confidence_2,Problem.confidence_3 as confidence_3,Problem.confidence_4 as confidence_4,Problem.confidence_5 as confidence_5,
Problem.confidence_np_1 as confidence_np_1,Problem.confidence_np_2 as confidence_np_2,Problem.confidence_np_3 as confidence_np_3,Problem.confidence_np_4 as confidence_np_4,Problem.confidence_np_5 as confidence_np_5,
 Users.university as s_name

FROM Problem LEFT JOIN Users ON Problem.users_id=Users.users_id ;");





//echo (FROM Problem LEFT JOIN Users ON Problem.users_id=Users.users_id;";);    SELECT  Assign.prob_num as active_prob_num , FROM Assign ");
$stmt = $pdo->query($qstmnt);
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
   
     echo "<tr><td>";
	
	// echo ('<div class = "probnum">');
	
	echo(htmlentities($row['problem_id']));
	// echo ('</div>');
	
    echo("</td><td>");	
	
	if(!isset($row["eff_stu_1"])){$eff_stu_1 = 0;} else {$eff_stu_1 = $row["eff_stu_1"];}
	if(!isset($row["eff_stu_2"])){$eff_stu_2 = 0;} else {$eff_stu_2 = $row["eff_stu_2"];}
	if(!isset($row["eff_stu_3"])){$eff_stu_3 = 0;} else {$eff_stu_3 = $row["eff_stu_3"];}
	if(!isset($row["eff_stu_4"])){$eff_stu_4 = 0;} else {$eff_stu_4 = $row["eff_stu_4"];}
	if(!isset($row["eff_stu_5"])){$eff_stu_5 = 0;} else {$eff_stu_5 = $row["eff_stu_5"];}
	
	if(!isset($row["confidence_np_1"])){$confidence_np_1 = 0;} else {$confidence_np_1 = $row["confidence_np_1"];}
	if(!isset($row["confidence_np_2"])){$confidence_np_2 = 0;} else {$confidence_np_2 = $row["confidence_np_2"];}
	if(!isset($row["confidence_np_3"])){$confidence_np_3 = 0;} else {$confidence_np_3 = $row["confidence_np_3"];}
	if(!isset($row["confidence_np_4"])){$confidence_np_4 = 0;} else {$confidence_np_4 = $row["confidence_np_4"];}
	if(!isset($row["confidence_np_5"])){$confidence_np_5 = 0;} else {$confidence_np_5 = $row["confidence_np_5"];}
	
	$confidence_np_tot = $confidence_np_1+$confidence_np_2+$confidence_np_3+$confidence_np_4+$confidence_np_5;
	
	$eff_stu_tot = $eff_stu_1+$eff_stu_2+$eff_stu_3+$eff_stu_4+$eff_stu_5;
	
	if($eff_stu_tot==0) {
		
	echo(' ');	
	} else {
		
		$tot_eff_score =  $eff_stu_1*1+$eff_stu_2*2+$eff_stu_3*3+$eff_stu_4*4+$eff_stu_5*5;
		$ave_eff = round($tot_eff_score/$eff_stu_tot*10)/10;
		
		echo('<font size="2"> ave = '.$ave_eff);
		
		echo('<span class="inlinebar1">'.$eff_stu_1.", ".$eff_stu_2.", ".$eff_stu_3.", ".$eff_stu_4.", ".$eff_stu_5.'</span>');	
				echo('<br><font size="1"> &nbsp;&nbsp; eff'."</font>");
			echo('<font size="1"> &nbsp;&nbsp; n ='.$eff_stu_tot."</font>");
	}
	
  
    echo("</td><td>");
	
	if(!isset($row["diff_stu_1"])){$diff_stu_1 = 0;} else {$diff_stu_1 = $row["diff_stu_1"];}
	if(!isset($row["diff_stu_2"])){$diff_stu_2 = 0;} else {$diff_stu_2 = $row["diff_stu_2"];}
	if(!isset($row["diff_stu_3"])){$diff_stu_3 = 0;} else {$diff_stu_3 = $row["diff_stu_3"];}
	if(!isset($row["diff_stu_4"])){$diff_stu_4 = 0;} else {$diff_stu_4 = $row["diff_stu_4"];}
	if(!isset($row["diff_stu_5"])){$diff_stu_5 = 0;} else {$diff_stu_5 = $row["diff_stu_5"];}
	
	$confidence_np_tot = $confidence_np_1+$confidence_np_2+$confidence_np_3+$confidence_np_4+$confidence_np_5;
	
	$diff_stu_tot = $diff_stu_1+$diff_stu_2+$diff_stu_3+$diff_stu_4+$diff_stu_5;
	$tot_attempt = $confidence_np_tot+$diff_stu_tot;
	if($tot_attempt!=0){
		$percent_np = round($confidence_np_tot/($confidence_np_tot+$diff_stu_tot)*100);
	} else {
		$percent_np= '';
	}
	if($diff_stu_tot==0) {
		
		echo(' ');	
	} else {
		
			$tot_diff_score =  $diff_stu_1*1+$diff_stu_2*2+$diff_stu_3*3+$diff_stu_4*4+$diff_stu_5*5;
			$ave_diff = round($tot_diff_score/$diff_stu_tot*10)/10;
			echo('<font size="2"> ave = '.$ave_diff);
			echo('<span class="inlinebar2">'.$diff_stu_1.", ".$diff_stu_2.", ".$diff_stu_3.", ".$diff_stu_4.", ".$diff_stu_5.'</span>');
			echo('<br><font size="1"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; diff'."</font>");
			
			
			if ($percent_np!=0){
					echo('<br><font size="1"> n_tot ='.$tot_attempt."</font>");
					echo('<br><font size="1"> < 100 ='.$percent_np.' %'."</font>");
			}
	}
     echo("</td><td>");
	 	if(!isset($row["t_b4due_1"])){$t_b4due_1 = 0;} else {$t_b4due_1 = $row["t_b4due_1"];}
		if(!isset($row["t_b4due_2"])){$t_b4due_2 = 0;} else {$t_b4due_2 = $row["t_b4due_2"];}
		if(!isset($row["t_b4due_3"])){$t_b4due_3 = 0;} else {$t_b4due_3 = $row["t_b4due_3"];}
		if(!isset($row["t_b4due_4"])){$t_b4due_4 = 0;} else {$t_b4due_4 = $row["t_b4due_4"];}
		if(!isset($row["t_b4due_5"])){$t_b4due_5 = 0;} else {$t_b4due_5 = $row["t_b4due_5"];}
	 	if(!isset($row["t_b4due_6"])){$t_b4due_6 = 0;} else {$t_b4due_6 = $row["t_b4due_6"];}
		if(!isset($row["t_b4due_7"])){$t_b4due_7 = 0;} else {$t_b4due_7 = $row["t_b4due_7"];}
	 
		if(!isset($row["t_b4due_np_1"])){$t_b4due_np_1 = 0;} else {$t_b4due_np_1 = $row["t_b4due_np_1"];}
		if(!isset($row["t_b4due_np_2"])){$t_b4due_np_2 = 0;} else {$t_b4due_np_2 = $row["t_b4due_np_2"];}
		if(!isset($row["t_b4due_np_3"])){$t_b4due_np_3 = 0;} else {$t_b4due_np_3 = $row["t_b4due_np_3"];}
		if(!isset($row["t_b4due_np_4"])){$t_b4due_np_4 = 0;} else {$t_b4due_np_4 = $row["t_b4due_np_4"];}
		if(!isset($row["t_b4due_np_5"])){$t_b4due_np_5 = 0;} else {$t_b4due_np_5 = $row["t_b4due_np_5"];}
	 	if(!isset($row["t_b4due_np_6"])){$t_b4due_np_6 = 0;} else {$t_b4due_np_6 = $row["t_b4due_np_6"];}
		if(!isset($row["t_b4due_np_7"])){$t_b4due_np_7 = 0;} else {$t_b4due_np_7 = $row["t_b4due_np_7"];}
	 
		$t_b4due_tot = $t_b4due_1+$t_b4due_2+$t_b4due_3+$t_b4due_4+$t_b4due_5+$t_b4due_6+$t_b4due_7;
		$t_b4due_np_tot = $t_b4due_np_1+$t_b4due_np_2+$t_b4due_np_3+$t_b4due_np_4+$t_b4due_np_5+$t_b4due_np_6+$t_b4due_np_7;
		$tot_tb4due=$t_b4due_tot+$t_b4due_np_tot;
	 
		if ($tot_tb4due ==0){
			echo(' ');	
		} else {
	 
			$max_cat=max($t_b4due_1+$t_b4due_np_1,$t_b4due_2+$t_b4due_np_2,$t_b4due_3+$t_b4due_np_3,$t_b4due_4+$t_b4due_np_4,$t_b4due_5+$t_b4due_np_5,$t_b4due_6+$t_b4due_np_6,$t_b4due_7+$t_b4due_np_7);
			
			echo('<font size="1">mode = ');
			if($max_cat == $t_b4due_1+$t_b4due_np_1){echo("< 1h");}
			if($max_cat == $t_b4due_2+$t_b4due_np_2){echo(" 1-5h");}
			if($max_cat == $t_b4due_3+$t_b4due_np_3){echo(" 5-12h");}
			if($max_cat == $t_b4due_4+$t_b4due_np_4){echo(" 12-24h");}
			if($max_cat == $t_b4due_5+$t_b4due_np_5){echo(" 1-2d");}
			if($max_cat == $t_b4due_6+$t_b4due_np_6){echo(" 2-7d");}
			if($max_cat == $t_b4due_7+$t_b4due_np_7){echo(" >1wk");}
	 
			 echo('<span class="inlinebar2">'.$t_b4due_7.": ".$t_b4due_np_7." ,".$t_b4due_6.": ".$t_b4due_np_6." ,".$t_b4due_5.": ".$t_b4due_np_5.", ".$t_b4due_4.": ".$t_b4due_np_4." , ".$t_b4due_3.": ".$t_b4due_np_3.", ".$t_b4due_2.": ".$t_b4due_np_2." ,".$t_b4due_1.": ".$t_b4due_np_1.'</span>');
			echo('<br><font size="1">  early &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; late'."</font>");
			echo('<br><font size="1"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; t_b4due'."</font>");
			echo('</font>');
		}
	 
	
	echo("</td><td>");
	
		if(!isset($row["t_take1_1"])){$t_take1_1 = 0;} else {$t_take1_1 = $row["t_take1_1"];}
		if(!isset($row["t_take1_2"])){$t_take1_2 = 0;} else {$t_take1_2 = $row["t_take1_2"];}
		if(!isset($row["t_take1_3"])){$t_take1_3 = 0;} else {$t_take1_3 = $row["t_take1_3"];}
		if(!isset($row["t_take1_4"])){$t_take1_4 = 0;} else {$t_take1_4 = $row["t_take1_4"];}
		if(!isset($row["t_take1_5"])){$t_take1_5 = 0;} else {$t_take1_5 = $row["t_take1_5"];}
	 	if(!isset($row["t_take1_6"])){$t_take1_6 = 0;} else {$t_take1_6 = $row["t_take1_6"];}
	
	 
		if(!isset($row["t_take1_np_1"])){$t_take1_np_1 = 0;} else {$t_take1_np_1 = $row["t_take1_np_1"];}
		if(!isset($row["t_take1_np_2"])){$t_take1_np_2 = 0;} else {$t_take1_np_2 = $row["t_take1_np_2"];}
		if(!isset($row["t_take1_np_3"])){$t_take1_np_3 = 0;} else {$t_take1_np_3 = $row["t_take1_np_3"];}
		if(!isset($row["t_take1_np_4"])){$t_take1_np_4 = 0;} else {$t_take1_np_4 = $row["t_take1_np_4"];}
		if(!isset($row["t_take1_np_5"])){$t_take1_np_5 = 0;} else {$t_take1_np_5 = $row["t_take1_np_5"];}
	 	if(!isset($row["t_take1_np_6"])){$t_take1_np_6 = 0;} else {$t_take1_np_6 = $row["t_take1_np_6"];}
		
	 
		$t_take1_tot = $t_take1_1+$t_take1_2+$t_take1_3+$t_take1_4+$t_take1_5+$t_take1_6;
		$t_take1_np_tot = $t_take1_np_1+$t_take1_np_2+$t_take1_np_3+$t_take1_np_4+$t_take1_np_5+$t_take1_np_6;
		$tot_take1=$t_take1_tot+$t_take1_np_tot;
	 
		if ($tot_take1 ==0){
			echo(' ');	
		} else {
			
			$max_cat=max($t_take1_1+$t_take1_np_1,$t_take1_2+$t_take1_np_2,$t_take1_3+$t_take1_np_3,$t_take1_4+$t_take1_np_4,$t_take1_5+$t_take1_np_5,$t_take1_6+$t_take1_np_6);
			
			echo('<font size="1">mode = ');
			if($max_cat == $t_take1_1+$t_take1_np_1){echo("< 5m");}
			if($max_cat == $t_take1_2+$t_take1_np_2){echo(" 5-15m");}
			if($max_cat == $t_take1_3+$t_take1_np_3){echo(" 15-30m");}
			if($max_cat == $t_take1_4+$t_take1_np_4){echo(" 30-60m");}
			if($max_cat == $t_take1_5+$t_take1_np_5){echo(" 1-3h");}
			if($max_cat == $t_take1_6+$t_take1_np_6){echo(" >3h");}
	 
			 echo('<span class="inlinebar2">'.$t_take1_1.": ".$t_take1_np_1." ,".$t_take1_2.": ".$t_take1_np_2.", ".$t_take1_3.": ".$t_take1_np_3." , ".$t_take1_4.": ".$t_take1_np_4.", ".$t_take1_5.": ".$t_take1_np_5." ,".$t_take1_6.": ".$t_take1_np_6.'</span>');
			echo('<br><font size="1"><5min &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>3h'."</font>");
			echo('<br><font size="1"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; t spent'."</font>");
			echo('</font>');
		}
	
    echo("</td><td>");
	
	
	
	echo(htmlentities($row['name']));
    echo("</td><td>");
	echo(htmlentities($row['ref']));
    echo("</td><td>");
    echo(htmlentities($row['subject']));
	echo("</td><td>");  
	echo(htmlentities($row['course']));
    echo("</td><td>");
	
	$sec_des="";
	if(strlen($row['s_concept'])!=0){$sec_des="<br>2)&nbsp;";}
	
	echo("1)&nbsp;".htmlentities($row['p_concept']).$sec_des.htmlentities($row['s_concept']));
    echo("</td><td>");
	
    echo(htmlentities($row['title']));
    echo("</td><td>");
	// if we have over 7 students that have completed it successfully we should change the status to circulated if it is not already
	if($eff_stu_tot > 6 && $row['status'] != 'Circulated'){
		$status_update = 'Circulated';
		$sql = "UPDATE Problem SET status = :status WHERE problem_id =:problem_id";
		$stmt4 = $pdo->prepare($sql);
			$stmt4->execute(array(
			'status' => $status_update,
			'problem_id' => $row['problem_id']
			));
	} else {
		$status_update = '';
	}
	
	// if it is active for this user print active for the status
			$asstmnt = "SELECT Assign.assign_num AS assign_ass_num 
			FROM Assign 
			WHERE (Assign.prob_num =". $row['problem_id']." AND Assign.iid=".$users_id.");";
				
			$stmt2 = $pdo->query($asstmnt);
			 $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
			if($row2 == false){
				if($status_update =='Circulated'){
					echo('Circulated');
				} else {
				echo($row['status']);
				}
			} else {
				echo('Asn '.$row2["assign_ass_num"].'<br> <span style = "color: red;" > Active </span>');
			}
			// test to see if it is being used by other people and display inuse
			$usestmnt = "SELECT Assign.instr_last AS instr_last_nm 
			FROM Assign 
			WHERE (Assign.prob_num =". $row['problem_id']." AND Assign.iid <>".$users_id.");";
				
				$stmt5 = $pdo->query($usestmnt);
				$i=1;
				while ( $row5 = $stmt5->fetch(PDO::FETCH_ASSOC) ) {
						if($i==1){
							echo('<br><font size=1> in use by:');
							$i=0;
						}
					echo("<br><font size=1>".$row5['instr_last_nm']);
				}
			
			
	
    echo("</td><td>");

	echo(htmlentities($row['nm_author']));
    
    echo("</td><td>");
	if($row['username']==$username or $security=='admin'){
		echo('<a href="editpblm.php?problem_id='.$row['problem_id'].'">Edit</a> / ');
		echo('<a href="deletepblm.php?problem_id='.$row['problem_id'].'">Del</a> / ');
		echo('<a href="suspendpblm.php?problem_id='.$row['problem_id'].'">Susp-unSus</a> / ');
	}
		echo('<a href="QRactivatePblm.php?problem_id='.$row['problem_id'].'&users_id='.$users_id.'">Act-deAct</a>');
	//echo('<a href="downloadpblm.php?problem_id='.$row['problem_id'].'">Download</a>');
	  echo("</td><td>");
	echo('<form action = "getBC.php" method = "POST" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "hidden" name = "index" value = "1" ><input type = "submit" value ="PreView"></form>');
   	  echo("</td><td>");
	if(isset($_SESSION['username'])){
		echo('<form action = "QRPRepo.php" method = "POST" > <input type = "hidden" name = "soln_preview" value ="'.$row['soln_pblm'].'"><input type = "submit" value ="PreView"></form>');
	}
   echo("</td></tr>\n");
     
}

	echo("</tbody>");
	 echo("</table>");
	echo ('</div>');	



//echo ('"'.$preview.'"');
?>

<script>
	
	$(".inlinebar1").sparkline("html",{type: "bar", height: "50", barWidth: "10", resize: true, barSpacing: "5", barColor: "#7ace4c"});
	$(".inlinebar2").sparkline("html",{type: "bar", height: "50", barWidth: "10", resize: true, barSpacing: "5", barColor: "orange"});
	
	
	// $(document).ready( function () {
    //$('#table_format').DataTable(); 
	
	
	
	$(document).ready( function () {
    $('#table_format').DataTable({"sDom": 'W<"clear">lfrtip',
		"oColumnFilterWidgets": {
		"aiExclude": [ 0,1,2,3,4,6,10,13,14,15 ] }});
	

	// jQuery('#table_format').ddTableFilter();
	} );
	
	
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