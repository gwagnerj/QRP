<?php
session_start();
	require_once "pdo.php";
// can do more later if need be
 $_SESSION['sucess']='Exam No Longer Active';
 
 echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";

	?>