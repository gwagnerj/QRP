<?php
require_once "pdo.php";
session_start();
$problem_id= '';

if(isset($_POST['problem_id'])){
	$problem_id = htmlentities($_POST['problem_id']);
	$_SESSION['problem_id']=$problem_id;
} else {

	$_SESSION['error'] = 'problem id was not set';
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
	
}

if(isset($_POST['iid'])){
	$iid = htmlentities($_POST['iid']);
	$_SESSION['iid']=$iid;
} else {

	$_SESSION['error'] = 'user_id iid was not set';
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
}


 $sql = "SELECT * FROM Problem WHERE problem_id = :problem_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':problem_id' => $problem_id));
	$pblm_data = $stmt -> fetch();
      
    $stmt = $pdo->prepare("SELECT * FROM Input where problem_id = :problem_id AND dex = :dex");
	$stmt->execute(array(":problem_id" => $_POST['problem_id'], ":dex" => 1));
	$row = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT * FROM Qa where problem_id = :problem_id AND dex = :dex");
	$stmt->execute(array(":problem_id" => $_POST['problem_id'], ":dex" => 1));
	$row_ans = $stmt->fetch();


   
  // set the answer and margin of error into a ans_pattern
   $last_part = 9;
   $i = 9;
   $num_parts = 0;
   foreach (range('j','a' )as $m){
        $ans[$i]= $row_ans['ans_'.$m];
        // echo 'ans_i'. $row_ans['ans_'.$m];
        // echo'<br>';
       if($row_ans['ans_'.$m]<1.2e43){
           $num_parts ++;
           $part_flag[$i] = 1;
        } else {
            $part_flag[$i] = 0; 
            $last_part = $i; 
        }
     $i--;
    }
    // echo ("last_part ".$last_part);
    // echo'<br>';
    // echo 'num_parts '.$num_parts;

    if ($num_parts == 0){$num_parts =1;}

           
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Homework Moodle XML Input</title>



        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

</head>
<body>
<header>
<h1 class = "fs-1 text-primary ms-4 mt-4">QR Homework Moodle XML Input </h1>
</header>



<div class = "form-container ms-5 bs-3">

<form method = "POST" action="makeMoodleXML.php">
<label class = "fs-3 my-4" for = "num_copies"> Number of different versions</label>
<input type="number" id = "num_copies" name = "num_copies"  value= "100" min = "1" max = "119" ></input>

    <div class = "fs-2"> Weight for Each Part </div>
<div class = "percentage-container ms-4">

<input type="hidden" name = "problem_id"  value= "<?php echo ($problem_id);?>"></input>
<input type="hidden" name = "iid"  value= "<?php echo ($iid);?>"></input>
<?php

$m = 'a';
$sum_value =0;
for ( $i = 0; $i <= $last_part;$i++){
           if($part_flag[$i] == 1 && $i != $last_part){
               $value = round(100/$num_parts);
            //    echo 'value '.$value;
               echo '<br>';
               $sum_value += $value;
               echo '<span class = "fs-4 my-1 by-2 text-primary"> '.$m.': <input class = "f" id = "percentage-part_'.$m.'" name = "percentage-part_'.$m.'" type ="number" min ="0" max = "100" value = "'.$value.'"></input></span>';
               echo '<br>';
           }
            elseif($part_flag[$i] == 1 && $i == $last_part)
            {
                $value = 100-$sum_value;
                echo 'Percentage for part '.$m.': <input class = "f" id = "percentage-part_'.$m.'" name = "percentage-part_'.$m.'" type ="number" min ="0" max = "100" value = "'.$value.'"></input>';
                echo '<br>';
           }
           $m++;
        }
        echo '<br>';
        echo '<br>';


?>
</div>
<div class="fs-3 mb-3"> Hints for the Student </div>
<div class="hint-container ms-4">
    <div class="form-check fs-4">
        <select class="" name="hint_1">

            <option value=""> </option>
            <option value="1" selected>1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
        </select>
        <label class="form-check-label" for="hint-1">

            Check your Units
        </label>
        <input type="hidden" name="hint-text_1" value="Check your Units"></input>
    </div>


    <div class="form-check fs-4">
        <select class="" name="hint_2">

            <option value=""> </option>
            <option value="1">1</option>
            <option value="2" selected>2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
        </select>
        <label class="form-check-label" for="hint-2">
            Reread the Problem Statement
        </label>
        <input type="hidden" name="hint-text_2" value="Reread the Problem Statement"></input>
    </div>

    <div class="form-check fs-4">
        <select class="" name="hint_3">

            <option value=""> </option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3" selected>3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
        </select>
        <label class="form-check-label" for="hint-3">
            Draw and Label a Drawing
        </label>
        <input type="hidden" name="hint-text_3" value=" Draw and Label a Drawing"></input>
    </div>


    <div class="form-check fs-4">
        <select class="" name="hint_4">

            <option value="" selected> </option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3" >3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
        
            </select>
        <input type="text" size = "90" name="hint-text_4" value=""></input>
    </div>


    <div class="form-check fs-4">
        <select class="" name="hint_5">

            <option value="" selected> </option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3" >3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
        
            </select>
        <input type="text" size = "90" name="hint-text_5" value=""></input>
    </div>

    <div class="form-check fs-4">
        <select class="" name="hint_6">

            <option value="" selected> </option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3" >3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
        
            </select>
        <input type="text" size = "90" name="hint-text_6" value=""></input>
    </div>

    <div class="form-check fs-4">
        <select class="" name="hint_7">

            <option value="" selected> </option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3" >3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
        
            </select>
        <input type="text" size = "90" name="hint-text_7" value=""></input>
    </div>


</div>


        <button type="submit" class="btn btn-primary  fs-1 my-4 b-4">Submit</button>

</form>
    </div>
</body>
</html>
