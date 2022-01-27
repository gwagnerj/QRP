<?php
require_once 'pdo.php';
session_start();

if (isset($_POST['iid'])) {
    $iid = $_POST['iid'];
} elseif (isset($_GET['iid'])) {
    $iid = $_GET['iid'];
} else {
    $_SESSION['error'] = 'invalid User_id in QRQuestionMgmt ';
    header('Location: QRPRepo.php');
    die();
}
// fix bug if no class is selected and get a pdo error____________________________________________

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_name'])) {
    // see if the We already have a entry in the Assigntime table for this one or its new

    $new_flag = 0;

    $sql =
        'SELECT assigntime_id FROM Assigntime WHERE currentclass_id = :currentclass_id AND iid = :iid AND assign_num = :assign_num';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':currentclass_id' => $_POST['currentclass_id'],
        ':assign_num' => $_POST['active_assign'],
        ':iid' => $_POST['iid'],
    ]);
    $assigntime_data = $stmt->fetch();
    if ($assigntime_data == false) {
        $new_flag = 1;
    }
    /*     
         //  echo(' assigntime_id: '.$assigntime_id);
           echo(' currentclass_id: '.$_POST['currentclass_id']);
           echo(' assign_num: '.$_POST['active_assign']);
           echo(' iid: '.$_POST['iid']);
           echo(' new_flag: '.$new_flag);
           die();
          */

    // now go to the QRAssignmentStart2 with the assigntime_id
    header(
        'Location: QRAssignmentStart1.php?currentclass_id=' .
            $_POST['currentclass_id'] .
            '&assign_num=' .
            $_POST['active_assign'] .
            '&iid=' .
            $_POST['iid'] .
            '&new_flag=' .
            $new_flag
    );
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

$_SESSION['counter'] = 0; // this is for the score board

if (isset($_SESSION['error'])) {
    echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo '<p style="color:green">' . $_SESSION['success'] . "</p>\n";
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
<h1>Quick Response Question Managment</h1>
</header>

<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo '<p style="color:green">' . $_SESSION['success'] . "</p>\n";
    unset($_SESSION['success']);
}
?>

<!--<h3>Print the problem statement with "Ctrl P"</h3>
 <p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p>  -->
<form id = "the_form"  method = "POST"  >
	
    <div id ="discipline_id">	
				Discipline: &nbsp;
				<select name = "discipline_id" id = "discipline-id">
				 <option value = "" selected disabled hidden > Select Discipline</option>  
				<?php
    $sql = 'SELECT * FROM `Discipline`';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
						<option value="<?php echo $row['discipline_id']; ?>" ><?php echo $row[
    'discipline_name'
]; ?> </option>
						<?php }
    ?>
                    
                    
				</select>
		</div>

        </br>
    <div id ="current_class_dd">	
				Current Class: &nbsp;
				<select name = "currentclass_id" id = "currentclass_id">
				 <option value = "" selected disabled hidden > Select Course  </option> 
				<?php
    $sql = 'SELECT * FROM `CurrentClass` WHERE `iid` = :iid';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':iid' => $iid]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
						<option value="<?php echo $row['currentclass_id']; ?>" ><?php echo $row[
    'name'
]; ?> </option>
						<?php }
    ?>
                    
                    
				</select>
		</div>
            <br>
                <font color=#003399>Current Course &nbsp; </font>
                    
                    <select id="current-course" name = "current_course" required >
                       <option value="0">- Select Current Course -</option>
                    </select>
                <br>

                <br>
                <font color=#003399>Current Concept &nbsp; </font>
                    
                    <select id="active_assign" name = "active_assign" required >
                       <option value="0">- Select Current Course -</option>
                    </select>
                 <br>
                <br>
                <font color=#003399>Past Courses &nbsp; </font>
                    
                    <select id="active_assign" name = "active_assign" required >
                       <option value="0">- Select Current Course -</option>
                    </select>
              <br>
                    
            <p><input type="hidden" name="iid" id="iid" value=<?php echo $iid; ?> ></p>
			<p><input type="hidden" name="where_from" id="where_from" value="QRAssignmentStart0" ></p>
			<p><input type = "submit" name = "submit_name" id = "submit_id" value = "Start / Edit Assignment Activation"></p><hr><br>
            <!-- <p><input type="submit" formaction="remove_assignment.php" value="Deactivate Assignment"></p><br>
             <p><input type="submit" formaction="stu_assignment_results.php" value="Student Results">  &nbsp;&nbsp; Load Student Work Images (slow) <input type="checkbox" name="load_images" id = "load_images" checked ></p><br>
            <p><input type="submit" formaction="stu_login_info.php" value="See Student Login Information"></p> -->
            <!-- <p><input type="submit" formaction="see_all_assignments.php" target = "_blank" value="See all Assignments for your Classes"></p> -->
            <!-- <a href ="see_all_assignments.php?iid=<?php echo $iid?>"target="_blank"><button>See All your Assignments In New </button></a> -->

	
	</form>
<!-- 
    <p style="font-size:100px;"></p>   
            <a href ="see_all_assignments.php?iid=<?php echo $iid?>"target="_blank"><button>See All Your Assignments in New Tab</button></a> -->

  <p style="font-size:20px;"></p>   
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	
	<script>
	
	
	
</script>	

</body>
</html>



