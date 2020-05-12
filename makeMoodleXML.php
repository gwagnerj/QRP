<?php
//header( "content-type: application/xml; charset=ISO-utf-8" );
     // Set the content type to be XML, so that the browser will   recognise it as XML.

require_once "pdo.php";
session_start();


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
            $pattern[$i]= '/##'.$nvar[$i].'.+?##/';
         // echo ($pattern[$i]);
         }
   }
   //echo $nv;
   $i = 0;
    for ($m = 'a'; $m<='j'; $m++){
        $tol[$i] = $pblm_data['tol_'.($m)];
        $i++;
    }
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
           
            $xml_text = $xml->createElement( "text","\$course\$/top/CategoryName"); 
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
           $cat_name = '\$course\$/top/CategoryName/'.$pblm_data['title'];
           
          //  $xml_text = $xml->createElement( "text","\$course\$/top/CategoryName/Testing XML up1"); 
             $xml_text = $xml->createElement( "text", $cat_name); 
             $xml_category->appendChild( $xml_text );
            
            
            $xml_info = $xml->createElement( "info");
              $xml_info->setAttribute( "format", "html" );
            // Append category and info to question.
           
            $xml_question->appendChild( $xml_info );

        $xml_quiz->appendChild( $xml_question );
 
   
   
  for($dex=2; $dex<3; $dex++){
      
    $stmt = $pdo->prepare("SELECT * FROM Input where problem_id = :problem_id AND dex = :dex");
	$stmt->execute(array(":problem_id" => $_POST['problem_id'], ":dex" => $dex));
	$row = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT * FROM Qa where problem_id = :problem_id AND dex = :dex");
	$stmt->execute(array(":problem_id" => $_POST['problem_id'], ":dex" => $dex));
	$row_ans = $stmt->fetch();

 
    $xml_file_name = substr($pblm_data['htmlfilenm'], 0, strrpos($pblm_data['htmlfilenm'], "."));
    $xml_file_name = $xml_file_name.'.xml';

    $htmlfilenm = "uploads/".$pblm_data['htmlfilenm'];



//------------------------------------Get index specific data---------------------------- 
   // Read in the value for the input variables
   
    for ($i = 0; $i <= $nv; $i++) {
    
        if($row['v_'.($i+1)]!='Null' ){
            $vari[$i] = $row['v_'.($i+1)];
         // echo ($pattern[$i]);
        }
    }
   
  // set the answer and margin of error into a pattern
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
            
             $xml_comment = $xml->createComment($pblm_data['title']); 
             $xml_question1->appendChild( $xml_comment );
             
             $xml_questiontext = $xml->createElement( "questiontext" );
              $xml_questiontext->setAttribute( "format", "html" );
              
                
              $html_stem = get_string_between(file_get_contents($htmlfilenm),'t==','p==');  
              $htmlQuestions = "p==".get_string_between(file_get_contents($htmlfilenm),'p==','==t'); 
              $html_reflections = get_string_between(file_get_contents($htmlfilenm),'w==','==w'); 
               
               // substitute all of the variables into the stem // note - we would also need to substitute in if the vars are in the questions
                for( $i=0;$i<$nv;$i++){
                    $html_stem = preg_replace($pattern[$i],$vari[$i],$html_stem);

                }
                $m='a';
                $q='b';
                $html_question = '';
                for( $i=0;$i<$last_part;$i++){
                    if($ans[$i]<1.2e43){
                       // if($i != $last_part-1){
                          $string1= $m."==p";
                          $string2 = 'p=='.$q;
                          $html_q[$i] = trim(get_string_between($htmlQuestions,$string1,$string2));
                          
                            // just in case there are some variables in the questions we need to substitute for them
                           for( $j=0;$j<$nv;$j++){
                                    $html_q[$i] = preg_replace($pattern[$j],$vari[$j],$html_q[$i]);
                                }
                            // now add the questions and answer pattern to the stem for the complete 
                            $html_question = $html_question.$m.")&nbsp;".$html_q[$i].'<br>&nbsp;&nbsp;&nbsp;&nbsp;'.$ans_pattern[$i]."<br><br>";
                        $m++;
                        $q++;
                      //  } 
                    }
                }
     
           
             // now add the stem
              $html_question = $html_stem.'<br>'.$html_question;
              $html_question = strip_tags($html_question,'<br>,<img>,<sub>,<sup>,</sub></sup>');  // strip out the tags except the ones in the secound argument
         
         // need to take care of the images     
         
              $dom = new DOMDocument();
               $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html_question);

                $images = $dom->getElementsByTagName('img');
            
                foreach ($images as $image) {
                     $src = $image->getAttribute('src');
                     $src = 'uploads/'.$src;
                     
                     // $src = urldecode(str_replace('/','\\', $src));  // comment this out when not on the local system
             
                     $src = urldecode($src);
                     $type = pathinfo($src, PATHINFO_EXTENSION);
                     $base64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($src));
                     $image->setAttribute("src", $base64); 
                     $html_question = $dom->saveHTML();
                         
               }
   
             
             $xml_text1 = $xml->createElement( "text");
             
             $xml_questiontext->appendChild( $xml_text1 );
          
              $xml_text1->appendChild(
                      $xml->createCDATASection($html_question)
                    );
                    
           $xml_question->appendChild( $xml_questiontext );
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
        
         // $xml_quiz->appendChild( $xmlCDATA );
          
          $xml->appendChild( $xml_quiz );
        
        // Parse the XML.
        print $xml->saveXML();
         
        // $xmlData  = $xml->saveXML();
            $xml->save($xml_file_name);

      }

















