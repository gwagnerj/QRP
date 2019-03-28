<?php
	require_once "pdo.php";
	session_start();
	


	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>Add Concept</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="DataTables-1.10.18/js/jquery.dataTables.js"></script>

</head>

<body>
<header>
<h1>Adding a Concept Title to the Database </h1>
<h3><font color = blue> Please Search the Data Base for Synonyms before Adding this Concept  </font>  </h3>
</header>

<?php
	 $_SESSION['error'] = '';
	 $_SESSION['sucess'] = '';
	 
	
	
	if (isset($_POST['cancel'])) {
		
		//echo ('you cancelled');
		 header( 'Location: QRPRepo.php' ) ;
		 return; 
		
	}
		$concept_name = $syn1 = $syn2 = $syn3 = "";
		$syn4 = $syn5 = $syn6 = $syn7 = "";
	
	if(isset($_POST['concept']) && strlen($_POST['concept'])>0){
		$concept_name = htmlentities ($_POST['concept']);
		if(isset($_POST['syn1']) && strlen($_POST['syn1'])>0){
				$syn1 = $_POST['syn1'];
		}
		if(isset($_POST['syn2']) && strlen($_POST['syn2'])>0){
				$syn2 = $_POST['syn2'];
		}
		if(isset($_POST['syn3']) && strlen($_POST['syn3'])>0){
				$syn3 = $_POST['syn3'];
		}
		if(isset($_POST['syn4']) && strlen($_POST['syn4'])>0){
				$syn4 = $_POST['syn4'];
		}
		if(isset($_POST['syn5']) && strlen($_POST['syn5'])>0){
				$syn5 = $_POST['syn5'];
		}
		if(isset($_POST['syn6']) && strlen($_POST['syn6'])>0){
				$syn6 = $_POST['syn6'];
		}
		if(isset($_POST['syn7']) && strlen($_POST['syn7'])>0){
				$syn7 = $_POST['syn7'];
		}
		try {
				$sql = "INSERT INTO Concept (concept_name, synonym1, synonym2, synonym3,synonym4, synonym5, synonym6,synonym7)
							VALUES (:concept_name, :syn1, :syn2, :syn3, :syn4, :syn5, :syn6, :syn7)";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
					':concept_name' => $concept_name,
					':syn1' => $syn1,
					':syn2' => $syn2,
					':syn3' => $syn3,
					':syn4' => $syn4,
					':syn5' => $syn5,
					':syn6' => $syn6,
					':syn7' => $syn7,
					));
					 $_SESSION['sucess'] = 'the concept was added to database';
					 header( 'Location: QRPRepo.php' ) ;
					 return; 
		
		} catch (PDOException $e) {
			echo ('duplicate error');
			 $_SESSION['error'] = $e -> getMessage();
			
		}
		
		
		
	} else {
		 $_SESSION['error'] = 'something went wrong when adding the concept to the database';
	}
 
?>

<!--<h3>Print the problem statement with "Ctrl P"</h3>
 <p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p>  -->
<form  method="POST"  autocomplete = 'off' >
	
	<p><font color=#003399>Title of Concept: </font><input type="text" name="concept" id = "concept" size= 40   > <input type = "button" name = "add synonym" value = "Add Synonym" id = "add_syn1"> </p>
<!-- 	<div class="input_fields_wrap">
    <button class="add_field_button">Add Synonyms</button>
    <div id = "blank"><input type="text" name="syn[]"></div>
	</div>
	 -->
	<p><font color=#003399><div id = "syn1">Synonym 1: </font><input type="text" name="syn1"  size= 40   > <input type = "button" name = "add synonym" value = " Add Synonym" id = "add_syn2"> <input type = "button"  value = " Remove Synonym" id = "rem_syn2"> </div>
	<p><font color=#003399><div id = "syn2">Synonym 2: </font><input type="text" name="syn2"  size= 40   > <input type = "button" name = "add synonym" value = " Add Synonym" id = "add_syn3"> <input type = "button"  value = " Remove Synonym" id = "rem_syn3"> </div> 
	<p><font color=#003399><div id = "syn3">Synonym 3: </font><input type="text" name="syn3"  size= 40   > <input type = "button" name = "add synonym" value = " Add Synonym" id = "add_syn4"> <input type = "button"  value = " Remove Synonym" id = "rem_syn4"> </div> 
	<p><font color=#003399><div id = "syn4">Synonym 4: </font><input type="text" name="syn4"  size= 40   > <input type = "button" name = "add synonym" value = " Add Synonym" id = "add_syn5"> <input type = "button"  value = " Remove Synonym" id = "rem_syn5"> </div> 
	<p><font color=#003399><div id = "syn5">Synonym 5: </font><input type="text" name="syn5" size= 40   > <input type = "button" name = "add synonym" value = " Add Synonym" id = "add_syn6"> <input type = "button"  value = " Remove Synonym" id = "rem_syn6"> </div> 
	<p><font color=#003399><div id = "syn6">Synonym 6: </font><input type="text" name="syn6"  size= 40   > <input type = "button" name = "add synonym" value = " Add Synonym" id = "add_syn7"> <input type = "button"  value = " Remove Synonym" id = "rem_syn7"> </div> 
	<p><font color=#003399><div id = "syn7">Synonym 7: </font><input type="text" name="syn7"  size= 40   > <input type = "button"  value = " Remove Synonym" id = "rem_syn8"> </div> 
<!--	<input type="hidden" name="submitted" value="name" /> -->
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "submit" name "submit"> 
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	
	<input type="submit" name="cancel" value="Cancel" />
	<p> <br> </p>
	<b>Recorded Concepts: </b>
		<hr>	
		
	</form>
	
	
	<Script>
	$(document).ready( function () {
			$('#syn1').hide();
			$('#syn2').hide();
			$('#syn3').hide();
			$('#syn4').hide();
			$('#syn5').hide();
			$('#syn6').hide();
			$('#syn7').hide();
			$('#blank').hide();
		
				
					$('#add_syn1').click(function() {
						$('#syn1').show();
					});
					$('#add_syn2').click(function() {
						$('#syn2').show();
					});
					$('#rem_syn2').click(function() {
						$('input[name="syn1"').val("");
						$('#syn1').hide();
					});
					$('#add_syn3').click(function() {
						$('#syn3').show();
					});
					$('#rem_syn3').click(function() {
						$('input[name="syn2"').val("");
						$('#syn2').hide();
					});
					$('#add_syn4').click(function() {
						$('#syn4').show();
					});
					$('#rem_syn4').click(function() {
						$('input[name="syn3"').val("");
						$('#syn3').hide();
					});
					$('#add_syn5').click(function() {
						$('#syn5').show();
					});
					$('#rem_syn5').click(function() {
						$('input[name="syn4"').val("");
						$('#syn4').hide();
					});
					$('#add_syn6').click(function() {
						$('#syn6').show();
					});
					$('#rem_syn6').click(function() {
						$('input[name="syn5"').val("");
						$('#syn5').hide();
					});
					$('#add_syn7').click(function() {
						$('#syn7').show();
					});
					$('#rem_syn7').click(function() {
						$('input[name="syn6"').val("");
						$('#syn6').hide();
					});
					$('#rem_syn8').click(function() {
						$('input[name="syn7"').val("");
						$('#syn7').hide();
					});
					
					
					 $('#table_format').DataTable({"sDom": 'W<"clear">lfrtip',
						"order": [[ 1, 'asc' ] ],
						 "lengthMenu": [ 50, 100, 200 ],
						"oColumnFilterWidgets": {
						"aiExclude": [ 0 ] }});
					
			//	if($('#concept').val().length >1){}
			
		/* 	var max_fields      = 7; //maximum input boxes allowed
			var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
			var add_button      = $(".add_field_button"); //Add button ID
	
			var x = 0; //initlal text box count
			$(add_button).click(function(e){ //on add input button click
				e.preventDefault();
				if(x < max_fields){ //max input box allowed
					x++; //text box increment
				$(wrapper).append('<div><input type="text" name="syn[]"/><a href="#" class="remove_field">Remove</a></div>'); //add input box
		}
	});
	
	$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
		e.preventDefault(); $(this).parent('div').remove(); x--; */
	})
			
			
			
			
			
			
			
			/* $('#add_syn').click(function(e) {
					 e.preventDefault();
					 
					
				 });
				 */
				
				
				
				
				// if ($(this).is(':checked')){ //radio is now checked
				// 	$(".reflection").prop('checked', false);
					
					// $('input[type="checkbox"]').prop('checked', false); //unchecks all checkboxes
				//}
			// });

			// $('.reflection').change(function() {
			// $('input[type="checkbox"]').change(function() {
			//	if ($(this).is(':checked')){
			//		$('input[type="radio"]').prop('checked', false);
			//	}
	

	
	
	
	
	
	
	</script>
	<?php
	
	 echo ('<table id="table_format" class = "a" border="1" >'."\n");
	

	
	 echo("<thead>");

	echo("</td><th>");
	echo('Num');
	echo("</th><th>");
	echo('Concept');
   echo("</th><th>");
	echo('synonym1');
	 echo("</th><th>");
	echo('synonym2');
	 echo("</th><th>");
	echo('synonym3');
	 echo("</th><th>");
	echo('synonym4');
	 echo("</th><th>");
	echo('synonym5');
	 echo("</th><th>");
	echo('synonym6');
	 echo("</th><th>");
	echo('synonym7');
	echo("</th></tr>\n");
	 echo("</thead>");
	 
	  echo("<tbody>");
	 
	 $stmt = "SELECT * FROM `concept`";
	 $stmt2 = $pdo->query($stmt);
	 while ( $row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
			echo "<tr><td>";
			echo($row['concept_id']);
			echo("</td><td>");	
			echo(htmlentities($row['concept_name']));
			echo("</td><td>");	
			echo(htmlentities($row['synonym1']));
				echo("</td><td>");	
			echo(htmlentities($row['synonym2']));
				echo("</td><td>");	
			echo(htmlentities($row['synonym3']));
				echo("</td><td>");	
			echo(htmlentities($row['synonym4']));
				echo("</td><td>");	
			echo(htmlentities($row['synonym5']));
				echo("</td><td>");	
			echo(htmlentities($row['synonym6']));
				echo("</td><td>");	
			echo(htmlentities($row['synonym7']));
			echo("</td></tr>\n");
	}
	echo("</tbody>");
	echo("</table>");
	?>


</body>
</html>



