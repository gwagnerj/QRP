<?php
require_once "pdo.php";
session_start();

// THis is called by NumerictoMC.php and is used to get the problem data from the qa table to develop the responses
 
  /*   $sql = "SELECT * FROM Problem WHERE problem_id = :problem_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':problem_id' => $_POST['problem_id']));
	$data = $stmt -> fetch();
	// need to put some error checking here
		$rows=$data; */
// query the input table for the actual input values
	

/* 	 $users_id = $rows['users_id'];
	
	$sql = "SELECT * FROM Users WHERE users_id = :users_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':users_id' => $users_id));
	$data2 = $stmt -> fetch();
		$row_user=$data2;
		$rows=array_merge($rows,$row_user);
	
 */
	
 
	$stmt = $pdo->prepare("SELECT * FROM qa where problem_id = :problem_id AND dex = :dex");
	// $stmt->execute(array(":problem_id" => $_POST['problem_id'],":dex" => $_POST['index'] ));
	 $stmt->execute(array(":problem_id" => 256, ":dex" => 8 ));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
		if ( $row === false ) {
			$_SESSION['error'] = 'could not read row of table qa values';
	
		}	


 $mc1 = 'ans_a'; // temp
 $mc2 = 'ans_b'; // temp
 $mc3 = 'ans_c'; // temp
	
	 // $mc1 = $_POST['mc1'];	
	 // $mc2 = $_POST['mc2'];
	 // $mc3 = $_POST['mc3'];	
	
	 
	
	
	// $m=0;
	$n = 3;  // temp
	
	//$n = $_POST['n'];
	
	
	//for ($m =0; $m<$n-1;$m++){
	//	$ans[$m]= $row["'".$mc1."'"];
	//	$ans_mc1 =  $mc1_i;
	//$ans_mc1 = array_values($row);	
	//}
	//  $newArray = array_keys($row);
	foreach ($row as $key => $value)
		if($key == $mc1){$ans_mc1 = $value;	} 
		
		
	foreach ($row as $key => $value)	
		if($key == $mc2) {	$ans_mc2 = $value;}
	foreach ($row as $key => $value)		
		if($key == $mc3) {	$ans_mc3 = $value;}
		
	 print_r ($ans_mc2);
	 // print_r ($y); 
	  // $ answers is an array of all the answers for the problem.  It is an Associative array that has n elements with the keys being the table fields
	  $ans = array_slice($row,3,$n,true);  // the answers start at an indix of 3
	  print_r($ans);
	  
	  // $keyArray - an array with the keys for the key value pairs in ans and so can get the keys by number key $keyArray[0] = 'ans_a'
	$keyArray = array_keys($ans);
	print_r($keyArray);
	
	
	 
	 // this get the range of answers for all of the indicies to compute the options for the MC question
	 
		$stmt = $pdo->prepare("SELECT `". $mc1."` FROM qa where problem_id = :problem_id ");
		// $stmt->execute(array(":problem_id" => $_POST['problem_id']));  
		 $stmt->execute(array(":problem_id" => 256 )); // temp
		$mc1_arr = $stmt->fetchALL(PDO::FETCH_COLUMN);
		
	//print_r($mc1_arr);
		
		// algorithm for a responses for the the first mc question
	
	$min_1 = min($mc1_arr);
	$max_1 = max($mc1_arr);
	$range_1_div7 = ($max_1 - $min_1)/7;
	$bin_1 = intval(($ans_mc1 - $min_1)/$range_1_div7)+1;
	
	
	// check to see if even or odd
	if($bin_1 % 2 == 0){
		$bin_1_even = true;
	} else {
		$bin_1_even = false;
	}
	
	for ($j=0; $j<=3;$j++) {
			if ($bin_1_even) {
				$r = $j*2+2;
			} else {
				$r = $j*2+1;
			}
			
			if($bin_1 != $r){
				$alt_1[$j] = $min_1 + $range_1_div7 * ($r-1)+rand(1,999)/1000*$range_1_div7;
			} else {
				$alt_1[$j] = $ans_mc1;	
			}
			
			 $alt_1[$j] = sigFig($alt_1[$j],3);
	}
		
		
	if (isset($mc2)){		
			

		$stmt = $pdo->prepare("SELECT `". $mc2."` FROM qa where problem_id = :problem_id ");
			// $stmt->execute(array(":problem_id" => $_POST['problem_id'] ));  
			 $stmt->execute(array(":problem_id" => 256 )); // temp
			$mc2_arr = $stmt->fetchALL(PDO::FETCH_COLUMN);
			
	//	print_r($mc2_arr);
		
	// algorithm for b
		$min_2 = min($mc2_arr);
		$max_2 = max($mc2_arr);
		$range_2_div7 = ($max_2 - $min_2)/7;
		$bin_2 = intval(($ans_mc2 - $min_2)/$range_2_div7)+1;


		
		
		if($bin_2 % 2 == 0){
			$bin_2_even = true;
		} else {
			$bin_2_even = false;
		}
		// everse the order if part a and part b will have the same response
		if (abs($bin_2 - $bin_2)<=1){
			for ($j=3; $j>=0;$j--) {
					if ($bin_2_even) {
						$r = $j*2+2;
					} else {
						$r = $j*2+1;
					}
					
					if($bin_2 != $r){
						$alt_2[$j] = $min_2 + $range_2_div7*($r-1)+rand(1,999)/1000*$range_2_div7;
					} else {
						$alt_2[$j] = $ans_mc2;	
					}
					
					 $alt_2[$j] = sigFig($alt_2[$j],3);
			}
		
		 } else {
			
			for ($j=0; $j<=3;$j++) {
					if ($bin_2_even) {
						$r = $j*2+2;
					} else {
						$r = $j*2+1;
					}
					
					if($bin_2 != $r){
						$alt_2[$j] = $min_2 + $range_2_div7*($r-1)+rand(1,999)/1000*$range_2_div7;
					} else {
						$alt_2[$j] = $ans_mc2;	
					}
					
					 $alt_2[$j] = sigFig($alt_2[$j],3);
			}
			
		} 
	
	}
	// algorithm for c
	
	
	
	
	
	
	if (isset($mc3)){		
			

		$stmt = $pdo->prepare("SELECT `". $mc3."` FROM qa where problem_id = :problem_id ");
			// $stmt->execute(array(":problem_id" => $_POST['problem_id'] ));  
			 $stmt->execute(array(":problem_id" => 256 )); // temp
			$mc3_arr = $stmt->fetchALL(PDO::FETCH_COLUMN);
			
	//	print_r($mc2_arr);
	
	
	
		$min_3 = min($mc3_arr);
		$max_3 = max($mc3_arr);
		$range_3_div7 = ($max_3 - $min_3)/7;
		$bin_3 = intval(($ans_mc3 - $min_3)/$range_3_div7)+1;

		
		if($bin_3 % 2 == 0){
			$bin_3_even = true;
		} else {
			$bin_3_even = false;
		}
		
		
		for ($j=0; $j<=3;$j++) {
				if ($bin_3_even) {
					$r = $j*2+2;
				} else {
					$r = $j*2+1;
				}
				
				if($bin_3 != $r){
					$alt_3[$j] = $min_3 + $range_3_div7*($r-1)+rand(1,999)/1000*$range_3_div7;
				} else {
					$alt_3[$j] = $ans_mc3;	
				}
				
				 $alt_3[$j] = sigFig($alt_3[$j],3);
		}
	
	}
	
	print_r($alt_3); 
	


	/* $resp_arr = array('key_a' => $ans_a, 'opt_a_1' => $alt_a[0],'opt_a_2' => $alt_a[1],'opt_a_3' => $alt_a[2],'opt_a_4' => $alt_a[3],
					'key_b' => $ans_b, 'opt_b_1' => $alt_b[0],'opt_b_2' => $alt_b[1],'opt_b_3' => $alt_b[2],'opt_b_4' => $alt_b[3],
					'key_c' => $ans_c, 'opt_c_1' => $alt_c[0],'opt_c_2' => $alt_c[1],'opt_c_3' => $alt_c[2],'opt_c_4' => $alt_c[3]
	); */
	
	
	// echo json_encode($resp_arr);
	
	
	
	
	
	
function sigFig($value, $digits)
{
    if ($value == 0) {
        $decimalPlaces = $digits - 1;
    } elseif ($value < 0) {
        $decimalPlaces = $digits - floor(log10($value * -1)) - 1;
    } else {
        $decimalPlaces = $digits - floor(log10($value)) - 1;
    }

    $answer = round($value, $decimalPlaces);
    return $answer;
}	
	
?>	

