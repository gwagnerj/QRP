<?php
require_once "pdo.php";
require_once "simple_html_dom.php";

session_start();
    

$explanation_str = '';

if(isset($_POST['iid'])){
  $iid = $_POST['iid'];
} elseif (isset($_GET['iid'])){
    $iid = $_GET['iid'];
} else {

$_SESSION['error'] = 'iid lost in writeQuestionInstructor';
header('Location: QRPRepo.php');
die();

}

if (isset($_GET['check_flag'])){ $check_flag = $_GET['check_flag'];}else{ $check_flag =0;}

//? initialize key array
$letters = range ('a','j');
$key = array();
foreach ($letters as $l){
    $key[$l] = 0;
}

//? initialize vars
$num_options = 4;
$course = $primary_concept = $secondary_concept = $tertiary_concept = $question_type = $question_use = $htmlfilenm = $nm_author = '';
$stem_text_1_str = '';
$option_str_ar = array_fill(0,10,''); // initialize a array of blank strings

$is_author_flag = true;  // assume this is true until we show it as false
$questionwomb_id =0;
$status = "started";  //? Assume this until we can show otherwise

if (isset($_GET['questionwomb_id'])){
    $questionwomb_id = $_GET['questionwomb_id'];
    
    

   
    //? comming from an edit and need to see if this is the author 1st reviewer or 2nd reviewer
    $sql = "SELECT * FROM QuestionWomb WHERE questionwomb_id = :questionwomb_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':questionwomb_id' => $questionwomb_id));
    $questionwomb_data = $stmt -> fetch();

    $qw_student_id = $questionwomb_data['student_id'];
    $qw_user_id = $questionwomb_data['user_id'];
    $primary_concept = $questionwomb_data['primary_concept'];
    $secondary_concept = $questionwomb_data['secondary_concept'];
    $tertiary_concept = $questionwomb_data['tertiary_concept'];
    $title = $questionwomb_data['title'];
    $subject = $questionwomb_data['subject'];
    $grade = $questionwomb_data['grade'];
    $question_type = $questionwomb_data['question_type'];
    $question_use = $questionwomb_data['question_use'];
    $specif_ref = $questionwomb_data['specif_ref'];
    $unpubl_auth = $questionwomb_data['unpubl_auth'];
    $course = $questionwomb_data['course'];
    $status = $questionwomb_data['status'];
    $htmlfilenm = $questionwomb_data['htmlfilenm'];
    $explanation_filenm = $questionwomb_data['explanation_filenm'];


  //  var_dump( $explanation_filenm);

    if ($explanation_filenm){

        $html_explan = new simple_html_dom();
        $full_explan_filenm = 'uploads/'.$explanation_filenm.'.htm';
     //   echo ' full_explan_filenm   ______  '. $full_explan_filenm;
        $html_explan -> load_file ($full_explan_filenm);
        if ($html_explan){
            $ret_expl = $html_explan->find('#explanation')[0]->innertext;
            $explanation_str = $ret_expl;
        } else {
            $explanation_str = '';
        }
    }

    $html = new simple_html_dom();
    $full_htmlfn = 'uploads/'.$htmlfilenm.'.htm';
    $html -> load_file ($full_htmlfn);
    $ret = $html->find('#stem_text_1')[0]->innertext;
    $stem_text_1_str = $ret;
    $num_options = 0;
    $i = 0;
    foreach ($letters as $l){
        $select = 'key_'.$l;
        $key[$l] = $questionwomb_data[ $select];
        if ($questionwomb_data[ $select] != NULL){  //? its null if there is no value for it (that question did not have that part)
            $num_options++;
            $sel = '#question_option_'.$l;
            $option_str_ar[$i] = str_replace('##','',$html->find($sel)[0]->innertext);  //? should read in all of the previosu text from the file options that has already been put in
        }
        $i++;
    }

  
}





//? get the contributor or student contributor information
//? first set it up for a student coming from moodle_to_writeQuestion.php

            $sql = 'SELECT * FROM Student WHERE `student_id` = :student_id';
            $stmt = $pdo->prepare($sql);
                $stmt->execute(array(':student_id' => $qw_student_id));
                $student_data = $stmt -> fetch();
                $first_name = $student_data['first_name'];
                $last_name = $student_data['last_name'];
                $nm_author = $first_name.' '.$last_name;
                $university = $student_data['university'];
                $email = $student_data['school_email'];


    // if ($questionwomb_id !=0 && ($student_id == $qw_student_id || $user_id == $qw_user_id))  {
    //     $is_author_flag = true;
    // }     

  



$discipline = '';	


// if (isset($_POST['reset']))	{
			
// 			$title = '';
// 			unset($_SESSION['title']);
// 			 unset($_POST);
// 			header('Location: writeQuestion.php'); // reloads the page
// 			die();
// 		}
// Flash pattern
	if ( isset($_SESSION['error']) ) {
		echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
		unset($_SESSION['error']);
	}
// echo 'check_flag '.$check_flag;
?>
<!DOCTYPE HTML>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title>QRQuestions</title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
         <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"> 
         <!-- <script type="text/javascript" src="jquery-te-1.3.2.js"></script> -->
         <!-- <script src="node_modules/@wiris/mathtype-generic/wirisplugin-generic.js"></script> -->
         <script src="https://cdn.tiny.cloud/1/85w3ssemz2iqrt9zi0qce5e3emgos9nsyvkfv9bt0loc3twd/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
         </script>

    <?php  
    echo "  <script>
    tinymce.init({
      selector: '#question_stem_text_1',
      setup: function (editor) {
      editor.on('init', function (e) {
        editor.setContent('".$stem_text_1_str."');
      });
    },
      min_height: 50,
      height: 110,
      margin: 10,
      content_style: 'body { line-height: 1; }',
           menubar: false,
           toolbar: ' superscript subscript | bold italic underline | cut copy paste | alignleft aligncenter alignright | outdent indent | table |charmap',
           tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
         plugins: 'a11ychecker charmap advcode casechange export formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
       //    toolbar: 'a11ycheck addcomment showcomments casechange checklist code export formatpainter pageembed permanentpen table',
       // toolbar: 'fontselect '
    });
  </script>";

    echo "  <script>
    tinymce.init({
      selector: '#explanation',
      setup: function (editor) {
      editor.on('init', function (e) {
        editor.setContent('".$explanation_str."');
      });
    },
      min_height: 50,
      height: 110,
      margin: 10,
      content_style: 'body { line-height: 1; }',
           menubar: false,
           toolbar: ' superscript subscript | bold italic underline | cut copy paste | alignleft aligncenter alignright | outdent indent | table |charmap',
           tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
         plugins: 'a11ychecker charmap advcode casechange export formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
       //    toolbar: 'a11ycheck addcomment showcomments casechange checklist code export formatpainter pageembed permanentpen table',
       // toolbar: 'fontselect '
    });
  </script>";
//loop thru the options and put the values that have already been put into input boxes in tinymce


    $i = 0;
    foreach ($letters as $l){
        $j = $i + 1;

        echo "  <script>
        tinymce.init({
          selector: '#question-option_".$j."',
          setup: function (editor) {
          editor.on('init', function (e) {
            editor.setContent('".$option_str_ar[$i]."');
          });
        },
          min_height: 50,
          height: 110,
          margin: 10,
          content_style: 'body { line-height: 1; }',
               menubar: false,
               toolbar: ' superscript subscript | bold italic underline | cut copy paste | alignleft aligncenter alignright | outdent indent | table |charmap',
          tinycomments_mode: 'embedded',
          tinycomments_author: 'Author name',
             plugins: 'a11ychecker charmap advcode casechange export formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
           //    toolbar: 'a11ycheck addcomment showcomments casechange checklist code export formatpainter pageembed permanentpen table',
          // toolbar: 'charmap',
    
        });
      </script>";
    
    
        $i++;
    }





   

    ?>



<style type="text/css">
.hide{
    display: none;
}

body{ 
    margin-left: 3rem;
    background-color: #FFFAFA;
}

.form-group{
    width: 50% !important;
    display:inline-block !important;
    font-size: 1.3rem;
    color: #000000;
}
.optional{ 
    color: gray;
}
select.form-control{display:inline-block}

.image_preview{
     width: 40%;
     min-height: 100px;
     border: 2px solid #dddddd !important;
     margin-top: 15px;
     margin-left: 10px;


     display: flex;
     align-items: center;
     justify-content: center;
     font-weight: bold;
     color: #cccccc !important;
}
.image_preview_image{ 

    /* display: none !important; */
    width: 100%;
}
/* label {
        display: inline-block;
        text-align: right;
         width: 10%; 
      } */
   span { 
       display: inline-block;

   } 

.text{
    display: inline-block;

}

input[type='radio'] { 
     transform: scale(1.5); 
 }
input[type='checkbox'] { 
     transform: scale(1.5); 
 }

.option_label{ 
    position:relative; 
    right:12px;
}

/* .tox-editor-header{ 
    display:none;
} */


</style>
</head>

<body>
<header>
<h2>Quick Response Question Writing System</h2>

<h3 id = "name_header" class = "m-4"> Welcome  <?php echo $nm_author; ?> </h3>
</header>
<div id = "btn_group">
    <button type="button" id = "write_btn" title = "Write a new question" class="btn btn-outline-primary btn-lg ms-4">Write Question</button>
    <!-- <button type="button" id = "edit_btn" title = "Edit Questions I have Written Earlier" class="btn btn-outline-success btn-lg ms-4">Edit Question</button> -->
    <button type="button" id = "check_btn" title = "Check Questions Others have Written" class="btn btn-outline-secondary btn-lg ms-4">Check Others Questions</button>
</div>
<!-- put in a hide class to the meta  container in the final version -->
<div id = "big_question_container" class="big-question-container mx-4 ">
   <div id = "meta_container" class = "hide">

        <h3><b>Please Provide Question Meta Data:</b></h3>
   <form id = "main_form" method="POST" action ="writeQuestionCatcherInstructor.php" enctype = "multipart/form-data">
        <p></p>

        <input type="hidden" name="email" value ="<?php echo ($email)?>" >
        <input type="hidden" name="nm_author" value ="<?php echo ($nm_author)?>" >
        <input type="hidden" name="university" value="<?php echo ($university)?>" >
        <input type="hidden" name="student_id" id = "student_id" value="<?php echo ($qw_student_id)?>">
        <input type="hidden" name="iid" value="<?php echo ($iid)?>">
        <input type="hidden" name="num_options" id = "num_options" value="<?php echo ($num_options)?>">
        <input type="hidden"  id = "is_author_flag" value="<?php echo ($is_author_flag)?>">
        <input type="hidden"  id = "qw_course" value="<?php echo ($course)?>">
        <input type="hidden"  id = "qw_primary_concept" value="<?php echo ($primary_concept)?>">
        <input type="hidden"  id = "qw_secondary_concept" value="<?php echo ($secondary_concept)?>">
        <input type="hidden"  id = "questionwomb_id" name = "questionwomb_id" value="<?php echo ($questionwomb_id)?>">
        <input type="hidden"  id = "status" name = "status" value="<?php echo ($status)?>">
        <input type="hidden"  id = "check_flag" name = "check_flag" value="<?php echo ($check_flag)?>">
       
        <div class = "row" id = "title_container">
            <p> Question Title:
                <input type="text" name="title" id = "title" value = "<?php if (isset($title)){echo $title;} ?>"></p>
        </div>	


        <div class = "row "  id = "discipline_container">
            <div class = "form-group" style = "display: inline;">
                <label for = "Discipline">Discipline (e.g. Chemical Engineering):</label>
                <select  id = "discipline" name = "subject" disabled >
                    <option  selected = "" disabled = "" value = ""> Select Discipline </option>
                    <?php
                        $stmt = "SELECT * FROM `Discipline`";
                        $stmt = $pdo->query($stmt);
                        $stmt = $pdo->query("SELECT * FROM Discipline ORDER BY Discipline.discipline_name");
                        $disciplines = $stmt->fetchALL(PDO::FETCH_ASSOC);
                            foreach ($disciplines as $discipline) {
                                if ($discipline['discipline_name']==$subject) {$sel = "selected";} else {$sel = "";}
                                    echo "<option ".$sel." id='".$discipline['discipline_id']."' value='".$discipline['discipline_name']."'>".$discipline['discipline_name']."</option>";
                            }
                    ?>
                </select>
            </div>
        </div>
        </br>
        <div  id = "course_container" class = "row">	
            <div class = "form-group">
                <label for = "course">Course Name (e.g. Thermodynamics):</label>
                <select    id = "course" name = "course" disabled>	
                <option selected = "" disabled = "" value = "" > Select Course </option>
                    
                </select>
            </div>
        </div>		
        </br>		
        <div  id = "pconcept_container" class = "row">	
            <div class = "form-group">
                <label for = "p_concept">Primary Concept (e.g. Conservation of Mass ):</label>
                <select  id = "p_concept" name = "p_concept" disabled>	
                <option selected = "" disabled = "" value = ""> Select Primary Concept </option>	
                </select>
            </div>
        </div>			
        </br>			
        <div id = "sconcept_container" class = "row">	
            <div  class = "optional">
                <label for = "course">Secondary Concept (optional):</label>
                <select  id = "s_concept" name = "s_concept" disabled>	
                <option selected = "" disabled = ""> Select Secondary Concept </option>		
                </select>
            </div>
        </div>		
        
        
        <div class = "row mt-3 optional" >	
            <p>Other Descriptor(s) Instructors may Search for (e.g. water treatment cooling tower )(optional):
            <input  type="text" name="t_concept" id = "t_concept" value =" <?php if (isset($tertiary_concept)){echo $tertiary_concept;} ?>" disabled> </p>
         </div>

        <div class = "row mt-3" id = "unpub_author">	
            <div class = "optional">
                <label for = "course">Author of Question (if different than Contributor):</label>
                <input   type="text" name="un_nm_author" id = "un_nm_author" value =" <?php if (isset($unpubl_auth)){echo $unpubl_auth;} ?>" disabled ></p>
                
                </select>
                
            </div>
        </div>					
        <p>
        <div class = "row optional">
            <p>Specific Reference (if applicable) (e.g. Felder 4th ex 3.2):
            <input type="text" name="spec_ref" id = "spec_ref" value = " <?php if (isset($specif_ref)){echo $specif_ref;} ?>" disabled ></p>
        </div>
        <p>
  



        <div  id = "grade_container" class = "row my-3">	
            <div class = "form-group  optional fs-5">
                <label for = "grade">Question Level:</label>
                <select  id = "grade" name = "grade" disabled>	
                <option value = "1" <?php  if ($grade == 1){echo 'selected';} ?> > Elementary School </option>
                <option value = "2" <?php  if ($grade == 2){echo 'selected';} ?> > Middle School </option>
                <option value = "3" <?php  if ($grade == 3){echo 'selected';} ?> > High School  </option>
                <option value = "4" selected > College or Post Graduate </option>

                </select>
            </div>
        </div>		
        
        <div  id = "quest_usage_container" class = "row mt-5">	
            <div class = "form-group">
                <label for = "question_use">Question Use Catagory:</label>
                <select  id = "question_use" name = "question_usage" disabled>	
                <option selected = "" disabled = "" value = ""> Select Question Use Catagory </option>	
                <option value = "1"  <?php  if ($question_use == 1){echo 'selected';} ?> > Basic Knowledge </option>
                <option value = "2"  <?php  if ($question_use == 2){echo 'selected';} ?> > Basic Concept </option>
                <option value = "3"  <?php  if ($question_use == 3){echo 'selected';} ?> > More Advanced Concepts </option>
                <option value = "4"   <?php  if ($question_use == 4){echo 'selected';} ?>> Applications Involving Calculations </option>
                </select>
            </div>
        </div>			


        <div  id = "question_type_container" class = "row mt-3">	
            <div class = "form-group">
                <label for = "question_type">Question Format:</label>
                <select  id = "question_type" name = "question_type" disabled>	
                <option selected = "" disabled = "" value = ""> Select Question Format </option>	
                <option value = "1"  <?php  if ($question_type == 1){echo 'selected';} ?> > Just Text - Single Correct </option>
                <option value = "2" <?php  if ($question_type == 2){echo 'selected';} ?>  > Contains Images - Single Correct</option>
                <option value = "3"  <?php  if ($question_type == 3){echo 'selected';} ?> > Multiple Correct </option>

                </select>
            </div>
        </div>	
                    
        <br>
     </div>
            
            &nbsp; &nbsp;
  

 <button type = "button" class = "btn btn-primary hide "id = "hide_meta_btn" > Hide/Show Meta Data </button>
  <!-- put in the hide in the class below when finished editing file -->
 <div id = "question_writing_area_container" class= "hide" >

            <hr>
        <div class="user_input m-3" id="question_stem_container">
            <div id = "question_stem_container_1">
                <label for = "question_stem_text_1" class = "fs-4">Input Question Text Below:</label>
                <!-- <textarea id = "question_stem_text_1" class = "text fs-3 border border-secondary border-width-2" "></textarea> -->
                <textarea id = "question_stem_text_1" name = "question_stem_text_1" class = "text fs-3 border border-secondary border-width-2" contentEditable="true"></textarea>
            </div>
<!-- 
            <button type = "button" id = "stem_add_pic_btn" class = "btn btn-outline-primary btn-sm" title = "add image" >&#x2603;</button>
            <div class="user_input m-3 hide" id="stem_pic_1">
            
                <input type = "file" name = "stem_pic" id = "stem_pic" class="form-control" accept="image/*"  ></input>
            </div>
 -->
            <!-- <button type = "button" id = "stem_add_eq_btn" class = "btn btn-outline-secondary btn-sm" title = "add equation" >+&#x2211;</button>
            <div id = "question_stem_equation" class = "hide">
                <div id="stem_equation_toolbar"class = ""></div> 
                <div id="stem_equation" class = "mb-4 ms-5 border border-secondary border-width-2" contenteditable="true"></div>
            </div> -->
            <!-- <button type = "button" title = "add another text area" id = "add_question_stem_text_2_btn" class = " add_text btn-sm btn btn-outline-primary">+/-</button>


            <div id = "question_stem_container_2" class = "hide">
                <label for = "question_stem_text_2" class = "fs-5 mt-3">Additonal Text:</label>
                <textarea id = "question_stem_text_2" name = "question_stem_text_2" class = "text fs-3 border border-secondary border-width-2" contentEditable="true"></textarea>
            </div> -->

        </div>
            <br>
            <!-- <label for = "question_option_container">Input Options Text Below:</label> -->
            
            <div id = options>
           
                <div id = "container-option_1">
               
                    <label for ="question-option_1" class = "option_label fs-4 " > a)</label>
                   
                    <input type = "radio" name = "correct_option[]" required class = "radio "  <?php if($key['a']==1 && $question_type<3 ){echo "checked";}?>  value = "key_a">  <input type="checkbox" class = "check hide"  <?php if($key['a']==1 && $question_type==3) {echo "checked";} ?> name="correct_option[]" value="key_a">  <span class = "ms-1 fs-6 text-secondary"> correct </span></input>

                    <textarea id = "question-option_1" name = "question_option_a" style="width:95%;" class = "text fs-3 ms-2 mb-5 border  border-secondary border-width-2 options " contentEditable="true"></textarea>
                </div>
               
                <div id = "container-option_2">

                    <label for ="question-option_2" class = "option_label fs-4 mt-2 " > b)</label>
                    <input type = "radio" name = "correct_option[]" class = "radio " <?php if($key['b']==1 && $question_type<3 ){echo "checked";}?>  value = "key_b">  <input type="checkbox" class = "check  hide" <?php if($key['b']==1 && $question_type==3){echo "checked";}?> name="correct_option[]" value="key_b">  <span class = "ms-1 fs-6 text-secondary"> correct </span></input>

                    <textarea id = "question-option_2" name = "question_option_b" style="width:95%;" class = "text fs-3 ms-2 mb-5 border  border-secondary border-width-2 options " contentEditable="true"></textarea>
                    <!-- <button type = "button" title = "add another option" id = "add-option_2" class = " add-option_btn-sm btn btn-outline-primary hide">&plus;</button> -->
                </div>

                <div id = "container-option_3" class = "<?php if ($num_options < 3){echo 'hide';} ?>">
                    <label for ="question-option_3" class = "option_label fs-4 mt-2 " > c)</label>
                    <input type = "radio" name = "correct_option[]" class = "radio "  <?php if($key['c']==1 && $question_type<3 ){echo "checked";}?> value = "key_c">  <input type="checkbox" class = "check  hide" <?php if($key['c']==1 && $question_type==3){echo "checked";}?> name="correct_option[]" value="key_c">  <span class = "ms-1 fs-6 text-secondary"> correct </span></input>

                    <textarea id = "question-option_3"  name = "question_option_c" style="width:95%;" class = "text fs-3 ms-2 mb-5 border  border-secondary border-width-2 options " contentEditable="true"></textarea>
                    <!-- <button type = "button" title = "add another option" id = "add-option_3" class = " add-option_btn btn-sm btn-outline-primary hide ">&plus;</button>
                    <button type = "button" title = "remove option" id = "remove-option_3" class = " remove-option btn btn-sm btn-outline-secondary hide">&minus;</button> -->
                </div>
             
                <div id = "container-option_4" class = "<?php if ($num_options < 4){echo 'hide';} ?>">
                    <label for ="question-option_4" class = "option_label fs-4 mt-2 " > d)</label>
                    <input type = "radio" name = "correct_option[]" class = "radio "  <?php if($key['d']==1 && $question_type<3 ){echo "checked";}?> value = "key_d">  <input type="checkbox" class = "check  hide" <?php if($key['d']==1 && $question_type==3){echo "checked";}?> name="correct_option[]" value="key_d">  <span class = "ms-1 fs-6 text-secondary"> correct </span></input>

                    <textarea id = "question-option_4"  name = "question_option_d" style="width:95%;" class = "text fs-3 ms-2 mb-5 border  border-secondary border-width-2 options " contentEditable="true"></textarea>
                 </div>
                 
                <div id = "container-option_5" class = "<?php if ($num_options < 5){echo 'hide';} ?>">
                    <label for ="question-option_5" class = "option_label fs-4 mt-2 " > e)</label>
                    <input type = "radio" name = "correct_option[]" class = "radio "  <?php if($key['e']==1 && $question_type<3 ){echo "checked";}?> value = "key_e">  <input type="checkbox" class = "check  hide" <?php if($key['e']==1 && $question_type==3){echo "checked";}?> name="correct_option[]" value="key_e">  <span class = "ms-1 fs-6 text-secondary"> correct </span></input>
                    <textarea id = "question-option_5"  name = "question_option_e" style="width:95%;" class = "text fs-3 ms-2 mb-5 border  border-secondary border-width-2 options " contentEditable="true"></textarea>
                </div>

                <div id = "container-option_6" class = "<?php if ($num_options < 6){echo 'hide';} ?>">
                    <label for ="question-option_6" class = "option_label fs-4 mt-2 " > f)</label>
                    <input type = "radio" name = "correct_option[]" class = "radio "  <?php if($key['f']==1 && $question_type<3 ){echo "checked";}?> value = "key_f">  <input type="checkbox" class = "check  hide" <?php if($key['f']==1 && $question_type==3){echo "checked";}?> name="correct_option[]" value="key_f">  <span class = "ms-1 fs-6 text-secondary"> correct </span></input>
                    <textarea id = "question-option_6"  name = "question_option_f" style="width:95%;" class = "text fs-3 ms-2 mb-5 border  border-secondary border-width-2 options " contentEditable="true"></textarea>
                </div>

                <div id = "container-option_7" class = "<?php if ($num_options < 7){echo 'hide';} ?>">
                    <label for ="question-option_7" class = "option_label fs-4 mt-2 " > g)</label>
                    <input type = "radio" name = "correct_option[]" class = "radio "  <?php if($key['g']==1 && $question_type<3 ){echo "checked";}?> value = "key_g">  <input type="checkbox" class = "check  hide" <?php if($key['g']==1 && $question_type==3){echo "checked";}?> name="correct_option[]" value="key_g">  <span class = "ms-1 fs-6 text-secondary"> correct </span></input>
                    <textarea id = "question-option_7"  name = "question_option_g" style="width:95%;" class = "text fs-3 ms-2 mb-5 border  border-secondary border-width-2 options " contentEditable="true"></textarea>
                </div>

                <div id = "container-option_8" class = "<?php if ($num_options < 8){echo 'hide';} ?>">
                    <label for ="question-option_8" class = "option_label fs-4 mt-2 " > h)</label>
                    <input type = "radio" name = "correct_option[]" class = "radio "  <?php if($key['h']==1 && $question_type<3 ){echo "checked";}?> value = "key_h">  <input type="checkbox" class = "check  hide" <?php if($key['h']==1 && $question_type==3){echo "checked";}?> name="correct_option[]" value="key_h">  <span class = "ms-1 fs-6 text-secondary"> correct </span></input>
                    <textarea id = "question-option_8"  name = "question_option_h" style="width:95%;" class = "text fs-3 ms-2 mb-5 border  border-secondary border-width-2 options " contentEditable="true"></textarea>
                </div>

                <div id = "container-option_9" class = "<?php if ($num_options < 9){echo 'hide';} ?>">
                    <label for ="question-option_9" class = "option_label fs-4 mt-2 " > i)</label>
                    <input type = "radio" name = "correct_option[]" class = "radio "  <?php if($key['i']==1 && $question_type<3 ){echo "checked";}?> value = "key_i">  <input type="checkbox" class = "check  hide" <?php if($key['i']==1 && $question_type==3){echo "checked";}?> name="correct_option[]" value="key_i">  <span class = "ms-1 fs-6 text-secondary"> correct </span></input>
                    <textarea id = "question-option_9"  name = "question_option_i" style="width:95%;" class = "text fs-3 ms-2 mb-5 border  border-secondary border-width-2 options " contentEditable="true"></textarea>
                </div>

                <div id = "container-option_10" class = "<?php if ($num_options < 10){echo 'hide';} ?>">
                    <label for ="question-option_10" class = "option_label fs-4 mt-2 " > j)</label>
                    <input type = "radio" name = "correct_option[]" class = "radio "  <?php if($key['j']==1 && $question_type<3 ){echo "checked";}?> value = "key_j">  <input type="checkbox" class = "check  hide" <?php if($key['j']==1 && $question_type==3){echo "checked";}?> name="correct_option[]" value="key_j">  <span class = "ms-1 fs-6 text-secondary"> correct </span></input>
                    <textarea id = "question-option_10"  name = "question_option_j" style="width:95%;" class = "text fs-3 ms-2 mb-5 border  border-secondary border-width-2 options " contentEditable="true"></textarea>
                </div>


                <button type = "button" title = "add another option" id = "add-option" class = "ms-5 mt-2 mb-3 add-option btn btn-sm btn-outline-primary ">&plus;</button>
                   <button type = "button" title = "remove option" id = "remove-option" class = "ms-1 mt-2 mb-3 remove-option btn btn-sm btn-outline-secondary" >&minus;</button>


                </div>
                <div id = "soln_container" class = "">
                    <div id = "soln_file_container" class = "hide">
                        <label for="file">Choose file to upload</label>
                        <input type="file" class = "" id="auth_solnfile" name="auth_solnfile" accept="image/*,.pdf"> </input>
                    </div>

                    <div id = "explanation_container" class = "">
                    <label for ="explanation" class = "option_label fs-4 mt-2 " > Explaination </label>
                        <textarea id = "explanation" name = "explanation" style="width:95%;" class = "text fs-3 ms-2 mb-5 border  border-secondary border-width-2 options " contentEditable="true" >

                        </textarea>

                    </div>

                    <div id = "check1_file_container" class = "hide">
                        <label for="file">Choose file to upload</label>
                        <input type="file" class = "" id="check1_solnfile" name="check1_solnfile" accept="image/*,.pdf"> </input>
                    </div>

                    <div id = "check2_file_container" class = "hide">
                        <label for="file">Choose file to upload</label>
                        <input type="file" class = "" id="check2_solnfile" name="check2_solnfile" accept="image/*,.pdf"> </input>
                    </div>
                    <h2 id = "error_in_form" class = " hide text-danger" > </h2>    

            </div>
                <button type = "button" id = "submit_btn" name = "submit_btn" value = "move_on" class = "m-3 btn btn-outline-danger btn-lg">Submit</button>


            </div>


        </form>
</div>




<script type="text/javascript">
	
$(document).ready(function(){


    const title = document.getElementById('title');
    const discipline = document.getElementById('discipline');
    const course = document.getElementById('course');
    const p_concept = document.getElementById('p_concept');
    const s_concept = document.getElementById('s_concept');
    const t_concept = document.getElementById('t_concept');
    const un_nm_author = document.getElementById('un_nm_author');
    const spec_ref = document.getElementById('spec_ref');
    const question_use = document.getElementById('question_use');
    const question_type = document.getElementById('question_type');
    const grade = document.getElementById('grade');
    const hide_meta_btn = document.getElementById('hide_meta_btn');
    const meta_container = document.getElementById('meta_container');
    const question_writing_area_container  = document.getElementById('question_writing_area_container');
    const stem_pic = document.getElementById('stem_pic');
    const image_preview_image_1 = document.getElementById('image_preview_image_1')
    const image_preview_default_text = document.querySelector('.image_preview_default_text')
    // const add_question_stem_text_2_btn = document.getElementById('add_question_stem_text_2_btn');
    // const question_stem_container_2 = document.getElementById('question_stem_container_2');
    const editorIcon = document.getElementById('editorIcon');
    const check_btn = document.getElementById('check_btn');
    const edit_btn = document.getElementById('edit_btn');
    const btn_group = document.getElementById('btn_group');
    const addOption = document.getElementById('add-option');
    const removeOption = document.getElementById('remove-option')  // starts out with 4 options
    let num_options = document.getElementById('num_options');
    const is_author_flag = document.getElementById('is_author_flag').value;
    const qw_course = document.getElementById('qw_course').value;
    const qw_primary_concept = document.getElementById('qw_primary_concept').value;
    const qw_secondary_concept = document.getElementById('qw_secondary_concept').value;
    const submit_btn = document.getElementById('submit_btn');
    const main_form = document.getElementById('main_form');
    const status = document.getElementById('status');
    let radios = document.getElementsByClassName("radio");
     let checks = document.getElementsByClassName("check");
     const error_in_form = document.getElementById('error_in_form');
     const questionwomb_id = document.getElementById('questionwomb_id').value;
     const write_btn = document.getElementById('write_btn');
    const big_question_container = document.getElementById('big_question_container');
    const soln_container = document.getElementById('soln_container');
    const student_id = document.getElementById('student_id').value;
    const check_flag = document.getElementById('check_flag').value;
    const explanation = document.getElementById('explanation');

  
// let tox_editor_header = document.getElementsByClassName('tox-editor-header');
//     console.log(' tox_editor_header', tox_editor_header);
//     console.log(' tox_editor_header.length', tox_editor_header.length);

//     for (let i = 0; i < tox_editor_header.length; i++){
//         tox_editor_header[i].classList.add('hide');
//         console.log('tox_editor_header',tox_editor_header[i]);
//     }

    submit_btn.addEventListener('click', (e)=>{
    //? make sure all neccesary inputs are available_funds  for edited files mainly

                     un_nm_author.disabled = false;
                    spec_ref.disabled = false;
                    question_use.disabled = false;
                    question_type.disabled = false;
                    p_concept.disabled = false;
                    s_concept.disabled = false;
                    t_concept.disabled = false;
                    course.disabled = false;
                    discipline.disabled = false;
                    let error = '';

                   

                    //? check if the problems is other than basic knowledge that they have entered and explanation of the problem

                    if (question_use.value > 1){
                        tinyMCE.triggerSave();  // this transferes the tinyMCE to the text area
                        console.log ('question_use',question_use.value);
                        console.log ('explanation.value',explanation.value.length)
                        if(explanation.value.length==0){
                            error_in_form.classList.remove('hide');
                            error = 'Error - Explanation is required for this type of question';
                           error_in_form.innerText = error;
                        }

                    }

                    // make sure that at least one radio or checkbox is checked button is checked out
                    let numchecked = 0;
                    for (let i = 0; i < radios.length; i++){
                        if(radios[i].checked == true){
                            numchecked++;
                        }
                    }
                    for (let i = 0; i < checks.length; i++){
                        if(checks[i].checked == true){
                            numchecked++;
                        }
                    }

                    if (numchecked ==0) {
                        error_in_form.classList.remove('hide');
                        error = 'Error - No Option is Selected as Correct';
                        error_in_form.innerText = error;
                    }

                    if (error == ''){ main_form.submit();}




    })

        if (questionwomb_id != 0){
            meta_container.classList.add('hide');
            hide_meta_btn.classList.remove('hide');
            btn_group.classList.add('hide');
            question_writing_area_container.classList.remove('hide');
            // console.log ('meta_container',meta_container);
            // console.log ('hide_meta_btn',hide_meta_btn);

        }


        if (question_use.value == 1){soln_container.classList.add('hide');}  //? these are in effect if they come in from an edited file

        question_use.addEventListener('change',()=>{
            if (question_use.value == 1){  soln_container.classList.add('hide'); }
            if (question_use.value != 1){  soln_container.classList.remove('hide'); }
        })

        if (question_type.value == 3){
            //? reveal the checkboxes and hide the radio buttonset
            for (let i = 0; i < radios.length; i++){
                radios[i].checked = false;
                 radios[i].classList.add("hide");
                 radios[i].required = false;

            }
            for (let i = 0; i < checks.length; i++){
                checks[i].classList.remove("hide");
            }
        } else {  //? in case they change it back to single correct
            for (let i = 0; i < radios.length; i++){
                 radios[i].classList.remove("hide");
                 radios[i].required = true;
            }
            for (let i = 0; i < checks.length; i++){
                checks[i].checked = false;
                checks[i].classList.add("hide");
                
            }
        }


    question_type.addEventListener('change',() => {
       
        if (question_type.value == 3){
            //? reveal the checkboxes and hide the radio buttonset
            for (let i = 0; i < radios.length; i++){
                radios[i].checked = false;
                 radios[i].classList.add("hide");
                 radios[i].required = false;

            }
            for (let i = 0; i < checks.length; i++){
                checks[i].classList.remove("hide");
            }
        } else {  //? in case they change it back to single correct
            for (let i = 0; i < radios.length; i++){
                 radios[i].classList.remove("hide");
                 radios[i].required = true;
            }
            for (let i = 0; i < checks.length; i++){
                checks[i].checked = false;
                checks[i].classList.add("hide");
            }
        }
    })

        removeOption.addEventListener('click', (e)=>{
            if(num_options.value>2){
                let selector = 'container-option_'+num_options.value;
                let target = document.getElementById(selector);
                console.log('target',target);
                target.querySelector('.radio').checked = false;
                target.querySelector('.check').checked = false;
     //           target.checked = false;
                target.classList.add('hide');
                num_options.value = parseInt(num_options.value) -1;
            }

        })
        addOption.addEventListener('click', (e)=>{
            console.log ('clickAdd', num_options.value)
            if(num_options.value<10){
                num_options.value = parseInt(num_options.value) +1;
                let selector = 'container-option_'+num_options.value;
                console.log (selector);
                let target = document.getElementById(selector);
                target.classList.remove('hide');
            }

        })



    // console.log('question_writing_area_container',question_writing_area_container);
    // console.log ('editor icon ',editorIcon);

    hide_meta_btn.addEventListener('click', ()=>{meta_container.classList.toggle('hide'); btn_group.classList.toggle('hide');})



    title.addEventListener('input', ()=>{discipline.disabled = false;})

    check_btn.addEventListener('click', ()=>{
        let location = 'writeQuestionCheck.php?student_id='+student_id
        console.log ('location',location);
        window.location.href = location;
    })
 
    write_btn.addEventListener('click', function(){
       big_question_container.classList.remove('hide');
        btn_group.classList.add('hide');
        meta_container.classList.remove('hide');
        const name_header = document.getElementById('name_header');
        name_header.classList.add('hide');
    })

    p_concept.addEventListener('change', ()=>{
                    un_nm_author.disabled = false;
                    spec_ref.disabled = false;
                    question_use.disabled = false;
                    question_type.disabled = false;
     //               grade.disabled = false;
                })

         question_type.addEventListener('change',()=>{
            meta_container.classList.add('hide');
            hide_meta_btn.classList.remove('hide');
            // image_preview_default_text.classList.add('hide');
            question_writing_area_container.classList.remove('hide');
        })    


			$('#add_auth').hide();
			$('#add_concept').hide();
            console.log (' title ',title);
            if (title.value.length>0){
                discipline.disabled = false;
            }

            if (qw_course.length >0) {  //? already have data from data table
                $('#course').prepend('<option selected>' + qw_course + '</option>')
            }

            if (qw_primary_concept.length >0){
                $('#p_concept').prepend('<option selected>' + qw_primary_concept + '</option>')
            }
            if (qw_secondary_concept.length >0){
                $('#s_concept').prepend('<option selected>' + qw_secondary_concept + '</option>')
            }
			
            discipline.addEventListener('change', ()=>{
                course.disabled = false; 
                course.selectedIndex = 0;
                $("#course").empty();
                $("#p_concept").empty();
                $("#s_concept").empty();
                t_concept.value ='';
              $('#course').append('<option> Select Course </option>') 
				
				let discipline_val = $("#discipline").val();
				$.ajax({
					url: 'dcData.php',
					method: 'post',
					data: 'discipline=' + discipline_val
				}).done(function(course){
					 course = JSON.parse(course);
					course.forEach(function(course){
						$('#course').append('<option>' + course.course_name + '</option>') 
						
					 })
				})
			})
			
			$("#course").change(function(){
				$('#add_auth').show();
				$('#add_concept').show();
                p_concept.disabled = false;
                $("#p_concept").empty();
                $('#p_concept').append('<option> Select Primary Concept </option>') 
                s_concept.disabled = false;
                $("#s_concept").empty();
                $('#s_concept').append('<option> Select Secondary Concept </option>') 
                t_concept.disabled = false;
                t_concept.value ='';
               
				
				let course_val = $("#course").val();
				$.ajax({
					
					url: 'ccData.php',
					method: 'post',
					data: 'course=' + course_val
				}).done(function(p_concept){
					 concept = JSON.parse(p_concept);
					concept.forEach(function(concept){
						$('#p_concept').append('<option>' + concept.concept_name + '</option>') 
										
						
					 })
					 concept.forEach(function(concept){
						$('#s_concept').append('<option>' + concept.concept_name + '</option>') 
					 })
				})
			
			$("input[name=un_nm_author]").keypress(function(){
				$('#publ_auth').hide();
			})
			
			})
			
		})


	</script>
<script type="text/javascript">
$(document).ready(function(){

});



</script>

</body>
</html>