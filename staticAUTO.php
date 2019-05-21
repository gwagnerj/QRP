<?php

session_start();

//$stu_name = '';
$problem_id= '';
$index='';
$pin='';
/* 
Was setting this up to do more php input validation - but have put it off
$PIN_LLimit = 1;
$PIN_ULimit = 200;
$prob_LLimit = 1;
$prob_ULimit = 100000;
$PIN_Check = array('options'=>array('min_range'=>$PIN_LLimit,'max_range'=>$PIN_ULimit,));
$prob_Check = array('options'=>array('min_range'=>$prob_LLimit,'max_range'=>$prob_ULimit,)); */

/* if(isset($_POST['stu_name'])){
	
	
	$stu_name = htmlentities($_POST['stu_name']);
	$_SESSION['stu_name']=$stu_name;
	
}  */

if(isset($_POST['problem_id'])){
	
	$problem_id = htmlentities($_POST['problem_id']);
	
	$_SESSION['problem_id']=$problem_id;
}

if(isset($_POST['index'])){
	
	$index = htmlentities($_POST['index']);
	$_SESSION['index']=$index;
}
if(isset($_POST['pin'])){
	
	$pin = htmlentities($_POST['pin']);
	
}




?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRHomework</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>

<body>
<header>
<h1>Quick Response Homework </h1>
</header>

<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}

 
?>
<div id = substitute_me> 
<h3>Print the problem statement with "Ctrl P"</h3>
<p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p>
<form method="POST">
	<!-- <p><font color=#003399>Name: </font><input type="text" name="stu_name" id = "stu_name_id" size= 20  value="<?php echo($stu_name);?>" ></p> -->
	<p><font color=#003399>Problem Number: </font><input type="number" name="problem_id" id="prob_id" size=3 value=<?php echo($problem_id);?> ></p>
	<p><font color=#003399>DEX: </font><input type="number" name="index" id="index_id" size=3 value=<?php echo($index);?> ></p>
	<p><font color=#003399>PIN: </font><input type="number" name="pin" id="pin_id" size=3 value=<?php echo($pin);?> ></p>
	
	</form>
</div>
	
<script>
	
	$(document).ready(function(){
	
		var inde = $('input#index_id').val();
		var problem = $('input#prob_id').val();
		var pin = $('input#pin_id').val();
		var s_name = 'John Doe';
		var statusFlag=true;
	
	if($.trim(problem) != '' && problem > 0 && problem < 100000 && inde>=1 && inde<=200){
	// alert(1);
	
		 $.post('fetchpblminput.php', {problem_id : problem, index : inde }, function(data){
			
			try{
				var arr = JSON.parse(data);
			}
			catch(err) {
				alert ('problem data unavailable');
			}
		
		
		
		// Get the html file name from the database
			
			
			
			var static_f = true;
			
			
			// alert(openup);
			
						var openup = arr.htmlfilenm;
						openup = escape(openup);
						 console.log ('openupfilename',openup);
						
						var game = arr.game_prob_flag;
						var status = arr.status;
						var prob_num = arr.problem_id;
						var contrib_first = arr.first;
						var contrib_last = arr.last;
						var contrib_university = arr.university;
					
								$('#substitute_me').load("uploads/"+openup, 'document').html();
			
			
			
			
			
			
			
			var game = arr.game_prob_flag;
			var status = arr.status;
			var prob_num = arr.problem_id;
			var contrib_first = arr.first;
			var contrib_last = arr.last;
			var contrib_university = arr.university;
			sessionStorage.setItem('contrib_first',contrib_first);
			sessionStorage.setItem('contrib_last',contrib_last);
			sessionStorage.setItem('contrib_university',contrib_university);
			sessionStorage.setItem('nm_author',arr.nm_author);
			sessionStorage.setItem('specif_ref',arr.specif_ref);
			
			console.log(contrib_first);
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
					sessionStorage.setItem('stu_name',s_name);
					sessionStorage.setItem('problem_id',problem);
					sessionStorage.setItem('index',inde);
					sessionStorage.setItem('static_flag',static_f);
					sessionStorage.setItem('pin',pin);
			
			
		 } else {
			
				alert('This problem is temporarily suspended, please check back later.');
				//window.location.href="QRhomework.php";
				
				statusFlag=false;
				//return;
			

		 }

			 
			
  });
  
  
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




/* 
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
		
		 */
		
		
		
		
		
		
		
		
		//window.location.href="uploads/"+openup;
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

</body>
</html>



