<?php
require_once "pdo.php";
session_start();


// this strips out the get parameters so they are not in the url - its is not really secure data but I would rather not having people messing with them
// if they do not what they are doing

 if (!empty($_GET)) {
        $_SESSION['got'] = $_GET;
        header('Location: QRdisplayExamPblm.php');
        die;
    } else{
        if (!empty($_SESSION['got'])) {
            $_GET = $_SESSION['got'];
            unset($_SESSION['got']);
        }
    }
// check input and send them back if not proper
    if( isset($_POST['examactivity_id'])){
         $examactivity_id = $_POST['examactivity_id'];
    } elseif (isset($_GET['examactivity_id'])) {
         $examactivity_id = $_GET['examactivity_id']; 
    } else {
        
        header("Location: QRExamRegistration.php");
        die();    
    }
   




   if(isset($_POST['problem_id'])&& isset($_POST['examactivity_id'])){
        $problem_id = $_POST['problem_id'];
        $_SESSION['problem_ios'] = $problem_id;
    
    }elseif(isset($_SESSION['problem_ios'])){
    
        $problem_id = $_SESSION['problem_ios'];
    
    
    } else  {
      $_SESSION['error'] = 'Problem Not Selected';


   header("Location: QRExam.php?examactivity_id=".$examactivity_id
        );
        die();   
         
    }  
          
 // initialize some vars
 
  $complete = '';
    $alias_num = '';
    $iid = '';
    $cclass_id = '';
    $pin = '';
    $exam_code ='';
  
	$stu_name = '';
	$instr_last='';
    $cclass_name='';
    $dex='';
    $globephase = 0;

// get the information needed form the SQL tables
   $sql = "SELECT * FROM `Examactivity` WHERE examactivity_id = :examactivity_id";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
             ':examactivity_id' => $examactivity_id,
          )); 
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
             if($row != false){
                 $examtime_id = $row['examtime_id']; 
                $iid = $row['iid'];
                $dex = $row['dex'];
                $pin = $row['pin'];
                $stu_name = $row['name'];
                $exam_code = $row['exam_code'];
                $cclass_id = $row['currentclass_id'];
                $suspend_flag = $row['suspend_flag'];

             } else {
                 $_SESSION['error'] = 'examactivity table could not be read in QRExam.php';
                header("Location: QRExamRegistration.php");
                die();  
             }

            $sql = " SELECT * FROM `Examtime` WHERE examtime_id = :examtime_id" ;
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                         ':examtime_id' => $examtime_id,
                    ));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($row != false){
                        $globephase = $row['globephase']; 
                        $exam_num = $row['exam_num'];
                    } else {
                       $_SESSION['error'] = 'examtime table could not read - Exam over or not Initiated';
                        header("Location: QRExamRegistration.php");
                        die();     
                    }
                    
                    if ($globephase !=1){
                         $_SESSION['error'] = 'Exam is not in progress';
                        header("Location: QRExam.php?examactivity_id=".$examactivity_id
                        );
                        die();     
                        
                    }
                    
                    
              $sql = " SELECT `name` FROM `CurrentClass` WHERE currentclass_id = :currentclass_id" ;
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                         ':currentclass_id' => $cclass_id,
                    ));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($row != false){
                        $cclass_name = $row['name']; 
                    } else {
                       $_SESSION['error'] = 'Currentclass table could not read - Class Not Valid';
                       
                    }     
             $sql = " SELECT * FROM `Exam` WHERE currentclass_id = :currentclass_id AND problem_id = :problem_id AND exam_num = :exam_num" ;
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                         ':currentclass_id' => $cclass_id,
                           ':problem_id' => $problem_id,
                             ':exam_num' => $exam_num,
                    ));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($row != false){
                        $alias_num = $row['alias_num']; 
                    }                     
                    	

 $sql = "SELECT `htmlfilenm` FROM Problem WHERE problem_id = :problem_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':problem_id' => $problem_id));
	$data = $stmt -> fetch();
	// need to put some error checking here
		$rows=$data;


$htmlfilenm = "uploads/".$rows['htmlfilenm'];



// passing my php varables into the js varaibles needed for the script below

// Sneak the exam_flag in




$pass = array(
    'dex' => $dex,
    'problem_id' => $problem_id,
    'stu_name' => $stu_name,
	'pin' => $pin,
	'iid' => $iid,
    'alias_num' => $alias_num,
	'exam_num' => $exam_num,
    'assign_num' => $exam_num,
    'cclass_id' => $cclass_id,
    'examactivity_id' => $examactivity_id,
    'cclass_name' => $cclass_name,
    'examtime_id' => $examtime_id,
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

<title>QRExam</title> 
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<script type="text/javascript" charset="utf-8" src="qrcode.js"></script>

<style>
#water_mark {
  position: absolute;
  left: 0px;
  top: 0px;
  z-index: 1;
}

#examchecker {
  position: relative;
  left: 0px;
  top: 0px;
  z-index: 1;
}
</style>


</head>

<body>

<form method = "POST" Action = "">
         <input type="hidden" id = "problem_id" name="problem_id" value="<?php echo ($problem_id)?>" >

</form>
  <!-- <div style="background-image: url('Water_Mark_for_exam.png');">  -->
   
<img id = "water_mark" src="Water_Mark_for_exam_trans_bckgrnd.png" >

  



<div id = substitute_me>  </div>
<?php  iconv("Windows-1252", "UTF-8", include($htmlfilenm)); 
			// include($htmlfilenm);

 ?>
 <!--
-->
 <div id = 'examchecker'>
 <iframe src="QRExamCheck.php?exam_num=<?php echo($exam_num);?>&cclass_id=<?php echo($cclass_id);?>&alias_num=<?php echo($alias_num);?>&pin=<?php echo($pin);?>&iid=<?php echo($iid);?>&examactivity_id=<?php echo($examactivity_id);?>&problem_id=<?php echo($problem_id);?>&dex=<?php echo($dex);?>" style = "width:70%; height:50%;"></iframe>

</div>



<script>
$(document).ready(function(){
		
        
        // disable right mouse click copy and copy paste  From https://www.codingbot.net/2017/03/disable-copy-paste-mouse-right-click-using-javascript-jquery-css.html
            //Disable cut copy paste
            $('body').bind('cut copy paste', function (e) {
                e.preventDefault();
            });
            
            //Disable mouse right click
            $("body").on("contextmenu",function(e){
                return false;
            });
        
        
        var dex = pass['dex'];
		var problem = pass['problem_id'];
        var examactivity_id = pass['examactivity_id'];
		var stu_name = pass['stu_name'];
		var pin = pass['pin'];
		var iid = pass['iid'];
		var assign_num = pass['assign_num'];
		var alias_num = pass['alias_num'];
        var exam_num = pass['exam_num'];
        var cclass_id = pass['cclass_id'];
        var cclass_name = pass['cclass_name'];
        var examtime_id = pass['examtime_id'];
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
							
							sessionStorage.setItem('exam_flag',1);
                    		sessionStorage.setItem('examactivity_id',examactivity_id);
                     		sessionStorage.setItem('iid',iid);
                     		sessionStorage.setItem('pin',pin);
                      		sessionStorage.setItem('dex',dex);

                     		sessionStorage.setItem('alias_num',alias_num);
                     		sessionStorage.setItem('cclass_name',cclass_name);
                     		sessionStorage.setItem('exam_num',exam_num);
                     		sessionStorage.setItem('assign_num',assign_num);
                     		sessionStorage.setItem('stu_name',stu_name);
                     		sessionStorage.setItem('cclass_id',cclass_id);
                      		sessionStorage.setItem('alias_num',alias_num);
                            sessionStorage.setItem('problem_id',problem);
							sessionStorage.setItem('title',arr.title);
                            
                        /*      
							sessionStorage.setItem('stu_name',s_name);
							
							sessionStorage.setItem('dex',dex);
							sessionStorage.setItem('pin',pin);
							sessionStorage.setItem('iid',iid); 
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
					  */
					
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

					  script.src = "SubstvarsExam.js";
					  document.getElementsByTagName( "head" )[0].appendChild( script );
					}

					
					
		
					// call the function...
					loadScript("SubstvarsExam.js", function() {
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
				
				alert ('invalid user input dex = '+dex+' problem= '+problem);
				
// this to the end of this script is from QRExamCheck to shut things down when the globephase changes from 1	
			}
    	// get the current phase
				
				console.log ('examtime_id = ',examtime_id);
				
                     var request;
                function fetchPhase() {
                    request = $.ajax({
                        type: "POST",
                        url: "fetchGPhase.php",
                        data: "examtime_id="+examtime_id,
                        success: function(data){
                           try{
                                var arrn = JSON.parse(data);
                            }
                            catch(err) {
                                alert ('game data unavailable Data not found');
                                alert (err);
                                return;
                            }
                            
                             var phase = arrn.phase;
                            var end_of_phase = arrn.end_of_phase;
                            	console.log ('phase = ',phase);
                           if(phase != 1){  // submit away work time has eneded this is going to stop game and not back to the router
                               $("#phase").attr('value', phase);
                               SubmitAway(); 
                            }
                        }
                    });
                }
                setInterval(function() {
                    if (request) request.abort();
                    fetchPhase();
                }, 10000);

                
                     function SubmitAway() { 
                        window.close();
                       // document.getElementById('the_form').submit();
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