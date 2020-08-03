<?php
require_once "pdo.php";
require_once "simple_html_dom.php";
include 'phpqrcode/qrlib.php'; 
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
         
    
      $qrcode = "<span id = 'qrcode_id'><right><img src='".$file."'><p> Problem Checker </p></right></span>"; 
      
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
    
      $qrcode_bc = "<span id = 'qrcode_id_bc'><right><img src='".$file_bc."'><p> Base_Case Checker </p></right></span>"; 
      
      

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
            $pattern[$i]= '/##'.$nvar[$i].'.+?##/';
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
       'switch_to_bc' => $switch_to_bc
      
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
   background-color: #e6f7ff;  
}
#BC_checker{
   background-color: #e6f7ff;  
}
#qrcode_id_bc{
   background-color: #e6f7ff;  
}

</style>
</head>

<body>


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
    
    if ($reflect_flag ==1){$reflect = $html->find('#reflect',0).'<div id = "reflect_confirm"> </div><form id = "reflect_text_form"><textarea id = "reflect_text"  r_class = "text_box" rows = "4" cols = "200" spellcheck = "true" maxlength = "3000" >'.htmlentities($activity_data["reflect_text"]).'</textarea><input type = "submit" id = "submit_reflect" value = "Save"></form>';}else {$reflect = '';}
    if ($connect_flag ==1){$connect = $html->find('#connect',0).'<div id = "connect_confirm"> </div><form id = "connect_text_form"><textarea id = "connect_text"  r_class = "text_box" rows = "4" cols = "200" spellcheck = "true" maxlength = "3000" >'.htmlentities($activity_data["connect_text"]).'</textarea><input type = "submit" id = "submit_connect" value = "Save"></form>';}else {$connect = '';}
    if ($explore_flag ==1){$explore = $html->find('#explore',0).'<div id = "explore_confirm"> </div><form id = "explore_text_form"><textarea id = "explore_text"  r_class = "text_box" rows = "4" cols = "200" spellcheck = "true" maxlength = "3000" >'.htmlentities($activity_data["explore_text"]).'</textarea><input type = "submit" id = "submit_explore" value = "Save"></form>';}else {$explore = '';}
    if ($society_flag ==1){$society = $html->find('#society',0).'<div id = "society_confirm"> </div><form id = "society_text_form"><textarea id = "society_text"  r_class = "text_box" rows = "4" cols = "200" spellcheck = "true" maxlength = "3000" >'.htmlentities($activity_data["society_text"]).'</textarea><input type = "submit" id = "submit_society" value = "Save"></form>';}else {$society = '';}
     
             // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced

      for( $i=0;$i<$nv;$i++){
            $problem = preg_replace($pattern[$i],$vari[$i],$problem);
            $base_case = preg_replace($pattern[$i],$BC_vari[$i],$base_case);
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
  // repeat with the base_case ______________________________________________________________________
     
         // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
            $base_case = preg_replace($pattern[$i],$vari[$i],$base_case);
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
         
         
    // only include the document above the checker
       $this_html ='<hr>'.$qrcode.$qrcode_bc.$problem.'<hr> <div id = "base_case"><h2>Base_Case:</h2>'.$base_case.'</div>';
 
   // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
            $this_html = preg_replace($pattern[$i],$vari[$i],$this_html);
        }
 
  echo $this_html; //-------------------------------------------------------display first part ------------------------------------------------------------------
  
 //  unlink('temp2 png');
  
  ?>
  <!--   -->
   <div id = 'checker'>
   <iframe name = "checker2" id = "checker2" src="QRChecker2.php?activity_id=<?php echo($activity_id);?>" style = "width:90%; height:50%;"></iframe></div>
      <div id = 'BC_checker'>
   <iframe  name ="BC_checker2" id = "BC_checker2" src="QR_BC_Checker2.php?activity_id=<?php echo($activity_id);?>" style = "width:90%; height:50%;"></iframe></div>

 <?php
 
 // the document below the checker
  $this_html = '<hr><div id = "reflections">'.$reflect.$explore.$connect.$society.'</div>';
  // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
            $this_html = preg_replace($pattern[$i],$vari[$i],$this_html);
        }
   // substitute for all of the varables, images and varaible images that might be in the reflections portion
   
   
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
       
       // turn base-case back into and simple_html_dom object that I can replace the varaible images on 
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
         
   
   
   
  
   echo $this_html; 
  // unlink('temp2 png');
 
 
 ?>
 
<script>
// try to read in a value from the iframe
 


 $(document).ready(function(){
    
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
             $('#checkerbutton').prop('value','to QR code'); 
          var bc_display = true;
        } else {
            var bc_display = false;
                 $('#qrcode_id_bc').hide();
                 $('#qrcode_id').hide();
                $('#base_case').hide();
                $('#directions').hide();
                $('#BC_checker').hide();
               $('#basecasebutton').prop('value','to Base-case');
                $('#checkerbutton').prop('value','to QR code'); 
        }
      
        
    
    
     var qr_code = false;
     var myFrame = document.getElementById('checker2').contentWindow
    
    $("#checker2").on("load", function () {
               //  var total_count = $("#checker2").contents().find("#total_count").html();
                var total_count = $("#checker2").contents().find("#total_count").text();
                 var PScore = $("#checker2").contents().find("#PScore").val();
                  switch_to_bc = $("#checker2").contents().find("#switch_to_bc").val();
                 
                var parent_test = document.getElementById('checker2').contentWindow.test;
              
                   var test2 = myFrame.test;
                
              //  console.log('test from parent: '+test);  
             
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
                      $('#checkerbutton').prop('value','to QR code'); 
                      bc_display = true;
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
                    $('#basecasebutton').prop('value','to Problem');
                   // $("#btnAddProfile").prop('value', 'Save');
            } else if(!bc_display && qr_code){
                    $("#problem").show(); 
                    $("#base_case").hide();                        
                    $("#checker").hide();
                    $("#BC_checker").hide();
                    $('#qrcode_id').show();
                    $('#qrcode_id_bc').hide();
                    $('#basecasebutton').prop('value','to Base-case');
            }else if(bc_display && !qr_code){
                    $("#problem").hide(); 
                    $("#base_case").show();                        
                    $("#checker").hide();
                    $("#BC_checker").show();
                    $('#qrcode_id').hide();
                    $('#qrcode_id_bc').hide();
                     $('#reflections').hide();
                    $('#reflectionsbutton').hide();
                     $('#basecasebutton').prop('value','to Problem');
            } else {
                    $("#problem").show(); 
                    $("#base_case").hide();                        
                    $("#checker").show();
                    $("#BC_checker").hide();
                    $('#qrcode_id').hide();
                    $('#qrcode_id_bc').hide();
                     $('#basecasebutton').prop('value','to Base-case');
            }

        
     });
   
      
     $('#checkerbutton').click(function(){
        if(qr_code == false){qr_code =true;}else{qr_code =false;}
        

        if(bc_display && qr_code){
                    $("#problem").hide(); 
                    $("#base_case").show();                        
                    $("#checker").hide();
                    $("#BC_checker").hide();
                    $('#qrcode_id').hide();
                    $('#qrcode_id_bc').show();
                     $('#reflections').hide();
                    $('#reflectionsbutton').hide();
                      $('#checkerbutton').prop('value','to Checker'); 
            } else if(!bc_display && qr_code){
                    $("#problem").show(); 
                    $("#base_case").hide();                        
                    $("#checker").hide();
                    $("#BC_checker").hide();
                    $('#qrcode_id').show();
                    $('#qrcode_id_bc').hide();
                    $('#checkerbutton').prop('value','to Checker'); 
            }else if(bc_display && !qr_code){
                    $("#problem").hide(); 
                    $("#base_case").show();                        
                    $("#checker").hide();
                    $("#BC_checker").show();
                    $('#qrcode_id').hide();
                    $('#qrcode_id_bc').hide();
                    $('#reflections').hide();
                    $('#reflectionsbutton').hide();
                    $('#checkerbutton').prop('value','to QR code'); 
            } else {
                    $("#problem").show(); 
                    $("#base_case").hide();                        
                    $("#checker").show();
                    $("#BC_checker").hide();
                    $('#qrcode_id').hide();
                    $('#qrcode_id_bc').hide();
                    $('#checkerbutton').prop('value','to QR code'); 
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
                }
            });
          return false;
        });

    $('#reflect_text').on('input',function(){
        $("#reflect_confirm").html('<div style = "color:red;"><b>changes not saved</b>');
    });
    $('#connect_text').on('input',function(){
        $("#connect_confirm").html('<div style = "color:red;"><b>changes not saved</b>');
    });

    $('#explore_text').on('input',function(){
        $("#explore_confirm").html('<div style = "color:red;"><b>changes not saved</b>');
    });
    $('#society_text').on('input',function(){
        $("#society_confirm").html('<div style = "color:red;"><b>changes not saved</b>');
    });

// put the points next to the reflections
   $('#reflect').append('('+perc_ref+' %)');
   $('#explore').append('('+perc_exp+' %)');
   $('#connect').append('('+perc_con+' %)');
   $('#society').append('('+perc_soc+' %)');

 });


</script>

 
</body>
</html>