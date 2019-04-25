<?php
require_once "pdo.php";
session_start();

// THis is called by NumerictoMC.php to find out how many parts are in the problem using the qa table
 
 
	$n=0;
 
	$stmt = $pdo->prepare("SELECT * FROM `Qa` where problem_id = :problem_id AND dex = :dex");
	 $stmt->execute(array(":problem_id" => $_POST['problem_id'],":dex" => 1 ));
	 //$stmt->execute(array(":problem_id" => 256, ":dex" => 8 ));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
		if ( $row === false ) {
			$_SESSION['error'] = 'could not read row of table qa values';
	
		}	
	
	if ($row['ans_a'] != 1.2345e43){$n++;}
	if ($row['ans_b'] != 1.2345e43){$n++;}
	if ($row['ans_c'] != 1.2345e43){$n++;}
	if ($row['ans_d'] != 1.2345e43){$n++;}
	if ($row['ans_e'] != 1.2345e43){$n++;}
	if ($row['ans_f'] != 1.2345e43){$n++;}
	if ($row['ans_g'] != 1.2345e43){$n++;}
	if ($row['ans_h'] != 1.2345e43){$n++;}
	if ($row['ans_i'] != 1.2345e43){$n++;}
	if ($row['ans_j'] != 1.2345e43){$n++;}
	
	
	
	$resp_arr = array('n' => $n);
	
	
	 echo json_encode($resp_arr);
	
	
	
	
	
	

	
?>	

