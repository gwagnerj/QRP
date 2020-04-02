<?php
require_once "pdo.php";
session_start();

// this is a project to get Stage an Exam questions from the nummeic problems in the repository and is run from QRPRepo.php
// along with the instuctor_ID (aka users_id) and problem-id to a game table and display the problem for the user to print.



$problem_id= '';



if(isset($_POST['problem_id'])){
	$problem_id = htmlentities($_POST['problem_id']);
    
	$_SESSION['problem_id']=$problem_id;
} else {

	$_SESSION['error'] = 'problem id was not set';
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
	
}

if(isset($_POST['iid'])){
	$iid = htmlentities($_POST['iid']);
	$_SESSION['iid']=$iid;
} else {

	$_SESSION['error'] = 'user_id iid was not set';
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
}



$activate_flag = 1; // temp


  $sql = "SELECT * FROM Problem WHERE problem_id = :problem_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':problem_id' => $problem_id));
	$problem_data = $stmt -> fetch();

	
	// Check to see if this instructor has any currentclasses
	 $sql = "SELECT * FROM CurrentClass WHERE iid = :iid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':iid' => $iid));
	$current_class_data = $stmt -> fetch();
	if ($current_class_data == false){
		$_SESSION['error'] = 'There are no current classes for this Instructor - Please Add a Class that you are Teaching';
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
	} 
    
    	// check to see if this is a new problem and they want the start over file issued
	if ($problem_data['status']=='num issued'){
		$_SESSION['error'] = 'The status of this problem is num issued and cannot be activated';
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
	}
    
    
	 $sql = "SELECT * FROM Users WHERE users_id = :iid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':iid' => $iid));
	$Users_data = $stmt -> fetch();
	
    
    $sql = "SELECT * FROM Exam WHERE iid = :iid AND problem_id = :problem_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':iid' => $iid,':problem_id' => $problem_id));
	$Exam_data = $stmt -> fetch();
	
	$university = $Users_data['university'];
	$instr_last = $Users_data['last'];
   
	
    // if the assignment data is not equal to false then we already have an entry make the values of the variables equal to the values in the db
	if($Exam_data != false) {
		$exam_id = $Exam_data['exam_id'];
		// echo($assign_id);
		$exam_num = $Exam_data['exam_num'];
		$alias_num = $Exam_data['alias_num'];
        $exp_date = $Exam_data['exp_date'];
		 $currentclass_id = $Exam_data['currentclass_id'];
		
		$proctor_id1 = $Exam_data['proctor_id1'];
		$proctor_id2 = $Exam_data['proctor_id2'];
		$proctor_id3 = $Exam_data['proctor_id3'];
		$activate_flag = 0;
	} else {
		
		// initialize a bunch of variables if we do not have a file in assign
		$activate_flag = 1;	
        
		$instr_last =  $assign_num =  "";
		$exam_id = $alias_num = $exam_num = "";
		$proctor_id1 = $proctor_id2 = $proctor_id3 = "";
		$currentclass_id = '';
	}
    
// we have a file and are trying to unstage it  
   if(isset($_POST['Unstage']) && $Exam_data != false){
	 
	 $sql = "DELETE FROM Exam WHERE exam_id = :exam_id";  
	   $stmt = $pdo -> prepare($sql);
	   $stmt -> execute(array(
		':exam_id' => $exam_id
	   ));
	 
	//echo('the problem was deactivated'.$assign_id);
	 $_SESSION['sucess'] = 'the problem was unstaged';
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
   }



// we dont have an entry and we are trying to stage it - create an new entry 
if(isset($_POST['Stage']) && $Exam_data==false){
	$activate_flag = 1;
	
	$currentclass_id =  htmlentities($_POST['currentclass_id']);
	
		if(isset($_POST['exp_date'])){
			$exp_date=$_POST['exp_date'];
			} 
			
		 $sql = "SELECT exp_date FROM CurrentClass WHERE currentclass_id = :currentclass_id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(':currentclass_id' => $currentclass_id));
		$class_exp_data = $stmt -> fetch();
		$class_exp_date = $class_exp_data['exp_date'];

	if($exp_date > $class_exp_date){
	$_SESSION['error']	= 'Expiration date of Exam cannot exceed the expiration date on the class';
		
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
	}

	// Set parameters
           
		   $exam_num = htmlentities($_POST['exam_num']);
		     $alias_num = htmlentities($_POST['alias_num']);
			$instr_last = $Users_data['last'];
			$iid = $Users_data['users_id'];
			
			$university = $Users_data['university'];
				if(isset($_POST['proctor_id1'])){
			$proctor_id1=$_POST['proctor_id1'];
			} 
			if(isset($_POST['proctor_id2'])){
				$proctor_id2=$_POST['proctor_id2'];
			} 
			if(isset($_POST['proctor_id3'])){
				$proctor_id3=$_POST['proctor_id3'];
			} 

// check to make sure the problem number for that exam and class is not already in the system
		
		$sql = "SELECT exam_id FROM Exam WHERE currentclass_id = :currentclass_id AND alias_num = :alias_num AND exam_num = :exam_num";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':currentclass_id' => $currentclass_id,
			':alias_num' => $alias_num,
			':exam_num' => $exam_num
		));
		$check = $stmt -> fetch();
		if ($check != false){
			$_SESSION['error']	= 'Duplication error - problems must have distinct problem numbers for an exam in a class';
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
		}
	 // Prepare an insert statement
        $sql = "INSERT INTO Exam (instr_last, iid, university,  exam_num, problem_id, exp_date,proctor_id1,proctor_id2,proctor_id3,alias_num,currentclass_id)
		VALUES (:instr_last, :iid,:university,:exam_num,:problem_id,:exp_date,:proctor_id1,:proctor_id2,:proctor_id3,:alias_num,:currentclass_id)";
         
       
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':instr_last' => $instr_last,
				':iid' => $iid,
				':university' => $university,				
				':exam_num' => $exam_num,
				':alias_num' => $alias_num,
				':problem_id' => $problem_id,
				':exp_date' => $exp_date,
				':proctor_id1' => $proctor_id1,
				':proctor_id2' => $proctor_id2,
				':proctor_id3' => $proctor_id3,
				':currentclass_id' => $currentclass_id
				));

        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
	}	

	// We have a file and are trying to edit it- just update the entry
   if(isset($_POST['Submitted']) && $Exam_data !== false){ 
   
	
  // changing assignment number so need a new time 
   if($exam_num != $_POST['exam_num'] ) {
	$exam_num = $_POST['exam_num'];
	
   }
     if($alias_num != $_POST['alias_num'] ) {
	$alias_num = $_POST['alias_num'];
	
   }
		
		if(isset($_POST['exp_date'])){
			$exp_date=$_POST['exp_date'];
		} 
		// check the grader_id1 
		if(isset($_POST['proctor_id1'])){
			$proctor_id1=$_POST['proctor_id1'];
		} 
		if(isset($_POST['proctor_id2'])){
			$proctor_id2=$_POST['proctor_id2'];
		} 
		if(isset($_POST['proctor_id3'])){
			$proctor_id3=$_POST['proctor_id3'];
		} 
		
        	$sql = "UPDATE Exam SET   exam_num = :exam_num, exp_date = :exp_date,
			proctor_id1 = :proctor_id1, proctor_id2 = :proctor_id2, proctor_id3 = :proctor_id3, alias_num = :alias_num
					WHERE exam_id = :exam_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
			':exam_id' => $exam_id,
			
			
			':exam_num' => $exam_num,
			':alias_num' => $alias_num,
			':exp_date' => $_POST['exp_date'],
			':proctor_id1' => $proctor_id1,
			':proctor_id2' => $proctor_id2,
			':proctor_id3' => $proctor_id3
			));
			 $_SESSION['sucess'] = 'the problem was edited and remains active';
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
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
<script type="text/javascript" charset="utf-8" src="qrcode.js"></script>
</head>
<body>
<header>
<h2>Activate / Deactivate - Please select the options that you want to exam with problem <?php echo ( $problem_id); ?></h2>
</header>	
	 <div class="wrapper">
       
        <form  method="post">
			<?php
				if($activate_flag== 1){
							 echo('<h4><input type="checkbox" name="Stage" checked > Stage - make available for exam </h4>');
					
				} else {
					
					echo('<h4><input type="checkbox" name="Unstage" > Unstage </h4>');
				}
			
			?>
								
				<div id ="current_class_dd">	
				Course: </br>
				<select name = "currentclass_id" id = "currentclass_id">
				
				<?php
                    if($activate_flag!=1){
                        $sql = 'SELECT * FROM `CurrentClass` WHERE `iid` = :iid AND `currentclass_id` = :currentclass_id';
                        $stmt = $pdo->prepare($sql);
                        $stmt -> execute(array(':iid' => $iid, ':currentclass_id' => $currentclass_id));
                        $row = $stmt->fetch();
                        
                        ?>
						<option value="<?php echo $currentclass_id; ?>" ><?php echo $row['name']; ?> </option>
						<?php
                      
                    } else {
                    ?>
                        <option value = "" selected disabled hidden >  Class  </option> 
                    <?php
					$sql = 'SELECT * FROM `CurrentClass` WHERE `iid` = :iid';
					$stmt = $pdo->prepare($sql);
					$stmt -> execute(array(':iid' => $iid));
					while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) 
						{ ?>
						<option value="<?php echo $row['currentclass_id']; ?>" ><?php echo $row['name']; ?> </option>
						<?php
 							}
                    }?>
				</select>
				</div>
				</br>	
				
				<div id ="section_check">	

				</div>
				</br>		
			<div id = "active_assign">
			
			</div>
	
			</br> <input type= "text" Name="exam_num" id = "exam_num" size="1" <?php if(strlen($exam_num) !== 0){echo ('value ='.$exam_num);  }?> required> Exam Number <br> </br>
			</br>
			<div id = "active_alias">
			
			</div>
			</br> <input type= "text" Name="alias_num" id = "alias_num" size="1" <?php if(strlen($alias_num) !== 0){echo ('value ='.$alias_num);  }?> required> Problem Number Within Exam <br>

         
				<p><font >When Should this Exam Expire (max is 6 months from now) </font><input type="date" name="exp_date" value = "2020-05-13"   id="exp_date" ></p>
				
			
			
			
			<div id = "allow_proctor">
				</br>
				&nbsp Who can Activate and Proctor this Exam: </br>
				&nbsp &nbsp <input type="radio" name="allow_proct" class = "allow_proct_class" value=0 checked> Only me <br>
				&nbsp &nbsp <input type="radio" name="allow_proct" class = "allow_proct_class" value=1 id = "allow_proct" > Allow myself and Users with the following IDs:
				<input type = "number" name = "proctor_id1" id = "proctor_id1" min = "0" max = "10000" value = "<?php if ($Exam_data['proctor_id1'] !=null){echo $Exam_data['proctor_id1'];} else{echo'';}?>">
				<input type = "number" name = "proctor_id2" id = "proctor_id2" min = "0" max = "10000" value = "<?php if ($Exam_data['proctor_id2'] !=null){echo $Exam_data['proctor_id2'];} else{echo'';}?>">
				<input type = "number" name = "proctor_id3" id = "proctor_id3" min = "0" max = "10000" value = "<?php if ($Exam_data['proctor_id3'] !=null){echo $Exam_data['proctor_id3'];} else{echo'';}?>">
				&nbsp; &nbsp; &nbsp;  for a listing of ID's: <a href="getiid.php" target = "_blank"><b>Click Here</b></a></font></br>
			</div>
			
			   <input type="hidden" name="problem_id"  value="<?php echo ($problem_id)?>" >
                <input type="hidden" name="iid"  value="<?php echo ($iid)?>" >
			
			<input type="hidden" name="Submitted" value="name" />
			<p><input type = "submit"></p>
        </form>
		<p> &nbsp; </p>
        <p> &nbsp; </p>
        <form id = "close">
            	<p><input type = "submit" name = "Close" Value = "Cancel and Close" id = "close_it"></p>
        </form>
    </div>    
 
	
		
	<script>
	
	// still need to take care of sections and put them in the database 
	
	
	$(document).ready( function () {
        
          $("#close_it").click(function(){
             window.close();
            });
        
        
        
		var sec_tot = 0;
		var currentclass_name = '';
		
			$("#currentclass_id").change(function(){
		var	 currentclass_id = $("#currentclass_id").val();
		//	console.log ('currentclass_id: '+currentclass_id);
			
			$.ajax({
					url: 'getsections.php',
					method: 'post',
						data: {currentclass_id:currentclass_id}
				//	data: 'currentclass_id=' + currentclass_id
				}).done(function(section){
					// console.log(section);
					var L = section.indexOf("]");
					//console.log("L: "+L);
					
					var section = section.substring(1, L);
					// console.log(section);
					section = JSON.parse(section);
					 console.log(section.sec_desig_1);
					 	 $('#section_check').empty();
						
				
					var i;
					for (i = 1; i < 6; i++) { 
					 
						var sec = "section.sec_desig_"+i;
					//	console.log (eval(sec));
						
						 if (eval(sec).length>1){
							// console.log ("WTH"+section.sec_desig_1);
							$('#section_check').append('<input class = "sel_checkbox" name="'+eval(sec)+'" id = "'+eval(sec)+'" type="checkbox" checked value="'+sec+'"/> '+eval(sec)+'<br/>') ;
							sec_tot = sec_tot+1;
						 }
					}			
					//console.log ("sec_tot: "+sec_tot);
				})
			
			});
			
			$("#exam_num").click(function() {
				
			// console.log ("clicked assignment");
			var num_checked_sec = $('.sel_checkbox:checked').length;
			console.log ("num_checked_sec: "+num_checked_sec);
			console.log ("sec_tot: "+sec_tot);
			if (num_checked_sec == 0){ // we have no sections then just get the active problems from the Assig Table 
				// need to give it 	
				var currentclass_id = $("#currentclass_id").val();
				 console.log("currentclass_id: "+currentclass_id);
					$.ajax({
						url: 'getactiveexam.php',
						method: 'post',
					
					data: {currentclass_id:currentclass_id}
					}).done(function(activeass){
						console.log("activeass: "+activeass);
					 console.log(activeass);
					 activeass = JSON.parse(activeass);
					 	 $('#active_assign').empty();
						var i = 0;
						n = activeass.length;
						console.log("n: "+n);
						$('#active_assign').append("&nbsp;&nbsp;&nbsp;&nbsp;Current exams in system for this course: ") ;	
						for (i=0;i<n;i++){
							console.log(activeass[i]);	
							$('#active_assign').append(activeass[i]) ;	
							
							if (i != n-1){
								$('#active_assign').append(', &nbsp;') ;	
							}
						}
						
					});	

					
					$("#alias_num").click(function() {
					var currentclass_id = $("#currentclass_id").val();
					var exam_num = $("#exam_num").val();
					 console.log("currentclass_id: "+currentclass_id);
					  console.log("exam_num: "+exam_num);
					$.ajax({
						url: 'getactivealiasexam.php',
						method: 'post',
						data:{currentclass_id:currentclass_id, exam_num:exam_num }
					}).done(function(activealiasexam){
						console.log("activealiasexam: "+activealiasexam);
					
					 activealiasexam = JSON.parse(activealiasexam);
					 	 $('#active_alias').empty();
						var i = 0;
						n = activealiasexam.length;
						console.log("n_alias: "+n);
						$('#active_alias').append("&nbsp;&nbsp;&nbsp;&nbsp;Current problems in system for this exam: ") ;	
						for (i=0;i<n;i++){
							console.log(activealiasexam[i]);	
							$('#active_alias').append(activealiasexam[i]) ;	
							
							if (i != n-1){
								$('#active_alias').append(', &nbsp;') ;	
							}
						}
						 
					});					
					
				});			
					
			}
				
				
				
			});
			 
			
			$('input[name="choice"]').change(function() {
				if ($(this).is(':checked')){ //radio is now checked
					$(".reflection").prop('checked', false);
					
					// $('input[type="checkbox"]').prop('checked', false); //unchecks all checkboxes
				}
			});

			$('.reflection').change(function() {
			// $('input[type="checkbox"]').change(function() {
				if ($(this).is(':checked')){
					$('input[name="choice"]').prop('checked', false);
				}
			});
			
			
			// suggest a date as the end of the semester for the expiration date
            
          //  this function is from https://stackoverflow.com/questions/563406/add-days-to-javascript-date
            
            
            Date.prototype.addDays = function(days) {
             var date = new Date(this.valueOf());
                date.setDate(date.getDate() + days);
                return date;
            }

            var date = new Date();
            
            
            
            
		
		var m = 0;
		var d = new Date();   // current date
       
		var minDate = d.toISOString(true).slice(0,10);
        
        
		document.getElementById("exp_date").setAttribute("min", minDate);
		
		var max_months = 6;
        
		var max_date = new Date().addDays(max_months*30);
	
        
		//console.log("Date after " + max_months + " months:", maxDate);
		var maxDate = max_date.toISOString(true).slice(0,10);
		document.getElementById("exp_date").setAttribute("max", maxDate);
		
		 
         var date_val = new Date().addDays(7);
		
		date_val = date_val.toISOString(true).slice(0,10);
		document.getElementById("exp_date").value = date_val;
	
	} );
	
	
</script>	

</body>
</html>



