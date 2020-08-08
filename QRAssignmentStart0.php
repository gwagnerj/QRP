<?php
	require_once "pdo.php";
	session_start();
	
	
    if (isset($_POST['iid'])) {
	$iid = $_POST['iid'];
} elseif(isset($_GET['iid'])){
    $iid = $_GET['iid'];
} else {
	 $_SESSION['error'] = 'invalid User_id in QRAssignmentStart0 ';
      			header( 'Location: QRPRepo.php' ) ;
				die();
}
// fix bug if no class is selected and get a pdo error____________________________________________





if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_name']) ) {
    
 
   // see if the We already have a entry in the Assigntime table for this one or its new
   
   $new_flag = 0;
   
   
    $sql = 'SELECT assigntime_id FROM Assigntime WHERE currentclass_id = :currentclass_id AND iid = :iid AND assign_num = :assign_num';     
          $stmt = $pdo->prepare($sql);
           $stmt -> execute(array (
           ':currentclass_id' => $_POST['currentclass_id'],
           ':assign_num' => $_POST['active_assign'],
           ':iid' => $_POST['iid'],
           )); 
            $assigntime_data = $stmt->fetch();   
           if ($assigntime_data == false){
                $new_flag = 1;
           }
        
          // echo('assigntime_id: '.$assigntime_id);
          // now go to the QRAssignmentStart2 with the assigntime_id 
                        header( 'Location: QRAssignmentStart1.php?currentclass_id='.$_POST["currentclass_id"].'&assign_num='.$_POST['active_assign'].'&iid='.$_POST['iid'].'&new_flag='.$new_flag);
                        die();
         
        
}

// this is called from the main repo and this will Collect the information on a particular assignment from the instructor then moves onto .  THis file was coppied form QRExamStart.php

        
/* 		
			$alias_num = $exam_num = $cclass_id = '';   
			
			
            $sql_stmt = "SELECT * FROM Exam WHERE DATE(NOW())<= exp_date AND iid = :iid order by exam_id";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt -> execute(array(':iid' => $iid));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 */
     
// this will be called form the main repo when the game master wants to run a game
// this is just to get the game number and go on to QRGMaster.php with a post of the game number.
// Validity will be checked in that file and sent back here if it is not valid

$_SESSION['counter']=0;  // this is for the score board



	if ( isset($_SESSION['error']) ) {
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) ) {
			echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
			unset($_SESSION['success']);
		}

	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QR Assignment Start</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<style>
   .outer {
  width: 100%;
  margin: 0 auto;
 
}

.inner {
  margin-left: 50px;
 
} 


</style>



</head>

<body>
<header>
<h1>Quick Response Assignment Setup</h1>
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
	
 
 
 
?>

<!--<h3>Print the problem statement with "Ctrl P"</h3>
 <p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p>  -->
<form id = "the_form"  method = "POST"  >
	
    <div id ="current_class_dd">	
				Course: &nbsp;
				<select name = "currentclass_id" id = "currentclass_id">
				 <option value = "" selected disabled hidden > Select Course  </option> 
				<?php
                   
					$sql = 'SELECT * FROM `CurrentClass` WHERE `iid` = :iid';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(':iid' => $iid));
					while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) 
						{ ?>
						<option value="<?php echo $row['currentclass_id']; ?>" ><?php echo $row['name']; ?> </option>
						<?php
 							}
                    ?>
                    
                    
				</select>
		</div>
             </br>
                <font color=#003399>Assignment Number: &nbsp; </font>
                    
                    <select id="active_assign" name = "active_assign" required >
                       <option value="0">- Select Assignment -</option>
                    </select>
                </br>	
                <br>
            
                    
            <p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
			<p><input type = "submit" name = "submit_name" id = "submit_id" value = "Start / Edit Assignment Activation"></p><hr><br>
            <p><input type="submit" formaction="remove_assignment.php" value="Deactivate Assignment"></p><br>
             <p><input type="submit" formaction="stu_assignment_results.php" value="Student Results"></p><br>
            <p><input type="submit" formaction="stu_login_info.php" value="See Student Login Information"></p>

	
	</form>
  <p style="font-size:100px;"></p>   
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	
	<script>
	
	
	
	$(document).ready( function () {
		
		var currentclass_name = "";
		
			$("#currentclass_id").change(function(){
            var	 currentclass_id = $("#currentclass_id").val();
                console.log ('currentclass_id: '+currentclass_id);
				
				// need to give it 	
					$.ajax({
						url: 'getactiveassignments.php',
						method: 'post',
					
					data: {currentclass_id:currentclass_id}
					}).done(function(activeass){
						console.log("activeass: "+activeass);
					 console.log(activeass);
					 activeass = JSON.parse(activeass);
					 	 $('#active_assign').empty();
						var i = 0;
						n = activeass.length;
						console.log("n: "+n);
						for (i=0;i<n;i++){
							console.log(activeass[i]);	
                            var s_act=activeass[i].toString();
                            console.log(s_act);	
							 $("#active_assign").append("<option value="+activeass[i]+">"+s_act+"</option>");
							if (i != n-1){

							}
						}
						
					});	
				
			
			 
            } );
        
      

});
        
       
	
	
	
	
</script>	

</body>
</html>



