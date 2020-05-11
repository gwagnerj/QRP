<?php
require_once "pdo.php";
session_start();

// This is the make a Moodle XML file of the problem

$problem_id= '';



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

	$dex = 2;


 $sql = "SELECT * FROM Problem WHERE problem_id = :problem_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':problem_id' => $problem_id));
	$pblm_data = $stmt -> fetch();
	
//echo($data['title']);



    $stmt = $pdo->prepare("SELECT * FROM Input where problem_id = :problem_id AND dex = :dex");
	$stmt->execute(array(":problem_id" => $_POST['problem_id'], ":dex" => $dex));
	$row = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT * FROM Qa where problem_id = :problem_id AND dex = :dex");
	$stmt->execute(array(":problem_id" => $_POST['problem_id'], ":dex" => $dex));
	$row_ans = $stmt->fetch();

// read in the names of the variables for the problem
    $nv = 0;  // number of non-null variables
   for ($i = 0; $i <= 13; $i++) {
    
        if($pblm_data['nv_'.($i+1)]!='Null'){
            $nvar[$i]=$pblm_data['nv_'.($i+1)];
            $nv++;
           echo($nvar[$i]." ");
        }  
        if($row['v_'.($i+1)]!='Null'){
         $vari[$i] = $row['v_'.($i+1)];
          echo($vari[$i]." ");
        } 
      
   }
   echo $nv;
   
   /* 
    for ($i = 0; $i <= 13; $i++) {
    
        if($pblm_data['nv_'.($i+1)]!='Null'){
            $nvar[$i]=$pblm_data['nv_'.($i+1)];
            $nv++;
           echo($nvar[$i]." ");
       }
   }
    */
   
  // Read in the tolerances, units  and answers for parts of problem
   $i = 0;
   for ($m = 'a'; $m<='j'; $m++){
     $tol[$i] = $pblm_data['tol_'.($m)];
      
      if($pblm_data['units_'.($m)]!='Null'){
        $units[$i] = $pblm_data['units_'.($m)];
      }
      $ans[$i]= $row_ans['ans_'.$m];
      
      
    // echo ($units[$i]." ");
   // echo ($tol[$i]." ");
    $i++;
   }
   
   // read in the answers for the variables
  
/* 
  for ($i = ){
       
       
       
   }
   
    */
   
   
   
   
/* 
  $bc_var = Array(15);
    $x = "";
    $nvar = new Array(15);
    $vari = new Array(15);
    $oNvar = new Array(15);
 */


$xml_file_name = substr($pblm_data['htmlfilenm'], 0, strrpos($pblm_data['htmlfilenm'], "."));
$xml_file_name = $xml_file_name.'.xml';

$htmlfilenm = "uploads/".$pblm_data['htmlfilenm'];


// this next function came from stack overflow https://stackoverflow.com/questions/5696412/how-to-get-a-substring-between-two-strings-in-php
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}


/* 
$fullstring = 'this is my [tag]dog[/tag]';
$parsed = get_string_between($fullstring, '[tag]', '[/tag]');

echo $parsed; // (result = dog)
 */


        // Set the content type to be XML, so that the browser will   recognise it as XML.
     //   header( "content-type: application/xml; charset=ISO-8859-15" );
     //   header( "content-type: application/xml; charset=ISO-utf-8" );

        // "Create" the document.
      //  $xml = new DOMDocument( "1.0", "ISO-8859-15" );
      
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
           
            $xml_text = $xml->createElement( "text","\$course\$/top/CategoryName/Testing XML up1"); 
             $xml_category->appendChild( $xml_text );
            
            
            $xml_info = $xml->createElement( "info");
              $xml_info->setAttribute( "format", "html" );
            // Append category and info to question.
           
            $xml_question->appendChild( $xml_info );
       
        $xml_quiz->appendChild( $xml_question );
 
             $xml_question1 = $xml->createElement( "question");

            // Set the attributes.
         
            $xml_question1->setAttribute( "type", "cloze" );
            
            $cloze = 'cloze1';
            
            $xml_name = $xml->createElement( "name");
             $xml_text = $xml->createElement( "text",  $pblm_data['title'] );
             $xml_name->appendChild( $xml_text );
            
         
            $xml_question1->appendChild( $xml_name );
              

            $xml_comment = $xml->createComment('this is my comment'); 
             $xml_question1->appendChild( $xml_comment );
            
             $xml_comment = $xml->createComment($pblm_data['title']); 
             $xml_question1->appendChild( $xml_comment );
             
             $xml_questiontext = $xml->createElement( "questiontext" );
              $xml_questiontext->setAttribute( "format", "html" );
              
                
              $html_stem = get_string_between(file_get_contents($htmlfilenm),'t==','p==');  // I think this will always be OK
              $html_q_a = get_string_between(file_get_contents($htmlfilenm),'a==p','p==b');  // what if part a is not numerical we need to check using the answers  also these should be an array
              $html_q_b = get_string_between(file_get_contents($htmlfilenm),'a==p','p==b'); // what if part b does not exist of is the last one

             $xml_text1 = $xml->createElement( "text");
             
               $xml_questiontext->appendChild( $xml_text1 );
          
                     $xml_text1->appendChild(
                      $xml->createCDATASection($html_stem." x is equal to 5 and y is equal to 4.&nbsp; <br>Determine the value of :<br>a) x +y&nbsp;{2:NUMERICAL:=9.0:0.1#Feedback for correct answer 9}.<br><br>b)
                  x - y&nbsp;&nbsp;{2:NUMERICAL:=-1.0:0.1#Feedback for correct answer 1}.<br>")
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

         
/* 
           $xml_info = $xml->createElement( "info");
          
            // Append category and info to question.
            $xml_question->appendChild( $xml_category );
            $xml_question->appendChild( $xml_info );
 */







     
        
        
         // $xml_quiz->appendChild( $xmlCDATA );
          
          
          $xml->appendChild( $xml_quiz );
        
        // Parse the XML.
        print $xml->saveXML();
         
        // $xmlData  = $xml->saveXML();

            $xml->save($xml_file_name);





















