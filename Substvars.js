
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
var exam_flag = 0;
exam_flag = sessionStorage.getItem('exam_flag');
var exam_num =0;
exam_num = sessionStorage.getItem('exam_num');
var examactivity_id =0;
examactivity_id = sessionStorage.getItem('examactivity_id');
var exam_code =0;
exam_code = sessionStorage.getItem('exam_code');
console.log ('exam_flag: '+ exam_flag);


// preproblem stuff also probably handle in server
/* 
var pp1 = sessionStorage.getItem('pp1');
var pp2 = sessionStorage.getItem('pp2');
var pp3 = sessionStorage.getItem('pp3');
var pp4 = sessionStorage.getItem('pp4');
var time_pp1 = sessionStorage.getItem('time_pp1');
var time_pp2 = sessionStorage.getItem('time_pp2');
var time_pp3 = sessionStorage.getItem('time_pp3');
var time_pp4 = sessionStorage.getItem('time_pp4');
var MC_flag = false;

 */
// will probably handle this is the server also
/* var iid = sessionStorage.getItem('iid');
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
*/



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



$(document).ready(function(){
	
	
	 
		$(function(){
			
			// put the button in the document	
				$('p:first').before('<button id="backbut"> back </button> '+showhide+' <button id="directionsbutton">directions</button> <button id="pblmbutton">pblm statement</button>  <button id="basecasebutton">base-case</button> <button id="reflectionbutton">Reflections</button>') ;
				$('p:first').before('  <button id="refl" style = "height:17px">Reflect</button> <button id="expl" style = "height:17px">Explore</button>  <button id="conn" style = "height:17px">Connect</button> <button id="soci" style = "height:17px">Society</button>') ;
				
				$('#refl').hide();
				$('#expl').hide();
				$('#conn').hide();
				$('#soci').hide();
/* 
			  // Cloning the problem statement to get the basecase

			  var problem_st = document.getElementById('problem');
			var clone = problem_st.cloneNode(true);
			clone.id = "basecase";

			document.body.appendChild(clone);
				
			   $('#basecase').prepend('<h2>Base-Case</h2>');
				$('#reflections').prepend('<h2>Reflections</h2>');
 */
			// build arrays for the values and names
			
			
	

			// replace the url from whatever is there to qrproblems.org/QRP/QRChecker.php?problem_id=problem_id&=dex
//!!~~~~~~!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			//var newHref = 'https://qrproblems.org/QRP/QRChecker.php'+'?problem_id='+problem_id+'&pin='+pin+'&iid='+iid;                             
			var newHref ='';
           if (exam_flag == 1){

                newHref = '../QRP/QRExamCheck.php'+'?exam_num='+exam_num+'&cclass_id='+cclass_id+'&alias_num='+alias_num+'&pin='+pin+'&iid='+iid+'&examactivity_id='+examactivity_id+'&problem_id='+problem_id+'&dex='+dex;

           } else {               
                newHref = '../QRP/QRChecker.php'+'?assign_num='+assign_num+'&cclass_id='+cclass_id+'&alias_num='+alias_num+'&pin='+pin+'&iid='+iid;
           }
			var oldHref = "[href="+$('#directions').find('a:first').attr('href')+"]";
			//var oldHref = $('a').attr('href');


			//selects the first anchor tag in the directions div and replaces it with the particular url
		
          
			// $("a".oldHref).prop('href', newHref);

            $('#directions a').prop('href', newHref);
			$('#directions a').prop('target', '_blank');


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
				if(exam_flag==1){
                    
                    window.location.replace('../QRP/QRExam.php'+'?examactivity_id='+examactivity_id); // axam_num and examactivity
                } else {
					window.location.replace('../QRP/QRhomework.php'+'?assign_num='+assign_num+'&cclass_id='+cclass_id+'&alias_num='+alias_num+'&pin='+pin+'&iid='+iid+'&stu_name='+stu_name_back);
				}	
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
					$("#explore").toggle();
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
					$("#connect").toggle();
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
					$("#society").toggle();
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
	
			if (dex =='1' || static_flag == 'true'){
			//	 console.log('index is ', index);
				$('#directions').hide();
				
				$('#reflections').show();	
                
                if (dex == '1'){
					
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
    
 });
 
