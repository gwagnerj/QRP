<?php
	require_once "pdo.php";
	session_start();
	
/* 	
    if (isset($_POST['iid'])) {
	$iid = $_POST['iid'];
} else {
	 $_SESSION['error'] = 'invalid User_id in QRExamPrint0.php ';
      			header( 'Location: QRPRepo.php' ) ;
				die();
}
 */
$iid = 1;  // temporary
	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRExam Print</title>
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
<h1>Quick Response Exam - Print Exam</h1>
	 <!DOCTYPE html>
	<html lang = "en">
	<head>
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<meta Charset = "utf-8">
	<title>QR Exam Print</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 

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
<form id = "the_form"  method = "POST" action = "QRExamPrint1.php" >
	
    <div id ="current_class_dd">	
				Course: &nbsp;
				<select name = "currentclass_id" id = "currentclass_id" required>
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
                <font color=#003399>Exam Number: &nbsp; </font>
                    
                    <select id="exam_num" name = "exam_num" required >
                       <option value="" selected disabled hidden >- Select Exam -</option>
                    </select>
                </br>	
    
    
         
                      <font color=#003399>Exam Versions: &nbsp; </font>
                 
                   <div  class = "outer" >
                     <div  class = "inner" >
                 
                             <div>
                               <input type = "radio" name ="exam_version" id = "exam_version" value = "1" checked > </input>
                                   <label for "exam_version"> Different for Every Examinee </label>
                             </div>
                               </br>
                             <div  >
                                <input type = "radio" name ="exam_version" id = "exam_version" value = "2" > </input>
                                   <label for "exam_version">   
                                    <input    type = "number" name ="num_versions" id = "num_versions" min = "1", max = "999" value = "4" required  > min</input>
                                   
                                   </label>   
                            </div>
                            
                       </div>
                   </div>
                  </br>
     
            
             <p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
			<p><input type = "submit" id = "submit_id"></p>
   
	
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
						url: 'getactiveexam.php',
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
						// $('#active_assign').append("&nbsp;&nbsp;&nbsp;&nbsp;Current exams in system for this course: ") ;	
						for (i=0;i<n;i++){
							console.log(activeass[i]);	
							//$('#exam_num').append(activeass[i]) ;	
                            var s_act=activeass[i].toString();
                            console.log(s_act);	
							 $("#exam_num").append("<option value="+activeass[i]+">"+s_act+"</option>");
							if (i != n-1){
								          //    $("#exam_num").append("<option value='"+activeass[i]+"'></option>");

							}
						}
						
					});	
				
			
			 
		} );
        
        $("#exam_num").change(function(){
            var exam_num = $("#exam_num").val();
            console.log("exam_num: "+exam_num)
            
        });
        
        
        $("#submit_id").click(function(){
          
  
          
        });
	
	} );
	
	
</script>	

</body>
</html>



