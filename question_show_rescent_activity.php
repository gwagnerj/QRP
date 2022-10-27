<?php
require_once "pdo.php";
include 'phpqrcode/qrlib.php'; 
session_start();

    $iid = '1';
   
    if(isset($_POST["iid"])){
        $iid = $_POST["iid"];
    }elseif(isset($_GET["iid"])){
        $iid = $_GET["iid"];
    }
    

    $currentclass = 'Testing Problems';
    if(isset($_POST["currentclass"])){
        $currentclass = $_POST["currentclass"];
    }elseif(isset($_GET["currentclass"])){
        $currentclass = $_GET["currentclass"];
    }

    $open_window_d = DATETime::createFromFormat('Y-m-d',date('Y-m-d')); $open_window_d -> modify('-7 day'); date_default_timezone_set('America/Indiana/Indianapolis'); 
    $close_window_d = DATETime::createFromFormat('Y-m-d',date('Y-m-d'));
    $open_window_d_str = $open_window_d->format('Y-m-d');
    $close_window_d_str = $close_window_d -> format('Y-m-d');
    $date_now = DATETime::createFromFormat('Y-m-d',date('Y-m-d'));


//? If we changed them in the form reset the dates
    if(isset($_POST["open_window_d"])){
        $open_window_d_str = $_POST["open_window_d"];
        $open_window_d = DATETime::createFromFormat('Y-m-d',date($_POST["open_window_d"]));
    }elseif(isset($_GET["open_window_d"])){
        $open_window_d_str = $_GET["open_window_d"];
        $open_window_d = DATETime::createFromFormat('Y-m-d',date($_GET["open_window_d"]));
    }
    if(isset($_POST["close_window_d"])){
        $close_window_d_str = $_POST["close_window_d"];
        $close_window_d = DATETime::createFromFormat('Y-m-d',date($_POST["close_window_d"]));
        // $close_window_d ->modify('+1 day');

    }elseif(isset($_GET["close_window_d"])){
        $close_window_d_str = $_GET["close_window_d"];
        $close_window_d = DATETime::createFromFormat('Y-m-d',date($_GET["close_window_d"]));
        // $close_window_d ->modify('+1 day');

    }

    

    $close_window_d_query = DATETime::createFromFormat('Y-m-d',date($close_window_d_str));
    $close_window_d_query -> modify('+1 day'); 
    
    $close_window_d_str_query = $close_window_d_query -> format('Y-m-d');

    // var_dump($close_window_d_query);
    // var_dump($close_window_d);

    $diff = date_diff($open_window_d, $close_window_d);
    $days_diff = abs($diff->format("%a")); 


    $sql = "SELECT currentclass_id FROM CurrentClass WHERE  `name` = :currentclass";

    $stmt = $pdo->prepare($sql);	
    $stmt->execute(array(
        ':currentclass'	=> $currentclass,
       
    ));
    $currentclass_id_ar = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentclass_id = $currentclass_id_ar['currentclass_id'];




    $sql = "SELECT 
    QuickQuestionActivity.student_id AS student_id,
    Student.first_name as first_name,
    Student.last_name as last_name

    FROM QuickQuestionActivity
    LEFT JOIN Student ON QuickQuestionActivity.student_id = Student.student_id
    WHERE QuickQuestionActivity.currentclass_id = :currentclass_id 
    AND Student.username NOT LIKE 'temp%'
    AND QuickQuestionActivity.created_at  >= :open_window_d_str AND QuickQuestionActivity.created_at <= :close_window_d_str
   GROUP BY student_id
    ORDER BY last_name ASC
    
    ";
//     $sql = "SELECT 
//     QuickQuestionActivity.student_id AS student_id,
//     Student.first_name as first_name,
//     Student.last_name as last_name

//     FROM QuickQuestionActivity
//     LEFT JOIN Student ON QuickQuestionActivity.student_id = Student.student_id
//     WHERE QuickQuestionActivity.currentclass_id = :currentclass_id 
//     AND Student.username NOT LIKE 'temp%'
//     AND QuickQuestionActivity.created_at  BETWEEN :open_window_d_str AND :close_window_d_str 
//    GROUP BY student_id
//     ORDER BY last_name ASC
    
//     ";

        $stmt = $pdo->prepare($sql);	
        $stmt->execute(array(
            ':currentclass_id'	=> $currentclass_id,
            ':open_window_d_str' => $open_window_d_str,
            ':close_window_d_str' => $close_window_d_str_query,
        ));

        $student_name_ar = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // var_dump($student_name_ar);
        // die();


    $sql = "SELECT 
    question_id ,
    QuickQuestionActivity.created_at AS created_at
    FROM QuickQuestionActivity
    LEFT JOIN Student ON QuickQuestionActivity.student_id = Student.student_id
    WHERE QuickQuestionActivity.currentclass_id = :currentclass_id 
    AND Student.username NOT LIKE 'temp%'
    AND QuickQuestionActivity.created_at  BETWEEN :open_window_d_str AND :close_window_d_str
   GROUP BY question_id
   ORDER BY QuickQuestionActivity.created_at ASC, question_id ASC
    ";

        $stmt = $pdo->prepare($sql);	
        $stmt->execute(array(
            ':currentclass_id'	=> $currentclass_id,
            ':open_window_d_str' => $open_window_d_str,
            ':close_window_d_str' => $close_window_d_str_query,

        ));

        $question_ids = $stmt->fetchALL();

//! there is a better way to do this just using sql but....
        $i = 0;
      foreach ($student_name_ar as $student_name){
        $student_id = $student_name['student_id'];
        $sql = "SELECT question_id, response_st
        
    
        FROM QuickQuestionActivity
        
        WHERE currentclass_id = :currentclass_id 
                AND created_at BETWEEN :open_window_d_str AND :close_window_d_str
                AND student_id = :student_id
      
        ORDER BY  created_at ASC, question_id ASC
        
        ";
    
            $stmt = $pdo->prepare($sql);	
            $stmt->execute(array(
                ':currentclass_id'	=> $currentclass_id,
                ':student_id'	=> $student_id,
                ':open_window_d_str' => $open_window_d_str,
                ':close_window_d_str' => $close_window_d_str_query,
       
            ));
            $students[$i] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $i++;
      }

    //  var_dump($students);
      

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="McKetta.png" />  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Rescent Response</title>

    <style>

    </style>




</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
            <button id = "toggle_question"type="button" class="btn btn-outline-secondary mx-3 "> <i class="bi bi-question-circle"></i> Toggle Question</button>
           
        </nav>

        <h1> Show Rescent Activity of Students Responding to Quick Questions </h1>

        <h2 class = "text-primary"> For <?php echo $currentclass; ?></h2>
      <form class = "form" action = "#" method = "POST">
        <input type = "hidden" name = "iid" value = "<?php echo $iid;?>">
        <input type = "hidden" name = "currentclass" value = "<?php echo $currentclass;?>">
        <span class = "text-secondary fs-4 ms-5">Date Range </span>
    From: <input type = "date" class = "my-2" name ="open_window_d" id = "open_window_d" value="<?php  echo $open_window_d->format('Y-m-d');  ?>"  ></input>&nbsp;
       To: <input type = "date" class = "my-2" name ="close_window_d" id = "close_window_d" value="<?php  echo $close_window_d->format('Y-m-d'); ?>"  ></input>&nbsp;
      <button type="submit" class = "btn btn-outline-secondary btn-sm ms-3 " id = "date_range_change_submit">Submit Date </button>
    </form>
    <div class = "table_container mt-3">
        <table class = "table table-striped  main_table">
            <th scope='col'>First</th>
            <th scope='col'>Last &nbsp;&nbsp;&nbsp;&nbsp; Question ID -></th>

           <?php 
           foreach ($question_ids as $question_id){
                echo "<th scope='col'> ";
                echo $question_id['question_id'];
                echo " </th>";
           }
           echo "</tr>";
           echo "<td></td>";
           echo "<td>Date --></td>";
           $emms = array();
           $m = 0;
           foreach ($question_ids as $question_id){
                $temp_date[$m] = date_create($question_id['created_at']);
              
                $min_diff = 0;
                if($m !=0){
                    $diff = date_diff($temp_date[$m-1], $temp_date[$m]);
                    $min_diff = abs($diff->format("%i"));

                }
                
                if($m==0 || $min_diff>=10 ){
                    echo "<td class = 'border-start'> ";
                   
                    array_push($emms,$m);
                    echo date_format($temp_date[$m],'d-M-Y');
                   
                        
                    

                } else {
                    echo "<td> ";
                }
                echo " </td>";
                $m++;
             }


           echo "</tr>";


            for ($i=0; $i<count($student_name_ar);$i++) {
                echo "<tr>";
                echo "<td>".$student_name_ar[$i]['first_name']."</td>";
                echo "<td>".$student_name_ar[$i]['last_name']."</td>";
                for ($j = 0; $j <count($question_ids);$j++) {
                   
                    if (in_array($j,$emms)){echo "<td class = 'border-start' >";} else { echo "<td>";}
                    echo $students[$i][$j]['response_st']."</td>";
                }
      //         
                echo "</tr>";
            }
           
           ?>
        </table>

    </div>
                
    </div>
   

    <script type="text/javascript" charset="utf-8">
        const date_range_change_submit = document.getElementById('date_range_change');
        const open_window_d = document.getElementById('open_window');
        
    </script>
    
</body>
</html>