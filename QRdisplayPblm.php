<?php
require_once "pdo.php";
session_start();


// this strips out the get parameters so they are not in the url - its is not really secure data but I would rather not having people messing with them
// if they do not what they are doing

 if (!empty($_GET)) {
        $_SESSION['got'] = $_GET;
        header('Location: QRdisplayPblm.php');
        die;
    } else{
        if (!empty($_SESSION['got'])) {
            $_GET = $_SESSION['got'];
            unset($_SESSION['got']);
        }

        //use the $_GET vars here..
    

//  Set the varaibles to the Get Parameters or if they do not exist try the session variables if those don't exist error back to QRhomework


	if(isset($_GET['problem_id'])) {
			$problem_id = htmlentities($_GET['problem_id']);
		}else if(isset($_SESSION['problem_id'])) {
			$problem_id = htmlentities($_SESSION['problem_id']);
		} else {
			$_SESSION['error'] = 'problem_id is not being read into the diplay error 30';
			header("Location: QRhomework.php");
			return;
	} 

	if(isset($_GET['dex'])) {
			$dex = htmlentities($_GET['dex']);
		}else if(isset($_SESSION['dex'])) {
			$dex = htmlentities($_SESSION['dex']);
		} else {
			$_SESSION['error'] = 'dex is not being read into the diplay error 31';
			header("Location: QRhomework.php");
			return;
	} 

	if(isset($_GET['pin'])) {
			$pin = htmlentities($_GET['pin']);
		}else if(isset($_SESSION['pin'])) {
			$pin = htmlentities($_SESSION['pin']);
		} else {
			$_SESSION['error'] = 'pin is not being read into the diplay error 32';
			header("Location: QRhomework.php");
			return;
	} 

	if(isset($_GET['iid'])) {
			$iid = htmlentities($_GET['iid']);
		}else if(isset($_SESSION['iid'])) {
			$iid = htmlentities($_SESSION['iid']);
		} else {
			$_SESSION['error'] = 'iid is not being read into the diplay error 33';
			header("Location: QRhomework.php");
			return;
	} 

	if(isset($_GET['stu_name'])) {
			$stu_name = htmlentities($_GET['stu_name']);
		}else if(isset($_SESSION['stu_name'])) {
			$stu_name = htmlentities($_SESSION['stu_name']);
		} else {
			$_SESSION['error'] = 'stu_name is not being read into the diplay error 34';
			header("Location: QRhomework.php");
			return;
	} 

	}
// can do the same as above ot the rest of the varaibles but won't unless I have trouble




 $sql = "SELECT `htmlfilenm` FROM Problem WHERE problem_id = :problem_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':problem_id' => $problem_id));
	$data = $stmt -> fetch();
	// need to put some error checking here
		$rows=$data;


$htmlfilenm = "uploads/".$rows['htmlfilenm'];



// passing my php varables into the js varaibles needed for the script below
$pass = array(
    'dex' => $dex,
    'problem_id' => $problem_id,
    'stu_name' => $stu_name,
	'pin' => $pin,
	'iid' => $iid,
	'reflect_flag' => $_GET['reflect_flag'],
	'explore_flag' => $_GET['explore_flag'],  // these are set in 
	'connect_flag' => $_GET['connect_flag'],
	'society_flag' => $_GET['society_flag'],
	'choice' => $_GET['choice'],
	
	'pp1' => $_GET['pp1'],
	'pp2' => $_GET['pp2'],
	'pp3' => $_GET['pp3'],
	'pp4' => $_GET['pp4'],
	'time_pp1' => $_GET['time_pp1'],
	'time_pp2' => $_GET['time_pp2'],
	'time_pp3' => $_GET['time_pp3'],
	'time_pp4' => $_GET['time_pp4'],
);

// echo ($pass['society_flag']);
//die();
echo '<script>';
echo 'var pass = ' . json_encode($pass) . ';';
echo '</script>';
  
  
 
 // 

?>

<!DOCTYPE html>
<html lang = "en">
<head>
<meta charset="UTF-8">

<link rel="icon" type="image/png" href="McKetta.png" >

<title>QRHomework</title> 
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>





</head>

<body>
<div id = substitute_me>  </div>
<?php  //iconv("Windows-1252", "UTF-8", include($htmlfilenm)); 
			include($htmlfilenm);

 ?>
<script>
$(document).ready(function(){
		var dex = pass['dex'];
		var problem = pass['problem_id'];
		var s_name = pass['stu_name'];
		var pin = pass['pin'];
		var iid = pass['iid'];
		var reflect_flag = pass['reflect_flag'];
		var explore_flag = pass['explore_flag'];
		var connect_flag = pass['connect_flag'];
		var society_flag = pass['society_flag'];
		var choice = pass['choice'];
		var pp1 = pass['pp1'];
		var pp2 = pass['pp2'];
		var pp3 = pass['pp3'];
		var pp4 = pass['pp4'];
		var time_pp1 = pass['time_pp1'];
		var time_pp2 = pass['time_pp2'];
		var time_pp3 = pass['time_pp3'];
		var time_pp4 = pass['time_pp4'];
		var statusFlag=true;
			
			
		if($.trim(problem) != '' && problem > 0 && problem < 100000 && dex>=1 && dex<=200){
	// alert(1);
	
				 $.post('fetchpblminput.php', {problem_id : problem, index : dex }, function(data){
					
					try{
						var arr = JSON.parse(data);
					}
					catch(err) {
						alert ('problem data unavailable');
					}
				
				// Get the html file name from the database
					
					var openup = arr.htmlfilenm;
					
					openup = escape(openup);
					
					// openup = "'"+openup+"'";
					
					// alert(openup);
					//console.log (arr);
					var game = arr.game_prob_flag;
					var status = arr.status;
					var prob_num = arr.problem_id;
					var contrib_first = arr.first;
					var contrib_last = arr.last;
					var contrib_university = arr.university;
					var static_f = false;
					
					
				
// next just put the substvars. js script in here but have it use the $('#substitute_me').load("uploads/"+openup+".html");  or something similar  THis should replace the stuff up
// in the html part of this document and then operate on it with the script file form the substvars stuff.  I need to get rid of all of the localstorage stuff from both this script and the one from substvars
// The substvars script will should be elliminated from all future uploaded html problem files.
			
			//	console.log (openup);
			
		//	$('#substitute_me').load("uploads/"+openup, 'document').html();
			
			// now change the source of the images so that they are loaded properly
			//console.log('wtf');
				
				
				
				
				
				
				
				// have to put uploads/ in front of file 

					sessionStorage.setItem('contrib_first',contrib_first);
					sessionStorage.setItem('contrib_last',contrib_last);
					sessionStorage.setItem('contrib_university',contrib_university);
					sessionStorage.setItem('nm_author',arr.nm_author);
					sessionStorage.setItem('specif_ref',arr.specif_ref);
					
					console.log(contrib_last);
					var contrib_last2 = sessionStorage.getItem('contrib_last');
					console.log('contrib_last 2',contrib_last2);
					
				//	console.log('arr', arr);
					if (status !== 'suspended'){
							
							sessionStorage.setItem('MC_flag','false');
							sessionStorage.setItem('nv_1',arr.nv_1);
							sessionStorage.setItem(arr.nv_1,arr.v_1);
							sessionStorage.setItem('nv_2',arr.nv_2);
							sessionStorage.setItem(arr.nv_2,arr.v_2);
							sessionStorage.setItem('nv_3',arr.nv_3);
							sessionStorage.setItem(arr.nv_3,arr.v_3);
							sessionStorage.setItem('nv_4',arr.nv_4);
							sessionStorage.setItem(arr.nv_4,arr.v_4);
							sessionStorage.setItem('nv_5',arr.nv_5);
							sessionStorage.setItem(arr.nv_5,arr.v_5);
							sessionStorage.setItem('nv_6',arr.nv_6);
							sessionStorage.setItem(arr.nv_6,arr.v_6);
							sessionStorage.setItem('nv_7',arr.nv_7);
							sessionStorage.setItem(arr.nv_7,arr.v_7);
							sessionStorage.setItem('nv_8',arr.nv_8);
							sessionStorage.setItem(arr.nv_8,arr.v_8);
							sessionStorage.setItem('nv_9',arr.nv_9);
							sessionStorage.setItem(arr.nv_9,arr.v_9);
							sessionStorage.setItem('nv_10',arr.nv_10);
							sessionStorage.setItem(arr.nv_10,arr.v_10);
							sessionStorage.setItem('nv_11',arr.nv_11);
							sessionStorage.setItem(arr.nv_11,arr.v_11);
							sessionStorage.setItem('nv_12',arr.nv_12);
							sessionStorage.setItem(arr.nv_12,arr.v_12);
							sessionStorage.setItem('nv_13',arr.nv_13);
							sessionStorage.setItem(arr.nv_13,arr.v_13);
							sessionStorage.setItem('nv_14',arr.nv_14);
							sessionStorage.setItem(arr.nv_14,arr.v_14);
							
							
							sessionStorage.setItem('title',arr.title);
							/* sessionStorage.setItem('stu_name',s_name);
							sessionStorage.setItem('problem_id',problem);
							sessionStorage.setItem('dex',dex);
							sessionStorage.setItem('pin',pin);
							sessionStorage.setItem('iid',iid); */
							sessionStorage.setItem('reflect_flag',reflect_flag);
							sessionStorage.setItem('explore_flag',explore_flag);
							sessionStorage.setItem('connect_flag',connect_flag);
							sessionStorage.setItem('society_flag',society_flag);
							sessionStorage.setItem('choice',choice);
							sessionStorage.setItem('static_flag',static_f);
							sessionStorage.setItem('pp1',pp1);
							sessionStorage.setItem('pp2',pp2);
							sessionStorage.setItem('pp3',pp3);
							sessionStorage.setItem('pp4',pp4);
							sessionStorage.setItem('time_pp1',time_pp1);
							sessionStorage.setItem('time_pp2',time_pp2);
							sessionStorage.setItem('time_pp3',time_pp3);
							sessionStorage.setItem('time_pp4',time_pp4);
					
					
				 } else {
					
						alert('This problem is temporarily suspended, please check back later.');
						//window.location.href="QRhomework.php";
						
						statusFlag=false;
						//return;
					

				 }

					 
					
		  });
		  
		  // get the basecase data
		   $.post('fetchpblminput.php', {problem_id : problem, index : 1 }, function(data){
					
					var arr2 = JSON.parse(data);
				// Get the html file name from the database
					
				//	var openup = arr.htmlfilenm;
				
				var openup = arr2.htmlfilenm;		
				//	alert(openup);
				
			//	alert (openup);
				if (openup == null){
					
				alert('problem not present');
				return;
					
				}
				
				
				var game = arr2.game_prob_flag;
					
				//	Set up the basecase values into the local variables
					if (statusFlag){
						if (game==0){
							
							var x = "bc_"+arr2.nv_1;
							sessionStorage.setItem(x,arr2.v_1);
							
							x = "bc_"+arr2.nv_2;
							sessionStorage.setItem(x,arr2.v_2);
							
							x = "bc_"+arr2.nv_3;
							sessionStorage.setItem(x,arr2.v_3);
								x = "bc_"+arr2.nv_4;
							sessionStorage.setItem(x,arr2.v_4);
							x = "bc_"+arr2.nv_5;
							sessionStorage.setItem(x,arr2.v_5);
							x = "bc_"+arr2.nv_6;
							sessionStorage.setItem(x,arr2.v_6);
								x = "bc_"+arr2.nv_7;
							sessionStorage.setItem(x,arr2.v_7);
							x = "bc_"+arr2.nv_8;
							sessionStorage.setItem(x,arr2.v_8);
							x = "bc_"+arr2.nv_9;
							sessionStorage.setItem(x,arr2.v_9);
								x = "bc_"+arr2.nv_10;
							sessionStorage.setItem(x,arr2.v_10);
							x = "bc_"+arr2.nv_11;
							sessionStorage.setItem(x,arr2.v_11);
							x = "bc_"+arr2.nv_12;
							sessionStorage.setItem(x,arr2.v_12);
								x = "bc_"+arr2.nv_13;
							sessionStorage.setItem(x,arr2.v_13);
							x = "bc_"+arr2.nv_14;
							sessionStorage.setItem(x,arr2.v_14);
							
							/* sessionStorage.setItem('title',arr2.title);
							sessionStorage.setItem('stu_name',s_name);
							sessionStorage.setItem('problem_id',problem);
							sessionStorage.setItem('index',inde); */
						
					// redirect the browser to the problem file
					
				// alert (statusFlag);

				// should run the php in the model to test the user input make sure the instructor ID or last name is vaiid and create and entry in the temp table if there 
				// isnt one and read the status if there is one and put it in the hidden html or get it via Json and AJAX
				
			// load the external javascript file to make the magic happen
			// this comes from https://stackoverflow.com/questions/14644558/call-javascript-function-after-script-is-loaded 		
				
				function loadScript( url, callback ) {
					  var script = document.createElement( "script" )
					  script.type = "text/javascript";
					  if(script.readyState) {  // only required for IE <9
						script.onreadystatechange = function() {
						  if ( script.readyState === "loaded" || script.readyState === "complete" ) {
							script.onreadystatechange = null;
							callback();
						  }
						};
					  } else {  //Others
						script.onload = function() {
						  callback();
						};
					  }

					  script.src = "Substvars.js";
					  document.getElementsByTagName( "head" )[0].appendChild( script );
					}

					
					
		
					// call the function...
					loadScript("Substvars.js", function() {
					//  alert('script ready!'); 
					  	var imgPath = '';
						var indexQRP = '';
						var addPath = "uploads/";
					//	alert(addPath);
								$('img').each(function(){
									
									imgPath = $(this).prop('src');
										console.log('imagepath before',imgPath);
								//		alert (imgPath);
										//referrer.toLowerCase().indexOf
									indexQRP = imgPath.toLowerCase().indexOf('/qrp/')+5;
									console.log('indexofQRP',indexQRP);
									imgPath = [imgPath.slice(0, indexQRP), addPath, imgPath.slice(indexQRP)].join('');
									console.log('imagepath',imgPath);
									
									$(this).prop('src', imgPath);
								
								});
					});
					
				/* 	// try this as a separate script below
					// comes from https://stackoverflow.com/questions/6985507/one-time-page-refresh-after-first-page-load on reloading the page to get rid of the sometimes error of varaibles not being substituted in
					window.onload = function() {
						if(!window.location.hash) {
							window.location = window.location + '#loaded';
							window.location.reload();
						}
					}
						 */	
					
						// in this case we are using the php to load the function so we do not need to replace the substitute me with the JQ
					
			/* 		
					$('#substitute_me').load("uploads/"+openup, 'document', function () {
												// call the function...
												loadScript("Substvars.js", function() {
													//  alert('script ready!'); 
													var imgPath = '';
													var indexQRP = '';
													var addPath = "uploads/";
													//	alert(addPath);
													
													// for each image in the document slip in the qrp subdirectory designation into the path to get the correct path to the image
													$('img').each(function(){
														
														imgPath = $(this).prop('src');
															console.log('imagepath before',imgPath);
														//		alert (imgPath);
															//referrer.toLowerCase().indexOf
														indexQRP = imgPath.toLowerCase().indexOf('/qrp/')+5;
														console.log('indexofQRP',indexQRP);
														imgPath = [imgPath.slice(0, indexQRP), addPath, imgPath.slice(indexQRP)].join('');
														console.log('imagepath',imgPath);
														
														$(this).prop('src', imgPath);
												
													});
												});
											}).html();

		 */
				
				
				// window.location.href="uploads/"+openup;
						} else {
				
					alert('not a homework problem');
						} 
					} else {
						
					// alert('This problem is temporarily suspended, please check back later on2.');
								return;
						
						
					}

		   



					
		  });
		  
		  
		  
		  
			}
			else{
				
				alert ('invalid user input');
				
				
			}

});

</script>
<script>

	$(document).ready(function(){
		
		
		// comes from https://stackoverflow.com/questions/6985507/one-time-page-refresh-after-first-page-load on reloading the page to get rid of the sometimes error of varaibles not being substituted in
					window.onload = function() {
						if(!window.location.hash) {
							window.location = window.location + '#loaded';
							window.location.reload();
						}
					} 


	 });
</script>

 
</body>
</html>