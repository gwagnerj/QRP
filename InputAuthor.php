<?php
	require_once "pdo.php";
	session_start();
	
	 $_SESSION['error'] = '';
	 $_SESSION['sucess'] = '';
	 
	//echo ($_SESSION['course']);
	//die();
	
	if (isset($_POST['cancel'])) {
		
		//echo ('you cancelled');
		 header( 'Location: requestPblmNum.php' ) ;
		 return; 
		
	}
		$author_name = $syn1 = $syn2 = $syn3 = "";
		$syn4 = $syn5 = $syn6 = $syn7 = "";
	
	if(isset($_POST['author']) && strlen($_POST['author'])>0){
		$author_name = htmlentities ($_POST['author']);
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
				$sql = "INSERT INTO Author (author_name, synonym1, synonym2, synonym3,synonym4, synonym5, synonym6,synonym7)
							VALUES (:author_name, :syn1, :syn2, :syn3, :syn4, :syn5, :syn6, :syn7)";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
					':author_name' => $author_name,
					':syn1' => $syn1,
					':syn2' => $syn2,
					':syn3' => $syn3,
					':syn4' => $syn4,
					':syn5' => $syn5,
					':syn6' => $syn6,
					':syn7' => $syn7,
					));
					 $_SESSION['sucess'] = 'the author was added to database';
					 
					 // now get the course id from the data table
					 
					$author_id=$pdo->lastInsertId();
					
					$stmt = "SELECT Course.course_id FROM Course WHERE Course.course_name ="."'".$_SESSION['course']."'";
					$stmt = $pdo->query($stmt);
					$coursess = $stmt->fetchALL(PDO::FETCH_ASSOC);  // this is an array of arrays ugh
					
					
					$courses = $coursess[0];
					$course_id = $courses['course_id'];
					
					// connect the concept to the course so it ends up added to the list when you pull down the concept
					
					$sql = "INSERT INTO CourseAuthorConnect (course_id, author_id)
							VALUES (:course_id, :author_id)";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
					':course_id' => $course_id,
					':author_id' => $author_id
					));
					
					 $_SESSION['success'] = 'the author was added to database';
					 
					 
					 header( 'Location: requestPblmNum.php' ) ;
					 return; 
		
		} catch (PDOException $e) {
			echo ('duplicate error');
			 $_SESSION['error'] = $e -> getMessage();
			
			
		}
		
		
		
		
		
		
		
		
		
		
		
	} else {
		 $_SESSION['error'] = 'something went wrong when adding the author to the database';
	}
 

	?>
<!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>Add Author</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="DataTables-1.10.18/js/jquery.dataTables.js"></script>

</head>

<body>



<header>
<h1>Adding a New Author to the Database </h1>
<h3><font color = red> Please Search Existing Concepts before Adding a New One  </font>  </h3>
<h3><font color =black> Please do not add a new references for different additions  </font>  </h3>
</header>
<!--<h3>Print the problem statement with "Ctrl P"</h3>
 <p><font color = 'blue' size='2'> Try "Ctrl +" and "Ctrl -" for resizing the display</font></p>  -->
<form  method="POST"  autocomplete = 'off' >
	
	<p><font color=#003399>1st Author: (in format - last name 1st initial. 2nd initial.)</font><input type="text" class = "text" name="author" id = "author" size= 40   > <input type = "button" name = "add synonym" value = "Add Author" id = "add_syn1"> </p>
<!-- 	<div class="input_fields_wrap">
    <button class="add_field_button">Add Synonyms</button>
    <div id = "blank"><input type="text" name="syn[]"></div>
	</div>
	 -->
	<p><font color=#003399><div id = "syn1">2nd Author: </font><input type="text" name="syn1"  size= 40   > <input type = "button" name = "add synonym" value = " Add Another Author" id = "add_syn2"> <input type = "button"  value = " Remove Author" id = "rem_syn2"> </div>
	<p><font color=#003399><div id = "syn2">3rd Author: </font><input type="text" name="syn2"  size= 40   > <input type = "button" name = "add synonym" value = " Add Another Author" id = "add_syn3"> <input type = "button"  value = " Remove Author" id = "rem_syn3"> </div> 
	<p><font color=#003399><div id = "syn3">4th Author: </font><input type="text" name="syn3"  size= 40   > <input type = "button" name = "add synonym" value = " Add Another Author" id = "add_syn4"> <input type = "button"  value = " Remove Author" id = "rem_syn4"> </div> 
	<p><font color=#003399><div id = "syn4">5th Author: </font><input type="text" name="syn4"  size= 40   > <input type = "button" name = "add synonym" value = " Add Another Author" id = "add_syn5"> <input type = "button"  value = " Remove Author" id = "rem_syn5"> </div> 
	<p><font color=#003399><div id = "syn5">6th Author: </font><input type="text" name="syn5" size= 40   > <input type = "button" name = "add synonym" value = " Add Another Author" id = "add_syn6"> <input type = "button"  value = " Remove Author" id = "rem_syn6"> </div> 
	<p><font color=#003399><div id = "syn6">7th Author: </font><input type="text" name="syn6"  size= 40   > <input type = "button" name = "add synonym" value = " Add Another Author" id = "add_syn7"> <input type = "button"  value = " Remove Author" id = "rem_syn7"> </div> 
	<p><font color=#003399><div id = "syn7">8th Author: </font><input type="text" name="syn7"  size= 40   > <input type = "button"  value = " Remove Author" id = "rem_syn8"> </div> 
<!--	<input type="hidden" name="submitted" value="name" /> -->
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "submit" name "submit"> 
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	
	<input type="submit" name="cancel" value="Cancel" />
	<p> <br> </p>
	<b>Recorded Authors: </b>
		<hr>	
		
	</form>
	
	
	<Script>
	$(document).ready( function () {
			
			// this will display the add synonym button when you start typing text in the title
			$('#add_syn1').hide();
			$("input[name='author']").keyup(function () {
				if ($(this).val()) {
					$("#add_syn1").show();
				}
				else {
					$("#add_syn1").hide();
				}
			});
			
			$('#add_syn2').hide();
			$("input[name='syn1']").keyup(function () {
				if ($(this).val()) {
					$("#add_syn2").show();
				}
				else {
					$("#add_syn2").hide();
				}
			});
			
			$('#add_syn3').hide();
			$("input[name='syn2']").keyup(function () {
				if ($(this).val()) {
					$("#add_syn3").show();
				}
				else {
					$("#add_syn2").hide();
				}
			});
			
			$('#add_syn4').hide();
			$("input[name='syn3']").keyup(function () {
				if ($(this).val()) {
					$("#add_syn4").show();
				}
				else {
					$("#add_syn3").hide();
				}
			});
			
			$('#add_syn5').hide();
			$("input[name='syn4']").keyup(function () {
				if ($(this).val()) {
					$("#add_syn5").show();
				}
				else {
					$("#add_syn4").hide();
				}
			});		
			
			$('#add_syn6').hide();
			$("input[name='syn5']").keyup(function () {
				if ($(this).val()) {
					$("#add_syn6").show();
				}
				else {
					$("#add_syn5").hide();
				}
			});		
			
			$('#add_syn7').hide();
			$("input[name='syn6']").keyup(function () {
				if ($(this).val()) {
					$("#add_syn7").show();
				}
				else {
					$("#add_syn6").hide();
				}
			});		
			
		
			
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
						$('#add_syn1').hide();
						$('input[name = "syn1"]').focus();
					});
					$('#add_syn2').click(function() {
						$('#syn2').show();
						$('#add_syn2').hide();
						$('#rem_syn2').hide();
						$('input[name = "syn2"]').focus();
					});
					$('#rem_syn2').click(function() {
						$('input[name="syn1"').val("");
						$('#syn1').hide();
						$('#add_syn1').show();
					});
					$('#add_syn3').click(function() {
						$('#syn3').show();
						$('#add_syn3').hide();
						$('#rem_syn3').hide();
						$('input[name = "syn3"]').focus();
					});
					$('#rem_syn3').click(function() {
						$('input[name="syn2"').val("");
						$('#syn2').hide();
						$('#add_syn2').show();
						$('#rem_syn2').show();
					});
					$('#add_syn4').click(function() {
						$('#syn4').show();
						$('#add_syn4').hide();
						$('#rem_syn4').hide();
						$('input[name = "syn4"]').focus();
					});
					$('#rem_syn4').click(function() {
						$('input[name="syn3"').val("");
						$('#syn3').hide();
						$('#add_syn3').show();
						$('#rem_syn3').show();
					});
					$('#add_syn5').click(function() {
						$('#syn5').show();
						$('#add_syn5').hide();
						$('#rem_syn5').hide();
						$('input[name = "syn5"]').focus();
					});
					$('#rem_syn5').click(function() {
						$('input[name="syn4"').val("");
						$('#syn4').hide();
						$('#add_syn4').show();
						$('#rem_syn4').show();
					});
					$('#add_syn6').click(function() {
						$('#syn6').show();
						$('#add_syn6').hide();
						$('#rem_syn6').hide();
						$('input[name = "syn6"]').focus();
					});
					$('#rem_syn6').click(function() {
						$('input[name="syn5"').val("");
						$('#syn5').hide();
						$('#add_syn5').show();
						$('#rem_syn5').show();
					});
					$('#add_syn7').click(function() {
						$('#syn7').show();
						$('#add_syn7').hide();
						$('#rem_syn7').hide();
						$('input[name = "syn7"]').focus();
					});
					$('#rem_syn7').click(function() {
						$('input[name="syn6"').val("");
						$('#syn6').hide();
						$('#add_syn6').show();
						$('#rem_syn6').show();						
					});
					$('#rem_syn8').click(function() {
						$('input[name="syn7"').val("");
						$('#syn7').hide();
						$('#add_syn7').show();
						$('#rem_syn7').show();						
					});
					
					
					 $('#table_format').DataTable({"sDom": 'W<"clear">lfrtip',
							"order": [[ 1, 'asc' ] ],
							 "lengthMenu": [ 50, 100, 200 ],
							"columnDefs" : [{"visible": false, "targets": [0]}],  // we could conditionally hide columns if all fo the entries were blank Just add , then number of column to hide
							
							// "oColumnFilterWidgets": {
							// "aiExclude" : [ 0 ] 
							
							
							// }
						});
					
	});
	
	</script>
	
	<?php
	
	 echo ('<table id="table_format" class = "a" border="1" >'."\n");
	

	
	 echo("<thead>");

	echo("</td><th>");
	echo('Num');
	echo("</th><th>");
	echo(' 1st Author');
   echo("</th><th>");
	echo('2nd Author');
	 echo("</th><th>");
	echo('3rd Author');
	 echo("</th><th>");
	echo('4th Author');
	 echo("</th><th>");
	echo('5th Author');
	 echo("</th><th>");
	echo('6th Author');
	 echo("</th><th>");
	echo('7th Author');
	 echo("</th><th>");
	
	echo('Function');
	echo("</th></tr>\n");
	 echo("</thead>");
	 
	  echo("<tbody>");
	 
	 $stmt = "SELECT * FROM `Author`";
	 $stmt2 = $pdo->query($stmt);
	 while ( $row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
			echo "<tr><td>";
			echo($row['author_id']);
			echo("</td><td>");	
			echo(htmlentities($row['author_name']));
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
			
			//	echo("</td><td>");	
	
			echo('<a href="addlistedauthor.php?author_id='.$row['author_id'].'"><b> Add  </b> </a>');

			echo("</td></tr>\n");
	}
	echo("</tbody>");
	echo("</table>");
	?>


</body>
</html>



