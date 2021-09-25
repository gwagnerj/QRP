<?php
	require_once "pdo.php";
	session_start();
	
	
    if (isset($_POST['iid'])) {
	$iid = $_POST['iid'];
} elseif(isset($_GET['iid'])){
    $iid = $_GET['iid'];
} else {
	 $_SESSION['error'] = 'invalid User_id in QRExamMgmt ';
      			header( 'Location: QRPRepo.php' ) ;
				die();
}
// fix bug if no class is selected and get a pdo error____________________________________________

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_name']) ) {
    
 
   // see if the We already have a entry in the Assigntime table for this one or its new
   
   $new_flag = 0;
   
   
    $sql = 'SELECT eexamtime_id FROM Eexamtime WHERE currentclass_id = :currentclass_id AND iid = :iid AND exam_num = :exam_num';     
          $stmt = $pdo->prepare($sql);
           $stmt -> execute(array (
           ':currentclass_id' => $_POST['currentclass_id'],
           ':exam_num' => $_POST['exam_num'],
           ':iid' => $iid,
           )); 
            $examtime_data = $stmt->fetch();   
           if ($examtime_data == false){
                $new_flag = 1;
           }
   
          // now go to the QRExamOption with the assigntime_id 
                        header( 'Location: QRExamOption.php?currentclass_id='.$_POST["currentclass_id"].'&exam_num='.$_POST['exam_num'].'&iid='.$iid.'&new_flag='.$new_flag);
                        die();
}
// this is called from the main repo and this will Collect the information on a particular assignment from the instructor then moves onto .  THis file was coppied form QRExamStart.php

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
<title>QR Exam Start</title>
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
<h1>Quick Response Exam Management</h1>
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

<form id = "the_form"  method = "POST">
	
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
                       <option value="0">- Select Option -</option>
                    </select>
                </br>	
                <br>
                <p><input type="hidden" name="iid" id="iid" value=<?php echo($iid);?> ></p>
                <p><input type="hidden" name="eexamtime_id" id="eexamtime_id" value='' ></p>
                <p><input type="hidden" name="from_QRExamMgmt" id=from_QRExamMgmt" value=true ></p>
			    <p><input type = "submit" name = "submit_name" id = "submit_id" value = "Points and Options"></p><br>
				
				<p><input type="submit" formaction="QRExamRetrieve.php" value="Student Exam Results">  &nbsp;&nbsp; Load Student Work Images (slow) <input type="checkbox" name="load_images" id = "load_images" checked ></p><br>

			<div id = "button_group">	
				<p><input class = "exam_buttons" type="submit" formaction="remove_exam_0.php" value="ReStart or Remove Exam or Game"></p><br>
				
				&nbsp;&nbsp;&nbsp; <input class = "exam_buttons" checked type="radio"  name="groups" id="no_groups" value = "no">  Individual &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</input> 
				 <input class = "exam_buttons"  type="radio" name="groups" id="groups" value = "yes"> Group </input>
             		 <span  id="number_teams"> Number of Groups <input type="number" min = "0" max = "99" name="number_teams" value=5 ></input></span> 
					  <p>  
					  &nbsp;&nbsp;&nbsp;	<input class = "exam_buttons" checked type="radio"  name="game_flag" id="no_game" value = "0">Quiz or Exam &nbsp;&nbsp;</input> 
					<input class = "exam_buttons"  type="radio" name="game_flag" id="game" value = "1"> Game </input>
				
				<p><input class = "exam_buttons" type="submit" formaction="QREStart.php" value="Start Examination"></p><br> <!--This button should only be present if the selected Exam has already setup-->
				
  
             </p>

				<p><input class = "exam_buttons" type="submit" formaction="QRExamPrint0.php" value="Print Examination"></p><br> <!--This button should only be present if the selected Exam has already setup-->
			</div>	
             <p><input id = "get_student_login_data" type="submit" formaction="stu_login_info.php"   value="See Student Login Information"></p>
          
	</form>
  <p style="font-size:100px;"></p>   
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	
	<script>
	$(document).ready( function () {
		$('#button_group').hide();
		var currentclass_name = "";
			$("#currentclass_id").change(function(){
			var	 currentclass_id = $("#currentclass_id").val();

							$('#exam_num').empty();
						  $("#exam_num").append("<option value = '0'>-select option-</option>");
			
                // console.log ('currentclass_id: '+currentclass_id);
				// need to give it 	
					$.ajax({   // this looks in the Eexam table for the values
						url: 'getstagedexam.php',
						method: 'post',
					
					data: {currentclass_id:currentclass_id}
					}).done(function(activeass){
					//	console.log("activeass: "+activeass);
					// console.log(activeass);
					 activeass = JSON.parse(activeass);
						  $('#exam_num').empty();
						  $("#exam_num").append("<option value = '0'>-select option-</option>");
					//	  $("#exam_num").append("-Select Option -");

						var i = 0;
						n = activeass.length;
					//	console.log("n: "+n);
						for (i=0;i<n;i++){
						//	console.log(activeass[i]);	
                            var s_act=activeass[i].toString();
                         //   console.log(s_act);	
							 $("#exam_num").append("<option value="+activeass[i]+">"+s_act+"</option>");
							if (i != n-1){
							}
						}
					});	

					

			} );

			$("#exam_num").add("#currentclass_id").on('change',function(){
						// check to see if the exam or game has been setup and then display the "Start Examination" or "Start Game" buttons
							// do an ajax call and get the examOrGameReady url to check the status of the selected exam or game_id it will check the Eexamtime table
							var examtime_id = 0;
							var game_flag = null;
							var exam_num = 0;
							var	 currentclass_id = $("#currentclass_id").val();
			//				console.log ('currentclass_id: '+currentclass_id);
							var	 exam_num = $("#exam_num").val();
			//				console.log ('exam_num: '+exam_num);
							var activegameorexam = '';
							if (exam_num >0){
								$('#submit_id').show();
								$('#button_group').hide();
									$.ajax({   // this looks in the Eexam table for the values
										url: 'getactivegameorexam.php',
										method: 'post',
						
									data: {currentclass_id:currentclass_id,exam_num:exam_num}
									}).done(function(activegameorexam){
								
									activegameorexam = JSON.parse(activegameorexam);
								if(activegameorexam !== undefined){
									var game_flag = activegameorexam[0][0];


									console.log('game_flag: '+game_flag);
									 eexamtime_id = activegameorexam[0][1];
									 $("#eexamtime_id").val(eexamtime_id);
									console.log('eexamtime_id: '+eexamtime_id);
								} else {
									eexamtime_id = 0;
									game_flag = 0;
								}

								
							
								if (eexamtime_id >0 && ( game_flag==null || game_flag==0)){
									$('#button_group').show();
									$('#game_button').hide();	
									$('.exam_buttons').show();	

								} else if(eexamtime_id >0 && game_flag==1) {
									$('#button_group').show();
									$('#game_button').show();	
									$('.exam_buttons').show();
									$('#load_images').hide();	
								} else {
									$('#game_button').hide();	
									$('.exam_buttons').show();	

								}
								
								})

							} else {
								$('#button_group').show();
								$('#submit_id').hide();
								$('.exam_buttons').show();	
							}
							
						

			})
			$('#exam_num').trigger('change');
			
			$('#number_teams').hide();
			$('#number_teams').val(0);
			$('#groups').change(function(){
				if (this.checked){
					$('#number_teams').show();
					$('#number_teams').val(5);
				}
			});
			$('#no_groups').change(function(){
				if (this.checked){
					$('#number_teams').hide();
					$('#number_teams').val(0);
				}
			});
				
			
});
        
</script>	

</body>
</html>



