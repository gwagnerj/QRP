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

</head>

<body>
<header>
<h1>Adding a Concept Title to the Database </h1>
</header>

<?php
	 $_SESSION['error'] = '';
	 $_SESSION['sucess'] = '';
	 
	
	
	if (isset($_POST['cancel'])) {
		
		//echo ('you cancelled');
		 header( 'Location: QRPRepo.php' ) ;
		 return; 
		
	}
	
	
	
	if(isset($_POST['concept']) && strlen($_POST['concept'])>0){
		
		
		try {
				$concept_name = htmlentities ($_POST['concept']);
				
				$sql = "INSERT INTO Concept (concept_name)
							VALUES (:concept_name)";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
					':concept_name' => $concept_name
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
	
	<p><font color=#003399>Title of Concept: </font><input type="text" name="concept" id = "concept" size= 40   ></p>
	
<!--	<input type="hidden" name="submitted" value="name" /> -->
			<input type = "submit" name "submit"> <br> <br> <br> <br>
	

	
	
	
	<input type="submit" name="cancel" value="Cancel" />
			
	</form>
	<p> <br> </p>
	
	
	<?php
	
	 echo ('<table id="table_format" class = "a" border="1" >'."\n");
	

	
	 echo("<thead>");

	echo("</td><th>");
	echo('Num');
	echo("</th><th>");
	echo('Concept');
   
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
			echo("</td></tr>\n");
	}
	
	echo("</tbody>");
	echo("</table>");
	
	
	?>


</body>
</html>



