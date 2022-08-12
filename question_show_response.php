<?php
require_once "pdo.php";
include 'phpqrcode/qrlib.php'; 
session_start();

    $iid = '1';
    if(isset($_POST["iid"])){
        $iid = $_POST["iid"];
    }elseif(isset($_GET["iid"])){
        $iid = $_GET["iid"];
    }
    $course = 'Testing Problems';
    if(isset($_POST["course"])){
        $course = $_POST["course"];
    }elseif(isset($_GET["course"])){
        $course = $_GET["course"];
    }
    $currentcourse = 'Testing Problems';
    if(isset($_POST["course"])){
        $currentcourse = $_POST["currentcourse"];
    }elseif(isset($_GET["currentcourse"])){
        $currentcourse = $_GET["currentcourse"];
    }
    $question_id = '1';
    if(isset($_POST["question_id"])){
        $question_id = $_POST["question_id"];
    }elseif(isset($_GET["question_id"])){
        $question_id = $_GET["question_id"];
    }


    $sql = "SELECT currentclass_id FROM CurrentClass WHERE  `name` = :course";

    $stmt = $pdo->prepare($sql);	
    $stmt->execute(array(
        ':course'	=> $course,
       
    ));
    $currentclass_id_ar = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentclass_id = $currentclass_id_ar['currentclass_id'];

    $sql = "SELECT * FROM Question WHERE  course = :course AND `question_id`=:question_id ";

    $stmt = $pdo->prepare($sql);	
    $stmt->execute(array(
        ':course'	=> $course,
        ':question_id'	=> $question_id
       
    ));
    $question = $stmt->fetch(PDO::FETCH_ASSOC);

    $html_fn = $question['htmlfilenm'];
    if(!strpos($html_fn,'.htm')){
        $html_fn = $html_fn.'.htm';
    }
// echo('$html_fn: '.$html_fn);
   $html_fp = 'uploads/'.$html_fn;  // the file path to the question
// echo('$html_fp: '.$html_fp);




// $qrchecker_text =  'https://www.qrproblems.org/QRP/QR_BC_Checker2.php?question_id='.$question_id.'&currentcourse='.$currentcourse; 
$qrquestion_text =  'localhost/QRP/QR_to_quickQuestion.php?question_id='.$question_id.'&currentcourse='.$currentcourse; 

$file_qrcode = 'uploads/temp_bc.png'; 
        // $ecc stores error correction capability('L') 
        $ecc = 'XL'; 
        $pixel_size = 4; 
        $frame_size = 3; 
          
        // Generates QR Code and Stores it in directory given 
          QRcode::png($qrquestion_text, $file_qrcode, $ecc, $pixel_size, $frame_size); 
          $qrcode = "<span id = 'qrcode_id_bc'><img src='".$file_qrcode."'><h2> QR Quick Question </h2></span>"; 


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="McKetta.png" />  
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Response Chart</title>

    <style>
#iframe{

width: 1000px;
height: 500px !important;
/*transform: scale(0.9);
 position: absolute;
left: -300px !important;
top: -100px !important;
padding: 0 !important;
margin-left: 0 !important; */

}
#question_stem{ 
   display:flex;
}

.hide{ 

display:none !important;

}
.col{position:relative;}
    </style>




</head>
<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <button id = "toggle_question"type="button" class="btn btn-outline-secondary mx-3 ">Toggle Question</button>
             <button id = "show_chart" type="button" class="btn btn-outline-primary ">Show Results</button>

            <button id = "toggle_QRCode" type="button" class="btn btn-outline-success position-absolute top-0 end-0 m-3">Toggle QRCode</button>

        </nav>
        <div id="question_info">
            <div class="row m-2  ">
                <div class="col">
                    <h4 class = "  ms-5">qrquestion.org </h4>
                </div>
                <div class="col">
                    <h4 class = "text-primary  ms-5">Question Number: <span class="text-primary fw-bold border rounded"> <?php echo $question_id ?></span></h4>
                </div>
            <div class="col">
                    <h4 class = "text-success text-decoration-underline ms-5">Class Number: <span class="text-success fw-bold border rounded"><?php echo $currentclass_id ?></span></h4>
            </div>
        </div>
        </div>

    <div id = "question_title" class="question_title h1"> <?php echo $question['title']?> Question Respose:</div>

            <div class="response_container">

                <div class = "position-relative">
                    <div class = "qrcode my-5 ms-5 hide position-relative top-0 start-50 translate-middle-x p-5" id = "qrcode">
                        <?php echo $qrcode; ?>
                    </div>
                </div>

                </div>
                

                <div id = "question_stem" class="question_stem">
                    <iframe id="iframe" class = "iframe mb-4" src = "<?php echo $html_fp; ?>">

                    </iframe>

                </div>

                    <div class="question_results_graph hide" id="question_results_graph">
                        
                        <div class="results-control" id="results_control">
                            <div class="row">
                                <div class="col">
                                    <button id = "toggle_chart" type="button" class="btn btn-outline-primary">Initial/Final</button>
                                 </div>
                                 <div class="col">
                                        <button id = "refresh_chart" type="button" class="btn btn-outline-secondary position-absolute top-0 end-0 m-3">Refresh Data</button>
                                 </div>
                            </div>
                        </div>


                            <div class="result_type h3 m-5 text-primary" id="result_type">Initial Results</div>
                

                        <canvas id = "quick1_chart"></canvas>
                    </div>

              


                
            </div>
   






    </div>


    <script type="text/javascript" charset="utf-8">

        // use the window object to get the url parameters from

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const question_id = urlParams.get('question_id')
        const iid = urlParams.get('iid')
        const course = urlParams.get('course')  //? how the question is classified
        const currentcourse = urlParams.get('currentcourse')  //? the question the class was sent to
       // console.log('from q show response question_id',question_id);

       const toggle_chart = document.getElementById('toggle_chart')
       const question_title = document.getElementById('question_title')
        const iframe = document.getElementById('iframe')
        const toggle_question = document.getElementById('toggle_question')
        const show_chart = document.getElementById('show_chart')
        const question_results_graph = document.getElementById('question_results_graph');
        const result_type = document.getElementById('result_type');
        const toggle_QRCode = document.getElementById('toggle_QRCode');
        const qrcode = document.getElementById('qrcode');
        const refresh_chart = document.getElementById('refresh_chart');
        let num_parts =0;
        let data1_flag = true;
        let chart_data ='';
        let myChart ='';

        toggle_QRCode.addEventListener('click', ()=>{
            qrcode.classList.toggle("hide");
        })

        show_chart.addEventListener('click', ()=>{
            question_results_graph.classList.remove('hide');
            show_chart.classList.add('hide');
          //  fetch_data ();
        })

        toggle_question.addEventListener('click', ()=>{
            iframe.classList.toggle("hide")
        })

        refresh_chart.addEventListener('click', ()=>{
            update_chart ();
        })



  const fetch_data = () =>{
        
        fetch('getQuestionForDisplayResults.php',{method: 'POST',
          headers: {
                
                "Content-Type": "application/json",
                "Accept":"application/json, text/plain, */*"
            },
            body: JSON.stringify({iid:iid, course:course, question_id:question_id}),
        })
        .then((res1) => res1.json())
        .then((question)=>{
            console.log (question);


            
            let i_frame=iframe.contentWindow.document;  //? this should be the dom for the iframe
            console.log("i_frame",i_frame);
            console.log ('wtf')
            let child_nodes = i_frame.childNodes;
            console.log('child_nodes',child_nodes);
            let q_stem_text = child_nodes[0].querySelector('#stem_text_1').innerText;
            let q_stem_html = child_nodes[0].querySelector('#stem_text_1').innerHTML;

            console.log('i_frame.childNodes',child_nodes)
            console.log('child_nodes[0]',child_nodes[0])
            child_nodes[0].innerHTML = child_nodes[0].innerHTML.replaceAll('##','');

            let options=[];
            let options_str = [];
             num_parts = 0;
            for (let i = 0; i <=9;i++) {    //? looping thru 0 a thru j
            let n = String.fromCharCode(97 + i);
            let selector = `#question_option_${n}`
                if(child_nodes[0].querySelector(selector)){
                num_parts ++;
                options[i]= child_nodes[0].querySelector(selector).innerText;
                options[i] = options[i].replaceAll('##','');
                options_str[i] = options[i].replace(/<[^>]+>/g, '');
                }
            
            }
                console.log("options",options);
                console.log("options_str",options_str);
                console.log("num_parts",num_parts);
    
// get the chart responses just like in QuestionRepo.php
        
            console.log('question_id', question_id);
            console.log('course',course);
            console.log('currentcourse',currentcourse);
            console.log('iid',iid);

    fetch('get_quick1_question_response.php',{method: 'POST',
          headers: {
                
                "Content-Type": "application/json",
                "Accept":"application/json, text/plain, */*"
            },
            body: JSON.stringify({iid:iid, course:currentcourse, question_id:question_id}),
        })
        .then((res1) => res1.json())
        .then((responses)=>{
          console.log('response from get_quick1_question_response',responses);
         let data_for_1 = [];
         let data_for_2 = [];
         let count =0;

          for(let i=0;i<num_parts; i++){
            count =0;
            responses.forEach((response)=>{
              for (let j = 0; j < response.response_st.length; j++) {
                // console.log('response.response_st.charAt(j): ',response.response_st.charAt(j))
                if(response.response_st.charAt(j) == String.fromCharCode(i+97) && response.try_number==1){
                 count += parseInt(response.count);
                }
              }
            })
            data_for_1.push(count);
          }
          for(let i=0;i<num_parts; i++){
            count =0;
            responses.forEach((response)=>{
              
              for (let j = 0; j < response.response_st.length; j++) {
               //   console.log(response.charAt(i));
              
              if(response.response_st.charAt(j) == String.fromCharCode(i+97) && response.try_number==2){
                count += parseInt(response.count);
                }
              }
            })
            data_for_2.push(count);
          }
          console.log('data_for_1',data_for_1)
          console.log('data_for_2',data_for_2)
      

 
 
          data1_flag = true;

            //? draw the chart 

            const labels = options_str;
                
             chart_data = {
                            labels: labels,
                            datasets: [{
                            label: 'Initial Response',
                            backgroundColor: 'rgb(0, 73, 153)',
                            borderColor: 'rgb(0, 73, 153)',
                            data: data_for_1,
                            }]
                        };

                const config = {
                type: 'bar',
                
                data: chart_data,
                options: {
                        indexAxis: 'y',
                        scales: {
                        x: {
                            ticks: {
                                stepSize:1,
                                    font: {
                                        size: 20
                                    }
                            },
                            title: {
                            display: true,
                            text: 'Votes',
                            font: {
                                size: 25
                            }
                            }
                        },
                        y: {
                            ticks: {
                            font: {
                                size: 14
                            }
                            },
                            title: {
                            display: false,
                            text: 'titleY',
                            font: {
                                size: 20
                            }
                            }
                        }
                        }
                    }
                };
            
                 myChart = new Chart(
                document.getElementById('quick1_chart'),
                config
            );

            toggle_chart.addEventListener('click',()=>{
                update_chart ();
                console.log('chart_data.datasets[0].data1',chart_data.datasets[0].data)
                toggle_chart.classList.toggle('btn-outline-primary')
                        toggle_chart.classList.toggle('btn-outline-success')

                if (data1_flag){
                data1_flag = false;
                chart_data.datasets[0].data = data_for_2;
                chart_data.datasets[0].label = "Response After Discussion";
                chart_data.datasets[0].backgroundColor = 'rgb(0, 72, 0)';
                chart_data.datasets[0].borderColor = 'rgb(0, 72, 0)';
                result_type.innerText = 'Response After Discussion';
                result_type.classList.add('text-success');
                result_type.classList.remove('text-primary');


                } else {
                data1_flag = true;
                chart_data.datasets[0].data = data_for_1;
                chart_data.datasets[0].label = "Initial Response";
                chart_data.datasets[0].backgroundColor = 'rgb(0, 73, 153)';
                chart_data.datasets[0].borderColor = 'rgb(0, 73, 153)';
                result_type.innerText = 'Initial Response';
                result_type.classList.add('text-primary');
                result_type.classList.remove('text-success');
                }
                myChart.update();
                console.log('chart_data.datasets[0].data2',chart_data.datasets[0].data)


            }) 



           })  

        
 })

}  

const update_chart = () => {
    fetch('get_quick1_question_response.php',{method: 'POST',
          headers: {
                
                "Content-Type": "application/json",
                "Accept":"application/json, text/plain, */*"
            },
            body: JSON.stringify({iid:iid, course:currentcourse, question_id:question_id}),
        })
        .then((res1) => res1.json())
        .then((responses)=>{
          console.log('response from get_quick1_question_response',responses);
         let data_for_1 = [];
         let data_for_2 = [];
         let count =0;

          for(let i=0;i<num_parts; i++){
            count =0;
            responses.forEach((response)=>{
              for (let j = 0; j < response.response_st.length; j++) {
                // console.log('response.response_st.charAt(j): ',response.response_st.charAt(j))
                if(response.response_st.charAt(j) == String.fromCharCode(i+97) && response.try_number==1){
                 count += parseInt(response.count);
                }
              }
            })
            data_for_1.push(count);
          }
          for(let i=0;i<num_parts; i++){
            count =0;
            responses.forEach((response)=>{
              
              for (let j = 0; j < response.response_st.length; j++) {
               //   console.log(response.charAt(i));
              
              if(response.response_st.charAt(j) == String.fromCharCode(i+97) && response.try_number==2){
                count += parseInt(response.count);
                }
              }
            })
            data_for_2.push(count);
          }
          console.log('data_for_1',data_for_1)
          console.log('data_for_2',data_for_2)
       
        if (data1_flag){
                chart_data.datasets[0].data = data_for_1;
                } else {
                chart_data.datasets[0].data = data_for_2;
                }
                myChart.update();
                console.log('chart_data.datasets[0].data2',chart_data.datasets[0].data)
         })
      
}

fetch_data();
    </script>
    
</body>
</html>