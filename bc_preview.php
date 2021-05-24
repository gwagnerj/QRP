<?php
session_start();
require_once 'pdo.php';
require_once 'simple_html_dom.php';
require_once '..\encryption_base.php';

if (isset($_POST['problem_id'])) {
    $problem_id = $_POST['problem_id'];
} elseif (isset($_GET['problem_id'])) {
    $problem_id = $_GET['problem_id'];
} elseif ($_SESSION['problem_id']) {
    $problem_id = $_SESSION['problem_id'];
} else {
    $_SESSION['error'] = 'no problem id in bc_preview';
}

$sql = 'SELECT * FROM Problem WHERE problem_id = :problem_id';
$stmt = $pdo->prepare($sql);
$stmt->execute([':problem_id' => $problem_id]);
$pblm_data = $stmt->fetch();
$contrib_id = $pblm_data['users_id'];
$nm_author = $pblm_data['nm_author'];
$specif_ref = $pblm_data['specif_ref'];
$htmlfilenm = $pblm_data['htmlfilenm'];

$solnfilenm = $pblm_data['soln_pblm'];
$solnfilenm = 'uploads/' . $solnfilenm;

$htmlfilenm = 'uploads/' . $htmlfilenm;

//             echo ('<h2> htmlfilenm: '.$htmlfilenm.'</h2>');

// read in the names of the variables for the problem
$nv = 0; // number of non-null variables
for ($i = 0; $i <= 13; $i++) {
    if ($pblm_data['nv_' . ($i + 1)] != 'Null') {
        $nvar[$i] = $pblm_data['nv_' . ($i + 1)];
        $nv++;
    }
}

// read in the input varaibles for the basecase
$stmt = $pdo->prepare(
    'SELECT * FROM Input where problem_id = :problem_id AND dex = :dex'
);
$stmt->execute([':problem_id' => $problem_id, ':dex' => 1]);
$BC_row = $stmt->fetch();

// Read in the value for the input variables

for ($i = 0; $i <= $nv; $i++) {
    if ($BC_row['v_' . ($i + 1)] != 'Null') {
        $vari[$i] = $BC_row['v_' . ($i + 1)];
        $BC_vari[$i] = $BC_row['v_' . ($i + 1)];
        $pattern[$i] = '/##' . $nvar[$i] . ',.+?##/';
    }
}
?>

<!DOCTYPE html >
<html lang = "en">
<head>
<meta charset="UTF-8">

<link rel="icon" type="image/png" href="McKetta.png" >
<!--
<title>QRHomework</title> 
<meta name="viewport" content="width=device-width, initial-scale=1" /> 

-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<script src="./crypto-js-4.0.0/crypto-js.js"></script><!-- https://github.com/brix/crypto-js/releases crypto-js.js can be download from here -->
<script src="Encryption.js"></script>
<script type="text/javascript" charset="utf-8" src="qrcode.js"></script>
<title><?php echo 'bc_' . $problem_id; ?></title>
</head>

<body>
<?php
//  I'm using reading from the $html and buiding the file $this_html.  I had to build it in two parts because of putting the
//i-frame for the checker in the middle of the document

$html = new simple_html_dom();
$html->load_file($htmlfilenm);
//            echo ('<h2> htmlfilenm: '.$htmlfilenm.'</h2>');

$base_case = $html->find('#problem', 0);
$reflection_text = $html->find('#reflections', 0);
// substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced

for ($i = 0; $i < $nv; $i++) {
    if ($BC_row['v_' . ($i + 1)] != 'Null') {
        $base_case = preg_replace($pattern[$i], $BC_vari[$i], $base_case);
        $reflection_text = preg_replace(
            $pattern[$i],
            $BC_vari[$i],
            $reflection_text
        );
    }
}

// add some markup to specific to the basecase since I just created it from the problem
$base_case = preg_replace(
    '/<div id="problem">/',
    '<div id="BC_problem">',
    $base_case
);
$base_case = preg_replace(
    '/<div id="questions">/',
    '<div id="BC_questions">',
    $base_case
);

foreach (range('a', 'j') as $m) {
    $let_pattern = 'part' . $m;
    $base_case = preg_replace(
        '/<div id="' . $let_pattern . '">/',
        '<div id="BC_' . $let_pattern . '">',
        $base_case
    );
}

// substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
for ($i = 0; $i < $nv; $i++) {
    if ($BC_row['v_' . ($i + 1)] != 'Null') {
        $base_case = preg_replace($pattern[$i], $vari[$i], $base_case);
    }
}

$dom = new DOMDocument();
libxml_use_internal_errors(true); // this gets rid of the warning that the p tag isn't closed explicitly
$dom->loadHTML('<?xml encoding="utf-8" ?>' . $base_case);
$images = $dom->getElementsByTagName('img');
foreach ($images as $image) {
    $src = $image->getAttribute('src');
    $src = 'uploads/' . $src;
    $src = urldecode($src);
    $type = pathinfo($src, PATHINFO_EXTENSION);
    $base64 =
        'data:image/' .
        $type .
        ';base64,' .
        base64_encode(file_get_contents($src));
    $image->setAttribute('src', $base64);
    $base_case = $dom->saveHTML();
}

$dom = new DOMDocument();
libxml_use_internal_errors(true); // this gets rid of the warning that the p tag isn't closed explicitly
$dom->loadHTML('<?xml encoding="utf-8" ?>' . $reflection_text);
$images = $dom->getElementsByTagName('img');
foreach ($images as $image) {
    $src = $image->getAttribute('src');
    $src = 'uploads/' . $src;
    $src = urldecode($src);
    $type = pathinfo($src, PATHINFO_EXTENSION);
    $base64 =
        'data:image/' .
        $type .
        ';base64,' .
        base64_encode(file_get_contents($src));
    $image->setAttribute('src', $base64);
    $reflection_text = $dom->saveHTML();
}

// turn base-case back into and simple_html_dom object that I can replace the varaible images on

if (str_get_html($base_case) != false) {
    $base_case = str_get_html($base_case);

    $keep = 0;
    $varImages = $base_case->find('.var_image');
    foreach ($varImages as $varImage) {
        $var_image_id = $varImage->id;

        for ($i = 0; $i < $nv; $i++) {
            if (trim($var_image_id) == trim($BC_vari[$i])) {
                $keep = 1;
            }
        }
        if ($keep == 0) {
            //  get rid of the caption and the image
            $varImage->find('.MsoNormal', 0)->outertext = '';
            $varImage->find('.MsoCaption', 0)->outertext = '';
        } else {
            //  get rid of the caption
            $varImage->find('.MsoCaption', 0)->outertext = '';
        }
        $keep = 0;
    }
}

// only include the document above the checker
$this_html =
    ' <div id = "base_case"><h4>Base Case Problem ' .
    $problem_id .
    '.</h4>' .
    $base_case .
    '</div>';
/* 
   // substitute all of the variables with their values - since the variable images do not fit the pattern they wont be replaced
       for( $i=0;$i<$nv;$i++){
            $this_html = preg_replace($pattern[$i],$vari[$i],$this_html);
        }
         */
echo $this_html;
echo '<hr>';
echo '<p style="page-break-before: always"> ';

// put in the pdf for the solution to the basecase
echo '<h4>Solution to Base Case</h4>';
// echo ('<iframe src="'.$solnfilenm.'" width="90%"  ></iframe>');
echo '<iframe src="' . $solnfilenm . '" width="60%" height="600px" ></iframe>';
echo '<hr>';
echo '<p style="page-break-before: always"> ';

echo $reflection_text;
?>
<style>
.hidden {
    visibility: hidden;
}
.display_none { display: none;
}


</style>

<script> 
    var enc_key = '<?php echo $enc_key; ?>';
    // console.log('enc_key ',enc_key);
        var vid1 = document.getElementById("vid1")
      

    vid1.onloadeddata = function() {
        //alert("Browser has loaded the current frame");
        console.log(" vid1 ", vid1);
   //     console.log(" vid1 ", vid1);
            const duration = vid1.duration;
            console.log(" duration ", duration);

            // console.log(duration);
            vid1.addEventListener("play",getTime)
            function getTime(e){
                let event = e.currentTarget;
                console.log (" event ",event)
                let quiz_items = event.getElementsByClassName("vid1-question") // need to loop throut these
                 console.log("the quiz_items are ",quiz_items);
                let encryption = new Encryption();
                // const nodeItems = document.getElementsByClassName('node-item');
                let question_number = 0; // counter for displaying the right question
                let trip = 0;
                    for (const quiz_item of  quiz_items) {
                        console.log (" quiz_item ",quiz_item);
                    
                        //  let pause_time = encryption.decrypt(quiz_item.querySelector(".time").innerText,enc_key);  //getting the time that has been encoded 64

                        // let pause_time = encryption.decrypt(Q_1_1.querySelector(".time").innertext,enc_key);  //getting the time that has been encoded 64
                        let question = quiz_item.querySelector(".text").innerText;  //getting the question that has been encoded 64
                        question = encryption.decrypt(question,enc_key); 
                        console.log (" question ",question);
                        let pause_time =  quiz_item.querySelector(".time").innerText; 
                        pause_time = encryption.decrypt(pause_time,enc_key); 
                        console.log (" pause time ",pause_time);


                        let option1 =  encryption.decrypt(quiz_item.querySelector(".option-1").innerText,enc_key); //getting the option that has been//getting the question that has been encoded 64
                        // question = encryption.decrypt(question,enc_key);
                        // console.log (" the question is ",question);

                        console.log (" the option1 is ",option1);
                    
                        setInterval(function(){
                          

                            const vid1_container = document.getElementById("vid1_container")

                       //     if (event.currentTime >pause_time){vid1.pause(); vid1_container.classList.add("hidden"); }
                         
                            if (event.currentTime >pause_time){
                                    vid1.pause();
                                    vid1.classList.add("hidden");
                                    if (trip == 0){ trip = 1;
                                         displayQuestion(question_number);
                                    }
                                 }
                            //  console.log(event.currentTime);

                        },250);
                       
                       function displayQuestion(Q_number){
                            let vid1_question_container = document.getElementById('vid1_question_container');
                            vid1_question_container.innerHTML += 'Hello ';
                            vid1_question_container.innerHTML += Q_number;
                            question_number ++;
                        }


                 }
            }


    };

        vid1.addEventListener("play",findVideoProperties);

        function findVideoProperties(e){
      
        }
        </script>




