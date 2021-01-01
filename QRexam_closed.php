<?php
	session_start();
    
   if ( isset($_SESSION['error_check']) ) {
			echo '<p style="color:red">'.$_SESSION['error_check']."</p>\n";
			unset($_SESSION['error_check']);
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