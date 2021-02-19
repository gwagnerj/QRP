<?php
 //header( "content-type: application/xml; charset=ISO-utf-8" );
 // Set the content type to be XML, so that the browser will   recognise it as XML.

require_once "pdo.php";
require_once "simple_html_dom.php";
session_start();


echo ('	<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>');

// This is the make a Moodle XML file of the problem

$problem_id= '';


// this next function modified from stack overflow https://stackoverflow.com/questions/5696412/how-to-get-a-substring-between-two-strings-in-php

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    if (strpos($string, $end)!=false){
        $len = strpos($string, $end, $ini) - $ini;
    } else {$len = strlen($string)-$ini;}
    return substr($string, $ini, $len);
}

if(isset($_POST['problem_id'])){
	$problem_id = htmlentities($_POST['problem_id']);
    
	$_SESSION['problem_id']=$problem_id;
} else {

	$_SESSION['error'] = 'problem id was not set';
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
	
}

if(isset($_POST['iid'])){
	$iid = htmlentities($_POST['iid']);
	$_SESSION['iid']=$iid;
} else {

	$_SESSION['error'] = 'user_id iid was not set';
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
}


 $sql = "SELECT * FROM Problem WHERE problem_id = :problem_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':problem_id' => $problem_id));
	$pblm_data = $stmt -> fetch();

// read in the names of the variables for the problem
    $nv = 0;  // number of non-null variables
   for ($i = 0; $i <= 13; $i++) {
    
        if($pblm_data['nv_'.($i+1)]!='Null' ){
            $nvar[$i]=$pblm_data['nv_'.($i+1)];
            $nv++;
            $pattern_for_var_image[$i] = '/##'.$nvar[$i].',img##/';
            $pattern_for_var_img_sub[$i] = 's__'.$nvar[$i].',img__s';
            $pattern_for_var_img_sub2[$i] = 'x__'.$nvar[$i].',img__x';
      
            $pattern[$i]= '/##'.$nvar[$i].',.*?##/';
         }
   }
   //echo $nv;
   
   //read tolerances into array
   $i = 0;
    for ($m = 'a'; $m<='j'; $m++){
        $tol[$i] = $pblm_data['tol_'.($m)];
        $tol_type[$i] = $pblm_data['tol_'.($m).'_type'];  // this will be 0 for relative and 1 for absolute
        $i++;
    }
    
    $xml_file_name = substr($pblm_data['htmlfilenm'], 0, strrpos($pblm_data['htmlfilenm'], "."));
    $xml_file_name = $xml_file_name.'.xml';

    $htmlfilenm = "uploads/".$pblm_data['htmlfilenm'];

  //-----------------------------------------------------creating header for HTML file -------------------------------

        // "Create" the document.
      
        $xml = new DOMDocument();
                $xml->encoding = 'utf-8';
                $xml->xmlVersion = '1.0';
                $xml->formatOutput = true;

        // Create some elements.
       
       $xml_quiz = $xml->createElement( "quiz" );
            $xml_question = $xml->createElement( "question");

            // Set the attributes.
            $xml_question->setAttribute( "type", "category" );
            $xml_category = $xml->createElement( "category"); 
            $xml_question->appendChild( $xml_category );
             $cat_name = '\$course\$/top/CategoryName/'.$pblm_data['title'];
            
            
          // $xml_text = $xml->createElement( "text","\$course\$/top/CategoryName"); 
              $xml_text = $xml->createElement( "text",$cat_name); 
             $xml_category->appendChild( $xml_text );
            
            
            $xml_info = $xml->createElement( "info");
              $xml_info->setAttribute( "format", "moodle_auto_format" );
            // Append category and info to question.
           
            $xml_question->appendChild( $xml_info );
       
        $xml_quiz->appendChild( $xml_question );
 
        $xml_question = $xml->createElement( "category");

            // Set the attributes.
            $xml_question->setAttribute( "type", "category" );
            $xml_category = $xml->createElement( "category"); 
             $xml_question->appendChild( $xml_category );
           
                      // the catagory name should be the problem title
           
          //  $xml_text = $xml->createElement( "text","\$course\$/top/CategoryName/Testing XML up1"); 
             $xml_text = $xml->createElement( "text", $cat_name); 
             $xml_category->appendChild( $xml_text );
            
            $xml_info = $xml->createElement( "info");
              $xml_info->setAttribute( "format", "html" );
            // Append category and info to question.
           
            $xml_question->appendChild( $xml_info );

        $xml_quiz->appendChild( $xml_question );
 
   
   //--------------------------------big Loop---------------------------------------------------------------------------------------------------
  for($dex=111; $dex<=160; $dex++){
      
    $stmt = $pdo->prepare("SELECT * FROM Input where problem_id = :problem_id AND dex = :dex");
	$stmt->execute(array(":problem_id" => $_POST['problem_id'], ":dex" => $dex));
	$row = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT * FROM Qa where problem_id = :problem_id AND dex = :dex");
	$stmt->execute(array(":problem_id" => $_POST['problem_id'], ":dex" => $dex));
	$row_ans = $stmt->fetch();


//------------------------------------Get index specific data ---------------------------- 
   // Read in the value for the input variables
   
    for ($i = 0; $i <= $nv; $i++) {
    
        if($row['v_'.($i+1)]!='Null' ){
            $vari[$i] = $row['v_'.($i+1)];
        }
    }
   
  // set the answer and margin of error into a ans_pattern
   $last_part = 0;
   $i = 0;
   for ($m = 'a'; $m<='j'; $m++){
        $ans[$i]= $row_ans['ans_'.$m];
       if($row_ans['ans_'.$m]<1.2e43){
           $last_part = $i+1;
           if($tol_type[$i]==1){
              $MOE[$i]= $tol[$i]/1000000;
           } else {
              $MOE[$i]= $tol[$i]/1000 * $ans[$i];

           }
            $ans_pattern[$i] = "{1:NUMERICAL:=".$ans[$i].":".$MOE[$i]."}";
        }
     $i++;
    }

  //--------------------------------make the xml specific for the problem index------------------------------------------------------------------------------------------      
           
       $xml_question1 = $xml->createElement( "question");

        // Set the attributes.
        $xml_question1->setAttribute( "type", "cloze" );
        $version = 'version_'.$dex;
        $xml_name = $xml->createElement( "name");
        $xml_text = $xml->createElement( "text", $version );
        $xml_name->appendChild( $xml_text );
        $xml_question1->appendChild( $xml_name );
        $xml_comment = $xml->createComment('this is my comment'); 
         $xml_question1->appendChild( $xml_comment );
         $xml_comment = $xml->createComment('version - '.$version); 
         $xml_question1->appendChild( $xml_comment );
         $xml_questiontext = $xml->createElement( "questiontext" );
         $xml_questiontext->setAttribute( "format", "html" );
    
         $html_file = file_get_html($htmlfilenm);  // reads file into a simple html object
      $html_file = $html_file->find('#problem',0);  // get rid of everything but the problem statement and questions
      
         // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
            $html_file = preg_replace($pattern[$i],$vari[$i],$html_file);
        }
        
       $html_file = str_get_html($html_file); 
     
     // this next code replaces each question with the question and the answer and tolerance in the form Moodle expects
          $m='a';
           for( $i=0;$i<$last_part;$i++){
                if($ans[$i]<1.2e43){
                       $part_name = '#part'.$m;
                       $part[$i] = $html_file->find($part_name,0);
                       $part[$i] = $part[$i].'<br>&nbsp;&nbsp;&nbsp;&nbsp;'.$ans_pattern[$i].'<br><br>';
                       $html_file->find($part_name,0)->innertext=$part[$i];
                       // print($part[$i]);
                }
                $m++;
           }
   

    // put the images into the problem statement part of the document     
    $dom = new DOMDocument();
   libxml_use_internal_errors(true); // this gets rid of the warning that the p tag isn't closed explicitly
       $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html_file);
       $images = $dom->getElementsByTagName('img');
        foreach ($images as $image) {
            $src = $image->getAttribute('src');
             $src = 'uploads/'.$src;
             $src = urldecode($src);
             $type = pathinfo($src, PATHINFO_EXTENSION);
             $base64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($src));
             $image->setAttribute("src", $base64); 
             $html_file = $dom->saveHTML();
       }
    
         //  print($html_file); 
            
     /*         
         $htmlfile = new simple_html_dom();
         $htmlfile->load_file($html_file);
          print($htmlfile); 
         
     
     
        
    //  fix later this takes care of variable images and I need to find out what is wrong may need to make new file  
        
        $keep = 0;
        
      */     
      
       //  print($html_file); 
        /*   
               $varImages = $html_file -> find('.var_image');
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
                
        */ 
 
//-------------------------------write more xml-----------------------------------------------------------------------------------------------------------
              
        $xml_text1 = $xml->createElement( "text");
        $xml_questiontext->appendChild( $xml_text1 );
        $xml_text1->appendChild(
                   $xml->createCDATASection($html_file)
                );
        $xml_question1->appendChild( $xml_questiontext );
        $xml_feedback = $xml->createElement( "generalfeedback" );
        $xml_feedback->setAttribute( "format", "html" );
        $xml_question1->appendChild( $xml_feedback );
        $xml_penalty = $xml->createElement( "penalty","0.3333333" );
        $xml_question1->appendChild( $xml_penalty );
        $xml_hidden = $xml->createElement( "hidden","0" );
        $xml_question1->appendChild( $xml_hidden );
        $xml_idnumber = $xml->createElement( "idnumber" );
        $xml_question1->appendChild( $xml_idnumber );

        $xml_hint = $xml->createElement( "hint" );
        $xml_hint ->setAttribute( "format", "html" );
        $xml_text2 = $xml->createElement( "text" );
         $xml_text2 ->appendChild( $xml->createCDATASection("<p> </p>"));   
         $xml_hint->appendChild( $xml_text2);
        
         $xml_hint3 = $xml->createElement( "hint" );
         $xml_hint3 ->setAttribute( "format", "html" );
         $xml_text3 = $xml->createElement( "text" );
         $xml_text3 ->appendChild( $xml->createCDATASection("<p> </p>"));   
         $xml_hint3->appendChild( $xml_text3);

         $xml_hint4 = $xml->createElement( "hint" );
         $xml_hint4 ->setAttribute( "format", "html" );
         $xml_text4 = $xml->createElement( "text" );
         $xml_text4 ->appendChild( $xml->createCDATASection("<p> </p>"));   
         $xml_hint4->appendChild( $xml_text4);
         
         

 //       $xml_hint ->appendChild( $xml->createCDATASection("<p>units?</p>"));
 //       $xml_question1->appendChild( $xml_hint );
    $xml_question1->appendChild( $xml_hint );
    $xml_question1->appendChild( $xml_hint3 );
    $xml_question1->appendChild( $xml_hint4 );

    $xml_quiz->appendChild( $xml_question1 );
        $xml->appendChild( $xml_quiz );
        $xml_document = $xml->saveXML();
      }

      $xml->save('uploads/'.$xml_file_name);
   
    echo('<a href = "uploads/'.$xml_file_name.'?dummy = dummy" download> download file</a>');
    echo('<br> <br><br>After downloading the file, close this browser window');
    
 ?>
