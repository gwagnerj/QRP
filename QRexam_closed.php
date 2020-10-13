<?php
	session_start();
    
   if ( isset($_SESSION['error']) ) {
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) ) {
			echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
			unset($_SESSION['success']);
		} 
?>


<html>
<head>
</head>
<body>

<h2> Exam is now Closed. You may Close the Browser </h2>


</body>

</html>