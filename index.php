<?php
require_once 'pdo.php';
session_start();

// initialize some values
$game_code_err = $first_name_err = $last_name_err = $game_name_err = $vers_code_err = $team_name_err = $radio_select_err = $globephase = $eexamtime_id = $updated_at = $team_num = $team_num_err =
    '';
$game_code = $first_name = $last_name = $game_name = $vers_code = $qrcode = $team_name =
    '';
$team_cap = 0;
$game_points = '0';
$checker_only = '1';
$checker = 'not set';
$dex_print = '140'; //default value for dex_print
unset($_SESSION['error']);
$_SESSION['error'] = '';

// Processing form data when form is submitted

if (isset($_SESSION['uniq_username'])) {
    // already been through and input the data and now we are updateing it with a back button or reloaded the page
    $uniq_username = $_SESSION['uniq_username'];
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $game_name = $_SESSION['game_name'];
    // $game_code =$_SESSION['eexamnow_id'];
    $student_id = $_SESSION['student_id'];
    $currentclass_id = $_SESSION['currentclass_id'];
    $game_points = $_SESSION['game_points'];
    $team_cap = $_SESSION['team_cap'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if game_code is empty
    if (empty(trim($_POST['game_code']))) {
        $game_code_err = 'Please enter game code.';
    } else {
        $game_code = trim($_POST['game_code']);
    }
    // Check if game_points is game_points
    if (empty(trim($_POST['game_points']))) {
        // $game_point_err = 'Please enter game points.';
    } else {
        $game_points = trim($_POST['game_points']);
    }
    // Check if team_num is empty
    if (empty(trim($_POST['team_num']))) {
        $team_num_err = 'Please enter your team number.';
    } else {
        $team_num = trim($_POST['team_num']);
    }
    // Check if team_cap is empty
    if (empty(trim($_POST['team_cap']))) {
        $team_cap_err = 'Select team captain status.';
    } else {
        $team_cap = trim($_POST['team_cap']);
        if ($team_cap == 'yes') {
            $team_cap = 1;
        } else {
            $team_cap = 0;
        }
        // var_dump($team_cap);
    }

    // check if they are a team captain that they input the team names
    if (trim($_POST['team_cap']) == 'yes' && empty(trim($_POST['team_name']))) {
        $team_name_err = 'Please discuss with your team and select a team name';
    } else {
        $team_name = trim($_POST['team_name']);
    }

    // Check if first_name is empty
    if (empty(trim($_POST['first_name']))) {
        $first_name_err = 'Please enter first name.';
    } else {
        $first_name = trim($_POST['first_name']);
    }
    // Check if last_name is empty
    if (empty(trim($_POST['last_name']))) {
        $last_name_err = 'Please enter last name.';
    } else {
        $last_name = trim($_POST['last_name']);
    }
    // Check if game_name is empty
    if (empty(trim($_POST['game_name']))) {
        $game_name_err = 'Please enter game name.';
    } else {
        $game_name = trim($_POST['game_name']);
    }

    // Check if radio button is empty
    if (empty(trim($_POST['qrcode']))) {
        $radio_select_err = 'Please select an option.';
    } elseif (
        trim($_POST['qrcode']) == 'yes' &&
        empty(trim($_POST['vers_code']))
    ) {
        $vers_code_err = 'Please put in the version code.';
        // $qrcode = trim($_POST["qrcode"]);
    } elseif (trim($_POST['qrcode']) == 'yes') {
        $checker_only = 0;
        $vers_code = trim($_POST['vers_code']);
        // echo ('vers_code is '.$vers_code);

        if ($vers_code < 10000 || $vers_code >= 100000) {
            // should be a 5 digit number if it is not it is an error
            $vers_code_err = 'version code number is in error.';
        } else {
            $dex_code_string = (string) $vers_code;
            $key = $dex_code_string[0];
            $mid_three =
                $dex_code_string[1] .
                $dex_code_string[2] .
                $dex_code_string[3] +
                0;
            $last_dig = $dex_code_string[4];

            if ($mid_three < 300) {
                // dex is over a three digit number
                $dex_print = $mid_three - $key - $last_dig;
            } elseif ($mid_three < 600) {
                // dex is a one digit number
                $dex_print = $mid_three - 300 - $last_dig;
            } else {
                // dex is a two digit number
                $dex_print = $mid_three - 600 - $last_dig;
            }
            if ($dex_print >= 201 || $dex_print < 0) {
                $_SESSION['error'] =
                    '<h3 style="color:red;"> &nbsp; Version Code is in error.  Please re-input this number </h3>';
                $vers_code_err = 'version code number is in error.';
            }
        }
    }
    $qrcode = trim($_POST['qrcode']);

    // echo ('qrcode is '.$qrcode);

    // Validate credentials
    if (
        empty($game_name_err) &&
        empty($last_name_err) &&
        empty($first_name_err) &&
        empty($game_code_err) &&
        empty($vers_code_err) &&
        empty($team_num_err)
    ) {
        // now put the studetn in the student table and have them sign on to the currentclass then go to the QRExamRegistration 2
        // get the eexamtime_id and
        // check to make sure a game is Running

        // echo ' game_code '.$game_code;

        $sql = 'SELECT * FROM Eexamnow WHERE eexamnow_id =:eexamnow_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':eexamnow_id' => $game_code,
        ]);
        $eexamnow_data = $stmt->fetch(); // should do error checking to see if it is not false

        if ($eexamnow_data == false) {
            $_SESSION['error'] =
                ' game with that game code has not been initialized';
            // header('Location: '.$_SERVER['PHP_SELF']);
            // die();
        } else {
            $globephase = $eexamnow_data['globephase'];
            $eexamtime_id = $eexamnow_data['eexamtime_id'];

        
   //? check to see if the team number is valid for the number of teams that the gamemaster set up
                $sql = 'SELECT `number_teams` FROM Eexamtime WHERE eexamtime_id = :eexamtime_id';
                $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'eexamtime_id' => $eexamtime_id,
                    ]);
                    $number_allowed_teams = $stmt->fetch();
                    if ($number_allowed_teams['number_teams'] < $team_num){
                        $_SESSION['error'] =  ' The team number is higher than that set up by the game master.  Please confirm you team number';
                        $team_num_err = 'The team number is higher than that set up by the game master.  Please confirm you team number';          
                        
                        // header('Location: '.$_SERVER['PHP_SELF']);
                        //              die();

                    }
         }

            $updated_at = $eexamnow_data['updated_at'];
        
        if ($globephase > 1) {
            $_SESSION['error'] =
                $_SESSION['error'] .
                '<br>  game'.$game_code.' is no longer active ';
            // header('Location: '.$_SERVER['PHP_SELF']);
            // die();
        }
        $updated_at = strtotime($updated_at);
        $now = strtotime(date('Y-m-d H:i:s'));
        $diff_time = $now - $updated_at;
        $hrs_diff = $diff_time / 3600;
        if ($hrs_diff > 8) {
            // if the game was last updated over 8 hrs ago it is not a current game iven if the globephase is a 0 or 1
            $_SESSION['error'] =
                $_SESSION['error'] . '<br>  game is not current';
        }

        //    var_dump($updated_at);
        //    var_dump($now);
        //    var_dump($hrs_diff);
        // die;

        $sql =
            'SELECT currentclass_id FROM Eexamtime WHERE eexamtime_id =:eexamtime_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':eexamtime_id' => $eexamtime_id,
        ]);
        $eexamtime_data = $stmt->fetch(); // should do error checking to see if it is not false
        if ($eexamtime_data === false) {
            $_SESSION['error'] =
                $_SESSION['error'] .
                '<br> eexamtime_id not found - something went wrong';
            // header('Location: '.$_SERVER['PHP_SELF']);
            // die();
        } else {
            $currentclass_id = $eexamtime_data['currentclass_id'];
        }

        if (empty($_SESSION['error'])) {
            if (!isset($_SESSION['uniq_username'])) {
                // Fisrt time to input the values

                $uniq_username =
                    'temp_' .
                    $game_code .
                    '_' .
                    $currentclass_id .
                    '_' .
                    uniqid(); // uniqid generates a 13 char random character string
                $_SESSION['uniq_username'] = $uniq_username;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;
                $_SESSION['game_name'] = $game_name;
                $_SESSION['team_cap'] = $team_cap;

                $sql = "INSERT INTO `Student` 
                (  `first_name` ,  `last_name`, `game_name`,username) 
                VALUES  ( :first_name, :last_name,   :game_name, :username)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':first_name' => $first_name,
                    ':last_name' => $last_name,
                    ':game_name' => $game_name,
                    ':username' => $uniq_username,
                ]);

                $student_id = $pdo->lastInsertId();
                $_SESSION['student_id'] = $student_id;
                $_SESSION['currentclass_id'] = $currentclass_id;
                $_SESSION['game_points'] = $game_points;
                $_SESSION['eexamnow_id'] = $game_code;

                $sql = "INSERT INTO `StudentCurrentClassConnect` 
                ( `student_id`,  `currentclass_id`,`pin`) 
                VALUES  ( :student_id, :currentclass_id, :pin)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':student_id' => $student_id,
                    ':currentclass_id' => $currentclass_id,
                    ':pin' => $dex_print - 1,
                ]);

                $sql = "INSERT INTO `Eregistration` 
                ( `student_id`,  `dex` ,  `eexamnow_id`, `exam_code`,checker_only,kahoot_points) 
                VALUES  ( :student_id, :dex,   :eexamnow_id, :exam_code, :checker_only,:kahoot_points)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':student_id' => $student_id,
                    ':dex' => $dex_print,
                    ':eexamnow_id' => $game_code,
                    ':exam_code' => '1000',
                    ':kahoot_points' => $game_points,
                    ':checker_only' => $checker_only,
                ]);

                $eregistration_id = $pdo->lastInsertId();
                $_SESSION['eregistration_id'] = $eregistration_id;

                // now put them in a team table first see if the team has already been created
                $sql = "SELECT team_id FROM Team
                WHERE currentclass_id = :currentclass_id AND eexamnow_id = :eexamnow_id AND team_num = :team_num";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':currentclass_id' => $currentclass_id,
                    ':eexamnow_id' => $game_code,
                    ':team_num' => $team_num,
                ]);
                $teams_id = $stmt->fetch();

                // var_dump($teams_id);
                // echo ' currentclass_Id '.$currentclass_id;
                // echo ' eexamnow_Id '.$game_code;
                // echo ' team_num '.$team_num;

                if (!$teams_id) {
                    // we do not have a team we need to create ones
                    $sql = "INSERT INTO Team (team_num, currentclass_id, eexamnow_id)
                     VALUES (:team_num, :currentclass_id, :eexamnow_id)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':currentclass_id' => $currentclass_id,
                        ':eexamnow_id' => $game_code,
                        ':team_num' => $team_num,
                    ]);

                    $team_id = $pdo->lastInsertId();
                } else {
                    $team_id = $teams_id['team_id'];
                }

                // now insert the studetn into the TeamStudentConnect Table and

                $sql = "INSERT INTO TeamStudentConnect (student_id, eexamnow_id, team_id, team_num, dex, team_cap)
                        VALUES (:student_id, :eexamnow_id, :team_id, :team_num, :dex, :team_cap)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':student_id' => $student_id,
                    ':eexamnow_id' => $game_code,
                    ':team_id' => $team_id,
                    ':team_num' => $team_num,
                    ':dex' => $dex_print,
                    ':team_cap' => $team_cap,
                ]);

                // if the student is the captain then inset the team naem into the Team tables
                if (strlen($team_name > 0)) {
                    $sql =
                        'UPDATE Team SET team_name = :team_name WHERE team_id = :team_id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':team_id' => $team_id,
                        ':team_name' => $team_name,
                    ]);
                }
                

                $_SESSION['success'] = ' Registered for the exam';
                header(
                    'Location: stu_exam_frontpage.php?eregistration_id=' .
                        $eregistration_id .
                        '&checker=' .
                        $checker
                );
                die();
            } else {
                // we already have an id and need to update instead of insert since we have multiple tables I decided to go this way instead of the on duplicate key update command in My SQL

                $uniq_username = $_SESSION['uniq_username'];
                $eregistration_id = $_SESSION['eregistration_id'];

                $sql = "UPDATE `Student`
                     SET first_name = :first_name, last_name = :last_name, game_name = :game_name
                    WHERE username = :username";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':first_name' => $first_name,
                    ':last_name' => $last_name,
                    ':game_name' => $game_name,
                    ':username' => $uniq_username,
                ]);

                $sql = "UPDATE StudentCurrentClassConnect
                        SET pin = :pin
                        WHERE student_id = :student_id AND currentclass_id = :currentclass_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':student_id' => $student_id,
                    ':currentclass_id' => $currentclass_id,
                    ':pin' => $dex_print - 1,
                ]);

                $sql = "UPDATE Eregistration
                        SET  dex = :dex, eexamnow_id = :eexamnow_id, checker_only = :checker_only, kahoot_points = :kahoot_points
                        WHERE student_id = :student_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':student_id' => $student_id,
                    ':dex' => $dex_print,
                    ':eexamnow_id' => $game_code,
                    ':kahoot_points' => $game_points,
                    ':checker_only' => $checker_only,
                ]);

                $sql = "UPDATE TeamStudentConnect
                            SET team_num = :team_num, team_cap = :team_cap, dex = :dex
                            WHERE student_id = :student_id AND eexamnow_id = :eexamnow_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':student_id' => $student_id,
                    ':eexamnow_id' => $game_code,
                    ':team_num' => $team_num,
                    ':dex' => $dex_print,
                    ':team_cap' => $team_cap,
                ]);

                $sql =
                    'SELECT team_id FROM TeamStudentConnect Where student_id = :student_id AND eexamnow_id = :eexamnow_id';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':student_id' => $student_id,
                    ':eexamnow_id' => $game_code,
                ]);

                $teams_id = $stmt->fetch();
                $team_id = $teams_id['team_id'];

                // if the student is the captain then inset the team naem into the Team tables

                if (strlen($team_name > 0)) {
                    $sql =
                        'UPDATE Team SET team_name = :team_name WHERE team_id = :team_id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':team_id' => $team_id,
                        ':team_name' => $team_name,
                    ]);
                }

                $_SESSION['success'] = ' Registered for the exam';
                header(
                    'Location: stu_exam_frontpage.php?eregistration_id=' .
                        $eregistration_id .
                        '&checker=' .
                        $checker
                );
                die();
            }
        } else {
            $game_code = '';
            $game_code_err = ' game that was input is not valid running game';
        }
    }

    // Close connection
    unset($pdo);
}

if (isset($_SESSION['success'])) {
    echo $_SESSION['success'];
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

<link rel="icon" type="image/png" href="McKetta.png" />  
    <meta charset="UTF-8">
<title>QRP Student Game Login</title>
 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
        .check{ 
        margin-left:10px;
        }

    </style>
</head>
<body>
    <div class="wrapper">
	
        <h2>Welcome to Quick Response Game </h2>
       
		
        <form action="<?php echo htmlspecialchars(
            $_SERVER['PHP_SELF']
        ); ?>" method="POST">
            
        <div class="form-group <?php echo !empty($game_code_err)
            ? 'has-error'
            : ''; ?>">
                <label>Game Code</label>
                <input type="text" name="game_code"class="form-control" value="<?php echo $game_code; ?>"></input>
                <span class="help-block"><?php echo $game_code_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Game Points</label>
                <input type="text" name="game_points" class="form-control" value="<?php echo $game_points; ?>"></input>
               
            </div>    
            <div class="form-group <?php echo !empty($team_num_err)
                ? 'has-error'
                : ''; ?>">
                <label>Team Number </label>


                <input type="number" min="1" max="99" name="team_num" class="form-control" value="<?php echo $team_num; ?>">
                <span class="help-block"><?php echo $team_num_err; ?></span>
            </div>   

            <label>Team Captain</label>
            <div class="form-check check">

                <label>  <input type="radio" name="team_cap" class="form-control" id = "team_cap_no" value="no" required <?php if (
                    $team_cap == '0' ||
                    $team_cap == ''
                ) {
                    echo 'checked';
                } ?> > No </input></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label>  <input type="radio" name="team_cap" class="form-control" id = "team_cap_yes" value="yes" required <?php if (
                    $team_cap == '1'
                ) {
                    echo 'checked';
                } ?> > Yes </input></label>
                 <div id = "team_name_input" > <br>
                   <label> Team Name: </label>
                    <input type ="text" id = "team_name" name = "team_name" value =  "<?php echo $team_name; ?>" > </input> </h4>
                    <span id = "team_name_err_message" class="help-block"><?php echo $team_name_err; ?></span>
                </div>
                <br>
            </div>    

            <div class="form-group <?php echo !empty($first_name_err)
                ? 'has-error'
                : ''; ?>">
                <label>First (given) name </label>
                <input type="text" name="first_name"class="form-control" value="<?php echo $first_name; ?>">
                <span class="help-block"><?php echo $first_name_err; ?></span>
            </div>    
            <div class="form-group <?php echo !empty($last_name_err)
                ? 'has-error'
                : ''; ?>">
                <label>Last (family) Name </label>
                <input type="text" name="last_name"class="form-control" value="<?php echo $last_name; ?>">
                <span class="help-block"><?php echo $last_name_err; ?></span>
            </div>    
            <div class="form-group <?php echo !empty($game_name_err)
                ? 'has-error'
                : ''; ?>">
                <label>Alias (game name) </label>
                <input type="text" name="game_name"class="form-control" value="<?php echo $game_name; ?>">
                <span class="help-block"><?php echo $game_name_err; ?></span>
            </div>    
            <div class="form-group <?php echo !empty($vers_code_err)
                ? 'has-error'
                : ''; ?>">
            <label><h4>Do you have a printed form of the game problem?</h4></label><br>
            <h4> <input  type="radio" id = "yes" name="qrcode" value = "yes" <?php if (
                $qrcode == 'yes'
            ) {
                echo 'checked';
            } ?> required> <label for="yes"> Yes </Label></input> <span id = "dex_code_input" >
             Version Code:
             <input type ="number" id = "vers_code" name = "vers_code" min = "10000" max = "99999" value =  <?php echo $vers_code; ?> > </input> </h4>
            
             <span id = "vers_code_err_message" class="help-block"><?php echo $vers_code_err; ?></span>

            <h4> <input type="radio" id = "no" name="qrcode" value = "no" <?php if (
                $qrcode == 'no'
            ) {
                echo 'checked';
            } ?> > <label for="no"> No </Label></input> </h4>

            </div>    
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
		</form>
		
		  
       
    </div>

    <script>

        document.getElementById('dex_code_input').style.visibility='hidden';
        
        document.getElementById('team_name_input').style.display='none';

      team_cap = document.getElementById('team_cap_yes');
      not_team_cap = document.getElementById('team_cap_no')
      team_cap_no = document.getElementById('team_cap_no').checked;
      team_cap_yes = document.getElementById('team_cap_yes').checked;
      if (team_cap_yes == true) {teamNameInput()}

      team_cap.addEventListener('click', teamNameInput);

      not_team_cap.addEventListener('click', function () { document.getElementById('team_name_input').style.display='none';} );

      function teamNameInput(){
            document.getElementById('team_name_input').style.display = "block";
        }




        const is_yes = document.getElementById('yes').checked;
        // console.log(`is yes is ${is_yes}`);
        if (is_yes ==true){
            document.getElementById('dex_code_input').style.visibility = "visible";
        }
       


   

     if (document.querySelector('input[name="qrcode"]')) {
        document.querySelectorAll('input[name="qrcode"]').forEach((elem) => {
            elem.addEventListener("change", function(event) {
            let item = event.target.value;
                if(item == 'yes'){
                    document.getElementById('dex_code_input').style.visibility = "visible";
                } else {
                    document.getElementById('dex_code_input').style.visibility='hidden';  
                    document.getElementById('vers_code_err_message').style.display='none';  
                }
            });
         });
    }

</script>
</body>
</html>


