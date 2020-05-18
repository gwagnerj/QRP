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
          //   echo ($pattern_for_var_image[$i]);
          //    echo ($pattern_for_var_img_sub[$i]);
            $pattern[$i]= '/##'.$nvar[$i].'.+?##/';
         // echo ($pattern[$i]);
         }
   }
   //echo $nv;
   
   //read taolerances into array
   $i = 0;
    for ($m = 'a'; $m<='j'; $m++){
        $tol[$i] = $pblm_data['tol_'.($m)];
        $i++;
    }
    
    
    $xml_file_name = substr($pblm_data['htmlfilenm'], 0, strrpos($pblm_data['htmlfilenm'], "."));
    $xml_file_name = $xml_file_name.'.xml';

    $htmlfilenm = "uploads/".$pblm_data['htmlfilenm'];
/* 
    $html_stem = get_string_between(file_get_contents($htmlfilenm),'t==','p==');  
    $htmlQuestions = "p==".get_string_between(file_get_contents($htmlfilenm),'p==','==t'); 
    $html_reflections = get_string_between(file_get_contents($htmlfilenm),'w==','==w'); 
     */
    
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
  for($dex=101; $dex<=150; $dex++){
      
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
           
       //    echo ('dex '.$dex);
       //  echo ('vari '.$vari[$i]);
       //   echo ('pattern for input variable '.$pattern[$i]);
        }
    }
   
  // set the answer and margin of error into a ans_pattern
   $last_part = 0;
   $i = 0;
   for ($m = 'a'; $m<='j'; $m++){
        $ans[$i]= $row_ans['ans_'.$m];
       if($row_ans['ans_'.$m]<1.2e43){
           $last_part = $i+1;
            $MOE[$i]= $tol[$i]/1000 * $ans[$i];
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
        
        
        
        
                $html_file = file_get_contents($htmlfilenm);
              //  print($html_file);  
             
                        



             
                     //(<img)(.+)(img__s)(.+)>\d.
          // $pattern_for_var_image[$i] = '/(<img)(.+?)__s'.$nvar[$i].',img__s(.+?)>'.vari[$j].'/gs';
               
               // first protect all of the images that could be a variable images and put a temporary code for them
                
                for( $i=0;$i<$nv;$i++){
                    $html_file = preg_replace($pattern_for_var_image[$i],$pattern_for_var_img_sub[$i],$html_file);
                } 
                
              // print($html_file);  
                
                
                
                
                 // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
               for( $i=0;$i<$nv;$i++){
                    $html_file = preg_replace($pattern[$i],$vari[$i],$html_file);
                }
               // print($html_file);  
                // now take care of the varaible images 
                for( $i=0;$i<$nv;$i++){
            
                   $image_match_pattern = '/('.'(img)(.+?)s__'.$nvar[$i].',img__s(.+?)'.substr($vari[$i], strpos($vari[$i], "_") + 1).'(?=<\/span>)){1}/s';
                   
                   //s__equ,img__s(.+?)1(?=<\/span>)
                   $image_match_pattern_sub = '(img)(.+?)x__'.$nvar[$i].',img__x(.+?)'.substr($vari[$i], strpos($vari[$i], "_") + 1).'(?=<\/span>)';
    //               echo('image match pattern: '.$image_match_pattern);
    //            echo('<br> WTF </br> &nbsp; ');
                   if(preg_match($image_match_pattern,$html_file)){
                        
                        // replace the markup in the image
                      // $pattern_for_var_img_sub2[$i] = 'x__'.$nvar[$i].',img__x';
                         $pattern_to_protect = '/'.$pattern_for_var_img_sub[$i].'(.+?)'.substr($vari[$i], strpos($vari[$i], "_") + 1).'(?=<\/span>)/s';
                         $pattern_to_protect_sub = $pattern_for_var_img_sub2[$i].substr($vari[$i], strpos($vari[$i], "_") + 1);
        //                  echo('pattern to protect '.$pattern_to_protect);
        //                  echo('</br>');
                      //  echo('&nbsp; replace with <br>');
                       //   echo($pattern_for_var_img_sub2[$i]);
                     //   echo('yeah <br>');
                        
                     //   $html_file = preg_replace($pattern_to_protect,$pattern_to_protect_sub,$html_file);
                       //  $html_file = preg_replace($image_match_pattern,$image_match_pattern_sub,$html_file);
                       
                    } 
                }
                // get rid of all images that have the old sub pattern
               /*  
                 for( $i=0;$i<$nv;$i++){
                        $html_file = preg_replace('/(<img)(.+)__s'.$nvar[$i].'img__s(.+)>'.$vari[$i].'/s','',$html_file);
                 }
         */
        // print($html_file);       
                $html_stem = get_string_between($html_file,'t==','p==');  
                $htmlQuestions = "p==".get_string_between($html_file,'p==','==t'); 
                $html_reflections = get_string_between($html_file,'w==','==w'); 
                
            // looking for variable images 
            
            /* 
            
             for( $i=0;$i<$nv;$i++){
                    $html_stem = preg_replace($pattern[$i],$vari[$i],$html_stem);

                }
            */ 
               
            $dom = new DOMDocument();
             //$dom->encoding = 'utf-8';
           // $dom->formatOutput = true;   
                 
                
                $m='a';
                $q='b';
                $html_question = '<! doctype html>';
                for( $i=0;$i<$last_part;$i++){
                    if($ans[$i]<1.2e43){
                       // if($i != $last_part-1){
                          $string1= $m."==p";
                          $string2 = 'p=='.$q;
                          $html_q[$i] = trim(get_string_between($htmlQuestions,$string1,$string2));
                          
                          
                            // now add the questions and answer pattern to the stem for the complete 
                            $html_question = $html_question.$m.")&nbsp;".$html_q[$i].'<br>&nbsp;&nbsp;&nbsp;&nbsp;'.$ans_pattern[$i]."<br><br>";
                        $m++;
                        $q++;
                      //  } 
                    }
                }
     
           
             // now add the stem
              $html_question = $html_stem.'<br>'.$html_question;
          //    $html_question = strip_tags($html_question,'<br>,<img>,<sub>,<sup>,</sub></sup>');  // strip out the tags except the ones in the secound argument
         
         // need to take care of the images  for the xml Moodle document this means replacing the image reference with an encoded embedded image   
                libxml_use_internal_errors(true); // this gets rid of the warnig that the p tag isn't closed explicitly
                 $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html_question);
  
               $images = $dom->getElementsByTagName('img');
             
                foreach ($images as $image) {
                   /*  
                    if (preg_match("/s__/i", $html_question)) {
                        echo "A match was found.";
                    } else {
                        echo "A match was not found.";
                    }


 */
                    $src = $image->getAttribute('src');
                     $src = 'uploads/'.$src;
                     
                    
             
                     $src = urldecode($src);
                     $type = pathinfo($src, PATHINFO_EXTENSION);
                     $base64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($src));
                     $image->setAttribute("src", $base64); 
                     $html_question = $dom->saveHTML();
                     
               }
   
  // echo($html_question);
  $simp_htm_doc = new simple_html_dom();
    $simp_htm_doc->load($html_question);
    
 
    $captions = $simp_htm_doc -> find('.MsoCaption');
    // $images = $simp_htm_doc -> prev_sibling(find('.MsoCaption'));
   // $num_captions = count($captions);
   // echo('$num_captions:  '.$num_captions);
    
    //for ($j=0; $j<$num_captions;$j++){
    
    foreach($captions as $caption){
        $caption_text =$caption->plaintext;
        $caption_text = preg_split("/(,|s__|__s)/",$caption_text);
        $caption_value = trim(trim($caption_text[1])."_".trim($caption_text[3]));
        
        $image = $caption->prev_sibling();
        
       
           $keep_image = 0;        
          for( $i=0;$i<$nv;$i++){
                //  echo('  vari[$i] = '.$vari[$i]);
                    if($vari[$i] == $caption_value){
                    //    echo('___________________________________yes________________________________________');
                     $keep_image = 1;
                    }
                }
                    
               if($keep_image == 0)  {   
                  // echo ('delete image');
                   $image->outertext = '';
                   
               }   
              //  echo('  caption_value = '.$caption_value);
               
              $caption->outertext='';
       
     
       
       
       
       
    }
  
 // echo($simp_htm_doc);



//------------------------------------------------------------------------------------------------------------------------------------------



   
             
              
             $xml_text1 = $xml->createElement( "text");
             
             $xml_questiontext->appendChild( $xml_text1 );
          
              $xml_text1->appendChild(
                    //  $xml->createCDATASection($html_question)
                       $xml->createCDATASection($simp_htm_doc)
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
            
         $xml_quiz->appendChild( $xml_question1 );
   
          $xml->appendChild( $xml_quiz );
        
     $xml_document = $xml->saveXML();

    
       
       
      }
      
                 
      
       $xml->save('uploads/'.$xml_file_name);
 //print $xml->saveXML();

   // Parse the XML.
   
  
     
   
  echo('<a href = "uploads/'.$xml_file_name.'?dummy = dummy" download> download file</a>');
    echo('<br> <br><br>After downloading the file, close this browser window');
   
  



 ?>
  
  
 
         
  
   












