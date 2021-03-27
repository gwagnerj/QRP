<?php
require_once "pdo.php";

    if (isset($_POST['eregistration_id'])  ){
       
            $sql = 'DELETE FROM Eregistration
                    WHERE  eregistration_id =:eregistration_id';

			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
             ":eregistration_id"   =>   $_POST['eregistration_id'], 
            ));
	}
 ?>





