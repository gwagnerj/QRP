<?php
require_once 'pdo.php';
session_start();

if (isset($_POST['cclass_id'])) {
    $student_id = $_POST['student_id'];

    $currentclass_id = $_POST['cclass_id'];
    // check to see if they already have an entry in the StudentCurrentClassConnect table
    $sql =
        'SELECT COUNT(pin) as counts FROM StudentCurrentClassConnect WHERE currentclass_id = :currentclass_id AND student_id = :student_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':currentclass_id' => $currentclass_id,
        ':student_id' => $student_id,
    ]);
    $count_data = $stmt->fetch();
    $count_pin = $count_data['counts'];
    if ($count_pin != 0) {
        $_SESSION['error'] =
            'student already has an entry for this class in stu_getclass';
        header('Location: stu_getclass.php?student_id=' . $student_id);
        return;
    }

    // now need to get a pin for the student and put it in the StudentCurrentClassConnect table
    $sql =
        'SELECT MAX(pin) AS largestpin FROM StudentCurrentClassConnect WHERE currentclass_id = :currentclass_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':currentclass_id' => $currentclass_id]);
    $sccc_data = $stmt->fetch();
    $largestpin = $sccc_data['largestpin'];
    $pin = $largestpin + 1;

    // now ,make an entry in the StudentCurrentClassConnect table
    $sql =
        'INSERT INTO StudentCurrentClassConnect (currentclass_id, student_id, pin) VALUES (:currentclass_id, :student_id, :pin)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':currentclass_id' => $currentclass_id,
        ':student_id' => $student_id,
        ':pin' => $pin,
    ]);

    $_SESSION['success'] =
        'signup for class successful - you may now access assignments for this class';
    header('Location: stu_frontpage.php?student_id=' . $student_id);
    return;
}

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    $sql = 'SELECT * FROM Student WHERE `student_id` = :student_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':student_id' => $student_id]);
    $student_data = $stmt->fetch();
    $first_name = $student_data['first_name'];
    $last_name = $student_data['last_name'];
    $university = $student_data['university'];
} else {
    $_SESSION['error'] = 'student_id not set in stu_getclass';
}
// this is the normal place to start for students checking their homework and goes the the QRcontroller.  Can also come from the rtnCode.php or the back button on QRdisplay.php  This will

// first time thru set scriptflag to zero - this will turn to 1 if the script ran
//	if (!isset($sc_flag)){$sc_flag=0;}

if (isset($_POST['reset'])) {
    $iid = '';
    $stu_name = '';
    $pin = '';
    $last = '';
    $first = '';
    $alias_num = $assign_num = $cclass_id = $activity_id = '';
    //	session_destroy();
}

if (isset($_SESSION['error'])) {
    echo $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRHomework</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>

<body>
<header>
<h1>Please Select Your Class </h1>
</header>

<?php if (
    isset($_POST['pin']) ||
    isset($_POST['problem_id']) ||
    isset($_POST['iid'])
) {
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p style="color:green">' . $_SESSION['success'] . "</p>\n";
        unset($_SESSION['success']);
    }
} ?>

<form autocomplete="off" method="POST" >
	  
	<p><font color=#003399>Name: <?php echo $first_name . ' ' . $last_name; ?> </p>
	<input autocomplete="false" name="hidden" type="text" style="display:none;">
	<div id ="instructor_id">	
				<font color=#003399> Instructor: &nbsp; </font>
				<?php
    echo '<select name = "iid" id = "iid">';
    echo '	<option value = "" selected disabled hidden >  Select Instructor  </option> ';
    $sql =
        'SELECT DISTINCT iid, last, first FROM Users RIGHT JOIN CurrentClass ON Users.users_id = CurrentClass.iid';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
								<option value="<?php echo $row['iid']; ?>" ><?php echo $row['last'] .
    ', ' .
    $row['first']; ?> </option>
							<?php }
    echo '</select>';
    ?>
						
				
				</div>
                
                 <input type="hidden" id = "student_id" name="student_id" value="<?php echo $student_id; ?>" >
				</br>
	
<!--	<div id ="current_class_dd">	-->
			<font color=#003399>Course: </font>
			
			<?php
   echo '&nbsp;<select name = "cclass_id" id = "current_class_dd" >';

   echo '</select>';
   ?>
		</br>	
		
	<p><input type = "submit" name = "submit" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	</form>
	</br>
	<form method = "POST">
		<p><input type = "submit" value="Reset Input" name = "reset"  size="2" style = "width: 30%; background-color: #FAF1BC; color: black"/> &nbsp &nbsp </p>  
	</form>

<script>
	
		$("#iid").change(function(){
		var	 iid = $("#iid").val();
		 $('#alias_num_div').empty();
		  $('#assign_num').empty();
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
						$('#current_class_dd').append("<option selected disabled hidden> Please Select Course </option>") ;
						for (i=0;i<n;i++){
							
							  $('#current_class_dd').append('<option  value="' + cclass_id[i] + '">' + cclass[i] + '</option>');
					}
				})
			})
		});
			
		
			
	
		
</script>



</body>
</html>



