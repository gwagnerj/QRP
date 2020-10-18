<?php
require_once "pdo.php";
require_once "simple_html_dom.php";
include 'phpqrcode/qrlib.php'; 
session_start();


// this strips out the get parameters so they are not in the url - its is not really secure data but I would rather not having people messing with them
// if they do not know what they are doing


// check input and send them back if not proper
    if( isset($_POST['currentclass_id'])){
         $currentclass_id = $_POST['currentclass_id'];
    }else {
        $_SESSION['error']= 'no currentclass_id in QRExamPrint1';
        header("Location: QRExamPrint0.php");
        die();    
    }
     if( isset($_POST['exam_num'])){
         $exam_num = $_POST['exam_num'];
     } else {
        $_SESSION['error']= 'no exam_num in QRExamPrint1';
        header("Location: QRExamPrint0.php");
        die();    
    }
     if( isset($_POST['iid'])){
         $iid = $_POST['iid'];
     } else {
        $_SESSION['error']= 'no iid in QRExamPrint1';
        header("Location: QRExamPrint0.php");
        die();    
    }
     if( isset($_POST['exam_version'])){
         $exam_version = $_POST['exam_version'];
     } else {
        $_SESSION['error']= 'no exam_version in QRExamPrint1';
        header("Location: QRExamPrint0.php");
        die();    
    }
     if( isset($_POST['num_versions'])){
         $num_versions = $_POST['num_versions'];
     } else {
        $_SESSION['error']= 'no num_versions in QRExamPrint1';
        header("Location: QRExamPrint0.php");
        die();    
    }
     if( isset($_POST['sets'])){
         $sets = $_POST['sets'];
     } else {
        $_SESSION['error']= 'no sets in QRExamPrint1';
        header("Location: QRExamPrint0.php");
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

                    
              $sql = " SELECT `name` FROM `CurrentClass` WHERE currentclass_id = :currentclass_id" ;
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                         ':currentclass_id' => $currentclass_id,
                    ));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($row != false){
                        $cclass_name = $row['name']; 
                    } else {
                       $_SESSION['error'] = 'Currentclass table could not read - Class Not Valid';
                       
                    }   
            if ($exam_version == 1){
            // get all of the students in the class - name of student, dex of student
             $sql = " SELECT * FROM `Student` INNER JOIN StudentCurrentClassConnect ON Student.student_id = StudentCurrentClassConnect.student_id  WHERE StudentCurrentClassConnect.currentclass_id = :currentclass_id ORDER BY last_name ASC" ;
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                         ':currentclass_id' => $currentclass_id,
                    ));
                   $student_data = $stmt->fetchALL(PDO::FETCH_ASSOC);
            //       var_dump($student_data);
            
            }

            // get all of the data for the exam for each problem
                    
             $sql = " SELECT * FROM `Exam` WHERE currentclass_id = :currentclass_id AND exam_num = :exam_num ORDER BY alias_num" ;
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                         ':currentclass_id' => $currentclass_id,
                             ':exam_num' => $exam_num,
                    ));
                    $exam_data = $stmt->fetchALL(PDO::FETCH_ASSOC);
                       
               //        echo (' exam_data <br>');
                       
                  //     var_dump($exam_data);       
                  	
?>

<!DOCTYPE html>
<html lang = "en">
<head>
<meta charset="UTF-8">

<link rel="icon" type="image/png" href="McKetta.png" >

<title>QR Exam Print</title> 
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
#backbutton {
  position: relative;
  left: 0px;
  top: 0px;
  z-index: 1;
}
#directionsbutton {
  position: relative;
  left: 0px;
  top: 0px;
  z-index: 1;
}

#reflectionsbutton {
  position: relative;
  left: 0px;
  top: 0px;
  z-index: 1;
}

@media print {
        #go_back {
          display:none;
        }
}
</style>


</head>

<body>

<a id = "go_back" href="QRPRepo.php">Finished / Cancel - go back to Repository  <br><hr></a>
<?php

// put in a button or link to go back to rhe repo
  
 
//echo '<br><hr>';
//echo  '<p style="page-break-before: always"> ';    	


    if ($exam_version == 1){
// big loop for each student

        foreach($student_data as $student_datum){
            $pin = $student_datum['pin'];
              $dex = ($pin-1) % 199 + 2;
              //echo(' dex '.$dex);
            
          //  echo $student_datum['first_name']  ;
          // for each problem on the exam
          foreach($exam_data as $exam_datum){
                //      echo $exam_datum['problem_id'];
                $problem_id = $exam_datum['problem_id'];
                $sql = "SELECT * FROM Problem WHERE problem_id = :problem_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(':problem_id' => $problem_id));
                $pblm_data = $stmt -> fetch();
                $contrib_id = $pblm_data['users_id'];
                $nm_author = $pblm_data['nm_author'];
                $specif_ref = $pblm_data['specif_ref'];
                $htmlfilenm = $pblm_data['htmlfilenm'];
            
                

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
              
               // Read in the value for the input variables
               
                for ($i = 0; $i < $nv; $i++) {
                    if($row['v_'.($i+1)]!='Null' ){
                        $vari[$i] = $row['v_'.($i+1)];
             //           echo ('  $vari[$i]: '. $vari[$i]);
                        $pattern[$i]= '/##'.$nvar[$i].',.+?##/';
                    }
                }
       $stu_name = $student_datum['first_name'].'&nbsp; '.$student_datum['last_name'];      
        $alias_num = $exam_datum['alias_num'];      
  $html = new simple_html_dom();
      $html->load_file($htmlfilenm);
      $header_stuff = new simple_html_dom();
      $header_stuff -> load_file('exam_problem_print_header_stuff.html');
            // subbing in the header
       $header_stuff ->find('#stu_name',0)->innertext = $stu_name;
      $header_stuff ->find('#course',0)->innertext = $cclass_name;
       $header_stuff ->find('#exam_num',0)->innertext = $exam_num;
       $header_stuff ->find('#problem_num',0)->innertext = $alias_num;

  $problem = $html->find('#problem',0);
  
   for( $i=0;$i<$nv;$i++){
           if($row['v_'.($i+1)]!='Null' ){
            $problem = preg_replace($pattern[$i],$vari[$i],$problem);
           }
        }
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
        
               
    // only include the document above the checker
       $this_html ='<br>'.$problem;
 
   // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
             if($row['v_'.($i+1)]!='Null' ){
                $this_html = preg_replace($pattern[$i],$vari[$i],$this_html);
             }
        }
         echo($header_stuff);
        // echo ('<hr>');
          echo $this_html; 
           echo  '<p style="page-break-before: always"> ';        
              
              
              
              
          }
            
        }
    }  elseif ($exam_version == 2){ // we have a limited number of versions of the printed exam

        // echo(' exam_version: '.$exam_version);
        // echo(' num_versions: '.$num_versions);
         //$sets = 2;
           for ($set=1;$set<=$sets;$set++)  {
                   for( $ver=0;$ver<$num_versions;$ver++){
                      
                        $stu_name = '<u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>';      
                         
                          $header_stuff = new simple_html_dom();
                          $header_stuff -> load_file('exam_problem_print_header_stuff.html');
                                // subbing in the header
                           $header_stuff ->find('#stu_name',0)->innertext = $stu_name;
                          $header_stuff ->find('#course',0)->innertext = $cclass_name;
                           $header_stuff ->find('#exam_num',0)->innertext = $exam_num;
                          

                      
                      
                      
                      
                        $index_start = 150;     // arbitrarily start getting the versions from a dex of 150 and counting up from there
                        $dex = $index_start + $ver;
                        
                           // make the dex code by hassing the dex
                           $key = rand(1,9);
                           $last_dig = rand(0,9);
                           if ($key ==1){$mid_two = $ver +$last_dig+16;}
                           if ($key ==2){$mid_two = $ver +$last_dig+25;}
                           if ($key ==3){$mid_two = $ver+$last_dig +36;}
                           if ($key ==4){$mid_two = $ver+$last_dig +49;}
                           if ($key ==5){$mid_two = $ver+$last_dig +64;}
                           if ($key ==6){$mid_two = $ver+$last_dig +81;}
                           if ($key ==7){$mid_two = $ver+$last_dig +53;}
                           if ($key ==8){$mid_two = $ver+$last_dig +23;}
                           if ($key ==9){$mid_two = $ver+$last_dig +73;}
                        
                        $dex_code = $key.$mid_two.$last_dig;
                        
                       // echo(' dex_code:  '.$dex_code);

                        // Make the QRcode 

                            $qrcode_text =  'https://www.qrproblems.org/QRP/QRExamRegistration.php?dex_code='.$dex_code; 
                                            
                            $file = 'uploads/temp_exam png';   // where the qrimage is going to be stored in uploads directory
                              
                            // $ecc stores error correction capability('L') 
                            $ecc = 'M'; 
                            $pixel_size = 2; 
                            $frame_size = 1; 
                              
                            // Generates QR Code and Stores it in directory given 
                              QRcode::png($qrcode_text, $file, $ecc, $pixel_size, $frame_size); 
                             // QRcode::png($text); 
                            // Displaying the stored QR code from directory 
                        
                          $qrcode = "<span ><img src='".$file."'><br>Code: ".$dex_code." </span>"; 
                            $header_stuff ->find('#qr_code_id',0)->innertext = $qrcode;
                            echo($header_stuff);
                           echo ('<hr>');
                            
                      //  echo ($qrcode);
                       
                         
                     foreach($exam_data as $exam_datum){
                        //      echo $exam_datum['problem_id'];
                     
                        
                        $problem_id = $exam_datum['problem_id'];
                        $sql = "SELECT * FROM Problem WHERE problem_id = :problem_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(array(':problem_id' => $problem_id));
                        $pblm_data = $stmt -> fetch();
                        $contrib_id = $pblm_data['users_id'];
                        $nm_author = $pblm_data['nm_author'];
                        $specif_ref = $pblm_data['specif_ref'];
                        $htmlfilenm = $pblm_data['htmlfilenm'];
                        
                        

                        $htmlfilenm = "uploads/".$htmlfilenm;
                        $alias_num = $exam_datum['alias_num'];      
                         
                        echo ('<h4>'.$alias_num.')</h4>'); 
                         
                         
                         $html = new simple_html_dom();
                          $html->load_file($htmlfilenm);

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
                      
                       // Read in the value for the input variables
                       
                        for ($i = 0; $i < $nv; $i++) {
                            if($row['v_'.($i+1)]!='Null' ){
                                $vari[$i] = $row['v_'.($i+1)];
                     //           echo ('  $vari[$i]: '. $vari[$i]);
                                $pattern[$i]= '/##'.$nvar[$i].',.+?##/';
                            }
                        }
          

          $problem = $html->find('#problem',0);
          
           for( $i=0;$i<$nv;$i++){
                   if($row['v_'.($i+1)]!='Null' ){
                    $problem = preg_replace($pattern[$i],$vari[$i],$problem);
                   }
                }
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
                
                       
            // only include the document above the checker
               $this_html ='<br>'.$problem;
         
           // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
               for( $i=0;$i<$nv;$i++){
                     if($row['v_'.($i+1)]!='Null' ){
                        $this_html = preg_replace($pattern[$i],$vari[$i],$this_html);
                     }
                }
                
                  echo $this_html; 
                   echo  '<p style="page-break-before: always"> ';        
                      
                      
                      
                      
                  }
          
                       
            }        
         }
    }        
        

 die();


  

  // $dex = 10;
  //  echo('dex: '.$dex);  
 
   // see if we need a reflections button
 // think if we want to add reflection type questions to exam problems - right now they are not in the exam table (they are in the assign table) 
  /*  
   if ($ref_choice!=0 || $reflect_flag !=0 || $explore_flag !=0 || $connect_flag !=0 || $society_flag !=0){
     $reflection_button_flag = 1;  
   } else {
     $reflection_button_flag = 0; 
   }
    */
   
//------------------------------------------------------------------------------------------------------------

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



<form method = "POST" Action = "">
         <input type="hidden" id = "problem_id" name="problem_id" value="<?php echo ($problem_id)?>" >

</form>
  <!-- <div style="background-image: url('Water_Mark_for_exam.png');">  -->
   
<img id = "water_mark" src="uploads/Water_Mark_for_exam_trans_bckgrnd.png" >

  

 


<?php

 ?>
  <!--  
   <div id = 'checker'>
   <iframe name = "checker2" id = "checker2" src="QRChecker2.php?activity_id=<?php echo($activity_id);?>" style = "width:90%; height:50%;"></iframe></div>
 -->
 <?php
 
 
?>
 <div id = 'examchecker'>
 <iframe src="QRExamCheck.php?exam_num=<?php echo($exam_num);?>&cclass_id=<?php echo($cclass_id);?>&alias_num=<?php echo($alias_num);?>&pin=<?php echo($pin);?>&iid=<?php echo($iid);?>&examactivity_id=<?php echo($examactivity_id);?>&problem_id=<?php echo($problem_id);?>&dex=<?php echo($dex);?>" style = "width:70%; height:50%;"></iframe>

</div>

<script>
 $(document).ready(function(){

 
     var examactivity_id = pass['examactivity_id']; 
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
       

  $('#questions').prepend('<p> Questions for '+stu_name+':</p>');


  	$("#backbutton").css({"background-color":"lightyellow",
            });
    
            $("#backbutton").click(function(){
                     window.location.replace('QRExam.php?examactivity_id='+examactivity_id); // would like to put some parameters here instead of relying on session (like below)
			 });

        $('#directions').hide();
    
     $('#directionsbutton').click(function(){
        $("#directions").toggle();
     });
     
    
        // disable right mouse click copy and copy paste  From https://www.codingbot.net/2017/03/disable-copy-paste-mouse-right-click-using-javascript-jquery-css.html
            //Disable cut copy paste
            $('body').bind('cut copy paste', function (e) {
                e.preventDefault();
            });
            
            //Disable mouse right click
            $("body").on("contextmenu",function(e){
                return false;
            });
        

 /*    
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
         //Turn this off for now - will release this feature later   !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  
          
    
    
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
                      $('#reflections').show();
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
                    $('#reflections').show();
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
                     $('#reflections').show();
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
                     $('#reflections').show();
                    $('#checkerbutton').prop('value','to QR code'); 
            }

    });
 */
});

 </script>
</body>
</html>