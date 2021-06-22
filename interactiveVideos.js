var interactiveVideoJSloaded = true;
var score = 0;
var vid_current_time_ar = new Array(0, 0, 0); //? this should be updated as the video gets pause either by the program or user
var vid_current_index_ar = new Array(0, 0, 0); //? this is the progress through each video - index will need to be updated as we go
var pause_vid_ar = new Array(); //? for each video we have an array of pause times
var vid_ar = new Array();
var vid_question_ar = new Array(); //? for each video we have an array of questions elements
var quiz_items_ar = new Array(); //? for each video have an array of questions that have an array of 

var vid_num_questions_ar = new Array();
var vid_container_ar = new Array();
var vid_controls_ar = new Array();
//    var vid_question_points_ar = new Array();
//    var vid_question_point_total_ar = new Array();
var q_points_ar = new Array(); // an array of array of points for each question in each video
var total_points_ar = new Array(); // just the total points available for each video
total_points_ar[0] = 1;
total_points_ar[1] = 1;
total_points_ar[2] = 1;
var total_earned_points_ar = new Array(); //just the total earned for each array
total_earned_points_ar[0] = 0;
total_earned_points_ar[1] = 0;
total_earned_points_ar[2] = 0;

vid_ar[0] = document.getElementById("vid1");
vid_ar[1] = document.getElementById("vid2");
vid_ar[2] = document.getElementById("vid3");

vid_container_ar[0] = document.getElementById("vid1_container");
vid_container_ar[1] = document.getElementById("vid2_container");
vid_container_ar[2] = document.getElementById("vid3_container");


console.log("vid_ar: ", vid_ar);

vid_question_ar[0] = document.getElementById("vid1_question_container");
vid_question_ar[1] = document.getElementById("vid2_question_container");
vid_question_ar[2] = document.getElementById("vid3_question_container");

vid_controls_ar[0] = document.getElementById("vid1-controls");
vid_controls_ar[1] = document.getElementById("vid2-controls");
vid_controls_ar[2] = document.getElementById("vid3-controls");

console.log("vid_question_ar ", vid_question_ar);

var vid_duration_ar = new Array(); // how long each video is
var video;
var encryption = new Encryption();

//! get the elements in the videos into the array variables and add event listeners to the videos


for (let i = 0; i < 3; i++) {
    if (vid_ar[i]) {
        //    function initialize(){ 


        vid_ar[i].onloadeddata = function () {


            //       console.log(" vid_ar[i] ", vid_ar[i]);
            vid_duration_ar[i] = vid_ar[i].duration;
            console.log(" duration ", vid_duration_ar[i]);
            quiz_items_ar[i] = vid_question_ar[i].querySelectorAll(".vid-question");
            console.log("i - 2nd in function", i);
            console.log("quiz_items_ar [i]", quiz_items_ar[i]);
            //*             getPoints(i, quiz_items_ar[i])

            var p_time = new Array();

            for (var m = 0; m < quiz_items_ar[i].length; m++) { //? an array of array of array - actually nodelists

                let quiz_item = quiz_items_ar[i][m];
                console.log("quiz_item ", quiz_item)
                p_time[m] = quiz_item.querySelector(".time").innerHTML;
                p_time[m] = encryption.decrypt(p_time[m], localStorage.getItem('vid_enc_key'));


                // console.log("pause_item", p_time[m])


            }
            pause_vid_ar[i] = p_time;



            console.log("the quiz_items are ", quiz_items_ar[i]);
            var question_number = 0; // counter for displaying the right question

            vid_num_questions_ar[i] = quiz_items_ar[i].length;
            console.log(' vid_num_questions ', vid_num_questions_ar)

            vid_ar[i].removeAttribute("controls")

            vid_controls_ar[i].innerHTML += '<button class = "start-video-button fa fa-play butn "> Play </button>';
            if (i != 0) {
                vid_controls_ar[i].classList.add('display_none') // start off by only displaying the first video
                vid_container_ar[i].classList.add("low-opacity");
            }
            vid_controls_ar[i].innerHTML += '<button class = "resume-video-button display_none fa fa-play butn "> Resume Video </button>';
            vid_controls_ar[i].innerHTML += '<button class = "pause-video-button  display_none fa fa-pause butn "> Pause Video </button>';
            vid_controls_ar[i].innerHTML += '<div class = "speed-video-slider-container display_none" ><label for="speed"> Play Back Speed </label> <input type = "range" name = "speed" min = "0.6" max = "1.8" step = "0.2" class = "speed-video-slider" value = "1">  </input><output class = "output-speed" for = "speed" > 1 </output></div>';
            //   vid_controls_ar[i].innerHTML += '&nbsp; Video Progress <progress min ="0" max = "100" value = "0" class = "progress-bar"> Video Progress </progress><output class = "progress-bar-value"></output>';
//?             vid_container_ar[i].querySelector(".video-info").innerHTML += '&nbsp; Video Progress <progress min ="0" max = "100" value = "0" class = "progress-bar"> Video Progress </progress><output class = "progress-bar-value"></output>';
           var progress_bar = document.createElement("div");
           progress_bar.innerHTML = '&nbsp; Video Progress <progress min ="0" max = "100" value = "0" class = "progress-bar"> Video Progress </progress><output class = "progress-bar-value"></output>';
           vid_container_ar[i].querySelector(".video-info").prepend(progress_bar);
        //  vid_container_ar[i].querySelector(".video-info").innerHTML += '&nbsp; Video Progress <progress min ="0" max = "100" value = "0" class = "progress-bar"> Video Progress </progress><output class = "progress-bar-value"></output>';
            vid_container_ar[i].querySelector(".video-info").innerHTML += '<span class = "video-results try-again display_none"> <p> Please, Try Again: You missed some questions </p> </span>';
            vid_container_ar[i].querySelector(".video-info").innerHTML += '<span class = "video-results good display_none"> <p> Great Score </p> </span>';



            vid_container_ar[i].querySelector(".start-video-button").addEventListener("click", function (e) {
                let video_container = e.target.parentNode.parentNode;
                video = video_container.querySelector(".video");
                let video_id = video.id;
                let video_num = video_id.charAt(video_id.length - 1);
                let video_index = parseInt(video_num) - 1;
                console.log("video_index", video_index);
                //  localStorage.setItem('video_index',video_index);
                video_container.querySelector(".pause-video-button").classList.remove("display_none");

                video_container.querySelector(".try-again").classList.add("display_none");
                video_container.querySelector(".good").classList.add("display_none");
                video_container.querySelector(".video-scores").classList.add("display_none");
                video_container.querySelector(".video-scores").innerHTML = '<span class = "scores"> Scores: </span>';
                video_container.querySelector(".speed-video-slider-container").classList.remove("display_none");
                console.log("video", video);

                //                 localStorage.setItem('upcoming_q_index',0);
                this.classList.add("display_none");
                total_earned_points_ar[video_index] = 0; //! need to probably make sure the score is reinitilized for this video container
                runTillPause(video, i, 0, 0)
            })

            vid_container_ar[i].querySelector(".pause-video-button").addEventListener("click", function (e) {
                let video_container = e.target.parentNode.parentNode;
                let video = video_container.querySelector(".video");

                video_container.querySelector(".resume-video-button").classList.remove("display_none");
                console.log("video", video);
                video_container.querySelector(".speed-video-slider-container").classList.add("display_none");
                this.classList.add("display_none");
                video.pause();

            })
            vid_container_ar[i].querySelector(".resume-video-button").addEventListener("click", function (e) {
                let video_container = e.target.parentNode.parentNode;
                let video = video_container.querySelector(".video");
                video_container.querySelector(".pause-video-button").classList.remove("display_none");
                video_container.querySelector(".speed-video-slider-container").classList.remove("display_none");

                console.log("video", video);
                this.classList.add("display_none");

                video.play();

            })
            vid_container_ar[i].querySelector(".speed-video-slider").addEventListener("mouseup", function (e) {
                let video_container = e.target.parentNode.parentNode.parentNode;

                let video = video_container.querySelector(".video");
                //   video_container.querySelector(".pause-video-button").classList.remove("display_none");
                console.log("video", video);
                let output = video_container.querySelector(".output-speed");

                let speed = this.value;
                output.textContent = speed;
                video.playbackRate = speed;

            })
            //put the video duration in the progress bar
            vid_container_ar[i].querySelector(".progress-bar-value").innerHTML = "&nbsp;" + Math.trunc(vid_ar[i].duration / 60) + "m:" + Math.round(vid_ar[i].duration % 60) + "s";


        }
    }
}



function runTillPause(video, video_index, upcoming_q_index, start_time) { //? Play the selected video from the start time until the pause time of the upcoming question then call a function to display the upcoming question by sending them the upcoming question element


    localStorage.setItem('video_index', video_index);
    localStorage.setItem('upcoming_q_index', upcoming_q_index);
    video.removeAttribute("controls")
    console.log("video_index1", video_index);
    console.log("upcoming_q_index0.5", upcoming_q_index) 
    // if (pause_vid_ar.length()==0){
    //     location.reload(true);
    // }

    pause_time = pause_vid_ar[video_index][upcoming_q_index];


    let upcoming_question = quiz_items_ar[video_index][upcoming_q_index];
    console.log("upcoming_question ", upcoming_question);

    // let video = e.currentTarget;
    console.log(" video ", {
        video
    });


    //    console.log("video.currentTime:",  video.currentTime);

    var trip = 0;
    localStorage.setItem('trip', 0);


    console.log("pause_time", pause_time);



    video.currentTime = start_time;
    // video.playbackRate=2.0;
    console.log("video.currentTime:", video.currentTime);

    const video_container = video.parentNode;
    console.log("video_container ", video_container);
    const video_controls = video_container.querySelector(".vid-controls");
    const video_info = video_container.querySelector(".video-info");
    console.log("video_controls", video_controls);
    const progress_bar = video_info.querySelector(".progress-bar");
    const gray_out_vid = "gray-out-" + video.id;
    console.log("gray_out_vid", gray_out_vid);
    let gray_outs = document.getElementsByClassName(gray_out_vid);
    console.log("gray_outs", gray_outs);


    video.play();
    var trip2 = 0;
    setInterval(function () {
        let upcoming_q_index = parseInt(localStorage.getItem("upcoming_q_index"));
        let video_index = parseInt(localStorage.getItem("video_index"));
        //      console.log ("video_index1.6", video_index);
        let vid_num = video_index + 1;
        let vid_id = "vid" + vid_num;
        //       console.log ("vid_id1.6", vid_id)
        video = document.getElementById(vid_id);
        //      console.log("video1.6", video)
        let display_question_info = video.parentNode.querySelector(".display-question-info");
        let q_num = upcoming_q_index + 1;
        let v_num = video_index + 1;



        if (video.currentTime > pause_time) {
            video.pause();
            // isPlaying = false;//* maybe call a function that pauses the video changews the starttime and advances the index for the problem number
            video.classList.add("display_none");
            video.parentNode.querySelector(".pause-video-button").classList.add("display_none");
            video.parentNode.querySelector(".speed-video-slider-container").classList.add("display_none");
            video.parentNode.querySelector(".video-info").classList.add("display_none");
            let trip = parseInt(localStorage.getItem("trip"));

            if (trip == 0) {

                display_question_info.innerHTML = '<h3>  Question ' + q_num + ' of ' + vid_num_questions_ar[video_index] + '</h3>';
                displayQuestion(upcoming_q_index, video_index); //! this is where we call to diplay the function

                vid_current_index_ar[video_index]++;

                trip = 1; // kind of a bad way to only do it once - figure a better way
                localStorage.setItem('trip', 1);
            }
        } else {
            //* update the progress bar
            let percent = video.currentTime / video.duration * 100;
            progress_bar.value = percent;
            let percent_score_for_video = 0;
            if (total_points_ar[video_index] > 0) {
                percent_score_for_video = total_earned_points_ar[video_index] / total_points_ar[video_index] * 100
            }
            //      console.log("percent_score_for_video", percent_score_for_video);
            if (percent > 99) {
                video.parentNode.querySelector(".pause-video-button").classList.add("display_none");
                video.parentNode.querySelector(".start-video-button").classList.remove("display_none");
              if (percent_score_for_video<95){ video.parentNode.querySelector(".try-again").classList.remove("display_none");
}
                vid_current_index_ar[video_index] = 0; // starting over 
                upcoming_q_index = 0;

            }
            
           
            
            



            if (percent > 98 && percent_score_for_video > 95) {
                for (gray_out of gray_outs) {
                    gray_out.classList.remove("gray-out");
                    video.parentNode.querySelector(".pause-video-button").classList.add("display_none");
                    video.parentNode.querySelector(".speed-video-slider-container").classList.add("display_none");
           //        video.parentNode.querySelector(".resume-video").classList.add("display_none");
                    video.parentNode.querySelector(".good").classList.remove("display_none");
                    video.parentNode.querySelector(".start-video-button").classList.remove("display_none");
                }

                if (vid_controls_ar[v_num]) {
                    vid_controls_ar[v_num].classList.remove('display_none') //! only if there is one to remove
                    vid_container_ar[v_num].classList.remove("low-opacity");

                }


            }

        }


    }, 250);

}





function displayQuestion(q_index, video_index) {
    console.log("video_index2", video_index);
    let vid_num = parseInt(video_index) + 1;
    let vid_id = "vid" + vid_num;
    let q_num = parseInt(q_index) + 1;
    let problem_id = parseInt(localStorage.getItem("problem_id"));
    let q_id = "Q-" + vid_num + "-" + q_num + "-" + problem_id;
    console.log("q_id", q_id);

    let vid_question = document.getElementById(q_id);

    //   vid_question_container = upcoming_question.parentElement;
    //  let vid_question = document.getElementById(upcoming_question.id); //! thowing an error if you do the 2nd video : TypeError: Cannot read property 'id' of
    let vid_question_container = vid_question.parentElement;
    vid_question_container.classList.remove("hidden");
    let display_question = vid_question_container.querySelector(".display-question");
    console.log("display_question", display_question);
    display_question.innerHTML += '<h2 id = "the_question">' + encryption.decrypt(vid_question.querySelector(".text").innerText, localStorage.getItem('vid_enc_key')) + '</h2>'; //* display the question text
    let options = vid_question.querySelectorAll(".option");
    let keys = vid_question.querySelector(".key").innerText;
    keys = encryption.decrypt(keys, localStorage.getItem('vid_enc_key'));
    keys = keys.split(";")
    //           console.log("keys ", keys);
    let key_total = 0;
    let pos_key = 0;
    for (var m = 0; m < keys.length; m++) {
        if (parseInt(keys[m]) > 0) {
            pos_key = parseInt(keys[m]);
        } else {
            pos_key = 0;
        }
        key_total += pos_key;
    }
    let one_answer = false;
    for (var m = 0; m < keys.length; m++) {
        if (key_total == keys[m] || one_answer) {
            one_answer = true;
        }
    }

    if (one_answer) {
        // put in radio buttons 
        let k = 1;

        for (const option of options) {
            let key = keys[k - 1];
            //!      display_question.innerHTML += '<input type="radio" class = "response" id = "response_' + k + '" name  = "response"  value = "' + key + '"  >' + encryption.decrypt(option.innerText, localStorage.getItem('vid_enc_key')) + '</input><br><br>'
//?             display_question.innerHTML += '<input type="radio"  class = "response" id = "response_' + k + '" name  = "response"  value = "' + key + '"  >' + option.innerHTML + '</input><br><br>'
            display_question.innerHTML += '<input type="radio"  class = "response" id = "response_' + k + '" name  = "response" onclick = "showSubmit()" value = "' + key + '"  >' + option.innerHTML + '</input><br><br>'
            //   display_question.innerHTML += '<input type="radio" class = "response" id = "response_' + k + '" name  = "response"  value = "' + key + '"  >'
            //   display_question.innerHTML += encryption.decrypt(option.innerText, localStorage.getItem('vid_enc_key')) 
            //   display_question.innerHTML += '</input><br><br>'
            k++;
        }
        display_question.innerHTML += '<button class = "submit_button butn fa fa-arrow-circle-up display_none" id = "active-button option-' + k + '" onclick = "checkResponse(' + video_index + ',' + q_index + ' )" > Submit </button>';
        

    } else {
        // put in checkboxes
        display_question.innerHTML += "<p> Select All That Apply </p>"
        let k = 1;

        for (const option of options) {
            let key = keys[k - 1]
            //      display_question.innerHTML += '<input type="checkbox" class = "response" id = "response_' + k + '" value = "' + key + '" >' + encryption.decrypt(option.innerText, localStorage.getItem('vid_enc_key')) + '</input><br><br>'
            display_question.innerHTML += '<input type="checkbox" class = "response" id = "response_' + k + '"  onclick = "showSubmit()" value = "' + key + '" >' + option.innerText + '</input><br><br>'
            k++;
        }
        display_question.innerHTML += '<button class = "submit_button  butn fa fa-arrow-circle-up display_none" id = "active-button option-' + k + '" onclick = "checkResponse(' + video_index + ',' + q_index + ' )" > Submit </button>';
    }


   
    // let k = 1;


    let vid_container_id = vid_question_container.parentElement.id;
 //   let vid_container = vid_question_container.parentElement
    console.log("vid_container 6/22/21",vid_container_id);
    //display_question = vid_container.querySelector(".display-question");


//?     vid_question_container.innerHTML += '<br><button id = "resume_after_submit_vid'+video_index+'" class = "resume-video hidden butn fa fa-play" onclick = "resumeVideo(' + vid_container + ',' + video_index + ' )"> Resume Video </button>';
      display_question.innerHTML += '<br><button id = "resume_after_submit_vid'+video_index+'" class = "resume-video hidden butn fa fa-play" onclick = "resumeVideo(' + vid_container_id + ',' + video_index + ' )"> Resume Video </button>';
}

function showSubmit(){
           let submit_button = document.querySelector(".submit_button");
            submit_button.classList.remove("display_none");
        };


function checkResponse(video_index, q_index) {
    console.log("q_index", q_index);
    let q_num = parseInt(q_index)+1;
    document.querySelector(".submit_button").classList.add("hidden");
    console.log("video_index3", video_index);
    //  console.log ("number of questions in video", vid_ar[video_index].length);
    let vid_num = video_index + 1;
    let display_question_id = "display_question_vid" + vid_num;

    let selected_keys = document.querySelectorAll('input[class="response"]:checked'); // which values were checked

    n = selected_keys.length; // how many responses were made (1 with radio buttons but could be more)
    if (n==0){return;}
  
    console.log("n:", n)
    console.log("selected_keys ", selected_keys)

    let question_total = 0;

    for (var j = 0; j < n; j++) {
        console.log("selected_keys[j] ", selected_keys[j].value)
        question_total += parseInt(selected_keys[j].value); // how many points did they get with their selections
    }
    question_total = Math.max(question_total, 0); // cant get negative points for an attmepted question


    console.log(" question_total", question_total);

    let question = document.getElementById(display_question_id);
    console.log("question ", question);
    let question_container = question.parentNode;
    let video_container = question_container.parentNode;
    let video_scores = video_container.querySelector(".video-scores");
    console.log("question_container ", question_container);
    let vid_questions = question_container.querySelectorAll(".vid-question")
    console.log("vid_questions ", vid_questions);
    // console.log(' vid_num_questions ', vid_num_questions_ar);

    let total_points_available_thus_far_in_video = 0
    let total_points_earned_thus_far_in_video = 0
    let q_points_available_ar = new Array();
    let total_available_in_video = 0;

    for (m = 0; m < vid_questions.length; m++) {

        q_points_available_ar[m] = parseInt(vid_questions[m].querySelector(".key").dataset.total);
        if (isNaN(q_points_available_ar[m])) {
            q_points_available_ar[m] = 100;
        }

        if (q_points_available_ar[m] > 0) {
            total_available_in_video += parseInt(q_points_available_ar[m]);
        }
        console.log("q_points_available_ar[m]", q_points_available_ar[m]);

        if (m <= q_index) {
            total_points_available_thus_far_in_video += parseInt(q_points_available_ar[m]);
        }
    }

    q_points_ar[video_index] = q_points_available_ar;

    console.log("q_points_ar", q_points_ar);
    total_points_ar[video_index] = parseInt(total_available_in_video);
    console.log("total_points_ar", total_points_ar);
    total_earned_points_ar[video_index] += question_total;
    console.log("total_earned_points_ar", total_earned_points_ar);
    console.log("total_points_available_thus_far_in_video", total_points_available_thus_far_in_video)

    //     var q_points_ar = new Array();  // an array of array of points for each question in each video
    // var total_points_ar = new Array ();  // just the total points for each video
    // var total_earned_points_ar = new Array ();  //just the total earned for each array



    let resume_video_button = video_container.querySelector(".resume-video");
    resume_video_button.classList.remove("hidden");
    resume_video_button.classList.remove("display_none");
    // score += parseInt(question_total);
    score = total_earned_points_ar.reduce((a, b) => parseInt(a) + parseInt(b), 0); //? summing an array with reduce method


    console.log("total_points_ar", total_points_ar)
    video_scores.classList.remove("display_none");
    video_scores.innerHTML += 'q'+q_num+' ' + question_total + '%, ';

    if (question_total < 30) {

        question.classList.add("wrong");
        question.innerHTML += "<h2> Incorrect </h2>";
        question.innerHTML += "<h2> Question Percentage is: " + question_total + " </h2>";
        question.innerHTML += "<h2> Total Score is: " + score + " </h2>";
      
    } else if (question_total < 95) {


        question.classList.add("part-correct");
        question.innerHTML += "<h2> Partially Correct </h2>";
        question.innerHTML += "<h2> Question Percentage is: " + question_total + " </h2>";
        question.innerHTML += "<h2> Total Score is: " + score + " </h2>";


    } else {

        question.classList.add("correct");
        question.innerHTML += "<h2> Correct </h2>";
        question.innerHTML += "<h2> Question Percentage is: " + question_total + " </h2>";
        question.innerHTML += "<h2> Total Score is: " + score + " </h2>";
    }



}


function resumeVideo(vid_container_id, video_index) {

    console.log("vid_current_index_ar ", vid_current_index_ar)
 //   console.log("vid_container_id item zero", vid_container_id.item(0).id);
    console.log("type of vid_container_id", typeof vid_container_id);
    console.log(" vid_container_id",  vid_container_id);
    console.log("length of vid_container_id",  Object.keys(vid_container_id).length);
    if (Object.keys(vid_container_id).length>0){
        video_container = document.getElementById(vid_container_id.item(0).id);
    }else{
        video_container = document.getElementById(vid_container_id.id);
    }

    console.log("video_container", video_container);

 

    let video_question_container = video_container.querySelector(".vid_questions")
 //   let video_question_container = video_container.item(0).getElementsByClassName(".vid_questions")
    console.log("video_question_container",video_question_container)

    let diplay_question = video_question_container.querySelector(".display-question");
    let display_question_info = video_container.querySelector(".display-question-info")
    let resume_video_button = document.querySelector(".resume-video");
    resume_video_button.classList.add("hidden");
    display_question_info.classList.add("hidden");
    diplay_question.classList.remove("wrong");
    diplay_question.classList.remove("part-correct");
    diplay_question.classList.remove("correct");
    diplay_question.innerHTML = '';
    // video_question_container.classList.add("hidden");

    // video_container.querySelector(".vid_questions").classList.add("hidden");
    let video = video_container.querySelector(".video");
    video.classList.remove("display_none");
    video.parentNode.querySelector(".pause-video-button").classList.remove("display_none");
    video.parentNode.querySelector(".progress-bar").classList.remove("display_none");
    video.parentNode.querySelector(".speed-video-slider-container").classList.remove("display_none");
    video.parentNode.querySelector(".video-info").classList.remove("display_none");
    console.log("video2", video);
    video.play();

    current_index = vid_current_index_ar[video_index];
    console.log("current_index in function resume video", current_index);
    //  current_index =  parseInt(localStorage.getItem("upcoming_q_index"));
    previous_index = current_index - 1;
    start_time = pause_vid_ar[video_index][previous_index];
    //! if the next value of the pause vid array does not exits then the there are not more questions

    runTillPause(video, video_index, current_index, start_time);

}


