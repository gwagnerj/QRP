<?php
	require_once "pdo.php";
	session_start();
	
    if (isset($_POST['iid'])) {
	$iid = $_POST['iid'];
} else {
	 $_SESSION['error'] = 'invalid User_id in QRExamStart.php ';
      			header( 'Location: QRPRepo.php' ) ;
				die();
}
// Validity will be checked in that file and sent back here if it is not valid

$_SESSION['counter']=0;  // this is for the score board
	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRExam Start</title>
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
<h1>Quick Response Exam - Setup Exam</h1>
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
<form id = "the_form"  method = "POST" action = "QREStart.php" >
	
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
                <font color=#003399>Exam Number: &nbsp; </font>
                    
                    <select id="exam_num" name = "exam_num" required >
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
					});	
		} );
        
        $("#exam_num").change(function(){
            var exam_num = $("#exam_num").val();
            console.log("exam_num: "+exam_num)
        });
	} );
	
	
</script>	

</body>
</html>



