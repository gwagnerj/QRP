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

?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QR Question Mgmt</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<style>
   .outer {
  width: 100%;
  margin: 0 auto;
 
}
.hide{ 
display: none;

}

body {margin:2em;padding:0}

.navbar{
display: flex;
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
<nav class="navbar">
    <?php
    echo('<form action = "QRQuestionMgmt1.php" method = "POST"> <input type = "hidden" name = "iid" value = "'.$iid.'"><input class = "btn btn-outline-dark btn-lg" type = "submit" value ="Set Up Questions for Delivery"></form> &nbsp;');
    echo('<form action = "writeQuestionBirth.php" method = "POST"> <input type = "hidden" name = "iid" value = "'.$iid.'"><input class = "btn btn-outline-primary btn-lg" type = "submit" value ="Promote Questions to Active Bank"></form> &nbsp;');
    echo('<form action = "writeQuestionStudentActivity.php" method = "POST"> <input type = "hidden" name = "iid" value = "'.$iid.'"><input class = "btn btn-outline-success btn-lg" type = "submit" value ="Check Student Question Activity"></form> &nbsp;');

?>
</nav>

  <p style="font-size:20px;"></p>   
    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	

  


	<script>
 
	
</script>	

</body>
</html>



