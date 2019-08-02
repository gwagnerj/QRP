<?php
require_once "pdo.php";

session_unset();
session_start();
$_SESSION['checker']=1;





?>

<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRProblems</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
<header>
<h1>Quick Response Problems</h1>
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
$iid='';
$p_num = $pin =  "";
$index = "";
$assign_num = $alias_num = '';
$gs_num = "";
?>

<form action = "QRChecker.php" method = "GET" autocomplete="off">
<!--	<p><font color=#003399>Problem Number: </font><input type="number" name="problem_id" required min = "1" size=3 value="<?php echo (htmlentities($p_num))?> "  ></p>-->
	

	<p><font color=#003399>Your PIN: </font><input type="number" min = "1" max = "10000" name="pin" id="pin_id" size=3 required value=<?php echo($pin);?> ></p>
	<div id ="instructor_id">	
				<font color=#003399> Instructor: &nbsp; </font>
				<select name = "iid" id = "iid">
				<option value = "" selected disabled hidden >  Select Instructor  </option> 
				
				<?php
				
				$sql = 'SELECT DISTINCT iid, last, first FROM Users RIGHT JOIN CurrentClass ON Users.users_id = CurrentClass.iid';
				$stmt = $pdo->prepare($sql);
					$stmt -> execute();
					while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) 
				{ ?>
						<option value="<?php echo $row['iid']; ?>" ><?php echo ($row['last'].",".$row['first']); ?> </option>
						<?php
 							} 
						?>
				
						
				</select>
				</div>
				</br>
	
<!--	<div id ="current_class_dd">	-->
			<font color=#003399>Course: </font>
			 &nbsp;<select name = "cclass_id" id = "current_class_dd">
		
		</select>
		</br>	
		</br>
		<font color=#003399>Assignment Number: </font>
			 &nbsp;<select name = "assign_num" id = "assign_num">
			
		
		</select>
		</br>	
		<br>
		
		<div id = "alias_num_div">
		
		</div>
		</br>	
		<br>
		
	<p><input type = "submit" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	</form>	
	
	
	
	
	
	<!--
	<p><font color=#003399>Assignment Number: </font><input type="number"  name="assign_num" required min = "1" max = "10000" size=3 value="<?php echo (htmlentities($assign_num))?> "  ></p>
	<p><font color=#003399>Problem Number: </font><input type="number"  name="alias_num" required min = "1" max = "10000" size=3 value="<?php echo (htmlentities($alias_num))?> "  ></p>
	<p><font color=#003399>Your PIN: </font><input type="number"  name="pin" required min = "1" max = "10000" size=3 value="<?php echo (htmlentities($index))?> "  ></p>
	<p><font color=#003399>Instructor ID: </font><input type="number" name="iid" required min = "1" max="10000" id="iid" size=5 value=<?php echo($iid.' ');?> >
	<font color=#003399 >  &nbsp; &nbsp; &nbsp;  or if you don't know: <a href="getiid.php"><b>Click Here</b></a></font></p>
	
	<!-- <p><font color=#003399>Grading Scheme Number: </font><input type="text" name="gs_num" size=3 value="<?php echo (htmlentities($gs_num))?>"  ></p> 

	<p><input type = "submit" value="Submit" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>
	</form>-->

<script>
	$("#iid").change(function(){
		var	 iid = $("#iid").val();
			$.ajax({
					url: 'getcurrentclass.php',
					method: 'post',
						data: {iid:iid}
				
				}).done(function(cclass){
					cclass = JSON.parse(cclass);
					 // now get the currentclass_id
			$.ajax({
					url: 'getcurrentclass_id.php',
					method: 'post',
						data: {iid:iid}
				
				}).done(function(cclass_id){
					cclass_id = JSON.parse(cclass_id);
					 $('#current_class_dd').empty();
					n = cclass.length;
				//		console.log("n: "+n);
						$('#current_class_dd').append("<option> Please Select Course </option>") ;
						for (i=0;i<n;i++){
							
							  $('#current_class_dd').append('<option  value="' + cclass_id[i] + '">' + cclass[i] + '</option>');
					}
				})
			})
		});
			
			
			
			// this is getting the assignment number once the course has been selected
			$("#current_class_dd").change(function(){
		var	 currentclass_id = $("#current_class_dd").val();
			console.log ('currentclass_id: '+currentclass_id);
			$.ajax({
					url: 'getactiveassignments.php',
					method: 'post',
					data: {currentclass_id:currentclass_id}
				}).done(function(activeass){
					activeass = JSON.parse(activeass);
					 	 $('#assign_num').empty();
						
				
					n = activeass.length;
						$('#assign_num').append("&nbsp;&nbsp;&nbsp;&nbsp;Assignment for this courses : ") ;
						for (i=0;i<n;i++){
							  $('#assign_num').append('<option  value="' + activeass[i] + '">' + activeass[i] + '</option>');
					}
				}) 
			});
			
			// this is getting the problem numbers (alias number) once the course has been selected
			$("#assign_num").change(function(){
		var	 assign_num = $("#assign_num").val();
		var	 currentclass_id = $("#current_class_dd").val();
			console.log ('currentclass_id 2nd time: '+currentclass_id);
			$.ajax({
					url: 'getactivealias.php',
					method: 'post',
					data: {assign_num:assign_num,currentclass_id:currentclass_id}
				
				}).done(function(activealias){
				
					activealias = JSON.parse(activealias);
					 	 $('#alias_num').empty();
					n = activealias.length;
						$('#alias_num_div').append(" <font color=#003399> Select Problem for this Assignment : </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;
						for (i=0;i<n;i++){
							$('#alias_num_div').append('<input  name="alias_num"  type="radio"  value="'+activealias[i]+'"/> '+activealias[i]+'&nbsp; &nbsp; &nbsp;') ;

					}
					
				}) 
			});

</script>


</body>
</html>


