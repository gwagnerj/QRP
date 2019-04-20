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
	 $stmt->execute(array(":problem_id" => $_POST['problem_id'],":dex" => $_POST['index'] ));
	// $stmt->execute(array(":problem_id" => 256, ":dex" => 8 ));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
		if ( $row === false ) {
			$_SESSION['error'] = 'could not read row of table qa values';
	
		}	
	
	$ans_a = $row['ans_a'];
	$ans_b = $row['ans_b'];
	$ans_c = $row['ans_c'];
	$ans_d = $row['ans_d'];
	$ans_e = $row['ans_e'];
	$ans_f = $row['ans_f']; 
	
	
	 for ($i = 2; $i < 200; $i++){
		$stmt = $pdo->prepare("SELECT * FROM qa where problem_id = :problem_id AND dex = :dex");
		 $stmt->execute(array(":problem_id" => $_POST['problem_id'],":dex" => $i ));
		// $stmt->execute(array(":problem_id" => 256,":dex" => $i ));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$all_ans_a[$i-2] = $row['ans_a'];
			$all_ans_b[$i-2] = $row['ans_b'];
			$all_ans_c[$i-2] = $row['ans_c'];
			$all_ans_d[$i-2] = $row['ans_d'];
			$all_ans_e[$i-2] = $row['ans_e'];
			$all_ans_f[$i-2] = $row['ans_f'];
	} 
	
	
	
	
// algorithm for a responses
	
	$min_a = min($all_ans_a);
	$max_a = max($all_ans_a);
	$range_a_18 = ($max_a - $min_a)/7;
	$bin_a = intval(($ans_a - $min_a)/$range_a_18)+1;
	
	
	// check to see if even or odd
	if($bin_a % 2 == 0){
		$bin_a_even = true;
	} else {
		$bin_a_even = false;
	}
	
	for ($j=0; $j<=3;$j++) {
			if ($bin_a_even) {
				$r = $j*2+2;
			} else {
				$r = $j*2+1;
			}
			
			if($bin_a != $r){
				$alt_a[$j] = $min_a + $range_a_18*($r-1)+rand(1,999)/1000*$range_a_18;
			} else {
				$alt_a[$j] = $ans_a;	
			}
			
			 $alt_a[$j] = sigFig($alt_a[$j],3);
	}
	
	// algorithm for b
	
	$min_b = min($all_ans_b);
	$max_b = max($all_ans_b);
	$range_b_18 = ($max_b - $min_b)/7;
	$bin_b = intval(($ans_b - $min_b)/$range_b_18)+1;
	
	if($bin_b % 2 == 0){
		$bin_b_even = true;
	} else {
		$bin_b_even = false;
	}
	// everse the order if part a and part b will have the same response
	if (abs($bin_a - $bin_b)<=1){
		for ($j=3; $j>=0;$j--) {
				if ($bin_b_even) {
					$r = $j*2+2;
				} else {
					$r = $j*2+1;
				}
				
				if($bin_b != $r){
					$alt_b[$j] = $min_b + $range_b_18*($r-1)+rand(1,999)/1000*$range_b_18;
				} else {
					$alt_b[$j] = $ans_b;	
				}
				
				 $alt_b[$j] = sigFig($alt_b[$j],3);
		}
	
	 } else {
		
		for ($j=0; $j<=3;$j++) {
				if ($bin_b_even) {
					$r = $j*2+2;
				} else {
					$r = $j*2+1;
				}
				
				if($bin_b != $r){
					$alt_b[$j] = $min_b + $range_b_18*($r-1)+rand(1,999)/1000*$range_b_18;
				} else {
					$alt_b[$j] = $ans_b;	
				}
				
				 $alt_b[$j] = sigFig($alt_b[$j],3);
		}
		
	} 
	
	// algorithm for c
	
	$min_c = min($all_ans_c);
	$max_c = max($all_ans_c);
	$range_c_18 = ($max_c - $min_c)/7;
	$bin_c = intval(($ans_c - $min_c)/$range_c_18)+1;
	
	if($bin_c % 2 == 0){
		$bin_c_even = true;
	} else {
		$bin_c_even = false;
	}
	
	
	for ($j=0; $j<=3;$j++) {
			if ($bin_c_even) {
				$r = $j*2+2;
			} else {
				$r = $j*2+1;
			}
			
			if($bin_c != $r){
				$alt_c[$j] = $min_c + $range_c_18*($r-1)+rand(1,999)/1000*$range_c_18;
			} else {
				$alt_c[$j] = $ans_c;	
			}
			
			 $alt_c[$j] = sigFig($alt_c[$j],3);
	}
	
	
	
	
	
	
	
	
	
	
	/* echo (" For part a ");
	echo $ans_a;
	echo (" ");
	echo ($min_a);
	echo (" ");
	echo ($max_a);
	echo (" ");
	echo $bin_a;
	echo (" ");
	
	
	print_r($alt_a);
	
	echo (" For part b ");
	
	echo ($ans_b);
	echo (" ");
	echo ($min_b);
	echo (" ");
	echo ($max_b);
	echo (" ");
	echo $bin_b;
	echo (" ");
	echo (" ");
	
	print_r($alt_b);
	
	echo (" For part c ");
	
	echo ($ans_c);
	echo (" ");
	echo ($min_c);
	echo (" ");
	echo ($max_c);
	echo (" ");
	echo $bin_c;
	echo (" ");
	echo (" ");
	
	print_r($alt_c); */
	
	$resp_arr = array('key_a' => $ans_a, 'opt_a_1' => $alt_a[0],'opt_a_2' => $alt_a[1],'opt_a_3' => $alt_a[2],'opt_a_4' => $alt_a[3],
					'key_b' => $ans_b, 'opt_b_1' => $alt_b[0],'opt_b_2' => $alt_b[1],'opt_b_3' => $alt_b[2],'opt_b_4' => $alt_b[3],
					'key_c' => $ans_c, 'opt_c_1' => $alt_c[0],'opt_c_2' => $alt_c[1],'opt_c_3' => $alt_c[2],'opt_c_4' => $alt_c[3]
	);
	
	
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
	
?>	

