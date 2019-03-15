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
		return; 
	}
	
	if (isset($_SESSION['iid']) ){
		$iid = $_SESSION['iid'];

		echo ('Student Results for Problem Number '.$problem_id.' for instuctor ID '.$iid);
	} else {
		
		$_SESSION['error']= 'QRPStuResults could not get instructor ID';
		header( 'Location: QRPRepo.php' ) ;
		return; 
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

	 echo('Average Score <br> All Time');
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
					echo('<br><font size="1"> &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; early &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; late'."</font>");
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
					echo('<br><font size="1">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<5min &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>3h'."</font>");
					echo('<br><font size="1"> &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; t spent'."</font>");
					echo('</font>');
				}
	echo("</td><td>");
	echo('average');
	echo("</td>");
	echo("</tbody>");
	echo("</table>");
	echo ('</div>');	




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
	echo('1st open');
    echo("</th><th>");
	echo('t_PProb1');
    echo("</th><th>");
	echo('t_PProb2');
    echo("</th><th>");
	echo('t_PProb3');
    echo("</th><th>");
	echo('t_PProb4');
	echo("</th></tr>\n");
	 echo("</thead>");
	 
	  echo("<tbody>");
	//
	
	$sql="SELECT * FROM Activity WHERE problem_id = :problem_id AND iid =:iid";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(
		':problem_id' => $problem_id,
		':iid' => $iid
	));
	
	

//$stmt = $pdo->query($qstmnt);
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  
			 echo "<tr><td>";
			echo(htmlentities($row['assign_id']));
			echo("</td><td>");	
			echo(htmlentities($row['stu_name']));
			echo("</td><td>");
			echo(htmlentities($row['pin']));
			echo("</td><td>");
			echo(htmlentities($row['score']));
			echo("</td><td>");  
			echo(htmlentities($row['time_created']));
			echo("</td><td>");
			if ($row['time_pp1']!=0){echo(htmlentities($row['time_pp1']));} else {echo('');	}
			echo("</td><td>");
			if ($row['time_pp2']!=0){echo(htmlentities($row['time_pp2']));} else {echo('');	}
			echo("</td><td>");
			if ($row['time_pp3']!=0){echo(htmlentities($row['time_pp3']));} else {echo('');	}
			echo("</td><td>");
			if ($row['time_pp4']!=0){echo(htmlentities($row['time_pp4']));} else {echo('');	}
		   echo("</td></tr>\n");
   
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
	

	// jQuery('#table_format').ddTableFilter();
	
	} );
	
	
	
	 
</script>


</body>
</html>