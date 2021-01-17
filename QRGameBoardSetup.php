<?php
require_once "pdo.php";
session_start();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Board Setup</title>

    <style>
    .container{
        padding: 20px;
    }
    .form_content{
        padding-left: 20px;

    }
    p { 
        margin-bottom: 5px;
    }
    h1 { 
        margin-bottom: 5px;
    }
     h2 { 
        margin-top: 5px;
    }
    
    </style>
</head>
<body>
<h1> Game Board SetUp</h1>
<div class="container">
    <h2> Create Actions (aka action cards): </h2>
    <form id="action_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class ="form_content">
            <p>Title of the Action Card <input type = "text" name = "game_action_title" id = "game_action_title"  minlength = "3" maxlength = "50" ></input></p>
            <p> <b> First Time </b>Financial Cost   <input type = "number"  id = "fin_onetime_cost" name = "fin_onetime_cost" min = "0" max = "99" ></input></p>
            <p> <b> Ongoing </b> Financial Cost <input type = "number"  id = "fin_ongoing_cost" name = "fin_ongoing_cost" value = "0" min = "0" max = "9" ></input></p>
            <p>Ongoing <b> Environmental </b> Benefit <input type = "number"  id = "env_ongoing_benefit" name = "env_ongoing_benefit" min = "0" max = "9" ></input></p>
            <p>Ongoing <b> Societal </b> Benefit <input type = "number" id = "soc_ongoing_benefit" name = "soc_ongoing_benefit" min = "0" max = "9" ></input></p>
            <p><label for="action_imgage_file">Select Action Card <b> Image </b>:</label>  <input type="file" id="action_imgage_file" name="action_imgage_file" accept="image/*"></p>
            <p><label for="action_html_file">Select Action Card html file (if any) :</label>  <input type="file" id="action_html_file" name="action_html_file" accept=".html,.htm"></p>
            <p><label for="action_video_file">Select Action Card video file (if any):</label>  <input type="file" id="action_video_file" name="action_video_file" accept=".mp4"></p>
            </div>
                    <input type="submit" name = "submit_action_form" id = "submit_action_form" value = "Submit">
    </form>

</div> 
<div class="container">
    <h2> Create Developments </h2>
    <form id="development_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class ="form_content">
            <p>Title of the Development Card <input type = "text" name = "game_development_title" id = "game_development_title"  minlength = "3" maxlength = "50" ></input></p>
            <p> Development Catagory   <select  id = "development_catagory" name = "development_catagory"  >
            <option value = "" selected disabled hidden >--Choose Catagory--</option>
            <option value = "political" >Political</option>
            <option value = "market" >Market</option>
            <option value = "local" >Local</option>
            <option value = "facility" > Facility </option>
            
            
            </select></p>
            <p> One Time Financial Change  <input type = "number"  id = "fin_onetime_change" name = "fin_onetime_change" value = "0" min = "-99" max = "99" ></input></p>
            <p> One Time Environmental Change  <input type = "number"  id = "env_onetime_change" name = "env_onetime_change" value = "0" min = "-99" max = "99" ></input></p>
            <p> One Time Societal Change  <input type = "number"  id = "soc_onetime_change" name = "soc_onetime_change" value = "0" min = "-99" max = "99" ></input></p>
            <p> Cash Flow Change <input type = "number"  id = "cash_flow_change" name = "cash_flow_change" value = "0" min = "-99" max = "99" ></input></p>
            <p> Production Change  <input type = "number"  id = "production_change" name = "production_change" value = "0" min = "-99" max = "99" ></input></p>
            <p> Margin Change  <input type = "number"  id = "margin_change" name = "margin_change" value = "0" min = "-99" max = "99" ></input></p>
            <p> Financial Weighting Factor Changes <input type = "number"  id = "fin_wt_change" name = "fin_wt_change" value = "0" min = "-99" max = "99" ></input></p>
            <p> Environmental Weighting Factor Changes <input type = "number"  id = "env_wt_change" name = "env_wt_change" value = "0" min = "-99" max = "99" ></input></p>
            <p> Societal Weighting Factor Changes <input type = "number"  id = "soc_wt_change" name = "soc_wt_change" value = "0" min = "-99" max = "99" ></input></p>
            <p><label for="development_imgage_file">Select Development <b> Image </b>:</label>  <input type="file" id="development_imgage_file" name="development_imgage_file" accept="image/*"></p>
            <p><label for="development_html_file">Select Development html file (if any) :</label>  <input type="file" id="development_html_file" name="developmentn_html_file" accept=".html,.htm"></p>
            <p><label for="development_video_file">Select Development video file (if any):</label>  <input type="file" id="development_video_file" name="development_video_file" accept=".mp4"></p>
            </div>
                    <input type="submit" name = "submit_action_form" id = "submit_action_form" value = "Submit">
    </form>

</div> 
<a href="QRPRepo.php">Finished / Cancel - go back to Repository</a>
<script>



</script>
</body>
</html>