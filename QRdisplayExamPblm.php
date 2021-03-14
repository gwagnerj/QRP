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
        header('Location: QRdisplayExamPblm.php');
        die;
    } else{
        if (!empty($_SESSION['got'])) {
            $_GET = $_SESSION['got'];
            unset($_SESSION['got']);
        }
    }
    */
   // if we are comming in on a get we should get the eregistration_id and then populate the variables we need for the tables:

    if ((isset($_GET['eregistration_id']) || isset($_POST['eregistration_id'])) && (isset($_GET['problem_id']) || isset($_POST['problem_id']))){
          if (isset($_GET['eregistration_id'])){
              $eregistration_id = $_GET['eregistration_id'];
          }elseif(isset($_POST['eregistration_id'])){
              $eregistration_id = $_POST['eregistration_id'];
          }
          if(isset($_GET['problem_id'])){
            $problem_id = $_GET['problem_id'];
          }elseif(isset($_POST['problem_id'])){
            $problem_id = $_POST['problem_id'];
          }
      } else{
        $_SESSION['error'] = 'Lost eregistration_id or problem_id in QRDisplayExamPblm';
        header("Location: stu_exam_frontpage.php?eregistration_id=".$eregistration_id);
        die();   

      }
      
    
        // get the eactivity form the data tables
        
        $sql = ' SELECT `eactivity_id`
          FROM `Eactivity` WHERE `eregistration_id` = :eregistration_id AND `problem_id` = :problem_id ORDER BY eactivity_id DESC LIMIT 1';
          $stmt = $pdo->prepare($sql);
          $stmt->execute(array(
            ':eregistration_id' => $eregistration_id,
            ':problem_id' => $problem_id
          ));
          $eactivity_id_data = $stmt->fetch(PDO::FETCH_ASSOC);
          //var_dump($eactivity_id_data);

          $eactivity_id = $eactivity_id_data['eactivity_id'];
     //    echo ('<br>');
     //     echo ('eactivity_id: '.$eactivity_id);
      

          
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
 $sql = "SELECT checker_only,switch_to_bc,progress, dex,currentclass_id,alias_num,Eregistration.exam_code AS exam_code,last_name,first_name,  Eregistration.eexamnow_id AS eexamnow_id, eexamtime_id, Eregistration.student_id AS student_id
FROM `Eactivity`
         LEFT JOIN Eregistration ON Eregistration.eregistration_id = Eactivity.eregistration_id
         LEFT JOIN Student ON Student.student_id = Eactivity.student_id
         LEFT JOIN Eexamnow ON Eexamnow.eexamnow_id = Eactivity.eexamnow_id
         
    WHERE eactivity_id = :eactivity_id";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
             ':eactivity_id' => $eactivity_id,
          )); 
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
          //  var_dump($row);
             if($row != false){
                 $eexamtime_id = $row['eexamtime_id']; 
 //               $iid = $row['iid'];
                $switch_to_bc = $row['switch_to_bc'];
                if (is_null($switch_to_bc)){$switch_to_bc = 0;}
                $progress = $row['progress'];
                $checker_only = $row['checker_only'];
                $dex = $row['dex'];
                $student_id = $row['student_id'];
                $stu_name = $row['first_name'].' '.$row['last_name'];
                $exam_code = $row['exam_code'];
                $cclass_id = $row['currentclass_id'];
                $suspend_flag = 0;
                $eexamnow_id = $row['eexamnow_id'];

      // get the work_flow  and send it to the JS  (work_flow can be open, bc_if and bc_first)
  $sql = 'SELECT work_flow FROM Eexamtime WHERE eexamtime_id = :eexamtime_id';
  $stmt = $pdo->prepare($sql);
  $stmt -> execute(array(
    ':eexamtime_id' => $eexamtime_id,
 )); 
   $work_flows = $stmt->fetch();
   $work_flow = $work_flows['work_flow'];
//echo 'workflow '.$work_flow;


// var_dump($row);

         $sql = 'SELECT iid FROM CurrentClass
                WHERE currentclass_id = :currentclass_id';
                  $stmt = $pdo->prepare($sql);
                  $stmt -> execute(array(
                  ':currentclass_id' => $cclass_id,
                  )); 
                  $iid_data = $stmt->fetch(PDO::FETCH_ASSOC);
                  $iid = $iid_data['iid']; 



        $sql = 'SELECT pin FROM StudentCurrentClassConnect 
        WHERE student_id = :student_id AND currentclass_id = :currentclass_id';
          $stmt = $pdo->prepare($sql);
          $stmt -> execute(array(
          ':student_id' => $student_id,
          ':currentclass_id' => $cclass_id,
          )); 
          $pin_data = $stmt->fetch(PDO::FETCH_ASSOC);
          $pin = $pin_data['pin'];



               

             } else {
                 $_SESSION['error'] = 'examactivity table could not be read in QRdisplayExamPblem.php';
                header("Location: QRExamRegistration.php");
                die();  
             }

            $sql = " SELECT * FROM `Eexamnow` WHERE eexamnow_id = :eexamnow_id" ;
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                         ':eexamnow_id' => $eexamnow_id,
                    ));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($row != false){
                        $globephase = $row['globephase']; 
                        $eexamtime_id = $row['eexamtime_id'];
                      //  $game_flag = $row['game_flag'];

                     } else {
                       $_SESSION['error'] = 'examtime table could not read - Exam over or not Initiated';
                    //    header("Location: QRExamRegistration.php");
                      //  header("Location: StopExam.php");

                        die();     
                    }
                    
                    if ($globephase !=1){

                        // test to see if we are running a game and if they are the team captaining
                       if($globephase ==3){
                        $sql = " SELECT * FROM `Eexamtime` WHERE eexamtime_id_id = :eexamtime_id" ;
                        $stmt = $pdo->prepare($sql);
                        $stmt -> execute(array(
                             ':eexamtime_id' => $eexamtime_id,
                        ));
                        $eexamtime_data = $stmt->fetch(PDO::FETCH_ASSOC);
                        $game_flag = $eexamtime_data['game_flag'];



                          if ($game_flag ==1){

                            $sql = "SELECT team_cap FROM TeamStudentConnect WHERE `eexamnow_id` = :eexamnow_id AND student_id = :student_id";
                            $stmt = $pdo->prepare($sql);
                            $stmt -> execute(array(
                              ':eexamnow_id' => $eexamnow_id,
                              ':student_id' => $student_id,
                            ));
                            $teamstudentconnect_data = $stmt->fetch();
                                if ($teamstudentconnect_data['team_cap']==1){
                                  header("Location: teamcaptain.php");
                                  die();  
                  
                                }
                              }

                        }

                         $_SESSION['error'] = 'Exam is not in progress';
                        header("Location: stu_exam_frontpage.php?eregistration_id=".$eregistration_id );
                      //  header("Location: StopExam.php" );
                      
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
             $sql = " SELECT * FROM `Eexam` WHERE currentclass_id = :currentclass_id AND problem_id = :problem_id AND iid = :iid" ;
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                         ':currentclass_id' => $cclass_id,
                           ':problem_id' => $problem_id,
                             ':iid' => $iid,
                    ));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($row != false){
                        $alias_num = $row['alias_num']; 
                        $eexam_id = $row['eexam_id'];
                        $exam_num = $row['exam_num'];
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
// if($checker_only ==0){
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
                 
         //  var_dump($BC_vari);


           // Read in the value for the input variables
           
            for ($i = 0; $i < $nv; $i++) {
                if($row['v_'.($i+1)]!='Null' ){

                  $pattern[$i]= '/##'.$nvar[$i].',.+?##/';
                  $vari[$i] = $row['v_'.($i+1)];
                  $BC_vari[$i] = $BC_row['v_'.($i+1)];
                 
                 //   $base_case = preg_replace($pattern[$i],$BC_row[$i],$base_case);
                    //           echo ('  $vari[$i]: '. $vari[$i]);
                    
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


// } 

        $pass = array(
            'checker'=>$checker_only,
           'eregistration_id' => $eregistration_id,
           'progress' => $progress,
            'dex' => $dex,
            'problem_id' => $problem_id,
            'stu_name' => $stu_name,
           'pin' => $pin,
            'iid' => $iid,
            'alias_num' => $alias_num,
            'exam_num' => $exam_num,
            'assign_num' => $exam_num,
            'cclass_id' => $cclass_id,
            'eactivity_id' => $eactivity_id,
            'cclass_name' => $cclass_name,
            'eexamtime_id' => $eexamtime_id,
            'switch_to_bc' => $switch_to_bc,
            'work_flow'=> $work_flow
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
#basecasebutton {
  position: relative;
  left: 0px;
  top: 0px;
  z-index: 1;
}
#display_pblm_button{
  position: relative;
  left: 0px;
  top: 0px;
  z-index: 1;
}

#base_case{
   background-color: #e6f7ff;  
}
#BC_checker{
   background-color: #e6f7ff;  
}
#qrcode_id_bc{
   background-color: #e6f7ff;  
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
<input type="hidden" id = "eregistration_id" name="eregistration_id" value="<?php echo ($eregistration_id)?>" >

</form>
  <!-- <div style="background-image: url('Water_Mark_for_exam.png');">  -->
<?php   



        echo('<img id = "water_mark" src="uploads/Water_Mark_for_exam_trans_bckgrnd.png" >');



 




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
       
       

  $problem = $html->find('#problem',0);
  $base_case = $html->find('#problem',0); 

  
   for( $i=0;$i<$nv;$i++){
           if($row['v_'.($i+1)]!='Null' ){
            $problem = preg_replace($pattern[$i],$vari[$i],$problem);
            $base_case = preg_replace($pattern[$i],$BC_vari[$i],$base_case);

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
    $this_html ='<hr> <div id = "base_case"><h2>Base_Case:</h2>'.$base_case.'</div>';







      
               
    // only include the document above the checker
      // $this_html ='<hr><br>'.$problem;
       $this_html = $problem.' <div id = "base_case"><h2>Base_Case:</h2>'.$base_case.'</div>';

 
   // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
             if($row['v_'.($i+1)]!='Null' ){
                $this_html = preg_replace($pattern[$i],$vari[$i],$this_html);
             }
        }
  echo $this_html; 

 
/* 
echo ' dex '.$dex.'<br>';
echo ' pin '.$pin.'<br>';
echo ' alias_num '.$alias_num.'<br>';
 */
 ?>


 <div id = 'examchecker'>
 <iframe src="QRExamCheck.php?exam_num=<?php echo($exam_num);?>&cclass_id=<?php echo($cclass_id);?>&alias_num=<?php echo($alias_num);?>&pin=<?php echo($pin);?>&iid=<?php echo($iid);?>&eactivity_id=<?php echo($eactivity_id);?>&problem_id=<?php echo($problem_id);?>&dex=<?php echo($dex);?>" style = "width:80%; height:70%;"></iframe>

 </div>

 <div id = 'BC_checker'>
   <iframe   src="QRExamCheck.php?exam_num=<?php echo($exam_num);?>&cclass_id=<?php echo($cclass_id);?>&alias_num=<?php echo($alias_num);?>&pin=<?php echo($pin);?>&iid=<?php echo($iid);?>&eactivity_id=<?php echo($eactivity_id);?>&problem_id=<?php echo($problem_id);?>&dex=1>" style = "width:80%; height:70%;"></iframe>


</div>

<script>
  $(document).ready(function(){

 
     const eactivity_id = pass['eactivity_id']; 
     const stu_name = pass['stu_name']; 
     const reflection_button_flag = pass['reflection_button_flag'];
      const eregistration_id =pass['eregistration_id'];
      const reflect_flag = pass['reflect_flag']; 
      const explore_flag = pass['explore_flag']; 
      const connect_flag = pass['connect_flag']; 
      const society_flag = pass['society_flag']; 
      const ref_choice = pass['ref_choice']; 
      const perc_ref = pass['perc_ref'];
      const perc_exp = pass['perc_exp'];
      const perc_con = pass['perc_con'];
      const perc_soc = pass['perc_soc'];
      const checker = pass['checker'];
      let switch_to_bc = pass['switch_to_bc'];
      const work_flow = pass['work_flow'];
      const progress = pass['progress'];
      console.log('switch_to_bc: '+switch_to_bc);   

  $('#questions').prepend('<p> Questions for '+stu_name+':</p>');


  	$("#backbutton").css({"background-color":"lightyellow",
            });
    console.log('eregistration_id: '+eregistration_id);
            $("#backbutton").click(function(){
                     window.location.replace('stu_exam_frontpage.php?eregistration_id='+eregistration_id); // would like to put some parameters here instead of relying on session (like below)
			 });

        $('#directions').hide();
    
     $('#directionsbutton').click(function(){
        $("#directions").toggle();
     });
console.log ('work_flow '+work_flow );


if (checker == 1){  // this is the default value comiong in to problem
 $('#problem').hide();
 $('#water_mark').hide();
}

$('#display_pblm_button').click(function(){
    $('#problem').toggle();
    $('#water_mark').toggle();
     });


if (work_flow == 'bc_first' && progress < 1 ){
    switch_to_bc = 1;
    $('#water_mark').hide();
    $('#basecasebutton').hide();


}



// initial state of the display
 // if(work_flow =='bc_first' )
/* 
 if (work_flow =='open' || progress >1 || switch_to_bc !=1 ){ 
 }




     $('#basecasebutton').click(function(){
    console.log('click');
     });

 */
    // $('#basecasebutton').hide();


     

 /*   
        // disable right mouse click copy and copy paste  From https://www.codingbot.net/2017/03/disable-copy-paste-mouse-right-click-using-javascript-jquery-css.html
            //Disable cut copy paste
            $('body').bind('cut copy paste', function (e) {
                e.preventDefault();
            });
            
            //Disable mouse right click
            $("body").on("contextmenu",function(e){
                return false;
            });
        
  
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
    */
 
    console.log('switch_to_bc: '+switch_to_bc);
    // switch_to_bc = 1;
      if (switch_to_bc == 1){
         $('#base_case').show();
         $('#BC_checker').show();
          $("#problem").hide(); 
         $('#reflections').hide();
          $("#examchecker").hide();
           $('#basecasebutton').hide();
         $('#qrcode_id_bc').hide();
         $('#reflectionsbutton').hide();
          $('#qrcode_id').hide(); 
         
       //      $('#checkerbutton').prop('value','to QR code'); 
          var bc_display = true;
        } else {
            var bc_display = false;
                 $('#qrcode_id_bc').hide();
                 $('#qrcode_id').hide();
                $('#base_case').hide();
                $('#directions').hide();
                $('#BC_checker').hide();
               $('#basecasebutton').prop('value','to Base-case');
   //             $('#checkerbutton').prop('value','to QR code'); 
        }


/*


   
      
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
    
  */ 
    
        var qr_code = false;
       var  bc_display = false;
        // get rid of the basecase for now latter may bring it back later

      $('#basecasebutton').hide();


      $('#basecasebutton').click(function(){

        if(bc_display == false){bc_display =true;}else{bc_display =false;}
            if(bc_display && qr_code){
                    $("#problem").hide(); 
                    $("#base_case").show();                        
                    $("#examchecker").hide();
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
                    $("#examchecker").hide();
                    $("#BC_checker").hide();
                    $('#qrcode_id').show();
                    $('#qrcode_id_bc').hide();
                      $('#reflections').show();
                    $('#basecasebutton').prop('value','to Base-case');
            }else if(bc_display && !qr_code){
                    $("#problem").hide(); 
                    $("#base_case").show();                        
                    $("#examchecker").hide();
                    $("#BC_checker").show();
                    $('#qrcode_id').hide();
                    $('#qrcode_id_bc').hide();
                     $('#reflections').hide();
                    $('#reflectionsbutton').hide();
                     $('#basecasebutton').prop('value','to Problem');
            } else {
                    $("#problem").show(); 
                    $("#base_case").hide();                        
                    $("#examchecker").show();
                    $("#BC_checker").hide();
                    $('#qrcode_id').hide();
                    $('#qrcode_id_bc').hide();
                    $('#reflections').show();
                     $('#basecasebutton').prop('value','to Base-case');
            }

        
     });
   /*
      
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