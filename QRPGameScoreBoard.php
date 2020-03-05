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
							
		
		
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                    <link rel="stylesheet" type="text/css" href="DataTables-1.10.18/css/jquery.dataTables.css"/> 
                    <script type="text/javascript" src="DataTables-1.10.18/js/jquery.dataTables.js"></script>
                    <script type="text/javascript" charset="utf-8" src="DataTables-1.10.18/extras/js/ColumnFilterWidgets.js"></script>
                    <meta http-equiv="refresh" content="10"/>
                
                    
                    
                      <meta name="viewport" content="width=device-width, initial-scale=1" /> 

                    <link rel="stylesheet" type="text/css" href="jquery.countdown.css"> 
                        
                    <script type="text/javascript" src="jquery.plugin.js"></script> 
                    <script type="text/javascript" src="jquery.countdown.js"></script>
                    
                    
                    
                    
                    
                    
				<!-- THis is from sparklines jquery plugin   -->	

				<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.js"></script>
		
	</head>

	<body>
	<header>
	<h2>Quick Game Score Board</h2>
	 
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
        
     if(isset($_GET['stop_time'])){
            $stop_time = $_GET['stop_time'];
        
        }else{
            $stop_time = '';
        }  
        
        $time_type = gettype( $stop_time);
        $time_time = strtotime($stop_time);
        
        
       // echo ('time_type = '.$time_type);
        
      //   echo ('time_time = '.$time_time);
       // echo ('stop_time = '.$stop_time);
      //  echo ('<div  id="stop_time" value= '.$time_time.'>'.'stop_time = '.$stop_time.'</div>');
        
    ?>    
     
    <h1><font  style="font-size:100%; color:blue;"> <?php echo '';?> </font>
    
    <form>
        <input type="hidden" id = "stop_time"   value="<?php echo($stop_time)?>"  >
      <!--  <input type="button" id="pause" value="Pause" />
        <input type="button" id="resume" value="Resume" />  
        doesn't really pause/resume - use this to match master timer
        
        -->
    </form>
    
    	<div id="defaultCountdown" style="font-size:300%;color:red;"> </div></h1>
   <p style="font-size:100px;"></p>
    
       
     
       

<?php     
      //  echo "gmact_id".$gmact_id;
	
	 echo ('<table id="table_format" class = "a" border="1" >'."\n");	
		 echo("<thead>");

		echo("</td><th>");
		echo('Team');
		echo("</th><th>");
		echo('Average');
         echo("</th><th>");
     	echo('Scores');
        echo("</th><th>");
     	echo('Range');
		echo("</th><th>");
        echo('SDEV');
		echo("</th><th>");
        echo('Cohesivity');
		echo("</th><th>");
		echo('Team Score');
		echo("</th></tr>\n");
		 echo("</thead>");
		 
		  echo("<tbody>");
		//
		
		// Get the team_id of all the teams in the game_prob_flag
          $stmt = $pdo->prepare("SELECT DISTINCT `team_id`  FROM `Gameactivity` WHERE gmact_id = :gmact_id ");
			$stmt->execute(array(":gmact_id" => $gmact_id));
             $rows = $stmt->fetchALL(PDO::FETCH_ASSOC); 
           //  print_r($row[0]);
             
             foreach($rows as $row){
                 
             $team_ids =($row);
               //  echo $row['team_id'];
                // echo $Urow['team_id'];
               //  echo $team_ids['team_id'];
            
             
             
             
               // $team_ids = array_unique($row);
      //         print_r($team_ids);
                //  echo "team_id ".$team_id;
                foreach ($team_ids as $team_id){
                    
                    
             $stmt2 = $pdo->prepare("SELECT AVG(`score`) AS avg_score FROM `Gameactivity` WHERE gmact_id = :gmact_id AND team_id = :team_id ");
                $stmt2->execute(array(":gmact_id" => $gmact_id, ":team_id" => $team_id));
                $row2 = $stmt2 -> fetch();
                $team_score = $row2['avg_score'];
            
           
             $stmt2 = $pdo->prepare("SELECT `score` FROM `Gameactivity` WHERE gmact_id = :gmact_id AND team_id = :team_id ");
                $stmt2->execute(array(":gmact_id" => $gmact_id, ":team_id" => $team_id));
                $row2 = $stmt2 -> fetchALL(PDO::FETCH_ASSOC);
                
            
              }   
            
            
            
            
                 echo "<tr><td>";
                echo($team_id);
                echo("</td><td>");	
               
                  echo($team_score);
                echo("</td><td>");	
                
                 print('<span class="inlinebar1">');
                // $score.
                $max_score = 0.0;
                $min_score = 100.0;
                $cohesivity = 1.0;
                $n = 0;
                $cumm = 0;
                foreach ($row2 as $score){
                    $n=$n+1;
                   // $scr=gettype($score);
                   // echo $scr;
                    if($score['score']>$max_score){$max_score = $score['score'];}
                     if($score['score']<$min_score){$min_score = $score['score'];}
                     $cumm = $cumm+($score['score']-$team_score)*($score['score']-$team_score);
                   echo $score['score'].', ' ;
                }
                $range = $max_score-$min_score;
                $sdev = pow(($cumm/$n),0.5);
                echo'</span>';	
                echo("</td><td>");	
                // $range = max($row2)-min($row2);   
                 echo $range;
                // echo 'range';   
               
               //echo $max_score;
                echo("</td><td>");
                // $sdev = std_deviation($row2);
                 
               // echo 'sdev= ';
                echo $sdev;
                 echo("</td><td>");
                if($range>=40){
                  $cohesivity =  $cohesivity-0.5;
                }
                if($sdev>=15){
                  $cohesivity =  $cohesivity-0.5;
                }
                echo $cohesivity;
                
                 echo("</td><td>");
                
                echo 'TEAM SCORE';    
                    
               echo("</td></tr>\n");
            }
            
            
            echo("</tbody>");
             echo("</table>");






               function std_deviation($arr){
            $arr_size=count($arr);
            $mu=array_sum($arr)/$arr_size;
            $ans=0;
            foreach($arr as $elem){
                $ans+=($elem-$mu)*($elem-$mu);
            }
            return sqrt($ans/$arr_size);
}
	
?>

 



	<script>
	    $(document).ready( function () {	
		$(".inlinebar1").sparkline("html",{type: "bar", height: "100", barWidth: "10", resize: true, barSpacing: "5", barColor: "#7ace4c"});
	 /*	
    
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
		
         
    
		
				var stop_time = $("#stop_time").val();
                 console.log ("stop time1 UTC = "+stop_time);
				var stop_time = new Date(stop_time)
                var current_time = new Date();
                var offset = current_time.getTimezoneOffset();
                console.log ("stop time UTC = "+stop_time);
                console.log ("current_time UTC = "+current_time);
                 console.log ("offset = "+offset);
                
                var new_stop=stop_time.setMinutes(stop_time.getMinutes()-offset);
                Start();
             

               function Start(){
				$('#defaultCountdown').countdown({until: stop_time, format: 'ms',  layout: '{d<}{dn} {dl} {d>}{h<}{hn} {hl} {h>}{m<}{mn} {ml} {m>}{s<}{sn} {sl}{s>}'}); 
				}
                
               
                
 
                Start();

               function pause() {
                    $('#defaultCountdown').countdown('pause');
                    
                }
                 function resume() {
                    $('#defaultCountdown').countdown('resume');
                }

                $('#pause').click(pause);
                $('#resume').click(resume);
                
                      */         
				
			});		
	
         
         
		
	</script>

	
	</body>
	</html>