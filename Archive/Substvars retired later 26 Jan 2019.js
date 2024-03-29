
var problem_id = localStorage.getItem('problem_id');
var stu_name = localStorage.getItem('stu_name');
var name_length = stu_name.length;
if(name_length<1){
	stu_name = "__________________________";

}
var index = localStorage.getItem('index');
var title = localStorage.getItem('title');
var static_flag = localStorage.getItem('static_flag');

/* 			localStorage.setItem('contrib_first',contrib_first);
			localStorage.setItem('contrib_last',contrib_last);
			localStorage.setItem('contrib_university',contrib_university);
			localStorage.setItem('nm_author',arr.nm_author);
			localStorage.setItem('specif_ref',arr.specif_ref);
 */
var contrib_first = localStorage.getItem('contrib_first');
var contrib_last = localStorage.getItem('contrib_last');
var contrib_university = localStorage.getItem('contrib_university');
var nm_author = localStorage.getItem('nm_author');
var specif_ref = localStorage.getItem('specif_ref');



var nvar1 = localStorage.getItem('nv_1'); 

var var1 = localStorage.getItem(nvar1);


nvar1 = "##"+nvar1+",.+?##";
var oNvar1 = new RegExp(nvar1,"g");



var nvar2 = localStorage.getItem('nv_2'); 
var var2 = localStorage.getItem(nvar2);
nvar2 = "##"+nvar2+",.+?##";
var oNvar2 = new RegExp(nvar2,"g");

var nvar3 = localStorage.getItem('nv_3'); 
var var3 = localStorage.getItem(nvar3);
nvar3 = "##"+nvar3+",.+?##";
var oNvar3 = new RegExp(nvar3,"g");

var nvar4 = localStorage.getItem('nv_4'); 
var var4 = localStorage.getItem(nvar4);
nvar4 = "##"+nvar4+",.+?##";
var oNvar4 = new RegExp(nvar4,"g");

var nvar5 = localStorage.getItem('nv_5'); 
var var5 = localStorage.getItem(nvar5);
nvar5 = "##"+nvar5+",.+?##";
var oNvar5 = new RegExp(nvar5,"g");

var nvar6 = localStorage.getItem('nv_6'); 
var var6 = localStorage.getItem(nvar6);
nvar6 = "##"+nvar6+",.+?##";
var oNvar6 = new RegExp(nvar6,"g");

var nvar6 = localStorage.getItem('nv_6'); 
var var6 = localStorage.getItem(nvar6);
nvar6 = "##"+nvar6+",.+?##";
var oNvar6 = new RegExp(nvar6,"g");

var nvar7 = localStorage.getItem('nv_7'); 
var var7 = localStorage.getItem(nvar7);
nvar7 = "##"+nvar7+",.+?##";
var oNvar7 = new RegExp(nvar7,"g");

var nvar8 = localStorage.getItem('nv_8'); 
var var8 = localStorage.getItem(nvar8);
nvar8 = "##"+nvar8+",.+?##";
var oNvar8 = new RegExp(nvar8,"g");

var nvar9 = localStorage.getItem('nv_9'); 
var var9 = localStorage.getItem(nvar9);
nvar9 = "##"+nvar9+",.+?##";
var oNvar9 = new RegExp(nvar9,"g");

var nvar10 = localStorage.getItem('nv_10'); 
var var10 = localStorage.getItem(nvar10);
nvar10 = "##"+nvar10+",.+?##";
var oNvar10 = new RegExp(nvar10,"g");

var nvar11 = localStorage.getItem('nv_11'); 
var var11 = localStorage.getItem(nvar11);
nvar11 = "##"+nvar11+",.+?##";
var oNvar11 = new RegExp(nvar11,"g");

var nvar12 = localStorage.getItem('nv_12'); 
var var12 = localStorage.getItem(nvar12);
nvar12 = "##"+nvar12+",.+?##";
var oNvar12 = new RegExp(nvar12,"g");

var nvar13 = localStorage.getItem('nv_13'); 
var var13 = localStorage.getItem(nvar13);
nvar13 = "##"+nvar13+",.+?##";
var oNvar13 = new RegExp(nvar13,"g");

var nvar14 = localStorage.getItem('nv_14'); 
var var14 = localStorage.getItem(nvar14);
nvar14 = "##"+nvar14+",.+?##";
var oNvar14 = new RegExp(nvar14,"g");



// read in the basecase values for the variables
var x = "bc_"+localStorage.getItem('nv_1');
var bc_var1 = localStorage.getItem(x);

x = "bc_"+localStorage.getItem('nv_2');
var bc_var2 = localStorage.getItem(x);
x = "bc_"+localStorage.getItem('nv_3');
var bc_var3 = localStorage.getItem(x);
x = "bc_"+localStorage.getItem('nv_4');
var bc_var4 = localStorage.getItem(x);
x = "bc_"+localStorage.getItem('nv_5');
var bc_var5 = localStorage.getItem(x);
x = "bc_"+localStorage.getItem('nv_6');
var bc_var6 = localStorage.getItem(x);
x = "bc_"+localStorage.getItem('nv_7');
var bc_var7 = localStorage.getItem(x);
x = "bc_"+localStorage.getItem('nv_8');
var bc_var8 = localStorage.getItem(x);
x = "bc_"+localStorage.getItem('nv_9');
var bc_var9 = localStorage.getItem(x);
x = "bc_"+localStorage.getItem('nv_10');
var bc_var10 = localStorage.getItem(x);
x = "bc_"+localStorage.getItem('nv_11');
var bc_var11 = localStorage.getItem(x);
x = "bc_"+localStorage.getItem('nv_12');
var bc_var12 = localStorage.getItem(x);
x = "bc_"+localStorage.getItem('nv_13');
var bc_var13 = localStorage.getItem(x);
x = "bc_"+localStorage.getItem('nv_14');
var bc_var14 = localStorage.getItem(x);



// setting up the beginning markup with a REgular expression the g performs a global match - find all matches without stopping
// this should find all of the strings like v== and u== and the sEndMU finds the strings like ==v and ==U

var sBeginMU = "[k-zK-Z]\=\=";
var oBeginMU = new RegExp(sBeginMU,"g");

var sEndMU = "\=\=[k-zK-Z]";
var oEndMU = new RegExp(sEndMU,"g");


$(document).ready(function(){
	
	  var Head_txt1 = $("<p></p>").text("Name: " + stu_name + "\xa0\xa0"+"Problem: "+problem_id+"\xa0\xa0"+"PIN: " + index +"\xa0\xa0 ");
	  
	  var auth_field = (nm_author.length > 1 ? " by "+nm_author : "");
	  var ref_field = (specif_ref.length > 1 ? " similar to\xa0"+specif_ref : "");
	  var Head_txt3 = $("<p></p>").text(" Score: ______  rtn Code _____________-______ \xa0\xa0  Contributed by\xa0"+contrib_first+"\xa0"+contrib_last+" from\xa0"+contrib_university+ref_field+ auth_field);
	  
	 // var Head_txt4 = $("<p></p>").text("Contributed by:\xa0"+contrib_first+contrib_last+"\xa0 from:\xa0"+contrib_university+ref_field+ auth_field );

	// $('p:first').before(Head_txt4);
	 $('p:first').before(Head_txt1,Head_txt3,'<hr>');
	 
	  
	  $('p:first').before('\xa0\xa0<img border=0 width=50 height=50 id="McKetta_head" src="QRMcKetta.png"><hr>');

	//put real tags in the document to manipulate
	 
		$(function(){
			
			// put a div in that starts from the start of the document and includes the buttons and heder stuff
		
				$(".WordSection1").prepend('<div id="box0_start">');
				 $( "p" ).each(function( index ) {
			   var current_content =  $(this).text();
			 // put the end of the div before the closing markup tag 
				  if( current_content.indexOf("v==") !=-1) {
				 $(this).closest('p').before('<div id="box0_end">');
			  }
			});
				$("#box0_start").nextUntil("#box0_end").wrapAll("<div id='Header_stuff'></div>");
			
			// put the button in the document	
				$('p:first').before('show/hide: <button id="directionsbutton">directions</button> <button id="pblmbutton">pblm statement</button>  <button id="basecasebutton">base-case</button> <button id="reflectionbutton">Reflections</button>') ;
				$('p:first').before('  <button id="refl" style = "height:17px">Reflect</button> <button id="expl" style = "height:17px">Explore</button>  <button id="conn" style = "height:17px">Connect</button> <button id="soci" style = "height:17px">Society</button>') ;
				
				$('#refl').hide();
				$('#expl').hide();
				$('#conn').hide();
				$('#soci').hide();
				
				
			// Search thru all of the document looking for the markups and put divs for those
			 $( "p" ).each(function( index ) {
			   var current_content =  $(this).text();
			   // put in a div element at the start of the markup
			  if( current_content.indexOf("v==") !=-1) {
				  $(this).closest('p').before('<div id="box1-start">');
			  }
			 // put the end of the div after the closing markup tag 
				  if( current_content.indexOf("==v") !=-1) {
				  
				 $(this).closest('p').after('<div id="box1-end">');
			  }
			});
			// put all the content between the tags in a div

			$("#box1-start").nextUntil("#box1-end").wrapAll("<div id='directions'></div>");
			

			// Search thru all of the document looking for the markups and put divs for those
			 $( "p" ).each(function( index ) {
			   var current_content =  $(this).text();
			   // put in a div element at the start of the markup
			  if( current_content.indexOf("t==") !=-1) {
				  $(this).closest('p').before('<div id="box2-start">');
			  }
			 // put the end of the div after the closing markup tag 
				  if( current_content.indexOf("==t") !=-1) {
				  
				 $(this).closest('p').after('<div id="box2-end">');
			  }
			});
			// put all the content between the tags in a div

			$("#box2-start").nextUntil("#box2-end").wrapAll("<div id='problem'></div>");


			// Search thru all of the document looking for the markups and put divs for those
			 $( "p" ).each(function( index ) {
			   var current_content =  $(this).text();
			   // put in a div element at the start of the markup
			  if( current_content.indexOf("x==") !=-1) {
				  $(this).closest('p').before('<div id="box3-start">');
			  }
			 // put the end of the div after the closing markup tag 
				  if( current_content.indexOf("==u") !=-1) {
				  
				 $(this).closest('p').after('<div id="box3-end">');
			  }
			});
			// put all the content between the tags in a div

			$("#box3-start").nextUntil("#box3-end").wrapAll("<div id='old_basecase'></div>");
			 $("#old_basecase").hide();


			// Search thru all of the document looking for the markups and put divs for those
			 $( "p" ).each(function( index ) {
			   var current_content =  $(this).text();
			   // put in a div element at the start of the markup
			  if( current_content.indexOf("w==") !=-1) {
				  $(this).closest('p').before('<div id="box4-start">');
			  }
			 // put the end of the div after the closing markup tag 
				  if( current_content.indexOf("==w") !=-1) {
				  
				 $(this).closest('p').after('<div id="box4-end">');
			  }
			});
			// put all the content between the tags in a div

			$("#box4-start").nextUntil("#box4-end").wrapAll("<div id='reflections'></div>");

			// Search thru the reflections and put a div for the first one i) Reflect
			 $( "#Reflections p" ).each(function( index ) {
			   var current_content =  $(this).text();
			   // put in a div element at the start of the markup
			  if( current_content.indexOf("i) Reflect") !=-1) {
				  $(this).closest('p').before('<div id="box5-start">');
			  }
			 // put the end of the div after the closing markup tag 
				  if( current_content.indexOf("ii) Explore") !=-1) {
				  
				 $(this).closest('p').before('<div id="box5-end">');
				 
				 // try adding a text box to in this before box5-end
				 
			  }
			});
			
			// put all the content between the tags in a div

			$("#box5-start").nextUntil("#box5-end").wrapAll("<div id='reflect'></div>");

			// Search thru the reflections and put a div for the first one ii) Explore
			 $( "#Reflections p" ).each(function( index ) {
			   var current_content =  $(this).text();
			   // put in a div element at the start of the markup
			  if( current_content.indexOf("ii) Explore") !=-1) {
				  $(this).closest('p').before('<div id="box6-start">');
			  }
			 // put the end of the div after the closing markup tag 
				  if( current_content.indexOf("iii) Connect") !=-1) {
				  
				 $(this).closest('p').before('<div id="box6-end">');
			  }
			});
			// put all the content between the tags in a div

			$("#box6-start").nextUntil("#box6-end").wrapAll("<div id='explor'></div>");

			// Search thru the reflections and put a div for the first one iii) Connect
			 $( "#Reflections p" ).each(function( index ) {
			   var current_content =  $(this).text();
			  
			  // put in a div element at the start of the markup
			  if( current_content.indexOf("iii) Connect") !=-1) {
				  $(this).closest('p').before('<div id="box7-start">');
			  }
			 // put the end of the div after the closing markup tag 
				  if( current_content.indexOf("iv) S") !=-1) {
				  
				 $(this).closest('p').before('<div id="box7-end">');
			  }
			});
			// put all the content between the tags in a div

			$("#box7-start").nextUntil("#box7-end").wrapAll("<div id='connec'></div>");


			// Search thru the reflections and put a div for the first one iv) S (left it as this so it could be changed to just society instead of safety and society
			 $( "#Reflections p" ).each(function( index ) {
			   var current_content =  $(this).text();
			   // put in a div element at the start of the markup
			  if( current_content.indexOf("iv) S") !=-1) {
				  $(this).closest('p').before('<div id="box8-start">');
			  }
			
			// put the end of the div after the closing markup tag 
			 
			// $("p:last").before('<div id="box8-end">');


			 if( current_content.indexOf("iv) S") !=-1) {
				  
				 $(this).closest('p').after('<div id="box8-end">');
			 }
			 
				 
			});
			// put all the content between the tags in a div

			$("#box8-start").nextUntil("#box4-end").wrapAll("<div id='societ'></div>");






			  // Cloning the problem staement to get the basecase

			  var problem_st = document.getElementById('problem');
			var clone = problem_st.cloneNode(true);
			clone.id = "basecase";

			document.body.appendChild(clone);
				
			   $('#basecase').prepend('<h2>Base-Case</h2>');
				$('#reflections').prepend('<h2>Reflections</h2>');

			// build arrays for the values and names
			var name_array=[];
			var value_array=[];

			for (i=0;i<14;i++){
				var j = i+1;
				var nm_elem = 'var'+j
				 name_array = name_array.concat('var'+j);
				 value_array = value_array.concat(eval(nm_elem));
			 
			}

			// Search thru all of the paragraphs in the problem statement or reflections looking for the image markups 
			var numPara_tot = document.getElementsByTagName('p').length
			var numPara_basecase = document.getElementById('basecase').getElementsByTagName('p').length
			// console.log(numPara_tot);
			// console.log(numPara_basecase);
			var numPara = numPara_tot - numPara_basecase;
			// console.log(numPara);

			for (i=0;i<numPara;i++){
				// if the caption exists get the title of it
				var xxx = document.querySelectorAll('p')[i].outerHTML;
				
				var yyy = xxx.indexOf('MsoCaption');
				
				if (yyy!= -1){
							var capNum = xxx.indexOf("</p>");
						
							var figNum = xxx.slice(capNum-2,capNum).trim();
							

							var indexStart = xxx.indexOf("##");
							var indexEnd = xxx.indexOf(",img",indexStart);
							var cap_var_title = xxx.slice(indexStart+2,indexEnd)+'_'+figNum;
							var found = false;
							
							// for each caption test it against the variable values if one is the same delete the caption but if there is not one delete the caption
							// and the paragraph above it which should contain the figure
							
							for (j=0;j<14;j++){
								if(cap_var_title == value_array[j]){
									found = true
									document.querySelectorAll('p')[i].hidden = true;
								}
							}	
								if (found){
									
									found = false;
								} else	{
									//set the caption to hidden and the previous paragraph to hidden
								//	console.log('im here');
									
									document.querySelectorAll('p')[i].hidden = true;
									document.querySelectorAll('p')[i-1].hidden = true;
									
								}
				}
				
			}	

			// replace the url from whatever is there to qrproblems.org/QRP/QRChecker.php?problem_id=problem_id&=index

			var newHref = 'https://qrproblems.org/QRP/QRChecker.php'+'?problem_id='+problem_id+'&dex_num='+index;
			// console.log (newHref);
			var oldHref = "[href="+$('#directions').find('a:first').attr('href')+"]";
			//var oldHref = $('a').attr('href');
			// console.log (newHref);


			//selects the first anchor tag in the directions div and replaces it with the particular url
			$('#directions a').prop('href', newHref);
			$('#directions a').prop('target', '_blank');
			// $("a".oldHref).prop('href', newHref);

			// genrate an QRcode dynamically using the newHref  This had to be pretty large before the QR reader can read it


			/* let qrcode = new QRCode("output", {
				text: newHref,
				width:100,
				height:100,
				colorDark:"#990000",
				colorLight:"#ffffff",
				corectLevel: QRCode.CorrectLevel.M
			}); */

			//qrcode.clear();
			//qrcode.makeCode(newHref);

			// build arrays for the basecase values 

			var value_array_bc=[];

			for (i=0;i<14;i++){
				var j = i+1;
				var nm_elem = 'bc_var'+j
				 
				 value_array_bc = value_array_bc.concat(eval(nm_elem));
			 
			}

			// Search thru all of the paragraphs in the basecase  looking for the image markups 

			for (i=numPara+1;i<numPara_tot;i++){
				// if the caption exists get the title of it
				var xxx = document.querySelectorAll('p')[i].outerHTML;
				
				var yyy = xxx.indexOf('MsoCaption');
				
				if (yyy!= -1){
							var capNum = xxx.indexOf("</p>");
						
							var figNum = xxx.slice(capNum-2,capNum).trim();
							

							var indexStart = xxx.indexOf("##");
							var indexEnd = xxx.indexOf(",img",indexStart);
							var cap_var_title = xxx.slice(indexStart+2,indexEnd)+'_'+figNum;
							var found = false;
							
							// for each caption test it against the variable values if one is the same delete the caption but if there is not one delete the caption
							// and the paragraph above it which should contain the figure
							
							for (j=0;j<14;j++){
								if(cap_var_title == value_array_bc[j]){
									found = true
									document.querySelectorAll('p')[i].hidden = true;
								}
							}	
								if (found){
									
									found = false;
								} else	{
									//set the caption to hidden and the previous paragraph to hidden
								//	console.log('im here');
									
									document.querySelectorAll('p')[i].hidden = true;
									document.querySelectorAll('p')[i-1].hidden = true;
									
								}
				}
				
			}	


			  
			   // replace the variables in the problem statement
			   
			  $('#problem').html(function(){
			   return $(this).html().replace(oNvar1,var1 ); });
			  $('#problem').html(function(){
			   return $(this).html().replace(oNvar2,var2 ); });
			   $('#problem').html(function(){
			   return $(this).html().replace(oNvar3,var3 ); });
			   $('#problem').html(function(){
			   return $(this).html().replace(oNvar4,var4 ); });
			   $('#problem').html(function(){
			   return $(this).html().replace(oNvar5,var5 ); });
				$('#problem').html(function(){
			   return $(this).html().replace(oNvar6,var6 ); });
				$('#problem').html(function(){
			   return $(this).html().replace(oNvar7,var7 ); });
				$('#problem').html(function(){
			   return $(this).html().replace(oNvar8,var8 ); });
				$('#problem').html(function(){
			   return $(this).html().replace(oNvar9,var9 ); });
				$('#problem').html(function(){
			   return $(this).html().replace(oNvar10,var10 ); });
				$('#problem').html(function(){
			   return $(this).html().replace(oNvar11,var11 ); });
				$('#problem').html(function(){
			   return $(this).html().replace(oNvar12,var12 ); });
				$('#problem').html(function(){
			   return $(this).html().replace(oNvar13,var13 ); });
				$('#problem').html(function(){
			   return $(this).html().replace(oNvar14,var14 ); });
			   
			   
			   // substitute the values into the relections if there is any in that section
			  $('#reflections').html(function(){
			   return $(this).html().replace(oNvar1,var1 ); });
			  $('#reflections').html(function(){
			   return $(this).html().replace(oNvar2,var2 ); });
			   $('#reflections').html(function(){
			   return $(this).html().replace(oNvar3,var3 ); });
			   $('#reflections').html(function(){
			   return $(this).html().replace(oNvar4,var4 ); });
			   $('#reflections').html(function(){
			   return $(this).html().replace(oNvar5,var5 ); });
				$('#reflections').html(function(){
			   return $(this).html().replace(oNvar6,var6 ); });
				$('#reflections').html(function(){
			   return $(this).html().replace(oNvar7,var7 ); });
				$('#reflections').html(function(){
			   return $(this).html().replace(oNvar8,var8 ); });
				$('#reflections').html(function(){
			   return $(this).html().replace(oNvar9,var9 ); });
				$('#reflections').html(function(){
			   return $(this).html().replace(oNvar10,var10 ); });
				$('#reflections').html(function(){
			   return $(this).html().replace(oNvar11,var11 ); });
				$('#reflections').html(function(){
			   return $(this).html().replace(oNvar12,var12 ); });
				$('#reflections').html(function(){
			   return $(this).html().replace(oNvar13,var13 ); });
				$('#reflections').html(function(){
			   return $(this).html().replace(oNvar14,var14 ); });

			  // fill in the student name and index number 
			   
			 // substitute in the basecase
			 
			  $('#basecase').html(function(){
			   return $(this).html().replace(oNvar1,bc_var1 ); });
			  $('#basecase').html(function(){
			   return $(this).html().replace(oNvar2,bc_var2 ); });
			   $('#basecase').html(function(){
			   return $(this).html().replace(oNvar3,bc_var3 ); });
			   $('#basecase').html(function(){
			   return $(this).html().replace(oNvar4,bc_var4 ); });
			   $('#basecase').html(function(){
			   return $(this).html().replace(oNvar5,bc_var5 ); });
				$('#basecase').html(function(){
			   return $(this).html().replace(oNvar6,bc_var6 ); });
				$('#basecase').html(function(){
			   return $(this).html().replace(oNvar7,bc_var7 ); });
				$('#basecase').html(function(){
			   return $(this).html().replace(oNvar8,bc_var8 ); });
				$('#basecase').html(function(){
			   return $(this).html().replace(oNvar9,bc_var9 ); });
				$('#basecase').html(function(){
			   return $(this).html().replace(oNvar10,bc_var10 ); });
				$('#basecase').html(function(){
			   return $(this).html().replace(oNvar11,bc_var11 ); });
				$('#basecase').html(function(){
			   return $(this).html().replace(oNvar12,bc_var12 ); });
				$('#basecase').html(function(){
			   return $(this).html().replace(oNvar13,bc_var13 ); });
				$('#basecase').html(function(){
			   return $(this).html().replace(oNvar14,bc_var14 ); });
			 
			 
			 
			  // now get rid of the markup
			  
			   $("p").html(function(){
			   return $(this).html().replace(oBeginMU,"" ); }); 
				$("p").html(function(){
			   return $(this).html().replace("p==","" ); });
				$("p").html(function(){
			   return $(this).html().replace("P==","" ); });
				$("p").html(function(){
			   return $(this).html().replace("==p",")" ); });
			  $("p").html(function(){
			   return $(this).html().replace("==P",")" ); });
			  $("p").html(function(){
			  return $(this).html().replace(oEndMU,"" ); });






			// this is to change the color of the buttons depending on the state
			var white1 = false;
			var bgcolor1;
			var white2 = false;
			var bgcolor2;
			var white3 = true;
			var bgcolor3;
			var white4 = true;
			var bgcolor4;
			var white5 = true;
			var bgcolor5;
			var white6 = true;
			var bgcolor6;
			var white7 = true;
			var bgcolor7;
			var white8 = true;
			var bgcolor8;

			// turn don't display basecase or reflections 

			  bgcolor3 = $('#basecasebutton').css('backgroundColor');
			   $("#basecase").toggle();
				$('#basecasebutton').css("background-color", "lightgray");
				
			   bgcolor4 = $('#reflectionbutton').css('backgroundColor'); 
			   $("#reflections").toggle();
			  $('#reflectionbutton').css("background-color", "lightgray");
			  
			  

			   // toggle the content between show and hide on click of the button
				$('#directionsbutton').click(function(e){
					 e.preventDefault();
					 // console.log("hello1");
					 if (white1 = !white1) {
						bgcolor1 = $(this).css('backgroundColor');
						$(this).css("background-color", "lightgray");
					} else {
						$(this).css("background-color", bgcolor1);
					}
					 
					$("#directions").toggle();
				 });
				 
			   $('#pblmbutton').click(function(e){
					 e.preventDefault();
					  if (white2 = !white2) {
						bgcolor2 = $(this).css('backgroundColor');
						$(this).css("background-color", "lightgray");
					} else {
						$(this).css("background-color", bgcolor2);
					}
					 
					$("#problem").toggle();
				 });
			 
			  $('#basecasebutton').click(function(e){
					 e.preventDefault();
					  if (white3 = !white3) {
						bgcolor3 = $(this).css('backgroundColor');
						$(this).css("background-color", "lightgray");
					} else {
						$(this).css("background-color", bgcolor3);
					}
					 
					
					$("#basecase").toggle();
					  $("#basecase").css("background-color", "azure");
					   $("#basecase").css("border-style", "solid");
					  
					
				 });
				 
				 
				 // make the text boxes with the buttons for each of the 4 reflection areas
								$('#box5-end').addClass('nex-text-div')
								.html( $('<textarea id= "reflect_box" placeholder = "Type or paste reflect response here - text only.  This system does not save your response - print with ctrl P (or equivalent) when finished"  rows="1" cols = "150"/></textarea>').addClass( 'reflect_class' ) ) 
								.append( $('<button/>').addClass( 'remove' ).text( 'Remove' ) )
								.append( $('<button/>').addClass( 'display' ).text( 'reflect' ) )
								.insertAfter( '#box5-end' );
								$('.display').hide();
								
								
								 $('#box6-end').addClass('nex-text-div2')
								.html( $('<textarea id= "explore_box" placeholder ="Explore"  rows="1" cols = "150"/></textarea>').addClass( 'explore_class' ) ) 
								.append( $('<button/>').addClass( 'remove2' ).text( 'Remove' ) )
								.append( $('<button/>').addClass( 'display2' ).text( 'explore' ) )
								.insertAfter( '#box6-end' );
								$('.display2').hide();
								
								 $('#box7-end').addClass('nex-text-div3')
								.html( $('<textarea id= "connect_box" placeholder ="Connect" rows="1" cols = "150"/></textarea>').addClass( 'connect_class' ) ) 
								.append( $('<button/>').addClass( 'remove3' ).text( 'Remove' ) )
								.append( $('<button/>').addClass( 'display3' ).text( 'connect' ) )
								.insertAfter( '#box7-end' );
								$('.display3').hide();
								
								 $('#box4-end').addClass('nex-text-div4')
								.html( $('<textarea id= "society_box" placeholder ="Safety & Society" rows="1" cols = "150"/></textarea>').addClass( 'society_class' ) ) 
								.append( $('<button/>').addClass( 'remove4' ).text( 'Remove' ) )
								.append( $('<button/>').addClass( 'display4' ).text( 'society' ) )
								.insertAfter( '#box8-end' );
								$('.display4').hide();
								
				 
			   $('#reflectionbutton').click(function(e){
					 e.preventDefault();
					   if (white4 = ! white4) {
							bgcolor4 = $(this).css('backgroundColor');
							$(this).css("background-color", "lightgray");
							
						// hide the sub buttons	
							$('#refl').hide();
							$('.nex-text-div').hide();
							
							$('#expl').hide();
							$('.nex-text-div2').hide();
							$('#conn').hide();
							$('.nex-text-div3').hide();
							$('#soci').hide();
							$('.nex-text-div4').hide();

					} else {
							$(this).css("background-color", bgcolor4);
							$('#refl').show();
							// trying to add a text box after the reflections
							//$('#reflect').show();
							
							$('.nex-text-div').show();
							$('#expl').show();
							$('.nex-text-div2').show();
							$('#conn').show();
							$('.nex-text-div3').show();
							$('#soci').show();
							$('.nex-text-div4').show();
					}
					$("#reflections").toggle();
					  $("#reflections").css("background-color", "ivory");
					   $("#reflections").css("border-style", "solid");
				 });
				 
				 
			$('#refl').click(function(e){
					 e.preventDefault();
					  if (white5 = !white5) {
						 $(this).css("background-color", bgcolor5);
					
					} else {
						bgcolor5 = $(this).css('backgroundColor');
						$(this).css("background-color", "lightgray");
					}
					$("#reflect").toggle();
					$('.nex-text-div').toggle();
				 });
				 
				$('#expl').click(function(e){
					 e.preventDefault();
					  if (white6 = !white6) {
						 $(this).css("background-color", bgcolor6);
					
					} else {
						bgcolor6 = $(this).css('backgroundColor');
						$(this).css("background-color", "lightgray");
					}
					$("#explor").toggle();
					$('.nex-text-div2').toggle();
				 });

			$('#conn').click(function(e){
					 e.preventDefault();
					  if (white7 = !white7) {
						 $(this).css("background-color", bgcolor7);
					
					} else {
						bgcolor7 = $(this).css('backgroundColor');
						$(this).css("background-color", "lightgray");
					}
					$("#connec").toggle();
					$('.nex-text-div3').toggle();
				 });
			$('#soci').click(function(e){
					 e.preventDefault();
					  if (white8 = !white8) {
						 $(this).css("background-color", bgcolor7);
					
					} else {
						bgcolor8 = $(this).css('backgroundColor');
						$(this).css("background-color", "lightgray");
					}
					$("#societ").toggle();
					$('.nex-text-div4').toggle();
				 });

// toggle the text box buttons
			$(document).on('click', 'button.remove', function( e ){		
				e.preventDefault();
				
				$('.reflect_class').hide();
				$('.remove').hide();
				$('.display').show();
			});
			
			$(document).on('click', 'button.display', function( e ){		
				e.preventDefault();
				
				$('.reflect_class').show();
				$('.remove').show();
				$('.display').hide();
			});
			
			// toggle the text box buttons on the explore
			$(document).on('click', 'button.remove2', function( e ){		
				e.preventDefault();
				
				$('.explore_class').hide();
				$('.remove2').hide();
				$('.display2').show();
			});
			
			$(document).on('click', 'button.display2', function( e ){		
				e.preventDefault();
				
				$('.explore_class').show();
				$('.remove2').show();
				$('.display2').hide();
			});
			
			// toggle the text box buttons on the connect
			$(document).on('click', 'button.remove3', function( e ){		
				e.preventDefault();
				
				$('.connect_class').hide();
				$('.remove3').hide();
				$('.display3').show();
			});
			
			$(document).on('click', 'button.display3', function( e ){		
				e.preventDefault();
				
				$('.connect_class').show();
				$('.remove3').show();
				$('.display3').hide();
			});
			
				// toggle the text box buttons on the society
			$(document).on('click', 'button.remove4', function( e ){		
				e.preventDefault();
				
				$('.society_class').hide();
				$('.remove4').hide();
				$('.display4').show();
			});
			
			$(document).on('click', 'button.display4', function( e ){		
				e.preventDefault();
				
				$('.society_class').show();
				$('.remove4').show();
				$('.display4').hide();
			});
			
			
			
			//resize the textbox
			
			 $('#reflect_box').on('input propertychange keyup change', function(){ this.rows = this.value.match(/\n/g).length + 1 });
			$('#explore_box').on('input propertychange keyup change', function(){ this.rows = this.value.match(/\n/g).length + 1 });
			$('#connect_box').on('input propertychange keyup change', function(){ this.rows = this.value.match(/\n/g).length + 1 });
			$('#society_box').on('input propertychange keyup change', function(){ this.rows = this.value.match(/\n/g).length + 1 });
			
	
// if we are sent a 0 or 1 for the PIN we should display just the base-case without directions headers or 
	
	//console.log(static_flag);
	
	if (index =='1' || static_flag == 'true'){
	
		$('#directions').hide();
		$('#Header_stuff').hide();	
		if (index == '1'){
			$('#reflections').show();	
			$('#reflections').before('<hr>');
			
		}
		var bc_message = "QR"+problem_id+"-PIN-"+index+ " "+title+" - contributed by "+contrib_first+contrib_last+"\xa0 from\xa0"+contrib_university+ref_field+ auth_field;
	
		 $('body').prepend(bc_message).css("fontSize","8px");
	}
	
	
	
		});

		
 


 });
 
