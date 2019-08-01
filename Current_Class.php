<?php
require_once "pdo.php";
session_start();
// this is to set the class list for a particular semester for an instructor with multiple classes that use the system
// activated by a link from QRPRepo so will have information in session varaibles

if (isset($_SESSION['username'])) {
	$username=$_SESSION['username'];
} else {
	 $_SESSION['error'] = 'Session was lost -  please log in again';
	header('Location: QRPRepo.php');
	return;
}

if (isset($_SESSION['iid'])) {
	$iid=$_SESSION['iid'];
} else {
	
	// go get it from the Users table
	$sql = "SELECT * FROM Users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':username' => $username));
	$users_data = $stmt -> fetch();
	$iid = $users_data['iid'];
	$_SESSION['iid'] = $iid;
}


			if($_SERVER["REQUEST_METHOD"] == "POST"){
				
			if (isset($_POST['name']))	{
				$name = htmlentities($_POST['name']);
			} else {
				$_SESSION['error'] = 'Course name must be provided';
			/* 	header( 'Location: QRPRepo.php' ) ;
				return;  */
			}
			 
				 if(isset($_POST['sec_desig_1'])){ $sec_desig_1 = htmlentities($_POST['sec_desig_1']);} else {$sec_desig_1 = '';}
				 if(isset($_POST['sec_desig_2'])){ $sec_desig_2 = htmlentities($_POST['sec_desig_2']);} else {$sec_desig_2 = '';}
				 if(isset($_POST['sec_desig_3'])){ $sec_desig_3 = htmlentities($_POST['sec_desig_3']);} else {$sec_desig_3 = '';}
				 if(isset($_POST['sec_desig_4'])){ $sec_desig_4 = htmlentities($_POST['sec_desig_4']);} else {$sec_desig_4 = '';}
				 if(isset($_POST['sec_desig_5'])){ $sec_desig_5 = htmlentities($_POST['sec_desig_5']);} else {$sec_desig_5 = '';}
				 if(isset($_POST['sec_desig_6'])){ $sec_desig_6 = htmlentities($_POST['sec_desig_6']);} else {$sec_desig_6 = '';}
				 if(isset($_POST['exp_date'])){ $exp_date = htmlentities($_POST['exp_date']);} else {$exp_date = '';} // could make this now + 6 months later
				
			 
			 // Prepare an insert statement
					$sql = "INSERT INTO `CurrentClass` (name, iid, sec_desig_1, sec_desig_2,sec_desig_3, sec_desig_4,sec_desig_5, sec_desig_6, exp_date)
					VALUES (:name, :iid, :sec_desig_1, :sec_desig_2, :sec_desig_3, :sec_desig_4, :sec_desig_5, :sec_desig_6, :exp_date)";
					 
				   
						$stmt = $pdo->prepare($sql);
						$stmt->execute(array(
							':name' => $name,
							':iid' => $iid,
							':sec_desig_1' => $sec_desig_1,	
							':sec_desig_2' => $sec_desig_2,					
							':sec_desig_3' => $sec_desig_3,					
							':sec_desig_4' => $sec_desig_4,					
							':sec_desig_5' => $sec_desig_5,					
							':sec_desig_6' => $sec_desig_6,					
							':exp_date' => $exp_date
							));
							$_SESSION['success'] = 'Course has been added';
/* 
							header( 'Location: QRPRepo.php' ) ;
							return; 
 */
		}

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
<title>QRProblems - Current Class Setup</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
</head>

<body>
<header>
<h2>Current Courses In System </h2>
</header>	
	 <div class="wrapper">
       
        <form  method="post">
			<table id="table_format" class = "a" border="1" >
			<thead>
			</td>
			<th> Function </th>
			<th> Class Number </th>
			<th> Course Name</th>
			<th> Sect 1 name </th>
			<th> Sect 2 name </th>
			<th> Sect 3 name </th>
			<th> Sect 4 name </th>
			<th> Sect 5 name </th>
			<th> Sect 6 name </th>
			<th> Expires </th>
			
			<tr>
			</thead> <tbody>
			<?php
			
			// get the current list of the courses for this instructor	
			$sql = "SELECT * FROM `CurrentClass` WHERE iid = :iid";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(':iid' => $iid));
			while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				echo "<tr><td>";
			
					echo('<a href="deletecourse.php?currentclass_id='.$row['currentclass_id'].'"><b> Delete</b></a>');

			//echo('<form action = "deletecourse.php" method = "POST"> <input type = "hidden" name = "currentclass_id" value = "'.$row['currentclass_id'].'"><input type = "submit" value ="Delete"></form>');
				echo "</td><td>";
				
				echo(htmlentities($row['currentclass_id']));
				echo "</td><td>";
				
				echo(htmlentities($row['name']));
				
					echo "</td><td>";
				echo(htmlentities($row['sec_desig_1']));
				echo "</td><td>";
				echo(htmlentities($row['sec_desig_2']));
					echo "</td><td>";
				echo(htmlentities($row['sec_desig_3']));
					echo "</td><td>";
				echo(htmlentities($row['sec_desig_4']));
					echo "</td><td>";
				echo(htmlentities($row['sec_desig_5']));
					echo "</td><td>";
				echo(htmlentities($row['sec_desig_6']));
					echo "</td><td>";
				echo(htmlentities($row['exp_date']));
				echo "</td><tr>";
				
			}
		
			?>
			
			</td></tr>
			</tbody>
			</table>
			
	<h2>Add A Course: </h2>			
			<form method = "POST">				
			Course Name (e.g. Fluid Mechanics I) <br> <input type= "text" Name="name" >  </br> 
			<p>When Should This Activation Expire - A few days after the end of the semester is fine (max 1 yr from now)</br> <input type="date" name="exp_date" value = "2019-05-13"  min="2019-05-13" max='2000-01-10' id="exp_date" ></p></br>

			
			<h3><font color = "Blue"  > Only Add Sections if you are Teaching Multiple Sections of the Same Course </font> </h3>
			<font size = "2"> 
				Optional Section 1 Designation (e.g. Sect 1 - T,TH 10:00 AM) <br><input type= "text" Name="sec_desig_1"  ></br>
				Optional Section 2 Designation (e.g. Sect 2 - MWF 8:00 AM) <br> <input type= "text" Name="sec_desig_2" > </br> 
				 Optional Section 3 Designation  </br> <input type= "text" Name="sec_desig_3"  > </br> 
				Optional Section 4 Designation  </br> <input type= "text" Name="sec_desig_4"  > </br> 
				Optional Section 5 Designation  </br> <input type= "text" Name="sec_desig_5"  > </br> 
				Optional Section 6 Designation  </br> <input type= "text" Name="sec_desig_6"  > </br> 
			</font>
				
			
			
			
			
			<input type="hidden" name="Submitted" value="name" />
			<p><input type = "submit"></p>
			<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
        </form>
    </div>    
 
	
		
	<script>
	
	
	
	
	$(document).ready( function () {
			
			// suggest a date as the end of the semester for the expiration date
		
		var m = 0;
		var d = new Date();   // current date
		var minDate = d.toISOString(true).slice(0,10);
		document.getElementById("exp_date").setAttribute("min", minDate);
		// console.log (minDate);
		max_months = 9;
		var max_date = new Date();
		max_date.setMonth(max_date.getMonth() + max_months);
		//console.log("Date after " + max_months + " months:", maxDate);
		var maxDate = max_date.toISOString(true).slice(0,10);
		document.getElementById("exp_date").setAttribute("max", maxDate);
		
		var n = d.getMonth() +1; // current Month
	//	console.log ("n: "+n);
		var y = d.getFullYear();
	//	console.log ("y: "+y);
		if (n==11){
			m = 4;
			yr = y+1;
		} else if (n <=3) {
			m=4;
			yr = y;
		} else if (n >= 7) {
			m=11;
			yr = y;
		}	else {
			m=7;
			yr = y;
		}
		
		d.setFullYear(yr, m, 25);  // change d to the end of the semester
		var expDate = d.toISOString(true).slice(0,10);
		document.getElementById("exp_date").value = expDate;
	
	} );
	
	
</script>	
</body>
</html>
