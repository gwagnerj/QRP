 
let drawing_tool_background_src = localStorage.getItem('drawing_tool_background_src');
drawing_tool_background_src = drawing_tool_background_src.replace("%20"," ");
//drawing_tool_background_src = drawing_tool_background_src.replace("20"," ");
drawing_tool_background_src = "uploads/"+drawing_tool_background_src;
console.log("drawing_tool_background_src: " + drawing_tool_background_src);
  let drawing_tool = document.querySelector(".drawing_container");
  if(drawing_tool){
  Painterro({
      activeColor: '#00ff00', // default brush color is green
      id: "drawing_container1",
      defaultFontSize: 20,
      shadowScale: 0,
      defaultArrowLength: 50,
      toolbarPosition: 'top',
      availableFontSizes: [4, 8, 12, 16, 20, 24, 36],
      defaultLineWidth: 6,
      defaultTool: 'rect',

      //   availableEraserWidths: [4,8,12,20,30,50,100],
      //   initTextStyle:"16px 'Open Sans', sans-serif"
      //   hiddenTools: ['crop', 'line', 'arrow', 'rect', 'ellipse', 'brush', 'text', 'rotate', 'resize', 'save', 'open', 'close', 'undo', 'redo', 'zoomin', 'zoomout'],
      hiddenTools: ['save', 'close'],
      //     defaultFontSize:16,
      //     defaultEraserWidth: 10,
 //?   }).show();
 //?   }).show('uploads/p454_0_QRPropylene Control valve and dynamics_files/image001.png');
   }).show(drawing_tool_background_src);
 



  // var drawing_bar = document.getElementById('drawing_container1-bar');
  // var drawing_btn_open = drawing_bar.querySelector(".ptro-icon-open");
  // console.log ("drawing_btn_open", drawing_btn_open);

  //drawing_btn_open.click();

  var drawing_btn_close1 = document.getElementById('drawing-btn-close1');
  var drawing_btn_open1 = document.getElementById('drawing-btn-open1');

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
}
