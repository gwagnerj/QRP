<?php
	 session_start();
	 
	require_once "pdo.php";
		// this is comes in directly from QRPindex.php file that from the problem statement (will have get parameters if they press on the link here or directly from QRdisplaypblm.php via a link with get parameters
		
	$Bypass = 0;
		
	
		
		
			// first do some error checking on the input.  If it is not OK set the session failure and send them back to QRPIndex.
		if ( isset($_POST['problem_id']) ) {
			$problem_id = $_POST['problem_id'];
		} elseif ( isset($_GET['problem_id']) ) {
			$problem_id = $_GET['problem_id'];
		} elseif (isset($_SESSION['problem_id'])){
			$problem_id = $_SESSION['problem_id'];
		} 
		
		
		
	
		if ( isset($_POST['iid']) ) {
			$iid = $_POST['iid'];
		} elseif ( isset($_GET['iid']) ) {
			$iid = $_GET['iid'];
		} elseif (isset($_SESSION['iid'])){
			$iid = $_SESSION['iid'];
		} else {
		  $_SESSION['error'] = "Missing iid";
		  header('Location: QRPindex.php');
		 die();
		}
		
		if (isset($_GET['assign_num']) && isset($_GET['alias_num'])&& isset($_GET['cclass_id'])) {
			$assign_num = $_GET['assign_num'];
			$alias_num = $_GET['alias_num'];
			$cclass_id = $_GET['cclass_id'];
			// input these values manually will need to get the problem number
			$sql = "SELECT * FROM `Assign` WHERE `iid` = :iid AND `assign_num` = :assign_num AND `alias_num` = :alias_num AND `currentclass_id` = :cclass_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
			":iid" => $iid,
			":assign_num" => $assign_num,
			":alias_num" => $alias_num,
			":cclass_id" => $cclass_id
			));
			$row = $stmt -> fetch();
			if ( $row === false ) {
				$_SESSION['error'] = 'Could not find an assigned problem for the instructor, assignment and problem number given';
				header( 'Location: QRPindex.php' ) ;
				die();
			} else {
				$problem_id = $row['prob_num'];
				
				// get the name of the current class from the CurrentClass table
					$sql = "SELECT * FROM `CurrentClass` WHERE currentclass_id = $cclass_id";
					$stmt = $pdo->query($sql);
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
					if ( $row == false) {
						$cclass_name = "";
					} else {
							$cclass_name = $row['name'];
						
					}
				
				
				
				
			}				
			// add the following so I can access the problem with checkerBypass.php
		} elseif(isset($_GET['problem_id']) && isset($_GET['pin'])&& isset($_GET['iid'])) {
				$problem_id = $_GET['problem_id'];
				$pin = $_GET['pin'];
				$assign_num = '';
				$alias_num = '';
				$cclass_id = '';
				$cclass_name = '';
				$iid = '';
				$Bypass = 1;
		} else {
			
			 $_SESSION['error'] = "values for input";
			   header('Location: QRPindex.php');
				die();
		}
	
		if ( isset($_POST['pin']) ) {
			$pin = $_POST['pin'];
		} elseif ( isset($_GET['pin']) ) {
			$pin = $_GET['pin'];
		} elseif (isset($_SESSION['pin'])){
			$pin = $_SESSION['pin'];
		} else {
		  $_SESSION['error'] = "Missing pin";
		  header('Location: QRPindex.php');
		  return;
		}
		
		// Check toi see if I typed in the code
		$dispAns=substr($pin,0,7);

			if($dispAns=="McKetta" ){
				$pin2=substr($pin,7)+0;
				$index = ($pin2-1) % 199 + 2;  // converts the PIN to the index
				$pin = $pin2;
				$dispAnsflag=True;
			
			}	else {
				$dispAnsflag=False;
				$pin2 = $pin+0;
				$index = ($pin2-1) % 199 + 2;  // converts the PIN to the index
			}	
		
		
		
		if ($pin<0 or $pin>10000)  {
		  $_SESSION['error'] = "pin  out of range";
		  header('Location: QRPindex.php');
		 die();
		}
		
		$dex = ($pin-1) % 199 + 2; // % is PHP mudulus function - changing the PIN to an index between 2 and 200 / this formula has to match the one in the QRHomework.php
		//echo ($dex);
		
		$_SESSION['index'] = $dex;
		
		
		if ($problem_id<1 or $problem_id>1000000)  {
		  $_SESSION['error'] = "problem number out of range";
		  header('Location: QRPindex.php');
		 die();
		}
		
		
		
		
		// initialize a few variables
			$count = 0;
			for ($j=0;$j<=9;$j++){
					$wrongCount[$j]=0;		// accumulates how many times they missed a part
					$changed[$j]=0;		// 1 if they changed their response ero otherwise
					$addCount[$j]=0;  // this is zero if they get it right and 1 if they get it wrong
			}	
			//$_SESSION['wrongC']=$wrongCount; // temp
			$_SESSION['pin'] = $pin;
			$_SESSION['iid'] = $iid;
			$_SESSION['problem_id'] = $problem_id;
			
			
			$score = 0;
			$PScore = 0;
			$partsFlag = array();
			$resp = array('a'=>'','b'=>'','c'=>'','d'=>'','e'=>'','f'=>'','g'=>'','h'=>'','i'=>'','j'=>'');
			//$resp = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
			$corr = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
			$unit = array('a'=>"",'b'=>"",'c'=>"",'d'=>"",'e'=>"",'f'=>"",'g'=>"",'h'=>"",'i'=>"",'j'=>"");
			$tol=array('a'=>0.02,'b'=>0.02,'c'=>0.02,'d'=>0.02,'e'=>0.02,'f'=>0.02,'g'=>0.02,'h'=>0.02,'i'=>0.02,'j'=>0.02);
			$ansFormat=array('ans_a' =>"",'ans_b' =>"",	'ans_c' =>"",'ans_d' =>"",'ans_e' =>"",'ans_f' =>"",	'ans_g' =>"",'ans_h' =>"",'ans_i' =>"",'ans_j'=>"" );
			
			
			$hintLimit = 3;
			$dispBase = 1;
			
			$_SESSION['wrote_try_flag']=false;

			$tol_key=array_keys($tol);
			$resp_key=array_keys($resp);
			$corr_key=array_keys($corr);
			$ansFormat_key=array_keys($ansFormat);
			
			$time_sleep1 = 2;  // time delay in seconds
			$time_sleep1_trip = 6;  // number of trials it talkes to trip the time delay
			$time_sleep2 = 118;  // additional time if hit the next limit
			$time_sleep2_trip = 15;	
			
			// see if the problem has been suspended	
				
			$stmt = $pdo->prepare("SELECT * FROM Problem where problem_id = :problem_id");
			$stmt->execute(array(":problem_id" => $problem_id));
			$row = $stmt -> fetch();
			if ( $row === false ) {
				$_SESSION['error'] = 'Bad value for problem_id';
				header( 'Location: QRPindex.php' ) ;
				die();
			}	
			$probData=$row;	
			
			$probStatus = $probData['status'];
			if ($probStatus =='suspended'){
				$_SESSION['error'] = 'problem has been suspended, check back later';
				header( 'Location: QRPindex.php' ) ;
				die();
			}
				
			// get the tolerances and if the part has any hintfile	
				
				
			$tol['a']=$probData['tol_a']*0.001;	
			$tol['b']=$probData['tol_b']*0.001;
			$tol['c']=$probData['tol_c']*0.001;	
			$tol['d']=$probData['tol_d']*0.001;
			$tol['e']=$probData['tol_e']*0.001;	
			$tol['f']=$probData['tol_f']*0.001;
			$tol['g']=$probData['tol_g']*0.001;	
			$tol['h']=$probData['tol_h']*0.001;
			$tol['i']=$probData['tol_i']*0.001;	
			$tol['j']=$probData['tol_j']*0.001;	
			
			
			if (strlen($probData['hint_a'])>1){$hinta = $probData['hint_a'];$hintaPath="uploads/".$hinta;} else {$hintaPath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_b'])>1){$hintb = $probData['hint_b'];$hintbPath="uploads/".$hintb;} else {$hintbPath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_c'])>1){$hintc = $probData['hint_c'];$hintcPath="uploads/".$hintc;} else {$hintcPath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_d'])>1){$hintd = $probData['hint_d'];$hintdPath="uploads/".$hintd;} else {$hintdPath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_e'])>1){$hinte = $probData['hint_e'];$hintePath="uploads/".$hinte;} else {$hintePath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_f'])>1){$hintf = $probData['hint_f'];$hintfPath="uploads/".$hintf;} else {$hintfPath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_g'])>1){$hintg = $probData['hint_g'];$hintgPath="uploads/".$hintg;} else {$hintgPath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_h'])>1){$hinth = $probData['hint_h'];$hinthPath="uploads/".$hinth;} else {$hinthPath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_i'])>1){$hinti = $probData['hint_i'];$hintiPath="uploads/".$hinti;} else {$hintiPath ="uploads/default_hints.html";	}
			if (strlen($probData['hint_j'])>1){$hintj = $probData['hint_j'];$hintjPath="uploads/".$hintj;} else {$hintjPath ="uploads/default_hints.html";	}
					
			$unit = array_slice($row,22,20);  // does the same thing but easier so long as the table always has the same structure
			//print_r($unit);
			
			
		
			// Next check the Qa table and see which values have non null values - for those 
			$probParts=0;
			$stmt = $pdo->prepare("SELECT * FROM Qa where problem_id = :problem_id AND dex = :dex");
			$stmt->execute(array(":problem_id" => $problem_id, ":dex" => $dex));
			$row = $stmt -> fetch();
			if ( $row === false ) {
				$_SESSION['error'] = 'Bad value for problem_id';
				header( 'Location: QRPindex.php' ) ;
				die();
			}	
				$soln = array_slice($row,6,20); // this would mean the database table Qa would have the same structure - change the structure of the table and you break the code
			

			for ($i = 0;$i<=9; $i++){  
				if (($soln[$i]>=1.2e43 && $soln[$i] < 1.3e43) || $soln[$i]==null ) {
					$partsFlag[$i]=false;
				} else {
					$probParts = $probParts+1;
					$partsFlag[$i]=true;
				}
			}
			
			
			
		
		// look in the Checker table and see if we have an entry for this combination of pin, iid and problem_id

		$stmt = $pdo->prepare("SELECT * FROM `Checker` WHERE problem_id = :problem_id AND pin = :pin AND iid = :iid");
		$stmt->execute(array(":problem_id" => $problem_id, ":pin" => $pin, ":iid" => $iid));
		$row = $stmt -> fetch();
		if ( $row === false ) {
			$count = 0;
			$rand1= rand(100000,999999);  // sets up the rtn code on other page
			$rand2=rand(0,9);				// sets up the rtn code on the other page
			$_SESSION['rand'] = $rand1;   // temp  until I can get rtnCode.php reading from table
			 $_SESSION['rand2'] = $rand2;
			
			$_SESSION['score'] = "0"; // temp needed for the old session based system
			
			
			// need to get the expiration date from the Assignment table - which needs an expiration date //temp


			// make an entry in the checker table
			$sql = 'INSERT INTO `Checker` (problem_id, pin, iid, rand1, rand2, counts, score, wcount_a, wcount_b, wcount_c, wcount_d, wcount_e, wcount_f, wcount_g, wcount_h, wcount_i, wcount_j)	
						VALUES (:problem_id, :pin, :iid, :rand1, :rand2, :counts,:score,0,0,0,0,0,0,0,0,0,0)';
				$stmt = $pdo->prepare($sql);
				$stmt -> execute(array(
				':problem_id' => $problem_id,
				':pin' => $pin,
				':iid' => $iid,
				':rand1' => $rand1,
				':rand2' => $rand2,
				':counts' => $count,
				':score' => $score,
				));
			
			
			
			$_SESSION['problem_id'] = $problem_id;  // temp  
			$_SESSION['pin']=$pin;
			$_SESSION['index']=$dex;
			$_SESSION['iid']=$iid;
			
			
			
		}	else {          // not the first time in there was an entry in the checker table and we have data
			
			
			$checker_data = $row;
			$checker_id = $checker_data['checker_id'];
			
			

			// read the student responses into an array
			
			for ($j=0; $j<=9; $j++) {
				if($partsFlag[$j]) {
					$x = 'resp_'.$corr_key[$j];
						if (isset($_POST[$corr_key[$j]]) && is_numeric($_POST[$corr_key[$j]])){
							$resp[$corr_key[$j]] = $_POST[$corr_key[$j]]+0;
						} elseif(is_numeric($checker_data[$x])){
							$resp[$corr_key[$j]] = $checker_data[$x];
						} else {
							$resp[$corr_key[$j]] = '';
						}
			
				}
			}
			

			// see if the responses have changed
			
			if ($checker_data['resp_a']== $resp['a']){$changed[0] = false;} else  {$changed[0] = true;}
			if ($checker_data['resp_b']== $resp['b']){$changed[1] = false;} else  {$changed[1] = true;}	
			if ($checker_data['resp_c']== $resp['c']){$changed[2] = false;} else  {$changed[2] = true;}
			if ($checker_data['resp_d']== $resp['d']){$changed[3] = false;} else  {$changed[3] = true;}	
			if ($checker_data['resp_e']== $resp['e']){$changed[4] = false;} else  {$changed[4] = true;}
			if ($checker_data['resp_f']== $resp['f']){$changed[5] = false;} else  {$changed[5] = true;}	
			if ($checker_data['resp_g']== $resp['g']){$changed[6] = false;} else  {$changed[6] = true;}
			if ($checker_data['resp_h']== $resp['h']){$changed[7] = false;} else  {$changed[7] = true;}	
			if ($checker_data['resp_i']== $resp['i']){$changed[8] = false;} else  {$changed[8] = true;}
			if ($checker_data['resp_j']== $resp['j']){$changed[9] = false;} else  {$changed[9] = true;}	
			
			
			// check to see if the responses are within tolerance
			
			for ($j=0; $j<=9; $j++) {
				if($partsFlag[$j]) {
								
					if($soln[$j]==0){ 

						// its ero and they got it right
						if(is_numeric($_POST[$resp_key[$j]])){
								if ($_POST[$resp_key[$j]]==0){
											$corr[$corr_key[$j]]='Correct';
											$score=$score+1;
											$addCount[$j]=0;
									} else {
											// its zero and they got it wrong or did not attempt
																		
									if ($resp[$resp_key[$j]]=='' )  //  did not attempted it
									{
										$addCount[$j]=0;
										$corr[$corr_key[$j]]='';
									
									} elseif($changed[$j]==false) { // did not change it from last time but it's still wrong
											$corr[$corr_key[$j]]='Not Correct';
											$addCount[$j]=0;
									}
									else  // not correct (better to use POST value I suppose - fix later
									{
										$addCount[$j]=1;
									
											$corr[$corr_key[$j]]='Not Correct';
									
									}
								}		
								
								
								
							}

						}	else {	
						
					 
						//	$sol=$soln[$j]; 
							
						
						if(	abs(($soln[$j]-$resp[$resp_key[$j]])/$soln[$j])<= $tol[$tol_key[$j]]) {
							//echo ($tol[$tol_key[$j]]);
							$corr[$corr_key[$j]]='Correct';
							$score=$score+1;
							//	$_SESSION['$wrongC'[$j]] = 0;
						
							$addCount[$j]=0;  // if they get it right this gets set to zero?
													
						} else {// got it wrong or did not attempt
									
							if ($resp[$resp_key[$j]]=='' )  //  did not attempted it
							{
								$addCount[$j]=0;
								$corr[$corr_key[$j]]='';
							
							} elseif($changed[$j]==false) { // did not change it from last time but it's still wrong
									$corr[$corr_key[$j]]='Not Correct';
									$addCount[$j]=0;
							}
							else  // not correct (better to use POST value I suppose - fix later
							{
								$addCount[$j]=1;
							
									$corr[$corr_key[$j]]='Not Correct';
							}
							}
					}		
				}
			}
			
			if ($probParts !=0 ){$PScore=round($score/$probParts*100);} else {$PScore = 0;}  // Pscore is the percent score
			// talley the wrong counts
			
			$wrongCount[0] = $addCount[0] + $checker_data['wcount_a'];
			$wrongCount[1] = $addCount[1] + $checker_data['wcount_b'];
			$wrongCount[2] = $addCount[2] + $checker_data['wcount_c'];
			$wrongCount[3] = $addCount[3] + $checker_data['wcount_d'];
			$wrongCount[4] = $addCount[4] + $checker_data['wcount_e'];
			$wrongCount[5] = $addCount[5] + $checker_data['wcount_f'];
			$wrongCount[6] = $addCount[6] + $checker_data['wcount_g'];
			$wrongCount[7] = $addCount[7] + $checker_data['wcount_h'];
			$wrongCount[8] = $addCount[8] + $checker_data['wcount_i'];
			$wrongCount[9] = $addCount[9] + $checker_data['wcount_j'];
			
			
			
			$count = 0;
			for ($j=0;$j<9;$j++){
					$count = $wrongCount[$j]+$count;
			}	
			
			
			// Update the responses to the table

			$sql = "UPDATE Checker 
				SET resp_a = :resp_a, resp_b = :resp_b, resp_c = :resp_c, resp_d = :resp_d,
					resp_e = :resp_e, resp_f = :resp_f, resp_g = :resp_g, resp_h = :resp_h,
					resp_i = :resp_i, resp_j = :resp_j, 
					wcount_a = :wcount_a, wcount_b = :wcount_b, wcount_c = :wcount_c, wcount_d = :wcount_d,
					wcount_e = :wcount_e, wcount_f = :wcount_f, wcount_g = :wcount_g, wcount_h = :wcount_h,
					wcount_i = :wcount_i, wcount_j = :wcount_j, score = :score, counts = :counts
				WHERE checker_id = :checker_id";
			$stmt = $pdo->prepare($sql);
			$stmt -> execute(array(
			':resp_a' => $resp['a'],
			':resp_b' => $resp['b'],
			':resp_c' => $resp['c'],
			':resp_d' => $resp['d'],
			':resp_e' => $resp['e'],
			':resp_f' => $resp['f'],
			':resp_g' => $resp['g'],
			':resp_h' => $resp['h'],
			':resp_i' => $resp['i'],
			':resp_j' => $resp['j'],
			':wcount_a' => $wrongCount[0],
			':wcount_b' => $wrongCount[1],
			':wcount_c' => $wrongCount[2],
			':wcount_d' => $wrongCount[3],
			':wcount_e' => $wrongCount[4],
			':wcount_f' => $wrongCount[5],
			':wcount_g' => $wrongCount[6],
			':wcount_h' => $wrongCount[7],
			':wcount_i' => $wrongCount[8],
			':wcount_j' => $wrongCount[9],
			':score' => $score,
			':counts' => $count,
			':checker_id' => $checker_id,
			
			));
		}


		//get the tolerance for each part - only really need to do this once on the get request 
			/* $stmt = $pdo->prepare("SELECT * FROM Problem where problem_id = :problem_id");
			$stmt->execute(array(":problem_id" => $problem_id));
			$row = $stmt -> fetch(); */
			
			
			
	
	
			// test to see if the instructor put in the code to get the answers					
							
			
	
	
	
	
			// get the basecase database
			
			$stmt = $pdo->prepare("SELECT * FROM Qa where problem_id = :problem_id AND dex = :dex");
			$stmt->execute(array(":problem_id" => $problem_id, ":dex" => 1));
			$row = $stmt -> fetch();
				for ($j=0;$j<=9;$j++){
					$baseAns[$corr_key[$j]]=$row[$ansFormat_key[$j]];
				}
	
	
		



	// If $PScore gets to 100, write the number of tries to the problem in the proper bin num_try_1 is first try, num_try_2 is 2 - 3 tries ...
	// doing it here instead of the GetRating.php allows to keep track of external people solving the problem by bruit force guessing we can write the tries to the activity table in that module 
	//

	// if they scored 100 and have not wrote the number of tries before
	// count is actually one more than it should be
		
		if ($PScore == 100 && !$_SESSION['wrote_try_flag']) {
				
				$_SESSION['wrote_try_flag'] = true;
					
					// update the problem data on the cummulative wrong counts
					
					$sql = "UPDATE Problem SET `cumm_wcount_a` = COALESCE(`cumm_wcount_a`+ $wrongCount[0],`cumm_wcount_a`+0,0)  
												,`cumm_wcount_b` = COALESCE(`cumm_wcount_b`+ $wrongCount[1],`cumm_wcount_b`+0,0)  
												,`cumm_wcount_c` = COALESCE(`cumm_wcount_c`+ $wrongCount[2],`cumm_wcount_c`+0,0)  
												,`cumm_wcount_d` = COALESCE(`cumm_wcount_d`+ $wrongCount[3],`cumm_wcount_d`+0,0)
												,`cumm_wcount_e` = COALESCE(`cumm_wcount_e`+ $wrongCount[4],`cumm_wcount_e`+0,0) 
												,`cumm_wcount_f` = COALESCE(`cumm_wcount_f`+ $wrongCount[5],`cumm_wcount_f`+0,0)
												,`cumm_wcount_g` = COALESCE(`cumm_wcount_g`+ $wrongCount[6],`cumm_wcount_g`+0,0)
												,`cumm_wcount_h` = COALESCE(`cumm_wcount_h`+ $wrongCount[7],`cumm_wcount_h`+0,0)
												,`cumm_wcount_i` = COALESCE(`cumm_wcount_i`+ $wrongCount[8],`cumm_wcount_i`+0,0) 
												,`cumm_wcount_j` = COALESCE(`cumm_wcount_j`+ $wrongCount[9],`cumm_wcount_j`+0,0)
						WHERE problem_id = :problem_id";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(array(':problem_id' => $problem_id));
					
					
				if ($count == 2){ 
					$sql = "UPDATE Problem SET `num_try_1` = coalesce(`num_try_1`+1,1) WHERE problem_id = :problem_id";  // coalesce returns the first non-null value in the list
						$stmt = $pdo->prepare($sql);
						$stmt->execute(array(':problem_id' => $problem_id));		
				}	
				if ($count >= 3 && $count <= 5){ 
					$sql = "UPDATE Problem SET `num_try_2` = coalesce(`num_try_2`+1,1) WHERE problem_id = :problem_id";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(array(':problem_id' => $problem_id));		
				}
				if ($count >= 6 && $count <= 11){ 
					$sql = "UPDATE Problem SET `num_try_3` = coalesce(`num_try_3`+1,1) WHERE problem_id = :problem_id";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(array(':problem_id' => $problem_id));		
				}					
				if ($count >= 12 && $count <= 21){ 
					$sql = "UPDATE Problem SET `num_try_4` = coalesce(`num_try_4`+1,1) WHERE problem_id = :problem_id";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(array(':problem_id' => $problem_id));		
				}					

				if ($count >= 22 && $count <= 41){ 
					$sql = "UPDATE Problem SET `num_try_5` = coalesce(`num_try_5`+1,1) WHERE problem_id = :problem_id";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(array(':problem_id' => $problem_id));		
				}					
				if ($count >= 42 ){ 
					$sql = "UPDATE Problem SET `num_try_6` = coalesce(`num_try_6`+1,1) WHERE problem_id = :problem_id";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(array(':problem_id' => $problem_id));		
				}					
		
		
	}


		
	if(isset($_POST['pin']) && $index<=200 && $index>0 && $dispAnsflag)
		{
		echo "<table>";
		echo "Answers:";
		echo '<table border="1">';


		echo "<tr><th>index</th>";
		echo	"<th>a)</th>";
		echo	"<th>b)</th>";
		echo	"<th>c)</th>";
		echo	"<th>d)</th>";
		echo	"<th>e)</th>";
		echo	"<th>f)</th>";
		echo	"<th>g)</th>";
		echo	"<th>h)</th>";
		echo	"<th>i)</th>";
		echo	"<th>j)</th></tr>";
		echo	"<tr>";



		
			
		echo "<tr><td>";
		echo ($dex);
		echo ("</td><td>");
		echo ($soln['ans_a']);
		echo ("</td><td>");
		echo ($soln['ans_b']);
		echo ("</td><td>");
		echo ($soln['ans_c']);
		echo ("</td><td>");
		echo ($soln['ans_d']);
		echo ("</td><td>");
		echo ($soln['ans_e']);
		echo ("</td><td>");
		echo ($soln['ans_f']);
		echo ("</td><td>");
		echo ($soln['ans_g']);
		echo ("</td><td>");
		echo ($soln['ans_h']);
		echo ("</td><td>");
		echo ($soln['ans_i']);
		echo ("</td><td>");
		echo ($soln['ans_j']);
		echo ("</td>");
		
	}
		
	

	echo '</table>'	;
	
	if(isset($_POST['show_base']) and $dispBase){
		
	}
		
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
	<h1>QRProblem Checker</h1>
	</header>
	<main>
	<p> Course: <?php echo ($cclass_name); ?> </p>
	<p> Assignment Number: <?php echo ($assign_num); ?> </p>
	<p> Problem Number: <?php echo ($alias_num); ?> &nbsp;&nbsp;&nbsp;
	 <font size = "1">- Pid: <?php echo ($problem_id); ?> </font></p>
	

	<form autocomplete="off" method="POST" >
	<!-- <p>Problem Number: <input type="text" name="problem_number" ></p> -->
	<!-- <p> Please put in your index number </p> -->
	<p><font color=#003399>PIN: </font><input type="text" name="pin" size=3 value="<?php echo ($pin);?>"  ></p>
		
	<p> <strong> Fill in - then select "Check" </strong></p>


	<?php


	if ($partsFlag[0]){ ?> 
	<p> a): <input [ type=number]{width: 5%;} name="a" size = 10% value="<?php echo (htmlentities($resp['a']))?>" > <?php echo($unit[0]) ?> &nbsp - <b><?php echo ($corr['a']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['a']=="Correct" ){echo '- Computed value is: '.$soln[0];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[0]>$hintLimit and $corr['a']=="Not Correct" && $hintaPath != "uploads/default_hints.html" ){echo '<a href="'.$hintaPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[0] and @$wrongCount[0]>$time_sleep1_trip and @$wrongCount[0]< $time_sleep2_trip and $corr['a']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[0] and @$wrongCount[0]>=$time_sleep2_trip and $corr['a']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	  </p>
	<?php } 

	if ($partsFlag[1]){ ?> 
	<p> b): <input [ type=number]{width: 5%;} name="b" size = 10% value="<?php echo (htmlentities($resp['b']))?>" > <?php echo($unit[1]) ?> &nbsp - <b><?php echo ($corr['b']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['b']=="Correct" ){echo '- Computed value is: '.$soln[1];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[1]>$hintLimit and $corr['b']=="Not Correct" && $hintbPath != "uploads/default_hints.html" ){echo '<a href="'.$hintbPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[1] and @$wrongCount[1]>$time_sleep1_trip and @$wrongCount[1]< $time_sleep2_trip and $corr['b']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[1] and @$wrongCount[1]>=$time_sleep2_trip and $corr['b']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[2]){ ?> 
	<p> c): <input [ type=number]{width: 5%;} name="c" size = 10% value="<?php echo (htmlentities($resp['c']))?>" > <?php echo($unit[2]) ?> &nbsp - <b><?php echo ($corr['c']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['c']=="Correct" ){echo '- Computed value is: '.$soln[2];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[2]>$hintLimit and $corr['c']=="Not Correct"&& $hintcPath != "uploads/default_hints.html" ){echo '<a href="'.$hintcPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[2] and @$wrongCount[2]>$time_sleep1_trip and @$wrongCount[2]< $time_sleep2_trip and $corr['c']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[2] and @$wrongCount[2]>=$time_sleep2_trip and $corr['c']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[3]){ ?> 
	<p> d): <input [ type=number]{width: 5%;} name="d" size = 10% value="<?php echo (htmlentities($resp['d']))?>" > <?php echo($unit[3]) ?> &nbsp - <b><?php echo ($corr['d']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['d']=="Correct" ){echo '- Computed value is: '.$soln[3];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[3]>$hintLimit and $corr['d']=="Not Correct"&& $hintdPath != "uploads/default_hints.html" ){echo '<a href="'.$hintdPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[3] and @$wrongCount[3]>$time_sleep1_trip and @$wrongCount[3]< $time_sleep2_trip and $corr['d']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[3] and @$wrongCount[3]>=$time_sleep2_trip and $corr['d']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	
	</p>
	<?php } 

	if ($partsFlag[4]){ ?> 
	<p> e): <input [ type=number]{width: 5%;} name="e" size = 10% value="<?php echo (htmlentities($resp['e']))?>" > <?php echo($unit[4]) ?> &nbsp - <b><?php echo ($corr['e']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['e']=="Correct" ){echo '- Computed value is: '.$soln[4];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[4]>$hintLimit and $corr['e']=="Not Correct"&& $hintePath != "uploads/default_hints.html" ){echo '<a href="'.$hintePath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[4] and @$wrongCount[4]>$time_sleep1_trip and @$wrongCount[4]< $time_sleep1_trip and $corr['e']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[4] and @$wrongCount[4]>=$time_sleep2_trip and $corr['e']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[5]){ ?> 
	<p> f): <input [ type=number]{width: 5%;} name="f" size = 10% value="<?php echo (htmlentities($resp['f']))?>" > <?php echo($unit[5]) ?> &nbsp - <b><?php echo ($corr['f']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['f']=="Correct" ){echo '- Computed value is: '.$soln[5];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[5]>$hintLimit and $corr['f']=="Not Correct"&& $hintfPath != "uploads/default_hints.html" ){echo '<a href="'.$hintfPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[5] and @$wrongCount[5]>$time_sleep1_trip and @$wrongCount[5]< $time_sleep2_trip and $corr['f']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[5] and @$wrongCount[5]>=$time_sleep2_trip and $corr['f']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[6]){ ?> 
	<p> g): <input [ type=number]{width: 5%;} name="g" size = 10% value="<?php echo (htmlentities($resp['g']))?>" > <?php echo($unit[6]) ?> &nbsp - <b><?php echo ($corr['g']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['g']=="Correct" ){echo '- Computed value is: '.$soln[6];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[6]>$hintLimit and $corr['g']=="Not Correct"&& $hintgPath != "uploads/default_hints.html" ){echo '<a href="'.$hintgPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[6] and @$wrongCount[6]>$time_sleep1_trip and @$wrongCount[6]< $time_sleep2_trip and $corr['g']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[6] and @$wrongCount[6]>=$time_sleep2_trip and $corr['g']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[7]){ ?> 
	<p> h): <input [ type=number]{width: 5%;} name="h" size = 10% value="<?php echo (htmlentities($resp['h']))?>" > <?php echo($unit[7]) ?> &nbsp - <b><?php echo ($corr['h']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['h']=="Correct" ){echo '- Computed value is: '.$soln[7];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[7]>$hintLimit and $corr['h']=="Not Correct"&& $hinthPath != "uploads/default_hints.html" ){echo '<a href="'.$hinthPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[7] and @$wrongCount[7]>$time_sleep1_trip and @$wrongCount[7]< $time_sleep2_trip and $corr['h']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[7] and @$wrongCount[7]>=$time_sleep2_trip and $corr['h']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[8]){ ?> 
	<p> i): <input [ type=number]{width: 5%;} name="i" size = 10% value="<?php echo (htmlentities($resp['i']))?>" > <?php echo($unit[8]) ?> &nbsp - <b><?php echo ($corr['i']) ?> </b>
	<?php if (isset($_POST['pin']) and $corr['i']=="Correct" ){echo '- Computed value is: '.$soln[8];} ?>  
	<?php if (isset($_POST['pin']) and @$wrongCount[8]>$hintLimit and $corr['i']=="Not Correct"&& $hintiPath != "uploads/default_hints.html" ){echo '<a href="'.$hintiPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[8] and @$wrongCount[8]>$time_sleep1_trip and @$wrongCount[8]< $time_sleep2_trip and $corr['i']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[8] and @$wrongCount[8]>=$time_sleep2_trip and $corr['i']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 

	if ($partsFlag[9]){ ?> 
	<p> j): <input [ type=number]{width: 5%;} name="j" size = 10% value="<?php echo (htmlentities($resp['j']))?>" > <?php echo($unit[9]) ?> &nbsp - <b><?php echo ($corr['j']) ?> </b>
	<?php if (isset($_POST['pin']) and @$wrongCount[9]>$hintLimit and $corr['j']=="Not Correct"&& $hintjPath != "uploads/default_hints.html" ){echo '<a href="'.$hintjPath.'"target = "_blank"> hints for this part </a>';} ?>  
	<?php if (isset($_POST['pin']) and $changed[9] and @$wrongCount[9]>$time_sleep1_trip and @$wrongCount[9]< $time_sleep2_trip and $corr['j']=="Not Correct"){echo ("   time delay ".$time_sleep1." s"); sleep($time_sleep1);} ?>
	<?php if (isset($_POST['pin']) and $changed[9] and @$wrongCount[9]>=$time_sleep2_trip and $corr['j']=="Not Correct"){echo ("   time delay ".$time_sleep2." s"); sleep($time_sleep2);} ?>
	</p>
	<?php } 


	?>

	<!--<p>Grading Scheme: <input type="text" name="grade_scheme" ></p> -->
	<p><input type = "submit" value="Check" size="10" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp <b> <font size="4" color="Navy">Score:  <?php echo ($PScore) ?>%</font></b></p>


	</form>




	<p> Count: <?php echo ($count) ?> </p>

	<!-- <form method="get" >
	<p><input type = "submit" value="Finished"/> </p>
	</form> -->
<?php if ($Bypass==1){echo('<b><font size = "4" color = "red" > Contribitors and Instructors - Do not go to the rating section! </font></b>');}?>
	<form action="GetRating.php" method="POST">
	 <hr>
	<p><b><font Color="red">When Finished:</font></b></p>
	  <!--<input type="hidden" name="score" value=<?php echo ($score) ?> /> -->
	  <?php  $_SESSION['score'] = $PScore; $_SESSION['index'] = $index; $_SESSION['count'] = $count;?> 
	  
	  <input type = "number" hidden name = "score"  value = <?php echo ($PScore); ?> > </input>
	  <input type = "number" hidden name = "problem_id"  value = <?php echo ($problem_id); ?> > </input>
	   <input type = "number" hidden name = "iid"  value = <?php echo ($iid); ?> > </input>
		<input type = "number" hidden name = "pin"  value = <?php echo ($pin); ?> > </input>
		 <input type = "number" hidden name = "count"  value = <?php echo ($count); ?> > </input>
	 <b><input type="submit" value="Rate & Get rtn Code" style = "width: 30%; background-color:yellow "></b>
	 <p><br> </p>
	 <hr>
	</form>

	<form method = "POST">
	<p><input type = "submit" value="Get Base-Case Answers" name = "show_base" size="10" id = "show_base" style = "width: 30%; background-color: green; color: white"/> &nbsp &nbsp <b> <font size="4" color="Green"></font></b></p>
	  <input type = "number" hidden name = "problem_id"  value = <?php echo ($problem_id); ?> > </input>
	  <input type = "number" hidden name = "a"  value = <?php echo ($resp['a']); ?> > </input>
	  <input type = "number" hidden name = "b"  value = <?php echo ($resp['b']); ?> > </input>
	  <input type = "number" hidden name = "c"  value = <?php echo ($resp['c']); ?> > </input>
	  <input type = "number" hidden name = "d"  value = <?php echo ($resp['d']); ?> > </input>
	  <input type = "number" hidden name = "e"  value = <?php echo ($resp['e']); ?> > </input>
	  <input type = "number" hidden name = "f"  value = <?php echo ($resp['f']); ?> > </input>
	  <input type = "number" hidden name = "g"  value = <?php echo ($resp['g']); ?> > </input>
	  <input type = "number" hidden name = "h"  value = <?php echo ($resp['h']); ?> > </input>
	  <input type = "number" hidden name = "i"  value = <?php echo ($resp['i']); ?> > </input>
	  <input type = "number" hidden name = "j"  value = <?php echo ($resp['j']); ?> > </input>
	   <input type = "number" hidden name = "iid"  value = <?php echo ($iid); ?> > </input>
	<input type = "number" hidden name = "pin"  value = <?php echo ($pin); ?> > </input>
	
	</form>
	<div id = 'bc_answers'> </div>
	
	
	<?php


	if(isset($_POST['show_base']) and $dispBase){
			
			// do this with Javascript/jquery
			
			echo "<table>";
			echo "Base-Case Answers:";
			echo '<table border="1">';
			
				for ($j=0;$j<=9;$j++){
					if($partsFlag[$j]){
						echo	("<th>$corr_key[$j]</th>");
					}
					
					//echo ("</td><td>");
				}
				//echo ("</td>");
				
				echo "<tr>";
				for ($j=0;$j<=9;$j++){
					if($partsFlag[$j]){
						echo ("<td>");
						echo ($baseAns[$corr_key[$j]]);
						echo ("</td>");
					}
			
				}

		}





	?>




	</main>
	</body>
	</html>