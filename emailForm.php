<html>
	<head>
	<meta charset = "UTF-8">
	<title> email form</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


	</head>
	<body>
	<form action = "emailer2.php" method = "POST">
		<div class = "container">
			<div class = "row justify-contents-center" style = "margin-top-100px";>
				<div class ="col-md-6 col-md-offset-3" align = "center">
				<input type = "text" name = "name" id = "name" placeholder = "Your Name" class = "form-control">
				<input type = "email" name = "email" id = "email" placeholder = "Your Email" class = "form-control">
				<input type = "text" name = "subject" id = "subject" placeholder = "Subject" class = "form-control">
				<textarea name = "body"  class="form-control" id = "body" placeholder="E-mail Body"> </textarea>
				<input type = "submit"   value = "Send An Email"  class = "btn btn-primary">
				</div>
				
			</div>
		</div>
		</form>
	<script>
		/* 
		function sendEmail(){
					var name = $("#name");
					var subject = $("#subject");
					var email = $("#email");
					var body = $("#body");
					console.log(name.val());
					console.log(body.val());
					
				if(isNotEmpty(name) && isNotEmpty(email) && isNotEmpty(subject)  && isNotEmpty(body)){
					console.log( "setting up AJAX");
					$.ajax({
						url: 'emailer2.php',
						method: 'post',
						dataType: 'json',
						data: {
							name: name.val(),
							email: email.val(),
							subject: subject.val(),
							body: body.val()
						}, 
						sucess: function (response){
							console.log(response);
						}
					});
					
				}
				
			}
		function isNotEmpty(caller){
			if(caller.val()==""){
					caller.css('border', '1px solid red');
					return false;
			} else {
					caller.css('border','');
					return true;
					
			}
				
			
		}
	 */
	</script>
		


	</body>
</html>