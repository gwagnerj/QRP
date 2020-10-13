<?php
require_once "pdo.php";
session_start();
	
    
   if (isset($_POST['examactivity_id']) && isset($_POST['display_ans_key'] )  && isset($_POST['part'] ) ){
		
        
       
            $examactivity_id = $_POST['examactivity_id'];
             $display_ans_key = $_POST['display_ans_key'];
            $part = $_POST['part'];
        /*      
            $examactivity_id = 65;
            $display_ans_key = 'display_ans_pblm1';
            $part = 0;
           */  
            
            $display_ans_input = array(0,0,0,0,0,0,0,0,0,0);
          //  var_dump($display_ans_input);

           $sql = 'SELECT '.$display_ans_key.' FROM Examactivity WHERE examactivity_id = :examactivity_id';
            $stmt = $pdo->prepare($sql);
             $stmt->execute(array(
             ":examactivity_id" => $examactivity_id, 
             ));
                $display_activity_data = $stmt->fetch();
                if ( $display_activity_data[ $display_ans_key]=='Null' || $display_activity_data[ $display_ans_key] ==false)
                {
                   $display_ans_input = array(0,0,0,0,0,0,0,0,0,0); 
                } else {
                     $display_ans_input = $display_activity_data[$display_ans_key];  // this will be a string that we need to turn inot an array
                //     var_dump($display_ans_input);

                    $display_ans_input = explode(",",$display_ans_input);

                }
                 $display_ans_input[$part] = 1;  
               
                $display_ans_input = implode(",",$display_ans_input);
                echo(' display_ans_input: '.$display_ans_input);
        
                $stmt = $pdo->prepare("UPDATE `Examactivity` SET ".$display_ans_key." = :display_ans_input WHERE examactivity_id = :examactivity_id ");
                $stmt->execute(array(
                ":examactivity_id" => $examactivity_id,
                ":display_ans_input" => $display_ans_input,
            ));

	}
 ?>





