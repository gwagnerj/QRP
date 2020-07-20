<?php
	require_once "pdo.php";
	session_start();
	 //unset($_POST['change_class']);
    $currentclass_id = '';
      if (isset($_POST['stu_name'])){$student_id = $_POST['student_id'];}
       if (isset($_POST['stu_name'])){$stu_name = $_POST['stu_name'];}
      if (isset($_POST['iid'])){$iid = $_POST['iid'];}
	  if (isset($_POST['pin'])){$pin = $_POST['pin'];}
      if (isset($_POST['cclass_id'])){$cclass_id = $_POST['cclass_id']; $currentclass_id = $cclass_id;}
       if (isset($_POST['current_class_id'])){$current_class_id = $_POST['current_class_id'];}else{ $current_class_id = '';}
       if (isset($_POST['assign_num'])){$assign_num = $_POST['assign_num'];} else {$assign_num='';}
       if (isset($_POST['alias_num'])){$alias_num = $_POST['alias_num'];} else {$alias_num=''; }
        if (isset($_POST['problem_id'])){$problem_id = $_POST['problem_id'];} else {$problem_id='';}
         
     $progress = 0;
	
	$index='';
     // if (isset($_POST['stu_name'])){$stu_name = $_POST['stu_name']}
      
      
    if (isset($_GET['student_id'])){
      $student_id =   $_GET['student_id'];
      
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
   
              $sql = 'SELECT * FROM `StudentCurrentClassConnect` WHERE `student_id` = :student_id';
            $stmt = $pdo->prepare($sql);
             $stmt->execute(array(':student_id' => $student_id));
             $class_data = $stmt -> fetch();
             $pin = $class_data['pin'];   
            // $currentclass_id = $class_data['currentclass_id'];
             
              $sql = 'SELECT * FROM `CurrentClass` WHERE `currentclass_id` = :currentclass_id';
            $stmt = $pdo->prepare($sql);
             $stmt->execute(array(':currentclass_id' => $currentclass_id));
             $cclass_data = $stmt -> fetch();
             $iid = $cclass_data['iid'];   
             $cclass_name = $cclass_data['name']; 
             // echo('$iid: '.$iid);
             $cclass_id = $currentclass_id; 
             // put a while $class data not false and create the drop down list or do it with JS
         //    $pin = $class_data['pin'];   
         //    $currentclass_id = $class_data['currentclass_id'];
            
        }
    
     } else {
      // $_SESSION['error'] = 'student_id not set in stu_frontpage';
	
    }
    
    
    // this is the normal place to start for students checking their homework and goes the the QRcontroller.  Can also come from the rtnCode.php or the back button on QRdisplay.php  This will
     
 
	// first time thru set scriptflag to zero - this will turn to 1 if the script ran
//	if (!isset($sc_flag)){$sc_flag=0;}
	
    if(isset($_GET['activity_id']) || isset($_POST['activity_id'])){
		if(isset($_GET['activity_id'])){$activity_id = htmlentities($_GET['activity_id']);}
        if(isset($_POST['activity_id'])){$activity_id = htmlentities($_POST['activity_id']);}
        // get the information from the activity table
       // most things will be the same except the problem_id, alias number and once they select a problem we need to 
       // check the Activity table of send all of these to the controller or somekind of pass through file

       $sql = 'SELECT * FROM `Activity` WHERE `activity_id` = :activity_id';
        $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':activity_id' => $activity_id));
         $activity_data = $stmt -> fetch();
         $iid = $activity_data['iid'];   
         $pin = $activity_data['pin'];   
         $stu_name = $activity_data['stu_name'];   
         $cclass_id = $activity_data['currentclass_id']; 
         $currentclass_id = $activity_data['currentclass_id'];  
          $student_id = $activity_data['student_id']; 
         $dex = $activity_data['dex'];  
         $alias_num = $activity_data['alias_num'];  
         $assign_id = $activity_data['assign_id'];  
         $progess = $activity_data['progress'];  

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
         $class_name = $class_data['name'];
         $cclass_name = $class_data['name'];
         
        $sql = 'SELECT `assign_num` FROM Assign WHERE assign_id = :assign_id AND iid = :iid AND currentclass_id = :currentclass_id';
         $stmt = $pdo->prepare($sql);
         $stmt->execute(array(':assign_id' => $assign_id,
                    ':iid' => $iid,
                     ':currentclass_id' => $cclass_id,
         ));
         $assign_data = $stmt -> fetch();
         $assign_num = $assign_data['assign_num'];
      } 
        // this is the first time through if we do not have an activity_id - The activity is unique for a particular student for a particular problem
        $activity_id = '';
    
	if(isset($_POST['stu_name'])){
		$stu_name = htmlentities($_POST['stu_name']);
	} 
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
    
// Go get the problem id from the Assignment table
	if(isset($_POST['submit'])&& isset($_POST['assign_num'])&& isset($_POST['alias_num'])&& isset($_POST['iid']) && isset($_POST['cclass_id']) && isset($_POST['pin'])) {
        $assign_num = htmlentities($_POST['assign_num']);
		$alias_num = htmlentities($_POST['alias_num']);
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
       $sql = 'SELECT `activity_id` FROM `Activity` WHERE `problem_id` = :problem_id AND `currentclass_id` = :cclass_id AND `pin` = :pin AND `iid`=:iid AND `assign_id` = :assign_id ';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':problem_id' => $problem_id,
                                ':cclass_id' => $cclass_id,
                                ':pin' => $pin,
                                ':iid' => $iid,
                                ':assign_id' => $assign_id
         ));
         $activity_data = $stmt -> fetch();
         $activity_id = $activity_data['activity_id'];
         
         if(strlen($activity_id)< 1){
           
           // make a new entry in the activity table 
           
           if ($pin>10000 or $pin<0){
                 $_SESSION['error']='Your PIN should be between 1 and 10000.';	
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
                    

                        // go the controller
                  /*       
                        echo (' pin: '.$pin);
                        echo (' iid: '.$iid);
                        echo (' dex: '.$dex);
                          echo (' assign_num: '.$assign_num);
                           echo (' cclass_id: '.$cclass_id);
                            echo (' current_class_id: '.$current_class_id);
                        echo (' assign_id: '.$assign_id);
                        echo (' promblem_id: '.$problem_id);
                         echo (' student_id: '.$student_id);
                        echo (' stu_name: '.$stu_name);
                        echo (' alias_num: '.$alias_num);
                       
                        die();
                     */
                    // check the activity table and see if there is an entry if not make a new entry and go to the controller
                         
                        $sql = 'INSERT INTO Activity (problem_id, pin, iid, dex, student_id,    assign_id,  instr_last, university, pp1, pp2, pp3, pp4, post_pblm1, post_pblm2, post_pblm3, score, progress, stu_name, alias_num, currentclass_id, count_tot)	
                                             VALUES (:problem_id, :pin, :iid, :dex,:student_id, :assign_id, :instr_last,:university,:pp1,:pp2,:pp3,:pp4,:post_pblm1,:post_pblm2,:post_pblm3, :score,:progress, :stu_name, :alias_num, :cclass_id, :count_tot)';
                        $stmt = $pdo->prepare($sql);
                        $stmt -> execute(array(
                        ':problem_id' => $problem_id,
                        ':pin' => $pin,
                        ':iid' => $iid,
                        ':dex' => $dex,
                         ':student_id' => $student_id,
                       ':assign_id' => $assign_id,
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
                                    
                          $sql = 'SELECT `activity_id` FROM `Activity` WHERE `problem_id` = :problem_id AND `currentclass_id` = :cclass_id AND `pin` = :pin AND `iid`=:iid AND `assign_id` = :assign_id ';
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute(array(':problem_id' => $problem_id,
                                ':cclass_id' => $cclass_id,
                                ':pin' => $pin,
                                ':iid' => $iid,
                                ':assign_id' => $assign_id
                                ));
                                $activity_row =$stmt ->fetch();		
                                $activity_id = $activity_row['activity_id'];
                  //   echo (" activity_data (new value):  ".$activity_id;     
         }   

          header("Location: QRcontroller.php?activity_id=".$activity_id);
			return; 
		
     } elseif(isset($_POST['submit'])) {
		
		 $_SESSION['error']='The Class, Assignment, Problem and instructor ID are all Required';
	}
    
		if (isset($_POST['reset']))	{
             header("Location: QRHomework.php");
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
            header("Location: stu_getclass.php?student_id=".$student_id);
			return; 
		}
        
        
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

</head>

<body>
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

<form id = "big_form" autocomplete="off" method="POST" >
	  
	<p><font color=#003399>Name: <?php echo($stu_name);?> </p>
	<input autocomplete="false" name="hidden" type="text" style="display:none;">
	
				</br>
    <input type="hidden" id = "iid" name="iid" value="<?php echo ($iid);?>" > 
    <input type="hidden" id = "pin" name="pin" value="<?php echo ($pin);?>" >
    <input type="hidden" id = "cclass_id" name="cclass_id" value="<?php echo ($currentclass_id);?>" >
    <input type="hidden" id = "stu_name" name="stu_name" value="<?php echo ($stu_name);?>" >
	<input type="hidden" id = "student_id" name="student_id" value="<?php echo ($student_id);?>" >
<!--	<div id ="current_class_dd">	-->
			<font color=#003399>Course: </font>
			
			<?php
				//	echo (' num_classes: '.$num_classes);
                 //   echo (' student_id: '.$student_id);
                 //  echo (' currentclass_id '.$currentclass_id.'<br>');
                    if (isset($currentclass_id)>0 && $num_classes ==1 ){
                       // if (isset($currentclass_id)>0 ){
						echo ('<input type = "hidden" name = "cclass_id" id = "have_cclass_id" value = "'.$currentclass_id.'"></input>'); 
						echo ('<input type = "hidden" name = "cclass_name" id = "have_cclass_name" value = "'.$cclass_name.'"></input>'); 
						echo $cclass_name;
			} elseif($currentclass_id > 0){
                        
                        echo($cclass_name); 
                        echo ('<input type = "hidden" name = "cclass_id" id = "have_cclass_id2" value = "'.$currentclass_id.'"></input>'); 
                        ?>
                                &nbsp &nbsp &nbsp &nbsp <input type = "submit" value="change class" form = "change_the_class"  name = "change_class2"  size="1" style = "width: 10%; background-color: lightgrey; color: black"/> &nbsp &nbsp  

                        <?php

                   } else {
						echo('<select name = "cclass_id" id = "current_class_dd">');
                        echo ('	<option value = "" selected disabled hidden >  Select Class  </option> ');
                          $sql = 'SELECT * FROM `StudentCurrentClassConnect` JOIN CurrentClass ON StudentCurrentClassConnect.currentclass_id = CurrentClass.currentclass_id WHERE StudentCurrentClassConnect.student_id = :student_id';
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
		</br>	
		</br>
		<font color=#003399>Assignment Number: </font>
			
              <input type="hidden" name = "assign_num" id = "have_assign_num"  value="<?php echo ($assign_num);?>" >
            <?php
	
            echo(' &nbsp;<select name = "assign_num" id = "assign_num">');
			echo('</select>');
			?>
		</br>	
		<br>
		
		<div id = "alias_num_div">
		
		</div>
			<br>	
		
	<p><input type = "submit" name = "submit" value="Submit" id="submit_id" size="2" style = "width: 30%; background-color: #003399; color: white"/> &nbsp &nbsp </p>  
	</form>
	</br>
	<form method = "POST">
		<p><input type = "submit" value="Back to Login" name = "reset"  size="2" style = "width: 30%; background-color: #FAF1BC; color: black"/> &nbsp &nbsp </p>  
	</form>
    <br>
  <!---->
  
 
  <form method = "POST" id = "change_the_class" >
            <input type="hidden"  name="num_classes" value="<?php echo ($num_classes);?>" >
             <input type="hidden"  name="cclass_name" value="<?php echo ($cclass_name);?>" >
           <input type="hidden"  name="stu_name" value="<?php echo ($stu_name);?>" >
           <input type="hidden"  name="student_id" value="<?php echo ($student_id);?>" >
             <input type="hidden"  name="activity_id" value="<?php echo ($activity_id);?>" >
              <input type="hidden"  name="pin" value="<?php echo ($pin);?>" >
                <input type="hidden"  name="dex" value="<?php echo ($pin);?>" >
	 </form>
    
    </br>
    <form method = "POST">
		<p><input type = "submit" value="Add Another Class" name = "add_class"  size="2" style = "width: 30%; background-color: darkgreen; color: white"/> &nbsp &nbsp </p>  
	</form>

<script>
	// already been through and worked a problem and now getting another one all of the input fields should be defined just need another problem
		if($('#have_assign_num').val()!= undefined){
          var assign_num = $('#have_assign_num').val();	
        console.log("assign_num: "+assign_num);
        }
        
    if($('#have_iid').val()!= undefined && $('#have_cclass_id').val()!= undefined && $('#have_cclass_name').val()!= undefined && $('#have_assign_num').val()!= undefined){
	
		var iid = $('#have_iid').val();
 		var cclass_id = $('#have_cclass_id').val();
		var cclass_name = $('#have_cclass_name').val();
	
			console.log("iid: "+iid);
			console.log("cclass_id: "+cclass_id);
			console.log("cclass_name: "+cclass_name);
			
			$.ajax({
					url: 'getactivealias.php',
					method: 'post',
					data: {assign_num:assign_num,currentclass_id:cclass_id}
				
				}).done(function(activealias){
				
					activealias = JSON.parse(activealias);
					 	 $('#alias_num_div').empty();
					n = activealias.length;
						$('#alias_num_div').append(" <font color=#003399> Select Problem for this Assignment : </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;
						for (i=0;i<n;i++){
							$('#alias_num_div').append('<input  name="alias_num"  type="radio"  value="'+activealias[i]+'"/> '+activealias[i]+'&nbsp; &nbsp; &nbsp;') ;
					}
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
        console.log("cclass_id:----- "+cclass_id);
        }
        
        // var cclass_id = $('#have_cclass_id').val(); 
            console.log("cclass_id: "+cclass_id);
            
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
                   console.log('need to get ir from drop down');
                   
                 
		
        $("#current_class_dd").change(function(){
            var currentclass_id = $("#current_class_dd").val();
			console.log ('currentclass_id: '+currentclass_id);
      
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
					}
				}) 
		}) 
		}	
			// this is getting the problem numbers (alias number) once the course has been selected
			$("#assign_num").change(function(){
		var	 assign_num = $("#assign_num").val();
     //   $('#have_assign_num').val(assign_num);
           if (!isNaN(cclass_id))  {currentclass_id = cclass_id;} else {
          
           var currentclass_id = $("#current_class_dd").val();}
           
        console.log('assign_num: '+assign_num);
        console.log(' currentclass_id: '+currentclass_id);
	//	var	 currentclass_id = $("#current_class_dd").val();
		
			// console.log ('currentclass_id 2nd time: '+currentclass_id);
			$.ajax({
					url: 'getactivealias.php',
					method: 'post',
					data: {assign_num:assign_num,currentclass_id:currentclass_id}
				
				}).done(function(activealias){
				
					activealias = JSON.parse(activealias);
					 	 $('#alias_num_div').empty();
					    n = activealias.length;
						$('#alias_num_div').append(" <font color=#003399> Select Problem for this Assignment : </font></br> </br>&nbsp;&nbsp;&nbsp;&nbsp;") ;
						for (i=0;i<n;i++){
							//could put in code to get from the activity table which problems have been attempted and which are complete and color the radio buttons different
							$('#alias_num_div').append('<input  name="alias_num"  type="radio"  value="'+activealias[i]+'"/> '+activealias[i]+'&nbsp; &nbsp; &nbsp;') ;
					}
				}) 
			});

</script>


</body>
</html>



