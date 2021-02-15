<?php
	require_once "pdo.php";
	session_start();
    // var_dump ($_POST);
    // echo '<br>';
    // var_dump ($_GET);
 	// this is the normal place to start for students taking an exam and is the file redirected to from QRexam.com and QRexamPblm.org and then goes the the QRExam.php 
//  This file takes the input from the examanee and puts it in the examactivity table then goes to QRExam once this is complete
        $complete = 'QRExamRegistration2.php';
        $exam_num = '';
        $alias_num = '';
        $iid = '';
        $cclass_id = '';
        $currentclass_id = '';
        if (isset($_POST['pin'])){$pin = $_POST['pin'];} else {$pin ='';}
        $exam_code ='';
        $problem_id = '';
        $exam_code_error = 0; 
        $stu_name = '';
        $instr_last='';
        $cclass_name='';
        $dex=0;
        $eactivity_id = '';
        $eexamnow_id = '';
        $eexamtime_id = '';
     
      if(isset($_POST['checker'])){
            $checker =  $_POST['checker'];
    } elseif(isset($_GET['checker'])){
             $checker =  $_GET['checker'];
    } else {
         $checker =  "not set";
    }
     $_SESSION['checker'] = $checker;
    $checker_only =0;
     if ($checker =='checker_only'){$checker_only = 1;}
// echo ('checker: '.$checker);
// die;


   
    // we are using a printed exam with limited versions if this is non_zero
      if (isset($_GET['dex_print'])){
          $dex_print = $_GET['dex_print'];
          if ($dex_print == 0){
            $checker_only = 0;
          } else {$checker_only = 1;}
      } else {
          $dex_print = 0;
      }
      
 

        if (isset($_GET['student_id'])){
            $student_id =   $_GET['student_id'];
         } elseif (isset($_POST['student_id'])){
            $student_id =   $_POST['student_id'];
        } else {
                $_SESSION['error'] = 'lost the student_id in QRExamRegistration2 error2';
  //              header('Location:  QRExamRegistration1.php');
               die;
        }
           
            $sql = 'SELECT * FROM Student WHERE `student_id` = :student_id';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(':student_id' => $student_id));
                 $student_data = $stmt -> fetch();

            $first_name = $student_data['first_name'];
            $last_name = $student_data['last_name'];
            $stu_name = $first_name.' '.$last_name;
            $university = $student_data['university'];
        
            // look in the StudentCurrentClassConnect table to see how many entries there are if only one then go that course assignment
            $sql = 'SELECT COUNT(`currentclass_id`) FROM `StudentCurrentClassConnect` WHERE `student_id` = :student_id';
            $stmt = $pdo->prepare($sql);
                $stmt ->execute(array(
                ':student_id' => $student_id
                ));
                $num_classes = $stmt -> fetchColumn();
                
                // echo('$num_classes: '.$num_classes);
            if($num_classes ==0){
                // need to go to the select class  either modify this file to be more general or make a new file !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                header("Location: stu_getclass.php?student_id=".$student_id);
                return; 
            } elseif ($num_classes >= 1){
            // echo (' 52 ');
                // student has just one or mor classes - we will put this in the select class dialog 
                // we can get there pin and class_id
                $sql = 'SELECT StudentCurrentClassConnect.currentclass_id as currentclass_id,      
                            StudentCurrentClassConnect.pin as pin,
                             CurrentClass.name as cclass_name,
                             CurrentClass.iid as iid 

                             FROM `StudentCurrentClassConnect`
                             LEFT JOIN CurrentClass ON CurrentClass.currentclass_id =StudentCurrentClassConnect.currentclass_id
                             WHERE `student_id` = :student_id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(':student_id' => $student_id));
                    $class_data = $stmt -> fetchALL();

// var_dump ($class_data);

                    $i = 0;
                    foreach($class_data as $class_datum){
                        $pins[$i]=$class_datum['pin'];
                        $cclass_names[$i]=$class_datum['cclass_name'];
                        $currentclass_ids[$i] = $class_datum['currentclass_id'];
                       //  echo '  '.$cclass_names[$i];
                        $iids[$i]=$class_datum['iid'];
                        $i++;
                    }


                //     $pin = $class_data['pin'];   
                // $currentclass_id = $class_data['currentclass_id'];
                
                // $sql = 'SELECT * FROM `CurrentClass` WHERE `currentclass_id` = :currentclass_id';
                //     $stmt = $pdo->prepare($sql);
                //     $stmt->execute(array(':currentclass_id' => $currentclass_id));
                //     $cclass_data = $stmt -> fetch();
                //     $iid = $cclass_data['iid'];   
                // $cclass_name = $cclass_data['name']; 
                // // echo('$iid: '.$iid);
                // $cclass_id = $currentclass_id;


            } 
 
    // We submitted and have all the data
         if(isset($_POST['submit_form']) && isset($_POST['selected_class'])  && isset($_POST['student_id'])){
            $selected_class = $_POST['selected_class'];
            $currentclass_id = $currentclass_ids[$selected_class];
            $iid = $iids[$selected_class];

        //     echo ('iid: '. $iid);
        //     echo (' currentclass_id: '. $currentclass_id);
        //    echo ('eactivity_id: '. $eactivity_id);
            
            if (strlen($eactivity_id)<=0){  

                $sql = "SELECT * FROM `Eexamtime` LEFT JOIN Eexamnow ON Eexamtime.eexamtime_id = Eexamnow.eexamtime_id  WHERE Eexamtime.iid = :iid  AND Eexamtime.currentclass_id = :currentclass_id AND Eexamnow.globephase < 3 AND Eexamnow.end_of_phase > NOW() ORDER BY Eexamnow.end_of_phase DESC LIMIT 1 ";
                //   $sql = " SELECT * FROM `Examtime` WHERE iid = :iid AND exam_code = :exam_code AND currentclass_id = :currentclass_id"   ;
                    $stmt = $pdo->prepare($sql);
                    $stmt -> execute(array(
                         ':iid' => $iid,
                         ':currentclass_id' => $currentclass_id,
                    ));
                   
                    $Eexamtime_data = $stmt->fetch(PDO::FETCH_ASSOC);
                  //  var_dump($Eexamtime_data);

                    if($Eexamtime_data != false){  // there is an exam running
                        $eexamtime_id = $Eexamtime_data['eexamtime_id']; 
                        $work_time = $Eexamtime_data['nom_time'];
                        $exam_num = $Eexamtime_data['exam_num'];
                        $eexamnow_id = $Eexamtime_data['eexamnow_id'];
                        $exam_code = $Eexamtime_data['exam_code'];
                        
                     
                        // check to see if there is already an entry and they are trying to re-register then just read the Examactivity table
                            

                                    $sql = " SELECT * FROM `Eregistration` WHERE student_id = :student_id AND eexamnow_id = :eexamnow_id "   ;
                                        $stmt = $pdo->prepare($sql);
                                        $stmt -> execute(array(
                                            ':student_id' => $student_id,
                                            ':eexamnow_id' => $eexamnow_id,
                                        ));
                                        
                                        $Eregistration_data = $stmt->fetch(PDO::FETCH_ASSOC);
                                      
    //                                    var_dump($Eregistration_data);

                                        if($Eregistration_data == false){      

                            // ----------------------- put the entry into the eregistration table for this user  ------------------------------------------------------

                                            // get the pin from the student class connect table

                                            // echo ' currentclass_id '.$currentclass_id;
                                            // echo ' student_id '.$student_id;
                                            
                                            $sql = 'SELECT * FROM `StudentCurrentClassConnect` WHERE `student_id` = :student_id AND currentclass_id = :currentclass_id LIMIT 1';
                                                $stmt = $pdo->prepare($sql);
                                                $stmt->execute(array(
                                                ':student_id' => $student_id,
                                                ':currentclass_id' => $currentclass_id
                                                ));
                                                $class_data = $stmt -> fetch();
                                              //  $class_datum = $class_data[0];
                                                $pin = $class_data['pin'];   
                                     //            echo (' pin '.$pin);
                                                $dex = ($pin-1) % 199 + 2; // % is PHP mudulus function - changing the PIN to an index between 2 and 200

                                                if ($dex_print != 0){  // we are using a version of the exam with limited versions
                                                    $dex = $dex_print;
                                                } else {
                                                    $dex = ($pin-1) % 199 + 2; // % is PHP mudulus function - changing the PIN to an index between 2 and 200
                                                }


                                                $sql = "INSERT INTO `Eregistration` 
                                                                ( `student_id`,  `dex` ,  `eexamnow_id`, `exam_code`,checker_only) 
                                                        VALUES  ( :student_id, :dex,   :eexamnow_id, :exam_code, :checker_only)";
                                                $stmt = $pdo->prepare($sql);
                                                $stmt -> execute(array(
                                                    ':student_id'=> $student_id,
                                                        ':dex' => $dex,
                                                        ':eexamnow_id' => $eexamnow_id,
                                                        ':exam_code' => $exam_code,
                                                        ':checker_only' => $checker_only,
                                                ));

                                                $eregistration_id = $pdo->lastInsertId();
                                                $_SESSION["success"] = ' Registered for the exam';
                                                 header("Location: stu_exam_frontpage.php?eregistration_id=".$eregistration_id."&checker=".$checker);
                                                  die();

                                        
                                        } else {
                                                $dex = $Eregistration_data['dex'];
                                                $exam_code = $Eregistration_data['exam_code'];
                                                $eregistration_id = $Eregistration_data['eregistration_id'];
                                                // echo (' pin '.$pin);

                                                $_SESSION["success"] = 'Already registered for the exam';
                                               
                                              
                                                header("Location:stu_exam_frontpage.php?eregistration_id=".$eregistration_id."&checker=".$checker);
                                                die();

                                        } 
                                            // go onto the next step
         
                                            
                                } else { // no exam running 
                                   // echo ' no exam running ';

                                    $_SESSION['error'] = "No exam currently running";

                                }

                                                
                            //    header("Location: QRExam.php?eregistration_id=".$eregistration_id."&checker=".$checker);
                            //    die();
                                                

             } else {
                        
                $_SESSION['error'] = 'Exam Not Yet Active or Input Incorrect'; 
            }
                   

    } elseif(isset($_POST['submit_form'])) {
    //$_SESSION['cclass_id'] = $_SESSION['cclass_name'] = '';
    $_SESSION['error']='The Class Must be Selected ';
    }

 
                // this should probably go back the previous screen			
                if (isset($_POST['reset']))	{

                $iid = '';
                $stu_name = '';

                $last = '';
                $first = $exam_code = '';
                $alias_num = $exam_num = $cclass_id = '';
                $been_there_flag = 0;

                //	session_destroy();

                }


  ?>
    <!DOCTYPE html>
     <html lang = "en">
            <head>
            <link rel="icon" type="image/png" href="McKetta.png" />  
            <meta Charset = "utf-8">
                 <title>QRExam</title>
                 <meta name="viewport" content="width=device-width, initial-scale=1" /> 
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css"> 

            <style type="text/css">
                 body{ font: 14px sans-serif; }
             .wrapper{ padding: 20px; }

        
    </style>
        </head>

        <body>
        <div class="wrapper">
                <header>
                      <h1>Quick Response Exam Registration</h1>
                </header>

        <?php

        if(isset($_POST['pin']) || isset($_POST['problem_id']) || isset($_POST['iid'])){
            if ( isset($_SESSION['error']) ) {
                echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
                unset($_SESSION['error']);
            }
            if ( isset($_SESSION['success']) ) {
                echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
                unset($_SESSION['success']);
            }
        }

        ?>


     <form autocomplete="off" action = "" method="post" id = "the_form"  >
             
            <div class="form-group">   

                    <h3><font color=#003399>Your Name: </font><?php echo($stu_name);?> </h3> 
             </div>

            
             <?php
            //  echo 'pin: '.$pin.'<br>';
            //  echo 'classname: '.$class_datum['pin'].'<br>';
            //  echo 'student_id: '.$student_id.'<br>';
            //  var_dump($class_datum);
             ?> 
       
        <div class="form-group">   

                    <input autocomplete="false" name="hidden" type="text" style="display:none;">
                  
                    <input type="hidden" id = "student_id" name="student_id" value="<?php echo ($student_id)?>" >
            <b>Class Name: &nbsp; </b>

                  <?php
                    echo('<select name = "selected_class" id = "selected_class"> ');
                 if ($num_classes >1){ 
                 
                   echo ('	<option value = "" selected disabled hidden >  Select Class  </option> ');
                    $i=0;
                     foreach ($pins as $pin) {
                         echo '<option value="'.$i.'" >'.$cclass_names[$i].'</option>';
                      $i++;       
                 }

                } else {  // really don't need to have them pick the class if there is only one
                    echo '<option value="0" >'.$cclass_names[0].'</option>';
                }
                ?>
                    echo '</select>';
    
                  <br>

                    <input type="hidden" id = "examactivity_id" name="examactivity_id" value="<?php echo ($examactivity_id)?>" >
                    </div>
                    <p><input type = "submit" name = "submit_form" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
            </form>
        
    <script>

            // $("#iid").change(function(){
            //     var	 iid = $("#iid").val();

            //     $.ajax({
            //         url: 'getcurrentclass.php',
            //         method: 'post',
            //         data: {iid:iid}

            //     }).done(function(cclass){
            //             cclass = JSON.parse(cclass);
            //             // now get the currentclass_id
            //         $.ajax({
            //             url: 'getcurrentclass_id.php',
            //             method: 'post',
            //             data: {iid:iid}

            //         }).done(function(cclass_id){
            //                 cclass_id = JSON.parse(cclass_id);
            //                 $('#current_class_dd').empty();
            //                 n = cclass.length;
            //             //		console.log("n: "+n);
            //             $('#current_class_dd').append("<option selected disabled hidden> Please Select Course </option>") ;
            //                 for (i=0;i<n;i++){

            //                         $('#current_class_dd').append('<option  value="' + cclass_id[i] + '">' + cclass[i] + '</option>');
            //                 }
            //         })
            //     })
            // });
     </script>

    </body>
    </html>




