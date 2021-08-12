<?php

	  if (isset($_POST['activity_id'])){
			$activity_id = $_POST['activity_id'];
           // $activity_ids = [1201,1202,1206,1205];
			$dir = 'student_work/';

        $num_files = 0;
            $file_name = $dir.$activity_id.'-'.'*';
             $files = glob($file_name);
             if($files){$num_files =count($files);}
             
	 echo ($num_files);

	//	  echo json_encode(rtrim($num_files));
	}
 ?>





