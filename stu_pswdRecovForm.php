<?php
session_start();
if (isset($_SESSION['failure'])){
	echo $_SESSION['failure'];
}
?>



<html>
	<head>
<meta charset = "UTF-8">
	<title> Password Recovery</title>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


	</head>

	<body>
		<form action="stu_reset-request.php" method="post">
					Enter School email in system to recieve information on your password or username
					<input type="email" class="text" id = "email" name="email" placeholder="email" required> </br>
					<input type="submit" class="submit" name = "reset-request-submit" value="Submit">
				</div>
			</div>
		</form>

	</body>


</html>