 
var drawing_tool_background_src = localStorage.getItem('drawing_tool_background_src');
if (drawing_tool_background_src) {
  drawing_tool_background_src = drawing_tool_background_src.replace("%20", " ");
  console.log("location", location);
  drawing_tool_background_src = location.origin + "/QRP/uploads/" + drawing_tool_background_src;
  // drawing_tool_background_src = location.origin+"uploads/"+drawing_tool_background_src;
  console.log("drawing_tool_background_src", drawing_tool_background_src);
}
var activity_id_element = document.getElementById("activity_id");
var problem_id_element = document.getElementById("problem_id");
var activity_id = 1;
if (activity_id_element) {
  activity_id = activity_id_element.value
}
var problem_id = 1;
if (problem_id_element) {
  problem_id = problem_id_element.value
}


console.log("activity_id: " + activity_id);
console.log("problem_id: " + problem_id);

//? get the saved image from the students previous session if there is anything


let filename = location.origin + '/QRP/drawing_tool_images/' + activity_id + '-drawing-1-problem-' + problem_id + '.png';
// var previous_background_4 = "lala";



// var previous_background2 = LoadImg(filename);
// console.log ("previous_background2",previous_background2);

// function LoadImg(filename) {
//   var xmlhttp;
//       xmlhttp = new XMLHttpRequest();

//   xmlhttp.onreadystatechange = function() {
//       if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {  
//           console.log ("previous_background_4-1",previous_background_4);
//         previous_background_4 = "data:image/png;base64," + xmlhttp.responseText;
//           console.log ("previous_background_4-2",previous_background_4);
//       //  return previous_background2;
//       }

//   };   

//   xmlhttp.open("GET", 'get_saved_drawing.php?LoadImg='+filename );
//   xmlhttp.send(null);
//   // return previous_background3;
// }





// $.ajax({
//   url: "get_saved_drawing.php",
//   type: "POST",
//   data: {


//   },
//   processData: false,
//   contentType: false,
//   success: function(data){
//     done(false);
//       console.log(data);
//   }
// });



// look for previous drawing if they have saved it
// console.log("drawing_tool_background_src: " + drawing_tool_background_src);

//?  let drawing_tool = document.querySelector(".drawing_container");
let drawing_tool = document.getElementById("drawing_container1");
let BC_drawing_tool = document.getElementById("drawing_BC_container1");

var ptro = Painterro({
  activeColor: '#00ff00', // default brush color is green
  id: "drawing_container1",
  defaultFontSize: 20,
  shadowScale: 0,
  defaultArrowLength: 50,
  toolbarPosition: 'top',
  availableFontSizes: [4, 8, 12, 16, 20, 24, 36],
  defaultLineWidth: 6,
  defaultTool: 'arrow',
  saveHandler: function (image, done) {
    // of course, instead of raw XHR you can use fetch, jQuery, etc
    var formData = new FormData();
    formData.append('activity_id', activity_id);
    formData.append('problem_id', problem_id);
    formData.append('image', image.asBlob('image/png'));

    $.ajax({
      url: "upload_painterro_image.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (data) {
        done(false);
        console.log(data);
      }
    });
  

    //  console.log("save was clicked")
  },
  activeColor: '#00b400' // change active color to green

  //   availableEraserWidths: [4,8,12,20,30,50,100],
  //   initTextStyle:"16px 'Open Sans', sans-serif"
  //   hiddenTools: ['crop', 'line', 'arrow', 'rect', 'ellipse', 'brush', 'text', 'rotate', 'resize', 'save', 'open', 'close', 'undo', 'redo', 'zoomin', 'zoomout'],
  //     defaultFontSize:16,
  //     defaultEraserWidth: 10,
});


var BC_ptro = Painterro({
  activeColor: '#00ff00', // default brush color is green
  id: "drawing_BC_container1",
  defaultFontSize: 20,
  shadowScale: 0,
  defaultArrowLength: 50,
  toolbarPosition: 'top',
  availableFontSizes: [4, 8, 12, 16, 20, 24, 36],
  defaultLineWidth: 6,
  defaultTool: 'arrow',
  hiddenTools: [ 'save', 'open', 'close'],

  saveHandler: function (image, done) {
    // of course, instead of raw XHR you can use fetch, jQuery, etc
    var formData = new FormData();
    formData.append('activity_id', activity_id);
    formData.append('problem_id', problem_id);
    formData.append('image', image.asBlob('image/png'));

    $.ajax({
      url: "upload_painterro_image.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (data) {
        done(false);
        console.log(data);
      }
    });


    //  console.log("save was clicked")
  },
  activeColor: '#00b400' // change active color to green

  //   availableEraserWidths: [4,8,12,20,30,50,100],
  //   initTextStyle:"16px 'Open Sans', sans-serif"
  //   hiddenTools: ['crop', 'line', 'arrow', 'rect', 'ellipse', 'brush', 'text', 'rotate', 'resize', 'save', 'open', 'close', 'undo', 'redo', 'zoomin', 'zoomout'],
 //    hiddenTools: ['crop', 'line', 'arrow', 'rect', 'ellipse', 'brush', 'text', 'rotate', 'resize', 'save', 'open', 'close', 'undo', 'redo', 'zoomin', 'zoomout'],
  //     defaultFontSize:16,
  //     defaultEraserWidth: 10,
});

//  console.log ("previous_background_4-3",previous_background_4);

function ImageExist(url) {  
  if (url) {
    var req = new XMLHttpRequest();
    req.open('HEAD', url, false); // this false (synchrounous has been depreciated so I may need to change it to true but then it is asynchrounous and may need promise..)
    req.send();
    return req.status == 200;
  } else {
    return false;
  }
}

console.log("imageexists background", ImageExist(drawing_tool_background_src))
if (ImageExist(filename)) {
  ptro.show(filename);
} else if (ImageExist(drawing_tool_background_src)) {
  ptro.show(drawing_tool_background_src);
  // BC_ptro.show(drawing_tool_background_src);
} else {
  ptro.show();
  // BC_ptro.show();
}

if (ptro.show(filename) != false) {
  ptro.show(filename)
} else {
  ptro.show(drawing_tool_background_src)
};

  BC_ptro.show();


// if (BC_ptro.show(filename) != false) {
//   BC_ptro.show(filename)
// } else {
//   BC_ptro.show(drawing_tool_background_src)
// };


//  ptro.show(drawing_tool_background_src);
//  ptro.show(filename);
//drawing_btn_open.click();

var drawing_btn_close1 = document.getElementById('drawing-btn-close1');
var drawing_btn_open1 = document.getElementById('drawing-btn-open1');
var BC_drawing_btn_close1 = document.getElementById('drawing-BC-btn-close1');
var BC_drawing_btn_open1 = document.getElementById('drawing-BC-btn-open1');

drawing_btn_close1.addEventListener('click', function () {
  drawing_btn_open1.classList.remove("display_none");
  drawing_btn_close1.classList.add("display_none");
  drawing_container1.classList.add("display_none");

})

drawing_btn_open1.addEventListener('click', function () {
  drawing_btn_open1.classList.add("display_none");
  drawing_btn_close1.classList.remove("display_none");
  drawing_container1.classList.remove("display_none");

})
BC_drawing_btn_close1.addEventListener('click', function () {
  BC_drawing_btn_open1.classList.remove("display_none");
  BC_drawing_btn_close1.classList.add("display_none");
  BC_drawing_container1.classList.add("display_none");

})

BC_drawing_btn_open1.addEventListener('click', function () {
  BC_drawing_btn_open1.classList.add("display_none");
  BC_drawing_btn_close1.classList.remove("display_none");
  BC_drawing_container1.classList.remove("display_none");

})
//}
