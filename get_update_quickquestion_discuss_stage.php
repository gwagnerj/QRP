<?php 
require_once "pdo.php";
$hours_active = 1.5;
$iid = 1;
$currentclass_name = "Testing Problems";
$question_id = 77;
$currentclass_id = 44;  //?  44 is the testing problems class
$email_flag = false;
$discuss_stage ="1";
$success['num_emails'] = '0';
$success['flag'] = false;
// JSON.stringify({iid:iid, course:send_course_name, question_id:question_send_id})

$json = file_get_contents("php://input"); // json string

$object = json_decode($json); // php object
if ($object){
        $iid = $object->iid;  // pulls the iid value out of the key value 
        $currentclass_name = $object->course;  
         $question_id = $object->question_id;  
}



//? get the info for the current class
$sql = "SELECT currentclass_id FROM CurrentClass WHERE `name` = :currentclass_name AND iid = :iid";
$stmt = $pdo->prepare($sql);	
$stmt->execute(array(
':currentclass_name'	=> $currentclass_name,
':iid'	=> $iid,
));
$currentclass_id= $stmt->fetch(PDO::FETCH_COLUMN);

// echo ($currentclass_id);

date_default_timezone_set('America/New_York');
$now = date('Y-m-d');

//? get all of the students info in that class

$sql = 'SELECT * FROM StudentCurrentClassConnect
JOIN Student ON StudentCurrentClassConnect.student_id = Student.student_id
WHERE currentclass_id = :currentclass_id';  
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
        ':currentclass_id' => $currentclass_id
        ));
$studentccconnect_data = $stmt->fetchALL(PDO::FETCH_ASSOC);
$i=0;
foreach ($studentccconnect_data as $sccc_datum){
        $student_ids[$i] = $sccc_datum['student_id'];
        $i++;
}
$i = 0;
foreach ($student_ids as $student_id){


        // echo 'key_code: ' . $key_code;
        // UPDATE table_name
        // SET column1 = value1, column2 = value2, ...
        // WHERE condition;

        $sql = "UPDATE QuickQuestionActivity SET discuss_stage = :discuss_stage
                WHERE student_id =:student_id AND currentclass_id = :currentclass_id AND question_id = :question_id AND try_number = :try_number";
                $stmt = $pdo->prepare($sql);	
                $stmt->execute(array(
                        ':discuss_stage'	=> 3,
                        ':question_id'	=> $question_id,
                        ':currentclass_id'	=> $currentclass_id,
                        ':student_id'	=> $student_id,
                        ':try_number'	=> "1",
                ));

                $i++;
}
if ($i>0){
        $success['updates'] = $i;
        $success['flag'] = true;  
}
echo json_encode($success);


?>