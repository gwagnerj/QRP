<?php
 session_start();
   $_SESSION['score'] = "0";
	
	//$_SESSION['count'] = 0;
	
Require_once "pdo.php";

// first do some error checking on the input.  If it is not OK set the session failure and send them back to QRPIndex.
/* if ( ! isset($_GET['problem_id']) ) {
  $_SESSION['error'] = "Missing problem number";
  header('Location: QRPindex.php');
  return;
}
if ( ! isset($_GET['dex_num']) ) {
  $_SESSION['error'] = "Missing index number";
  header('Location: QRPindex.php');
  return;
} 
 
if ($_GET['problem_id']<1 or $_GET['problem_id']>1000000)  {
  $_SESSION['error'] = "problem number out of range";
  header('Location: QRPindex.php');
  return;
}
if ($_GET['dex_num']<2 or $_GET['dex_num']>200)  {
  $_SESSION['error'] = "PIN out of range";
  header('Location: QRPindex.php');
  return;
}

$_SESSION['index'] = $_GET['dex_num'];


if ( isset($_GET['problem_id']) and  isset($_GET['dex_num'])) {
	$_SESSION['problem_id'] = $_GET['problem_id'];

	$_SESSION['index'] = $_GET['dex_num'];
	
} */
	
	// initialize some variables
	// put this in to get the file up needs to be removed when ready for prime time
		$_SESSION['index']=22;
		$_SESSION['problem_id']=176;

	
	$probParts=0;
	//$partsFlag = array('a'=>false,'b'=>false,'c'=>false,'d'=>false,'e'=>false,'f'=>false,'g'=>false,'h'=>false,'i'=>false,'j'=>false);
	$resp = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
	$unit = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
	
	
	$dispBase = 1;
	

	$resp_key=array_keys($resp);
	
		
	// Next check the Qa table and see which values have non null values - for those 

$stmt = $pdo->prepare("SELECT * FROM Qa where problem_id = :problem_id AND dex = :dex");
$stmt->execute(array(":problem_id" => $_SESSION['problem_id'], ":dex" => $_SESSION['index']));
//$row = $stmt->fetch(PDO::FETCH_ASSOC);
$row = $stmt -> fetch();
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for problem_id';
    header( 'Location: QRPindex.php' ) ;
    return;
}	
		$soln = array_slice($row,6); // this would mean the database table Qa would have the dame structure
	

	for ($i = 0;$i<=9; $i++){  
		if ($soln[$i]==1.2345e43) {
			$partsFlag[$i]=false;
		} else {
			$probParts = $probParts+1;
			$partsFlag[$i]=true;
		}
	}
	//get the tolerance for each part - only really need to do this once on the get request - change if it is slow
	$stmt = $pdo->prepare("SELECT * FROM Problem where problem_id = :problem_id");
	$stmt->execute(array(":problem_id" => $_SESSION['problem_id']));
	//$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$row = $stmt -> fetch();
	
	$probData=$row;	
	
	$probStatus = $probData['status'];
	if ($probStatus =='suspended'){
		$_SESSION['error'] = 'problem has been suspended, check back later';
		header( 'Location: QRPindex.php' ) ;
		return;	
	}
	

	
	$unit = array_slice($row,22,20);  // shows the same thing but easier so long as the table always has the same structure
	//print_r($unit);

	
// read the student responses into an array
	$resp['a']=$_POST['a']+0;
	$resp['b']=$_POST['b']+0;
	$resp['c']=$_POST['c']+0;
	$resp['d']=$_POST['d']+0;
	$resp['e']=$_POST['e']+0;
	$resp['f']=$_POST['f']+0;
	$resp['g']=$_POST['g']+0;
	$resp['h']=$_POST['h']+0;
	$resp['i']=$_POST['i']+0;
	$resp['j']=$_POST['j']+0;
	
	
		
	
	
	?>



<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRChecker</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
</head>

<body>
<header>
<h1>QRHomework Problem Preliminaries</h1>
</header>
<main>


<p> Problem Number: <?php echo ($_SESSION['problem_id']) ?> </p>


<form autocomplete="off" method="POST" >
<!-- <p>Problem Number: <input type="text" name="problem_number" ></p> -->
<!-- <p> Please put in your index number </p> -->
<p><font color=#003399>PIN: </font><input type="text" name="dex_num" size=3 value="<?php echo (htmlentities($_SESSION['index']))?>"  ></p>
<p> <strong> make a thoughtful guestimate after reading the problem statement - then select "Submit" </strong></p>


<?php


if ($partsFlag[0]){ ?> 
<p> a): <input [ type=number]{width: 5%;} name="a" size = 10% value="<?php if ($resp['a']!=0){echo (htmlentities($resp['a']));}?>" required > <?php echo($unit[0]) ?>
  </p>
<?php } 

if ($partsFlag[1]){ ?> 
<p> b): <input [ type=number]{width: 5%;} name="b" size = 10% value="<?php if ($resp['b']!=0){echo (htmlentities($resp['b']));}?>" required > <?php echo($unit[1]) ?>
</p>
<?php } 

if ($partsFlag[2]){ ?> 
<p> c): <input [ type=number]{width: 5%;} name="c" size = 10% value="<?php if ($resp['c']!=0){echo (htmlentities($resp['c']));}?>" required> <?php echo($unit[2]) ?> 
</p>
<?php } 

if ($partsFlag[3]){ ?> 
<p> d): <input [ type=number]{width: 5%;} name="d" size = 10% value="<?php if ($resp['d']!=0){echo (htmlentities($resp['d']));}?>" required> <?php echo($unit[3]) ?> 
</p>
<?php } 

if ($partsFlag[4]){ ?> 
<p> e): <input [ type=number]{width: 5%;} name="e" size = 10% value="<?php if ($resp['e']!=0){echo (htmlentities($resp['e']));}?>" required > <?php echo($unit[4]) ?>
<?php } 

if ($partsFlag[5]){ ?> 
<p> f): <input [ type=number]{width: 5%;} name="f" size = 10% value="<?php if ($resp['f']!=0){echo (htmlentities($resp['f']));}?>" required> <?php echo($unit[5]) ?> 
</p>
<?php } 

if ($partsFlag[6]){ ?> 
<p> g): <input [ type=number]{width: 5%;} name="g" size = 10% value="<?php if ($resp['g']!=0){echo (htmlentities($resp['g']));}?>" required> <?php echo($unit[6]) ?> 
</p>
<?php } 

if ($partsFlag[7]){ ?> 
<p> h): <input [ type=number]{width: 5%;} name="h" size = 10% value="<?php if ($resp['h']!=0){echo (htmlentities($resp['h']));}?>" required> <?php echo($unit[7]) ?> 
</p>
<?php } 

if ($partsFlag[8]){ ?> 
<p> i): <input [ type=number]{width: 5%;} name="i" size = 10% value="<?php if ($resp['i']!=0){echo (htmlentities($resp['i']));}?>" required> <?php echo($unit[8]) ?>
</p>
<?php } 

if ($partsFlag[9]){ ?> 
<p> j): <input [ type=number]{width: 5%;} name="j" size = 10% value="<?php if ($resp['j']!=0){echo (htmlentities($resp['j']));}?>" required> <?php echo($unit[9]) ?>
</p>
<?php } 


?>

<!--<p>Grading Scheme: <input type="text" name="grade_scheme" ></p> -->
<p><input type = "submit" value="Submit" size="10" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp <b> <font size="4" color="Navy"></font></b></p>


</form>

 <!--<form method="get" >
<p><input type = "submit" value="Finished"/> </p>
</form> 

  <?php $_SESSION['score'] = $PScore; $_SESSION['index'] = $index; $_SESSION['count'] = $count; ?>
 <b><input type="submit" value="Rate & Get rtn Code" style = "width: 30%; background-color:yellow "></b>
 <p><br> </p>
 <hr>
</form>

<form method = "POST">
<p><input type = "submit" value="Get Base-Case Answers" name = "show_base" size="10" style = "width: 30%; background-color: green; color: white"/> &nbsp &nbsp <b> <font size="4" color="Green"></font></b></p>
</form>  
-->





</main>
</body>
</html>