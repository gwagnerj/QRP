
var problem_id = sessionStorage.getItem('problem_id');
var stu_name = sessionStorage.getItem('stu_name');

if (stu_name == null) { stu_name = ""};
var name_length = stu_name.length;
if(name_length<1){
	stu_name = "__________________________";
	var stu_name_back = '';
} else {
	var stu_name_back = stu_name;
}
var dex = sessionStorage.getItem('dex');
var pin = sessionStorage.getItem('pin');
var exam_flag = 1;

var exam_num =0;
exam_num = sessionStorage.getItem('exam_num');
var examactivity_id =0;
examactivity_id = sessionStorage.getItem('examactivity_id');
var exam_code =0;
exam_code = sessionStorage.getItem('exam_code');
console.log ('exam_flag: '+ exam_flag);
var reflect_flag = sessionStorage.getItem('reflect_flag');
var explore_flag = sessionStorage.getItem('explore_flag');
var connect_flag = sessionStorage.getItem('connect_flag');
var society_flag = sessionStorage.getItem('society_flag');
console.log ('society_flag: '+ society_flag);
var choice = sessionStorage.getItem('ref_choice');
var pp1 = sessionStorage.getItem('pp1');
var pp2 = sessionStorage.getItem('pp2');
var pp3 = sessionStorage.getItem('pp3');
var pp4 = sessionStorage.getItem('pp4');
var time_pp1 = sessionStorage.getItem('time_pp1');
var time_pp2 = sessionStorage.getItem('time_pp2');
var time_pp3 = sessionStorage.getItem('time_pp3');
var time_pp4 = sessionStorage.getItem('time_pp4');
var MC_flag = false;

// console.log ('reflect_flag is ',reflect_flag);
// console.log ('explore_flag is ',explore_flag);
// console.log ('connect_flag is ',connect_flag);
// console.log ('society_flag is ',society_flag);

var iid = sessionStorage.getItem('iid');
var assign_num = sessionStorage.getItem('assign_num');
var alias_num = sessionStorage.getItem('alias_num');
var cclass_id = sessionStorage.getItem('cclass_id');
var cclass_name = sessionStorage.getItem('cclass_name');
var title = sessionStorage.getItem('title');
var static_flag = sessionStorage.getItem('static_flag');


var contrib_last = sessionStorage.getItem('contrib_last');
if (contrib_last == null || contrib_last == "null") {contrib_last = " ";}
// console.log('last',contrib_last);

var contrib_first = sessionStorage.getItem('contrib_first');
if (contrib_first == null || contrib_first == "null" ){contrib_first = " ";}


var contrib_university = sessionStorage.getItem('contrib_university');
if (contrib_university == null ||contrib_university == "null" ){contrib_university = " ";}
var nm_author = sessionStorage.getItem('nm_author');
if (nm_author == null || nm_author == "null"){nm_author = " ";}
var specif_ref = sessionStorage.getItem('specif_ref');
console.log ("specif_ref ");
console.log (specif_ref);
if (specif_ref == null || specif_ref == "null"){specif_ref = " ";}

var bc_var = Array(15);
var x = "";
var nvar = new Array(15);
var vari = new Array(15);
var oNvar = new Array(15);
for (i=1;i<15;i++){
	nvar[i] = sessionStorage.getItem('nv_'+i);
	vari[i] = sessionStorage.getItem(nvar[i]);
	x = "bc_"+nvar[i];
	bc_var[i] = sessionStorage.getItem(x);
	nvar[i] ="##"+nvar[i]+",.+?##";
	oNvar[i] = new RegExp(nvar[i],"g");
	
	
}





/* // read in the basecase values for the variables
var bc_var = Array(15);
var x = "";
for (i=1;i<15;i++){
	 x = "bc_"+sessionStorage.getItem('nv_1');
}

var x = "bc_"+sessionStorage.getItem('nv_1');
var bc_var1 = sessionStorage.getItem(x);

x = "bc_"+sessionStorage.getItem('nv_2');
var bc_var2 = sessionStorage.getItem(x);
x = "bc_"+sessionStorage.getItem('nv_3');
var bc_var3 = sessionStorage.getItem(x);
x = "bc_"+sessionStorage.getItem('nv_4');
var bc_var4 = sessionStorage.getItem(x);
x = "bc_"+sessionStorage.getItem('nv_5');
var bc_var5 = sessionStorage.getItem(x);
x = "bc_"+sessionStorage.getItem('nv_6');
var bc_var6 = sessionStorage.getItem(x);
x = "bc_"+sessionStorage.getItem('nv_7');
var bc_var7 = sessionStorage.getItem(x);
x = "bc_"+sessionStorage.getItem('nv_8');
var bc_var8 = sessionStorage.getItem(x);
x = "bc_"+sessionStorage.getItem('nv_9');
var bc_var9 = sessionStorage.getItem(x);
x = "bc_"+sessionStorage.getItem('nv_10');
var bc_var10 = sessionStorage.getItem(x);
x = "bc_"+sessionStorage.getItem('nv_11');
var bc_var11 = sessionStorage.getItem(x);
x = "bc_"+sessionStorage.getItem('nv_12');
var bc_var12 = sessionStorage.getItem(x);
x = "bc_"+sessionStorage.getItem('nv_13');
var bc_var13 = sessionStorage.getItem(x);
x = "bc_"+sessionStorage.getItem('nv_14');
var bc_var14 = sessionStorage.getItem(x);

 */
// This is the Multiple choice stuff called from numericToMC.php for making questions you can print outerHTML

var MC_flag = sessionStorage.getItem('MC_flag');
if (MC_flag != null ){
	var key_1 = sessionStorage.getItem('key_1');
	var key_2 = sessionStorage.getItem('key_2');
	var key_3 = sessionStorage.getItem('key_3');
	var part_a = sessionStorage.getItem('part_a');
	var part_b = sessionStorage.getItem('part_b');
	var part_c = sessionStorage.getItem('part_c');
	var part_d = sessionStorage.getItem('part_d');
	var part_e = sessionStorage.getItem('part_e');
	var part_f = sessionStorage.getItem('part_f');
	var part_g = sessionStorage.getItem('part_g');
	var part_h = sessionStorage.getItem('part_h');
	var part_i = sessionStorage.getItem('part_i');
	var part_j = sessionStorage.getItem('part_j');
	var show_key = sessionStorage.getItem('show_key');
}



// setting up the beginning markup with a REgular expression the g performs a global match - find all matches without stopping
// this should find all of the strings like v== and u== and the sEndMU finds the strings like ==v and ==U

var sBeginMU = "[k-zK-Z]\=\=";
var oBeginMU = new RegExp(sBeginMU,"g");

var sEndMU = "\=\=[k-zK-Z]";
var oEndMU = new RegExp(sEndMU,"g");


$(document).ready(function(){
	
	if(reflect_flag==1 || explore_flag == 1 || connect_flag == 1 || society_flag == 1) {
	var reflections = "Required Reflections: "+(reflect_flag==1 ? ' reflect ' : "")+(explore_flag==1 ? ' explore ' : "")
			+ (connect_flag==1 ? ' connect ' :"")+(society_flag==1 ? ' society ' :"");
		
	} else if (choice == 0 || choice == "null" || choice == "NULL" ) {
		var reflections ="";
		
		
	} else {
		
		var reflections = "Reflections: Pick Any "+ choice ;
	}
	
    var assignorexam = 'Exam: ';
    var showhide = 'show/hide: ';
     var showhide = '';
	// var Head_txt1 = $("<p></p>").text("Name: " + stu_name + "\xa0\xa0"+"Course: "+cclass_name+"\xa0\xa0"+assignorexam+assign_num+"\xa0\xa0"+"Problem: "+alias_num+"\xa0\xa0"+"PIN: " + pin +"\xa0\xa0 - \xa0\xa0 "+ reflections);
	 var Head_txt1 = $("<p></p>").text("Name: " + stu_name + "\xa0\xa0"+"Course: "+cclass_name+"\xa0\xa0"+assignorexam+assign_num+"\xa0\xa0"+"Problem: "+alias_num+"\xa0\xa0"+"PIN: " + pin );
	  var auth_field = (nm_author.length > 1 ? " by "+nm_author : "");
	  var ref_field = (specif_ref.length > 1 ? " similar to\xa0"+specif_ref : "");
	 var pp_txt = (pp1==2 ? ' Preliminary Estimates completed at '+time_pp1 :"")+(pp2==2 ? ' Planning Questions completed at '+time_pp2 :"")
			+ (pp3==2 ? ' Preliminary MC completed at '+time_pp3 :"")+(pp4==2 ? ' Preliminary Supplemental completed at '+time_pp4 :"");
	
  
	
	 $('p:first').before(Head_txt1,'<hr>');
	 
	
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
				$('p:first').before('<button id="backbut"> back </button> '+showhide+' <button id="directionsbutton">directions</button> <button id="pblmbutton">pblm statement</button>  <button id="basecasebutton">base-case</button> <button id="reflectionbutton">Reflections</button>') ;
				$('p:first').before('  <button id="refl" style = "height:17px">Reflect</button> <button id="expl" style = "height:17px">Explore</button>  <button id="conn" style = "height:17px">Connect</button> <button id="soci" style = "height:17px">Society</button>') ;
				
				$('#refl').hide();
				$('#expl').hide();
				$('#conn').hide();
				$('#soci').hide();
				
				// if(reflect_flag==1){$('#refl').show();}
				// if(explore_flag==1){$('#expl').show();}
			

          
          /*   
            var qrcode = new QRCode(document.getElementById("qrcode"), {
                  text: "https://QRProblems.org/QRP/QRExamCheck.php",
                    width: 100,
                    height: 100,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });
                 */
       
               $('#basecasebutton').hide(); 
               $('#reflectionbutton').remove(); 
               $('#pblmbutton').hide(); 
                  $('#directionsbutton').hide(); 
                console.log('removing buttons');
        	
				
				
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

	
	
	
	
	
	// Search thru all of the document looking for the markups for the questions and put thm in a div 
			 $( "p" ).each(function( index ) {
			   var current_content =  $(this).text();
			   // put in a div element at the start of the markup
			  if( current_content.indexOf("p==a==p") !=-1) {
				  $(this).closest('p').before('<div id="boxa-start">');
			  }
			 // put the end of the div before the next one or after the current one if the next one does not exist 
				  if( current_content.indexOf("p==b==p") !=-1) {
				  
				 $(this).closest('p').before('<div id="boxa-end">');
			  } else if( current_content.indexOf("p==a==p") !=-1) {
				  $(this).closest('p').after('<div id="boxa-end">');
			  }
			});
			// put all the content between the tags in a div

			$("#boxa-start").nextUntil("#boxa-end").wrapAll("<div id='parta'></div>");

// Search thru all of the document looking for the markups for the questions and put thm in a div 
			 $( "p" ).each(function( index ) {
			   var current_content =  $(this).text();
			  if( current_content.indexOf("p==b==p") !=-1) {
				  $(this).closest('p').before('<div id="boxb-start">');
			  }
				  if( current_content.indexOf("p==c==p") !=-1) {
				 $(this).closest('p').before('<div id="boxb-end">');
			  } else if( current_content.indexOf("p==b==p") !=-1) {
				  $(this).closest('p').after('<div id="boxb-end">');
			  }
			});
			$("#boxb-start").nextUntil("#boxb-end").wrapAll("<div id='partb'></div>");


// Search thru all of the document looking for the markups for the questions and put thm in a div 
			 $( "p" ).each(function( index ) {
			   var current_content =  $(this).text();
			  if( current_content.indexOf("p==c==p") !=-1) {
				  $(this).closest('p').before('<div id="boxc-start">');
			  }
				  if( current_content.indexOf("p==d==p") !=-1) {
				 $(this).closest('p').before('<div id="boxc-end">');
			  } else if( current_content.indexOf("p==c==p") !=-1) {
				  $(this).closest('p').after('<div id="boxc-end">');
			  }
			});
			$("#boxc-start").nextUntil("#boxc-end").wrapAll("<div id='partc'></div>");

// Search thru all of the document looking for the markups for the questions and put thm in a div 
			 $( "p" ).each(function( index ) {
			   var current_content =  $(this).text();
			  if( current_content.indexOf("p==d==p") !=-1) {
				  $(this).closest('p').before('<div id="boxd-start">');
			  }
				  if( current_content.indexOf("p==e==p") !=-1) {
				 $(this).closest('p').before('<div id="boxd-end">');
			  } else if( current_content.indexOf("p==d==p") !=-1) {
				  $(this).closest('p').after('<div id="boxd-end">');
			  }
			});
			$("#boxd-start").nextUntil("#boxd-end").wrapAll("<div id='partd'></div>");

// Search thru all of the document looking for the markups for the questions and put thm in a div 
			 $( "p" ).each(function( index ) {
			   var current_content =  $(this).text();
			  if( current_content.indexOf("p==e==p") !=-1) {
				  $(this).closest('p').before('<div id="boxe-start">');
			  }
				  if( current_content.indexOf("p==f==p") !=-1) {
				 $(this).closest('p').before('<div id="boxe-end">');
			  } else if( current_content.indexOf("p==e==p") !=-1) {
				  $(this).closest('p').after('<div id="boxe-end">');
			  }
			});
			$("#boxe-start").nextUntil("#boxe-end").wrapAll("<div id='parte'></div>");

// Search thru all of the document looking for the markups for the questions and put thm in a div 
			 $( "p" ).each(function( index ) {
			   var current_content =  $(this).text();
			  if( current_content.indexOf("p==f==p") !=-1) {
				  $(this).closest('p').before('<div id="boxf-start">');
			  }
				  if( current_content.indexOf("p==g==p") !=-1) {
				 $(this).closest('p').before('<div id="boxf-end">');
			  } else if( current_content.indexOf("p==f==p") !=-1) {
				  $(this).closest('p').after('<div id="boxf-end">');
			  }
			});
			$("#boxf-start").nextUntil("#boxf-end").wrapAll("<div id='partf'></div>");

// Search thru all of the document looking for the markups for the questions and put thm in a div 
			 $( "p" ).each(function( index ) {
			   var current_content =  $(this).text();
			  if( current_content.indexOf("p==g==p") !=-1) {
				  $(this).closest('p').before('<div id="boxg-start">');
			  }
				  if( current_content.indexOf("p==h==p") !=-1) {
				 $(this).closest('p').before('<div id="boxg-end">');
			  } else if( current_content.indexOf("p==g==p") !=-1) {
				  $(this).closest('p').after('<div id="boxg-end">');
			  }
			});
			$("#boxg-start").nextUntil("#boxg-end").wrapAll("<div id='partg'></div>");

// Search thru all of the document looking for the markups for the questions and put thm in a div 
			 $( "p" ).each(function( index ) {
			   var current_content =  $(this).text();
			  if( current_content.indexOf("p==h==p") !=-1) {
				  $(this).closest('p').before('<div id="boxh-start">');
			  }
				  if( current_content.indexOf("p==i==p") !=-1) {
				 $(this).closest('p').before('<div id="boxh-end">');
			  } else if( current_content.indexOf("p==h==p") !=-1) {
				  $(this).closest('p').after('<div id="boxh-end">');
			  }
			});
			$("#boxh-start").nextUntil("#boxh-end").wrapAll("<div id='parth'></div>");

// Search thru all of the document looking for the markups for the questions and put thm in a div 
			 $( "p" ).each(function( index ) {
			   var current_content =  $(this).text();
			  if( current_content.indexOf("p==i==p") !=-1) {
				  $(this).closest('p').before('<div id="boxi-start">');
			  }
				  if( current_content.indexOf("p==j==p") !=-1) {
				 $(this).closest('p').before('<div id="boxi-end">');
			  } else if( current_content.indexOf("p==i==p") !=-1) {
				  $(this).closest('p').after('<div id="boxi-end">');
			  }
			});
			$("#boxi-start").nextUntil("#boxi-end").wrapAll("<div id='parti'></div>");

// Search thru all of the document looking for the markups for the questions and put thm in a div 
			 $( "p" ).each(function( index ) {
			   var current_content =  $(this).text();
			  if( current_content.indexOf("p==j==p") !=-1) {
					$(this).closest('p').before('<div id="boxj-start">');
					$(this).closest('p').after('<div id="boxj-end">');
			 }
			  
			});
			$("#boxj-start").nextUntil("#boxj-end").wrapAll("<div id='partj'></div>");


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






			  // Cloning the problem statement to get the basecase

			  var problem_st = document.getElementById('problem');
			var clone = problem_st.cloneNode(true);
			clone.id = "basecase";

			document.body.appendChild(clone);
				
			   $('#basecase').prepend('<h2>Base-Case</h2>');
				$('#reflections').prepend('<h2>Reflections</h2>');

			// build arrays for the values and names
			
			
	


			// Search thru all of the paragraphs in the problem statement or reflections looking for the image markups 
			var numPara_tot = document.getElementsByTagName('p').length
			var numPara_basecase = document.getElementById('basecase').getElementsByTagName('p').length
			var numPara = numPara_tot - numPara_basecase;

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
							
							for (j=1;j<15;j++){
								if(cap_var_title == vari[j]){
									found = true
									document.querySelectorAll('p')[i].hidden = true;
								}
							}	
								if (found){
									
									found = false;
								} else	{
									//set the caption to hidden and the previous paragraph to hidden
									
									document.querySelectorAll('p')[i].hidden = true;
									document.querySelectorAll('p')[i-1].hidden = true;
									
								}
				}
				
			}	

			// replace the url from whatever is there to qrproblems.org/QRP/QRChecker.php?problem_id=problem_id&=dex
//!!~~~~~~!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			//var newHref = 'https://qrproblems.org/QRP/QRChecker.php'+'?problem_id='+problem_id+'&pin='+pin+'&iid='+iid;                             
			var newHref ='';
        console.log('new day');

           //     newHref = '../QRP/QRExamCheck.php'+'?exam_num='+exam_num+'&cclass_id='+cclass_id+'&alias_num='+alias_num+'&pin='+pin+'&iid='+iid+'&examactivity_id='+examactivity_id+'&problem_id='+problem_id+'&dex='+dex;

          
			var oldHref = "[href="+$('#directions').find('a:first').attr('href')+"]";
			//var oldHref = $('a').attr('href');


			//selects the first anchor tag in the directions div and replaces it with the particular url
		
            	
            
             
               //  $('#directions a').text('QRExam')
           //      $('#directions').html('<p>QRExam <a href = '+oldHref+'>Link Exam Checker</a></p>')
            //        $('#directions a').prop('title', 'Exam Checker');
               // console.log ('getting it right');
             
            
			// $("a".oldHref).prop('href', newHref);

         //   $('#directions a').prop('href', newHref);
		//	$('#directions a').prop('target', '_blank');
            $('#directions').hide();

			
 /* 
			var value_array_bc=[];

			for (i=0;i<14;i++){
				var j = i+1;
				var nm_elem = 'bc_var'+j
				 
				 value_array_bc = value_array_bc.concat(eval(nm_elem));
			 
			}
  */
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

 // replace the variables in the problem statement and reflections---------------------------------------------------------------------------------------------------

		for (i = 1;i<15;i++){
			if(vari[i] !=null){
				 $('#problem').html(function(){
				   return $(this).html().replace(oNvar[i],'<span class ="var'+i+'">'+vari[i]+'</span>' ); });
				
				 $('#reflections').html(function(){
				   return $(this).html().replace(oNvar[i],'<span class ="var'+i+'">'+vari[i]+'</span>' ); });
				   
				 $('#basecase').html(function(){
					return $(this).html().replace(oNvar[i],'<span class ="bc_var'+i+'">'+bc_var[i]+'</span>' ); });
				
				}
			}
			   
	
			 
			 
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
				
	
			// color the back botton a little different
			$("#backbut").css('background-color','lightyellow')
			
			// go back to the input page for a different problem
			
            
            $("#backbut").click(function(){
					// e.preventDefault();
					// console.log("hello1");
				
                    
                    window.location.replace('../QRP/QRExam.php'+'?examactivity_id='+examactivity_id); // axam_num and examactivity
               	
				 });
			

			   // toggle the content between show and hide on click of the button
				$('#directionsbutton').click(function(e){
					 e.preventDefault();
					 // console.log("hello2");
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
								
								 $('#box8-end').addClass('nex-text-div4')
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
	
	// console.log('dex is ', dex);
	//  console.log('static_flag is ', static_flag);
			if (dex =='1' || static_flag == 'true'){
			//	 console.log('index is ', index);
				$('#directions').hide();
				 $('#Header_stuff').hide();	
				$('#reflections').show();	
                
                if (dex == '1'){
					
					// $('#Header_stuff').show();	
					
					$('.nex-text-div').hide();
					$('.nex-text-div2').hide();
					$('.nex-text-div3').hide();
					$('.nex-text-div4').hide();
					
					$('#reflections').before('<hr>'); 
					
					
				}
				var bc_message = "QR"+problem_id+"-PIN-"+pin+ " "+title+" - contributed by "+contrib_first+"\xa0"+contrib_last+" from\xa0"+contrib_university+ref_field+ auth_field;
			
				 $('body').prepend(bc_message).css("fontSize","8px");
			}
	// put in the value of the multiple choice 
		
	//	 console.log (MC_flag);
		if (MC_flag =="true"){
			
			$("#parta").append(part_a);
			$("#partb").append(part_b);
			$("#partc").append(part_c);
			$("#partd").append(part_d);
			$("#parte").append(part_e);
			$("#partf").append(part_f);
			$("#partg").append(part_g);
			$("#parth").append(part_h);
			$("#parti").append(part_i);
			$("#partj").append(part_j);
			
			
			if (part_a==""){$("#parta").hide();}
			if (part_b==""){$("#partb").hide();}
			if (part_c==""){$("#partc").hide();}
			
			if (part_d==""){$("#partd").hide();}
			if (part_e==""){$("#parte").hide();}
			if (part_f==""){$("#partf").hide();}
			if (part_g==""){$("#partg").hide();}
			if (part_h==""){$("#parth").hide();}
			if (part_i==""){$("#parti").hide();}
			if (part_j==""){$("#partj").hide();}
		
		//	console.log (show_key);
			if(show_key == "1"){
				if(key_2 == 0){key_2 = "";}
				if(key_3 == 0){key_3 = "";}
				
				$("#problem").append("<p><font size = 2> Keys to M/C Questions: "+ key_1+ ", "+ key_2 +", "+ key_3+"</font></p>");
			}
		}
	
		});

// put in the QRcode directions for the plannning and reflections stages for the game problem
    var game_id = sessionStorage.getItem('game_id');
    console.log ("game_id "+game_id);
   
    if ( game_id!=null) {
     var qrcode = new QRCode(document.getElementById("qrcode"), {
          
        
          text: "https://QRProblems.org/QRP/getGamePblmNum.php?game_id="+game_id,
         
            width: 100,
            height: 100,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
        
        
        
   
    // put the directions for the planning
 /*     
      $("#exp_date").replaceWith("<p><font size = 3> Planning Stage - After reading the statement and making any preliminary diagrams try to answer the following: </font></p>");
      //  $("#exp_date").append("<font size = 2><ol> <li>What principles and equations will I need?</li><li>Do I need information not in the problem statment?</li><li>Are there assumptions/basis/that would make the solution easier?</li><li>Are there tables or diagrams I can create to keep track of the information?</li><li>Which part will be the most difficult?</li></ol></font>");
       

       $(":button").remove();
        $("#reflections").hide();
          $("#pblmbutton").remove();
           $("#reflectionbutton").remove();
          $("#backbut").css('background-color','blue')
          
           */
   }
 

   /*  
  
    $("#exp_date").append("<p><font size = 3> Planning Stage - After reading the statement and making any preliminary diagrams try to answer the following:
</p> <p>  What other tables / diagrams will be useful </p> <p> What Principles or equations will I need   </font></p>");

    
    	
     */    
         
      
    
 });
 
