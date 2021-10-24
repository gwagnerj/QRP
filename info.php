<?php
require_once 'pdo.php';
session_start();
session_unset();
$username_err = $email = $email_err = "";
$first_err = $last_err = $first = $last =  "";
 $form_style = "";
 $thank_you_style = "display_none";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
	// Validate email
    if(empty(trim($_POST['email']))){
        $email_err = "Please enter a valid email address that you check regularly.";     
    } elseif(strlen(trim($_POST['email'])) < 4){
        $email_err = "email input too short to be a valid email address.";
    } else{
        $email = htmlentities(trim($_POST['email']));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
          }

    }
	// Validate first name
    if(empty(trim($_POST['first']))){
        $first_err = "Please enter a valid first name.";     
    } elseif(strlen(trim($_POST['first'])) < 1){
        $first_err = "First name too short.";
    } else{
        $first = htmlentities(trim($_POST['first']));
    }
	// Validate last name
    if(empty(trim($_POST['last']))){
        $last_err = "Please enter a valid last name.";     
    } elseif(strlen(trim($_POST['last'])) < 2){
        $last_err = "last name too short.";
    } else{
        $last = htmlentities(trim($_POST['last']));
    }
	
	
    // Check input errors before inserting in database
    if(	empty($email_err) && empty($first_err) && empty($last_err)){
        
        // Prepare an insert statement
		
        $sql = "INSERT INTO `Interest` ( `email`, `first`, `last`)
		VALUES ( :e_mail, :first, :last )";
		
            $stmt = $pdo->prepare($sql);
            $stmt ->execute(array(
                ':e_mail' => $email,
                ':first' => $first,
                ':last' => $last
            ));
            $thank_you_style = "";
            $form_style = "display_none";

		
		
    } else {
		
		echo ' There is an error in one of the parameters';
		echo '</br>';
		if (!empty($username_err)){echo $username_err; echo '</br>';}
		if (!empty($password_err)){echo $password_err; echo '</br>';}
		if (!empty($university_err)){echo $university_err; echo '</br>';}
		if (!empty($sponsor_err)){echo $sponsor_err; echo '</br>';}
		if (!empty($security_err)){echo $security_err; echo '</br>';}
		
	}
   
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Interested in QRProblem System?</title>
  
	<link rel="icon" type="image/png" href="McKetta.png" />  
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
   .display_none { display: none;
}

        body{ font: 14px sans-serif; }
        .wrapper{ width: 70%; padding: 20px; }
    </style>
</head>
<body>
    <div id = "form-text" class = "<?php echo $form_style ?>">
    <div class="wrapper">
        <h2>Interested in QR Problem System?</h2>
        <h3>Sign up to get periodic info</h3>

		
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		
		
		
		
			
			
			<div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?> py-4">
                <label>email you check regularly</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>		
             <div class="form-group <?php echo (!empty($first_err)) ? 'has-error' : ''; ?> py-4">
                <label>First (Given) Name</label>
                <input type="text" name="first"class="form-control" value="<?php echo $first; ?>">
                <span class="help-block"><?php echo $first_err; ?></span>
            </div>  
			<div class="form-group <?php echo (!empty($last_err)) ? 'has-error' : ''; ?> py-4">
                <label>Last (sir) Name</label>
                <input type="text" name="last"class="form-control" value="<?php echo $last; ?>">
                <span class="help-block"><?php echo $last_err; ?></span>
            </div> 
            <br>
        
            <br>
            Interest in recieving information on:
                <br> 
            <div class="form-check form-check-inline px-4">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1" checked>
            <label class="form-check-label" for="inlineCheckbox1">QRGame</label>
            </div>
            <div class="form-check form-check-inline">
            <input class="form-check-input " type="checkbox" id="inlineCheckbox2" value="option2">
            <label class="form-check-label" for="inlineCheckbox2">QRHomework</label>
            </div>
            <div class="form-check form-check-inline py-2">
            <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
            <label class="form-check-label" for="inlineCheckbox3">QRExam</label>
            </div>
            <br>


            <input type ="submit" class = "btn btn-primary btn-lg my-4 py-2"></input>
        </form>
    </div>  
  </div>  
  <div id = "thank-you" class = "<?php echo $thank_you_style; ?>">

<h2 class = "ms-4 my-4">Thank you for your interest in the QRProblem System!</h2>
<h3 class = "ms-4">John Wagner</h3>
<h4 class = "ms-4">wagnerj@trine.edu</h4>

  </div>
</body>

<script>
	 
</script>
</html>