<?php
require_once "pdo.php";
session_start();

// THis is called by NumerictoMC.php and is used to get the problem data from the qa table to develop the responses
 

	   $dex = $_POST['dex']; 
	 $mc1 = $_POST['mc1'];	
	if(isset($_POST['mc2'])){$mc2 = $_POST['mc2'];} else {$mc2 = 'not_s';}
	if(isset($_POST['mc3'])){$mc3 = $_POST['mc3'];} else {$mc3 = 'not_s';}
	// $mc3 = $_POST['mc3'];	
	 $problem_id = $_POST['problem_id'];
	 $dex = $_POST['dex']; 
	 $n = $_POST['n']; 
	
	/*  $mc1 = 'ans_a'; // temp
	$mc2 = 'ans_b'; // temp
	$mc3 = ''; // temp
	$problem_id = 255
	; //temp
	$dex = 3;  //temp
	 $n = 2;  // temp  */ 
	
 
	$stmt = $pdo->prepare("SELECT * FROM qa where problem_id = :problem_id AND dex = :dex");
	 $stmt->execute(array(":problem_id" => $problem_id,":dex" => $dex ));
	
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
		if ( $row === false ) {
			$_SESSION['error'] = 'could not read row of table qa values';
	
		}	
	
	//for ($m =0; $m<$n-1;$m++){
	//	$ans[$m]= $row["'".$mc1."'"];
	//	$ans_mc1 =  $mc1_i;
	//$ans_mc1 = array_values($row);	
	//}
	//  $newArray = array_keys($row);
	foreach ($row as $key => $value)
		if($key == $mc1){$ans_mc1 = $value;	} 
		
		
	foreach ($row as $key => $value)	
		if(isset($mc2) && $key == $mc2) {	$ans_mc2 = $value;}
	foreach ($row as $key => $value)		
		if(isset($mc3) && $key == $mc3) {	$ans_mc3 = $value;}
		
	// print_r ($ans_mc2);
	 // print_r ($y); 
	  // $ answers is an array of all the answers for the problem.  It is an Associative array that has n elements with the keys being the table fields
	  $ans = array_slice($row,3,$n,true);  // the answers start at an indix of 3
	//  print_r($ans);
	  
	  // $keyArray - an array with the keys for the key value pairs in ans and so can get the keys by number key $keyArray[0] = 'ans_a'
	$keyArray = array_keys($ans);
//	print_r($keyArray);
	
	
	 
	 // this get the range of answers for all of the indicies to compute the options for the MC question
	 
		$stmt = $pdo->prepare("SELECT `". $mc1."` FROM qa where problem_id = :problem_id ");
		$stmt->execute(array(":problem_id" => $problem_id));  
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
				$alt_1[$j] = $min_1 + $range_1_div7 * ($r-1)+seedRand($dex)*$range_1_div7;
			} else {
				$alt_1[$j] = $ans_mc1;	
			}
			
			 $alt_1[$j] = sigFig($alt_1[$j],3);
	}
		
		
	if (isset($mc2) && substr($mc2,0,3)=='ans' ){		
			

		$stmt = $pdo->prepare("SELECT `". $mc2."` FROM qa where problem_id = :problem_id ");
			 $stmt->execute(array(":problem_id" => $problem_id ));  
		
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
					$k = abs($j-3);
					if ($bin_2_even) {
						$r = $j*2+2;
					} else {
						$r = $j*2+1;
					}
					
					if($bin_2 != $r){
						
						$alt_2[$k] = $min_2 + $range_2_div7*($r-1)+seedRand($dex)*$range_2_div7;
					} else {
						$alt_2[$k] = $ans_mc2;	
					}
					
					 $alt_2[$k] = sigFig($alt_2[$k],3);
			}
		
		 } else {
			
			for ($j=0; $j<=3;$j++) {
					if ($bin_2_even) {
						$r = $j*2+2;
					} else {
						$r = $j*2+1;
					}
					
					if($bin_2 != $r){
						$alt_2[$j] = $min_2 + $range_2_div7*($r-1)+seedRand($dex)*$range_2_div7;
					} else {
						$alt_2[$j] = $ans_mc2;	
					}
					
					 $alt_2[$j] = sigFig($alt_2[$j],3);
			}
			
		} 
	
	} else {
		$alt_2[0] = 0;
		$alt_2[1] = 0;
		$alt_2[2] = 0;
		$alt_2[3] = 0;
		$ans_mc2 = 0;
	}
	
	
	// algorithm for c
	
	if (isset($mc3) && substr($mc3,0,3)=='ans'){		

		$stmt = $pdo->prepare("SELECT `". $mc3."` FROM qa where problem_id = :problem_id ");
			 $stmt->execute(array(":problem_id" => $problem_id ));  
		
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
	
	} else {
		$alt_3[0] = 0;
		$alt_3[1] = 0;
		$alt_3[2] = 0;
		$alt_3[3] = 0;
		$ans_mc3 = 0;
	}


// print_r($ans);
// print_r ($alt_1);
// print_r ($alt_2);
// print_r ($alt_3);
	
$alt_1assoc = array('opt_i_1' => $alt_1[0], 'opt_ii_1' => $alt_1[1], 'opt_iii_1' => $alt_1[2],'opt_iv_1' => $alt_1[3],'key_1' => $ans_mc1);
$alt_2assoc = array('opt_i_2' => $alt_2[0], 'opt_ii_2' => $alt_2[1], 'opt_iii_2' => $alt_2[2],'opt_iv_2' => $alt_2[3],'key_2' => $ans_mc2);
$alt_3assoc = array('opt_i_3' => $alt_3[0], 'opt_ii_3' => $alt_3[1], 'opt_iii_3' => $alt_3[2],'opt_iv_3' => $alt_3[3],'key_3' => $ans_mc3);

	$resp_arr = array(
			'opt_i_1' => $alt_1[0], 'opt_ii_1' => $alt_1[1], 'opt_iii_1' => $alt_1[2],'opt_iv_1' => $alt_1[3],'key_1' => $ans_mc1,
			'opt_i_2' => $alt_2[0], 'opt_ii_2' => $alt_2[1], 'opt_iii_2' => $alt_2[2],'opt_iv_2' => $alt_2[3],'key_2' => $ans_mc2,
			'opt_i_3' => $alt_3[0], 'opt_ii_3' => $alt_3[1], 'opt_iii_3' => $alt_3[2],'opt_iv_3' => $alt_3[3],'key_3' => $ans_mc3,
			'key_1' => $ans_mc1, 'key_2' => $ans_mc2, 'key_3' => $ans_mc3
	);

	// $resp_arr = array_merge($resp_arr,$ans);

 for ($k=0;$k<$n;$k++){
	$resp_arr[$keyArray[$k]] = $ans[$keyArray[$k]];
} 

	 echo json_encode($resp_arr);
	

	
	
	
	
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

function seedRand($seed){
	// simple seeded random number generator for a number between 0 and 1 but will give the same for a given seed
	$a = (48271*$seed)% (2^31-1);
	$randnum = ((48271*$a)%(2^31-1))/(2^31-1);
	return $randnum;
}

	
?>	

