<?php
	require_once "pdo.php";
	session_start();
    
  if(isset($_POST['exam_code'])){
            $exam_code = $_POST['exam_code'];
        
        } elseif(isset($_GET['exam_code'])){
            $exam_code = $_GET['exam_code'];
            
        
        }else{
            $exam_code = '';
            $_SESSION['error']= 'no exam_code in post in QRERetrieve.php';
              header('Location: QRExamRetrieve.php');
            return;   
            
            
        }
        
       if(isset($_POST['iid'])){
            $iid = $_POST['iid'];
        
        } elseif(isset($_GET['iid'])){
            $iid = $_GET['iid'];
            
        
        }else{
            $iid = '';
            $_SESSION['error']= 'no iid in post in QRERetrieve.php';
              header('Location: QRExamRetrieve.php');
            return;   
            
            
        }  
        
        $delete_flag = 0;
           if(isset($_POST['delete_flag'])){
            $delete_flag = $_POST['delete_flag'];
        
        }

    $sql = "SELECT * FROM `Examactivity` WHERE exam_code = :exam_code";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
             ':exam_code' => $exam_code,
          )); 
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
             if($row != false){
                 $examtime_id = $row['examtime_id']; 
                $iid_tab = $row['iid'];
               
             } else {
                 
                 $_SESSION['error']= 'could not retreive examtime_id in QRERetrieve.php';
                header('Location: QRExamRetrieve.php');
                return;   
                 
             }

            if ($iid != $iid_tab){
                
                 $_SESSION['error']= 'instructor not the same as requestor in QRERetrieve.php';
                header('Location: QRExamRetrieve.php');
                return;     
                
            }


            if(isset($_POST['close'])){
                    
                    if($delete_flag ==1){
                        
                        $sql = "DELETE FROM Examactivity WHERE iid = :iid AND exam_code = :exam_code";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(array(
                        ":iid" => $iid,
                          ":exam_code" => $exam_code
                        ));
                        
                    }
                    
                   //  header("Location: QRExam.php?examactivity_id=".$examactivity_id );
                     header("Location: QRPRepo.php");
                      return;  
                    
                /*     echo  "<script type='text/javascript'>";
                    echo "window.close();";
                echo "</script>"; */
     }


  
        $stmt = $pdo->prepare("SELECT *  FROM `Examactivity` WHERE examtime_id = :examtime_id ORDER BY `pin` ASC ");
			$stmt->execute(array(":examtime_id" => $examtime_id));
             $row1 = $stmt->fetch(PDO::FETCH_ASSOC); 
             
             
         
             
            if (strlen($row1['response_pblm1'] ) > 0) {
            
               $pblm1_flag =1;
            } else{
                  $pblm1_flag =0 ;
            }
            
              if (strlen($row1['response_pblm2']) >0 ) {
                 $pblm2_flag =1;
            } else{
                  $pblm2_flag =0;
            }  
   
               if (strlen($row1['response_pblm3']) >0 ) {
                 $pblm3_flag =1;
            } else{
                  $pblm3_flag =0;
            }  
            
            
               if (strlen($row1['response_pblm4']) >0 ) {
                 $pblm4_flag =1;
            } else{
                  $pblm4_flag =0;
            }  

 
               if (strlen($row1['response_pblm5']) >0 ) {
                 $pblm5_flag =1;
            } else{
                  $pblm5_flag =0;
            }  





   
?>
	 <!DOCTYPE html>
	<html lang = "en">
	<head>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta Charset = "utf-8">
	<title>QRExam Retrieve Exam Data</title>
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
	<h2>Quick Response Exam Data</h2>
    </header>
   
  <!-- 
    <h2>Points Assignment: </h2>
    (Refresh to Get Totals)
    <p>
    &nbsp;&nbsp;Problem 1:  a:<input type = "number" size = "2" max = "100" min = "0" name = "p1a" value = "<?php if(isset($_POST['p1a'])){echo($_POST['p1a']);}else{echo(0);}?>" ></input> &nbsp;
               b:<input type = "number" size = "2" max = "100" min = "0" name = "p1b" value = "<?php if(isset($_POST['p1b'])){echo($_POST['p1b']);}else{echo(0);}?>"></input> &nbsp;
               c:<input type = "number" size = "2" max = "100" min = "0" name = "p1c" value = "<?php if(isset($_POST['p1c'])){echo($_POST['p1c']);}else{echo(0);}?>" ></input> &nbsp;
               d:<input type = "number" size = "2" max = "100" min = "0" name = "p1d" value = "<?php if(isset($_POST['p1d'])){echo($_POST['p1d']);}else{echo(0);}?>" ></input> &nbsp;
               e:<input type = "number" size = "2" max = "100" min = "0" name = "p1e" value = "<?php if(isset($_POST['p1e'])){echo($_POST['p1e']);}else{echo(0);}?>" ></input> &nbsp;
               f:<input type = "number" size = "2" max = "100" min = "0" name = "p1f" value = "<?php if(isset($_POST['p1f'])){echo($_POST['p1f']);}else{echo(0);}?>" ></input> &nbsp;
               g:<input type = "number" size = "2" max = "100" min = "0" name = "p1g" value = "<?php if(isset($_POST['p1g'])){echo($_POST['p1g']);}else{echo(0);}?>" ></input> &nbsp;
               h:<input type = "number" size = "2" max = "100" min = "0" name = "p1h" value = "<?php if(isset($_POST['p1h'])){echo($_POST['p1h']);}else{echo(0);}?>" ></input> &nbsp;
               i:<input type = "number" size = "2" max = "100" min = "0" name = "p1i" value = "<?php if(isset($_POST['p1i'])){echo($_POST['p1i']);}else{echo(0);}?>" ></input> &nbsp;
               j:<input type = "number" size = "2" max = "100" min = "0" name = "p1j" value = "<?php if(isset($_POST['p1j'])){echo($_POST['p1j']);}else{echo(0);}?>" ></input> &nbsp;
   
   
   <?php if(isset($_POST['p1a'])){$p1_tot = intval($_POST['p1a'])+intval($_POST['p1b'])+intval($_POST['p1c'])+intval($_POST['p1d'])+intval($_POST['p1e'])+intval($_POST['p1f'])+intval($_POST['p1g'])+intval($_POST['p1h'])+intval($_POST['p1i'])+intval($_POST['p1j']);}else{$p1_tot=0;}?>
   &nbsp;&nbsp; Total <?php echo($p1_tot);?>            
   </p>
     <p>
    &nbsp;&nbsp;Problem 2:  a:<input type = "number" size = "2" max = "100" min = "0" name = "p2a" value = "<?php if(isset($_POST['p2a'])){echo($_POST['p2a']);}else{echo(0);}?>" ></input> &nbsp;
               b:<input type = "number" size = "2" max = "100" min = "0" name = "p2b" value = "<?php if(isset($_POST['p2b'])){echo($_POST['p2b']);}else{echo(0);}?>"></input> &nbsp;
               c:<input type = "number" size = "2" max = "100" min = "0" name = "p2c" value = "<?php if(isset($_POST['p2c'])){echo($_POST['p2c']);}else{echo(0);}?>" ></input> &nbsp;
               d:<input type = "number" size = "2" max = "100" min = "0" name = "p2d" value = "<?php if(isset($_POST['p2d'])){echo($_POST['p2d']);}else{echo(0);}?>" ></input> &nbsp;
               e:<input type = "number" size = "2" max = "100" min = "0" name = "p2e" value = "<?php if(isset($_POST['p2e'])){echo($_POST['p2e']);}else{echo(0);}?>" ></input> &nbsp;
               f:<input type = "number" size = "2" max = "100" min = "0" name = "p2f" value = "<?php if(isset($_POST['p2f'])){echo($_POST['p2f']);}else{echo(0);}?>" ></input> &nbsp;
               g:<input type = "number" size = "2" max = "100" min = "0" name = "p2g" value = "<?php if(isset($_POST['p2g'])){echo($_POST['p2g']);}else{echo(0);}?>" ></input> &nbsp;
               h:<input type = "number" size = "2" max = "100" min = "0" name = "p2h" value = "<?php if(isset($_POST['p2h'])){echo($_POST['p2h']);}else{echo(0);}?>" ></input> &nbsp;
               i:<input type = "number" size = "2" max = "100" min = "0" name = "p2i" value = "<?php if(isset($_POST['p2i'])){echo($_POST['p2i']);}else{echo(0);}?>" ></input> &nbsp;
               j:<input type = "number" size = "2" max = "100" min = "0" name = "p2j" value = "<?php if(isset($_POST['p2j'])){echo($_POST['p2j']);}else{echo(0);}?>" ></input> &nbsp;
   
   
   <?php if(isset($_POST['p2a'])){$p2_tot = intval($_POST['p2a'])+intval($_POST['p2b'])+intval($_POST['p2c'])+intval($_POST['p2d'])+intval($_POST['p2e'])+intval($_POST['p2f'])+intval($_POST['p2g'])+intval($_POST['p2h'])+intval($_POST['p2i'])+intval($_POST['p2j']);}else{$p2_tot=0;}?>
   &nbsp;&nbsp; Total <?php echo($p2_tot);?>            
   </p>
   
   <p>
    &nbsp;&nbsp;Problem 3:  a:<input type = "number" size = "2" max = "100" min = "0" name = "p3a" value = "<?php if(isset($_POST['p3a'])){echo($_POST['p3a']);}else{echo(0);}?>" ></input> &nbsp;
               b:<input type = "number" size = "2" max = "100" min = "0" name = "p3b" value = "<?php if(isset($_POST['p3b'])){echo($_POST['p3b']);}else{echo(0);}?>"></input> &nbsp;
               c:<input type = "number" size = "2" max = "100" min = "0" name = "p3c" value = "<?php if(isset($_POST['p3c'])){echo($_POST['p3c']);}else{echo(0);}?>" ></input> &nbsp;
               d:<input type = "number" size = "2" max = "100" min = "0" name = "p3d" value = "<?php if(isset($_POST['p3d'])){echo($_POST['p3d']);}else{echo(0);}?>" ></input> &nbsp;
               e:<input type = "number" size = "2" max = "100" min = "0" name = "p3e" value = "<?php if(isset($_POST['p3e'])){echo($_POST['p3e']);}else{echo(0);}?>" ></input> &nbsp;
               f:<input type = "number" size = "2" max = "100" min = "0" name = "p3f" value = "<?php if(isset($_POST['p3f'])){echo($_POST['p3f']);}else{echo(0);}?>" ></input> &nbsp;
               g:<input type = "number" size = "2" max = "100" min = "0" name = "p3g" value = "<?php if(isset($_POST['p3g'])){echo($_POST['p3g']);}else{echo(0);}?>" ></input> &nbsp;
               h:<input type = "number" size = "2" max = "100" min = "0" name = "p3h" value = "<?php if(isset($_POST['p3h'])){echo($_POST['p3h']);}else{echo(0);}?>" ></input> &nbsp;
               i:<input type = "number" size = "2" max = "100" min = "0" name = "p3i" value = "<?php if(isset($_POST['p3i'])){echo($_POST['p3i']);}else{echo(0);}?>" ></input> &nbsp;
               j:<input type = "number" size = "2" max = "100" min = "0" name = "p3j" value = "<?php if(isset($_POST['p3j'])){echo($_POST['p3j']);}else{echo(0);}?>" ></input> &nbsp;
   
   
   <?php if(isset($_POST['p3a'])){$p3_tot = intval($_POST['p3a'])+intval($_POST['p3b'])+intval($_POST['p3c'])+intval($_POST['p3d'])+intval($_POST['p3e'])+intval($_POST['p3f'])+intval($_POST['p3g'])+intval($_POST['p3h'])+intval($_POST['p3i'])+intval($_POST['p3j']);}else{$p3_tot=0;}?>
   &nbsp;&nbsp; Total <?php echo($p3_tot);?>            
   </p>
   <p>
    &nbsp;&nbsp;Problem 4:  a:<input type = "number" size = "2" max = "100" min = "0" name = "p4a" value = "<?php if(isset($_POST['p4a'])){echo($_POST['p4a']);}else{echo(0);}?>" ></input> &nbsp;
               b:<input type = "number" size = "2" max = "100" min = "0" name = "p4b" value = "<?php if(isset($_POST['p4b'])){echo($_POST['p4b']);}else{echo(0);}?>"></input> &nbsp;
               c:<input type = "number" size = "2" max = "100" min = "0" name = "p4c" value = "<?php if(isset($_POST['p4c'])){echo($_POST['p4c']);}else{echo(0);}?>" ></input> &nbsp;
               d:<input type = "number" size = "2" max = "100" min = "0" name = "p4d" value = "<?php if(isset($_POST['p4d'])){echo($_POST['p4d']);}else{echo(0);}?>" ></input> &nbsp;
               e:<input type = "number" size = "2" max = "100" min = "0" name = "p4e" value = "<?php if(isset($_POST['p4e'])){echo($_POST['p4e']);}else{echo(0);}?>" ></input> &nbsp;
               f:<input type = "number" size = "2" max = "100" min = "0" name = "p4f" value = "<?php if(isset($_POST['p4f'])){echo($_POST['p4f']);}else{echo(0);}?>" ></input> &nbsp;
               g:<input type = "number" size = "2" max = "100" min = "0" name = "p4g" value = "<?php if(isset($_POST['p4g'])){echo($_POST['p4g']);}else{echo(0);}?>" ></input> &nbsp;
               h:<input type = "number" size = "2" max = "100" min = "0" name = "p4h" value = "<?php if(isset($_POST['p4h'])){echo($_POST['p4h']);}else{echo(0);}?>" ></input> &nbsp;
               i:<input type = "number" size = "2" max = "100" min = "0" name = "p4i" value = "<?php if(isset($_POST['p4i'])){echo($_POST['p4i']);}else{echo(0);}?>" ></input> &nbsp;
               j:<input type = "number" size = "2" max = "100" min = "0" name = "p4j" value = "<?php if(isset($_POST['p4j'])){echo($_POST['p4j']);}else{echo(0);}?>" ></input> &nbsp;
   
   
   <?php if(isset($_POST['p4a'])){$p4_tot = intval($_POST['p4a'])+intval($_POST['p4b'])+intval($_POST['p4c'])+intval($_POST['p4d'])+intval($_POST['p4e'])+intval($_POST['p4f'])+intval($_POST['p4g'])+intval($_POST['p4h'])+intval($_POST['p4i'])+intval($_POST['p4j']);}else{$p4_tot=0;}?>
   &nbsp;&nbsp; Total <?php echo($p4_tot);?>            
   </p>
   <p>
    &nbsp;&nbsp;Problem 5:  a:<input type = "number" size = "2" max = "100" min = "0" name = "p5a" value = "<?php if(isset($_POST['p5a'])){echo($_POST['p5a']);}else{echo(0);}?>" ></input> &nbsp;
               b:<input type = "number" size = "2" max = "100" min = "0" name = "p5b" value = "<?php if(isset($_POST['p5b'])){echo($_POST['p5b']);}else{echo(0);}?>"></input> &nbsp;
               c:<input type = "number" size = "2" max = "100" min = "0" name = "p5c" value = "<?php if(isset($_POST['p5c'])){echo($_POST['p5c']);}else{echo(0);}?>" ></input> &nbsp;
               d:<input type = "number" size = "2" max = "100" min = "0" name = "p5d" value = "<?php if(isset($_POST['p5d'])){echo($_POST['p5d']);}else{echo(0);}?>" ></input> &nbsp;
               e:<input type = "number" size = "2" max = "100" min = "0" name = "p5e" value = "<?php if(isset($_POST['p5e'])){echo($_POST['p5e']);}else{echo(0);}?>" ></input> &nbsp;
               f:<input type = "number" size = "2" max = "100" min = "0" name = "p5f" value = "<?php if(isset($_POST['p5f'])){echo($_POST['p5f']);}else{echo(0);}?>" ></input> &nbsp;
               g:<input type = "number" size = "2" max = "100" min = "0" name = "p5g" value = "<?php if(isset($_POST['p5g'])){echo($_POST['p5g']);}else{echo(0);}?>" ></input> &nbsp;
               h:<input type = "number" size = "2" max = "100" min = "0" name = "p5h" value = "<?php if(isset($_POST['p5h'])){echo($_POST['p5h']);}else{echo(0);}?>" ></input> &nbsp;
               i:<input type = "number" size = "2" max = "100" min = "0" name = "p5i" value = "<?php if(isset($_POST['p5i'])){echo($_POST['p5i']);}else{echo(0);}?>" ></input> &nbsp;
               j:<input type = "number" size = "2" max = "100" min = "0" name = "p5j" value = "<?php if(isset($_POST['p5j'])){echo($_POST['p5j']);}else{echo(0);}?>" ></input> &nbsp;
   
   
   <?php if(isset($_POST['p5a'])){$p5_tot = intval($_POST['p5a'])+intval($_POST['p5b'])+intval($_POST['p5c'])+intval($_POST['p5d'])+intval($_POST['p5e'])+intval($_POST['p5f'])+intval($_POST['p5g'])+intval($_POST['p5h'])+intval($_POST['p5i'])+intval($_POST['p5j']);}else{$p5_tot=0;}?>
   &nbsp;&nbsp; Total <?php echo($p5_tot);?>            
   </p>
              
   <?php $tot = $p1_tot+$p2_tot+$p3_tot+$p4_tot+$p5_tot?>
 <p>&nbsp;&nbsp; Total Points Assigned <?php echo($tot);?>    </p>
  
     
 -->    


<?php
// data validation on the Post vaiables from QRExamRetrieve.php




	if ( isset($_SESSION['error']) ) {
		echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
		unset($_SESSION['error']);
	}
	if ( isset($_SESSION['success']) ) {
		echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
		unset($_SESSION['success']);
	}
    
  
    
   
	 echo ('<table id="table_format" style = "text-align:center" class = "a" border="1" >'."\n");	
		 echo("<thead>");

		echo("<th>");
		echo('name');
        echo("</th><th>");
		echo('pin');
          echo("</th><th>");
		//echo('Extend t');
      //  echo("</th><th>");
       
        if($pblm1_flag ==1){echo('Correct 1'); echo("</th><th>");}
        if($pblm2_flag ==1){echo('Correct 2'); echo("</th><th>");}
        if($pblm3_flag ==1){echo('Correct 3'); echo("</th><th>");}
        if($pblm4_flag ==1){echo('Correct 4'); echo("</th><th>");}
        if($pblm5_flag ==1){echo('Correct 5'); echo("</th><th>");}
        
        if($pblm1_flag ==1){echo('Pts 1'); echo("</th><th>");}
        if($pblm2_flag ==1){echo('Pts 2'); echo("</th><th>");}
        if($pblm3_flag ==1){echo('Pts 3'); echo("</th><th>");}
        if($pblm4_flag ==1){echo('Pts 4'); echo("</th><th>");}
        if($pblm5_flag ==1){echo('Pts 5'); echo("</th><th>");}
       
        if($pblm1_flag ==1){echo('Try 1'); echo("</th><th>");}
        if($pblm2_flag ==1){echo('Try 2'); echo("</th><th>");}
        if($pblm3_flag ==1){echo('Try 3'); echo("</th><th>");}
        if($pblm4_flag ==1){echo('Try 4'); echo("</th><th>");}
        if($pblm5_flag ==1){echo('Try 5'); echo("</th><th>");}



        echo('Total');
		echo("</th></tr>\n");
		 echo("</thead>");
		 
		  echo("<tbody>");
		//

 

		
		// Get the team_id of all the teams in the game_prob_flag
          $stmt = $pdo->prepare("SELECT *  FROM `Examactivity` WHERE examtime_id = :examtime_id ORDER BY `pin` ASC ");
			$stmt->execute(array(":examtime_id" => $examtime_id));
             $rows = $stmt->fetchALL(PDO::FETCH_ASSOC); 
           //  print_r($row[0]);
             
             foreach($rows as $row){
            
                 echo "<tr><td>";
                echo($row['name']);
                echo("</td><td>");	
                  echo($row['pin']);
                  echo("</td><td>");
                  
                //  echo('toggle');
                   
            /*   
                
                  if($row['extend_time_flag']==1){echo('<p>yes</p>');} 
                    echo('<form action = "QRExamEditExaminee.php" method = "POST" target = "_blank" > <input type = "hidden" name = "examactivity_id" value = "'.$row['examactivity_id'].'"><input type = "submit" name = "edit" value ="Edit"></form>');
                   // echo('examactivity_id = '.$row['examactivity_id']);
               //   echo('<input type = "checkbox" '.$checked.' name ="'.$row['examactivity_id'].'"></input>');
 //--------------------------------------------------------------------------------------------------------fix this-------------------------------------                 
                  
                  
                    echo("</td><td>");	
                     */
                    
                if ($pblm1_flag == 1){  print('<span class="inlinebar1">');  echo($row['response_pblm1']);   print('</span>');   echo("</td><td>");}
                if ($pblm2_flag == 1){  print('<span class="inlinebar1">');  echo($row['response_pblm2']);   print('</span>');   echo("</td><td>");}
                if ($pblm3_flag == 1){  print('<span class="inlinebar1">');  echo($row['response_pblm3']);   print('</span>');   echo("</td><td>");}
                if ($pblm4_flag == 1){  print('<span class="inlinebar1">');  echo($row['response_pblm4']);   print('</span>');   echo("</td><td>");}
                if ($pblm5_flag == 1){  print('<span class="inlinebar1">');  echo($row['response_pblm5']);   print('</span>');   echo("</td><td>");}
               
                if ($pblm1_flag == 1){   echo($row['pblm_1_score']);    echo("</td><td>");}
                if ($pblm2_flag == 1){    echo($row['pblm_2_score']);    echo("</td><td>");}
                if ($pblm3_flag == 1){    echo($row['pblm_3_score']);     echo("</td><td>");}
                if ($pblm4_flag == 1){    echo($row['pblm_4_score']);     echo("</td><td>");}
                if ($pblm5_flag == 1){   echo($row['pblm_5_score']);    echo("</td><td>");}
               
                        
                if ($pblm1_flag == 1){   echo($row['trynum_pblm1']);    echo("</td><td>");}
                if ($pblm2_flag == 1){    echo($row['trynum_pblm2']);    echo("</td><td>");}
                if ($pblm3_flag == 1){    echo($row['trynum_pblm3']);     echo("</td><td>");}
                if ($pblm4_flag == 1){    echo($row['trynum_pblm4']);     echo("</td><td>");}
                if ($pblm5_flag == 1){   echo($row['trynum_pblm5']);    echo("</td><td>");}
               
               
                
             
              
                 
              
                 
                $total_score = $row['pblm_1_score']+$row['pblm_2_score']+$row['pblm_3_score']+$row['pblm_4_score']+$row['pblm_5_score'];

                 echo($total_score);
               
                echo("</td></tr>\n");
           
            // now update the examactivity table with the scores
        /*         $sql = "UPDATE `Examactivity` SET pblm_1_score = :pblm_1_score, pblm_2_score = :pblm_2_score , pblm_3_score = :pblm_3_score, pblm_4_score = :pblm_4_score, pblm_5_score = :pblm_5_score WHERE examactivity_id = :examactivity_id ";
             $stmt = $pdo->prepare($sql);
			$stmt->execute(array(
            ":examactivity_id" => $row['examactivity_id'],
            ":pblm_1_score" => $points_1,
            ":pblm_2_score" => $points_2,
            ":pblm_3_score" => $points_3,
            ":pblm_4_score" => $points_4,
            ":pblm_5_score" => $points_5,
            ));
             */
            

           }
                 
              
               
               // echo('<form action = "QRGameFixSum.php" method = "POST" target = "_blank"> <input type = "hidden" name = "gmact_id" value = "'.$row['gmact_id'].'"><input type = "hidden" name = "team_id" value =  "'.$row['team_id'].'"><input type = "submit" value ="Fix Team Sums"></form>');
	          //   echo('<form action = "QRGameDeletePlayer.php" method = "POST" target = "_blank">  <input type = "hidden" name = "gameactivity_id" value = "'.$row['gameactivity_id'].'"><input type = "submit" value ="Delete Player"></form>');
             //    echo('<form action = "QRGameEditPlayer.php" method = "POST" target = "_blank"> <input type = "hidden" name = "gameactivity_id" value = "'.$row['gameactivity_id'].'"><input type = "submit" value ="Edit Player Data"></form>');

                // echo("&nbsp; ");
				// echo('<form action = "getGame.php" method = "POST" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "hidden" name = "iid" value = "'.$users_id.'"><input type = "submit" value ="Game"></form>');
				// echo("&nbsp; ");
				// echo('<form action = "numericToMC.php" method = "POST" target = "_blank"> <input type = "hidden" name = "problem_id" value = "'.$row['problem_id'].'"><input type = "submit" value ="Make MC"></form>');
				                
                    
            
            
           
            echo("</tbody>");
             echo("</table>");
             
             

 

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
       



       if ( isset($_SESSION['error']) ) {
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) ) {
			echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
			unset($_SESSION['success']);
		}       
            
            
            
?>
      <!-- 
        <form  method="POST" action = "" id = "refresh_page">
  
       
       <p style="font-size:10px;"></p>
         
          <p><input type = "submit" name = "refresh" value="Refresh Page" id="refrsh_id" size="2" style = "width: 30%; background-color: blue; color: white"/> &nbsp &nbsp </p>  

             <p><input type="hidden" name="exam_code" id="exam_code" value=<?php echo($exam_code);?> ></p>
              <p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
        
           <input type="hidden" name="examtime_id"  value=<?php echo($examtime_id);?> >

         
       </form>  
        <p style="font-size:75px;"></p>  

    -->
        
        <form method="POST" >
             
             <p><input type="hidden" name="exam_code" id="exam_code" value=<?php echo($exam_code);?> ></p>
              <p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
        
             <p><input type = "submit" name = "close" value="Exit - Close Window" id="close_id" size="2" style = "width: 40%; background-color: black; color: white"/> &nbsp &nbsp </p>
        </form>
        </br>
        </br>
        <hr>
        </br>
        
           <form method="POST" >
             
             <p><input type="hidden" name="exam_code" id="exam_code" value=<?php echo($exam_code);?> ></p>
              <p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
                <p><input type="hidden" name="delete_flag"  value="1" ></p>
        
             <p><input type = "submit" name = "close" value="Delete Data and Exit (cannot be undone)"  size="2" style = "width: 40%; background-color: red; color: white"/> &nbsp &nbsp </p>
        </form>

	<script>
	$(document).ready( function () {	
    
  
	 	
     $(".inlinebar1").sparkline("html",{type: "bar", height: "20", barWidth: "5", resize: true, barSpacing: "2", barColor: "navy"});
	  /*  	
    
        $(".inlinebar2").sparkline("html",{type: "bar", height: "50", barWidth: "10", resize: true, barSpacing: "5", barColor: "orange"});
		
		localStorage.setItem('MC_flag','false');  // initialize multiple choice flag to false
		
		
		
		$(document).ready( function () {
          */   
            // auto refresh page 
            
           
                   
              /*       

                $('#table_format').DataTable({
                        "order": [[ 1, 'asc' ] ],
                        "lengthMenu": [ 30, 50, 100 ],
                       
                    
                });
                 */

		// jQuery('#table_format').ddTableFilter();
		} );
         
         
                   
		
	</script>

	
	</body>
	</html>