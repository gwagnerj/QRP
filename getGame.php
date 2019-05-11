<?php
require_once "pdo.php";
session_start();

// this is a project to get game questions from the nummeic problems in the repository
// The file should get the values of the non null variables have the instructor / game master select which vars to be substituted and write the name varaibles to rect oval trap and hex fields 
// along with the instuctor_ID (aka users_id) and problem-id to a game table 





$problem_id= '';
$index='';


if(isset($_POST['problem_id'])){
	$problem_id = htmlentities($_POST['problem_id']);
	$_SESSION['problem_id']=$problem_id;
} else {

	$_SESSION['error'] = 'problem id was not set';
	header('Location: QRPRepo.php');
	return;
	
}

if(isset($_POST['iid'])){
	$iid = htmlentities($_POST['iid']);
	$_SESSION['iid']=$iid;
} else {

	$_SESSION['error'] = 'user_id iid was not set';
	header('Location: QRPRepo.php');
	return;
}


// need to check the database to see if there is an entry and change activate to deactivate  code should be similar to QRactivate.php but for now
$activate_flag = 1; // temp



?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRGame</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<style>
table, th, td {
	width = 20
  // border: 1px solid black;
}


</style>


</head>

<body>
<header>
<h1>Quick Response Game Problem Set-up </h1>
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
// $problem_id = 238; // temp
// $index = 101; // temp  should just have them put one in
?>

<div id = substitute_me>

<p><font color=#003399>Problem Number: </font> <?php echo($problem_id);?> &nbsp; &nbsp;
	<font color=#003399>Your Instructor ID: </font><?php echo($iid);?></p>

<form method="POST">
	
		<?php
				if($activate_flag== 1){
							 echo('<h4><input type="checkbox" name="activate" id = "activate" checked > Activate - make available to students </h4>');
					
				} else {
					
					echo('<h4><input type="checkbox" name="deactivate" id = "deactivate" > Deactivate </h4>');
				}
			
			?>
	
	
	<p><font color=#003399> </font><input type="hidden" name="problem_id" id="prob_id" size=3 value=<?php echo($problem_id);?> >
	<p><font color=#003399> </font><input type="hidden" name="iid" id="iid" size=3 value=<?php echo($iid);?> >
	<p><font color=#003399>Index of Dataset </font><input type="number" name="dex" id="dex" size=3 required min = "2" max = "199" ></p>
	<p><font color=#003399>Time in Minutes for Students to Work Problem </font><input type="number" name="work_time" id="work_time" value = 15 size=3 required min = "1" max = "199" ></p>
	<p><font color=#003399>How long in days to keep this Game Problem Active </font><input type="number" name="time_delete" value = 30 id="time_delete" size=3 required min = "1" ></p>
	
	
	
	<table   class = "onePerColumn">
		<thead>
			<tr>
				<th><h4> <font color = "blue" size =5 >Varaible Name </font> </th>
				<th>	<span class = "nv_1" id = "nv_1" > nv_1 </span> </th>
				<th>	<span class = "nv_2" id = "nv_2" > nv_2 </span> </th>
				<th>	<span class = "nv_3" id = "nv_3" > nv_3 </span> </th>
				<th>	<span class = "nv_4" id = "nv_4" > nv_4 </span> </th>
				<th>	<span class = "nv_5" id = "nv_5" > nv_5 </span> </th>
				<th>	<span class = "nv_6" id = "nv_6" > nv_6 </span> </th>
				<th>	<span class = "nv_7" id = "nv_7" > nv_7 </span> </th>
				<th>	<span class = "nv_8" id = "nv_8" > nv_8 </span> </th>
				<th>	<span class = "nv_9" id = "nv_9" > nv_9 </span> </th>
				<th>	<span class = "nv_10" id = "nv_10" > nv_10 </span> </th>
				<th>	<span class = "nv_11" id = "nv_11" > nv_11 </span> </th>
				<th>	<span class = "nv_12" id = "nv_12" > nv_12 </span> </th>
				<th>	<span class = "nv_13" id = "nv_13" > nv_13 </span> </th>
				<th>	<span class = "nv_14" id = "nv_14" > nv_14 </span> </th>
			
			
			<tr><td>	<font color = "blue" size =5 > Rectangle </font> </td>
				
				<td><span class = "nv_1"><input type="radio" name="rect" Value="ans_nv_1" checked = "checked"> </span></td>
				<td><span class = "nv_2"><input type="radio" name="rect" Value="ans_nv_2"> </span></td>
				<td><span class = "nv_3"><input type="radio" name="rect" Value="ans_nv_3"> </span></td>
				<td><span class = "nv_4"><input type="radio" name="rect" Value="ans_nv_4"> </span></td>
				<td><span class = "nv_5"><input type="radio" name="rect" Value="ans_nv_5"> </span></td>
				<td><span class = "nv_6"><input type="radio" name="rect" Value="ans_nv_6"> </span></td>
				<td><span class = "nv_7"><input type="radio" name="rect" Value="ans_nv_7"> </span></td>
				<td><span class = "nv_8"><input type="radio" name="rect" Value="ans_nv_8"> </span></td>
				<td><span class = "nv_9"><input type="radio" name="rect" Value="ans_nv_9"> </span></td>
				<td><span class = "nv_10"><input type="radio" name="rect" Value="ans_nv_10"> </span></td>
				<td><span class = "nv_11"><input type="radio" name="rect" Value="ans_nv_11"> </span></td>
				<td><span class = "nv_12"><input type="radio" name="rect" Value="ans_nv_12"> </span></td>
				<td><span class = "nv_13"><input type="radio" name="rect" Value="ans_nv_13"> </span></td>
				<td><span class = "nv_14"><input type="radio" name="rect" Value="ans_nv_14"> </span></td>
			
			
			<tr><td>	<font color = "blue" size =5 > Oval </font> </td>
				
				<td><span class = "nv_1"><input type="radio" name="oval" Value="ans_nv_1" > </span></td>
				<td><span class = "nv_2"><input type="radio" name="oval" Value="ans_nv_2" checked = "checked"> </span></td>
				<td><span class = "nv_3"><input type="radio" name="oval" Value="ans_nv_3"> </span></td>
				<td><span class = "nv_4"><input type="radio" name="oval" Value="ans_nv_4"> </span></td>
				<td><span class = "nv_5"><input type="radio" name="oval" Value="ans_nv_5"> </span></td>
				<td><span class = "nv_6"><input type="radio" name="oval" Value="ans_nv_6"> </span></td>
				<td><span class = "nv_7"><input type="radio" name="oval" Value="ans_nv_7"> </span></td>
				<td><span class = "nv_8"><input type="radio" name="oval" Value="ans_nv_8"> </span></td>
				<td><span class = "nv_9"><input type="radio" name="oval" Value="ans_nv_9"> </span></td>
				<td><span class = "nv_10"><input type="radio" name="oval" Value="ans_nv_10"> </span></td>
				<td><span class = "nv_11"><input type="radio" name="oval" Value="ans_nv_11"> </span></td>
				<td><span class = "nv_12"><input type="radio" name="oval" Value="ans_nv_12"> </span></td>
				<td><span class = "nv_13"><input type="radio" name="oval" Value="ans_nv_13"> </span></td>
				<td><span class = "nv_14"><input type="radio" name="oval" Value="ans_nv_14"> </span></td>
				
			<tr><td>	<font color = "blue" size =5 > Trapazoid </font> </td>
				
				<td><span class = "nv_1"><input type="radio" name="trap" Value="ans_nv_1" > </span></td>
				<td><span class = "nv_2"><input type="radio" name="trap" Value="ans_nv_2"> </span></td>
				<td><span class = "nv_3"><input type="radio" name="trap" Value="ans_nv_3" checked = "checked"> </span></td>
				<td><span class = "nv_4"><input type="radio" name="trap" Value="ans_nv_4"> </span></td>
				<td><span class = "nv_5"><input type="radio" name="trap" Value="ans_nv_5"> </span></td>
				<td><span class = "nv_6"><input type="radio" name="trap" Value="ans_nv_6"> </span></td>
				<td><span class = "nv_7"><input type="radio" name="trap" Value="ans_nv_7"> </span></td>
				<td><span class = "nv_8"><input type="radio" name="trap" Value="ans_nv_8"> </span></td>
				<td><span class = "nv_9"><input type="radio" name="trap" Value="ans_nv_9"> </span></td>
				<td><span class = "nv_10"><input type="radio" name="trap" Value="ans_nv_10"> </span></td>
				<td><span class = "nv_11"><input type="radio" name="trap" Value="ans_nv_11"> </span></td>
				<td><span class = "nv_12"><input type="radio" name="trap" Value="ans_nv_12"> </span></td>
				<td><span class = "nv_13"><input type="radio" name="trap" Value="ans_nv_13"> </span></td>
				<td><span class = "nv_14"><input type="radio" name="trap" Value="ans_nv_14"> </span></td>
				
			<tr><td>	<font color = "blue" size =5 > Hexagon </font> </td>
				
				<td><span class = "nv_1"><input type="radio" name="hexa" Value="ans_nv_1" > </span></td>
				<td><span class = "nv_2"><input type="radio" name="hexa" Value="ans_nv_2"> </span></td>
				<td><span class = "nv_3"><input type="radio" name="hexa" Value="ans_nv_3"> </span></td>
				<td><span class = "nv_4"><input type="radio" name="hexa" Value="ans_nv_4" checked = "checked"> </span></td>
				<td><span class = "nv_5"><input type="radio" name="hexa" Value="ans_nv_5"> </span></td>
				<td><span class = "nv_6"><input type="radio" name="hexa" Value="ans_nv_6"> </span></td>
				<td><span class = "nv_7"><input type="radio" name="hexa" Value="ans_nv_7"> </span></td>
				<td><span class = "nv_8"><input type="radio" name="hexa" Value="ans_nv_8"> </span></td>
				<td><span class = "nv_9"><input type="radio" name="hexa" Value="ans_nv_9"> </span></td>
				<td><span class = "nv_10"><input type="radio" name="hexa" Value="ans_nv_10"> </span></td>
				<td><span class = "nv_11"><input type="radio" name="hexa" Value="ans_nv_11"> </span></td>
				<td><span class = "nv_12"><input type="radio" name="hexa" Value="ans_nv_12"> </span></td>
				<td><span class = "nv_13"><input type="radio" name="hexa" Value="ans_nv_13"> </span></td>
				<td><span class = "nv_14"><input type="radio" name="hexa" Value="ans_nv_14"> </span></td>
				
			
				
				
		</table>		
		
	<!-- <p><input type = "checkbox" value=1 name = "show_key" id="show_key" size="14" /> Show Key  </p>-->
	<p><input type = "submit" value="Submit" id="submit_id" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp; &nbsp; </p> 
	</form>

	  </div>
	  
	  <?php
		if($activate_flag== 1){
				echo ('<p> &nbsp; </p><hr>');
				echo ('<p><a href="QRPRepo.php">Cancel</a></p>');
		}
		
		?>
<script>

	
	var rect = "";
	var oval = "";
	var trap = "";
	var hexa = "";
	var game_id;
	
	
	$(document).ready(function(){
	// this next bit makes it so you can not have two columns with two items checked
			 $(".onePerColumn :radio,:checkbox").change(function(){
				var col = $(this).attr("value");
					$(".onePerColumn :radio[value='" + col + "']:checked,.onePerColumn :checkbox[value='" + col + "']:checked").not(this).each(function(){
						$(this).prop('checked',false);
					});
			});
 	
	var problem = $('input#prob_id').val();
	var iid = $('input#iid').val();
	
	if($("#activate").is(':checked')) {
	var activate_flag = 1;  // checked
	} else {
		var activate_flag = 0; // unchecked
	}
	
// console.log('problem_id',problem); // temp
// console.log('iid',iid); // temp
// console.log('dex',dex); // temp

	
// Get the variables names that are not null for the problem

	$.post('fetchVarsInProblem.php', {problem_id : problem, }, function(data){
				
				try{
					var arrn = JSON.parse(data);
				}
				catch(err) {
					alert ('problem data unavailable variables not found');
					alert (err);
				}

					/* var nv_1 = arrn.nv_1
					console.log (arrn.nv_1);
				console.log (arrn.nv_2);
				console.log (arrn.nv_3);
				console.log (arrn.nv_14); */


			if(arrn.nv_14 == null || arrn.nv_14 == "Null" ){$(".nv_14").hide();}else {$("#nv_14").html(arrn.nv_14);}
			if(arrn.nv_13 == null || arrn.nv_13 == "Null" ){$(".nv_13").hide();}else {$("#nv_13").html(arrn.nv_13);}
			if(arrn.nv_12 == null || arrn.nv_12 == "Null" ){$(".nv_12").hide();}else {$("#nv_12").html(arrn.nv_12);}
			if(arrn.nv_11 == null || arrn.nv_11 == "Null" ){$(".nv_11").hide();}else {$("#nv_11").html(arrn.nv_11);}
			if(arrn.nv_10 == null || arrn.nv_10 == "Null" ){$(".nv_10").hide();}else {$("#nv_10").html(arrn.nv_10);}
			if(arrn.nv_9 == null || arrn.nv_9 == "Null" ){$(".nv_9").hide();}else {$("#nv_9").html(arrn.nv_9);}
			if(arrn.nv_8 == null || arrn.nv_8 == "Null" ){$(".nv_8").hide();}else {$("#nv_8").html(arrn.nv_8);}
			if(arrn.nv_7 == null || arrn.nv_7 == "Null" ){$(".nv_7").hide();}else {$("#nv_7").html(arrn.nv_7);}
			if(arrn.nv_6 == null || arrn.nv_6 == "Null" ){$(".nv_6").hide();}else {$("#nv_6").html(arrn.nv_6);}
			if(arrn.nv_5 == null || arrn.nv_5 == "Null" ){$(".nv_5").hide();}else {$("#nv_5").html(arrn.nv_5);}
			if(arrn.nv_4 == null || arrn.nv_4 == "Null" ){$(".nv_4").hide();}else {$("#nv_4").html(arrn.nv_4);}
			if(arrn.nv_3 == null || arrn.nv_3 == "Null" ){$(".nv_3").hide();}else {$("#nv_3").html(arrn.nv_3);}
			if(arrn.nv_2 == null || arrn.nv_2 == "Null" ){$(".nv_2").hide();}else {$("#nv_2").html(arrn.nv_2);}
			if(arrn.nv_1 == null || arrn.nv_1 == "Null" ){$(".nv_1").hide();} else {$("#nv_1").html( arrn.nv_1 );}



 // we are here editing this file
	
	$('input#submit_id').on('click',function(event){
		
		
	event.preventDefault();
		/* var OneIsChecked = $('input[name = "rect"]:checked').length ==1;  // not sure I need this 
			if(!OneIsChecked){alert('Rectangle needs at leat one checked');
			return;
			} */
		
	//	console.log (OneIsChecked);
	if ($('input#dex').val() >= 2 && $('input#dex').val()<=200 && $('input#work_time').val() >= 2 && $('input#work_time').val()<=200 && $('input#time_delete').val()>=1){	
			var dex = $('input#dex').val();
			var work_time = $('input#work_time').val();
			var time_delete = $('input#time_delete').val();
			
			 rect = $('input[name = "rect"]:checked').val();
			 oval = $('input[name = "oval"]:checked').val();
			 trap = $('input[name = "trap"]:checked').val();
			hexa = $('input[name = "hexa"]:checked').val();
			
			// this is disgraceful but here goes - got tired of more sophisticated
			
			if(rect == "ans_nv_1") {rect = arrn.nv_1;}
			if(rect == "ans_nv_2") {rect = arrn.nv_2;}
			if(rect == "ans_nv_3") {rect = arrn.nv_3;}
			if(rect == "ans_nv_4") {rect = arrn.nv_4;}
			if(rect == "ans_nv_5") {rect = arrn.nv_5;}
			if(rect == "ans_nv_6") {rect = arrn.nv_6;}
			if(rect == "ans_nv_7") {rect = arrn.nv_7;}
			if(rect == "ans_nv_8") {rect = arrn.nv_8;}
			if(rect == "ans_nv_9") {rect = arrn.nv_9;}
			if(rect == "ans_nv_10") {rect = arrn.nv_10;}
			if(rect == "ans_nv_11") {rect = arrn.nv_11;}
			if(rect == "ans_nv_12") {rect = arrn.nv_12;}
			if(rect == "ans_nv_13") {rect = arrn.nv_13;}
			if(rect == "ans_nv_14") {rect = arrn.nv_14;}
			
			if(oval == "ans_nv_1") {oval = arrn.nv_1;}
			if(oval == "ans_nv_2") {oval = arrn.nv_2;}
			if(oval == "ans_nv_3") {oval = arrn.nv_3;}
			if(oval == "ans_nv_4") {oval = arrn.nv_4;}
			if(oval == "ans_nv_5") {oval = arrn.nv_5;}
			if(oval == "ans_nv_6") {oval = arrn.nv_6;}
			if(oval == "ans_nv_7") {oval = arrn.nv_7;}
			if(oval == "ans_nv_8") {oval = arrn.nv_8;}
			if(oval == "ans_nv_9") {oval = arrn.nv_9;}
			if(oval == "ans_nv_10") {oval = arrn.nv_10;}
			if(oval == "ans_nv_11") {oval = arrn.nv_11;}
			if(oval == "ans_nv_12") {oval = arrn.nv_12;}
			if(oval == "ans_nv_13") {oval = arrn.nv_13;}
			if(oval == "ans_nv_14") {oval = arrn.nv_14;}
			
			if(trap == "ans_nv_1") {trap = arrn.nv_1;}
			if(trap == "ans_nv_2") {trap = arrn.nv_2;}
			if(trap == "ans_nv_3") {trap = arrn.nv_3;}
			if(trap == "ans_nv_4") {trap = arrn.nv_4;}
			if(trap == "ans_nv_5") {trap = arrn.nv_5;}
			if(trap == "ans_nv_6") {trap = arrn.nv_6;}
			if(trap == "ans_nv_7") {trap = arrn.nv_7;}
			if(trap == "ans_nv_8") {trap = arrn.nv_8;}
			if(trap == "ans_nv_9") {trap = arrn.nv_9;}
			if(trap == "ans_nv_10") {trap = arrn.nv_10;}
			if(trap == "ans_nv_11") {trap = arrn.nv_11;}
			if(trap == "ans_nv_12") {trap = arrn.nv_12;}
			if(trap == "ans_nv_13") {trap = arrn.nv_13;}
			if(trap == "ans_nv_14") {trap = arrn.nv_14;}
			
			if(hexa == "ans_nv_1") {hexa = arrn.nv_1;}
			if(hexa == "ans_nv_2") {hexa = arrn.nv_2;}
			if(hexa == "ans_nv_3") {hexa = arrn.nv_3;}
			if(hexa == "ans_nv_4") {hexa = arrn.nv_4;}
			if(hexa == "ans_nv_5") {hexa = arrn.nv_5;}
			if(hexa == "ans_nv_6") {hexa = arrn.nv_6;}
			if(hexa == "ans_nv_7") {hexa = arrn.nv_7;}
			if(hexa == "ans_nv_8") {hexa = arrn.nv_8;}
			if(hexa == "ans_nv_9") {hexa = arrn.nv_9;}
			if(hexa == "ans_nv_10") {hexa = arrn.nv_10;}
			if(hexa == "ans_nv_11") {hexa = arrn.nv_11;}
			if(hexa == "ans_nv_12") {hexa = arrn.nv_12;}
			if(hexa == "ans_nv_13") {hexa = arrn.nv_13;}
			if(hexa == "ans_nv_14") {hexa = arrn.nv_14;}
			
			console.log('problem_id',problem); // temp
			console.log('iid',iid); // temp
			console.log('dex',dex); // temp
			console.log ('rect',rect);
			console.log ('oval',oval);
			console.log ('trap',trap);
			console.log ('hexa',hexa);
			console.log ('time_delete',time_delete);
			console.log ('work_time',work_time);
			console.log ('activate_flag',activate_flag);
			
			// now write these values to a php file that will  the Game table along with the problem_id and the instructor_id
			if (activate_flag == 1) {
				 $.post('GameRW.php', {problem_id : problem, iid : iid, dex : dex, activate_flag : activate_flag, rect : rect, oval : oval, trap : trap, hexa : hexa, work_time : work_time, time_delete : time_delete }, function(data){
								
								try{
									var arrGame = JSON.parse(data);
								}
								catch(err) {
									alert ('problem data unavailable');
								}
				
					var	game_id = arrGame.game_id;
					console.log('game_id',game_id);
			
			
			// we are here temp can we just call static auto
			

				var inde = dex;
			
			//	var s_name = $('input#stu_name_id').val();
				var statusFlag=true;
			
				if($.trim(problem) != '' && problem > 0 && problem < 100000 && inde>=1 && inde<=200){
			// alert(1);
			
					 $.post('fetchpblminput.php', {problem_id : problem, index : inde }, function(data){
						
						try{
							var arr = JSON.parse(data);
						}
						catch(err) {
							alert ('problem input data unavailable');
						}
						
						
									var openup = arr.htmlfilenm;
								openup = escape(openup);
								 console.log ('openupfilename',openup);
								
								var game = arr.game_prob_flag;
								var status = arr.status;
								var prob_num = arr.problem_id;
								var contrib_first = arr.first;
								var contrib_last = arr.last;
								var contrib_university = arr.university;
							//	var newPath = "uploads/"+openup+ " 'document'";
										$('#substitute_me').load("uploads/"+openup, 'document').html();
					
					
					console.log (mc1);
					console.log (mc2);
					console.log (mc3);
					
					// variables to pass
					var part_a = "";
					var part_b = "";
					var part_c = "";
					var part_d = "";
					var part_e = "";
					var part_f = "";
					var part_g = "";
					var part_h = "";
					var part_i = "";
					var part_j = "";
				
					console.log (n);
					console.log(problem);
					console.log(inde);
					console.log(mc1);
					console.log(mc2);
						console.log(mc3);
					
					
					$.post('fetchpblmqa.php', {problem_id : problem, dex : inde , mc1 : mc1, mc2 : mc2, mc3 : mc3 , n : n }, function(data){
						
						try{
							var arr2 = JSON.parse(data);
						}
						catch(err) {
						//	var arr2 -> data.text();
							
							alert ('problem qa data is unavailable')
							alert (err);
							alert (arr2);
						}
				// construct the multiple choice option strings
				var mc1_str = "<p ><font size = '3'> &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;       i) "+arr2.opt_i_1+"   &nbsp;&nbsp;   ii) " +arr2.opt_ii_1+"  &nbsp;&nbsp;    iii) "+ arr2.opt_iii_1 + "  &nbsp;&nbsp;    iv) "+arr2.opt_iv_1+"</font> </p>";
				var mc2_str = " <p ><font size = '3'>  &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;    &nbsp; &nbsp;&nbsp;        i) "+arr2.opt_i_2+"  &nbsp;&nbsp;    ii) " +arr2.opt_ii_2+"  &nbsp;&nbsp;    iii) "+ arr2.opt_iii_2 + "   &nbsp;&nbsp;   iv) "+arr2.opt_iv_2+"</font> </p>";
				var mc3_str = " <p ><font size = '3'>   &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;     &nbsp; &nbsp;&nbsp;      i) "+arr2.opt_i_3+"  &nbsp;&nbsp;    ii) " +arr2.opt_ii_3+"   &nbsp;&nbsp;   iii) "+ arr2.opt_iii_3 + " &nbsp;&nbsp;     iv) "+arr2.opt_iv_3+"</font> </p>";
				
				
				// give them the answers for the parts of the problem the instructor wants to give them the answers
					if(give_ans.includes("ans_a")){part_a = "<p ><font size = '2'> &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_a+"</font> </p>";}
					if(give_ans.includes("ans_b")){part_b = "<p ><font size = '2'> &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_b+"</font> </p>";}
					if(give_ans.includes("ans_c")){part_c = "<p ><font size = '2'> &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_c+"</font> </p>";}
					if(give_ans.includes("ans_d")){part_d = "<p ><font size = '2'> &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_d+"</font> </p>";}
					if(give_ans.includes("ans_e")){part_e = "<p ><font size = '2'>&nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;  &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_e+"</font> </p>";}
					if(give_ans.includes("ans_f")){part_f = "<p ><font size = '2'> &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_f+"</font> </p>";}
					if(give_ans.includes("ans_g")){part_g = "<p ><font size = '2'>&nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;  &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_g+"</font> </p>";}
					if(give_ans.includes("ans_h")){part_h = "<p ><font size = '2'> &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_h+"</font> </p>";}
					if(give_ans.includes("ans_i")){part_i = "<p ><font size = '2'>&nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;  &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_i+"</font> </p>";}
					if(give_ans.includes("ans_j")){part_j = "<p ><font size = '2'> &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;   &nbsp; &nbsp;&nbsp;the answer to this part is "+arr2.ans_j+"</font> </p>";}
					
				// define the different parts of - know I should read into an array but...	
					if(mc1 == "ans_a"){part_a = mc1_str;}
					if(mc1 == "ans_b"){part_b = mc1_str;}
					if(mc1 == "ans_c"){part_c = mc1_str;}
					if(mc1 == "ans_d"){part_d = mc1_str;}
					if(mc1 == "ans_e"){part_e = mc1_str;}
					if(mc1 == "ans_f"){part_f = mc1_str;}
					if(mc1 == "ans_g"){part_g = mc1_str;}
					if(mc1 == "ans_h"){part_h = mc1_str;}
					if(mc1 == "ans_i"){part_i = mc1_str;}
					if(mc1 == "ans_j"){part_j = mc1_str;}
					
					if(mc2 == "ans_a"){part_a = mc2_str;}
					if(mc2 == "ans_b"){part_b = mc2_str;}
					if(mc2 == "ans_c"){part_c = mc2_str;}
					if(mc2 == "ans_d"){part_d = mc2_str;}
					if(mc2 == "ans_e"){part_e = mc2_str;}
					if(mc2 == "ans_f"){part_f = mc2_str;}
					if(mc2 == "ans_g"){part_g = mc2_str;}
					if(mc2 == "ans_h"){part_h = mc2_str;}
					if(mc2 == "ans_i"){part_i = mc2_str;}
					if(mc2 == "ans_j"){part_j = mc2_str;}
					
					if(mc3 == "ans_a"){part_a = mc3_str;}
					if(mc3 == "ans_b"){part_b = mc3_str;}
					if(mc3 == "ans_c"){part_c = mc3_str;}
					if(mc3 == "ans_d"){part_d = mc3_str;}
					if(mc3 == "ans_e"){part_e = mc3_str;}
					if(mc3 == "ans_f"){part_f = mc3_str;}
					if(mc3 == "ans_g"){part_g = mc3_str;}
					if(mc3 == "ans_h"){part_h = mc3_str;}
					if(mc3 == "ans_i"){part_i = mc3_str;}
					if(mc3 == "ans_j"){part_j = mc3_str;}
					
					
					console.log ('part_a', part_a);
					
					console.log ('part_d', part_d);
						console.log ('mc1_str', mc1_str);
					
					
					var key_1 = arr2.key_1;
					var opt_i_1 = arr2.opt_i_1;
					var opt_ii_1 = arr2.opt_ii_1;
					var opt_iii_1 = arr2.opt_iii_1;
					var opt_iv_1 = arr2.opt_iv_1;
					console.log('1st muliple choice Options');
					console.log(key_1);
					console.log(opt_i_1);
					console.log(opt_ii_1);
					console.log(opt_iii_1);
					console.log(opt_iv_1);
					console.log('2nd muliple choice Options');
					console.log(arr2.key_2);
					console.log(arr2.opt_i_2);
					console.log(arr2.opt_ii_2);
					console.log(arr2.opt_iii_2);
					console.log(arr2.opt_iv_2);
						console.log('3rd muliple choice Options');
					console.log(arr2.key_3);
					console.log(arr2.opt_i_3);
					console.log(arr2.opt_ii_3);
					console.log(arr2.opt_iii_3);
					console.log(arr2.opt_iv_3);
				
					// alert ('wowo');
					
					//console.log(resp_a[50]);
				// console.log (resp_a);	
					// Get the html file name from the database
						var static_f = true;
						var openup = arr.htmlfilenm;
						
						// alert(openup);
						
						var game = arr.game_prob_flag;
						var status = arr.status;
						var prob_num = arr.problem_id;
						var contrib_first = arr.first;
						var contrib_last = arr.last;
						var contrib_university = arr.university;
						
						$('#substitute_me').load("uploads/"+openup, 'document').html();
						
						sessionStorage.setItem('contrib_first',contrib_first);
						sessionStorage.setItem('contrib_last',contrib_last);
						sessionStorage.setItem('contrib_university',contrib_university);
						sessionStorage.setItem('nm_author',arr.nm_author);
						sessionStorage.setItem('specif_ref',arr.specif_ref);
						
					//	console.log(contrib_first);
					//	console.log('arr', arr);
						if (status !== 'suspended'){
							
								sessionStorage.setItem('nv_1',arr.nv_1);
								sessionStorage.setItem(arr.nv_1,arr.v_1);
								sessionStorage.setItem('nv_2',arr.nv_2);
								sessionStorage.setItem(arr.nv_2,arr.v_2);
								sessionStorage.setItem('nv_3',arr.nv_3);
								sessionStorage.setItem(arr.nv_3,arr.v_3);
								sessionStorage.setItem('nv_4',arr.nv_4);
								sessionStorage.setItem(arr.nv_4,arr.v_4);
								sessionStorage.setItem('nv_5',arr.nv_5);
								sessionStorage.setItem(arr.nv_5,arr.v_5);
								sessionStorage.setItem('nv_6',arr.nv_6);
								sessionStorage.setItem(arr.nv_6,arr.v_6);
								sessionStorage.setItem('nv_7',arr.nv_7);
								sessionStorage.setItem(arr.nv_7,arr.v_7);
								sessionStorage.setItem('nv_8',arr.nv_8);
								sessionStorage.setItem(arr.nv_8,arr.v_8);
								sessionStorage.setItem('nv_9',arr.nv_9);
								sessionStorage.setItem(arr.nv_9,arr.v_9);
								sessionStorage.setItem('nv_10',arr.nv_10);
								sessionStorage.setItem(arr.nv_10,arr.v_10);
								sessionStorage.setItem('nv_11',arr.nv_11);
								sessionStorage.setItem(arr.nv_11,arr.v_11);
								sessionStorage.setItem('nv_12',arr.nv_12);
								sessionStorage.setItem(arr.nv_12,arr.v_12);
								sessionStorage.setItem('nv_13',arr.nv_13);
								sessionStorage.setItem(arr.nv_13,arr.v_13);
								sessionStorage.setItem('nv_14',arr.nv_14);
								sessionStorage.setItem(arr.nv_14,arr.v_14);
								
								
								sessionStorage.setItem('title',arr.title);
								//sessionStorage.setItem('stu_name',s_name);
								sessionStorage.setItem('problem_id',problem);
								sessionStorage.setItem('index',inde);
								sessionStorage.setItem('static_flag',static_f);
								sessionStorage.setItem('MC_flag','true');
								sessionStorage.setItem('key_1',arr2.key_1);
								sessionStorage.setItem('key_2',arr2.key_2);
								sessionStorage.setItem('key_3',arr2.key_3);
								sessionStorage.setItem('part_a',part_a);
								sessionStorage.setItem('part_b',part_b);
								sessionStorage.setItem('part_c',part_c);
								sessionStorage.setItem('part_d',part_d);
								sessionStorage.setItem('part_e',part_e);
								sessionStorage.setItem('part_f',part_f);
								sessionStorage.setItem('part_g',part_g);
								sessionStorage.setItem('part_h',part_h);
								sessionStorage.setItem('part_i',part_i);
								sessionStorage.setItem('part_j',part_j);
								sessionStorage.setItem('show_key',show_key);


								
								// if the give answers box is checked send the answers to the page
							//	if (){}
								
								
								
							// this comes from https://stackoverflow.com/questions/14644558/call-javascript-function-after-script-is-loaded 		
						
						function loadScript( url, callback ) {
							  var script = document.createElement( "script" )
							  script.type = "text/javascript";
							  if(script.readyState) {  // only required for IE <9
								script.onreadystatechange = function() {
								  if ( script.readyState === "loaded" || script.readyState === "complete" ) {
									script.onreadystatechange = null;
									callback();
								  }
								};
							  } else {  //Others
								script.onload = function() {		``
								  callback();
								};
							  }

							  script.src = "Substvars.js";
							  document.getElementsByTagName( "head" )[0].appendChild( script );
							}


							// call the function...
							loadScript("Substvars.js", function() {
							//  alert('script ready!'); 
								var imgPath = '';
								var indexQRP = '';
								var addPath = "uploads/";
							//	alert(addPath);
										$('img').each(function(){
											
											imgPath = $(this).prop('src');
												console.log('imagepath before',imgPath);
										//		alert (imgPath);
												//referrer.toLowerCase().indexOf
											indexQRP = imgPath.toLowerCase().indexOf('/qrp/')+5;
											console.log('indexofQRP',indexQRP);
											imgPath = [imgPath.slice(0, indexQRP), addPath, imgPath.slice(indexQRP)].join('');
											console.log('imagepath',imgPath);
											
											$(this).prop('src', imgPath);
										
										});
							});
								
							//	});	
						
							// window.location.href="uploads/"+openup;
								
						 } else {
							
								alert('This problem is temporarily suspended, please check back later.');
								//window.location.href="QRhomework.php";
								
								statusFlag=false;
								//return;
							

						 }

						 
					
					});
				  });			
			  
			 
			  
			  
			  
			  
					}
						else{
							
							alert ('invalid user input');
							
							
							}
					
			
				});
			}
			
			
			
			
			} else {
					alert ("invalid input - index must have a value between 2 and 200 and both time values must have values");
	
			}	
					
					
					
			});
	});
	
});
	
	
</script>

</body>
</html>



