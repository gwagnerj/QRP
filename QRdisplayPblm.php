<?php
require_once "pdo.php";
require_once "simple_html_dom.php";
session_start();
/* 
function clean($text){
    
   $lines = explode("\n", $text); 
    foreach($lines as $idx => $line) {
        if ( '&nbsp;' === trim($line) ) {
            // If the text in the given line is &nbsp; then replace this line 
            // with and emty character
            $lines[$idx] = str_replace('&nbsp;', '', $lines[$idx]);
            
        }
    } 
    $text = implode("\n",$lines);
    
}
 */

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
			die();
	} 

	if(isset($_GET['dex'])) {
			$dex = htmlentities($_GET['dex']);
		}else if(isset($_SESSION['dex'])) {
			$dex = htmlentities($_SESSION['dex']);
		} else {
			$_SESSION['error'] = 'dex is not being read into the diplay error 31';
			header("Location: QRhomework.php");
			die();
	} 

	if(isset($_GET['pin'])) {
			$pin = htmlentities($_GET['pin']);
		}else if(isset($_SESSION['pin'])) {
			$pin = htmlentities($_SESSION['pin']);
		} else {
			$_SESSION['error'] = 'pin is not being read into the diplay error 32';
			header("Location: QRhomework.php");
			die();
	} 
	/* 
	if(isset($_GET['assign_num'])) {
			$assign_num = htmlentities($_GET['assign_num']);
		}else if(isset($_SESSION['assign_num'])) {
			$assign_num = htmlentities($_SESSION['assign_num']);
		} else {
			$_SESSION['error'] = 'assign_num is not being read into the diplay error 33';
			header("Location: QRhomework.php");
			return;
	} 
	if(isset($_GET['alias_num'])) {
			$alias_num = htmlentities($_GET['alias_num']);
		}else if(isset($_SESSION['alias_num'])) {
			$alias_num = htmlentities($_SESSION['alias_num']);
		} else {
			$_SESSION['error'] = 'alias_num is not being read into the diplay error 34';
			header("Location: QRhomework.php");
			return;
	} 
 */
	if(isset($_GET['iid'])) {
			$iid = htmlentities($_GET['iid']);
		}else if(isset($_SESSION['iid'])) {
			$iid = htmlentities($_SESSION['iid']);
		} else {
			$_SESSION['error'] = 'iid is not being read into the diplay error 35';
			header("Location: QRhomework.php");
			die();
	} 

	if(isset($_GET['stu_name'])) {
			$stu_name = htmlentities($_GET['stu_name']);
		}else if(isset($_SESSION['stu_name'])) {
			$stu_name = htmlentities($_SESSION['stu_name']);
		} else {
			$_SESSION['error'] = 'stu_name is not being read into the diplay error 34';
			header("Location: QRhomework.php");
			die();
	} 

	}
// can do the same as above ot the rest of the varaibles but won't unless I have trouble
if (isset($_GET['reflect_flag'])){$reflect_flag = $_GET['reflect_flag'];} elseif(isset($_SESSION['reflect_flag'])){$reflect_flag = $_SESSION['reflect_flag'];} else {$reflect_flag = '';}
if (isset($_GET['explore_flag'])){$explore_flag = $_GET['explore_flag'];} elseif(isset($_SESSION['explore_flag'])){$explore_flag = $_SESSION['explore_flag'];} else {$explore_flag = '';}
if (isset($_GET['connect_flag'])){$connect_flag = $_GET['connect_flag'];} elseif(isset($_SESSION['connect_flag'])){$connect_flag = $_SESSION['connect_flag'];} else {$connect_flag = '';}
if (isset($_GET['society_flag'])){$society_flag = $_GET['society_flag'];} elseif(isset($_SESSION['society_flag'])){$society_flag = $_SESSION['society_flag'];} else {$society_flag = '';}
if (isset($_GET['choice'])){$choice = $_GET['choice'];} elseif(isset($_SESSION['choice'])){$choice = $_SESSION['choice'];} else {$choice = '';}


 $sql = "SELECT * FROM Problem WHERE problem_id = :problem_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':problem_id' => $problem_id));
	$pblm_data = $stmt -> fetch();
	// need to put some error checking here
	//	$rows=$data;


$htmlfilenm = "uploads/".$pblm_data['htmlfilenm'];

// read in the names of the variables for the problem
    $nv = 0;  // number of non-null variables
   for ($i = 0; $i <= 13; $i++) {
        if($pblm_data['nv_'.($i+1)]!='Null' ){
            $nvar[$i]=$pblm_data['nv_'.($i+1)];
            $nv++;
         }
   }
  /*  
   //read tolerances into array
   $i = 0;
    for ($m = 'a'; $m<='j'; $m++){
        $tol[$i] = $pblm_data['tol_'.($m)];
        $i++;
    }
 */
       
    $stmt = $pdo->prepare("SELECT * FROM Input where problem_id = :problem_id AND dex = :dex");
	$stmt->execute(array(":problem_id" => $problem_id, ":dex" => $dex));
	$row = $stmt->fetch();
    
     $stmt = $pdo->prepare("SELECT * FROM Input where problem_id = :problem_id AND dex = :dex");
	$stmt->execute(array(":problem_id" => $problem_id, ":dex" => 1));
	$BC_row = $stmt->fetch();
/* 
    $stmt = $pdo->prepare("SELECT * FROM Qa where problem_id = :problem_id AND dex = :dex");
	$stmt->execute(array(":problem_id" => $problem_id, ":dex" => $dex));
	$row_ans = $stmt->fetch();

 */
   // Read in the value for the input variables
   
    for ($i = 0; $i <= $nv; $i++) {
        if($row['v_'.($i+1)]!='Null' ){
            $vari[$i] = $row['v_'.($i+1)];
            $BC_vari[$i] = $BC_row['v_'.($i+1)];
            $pattern[$i]= '/##'.$nvar[$i].'.+?##/';
        }
    }
   



// passing my php varables into the js varaibles needed for the script below
/* 
$pass = array(
    
	'reflect_flag' => $reflect_flag,
	'explore_flag' => $explore_flag,  // these are set in 
	'connect_flag' => $connect_flag,
	'society_flag' => $society_flag
);

// echo ($pass['society_flag']);
//die();
echo '<script>';
echo 'var pass = ' . json_encode($pass) . ';';
echo '</script>';
  
   */
 
 // 

?>
<!DOCTYPE html >
<html lang = "en">
<head>
<meta charset="UTF-8">

<link rel="icon" type="image/png" href="McKetta.png" >
<!--
<title>QRHomework</title> 
<meta name="viewport" content="width=device-width, initial-scale=1" /> 

-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<script type="text/javascript" charset="utf-8" src="qrcode.js"></script>
<style>
#base_case{
   background-color: lightblue;  
}


</style>
</head>

<body>

<form id = "" name = "">

</form>
<!-- <div id = substitute_me>  </div> -->


<?php  
 
 	  $html = new simple_html_dom();
      $html->load_file($htmlfilenm);
      $header_stuff = new simple_html_dom();
      $header_stuff -> load_file('problem_header_stuff.html');
      // subbing in the header
       $header_stuff ->find('#stu_name',0)->innertext = $stu_name;
       $header_stuff ->find('#course',0)->innertext = ' Material Balances ';
      
      
      echo ($header_stuff);
       $directions = '';
    // $directions = $html->find('#directions',0);
      $problem = $html->find('#problem',0);
       $base_case = $html->find('#problem',0); 
    
     if($choice >0 ){$reflect_flag = $connect_flag = $explore_flag = $society_flag = 1;}
    
    if ($reflect_flag ==1){$reflect = $html->find('#reflect',0).'<textarea id = "reflect_text" r_class = "text_box" rows = "4" cols = "100"></textarea>';}else {$reflect = '';}
    if ($connect_flag ==1){$connect = $html->find('#connect',0).'<textarea id = "connect_text" r_class = "text_box" rows = "4" cols = "100"></textarea>';}else {$connect = '';}
    if ($explore_flag ==1){$explore = $html->find('#explore',0).'<textarea id = "explore_text" r_class = "text_box" rows = "4" cols = "100"></textarea>';}else {$explore = '';}
    if ($society_flag ==1){$society = $html->find('#society',0).'<textarea id = "society_text" r_class = "text_box" rows = "4" cols = "100"></textarea>';}else {$society = '';}
    
  
     
     
     
    
      for( $i=0;$i<$nv;$i++){
            $problem = preg_replace($pattern[$i],$vari[$i],$problem);
            $base_case = preg_replace($pattern[$i],$BC_vari[$i],$base_case);
        }
        
        
       $base_case = preg_replace('/<div id="problem">/','<div id="BC_problem">',$base_case);
         $base_case = preg_replace('/<div id="questions">/','<div id="BC_questions">',$base_case);
         
         foreach(range('a' , 'j') as $m){
             $let_pattern = 'part'.$m;
              $base_case = preg_replace('/<div id="'.$let_pattern.'">/','<div id="BC_'.$let_pattern.'">',$base_case);
             
         }
        
       $this_html = $directions.$problem.'<hr>'.'<div id = "base_case"><h2>Base_Case:</h2>'.$base_case.'</div>'.'<hr><div id = "reflections">'.$reflect.$explore.$connect.$society.'</div>';

 
   // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
            $this_html = preg_replace($pattern[$i],$vari[$i],$this_html);
        }
       
       
    
   $dom = new DOMDocument();
   libxml_use_internal_errors(true); // this gets rid of the warnig that the p tag isn't closed explicitly
       $dom->loadHTML('<?xml encoding="utf-8" ?>' . $this_html);
       $images = $dom->getElementsByTagName('img');
     
        foreach ($images as $image) {
          
            $src = $image->getAttribute('src');
             $src = 'uploads/'.$src;
     
             $src = urldecode($src);
             $type = pathinfo($src, PATHINFO_EXTENSION);
             $base64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($src));
             $image->setAttribute("src", $base64); 
             $this_html = $dom->saveHTML();
       }
  
   echo $this_html; 
   
   //   echo ($html); 
  
  ?>
  <!--
   <div id = 'examchecker'>
   <iframe src="QRExamCheck.php?exam_num=<?php echo($exam_num);?>&cclass_id=<?php echo($cclass_id);?>&alias_num=<?php echo($alias_num);?>&pin=<?php echo($pin);?>&iid=<?php echo($iid);?>&examactivity_id=<?php echo($examactivity_id);?>&problem_id=<?php echo($problem_id);?>&dex=<?php echo($dex);?>" style = "width:70%; height:50%;"></iframe>

 -->
<script>
 $(document).ready(function(){

  $('#basecasebutton').click(function(){
        $("#base_case").toggle();
     });

     $('#problembutton').click(function(){
        $("#problem").toggle();
     });
       $('#reflectionsbutton').click(function(){
        $("#reflections").toggle();
     });
    // color the back botton a little different
			$("#backbutton").css({"background-color":"lightyellow",
           /*  
              "position": "relative",
              "left": "0px",
              "top": "0px",
            "z-index": "1"
             */
            });
              
             // console.log('buttons');
			// $("#backbut").css('z-index', '1')
			// go back to the input page for a different problem
			
          
            $("#backbutton").click(function(){
		  			
                    // e.preventDefault();
					// console.log("hello1");
				
                     window.location.replace('QRhomework.php'); // would like to put some parameters here instead of relying on session (like below)
                  //  window.location.replace('../QRP/QRExam.php'+'?examactivity_id='+examactivity_id); // axam_num and examactivity
              	
				 });
 
 });


</script>

 
</body>
</html>