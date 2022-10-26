<?php
        require_once "pdo.php";
        //var_dump($_POST);
        if(isset($_POST['info'])) 
            {$info = $_POST['info'];} 
        else
        {
            echo 'error - info not passed to question_check';
            die();
        }

        if(!isset($info[0]) || !isset($info[1]) || !isset($info[2]) || !isset($info[3]) ){
            echo 'error - some information passed to question_check is missing ';
            die();
        }

        $return_data = array();


        $index_st = $info[0];   // these are where the responses are
        $question_id = $info[1];
        $student_id = $info[2];
        $email_flag = $info[3];
        $currentclass_id = $info[4];
        $questionset_id = $info[5];
        $quickquestionactivity_id = $info[6];
        $selected_ar = array();
        for ($i = 7; $i < count($info); $i++){
            $k = $i-7;
            $selected_ar[$k] = $info[$i];
            $response_alias_ar[$k] = explode('-',$info[$i])[1];
        }

        $num_responses = strlen($index_st);

        // see if the response is correct then 
        // var_dump($response_alias_ar);

        // get the question information 

        $sql = "SELECT * FROM Question WHERE question_id = :question_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':question_id' => $question_id
        ));
            $question_data = $stmt->fetch(PDO::FETCH_ASSOC);


        date_default_timezone_set('America/New_York');

        $now = date('Y-m-d');


            $m = 0;
            foreach(range('a','j') as $v){
                $letter_number[$v] = $m;
                $number_letter[$m] = $v;
                $m++;
            }

            $response_st = '';
            $index_ar = array_map('intval', str_split($index_st));

            for ($i = 0; $i < $num_responses; $i++) {
                $index_inverse_ar[$index_ar[$i]] = $i;
            }
            
         //   var_dump($index_inverse_ar);
            
            
            $i=0;
            foreach ($response_alias_ar as $response_alias) {

                $number_response[$i] = $letter_number[$response_alias];
                $response_alias[$i] = $number_response[$i];
                $response_base_num[$i] =  $index_ar[$number_response[$i]];
                $response_ar[$i] = $number_letter[$response_base_num[$i]];
                $response_st =  $response_st.$response_ar[$i];
                $response_selector[$i] = 'key_'. $response_ar[$i];     // column names for the scoring columns in the base
  //   echo ' response_ar[i] '.$response_ar[$i];
                $i++;
            }

            $selected_correct_number = array();
            $selected_correct_value = array();
            $selected_wrong_number = array();
           
   //     echo ' response selector '.$response_selector[$i];

                $key_total = 0;

                for ($j=0; $j<$num_responses; $j++){      // get an array of which responses are correct from the data table question
                    $v = $number_letter[$j];  
                    $column_selector = 'key_'.$v;
                    if ($question_data[$column_selector]>0){
                        $correct_ar[$j] = 1;
                    } else {
                        $correct_ar[$j] = 0;
                    }
                }


        for ($j=0; $j<$num_responses; $j++){   
            $selected_options_ar[$j]=0;         // an array in the base (not suffled) of zeros and 1s for the responses
            $v = $number_letter[$j];
            $column_selector = 'key_'.$v;

            if(in_array($column_selector,$response_selector)){
                $selected_options_ar[$j] = 1;
            }
        }


        for ($j=0; $j<$num_responses; $j++){   
           $selected_correct[$j] = $correct_ar[$j] * $selected_options_ar[$j];
           $selected_wrong[$j] = $selected_options_ar[$j] -  $selected_correct[$j];
           $unselected_wrong[$j] = $correct_ar[$j]-$selected_correct[$j];
           $wrong_ar[$j] =  $unselected_wrong[$j] + $selected_wrong[$j];
        }


        for ($j=0; $j<$num_responses; $j++){   
            $selected_correct_alias[$j] = $selected_correct[$index_ar[$j]];
            $selected_wrong_alias[$j] = $selected_wrong[$index_ar[$j]];
            $unselected_wrong_alias[$j] =$unselected_wrong[$index_ar[$j]];
            $correct_alias[$j] = $correct_ar[$index_ar[$j]];
         }
 

         ksort($selected_correct_alias);
         ksort($selected_wrong_alias);
         ksort($unselected_wrong_alias);
         ksort($correct_alias);



    $total_wrong = array_sum($wrong_ar);
            $selected_correct = array_sum($selected_correct);
            $selected_wrong = array_sum($selected_wrong);
            $total_correct=array_sum($correct_ar);
            $fraction_reduction = ($total_wrong/$total_correct)/2;
            $percent_correct = (1- $fraction_reduction)*100;

          

            
            // echo ' selected_correct '.$selected_correct.' selected_wrong '.$selected_wrong.' percent_correct '.$percent_correct;
            // echo ' response_st '.$response_st;

        // //? make sure the question is valid for the student 
        //? this is already done in the question_quick_show calling file
        // $sql = "SELECT quickquestionactivity_id FROM QuickQuestionActivity
        //  WHERE question_id = :question_id AND currentclass_id = :currentclass_id AND student_id = :student_id 
        //  AND expires_at > NOW() AND response_st > :response_st
        //   ORDER BY quickquestionactivity_id DESC LIMIT 1";
        // $stmt = $pdo->prepare($sql);
        // $stmt->execute(array(
        // ':question_id' => $question_id,
        // ':currentclass_id' => $currentclass_id,
        // ':student_id' => $student_id,
        // ':response_st' => '',
        // ));
        //     $quickquestionactivity_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
        //     if($quickquestionactivity_data){
        //         $quickquestionactivity_id = $quickquestionactivity_data['quickquestionactivity_id'];
        //         //? already answered this question activity

        //     } else {
        //         $quickquestionactivity_id = "0";
        //     }

            // score if email the problem - if late one point fore every 24 hours

            $percent_total = 0;
            if ($key_total != 0){$percent_total = $pblm_score / $key_total * 100;}
            $correct_flag =1; //! worry about this later
           
            $score = 10 ;  // no matter if they get the first on write or wrong they get full credit as long as they try but will be repeated more often where they can possibly get it wrong
            if($score<0){$score = 0;}

        

        
             //? insert the information into the questionactivity table, the response table and possibly update the Question for the statistics for that question
             $sql = "UPDATE QuickQuestionActivity SET response_st = :response_st,correct_flag = :correct_flag,score = :score
                    WHERE quickquestionactivity_id = :quickquestionactivity_id
             ";
                       $stmt = $pdo->prepare($sql);
                         $stmt->execute(array(
                          ':quickquestionactivity_id' => $quickquestionactivity_id,
                          ':response_st' => $response_st,
                          ':correct_flag' => $correct_flag,
                          ':score' => $score,
                             )
                 );

                
                 //? not sure why we need the try_number returned for us

             $sql = "SELECT try_number FROM QuickQuestionActivity WHERE quickquestionactivity_id = :quickquestionactivity_id";            
             $stmt = $pdo->prepare($sql);
             $stmt->execute(array(
                 ':quickquestionactivity_id' => $quickquestionactivity_id
             ));
                 $try_number_ar = $stmt->fetch(PDO::FETCH_ASSOC);
                 $try_number = $try_number_ar['try_number'];
     




        //          //! get the next question if there is one available    
                 
        //      $sql = "SELECT * FROM QuickQuestionActivity WHERE 
        //             expires_at >NOW() AND email_flag = :email_flag AND response_st = :response_st AND student_id = :student_id 
        //             AND currentclass_id = :currentclass_id 
        //             ORDER BY discuss_stage DESC, try_number ASC LIMIT 1
        //      ";
        //         $stmt = $pdo->prepare($sql);
        //         $stmt->execute(array(
        //             ':response_st' => "",
        //             ':email_flag' => "0",
        //             ':student_id' => $student_id,
        //             ':currentclass_id' => $currentclass_id,
        //   ));
        //             $next_question_ar = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $return_data['score']=$score;
             $return_data['response_st']= $response_st;
             $return_data['try_number']= $try_number;
             $return_data['questionset_id']= $questionset_id;
            $return_data['percent_correct'] =$percent_correct;
             $return_data['selected_correct_number'] = $selected_correct;
             $return_data['selected_wrong_number'] =$selected_wrong;
             $return_data['selected_wrong_alias'] =$selected_wrong_alias;
             $return_data['selected_correct_alias'] = $selected_correct_alias;
             $return_data['correct_alias']=$correct_alias;
             $return_data['question_id'] = $question_id;
             $return_data['wrong_ar'] = $wrong_ar;
             $return_data['selected_options_ar'] = $selected_options_ar;
             $return_data['correct_ar'] = $correct_ar;
            //  $return_data['next_question_id'] = $next_question_ar['question_id'];
             
            //  if ($next_question_ar){
            //     $return_data['next_question_id'] = $next_question_ar['question_id'];
            //     $return_data['next_try_number'] = $next_question_ar['try_number'];
            //     $return_data['next_discuss_stage'] = $next_question_ar['discuss_stage'];
            //     $return_data['next_quickquestionactivity_id'] = $next_question_ar['quickquestionactivity_id'];
            //  } else {
            //     $return_data['next_question_id'] = "none";
            //     $return_data['next_try_number'] = "";
            //     $return_data['next_discuss_stage'] = "";
            //     $return_data['next_quickquestionactivity_id'] = "";

            //  }

              print json_encode($return_data);


 ?>





