<?php
require_once "pdo.php";
require_once "simple_html_dom.php";
// this bit of code takes a problem from an microsoft office conversion to html of a QRproblem and replaces the text makup with actual html divs with id's that can be searched. 

$problem_id = 531;

 $sql = "SELECT * FROM Problem WHERE problem_id = :problem_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':problem_id' => $problem_id));
	$pblm_data = $stmt -> fetch();
	
 $htmlfilenm = "uploads/".$pblm_data['htmlfilenm'];

 //$html_file = file_get_contents($htmlfilenm);

  $html = new simple_html_dom();
  
  $html->load_file($htmlfilenm); 
//echo($html);
  // echo $html->plaintext;
   
   $tags = $html->find('p');
   $html = str_replace($html->find('p' , 0),'<div id = "quote">'.$html->find('p' , 0),$html);
 
    foreach ($tags as $tag){
         if (strpos(trim($tag->plaintext),'v==')!== false) {$html = str_replace($tag->outertext,'</div><div id="directions">' . $tag->outertext,$html);}
         if (strpos(trim($tag->plaintext),'==v')!== false) {$html = str_replace($tag->outertext,$tag->outertext."</div>",$html);}       

        if (strpos(trim($tag->plaintext),'t==')!== false) {        
            $html = str_replace($tag->outertext,'<div id="problem">' . $tag->outertext,$html);
        }
         if (strpos(trim($tag->plaintext),'==t')!== false) {$html = str_replace($tag->outertext,$tag->outertext."</div>",$html);}
       
 
        
         if (strpos(trim($tag->plaintext),'x==')!== false) {$html = str_replace($tag->outertext,'<div id="old_basecase">' . $tag->outertext,$html);}
         if (strpos(trim($tag->plaintext),'==x')!== false) {$html = str_replace($tag->outertext,$tag->outertext."</div>",$html);}
         
         if (strpos(trim($tag->plaintext),'u==')!== false) {$html = str_replace($tag->outertext,'<div id="old_unspecified">' . $tag->outertext,$html);}
         if (strpos(trim($tag->plaintext),'==u')!== false) {$html = str_replace($tag->outertext,$tag->outertext."</div>",$html);}
         
         if (strpos(trim($tag->plaintext),'w==')!== false) {$html = str_replace($tag->outertext,'<div id="reflections">' . $tag->outertext,$html);}
         if (strpos(trim($tag->plaintext),'==w')!== false) {$html = str_replace($tag->outertext,$tag->outertext."</div>",$html);}
        
    }
   
 $html = str_get_html($html);
    

    $tags = $html->find('#problem p');  // finds all of the p tags in the div with the ID problem
    foreach($tags as $tag){
          if (strpos(trim($tag->plaintext),'p==a==p')!== false) {$html = str_replace($tag->outertext,'<div id="questions">' . $tag->outertext,$html);}
            if (strpos(trim($tag->plaintext),'==t')!== false) {$html = str_replace($tag->innertext,$tag->innertext.'</div>',$html);}
        
        foreach(range('a','j') as $v){
            if (strpos(trim($tag->plaintext),'p=='.$v.'==p')!== false) { $html = str_replace($tag->outertext,'<div id="part'.$v.'">' . $tag->outertext.'</div>',$html);}
        }
    }
    
    $html = str_get_html($html);
    
      $tags = $html->find('#reflections p');
     //    $tags = $html->find('#problem p');  // finds all of the p tags in the div with the ID problem
    foreach($tags as $tag){
       
            if (strpos(str_replace(' ','',$tag->plaintext),'i)Reflect')!== false) { $html = str_replace($tag->outertext,'<div id="reflect">' . $tag->outertext,$html);}
            if (strpos(str_replace(' ','',$tag->plaintext),'ii)Explore')!== false) { $html = str_replace($tag->outertext,'</div><div id="explore">' . $tag->outertext,$html);}
            if (strpos(str_replace(' ','',$tag->plaintext),'iii)Connect')!== false) { $html = str_replace($tag->outertext,'</div><div id="connect">' . $tag->outertext,$html);}
            if (strpos(str_replace(' ','',$tag->plaintext),'iv)S')!== false) { $html = str_replace($tag->outertext,'</div><div id="society">' . $tag->outertext.'</div>',$html);}
    }
    // Take care of the images and captions if any then variable images if any
    
    
    
    // get rid of the markup except variable markup
    
    $html = str_replace('v==','',$html);
    $html = str_replace('==v','',$html);
    $html = str_replace('t==','',$html);
    $html = str_replace('==t','',$html);
    $html = str_replace('u==','',$html);
    $html = str_replace('==u','',$html);
    $html = str_replace('w==','',$html);
    $html = str_replace('==w','',$html);
    $html = str_replace('x==','',$html);
    $html = str_replace('==x','',$html);
    $html = str_replace('v==','',$html);
    $html = str_replace('==v','',$html);

 foreach(range('a','j') as $v){
   $html = str_replace('p=='.$v,$v.')',$html); 
   $html = str_replace('==p','',$html); 
 }
 

   // put a div around all of the captions
    $html = str_get_html($html);
     $tags = $html->find('.MsoCaption'); 
      $i = 1;
    foreach($tags as $tag){
        $html = str_replace($tag->outertext,'<div class = "caption" id="caption_id_'.$i.'">' . $tag->outertext.'</div>',$html);
        $i++;  
     }
     
     
// for images with variable captions group the image with the caption in a div named for the text in the caption less the markup

  $html = str_get_html($html);
    

    $tags = $html->find('.caption'); 
      
    foreach($tags as $tag){
        $caption_text =$tag->plaintext;
        $caption_text = preg_split("/(,|##)/",$caption_text);
        $caption_value = trim(trim($caption_text[1])."_".trim($caption_text[3]));
        
        $image = $tag->prev_sibling();
        $html = str_replace($image->outertext, '<div class = "var_image" id = "'.$caption_value.'">'.$image->outertext,$html);
        $html = str_replace($tag->outertext, $tag->outertext.'</div>',$html);
        
     }


    $html = str_get_html($html);
     echo($html);
     
     
     
     
    $str = $html->save();
    $html->save($htmlfilenm);

?>