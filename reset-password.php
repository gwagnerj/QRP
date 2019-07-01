<?php
require_once 'pdo.php';

// this all comes from a tutorial by mmtuts at https://www.youtube.com/watch?v=wUkKCMEYj9M
	if(isset($_POST['reset-password-submit'])) {
			$selector = $_POST['selector'];
			$validator = $_POST['validator'];
			$password = $_POST['pswd'];
			$passwordRepeat = $_POST['pswd-repeat'];
			if (empty($password)||empty($password-repeat)){
					header('location: create-new-password.php?selector=".$selector."&validator=".bin2hex($validator)."&newpswd = empty"')
					exit();
			} elseif ($password!=$passwordRepeat) {
					header('location: create-new-password.php?selector=".$selector."&validator=".bin2hex($validator)."&newpswd = pswdnotsame"')
					exit();
			}
			$currentDate = date("U");
			$sql = 'SELECT * FROM Pswdreset WHERE selector = :selector AND token_exp < $currentDate';
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
			':selector' => $selector
			));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$tokenEmail = $row['email'];  // the email from the password reset table
			$tokenBin = hex2bin($validator);
			$tokenCheck = password_verify($tokenBin,$row['token']);
			if($tokenCheck == false) {
				echo 'token did not match';
				die();
			} elseif($tokenCheck==true) {
			// change the password	
				
			$sql = 'SELECT * FROM `Users` WHERE email = ":email"';
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
			':email' => $tokenEmail
			));
			
			$row2 = $stmt->fetch(PDO::FETCH_ASSOC);
			$usersEmail=$row2['email'];
				if($usersEmail != $tokenEmail){
					echo 'email does not match what is in system';
					die();
				} else {
				// update the Users table	

				$newpswdhash = password_hash($password,PASSWORD_DEFAULT);
					$sql = 'UPDATE Users SET password=:password WHERE email = :email';
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
					':password' => $newpswdhash,
					':email' => $tokenEmail
					));
					
					// delete the token out of the Pswdreset table
					$sql = 'DELETE FROM Pswdreset WHERE email = :email';
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
					':email' => $tokenEmail
					));
					header("Location: login.php?newpswd=pswdupdated");
				}
				
			}
			
			

				
			}
	
	} else {
		header('location: login.php')	
	}

<?