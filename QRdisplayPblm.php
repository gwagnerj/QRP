<?php
require_once "pdo.php";
require_once "simple_html_dom.php";
include 'phpqrcode/qrlib.php'; 
require_once '../encryption_base.php';

session_start();

// this strips out the get parameters so they are not in the url - its is not really secure data but I would rather not having people messing with them
// if they do not what they are doing


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
     
    $switch_to_bc = $activity_data['switch_to_bc']; 
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
     
     $sql = 'SELECT name FROM CurrentClass WHERE currentclass_id = :currentclass_id';
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
      $sequential = $assign_data['sequential'];   

      $salt = $problem_id*$problem_id;
      $salt2 = $problem_id*$problem_id+$problem_id;
      $enc_key = $enc_key.$salt;
      $vid_enc_key = $vid_enc_key.$salt2;
      
         
	$reflect_flag = $assign_data['reflect_flag'];    
    $explore_flag = $assign_data['explore_flag'];    
    $connect_flag = $assign_data['connect_flag'];  
    $society_flag = $assign_data['society_flag'];  
    $ref_choice = $assign_data['ref_choice'];  
    //--------------------------------------------------------------------------------------------------------------------- also in QR_BC_Checker2 and QRChecker2
     $sql = 'SELECT * FROM Assigntime WHERE assign_num = :assign_num AND currentclass_id = :currentclass_id'; // may not want everything here
     $stmt = $pdo->prepare($sql);
     $stmt->execute(array(':assign_num' => $assignment_num,
                          ':currentclass_id' => $currentclass_id));
     $assigntime_data = $stmt -> fetch();
     $perc_of_assign = $assigntime_data['perc_'.$alias_num];
     $due_date = new DateTime($assigntime_data['due_date']);
     //$due_date = $assigntime_data['due_date'];
     $due_date = $due_date->format(' D, M d,  g:i A');
     $due_date_int = strtotime($due_date);
     $window_closes = new DateTime($assigntime_data['window_closes']);
     $window_closes = $window_closes->format(' D, M d,  g:i A');
     $window_closes_int = strtotime($window_closes);
     $late_points = $assigntime_data['late_points'];
     $credit = $assigntime_data['credit'];
     $fixed_percent_decline = $assigntime_data['fixed_percent_decline'];
      $now = new DateTime($activity_data['last_updated_at']);
      $now = $now->format(' D, M d,  g:i A');

    // $now = date(strtotime($activity_data['time_created']),' D, M d,  g:i A');
    // $now = date(' D, M d,  g:i A');
    $now_int = strtotime($now);
    $perc_late_p_prob = $perc_late_p_part = $perc_late_p_assign = 0; 
    $late_penalty = 0;
    $ec_daysb4due_elgible = $assigntime_data['ec_daysb4due_elgible'];
    $due_date_ec_int = $due_date_int - $ec_daysb4due_elgible*60*60*24;
    $due_date_ec = date(' D, M d,  g:i A', $due_date_ec_int);
     
    if ($now_int > $due_date_int ) {  // figure out the late penalty
         if($late_points == 'linear'){
             $late_penalty = round(100*($now_int - $due_date_int)/($window_closes_int - $due_date_int));
          //   $late_penalty = 100;

         }
          if($late_points == 'fixedpercent'){
             // $late_penalty = 30;
             $days_past_due = ceil(($now_int - $due_date_int)/(60*60*24)); // ceil is php roundup
             $late_penalty = $days_past_due*$fixed_percent_decline;  
         }
         if ($credit =='latetoparts'){
             $perc_late_p_part = $late_penalty;
         } elseif ($credit =='latetoproblems'){
             $perc_late_p_prob = $late_penalty;
         }else {  // late penalty is latetoall and applies to the entire assignment
            $perc_late_p_assign = $late_penalty;
         }
    }
//end section-------------------------------------------------------------------------------------------------------------------------------------------    
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
        $qrchecker_text =  'https://www.qrproblems.org/QRP/QRChecker2.php?activity_id='.$activity_id; 
        $file = 'uploads/temp2 png'; 
        // $ecc stores error correction capability('L') 
        $ecc = 'M'; 
        $pixel_size = 2; 
        $frame_size = 1; 
          
        // Generates QR Code and Stores it in directory given 
          QRcode::png($qrchecker_text, $file, $ecc, $pixel_size, $frame_size); 
         
    
      $qrcode = "<span id = 'qrcode_id'><img src='".$file."'><p> Problem Checker </p></span>"; 
      
      // Make the QRcode for Base-case
        $qrchecker_text_bc =  'https://www.qrproblems.org/QRP/QR_BC_Checker2.php?activity_id='.$activity_id; 
                        
        $file_bc = 'uploads/temp_bc png'; 
          
        // $ecc stores error correction capability('L') 
        $ecc = 'M'; 
        $pixel_size = 2; 
        $frame_size = 1; 
          
        // Generates QR Code and Stores it in directory given 
          QRcode::png($qrchecker_text_bc, $file_bc, $ecc, $pixel_size, $frame_size); 
         // QRcode::png($text); 
        // Displaying the stored QR code from directory 
    
      $qrcode_bc = "<span id = 'qrcode_id_bc'><img src='".$file_bc."'><p> Base_Case Checker </p></span>"; 
      
      

$htmlfilenm = "uploads/".$htmlfilenm;

// read in the names of the variables for the problem
    $nv = 0;  // number of non-null variables
   for ($i = 0; $i <= 13; $i++) {
        if($pblm_data['nv_'.($i+1)]!='Null' ){
            $nvar[$i]=$pblm_data['nv_'.($i+1)];
            $nv++;
         }
   }
       
    $stmt = $pdo->prepare("SELECT * FROM Input where problem_id = :problem_id AND dex = :dex");
	$stmt->execute(array(":problem_id" => $problem_id, ":dex" => $dex));
	$row = $stmt->fetch();
    
     $stmt = $pdo->prepare("SELECT * FROM Input where problem_id = :problem_id AND dex = :dex");
	$stmt->execute(array(":problem_id" => $problem_id, ":dex" => 1));
	$BC_row = $stmt->fetch();

   // Read in the value for the input variables
   
    for ($i = 0; $i <= $nv; $i++) {
        if($row['v_'.($i+1)]!='Null' ){
            $vari[$i] = $row['v_'.($i+1)];
            $BC_vari[$i] = $BC_row['v_'.($i+1)];
            $pattern[$i]= '/##'.$nvar[$i].',.+?##/';
        }
    }
   // see if we need a reflections button
   if ($ref_choice!=0 || $reflect_flag !=0 || $explore_flag !=0 || $connect_flag !=0 || $society_flag !=0){
     $reflection_button_flag = 1;  
   } else {
     $reflection_button_flag = 0; 
   }
   
   
   
$pass = array(
    
    'stu_name' => $stu_name,
    'activity_id' => $activity_id,
    'reflection_button_flag' => $reflection_button_flag,
      'reflect_flag' => $reflect_flag,
      'explore_flag' => $explore_flag,
      'connect_flag' => $connect_flag,
      'society_flag' => $society_flag,
      'ref_choice' => $ref_choice,
      'perc_ref' => $assigntime_data['perc_ref_'.$alias_num], // to put the points by the reflections in JS
      'perc_exp' => $assigntime_data['perc_exp_'.$alias_num],
      'perc_con' => $assigntime_data['perc_con_'.$alias_num],
      'perc_soc' => $assigntime_data['perc_soc_'.$alias_num],
       'switch_to_bc' => $switch_to_bc,
       'sequential' => $sequential

      
    );
    echo '<script>';
    echo 'var pass = ' . json_encode($pass) . ';';
    echo '</script>';

 // echo "<script>document.write(localStorage.setItem('enc_key', '".$enc_key."'))</script>";
  echo "<script>window.localStorage.setItem('enc_key', '" . $enc_key . "');</script>";
  echo "<script>window.localStorage.setItem('vid_enc_key', '" . $vid_enc_key . "');</script>";
  echo "<script>window.localStorage.setItem('problem_id', '" . $problem_id . "');</script>";
//   echo "<script>document.write(localStorage.setItem('vid_enc_key', '".$vid_enc_key."'))</script>";
// echo "<script>document.write(localStorage.setItem('problem_id', '".$problem_id."'))</script>";



?>
<!DOCTYPE html >
<html lang = "en">
<head>
<meta charset="UTF-8">


<link rel="icon" type="image/png" href="McKetta.png" >

<link href="https://vjs.zencdn.net/7.10.2/video-js.css" rel="stylesheet" />

<!--
<title>QRHomework</title> 
<meta name="viewport" content="width=device-width, initial-scale=1" /> 

-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<script src="./crypto-js-4.0.0/crypto-js.js"></script><!-- https://github.com/brix/crypto-js/releases crypto-js.js can be download from here -->
<script src="Encryption.js"></script>

<script src="drawingtool/painterro-1.2.57.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">  
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
 
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <link rel="stylesheet" href="displayProblem.css"> 



<script type="text/javascript" charset="utf-8" src="qrcode.js"></script>
<style>

#base_case{
   background-color: #e6f7ff;  
}
#BC_checker2{
   background-color: #e6f7ff;  
}
#qrcode_id_bc{
   /* background-color: #e6f7ff;   */
}
/* 
.hidden {
    visibility: hidden;
}
.display_none { display: none;
}
.gray-out { background-color:black;
            padding: 0 3px 0 3px;
           
}
.gray-out:after{
   color:white;
    content:'watch video';
}
 */

</style>


</head>

<body class = "ms-4">


<?php  
 //  I'm using reading from the $html and buiding the file $this_html.  I had to build it in two parts because of putting the 
 //i-frame for the checker in the middle of the document
 
 
 	  $html = new simple_html_dom();
      $html->load_file($htmlfilenm);
      $header_stuff = new simple_html_dom();
      $header_stuff -> load_file('problem_header_stuff.html');
      // subbing in the header
       $header_stuff ->find('#stu_name',0)->innertext = $stu_name;
       $header_stuff ->find('#course',0)->innertext = $class_name;
       $header_stuff ->find('#assignment_num',0)->innertext = $assignment_num;
       $header_stuff ->find('#problem_num',0)->innertext = $alias_num;
       $header_stuff ->find('#perc_of_assign',0)->innertext = $perc_of_assign.'%';
       $header_stuff ->find('#due_date',0)->innertext = $due_date;
       $header_stuff ->find('#due_date_ec',0)->innertext = $due_date_ec;
      // $header_stuff ->find('#now',0)->innertext = $now;
      //        $header_stuff ->find('#now',0)->innertext = $now;
      // $header_stuff ->find('#window_closes',0)->innertext = $window_closes;


      $drawing_tool_background = $html->find('.drawing-tool-background');
      if ($drawing_tool_background){
        $drawing_tool_background_arr = $drawing_tool_background[0];
        $drawing_tool_background_src = $drawing_tool_background_arr->children(0)->getAttribute("src");
      // echo "<script>document.write(localStorage.setItem('drawing_tool_background_src', '". $drawing_tool_background_src."'))</script>";
      echo "<script>window.localStorage.setItem('drawing_tool_background_src', '" . $drawing_tool_background_src . "');</script>";

      }
      
      if ($perc_late_p_prob !=0){
         $header_stuff ->find('#late_penalty',0)->innertext = 'Late Penalty on Problem: '.$perc_late_p_prob.'%';
       }
       
     

       $header_stuff ->find('#contributor_name',0)->innertext = $contrib_first.' '.$contrib_last;
       $header_stuff ->find('#university',0)->innertext = $contrib_univ;
      if (strlen($nm_author)>1){$header_stuff ->find('#nm_author',0)->innertext = ' based on a problem by: '.$nm_author;}
      if (strlen($specif_ref)>1){$header_stuff ->find('#specif_ref',0)->innertext = ' reference: '.$specif_ref;}
// could also store custom directions look fof them in the assignment table and substitute them in - maybe later
      echo ($header_stuff);
      
      $problem = $html->find('#problem',0);
       $base_case = $html->find('#problem',0); 
    
     if($ref_choice >0 ){$reflect_flag = $connect_flag = $explore_flag = $society_flag = 1;}
   /*  
    if ($reflect_flag ==1){$reflect = $html->find('#reflect',0).'<textarea id = "reflect_text" r_class = "text_box" rows = "4" cols = "150"></textarea>';}else {$reflect = '';}
    if($connect_flag ==1 && isset($pblm_data['connect'])){$connect = $pblm_data['connect'].'<textarea id = "connect_text" r_class = "text_box" rows = "4" cols = "150"></textarea>';
    } elseif ($connect_flag ==1){$connect = $html->find('#connect',0).'<textarea id = "connect_text" r_class = "text_box" rows = "4" cols = "200"></textarea>';
    }else {$connect = '';}
     */
    
    if ($reflect_flag ==1){$reflect = $html->find('#reflect',0).'<div id = "reflect_confirm"> </div><form id = "reflect_text_form"><textarea id = "reflect_text"  r_class = "text_box" rows = "4" cols = "200" spellcheck = "true" maxlength = "3000" >'.htmlentities($activity_data["reflect_text"]).'</textarea><button type = "submit" id = "submit_reflect" class = "btn btn-secondary">Save <i class="bi bi-save"></i></button></form>';}else {$reflect = '';}
    if ($connect_flag ==1){$connect = $html->find('#connect',0).'<div id = "connect_confirm"> </div><form id = "connect_text_form"><textarea id = "connect_text"  r_class = "text_box" rows = "4" cols = "200" spellcheck = "true" maxlength = "3000" >'.htmlentities($activity_data["connect_text"]).'</textarea><button type = "submit" id = "submit_connect" class = "btn btn-secondary">Save <i class="bi bi-save"></i></button></form>';}else {$connect = '';}
    if ($explore_flag ==1){$explore = $html->find('#explore',0).'<div id = "explore_confirm"> </div><form id = "explore_text_form"><textarea id = "explore_text"  r_class = "text_box" rows = "4" cols = "200" spellcheck = "true" maxlength = "3000" >'.htmlentities($activity_data["explore_text"]).'</textarea><button type = "submit" id = "submit_explore" class = "btn btn-secondary">Save <i class="bi bi-save"></i></button></form>';}else {$explore = '';}
    if ($society_flag ==1){$society = $html->find('#society',0).'<div id = "society_confirm"> </div><form id = "society_text_form"><textarea id = "society_text"  r_class = "text_box" rows = "4" cols = "200" spellcheck = "true" maxlength = "3000" >'.htmlentities($activity_data["society_text"]).'</textarea><button type = "submit" id = "submit_society" class = "btn btn-secondary">Save <i class="bi bi-save"></i></button></form>';}else {$society = '';}
     
             // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced

      for( $i=0;$i<$nv;$i++){
           if($row['v_'.($i+1)]!='Null' ){
            $problem = preg_replace($pattern[$i],$vari[$i],$problem);
            $base_case = preg_replace($pattern[$i],$BC_vari[$i],$base_case);
           }
        }
         // put in the special personalized variables into the problem statement
         $stu_city = 'Angola';   // will read these from the table once we have a student login ---------------------
         $stu_state = 'Indiana';
         $stu_explode = explode(' ',$stu_name);
         $stu_first = $stu_explode[0];
         
         $problem = preg_replace('/!!stu_name!!/',$stu_name,$problem);
         $problem = preg_replace('/!!stu_university!!/',$university,$problem);
         $problem = preg_replace('/!!stu_first!!/',$stu_first,$problem);
         $problem = preg_replace('/!!stu_city!!/',$stu_city,$problem);
         $problem = preg_replace('/!!stu_state!!/',$stu_state,$problem);
         
         $base_case = preg_replace('/!!stu_name!!/',$stu_name,$base_case);
         $base_case = preg_replace('/!!stu_university!!/',$university,$base_case);
         $base_case = preg_replace('/!!stu_first!!/',$stu_first,$base_case);
         $base_case = preg_replace('/!!stu_city!!/',$stu_city,$base_case);
         $base_case = preg_replace('/!!stu_state!!/',$stu_state,$base_case);
         
        
     // add some markup to specific to the basecase since I just created it from the problem   
       $base_case = preg_replace('/<div id="problem">/','<div id="BC_problem">',$base_case);
         $base_case = preg_replace('/<div id="questions">/','<div id="BC_questions">',$base_case);
         
         foreach(range('a' , 'j') as $m){
             $let_pattern = 'part'.$m;
              $base_case = preg_replace('/<div id="'.$let_pattern.'">/','<div id="BC_'.$let_pattern.'">',$base_case);
             
         }
/*          
       for( $i=0;$i<$nv;$i++){
            $problem = preg_replace($pattern[$i],$vari[$i],$problem); // I think I just did this on 10 lines above
        }
      */   
    
        

     
        
    // put the images into the problem statement part of the document     
    $dom = new DOMDocument();
   libxml_use_internal_errors(true); // this gets rid of the warning that the p tag isn't closed explicitly
       $dom->loadHTML('<?xml encoding="utf-8" ?>' . $problem);
       $images = $dom->getElementsByTagName('img');
        foreach ($images as $image) {
            $src = $image->getAttribute('src');
             $src = 'uploads/'.$src;
             $src = urldecode($src);
             $type = pathinfo($src, PATHINFO_EXTENSION);
             $base64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($src));
             $image->setAttribute("src", $base64); 
             $problem = $dom->saveHTML();
       }
       
       // turn problem back into and simple_html_dom object that I can replace the varaible images on 
       
    /*      
       if(str_get_html($base_case) != false){
            $base_case =str_get_html($base_case); 
           
           $keep = 0;
           $varImages = $base_case -> find('.var_image');
        */
       
        if(str_get_html($problem) != false){
           
               $problem =str_get_html($problem); 
               $keep = 0;
               $varImages = $problem -> find('.var_image');
               foreach($varImages as $varImage) {
                  $var_image_id = $varImage -> id;  
                  
                   for( $i=0;$i<$nv;$i++){
                      if(trim($var_image_id) == trim($vari[$i])){$keep = 1;} 
                    } 
                    
                    If ($keep==0){
                        //  get rid of the caption and the image
                           $varImage->find('.MsoNormal',0)->outertext = '';
                           $varImage->find('.MsoCaption',0)->outertext = '';
                    } else {
                         //  get rid of the caption 
                        $varImage->find('.MsoCaption',0)->outertext = '';
                    }
                     $keep = 0;
                }
        }    
                
  // repeat with the base_case ______________________________________________________________________
     
         // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
             if($row['v_'.($i+1)]!='Null' ){
                $base_case = preg_replace($pattern[$i],$vari[$i],$base_case);
             }
        }
         
          $dom = new DOMDocument();
   libxml_use_internal_errors(true); // this gets rid of the warning that the p tag isn't closed explicitly
       $dom->loadHTML('<?xml encoding="utf-8" ?>' . $base_case);
       $images = $dom->getElementsByTagName('img');
        foreach ($images as $image) {
            $src = $image->getAttribute('src');
             $src = 'uploads/'.$src;
             $src = urldecode($src);
             $type = pathinfo($src, PATHINFO_EXTENSION);
             $base64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($src));
             $image->setAttribute("src", $base64); 
             $base_case = $dom->saveHTML();
       }
       
       // turn base-case back into and simple_html_dom object that I can replace the varaible images on 
       
        if(str_get_html($base_case) != false){
           $base_case =str_get_html($base_case); 
           $keep = 0;
           $varImages = $base_case -> find('.var_image');
           foreach($varImages as $varImage) {
              $var_image_id = $varImage -> id;  
              
               for( $i=0;$i<$nv;$i++){
                  if(trim($var_image_id) == trim($BC_vari[$i])){$keep = 1;} 
                } 
                If ($keep==0){
                    //  get rid of the caption and the image
                       $varImage->find('.MsoNormal',0)->outertext = '';
                       $varImage->find('.MsoCaption',0)->outertext = '';
                } else {
                     //  get rid of the caption 
                    $varImage->find('.MsoCaption',0)->outertext = '';
                }
                 $keep = 0;
            } 
        }
         
    // only include the document above the checker
       $this_html ='<hr>'.$qrcode.$qrcode_bc.$problem.'<hr> <div id = "base_case" class = "ms-5"><h2>Base_Case:</h2>'.$base_case.'</div>';
 
   // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
             if($row['v_'.($i+1)]!='Null' ){
                $this_html = preg_replace($pattern[$i],$vari[$i],$this_html);
             }
        }
 
  echo $this_html; //-------------------------------------------------------display first part ------------------------------------------------------------------
  
 //  unlink('temp2 png');
  
  ?>
  <!--   -->
   <div id = 'checker'>
   <iframe name = "checker2" class = "border border-primary ms-5 px-2 py-2" id = "checker2" src="QRChecker2.php?activity_id=<?php echo($activity_id);?>" style = "width:90%; height:50%;"></iframe></div>
   <!-- <iframe name = "checker2" id = "checker2" src="QRChecker2.php?activity_id=<?php echo($activity_id);?>" ></iframe></div> -->
      <div id = 'BC_checker'>
   <iframe  name ="BC_checker2" id = "BC_checker2" class = "border border-primary ms-5 px-4 py-2"  src="QR_BC_Checker2.php?activity_id=<?php echo($activity_id);?>" style = "width:90%; height:50%; "></iframe></div>
 <?php
 
 // the document below the checker
  $this_html = '<hr><div id = "reflections" class = "ms-5">'.$reflect.$explore.$connect.$society.'</div>';
  // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
             if($row['v_'.($i+1)]!='Null' ){
                $this_html = preg_replace($pattern[$i],$vari[$i],$this_html);
             }
        }
   // substitute for all of the varables, images and varaible images that might be in the reflections portion
   
   
          // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
             if($row['v_'.($i+1)]!='Null' ){
                $this_html = preg_replace($pattern[$i],$vari[$i],$this_html);
             }
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
       
       // turn base-case back into and simple_html_dom object that I can replace the varaible images on 
       
       
       
       
       
        if(str_get_html($problem) != false && str_get_html($problem) != true){
           $this_html =str_get_html($this_html); 
           $keep = 0;
           $varImages = $this_html -> find('.var_image');
           foreach($varImages as $varImage) {
              $var_image_id = $varImage -> id;  
              
               for( $i=0;$i<$nv;$i++){
                  if(trim($var_image_id) == trim($BC_vari[$i])){$keep = 1;} 
                } 
                If ($keep==0){
                    //  get rid of the caption and the image
                       $varImage->find('.MsoNormal',0)->outertext = '';
                       $varImage->find('.MsoCaption',0)->outertext = '';
                } else {
                     //  get rid of the caption 
                    $varImage->find('.MsoCaption',0)->outertext = '';
                }
                 $keep = 0;
            } 
        }
   
   
   
  
   echo $this_html; 
  // unlink('temp2 png');
 


 ?>
 <!-- <script src="https://vjs.zencdn.net/7.10.2/video.min.js"></script> -->
 
<style>
  img {
  position: relative;
  left: 0px;
  top: 0px;
  z-index: -1;
}
</style>

 
<script>

function resizeIFrameToFitContent( frame ) {
    console.log("frame",frame);
    let iframe_height = parseInt(frame.contentWindow.document.body.scrollHeight);
   
    iframe_height = Math.round(iframe_height **1.08) +10;
    iframe_height = iframe_height.toString() + "px" ;
            frame.style.height =  iframe_height
            console.log ("frame.style.height",frame.style.height);
           
  
         // set the width of the iframe as the 
         // width of the iframe content
        //  frame.style.width  = 
     //     frame.contentWindow.document.body.scrollWidth+'px';
        // iFrame.width  = iFrame.contentWindow.document.body.scrollWidth;
        // iFrame.height = iFrame.contentWindow.document.body.scrollHeight;
        }
    //     function resizeIFrameToFitContent_bc( frame ) {
    // console.log("frame",frame);
    // let iframe_height = parseInt(frame.contentWindow.document.body.scrollHeight);
    // iframe_height = Math.round(iframe_height **1.08) +1000;
    // iframe_height = iframe_height.toString() + "px" ;
    //         frame.style.height =  iframe_height
    //         console.log ("frame.style.height",frame.style.height);

    //     }

 $(document).ready(function(){
   



  window.addEventListener('error', function(event) { window.location.reload(true); })




//   if (typeof window.interactiveVideoJSloaded == 'undefined') {
//     console.log ("window.interactiveVideoJSloaded","undefined");
//     console.log ("window.vid_ar",window.vid_ar);
//     $.getScript('interactiveVideos.js');
// } else {
// console.log ("window.interactiveVideoJSloaded",window.interactiveVideoJSloaded);
// console.log ("window.vid_ar",window.vid_ar);

// }



  console.log ("here we go");
    
    var activity_id = pass['activity_id']; 
     var stu_name = pass['stu_name']; 
     var reflection_button_flag = pass['reflection_button_flag'];

      var reflect_flag = pass['reflect_flag']; 
      var explore_flag = pass['explore_flag']; 
      var connect_flag = pass['connect_flag']; 
      var society_flag = pass['society_flag']; 
      var ref_choice = pass['ref_choice']; 
      var perc_ref = pass['perc_ref'];
      var perc_exp = pass['perc_exp'];
      var perc_con = pass['perc_con'];
      var perc_soc = pass['perc_soc'];
      var switch_to_bc = pass['switch_to_bc'];
      var sequential = pass['sequential'];

      var parts = new Array(10);
       parts[0] = document.getElementById("parta");
       parts[1] = document.getElementById("partb");
       parts[2] = document.getElementById("partc");
      parts[3] = document.getElementById("partd");
      parts[4] = document.getElementById("parte");
      parts[5] = document.getElementById("partf");
      parts[6] = document.getElementById("partg");
      parts[7] = document.getElementById("parth");
      parts[8] = document.getElementById("parti");
      parts[9] = document.getElementById("partj");

      var BC_parts = new Array(10);
       BC_parts[0] = document.getElementById("BC_parta");
       BC_parts[1] = document.getElementById("BC_partb");
       BC_parts[2] = document.getElementById("BC_partc");
      BC_parts[3] = document.getElementById("BC_partd");
      BC_parts[4] = document.getElementById("BC_parte");
      BC_parts[5] = document.getElementById("BC_partf");
      BC_parts[6] = document.getElementById("BC_partg");
      BC_parts[7] = document.getElementById("BC_parth");
      BC_parts[8] = document.getElementById("BC_parti");
      BC_parts[9] = document.getElementById("BC_partj");

      console.log("parts",parts);



      if (sequential ==1){ 
    //    let checker_part_b = document.getElementById('checker2').contentWindow.document.getElementById('part-b-display').innerText;
    //    let checker_part_c = document.getElementById('checker2').contentWindow.document.getElementById('part-c-display').innerText;
    //    // let checker_part_d = document.getElementById('checker2').contentWindow.document.getElementById('part-d-display').innerText;
    //    console.log ("checker_part_b",checker_part_b);
    //    if (checker_part_b=="display_none"){ part_b.classList.add('display_none'); }
    //    if (checker_part_c=="display_none"){ part_c.classList.add('display_none'); }
    //  //  if (checker_part_d=="display_none"){ part_d.classList.add('display_none'); }

      }
       if (switch_to_bc == 1){
         $('#base_case').show();
         $('#BC_checker').show();
          $("#problem").hide(); 
         $('#reflections').hide();
          $("#checker").hide();
           $('#basecasebutton').hide();
          $('#qrcode_id_bc').hide();
          $('#reflectionsbutton').hide();
          $('#qrcode_id').hide(); 
           $('#directions').hide();
             $('#checkerbutton').html('to QR code <i class="bi bi-upc-scan"></i>'); 
          var bc_display = true;
        } else {
            var bc_display = false;
                 $('#qrcode_id_bc').hide();
                 $('#qrcode_id').hide();
                $('#base_case').hide();
                $('#directions').hide();
                $('#BC_checker').hide();
       //        $('#basecasebutton').text('to Base-case');
                $('#checkerbutton').html('to QR Code <i class="bi bi-upc-scan"></i>'); 
        }
         //Turn this off for now - will release this feature later   !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  
         $.getScript('interactiveVideos.js'); 


   
    
    
     var qr_code = false;
     var myFrame = document.getElementById('checker2').contentWindow


    
    $("#checker2").on("load", function () {
               //  var total_count = $("#checker2").contents().find("#total_count").html();
               let iframe = document.getElementById( "checker2");
               console.log("iframe",iframe);
               resizeIFrameToFitContent( iframe );

                var total_count = $("#checker2").contents().find("#total_count").text();
                 var PScore = $("#checker2").contents().find("#PScore").val();
                 let checker_display_parts = new Array(10);
                 checker_display_parts[0] =document.getElementById('checker2').contentWindow.document.getElementById('part-a-display');
                 checker_display_parts[1] =document.getElementById('checker2').contentWindow.document.getElementById('part-b-display');
                 checker_display_parts[2] =document.getElementById('checker2').contentWindow.document.getElementById('part-c-display');
                 checker_display_parts[3] =document.getElementById('checker2').contentWindow.document.getElementById('part-d-display');
                 checker_display_parts[4] =document.getElementById('checker2').contentWindow.document.getElementById('part-e-display');
                 checker_display_parts[5] =document.getElementById('checker2').contentWindow.document.getElementById('part-f-display');
                 checker_display_parts[6] =document.getElementById('checker2').contentWindow.document.getElementById('part-g-display');
                 checker_display_parts[7] =document.getElementById('checker2').contentWindow.document.getElementById('part-h-display');
                 checker_display_parts[8] =document.getElementById('checker2').contentWindow.document.getElementById('part-i-display');
                 checker_display_parts[9] =document.getElementById('checker2').contentWindow.document.getElementById('part-j-display');
              //   console.log("checker_display_parts",checker_display_parts);
                //  let display_b = $("#checker2").contents().find("#part-b-display").text();
                //  let display_j = $("#checker2").contents().find("#part-j-display").text();
           //      let display_c = $("#checker2").contents().find("#part-c-display").text();
                  switch_to_bc = $("#checker2").contents().find("#switch_to_bc").val();
            //    console.log('display_b',display_b);  
            for ( let k=0;k<9;k++){
              if (checker_display_parts[k]){if(checker_display_parts[k].innerText =="display_none"){parts[k].classList.add("display_none");} else {parts[k].classList.remove("display_none");}}
              if (checker_display_parts[k]){if(checker_display_parts[k].innerText =="display_blur"){parts[k].classList.add("display_blur");} else {parts[k].classList.remove("display_blur");}}
            }

              //   if (display_b =="display_none"){parts[1].classList.add("display_none");} else {parts[1].classList.remove("display_none");}
              // if (display_j){if( display_j =="display_none"){parts[9].classList.add("display_none");} else {parts[9].classList.remove("display_none");}}
           
           
              let parts_in_checker = true;
            if (parts_in_checker){
            var part_a_this = document.getElementById("parta"); // the section in the QRdisplayPblm document
            if (part_a_this) {
              let part_a_parent = part_a_this.cloneNode(true);
  //            console.log("part_a_parent", part_a_parent);
              var part_a = document.getElementById('checker2').contentWindow.document.getElementById("part-a-question"); // the section in this checker file
    //          console.log("part_a", part_a);
              if (part_a && part_a_parent) {
                part_a.insertBefore(part_a_parent, part_a.childNodes[0]);
              }
            }


            var part_b_this = document.getElementById("partb"); // the section in the QRdisplayPblm document
            if (part_b_this) {
              let part_b_parent = part_b_this.cloneNode(true);
              var part_b = document.getElementById('checker2').contentWindow.document.getElementById("part-b-question"); // the section in this checker file
              if (part_b && part_b_parent) {
                part_b.insertBefore(part_b_parent, part_b.childNodes[0]);
              }
            }

            let part_c_this = document.getElementById("partc");
            if (part_c_this) {
              let part_c_parent = part_c_this.cloneNode(true);
              var part_c = document.getElementById('checker2').contentWindow.document.getElementById("part-c-question");
              if (part_c && part_c_parent) {
                part_c.insertBefore(part_c_parent, part_c.childNodes[0]);
              }
            }
           
           let part_d_this = document.getElementById("partd");
           if (part_d_this) {
               let part_d_parent = part_d_this.cloneNode(true);
               let part_d = document.getElementById('checker2').contentWindow.document.getElementById("part-d-question");
               if (part_d && part_d_parent) {
                 part_d.insertBefore(part_d_parent, part_d.childNodes[0]);
               }
             }


           var part_e_this = document.getElementById("parte");
           if (part_e_this) {
             let part_e_parent = part_e_this.cloneNode(true);
             var part_e = document.getElementById('checker2').contentWindow.document.getElementById("part-e-question");
             if (part_e && part_e_parent) {
               part_e.insertBefore(part_e_parent, part_e.childNodes[0]);
             }
           }


           var part_f_this = document.getElementById("partf");
           if (part_f_this) {
             let part_f_parent = part_f_this.cloneNode(true);
             var part_f = document.getElementById('checker2').contentWindow.document.getElementById("part-f-question");
             if (part_f && part_f_parent) {
               part_f.insertBefore(part_f_parent, part_f.childNodes[0]);
             }
            }

             var part_g_this = document.getElementById("partg");
             if (part_g_this) {
               let part_g_parent = part_g_this.cloneNode(true);
               var part_g = document.getElementById('checker2').contentWindow.document.getElementById("part-g-question");
               if (part_g && part_g_parent) {
                 part_g.insertBefore(part_g_parent, part_g.childNodes[0]);
               }
             }


             var part_h_this = document.getElementById("parth");
             if (part_h_this) {
               let part_h_parent = part_h_this.cloneNode(true);
               var part_h = document.getElementById('checker2').contentWindow.document.getElementById("part-h-question");
               if (part_h && part_h_parent) {
                 part_h.insertBefore(part_h_parent, part_h.childNodes[0]);
               }
             }


           var part_i_this = document.getElementById("parti");
           if (part_i_this) {

             let part_i_parent = part_i_this.cloneNode(true);
             var part_i = document.getElementById('checker2').contentWindow.document.getElementById("part-i-question");
             if (part_i && part_i_parent) {
               part_i.insertBefore(part_i_parent, part_i.childNodes[0]);
             }
           }



           var part_j_this = document.getElementById("partj");
           if (part_j_this) {

             let part_j_parent = part_j_this.cloneNode(true);
             var part_j = document.getElementById('checker2').contentWindow.document.getElementById("part-j-question");
             if (part_j && part_j_parent) {
               part_j.insertBefore(part_j_parent, part_j.childNodes[0]);
             }
           }
           }   



             
                   console.log('blah: '+total_count);
                  console.log('PScore: '+PScore);
                    console.log('switch_to_bc: '+switch_to_bc);
                

                if (switch_to_bc == 1){
                     $('#base_case').show();
                     $('#BC_checker').show();
                      $("#problem").hide(); 
                     $('#reflections').hide();
                      $("#checker").hide();
                      $('#basecasebutton').hide();
                      $('#qrcode_id_bc').hide();
                      $('#reflectionsbutton').hide();
                      $('#qrcode_id').hide();
                      $('#checkerbutton').html('to QR code <i class="bi bi-upc-scan"></i>'); 
                      bc_display = true;
                }
        });
   
        $("#BC_checker2").on("load", function () {
              let bc_iframe = document.getElementById( "BC_checker2");
               console.log("bc_iframe",bc_iframe);
         //!     resizeIFrameToFitContent_bc( bc_iframe );


          let BC_checker_display_parts = new Array(10);
                 BC_checker_display_parts[0] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-a-BC-display');
                 BC_checker_display_parts[1] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-b-BC-display');
                 BC_checker_display_parts[2] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-c-BC-display');
                 BC_checker_display_parts[3] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-d-BC-display');
                 BC_checker_display_parts[4] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-e-BC-display');
                 BC_checker_display_parts[5] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-f-BC-display');
                 BC_checker_display_parts[6] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-g-BC-display');
                 BC_checker_display_parts[7] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-h-BC-display');
                 BC_checker_display_parts[8] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-i-BC-display');
                 BC_checker_display_parts[9] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-j-BC-display');
 //                console.log("BC_checker_display_parts",BC_checker_display_parts);

          let BC_checker_container_parts = new Array(10);
                 BC_checker_container_parts[0] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-a-BC-container');
                 BC_checker_container_parts[1] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-b-BC-container');
                 BC_checker_container_parts[2] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-c-BC-container');
                 BC_checker_container_parts[3] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-d-BC-container');
                 BC_checker_container_parts[4] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-e-BC-container');
                 BC_checker_container_parts[5] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-f-BC-container');
                 BC_checker_container_parts[6] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-g-BC-container');
                 BC_checker_container_parts[7] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-h-BC-container');
                 BC_checker_container_parts[8] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-i-BC-container');
                 BC_checker_container_parts[9] =document.getElementById('BC_checker2').contentWindow.document.getElementById('part-j-BC-container');
     //            console.log("BC_checker_container_parts",BC_checker_container_parts);

            for ( let k=0;k<9;k++){
             
              // if (checker_display_parts[k]){if(checker_display_parts[k].innerText =="display_none"){parts[k].classList.add("display_none");} else {parts[k].classList.remove("display_none");}}
              if (BC_checker_display_parts[k]){ if(BC_checker_display_parts[k].innerText =="display_blur"){BC_parts[k].classList.add("display_blur");} else {BC_parts[k].classList.remove("display_blur");}}
            }

            let BC_parts_in_checker = true;
            if (BC_parts_in_checker){
            var BC_part_a_this = document.getElementById("BC_parta"); // the section in the QRdisplayPblm document
 //           console.log("BC_part_a_this", BC_part_a_this);

            if (BC_part_a_this) {
              let BC_part_a_parent = BC_part_a_this.cloneNode(true);
  //            console.log("BC_part_a_parent", BC_part_a_parent);
              var BC_part_a = document.getElementById('BC_checker2').contentWindow.document.getElementById("part-a-BC-question"); // the section in this checker file
  //            console.log("BC_part_a", BC_part_a);
              if (BC_part_a && BC_part_a_parent) {
                BC_part_a.insertBefore(BC_part_a_parent, BC_part_a.childNodes[0]);
              }
            }



            var BC_part_b_this = document.getElementById("BC_partb"); // the section in the QRdisplayPblm document
            if (BC_part_b_this) {
              let BC_part_b_parent = BC_part_b_this.cloneNode(true);
              var BC_part_b = document.getElementById('BC_checker2').contentWindow.document.getElementById("part-b-BC-question"); // the section in this checker file
              if (BC_part_b && BC_part_b_parent) {
                let partb_BC_input = document.getElementById('BC_checker2').contentWindow.document.getElementById("part-b-BC");
                console.log ("BC_part_b",BC_part_b);
          //       partb_BC_input.addEventListener("mouseenter",function(e){
          //  //       console.log ("hover");
          //         BC_part_b_parent.classList.remove("display_blur");
          //         BC_part_b.classList.remove("display_blur");
          //        })
          //       partb_BC_input.addEventListener("mouseleave",function(e){
          //         BC_part_b_parent.classList.add("display_blur");
          //         BC_part_b.classList.add("display_blur");
          //        })
                BC_part_b.insertBefore(BC_part_b_parent, BC_part_b.childNodes[0]);
              }
            }



            var BC_part_c_this = document.getElementById("BC_partc"); // the section in the QRdisplayPblm document
            if (BC_part_c_this) {
              let BC_part_c_parent = BC_part_c_this.cloneNode(true);
              var BC_part_c = document.getElementById('BC_checker2').contentWindow.document.getElementById("part-c-BC-question"); // the section in this checker file
              if (BC_part_c && BC_part_c_parent) {
                BC_part_c.insertBefore(BC_part_c_parent, BC_part_c.childNodes[0]);
              }
            }


            var BC_part_d_this = document.getElementById("BC_partd"); // the section in the QRdisplayPblm document
            if (BC_part_d_this) {
              let BC_part_d_parent = BC_part_d_this.cloneNode(true);
              var BC_part_d = document.getElementById('BC_checker2').contentWindow.document.getElementById("part-d-BC-question"); // the section in this checker file
              if (BC_part_d && BC_part_d_parent) {
                BC_part_d.insertBefore(BC_part_d_parent, BC_part_d.childNodes[0]);
              }
            }


            var BC_part_e_this = document.getElementById("BC_parte"); // the section in the QRdisplayPblm document
            if (BC_part_e_this) {
              let BC_part_e_parent = BC_part_e_this.cloneNode(true);
              var BC_part_e = document.getElementById('BC_checker2').contentWindow.document.getElementById("part-e-BC-question"); // the section in this checker file
              if (BC_part_e && BC_part_e_parent) {
                BC_part_e.insertBefore(BC_part_e_parent, BC_part_e.childNodes[0]);
              }
            }


            var BC_part_f_this = document.getElementById("BC_partf"); // the section in the QRdisplayPblm document
            if (BC_part_f_this) {
              let BC_part_f_parent = BC_part_f_this.cloneNode(true);
              var BC_part_f = document.getElementById('BC_checker2').contentWindow.document.getElementById("part-f-BC-question"); // the section in this checker file
              if (BC_part_f && BC_part_f_parent) {
                BC_part_f.insertBefore(BC_part_f_parent, BC_part_f.childNodes[0]);
              }
            }


            var BC_part_g_this = document.getElementById("BC_partg"); // the section in the QRdisplayPblm document
            if (BC_part_g_this) {
              let BC_part_g_parent = BC_part_g_this.cloneNode(true);
              var BC_part_g = document.getElementById('BC_checker2').contentWindow.document.getElementById("part-g-BC-question"); // the section in this checker file
              if (BC_part_g && BC_part_g_parent) {
                BC_part_g.insertBefore(BC_part_g_parent, BC_part_g.childNodes[0]);
              }
            }


            var BC_part_h_this = document.getElementById("BC_parth"); // the section in the QRdisplayPblm document
            if (BC_part_h_this) {
              let BC_part_h_parent = BC_part_h_this.cloneNode(true);
              var BC_part_h = document.getElementById('BC_checker2').contentWindow.document.getElementById("part-h-BC-question"); // the section in this checker file
              if (BC_part_h && BC_part_h_parent) {
                BC_part_h.insertBefore(BC_part_h_parent, BC_part_h.childNodes[0]);
              }
            }

            var BC_part_i_this = document.getElementById("BC_parti"); // the section in the QRdisplayPblm document
            if (BC_part_i_this) {
              let BC_part_i_parent = BC_part_i_this.cloneNode(true);
              var BC_part_i = document.getElementById('BC_checker2').contentWindow.document.getElementById("part-i-BC-question"); // the section in this checker file
              if (BC_part_i && BC_part_i_parent) {
                BC_part_i.insertBefore(BC_part_i_parent, BC_part_i.childNodes[0]);
              }
            }


            var BC_part_j_this = document.getElementById("BC_partj"); // the section in the QRdisplayPblm document
            if (BC_part_j_this) {
              let BC_part_j_parent = BC_part_j_this.cloneNode(true);
              var BC_part_j = document.getElementById('BC_checker2').contentWindow.document.getElementById("part-j-BC-question"); // the section in this checker file
              if (BC_part_j && BC_part_j_parent) {
                BC_part_j.insertBefore(BC_part_j_parent, BC_part_j.childNodes[0]);
              }
            }






          }



        });
     
    
    
    if(reflect_flag == 0 && ref_choice == 0){$("#reflect").hide(); }    
    if(explore_flag == 0 && ref_choice == 0){$("#explore").hide(); }    
    if(connect_flag == 0 && ref_choice == 0){$("#connect").hide(); }    
    if(society_flag == 0 && ref_choice == 0){$("#society").hide(); }    
    
    
  $('#basecasebutton').click(function(){

        if(bc_display == false){bc_display =true;}else{bc_display =false;}
      
            if(bc_display && qr_code){
                    $("#problem").hide(); 
                    $("#base_case").show();                        
                    $("#checker").hide();
                    $("#BC_checker").hide();
                    $('#qrcode_id').hide();
                    $('#qrcode_id_bc').show();
                    $('#reflections').hide();
                    $('#reflectionsbutton').hide();
                    $('#basecasebutton').html('to Problem <i class="bi bi-arrow-up-square"></i>');
                   // $("#btnAddProfile").prop('value', 'Save');
            } else if(!bc_display && qr_code){
                    $("#problem").show(); 
                    $("#base_case").hide();                        
                    $("#checker").hide();
                    $("#BC_checker").hide();
                    $('#qrcode_id').show();
                    $('#qrcode_id_bc').hide();
                      $('#reflections').show();
                    $('#basecasebutton').html('to Base-case  <i class="bi bi-arrow-down-square"></i>');
            }else if(bc_display && !qr_code){
              // let bc_iframe = document.getElementById( "BC_checker2");
              //  console.log("bc_iframe",bc_iframe);
              // resizeIFrameToFitContent( bc_iframe );


                    $("#problem").hide(); 
                    $("#base_case").show();                        
                    $("#checker").hide();
                    $("#BC_checker").show();
                    $('#qrcode_id').hide();
                    $('#qrcode_id_bc').hide();
                     $('#reflections').hide();
                    $('#reflectionsbutton').hide();
                     $('#basecasebutton').html('to Problem <i class="bi bi-arrow-up-square"></i>');
            } else {
                    $("#problem").show(); 
                    $("#base_case").hide();                        
                    $("#checker").show();
                    $("#BC_checker").hide();
                    $('#qrcode_id').hide();
                    $('#qrcode_id_bc').hide();
                    $('#reflections').show();
                     $('#basecasebutton').html('to Base-case <i class="bi bi-arrow-down-square"></i>');
            }

        
     });
   
      
     $('#checkerbutton').click(function(){
        if(qr_code == false){qr_code =true;}else{qr_code =false;}
        

        if(bc_display && qr_code){
                    $("#problem").hide(); 
                    $("#base_case").show();         // should eramd tp [problems]               
                    $("#checker").hide();
                    $("#BC_checker").hide();
                    $('#qrcode_id').hide();
                    $('#qrcode_id_bc').show();
                     $('#reflections').hide();
                    $('#reflectionsbutton').hide();
                      $('#checkerbutton').html('to Checker <i class="bi bi-ui-checks"></i>'); 
                      $('#basecasebutton').html('to Problem <i class="bi bi-arrow-up-square"></i>'); 
            } else if(!bc_display && qr_code){
                    $("#problem").show(); 
                    $("#base_case").hide();                        
                    $("#checker").hide();
                    $("#BC_checker").hide();
                    $('#qrcode_id').show();
                    $('#qrcode_id_bc').hide();
                     $('#reflections').show();
                    $('#checkerbutton').html('to Checker <i class="bi bi-ui-checks"></i>'); 
                    $('#basecasebutton').html('to Bace-Case <i class="bi bi-arrow-down-square"></i>'); 

            }else if(bc_display && !qr_code){
                    $("#problem").hide(); 
                    $("#base_case").show();                        
                    $("#checker").hide();
                    $("#BC_checker").show();
                    $('#qrcode_id').hide();
                    $('#qrcode_id_bc').hide();
                    $('#reflections').hide();
                    $('#reflectionsbutton').hide();
                    $('#basecasebutton').html('to Problem <i class="bi bi-arrow-up-square"></i>'); 
                    $('#checkerbutton').html('to QR code <i class="bi bi-upc-scan"></i>'); 
            } else {
                    $("#problem").show(); 
                    $("#base_case").hide();                        
                    $("#checker").show();
                    $("#BC_checker").hide();
                    $('#qrcode_id').hide();
                    $('#qrcode_id_bc').hide();
                     $('#reflections').show();
                    $('#checkerbutton').html('to QR code <i class="bi bi-upc-scan"></i>'); 
                    $('#basecasebutton').html('to Bace-Case <i class="bi bi-arrow-down-square"></i>'); 

            }

    });



/*                 
        if (bc_display){
         $("#qrcode_id_bc").toggle();
        } else {
           $("#qrcode_id").toggle();  
        }
        */
   
     
      if(reflection_button_flag == 0){
        $('#reflectionsbutton').hide();
      }
       $('#reflectionsbutton').click(function(){
            if(reflection_button_flag == 1){
                $("#reflections").toggle();
            }
     });
     
     $('#directionsbutton').click(function(){
        $("#directions").toggle();
     });
     
     $('#questions').prepend('<p> Questions for '+stu_name+':</p>');
    // color the back botton a little different
	//		$("#backbutton").css({"background-color":"lightyellow",
           /*  
              "position": "relative",
              "left": "0px",
              "top": "0px",
            "z-index": "1"
             */
         //   });
              
             // console.log('buttons');
			// $("#backbut").css('z-index', '1')
			// go back to the input page for a different problem
			
          
            $("#backbutton").click(function(){
		  			
                    // e.preventDefault();
					// console.log("hello1");
				
                     window.location.replace('stu_frontpage.php?activity_id='+activity_id); // would like to put some parameters here instead of relying on session (like below)
                  //  window.location.replace('../QRP/QRExam.php'+'?examactivity_id='+examactivity_id); // axam_num and examactivity
              	
				 });
                 
                 
 /*  this was an experiment to sneek values from the iframe to this script using local vars on a timer - if I am going that way I will just use AJAX               
           var  count_from_check  =0;
           var ec_elgible_flag = 0;
             var changed_flag = 0;
     function change_count(){
          count_from_check = localStorage.getItem('count_from_check');
          ec_elgible_flag = localStorage.getItem('ec_elgible_flag');
          changed_flag = localStorage.getItem('changed_flag');  
        if (changed_flag == 1 &&  ec_elgible_flag ==1){
           hs = 1;  
            console.log (' hs: '+hs);
        }
     // console.log(' count_from_check: '+count_from_check);
     //    console.log('ec_elgible_flag: '+ec_elgible_flag);
     //     console.log('changed_flag: '+changed_flag);
    setTimeout(change_count, 3000);
}

change_count(); 
     // var count_from_check = localStorage.getItem('count_from_check');
     // var count_from_check = $('#checker2').contents().find('#count_from_check').val();
      */
      
      // this next block of code puts the text entries for the reflections into the activity table via AJAX
            $("#reflect_text_form").submit(function() {
           var reflect_text = $("#reflect_text").val();
            $.ajax({
                type: "POST",
                url: "addReflect_text.php",
                data: {
                  activity_id: activity_id,  
                  reflect_text: reflect_text
                },

                success: function(result) {
                  $("#reflect_confirm").html(result);
                  let save_reflect_button = document.getElementById("submit_reflect");
                  save_reflect_button.classList.remove("btn-danger");

                }
            });
          return false;
        });
        
              $("#connect_text_form").submit(function() {
           var connect_text = $("#connect_text").val();
            $.ajax({
                type: "POST",
                url: "addConnect_text.php",
                data: {
                  activity_id: activity_id,  
                  connect_text: connect_text
                },

                success: function(result) {
                  $("#connect_confirm").html(result);
                  let save_connect_button = document.getElementById("submit_connect");
                  save_connect_button.classList.remove("btn-danger");

                }
            });
          return false;
        });
      
      
       $("#explore_text_form").submit(function() {
           var explore_text = $("#explore_text").val();
            $.ajax({
                type: "POST",
                url: "addExplore_text.php",
                data: {
                  activity_id: activity_id,  
                  explore_text: explore_text
                },

                success: function(result) {
                  $("#explore_confirm").html(result);
                  let save_explore_button = document.getElementById("submit_explore");
                  save_explore_button.classList.remove("btn-danger");

                }
            });
          return false;
        });
        
          $("#society_text_form").submit(function() {
           var society_text = $("#society_text").val();
            $.ajax({
                type: "POST",
                url: "addSociety_text.php",
                data: {
                  activity_id: activity_id,  
                  society_text: society_text
                },

                success: function(result) {
                  $("#society_confirm").html(result);
                  let save_society_button = document.getElementById("submit_society");
                   save_society_button.classList.remove("btn-danger");

                }
            });
          return false;
        });

    $('#reflect_text').on('input',function(){
        $("#reflect_confirm").html('<div style = "color:red;"><b>changes not saved</b>');
      let save_reflect_button = document.getElementById("submit_reflect");
        save_reflect_button.classList.add("btn-danger");
 //       $("#submit_reflect").html('<div style = "color:red;"><b>changes not saved</b>');
    });
    $('#connect_text').on('input',function(){
        $("#connect_confirm").html('<div style = "color:red;"><b>changes not saved</b>');
        let save_connect_button = document.getElementById("submit_connect");
        save_connect_button.classList.add("btn-danger");

    });

    $('#explore_text').on('input',function(){
        $("#explore_confirm").html('<div style = "color:red;"><b>changes not saved</b>');
        let save_explore_button = document.getElementById("submit_explore");
        save_explore_button.classList.add("btn-danger");

    });
    $('#society_text').on('input',function(){
        $("#society_confirm").html('<div style = "color:red;"><b>changes not saved</b>');
        let save_society_button = document.getElementById("submit_society");
        save_society_button.classList.add("btn-danger");

    });

// put the points next to the reflections
   $('#reflect').append('('+perc_ref+' %)');
   $('#explore').append('('+perc_exp+' %)');
   $('#connect').append('('+perc_con+' %)');
   $('#society').append('('+perc_soc+' %)');

  //  let start_button_active = document.querySelector(".start-video-button");
  //   location.reload(true);
  // console.log ("start_button_active", start_button_active);
  
  // // if (typeof pause_vid_ar=='undefined') {
  // if (start_button_active == null && one_time == true) {
  //   location.reload(true);
  //   console.log("hard reload");
  // } else {
  //   console.log("no hard reload");
  // }


    // added the video-js player here to
   // const pausetime = 1000;


  // const vid1_flag = document.getElementById("vid1")
  // console.log ("vid1_flag "+vid1_flag);
  //  if (vid1_flag){
  //       const myPlayer = videojs('vid1', {
  //       playbackRates: [0.5,0.75, 1, 1.25, 1.5,1.75],
  //       controlBar: {
  //       fullscreenToggle: true,
  //       playToggle: true,
  //       captionsButton: true,
  //       chaptersButton: false,            
  //       subtitlesButton: true,
  //       remainingTimeDisplay: true,
  //       progressControl: {
  //         seekBar: false
  //       },
  //  //     fullscreenToggle: false,
  //       playbackRateMenuButton: true,
  //       }
  //       });
    
  //       myPlayer.on('timeupdate', function(e) {
  //         if (typeof pausetime !== 'undefined') {
  //             if (myPlayer.currentTime() >= pausetime) {
  //                 myPlayer.pause();
  //             }
  //          }
  //       });
  //       function skip(t) {
  //       myPlayer.currentTime(myPlayer.currentTime() +t);
  //       }
  //     }
  });



</script>

<script src="interactiveVideos.js"></script> 

<script src = "drawingTool.js"> </script>

 
</body>
</html>