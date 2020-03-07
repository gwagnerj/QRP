<?php
	session_start();
	require_once "pdo.php";
	
// Called by QRGameBackStage.php and then if all normal then goes back to  QRGameBackStage.php  The purpose of this program is to get the Kahoot data from a csv file.  
    
  
   //  echo('anything at all');
    
    
      if (isset($_POST['gmact_id'])){
        $gmact_id = $_POST['gmact_id'];
        }  
  
  
   
  
    if(isset($_POST['import_csv'])){
       //  echo('we posted');
        if($_FILES['kahoot_scores']['name']){
            // echo('got files');
            $filename=explode(".",$_FILES['kahoot_scores']['name']);
            if($filename[1]=='csv'){
                  // echo('its a csv');
                $handle = fopen($_FILES['kahoot_scores']['tmp_name'],"r");
                while($data=fgetcsv($handle)){  // data will be in array format in $data variable
                 //   echo('  data_0 type is = '.gettype($data[0]));
                   //  echo('  data_0 = '.$data[0]);
                    $pin =  htmlentities($data[0]);
                          //  echo(' pin is = '.$pin);
                          //  echo(', type of pin is  '.gettype($pin));
                          //  var_dump($pin);
                  $pin = (int) filter_var($pin,FILTER_SANITIZE_NUMBER_INT);
                 // var_dump($pin);
                           // echo(', pin is = '.$pin);
                         //   echo(', type of pin is  '.gettype($pin));
                            
                          //   echo(' data[1] is = '.$data[1]);
                           //  echo(' type of data[1] is  '.gettype($data[1]));
                  
                     $kahoot_score=htmlentities($data[1])+0;
                          //  echo(', type of kahoot_score is = '.gettype($kahoot_score));
                          //   echo(', kahoot_score is = '.$kahoot_score);
                  
                   $sql = "UPDATE `Gameactivity` 
                        SET
                       
                        kahoot_score = :kahoot_score
                        WHERE pin = :pin AND gmact_id = :gmact_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt -> execute(array(
                        ':gmact_id' => $gmact_id,
                         ':kahoot_score' => $kahoot_score,
                         ':pin' => $pin,
                        )); 
                
                
                }
            
            }
        
        
        }
        
    }



  
?>






<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRGame Import Kahoot</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>

<body>
<header>
<h1>Import the Kahoot Scores from a CSV file</h1>
<h2>File Should have Form: student pin, score</h2>
</header>   
  




<font size = "2"><?php  echo('  gmact_id = '.$gmact_id);?> </font>
<form  method="POST" enctype="multipart/form-data">
	 <div >	
        <p> Upload Kahoot CSV file <p>

        <p><font color="black">Input Kahoot Scores - csv: </font><input type='file' accept='.csv'  name='kahoot_scores'/></p>
          <p><input type="hidden" name="gmact_id" id="gmact_id" value=<?php echo($gmact_id);?> ></p>
          <p><input type = "submit" name = "import_csv" value="Import" id="submit_id" size="2" style = "width: 40%; background-color: indigo; color: white"/> &nbsp &nbsp </p>  
        </div>
	</form>
  <p style="font-size:150px;"></p>   
    
	
	


</body>
</html>






  
<?php   
   /* 
	
    // check the number of individuals on the team
        
        $sql_stmt = "SELECT COUNT(*) FROM Gameactivity WHERE team_id = :team_id AND `gmact_id`= :gmact_id ";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt->execute(array(':team_id' => $team_id,':gmact_id' => $gmact_id));
           $number_activated = $stmt->fetchColumn(); 
    
    // See what the team size is that is reported by the users
    
	 $sql_stmt = "SELECT * FROM Gameactivity WHERE `team_id`= :team_id AND `gmact_id`= :gmact_id ";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt->execute(array(':team_id' => $team_id,':gmact_id' => $gmact_id));
            $row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row2 as $row){
                    $gameactivity_id = $row['gameactivity_id'];
              
                   // update the gameactivity table to set the team_size_error to 1 
                   $sql = "UPDATE `Gameactivity` 
                        SET
                        team_size_error = 0,
                        team_size = :team_size
                        WHERE gameactivity_id = :gameactivity_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt -> execute(array(
                        ':gameactivity_id' => $gameactivity_id,
                         ':team_size' => $number_activated,
                        )); 
                       
                
            
                   // Sum up the answers for b and the last one and update the gameactivity table with those values - this is done in QRGetGamein but could be done here instead
                      $stmt = $pdo->prepare("SELECT SUM(`ans_b`) AS ans_sumb FROM `Gameactivity` WHERE gmact_id = :gmact_id AND team_id = :team_id ");
                        $stmt->execute(array(":gmact_id" => $gmact_id, ":team_id" => $team_id));
                        $row = $stmt -> fetch();
                        $ans_sumb = $row['ans_sumb'];
                        
                    $stmt = $pdo->prepare("UPDATE `Gameactivity` SET `ans_sumb` = :ans_sumb WHERE gameactivity_id = :gameactivity_id");
                        $stmt->execute(array(":gameactivity_id" => $gameactivity_id, ":ans_sumb" => $ans_sumb ));
                        
                     $stmt = $pdo->prepare("SELECT SUM(`ans_last`) AS ans_sumlast FROM `Gameactivity` WHERE gmact_id = :gmact_id AND team_id = :team_id ");
                        $stmt->execute(array(":gmact_id" => $gmact_id, ":team_id" => $team_id));
                        $row = $stmt -> fetch();
                        $ans_sumlast = $row['ans_sumlast'];   
                        
                    $stmt = $pdo->prepare("UPDATE `Gameactivity` SET `ans_sumlast` = :ans_sumlast WHERE gameactivity_id = :gameactivity_id");
                    $stmt->execute(array(":gameactivity_id" => $gameactivity_id,  ":ans_sumlast" => $ans_sumlast )); 
                        
            }       
            
            // move on to the QRGetGamein again can rely on session vars or if that gives problems use a POst and JS to submit
            
                          //  header('Location: QRGameGetIn.php');
                          //  return;   
                 
        
                    echo  "<script type='text/javascript'>";
                    echo "window.close();";
                    echo "</script>";
                    
        
    */
   ?>

