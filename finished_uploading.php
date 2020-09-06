<?php
	require_once "pdo.php";
	session_start();

      

	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>Finished Uploading</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 

</head>

<body>
<header>
<h1>Finished Uploading Files </h1>
</header>

<?php
        
        if ( isset($_SESSION['error']) ) {
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) ) {
			echo '<h2 style="color:green">'.$_SESSION['success']."</h2>\n";
			unset($_SESSION['success']);
		}
	//}
 
?>

<h2> Please Select Yellow "Back" Button in Top Left Corner to Select Another Problem</h2>
	

<script>
 
</script>

</body>
</html>



