<?php
session_start();
require_once "pdo.php";
require_once "simple_html_dom.php";
// problem just displays the problem without the checker so users can preview what the student is seeing - copied from bc_preview
if (isset($_POST['activity_id'])){
    $activity_id = $_POST['activity_id'];
} elseif(isset($_GET['activity_id'])){
     $activity_id = $_GET['activity_id'];
}  else {
   $_SESSION['error'] = 'no activity id in activity_preview';
}

 
 $sql = "SELECT * FROM Activity WHERE activity_id = :activity_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':activity_id' => $activity_id));
	$activity_data = $stmt -> fetch();
    
    $problem_id = $activity_data["problem_id"];
    $dex = $activity_data["dex"];
    
    
echo (' <h3>'.$activity_data["stu_name"].' activity on problem '.$activity_data["alias_num"].' &nbsp; &nbsp; problem_id '.$problem_id.' dex - '.$dex.'</h3>');
//echo (' <p> activity_id: '.$activity_id.'</p>');
//echo (' <p> Problem '.$activity_data["alias_num"].'</p>');

//echo (' <h3> Problem_id '.$problem_id.'-'.$dex.'</h3>');

echo (' Numeric Score on Problem Before Deductions: '.$activity_data['p_num_score_raw']);
echo '<br>';
echo (' Late Penalty: '.$activity_data['late_penalty']);
echo '<br>';echo (' Survey pts: '.$activity_data['survey_pts']);

echo '<br>';echo (' Numeric Score Net: '.$activity_data['p_num_score_net']);
echo '<br>';
echo '<br>';
echo '<hr> <h2> Activity on Problem</h2>';

  // get the answers and report them
     $sql = "SELECT * FROM Qa WHERE problem_id = :problem_id AND dex = :dex";
         $stmt = $pdo->prepare($sql);
         $stmt->execute(array(":problem_id" => $problem_id,
         ":dex" => $dex ));
         $row_ss = $stmt->fetch(PDO::FETCH_ASSOC); 
       
       echo ('<p>Answers</p>');
        foreach(range('a','j') as $v){
            $col = 'ans_'.$v;
             if($row_ss[$col]< 1.1e43){ echo($v.') '.$row_ss[$col].'<br>');}
        }
  echo ('<p>'.$activity_data["stu_name"].'\'s Responses:</p>');          
               

foreach(range('a','j') as $v){
    //echo "$v \n";
 
    $wc_field = 'wcount_'.$v;
    $correct_field = 'correct_'.$v;
    if($activity_data[$wc_field] != null ){
       if ($activity_data[$wc_field]==0 && $activity_data[$correct_field] != 1 ){
             echo ('&nbsp;&nbsp;&nbsp;'.$v.': not attempted <br>');  
       } else {
             
             $sql = 'SELECT * FROM Resp WHERE activity_id = :activity_id AND part_name = :part_name  ORDER by resp_id ASC';     
              $stmt = $pdo->prepare($sql);
               $stmt -> execute(array (
               ':activity_id' => $activity_id,
               ':part_name' => $v,
               )); 
              $resp_data = $stmt -> fetchALL();
           //  var_dump( $resp_data);
            if ($activity_data[$correct_field] ==0){
                echo ('&nbsp;&nbsp;&nbsp;'.$v.': incorrect with '.$activity_data[$wc_field].' tries: &nbsp;');
                foreach($resp_data as $resp_datum){
                    echo('&nbsp;&nbsp;&nbsp;'.$resp_datum["resp_value"].'&nbsp;at&nbsp;'.date("M j, g:i a",strtotime($resp_datum["created_at"])));
                }
               
            } else {
               $num_tries = $activity_data[$wc_field] +1;
           echo ('&nbsp;&nbsp;&nbsp; '.$v.': correct with '.$num_tries.' tries: &nbsp;');  
            foreach($resp_data as $resp_datum){
                    echo('&nbsp;&nbsp;&nbsp;'.$resp_datum["resp_value"].'&nbsp;at&nbsp;'.date("M j, g:i a",strtotime($resp_datum["created_at"])));
                }
        }
      }
      echo'<br>';
   }
    
}


echo ('<hr><h2>'.$activity_data["stu_name"].'\'s Problem Statement</h2>');

 $sql = "SELECT * FROM Problem WHERE problem_id = :problem_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':problem_id' => $problem_id));
	$pblm_data = $stmt -> fetch();
    $contrib_id = $pblm_data['users_id'];
    $nm_author = $pblm_data['nm_author'];
    $specif_ref = $pblm_data['specif_ref'];
    $htmlfilenm = $pblm_data['htmlfilenm'];
    $solnfilenm = $pblm_data['soln_pblm'];

  $htmlfilenm = "uploads/".$htmlfilenm;
  $solnfilenm = "uploads/".$solnfilenm;

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
	$stmt->execute(array(":problem_id" => $problem_id, ":dex" => $dex));
	$problem_row = $stmt->fetch();  
    

 // Read in the value for the input variables
   
    for ($i = 0; $i <= $nv; $i++) {
        if($problem_row['v_'.($i+1)]!='Null' ){
            $vari[$i] = $problem_row['v_'.($i+1)];
            $BC_vari[$i] = $problem_row['v_'.($i+1)];
            $pattern[$i]= '/##'.$nvar[$i].',.+?##/';
        }
    }

  
?>

<!DOCTYPE html >
<html lang = "en">
<head>
<meta charset="UTF-8">
<title><?php echo ($activity_data['stu_name'].'-P'.$activity_data['alias_num']); ?></title>
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

     $html = new simple_html_dom();
      $html->load_file($htmlfilenm);  

    $problem_statement = $html->find('#problem',0); 
    $reflection_text = $html->find('#reflections',0); 
    // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced

      for( $i=0;$i<$nv;$i++){
            $problem_statement = preg_replace($pattern[$i],$BC_vari[$i],$problem_statement);
        }
       
        
     // add some markup to specific to the basecase since I just created it from the problem   
       $problem_statement = preg_replace('/<div id="problem">/','<div id="BC_problem">',$problem_statement);
         $problem_statement = preg_replace('/<div id="questions">/','<div id="BC_questions">',$problem_statement);
         
         foreach(range('a' , 'j') as $m){
             $let_pattern = 'part'.$m;
              $problem_statement = preg_replace('/<div id="'.$let_pattern.'">/','<div id="BC_'.$let_pattern.'">',$problem_statement);
             
         }
         
            
         // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
            $problem_statement = preg_replace($pattern[$i],$vari[$i],$problem_statement);
        }
         
          $dom = new DOMDocument();
   libxml_use_internal_errors(true); // this gets rid of the warning that the p tag isn't closed explicitly
       $dom->loadHTML('<?xml encoding="utf-8" ?>' . $problem_statement);
       $images = $dom->getElementsByTagName('img');
        foreach ($images as $image) {
            $src = $image->getAttribute('src');
             $src = 'uploads/'.$src;
             $src = urldecode($src);
             $type = pathinfo($src, PATHINFO_EXTENSION);
             $base64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($src));
             $image->setAttribute("src", $base64); 
             $problem_statement = $dom->saveHTML();
       }
       
       // turn base-case back into and simple_html_dom object that I can replace the varaible images on 
       
        if(str_get_html($problem_statement) != false){
               $problem_statement =str_get_html($problem_statement); 
               $keep = 0;
               $varImages = $problem_statement -> find('.var_image');
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
       $this_html =' <div id = "problem_statement"><h3> Problem '.$problem_id.' - '.$dex.'</h3>'.$problem_statement.'</div>';
 
        echo $this_html;
          echo '<hr>';
        
        
      
               
               
               
             
        echo '<hr>';
      //  echo $reflection_text;
        
  
echo '<h3>Activity on Base Case</h3>';
echo '<br>';

foreach(range('a','j') as $v){
    //echo "$v \n";
    $field = 'bc_correct_'.$v;
    $wc_field = 'wcount_bc_'.$v;
      if($activity_data[$wc_field] != null ){
       if ($activity_data[$wc_field]==0 && $activity_data[$field] !=1 ){
             echo ('&nbsp;&nbsp;&nbsp;'.$v.': not attempted <br>');  
       } else {
             
             $sql = 'SELECT * FROM Bc_resp WHERE activity_id = :activity_id AND part_name = :part_name  ORDER by bc_resp_id ASC';     
              $stmt = $pdo->prepare($sql);
               $stmt -> execute(array (
               ':activity_id' => $activity_id,
               ':part_name' => $v,
               )); 
              $bc_resp_data = $stmt -> fetchALL();
           //  var_dump( $bc_resp_data);
            if ($activity_data[$field] ==0){
                echo ('&nbsp;&nbsp;&nbsp;'.$v.': incorrect with '.$activity_data[$wc_field].' tries: &nbsp;');
                foreach($bc_resp_data as $bc_resp_datum){
                    echo('&nbsp;&nbsp;&nbsp;'.$bc_resp_datum["resp_value"].'&nbsp;at&nbsp;'.date("M j, g:i a",strtotime($bc_resp_datum["created_at"])));
                }
               
            } else {
           echo ('&nbsp;&nbsp;&nbsp; '.$v.': correct with '.$activity_data[$wc_field].' tries: &nbsp;');  
            foreach($bc_resp_data as $bc_resp_datum){
                    echo('&nbsp;&nbsp;&nbsp;'.$bc_resp_datum["resp_value"].'&nbsp;at&nbsp;'.date("M j, g:i a",strtotime($bc_resp_datum["created_at"])));
                }
        }
      }
      echo'<br>';
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

 	  $html = new simple_html_dom();
      $html->load_file($htmlfilenm);  
  
   
    $base_case = $html->find('#problem',0); 
    $reflection_text = $html->find('#reflections',0); 
    // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced


      for( $i=0;$i<$nv;$i++){
          if($BC_row['v_'.($i+1)]!='Null' ){
            $base_case = preg_replace($pattern[$i],$BC_vari[$i],$base_case);
            $reflection_text = preg_replace($pattern[$i],$BC_vari[$i],$reflection_text);
          }
        }


      for( $i=0;$i<$nv;$i++){
          if($BC_row['v_'.($i+1)]!='Null' ){
            $base_case = preg_replace($pattern[$i],$BC_vari[$i],$base_case);
          }
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
           if($BC_row['v_'.($i+1)]!='Null' ){
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
       
             $dom = new DOMDocument();
   libxml_use_internal_errors(true); // this gets rid of the warning that the p tag isn't closed explicitly
       $dom->loadHTML('<?xml encoding="utf-8" ?>' . $reflection_text);
       $images = $dom->getElementsByTagName('img');
        foreach ($images as $image) {
            $src = $image->getAttribute('src');
             $src = 'uploads/'.$src;
             $src = urldecode($src);
             $type = pathinfo($src, PATHINFO_EXTENSION);
             $base64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($src));
             $image->setAttribute("src", $base64); 
             $reflection_text = $dom->saveHTML();
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
       $this_html =' <div id = "base_case"><h2>Base Case Problem '.$problem_id.'.</h2>'.$base_case.'</div>';
 /* 
   // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
            $this_html = preg_replace($pattern[$i],$vari[$i],$this_html);
        }
         */
        echo $this_html;
        echo '<hr>';
        // put in the pdf for the solution to the basecase
        echo '<h3>Solution to Base Case</h3>';
        echo ('<iframe src="'.$solnfilenm.'" width="90%" height = "50%" ></iframe>');
        // <iframe src="http://ipaddress/file.pdf" width="1000" height="1000"></iframe>
       
          echo '<hr>';
          echo '<h3>Reflections for Problem</h3>';
   
          
        echo $reflection_text;
        

?>





</body>
</html>




