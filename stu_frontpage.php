<?php
	require_once "pdo.php";
	session_start();
	 //unset($_POST['change_class']);
    $currentclass_id = '';
      
       if (isset($_POST['stu_name'])){$stu_name = $_POST['stu_name'];}
      if (isset($_POST['iid'])){$iid = $_POST['iid'];}
	  if (isset($_POST['pin'])){$pin = $_POST['pin'];}
      if (isset($_POST['cclass_id'])){$cclass_id = $_POST['cclass_id']; $currentclass_id = $cclass_id;}
       if (isset($_POST['current_class_id'])){$current_class_id = $_POST['current_class_id'];}else{ $current_class_id = '';}
       if (isset($_POST['assign_num'])){$assign_num = $_POST['assign_num'];} else {$assign_num='';}
    //    if (isset($_POST['alias_num'])){$alias_num = $_POST['alias_num']; echo ('alias_num is '.$alias_num);die;} else {$alias_num=''; }
       if (isset($_POST['submit'])){$alias_num = $_POST['submit']; } else {$alias_num=''; }
        if (isset($_POST['problem_id'])){$problem_id = $_POST['problem_id'];} else {$problem_id='';}
         
     $progress = 0;
	
	$index='';
     // if (isset($_POST['stu_name'])){$stu_name = $_POST['stu_name']}
      
     if (isset($_POST['student_id'])){$student_id = $_POST['student_id'];}
     else if (isset($_GET['student_id'])){ 
        $student_id =   $_GET['student_id'];
    } else if (isset($_SESSION['student_id'])) {

      $student_id = $_SESSION['student_id'];
    } else if (isset($_GET["activity_id"])){
        $activity_id = $_GET["activity_id"];
        $sql = 'SELECT student_id FROM `Activity` WHERE `activity_id` = :activity_id';
        $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':activity_id' => $activity_id));
         $activity_data = $stmt -> fetch();
         $student_id = $activity_data['student_id'];


    } else if (isset($_POST['activity_id'])) {
        $activity_id = $_POST['activity_id'];
        $sql = 'SELECT student_id FROM `Activity` WHERE `activity_id` = :activity_id';
        $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':activity_id' => $activity_id));
         $activity_data = $stmt -> fetch();
         $student_id = $activity_data['student_id'];

    } 
    else{
     // echo "error student_id not set in stu_frontpage";
      header("Location: stu_login.php");
   //    $_SESSION['error'] = 'student_id not set in stu_frontpage';
       die();
	
    }

    //  echo ("student_id ".$student_id) ;
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
            // need to go to the select class
  //          echo("Location: stu_getclass.php?student_id=".$student_id);
             header("Location: stu_getclass.php?student_id=".$student_id);
              return; 
        } elseif ($num_classes == 1){
            
              // student has just one class so no need to select 
              // we can get there pin and class_id
            $sql = 'SELECT * FROM `StudentCurrentClassConnect` WHERE `student_id` = :student_id';
            $stmt = $pdo->prepare($sql);
             $stmt->execute(array(':student_id' => $student_id));
             $class_data = $stmt -> fetch();
             $pin = $class_data['pin'];   
             $currentclass_id = $class_data['currentclass_id'];
             
              $sql = 'SELECT * FROM `CurrentClass` WHERE `currentclass_id` = :currentclass_id';
            $stmt = $pdo->prepare($sql);
             $stmt->execute(array(':currentclass_id' => $currentclass_id));
             $cclass_data = $stmt -> fetch();
             $iid = $cclass_data['iid'];   
             $cclass_name = $cclass_data['name']; 
             // echo('$iid: '.$iid);
             $cclass_id = $currentclass_id;
            
        } else {
   
        //   echo (' student_id '.$student_id);
        //  echo (' currentclass_id '.$currentclass_id);
        // die(); //  echo (' student_id '.$student_id);
            
   
              $sql = 'SELECT * FROM `StudentCurrentClassConnect` WHERE `student_id` = :student_id AND `currentclass_id` = :currentclass_id';
            $stmt = $pdo->prepare($sql);
             $stmt->execute(array(
             ':student_id' => $student_id,
             ':currentclass_id' => $currentclass_id
             ));
             $class_data = $stmt -> fetch();
             if($class_data){$pin = $class_data['pin']; } else {$pin = '';}  
            // $currentclass_id = $class_data['currentclass_id'];
             
              $sql = 'SELECT * FROM `CurrentClass` WHERE `currentclass_id` = :currentclass_id';
            $stmt = $pdo->prepare($sql);
             $stmt->execute(array(':currentclass_id' => $currentclass_id));
             $cclass_data = $stmt -> fetch();
             
             if ($cclass_data){$iid = $cclass_data['iid']; } else {$iid ='';}
             if ($cclass_data){ $cclass_name = $cclass_data['name']; } else { $cclass_name = '';}
             
             // echo('$iid: '.$iid);
             $cclass_id = $currentclass_id; 
             // put a while $class data not false and create the drop down list or do it with JS
         //    $pin = $class_data['pin'];   
         //    $currentclass_id = $class_data['currentclass_id'];
            
        }
    

    
    // this is the normal place to start for students checking their homework and goes the the QRcontroller.  Can also come from the rtnCode.php or the back button on QRdisplay.php  
     
 
	// first time thru set scriptflag to zero - this will turn to 1 if the script ran
//	if (!isset($sc_flag)){$sc_flag=0;}
	
    if(isset($_GET['activity_id']) || isset($_POST['activity_id'])){
		if(isset($_GET['activity_id'])){$activity_id = htmlentities($_GET['activity_id']);}
        if(isset($_POST['activity_id'])){$activity_id = htmlentities($_POST['activity_id']);}
        // get the information from the activity table
       // most things will be the same except the problem_id, alias number and once they select a problem we need to 
       // check the Activity table of send all of these to the controller or somekind of pass through file
 //      echo ('alias_num '.$alias_num);
   //    echo('activity_id'.$activity_id);
     //  die;
       $sql = 'SELECT * FROM `Activity` WHERE `activity_id` = :activity_id';
        $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':activity_id' => $activity_id));
         $activity_data = $stmt -> fetch();
         if ($activity_data){
         $iid = $activity_data['iid'];   
         $pin = $activity_data['pin'];   
         $stu_name = $activity_data['stu_name'];   
         $cclass_id = $activity_data['currentclass_id']; 
         $currentclass_id = $activity_data['currentclass_id'];  
          $student_id = $activity_data['student_id']; 
         $dex = $activity_data['dex'];  
  //!       $alias_num = $activity_data['alias_num'];  
         $assign_id = $activity_data['assign_id'];  
         $progess = $activity_data['progress'];  
         } else {
           $assign_id =0;
           $iid = 0;
           $cclass_id = 0;
         }

     $sql = 'SELECT COUNT(`currentclass_id`) FROM `StudentCurrentClassConnect` WHERE `student_id` = :student_id';
      $stmt = $pdo->prepare($sql);
              $stmt ->execute(array(
            ':student_id' => $student_id
              ));
             $num_classes = $stmt -> fetchColumn();
        
        $sql = 'SELECT name FROM CurrentClass WHERE currentclass_id = :currentclass_id';
         $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':currentclass_id' => $currentclass_id));
         $class_data = $stmt -> fetch();
         if($class_data){
          $class_name = $class_data['name'];
          $cclass_name = $class_data['name'];
         }
        $sql = 'SELECT `assign_num` FROM Assign WHERE assign_id = :assign_id AND iid = :iid AND currentclass_id = :currentclass_id';
         $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':assign_id' => $assign_id,
                    ':iid' => $iid,
                     ':currentclass_id' => $cclass_id,
         ));
         $assign_data = $stmt -> fetch();
         if ($assign_data){
         $assign_num = $assign_data['assign_num'];
         }
      } 
        // this is the first time through if we do not have an activity_id - The activity is unique for a particular student for a particular problem
        $activity_id = '';
    
	if(isset($_POST['stu_name'])){
		$stu_name = htmlentities($_POST['stu_name']);
	} 

  // echo ("cclass_id ".$cclass_id );
    if ($cclass_id>0){
        $current_class_id = $cclass_id;
       $sql = 'SELECT * FROM CurrentClass WHERE currentclass_id = :currentclass_id';
         $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':currentclass_id' => $currentclass_id));
         $class_data = $stmt -> fetch();
         $class_name = $class_data['name'];
         $cclass_name = $class_data['name'];
         $iid = $class_data['iid'];  
    }
 //    echo ('alias_num '.$alias_num);
    // die;
    
// Go get the problem id from the Assignment table
	if(isset($_POST['assign_num'])&& isset($alias_num)&& isset($_POST['iid']) && isset($_POST['cclass_id']) && isset($_POST['pin'])) {
//	 if(isset($_POST['submit'])&& isset($_POST['assign_num'])&& isset($alias_num)&& isset($_POST['iid']) && isset($_POST['cclass_id']) && isset($_POST['pin'])) {
        $assign_num = htmlentities($_POST['assign_num']);
	//	$alias_num = htmlentities($_POST['alias_num']);
		$cclass_id = htmlentities($_POST['cclass_id']);
        $pin = htmlentities($_POST['pin']);
// need to get the info from the assignment table so I can check to see if we already have an activity Id for this
        $sql = 'SELECT * FROM `Assign` WHERE `currentclass_id` = :cclass_id  AND `iid`=:iid  AND `assign_num` = :assign_num AND `alias_num` = :alias_num';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':cclass_id' => $cclass_id,
                                ':iid' => $iid,
                                ':assign_num' => $assign_num,
                                ':alias_num' => $alias_num,
         ));
         $assign_data = $stmt -> fetch();
         
         $problem_id = $assign_data['prob_num'];
          $assign_id = $assign_data['assign_id'];   
        
        // check to see if we already have activity if we do we can get the activity id and move on to controller
       $sql = 'SELECT `activity_id` FROM `Activity` WHERE `problem_id` = :problem_id AND `currentclass_id` = :cclass_id AND student_id = :student_id AND `pin` = :pin AND `iid`=:iid AND `assign_id` = :assign_id ';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':problem_id' => $problem_id,
                                ':cclass_id' => $cclass_id,
                                ':pin' => $pin,
                                ':student_id' => $student_id,
                                ':iid' => $iid,
                                ':assign_id' => $assign_id
         ));
         $activity_data = $stmt -> fetch();
         $activity_id = $activity_data['activity_id'];
// echo ("activity_id".$activity_id); 
        if(strlen($activity_id)< 1){
        //  echo ("am I here");  
           // make a new entry in the activity table 
   //? I don't think we use the pin for anything anymore        
           if (!is_numeric($pin) || $pin>10000 || $pin<=0){
       //          $_SESSION['error']='Your PIN is nonnumeric less than 1 or out of range 1st.';	
            } else {
                $dex = ($pin-1) % 199 + 2; // % is PHP mudulus function - changing the PIN to an index between 2 and 200
            }
					
            // get the name of the current class from the CurrentClass table
            $sql = "SELECT * FROM `CurrentClass` WHERE currentclass_id = :cclass_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':cclass_id' => $cclass_id,
                    ));        
                 $currentclass_data = $stmt -> fetch();
                 $cclass_name = $currentclass_data['name'];
             
             // get the info on the instructor from the Users table
            
                $sql = "SELECT `last` FROM `Users` WHERE users_id = :iid";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(
                        ':iid' => $iid,
                        ));        
                     $users_data = $stmt -> fetch();
                     $instr_last = $users_data['last'];
                     
                      $sql = "SELECT `assigntime_id` FROM `Assigntime` WHERE iid = :iid AND assign_num = :assign_num AND currentclass_id = :currentclass_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(
                        ':iid' => $iid,
                        ':assign_num' => $assign_num,
                        ':currentclass_id' => $current_class_id,
                        ));        
                     $assigntime_data = $stmt -> fetch();
                     $assigntime_id = $assigntime_data['assigntime_id'];



                    

                        // go the controller
                        //cludge - look up the pin from the table again before we pout it in the table this is a patch _______________________________________________________________________
            $sql = 'SELECT * FROM `StudentCurrentClassConnect` WHERE `student_id` = :student_id AND `currentclass_id` = :currentclass_id';
            $stmt = $pdo->prepare($sql);
             $stmt->execute(array(
             ':student_id' => $student_id,
             ':currentclass_id' => $cclass_id
             ));
             $class_data = $stmt -> fetch();
             $pin = $class_data['pin'];  

              if (!is_numeric($pin) || $pin>10000 || $pin<=0){
                     $_SESSION['error']='Your PIN is nonnumeric less than 1 or out of range 2nd.';	
                     
                     
                } else {
                    $dex = ($pin-1) % 199 + 2; // % is PHP mudulus function - changing the PIN to an index between 2 and 200
                }             
             
             
                    // check the activity table and see if there is an entry if not make a new entry and go to the controller
                    $sql = 'SELECT activity_id FROM Activity WHERE assign_id = :assign_id AND currentclass_id = :cclass_id AND student_id = :student_id ';
                     $stmt = $pdo->prepare($sql);
                        $stmt -> execute(array(
                       ':student_id' => $student_id,
                       ':assign_id' => $assign_id,
                        ':cclass_id' => $cclass_id,
                        ));
                    
                    $activity_data_check = $stmt -> fetch();
                    
                    if ( $activity_data_check == false){
                    
                         
                        $sql = 'INSERT INTO Activity (problem_id, pin, iid, dex, student_id,  assign_id, assigntime_id,  instr_last, university, pp1, pp2, pp3, pp4, post_pblm1, post_pblm2, post_pblm3, score, progress, stu_name, alias_num, currentclass_id, count_tot)	
                                             VALUES (:problem_id, :pin, :iid, :dex,:student_id, :assign_id, :assigntime_id, :instr_last,:university,:pp1,:pp2,:pp3,:pp4,:post_pblm1,:post_pblm2,:post_pblm3, :score,:progress, :stu_name, :alias_num, :cclass_id, :count_tot)';
                        $stmt = $pdo->prepare($sql);
                        $stmt -> execute(array(
                        ':problem_id' => $problem_id,
                        ':pin' => $pin,
                        ':iid' => $iid,
                        ':dex' => $dex,
                         ':student_id' => $student_id,
                       ':assign_id' => $assign_id,
                       ':assigntime_id' => $assigntime_id,
                       ':instr_last' => $instr_last,
                        ':university' => $assign_data['university'],
                        ':pp1' => $assign_data['pp_flag1'],
                        ':pp2' => $assign_data['pp_flag2'],
                        ':pp3' => $assign_data['pp_flag3'],
                        ':pp4' => $assign_data['pp_flag4'],
                        ':post_pblm1' => $assign_data['postp_flag1'],
                        ':post_pblm2' => $assign_data['postp_flag2'],
                        ':post_pblm3' => $assign_data['postp_flag3'],
                        ':score' => 0,
                        ':progress' => 1,
                        ':stu_name' => $stu_name,
                        ':alias_num' => $alias_num,
                        ':cclass_id' => $cclass_id,
                         ':count_tot' => 0
                        ));
                    
                    


                    
                          $sql = 'SELECT `activity_id` FROM `Activity` WHERE `problem_id` = :problem_id AND student_id = :student_id AND `currentclass_id` = :cclass_id AND `pin` = :pin AND `iid`=:iid AND `assign_id` = :assign_id ';
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute(array(':problem_id' => $problem_id,
                                ':cclass_id' => $cclass_id,
                                ':pin' => $pin,
                                ':iid' => $iid,
                                ':student_id' => $student_id,
                                ':assign_id' => $assign_id
                                ));
                                $activity_row =$stmt ->fetch();		
                                $activity_id = $activity_row['activity_id'];
                                
                    } else {


                        $activity_id =  $activity_data_check['activity_id'];

                    }                        
                  //   echo (" activity_data (new value):  ".$activity_id;     
         }   
    //    echo(' $activity_id  '.$activity_id);
    //    die();
          header("Location: QRcontroller.php?activity_id=".$activity_id);  // normal place to exit
			return; 
		
     } elseif(isset($_POST['submit'])) {
		
		 $_SESSION['error']='The Class, Assignment, Problem and instructor ID are all Required';
	}
    
		if (isset($_POST['reset']))	{
             header("Location: QRhomework.php");
			return; 
		}
        
        if (isset($_POST['change_class2']))	{
         //  echo (' POST["change_class2"]: '.$_POST['change_class2']);
          $currentclass_id = '';
          $student_id = $_POST['student_id'];
           $stu_name = $_POST['stu_name'];
           $cclass_name = $_POST['cclass_name'];
           $num_classes = $_POST['num_classes'];
            $pin = $_POST['pin'];
             //$pin = $_POST['pin'];
         
        //   echo (' activity_id: '.$activity_id);
        //   echo (' student_id: '.$student_id);
          unset($_POST['change_class']);
           //  $currentclass_id = 0;
			
		}
        if (isset($_POST['add_class']))	{
   //       echo ("Location: stu_getclass.php?student_id=".$student_id);
            // header("Location: stu_getclass.php?student_id=".$student_id);
			// return; 
		}
  

//? get the assignment scores if they file_exists

// echo $cclass_id;
// echo $iid;
// echo $assign_num;

// if (isset($cclass_id) && isset($iid) && isset($assign_num)){

//   $sql = "SELECT assigntime_id FROM `Assigntime` WHERE currentclass_id = :currentclass_id AND iid = :iid AND assign_num = :assign_num";
//   $stmt = $pdo->prepare($sql);
//   $stmt->execute(array(
//       ':currentclass_id' => $cclass_id,
//       ':iid' => $iid,
//       ':assign_num' => $assign_num,
//       ));        
//    $assigntime_data = $stmt -> fetch();
// }

// if($assigntime_data){
//   $assigntime_id = $assigntime_data['assigntime_id'];
// }

// //var_dump ($assigntime_data);

// if ($student_id && isset($assigntime_id)){

//   $sql = "SELECT * FROM `Assignscore` WHERE assigntime_id = :assigntime_id AND student_id = :student_id";
//   $stmt = $pdo->prepare($sql);
//   $stmt->execute(array(
//       ':assigntime_id' => $assigntime_id,
//       ':student_id' => $student_id,
//       ));        
//    $assignscore_data = $stmt -> fetch();
// }

// if (isset($assignscore_data)){
//   $qr_tot = $assignscore_data['qr_tot'];
//   $other_pblm = $assignscore_data['other_pblm'];
//   $assign_ec = $assignscore_data['assign_ec'];
//   $assign_tot = $assignscore_data['assign_tot'];

// } else {
//   $qr_tot = $other_pblm = $assign_ec = $assign_tot = 0;
// }




        
if (isset($_SESSION['error'])){
	echo $_SESSION['error']	;
	unset($_SESSION['error']);
	}
	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRHomework</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

<style>
  .btn-group-line{
   display: inline;
  }
  .hidden{ 
    display: none;
  }
  .disable{ 
    opacity: 0.5;
    pointer-events: none;
  }
table.main_table{
  table-layout: fixed;
  width: 100%;  
}
 
#feedback-container{
    display:flex;
}
.card-col{ 
  align-self: normal;
}
 

</style>
</head>
<nav class="navbar sticky-top  navbar-light bg-light">

<form method = "POST" class="container-fluid ">
		<!-- <p><button type = "submit"  name = "reset" class = "btn btn-warning  btn-outline-primary position-relative  bottom-0 start-0 justify-content-start"  > <i class="bi bi-box-arrow-left"></i> Back to Login </button> -->
		<!-- <p><button type = "submit"  name = "reset" class = "btn btn-warning position-relative  bottom-0 start-0 justify-content-start"  > <i class="bi bi-box-arrow-left"></i> Back to Login </button> -->
 
  </form>  
  <div class="position-relative">
   <p><button name = "add_class" id="add-class" class = "btn btn-sm btn-outline-secondary position-absolute top-100 end-0 translate-middle"> Add Another Class <i class="bi bi-clipboard-plus"></i></button>
</div>

</nav>

<body class = "ms-5">
<header>
<h1>Welcome to Quick Response Homework </h1>
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

<form name = "go-on-get" action="stu_getclass.php" method = "post" id="go-on-get">
            <input type = "hidden" name = "student_id" value = "<?php echo ( $student_id)?>" >
        </form>


<form id = "big_form" autocomplete="off" method="POST" >
	  
	<h5 >Name: <?php echo($stu_name);?></h5> 
	<input autocomplete="false" name="hidden" type="text" style="display:none;">
	
    <input type="hidden" id = "iid" name="iid" value="<?php echo ($iid);?>" > 
    <input type="hidden" id = "pin" name="pin" value="<?php echo ($pin);?>" >
    <input type="hidden" id = "cclass_id" name="cclass_id" value="<?php echo ($currentclass_id);?>" >
    <input type="hidden" id = "stu_name" name="stu_name" value="<?php echo ($stu_name);?>" >
	<input type="hidden" id = "student_id" name="student_id" value="<?php echo ($student_id);?>" >
<!--	<div id ="current_class_dd">	-->
			<!-- <font color=#003399>Course: </font> -->
			
			<?php
				//	echo (' num_classes: '.$num_classes);
                 //   echo (' student_id: '.$student_id);
                 //  echo (' currentclass_id '.$currentclass_id.'<br>');
                    if (isset($currentclass_id)>0 && $num_classes ==1 ){
                       // if (isset($currentclass_id)>0 ){
						echo ('<input type = "hidden" name = "cclass_id" id = "have_cclass_id" value = "'.$currentclass_id.'"></input>'); 
						echo ('<input type = "hidden" name = "cclass_name" id = "have_cclass_name" value = "'.$cclass_name.'"></input>'); 
                        echo('<h6 class = "mb-2 " >Class Name: '.$cclass_name); 
			} elseif($currentclass_id > 0){
                        
                        echo('<h6 class = "mb-2">Class Name: '.$cclass_name); 
                        echo ('<input type = "hidden" name = "cclass_id" id = "have_cclass_id2" value = "'.$currentclass_id.'"></input>'); 
                        ?>
                                <!-- <input type = "submit" value="change class" form = "change_the_class"  name = "change_class2"  size="1" style = "width: 10%; background-color: lightgrey; color: black"/> &nbsp &nbsp   -->
                                <button type = "submit" value="change class" form = "change_the_class"  name = "change_class2" class  = "btn btn-secondary position-absolute start-50  " > Change the Class </button> </h6>

                        <?php

                   } else {
						echo('<select name = "cclass_id" id = "current_class_dd">');
                        echo ('	<option value = "" selected disabled hidden >  Select Class  </option> ');
                          $sql = 'SELECT * FROM `StudentCurrentClassConnect` JOIN CurrentClass ON StudentCurrentClassConnect.currentclass_id = CurrentClass.currentclass_id WHERE StudentCurrentClassConnect.student_id = :student_id AND CurrentClass.exp_date > NOW()';
                          $stmt = $pdo->prepare($sql);
                          $stmt->execute(array(':student_id' => $student_id));
             
						while ( $class_data = $stmt -> fetch(PDO::FETCH_ASSOC)) 
							{ ?>
								<option value="<?php echo ($class_data['currentclass_id']);  ?>" <?php if ($class_data['currentclass_id']==$cclass_id){echo(" selected ");} ?> ><?php echo ($class_data['name']); ?> </option>
							<?php
							} 
						echo ('</select>');
			}
		
		?>
		<span class = "mx-5 "> Assignment Number: 
			
              <input type="hidden" name = "assign_num" id = "have_assign_num"  value="<?php echo ($assign_num);?>" >
            <?php
	
            echo(' &nbsp;<select name = "assign_num" id = "assign_num">');
			echo('</select>');
			?>
		</h6>
    <div id ="due-info" class = "text fs-5 fw-bold mb-2" ></div>
    <div id = "assignment-scores-container"> 
      <div id = "qr-tot" class = "text-secondary fs-6 fw-bold ms-4"> </div> 
      <div id = "assign-ec" class = "text-secondary fs-6 fw-bold ms-4"></div> 
      <div id = "other-pblm" class = "text-secondary fs-6 fw-bold ms-4" ></div> 
      <div id = "assign-tot" class = "text-primary fs-5 fw-bold my-2 ms_2"> </div> 
  
  </div>

       <section id="problem-cards" class="mt-1">
           <div id ="card-container" class = "feedback-container container-lg ms-2" >

               <div id = "alias_num_div" class="row my-5 align-items-center justify-conent-center">

                   <!-- <div id="alias_num_div">
                   </div> -->
                   <br>
                   <div id="files_section">
                   </div>
               </div>
           </div>
       </section>
        
		
	<!-- <p><input type = "submit" name = "submit" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>   -->
	<!--  need to figure out which homeworks had reflections and are past the due date but before the date that they closes and needs rated -->
   
 
   <br>
    <div id = "peer_rating_div">
    </div>
    
  
    </form>
	</br>
  
  <!---->
  
 
  <form method = "POST" id = "change_the_class" >
            <input type="hidden"  name="num_classes" value="<?php echo ($num_classes);?>" >
             <input type="hidden"  name="cclass_name" value="<?php echo ($cclass_name);?>" >
           <input type="hidden"  name="stu_name" value="<?php echo ($stu_name);?>" >
           <input type="hidden"  name="student_id" value="<?php echo ($student_id);?>" >
             <input type="hidden"  name="activity_id" value="<?php echo ($activity_id);?>" >
                   <input type="hidden"  name="currentclass_id" value="<?php echo ($currentclass_id);?>" >
              <input type="hidden"  name="pin" value="<?php echo ($pin);?>" >
                <input type="hidden"  name="dex" value="<?php echo ($dex);?>" >

	 </form>
    

<script>



$(document).ready( function () {
    console.log('here we go');

    let add_class = document.getElementById('add-class');
    let go_on_get = document.getElementById('go-on-get');
    add_class.addEventListener('click', function (e) {
      console.log(go_on_get);
       go_on_get.submit();
    })



   var p_num_score_net =  [];
   var p_num_score_raw = [];
   var survey_pts = [];
   var pp1_pts = [];
   var alias_nums = [];   
   var  activity_id_ar = new Array();

       function getResults (assign_num,currentclass_id,p_num_score_net,p_num_score_raw,survey_pts,pp1_pts){

            $.ajax({
                url: 'getassign_id.php',
                method: 'post',
                data: {assign_num:assign_num,currentclass_id:currentclass_id}
				
		    }).done(function(assignids){
					var assign_ids = JSON.parse(assignids);

                   var n = assign_ids.length;
                    for (i=0;i<n;i++){
                       var assign_id = JSON.parse(assign_ids[i]);
                             $.ajax({
                            url: 'getresults.php',
                            method: 'post',
                            data: {assign_id:assign_id,student_id:student_id}
                        
                        }).done(function(results){
                            var result = JSON.parse(results);
                             p_num_score_net[i] = result["p_num_score_net"];
                             p_num_score_raw[i] = result["p_num_score_raw"];
                             survey_pts[i] = result["survey_pts"];
                             pp1_pts[i] = result["pp1_pts"];
                              console.log('p_num_score_net1  '+ p_num_score_net[i]);
                       });
                        
                     }
                 }); 
                   
         }

   

	// already been through and worked a problem and now getting another one all of the input fields should be defined just need another problem
		if($('#have_assign_num').val()!= undefined){
          var assign_num = $('#have_assign_num').val();	
 //       console.log("assign_num: "+assign_num);
        }
        
    if($('#have_iid').val()!= undefined && $('#have_cclass_id').val()!= undefined && $('#have_cclass_name').val()!= undefined && $('#have_assign_num').val()!= undefined){
	
		var iid = $('#have_iid').val();
 		var cclass_id = $('#have_cclass_id').val();
		var cclass_name = $('#have_cclass_name').val();
	
//			console.log("iid: "+iid);
//			console.log("cclass_id: "+cclass_id);
//			console.log("cclass_name: "+cclass_name);








			
			$.ajax({
					url: 'getactivealias.php',
					method: 'post',
					data: {assign_num:assign_num,currentclass_id:cclass_id}
				
				}).done(function(activealias){
//				console.log('Im at 594');
					activealias = JSON.parse(activealias);
					 	 $('#alias_num_div').empty();
                       console.log(`activealias is ${activealias} in block 1`) ;
					n = activealias.length;
	//					$('#alias_num_div').append("<span class = 'my-3 text-primary'>  Select Problem for this Assignment : </span>") ;
						for (i=0;i<n;i++){

    //! this needs to be updated too
							$('#alias_num_div').append('<input  name="alias_num"  type="radio"  value="'+activealias[i]+'"/> '+activealias[i]+'&nbsp; &nbsp; &nbsp;') ;
                            alias_nums[i] = activealias[i];
                    }
   //                 console.log(' alias_nums1 '+alias_nums);
                   /*  
                    	$.ajax({
                            url: 'getresults.php',
                            method: 'post',
                            data: {assign_num:assign_num,currentclass_id:currentclass_id,student_id:student_id}
				
                            }).done(function(results){
                        //	    console.log ('Im on 611');
                               results = JSON.parse(results);
                                    // $('#alias_num_div').empty();
                                    n = results.length;
                                      console.log(' alias_nums2 '+alias_nums);   
                                    console.log('  n2 '+n);
                                 $('#alias_num_div').append('<tr><td>&nbsp; &nbsp; &nbsp;</td></tr>') ;
                                 $('#alias_num_div').append('<td>&nbsp; &nbsp; Pblm Points &nbsp;</td>') ;
                   //             console.log('typeof results'+typeof results);
                   //             console.log(' results'+results.activity_id);
                                //console.log('typeof results'+typeof results);
                                console.log(results);
                                    for (i=0;i<n;i++){
                                    var row = results[i];
                                        if(row.p_num_score_net != null){
                                            $('#alias_num_div').append('<td>&nbsp; &nbsp;'+row["p_num_score_net"]+'&nbsp;</td>') ;
                                        } else {
                                            
                                             $('#alias_num_div').append('<td></td>') ;
                                            
                                        }
                                        
                                    }
                            });
                    
                     */
				}) 
	} 
			
			// this is getting the assignment number once the course has been selected
		
        $('#alias_num_div').empty();
        
       if($('#have_cclass_id2').val()!= undefined){
          var cclass_id = $('#have_cclass_id2').val();	
        console.log("cclass_id: "+cclass_id);
        }
        else if($('#have_cclass_id').val()!= undefined){
          var cclass_id = $('#have_cclass_id').val();	
//        console.log("cclass_id:----- "+cclass_id);
        }
        
        // var cclass_id = $('#have_cclass_id').val(); 
  //          console.log("cclass_id: "+cclass_id);
            
            var currentclass_id = cclass_id;
      if (!isNaN(cclass_id))  {
               $.ajax({
					url: 'getactiveassignments2.php',
					method: 'post',
					data: {currentclass_id:currentclass_id}
				}).done(function(activeass){
					activeass = JSON.parse(activeass);
					 	 $('#assign_num').empty();

					n = activeass.length;
                   
						$('#assign_num').append("<option selected disabled hidden>  </option>") ;
                    
						for (i=0;i<n;i++){
							  $('#assign_num').append('<option  value="' + activeass[i] + '">' + activeass[i] + '</option>');
                              		 

                            //  $('#assign_num').append('<option '+if(activeass[i]==assign_num){"selected";} +'value="' + activeass[i] + '">' + activeass[i] + '</option>');
					}
                    
                    if (!isNaN(assign_num))  { $('#assign_num').val(assign_num).change();}
				}) 
      } else {
 //                  console.log('need to get ir from drop down');
                   
                 
		
        $("#current_class_dd").change(function(){
            var currentclass_id = $("#current_class_dd").val();
//			console.log ('currentclass_id: '+currentclass_id);
      
            $.ajax({
					url: 'getactiveassignments2.php',
					method: 'post',
					data: {currentclass_id:currentclass_id}
				}).done(function(activeass){
					activeass = JSON.parse(activeass);
					 $('#assign_num').empty();
				
					n = activeass.length;
  //                  console.log ('typeof activeass '+typeof activeass);
 //                    console.log ('n '+n);
					$('#assign_num').append("<option selected disabled hidden>  </option>") ;
					for (i=0;i<n;i++){
							  $('#assign_num').append('<option  value="' + activeass[i] + '">' + activeass[i] + '</option>');
					
                    }
				
                
                }) 
		}) 
      }	
      

     	$("#assign_num").change(function(){

                var	 assign_num = $("#assign_num").val();
                //   $('#have_assign_num').val(assign_num);
                if (!isNaN(cclass_id))  {currentclass_id = cclass_id;} else {
          
                var currentclass_id = $("#current_class_dd").val();}
           
 //           console.log('assign_num: '+assign_num);
 //           console.log(' currentclass_id: '+currentclass_id);
//			 console.log ('currentclass_id 2nd time: '+currentclass_id);
           


                 



	         $.ajax({
					url: 'getactivealias.php',
					method: 'post',
					data: {assign_num:assign_num,currentclass_id:currentclass_id},
				 
                    success: function(activealias,status,xhr){
                        console.log('here we go again');

                     console.log ('Im on 830 really'); 
  //                    console.log (activealias);
                      activealias = JSON.parse(activealias);


                      console.log(`activealias is ${activealias} in block 2`) ;

			 	      $('#alias_num_div').empty();
					    n = activealias.length;
		//				$('#alias_num_div').append("<span class = 'my-3 text-primary'>  Select Problem for this Assignment : </span>") ;
                       $('#alias_num_div').append('<div style = "overflow-x:auto;">');

    //?                   $('#alias_num_div').append('<table class = "main_table table-primary">');
						// $('#alias_num_div').append('<th  style="text-align:center" width="20%"> Problem: </th>') ;
                      
                        let student_id = document.getElementById('student_id').value;
console.log ("n",n);
                        for (i=0;i<n;i++){  //! n here is the number of problems so in the card system this would be the number of cards
							//could put in code to get from the activity table which problems have been attempted and which are complete and color the radio buttons different
         //! this is where we could put BS table classes               
                                let problem_number = activealias[i];
                                alias_nums[i] = activealias[i];
                                let alias_number = activealias[i];
                             var card = document.getElementById("alias_num_div");
                            let col_div  = document.createElement('div');
                            col_div.className = "card-col col-8 col-md-6 col-lg-4 g-2 ";
                            let inner_card = document.createElement('div');
                            inner_card.className = "card-body text-center py-2 px-0 bg-light ";
                            
  //!                       card.innerHTML += '<div class = "col-5 col-lg-4 col-xl-3"> <div class = "card"> <div class ="card-body text-center py-4"> <button id = "problem-btn-'+activealias[i]+'" class = "btn btn-primary" type = "submit"  name = "submit" value = "'+activealias[i]+'"> Problem '+activealias[i]+'</button> <div id = "provisional-pts_'+activealias[i]+'"> </div><div id = "extra-credit-pts_'+activealias[i]+'"> </div><div id = "late-penalty_'+activealias[i]+'"> </div><div id = "survey-pts_'+activealias[i]+'"> </div></div> </div> </div>';
   //!                          card.innerHTML += '<div class = "col-5 col-lg-4 col-xl-3"> <div class = "card"> <div class ="card-body text-center py-4"> <button id = "problem-btn-'+activealias[i]+'" class = "btn btn-primary" type = "submit"  name = "submit" value = "'+activealias[i]+'"> Problem '+activealias[i]+'</button> <div id = "provisional-pts_'+activealias[i]+'"> </div><div id = "extra-credit-pts_'+activealias[i]+'"> </div><div id = "late-penalty_'+activealias[i]+'"> </div><div id = "survey-pts_'+activealias[i]+'"> </div></div> </div> </div>';
                            
                            let card_element=document.createElement('div');
                            card_element.className = "card";


                            let card_body=document.createElement('div');
                            card_body.id = "card-body_"+activealias[i];
                            card_body.className = "card-body text-center py-4  d-flex flex-column ";

                            
                           inner_card.appendChild(card_body) ;
                           card_element.appendChild(inner_card)
                           col_div.appendChild(card_element);
    
    
                                card.appendChild(col_div);

                                var card_bodies = new Array();
                                card_bodies[i] = card_body;

                        

                        console.log ("card_bodies "+card_bodies)
                        }

                        console.log("assign_num:",assign_num);
                        console.log("currentclass_id:",currentclass_id);
                        console.log(" student_id:", student_id);
                        console.log(" nnnnnnnnn:", n);

                            $.ajax({
                                          url: 'getresults.php',
                                          method: 'post',
                                          data: {
                                              assign_num: assign_num,
                                              currentclass_id: currentclass_id,
                                              student_id: student_id,
                                              n:n
                                          },
                                          success: function (results, status, xhr) {
                                                  //  console.log ('Im on 842');
         


                                                  //                                   console.log('alias_nums 861 three ' + alias_nums); 
                                                  console.log("get results really " +results);

                                                  results = JSON.parse(results);
                                                  n2 = results.length;
                                                                                    //          console.log(' alias_nums three '+alias_nums);   
                                                                                          console.log('  n2yaw '+n2);
                                                                                    //  console.log('  n '+n);
                                                    //                                    console.log("get results really " +results[0]["activity_id"]);
                                                  //                                          
                                                  // write the net score in the table 

                                                  let due_info = document.getElementById('due-info');
                                                  let due_date = new Date();
                                                  if(results.length>0){ 
                                                    due_date = new Date(results[0]["due_date"]);
                                                 const formatted_date = due_date.toDateString();
                                                 const formatted_time = due_date.toLocaleTimeString('en-US');
                                                
                                       
                                                  due_info.innerText =' Due: '+formatted_date + ' at '+formatted_time;
                                                  let pp1_hours_before = results[0]['pp1_hours_before'];
                                                  let pre_look_pp1 = results[0]['pre_look_pp1'];
                                                  console.log("pre_look_pp1*************************",pre_look_pp1);

                                                  if (pp1_hours_before && pre_look_pp1 ==1){
                                                      console.log("pp1 hours before ------------------",pp1_hours_before);
                                                      pp1_due_date = new Date(results[0]["due_date"])
                                                      pp1_due_date.setHours(pp1_due_date.getHours()-pp1_hours_before)
                                                      now = new Date();
                                                      if(now<pp1_due_date){
                                                          const formatted_pp1_due_date = pp1_due_date.toDateString();
                                                          const formatted_pp1_due_time = pp1_due_date.toLocaleTimeString('en-US')
                                                          let pp1_node = document.createElement("p");                
                                                            let pp1_due_node = document.createTextNode("Pre-Problem Due: "+formatted_pp1_due_date+" at "+formatted_pp1_due_time);        
                                                            pp1_node.appendChild(pp1_due_node);                             
                                                            due_info.appendChild(pp1_node);     
                                                       }
                                                  }


                                                    const assigntime_id = results[0]["assigntime_id"]

                                                    console.log("assigntime_id",assigntime_id);
                                                    console.log ("student_id",student_id);

                                                    $.ajax({
                                                            url: 'get_assignscore_data.php',
                                                            method: 'post',
                                                            data: {assigntime_id:assigntime_id,student_id:student_id},
                                                    
                                                    success: function(assignscore){
                                                      console.log("assignscore",assignscore);                                                   
                                                      assignscore = JSON.parse(assignscore);
                                                           const qr_tot = assignscore['qr_tot'];
                                                           const other_pblm = assignscore['other_pblm'];
                                                           const assign_ec = assignscore['assign_ec'];
                                                           const assign_tot = assignscore['assign_tot'];
                                                           if(qr_tot && qr_tot !=0){document.getElementById("qr-tot").innerText = "Total on QR Problems: "+ qr_tot;}
                                                           if(other_pblm && other_pblm !=0){document.getElementById("other-pblm").innerText = "Total on Other Problems: "+other_pblm;}
                                                           if(assign_ec && assign_ec !=0){document.getElementById("assign-ec").innerText = "Assignment Extra Credit: "+ assign_ec;}
                                                           if(assign_tot){document.getElementById("assign-tot").innerText = "Total Score On Assignment:"+ assign_tot;}

                                                          console.log("qr_tot",qr_tot, other_pblm, assign_ec, assign_tot);


                                                    }

                                                            }); 


                                                  }

                                                  let perc_ref = new Array();
                                                  let perc_exp = new Array();
                                                  let perc_con = new Array();
                                                  let perc_soc = new Array();
                                                  let perc_any1 = new Array();
                                                  let perc_any2 = new Array();
                                                  let perc_any3 = new Array();



                                            for(var j = 0;j < n2; j++)   {

                                          //   let alias_num = i;
                                               let alias_num = results[j]["alias_num"];
    //!                                        console.log("the results at j are ",results[j]);


      //                                        console.log(` the alias number is ${alias_num}`);

                            let button = document.createElement('button');
  //                          button.id = "problem-btn_"+activealias[i];
                            button.id = "problem-btn_"+alias_num;
                            button.className =  "btn btn-primary  btn-outline-white mb-0 d-flex justify-content-between align-items-start mt-1";
                            // button.className =  "btn btn-warning mb-0 d-flex justify-content-between align-items-start mt-1";
                            button.type = "submit";
                            button.name = "submit";
                            button.value = alias_num;
                            button.innerText = 'Problem '+alias_num;

                           let percent_of_assignment = document.createElement('span');
                           percent_of_assignment.className = "text-secondary text-end ms-5 lh-1";
                           percent_of_assignment.style = "font-size:0.75rem; text-align: right; display: inline;"
                           let percent_of_assignment_text = "perc_"+alias_num;
  //                         console.log("percent_of_assignment_text",percent_of_assignment_text);
                             percent_of_assignment.innerHTML = results[j][percent_of_assignment_text]+'% of Assignment';
         

                                              let problem_title = document.createElement("div");
                                              problem_title.appendChild(button);
      

  //!                                         problem_title.innerHTML +=  '<div class="d-flex justify-content-between align-items-center ps-2 text-primary">Title</div> <div id = "Title-box" class = "border border-primary p-2 "><div class = "text-start ms-2">'+ results[j]["title"]+'</div></div></div>';
                                              problem_title.innerHTML +=  ' <div id = "Title-box" class = "border border-primary p-2 "><div class = "text-start ms-2" style = "font-size:0.85rem;">'+ results[j]["title"]+'</div></div></div>';
                                              problem_title.appendChild(percent_of_assignment);
                                        
                                              let progress_status = document.createElement("div");

                                              let progress_status_text = "Not Started";
    //! figure out progress status                                           
                                              progress_status.innerHTML = '<div class="d-flex justify-content-between align-items-center ps-2 text-primary">Status</div> <div id = "status-box" class = "border border-primary p-2 "><div class = "text-start ms-2">'+progress_status_text+'</div></div></div>';
                                              let points = document.createElement("div");

                                                let num_score_possible = 0;
                                                var wcount_bc_tot_ar = new Array();
                                               var correct_bc_tot_ar = new Array();
                                               var wrong_count_tot_ar = new Array();
                                               var correct_tot_ar =new Array();
                                               var wcount_tot_ar = new Array();
                                                var valid_part_ar = new Array();

                                                const possible_parts = "abcdefghij";
                                                for (let k = 0; k<possible_parts.length;k++){
                                                    let v = possible_parts.charAt(k)

                                                  let wcount_key = 'wcount_'+v;
                                                  let correct_key ='correct_'+v;
                                                  let wcount_bc_key = 'wcount_bc_'+v;
                                                  let bc_correct_key ='bc_correct_'+v;
                                                  let valid_part_key = 'perc_'+v+'_'+alias_num;

  //                                                console.log("valid_part_key",valid_part_key);
                                                 if(results[j][valid_part_key]>0) {valid_part_ar[k] = true;} else {valid_part_ar[k] = false;};


                                                  let wcount_parts =  results[j][ wcount_key];
                                                  let correct_parts =  results[j][ correct_key];
                                                  let wcount_bc_parts =  results[j][ wcount_bc_key];
                                                  let correct_bc_parts =  results[j][ bc_correct_key];

                                //                  console.log("correct_parts",correct_parts);

                                                  if (wcount_parts>0){wcount_tot_ar[k] =  parseInt( wcount_parts);}
                                                  if (correct_parts>0){correct_tot_ar[k] =true;} else {correct_tot_ar[k] = false;}
                                                  if ( wcount_bc_parts>0){ wcount_bc_tot_ar[k] = parseInt( wcount_bc_parts);}
                                                  if ( correct_bc_parts>0){correct_bc_tot_ar[k] = true;} else {correct_bc_tot_ar[k] = false;}



                                                    let point_part_key = 'perc_'+v+'_'+alias_num;
                                                    let point_parts = results[j][point_part_key];
                                                    if(point_parts){num_score_possible += parseInt(point_parts)}
                                                }


                                                let progress_indicator = '<table cellspacing="0" style = "border-collapse:colapse;  border-spacing:0 5px; border:1px solid #0275d8"><caption class = "mb-0 mt-2 pb-0" style = " caption-side: top;">Progress:</caption><tr class = "ms-4"><td  class = "my-0  px-1" style = "line-height: 0.7;" ></td>';
                                               
                                                for (let m = 0; m<valid_part_ar.length;m++){
                                                  let v = possible_parts.charAt(m)
                                                    if(valid_part_ar[m]){progress_indicator+='<td class = "my-0  px-1" style = "line-height: 0.7;" >'+v+'</td>'};
                                                }
                                                progress_indicator += '</tr><tr><td  class = "my-0  px-1" style = "line-height: 0.7;  text-align: left;">Base-Case</td>';
                                                for (let m = 0; m<valid_part_ar.length;m++){
                                                  let v = possible_parts.charAt(m)
                                                  let bc_icon = " bi-record";
        //                                          if (correct_bc_tot_ar[m]) {bc_icon = "bi-emoji-smile text-success";} else {if( wcount_bc_tot_ar[m]==1) {bc_icon = "bi-emoji-neutral text-secondary";} else if (wcount_bc_tot_ar[m]>1 && wcount_bc_tot_ar[m]<6){bc_icon = "bi-emoji-frown text-warning";}  else if (wcount_bc_tot_ar[m]>=6 ){bc_icon = "bi-emoji-angry text-danger";}}
                                                  if (correct_bc_tot_ar[m]) {bc_icon = "bi-check2-circle text-success";} else {if( wcount_bc_tot_ar[m]==1) {bc_icon = " bi-x-circle text-secondary";} else if (wcount_bc_tot_ar[m]>1 && wcount_bc_tot_ar[m]<6){bc_icon = " bi-x-circle text-danger";}  else if (wcount_bc_tot_ar[m]>=6 ){bc_icon = " bi-x-circle text-danger";}}
                                                    if(valid_part_ar[m]){progress_indicator+='<td class = "px-1"  style = "line-height: 0.7;" ><i class=" '+bc_icon+' m-0 p-0" style = "font-size: 0.7rem;"></i></td>'};
                                                }
                                                let problem_fb_button = "Problem";

                                                let problem_fb = '';
                                                  if (results[j]["fb_problem"]){
                                                    fb_problem = results[j]["fb_problem"]; problem_fb_button = '<button class="btn btn-outline-primary p-0 m-0" title = "Grader Feedback is available" style = "line-height: 0.9;" type="button" data-bs-toggle="offcanvas" data-bs-target="#problem-off-canvas'+j+'"> problem </button>'; 
                                                     problem_fb = '<div class="offcanvas offcanvas-start" tabindex="-1" id="problem-off-canvas'+j+'">   <div class="offcanvas-header" > <h5 class="offcanvas-title" id="offcanvasExampleLabel">Grader Feedback </h5> <button type="button" class="btn-close text-reset btn-outline-primary"  data-bs-dismiss="offcanvas" aria-label="Close"></button> </div> <div class="offcanvas-body"> </p><p class = "text-success"> Graders Response:</p> <p class = "text-success"> '+results[j]["fb_problem"].replace(/_/g," ")+'</p> </div> </div> </div>';
                                                    }









                                                progress_indicator += '</tr><tr><td  class = "mb-4  px-1" style = "line-height: 0.7;  text-align: left;">'+problem_fb_button+'</td>';

                                                for (let m = 0; m<valid_part_ar.length;m++){
                                                  let v = possible_parts.charAt(m)
                                                  let icon = "bi-record";
          //                                        if (correct_tot_ar[m] ) {icon = "bi-check2-circle text-success";} else if (wcount_tot_ar[m]){icon = " bi-x-circle text-danger";}
                                                  if (correct_tot_ar[m]) {icon = "bi-emoji-smile text-success";} else {if(wcount_tot_ar[m]==1) {icon = "bi-emoji-neutral text-secondary";} else if (wcount_tot_ar[m]>1 && wcount_tot_ar[m]<6){icon = "bi-emoji-frown text-danger";}  else if (wcount_tot_ar[m]>=6 ){icon = "bi-emoji-angry text-danger";}}
                                                    if(valid_part_ar[m]){progress_indicator+='<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi '+icon+' p-0 m-0" style = "font-size: 0.8rem;"></i></td>'};
                                                }



                                                progress_indicator += '</tr>';

                                                let valid_reflect_ref_key = 'perc_ref_'+alias_num;  //? if I put points in any of these
                                                  let valid_reflect_exp_key = 'perc_exp_'+alias_num;
                                                  let valid_reflect_con_key = 'perc_con_'+alias_num;
                                                  let valid_reflect_soc_key = 'perc_soc_'+alias_num;
                                                  let valid_reflect_any1_key = 'perc_any1_ref_'+alias_num;
                                                  let valid_reflect_any2_key = 'perc_any2_ref_'+alias_num;
                                                  let valid_reflect_any3_key = 'perc_any3_ref_'+alias_num;
                                                  let fb_reflect = fb_explore = fb_connect = fb_society = '';

//                                                  console.log('results[j]["fb_reflect"]',results[j]["fb_reflect"]);
// could turn the feedback buttons red danger for peer feedback when I get that developed

                                                  let reflect_fb_button = "Reflect";
                                                  let reflect_fb = '';
                                                  if (results[j]["fb_reflect"]){
                                                    fb_reflect = results[j]["fb_reflect"]; reflect_fb_button = '<button class="btn btn-outline-primary p-0 m-0" title = "Grader Feedback is available" style = "line-height: 0.9;" type="button" data-bs-toggle="offcanvas" data-bs-target="#reflect-off-canvas'+j+'"> Reflect </button>'; 
                                                     reflect_fb = '<div class="offcanvas offcanvas-start" tabindex="-1" id="reflect-off-canvas'+j+'">   <div class="offcanvas-header" > <h5 class="offcanvas-title" id="offcanvasExampleLabel"> Feedback </h5> <button type="button" class="btn-close text-reset btn-outline-primary"  data-bs-dismiss="offcanvas" aria-label="Close"></button> </div> <div class="offcanvas-body"> <div> <p> Question: '+ results[j]["reflect"]+'</p> <p  class = "text-primary"> Your Response: </p> <p class = "text-primary"> '+ results[j]["reflect_text"].replace(/_/g," ")+'</p><p class = "text-success"> Graders Response:</p> <p class = "text-success"> '+results[j]["fb_reflect"].replace(/_/g," ")+'</p> </div> </div> </div>';
                                                    }

                                                  let explore_fb_button = "Explore";
                                                  let explore_fb = '';
                                                  if (results[j]["fb_explore"]){
                                                    fb_explore = results[j]["fb_explore"]; explore_fb_button = '<button class="btn btn-outline-primary p-0 m-0" title = "Grader Feedback is available" style = "line-height: 0.9;" type="button" data-bs-toggle="offcanvas" data-bs-target="#explore-off-canvas'+j+'"> Explore </button>'; 
                                                     explore_fb = '<div class="offcanvas offcanvas-start" tabindex="-1" id="explore-off-canvas'+j+'">   <div class="offcanvas-header" > <h5 class="offcanvas-title" id="offcanvasExampleLabel"> Feedback </h5> <button type="button" class="btn-close text-reset btn-outline-primary"  data-bs-dismiss="offcanvas" aria-label="Close"></button> </div> <div class="offcanvas-body"> <div> <p> Question: '+ results[j]["explore"]+'</p> <p  class = "text-primary"> Your Response: </p> <p class = "text-primary"> '+ results[j]["explore_text"].replace(/_/g," ")+'</p><p class = "text-success"> Graders Response:</p> <p class = "text-success"> '+results[j]["fb_explore"].replace(/_/g," ")+'</p> </div> </div> </div>';
                                                    }

                                                  let connect_fb_button = "connect";
                                                  let connect_fb = '';
                                                  if (results[j]["fb_connect"]){
                                                    fb_connect = results[j]["fb_connect"]; connect_fb_button = '<button class="btn btn-outline-primary p-0 m-0" title = "Grader Feedback is available" style = "line-height: 0.9;" type="button" data-bs-toggle="offcanvas" data-bs-target="#connect-off-canvas'+j+'"> Connect </button>'; 
                                                     connect_fb = '<div class="offcanvas offcanvas-start" tabindex="-1" id="connect-off-canvas'+j+'">   <div class="offcanvas-header" > <h5 class="offcanvas-title" id="offcanvasExampleLabel"> Feedback </h5> <button type="button" class="btn-close text-reset btn-outline-primary"  data-bs-dismiss="offcanvas" aria-label="Close"></button> </div> <div class="offcanvas-body"> <div> <p> Question: '+ results[j]["connec_t"]+'</p> <p  class = "text-primary"> Your Response: </p> <p class = "text-primary"> '+ results[j]["connect_text"].replace(/_/g," ")+'</p><p class = "text-success"> Graders Response:</p> <p class = "text-success"> '+results[j]["fb_connect"].replace(/_/g," ")+'</p> </div> </div> </div>';
                                                    }

                                                  let society_fb_button = "society";
                                                  let society_fb = '';
                                                  if (results[j]["fb_society"]){
                                                    fb_society = results[j]["fb_society"]; society_fb_button = '<button class="btn btn-outline-primary p-0 m-0" title = "Grader Feedback is available" style = "line-height: 0.9;" type="button" data-bs-toggle="offcanvas" data-bs-target="#society-off-canvas'+j+'"> Society </button>'; 
                                                     society_fb = '<div class="offcanvas offcanvas-start" tabindex="-1" id="society-off-canvas'+j+'">   <div class="offcanvas-header" > <h5 class="offcanvas-title" id="offcanvasExampleLabel"> Feedback </h5> <button type="button" class="btn-close text-reset btn-outline-primary"  data-bs-dismiss="offcanvas" aria-label="Close"></button> </div> <div class="offcanvas-body"> <div> <p> Question: '+ results[j]["society"]+'</p> <p  class = "text-primary"> Your Response: </p> <p class = "text-primary"> '+ results[j]["society_text"].replace(/_/g," ")+'</p><p class = "text-success"> Graders Response:</p> <p class = "text-success"> '+results[j]["fb_society"].replace(/_/g," ")+'</p> </div> </div> </div>';
                                                    }

                                                    // console.log('results[j]["reflect"]',results[j]["reflect"]);  
                                           //          console.log('results[j]["connect"]',results[j]["connec_t"]);  //? this is the question being asked
                                                    // console.log('results[j]["fb_explore"]',results[j]["fb_explore"]); //? this is the feedback the grader wrote
                                                    // console.log('results[j]["explore_text"]',results[j]["explore_text"]); //? what the studetn wrote
                                                    // console.log( 'explore_fb', explore_fb);

                                                
                                                   perc_ref[j] = 0; if ( results[j][valid_reflect_ref_key]){perc_ref[j] = parseInt( results[j][valid_reflect_ref_key])}
                                                   perc_exp[j] = 0; if ( results[j][valid_reflect_exp_key]){perc_exp[j] = parseInt( results[j][valid_reflect_exp_key])} 
                                                    // console.log("results[j][valid_reflect_exp_key]",results[j][valid_reflect_exp_key]);
                                                    // console.log("perc_exp[j]",perc_exp[j]);
                                                   perc_con[j] = 0; if ( results[j][valid_reflect_con_key]){perc_con[j] = parseInt( results[j][valid_reflect_con_key])}
                                                   perc_soc[j] = 0; if ( results[j][valid_reflect_soc_key]){perc_soc[j] = parseInt( results[j][valid_reflect_soc_key])}
                                                   let any1_bol = any2_bol = any3_bol = any_bol = false;
                                                   perc_any1[j] = 0; if ( results[j][valid_reflect_any1_key]>0){perc_any1[j] = parseInt( results[j][valid_reflect_any1_key]); any1_bol = true; any_bol = true;}
                                                   perc_any2[j] = 0; if ( results[j][valid_reflect_any2_key]>0){perc_any2[j] = parseInt( results[j][valid_reflect_any2_key]); any2_bol = true; any_bol = true;}
                                                   perc_any3[j] = 0; if ( results[j][valid_reflect_any3_key]>0){perc_any3[j] = parseInt( results[j][valid_reflect_any3_key]); any3_bol = true; any_bol = true;}


                                                  let number_of_assigned_reflections = 0;
                                                  let table_addon = '<tr><td> </td></tr>';
                                                  if (perc_ref[j] !=0 ){number_of_assigned_reflections ++; table_addon += '<tr><td> '+reflect_fb_button+' </td>'; if (results[j]["reflect_text"]){table_addon += '<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi  bi-check2-circle text-secondary p-0 m-0" style = "font-size: 0.8rem;"></i></td></tr>'} else {table_addon += '<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi  bi-x-circle text-danger p-0 m-0" style = "font-size: 0.8rem;"></i></td></tr>'} } 
                                                  if (any_bol){table_addon += '<tr><td> Reflect </td>'; if (results[j]["reflect_text"]){table_addon += '<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi  bi-check2-circle text-secondary p-0 m-0" style = "font-size: 0.8rem;"></i></td></tr>'} else {table_addon += '<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi  bi-x-circle text-secondary p-0 m-0" style = "font-size: 0.8rem;"></i></td></tr>'} }
                                                  if (perc_exp[j] !=0){number_of_assigned_reflections ++; table_addon += '<tr><td> '+explore_fb_button+'</td>'; if (results[j]["explore_text"]){table_addon += '<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi  bi-check2-circle text-secondary p-0 m-0" style = "font-size: 0.8rem;"></i></td></tr>'} else {table_addon += '<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi  bi-x-circle text-danger p-0 m-0" style = "font-size: 0.8rem;"></i></td></tr>'} }
                                                  if (any_bol){ table_addon += '<tr><td> Explore </td>'; if (results[j]["explore_text"]){table_addon += '<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi  bi-check2-circle text-secondary p-0 m-0" style = "font-size: 0.8rem;"></i></td></tr>'} else {table_addon += '<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi  bi-x-circle text-secondary p-0 m-0" style = "font-size: 0.8rem;"></i></td></tr>'} }
                                                  if (perc_con[j] !=0){number_of_assigned_reflections ++;  table_addon += '<tr><td> '+connect_fb_button+'</td>'; if (results[j]["connect_text"]){table_addon += '<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi  bi-check2-circle text-secondary p-0 m-0" style = "font-size: 0.8rem;"></i></td></tr>'} else {table_addon += '<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi  bi-x-circle text-danger p-0 m-0" style = "font-size: 0.8rem;"></i></td></tr>'} }
                                                  if (any_bol){table_addon += '<tr><td> Connect </td>'; if (results[j]["connect_text"]){table_addon += '<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi  bi-check2-circle text-secondary p-0 m-0" style = "font-size: 0.8rem;"></i></td></tr>'} else {table_addon += '<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi  bi-x-circle text-secondary p-0 m-0" style = "font-size: 0.8rem;"></i></td></tr>'} }
                                                  if (perc_soc[j] !=0){number_of_assigned_reflections ++; table_addon += '<tr><td> '+society_fb_button+' </td>'; if (results[j]["society_text"]){table_addon += '<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi  bi-check2-circle text-secondary p-0 m-0" style = "font-size: 0.8rem;"></i></td></tr>'} else {table_addon += '<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi  bi-x-circle text-danger p-0 m-0" style = "font-size: 0.8rem;"></i></td></tr>'} }
                                                  if (any_bol){table_addon += '<tr><td> Society </td>'; if (results[j]["society_text"]){table_addon += '<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi  bi-check2-circle text-secondary p-0 m-0" style = "font-size: 0.8rem;"></i></td></tr>'} else {table_addon += '<td class = "px-1 mb-4"  style = "line-height: 0.7;" ><i class="bi  bi-x-circle text-secondary p-0 m-0" style = "font-size: 0.8rem;"></i></td></tr>'} }
                                                  if (perc_any1[j] !=0){number_of_assigned_reflections =1; table_addon += '<tr><td>Any 1</td></tr>'; }
                                                  if (perc_any2[j] !=0){number_of_assigned_reflections =2; table_addon += '<tr><td>Any 2</td></tr>'; }
                                                  if (perc_any3[j] !=0){number_of_assigned_reflections =3; table_addon += '<tr><td>Any 3</td></tr>'; }


                                                        console.log("number_of_assigned_reflections",number_of_assigned_reflections);

                                                if (number_of_assigned_reflections == 0) {progress_indicator += '</table>';}
                                                else 
                                                {
                                                 progress_indicator += table_addon+'</table>';
                                                }

                                                progress_indicator += reflect_fb + explore_fb + connect_fb + society_fb + problem_fb;

                                                progress_status.innerHTML = progress_indicator;



                                                let reflect_pts = 0; if (results[j]["reflect_pts"]){reflect_pts = results[j]["reflect_pts"];}
                                                let explore_pts = 0; if (results[j]["explore_pts"]){explore_pts = results[j]["explore_pts"];}
                                                let connect_pts = 0; if (results[j]["connect_pts"]){connect_pts = results[j]["connect_pts"];}
                                                let society_pts = 0; if (results[j]["society_pts"]){society_pts = results[j]["society_pts"];}
  //!                                              let total_pts = 0; if (results[j]["society_pts"]){society_pts = results[j]["society_pts"];}
                                            let fb_p_num_score_net = 0; if (results[j]["fb_p_num_score_net"]){fb_p_num_score_net = results[j]["fb_p_num_score_net"]}
                                            let fb_probtot_pts = 0; if (results[j]["fb_probtot_pts"]){fb_probtot_pts = results[j]["fb_probtot_pts"]}





                                                let provisional_points_text = results[j]["p_num_score_net"];  if (!provisional_points_text){provisional_points_text=0;}
                                                let fb_p_num_score_net_html = '<div class = "row text-start text-primary"  style = "font-size:0.9rem;"  title = "Actual Points are awarded from Grader on numerical part of problem" ><div class = "col ps-3 pe-0"> Actual: &nbsp;'+fb_p_num_score_net+' of '+ num_score_possible+'</div></div>';
                                                let fb_probtot_pts_html =''; if(fb_probtot_pts != 0) {fb_probtot_pts_html = '<div class = "row text-start text-primary"  style = "font-size:1.0rem;"  title = "Total points on Problem from Grader" ><div class = "col ps-3 pe-0"> Total for Problem: &nbsp;'+fb_probtot_pts+'</div>';} else {fb_probtot_pts_html = '<div class = "row text-start"  style = "font-size:0.7rem;"  title = "Total points on Problem from Grader" ><div class = "col ps-3 pe-0"> Total for Problem: &nbsp;'+fb_probtot_pts+'</div>';}
                                                let provisional_points_html = '<div class = "row text-start"  style = "font-size:0.9rem;" title = "Provisional Points are awarded when problem is Finished and Survey is completed"><div class = "col ps-3 pe-0"> Provisional: &nbsp;'+provisional_points_text+' of '+ num_score_possible+'</div>';
                                                  if (perc_ref[j] !=0 || any_bol){provisional_points_html += '<div class = "col px-1 me-0"> Reflect: &nbsp;'+reflect_pts+' of '+ perc_ref[j] +'</div></div>';} else {provisional_points_html += '</div>';}
                                                let extra_credit_points_text = results[j]["ec_pts"];  if (!extra_credit_points_text){extra_credit_points_text=0;}
                                               let extra_credit_points_html = '<div class = "row text-start" style = "font-size:0.9rem;"><div class = "col ps-3 pe-0"> Extra Credit: &nbsp;'+extra_credit_points_text+'</div>';
                                               if (perc_exp[j] !=0 || any_bol){extra_credit_points_html += '<div class = "col px-1"> Explore: &nbsp;'+explore_pts+' of '+ perc_exp[j] +'</div></div>';} else {extra_credit_points_html += '</div>';}

                                                let late_penalty_points_text = results[j]["late_penalty"];  if (!late_penalty_points_text){late_penalty_points_text=0;}
                                               let late_penalty_points_html = '<div class = "row text-start" style = "font-size:0.9rem;"><div class = "col ps-3 pe-0" > Late Penalty: &nbsp;'+late_penalty_points_text+'</div>';
                                               if (perc_con[j] !=0 || any_bol){late_penalty_points_html += '<div class = "col px-1"> Connect: &nbsp;'+connect_pts+' of '+ perc_con[j] +'</div></div>';} else {late_penalty_points_html += '</div>';}


                                                let survey_points_text = results[j]["survey_pts"];  if (!survey_points_text){survey_points_text=0;}
                                                let survey_points_possible = 0;
                                                let survey_points_possible_key = 'survey_'+alias_num;
                                                if(results[j][survey_points_possible_key]){survey_points_possible = results[j][survey_points_possible_key];}
                                               let survey_points_html = '<div class = "row text-start"  style = "font-size:0.9rem;"><div class = "col ps-3 pe-0">Survey: &nbsp;'+survey_points_text+' of '+survey_points_possible+'</div>';
                                               if (perc_soc[j] !=0 || any_bol){survey_points_html += '<div class = "col px-1"> Society: &nbsp;'+society_pts+' of '+ perc_soc[j] +'</div></div>';} else {survey_points_html += '</div>';}



                                               let pp1_points_text = results[j]["pp1_pts"];  if (!pp1_points_text){pp1_points_text=0;}
                                               let pp1_points_possible = 0;
                                                let pp1_points_possible_key = 'perc_pp1_'+alias_num;
                                                if(results[j][pp1_points_possible_key]){pp1_points_possible = results[j][pp1_points_possible_key];}
                                               let pp1_points_html = '<div class = "row text-start"  style = "font-size:0.9rem;"><div class = "col ps-3 pe-0">Pre Problem: &nbsp;'+pp1_points_text+' of '+pp1_points_possible+'</div>';
                                               if (perc_soc[j] !=0 || any_bol){pp1_points_html += '<div class = "col px-1"> Society: &nbsp;'+society_pts+' of '+ perc_soc[j] +'</div></div>';} else {pp1_points_html += '</div>';}


                                                points.innerHTML += '<div class="d-flex justify-content-between align-items-center ps-2 mt-3 text-primary" >Points</div> <div id = "points-box_'+alias_num+'" class = "border border-primary p-2"> '+provisional_points_html+fb_p_num_score_net_html+extra_credit_points_html+late_penalty_points_html+survey_points_html+pp1_points_html+fb_probtot_pts_html+'</div>';
                                              var card_body = [];
                                               card_body[j] = document.getElementById("card-body_"+alias_num);

// the reflections stuff



                                               card_body[j].appendChild(problem_title);
                                               card_body[j].appendChild(progress_status);
                                               card_body[j].appendChild(points);
//                                                var card_body = document.getElementById("card-body_"+alias_num);

// // the reflections stuff



//                                                card_body.appendChild(problem_title);
//                                                card_body.appendChild(progress_status);
//                                                card_body.appendChild(points);

                                                console.log(`the activity id for j = ${j} and activity_id  is ${results[j]['activity_id']}`);
                                                let activity_id_num = results[j]['activity_id'];
                                                console.log("activity_id_num",activity_id_num);

                                                    $.ajax({
                                                        url: 'getfilenumber.php',
                                                        method: 'post',
 //?                                                       data: {activity_id:results[j]['activity_id']},
                                                        data: {activity_id:activity_id_num},
                                                        
//?                                                            success: function(num_files,status){
                                                            success: function(num_files,status){
                                                              console.log("activity_id_num",activity_id_num);
                                                              console.log ("j",j);
                                                          console.log ("alias_nums",alias_num);
                                                        console.log ("num_files_type",typeof num_files);
                                                        console.log ("num_files_length",num_files.length);
                                                        console.log ("num_files",num_files);
                                                        console.log ("num_files",{num_files});
                                                              
                                                              let files_uploaded = document.createElement('div')
                                                              let upload_more_files = document.createElement('span')
 //                                                             let files_uploaded = document.createElement('button')
                                                                if(num_files){num_files = num_files;} else {num_files =0;}
                                                                
                                                                let text_color = "text-warning fw-bold";
                                                                let display_buttons_class = "disable";
                                                             if (num_files > 0 ){text_color = "text-primary"; display_buttons_class = ""; }
                                                      //        let activity_id_num = activity_id;
                                                              console.log("activity_id_num",activity_id_num);
 //                                                           files_uploaded.innerHTML = '<div id = "num-work-files-'+alias_num+'" class=" text-start my-2 ms-2">Work Files Uploaded: &nbsp; <span class = "'+text_color+'" > '+num_files+'</span></div>';
  //                                                          files_uploaded.innerHTML = '<button class = "btn btn-outline-secondary" id = "workFile-btn-'+alias_num+'" formmethod ="get" name = "workFile-btn-'+alias_num+'" value = "'+activity_id_num+'" formaction = "display_uploaded.php" formtarget = "_blank" ><div id = "num-work-files-'+alias_num+'" class=" text-start my-2 ms-2">Work Files Uploaded: &nbsp; <span class = "'+text_color+'" > '+num_files+'</span></div></button>';
                                                             files_uploaded.innerHTML = '<div class ="btn-group-line"><button class = "btn btn-outline-secondary mx-1 pd-0 btn-sm '+display_buttons_class+'" id = "workFile-btn-'+alias_num+'" formmethod ="post" name = "activity_id" value = "'+activity_id_num+'" formaction = "get_pdf.php" formtarget = "_blank" ><div id = "num-work-files-'+alias_num+'" class=" text-start my-2 ms-2">Display Uploaded Files &nbsp; <span class = "'+text_color+'" > '+num_files+'</span></div></button>';
                                                            upload_more_files.innerHTML = '<button class = "btn btn-outline-secondary btn-sm inline '+display_buttons_class+'" id = "upload_more_files-'+alias_num+'" formmethod ="post" name = "activity_id" value = "'+activity_id_num+'" formaction = "upload_work.php" formtarget = "_blank" ><div id = "upload_more-'+alias_num+'" class=" text-start my-2 ms-2">Upload More</div></button></div>';
                                                              let card_body = [];
                                                              card_body[j] = document.getElementById("card-body_"+alias_num);
                                                              console.log ("card_body[j]",card_body[j]);

                                                              files_uploaded.appendChild(upload_more_files);

                                                              card_body[j].appendChild(files_uploaded);
 //?                                                             card_body[j].appendChild(upload_more_files);
                                                           
                                                    
                                                    }
                                                          })
                                                          // let alias_num = j+1;
                                                          // let card_body = document.getElementById("card-body-"+alias_num)

                                                        


      
      //                                                 }




                                            }



                                          }



                                          
                                        });











                 
   //                        console.log('alias_nums 846 two ' + alias_nums); 
                             // console.log(' assign_num: '+assign_num);
                             // console.log(' currentclass_id: '+currentclass_id);
                  //            student_id = $("#student_id").val();
                             // console.log(' student_id: '+student_id);
                              

//                               $.ajax({
//                                           url: 'getresults.php',
//                                           method: 'post',
//                                           data: {
//                                               assign_num: assign_num,
//                                               currentclass_id: currentclass_id,
//                                               student_id: student_id
//                                           },
//                                           success: function (results, status, xhr) {
//                                                   //  console.log ('Im on 842');


//                                                   //                                   console.log('alias_nums 861 three ' + alias_nums); 

//                                                   results = JSON.parse(results);
//                                                   n2 = results.length;
//                                                   //                                            console.log(' alias_nums three '+alias_nums);   
//                                                   //                                        console.log('  n2 '+n2);
//                                                   //                                    console.log('  n '+n);
//                                                   //                                       console.log(results);
//                                                   //                                          
//                                                   // write the net score in the table 
//                                                   $('#alias_num_div').append('<tr><td>&nbsp;</td></tr>');
//                                                   $('#alias_num_div').append('<td  style="text-align:center" > Provisional Pts </td>');
//                                                   for (i = 0; i < n; i++) {
//                                                       var found = false;
//                                                       for (j = 0; j < n2; j++) {
//                                                           var row = results[j];
//                                                           if (typeof row.alias_num != "undefined") {
//                                                               if (row.alias_num == activealias[i]) {
//                                                                   found = true;
//                                                                   var row_found = row;
//                                                               }
//                                                           }
//                                                       }
//                                                       if (found) {
//                                                           if (row_found["p_num_score_net"] != null) {
//                                                               $('#alias_num_div').append('<td  style="text-align:center" >' + row_found["p_num_score_net"] + '</td>');
//                                                           } else {
//                                                               $('#alias_num_div').append('<td style="text-align:center"  > 0 </td>');
//                                                           }
//                                                       } else {
//                                                           $('#alias_num_div').append('<td></td>');
//                                                       }
//                                                   }

//                                                   // write the extra credit from the feedback
//                                                   $('#alias_num_div').append('<tr><td>&nbsp;</td></tr>');
//                                                   $('#alias_num_div').append('<td  style="text-align:center"> Extra Credit </td>');
//                                                   for (i = 0; i < n; i++) {
//                                                       var found = false;
//                                                       for (j = 0; j < n2; j++) {
//                                                           var row = results[j];
//                                                           if (typeof row.alias_num != "undefined") {
//                                                               if (row.alias_num == activealias[i]) {
//                                                                   found = true;
//                                                                   var row_found = row;
//                                                               }
//                                                           }
//                                                       }
//                                                       if (found) {
//                                                           if (row_found["ec_pts"] != null) {
//                                                               $('#alias_num_div').append('<td  style="text-align:center" >' + row_found["ec_pts"] + '</td>');
//                                                           } else {
//                                                               $('#alias_num_div').append('<td  style="text-align:center"> &nbsp; </td>');
//                                                           }
//                                                       } else {
//                                                           $('#alias_num_div').append('<td></td>');
//                                                       }
//                                                   }






//                                                   $('#alias_num_div').append('<tr><td>&nbsp; </td></tr>');
//                                                   $('#alias_num_div').append('<td  style="text-align:center"> Late Penalty </td>');
//                                                   for (i = 0; i < n; i++) {
//                                                       var found = false;
//                                                       for (j = 0; j < n2; j++) {
//                                                           var row = results[j];
//                                                           if (typeof row.alias_num != "undefined") {
//                                                               if (row.alias_num == activealias[i]) {
//                                                                   found = true;
//                                                                   var row_found = row;
//                                                               }
//                                                           }
//                                                       }
//                                                       if (found) {
//                                                           //                                               console.log('late p '+row_found.late_penalty );
//                                                           //                                               console.log('raw p '+row_found["p_num_score_raw"] );
//                                                           if (row_found["late_penalty"] != null) {
//                                                               if (row_found["late_penalty"] > row_found["p_num_score_raw"]) {
//                                                                   var late_penalty = row_found["p_num_score_raw"];
//                                                               } else {
//                                                                   var late_penalty = row_found["late_penalty"];
//                                                               }
//                                                               $('#alias_num_div').append('<td  style="text-align:center">' + late_penalty + '</td>');
//                                                           } else {
//                                                               $('#alias_num_div').append('<td>&nbsp; </td>');
//                                                           }
//                                                       } else {
//                                                           $('#alias_num_div').append('<td></td>');
//                                                       }
//                                                   }


//                                                   $('#alias_num_div').append('<tr><td>&nbsp; </td></tr>');
//                                                   $('#alias_num_div').append('<td style="text-align:center" > Survey </td>');
//                                                   for (i = 0; i < n; i++) {
//                                                       var found = false;
//                                                       for (j = 0; j < n2; j++) {
//                                                           var row = results[j];
//                                                           if (typeof row.alias_num != "undefined") {
//                                                               if (row.alias_num == activealias[i] && row.survey_pts > 0 && row.survey_pts != null) {
//                                                                   var row_found = row;
//                                                                   //                                                           console.log (row.survey_pts);
//                                                                   found = true;
//                                                               }
//                                                           }
//                                                       }
//                                                       if (found) {
//                                                           $('#alias_num_div').append('<td  style="text-align:center"> ' + row_found["survey_pts"] + ' </td>');
//                                                       } else {
//                                                           $('#alias_num_div').append('<td></td>');
//                                                       }
//                                                   }

//           // start problem  feedback
//                                         $('#alias_num_div').append('<tr><td>&nbsp;</td></tr>') ;
//                                         $('#alias_num_div').append('<td style="text-align:center">Comments</td>') ;
//                                        for (i=0;i<n;i++){  // go through and see if they have attempted that part at all
//                                             var found = false;
//                                             var fbtext = false;
//                                             for (j=0;j<n2;j++){
//                                                   var row = results[j];
//                                                    if (typeof row.alias_num != "undefined" ){
//                                                               found = true;
//                                                              if (row.fb_problem != null){
//                                                                      if (row.fb_problem.length > 1 ) { var row_found = row; fbtext = true;}
//                                                               }
//                                                     }
//                                               }       
                                            
//                                              if(fbtext && found){
//                                                   $('#alias_num_div').append('<td  style="text-align:center"> '+row_found["fb_problem"].replace(/_/g, ' ')+'</td>') ;
//                                              } else {
//                                              $('#alias_num_div').append('<td> </td>') ;  
//                                              } 
                                                                                       
//                                         }

//                              //  }
                               
                               
                               
                               
//         // start reflection part of the feedback
//                                         $('#alias_num_div').append('<tr><td>&nbsp;</td></tr>') ;
//                                         $('#alias_num_div').append('<td style="text-align:center">Reflect </td>') ;
//                                         for (i=0;i<n;i++){
//                                             var found = false;
//                                             var wrote = false;
//                                             var fbpts = false;
//                                             var fbtext = false;
//                                             for (j=0;j<n2;j++){ 
//                                                 var row = results[j];
//                                                    if (typeof row.alias_num != "undefined" ){
//                                                          if(row.alias_num == activealias[i] && row.reflect_flag ==1){
//                                                               found = true;
//                                                              if (row.reflect_pts > 0 ) { var row_found = row; fbpts = true;}
//                                                              if (row.fb_reflect != null){
//                                                                 if (row.fb_reflect.length > 1 ) { var row_found = row; fbtext = true;}
//                                                              }
//                                                              if (row.reflect_text != null){
//                                                                  if (row.reflect_text.length > 2){
//                                                                      wrote = true;
//                                                                      if (row.reflect_pts > 0 ) { var row_found = row; fbpts = true;}
//                                                                      if (row.fb_reflect){
//                                                                      if (row.fb_reflect.length > 1 ) { var row_found = row; fbtext = true;}}
//                                                                   }
//                                                               }
//                                                          }                                                       
//                                                     }
                                                    
//                                             }
//                                              if(fbpts && fbtext){
//                                                   $('#alias_num_div').append('<td  style="text-align:center">'+row_found["reflect_pts"]+'  <br> '+row_found["fb_reflect"].replace(/_/g, ' ')+'</td>') ;
//                                              } else if (fbpts) {
//                                                   $('#alias_num_div').append('<td  style="text-align:center">'+row_found["reflect_pts"]+'  </td>') ;
//                                              } else if (fbtext) {
//                                                   $('#alias_num_div').append('<td  style="text-align:center"> '+row_found["fb_reflect"].replace(/_/g, ' ')+'</td>') ;
//                                              }
//                                              else if (found && wrote){
//                                                  $('#alias_num_div').append('<td style="text-align:center"> &check; </td>') ;
//                                              } else if(found){
//                                                    $('#alias_num_div').append('<td style="text-align:center"> x </td>') ;
//                                              } else {
                                            
//                                              $('#alias_num_div').append('<td> </td>') ;  
//                                              }                                                          
//                                         }



//                     // explore feedback           
//                                        $('#alias_num_div').append('<tr><td>&nbsp; </td></tr>') ;
//                                        $('#alias_num_div').append('<td  style="text-align:center"> Explore </td>') ;
//                                         for (i=0;i<n;i++){
//                                             var found = false;
//                                             var wrote = false;
//                                             var fbpts = false;
//                                             var fbtext = false;
//                                             for (j=0;j<n2;j++){ 
//                                                 var row = results[j];
//                                                    if (typeof row.alias_num != "undefined" ){
//                                                          if(row.alias_num == activealias[i] && row.explore_flag ==1){
//                                                               found = true;
//                                                              if (row.explore_pts > 0 ) { var row_found = row; fbpts = true;}
//                                                              if (row.fb_explore != null){
//                                                                  if (row.fb_explore.length > 2 ) { var row_found = row; fbtext = true;}
//                                                              }
//                                                               if (row.explore_text != null){
//                                                                  if (row.explore_text.length > 2){
//                                                                      wrote = true;
//                                                                   }
//                                                               }
//                                                          }                                                       
//                                                     }
//                                             }
//                                              if(fbpts && fbtext){
//                                                   $('#alias_num_div').append('<td  style="text-align:center">'+row_found["explore_pts"]+'  <br> '+row_found["fb_explore"].replace(/_/g, ' ')+'</td>') ;
//                                              } else if (fbpts) {
//                                                   $('#alias_num_div').append('<td  style="text-align:center">'+row_found["explore_pts"]+'  </td>') ;
//                                              } else if (fbtext) {
//                                                   $('#alias_num_div').append('<td  style="text-align:center"> '+row_found["fb_explore"].replace(/_/g, ' ')+'</td>') ;
//                                              }
//                                              else if (found && wrote){
//                                                  $('#alias_num_div').append('<td style="text-align:center"> &check; </td>') ;
//                                              } else if(found){
//                                                    $('#alias_num_div').append('<td style="text-align:center"> x </td>') ;
//                                              } else {
                                            
//                                              $('#alias_num_div').append('<td> </td>') ;  
//                                              }                                                          
//                                         }
//                    // connect feedback            
//                                        $('#alias_num_div').append('<tr><td> &nbsp;</td></tr>') ;
//                                        $('#alias_num_div').append('<td style="text-align:center"> Connect </td>') ;
//                                         for (i=0;i<n;i++){
//                                             var found = false;
//                                             var wrote = false;
//                                             var fbpts = false;
//                                             var fbtext = false;
//                                             for (j=0;j<n2;j++){ 
//                                                 var row = results[j];
//                                                    if (typeof row.alias_num != "undefined" ){
//                                                          if(row.alias_num == activealias[i] && row.connect_flag ==1){
//                                                               found = true;
//                                                              if (row.connect_pts > 0 ) { var row_found = row; fbpts = true;}
//                                                              if (row.fb_connect != null){
//                                                                  if (row.fb_connect.length > 2 ) { var row_found = row; fbtext = true;}
//                                                              }
//                                                               if (row.connect_text != null){
//                                                                  if (row.connect_text.length > 2){
//                                                                      wrote = true;
//                                                                   }
//                                                               }
//                                                          }                                                       
//                                                     }
//                                             }
//                                              if(fbpts && fbtext){
//                                                   $('#alias_num_div').append('<td  style="text-align:center">'+row_found["connect_pts"]+'  <br> '+row_found["fb_connect"].replace(/_/g, ' ')+'</td>') ;
//                                              } else if (fbpts) {
//                                                   $('#alias_num_div').append('<td  style="text-align:center">'+row_found["connect_pts"]+'  </td>') ;
//                                              } else if (fbtext) {
//                                                   $('#alias_num_div').append('<td  style="text-align:center"> '+row_found["fb_connect"].replace(/_/g, ' ')+'</td>') ;
//                                              }
//                                              else if (found && wrote){
//                                                  $('#alias_num_div').append('<td style="text-align:center"> &check; </td>') ;
//                                              } else if(found){
//                                                    $('#alias_num_div').append('<td style="text-align:center"> x </td>') ;
//                                              } else {
                                            
//                                              $('#alias_num_div').append('<td> </td>') ;  
//                                              }                                                          
//                                         }
//                         // society
//                                        $('#alias_num_div').append('<tr><td> &nbsp;</td></tr>') ;
//                                        $('#alias_num_div').append('<td style="text-align:center"> Society </td>') ;
//                                         for (i=0;i<n;i++){
//                                             var found = false;
//                                             var wrote = false;
//                                             var fbpts = false;
//                                             var fbtext = false;
//                                             for (j=0;j<n2;j++){ 
//                                                 var row = results[j];
//                                                    if (typeof row.alias_num != "undefined" ){
//                                                          if(row.alias_num == activealias[i] && row.society_flag ==1){
//                                                               found = true;
//                                                              if (row.society_pts > 0 ) { var row_found = row; fbpts = true;}
//                                                              if (row.fb_society != null){
//                                                                  if (row.fb_society.length > 2 ) { var row_found = row; fbtext = true;}
//                                                              }
//                                                               if (row.society_text != null){
//                                                                  if (row.society_text.length > 2){
//                                                                      wrote = true;
//                                                                   }
//                                                               }
//                                                          }                                                       
//                                                     }
//                                             }
//                                              if(fbpts && fbtext){
//                                                   $('#alias_num_div').append('<td  style="text-align:center">'+row_found["society_pts"]+'  <br> '+row_found["fb_society"].replace(/_/g, ' ')+'</td>') ;
//                                              } else if (fbpts) {
//                                                   $('#alias_num_div').append('<td  style="text-align:center">'+row_found["society_pts"]+'  </td>') ;
//                                              } else if (fbtext) {
//                                                   $('#alias_num_div').append('<td  style="text-align:center"> '+row_found["fb_society"].replace(/_/g, ' ')+'</td>') ;
//                                              }
//                                              else if (found && wrote){
//                                                  $('#alias_num_div').append('<td style="text-align:center"> &check; </td>') ;
//                                              } else if(found){
//                                                    $('#alias_num_div').append('<td style="text-align:center"> x </td>') ;
//                                              } else {
                                            
//                                              $('#alias_num_div').append('<td> </td>') ;  
//                                              }                                                          
//                                         }
                                        
//              // write the score from the feedback
//                                         $('#alias_num_div').append('<tr><td>&nbsp;</td></tr>') ;
//                                         $('#alias_num_div').append('<td  style="text-align:center"> Actual Pts</td>') ;
//                                         for (i=0;i<n;i++){
//                                             var found = false;
//                                              for (j=0;j<n2;j++){ 
//                                                 var row = results[j];
//                                                    if (typeof row.alias_num != "undefined"  ){
//                                                          if(row.alias_num ==activealias[i]){
//                                                              found = true;
//                                                              var row_found = row;
//                                                          }
//                                                     }                                                       
//                                              }
//                                              if (found){
//                                                 if (row_found["fb_p_num_score_net"] != null){
//                                                   $('#alias_num_div').append('<td  style="text-align:center" >'+row_found["fb_p_num_score_net"]+'</td>') ;
//                                                 } else {
//                                                   $('#alias_num_div').append('<td  style="text-align:center"> &nbsp; </td>') ;
//                                                 }
//                                              } else 
//                                              {
//                                                  $('#alias_num_div').append('<td></td>') ;  
//                                              }                                                           
//                                         }                                       


//                   // write the score from the feedback
//                                         $('#alias_num_div').append('<tr><td>&nbsp;</td></tr>') ;
//                                         $('#alias_num_div').append('<td  style="text-align:center; background-color: lightgray" > Total Pts </td>') ;
//                                         for (i=0;i<n;i++){
//                                             var found = false;
//                                              for (j=0;j<n2;j++){ 
//                                                 var row = results[j];
//                                                    if (typeof row.alias_num != "undefined"  ){
//                                                          if(row.alias_num ==activealias[i]){
//                                                              found = true;
//                                                              var row_found = row;
//                                                          }
//                                                     }                                                       
//                                              }
//                                              if (found){
//                                                 if (row_found["fb_probtot_pts"] != null){
//                                                   $('#alias_num_div').append('<td  style="text-align:center ; background-color: lightgray" >'+row_found["fb_probtot_pts"]+'</td>') ;
//                                                 } else {
//                                                   $('#alias_num_div').append('<td  style="text-align:center ; background-color: lightgray"> &nbsp; </td>') ;
//                                                 }
//                                              } else 
//                                              {
//                                                  $('#alias_num_div').append('<td style="text-align:center ; background-color: lightgray"></td>') ;  
//                                              }                                                           
//                                         }                                       
                                        
                                        
//                                                     $('#alias_num_div').append('<tr ></tr>') ;
//                    // look for files that have been submitted to the system for the problem need to do this with AJAX i think
//                                         //  $('#files_section').append('</tr><tr>') ;
//                                         //  $('#alias_num_div').append('</tr><tr>') ;
//                                        $('#alias_num_div').append('<tr><td>&nbsp;</td></tr>') ;
//                                        $('#alias_num_div').append('<td style="text-align:center"> Files Uploaded </td>') ;
                                        
                                        
//                                         var activityIds = [];
//                                          for (j=0;j<n2;j++){ 
//                                              var row = results[j];
//                                              activityIds[j] =  row.activity_id ;
//    //                                           console.log(' activityIds  '+activityIds);
//                                          }  
//                                         $.ajax({
//                                         url: 'getfilenumber.php',
//                                         method: 'post',
//                                         data: {activity_ids:activityIds},
                                        
//                                             success: function(num_files,status,xhr){
//                                                 num_files = JSON.parse(num_files);
//       //                                            console.log (' num files '+ num_files);
//                                                   var position = '';
//                                                  for (i=0;i<n;i++){
//                                                     var found = false;
//                                                      for (j=0;j<n2;j++){ 
//                                                         var row = results[j];
//                                                            if (typeof row.alias_num != "undefined"  ){
//                                                                  if(row.alias_num == activealias[i]){
//                                                                      found = true;
//                                                                      position = j;
//                                                                  }
//                                                             }                                                       
//                                                      }
//                                                      if (found){
                                                         
//                                                         if (num_files[position] != null){
//                                                           $('#alias_num_div').append('<td style="text-align:center">'+num_files[position]+'</td>') ;
//                                                           position = '';
//                                                         } else {
//                                                           $('#alias_num_div').append('<td style="text-align:center"> 0 </td>') ;
//                                                         }
//                                                      } else 
//                                                      {
//                                                          $('#alias_num_div').append('<td></td>') ;  
//                                                      }                                                           
//                                                 }
     
// // should put in stuff from the assignscore for the points for the overall assignment and the weights for each part from assigntime table

                                                    
//      //?                                               $('#alias_num_div').append('</table>') ;
//                                                     $('#alias_num_div').append('</div>') ;
//                                              }
                                        
//                                         });
                                    
//                                     }   
                              
//                                });

//                         }
                 
                 }
				
                       
                });
            });
             
    
                    
                       // this bit should get the peer rating reflections from the assignment
            /*               
                          $.ajax({
                           url: 'get_peer_reflections.php',
                            method: 'post',
                            data: {assign_num:assign_num,currentclass_id:currentclass_id}
                          }).done(function(peer_reflections){
                      //      console.log(' peer_reflections: '+peer_reflections);
                           peer_reflections = JSON.parse(peer_reflections);
                            
                            const peer_reflection = Object.values(peer_reflections);
                            
                      //      console.log(' peer_reflection: '+peer_reflection);
                             n = peer_reflection.length;
                      //       console.log(' n: '+n);
                            
                        if(n>0) {    
                            $('#peer_rating_div').append("<hr><br> <font color=#003399> The following reflections from assignment "+assign_num+" are ready to be rated </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;
                            for (i=0;i<n;i++){
                                //could put in code to get from the activity table which problems have been attempted and which are complete and color the radio buttons different
                                $('#peer_rating_div').append('<input  name="peer_num"  type="radio"  value="'+peer_reflection[i]+'"/> '+peer_reflection[i]+'&nbsp; &nbsp; &nbsp;') ;
                             }
                             var text = '<br><p><input type = "submit" name = "Rate Reflection" formaction="peer_rating.php" value="Rate Reflection" id="Rate Reflection" size="2" style = "width: 30%; background-color:navy; color: white"/> <br><hr>&nbsp &nbsp </p>';
                                $('#peer_rating_div').append(text) ;  
                        }
                    })
                    
 
     
    if(isset($assigntime_id)){
       $now = time();
        $sql = 'SELECT `peer_refl_t`,`peer_refl_n` FROM Assigntime WHERE assigntime_id = :assigntime_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':assigntime_id' => $assigntime_id));
        $assigntime_data = $stmt -> fetch();
        $peer_refl_t = $assigntime_data['peer_refl_t'];
         $peer_refl_n = $assigntime_data['peer_refl_n'];
        $due_cutoff = $now - $peer_refl_t*24*60*60;


        $sql = 'SELECT * FROM Assigntime WHERE assigntime_id = :assigntime_id AND UNIX_TIMESTAMP(`due_date`) <= :now AND UNIX_TIMESTAMP(`due_date`) >= :due_cutoff '; 
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
        ':assigntime_id' => $assigntime_id,
        ':now' => $now,
        ':due_cutoff' => $due_cutoff,
        ));
        $assigntime_data = $stmt -> fetch();
       $current_class_id_peer = $assigntime_data['current_class_id'];
       $assign_num_peer = $assigntime_data['assign_num'];
       $assign_num_peer = $assigntime_data['assign_num'];
       $iid_peer = $assigntime_data['iid'];
       $reflections = array(); 
       for ($i=0;$i<=20;$i++){
            if($assigntime_data['perc_ref_'.$i]>0){$reflections['perc_ref_'.$i] = $assigntime_data['perc_ref_'.$i];}
            if($assigntime_data['perc_exp_'.$i]>0){$reflections['perc_exp_'.$i] = $assigntime_data['perc_exp_'.$i];}
            if($assigntime_data['perc_con_'.$i]>0){$reflections['perc_con_'.$i] = $assigntime_data['perc_con_'.$i];}
            if($assigntime_data['perc_soc_'.$i]>0){$reflections['perc_soc_'.$i] = $assigntime_data['perc_soc_'.$i];}
            
        }
   
      print_r( $reflections);
     }  

 */
 // });
});
</script>


</body>
</html>



