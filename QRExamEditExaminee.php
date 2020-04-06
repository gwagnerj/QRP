<?php
	session_start();
	require_once "pdo.php";
	
// Called by QRExamBackStage.php and then if all normal then goes back to  QRExamBStage.php  The purpose of this program is to edit a single row in the Examactivity table.  
  
      if (isset($_POST['examactivity_id'])){
        $examactivity_id = $_POST['examactivity_id'];
    }  else  {
       $_SESSION['error'] = "Missing examactivity_id from QRExamEditExaminee";
	  
      echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
   
   }
 
  if (isset($_POST['submit_name']))  {  // submitted this form
       
  $sql = "UPDATE `Examactivity` 
				SET 
                    `extend_time_flag` = :extend_time_flag,
                    `suspend_flag` = :suspend_flag
                   
				WHERE examactivity_id = :examactivity_id";
                $stmt = $pdo->prepare($sql);
                $stmt -> execute(array(
                   
                    ':extend_time_flag' => htmlentities($_POST['extend_time_flag']),
                        ':suspend_flag' => htmlentities($_POST['suspend_flag']),
                    
                    ':examactivity_id' => $examactivity_id
                ));


/* 

       $sql = "UPDATE `Examactivity` 
				SET                   
                    `pin` = :pin,
                   `examtime_id` = :examtime_id,
                    `name` = :name1,
                    `suspend_flag` = :supend_flag,
                    `extend_time_flag` = :extend_time_flag
                
                   
				WHERE examactivity_id = :examactivity_id";
                
                $stmt = $pdo->prepare($sql);
                $stmt -> execute(array(
                    ':pin' => htmlentities($_POST['pin']),
                    
                    
                    ':examtime_id' => htmlentities($_POST['examtime_id']),
                    ':name1' => htmlentities($_POST['name1']),
                    ':suspend_flag' => htmlentities($_POST['suspend_flag']),
                    ':extend_time_flag' => htmlentities($_POST['extend_time_flag']),
                    
                    
                    ':examactivity_id' => $examactivity_id
                ));

 */
  }      
 
 
    
    // See what the team size is that is reported by the users
    
	 $sql_stmt = "SELECT * FROM Examactivity WHERE `examactivity_id`= :examactivity_id";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt->execute(array(':examactivity_id' => $examactivity_id));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
          //  print_r ($row);
          //  echo ("game_id = ".$row['game_id']);
            
   if(isset($_POST['close'])){
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
     }
        
   
   ?>
 <!DOCTYPE html>
	<html lang = "en">
	<head>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta Charset = "utf-8">
	<title>QRExam Edit Examinee</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 
		
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                    
                      <meta name="viewport" content="width=device-width, initial-scale=1" /> 
				<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.js"></script>
		
	</head>

	<body>
	<header>
	<h2>&nbsp; QR Edit Examinee</h2>
	 
	</header>
<form action = "" method = "POST" >

      <p>&nbsp;&nbsp; Name:  <?php echo $row['name'];?> </p>
      <p>  &nbsp;&nbsp; PIN:    <?php echo $row['pin'];?> </p>

  <!--  <p><font color=#003399>&nbsp; pin </font><input type="number" name="pin" id = "pin" value = <?php echo $row['pin'];?> size=3 width = "40px"></p> 
    <p><font color=#003399>&nbsp; examtime_id </font><input type="number" name="examtime_id" id = "examtime_id" value = <?php echo $row['examtime_id'];?> size=3 width = "40px"></p>
    <p><font color=#003399>&nbsp; name </font><input type="text" name="name1" id = "name1" value = "<?php echo $row['name'];?>" size=3 width = "40px"></p>
    
  -->
  
    <p><font color=#003399>&nbsp; suspend_flag </font><input type="number" name="suspend_flag" id = "suspend_flag" min = 0 max =1 value = "<?php echo $row['suspend_flag'];?>" size=3 width = "40px"></p>
    <p><font color=#003399>&nbsp; extend_time_flag </font><input type="number" name="extend_time_flag" id = "extend_time_flag" min = 0 max =1 value = "<?php echo $row['extend_time_flag'];?>" size=3 width = "40px"></p>
   
      
    <p> When all the info is updated select "Submit" </p>
	<input type="hidden" name="examactivity_id" value="<?php echo ($examactivity_id)?>"  >
    
	<p><input type = "submit" value="Submit" name = "submit_name" size="14" style = "width: 30%; background-color: blue; color: white"/> &nbsp &nbsp </p>
	</form>
    
     <p style="font-size:150px;"></p>   
      <form method="POST" >
           <p><input type="hidden" name="examactivity_id" id="examactivity_id" value=<?php echo($examactivity_id);?> ></p>
         <p><input type = "submit" name = "close" value="Exit - Close Window" id="close_id" size="2" style = "width: 40%; background-color: black; color: white"/> &nbsp &nbsp </p>
      </form>
      
      
      
      
      
      </body>
      </html>
      
      