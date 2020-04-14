<?php
	require_once "pdo.php";
	session_start();
	
   
    
	
     if(isset($_POST['iid'])){
            $iid = $_POST['iid'];
        } 
        elseif (isset($_GET['iid'])){
            $iid = $_GET['iid'];
        } 
      
        else {
    
	 $_SESSION['error'] = 'invalid User_id (iid) in QRExamRetrieve.php '.$_GET['iid'];
      			header( 'Location: QRPRepo.php' ) ;
				die();
}

// this is called from the main repo and this will collect initial data from instructor and proceed to QREStart.php






    
        
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


	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRExam Retrieve</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>

<body>
<header>
<h1>Quick Response Exam - Retrieve Exam Data</h1>
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
<form id = "the_form"  method = "POST" action = "QRERetrieve.php" >
	
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
                <font color=#003399>Exam Code (latest on top of list): &nbsp; </font>
                    
                    <select id="exam_code" name = "exam_code" required >
                       <option value="0">- Select Exam -</option>
                    </select>
                </br>	
    
       
           
        
            
            
             <p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
			<p><input type = "submit" id = "submit_id"></p>
   
	
	</form>
  <p style="font-size:100px;"></p>   
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	
	<script>
	
	
	
	$(document).ready( function () {
        
		
		var currentclass_name = '';
		
			$("#currentclass_id").change(function(){
            var	 currentclass_id = $("#currentclass_id").val();
                console.log ('currentclass_id: '+currentclass_id);
				
				// need to give it 	
					$.ajax({
						url: 'getoldexam.php',
						method: 'post',
					
					data: {currentclass_id:currentclass_id}
					}).done(function(codenum){
						console.log("codenum: "+codenum);
					 console.log(codenum);
					 codenum = JSON.parse(codenum);
					 	 $('#exam_code').empty();
						var i = 0;
						n = codenum.length;
						console.log("n: "+n);
						
						for (i=0;i<n;i++){
							console.log(codenum[i]);	
							
                            var s_act=codenum[i].toString();
                            console.log(s_act);	
							 $("#exam_code").append("<option value="+codenum[i]+">"+s_act+"</option>");
							if (i != n-1){
								       
							}
						}
						
					});	
				
			
			 
		} );
        
        $("#exam_num").change(function(){
            var exam_num = $("#exam_num").val();
            console.log("exam_num: "+exam_num)
            
        });
        
        
        $("#submit_id").click(function(){
          
        /*    
          $.ajax({
             type: "POST",
             url: "QREStart.php",
             data: {currentclass_id:currentclass_id,exam_num:exam_num},
             success: function(msg) {
                alert("Form Submitted: " + msg);
             }
          });
           */
         /*  $.ajax({
				url: 'QREStart.php',
				method: 'post',
				data: {currentclass_id:currentclass_id,exam_num:exam_num}
					})
           */
          
        // $.post("QREStart.php",{currentclass_id:currentclass_id, exam_num:exam_num},);  
          
        });
	
	} );
	
	
</script>	

</body>
</html>



