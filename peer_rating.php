<?php
session_start();
require_once "pdo.php";
require_once "simple_html_dom.php";



if(isset($_POST['student_id'])){
  //  echo(' student_id '.$_POST['student_id']);
    $student_id = $_POST['student_id'];
} else {
   // echo(' no student_id ');
   
}
$student_id = 7; //------------------------------------------------------------------------------------------------
if(isset($_POST['peer_num'])){
  // echo(' peer_num '.$_POST['peer_num']);
     $peer_num = $_POST['peer_num'];
} else {
  // echo(' no peer_num ');
}
if(isset($_POST['assign_num'])){
  //  echo(' assign_num '.$_POST['assign_num']);
     $assign_num = $_POST['assign_num'];
} else {
  //  echo(' no assign_num ');
}


if(isset($_POST['cclass_id'])){
 //   echo(' cclass_id '.$_POST['cclass_id']);
      $currentclass_id = $_POST['cclass_id'];
} else {
 //   echo(' no cclass_id ');
}
// get what we are trying to find
$arr = explode(')', $peer_num);
//var_dump($arr);
$alias_num = trim($arr[0]);
// echo(' alias_num '.$alias_num);
$refl_type = trim($arr[1]);
$reflection_type = $refl_type.'_text';
$reflection_review_count = trim($arr[1]).'_review_count';  // the catagory name of the reflection review count
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
           
         // add 1 to all the values that were selected and write the selections to a rating table
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
                    echo('<br>');
                    $j = $i+1;
                    $reflections[$i] = $activity_datum[$reflection_type];
                    
                   echo(' reflection from student '.$j.' : '. $reflections[$i]); 
                    $i++;
         }
 
  }  else {
      
       
   // echo ' have rating_data need to put some code in to either exit or let the students update the rating';
         // get the reflections that they have already been assigned
         
         $i=0;
        foreach ($rating_data as $rating_datum){ 
                $activity_id = $rating_datum['activity_id'];
               // echo (' activity_id '.$activity_id);
               // echo (' reflection_type '.$reflection_type);
                 
                $sql = 'SELECT '.$reflection_type.' FROM Activity WHERE activity_id = :activity_id';
                $stmt = $pdo->prepare($sql);
                 $stmt->execute(array(
                     ':activity_id' => $activity_id,
                 ));
               $activity_datum = $stmt -> fetch();
               echo('<br>');
                $j = $i+1;
                $reflections[$i] = $activity_datum[$reflection_type];
              //  $problem_ids[$i] = $activity_datum['problem_id'];
               echo(' reflection from student '.$j.' : '. $reflections[$i]); 
               
                echo('<br>');
               //   echo(' problem_id '.$j.' : '. $problem_ids[$i]); 
                  
                $i++;
        } 
  }

         $sql = 'SELECT `problem_id` FROM Activity WHERE activity_id = :activity_id';
                $stmt = $pdo->prepare($sql);
                 $stmt->execute(array(
                     ':activity_id' => $activity_id,
                 ));
               $activity_datum = $stmt -> fetch();
                $problem_id = $activity_datum['problem_id'];
               echo(' problem_id: '. $problem_id); 
               
               
// now start building the problem statement much of this copied from QRdisplayPblm.php
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
           // $vari[$i] = $row['v_'.($i+1)];
            $BC_vari[$i] = $BC_row['v_'.($i+1)];
            $pattern[$i]= '/##'.$nvar[$i].'.+?##/';
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





?>







