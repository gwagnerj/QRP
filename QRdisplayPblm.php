<?php
require_once "pdo.php";
require_once "simple_html_dom.php";
include 'phpqrcode/qrlib.php'; 
session_start();
                        


// this strips out the get parameters so they are not in the url - its is not really secure data but I would rather not having people messing with them
// if they do not what they are doing
/* 
 if (!empty($_GET)) {
        $_SESSION['got'] = $_GET;
        header('Location: QRdisplayPblm.php');
        die;
    } else{
        if (!empty($_SESSION['got'])) {
            $_GET = $_SESSION['got'];
            unset($_SESSION['got']);
        }
    }
     */
        //use the $_GET vars here..
    

//  Set the varaibles to the Get Parameters or if they do not exist try the session variables if those don't exist error back to QRhomework

	if(isset($_GET['activity_id'])) {
			$activity_id = $_GET['activity_id'];
            
		} else {
			$_SESSION['error'] = 'activity_id is not being read into the diplay error 30';
			header("Location: QRhomework.php");
			die();
	} 
 
	//  Get all of the required info from the Activity Table
    
    $sql = 'SELECT * FROM Activity WHERE activity_id = :activity_id';
     $stmt = $pdo->prepare($sql);
     $stmt->execute(array(':activity_id' => $activity_id));
     $activity_data = $stmt -> fetch();
        
     $problem_id = $activity_data['problem_id'];   
     $iid = $activity_data['iid'];   
     $pin = $activity_data['pin'];   
     $stu_name = $activity_data['stu_name'];   
     $currentclass_id = $activity_data['currentclass_id'];   
     $instr_last = $activity_data['instr_last'];   
     $university = $activity_data['university'];   
     $dex = $activity_data['dex'];  
     $alias_num = $activity_data['alias_num'];  
      $assign_id = $activity_data['assign_id'];  
     
     $sql = 'SELECT name FROM Currentclass WHERE currentclass_id = :currentclass_id';
     $stmt = $pdo->prepare($sql);
     $stmt->execute(array(':currentclass_id' => $currentclass_id));
     $class_data = $stmt -> fetch();
     $class_name = $class_data['name'];
     
      $sql = 'SELECT * FROM Assign WHERE assign_id = :assign_id AND prob_num = :problem_id';
     $stmt = $pdo->prepare($sql);
     $stmt->execute(array(':assign_id' => $assign_id,
     ':problem_id' => $problem_id));
     $assign_data = $stmt -> fetch();
     $assignment_num = $assign_data['assign_num'];
      $alias_num = $assign_data['alias_num'];    
         
	
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
    $contrib_id = $pblm_data['users_id'];
    $nm_author = $pblm_data['nm_author'];
    $specif_ref = $pblm_data['specif_ref'];
    $htmlfilenm = $pblm_data['htmlfilenm'];
   // echo('htmlfilenm: '.$htmlfilenm);
    
$sql = "SELECT * FROM Users WHERE users_id = :users_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':users_id' => $contrib_id));
	$contrib_data = $stmt -> fetch();
    $contrib_first = $contrib_data['first'];   
    $contrib_last = $contrib_data['last'];
    $contrib_univ = $contrib_data['university'];
    
	// need to put some error checking here
	//	$rows=$data;

// Make the QRcode
        $qrchecker_text =  'https://www.qrproblems.org/QRP/QRChecker.php?problem_id='.$problem_id.'&pin='.$pin; 
                        //  https://www.qrproblems.org/QRP/QRChecker.php?assign_num=1&cclass_id=16&alias_num=2&pin=34&iid=1        
        $path = 'uploads/'; 
        $file = "temp2 png"; 
          
        // $ecc stores error correction capability('L') 
        $ecc = 'M'; 
        $pixel_size = 2; 
        $frame_size = 1; 
          
        // Generates QR Code and Stores it in directory given 
          QRcode::png($qrchecker_text, 'uploads/'.$file, $ecc, $pixel_size, $frame_size); 
         // QRcode::png($text); 
        // Displaying the stored QR code from directory 
      //  echo ("<right><img src='uploads/".$file."'></right>");
      $qrcode = "<right><img src='".$file."'></right>"; 
     
                        


$htmlfilenm = "uploads/".$htmlfilenm;

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
   
$pass = array(
    'stu_name' => $stu_name,
    'activity_id' => $activity_id
    );
    echo '<script>';
    echo 'var pass = ' . json_encode($pass) . ';';
    echo '</script>';



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




<?php  
 
 	  $html = new simple_html_dom();
      $html->load_file($htmlfilenm);
      $header_stuff = new simple_html_dom();
      $header_stuff -> load_file('problem_header_stuff.html');
      // subbing in the header
       $header_stuff ->find('#stu_name',0)->innertext = $stu_name;
       $header_stuff ->find('#course',0)->innertext = $class_name;
       $header_stuff ->find('#course',0)->innertext = $class_name;
       $header_stuff ->find('#assignment_num',0)->innertext = $assignment_num;
       $header_stuff ->find('#problem_num',0)->innertext = $alias_num;
       $header_stuff ->find('#contributor_name',0)->innertext = $contrib_first.' '.$contrib_last;
       $header_stuff ->find('#university',0)->innertext = $contrib_univ;
      if (strlen($nm_author)>1){$header_stuff ->find('#nm_author',0)->innertext = ' based on a problem by: '.$nm_author;}
      if (strlen($specif_ref)>1){$header_stuff ->find('#specif_ref',0)->innertext = ' reference: '.$specif_ref;}

      echo ($header_stuff);
       $directions = '';
    // $directions = $html->find('#directions',0);
      $problem = $html->find('#problem',0);
       $base_case = $html->find('#problem',0); 
    
     if($choice >0 ){$reflect_flag = $connect_flag = $explore_flag = $society_flag = 1;}
    
    if ($reflect_flag ==1){$reflect = $html->find('#reflect',0).'<textarea id = "reflect_text" r_class = "text_box" rows = "4" cols = "100"></textarea>';}else {$reflect = '';}
    if($connect_flag ==1 && isset($pblm_data['connect'])){$connect = $pblm_data['connect'].'<textarea id = "connect_text" r_class = "text_box" rows = "4" cols = "100"></textarea>';
    } elseif ($connect_flag ==1){$connect = $html->find('#connect',0).'<textarea id = "connect_text" r_class = "text_box" rows = "4" cols = "100"></textarea>';
    }else {$connect = '';}

   // if ($connect_flag ==1){$connect = $html->find('#connect',0).'<textarea id = "connect_text" r_class = "text_box" rows = "4" cols = "100"></textarea>';}else {$connect = '';}
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
       // $checker_text = '<div id = "checker" <iframe src = "QRChecker2.php?activity_id='.$activity_id.'" style ="width:90%; height:50%;"></iframe></div>';
       // echo (' checker_text: '.$checker_text);
       $this_html = $qrcode.$directions.$problem.'<hr>';
       
      
 
   // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
            $this_html = preg_replace($pattern[$i],$vari[$i],$this_html);
        }
    
   $dom = new DOMDocument();
   libxml_use_internal_errors(true); // this gets rid of the warning that the p tag isn't closed explicitly
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
   unlink('uploads/temp2 png');
   //   echo ($html); 
  
  ?>
  <!--   -->
   <div id = 'checker'>
   <iframe src="QRChecker2.php?activity_id=<?php echo($activity_id);?>" style = "width:90%; height:50%;"></iframe>
 <?php
  $this_html = '<div id = "base_case"><h2>Base_Case:</h2>'.$base_case.'</div>'.'<hr><div id = "reflections">'.$reflect.$explore.$connect.$society.'</div>';
  // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
            $this_html = preg_replace($pattern[$i],$vari[$i],$this_html);
        }
    
   $dom = new DOMDocument();
   libxml_use_internal_errors(true); // this gets rid of the warning that the p tag isn't closed explicitly
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
 //  unlink('uploads/temp2 png');
 
 
 ?>
 
<script>
 $(document).ready(function(){
    
    var activity_id = pass['activity_id']; 
     var stu_name = pass['stu_name']; 

  $('#basecasebutton').click(function(){
        $("#base_case").toggle();
     });

     $('#problembutton').click(function(){
        $("#problem").toggle();
     });
       $('#reflectionsbutton').click(function(){
        $("#reflections").toggle();
     });
     
     $('#questions').prepend('<p> Questions for '+stu_name+':</p>')
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
				
                     window.location.replace('QRhomework.php?activity_id='+activity_id); // would like to put some parameters here instead of relying on session (like below)
                  //  window.location.replace('../QRP/QRExam.php'+'?examactivity_id='+examactivity_id); // axam_num and examactivity
              	
				 });
 
 });


</script>

 
</body>
</html>