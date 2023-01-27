<?php

// Require the pdo.php file to get the PDO connection object
require_once('../openai_api_key.php');
require('class.pdf2text.php');

  // default values for json input
  $data['question_text'] = 'x is 5 and y is 6. a) what is x+y? b) what is x-y? c) what is x*y? d) what is x/y?'  ;
  $data['part'] = 'a'  ;
  $data['answer'] = '11'  ;
  $data['problem_id'] = '846';

 
  

      // Receive the JSON object from the request body
        //   $input = file_get_contents('php://input');
        //   $data = json_decode($input, true);

//? Initialize the formData arrauy for troubleshooting


 //echo json_encode($data, JSON_PRETTY_PRINT);




// Get the form data from the POST request
 $data = json_decode(file_get_contents('php://input'), true);
    
    // set up a vaiable that will hold a regular experssion in the form of 'P' then the problem_id then '_s_' and end in '.pdf'

    $pregex_pattern = '/P'.$data['problem_id'].'_s_.*.pdf/';

// $next_lettter = ++$data['part'].')';
$file_pdf = glob('uploads/P'.$data['problem_id'].'_s_*.pdf');
// var_dump($file_pdf);
$file_pdf_str =$file_pdf[0];
// parse this pdf file as a text string
     $file_pdf = file_get_contents($file_pdf_str);

     $a = new PDF2Text();
     $a->setFilename($file_pdf_str);
     $a->decodePDF();
     $pdf_text_txt = $a->output();

        $pdf_text_arr = explode('Correlation:',$pdf_text_txt);
        $pdf_text = $pdf_text_arr[0];






    // die();




   require __DIR__ . '/vendor/autoload.php'; // remove this line if you use a PHP Framework.
 
    use Orhanerday\OpenAi\OpenAi;


// require __DIR__ . '/vendor/autoload.php'; // remove this line if you use a PHP Framework.

// use Orhanerday\OpenAi\OpenAi;

    // use Orhanerday\OpenAi\OpenAi;
  
    //$open_ai_key = getenv('OPENAI_API_KEY');
    $open_ai = new OpenAi($openai_key);


    // $prompt = 'You are an expert in solving engineering problems and you expalin how to solve numerical problems to engineering students on a daily basis. ';
    // $prompt .= 'The student you are responding to is having difficulties solving part '.$data['part'].') of the following problem: '.$data['question_text'];
    // $prompt .= ' Answer in a friendly tone and in the style of Richard Feynman.';
    // $prompt .= ' Start by including questions to the student that may give them ideas on getting unstuck. ';
    // $prompt .= ' Then complete your response with the phrase: "For part '.$data["part"].'), one approach may be" and then include your best solution that results in';
    // $prompt .= ' the correct answer for part '.$data['part'].') of '.$data["answer"].' and uses the relevant parts of the Excel output: '.$pdf_text;    
    // $prompt .= ' However, do not mention the Excel Output to the student. '; 
    
    // echo json_encode($prompt);
    
    // die();

    
  
  
  //       //! For text completion  - generating a text response
  $prompt = 'You are an expert in solving engineering problems and you expalin how to solve numerical problems to engineering students on a daily basis. ';
  $prompt .= 'The student you are responding to is having difficulties solving part '.$data['part'].') of the following problem: '.$data['question_text'];
  $prompt .= ' Answer in a friendly tone and in the style of Richard Feynman.';
  $prompt .= ' Start by including questions to the student that may give them ideas on getting unstuck. ';
  $prompt .= ' Then complete your response with the phrase: "For part '.$data["part"].'), one approach may be" and then include your best solution that results in';
  $prompt .= ' the correct answer for part '.$data['part'].') of '.$data["answer"].' and uses the relevant parts of the Excel output: '.$pdf_text;    
  $prompt .= ' However, do not mention the Excel Output to the student. '; 
  
//   echo json_encode($prompt);
//   die();
  
  //           //  echo $prompt;
        $complete_text = $open_ai->completion([
  
            'model' => 'text-davinci-003',
        //    'model' => 'text-curie-001',
            
            'prompt' => $prompt,
            'temperature' => 0.1,
            'max_tokens' => 1000,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0.6,
            // 'stop' => ['\n', '\r', '\r\n', ' '],
          
        ]);
  
  
        $complete_json = json_decode($complete_text,true);
  
  
  //   // var_dump($complete_json);
  
        $response_text = $complete_json['choices'][0]['text'];
  //     //  $response_text .= '<p>'.$prompt.'<p>'.$response_text;
  
    
    //  echo json_encode($input);
      //  echo json_encode($response_text);
  
      // combine the propmt and the response into a single string and return an error message if the response is empty
      if ($response_text){
         // $response_text = $response_text;
          echo json_encode($response_text);
      } else {
          $response_text = 'Error in getting the response.';
          echo json_encode($response_text);
      }
  
  






?>