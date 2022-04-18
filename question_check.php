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

        if(!isset($info[0]) || !isset($info[1]) || !isset($info[3]) || !isset($info[4]) || !isset($info[5])){
            echo 'error - some information passed to question_check is missing ';
            die();
        }

        $return_data = array();
        $late_penalty_per_day = 2;  //two points a day for emailed 
        $point_loss_for_wrong = 5;
        $days_for_repeat_if_wrong = 3;
        $days_for_repeat_if_correct = 15;



        $index_st = $info[0];
        $question_id = $info[1];
        $questionset_id = $info[2];
        $student_id = $info[3];
        $email_flag = $info[4];
        $clarity_rating = intval(($info[5]+0)*10);
        $relavance_rating = intval(($info[6]+0)*10);
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

        // get the information from the questionset data
        // echo ' quiestionset_id '.$questionset_id;

        $sql = "SELECT * FROM QuestionSet
        JOIN QuestionTime ON QuestionSet.questiontime_id = QuestionTime.questiontime_id
        WHERE questionset_id = :questionset_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
            ':questionset_id' => $questionset_id
        ));
        $questionset_data = $stmt->fetch(PDO::FETCH_ASSOC);

        $questiontime_id = $questionset_data['questiontime_id'];
        $set_day_alias = $questionset_data['set_day_alias'];

        $start_time = $questionset_data['start_time'];

        $start_time = explode(":",$questionset_data['start_time'])[0].':'.explode(":",$questionset_data['start_time'])[1];

   //         echo ' start_time '.$start_time;


        date_default_timezone_set('America/New_York');

        $now = date('Y-m-d');
        $set_date = $questionset_data['set_date'];
        $set_date2 = explode(" ",$questionset_data['set_date'])[0].' '.$start_time;
        //  $set_date = $questionset_data['set_date'];
  //          echo ' set_date '.$set_date;

        $set_date_dt = new \DateTime($set_date2);
        $now_dt = new DateTime();
        $interval = $now_dt->diff($set_date_dt);
        // $interval = $set_date_dt->diff($now_dt);
        $diffInDays   = ceil($interval->d); 
        if ($now_dt < $set_date_dt){
            $diffInDays = 0;
            // echo 'now more than set date by '.$diffInHours;
        } 



            // need to change the response to the response to the base question (before shuffling)  with the key_code
            //convert the response ot a number 
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

            //     $key = 'key_'.$response;
                $key_total = 0;
             //   $i=0;

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

            if ($fraction_reduction >0){
                $repeat_wrong_flag = 1;
                $repeat_correct_flag = 0;
                $correct_flag = 0;
            } else {
                $repeat_wrong_flag = 0;
                $repeat_correct_flag = 1;
                $correct_flag = 1;
            }

            
            // echo ' selected_correct '.$selected_correct.' selected_wrong '.$selected_wrong.' percent_correct '.$percent_correct;
            // echo ' response_st '.$response_st;

        //? see if the student has ever seen this problem before for this problem setting  this is for the scoring (are we adding points or taking them away)
        $sql = "SELECT * FROM QuestionActivity WHERE question_id = :question_id AND questionset_id = :questionset_id AND student_id = :student_id AND (repeat_correct_flag = 1 OR repeat_wrong_flag = 1) ORDER BY questionactivity_id DESC LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
        ':question_id' => $question_id,
        ':questionset_id' => $questionset_id,
        ':student_id' => $student_id,
        ));
            $questionactivity_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if($questionactivity_data){
                //? already answered this question activity
              $first_time_flag = 0;

            } else {
                $first_time_flag = 1;
            }

            // score if email the problem - if late one point fore every 24 hours
        if ($email_flag ==1 || $first_time_flag == 1){

            $percent_total = 0;
            if ($key_total != 0){$percent_total = $pblm_score / $key_total * 100;}
            $score = 10 - $late_penalty_per_day*$diffInDays;  // no matter if they get the first on write or wrong they get full credit as long as they try but will be repeated more often where they can possibly get it wrong
            if($score<0){$score = 0;}

        

        
             //? insert the information into the questionactivity table, the response table and possibly update the Question for the statistics for that question

             $sql = "INSERT INTO QuestionActivity (question_id,questionset_id,student_id,response_st,repeat_correct_flag,repeat_wrong_flag,correct_flag,score) 
             VALUES(:question_id,:questionset_id,:student_id,:response_st,:repeat_correct_flag,:repeat_wrong_flag,:correct_flag,:score)";
                       $stmt = $pdo->prepare($sql);
                         $stmt->execute(array(
                         ':question_id'=>  $question_id,
                         ':questionset_id' =>  $questionset_id,
                          ':student_id' => $student_id,
                          ':response_st' => $response_st,
                          ':repeat_correct_flag' => $repeat_correct_flag,
                          ':repeat_wrong_flag' => $repeat_wrong_flag,
                          ':correct_flag' => $correct_flag,
                          ':score' => $score,
                             )
                 );

                        if ($correct_flag == 1 && $first_time_flag ==1 ){
                                    $sql = "UPDATE Question SET  num_total = num_total + 1, clarity_total = clarity_total + :clarity_rating, relavance_total = relavance_total + :relavance_rating
                                        WHERE `question_id` = :question_id
                                    ";
                                            $stmt = $pdo->prepare($sql);
                                                $stmt->execute(array(
                                                ':question_id'=>  $question_id,
                                                ':clarity_rating'=>  $clarity_rating,
                                                ':relavance_rating'=>  $relavance_rating,
                                                    )
                                        );
                                    } else if($correct_flag == 0  && $first_time_flag ==1 ) {
                                        $sql = "UPDATE Question SET  num_total = num_total + 1, num_correct = num_correct+1, clarity_total = clarity_total + :clarity_rating, relavance_total = relavance_total + :relavance_rating
                                        WHERE `question_id` = :question_id
                                    ";
                                            $stmt = $pdo->prepare($sql);
                                                $stmt->execute(array(
                                                ':question_id'=>  $question_id,
                                                ':clarity_rating'=>  $clarity_rating,
                                                ':relavance_rating'=>  $relavance_rating,

                                                    )
                                        );

                                    }

        } 
        else
         {      //? its not the first time they answered this question so we should have some data from the last time in $questionactivity_data
            // echo '<br>';
            // var_dump($questionactivity_data['repeat_wrong_flag']);
            $score = $questionactivity_data['score'];
            $score_loss = round($point_loss_for_wrong*$fraction_reduction);
            $score = -$score_loss;
            // echo ' points lost '.$score_loss;


            $sql = "INSERT INTO QuestionActivity (question_id,questionset_id,student_id,response_st,repeat_correct_flag,repeat_wrong_flag,correct_flag,score) 
            VALUES(:question_id,:questionset_id,:student_id,:response_st,:repeat_correct_flag,:repeat_wrong_flag,:correct_flag,:score)";
                      $stmt = $pdo->prepare($sql);
                        $stmt->execute(array(
                        ':question_id'=>  $question_id,
                        ':questionset_id' =>  $questionset_id,
                         ':student_id' => $student_id,
                         ':response_st' => $response_st,
                         ':repeat_correct_flag' => $repeat_correct_flag,
                         ':repeat_wrong_flag' => $repeat_wrong_flag,
                         ':correct_flag' => $correct_flag,
                         ':score' => $score,
                            )
                );
        }
        
       $return_data['score']=$score;


            //? get the total score for this student on these questions and
            $sql = "SELECT  score,correct_flag  FROM QuestionActivity
            JOIN QuestionSet ON QuestionActivity.questionset_id = QuestionSet.questionset_id
            WHERE student_id = :student_id AND questiontime_id = :questiontime_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
            ':questiontime_id' => $questiontime_id,
            ':student_id' => $student_id
            ));
                $questionset_scoredata = $stmt->fetchALL(PDO::FETCH_ASSOC);

            $total_score =0;
            $total_correct = 0;
            foreach($questionset_scoredata as $qs_score_datum){
                $total_score +=  $qs_score_datum['score'];
                $total_correct += $qs_score_datum['correct_flag'];
            }

            //  echo ' total score: '.$total_score;
            //  echo ' total correct: '.$total_correct;
            $return_data['total_score'] = $total_score;
            $return_data['total_correct'] = $total_correct;



            function getNextQuestion($pdo,$questiontime_id,$set_date,$set_day_alias,$days_for_repeat_if_correct,$days_for_repeat_if_wrong,$student_id){ 
              // ?look for the questionset with due on the same day that has an set_day_alias the same day
                $set_day_alias++;
                $sql = "SELECT questionset_id  FROM QuestionSet  
                WHERE questiontime_id = :questiontime_id AND set_date = :set_date AND set_day_alias = :set_day_alias LIMIT 1";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                ':questiontime_id' => $questiontime_id,
                ':set_day_alias' => $set_day_alias,
                ':set_date' => $set_date,
                ));
                $qs_data = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($qs_data){
                        return $qs_data['questionset_id'];
                    } 
                   
                    //? now look for questions that are the oldest and have not been worked you may not use this file except in compliance

                    $sql = 'SELECT QuestionSet.questionset_id as questionset_id  FROM QuestionSet 
                    WHERE  QuestionSet.questiontime_id = :questiontime_id AND QuestionSet.set_date <= DATE(NOW()) AND questionset_id NOT IN (SELECT QuestionActivity.questionset_id FROM QuestionActivity WHERE student_id = :student_id )
                    ORDER BY set_date ASC, set_day_alias ASC Limit 1 ';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(
                    ':questiontime_id' => $questiontime_id,
                    ':student_id' => $student_id,
                    ));
                    $qs_data = $stmt->fetch(PDO::FETCH_ASSOC);
                        if($qs_data){
                            return $qs_data['questionset_id'];
                        } 




                   // ? now look for All elgible old questions  want the old problems that were not answered by this studetn rescently  

                //     $now_dt = new DateTime();


                //     $time_coef = 0.9;         //! adjust these coeficients to get a reasonalbe repeat fraction
                //     $percent_wrong_coef = 0.5;
                //     $percent_wrong_last_time_coef = 30;
                //     $rand = rand(0,100);

                //     $sql = 'SELECT *  FROM QuestionSet Join QuestionActivity
                //     ON QuestionActivity.questionset_id = QuestionSet.questionset_id 
                //     WHERE  QuestionSet.questiontime_id = :questiontime_id AND student_id = :student_id AND
                //     ( QuestionActivity.repeat_wrong_flag = 1 AND QuestionActivity.updated_at < (NOW() - INTERVAL :days_for_repeat_if_wrong  DAY)
                //     OR
                //     QuestionActivity.repeat_correct_flag = 1 AND QuestionActivity.updated_at < (NOW() - INTERVAL :days_for_repeat_if_correct  DAY))
                //     GROUP BY QuestionActivity.question_id ORDER BY set_date DESC, set_day_alias ASC 
                //     ';
                //    $stmt = $pdo->prepare($sql);
                //    $stmt->execute(array(
                //    ':questiontime_id' => $questiontime_id,
                //    ':student_id' => $student_id,
                //    ':days_for_repeat_if_wrong' => $days_for_repeat_if_wrong,
                //    ':days_for_repeat_if_correct' => $days_for_repeat_if_correct,
                //    ));
                //    $q_old_data = $stmt->fetchALL(PDO::FETCH_ASSOC);
                //    if($q_old_data){
                //        foreach($q_old_data as $q_datum){      //? get all of the data for this question for this student_id
                //         $questionset_id = $q_datum['questionset_id'];

                //         $sql = "SELECT *  FROM QuestionActivity 
                //         WHERE questionset_id = :questionset_id AND student_id = :student_id ORDER BY updated_at DESC";
                //          $stmt = $pdo->prepare($sql);
                //          $stmt->execute(array(
                //          ':questionset_id' => $questionset_id,
                //          ':student_id' => $student_id,
                //          ));
                //          $qact_data = $stmt->fetchALL(PDO::FETCH_ASSOC);
                //              if($qact_data){  // this is all the data for this student for this question (questionset_id)

                //                 $num_attempts = count($qact_data);
                //                 $correct_last_time_flag = $qact_data[0]['correct_flag'];
                //                 $last_updated = $qact_data[0]['updated_at'];
                //                 $last_updated_dt = new \DateTime($last_updated);
                //                 $interval = $now_dt->diff( $last_updated_dt);
                //                 $diffInDays   = ceil($interval->d); 

                //                 $num_correct = $percent_correct = 0;
                //                 foreach ($qact_data as $qact_datum){
                //                     if ($qact_datum['correct_flag']==1){
                //                         $num_correct++;
                //                     }
                //                 }
                //                 $percent_correct = ceil($num_correct/$num_attempts*100);

                //                 $prob_display = 100;

                //                     //? calculate the probability of display
                //                     $prob_display = $time_coef* $diffInDays + $percent_wrong_coef*(100-$percent_correct)+$percent_wrong_last_time_coef*$correct_last_time_flag;

                //                 if($correct_last_time_flag ==1 && $percent_correct > 70 && $num_correct >= 2){
                //                         $prob_display = 0;      // this problem should not come up again could put in a retired field in the activity for this student but see 
                //                 }

                //             //? see if we are going to display this problem and
                //                 if ($rand<=$prob_display){
                //                     return $qact_datum['questionset_id'];
                //                 }
                //              } 
                //        }
                //        return $questionset_id;
                //    } 

                       return 0;  //? ran out of questions
                 }


              $questionset_id =   getNextQuestion($pdo,$questiontime_id,$set_date,$set_day_alias,$days_for_repeat_if_correct,$days_for_repeat_if_wrong,$student_id); 




            //  $count_first_time = 0;
            //  //?  check to see if there are any more problems that need worked in the question set_ but not ones that have already been answered
            //          $sql = "SELECT COUNT(*) as count_first_time FROM QuestionSet  
            //          WHERE questiontime_id = :questiontime_id AND questionset_id != :questionset_id AND set_date < NOW() AND set_day_alias > :set_day_alias";
            //          $stmt = $pdo->prepare($sql);
            //          $stmt->execute(array(
            //          ':questiontime_id' => $questiontime_id,
            //          ':questionset_id' => $questionset_id,
            //          ':set_day_alias' => $set_day_alias,
            //          ));
            //              $questionset_alldata = $stmt->fetchALL(PDO::FETCH_ASSOC);
             
            //              if($questionset_alldata){
            //                  $count_first_time = $questionset_alldata[0]['count_first_time'];
            //              }



            // $return_data['count_first_time'] = $count_first_time;
          
          
            //  //? look for questions in that are repeats that are due 

            //  $sql = 'SELECT COUNT(DISTINCT(question_id)) as count_repeat FROM QuestionActivity WHERE  questionset_id = :questionset_id AND student_id = :student_id AND ((repeat_correct_flag = 1 AND updated_at < NOW() - INTERVAL '.$days_for_repeat_if_correct.' DAY ) OR(repeat_wrong_flag = 1 AND updated_at < NOW() - INTERVAL '.$days_for_repeat_if_wrong .' DAY )  )';
            //  $stmt = $pdo->prepare($sql);
            //  $stmt->execute(array(
            //  ':questionset_id' => $questionset_id,
            //  ':student_id' => $student_id,
            //  ));
            //      $questionactivity_data_count = $stmt->fetchALL(PDO::FETCH_ASSOC);
            //      if($questionactivity_data_count){
            //         $count_repeat = $questionactivity_data_count[0]['count_repeat'];
            //      }
                 
            //  $return_data['count_repeat'] = $count_repeat;

            //  $total_count = $count_repeat+$count_first_time;

            //  if ($count_first_time != 0 ){  // find the question_id 
            //     $sql = "SELECT question_id,questionset_id FROM QuestionSet
            //     WHERE questiontime_id = :questiontime_id AND questionset_id != :questionset_id AND set_day_alias > :set_day_alias AND set_date < NOW() ORDER BY questionset_id ASC";
            //     $stmt = $pdo->prepare($sql);
            //     $stmt->execute(array(
            //     ':questiontime_id' => $questiontime_id,
            //     ':questionset_id' => $questionset_id,
            //     ':set_day_alias' => $set_day_alias,
            //     ));
            //          $questionset_question_id = $stmt->fetch(PDO::FETCH_ASSOC);
        
            //         if($questionset_question_id){
            //             $question_id = $questionset_question_id['question_id'];
            //             $questionset_id = $questionset_question_id['questionset_id'];
            //         } elseif($count_repeat>0){

            //             $sql = 'SELECT question_id  FROM QuestionActivity WHERE  questionset_id = :questionset_id AND student_id = :student_id AND ((repeat_correct_flag = 1 AND updated_at < NOW() - INTERVAL '.$days_for_repeat_if_correct.' DAY ) OR(repeat_wrong_flag = 1 AND updated_at < NOW() - INTERVAL '.$days_for_repeat_if_wrong .' DAY )  ) ORDER BY questionactivity_id ASC';
            //             $stmt = $pdo->prepare($sql);
            //             $stmt->execute(array(
            //             ':questionset_id' => $questionset_id,
            //             ':student_id' => $student_id,
            //             ));
            //                 $questionactivity_data_questionid = $stmt->fetch(PDO::FETCH_ASSOC);
                        
            //                 if($questionactivity_data_questionid){
            //                    $question_id = $questionactivity_data_questionid['question_id'];
            //                 }

            //         }

            //  } else {
            //      $question_id = 0;
            //  }



           

         //    echo ' question_id: '.$question_id;

             $return_data['response_st']= $response_st;
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
             $return_data['diffInDays'] = $diffInDays;

              print json_encode($return_data);


 ?>





