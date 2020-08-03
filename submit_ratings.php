<?php
session_start();
require_once "pdo.php";

$rated_flag = 1;
$ranked_flag = 1;

// CHeck if rating form is submitted
    if(isset($_POST['rating_submit'])){

            if (isset($_POST['n'])){$n = $_POST['n'];} 
            else{
                $_SESSION['error']= 'n not found in submit_rating';
                header('Location: stu_frontpage.php'); 
                return;
            }

            if (isset($_POST['rator_student_id'])){$rator_student_id = $_POST['rator_student_id'];} 
            if (isset($_POST['peer_num'])){$peer_num = $_POST['peer_num'];}
            if (isset($_POST['assign_num'])){$assign_num = $_POST['assign_num']; }
            if (isset($_POST['currentclass_id'])){$currentclass_id = $_POST['currentclass_id']; }

            for ($i = 0; $i < $n; $i++) {
                $j=$i+1;
               $post_name = 'student_'.$j;
               $rating_id_name = 'rating_id'.$j;
               
                if(isset($_POST[$post_name])){$student_rating = $_POST[$post_name];}
                if(isset($_POST[ $rating_id_name])){$rating_id = $_POST[ $rating_id_name]; }

                // update the entries in the table for these values
               $sql = 'UPDATE Rating 
                                SET rating = :rating
                                WHERE rating_id = :rating_id';
                 $stmt = $pdo->prepare($sql);
                  $stmt->execute(array(
                 ':rating_id' => $rating_id,
                 ':rating' => $student_rating,
                    ));

         // check to see if it has been ranked
        
       $sql = 'SELECT `ranking` FROM Rating WHERE `rating_id` = :rating_id';
        $stmt = $pdo->prepare($sql);
         $stmt->execute(array(
            ':rating_id' => $rating_id,
         ));
 
        $rating_data = $stmt -> fetch();
        if($rating_data['ranking'] == null &&  !is_numeric($rating_data['ranking'])){ $ranked_flag = 0;}  // if any are null or non numeric the $ranked_flag = 0
        }
        $rated_flag = 1;
        
    }
    // same thing for ranking --------------------------------------------------------------------------------------------------------------------
      if(isset($_POST['ranking_submit'])){

            if (isset($_POST['n'])){$n = $_POST['n'];} 
            else{
                $_SESSION['error']= 'n not found in submit_rating';
                header('Location: stu_frontpage.php'); 
                return;
            }

            if (isset($_POST['rator_student_id'])){$rator_student_id = $_POST['rator_student_id'];} 
            if (isset($_POST['peer_num'])){$peer_num = $_POST['peer_num'];}
            if (isset($_POST['assign_num'])){$assign_num = $_POST['assign_num']; }
            if (isset($_POST['currentclass_id'])){$currentclass_id = $_POST['currentclass_id']; }

            for ($i = 0; $i < $n; $i++) {
                $j=$i+1;
               $post_name = 'student_'.$j;
               $rating_id_name = 'rating_id'.$j;
            //   echo (' $post_name:  '.$post_name);
            //    echo (' $ranking_id_name:  '.$rating_id_name);
                if(isset($_POST[$post_name])){$ranking = $_POST[$post_name];}
                if(isset($_POST[ $rating_id_name])){$rating_id = $_POST[ $rating_id_name]; }

                // update the entries in the table for these values
               $sql = 'UPDATE Rating 
                                SET ranking = :ranking
                                WHERE rating_id = :rating_id';
                 $stmt = $pdo->prepare($sql);
                  $stmt->execute(array(
                 ':rating_id' => $rating_id,
                 ':ranking' => $ranking,
                    ));

         // check to see if it has been ranked
        
       $sql = 'SELECT `rating` FROM Rating WHERE `rating_id` = :rating_id';
        $stmt = $pdo->prepare($sql);
         $stmt->execute(array(
            ':rating_id' => $rating_id,
         ));
 
        $rating_data = $stmt -> fetch();
        if($rating_data['rating'] == null &&  !is_numeric($rating_data['ranking'])){ $rated_flag = 0;}  // if any are null or non numeric the $ranked_flag = 0
        }
        $ranked_flag = 1;
        
    }

   header('Location: peer_rating.php?student_id='.$rator_student_id.'&peer_num='.$peer_num.'&peer_num='.$peer_num.'&assign_num='.$assign_num.'&currentclass_id='.$currentclass_id.'&rated_flag='.$rated_flag.'&ranked_flag='.$ranked_flag);
			return; 
            
            
            
  /*           
            error checking code with echo statments in them
            
            if (isset($_POST['n'])){$n = $_POST['n']; echo(' n: '.$n);} else{echo' no n ';}
if (isset($_POST['rator_student_id'])){$rator_student_id = $_POST['rator_student_id']; echo(' rator_student_id: '.$rator_student_id);} else{echo' no rator_student_id ';}
if (isset($_POST['peer_num'])){$peer_num = $_POST['peer_num']; echo(' peer_num: '.$peer_num);} else{echo' no peer_num ';}
if (isset($_POST['assign_num'])){$assign_num = $_POST['assign_num']; echo(' assign_num: '.$assign_num);} else{echo' no assign_num ';}
if (isset($_POST['currentclass_id'])){$currentclass_id = $_POST['currentclass_id']; echo(' currentclass_id: '.$currentclass_id);} else{echo' no currentclass_id ';}





  if(isset($_POST[$post_name])){$student_rating = $_POST[$post_name]; echo(' student_rating: '.$student_rating);}
    if(isset($_POST[ $rating_id_name])){$rating_id = $_POST[ $rating_id_name]; echo(' rating_id: '.$rating_id);}
    echo '<br>';

 */
?>