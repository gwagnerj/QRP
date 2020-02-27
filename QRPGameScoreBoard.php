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
	<title>QRGame Score</title>
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

	#request_prob{
		text-align: right;
		 color: blue;
		width: 100%;
		height: 100%;
		border: none;
		overflow: auto;
		padding:0px;
		margin:0px;
		font-size:70%;
		
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
		 .widget-1 { width:150px; } 
		  .widget-2 { width:150px; } 
		        
			.widget-0 { width:150px; } 
		 
		 
		 
	.column-filter-widget { float:left; padding: 20px; border : none; width:200px;}
	.column-filter-widget select { display: block; }
	.column-filter-widgets a.filter-term { display: block; text-decoration: none; padding-left: 10px; font-size: 90%; }
	.column-filter-widgets a.filter-term:hover { text-decoration: line-through !important; }
	.column-filter-widget-selected-terms { clear:left; }
		
	.half-line {
		line-height: 0.5em;
	}	
		
	</style>
							
		
		
                    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
                    <link rel="stylesheet" type="text/css" href="DataTables-1.10.18/css/jquery.dataTables.css"/> 
                    <script type="text/javascript" src="DataTables-1.10.18/js/jquery.dataTables.js"></script>
                    <script type="text/javascript" charset="utf-8" src="DataTables-1.10.18/extras/js/ColumnFilterWidgets.js"></script>
                    <meta http-equiv="refresh" content="10"/>
		
				<!-- THis is from sparklines jquery plugin   -->	

				<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.js"></script>
		
	</head>

	<body>
	<header>
	<h2>Quick Game Score Board</h2>
	 <font size = '1' color = 'Blue'>(Try "ctrl +" or "ctrl -" to resize)</font>
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




	
			
		//	echo '<a href="QRGMaster.php">Timer and Controller Screen </b></a>';
			
			echo '&nbsp; &nbsp;&nbsp;';
		if(isset($_SESSION['gmact_id'])){
            $gmact_id = $_SESSION['gmact_id'];
        
        } elseif(isset($_GET['gmact_id'])){
            $gmact_id = $_GET['gmact_id'];
        
        }else{
            $gmact_id = '';
        }
       // echo "gmact_id".$gmact_id;

	echo ('<table id="table_format" class = "a" border="1" >'."\n");
		
		 echo("<thead>");

		echo("</td><th>");
		echo('Team');
		echo("</th><th>");
		echo('Score');
		
		echo("</th></tr>\n");
		 echo("</thead>");
		 
		  echo("<tbody>");
		//
		
		// Get the team_id of all the teams in the game_prob_flag
          $stmt = $pdo->prepare("SELECT `team_id`  FROM `Gameactivity` WHERE gmact_id = :gmact_id ");
			$stmt->execute(array(":gmact_id" => $gmact_id));
             $row = $stmt->fetch(PDO::FETCH_ASSOC); 
            // print_r($row);
                $team_ids = array_unique($row);
                
             //     echo "team_id ".$team_id;
                foreach ($team_ids as $team_id){
             $stmt2 = $pdo->prepare("SELECT AVG(`score`) AS avg_score FROM `Gameactivity` WHERE gmact_id = :gmact_id AND team_id = :team_id ");
                $stmt2->execute(array(":gmact_id" => $gmact_id, ":team_id" => $team_id));
                $row2 = $stmt2 -> fetch();
                $team_score = $row2['avg_score'];
        
                 echo "<tr><td>";
                echo($team_id);
                echo("</td><td>");	
                echo($team_score);
               echo("</td></tr>\n");
            }
            
            
            echo("</tbody>");
             echo("</table>");
            echo ('</div>');	

       
	
?>

	<script>
	/* 	
		$(".inlinebar1").sparkline("html",{type: "bar", height: "50", barWidth: "10", resize: true, barSpacing: "5", barColor: "#7ace4c"});
		$(".inlinebar2").sparkline("html",{type: "bar", height: "50", barWidth: "10", resize: true, barSpacing: "5", barColor: "orange"});
		
		localStorage.setItem('MC_flag','false');  // initialize multiple choice flag to false
		
		
		
		$(document).ready( function () {
		$('#table_format').DataTable({"sDom": 'W<"clear">lfrtip',
			"order": [[ 0, 'dsc' ] ],
			 "lengthMenu": [ 50, 100, 200 ],
			"oColumnFilterWidgets": {
			"aiExclude": [ ] }});
		

		// jQuery('#table_format').ddTableFilter();
		} );
		 */
		
	</script>

	
	</body>
	</html>