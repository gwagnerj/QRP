<?php
require_once "pdo.php";
session_start();
	
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
 
         foreach ($_POST as $param_names => $param_vals) { 
          $param_names = json_decode($param_names,true);
          foreach ($param_names as $param_name => $param_val){
            
/* 
          echo ('param_name  ');
          var_dump($param_name);
                   echo ('param_val  ');
          var_dump($param_val);
            */
     //        echo ('<br>');
   //       echo "Param: $param_name; Value: $param_val<br />\n";
     //       var_dump ($param_full_array);
     //        echo ('<br>');
     
             $param_full_array = explode("_",$param_name);

         
             $name_array = array_slice($param_full_array, 0, -2); // array ( "Hello", "World" )
             $col_name = strtolower(implode( $name_array));
              $student_id = array_slice($param_full_array, -2, 1);
             $student_id = implode( $student_id);
              $eactivity_id = array_slice($param_full_array, -1, 1);
             $eactivity_id = implode( $eactivity_id);
             
             
          
             
             if($col_name == "pnumscorenet"){$col_name = "fb_p_num_score_net";}
             if($col_name == "reflect"){$col_name = "reflect_pts";}
             if($col_name == "explore"){$col_name = "explore_pts";}
             if($col_name == "connect"){$col_name = "connect_pts";}
             if($col_name == "society"){$col_name = "society_pts";}
             if($col_name == "ecpts"){$col_name = "ec_pts";}
             if($col_name == "probtot"){$col_name = "fb_probtot_pts";}
             if($col_name == "fbreflect"){$col_name = "fb_reflect";}
             if($col_name == "fbexplore"){$col_name = "fb_explore";}
             if($col_name == "fbconnect"){$col_name = "fb_connect";}
             if($col_name == "fbsociety"){$col_name = "fb_society";}
             if($col_name == "fbproblem"){$col_name = "fb_problem";}
          /*    
             echo (' col_name '.$col_name);
             echo ('<br>');
             echo (' student_id '.$student_id);
             echo ('<br>');
             echo (' activity_id '.$activity_id);
             echo ('<br>');
            echo (' param_val '.$param_val);
             echo ('<br>'); echo ('<br>');
              */
             
           
             $sql = 'UPDATE Eactivity SET '.$col_name.'=:xyz 
                    WHERE eactivity_id = :eactivity_id
             ';
            
             
                $stmt = $pdo->prepare($sql);	
                $stmt->execute(array(
                 ":eactivity_id"   =>   $eactivity_id, 
                 ":xyz"   =>   $param_val, 
           
                ));
          }
        }
        
        
      
         echo ('success');    
            
	}
  //  echo ('fail');
 ?>





