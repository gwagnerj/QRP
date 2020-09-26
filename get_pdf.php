<?php

// Include config file
require_once 'pdo.php';
session_start();
//require_once "random_compat-2.0.18/lib/random.php"; // needed this for the random_bytes function did not work on the online version
//require_once('tcpdf_min/tcpdf.php');  

// this is a file that will display the file(s) that the student work on the screen.  It should handle pdf, jpeg or png2wbmp
// this is called by stu_assignment_results.php with the activity_id.  The student work should be in the student_work sub directory and have the format activityid-num-filename.extension.  the num is the number of the files

if (isset($_GET['activity_id'])){
    $activity_id = $_GET['activity_id'];
  //  echo (' $activity_id  '.$activity_id);
} else {
    echo('No activity id');
    // close it down give an error
} 

 $sql = "SELECT *  FROM `Activity` WHERE activity_id = :activity_id";
               $stmt = $pdo->prepare($sql);
               $stmt -> execute(array(
                  ':activity_id' => $activity_id,
                    )); 

          $activity_data = $stmt->fetch();
          echo('<h2> Work for '.$activity_data['stu_name'].' Problem '.$activity_data['alias_num'].'</h2><p> &nbsp;&nbsp;&nbsp; Problem_id '.$activity_data['problem_id'].' - '.$activity_data["dex"].'&nbsp;&nbsp;&nbsp; activity_id '.$activity_id.'</p>');
        //  echo('<p> &nbsp;&nbsp;&nbsp; activity_id '.$activity_id.'</p>'); 


//$file_name = "/student_work/".$activity_id."*.*";
//echo'<br>';

//echo('  file_name  '.$file_name);
echo'<br>';
$all_files = array();
$dir =  'student_work';
$prefix = $activity_id.'-';
chdir($dir);
$matches = glob("$prefix*");
if(is_array($matches) && !empty($matches)){
    foreach($matches as $match){
        $all_files[] = $match;
    }
}
 
 
 
 
/* 
 $pdf_files = glob("/student_work/".$activity_id."-*.pdf");
  $png_files = glob("/student_work/".$activity_id."-*.png");
  $jpg_files = glob("/student_work/".$activity_id."-*.jpg");
  $all_files = glob($file_name);
   */



//var_dump($all_files);

foreach($all_files as $all_file){
  // check the extension of the file 
     $tmp = explode('.', $all_file);
     $extension = end($tmp);
    // echo(' $extension '.$extension);
     if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' ){
  
            echo'<br>';
            echo '<img src="student_work/'.$all_file.'"   width="70%" height="100%">';
     } elseif($extension == 'pdf'){
       // echo(' all_file  '.$all_file); 
        echo(' <iframe frameborder="0" scrolling="no"');
         echo('width="70%" height="100%"');
         echo('src="student_work/'.$all_file.'">');
         
     echo '</iframe>';
         
         
         
         
     } else {
         $_SESSION['error'] = $extention.' file type not allowed';
           echo ($extention.'<h2> file type not allowed </h2>');
     }
}


/* 
        
        $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
        $obj_pdf->SetCreator(PDF_CREATOR);  
        $obj_pdf->SetTitle("QRBlood.com Data  for ".$users_data['first_name']." ".$users_data['last_name']);  
        $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
        $obj_pdf->SetDefaultMonospacedFont('helvetica');  
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
        $obj_pdf->setPrintHeader(false);  
        $obj_pdf->setPrintFooter(false);  
        $obj_pdf->SetAutoPageBreak(TRUE, 10);  
        $obj_pdf->SetFont('helvetica', '', 11);  
        $obj_pdf->AddPage();  
        $content = '';  
        $content .= '  
        <h3 align="center">QRBlood.com Data for '.$users_data["first_name"].' '.$users_data["last_name"].'</h3>
        <h4 align="center">Blood Sugar: <font color = "red"> more than 300 </font> <font color = "darkorange"> Between (150, 300) </font><font color = "darkgreen"> Between (80, 150) </font><font color = "blue"> less than 80 </font></h4><br /> 
        <table border="1" cellspacing="0" cellpadding="3">  
        <tr>  
          <th width="6%">Day</th>  
   <th width="10%">Date</th>  
        <th width="12%">Time</th>  
        <th width="8%">Blood Sugar</th>  
        <th width="8%">F.A. Insulin</th> 
        <th width="8%">L.A. Insulin</th>  
        <th width="8%">BP-Syst</th>  
        <th width="8%">BP-Dia</th>   
        <th width="8%">Pulse</th>         
        <th width="25%">Comment</th>  
        </tr> 
        '.$output;  
        
      //  $content .= fetch_data();  
        $content .= '</table>';  
        $obj_pdf->writeHTML($content);  
        $obj_pdf->Output('file.pdf', 'I');   
       */         
        ?>  
        
        <!DOCTYPE html>
<html lang = "en">
<head>
<link rel="icon" type="image/png" href="McKetta.png" />  
<meta Charset = "utf-8">
<title><?php echo ($activity_data['stu_name'].'-P'.$activity_data['alias_num']); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
		<link rel="stylesheet" type="text/css" href="jquery.countdown.css"> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript" src="jquery.plugin.js"></script> 
		<script type="text/javascript" src="jquery.countdown.js"></script>
     <!--   <!DOCTYPE html>  
        <html>  
        <head>  
        <title>Generate QRBlood Table Data To PDF</title>  
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />            
        </head>  
        <body>  
        <br />
        <div class="container">  
        <h4 align="center"> QRBlood Data</h4><br />  
        <div class="table-responsive">  
        <div class="col-md-12" align="right">
        <form method="post">  
        <input type="submit" name="generate_pdf" class="btn btn-success" value="Generate PDF" />  
        </form>  
        </div>
        <br/>
        <br/>
        <table class="table table-bordered">  
        <tr>  
        <th width="10%">Date/Time</th>  
        <th width="10%">Blood_Sugar</th>  
        <th width="10%">S.A. Insulin</th>  
        <th width="10%">L.A. Insulin</th>  
        <th width="10%">Pulse</th>  
         <th width="50%">Comment</th>  
        </tr>  
        <?php  
       // echo fetch_data();  
        ?>  
        </table>  
        </div>  
        </div>  
        </body>  
        </html>
     -->
     
     