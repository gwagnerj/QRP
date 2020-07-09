<?php
	require_once "pdo.php";
	session_start();
// this is called from the QRAssignmentSart.php and this will Collect the Points on a particular assignment from the instructor .  THis file was coppied form QRAssignmentSart.php
	
    if (isset($_GET['assigntime_id'])) {
	$assigntime_id = $_GET['assigntime_id'];
} else {
	 $_SESSION['error'] = 'invalid assigntime_id in QRExamStart2.php ';
      			header( 'Location: QRPRepo.php' ) ;
				die();
}

// table header
   echo ('<table id="table_format" class = "a" border="1" >'."\n");
        echo("<thead>");

		echo("</td><th>");
		echo('Problem Number');
		echo("</th><th>");
		echo('% of Assignment');
		echo("</th><th>");
		echo('part a)');
		 echo("</th><th>");
		
		echo('part b');
		echo("</th><th>");
		echo('part c');
		 echo("</th><th>");
		echo('part d');
		echo("</th><th>");
		echo('part e');
		echo("</th><th>");
		echo('part f');
		 echo("</th><th>");
		 echo('part g');
		echo("</th><th>");
		echo('part h');
		echo("</th><th>");
		echo('part i');
		echo("</th><th>");
		echo('part j');
		echo("</th><th>");
        echo('reflect');
		echo("</th><th>");
        echo('explore');
        echo("</th><th>");
        echo('connect');
		echo("</th><th>");
         echo('society');
         echo("</th><th>");
         echo('Sum for pblm');
	
		echo("</th></tr>\n");
		 echo("</thead>");
		 
		  echo("<tbody>");


// first get how many problems that the assignment has and how many parts to each problem
 $sql = "SELECT * FROM `Assigntime` WHERE assigntime_id = :assigntime_id";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
				':assigntime_id' => $assigntime_id,
				)); 
            $assigntime_data = $stmt->fetch();
        $assign_num = $assigntime_data['assign_num'];
        $iid = $assigntime_data['iid'];
        $currentclass_id = $assigntime_data['currentclass_id'];
       // echo ('currentclass_id: '.$currentclass_id);
        $i = 1;
        $sql = "SELECT * FROM `Assign` WHERE iid = :iid AND assign_num = :assign_num AND currentclass_id = :currentclass_id ORDER BY alias_num ";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
				':assign_num' => $assign_num,
                ':iid' => $iid,
                ':currentclass_id' => $currentclass_id,
				)); 
            $assign_datas = $stmt->fetchALL();
       foreach($assign_datas as $assign_data){
           $problem_id = $assign_data['prob_num'];
           
       //  echo   ('assign_data[prob_num]: '.$assign_data['prob_num']);
    
         echo "<tr><td>";
			
			
			echo(htmlentities($assign_data['alias_num']));
			
			echo("</td><td>");	
           echo('<input type = "number" min = "0" max = "100" id="perc_'.$i.'" name = "perc_'.$i.'" required  > </input>');
            echo("</td><td>");
          
            $x = 'a';          
              $sql = "SELECT * FROM `Qa` WHERE problem_id = :problem_id AND dex = :dex ";
           $stmt = $pdo->prepare($sql);
           $stmt -> execute(array(
				':problem_id' => $problem_id,
                ':dex' => 1,
				)); 
            $Qa_data = $stmt->fetch();
           foreach(range('a','j') as $x){  // only getting one row 
                //echo("</td><td>");	
               if($Qa_data['ans_'.$x]<1e43){
                       echo('<input type = "number" min = "0" max = "100" id="perc_'.$x.'_'.$i.'" name = "perc_'.$x.'_'.$i.'" required  > </input>');
               } else {echo('xxx');}
                        echo("</td><td>");
              
                $x++;  
          }
            echo("</td></tr>");	
       }    

//if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_name'])) {
    
   // echo'first page submitted';
   // input the values from the form into the Assigntime table - get the assigntime_id and then move onto page two to get points values for each part
  


        
/* 		
			$alias_num = $exam_num = $cclass_id = '';   
			
			
            $sql_stmt = "SELECT * FROM Exam WHERE DATE(NOW())<= exp_date AND iid = :iid order by exam_id";
            $stmt = $pdo->prepare($sql_stmt);
            $stmt -> execute(array(':iid' => $iid));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 */
     
// this will be called form the main repo when the game master wants to run a game
// this is just to get the game number and go on to QRGMaster.php with a post of the game number.
// Validity will be checked in that file and sent back here if it is not valid

$_SESSION['counter']=0;  // this is for the score board


	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QR Assignment Start</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<style>
   .outer {
  width: 100%;
  margin: 0 auto;
 
}

.inner {
  margin-left: 50px;
 
} 


</style>



</head>

<body>
<header>
<h1>Quick Response Assignment Setup</h1>
</header>

<?php
	
		if ( isset($_SESSION['error']) ) {
			echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) ) {
			echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
			unset($_SESSION['success']);
		}
	
 
 
 
?>


    
	<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
	
	<script>
	
	
	
	$(document).ready( function () {
		
		var currentclass_name = "";
		
			$("#currentclass_id").change(function(){
            var	 currentclass_id = $("#currentclass_id").val();
                console.log ('currentclass_id: '+currentclass_id);
				
				// need to give it 	
					$.ajax({
						url: 'getactiveassignments.php',
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
						for (i=0;i<n;i++){
							console.log(activeass[i]);	
                            var s_act=activeass[i].toString();
                            console.log(s_act);	
							 $("#active_assign").append("<option value="+activeass[i]+">"+s_act+"</option>");
							if (i != n-1){

							}
						}
						
					});	
				
			
			 
            } );
        
       $('input:radio[name="work_flow"]').change(
              function(){
                if ($(this).is(':checked') && $(this).val() == 'bc_if') {
                     $('#base_case_if').show();
                } else 
                {$('#base_case_if').hide();
                }
            
            //if($('#bc_if').is(':checked')) { $('#base_case_if').show(); } else {$('#base_case_if').hide();}
        });
        
        
     // this is from https://stackoverflow.com/questions/24468518/html5-input-datetime-local-default-value-of-today-and-current-time using pure JS   
        window.addEventListener("load", function() {
    var now = new Date();
    var utcString = now.toISOString().substring(0,19);
    var year = now.getFullYear();
    var month = now.getMonth() + 1;
    var day = now.getDate();
    var hour = now.getHours();
    var minute = now.getMinutes();
    var second = now.getSeconds();
    var localDatetime = year + "-" +
                      (month < 10 ? "0" + month.toString() : month) + "-" +
                      (day < 10 ? "0" + day.toString() : day) + "T" +
                      (hour < 10 ? "0" + hour.toString() : hour) + ":" +
                      (minute < 10 ? "0" + minute.toString() : minute) ;
     if (month ==12){month = 1} else {month = month +1; }    // set default window closes to one month in the future            
    var localDatetime2 = year + "-" +
                      (month < 10 ? "0" + month.toString() : month) + "-" +
                      (day < 10 ? "0" + day.toString() : day) + "T" +
                      (hour < 10 ? "0" + hour.toString() : hour) + ":" +
                      (minute < 10 ? "0" + minute.toString() : minute) ;
    var window_opens = document.getElementById("window_opens");
    window_opens.value = localDatetime;
    var window_closes = document.getElementById("window_closes");
    window_closes.value = localDatetime2;

});
        
        $("#submit_id").click(function(){
          
        /*    
          $.ajax({
             type: "POST",
             url: "QREStart.php",
             data: {currentclass_id:currentclass_id,exam_num:exam_num},
             success: function(msg) {
                alert("Form Submitted: " + msg);
             }
          });
           */
         /*  $.ajax({
				url: 'QREStart.php',
				method: 'post',
				data: {currentclass_id:currentclass_id,exam_num:exam_num}
					})
           */
          
        // $.post("QREStart.php",{currentclass_id:currentclass_id, exam_num:exam_num},);  
          
        });
	
	} );
	
	
</script>	

</body>
</html>



