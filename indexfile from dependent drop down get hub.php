<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<script src="../js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#authors").change(function(){
				var aid = $("#authors").val();
				$.ajax({
					url: 'data.php',
					method: 'post',
					data: 'aid=' + aid
				}).done(function(books){
					console.log(books);
					books = JSON.parse(books);
					$('#books').empty();
					books.forEach(function(book){
						$('#books').append('<option>' + book.name + '</option>')
					})
				})
			})
		})
	</script>
</head>
<body>
	<div class="container">
		<h1 class="text-center">Dependent Drop Down list In PHP/MySQL using jQuery & Ajax </h1>
		<hr>
		<div class="row">
		    <div class="col-md-6 col-md-offset-3">
				<form role="form" method="post" action="">
		        	<div class="row">
		                <div class="form-group">
		                    <label for="authors">Authors</label>
		                    <select class="form-control" id="authors" name="authors">
		                    	<option selected="" disabled="">Select Author</option>
		                    	<?php 
		                    		require 'data.php';
		                    		$authors = loadAuthors();
		                    		foreach ($authors as $author) {
		                    			echo "<option id='".$author['id']."' value='".$author['id']."'>".$author['name']."</option>";
		                    		}
		                    	 ?>
		                    </select>
		                </div>
		            </div>
		            <div class="row">
		                <div class="form-group">
		                    <label for="books">Books</label>
		                    <select class="form-control" id="books" name="books">
		                    	
		                    </select>
		                </div>
		            </div>
		        </form>
		    </div>
		</div>
	</div>
</body>
</html>



<!-- data.php file -->

<?php 
	require 'DbConnect.php';
	if(isset($_POST['aid'])) {
		$db = new DbConnect;
		$conn = $db->connect();
		$stmt = $conn->prepare("SELECT * FROM books WHERE author_id = " . $_POST['aid']);
		$stmt->execute();
		$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($books);
	}
	function loadAuthors() {
		$db = new DbConnect;
		$conn = $db->connect();
		$stmt = $conn->prepare("SELECT * FROM authors");
		$stmt->execute();
		$authors = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $authors;
	}
 ?>



<!-- php pdo connection.php file -->

<?php 
	class DbConnect {
		private $host = 'localhost';
		private $dbName = 'readers';
		private $user = 'root';
		private $pass = '';
		public function connect() {
			try {
				$conn = new PDO('mysql:host=' . $this->host . '; dbname=' . $this->dbName, $this->user, $this->pass);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;
			} catch( PDOException $e) {
				echo 'Database Error: ' . $e->getMessage();
			}
		}
	}
 ?>