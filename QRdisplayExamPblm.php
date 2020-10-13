<?php
require_once "pdo.php";
require_once "simple_html_dom.php";
include 'phpqrcode/qrlib.php'; 
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
 /*   
// if we are only wanting the checker then send then to exam_checker_only.php
      if( isset($_POST['checker'])){
         $checker = $_POST['checker'];
    } elseif (isset($_GET['checker'])) {
         $checker = $_GET['checker']; 
    } else {
       $checker = '42 on the QRdisplayExamPblm';
    }
 */



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
                      //  header("Location: QRExamRegistration.php");
                        header("Location: StopExam.php");

                        die();     
                    }
                    
                    if ($globephase !=1){
                         $_SESSION['error'] = 'Exam is not in progress';
                        header("Location: QRExam.php?examactivity_id=".$examactivity_id );
                      //  header("Location: StopExam.php" );
                      
                        die();     
                        
                    }
                    
                // echo (' checker: '.$checker);
                  if(isset($_POST['checker']) && ($_POST['checker'] == "checker_only" || $_SESSION['checker'] == "checker_only" )){
                      $checker_only = 1;
                      //  header("Location: exam_checker_only.php");
                      //  die();      
                    } elseif (isset($_POST['checker']) && $_POST['checker'] == "problem_and_checker"){
                         $checker_only = 0;
                    } else {
                        $checker_only = 0;
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
                    	

  $sql = "SELECT * FROM Problem WHERE problem_id = :problem_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':problem_id' => $problem_id));
	$pblm_data = $stmt -> fetch();
    $contrib_id = $pblm_data['users_id'];
    $nm_author = $pblm_data['nm_author'];
    $specif_ref = $pblm_data['specif_ref'];
    $htmlfilenm = $pblm_data['htmlfilenm'];

// this is the old way with substituting the variables in with the JS - now we are going to build this up with the php just like the homework - need to steel code from QRdisplayPblem.php


        $htmlfilenm = "uploads/".$htmlfilenm;
if($checker_only ==0){
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


} 

        $pass = array(
            'checker'=>$_POST['checker'],
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
</style>


</head>

<body>

<form method = "POST" Action = "">
         <input type="hidden" id = "problem_id" name="problem_id" value="<?php echo ($problem_id)?>" >

</form>
  <!-- <div style="background-image: url('Water_Mark_for_exam.png');">  -->
<?php   


    if($checker_only ==0){
        echo('<img id = "water_mark" src="uploads/Water_Mark_for_exam_trans_bckgrnd.png" >');

    }

 




  $html = new simple_html_dom();
      $html->load_file($htmlfilenm);
      $header_stuff = new simple_html_dom();
      $header_stuff -> load_file('exam_problem_header_stuff.html');
            // subbing in the header
       $header_stuff ->find('#stu_name',0)->innertext = $stu_name;
      $header_stuff ->find('#course',0)->innertext = $cclass_name;
       $header_stuff ->find('#exam_num',0)->innertext = $exam_num;
       $header_stuff ->find('#problem_num',0)->innertext = $alias_num;
       
       echo($header_stuff);
       
       
if($checker_only ==0){
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
       $this_html ='<hr><br>'.$problem;
 
   // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
             if($row['v_'.($i+1)]!='Null' ){
                $this_html = preg_replace($pattern[$i],$vari[$i],$this_html);
             }
        }
  echo $this_html; 
} 
  
 ?>
  <!--  
   <div id = 'checker'>
   <iframe name = "checker2" id = "checker2" src="QRChecker2.php?activity_id=<?php echo($activity_id);?>" style = "width:90%; height:50%;"></iframe></div>
 -->
 <?php
 
 
?>
 <div id = 'examchecker'>
 <iframe src="QRExamCheck.php?exam_num=<?php echo($exam_num);?>&cclass_id=<?php echo($cclass_id);?>&alias_num=<?php echo($alias_num);?>&pin=<?php echo($pin);?>&iid=<?php echo($iid);?>&examactivity_id=<?php echo($examactivity_id);?>&problem_id=<?php echo($problem_id);?>&dex=<?php echo($dex);?>" style = "width:80%; height:70%;"></iframe>

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
      var checker = pass['checker'];
      var switch_to_bc = pass['switch_to_bc'];
       

  $('#questions').prepend('<p> Questions for '+stu_name+':</p>');


  	$("#backbutton").css({"background-color":"lightyellow",
            });
    
            $("#backbutton").click(function(){
                     window.location.replace('QRExam.php?examactivity_id='+examactivity_id+'&checker='+checker); // would like to put some parameters here instead of relying on session (like below)
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