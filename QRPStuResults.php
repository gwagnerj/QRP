<?php
require_once "pdo.php";
session_start();




$preview="Null";
//if they request the file then set the $preview variable to the name of the file
	if (isset($_GET['problem_id']) ){
		$problem_id = $_GET['problem_id'];
	} else {
		$_SESSION['error']= 'QRPStuResults could not get problem number';
		header( 'Location: QRPRepo.php' ) ;
		die();
	}
	
	if (isset($_SESSION['iid']) ){
		$iid = $_SESSION['iid'];

		echo ('Student Results for Problem Number '.$problem_id.' for instuctor ID '.$iid);
	} else {
		
		$_SESSION['error']= 'QRPStuResults could not get instructor ID';
		header( 'Location: QRPRepo.php' ) ;
		die();
	}

?>






 <!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<meta Charset = "utf-8">
<title>QRP Repo</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 

						
	
	
						<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
				<!--		<script src="ddtf.js"></script> -->
						
						
						<link rel="stylesheet" type="text/css" href="DataTables-1.10.18/css/jquery.dataTables.css"/> 
						<script type="text/javascript" src="DataTables-1.10.18/js/jquery.dataTables.js"></script>
						
				<!--		<link rel="stylesheet" type="text/css" href="DataTables-1.10.18/extras/css/ColumnFilterWidgets.css"/> 
				-->
						
						<script type="text/javascript" charset="utf-8" src="DataTables-1.10.18/extras/js/ColumnFilterWidgets.js"></script>
						
						
		
	
			<!-- THis is from sparklines jquery plugin   -->	

			<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.js"></script>
	
					<!--	<script src="sparklines.js"></script>   -->
	
	
</head>

<body>
<header>
<h2>QR Student Results for Problem</h2>
 <font size = '1' color = 'Blue'>(Try "ctrl +" or "ctrl -" to resize)</font>
</header>


  


<?php	
	$sql2="SELECT * FROM Problem WHERE problem_id = :problem_id";
	$stmt2 = $pdo->prepare($sql2);
	$stmt2->execute(array(
		':problem_id' => $problem_id
	));

$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

	echo ('<table id="table_format2" class = "a" border="1" >'."\n");
	
	 echo("<thead>");

	echo("</td><th>");
	echo('t_b4due');
	echo("</th><th>");
	
	echo('t-spent');
    echo("</th><th>");

	 echo('Tries to get 100% <br> All Time');
	 
	  echo("</th><th>");
	  echo('Wrong counts each part <br> All Time');
	 echo("</th>");
	
	 echo("</thead>");
	 
	  echo("<tbody>");
	//

	 echo "<tr><td>";
				if(!isset($row2["t_b4due_1"])){$t_b4due_1 = 0;} else {$t_b4due_1 = $row2["t_b4due_1"];}
				if(!isset($row2["t_b4due_2"])){$t_b4due_2 = 0;} else {$t_b4due_2 = $row2["t_b4due_2"];}
				if(!isset($row2["t_b4due_3"])){$t_b4due_3 = 0;} else {$t_b4due_3 = $row2["t_b4due_3"];}
				if(!isset($row2["t_b4due_4"])){$t_b4due_4 = 0;} else {$t_b4due_4 = $row2["t_b4due_4"];}
				if(!isset($row2["t_b4due_5"])){$t_b4due_5 = 0;} else {$t_b4due_5 = $row2["t_b4due_5"];}
				if(!isset($row2["t_b4due_6"])){$t_b4due_6 = 0;} else {$t_b4due_6 = $row2["t_b4due_6"];}
				if(!isset($row2["t_b4due_7"])){$t_b4due_7 = 0;} else {$t_b4due_7 = $row2["t_b4due_7"];}
			 
				if(!isset($row2["t_b4due_np_1"])){$t_b4due_np_1 = 0;} else {$t_b4due_np_1 = $row2["t_b4due_np_1"];}
				if(!isset($row2["t_b4due_np_2"])){$t_b4due_np_2 = 0;} else {$t_b4due_np_2 = $row2["t_b4due_np_2"];}
				if(!isset($row2["t_b4due_np_3"])){$t_b4due_np_3 = 0;} else {$t_b4due_np_3 = $row2["t_b4due_np_3"];}
				if(!isset($row2["t_b4due_np_4"])){$t_b4due_np_4 = 0;} else {$t_b4due_np_4 = $row2["t_b4due_np_4"];}
				if(!isset($row2["t_b4due_np_5"])){$t_b4due_np_5 = 0;} else {$t_b4due_np_5 = $row2["t_b4due_np_5"];}
				if(!isset($row2["t_b4due_np_6"])){$t_b4due_np_6 = 0;} else {$t_b4due_np_6 = $row2["t_b4due_np_6"];}
				if(!isset($row2["t_b4due_np_7"])){$t_b4due_np_7 = 0;} else {$t_b4due_np_7 = $row2["t_b4due_np_7"];}
			 
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
					echo('<br><font size="1"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; early &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; late'."</font>");
					echo('<br><font size="1"> &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; t_b4due'."</font>");
					echo('</font>');
				}
	
	echo("</td><td>");	
				if(!isset($row2["t_take1_1"])){$t_take1_1 = 0;} else {$t_take1_1 = $row2["t_take1_1"];}
				if(!isset($row2["t_take1_2"])){$t_take1_2 = 0;} else {$t_take1_2 = $row2["t_take1_2"];}
				if(!isset($row2["t_take1_3"])){$t_take1_3 = 0;} else {$t_take1_3 = $row2["t_take1_3"];}
				if(!isset($row2["t_take1_4"])){$t_take1_4 = 0;} else {$t_take1_4 = $row2["t_take1_4"];}
				if(!isset($row2["t_take1_5"])){$t_take1_5 = 0;} else {$t_take1_5 = $row2["t_take1_5"];}
				if(!isset($row2["t_take1_6"])){$t_take1_6 = 0;} else {$t_take1_6 = $row2["t_take1_6"];}
			
			 
				if(!isset($row2["t_take1_np_1"])){$t_take1_np_1 = 0;} else {$t_take1_np_1 = $row2["t_take1_np_1"];}
				if(!isset($row2["t_take1_np_2"])){$t_take1_np_2 = 0;} else {$t_take1_np_2 = $row2["t_take1_np_2"];}
				if(!isset($row2["t_take1_np_3"])){$t_take1_np_3 = 0;} else {$t_take1_np_3 = $row2["t_take1_np_3"];}
				if(!isset($row2["t_take1_np_4"])){$t_take1_np_4 = 0;} else {$t_take1_np_4 = $row2["t_take1_np_4"];}
				if(!isset($row2["t_take1_np_5"])){$t_take1_np_5 = 0;} else {$t_take1_np_5 = $row2["t_take1_np_5"];}
				if(!isset($row2["t_take1_np_6"])){$t_take1_np_6 = 0;} else {$t_take1_np_6 = $row2["t_take1_np_6"];}
				
			 
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
					echo('<br><font size="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<5min &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>3h'."</font>");
					echo('<br><font size="1"> &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; t spent'."</font>");
					echo('</font>');
				}
	echo("</td><td>");
				if(!isset($row2["num_try_1"])){$num_try_1 = 0;} else {$num_try_1 = $row2["num_try_1"];}
				if(!isset($row2["num_try_2"])){$num_try_2 = 0;} else {$num_try_2 = $row2["num_try_2"];}
				if(!isset($row2["num_try_3"])){$num_try_3 = 0;} else {$num_try_3 = $row2["num_try_3"];}
				if(!isset($row2["num_try_4"])){$num_try_4 = 0;} else {$num_try_4 = $row2["num_try_4"];}
				if(!isset($row2["num_try_5"])){$num_try_5 = 0;} else {$num_try_5 = $row2["num_try_5"];}
				if(!isset($row2["num_try_6"])){$num_try_6 = 0;} else {$num_try_6 = $row2["num_try_6"];}
			
			 
				$num_try_tot = $num_try_1+$num_try_2+$num_try_3+$num_try_4+$num_try_5+$num_try_6;
			 
				if ($num_try_tot ==0){
					echo(' ');	
				} else {
					
					$max_cat=max($num_try_1,$num_try_2,$num_try_3,$num_try_4,$num_try_5,$num_try_6);
					
					echo('<font size="1">mode = ');
					if($max_cat == $num_try_1){echo("1");}
					if($max_cat == $num_try_2){echo("2-4");}
					if($max_cat == $num_try_3){echo("5-10");}
					if($max_cat == $num_try_4){echo("11-20");}
					if($max_cat == $num_try_5){echo("21-40");}
					if($max_cat == $num_try_6){echo("over 41");}
			 
					 echo('<span class="inlinebar2">'.$num_try_1." ,".$num_try_2.", ".$num_try_3." , ".$num_try_4.", ".$num_try_5." ,".$num_try_6.'</span>');
					echo('<br><font size="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1 &nbsp;&nbsp;&nbsp; 2-4 &nbsp;&nbsp;5-10&nbsp;11-20&nbsp;21-40&nbsp;&nbsp; >41  '."</font>");
					echo('<br><font size="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; number of tries'."</font>");
					echo('</font>');
				}
	echo("</td><td>");
				
				if(!isset($row2["cumm_wcount_a"])){$cumm_wcount[0] = 0;} else {$cumm_wcount[0] = $row2["cumm_wcount_a"];}
				if(!isset($row2["cumm_wcount_b"])){$cumm_wcount[1] = 0;} else {$cumm_wcount[1] = $row2["cumm_wcount_b"];}
				if(!isset($row2["cumm_wcount_c"])){$cumm_wcount[2] = 0;} else {$cumm_wcount[2] = $row2["cumm_wcount_c"];}
				if(!isset($row2["cumm_wcount_d"])){$cumm_wcount[3] = 0;} else {$cumm_wcount[3] = $row2["cumm_wcount_d"];}
				if(!isset($row2["cumm_wcount_e"])){$cumm_wcount[4] = 0;} else {$cumm_wcount[4] = $row2["cumm_wcount_e"];}
				if(!isset($row2["cumm_wcount_f"])){$cumm_wcount[5] = 0;} else {$cumm_wcount[5] = $row2["cumm_wcount_f"];}
				if(!isset($row2["cumm_wcount_g"])){$cumm_wcount[6] = 0;} else {$cumm_wcount[6] = $row2["cumm_wcount_g"];}
				if(!isset($row2["cumm_wcount_h"])){$cumm_wcount[7] = 0;} else {$cumm_wcount[7] = $row2["cumm_wcount_h"];}
				if(!isset($row2["cumm_wcount_i"])){$cumm_wcount[8] = 0;} else {$cumm_wcount[8] = $row2["cumm_wcount_i"];}
				if(!isset($row2["cumm_wcount_j"])){$cumm_wcount[9] = 0;} else {$cumm_wcount[9] = $row2["cumm_wcount_j"];}
				
			
			 
				$num_wrong_tot = array_sum($cumm_wcount);
			 
				if ($num_wrong_tot ==0){
					
					echo(' ');	
				} else {
					
					$max_cat=max($cumm_wcount);
					
					for ($j=0; $j<=9; $j++){
						$per_wrong[$j] = $cumm_wcount[$j]/$num_wrong_tot*100;
						// if ($per_wrong[$j] == 0){$per_wrong[$j] = "";}
					}
					
			 
					 echo('<span class="inlinebar2">'.$per_wrong[0]." ,".$per_wrong[1].", ".$per_wrong[2]." , ".$per_wrong[3].", ".$per_wrong[4]." ,".$per_wrong[5]." ,".$per_wrong[6]." ,".$per_wrong[7]." ,".$per_wrong[8]." ,".$per_wrong[9].'</span>');
					echo('<br><font size="1">&nbsp; a &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; b &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; c &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; d &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; e &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; f  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; g &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; h &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; i &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; j'."</font>");
					echo('<br><font size="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; tries each part'."</font>");
					echo('</font>');
				}
	
	
	echo("</td>");
	echo("</tbody>");
	echo("</table>");
	echo ('</div>');	

echo ('<br>');


echo ('<table id="table_format3" class = "a" border="1" >'."\n");
	
	 echo("<thead>");

	echo("</td><th>");
	echo('asn');
	echo("</th><th>");
	echo('stu_name');
    echo("</th><th>");
	echo('PIN');
	 echo("</th><th>");
	 echo('Score');
	 echo("</th><th>");
	  echo('rtn_code');
	   echo("</th><th>");
	  echo('Submitted');
	 echo("</th><th>");
	echo('1st open');
    echo("</th><th>");
	echo('Guestimates');
    echo("</th><th>");
	echo('Planning');
	 echo("</th><th>");
	echo('tries');
    echo("</th><th>");
	echo('t_PProb3');
    echo("</th><th>");
	echo('t_PProb4');
	 echo("</th><th>");
	echo('concepts');
	echo("</th></tr>\n");
	 echo("</thead>");
	
	  echo("<tbody>");
	//
	
	$sql="SELECT * FROM Assign WHERE ((prob_num = :problem_id AND iid =:iid)
		OR (prob_num = :problem_id AND grader_id1 =:iid)
		OR (prob_num = :problem_id AND grader_id2 =:iid)
		OR (prob_num = :problem_id AND grader_id3 =:iid)
	)";
	$stmt2 = $pdo->prepare($sql);
	$stmt2->execute(array(
		':problem_id' => $problem_id,
		':iid' => $iid
	));
	$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
	
	
	$sql="SELECT * FROM Activity WHERE (problem_id = :problem_id )";
	
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(
		':problem_id' => $problem_id,
		
	));
	

//$stmt = $pdo->query($qstmnt);
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	if ($row2 != false) {
			 echo "<tr><td>";
			echo(htmlentities($row['assign_id']));
			echo("</td><td>");	
			echo(htmlentities($row['stu_name']));
			echo("</td><td>");
			echo(htmlentities($row['pin']));
			echo("</td><td>");
			echo(htmlentities($row['score']));
			echo("</td><td>");  
			echo(htmlentities($row['rtn_code']));
			echo("</td><td>");  
			echo(htmlentities($row['time_complete']));
			echo("</td><td>");  
			echo(htmlentities($row['time_created']));
			echo("</td><td>");
			if ($row['time_pp1']!=0){echo(htmlentities($row['time_pp1']));} else {echo('');	}
			echo("</td><td>");
			if ($row['time_pp2']!=0){echo(htmlentities($row['time_pp2']));} else {echo('');	}
			echo("</td><td>");
			if ($row['num_try']!=0){echo(htmlentities($row['num_try']));} else {echo('');	}
			echo("</td><td>");
			if ($row['time_pp3']!=0){echo(htmlentities($row['time_pp3']));} else {echo('');	}
			echo("</td><td>");
			if ($row['time_pp4']!=0){echo(htmlentities($row['time_pp4']));} else {echo('');	}
			echo("</td><td>");
			if ($row['time_pp4']!=0){echo(htmlentities($row['est_what_concepts']));} else {echo('');	}
		   echo("</td></tr>\n");
	}
}

	echo("</tbody>");
	 echo("</table>");
	echo ('</div>');	



?>

<script>
 	
	$(".inlinebar1").sparkline("html",{type: "bar", height: "50", barWidth: "20", resize: true, barSpacing: "10", barColor: "#7ace4c"});
	$(".inlinebar2").sparkline("html",{type: "bar", height: "50", barWidth: "20", resize: true, barSpacing: "10", barColor: "orange"});
	
	
	
	
	$(document).ready( function () {
   // $('#table_format2').DataTable({"sDom": 'W<"clear">lfrtip',
		// "oColumnFilterWidgets":[] 
	//	});
	

	
	
	//$('#table_format3').DataTable({"sDom": 'W<"clear">lfrtip',
		// "order": [[ 0, 'dsc' ] ],
		// "lengthMenu": [ 50, 100, 200 ],
		// "oColumnFilterWidgets": {
		// "aiExclude": [ 0,1,2,3,4,6,10,13,14,15 ] 
		// });
	
	$('#table_format3').DataTable({
	 "lengthMenu": [ 30, 50, 100 ]
	});  
	});
	

	// jQuery('#table_format').ddTableFilter();
	
	
	
	
	
	 
</script>


</body>
</html>