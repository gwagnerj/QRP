<?php
 session_start();
  
 $rand=$_SESSION['rand'];
 $rand2=$_SESSION['rand2'];
  
//$rand= rand(100000,999999);

$first=substr($rand,0,1);
//echo $first;
if ($first ==1 ){$key1=1; $key2=6;}
elseif ($first==2){$key1=2; $key2 = 5;}
elseif ($first==3){$key1=3; $key2 = 4;}
elseif ($first==4){$key1=4; $key2 = 3;}
elseif ($first==5){$key1=5; $key2 = 2;}
elseif ($first==6){$key1=6; $key2 = 1;}
elseif ($first==7){$key1=1; $key2 = 3;}
elseif ($first==8){$key1=2; $key2 = 4;}
else {$key1=3; $key2=5;}

$map1=substr($rand,$key1-1,1);
$map2=substr($rand,$key2-1,1);


$rslt=$map1.$map2;


$rslt2=$rslt+$_SESSION['score'];

$rtn_Code = $rand.'-'.$rslt2.$rand2;

$_SESSION['rtn_Code']=$rtn_Code;

session_destroy();

?>

<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />
<meta Charset = "utf-8">
<title>QRProblems</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
</head>

<body>
<header>
<!--<h1>this is an application that gets the return code from the score</h1>-->
</header>
<main>
<p><b><font size=5><p>Your Computer Score:<font color = "blue"> <?php echo( $_SESSION['score'])?>%</font></font></b></p> 
<p><b><font size=5>Your rtn Code: <font color = "blue"><?php echo ($_SESSION['rtn_Code'])?></font></font></b></p>
<p><br></p>
<?php
$problem_id = (isset($_SESSION['problem_id']) ? $_SESSION['problem_id'] : '');
$iid = (isset($_SESSION['iid']) ? $_SESSION['iid'] : '');
$pin = (isset($_SESSION['pin']) ? $_SESSION['pin'] : '');
$stu_name = (isset($_SESSION['stu_name']) ? $_SESSION['stu_name'] : '');


	echo('<a href="QRhomework.php?problem_id='.$problem_id.'&pin='.$pin.'&iid='.$iid.'&stu_name='.$stu_name.'"><b> Return to Main Screen</b></a>');
	//QRhomework.php'+'?problem_id='+problem_id+'&pin='+pin+'&iid='+iid+'&stu_name='+stu_name_back
?>
</main>



<footer>
<!--<p>This is the footer</p> -->
</footer>
</body>
</html>