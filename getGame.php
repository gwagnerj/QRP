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
<h1>Quick Response Game </h1>
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
<h3>  <span id = "QRtoGame">  </span></h3>
<h2> <font color=#003399> <span id = "game_num">  </span> </font> </h2>
<h3>  <span id = "exp_date">  </span></h3>
<div id = "substitute_me">

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
	<div id ="dex_input"><p><font color="red">Index of Dataset - must print off each version since the number of vars > selected shapes </font><input type="number" name="dex" id="dex" size=3 required min = "2" max = "199" ></p></div>
	<p><font color=#003399>Prep Time in Minutes for Discussion on How to  Work Problem</font><input type="number" name="prep_time" id="prep_time" value = 1 size=3 required min = "1" max = "15" ></p>
	<p><font color=#003399>Work Time in Minutes for Students to Work Problem </font><input type="number" name="work_time" id="work_time" value = 15 size=3 required min = "1" max = "199" ></p>
	<p><font color=#003399>Post Time in Minutes for Post Problem Analysis </font><input type="number" name="post_time" id="post_time" value = 1 size=3 required min = "1" max = "15" ></p>
	<p><font color=#003399>How long in days to keep this Game Problem Active </font><input type="number" name="days_till_delete" value = 30 id="days_till_delete" size=3 required min = "1" ></p>
	
	
	
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
				
				<td><span class = "nv_1"><input type="radio" name="rect" Value="v_1" checked = "checked"> </span></td>
				<td><span class = "nv_2"><input type="radio" name="rect" Value="v_2"> </span></td>
				<td><span class = "nv_3"><input type="radio" name="rect" Value="v_3"> </span></td>
				<td><span class = "nv_4"><input type="radio" name="rect" Value="v_4"> </span></td>
				<td><span class = "nv_5"><input type="radio" name="rect" Value="v_5"> </span></td>
				<td><span class = "nv_6"><input type="radio" name="rect" Value="v_6"> </span></td>
				<td><span class = "nv_7"><input type="radio" name="rect" Value="v_7"> </span></td>
				<td><span class = "nv_8"><input type="radio" name="rect" Value="v_8"> </span></td>
				<td><span class = "nv_9"><input type="radio" name="rect" Value="v_9"> </span></td>
				<td><span class = "nv_10"><input type="radio" name="rect" Value="v_10"> </span></td>
				<td><span class = "nv_11"><input type="radio" name="rect" Value="v_11"> </span></td>
				<td><span class = "nv_12"><input type="radio" name="rect" Value="v_12"> </span></td>
				<td><span class = "nv_13"><input type="radio" name="rect" Value="v_13"> </span></td>
				<td><span class = "nv_14"><input type="radio" name="rect" Value="v_14"> </span></td>
			
			
			<tr><td>	<font color = "blue" size =5 > Oval </font> </td>
				
				<td><span class = "nv_1"><input type="radio" name="oval" Value="v_1" > </span></td>
				<td><span class = "nv_2"><input type="radio" name="oval" Value="v_2" checked = "checked"> </span></td>
				<td><span class = "nv_3"><input type="radio" name="oval" Value="v_3"> </span></td>
				<td><span class = "nv_4"><input type="radio" name="oval" Value="v_4"> </span></td>
				<td><span class = "nv_5"><input type="radio" name="oval" Value="v_5"> </span></td>
				<td><span class = "nv_6"><input type="radio" name="oval" Value="v_6"> </span></td>
				<td><span class = "nv_7"><input type="radio" name="oval" Value="v_7"> </span></td>
				<td><span class = "nv_8"><input type="radio" name="oval" Value="v_8"> </span></td>
				<td><span class = "nv_9"><input type="radio" name="oval" Value="v_9"> </span></td>
				<td><span class = "nv_10"><input type="radio" name="oval" Value="v_10"> </span></td>
				<td><span class = "nv_11"><input type="radio" name="oval" Value="v_11"> </span></td>
				<td><span class = "nv_12"><input type="radio" name="oval" Value="v_12"> </span></td>
				<td><span class = "nv_13"><input type="radio" name="oval" Value="v_13"> </span></td>
				<td><span class = "nv_14"><input type="radio" name="oval" Value="v_14"> </span></td>
				
			<tr><td>	<font color = "blue" size =5 > Trapazoid </font> </td>
				
				<td><span class = "nv_1"><input type="radio" name="trap" Value="v_1" > </span></td>
				<td><span class = "nv_2"><input type="radio" name="trap" Value="v_2"> </span></td>
				<td><span class = "nv_3"><input type="radio" name="trap" Value="v_3" checked = "checked"> </span></td>
				<td><span class = "nv_4"><input type="radio" name="trap" Value="v_4"> </span></td>
				<td><span class = "nv_5"><input type="radio" name="trap" Value="v_5"> </span></td>
				<td><span class = "nv_6"><input type="radio" name="trap" Value="v_6"> </span></td>
				<td><span class = "nv_7"><input type="radio" name="trap" Value="v_7"> </span></td>
				<td><span class = "nv_8"><input type="radio" name="trap" Value="v_8"> </span></td>
				<td><span class = "nv_9"><input type="radio" name="trap" Value="v_9"> </span></td>
				<td><span class = "nv_10"><input type="radio" name="trap" Value="v_10"> </span></td>
				<td><span class = "nv_11"><input type="radio" name="trap" Value="v_11"> </span></td>
				<td><span class = "nv_12"><input type="radio" name="trap" Value="v_12"> </span></td>
				<td><span class = "nv_13"><input type="radio" name="trap" Value="v_13"> </span></td>
				<td><span class = "nv_14"><input type="radio" name="trap" Value="v_14"> </span></td>
				
			<tr><td>	<font color = "blue" size =5 > Hexagon </font> </td>
				
				<td><span class = "nv_1"><input type="radio" name="hexa" Value="v_1" > </span></td>
				<td><span class = "nv_2"><input type="radio" name="hexa" Value="v_2"> </span></td>
				<td><span class = "nv_3"><input type="radio" name="hexa" Value="v_3"> </span></td>
				<td><span class = "nv_4"><input type="radio" name="hexa" Value="v_4" checked = "checked"> </span></td>
				<td><span class = "nv_5"><input type="radio" name="hexa" Value="v_5"> </span></td>
				<td><span class = "nv_6"><input type="radio" name="hexa" Value="v_6"> </span></td>
				<td><span class = "nv_7"><input type="radio" name="hexa" Value="v_7"> </span></td>
				<td><span class = "nv_8"><input type="radio" name="hexa" Value="v_8"> </span></td>
				<td><span class = "nv_9"><input type="radio" name="hexa" Value="v_9"> </span></td>
				<td><span class = "nv_10"><input type="radio" name="hexa" Value="v_10"> </span></td>
				<td><span class = "nv_11"><input type="radio" name="hexa" Value="v_11"> </span></td>
				<td><span class = "nv_12"><input type="radio" name="hexa" Value="v_12"> </span></td>
				<td><span class = "nv_13"><input type="radio" name="hexa" Value="v_13"> </span></td>
				<td><span class = "nv_14"><input type="radio" name="hexa" Value="v_14"> </span></td>
				
			
				
				
		</table>		
		
	<!-- <p><input type = "checkbox" value=1 name = "show_key" id="show_key" size="14" /> Show Key  </p>-->
	<p><input type = "submit" value="Submit" id="submit_id" size="14" style = "width: 30%; background-color: #003399; color: white"/> &nbsp; &nbsp; </p> 
	</form>

	  </div>
	  
	  <?php
		if($activate_flag== 1){
				echo ('<div id = "cancel_id"><p> &nbsp; </p><hr>');
				echo ('<p><a href="QRPRepo.php">Cancel</a></p>');
				echo ('</div>');
		}
		
		?>
<script>

	var dex = -1;  // this is the value if we don'e have to put in a dex
	var rect = "";
	var oval = "";
	var trap = "";
	var hexa = "";
	var game_id;
	var rect_vnum = "";
	var oval_vnum = "";
	var trap_vnum = "";
	var hexa_vnum = "";
	
	var num_tot_vars = 0;
	var num_checked = 0;
	$("#dex_input").hide();
	
	
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


			if(arrn.nv_14 == null || arrn.nv_14 == "Null" ){$(".nv_14").hide();}else {$("#nv_14").html(arrn.nv_14); num_tot_vars++;}
			if(arrn.nv_13 == null || arrn.nv_13 == "Null" ){$(".nv_13").hide();}else {$("#nv_13").html(arrn.nv_13);num_tot_vars++;}
			if(arrn.nv_12 == null || arrn.nv_12 == "Null" ){$(".nv_12").hide();}else {$("#nv_12").html(arrn.nv_12);num_tot_vars++;}
			if(arrn.nv_11 == null || arrn.nv_11 == "Null" ){$(".nv_11").hide();}else {$("#nv_11").html(arrn.nv_11);num_tot_vars++;}
			if(arrn.nv_10 == null || arrn.nv_10 == "Null" ){$(".nv_10").hide();}else {$("#nv_10").html(arrn.nv_10);num_tot_vars++;}
			if(arrn.nv_9 == null || arrn.nv_9 == "Null" ){$(".nv_9").hide();}else {$("#nv_9").html(arrn.nv_9);num_tot_vars++;}
			if(arrn.nv_8 == null || arrn.nv_8 == "Null" ){$(".nv_8").hide();}else {$("#nv_8").html(arrn.nv_8);num_tot_vars++;}
			if(arrn.nv_7 == null || arrn.nv_7 == "Null" ){$(".nv_7").hide();}else {$("#nv_7").html(arrn.nv_7);num_tot_vars++;}
			if(arrn.nv_6 == null || arrn.nv_6 == "Null" ){$(".nv_6").hide();}else {$("#nv_6").html(arrn.nv_6);num_tot_vars++;}
			if(arrn.nv_5 == null || arrn.nv_5 == "Null" ){$(".nv_5").hide();}else {$("#nv_5").html(arrn.nv_5);num_tot_vars++;}
			if(arrn.nv_4 == null || arrn.nv_4 == "Null" ){$(".nv_4").hide();}else {$("#nv_4").html(arrn.nv_4);num_tot_vars++;}
			if(arrn.nv_3 == null || arrn.nv_3 == "Null" ){$(".nv_3").hide();}else {$("#nv_3").html(arrn.nv_3);num_tot_vars++;}
			if(arrn.nv_2 == null || arrn.nv_2 == "Null" ){$(".nv_2").hide();}else {$("#nv_2").html(arrn.nv_2);num_tot_vars++;}
			if(arrn.nv_1 == null || arrn.nv_1 == "Null" ){$(".nv_1").hide();} else {$("#nv_1").html( arrn.nv_1 );num_tot_vars++;}

			if (num_tot_vars > 4){ $("#dex_input").show();}

			
		
			$('input#submit_id').on('click',function(event){
				
				
				event.preventDefault();
				/* var OneIsChecked = $('input[name = "rect"]:checked').length ==1;  // not sure I need this 
					if(!OneIsChecked){alert('Rectangle needs at least one checked');
					return;
					} */
				console.log ('dex1',dex);
				//	console.log (OneIsChecked);
				if (((dex >= 2 && dex<=200)||dex==-1) && $('input#work_time').val() >= 2 && $('input#work_time').val()<=200 && $('input#days_till_delete').val()>=1){	
				 
				var prep_time = $('input#prep_time').val();
				var work_time = $('input#work_time').val();
				var post_time = $('input#post_time').val();
				var days_till_delete = $('input#days_till_delete').val();
				// get the values of the ones that are checked
				 rect_vnum = $('input[name = "rect"]:checked').val();
				 oval_vnum = $('input[name = "oval"]:checked').val();
				 trap_vnum = $('input[name = "trap"]:checked').val();
				hexa_vnum = $('input[name = "hexa"]:checked').val();
				
				
			
				// this is disgraceful but here goes - got tired of trying more sophisticated stuff
				// in the future will have to put the sessionStorage stuff below after we know what the length of the shapes are
				
				if(rect_vnum == "v_1") {rect = arrn.nv_1; num_checked++;}
				if(rect_vnum == "v_2") {rect = arrn.nv_2; num_checked++;}
				if(rect_vnum == "v_3") {rect = arrn.nv_3; num_checked++;}
				if(rect_vnum == "v_4") {rect = arrn.nv_4; num_checked++;}
				if(rect_vnum == "v_5") {rect = arrn.nv_5; num_checked++;}
				if(rect_vnum == "v_6") {rect = arrn.nv_6; num_checked++;}
				if(rect_vnum == "v_7") {rect = arrn.nv_7; num_checked++;}
				if(rect_vnum == "v_8") {rect = arrn.nv_8; num_checked++;}
				if(rect_vnum == "v_9") {rect = arrn.nv_9; num_checked++;}
				if(rect_vnum == "v_10") {rect = arrn.nv_10; num_checked++;}
				if(rect_vnum == "v_11") {rect = arrn.nv_11; num_checked++;}
				if(rect_vnum == "v_12") {rect = arrn.nv_12; num_checked++;}
				if(rect_vnum == "v_13") {rect = arrn.nv_13; num_checked++;}
				if(rect_vnum == "v_14") {rect = arrn.nv_14; num_checked++;}
				
				if(oval_vnum == "v_1") {oval = arrn.nv_1;num_checked++;}
				if(oval_vnum == "v_2") {oval = arrn.nv_2;num_checked++;}
				if(oval_vnum == "v_3") {oval = arrn.nv_3;num_checked++;}
				if(oval_vnum == "v_4") {oval = arrn.nv_4;num_checked++;}
				if(oval_vnum == "v_5") {oval = arrn.nv_5;num_checked++;}
				if(oval_vnum == "v_6") {oval = arrn.nv_6;num_checked++;}
				if(oval_vnum == "v_7") {oval = arrn.nv_7;num_checked++;}
				if(oval_vnum == "v_8") {oval = arrn.nv_8;num_checked++;}
				if(oval_vnum == "v_9") {oval = arrn.nv_9;snum_checked++;}
				if(oval_vnum == "v_10") {oval = arrn.nv_10;num_checked++;}
				if(oval_vnum == "v_11") {oval = arrn.nv_11;snum_checked++;}
				if(oval_vnum == "v_12") {oval = arrn.nv_12;num_checked++;}
				if(oval_vnum == "v_13") {oval = arrn.nv_13;num_checked++;}
				if(oval_vnum == "v_14") {oval = arrn.nv_14;num_checked++;}
				
				if(trap_vnum == "v_1") {trap = arrn.nv_1; num_checked++;}
				if(trap_vnum == "v_2") {trap = arrn.nv_2;num_checked++;}
				if(trap_vnum == "v_3") {trap = arrn.nv_3;num_checked++;}
				if(trap_vnum == "v_4") {trap = arrn.nv_4;num_checked++;}
				if(trap_vnum == "v_5") {trap = arrn.nv_5;num_checked++;}
				if(trap_vnum == "v_6") {trap = arrn.nv_6;;num_checked++;}
				if(trap_vnum == "v_7") {trap = arrn.nv_7;num_checked++;}
				if(trap_vnum == "v_8") {trap = arrn.nv_8;num_checked++;}
				if(trap_vnum == "v_9") {trap = arrn.nv_9;num_checked++;}
				if(trap_vnum == "v_10") {trap = arrn.nv_10;num_checked++;}
				if(trap_vnum == "v_11") {trap = arrn.nv_11;num_checked++;}
				if(trap_vnum == "v_12") {trap = arrn.nv_12;num_checked++;}
				if(trap_vnum == "v_13") {trap = arrn.nv_13;num_checked++;}
				if(trap_vnum == "v_14") {trap = arrn.nv_14;num_checked++;}
				
				if(hexa_vnum == "v_1") {hexa = arrn.nv_1;num_checked++;}
				if(hexa_vnum == "v_2") {hexa = arrn.nv_2;num_checked++;}
				if(hexa_vnum == "v_3") {hexa = arrn.nv_3;num_checked++;}
				if(hexa_vnum == "v_4") {hexa = arrn.nv_4;num_checked++;}
				if(hexa_vnum == "v_5") {hexa = arrn.nv_5;num_checked++;}
				if(hexa_vnum == "v_6") {hexa = arrn.nv_6;num_checked++;}
				if(hexa_vnum == "v_7") {hexa = arrn.nv_7;num_checked++;}
				if(hexa_vnum == "v_8") {hexa = arrn.nv_8;num_checked++;}
				if(hexa_vnum == "v_9") {hexa = arrn.nv_9;num_checked++;}
				if(hexa_vnum == "v_10") {hexa = arrn.nv_10;num_checked++;}
				if(hexa_vnum == "v_11") {hexa = arrn.nv_11;num_checked++;}
				if(hexa_vnum == "v_12") {hexa = arrn.nv_12;num_checked++;}
				if(hexa_vnum == "v_13") {hexa = arrn.nv_13;num_checked++;}
				if(hexa_vnum == "v_14") {hexa = arrn.nv_14;num_checked++;}
				
				
			
				
				console.log ('num_checked',num_checked);
				console.log ('num_tot_vars',num_tot_vars); 
				
				if ($("#dex_input").is(':visible') && num_checked!=num_tot_vars  ){
					dex = $('input#dex').val();
				 } else {dex = -1;}
				
				
				if(num_checked>=num_tot_vars || dex != -1) {
				
					console.log('problem_id',problem); // temp
					console.log('iid',iid); // temp
					console.log('dex',dex); // temp
				
				
					console.log ('days_till_delete',days_till_delete);
					console.log ('work_time',work_time);
					console.log ('activate_flag',activate_flag);
					
						console.log('prep_time',prep_time); // temp
						console.log('work_time',work_time); // temp
							console.log('post_time',post_time); // temp
				
						console.log('rect_vnum ',rect_vnum);
						console.log('oval_vnum ',oval_vnum);
						console.log('trap_vnum ',trap_vnum);
						console.log('hexa_vnum ',hexa_vnum);
				
				
				
					// now write these values to a php file that will the Game table along with the problem_id and the instructor_id and get the game_id also computes the exp_date
					if (activate_flag == 1) {
						 $.post('GameRW.php', {problem_id : problem, iid : iid, dex : dex, activate_flag : activate_flag, rect : rect, oval : oval, trap : trap, hexa : hexa,
						 rect_vnum : rect_vnum, oval_vnum : oval_vnum, trap_vnum : trap_vnum, hexa_vnum : hexa_vnum ,prep_time :prep_time, work_time : work_time, post_time : post_time, days_till_delete : days_till_delete }, function(data){
									
							try{
								var arrGame = JSON.parse(data);
							}
							catch(err) {
								alert ('problem data unavailable from gameRW',err);
							}
					
							var	game_id = arrGame.game_id;
							var exp_date = arrGame.exp_date;
							console.log ('exp_date',exp_date);
							
							console.log('game_id ',game_id);
								console.log('problem ',problem);
							console.log('dex ',dex);
						
				
							// now get the maximum length of each input varaible so we know how long to make the input boxes also write these values to the Game table so that game setup on the other end knows how big to make them
							 $.post('GameVlength.php', {problem_id : problem, game_id : game_id, dex : dex, rect_vnum : rect_vnum, oval_vnum : oval_vnum, trap_vnum : trap_vnum, hexa_vnum : hexa_vnum }, function(data){
								
									try{
										var arrLength = JSON.parse(data);
									}
									catch(err) {
										alert ('problem data unavailable from Vlength',err);
									}
									
									var rect_length = arrLength.rect_length;
									var oval_length = arrLength.oval_length;
									var trap_length = arrLength.trap_length;
									var hexa_length = arrLength.hexa_length;
									
								/* 	console.log('rect_length',rect_length); //temp
									console.log('oval_length',oval_length);
									console.log('trap_length',trap_length);
									console.log('hexa_length',hexa_length); */
									
									
									// now adjust the length of the shapes
									var char_to_width = 18;
									var rect_width = rect_length * char_to_width+2;
									var oval_width = oval_length * char_to_width+4;
									var trap_width = trap_length * char_to_width+4;
									var hexa_width = hexa_length * char_to_width+4;
									
									var rect_svg = rect_width+6;
									var oval_svg = oval_width+6;
									var trap_svg = trap_width+6;
									
									var trapx_pt2 = trap_width-6;
									
									var hexa_svg = hexa_width+12;
									var hexax_pt2 = hexa_width-6;
									
								/* 	console.log('rect_width',rect_width);
									console.log('oval_width',oval_width);
									console.log('trap_width',trap_width);
									console.log('hexa_width',hexa_width);
									
									console.log('rect_svg',rect_svg);
									console.log('oval_svg',oval_svg);
									console.log('trap_svg',trap_svg);
									console.log('hexa_svg',hexa_svg); */
									
									
									var rect_shape = ' <svg width = '+rect_svg+' height = "26"  > <rect width = '+rect_width+' x="5" y="2" height = "22" fill = "transparent" stroke-width = "3" stroke = "blue"/> </svg> ';
									var oval_shape = ' <svg width = '+oval_svg+' height = "26"  > <rect width = '+oval_width+' x="5" y="3" height = "22" rx = "12" ry = "12" fill = "transparent" stroke-width = "3" stroke = "red"/> </svg> ';
									var trap_shape = '<svg  width = '+trap_svg+' height="26" >  <polygon  fill="white" stroke="green" stroke-width="3" points="7,1 '+trapx_pt2+',1 '+trap_width+',25 1,25"/> </svg>';
									var hexa_shape = '<svg  width = '+hexa_svg+' height="26" >  <polygon  fill="white" stroke="orange" stroke-width="3" points="7,1 '+hexax_pt2+',1 '+hexa_width+',12 '+hexax_pt2+',24 7,24 1,12"/> </svg>';
										

									

										
									if(rect_vnum == "v_1") {sessionStorage.setItem(arrn.nv_1,rect_shape);}
									if(rect_vnum == "v_2") {sessionStorage.setItem(arrn.nv_2,rect_shape);}
									if(rect_vnum == "v_3") {sessionStorage.setItem(arrn.nv_3,rect_shape);}
									if(rect_vnum == "v_4") {sessionStorage.setItem(arrn.nv_4,rect_shape);}
									if(rect_vnum == "v_5") {sessionStorage.setItem(arrn.nv_5,rect_shape);}
									if(rect_vnum == "v_6") {sessionStorage.setItem(arrn.nv_6,rect_shape);}
									if(rect_vnum == "v_7") {sessionStorage.setItem(arrn.nv_7,rect_shape);}
									if(rect_vnum == "v_8") {sessionStorage.setItem(arrn.nv_8,rect_shape);}
									if(rect_vnum == "v_9") {sessionStorage.setItem(arrn.nv_9,rect_shape);}
									if(rect_vnum == "v_10") {sessionStorage.setItem(arrn.nv_10,rect_shape);}
									if(rect_vnum == "v_11") {sessionStorage.setItem(arrn.nv_11,rect_shape);}
									if(rect_vnum == "v_12") {sessionStorage.setItem(arrn.nv_12,rect_shape);}
									if(rect_vnum == "v_13") {sessionStorage.setItem(arrn.nv_13,rect_shape);}
									if(rect_vnum == "v_14") {sessionStorage.setItem(arrn.nv_14,rect_shape);}
									
									if(oval_vnum == "v_1") {sessionStorage.setItem(arrn.nv_1,oval_shape);}
									if(oval_vnum == "v_2") {sessionStorage.setItem(arrn.nv_2,oval_shape);}
									if(oval_vnum == "v_3") {sessionStorage.setItem(arrn.nv_3,oval_shape);}
									if(oval_vnum == "v_4") {sessionStorage.setItem(arrn.nv_4,oval_shape);}
									if(oval_vnum == "v_5") {sessionStorage.setItem(arrn.nv_5,oval_shape);}
									if(oval_vnum == "v_6") {sessionStorage.setItem(arrn.nv_6,oval_shape);}
									if(oval_vnum == "v_7") {sessionStorage.setItem(arrn.nv_7,oval_shape);}
									if(oval_vnum == "v_8") {sessionStorage.setItem(arrn.nv_8,oval_shape);}
									if(oval_vnum == "v_9") {sessionStorage.setItem(arrn.nv_9,oval_shape);}
									if(oval_vnum == "v_10") {sessionStorage.setItem(arrn.nv_10,oval_shape);}
									if(oval_vnum == "v_11") {sessionStorage.setItem(arrn.nv_11,oval_shape);}
									if(oval_vnum == "v_12") {sessionStorage.setItem(arrn.nv_12,oval_shape);}
									if(oval_vnum == "v_13") {sessionStorage.setItem(arrn.nv_13,oval_shape);}
									if(oval_vnum == "v_14") {sessionStorage.setItem(arrn.nv_14,oval_shape);}
									
									if(trap_vnum == "v_1") {sessionStorage.setItem(arrn.nv_1,trap_shape);}
									if(trap_vnum == "v_2") {sessionStorage.setItem(arrn.nv_2,trap_shape);}
									if(trap_vnum == "v_3") {sessionStorage.setItem(arrn.nv_3,trap_shape);}
									if(trap_vnum == "v_4") {sessionStorage.setItem(arrn.nv_4,trap_shape);}
									if(trap_vnum == "v_5") {sessionStorage.setItem(arrn.nv_5,trap_shape);}
									if(trap_vnum == "v_6") {sessionStorage.setItem(arrn.nv_6,trap_shape);}
									if(trap_vnum == "v_7") {sessionStorage.setItem(arrn.nv_7,trap_shape);}
									if(trap_vnum == "v_8") {sessionStorage.setItem(arrn.nv_8,trap_shape);}
									if(trap_vnum == "v_9") {sessionStorage.setItem(arrn.nv_9,trap_shape);}
									if(trap_vnum == "v_10") {sessionStorage.setItem(arrn.nv_10,trap_shape);}
									if(trap_vnum == "v_11") {sessionStorage.setItem(arrn.nv_11,trap_shape);}
									if(trap_vnum == "v_12") {sessionStorage.setItem(arrn.nv_12,trap_shape);}
									if(trap_vnum == "v_13") {sessionStorage.setItem(arrn.nv_13,trap_shape);}
									if(trap_vnum == "v_14") {sessionStorage.setItem(arrn.nv_14,trap_shape);}
									
									if(hexa_vnum == "v_1") {sessionStorage.setItem(arrn.nv_1,hexa_shape);}
									if(hexa_vnum == "v_2") {sessionStorage.setItem(arrn.nv_2,hexa_shape);}
									if(hexa_vnum == "v_3") {sessionStorage.setItem(arrn.nv_3,hexa_shape);}
									if(hexa_vnum == "v_4") {sessionStorage.setItem(arrn.nv_4,hexa_shape);}
									if(hexa_vnum == "v_5") {sessionStorage.setItem(arrn.nv_5,hexa_shape);}
									if(hexa_vnum == "v_6") {sessionStorage.setItem(arrn.nv_6,hexa_shape);}
									if(hexa_vnum == "v_7") {sessionStorage.setItem(arrn.nv_7,hexa_shape);}
									if(hexa_vnum == "v_8") {sessionStorage.setItem(arrn.nv_8,hexa_shape);}
									if(hexa_vnum == "v_9") {sessionStorage.setItem(arrn.nv_9,hexa_shape);}
									if(hexa_vnum == "v_10") {sessionStorage.setItem(arrn.nv_10,hexa_shape);}
									if(hexa_vnum == "v_11") {sessionStorage.setItem(arrn.nv_11,hexa_shape);}
									if(hexa_vnum == "v_12") {sessionStorage.setItem(arrn.nv_12,hexa_shape);}
									if(hexa_vnum == "v_13") {sessionStorage.setItem(arrn.nv_13,hexa_shape);}
									if(hexa_vnum == "v_14") {sessionStorage.setItem(arrn.nv_14,hexa_shape);} 
									
									var inde = Math.abs(dex);
								
									//	var s_name = $('input#stu_name_id').val();
									var statusFlag=true;
								
									if($.trim(problem) != '' && problem > 0 && problem < 100000 && inde>=1 && inde<=200){
								
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
							
							
											var static_f = true;
										
											
											sessionStorage.setItem('contrib_first',contrib_first);
											sessionStorage.setItem('contrib_last',contrib_last);
											sessionStorage.setItem('contrib_university',contrib_university);
											sessionStorage.setItem('nm_author',arr.nm_author);
											sessionStorage.setItem('specif_ref',arr.specif_ref);
								
											//	console.log(contrib_first);
											//	console.log('arr', arr);
											if (status !== 'suspended'){
												sessionStorage.setItem('nv_1',arr.nv_1);
												sessionStorage.setItem('nv_2',arr.nv_2);
												sessionStorage.setItem('nv_3',arr.nv_3);
												sessionStorage.setItem('nv_4',arr.nv_4);
												sessionStorage.setItem('nv_5',arr.nv_5);
												sessionStorage.setItem('nv_6',arr.nv_6);
												sessionStorage.setItem('nv_7',arr.nv_7);
												sessionStorage.setItem('nv_8',arr.nv_8);
												sessionStorage.setItem('nv_9',arr.nv_9);
												sessionStorage.setItem('nv_10',arr.nv_10);
												sessionStorage.setItem('nv_10',arr.nv_11);
												sessionStorage.setItem('nv_12',arr.nv_12);
												sessionStorage.setItem('nv_13',arr.nv_13);
												sessionStorage.setItem('nv_14',arr.nv_14);
												
												if(sessionStorage.getItem(arrn.nv_1)==null){sessionStorage.setItem(arr.nv_1,arr.v_1);}
												if(sessionStorage.getItem(arrn.nv_2)==null){sessionStorage.setItem(arr.nv_2,arr.v_2);}
												if(sessionStorage.getItem(arrn.nv_3)==null){sessionStorage.setItem(arr.nv_3,arr.v_3);}
												if(sessionStorage.getItem(arrn.nv_4)==null){sessionStorage.setItem(arr.nv_4,arr.v_4);}
												if(sessionStorage.getItem(arrn.nv_5)==null){sessionStorage.setItem(arr.nv_5,arr.v_5);}
												if(sessionStorage.getItem(arrn.nv_6)==null){sessionStorage.setItem(arr.nv_6,arr.v_6);}
												if(sessionStorage.getItem(arrn.nv_7)==null){sessionStorage.setItem(arr.nv_7,arr.v_7);}
												if(sessionStorage.getItem(arrn.nv_8)==null){sessionStorage.setItem(arr.nv_8,arr.v_8);}
												if(sessionStorage.getItem(arrn.nv_9)==null){sessionStorage.setItem(arr.nv_9,arr.v_9);}
												if(sessionStorage.getItem(arrn.nv_10)==null){sessionStorage.setItem(arr.nv_10,arr.v_10);}
												if(sessionStorage.getItem(arrn.nv_11)==null){sessionStorage.setItem(arr.nv_11,arr.v_11);}
												if(sessionStorage.getItem(arrn.nv_12)==null){sessionStorage.setItem(arr.nv_12,arr.v_12);}
												if(sessionStorage.getItem(arrn.nv_13)==null){sessionStorage.setItem(arr.nv_13,arr.v_13);}
												if(sessionStorage.getItem(arrn.nv_14)==null){sessionStorage.setItem(arr.nv_14,arr.v_14);}
										
												console.log ("WTF",sessionStorage.getItem(arr.nv_1)); // temp
												console.log (sessionStorage.getItem(arr.nv_2));
												console.log (sessionStorage.getItem(arr.nv_3));
												console.log (sessionStorage.getItem(arr.nv_4));
												
												
												
												sessionStorage.setItem('title',arr.title);
												
												sessionStorage.setItem('problem_id',problem);
												sessionStorage.setItem('dex',dex);
												sessionStorage.setItem('static_flag',static_f);
												sessionStorage.setItem('MC_flag','false');
										


										
												// if the give answers box is checked send the answers to the page
								
										
										
										
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
												
											//	var exp_date = exp_date.replace(/:/g,"-");
												var yy = exp_date.substring(0,2);
												var mm 	= exp_date.substring(3,5);
												var dd 	= exp_date.substring(6,8);	
												var exp_date2 = mm +"/"+dd+"/20"+yy;	
												
											//	var msec = Date.parse(exp_date);
											//	var exp_date2 = new Date(msec);
												
											//	exp_date2 = new Date(exp_date);
												$("#game_num").html('Game Number: '+ game_id+' \xa0\xa0 Please check your responses with qrgames.org or scan the QR code.\xa0\xa0\xa0\xa0  <img border=0 width=50 height=50 id="qr_game" src="qr_game.svg">');
												$("#exp_date").html("Expires: "+ exp_date2);
												$("#cancel_id").hide();
											
											//	$("#QRtoGame").html('Please check your responses with qrgames.org or scan the QR code.  <img border=0 width=50 height=50 id="qr_game" src="qr_game.svg">');
																		
																			 $( "p" ).each(function( index ) {
																			   var current_content =  $(this).text();
																			   // put in a div element at the start of the markup
																			  if( current_content.indexOf("##") !=-1) {
																				alert ('did not substitute in all variables');

																				// $(this).closest('p').before('<div id="box1-start">');
																			  } else {
																				  console.log ('did substitute in all variables');
																				  
																			  }
																			
																			});
												
														
												
												
												
												
												
												
												
										
											} else {
									
												alert('This problem is temporarily suspended, please check back later.');
												//window.location.href="QRrepo.php";
											
												statusFlag=false;
												//return;
											}
							
										});			// fetch problem input
				  
									} else {  // input to get problem data ok
										alert ('invalid user input');
									}
						
				
								}); // get Variable length so we know what size to make boxes
						}); // Put selected varaibles into the Game table
				
					} // activate flag is set
				} else {$("#dex_input").show(); num_checked = 0; // alert ("must select more variables or fill in a the index set for each version of the game problem" );
				}
				
			} else {alert ("invalid input - index must have a value between 2 and 200 and both time values must have values");
			}	
						
			 // numbers in form are valid
		}); // on click
		
	});  // fetch vars in problem
	
});	  // doc ready
</script>

</body>
</html>



