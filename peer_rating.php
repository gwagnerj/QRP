<?php
session_start();
require_once "pdo.php";
require_once "simple_html_dom.php";




// get studetn id of the rator
if(isset($_POST['student_id'])){
    $student_id = $_POST['student_id'];
} elseif(isset($_GET['student_id'])){
     $student_id = $_GET['student_id'];
} else {
    $_SESSION['error'] = 'no student_id in peer_rating file';
  header('Location: stu_frontpage.php'); 
  return;
}

if(isset($_POST['peer_num'])){
     $peer_num = $_POST['peer_num'];
}  elseif(isset($_GET['peer_num'])){
     $peer_num = $_GET['peer_num'];
     
} else {
   $_SESSION['error'] = 'no peer_num in peer_rating file';
 header('Location: stu_frontpage.php'); 
  return;
}

 //    echo (' peer_num: '.$peer_num);


if(isset($_POST['assign_num'])){
 
     $assign_num = $_POST['assign_num'];
}  elseif(isset($_GET['assign_num'])){
     $assign_num = $_GET['assign_num'];
} else {
   $_SESSION['error'] = 'no assign_num in peer_rating file';
 header('Location: stu_frontpage.php'); 
  return;
}

if(isset($_POST['cclass_id'])){
 //   echo(' cclass_id '.$_POST['cclass_id']);
      $currentclass_id = $_POST['cclass_id'];
}  elseif(isset($_GET['currentclass_id'])){
     $currentclass_id = $_GET['currentclass_id'];
} else {
   $_SESSION['error'] = 'no currentclass_id in peer_rating file';
 header('Location: stu_frontpage.php'); 
  return;
}
$rated_flag = 0;
if(isset($_GET['rated_flag'])) {$rated_flag =$_GET['rated_flag'];}
$ranked_flag = 0;
if(isset($_GET['ranked_flag'])) {$ranked_flag = $_GET['ranked_flag'];}

// get what we are trying to find
$arr = explode(')', $peer_num);
//var_dump($arr);
$alias_num = trim($arr[0]);
// echo(' alias_num '.$alias_num);
$refl_type = trim($arr[1]);
$reflection_type = $refl_type.'_text';
$reflection_review_count = trim($arr[1]).'_review_count';  // the catagory name of the reflection review count
//echo(' reflection_review_count '.$reflection_review_count);
//echo(' refl_type '.$refl_type);
// now get the assign_id 

$sql = 'SELECT `assign_id` FROM Assign WHERE `assign_num` = :assign_num AND currentclass_id = :currentclass_id AND alias_num = :alias_num';
$stmt = $pdo->prepare($sql);
 $stmt->execute(array(
    ':assign_num' => $assign_num,
    ':currentclass_id' => $currentclass_id,
    ':alias_num' => $alias_num,
 ));
 
  $assign_data = $stmt -> fetch();
  $assign_id = $assign_data['assign_id'];
  
 //echo(' assign_id '.$assign_id);

// get how many students are ranking 
$sql = 'SELECT `peer_refl_n` FROM Assigntime WHERE `assign_num` = :assign_num AND currentclass_id = :currentclass_id';
$stmt = $pdo->prepare($sql);
 $stmt->execute(array(
    ':assign_num' => $assign_num,
    ':currentclass_id' => $currentclass_id,
 ));
 
  $assigntime_data = $stmt -> fetch();
  $peer_refl_n = $assigntime_data['peer_refl_n'];

// echo(' peer_refl_n '.$peer_refl_n);
 
// need to check the rating table to see if the student rator has already started a rating --------------------------------------------------

$reflections = array();
$sql = 'SELECT * FROM Rating WHERE assign_id = :assign_id AND refl_type = :refl_type AND  rator_student_id = :rator_student_id';
$stmt = $pdo->prepare($sql);
 $stmt->execute(array(
     ':assign_id' => $assign_id,
    ':refl_type' => $refl_type,
     ':rator_student_id' => $student_id,
 ));
  $rating_data = $stmt -> fetchALL();
  
  if ($rating_data == false){
    //echo ' dont have rating data ';
 
  // make an insert in the rating table for this rator and all of the ratees


        // ----------------------------------------------------------------------------------------------------------------------------------
        //$student_id = 1;
        $sql = 'SELECT '.$reflection_type.',`activity_id`, `student_id`,`problem_id` 
                FROM Activity WHERE `student_id`!=:student_id AND `currentclass_id` = :currentclass_id AND assign_id = :assign_id AND alias_num = :alias_num 
                ORDER BY '.$reflection_review_count.' DESC, RAND() LIMIT 0,'.$peer_refl_n.' ';
        $stmt = $pdo->prepare($sql);
         $stmt->execute(array(
             ':assign_id' => $assign_id,
            ':currentclass_id' => $currentclass_id,
            ':alias_num' => $alias_num,
             ':student_id' => $student_id,
         ));
          $activity_data = $stmt -> fetchALL();
           
         // problably should do this on submit of the rating   -  add 1 to all the values that were selected and 
         
         //write the selections to a rating table
         $i = 0;
         foreach($activity_data as $activity_datum){
            $sql = 'UPDATE Activity 
                    SET '.$reflection_review_count.' = '.$reflection_review_count.' + 1 
                    WHERE activity_id = :activity_id';
             $stmt = $pdo->prepare($sql);
              $stmt->execute(array(
             ':activity_id' => $activity_datum['activity_id'],
                ));
             
             $sql = 'INSERT INTO Rating (activity_id, assign_id, refl_type, rator_student_id, ratee_student_id,ranking_out_of )
                    VALUES (:activity_id,:assign_id,:refl_type, :rator_student_id,:ratee_student_id,:ranking_out_of)';
                  $stmt = $pdo->prepare($sql);  
                  $stmt->execute(array(
                 ':activity_id' => $activity_datum['activity_id'],
                   ':assign_id' => $assign_id,
                   ':refl_type' => $refl_type,
                  ':rator_student_id' => $student_id,
                  ':ratee_student_id' => $activity_datum['student_id'],
                  ':ranking_out_of' => $peer_refl_n,
                 
                    ));
                  //  echo('<br>');
                    $j = $i+1;
                    $reflections[$i] = $activity_datum[$reflection_type];
                    $rating_ids [$i] = $pdo->lastInsertId();
                    
                  // echo(' reflection from student '.$j.' : '. $reflections[$i]); 
                  //  echo(' rating_ids '.$j.' : '. $rating_ids[$i]); 
                    $i++;
         }
 
  }  else {
      
       
   // echo ' have rating_data need to put some code in to either exit or let the students update the rating';
         // get the reflections that they have already been assigned
         
         $i=0;
        foreach ($rating_data as $rating_datum){ 
                $activity_id = $rating_datum['activity_id'];
                $rating_ids[$i]=$rating_datum['rating_id'];
              
               // echo (' activity_id '.$activity_id);
               // echo (' reflection_type '.$reflection_type);
                 
                $sql = 'SELECT '.$reflection_type.' FROM Activity WHERE activity_id = :activity_id';
                $stmt = $pdo->prepare($sql);
                 $stmt->execute(array(
                     ':activity_id' => $activity_id,
                 ));
               $activity_datum = $stmt -> fetch();
            //   echo('<br>');
                $j = $i+1;
                $reflections[$i] = $activity_datum[$reflection_type];
              //  $problem_ids[$i] = $activity_datum['problem_id'];
          //     echo(' reflection from student '.$j.' : '. $reflections[$i]); 
               
            //    echo('<br>');
               //   echo(' problem_id '.$j.' : '. $problem_ids[$i]); 
                  
                $i++;
        } 
  }
// get problem number so we know which one to display
         $sql = 'SELECT `prob_num` FROM Assign WHERE assign_id = :assign_id';
                $stmt = $pdo->prepare($sql);
                 $stmt->execute(array(
                     ':assign_id' => $assign_id,
                 ));
               $assign_data = $stmt -> fetch();
                $problem_id = $assign_data['prob_num'];
              // echo(' problem_id: '. $problem_id); 
               
               
// now start building the problem statement much of this copied from QRdisplayPblm.php
 $sql = "SELECT * FROM Assign WHERE assign_id = :assign_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':assign_id' => $assign_id));
	$assign_data = $stmt -> fetch();
 
  $sql = "SELECT * FROM CurrentClass WHERE currentclass_id = :currentclass_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':currentclass_id' => $currentclass_id));
	$currentclass_data = $stmt -> fetch();
 $class_name = $currentclass_data['name'];
    
 
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

  // read in the input varaibles for the basecase
     $stmt = $pdo->prepare("SELECT * FROM Input where problem_id = :problem_id AND dex = :dex");
	$stmt->execute(array(":problem_id" => $problem_id, ":dex" => 1));
	$BC_row = $stmt->fetch();
    
    
   

 // Read in the value for the input variables
   
    for ($i = 0; $i <= $nv; $i++) {
        if($BC_row['v_'.($i+1)]!='Null' ){
            $vari[$i] = $BC_row['v_'.($i+1)];
            $BC_vari[$i] = $BC_row['v_'.($i+1)];
            $pattern[$i]= '/##'.$nvar[$i].',.+?##/';
        }
    }
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

</head>

<body>
<?php  
 //  I'm using reading from the $html and buiding the file $this_html.  I had to build it in two parts because of putting the 
 //i-frame for the checker in the middle of the document
 
 
 	  $html = new simple_html_dom();
      $html->load_file($htmlfilenm);  
     $rubric_stuff = new simple_html_dom();
     $rubric_stuff -> load_file('rubric_stuff.html');
      $rubric_stuff ->find('#course',0)->innertext = $class_name;
       $rubric_stuff ->find('#assignment_num',0)->innertext = $assign_num;
       $rubric_stuff ->find('#problem_num',0)->innertext = $alias_num;



   echo($rubric_stuff);
    $base_case = $html->find('#problem',0); 
    $reflection_text = $html->find('#'.$refl_type,0); 
    // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced

      for( $i=0;$i<$nv;$i++){
            $base_case = preg_replace($pattern[$i],$BC_vari[$i],$base_case);
        }
       
        
     // add some markup to specific to the basecase since I just created it from the problem   
       $base_case = preg_replace('/<div id="problem">/','<div id="BC_problem">',$base_case);
         $base_case = preg_replace('/<div id="questions">/','<div id="BC_questions">',$base_case);
         
         foreach(range('a' , 'j') as $m){
             $let_pattern = 'part'.$m;
              $base_case = preg_replace('/<div id="'.$let_pattern.'">/','<div id="BC_'.$let_pattern.'">',$base_case);
             
         }
         
            
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
       $this_html =' <div id = "base_case"><h2>Base Case Problem is given for your reference:</h2>'.$base_case.'</div>';
 /* 
   // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
            $this_html = preg_replace($pattern[$i],$vari[$i],$this_html);
        }
         */
        echo $this_html;
       //   echo ('something');
        echo $reflection_text;
        
        
        echo ('<hr>');
             echo '<div id = "rating_section">';
        echo ('<hr>');
  
         echo('<h3> Rating Section:</h3>');
          echo('<h4> Please rate the responses.  Select the directions button above for a full description of the Rubric </h4>');
       echo('<form id = "ratings_form" action = "submit_ratings.php" method = "POST" name = "ratings_form>"');
 
      $i = 0;
      foreach($reflections as $reflection){
          $j = $i+1;
          
          echo('Response for student'.$j.': '.$reflection.'<br>');
        // move these to external source  
         $rating_form = '<br>
                  <label for="rating">&nbsp;&nbsp;Please Pick the Best Descriptor the Response of Student '.$j.':</label>
                  <select  required name="student_'.$j.'" id="student_'.$j.'">
                      <option value = "">Please Select</option>
                    <option value="1">Multiple and relavent connections were made</option>
                    <option value="2">At least one relavent clear connection was made</option>
                    <option value="3">Connection(s) was a bit vague</option>
                    <option value="4">Unclear what was trying to be connected</option>
                  </select>';
           $rating_form = $rating_form.'<input type="hidden" name = "rating_id'.$j.'" value = "'.$rating_ids[$i].'">';

         echo($rating_form);
     //   $ratee_student_id = $rating_ids[$i];
     
        
    //    echo '<input type="hidden" name = "ratee_student_id_'.$j.'" value ="'.$ratee_student_id.'">';
            // other hidden input stuff can go in the form above to make the submit work
              echo ('<hr>');

          $i++;
      }
      $n = $i;  // total number of responses
      
      echo '<input type="hidden" name = "n" value = "'.$n.'">';
      echo '<input type="hidden" name = "rator_student_id" value = "'.$student_id.'">';
      echo '<input type="hidden" name = "peer_num" value = "'.$peer_num.'">';
      echo '<input type="hidden" name = "assign_num" value = "'.$assign_num.'">';
      echo '<input type="hidden" name = "currentclass_id" value = "'.$currentclass_id.'">';

      echo ('&nbsp;&nbsp;<input style="background-color:yellow;" type="submit" name = "rating_submit" value="Submit Ratings">');
      echo ('</form>');
      echo '</div>';
     // start ranking section 
         echo '<div id = "ranking_section">';
      $i = 1;
   
      echo ('<hr>');
      echo('<h3> Ranking Section:</h3>');
        echo('<h4> Please rank the responses from 1 = Best to '.$n.' = Worst </h4>');
        
       //echo('<form id = "ranking" method = "POST">');
        $opt = '<form id="ranking_form" action = "submit_ratings.php" method="POST">';
        foreach($reflections as $reflection){
           $k = 1;
            $m = $i-1;
         $opt = $opt.'<input type="hidden" name = "rating_id'.$i.'" value = "'.$rating_ids[$m].'">';
         $opt = $opt.'&nbsp;Student '.$i.':<select name = "student_'.$i.'" id="option'.$i.'" required><option value = "">';
                     foreach($reflections as $reflection){
                         $opt = $opt.'<option value = "'.$k.'">'.$k;
                        $k++;
                     }
                     $opt = $opt.'</select>';
                     $i++;
        }
         echo($opt);
         
      echo ('&nbsp;&nbsp;<button style="background-color:yellow;" class="submit" name="ranking_submit" >Submit Ranking</button>');
      echo '<input type="hidden" name = "n" value = "'.$n.'">';
      echo '<input type="hidden" name = "rator_student_id" value = "'.$student_id.'">';
      echo '<input type="hidden" name = "peer_num" value = "'.$peer_num.'">';
      echo '<input type="hidden" name = "assign_num" value = "'.$assign_num.'">';
      echo '<input type="hidden" name = "currentclass_id" value = "'.$currentclass_id.'">';
     
?>
&nbsp;&nbsp;<button type = "reset" class="button3" name="rankings_reset" >Reset Rankings</button>


             <input type="hidden"  id = "activity_id" name="activity_id" value="<?php echo ($activity_id);?>" >
             <input type="hidden"  id = "rated_flag" name="rated_flag" value="<?php echo ($rated_flag);?>" >
             <input type="hidden"  id = "ranked_flag" name="ranked_flag" value="<?php echo ($ranked_flag);?>" >

</form>

<br><br>
</div>


<!--
<form id="ranking_form" action="result.php" method="post" > 
    &nbsp;Student 1:<select id='option1' required><option value=''><option value='1'>1<option value='2'>2<option value='3'>3<option value='4'>4<option value='5'>5</select>
    &nbsp;&nbsp;Student 2:<select id='option2' required><option value=''><option value='1'>1<option value='2'>2<option value='3'>3<option value='4'>4<option value='5'>5</select>
    &nbsp;&nbsp;Job C<select id='option3' required><option value=''><option value='1'>1<option value='2'>2<option value='3'>3<option value='4'>4<option value='5'>5</select>
    &nbsp;&nbsp;Job D:<select id='option4' required><option value=''><option value='1'>1<option value='2'>2<option value='3'>3<option value='4'>4<option value='5'>5</select>
    &nbsp;&nbsp;Job E:<select id='option5' required><option value=''><option value='1'>1<option value='2'>2<option value='3'>3<option value='4'>4<option value='5'>5</select> 
    <button class="button3" name="click" >Submit</button>
    </form>
-->
<script>

// this makes it so ratings are mutually exclusive

$("#ranking_form").find("select").each(function(){
        $(this).change(function(){ //if any select input is changed
            var changedId = $(this).attr('id'); //store the changed id
            var valSelected = $(this).val(); //store the selected value
            console.log(' valSelected '+valSelected);

            $("#ranking_form").find("select").each(function(){ // loop through ranking_form again 
                var loopId = $(this).attr('id');
                if(loopId!==changedId){ //every select in put, but selected
                    $(this).find('option').each(function(){ //loop through every option
                        if($(this).val()==valSelected){ // make the selected option disabled
                            $(this).attr('disabled',true);
                        }
                    });
                }
            });
        });
    });





// take care of buttons

      var activity_id = $('#activity_id').val(); 
            $("#backbutton").click(function(){
                     window.location.replace('stu_frontpage.php?activity_id='+activity_id); // would like to put some parameters here instead of relying on session (like below)
              	
				 });
 

 
var rated_flag = $('#rated_flag').val();
var ranked_flag = $('#ranked_flag').val();
console.log(' rated_flag '+rated_flag);
console.log(' ranked_flag '+ranked_flag);

if (rated_flag == 1){
    $('#rating_section').hide();
} else {
     $('#rating_section').show();
}

if (ranked_flag == 1){
    $('#ranking_section').hide();
} else {
     $('#ranking_section').show();
}
    
/* 
$('#rating_reset').click(function(){
    //document.getElementById("ranking_form").reset();
    location.reload(true);
    
});
 */

</script>



