<html>
<head>
<meta charset = "UTF-8">
	<title> email form</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>



</head>
	<body>
		<div class="wrapper-main">
			<section class = "section-default">
				<?php
				// this all comes from a tutorial by mmtuts at https://www.youtube.com/watch?v=wUkKCMEYj9M
					$selector = $_GET['selector'];
					$validator = $_GET['validator'];
					if (empty($selector)|| empty($validator)){
							echo 'could not validate your request';
					} else {
						if(ctype_xdigit($selector)!==false && ctype_xdigit($validator)!==false)
					?>	
					<form action = "reset-password.php" method = "post">
						<div class = "row justify-contents-center" style = "margin-top-100px";>
							<div class ="col-md-6 col-md-offset-3" align = "center">
							<input type = "hidden" name = "selector" value = "<?php echo $selector;?>">
							<input type = "hidden" name = "validator" value = "<?php echo $validator;?>">
							<input type = "password" name = "pswd" placeholder = "Enter a new password..." required><p></p>
							<input type = "password" name = "pswd-repeat" placeholder = "Repeat new password..." required><p></p>
							<button type = "submit" name = "reset-password-submit"> Reset password </button>
							</div>
						</div>
					</form>
					<?php	
					}

				?>

			</section>
		</div>
	</body>

</html>