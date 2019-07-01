<html>
<head>


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
						<input type = "hidden" name = "selector" value = "<?php echo $selector;?>">
						<input type = "hidden" name = "validator" value = "<?php echo $validator;?>">
						<input type = "password" name = "pswd" placeholder = "Enter a new password..." required>
						<input type = "password" name = "pswd-repeat" placeholder = "Repeat new password..." required>
						<button type = "submit" name = "reset-password"> Reset password </button>
					</form>
					<?php	
					}

				?>

			</section>
		</div>
	</body>

</html>