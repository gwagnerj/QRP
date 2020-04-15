<?php
require_once "pdo.php";
session_start();
	$country = '';
    $region = '';
    $city = '';
    
    if (isset($_POST['country']) && isset($_POST['examactivity_id']) ){
		
        if(isset($_POST['city'])){$city = $_POST['city'];}
          if(isset($_POST['region'])){$region = $_POST['region'];}


        $sql = "UPDATE Examactivity
			SET country = :country, city = :city, region = :region
			WHERE examactivity_id = :examactivity_id"; 
			$stmt = $pdo->prepare($sql);	
			$stmt->execute(array(
             ":country"   =>   $_POST['country'], 
              ":city"   =>   $city, 
              ":region"   =>   $region, 
               ":examactivity_id"   =>   $_POST['examactivity_id'], 
            ));
	}
 ?>





