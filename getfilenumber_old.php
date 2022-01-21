<?php

	  if (isset($_POST['activity_ids'])){
			$activity_ids = $_POST['activity_ids'];
           // $activity_ids = [1201,1202,1206,1205];
			$dir = 'student_work/';
            
            $i = 0;
            foreach ($activity_ids as $activity_id ){
                $num_files[$i] = 0;
                $file_name = $dir.$activity_id.'-'.'*';
               // echo('  file_name  '.$file_name);
                
                $files = glob($file_name);

                if ( $files !== false )
                {
                    $num_files[$i] = count( $files );
                 //   echo (' num_files:  '.$num_files);
                }
                $i++;
            }
			//echo $num_files;
		 echo json_encode($num_files);
	}
 ?>





