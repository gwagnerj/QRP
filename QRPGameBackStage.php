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
	<title>QRGame BackStage</title>
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
							
		
		
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                    <link rel="stylesheet" type="text/css" href="DataTables-1.10.18/css/jquery.dataTables.css"/> 
                    <script type="text/javascript" src="DataTables-1.10.18/js/jquery.dataTables.js"></script>
                    <script type="text/javascript" charset="utf-8" src="DataTables-1.10.18/extras/js/ColumnFilterWidgets.js"></script>
                    <!-- <meta http-equiv="refresh" content="10"/> -->
                
                    
                    
                      <meta name="viewport" content="width=device-width, initial-scale=1" /> 

                    <link rel="stylesheet" type="text/css" href="jquery.countdown.css"> 
                        
                    <script type="text/javascript" src="jquery.plugin.js"></script> 
                    <script type="text/javascript" src="jquery.countdown.js"></script>
                    
                    
                    
                    
                    
                    
				<!-- THis is from sparklines jquery plugin   -->	

				<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.js"></script>
		
	</head>

	<body>
	<header>
	<h2>Quick Game Back Stage</h2>
	 
	</header>


	  
<?php
// data validation on the Post vaiables from QRGMaster.php

if(isset($_POST['gmact_id'])){
            $gmact_id = $_POST['gmact_id'];
        
        } elseif(isset($_GET['gmact_id'])){
            $gmact_id = $_GET['gmact_id'];
            
        
        } elseif(isset($_SESSION['gmact_id'])){
            $gmact_id = $_SESSION['gmact_id'];
        
        }else{
            $gmact_id = '';
            $_SESSION['error']= 'no Gmact ID in post of session var for backstage';
        }




	if ( isset($_SESSION['error']) ) {
		echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
		unset($_SESSION['error']);
	}
	if ( isset($_SESSION['success']) ) {
		echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
		unset($_SESSION['success']);
	}
    ?>
       <form  method="POST" action = "GetKahootData.php" target = "_blank" id = "get_kahoot">
           <p style="font-size:50px;"></p>
           <p><input type="hidden" name="gmact_id"  value=<?php echo($gmact_id);?> ></p>
          <p><input type = "submit" name = "getkahootdata" value="Get Kahoot Data" id="submit_id" size="2" style = "width: 30%; background-color: indigo; color: white"/> &nbsp &nbsp </p>  
       </form>  
  
	<?php
	 echo ('<table id="table_format" style = "text-align:center" class = "a" border="1" >'."\n");	
		 echo("<thead>");

		echo("<th>");
		echo('team_id');
        echo("</th><th>");
		echo('gameactivity_id');
		echo("</th><th>");
		echo('pin');
         echo("</th><th>");
     	echo('name');
        echo("</th><th>");
     	echo('team_size');
         echo("</th><th>");
        echo('Kahoot Score');
        echo("</th><th>");
        echo('ans_b');
		echo("</th><th>");
        echo('ans_sumb');
		echo("</th><th>");
         echo('ans_last');
		echo("</th><th>");
        echo('ans_sumlast');
        echo("</th><th>");
        echo('score');
        echo("</th><th>");
        echo('team_score');
		echo("</th><th>");
		echo('Function');
		echo("</th></tr>\n");
		 echo("</thead>");
		 
		  echo("<tbody>");
		//
		
		// Get the team_id of all the teams in the game_prob_flag
          $stmt = $pdo->prepare("SELECT *  FROM `Gameactivity` WHERE gmact_id = :gmact_id ORDER BY `team_id` ASC ");
			$stmt->execute(array(":gmact_id" => $gmact_id));
             $rows = $stmt->fetchALL(PDO::FETCH_ASSOC); 
           //  print_r($row[0]);
             
             foreach($rows as $row){
            
                 echo "<tr><td>";
                echo($row['team_id']);
             
                  echo "</td><td>";
                echo($row['gameactivity_id']);
               
                echo("</td><td>");	
                  echo($row['pin']);
                  
                echo("</td><td>");	
                 echo($row['name']);
            
                echo("</td><td>");	
                 echo($row['team_size']);
                 
                  echo("</td><td>");	
               echo($row['kahoot_score']);
            
                 echo("</td><td>");
                echo($row['ans_b']);
            
                echo("</td><td>");
                echo(sigFig($row['ans_sumb'],4));
                              
                 echo("</td><td>");
                 echo($row['ans_last']);
                 
                 echo("</td><td>");
                 echo(sigFig($row['ans_sumlast'],4));
               
                echo("</td><td>");	
                 echo($row['score']);
                 
                  echo("</td><td>");	
                 echo($row['team_score']);
                 
                 echo("</td><td>");
                
               
                echo('<form action = "QRGameFixSum.php" method = "POST" target = "_blank"> <input type = "hidden" name = "gmact_id" value = "'.$row['gmact_id'].'"><input type = "hidden" name = "team_id" value =  "'.$row['team_id'].'"><input type = "submit" value ="Fix Team Sums"></form>');
	             echo('<form action = "QRGameDeletePlayer.php" method = "POST" target = "_blank">  <input type = "hidden" name = "gameactivity_id" value = "'.$row['gameactivity_id'].'"><input type = "submit" value ="Delete Player"></form>');
                 echo('<form action = "QRGameEditPlayer.php" method = "POST" target = "_blank"> <input type = "hidden" name = "gameactivity_id" value = "'.$row['gameactivity_id'].'"><input type = "submit" value ="Edit Player Data"></form>');

                // echo("&nbsp; ");
				// echo('<form action = "getGame.php" method = "POST" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "hidden" name = "iid" value = "'.$users_id.'"><input type = "submit" value ="Game"></form>');
				// echo("&nbsp; ");
				// echo('<form action = "numericToMC.php" method = "POST" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "submit" value ="Make MC"></form>');
				                
                    
               echo("</td></tr>\n");
            }
            
            
            echo("</tbody>");
             echo("</table>");
             
                if(isset($_POST['close'])){
                    echo  "<script type='text/javascript'>";
                    echo "window.close();";
                echo "</script>";
     }
/* 
            echo('<input type="button" id="show_all" value="Show All" />');
             echo('<input type="button" id="hide_all" value="Hide All" />');
 */
	function sigFig($value, $digits)
            {
                if ($value == 0) {
                    $decimalPlaces = $digits - 1;
                } elseif ($value < 0) {
                    $decimalPlaces = $digits - floor(log10($value * -1)) - 1;
                } else {
                    $decimalPlaces = $digits - floor(log10($value)) - 1;
                }

                $answer = round($value, $decimalPlaces);
                return $answer;
            }
?>

        <form  method="POST" action = "" id = "refresh_page">
           <p style="font-size:50px;"></p>
           page auto-refreshes every 5s
           <p><input type="hidden" name="gmact_id"  value=<?php echo($gmact_id);?> ></p>
          <p><input type = "submit" name = "refresh" value="Refresh Page" id="refrsh_id" size="2" style = "width: 30%; background-color: blue; color: white"/> &nbsp &nbsp </p>  
       </form>  
        <p style="font-size:150px;"></p>   
        <form method="POST" >
             <p><input type="hidden" name="gmact_id" id="gmact_id" value=<?php echo($gmact_id);?> ></p>
             <p><input type = "submit" name = "close" value="Exit - Close Window" id="close_id" size="2" style = "width: 40%; background-color: black; color: white"/> &nbsp &nbsp </p>
        </form>

	<script>
	  /*  $(document).ready( function () {	
		$(".inlinebar1").sparkline("html",{type: "bar", height: "100", barWidth: "10", resize: true, barSpacing: "5", barColor: "#7ace4c"});
	 	
    
        $(".inlinebar2").sparkline("html",{type: "bar", height: "50", barWidth: "10", resize: true, barSpacing: "5", barColor: "orange"});
		
		localStorage.setItem('MC_flag','false');  // initialize multiple choice flag to false
		
		
		 */
		$(document).ready( function () {
            
            // auto refresh page 
            
             setInterval("$('#refresh_page').submit()",5000);
          
            

                $('#table_format').DataTable({
                        "order": [[ 0, 'dsc' ] ]
                });

		// jQuery('#table_format').ddTableFilter();
		} );
         
         
                   
		
	</script>

	
	</body>
	</html>